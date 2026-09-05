/**
 * D53四端布局、窄屏抽屉与已选条件交互审计。
 *
 * 运行前仅在Local临时关闭Coming Soon，运行后必须恢复。
 */

import assert from 'node:assert/strict';
import { mkdir } from 'node:fs/promises';
import { resolve } from 'node:path';
import { chromium } from 'playwright';

const baseUrl = process.env.DENTALL_BASE_URL || 'http://dentall.local';
const chromePath = process.env.DENTALL_CHROME_PATH || 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const outputDir = resolve('.codex-tmp');
const viewports = [
	{ width: 390, height: 900, columns: 2, drawer: true },
	{ width: 768, height: 900, columns: 2, drawer: true },
	{ width: 1024, height: 900, columns: 3, drawer: true },
	{ width: 1440, height: 900, columns: 4, drawer: false },
];

await mkdir(outputDir, { recursive: true });

const browser = await chromium.launch({ executablePath: chromePath, headless: true });
const page = await browser.newPage();
const browserErrors = [];

page.on('pageerror', (error) => browserErrors.push(`pageerror: ${error.message}`));
page.on('console', (message) => {
	if (message.type() === 'error') {
		browserErrors.push(`console: ${message.text()}`);
	}
});

async function open(url, drawer) {
	const response = await page.goto(url, { waitUntil: 'domcontentloaded' });
	assert(response && response.status() < 400, `请求失败：${url}`);
	await page.waitForFunction(
		(expectedDrawer) => {
			const filters = document.querySelector('.dentall-catalog-filters');
			const toggle = document.querySelector('[data-dentall-filter-toggle]');

			return filters && toggle
				&& Boolean(filters.closest('dialog')) === expectedDrawer
				&& toggle.hidden !== expectedDrawer;
		},
		drawer
	);
}

async function inspectFull(viewport) {
	await page.setViewportSize({ width: viewport.width, height: viewport.height });
	await open(`${baseUrl}/shop/`, viewport.drawer);

	const metrics = await page.evaluate(() => {
		const cards = Array.from(document.querySelectorAll('ul.products > li.product')).slice(0, 8);
		const rects = cards.map((card) => card.getBoundingClientRect());
		const firstTop = rects[0]?.top;
		const brandLinks = Array.from(document.querySelectorAll('.widget_brand_nav .wc-layered-nav-term a'));

		return {
			clientWidth: document.documentElement.clientWidth,
			scrollWidth: document.documentElement.scrollWidth,
			columns: rects.filter((rect) => Math.abs(rect.top - firstTop) < 2).length,
			brands: brandLinks.length,
			lastBrand: brandLinks.at(-1)?.textContent?.trim() || '',
			activeSummaries: document.querySelectorAll('.dentall-catalog-active-filters').length,
			filtersInsideDialog: Boolean(document.querySelector('dialog .dentall-catalog-filters')),
			filterToggleHidden: document.querySelector('[data-dentall-filter-toggle]')?.hidden,
		};
	});

	assert(metrics.scrollWidth <= metrics.clientWidth + 1, `${viewport.width}px出现横向溢出`);
	assert.equal(metrics.columns, viewport.columns, `${viewport.width}px商品列数错误`);
	assert.equal(metrics.brands, 30, `${viewport.width}px品牌未完整显示`);
	assert.equal(metrics.lastBrand, 'TEST D53 Brand 30 – Extra Long & "Quoted" Name');
	assert.equal(metrics.activeSummaries, 0, '无筛选时不应输出已选条件区');
	assert.equal(metrics.filtersInsideDialog, viewport.drawer);
	assert.equal(metrics.filterToggleHidden, !viewport.drawer);

	await page.screenshot({ path: resolve(outputDir, `day53-full-${viewport.width}.png`) });

	return metrics;
}

async function inspectZero(viewport, brandId) {
	await page.setViewportSize({ width: viewport.width, height: viewport.height });
	const query = new URLSearchParams({
		min_price: '100',
		max_price: '120',
		filter_size: 'large-105-mm',
		query_type_size: 'or',
		filter_product_brand: brandId,
	});
	await open(`${baseUrl}/shop/?${query}`, viewport.drawer);

	const metrics = await page.evaluate(() => {
		const active = document.querySelector('.dentall-catalog-active-filters');
		const targets = Array.from(document.querySelectorAll('.dentall-catalog-active-filters a'));

		return {
			clientWidth: document.documentElement.clientWidth,
			scrollWidth: document.documentElement.scrollWidth,
			activeSummaries: document.querySelectorAll('.dentall-catalog-active-filters').length,
			activeItems: document.querySelectorAll('.dentall-catalog-active-filters__list li').length,
			activeVisible: Boolean(active && active.getBoundingClientRect().height > 0),
			clearPath: new URL(document.querySelector('.dentall-catalog-active-filters__clear').href).pathname,
			zeroNotice: document.querySelector('.woocommerce-info')?.textContent?.trim() || '',
			filtersInsideDialog: Boolean(document.querySelector('dialog .dentall-catalog-filters')),
			filterToggleHidden: document.querySelector('[data-dentall-filter-toggle]')?.hidden,
			minimumTargetHeight: Math.min(...targets.map((target) => target.getBoundingClientRect().height)),
		};
	});

	assert(metrics.scrollWidth <= metrics.clientWidth + 1, `${viewport.width}px零结果出现横向溢出`);
	assert.equal(metrics.activeSummaries, 1, '已选条件区必须只有一份');
	assert.equal(metrics.activeItems, 3, '价格、Size、Brand应各有一个已选条件');
	assert(metrics.activeVisible, '窄屏关闭抽屉后已选条件仍需可见');
	assert.equal(metrics.clearPath, '/shop/');
	assert.match(metrics.zeroNotice, /No products were found matching your selection/);
	assert.equal(metrics.filtersInsideDialog, viewport.drawer);
	assert.equal(metrics.filterToggleHidden, !viewport.drawer);
	assert(metrics.minimumTargetHeight >= 44, 'Clear或移除链接高度不足44px');

	await page.screenshot({ path: resolve(outputDir, `day53-zero-${viewport.width}.png`) });

	return metrics;
}

try {
	const fullResults = [];
	const zeroResults = [];
	const redirectResults = [];
	let brandId = '';

	for (const viewport of viewports) {
		const metrics = await inspectFull(viewport);
		fullResults.push({ width: viewport.width, ...metrics });

		if (!brandId) {
			brandId = await page.evaluate(() => {
				const link = Array.from(document.querySelectorAll('.widget_brand_nav a')).find(
					(candidate) => candidate.textContent.trim() === 'TEST D53 Brand 04'
				);

				return link ? new URL(link.href).searchParams.get('filter_product_brand') : '';
			});
			assert(brandId, '无法读取Brand 04 ID');
		}
	}

	for (const viewport of viewports) {
		zeroResults.push({ width: viewport.width, ...await inspectZero(viewport, brandId) });
	}

	for (const width of [1199, 1200]) {
		await page.setViewportSize({ width, height: 900 });
		await open(`${baseUrl}/shop/`, width < 1200);
	}

	await page.setViewportSize({ width: 390, height: 900 });
	await open(`${baseUrl}/shop/`, true);
	const toggle = page.locator('[data-dentall-filter-toggle]');
	assert.equal(await toggle.count(), 1);
	await toggle.click();
	await page.locator('#dentall-catalog-filter-dialog[open]').waitFor({ state: 'visible' });
	const lastBrand = page.getByRole('link', { name: 'TEST D53 Brand 30 – Extra Long & "Quoted" Name', exact: true });
	assert.equal(await lastBrand.count(), 1);
	await lastBrand.scrollIntoViewIfNeeded();
	assert(await lastBrand.isVisible(), '抽屉无法滚动到第30个品牌');

	const close = page.locator('[data-dentall-filter-close]');
	assert.equal(await close.count(), 1);
	await close.click();
	assert.equal(await page.locator('#dentall-catalog-filter-dialog[open]').count(), 0);
	assert(await toggle.evaluate((element) => document.activeElement === element), '关闭后焦点未返回Filter按钮');

	await toggle.click();
	await page.keyboard.press('Escape');
	assert.equal(await page.locator('#dentall-catalog-filter-dialog[open]').count(), 0);
	assert(await toggle.evaluate((element) => document.activeElement === element), 'Escape后焦点未返回Filter按钮');

	const zeroQuery = new URLSearchParams({
		min_price: '100',
		max_price: '120',
		filter_size: 'large-105-mm',
		query_type_size: 'or',
		filter_product_brand: brandId,
	});
	await open(`${baseUrl}/shop/?${zeroQuery}`, true);
	const removeSize = page.getByRole('link', { name: 'Remove Size: Large 105 mm filter.', exact: true });
	assert.equal(await removeSize.count(), 1);
	await removeSize.focus();
	await page.keyboard.press('Tab');
	await page.keyboard.press('Shift+Tab');
	const focusStyle = await removeSize.evaluate((element) => {
		const style = getComputedStyle(element);

		return { outlineStyle: style.outlineStyle, outlineWidth: style.outlineWidth };
	});
	assert.notEqual(focusStyle.outlineStyle, 'none', '移除链接缺少可见键盘焦点');
	assert(parseFloat(focusStyle.outlineWidth) > 0, '移除链接焦点轮廓宽度必须大于0');
	await Promise.all([
		page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
		removeSize.click(),
	]);
	assert(!new URL(page.url()).searchParams.has('filter_size'), 'Size移除链接未删除Size');
	assert(new URL(page.url()).searchParams.has('filter_product_brand'), 'Size移除链接误删Brand');
	await page.goBack({ waitUntil: 'domcontentloaded' });
	assert.equal(await page.locator('.dentall-catalog-active-filters__list li').count(), 3, 'History返回后筛选状态未恢复');
	const clearFilters = page.getByRole('link', { name: 'Clear filters', exact: true });
	assert.equal(await clearFilters.count(), 1);
	await Promise.all([
		page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
		clearFilters.click(),
	]);
	assert.equal(new URL(page.url()).pathname, '/shop/');
	assert.equal(new URL(page.url()).search, '');

	const redirects = [
		{
			source: '/shop/?filter_size=large-105-mm%2Csmall-98-mm',
			target: '/shop/?filter_size=large-105-mm,small-98-mm&query_type_size=or',
		},
		{
			source: '/shop/?filter_size=large-105-mm%2Csmall-98-mm&query_type_size=and',
			target: '/shop/?filter_size=large-105-mm,small-98-mm&query_type_size=or',
		},
		{
			source: '/product-category/test-d12-products/?filter_size=large-105-mm%2Csmall-98-mm',
			target: '/product-category/test-d12-products/?filter_size=large-105-mm,small-98-mm&query_type_size=or',
		},
		{
			source: '/shop/?filter_size=does-not-exist&orderby=price-desc&foo=drop',
			target: '/shop/?orderby=price-desc',
		},
		{
			source: `/shop/?filter_product_brand=${brandId}%2Cinvalid`,
			target: '/shop/',
		},
		{ source: '/shop/?filter_color=red', target: '/shop/' },
	];

	for (const { source, target } of redirects) {
		const response = await page.request.get(`${baseUrl}${source}`, { maxRedirects: 0 });
		const location = response.headers().location;

		assert.equal(response.status(), 302, `${source}未执行302归一化`);
		assert.equal(location, `${baseUrl}${target}`, `${source}归一化目标错误`);
		redirectResults.push({ source, status: response.status(), location });
		await response.dispose();
	}

	const validOrResponse = await page.request.get(
		`${baseUrl}/shop/?filter_size=small-98-mm&query_type_size=or`,
		{ maxRedirects: 0 }
	);
	assert.equal(validOrResponse.status(), 200, '合法OR属性URL不应重定向');
	redirectResults.push({ source: 'valid_or', status: validOrResponse.status(), location: '' });
	await validOrResponse.dispose();

	const hiddenSearchFilters = new URLSearchParams({
		s: 'TEST',
		post_type: 'product',
		min_price: '0',
		max_price: '25',
		filter_size: 'large-105-mm',
		query_type_size: 'or',
		filter_product_brand: brandId,
	});
	const searchResponse = await page.goto(`${baseUrl}/?${hiddenSearchFilters}`, { waitUntil: 'domcontentloaded' });
	assert(searchResponse && searchResponse.status() === 200, '带隐藏筛选参数的商品搜索请求失败');
	const searchFilters = await page.evaluate(() => ({
		resultText: document.querySelector('.woocommerce-result-count')?.textContent?.trim() || '',
		filterUi: document.querySelectorAll('.dentall-catalog-filters, .dentall-catalog-active-filters, [data-dentall-filter-toggle]').length,
	}));
	assert.match(searchFilters.resultText, /30 results/);
	assert.equal(searchFilters.filterUi, 0, '商品搜索不得暴露或应用目录筛选UI');

	await page.setViewportSize({ width: 390, height: 900 });
	await open(`${baseUrl}/shop/?min_price=50&max_price=10`, true);
	await page.locator('#dentall-catalog-filter-dialog[open]').waitFor({ state: 'visible' });
	await page.waitForFunction(() => document.activeElement?.getAttribute('aria-invalid') === 'true');
	const reverseRange = await page.evaluate(() => ({
		errorVisible: document.querySelector('#dentall-price-error')?.getBoundingClientRect().height > 0,
		activeSummaries: document.querySelectorAll('.dentall-catalog-active-filters').length,
		invalidInputs: document.querySelectorAll('input[aria-invalid="true"]').length,
	}));
	assert.deepEqual(reverseRange, { errorVisible: true, activeSummaries: 1, invalidInputs: 2 });
	await page.keyboard.press('Escape');

	assert.deepEqual(browserErrors, [], `浏览器错误：${browserErrors.join(' | ')}`);
	console.log(JSON.stringify({ fullResults, zeroResults, brandId, redirectResults, searchFilters, reverseRange, browserErrors }, null, 2));
} finally {
	await browser.close();
}
