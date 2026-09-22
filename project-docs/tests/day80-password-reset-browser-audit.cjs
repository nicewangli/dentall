/**
 * D80找回密码、限频、设密与四端布局隔离Local审计。
 */

'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const { chromium } = require('playwright');

const baseUrl = process.env.DENTALL_BASE_URL;
const manifestPath = process.env.DENTALL_DAY80_MANIFEST;
const mailPath = process.env.DENTALL_DAY80_MAIL;
const outputPath = process.env.DENTALL_DAY80_BROWSER_RESULT;
const screenshotDir = process.env.DENTALL_DAY80_SCREENSHOTS;
const chromePath = process.env.DENTALL_CHROME_PATH || 'C:/Program Files/Google/Chrome/Application/chrome.exe';

for (const [name, value] of Object.entries({ baseUrl, manifestPath, mailPath, outputPath, screenshotDir })) {
	assert(value, `缺少环境变量：${name}`);
}

const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
const result = {
	status: 'running',
	assertions: 0,
	viewports: { request: {}, confirmation: {}, reset: {}, shortPassword: {}, usedLink: {} },
	pageErrors: [],
	consoleErrors: [],
	requestFailures: [],
};

function check(value, message) {
	result.assertions += 1;
	assert.ok(value, message);
}

function same(actual, expected, message) {
	result.assertions += 1;
	assert.equal(actual, expected, message);
}

function attachErrors(page, label) {
	page.on('pageerror', (error) => result.pageErrors.push(`${label}: ${error.message}`));
	page.on('console', (message) => {
		if (message.type() === 'error') result.consoleErrors.push(`${label}: ${message.text()}`);
	});
	page.on('requestfailed', (request) => {
		result.requestFailures.push(`${label}: ${request.url()} ${request.failure()?.errorText || 'failed'}`);
	});
}

function readMail() {
	if (!fs.existsSync(mailPath)) return [];
	return fs.readFileSync(mailPath, 'utf8').split(/\r?\n/).filter(Boolean).map((line) => JSON.parse(line));
}

async function waitForMail(startingCount) {
	for (let attempt = 0; attempt < 80; attempt += 1) {
		const messages = readMail();
		if (messages.length > startingCount) return messages[messages.length - 1];
		await new Promise((resolve) => setTimeout(resolve, 100));
	}
	throw new Error('未捕获到D80本地重置邮件。');
}

function resetUrlFrom(message) {
	const decoded = String(message.message).replaceAll('&amp;', '&').replaceAll('&#038;', '&').replaceAll('=3D', '=');
	const urls = [...decoded.matchAll(/https?:\/\/[^\s<>"']+/gi)].map((match) => match[0].replace(/[),.;]+$/, ''));
	const resetUrl = urls.find((url) => url.includes('key=') && url.includes('login='));
	check(resetUrl, '邮件缺少原生密码重置链接。');
	return resetUrl;
}

async function goto(page, relativeOrAbsolute) {
	const url = relativeOrAbsolute.startsWith('http') ? relativeOrAbsolute : `${baseUrl}${relativeOrAbsolute}`;
	const response = await page.goto(url, { waitUntil: 'domcontentloaded' });
	check(response && response.status() < 400, `请求失败：${new URL(url).pathname}`);
	return response;
}

async function submitResetRequest(page, identifier) {
	await goto(page, '/my-account/lost-password/');
	await page.locator('#user_login').fill(identifier);
	await Promise.all([
		page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
		page.locator('form.lost_reset_password button[type="submit"]').click(),
	]);
	return {
		heading: (await page.locator('.woocommerce-message').innerText()).trim(),
		message: (await page.locator('.woocommerce p').last().innerText()).trim(),
		pathname: new URL(page.url()).pathname,
	};
}

async function login(page, email, password) {
	await goto(page, '/my-account/');
	await page.locator('#username').fill(email);
	await page.locator('#password').fill(password);
	await Promise.all([
		page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
		page.locator('form.woocommerce-form-login button[name="login"]').click(),
	]);
	check(await page.locator('.woocommerce-MyAccount-navigation').count() === 1, 'TEST客户登录失败。');
}

async function verifyResponsiveLayout(browser) {
	for (const viewport of [390, 768, 1024, 1440]) {
		const context = await browser.newContext({ viewport: { width: viewport, height: 900 } });
		const page = await context.newPage();
		attachErrors(page, `request-${viewport}`);
		await goto(page, '/my-account/lost-password/');
		const metrics = await page.evaluate(() => {
			const form = document.querySelector('.woocommerce-ResetPassword.lost_reset_password');
			const button = form.querySelector('button[type="submit"]');
			return {
				overflow: document.documentElement.scrollWidth - document.documentElement.clientWidth,
				formWidth: form.getBoundingClientRect().width,
				buttonHeight: button.getBoundingClientRect().height,
				labelled: Boolean(document.querySelector('label[for="user_login"]')),
				stylesheet: [...document.styleSheets].some((sheet) => sheet.href && sheet.href.includes('account-auth.css')),
			};
		});
		result.viewports.request[viewport] = metrics;
		check(metrics.overflow <= 1, `${viewport}px找回页横向溢出。`);
		check(metrics.formWidth <= 576, `${viewport}px找回表单超过36rem。`);
		check(metrics.buttonHeight >= 44, `${viewport}px提交按钮触控高度不足44px。`);
		check(metrics.labelled, `${viewport}px账号输入缺少关联标签。`);
		check(metrics.stylesheet, `${viewport}px未按页加载账户样式。`);
		await page.screenshot({ path: path.join(screenshotDir, `lost-password-${viewport}.png`), fullPage: true });
		await context.close();
	}
}

async function verifyConfirmationLayouts(browser, identifier, expected) {
	for (const viewport of [390, 768, 1024, 1440]) {
		const context = await browser.newContext({ viewport: { width: viewport, height: 900 } });
		const page = await context.newPage();
		attachErrors(page, `confirmation-${viewport}`);
		const confirmation = await submitResetRequest(page, identifier);
		const overflow = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
		result.viewports.confirmation[viewport] = { overflow };
		check(overflow <= 1, `${viewport}px通用确认页横向溢出。`);
		same(confirmation.heading, expected.heading, `${viewport}px通用确认标题变化。`);
		same(confirmation.message, expected.message, `${viewport}px通用确认说明变化。`);
		await page.screenshot({ path: path.join(screenshotDir, `confirmation-${viewport}.png`), fullPage: true });
		await context.close();
	}
}

async function verifyResetLayouts(browser, resetUrl) {
	for (const viewport of [390, 768, 1024, 1440]) {
		const context = await browser.newContext({ viewport: { width: viewport, height: 900 } });
		const page = await context.newPage();
		attachErrors(page, `reset-${viewport}`);
		await goto(page, resetUrl);
		const metrics = await page.evaluate(() => {
			const form = document.querySelector('.woocommerce-ResetPassword.lost_reset_password');
			return {
				overflow: document.documentElement.scrollWidth - document.documentElement.clientWidth,
				formWidth: form.getBoundingClientRect().width,
				labels: ['password_1', 'password_2'].every((id) => document.querySelector(`label[for="${id}"]`)),
				stylesheet: [...document.styleSheets].some((sheet) => sheet.href && sheet.href.includes('account-auth.css')),
			};
		});
		result.viewports.reset[viewport] = metrics;
		check(metrics.overflow <= 1, `${viewport}px新密码表单横向溢出。`);
		check(metrics.formWidth <= 576, `${viewport}px新密码表单超过36rem。`);
		check(metrics.labels, `${viewport}px新密码字段缺少关联标签。`);
		check(metrics.stylesheet, `${viewport}px新密码表单未加载账户样式。`);
		await page.screenshot({ path: path.join(screenshotDir, `reset-${viewport}.png`), fullPage: true });
		await context.close();
	}
}

async function verifyShortPasswordLayouts(browser, resetUrl) {
	for (const viewport of [390, 768, 1024, 1440]) {
		const context = await browser.newContext({ viewport: { width: viewport, height: 900 } });
		const page = await context.newPage();
		attachErrors(page, `short-password-${viewport}`);
		await goto(page, resetUrl);
		await page.locator('#password_1').fill('Aa1!Bb2@Cc3');
		await page.locator('#password_2').fill('Aa1!Bb2@Cc3');
		await Promise.all([
			page.waitForNavigation({ waitUntil: 'domcontentloaded' }).catch(() => null),
			page.locator('form.lost_reset_password').evaluate((form) => form.submit()),
		]);
		const state = {
			overflow: await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth),
			error: (await page.locator('.woocommerce-error').innerText()).trim(),
			formVisible: await page.locator('#password_1').count(),
		};
		result.viewports.shortPassword[viewport] = state;
		check(state.overflow <= 1, `${viewport}px短密码错误态横向溢出。`);
		check(state.error.includes('at least 12 characters'), `${viewport}px未显示12字符服务端错误。`);
		same(state.formVisible, 1, `${viewport}px短密码错误后表单消失。`);
		await page.screenshot({ path: path.join(screenshotDir, `short-password-${viewport}.png`), fullPage: true });
		await context.close();
	}
}

async function verifyUsedLinkLayouts(browser, resetUrl) {
	for (const viewport of [390, 768, 1024, 1440]) {
		const context = await browser.newContext({ viewport: { width: viewport, height: 900 } });
		const page = await context.newPage();
		attachErrors(page, `used-link-${viewport}`);
		await goto(page, resetUrl);
		const state = {
			overflow: await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth),
			body: await page.locator('body').innerText(),
			formVisible: await page.locator('#password_1').count(),
		};
		result.viewports.usedLink[viewport] = { overflow: state.overflow, formVisible: state.formVisible };
		check(state.overflow <= 1, `${viewport}px已用链接错误态横向溢出。`);
		check(state.body.includes('invalid or has already been used'), `${viewport}px已用链接未显示统一失效提示。`);
		same(state.formVisible, 0, `${viewport}px已用链接仍显示新密码表单。`);
		await context.close();
	}
}

(async () => {
	fs.mkdirSync(screenshotDir, { recursive: true });
	const browser = await chromium.launch({ executablePath: chromePath, headless: true });
	try {
		await verifyResponsiveLayout(browser);

		const coreContext = await browser.newContext();
		const corePage = await coreContext.newPage();
		attachErrors(corePage, 'core-entry');
		await goto(corePage, '/wp-login.php?action=lostpassword');
		same(new URL(corePage.url()).pathname, '/my-account/lost-password/', 'WordPress核心找回入口未归一到My Account。');
		await goto(corePage, '/wp-login.php?action=retrievepassword');
		same(new URL(corePage.url()).pathname, '/my-account/lost-password/', 'WordPress核心retrievepassword入口未归一到My Account。');
		await coreContext.close();

		const requestContext = await browser.newContext();
		const requestPage = await requestContext.newPage();
		attachErrors(requestPage, 'request-flow');
		const mailBefore = readMail().length;
		const firstRequestAt = Date.now();
		const existing = await submitResetRequest(requestPage, manifest.email);
		const message = await waitForMail(mailBefore);
		same(readMail().length, mailBefore + 1, '存在账号首次请求未恰好产生一封本地邮件。');
		check(message.to.some((address) => address.toLowerCase() === manifest.email.toLowerCase()), '重置邮件收件人不是TEST客户。');
		const resetUrl = resetUrlFrom(message);

		const repeated = await submitResetRequest(requestPage, manifest.email);
		same(readMail().length, mailBefore + 1, '60秒内重复请求仍产生邮件。');
		same(repeated.heading, existing.heading, '重复请求公开标题发生变化。');
		same(repeated.message, existing.message, '重复请求公开说明发生变化。');

		await verifyConfirmationLayouts(browser, manifest.email, existing);
		await verifyResetLayouts(browser, resetUrl);
		await verifyShortPasswordLayouts(browser, resetUrl);

		const identityWait = Math.max(0, 10500 - (Date.now() - firstRequestAt));
		await requestPage.waitForTimeout(identityWait);
		const identityLimited = await submitResetRequest(requestPage, manifest.email);
		same(readMail().length, mailBefore + 1, '短等待后60秒身份限频未阻止重复邮件。');
		same(identityLimited.heading, existing.heading, '身份限频公开标题发生变化。');

		const unknown = await submitResetRequest(requestPage, `${manifest.marker}-missing@example.test`);
		same(readMail().length, mailBefore + 1, '不存在账号请求产生了邮件。');
		same(unknown.heading, existing.heading, '存在与不存在账号的公开标题不同。');
		same(unknown.message, existing.message, '存在与不存在账号的公开说明不同。');
		same(unknown.pathname, existing.pathname, '存在与不存在账号的结果URL不同。');
		same(existing.heading, 'Your password reset request has been received.', '通用确认标题不符合确认范围。');
		check(existing.message.startsWith('If an account matches'), '通用确认说明不符合确认范围。');
		await requestContext.close();

		await new Promise((resolve) => setTimeout(resolve, 10500));
		const sameAccountContext = await browser.newContext();
		const sameAccountPage = await sameAccountContext.newPage();
		attachErrors(sameAccountPage, 'same-account-link');
		await login(sameAccountPage, manifest.same_email, manifest.same_password);
		const sameMailBefore = readMail().length;
		await submitResetRequest(sameAccountPage, manifest.same_email);
		const sameMessage = await waitForMail(sameMailBefore);
		same(readMail().length, mailBefore + 2, '已登录目标客户的首次请求未恰好产生第二封本地邮件。');
		check(sameMessage.to.some((address) => address.toLowerCase() === manifest.same_email.toLowerCase()), '已登录重置邮件收件人不是目标TEST客户。');
		const sameResetUrl = resetUrlFrom(sameMessage);
		await goto(sameAccountPage, sameResetUrl);
		same(await sameAccountPage.locator('#password_1').count(), 1, '已登录目标客户无法打开自己的重置链接。');
		check(await sameAccountPage.locator('link[href*="account-auth.css"]').count() === 1, '已登录目标客户的重置表单未加载账户样式。');

		const otherAccountContext = await browser.newContext();
		const otherAccountPage = await otherAccountContext.newPage();
		attachErrors(otherAccountPage, 'other-account-link');
		await login(otherAccountPage, manifest.other_email, manifest.other_password);
		await goto(otherAccountPage, sameResetUrl);
		check((await otherAccountPage.locator('body').innerText()).includes('different user account'), '其他已登录客户未被拒绝使用目标重置链接。');
		same(await otherAccountPage.locator('#password_1').count(), 0, '其他已登录客户看到了目标新密码表单。');
		await otherAccountContext.close();
		await sameAccountContext.close();

		const resetContext = await browser.newContext();
		const resetPage = await resetContext.newPage();
		attachErrors(resetPage, 'reset-success');
		await goto(resetPage, resetUrl);
		same(await resetPage.locator('#password_1').count(), 1, '原生重置链接未显示新密码表单。');
		await resetPage.locator('#password_1').fill('Aa1!Bb2@Cc3#');
		await resetPage.locator('#password_2').fill('Aa1!Bb2@Cc3#');
		await Promise.all([
			resetPage.waitForNavigation({ waitUntil: 'domcontentloaded' }),
			resetPage.locator('form.lost_reset_password').evaluate((form) => form.submit()),
		]);
		check((await resetPage.locator('.woocommerce-message').innerText()).toLowerCase().includes('password has been reset'), '12字符密码未完成原生重置。');
		check((await resetPage.locator('.woocommerce-MyAccount-navigation').count()) === 1, '成功重置后未保持目标客户登录态。');
		await resetContext.close();

		await verifyUsedLinkLayouts(browser, resetUrl);

		same(result.pageErrors.length, 0, `浏览器Page错误：${result.pageErrors.join(' | ')}`);
		same(result.consoleErrors.length, 0, `浏览器Console错误：${result.consoleErrors.join(' | ')}`);
		same(result.requestFailures.length, 0, `浏览器资源失败：${result.requestFailures.join(' | ')}`);
		result.status = 'pass';
		fs.writeFileSync(outputPath, JSON.stringify(result, null, 2));
		console.log(JSON.stringify({ status: result.status, assertions: result.assertions, states: Object.keys(result.viewports).length }));
	} finally {
		await browser.close();
	}
})().catch((error) => {
	result.status = 'fail';
	result.error = error.message;
	fs.writeFileSync(outputPath, JSON.stringify(result, null, 2));
	console.error(error.stack || error.message);
	process.exitCode = 1;
});
