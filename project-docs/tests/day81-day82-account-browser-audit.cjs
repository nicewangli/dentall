/** D81/D82 隔离Local真实账户DOM、资源和四宽布局审计；凭据及截图只在私有运行目录。 */
'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const { chromium } = require('playwright');

const manifestPath = process.env.DENTALL_DAY81_MANIFEST;
const screenshotDir = process.env.DENTALL_DAY81_SCREENSHOTS;
const resultPath = process.env.DENTALL_DAY81_BROWSER_RESULT;
const chromePath = process.env.DENTALL_CHROME_PATH || 'C:/Program Files/Google/Chrome/Application/chrome.exe';

assert(manifestPath && screenshotDir && resultPath, '缺少私有运行路径环境变量');
const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
const baseUrl = new URL(manifest.url).origin;
assert.equal(baseUrl, 'http://127.0.0.1:18881', '只允许运行在D81/D82隔离站点');
fs.mkdirSync(screenshotDir, { recursive: true });

const results = { status: 'running', assertions: 0, pages: [], pageErrors: [], consoleErrors: [] };
function check(value, message) {
	results.assertions += 1;
	assert.ok(value, message);
}

async function visit(page, relativePath) {
	const response = await page.goto(`${baseUrl}${relativePath}`, { waitUntil: 'domcontentloaded' });
	check(response && response.status() === 200, `${relativePath} 未返回200`);
	return response;
}

async function auditPage(page, name, relativePath, width) {
	await page.setViewportSize({ width, height: 900 });
	const response = await visit(page, relativePath);
	await page.locator('.woocommerce-MyAccount-navigation').waitFor();
	const state = await page.evaluate(() => {
		const nav = document.querySelector('.woocommerce-MyAccount-navigation');
		const content = document.querySelector('.woocommerce-MyAccount-content');
		const addresses = document.querySelector('.woocommerce-Addresses');
		const miniCart = document.querySelector('.site-header-cart .widget_shopping_cart');
		const navRect = nav.getBoundingClientRect();
		const contentRect = content.getBoundingClientRect();
		const ids = [...document.querySelectorAll('[id]')].map((item) => item.id);
		return {
			viewport: innerWidth,
			scrollWidth: document.documentElement.scrollWidth,
			duplicateIds: ids.length - new Set(ids).size,
			navRect: { x: navRect.x, y: navRect.y, right: navRect.right, bottom: navRect.bottom },
			contentRect: { x: contentRect.x, y: contentRect.y },
			addressColumns: addresses ? getComputedStyle(addresses).gridTemplateColumns.split(' ').length : null,
			activeLinks: nav.querySelectorAll('a[aria-current="page"]').length,
			buttons: [...content.querySelectorAll('button[type="submit"]')].map((button) => button.getBoundingClientRect().height),
			accountCss: [...document.styleSheets].filter((sheet) => sheet.href && sheet.href.includes('customer-account.css')).length,
			accountJs: [...document.scripts].filter((script) => script.src && script.src.includes('account-email.js')).length,
			miniCartDisplay: miniCart ? getComputedStyle(miniCart).display : null,
		};
	});
	check(state.scrollWidth <= width + 1, `${name} ${width}px横向溢出`);
	check(state.duplicateIds === 0, `${name} ${width}px有重复ID`);
	check(state.activeLinks === 1, `${name} ${width}px缺少唯一当前导航`);
	check(state.accountCss === 1, `${name} ${width}px账户CSS未单次加载`);
	if (width === 390) check(state.miniCartDisplay === 'none', `${name} 390px Header Mini Cart 意外可见`);
	check(state.buttons.every((height) => height >= 44), `${name} ${width}px保存按钮不足44px`);
	if (width < 1024) {
		check(state.navRect.bottom <= state.contentRect.y + 1, `${name} ${width}px导航未堆叠`);
	} else {
		check(state.navRect.right <= state.contentRect.x + 1, `${name} ${width}px导航未与内容分栏`);
	}
	if (name === 'addresses') check(state.addressColumns === (width < 768 ? 1 : 2), `${name} ${width}px地址列数错误`);
	if (name === 'profile') {
		const email = page.locator('#account_email');
		check(await email.isVisible(), '资料页邮箱字段不可见');
		check(await email.getAttribute('readonly') !== null, '邮箱字段未标只读');
		check((await email.getAttribute('aria-describedby')) === 'dentall-account-email-note', '邮箱提示未与字段关联');
		check((await email.inputValue()) === manifest.other_email, '资料页邮箱与当前客户不符');
		check(state.accountJs === 1, '资料页邮箱脚本未单次加载');
	} else {
		check(state.accountJs === 0, `${name} 不应加载邮箱脚本`);
	}
	const navLink = page.locator('.woocommerce-MyAccount-navigation a').first();
	await navLink.focus();
	const focus = await navLink.evaluate((item) => ({ style: getComputedStyle(item).outlineStyle, width: parseFloat(getComputedStyle(item).outlineWidth) }));
	check(focus.style !== 'none' && focus.width >= 2, `${name} ${width}px键盘焦点不可见`);
	const file = path.join(screenshotDir, `${name}-${width}.png`);
	await page.screenshot({ path: file, fullPage: true });
	results.pages.push({ name, width, status: response.status(), ...state, focus });
}

(async () => {
	const browser = await chromium.launch({ headless: true, executablePath: chromePath });
	try {
		const guest = await browser.newContext();
		const guestPage = await guest.newPage();
		await guestPage.setViewportSize({ width: 390, height: 900 });
		await visit(guestPage, '/my-account/');
		check(await guestPage.locator('.woocommerce-form-login').count() === 1, 'Guest未见登录表单');
		check(await guestPage.locator('.woocommerce-MyAccount-navigation').count() === 0, 'Guest看到了私有导航');
		check(await guestPage.locator('link[href*="customer-account.css"]').count() === 0, 'Guest加载了登录态账户CSS');
		await guestPage.screenshot({ path: path.join(screenshotDir, 'guest-account-390.png'), fullPage: true });
		await visit(guestPage, '/');
		await guestPage.screenshot({ path: path.join(screenshotDir, 'guest-home-390.png'), fullPage: true });
		await guest.close();

		const context = await browser.newContext();
		const page = await context.newPage();
		page.on('pageerror', (error) => results.pageErrors.push(error.message));
		page.on('console', (message) => {
			if (message.type() === 'error') results.consoleErrors.push(message.text());
		});
		await visit(page, '/my-account/');
		await page.locator('#username').fill(manifest.other_login);
		await page.locator('#password').fill(manifest.other_password);
		await page.locator('button[name="login"]').click();
		await page.locator('.woocommerce-MyAccount-navigation').waitFor();

		const pages = [
			['dashboard', '/my-account/'],
			['profile', '/my-account/edit-account/'],
			['addresses', '/my-account/edit-address/'],
			['billing-form', '/my-account/edit-address/billing/'],
			['shipping-form', '/my-account/edit-address/shipping/'],
		];
		for (const [name, route] of pages) {
			for (const width of [390, 768, 1024, 1440]) await auditPage(page, name, route, width);
		}
		check(results.pageErrors.length === 0, `页面错误：${results.pageErrors.join(' | ')}`);
		check(results.consoleErrors.length === 0, `控制台错误：${results.consoleErrors.join(' | ')}`);
		results.status = 'pass';
		await context.close();
	} finally {
		await browser.close();
		fs.writeFileSync(resultPath, JSON.stringify(results, null, 2));
	}
	console.log(JSON.stringify({ status: results.status, assertions: results.assertions, pageCount: results.pages.length, pageErrors: results.pageErrors.length, consoleErrors: results.consoleErrors.length }));
})().catch((error) => {
	results.status = 'fail';
	results.failure = error.message;
	fs.writeFileSync(resultPath, JSON.stringify(results, null, 2));
	console.error(error);
	process.exitCode = 1;
});
