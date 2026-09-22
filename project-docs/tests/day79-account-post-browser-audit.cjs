/**
 * D79已验证账户后置Guest订单与手工关联浏览器审计。
 */

'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const { chromium } = require('playwright');

const baseUrl = process.env.DENTALL_BASE_URL;
const manifestPath = process.env.DENTALL_DAY79_MANIFEST;
const expectedVisible = process.env.DENTALL_DAY79_EXPECT_LATER_VISIBLE === '1';
const chromePath = process.env.DENTALL_CHROME_PATH || 'C:/Program Files/Google/Chrome/Application/chrome.exe';

assert(baseUrl && manifestPath, '缺少D79后置浏览器审计环境变量。');
const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
assert(manifest.passwords.a && manifest.orders.a_later_guest, '私有清单缺少A密码或后置Guest订单。');

let assertions = 0;
function same(actual, expected, message) {
	assertions += 1;
	assert.equal(actual, expected, message);
}

async function goto(page, relative) {
	const response = await page.goto(`${baseUrl}${relative}`, { waitUntil: 'domcontentloaded' });
	assertions += 1;
	assert(response && response.status() < 400, `请求失败：${relative}`);
}

async function login(page, email, password) {
	await goto(page, '/my-account/');
	await page.locator('#username').fill(email);
	await page.locator('#password').fill(password);
	await Promise.all([
		page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
		page.locator('button[name="login"]').click(),
	]);
	same(await page.locator('.woocommerce-MyAccount-navigation').count(), 1, '客户登录失败。');
}

async function logout(page) {
	const href = await page.locator('.woocommerce-MyAccount-navigation-link--customer-logout a').getAttribute('href');
	const response = await page.goto(href, { waitUntil: 'domcontentloaded' });
	assertions += 1;
	assert(response && response.status() < 400, '退出请求失败。');
	same(await page.locator('.woocommerce-form-login').count(), 1, '退出后仍有账户内容。');
}

(async () => {
	const browser = await chromium.launch({ executablePath: chromePath, headless: true });
	try {
		const aContext = await browser.newContext();
		const aPage = await aContext.newPage();
		await login(aPage, manifest.emails.a, manifest.passwords.a);
		await goto(aPage, '/my-account/orders/');
		const laterSelector = `a[href*="view-order/${manifest.orders.a_later_guest}"]`;
		same((await aPage.locator(laterSelector).count()) > 0, expectedVisible, '后置Guest订单可见性错误。');
		same(await aPage.locator('table.my_account_orders tbody tr').count(), expectedVisible ? 4 : 3, 'A订单行数错误。');

		await logout(aPage);
		await login(aPage, manifest.emails.a, manifest.passwords.a);
		await goto(aPage, '/my-account/orders/');
		same((await aPage.locator(laterSelector).count()) > 0, expectedVisible, '重新登录改变了后置Guest订单归属。');

		const bContext = await browser.newContext();
		const bPage = await bContext.newPage();
		await login(bPage, manifest.emails.b, manifest.passwords.b);
		await goto(bPage, '/my-account/orders/');
		same(await bPage.locator(laterSelector).count(), 0, 'B账户看到了A的后置订单。');

		const anonymousContext = await browser.newContext();
		const anonymousPage = await anonymousContext.newPage();
		await goto(anonymousPage, '/my-account/orders/');
		same(await anonymousPage.locator('.woocommerce-form-login').count(), 1, '匿名上下文读取了订单列表。');

		await Promise.all([aContext.close(), bContext.close(), anonymousContext.close()]);
		console.log(JSON.stringify({ status: 'pass', assertions, laterVisible: expectedVisible }));
	} finally {
		await browser.close();
	}
})().catch((error) => {
	console.error(error.stack || error.message);
	process.exitCode = 1;
});
