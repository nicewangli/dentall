/**
 * D79登录、注册、邮箱确认、Guest付款边界及四端账户页审计。
 *
 * 私有夹具、邮件捕获和截图均位于Git忽略且ACL收紧的Day79运行目录。
 */

'use strict';

const assert = require('node:assert/strict');
const crypto = require('node:crypto');
const fs = require('node:fs');
const path = require('node:path');
const { chromium } = require('playwright');

const baseUrl = process.env.DENTALL_BASE_URL;
const manifestPath = process.env.DENTALL_DAY79_MANIFEST;
const mailPath = process.env.DENTALL_DAY79_MAIL;
const outputPath = process.env.DENTALL_DAY79_BROWSER_RESULT;
const screenshotDir = process.env.DENTALL_DAY79_SCREENSHOTS;
const chromePath = process.env.DENTALL_CHROME_PATH || 'C:/Program Files/Google/Chrome/Application/chrome.exe';

for (const [name, value] of Object.entries({ baseUrl, manifestPath, mailPath, outputPath, screenshotDir })) {
	assert(value, `缺少环境变量：${name}`);
}

const baseOrigin = new URL(baseUrl).origin;
const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
const result = {
	status: 'running',
	assertions: 0,
	viewports: {},
	consoleErrors: [],
	pageErrors: [],
};

function check(value, message) {
	result.assertions += 1;
	assert.ok(value, message);
}

function same(actual, expected, message) {
	result.assertions += 1;
	assert.equal(actual, expected, message);
}

function readMail() {
	if (!fs.existsSync(mailPath)) return [];
	return fs.readFileSync(mailPath, 'utf8')
		.split(/\r?\n/)
		.filter(Boolean)
		.map((line) => JSON.parse(line));
}

async function waitForMail(recipient, startingCount) {
	for (let attempt = 0; attempt < 80; attempt += 1) {
		const messages = readMail();
		const match = messages.slice(startingCount).find((message) =>
			Array.isArray(message.to) && message.to.some((address) => address.toLowerCase() === recipient.toLowerCase())
		);
		if (match) return match;
		await new Promise((resolve) => setTimeout(resolve, 100));
	}
	throw new Error('未捕获到预期的本地邮件。');
}

function extractUrl(message, requiredPart) {
	const decoded = String(message.message)
		.replaceAll('&amp;', '&')
		.replaceAll('&#038;', '&')
		.replaceAll('=3D', '=');
	const urls = [];
	for (const match of decoded.matchAll(/href=["']([^"']+)["']/gi)) urls.push(match[1]);
	for (const match of decoded.matchAll(/https?:\/\/[^\s<>"']+/gi)) urls.push(match[0]);
	const url = urls.map((item) => item.replace(/[),.;]+$/, '')).find((item) => item.includes(requiredPart));
	assert(url, `邮件中缺少${requiredPart}链接。`);
	return url;
}

function attachErrors(page, label) {
	page.on('pageerror', (error) => result.pageErrors.push(`${label}: ${error.message}`));
	page.on('console', (message) => {
		if (message.type() === 'error') result.consoleErrors.push(`${label}: ${message.text()}`);
	});
}

async function goto(page, relativeOrAbsolute) {
	const url = relativeOrAbsolute.startsWith('http') ? relativeOrAbsolute : `${baseUrl}${relativeOrAbsolute}`;
	const response = await page.goto(url, { waitUntil: 'domcontentloaded' });
	check(response && response.status() < 400, `请求失败：${new URL(url).pathname}`);
	return response;
}

async function submitAndWait(page, locator) {
	await Promise.all([
		page.waitForNavigation({ waitUntil: 'domcontentloaded' }).catch(() => null),
		locator.click(),
	]);
}

async function login(page, identifier, password) {
	await goto(page, '/my-account/');
	await page.locator('#username').fill(identifier);
	await page.locator('#password').fill(password);
	await submitAndWait(page, page.locator('button[name="login"]'));
	check(await page.locator('.woocommerce-MyAccount-navigation').count() === 1, '有效客户登录失败。');
}

async function logout(page) {
	const link = page.locator('.woocommerce-MyAccount-navigation-link--customer-logout a');
	check(await link.count() === 1, '缺少WooCommerce退出链接。');
	const href = await link.getAttribute('href');
	await goto(page, href);
	check(await page.locator('.woocommerce-form-login').count() === 1, '退出后仍显示私有账户内容。');
}

async function failedLogin(browser, identifier) {
	const context = await browser.newContext();
	const page = await context.newPage();
	attachErrors(page, 'failed-login');
	await goto(page, '/my-account/');
	await page.locator('#username').fill(identifier);
	await page.locator('#password').fill('D79 definitely incorrect password');
	await submitAndWait(page, page.locator('button[name="login"]'));
	const notice = (await page.locator('.woocommerce-error').innerText()).trim();
	const state = { notice, pathname: new URL(page.url()).pathname, loggedIn: await page.locator('.woocommerce-MyAccount-navigation').count() };
	await context.close();
	return state;
}

async function verifyAnonymousLayout(browser) {
	for (const viewport of [390, 768, 1024, 1440]) {
		const context = await browser.newContext({ viewport: { width: viewport, height: 900 } });
		const page = await context.newPage();
		attachErrors(page, `viewport-${viewport}`);
		await goto(page, '/my-account/');
		const metrics = await page.evaluate(() => {
			const grid = document.querySelector('#customer_login');
			const ids = [...document.querySelectorAll('[id]')].map((element) => element.id);
			const duplicates = ids.filter((id, index) => ids.indexOf(id) !== index);
			const controls = ['username', 'password', 'reg_email'];
			return {
				overflow: document.documentElement.scrollWidth - document.documentElement.clientWidth,
				columns: getComputedStyle(grid).gridTemplateColumns.split(' ').length,
				duplicates: [...new Set(duplicates)],
				labels: controls.every((id) => document.querySelector(`label[for="${id}"]`)),
				buttonHeights: [...grid.querySelectorAll('button[type="submit"], button[name="login"], button[name="register"]')]
					.map((button) => button.getBoundingClientRect().height),
				stylesheet: [...document.styleSheets].some((sheet) => sheet.href && sheet.href.includes('account-auth.css')),
			};
		});
		result.viewports[viewport] = metrics;
		check(metrics.overflow <= 1, `${viewport}px账户页出现横向溢出。`);
		same(metrics.columns, viewport < 768 ? 1 : 2, `${viewport}px认证卡片列数错误。`);
		same(metrics.duplicates.length, 0, `${viewport}px存在重复ID。`);
		check(metrics.labels, `${viewport}px表单标签未正确关联。`);
		check(metrics.buttonHeights.every((height) => height >= 44), `${viewport}px按钮触控高度不足44px。`);
		check(metrics.stylesheet, `${viewport}px未加载条件账户样式。`);
		await page.screenshot({ path: path.join(screenshotDir, `account-${viewport}.png`), fullPage: true });
		await context.close();
	}
}

async function main() {
	fs.mkdirSync(screenshotDir, { recursive: true });
	const browser = await chromium.launch({ executablePath: chromePath, headless: true });

	try {
		await verifyAnonymousLayout(browser);

		const wrongExisting = await failedLogin(browser, manifest.emails.b);
		const unknown = await failedLogin(browser, `${manifest.marker}-missing@example.test`);
		same(wrongExisting.notice, unknown.notice, '存在账号与不存在账号的登录错误不同。');
		same(wrongExisting.notice, 'The login details are incorrect. Check them and try again.', '登录通用错误文案错误。');
		same(wrongExisting.pathname, unknown.pathname, '登录错误后的URL行为不同。');
		same(wrongExisting.loggedIn, 0, '错误密码产生了登录态。');

		const invalidNonceContext = await browser.newContext();
		const invalidNoncePage = await invalidNonceContext.newPage();
		await goto(invalidNoncePage, '/my-account/');
		await invalidNoncePage.locator('#username').fill(manifest.emails.b);
		await invalidNoncePage.locator('#password').fill(manifest.passwords.b);
		await invalidNoncePage.locator('input[name="woocommerce-login-nonce"]').evaluate((input) => { input.value = 'invalid'; });
		await submitAndWait(invalidNoncePage, invalidNoncePage.locator('button[name="login"]'));
		same(await invalidNoncePage.locator('.woocommerce-MyAccount-navigation').count(), 0, '无效登录Nonce仍创建认证会话。');
		await invalidNonceContext.close();

		const bContext = await browser.newContext();
		const bPage = await bContext.newPage();
		attachErrors(bPage, 'customer-b');
		await login(bPage, manifest.emails.b, manifest.passwords.b);
		await goto(bPage, '/my-account/orders/');
		const bOrders = await bPage.locator('table.my_account_orders tbody tr').count();
		same(bOrders, 2, 'B账户订单列表没有保持自身两张订单。');
		await goto(bPage, '/wp-admin/');
		check(!new URL(bPage.url()).pathname.startsWith('/wp-admin'), 'Customer进入了WordPress后台。');

		const freshPay = new URL(`/checkout/order-pay/${manifest.orders.a_physical_new}/`, baseUrl);
		freshPay.searchParams.set('pay_for_order', 'true');
		freshPay.searchParams.set('key', manifest.order_keys.a_physical_new);
		await goto(bPage, freshPay.href);
		check((await bPage.locator('form#order_review').count()) === 1, '宽限期内持有Guest付款链接的登录用户未进入付款页。');
		check((await bPage.locator('.woocommerce-error').innerText()).includes('guest order'), 'Guest付款的错账号警告缺失。');

		const redirectContext = await browser.newContext();
		const redirectPage = await redirectContext.newPage();
		await goto(redirectPage, '/my-account/');
		await redirectPage.locator('#username').fill(manifest.emails.b);
		await redirectPage.locator('#password').fill(manifest.passwords.b);
		await redirectPage.locator('form.woocommerce-form-login').evaluate((form) => {
			const input = document.createElement('input');
			input.type = 'hidden';
			input.name = 'redirect';
			input.value = 'https://evil.example/phish';
			form.append(input);
		});
		await submitAndWait(redirectPage, redirectPage.locator('button[name="login"]'));
		same(new URL(redirectPage.url()).origin, baseOrigin, '登录允许跳转到外部站点。');
		await redirectContext.close();

		const payContext = await browser.newContext();
		const payPage = await payContext.newPage();
		attachErrors(payPage, 'guest-pay');
		const oldPay = new URL(`/checkout/order-pay/${manifest.orders.a_physical_old}/`, baseUrl);
		oldPay.searchParams.set('pay_for_order', 'true');
		oldPay.searchParams.set('key', manifest.order_keys.a_physical_old);
		await goto(payPage, oldPay.href);
		same(await payPage.locator('form.woocommerce-verify-email').count(), 1, '超过宽限期的Guest订单没有要求Billing邮箱。');
		await payPage.locator('#email').fill('wrong@example.test');
		await submitAndWait(payPage, payPage.locator('button[name="verify"]'));
		same(await payPage.locator('form.woocommerce-verify-email').count(), 1, '错误Billing邮箱绕过了Guest订单校验。');
		await payPage.locator('#email').fill(manifest.emails.a);
		await submitAndWait(payPage, payPage.locator('button[name="verify"]'));
		same(await payPage.locator('form#order_review').count(), 1, '正确Billing邮箱未进入Guest付款页。');
		await payContext.close();

		const virtualContext = await browser.newContext();
		const virtualPage = await virtualContext.newPage();
		attachErrors(virtualPage, 'virtual-checkout');
		await goto(virtualPage, `/?add-to-cart=${manifest.products.virtual}`);
		await goto(virtualPage, '/checkout/');
		same(new URL(virtualPage.url()).pathname.replace(/\/$/, ''), '/checkout', '纯虚拟Cart被人工报价守卫挡回。');
		same(await virtualPage.locator('.woocommerce-form-login').count(), 0, '纯虚拟Guest Checkout被强制登录。');
		await virtualContext.close();

		const physicalContext = await browser.newContext();
		const physicalPage = await physicalContext.newPage();
		await goto(physicalPage, `/?add-to-cart=${manifest.products.physical}`);
		await goto(physicalPage, '/checkout/');
		same(new URL(physicalPage.url()).pathname.replace(/\/$/, ''), '/cart', '实体Cart可绕过报价流程直达Checkout。');
		await physicalContext.close();

		const cContext = await browser.newContext();
		const cPage = await cContext.newPage();
		attachErrors(cPage, 'customer-c');
		await login(cPage, manifest.emails.c, manifest.passwords.c);
		await goto(cPage, '/my-account/orders/');
		const sendLink = cPage.locator('a[href*="wc_send_verification"]');
		same(await sendLink.count(), 1, '未验证普通密码账户缺少确认邮箱入口。');
		const sendUrl = await sendLink.getAttribute('href');
		const beforeC = readMail().length;
		await goto(cPage, sendUrl);
		const cMail = await waitForMail(manifest.emails.c, beforeC);
		const afterFirstSend = readMail().length;
		await goto(cPage, sendUrl);
		same(readMail().length, afterFirstSend, '60秒内重复请求仍发送确认邮件。');
		const badSend = new URL(sendUrl);
		badSend.searchParams.set('_wpnonce', 'invalid');
		await goto(cPage, badSend.href);
		same(readMail().length, afterFirstSend, '无效确认邮件Nonce仍发送邮件。');
		const cVerifyUrl = extractUrl(cMail, 'wc_verify_email_key=');
		await logout(cPage);

		const loggedOutVerifyContext = await browser.newContext();
		const loggedOutVerifyPage = await loggedOutVerifyContext.newPage();
		await goto(loggedOutVerifyPage, cVerifyUrl);
		same(await loggedOutVerifyPage.locator('.woocommerce-form-login').count(), 1, '未登录打开确认链接消耗了Key或绕过登录。');
		await loggedOutVerifyContext.close();

		await goto(bPage, cVerifyUrl);
		check((await bPage.locator('.woocommerce-error').innerText()).includes('different account'), '错误账户打开确认链接未被拒绝。');
		await logout(bPage);

		await login(cPage, manifest.emails.c, manifest.passwords.c);
		await goto(cPage, cVerifyUrl);
		check((await cPage.locator('.woocommerce-message').innerText()).includes('confirmed'), '目标账户确认邮箱失败。');
		await goto(cPage, cVerifyUrl);
		same(new URL(cPage.url()).pathname.replace(/\/$/, ''), '/my-account/orders', '已使用确认链接重放行为错误。');
		await cContext.close();

		const aContext = await browser.newContext();
		const aPage = await aContext.newPage();
		attachErrors(aPage, 'customer-a');
		await goto(aPage, '/my-account/');
		const beforeA = readMail().length;
		await aPage.locator('#reg_email').fill(manifest.emails.a);
		await submitAndWait(aPage, aPage.locator('button[name="register"]'));
		same(await aPage.locator('.woocommerce-MyAccount-navigation').count(), 1, '唯一邮箱自助注册未自动登录。');
		const aMail = await waitForMail(manifest.emails.a, beforeA);
		const setPasswordUrl = extractUrl(aMail, 'key=');
		await logout(aPage);
		await goto(aPage, setPasswordUrl);
		same(await aPage.locator('#password_1').count(), 1, '新账户邮件设密链接无效。');
		const aPassword = `D79-${crypto.randomBytes(18).toString('base64url')}!7`;
		await aPage.locator('#password_1').fill(aPassword);
		await aPage.locator('#password_2').fill(aPassword);
		await submitAndWait(aPage, aPage.locator('button[value="Save"]'));
		check((await aPage.locator('.woocommerce-message').innerText()).toLowerCase().includes('password has been reset'), '新账户设密未完成。');
		manifest.passwords.a = aPassword;
		fs.writeFileSync(manifestPath, JSON.stringify(manifest, null, 2));
		await goto(aPage, '/my-account/');
		if ((await aPage.locator('.woocommerce-MyAccount-navigation').count()) === 0) {
			await login(aPage, manifest.emails.a, aPassword);
		} else {
			check(true, '设密后WooCommerce保持了目标客户登录态。');
		}
		await goto(aPage, '/my-account/orders/');
		same(await aPage.locator('table.my_account_orders tbody tr').count(), 3, '邮箱验证后未显示三张同邮箱历史Guest订单。');

		const bDeniedUrl = `/my-account/view-order/${manifest.orders.a_virtual}/`;
		const bDeniedContext = await browser.newContext();
		const bDeniedPage = await bDeniedContext.newPage();
		await login(bDeniedPage, manifest.emails.b, manifest.passwords.b);
		await goto(bDeniedPage, bDeniedUrl);
		check(!(await bDeniedPage.locator('body').innerText()).includes('79 Baseline Street'), 'B账户看到了A订单地址。');
		await bDeniedContext.close();

		const duplicateContext = await browser.newContext();
		const duplicatePage = await duplicateContext.newPage();
		await goto(duplicatePage, '/my-account/');
		await duplicatePage.locator('#reg_email').fill(manifest.emails.b);
		await submitAndWait(duplicatePage, duplicatePage.locator('button[name="register"]'));
		check((await duplicatePage.locator('.woocommerce-error').innerText()).includes(manifest.emails.b), 'Woo原生重复邮箱披露行为发生变化，风险记录失真。');
		result.registrationEnumerationResidual = true;
		await duplicateContext.close();

		await logout(aPage);
		await goto(aPage, '/my-account/orders/');
		same(await aPage.locator('.woocommerce-form-login').count(), 1, '退出后旧上下文仍可读取订单页。');
		await aContext.close();

		check(result.consoleErrors.length === 0, `浏览器Console错误：${result.consoleErrors.join(' | ')}`);
		check(result.pageErrors.length === 0, `浏览器Page错误：${result.pageErrors.join(' | ')}`);
		result.status = 'pass';
		fs.writeFileSync(outputPath, JSON.stringify(result, null, 2));
		console.log(JSON.stringify({ status: result.status, assertions: result.assertions, viewports: Object.keys(result.viewports).length }));
	} finally {
		await browser.close();
	}
}

main().catch((error) => {
	result.status = 'fail';
	result.error = error.message;
	fs.writeFileSync(outputPath, JSON.stringify(result, null, 2));
	console.error(error.stack || error.message);
	process.exitCode = 1;
});
