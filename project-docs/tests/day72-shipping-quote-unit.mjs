/**
 * D72 Cart Block人工运费邮件报价纯前端合同测试。
 *
 * 运行：node project-docs/tests/day72-shipping-quote-unit.mjs
 */

import assert from 'node:assert/strict';
import { readFile } from 'node:fs/promises';
import vm from 'node:vm';

const source = await readFile(
	new URL('../../app/public/wp-content/themes/dentall/assets/js/shipping-quote.js', import.meta.url),
	'utf8'
);

function sprintf(template, ...values) {
	let nextIndex = 0;

	return template.replace(/%(?:(\d+)\$)?[ds]/g, (match, position) => {
		const index = position ? Number.parseInt(position, 10) - 1 : nextIndex++;

		return String(values[index]);
	});
}

function loadFilters(recipient = 'quotes@example.com') {
	let filters;
	let quoteClickHandler;
	const context = {
		Intl,
		document: {
			addEventListener: (name, handler, capture) => {
				if (name === 'click' && capture === true) {
					quoteClickHandler = handler;
				}
			},
			createElement: () => {
				const field = { value: '' };

				Object.defineProperty(field, 'innerHTML', {
					set(value) {
						field.value = String(value)
							.replace(/&amp;/g, '&')
							.replace(/&quot;/g, '"')
							.replace(/<[^>]*>/g, '');
					},
				});

				return field;
			},
		},
		window: {
			location: { href: 'https://example.com/cart/' },
			dentallShippingQuote: {
				recipient,
				cartUrl: 'https://example.com/cart/',
			},
			wc: {
				blocksCheckout: {
					registerCheckoutFilters: (namespace, registeredFilters) => {
						assert.equal(namespace, 'dentall/shipping-quote');
						filters = registeredFilters;
					},
				},
			},
			wp: {
				i18n: {
					__: (value) => value,
					sprintf,
				},
			},
		},
	};

	context.window.window = context.window;
	context.window.document = context.document;
	context.window.Intl = Intl;
	vm.runInNewContext(source, context, { filename: 'shipping-quote.js' });
	filters.__testQuoteClickHandler = quoteClickHandler;
	filters.__testWindow = context.window;

	return filters;
}

const physicalCart = {
	cartNeedsShipping: true,
	extensions: { 'dentall/shipping-quote': { required: true } },
	cartItems: [
		{
			name: 'Dental &amp; Kit',
			sku: 'KIT-01',
			quantity: 2,
			variation: [{ attribute: 'Size', value: 'Large' }],
		},
		{
			name: 'Mirror',
			sku: '',
			quantity: 1,
			variation: [],
		},
	],
	cartCoupons: [{ code: 'DENTALL10' }],
	cartTotals: {
		total_items: '12345',
		currency_code: 'USD',
		currency_minor_unit: 2,
	},
};
const filters = loadFilters();
const args = { cart: physicalCart };

assert.equal(
	filters.proceedToCheckoutButtonLabel('Proceed to Checkout', {}, args),
	'Request Shipping Quote by Email'
);

const mailto = filters.proceedToCheckoutButtonLink('/checkout/', {}, args);
const parsedMailto = new URL(mailto);
const body = parsedMailto.searchParams.get('body');

assert.equal(parsedMailto.protocol, 'mailto:');
assert.equal(parsedMailto.pathname, 'quotes@example.com');
assert.equal(parsedMailto.searchParams.get('subject'), 'Shipping quote request');
assert.match(body, /Dental & Kit \| SKU: KIT-01 \| Specification: Size: Large \| Quantity: 2/);
assert.match(body, /Mirror \| SKU: Not provided \| Specification: Standard \| Quantity: 1/);
assert.match(body, /Required unless marked optional\./);
assert.match(body, /Billing email \(Required; enter an accurate email address\):/);
assert.match(body, /Phone \/ WhatsApp \(optional\):/);
assert.match(body, /Shipping first name \(Required\):/);
assert.match(body, /Shipping last name \(Required\):/);
assert.match(body, /Shipping company \(optional\):/);
assert.match(body, /Shipping country \(Required\):/);
assert.match(body, /Shipping state \/ province \(Required\):/);
assert.match(body, /Shipping city \(Required\):/);
assert.match(body, /Shipping postal code \(Required\):/);
assert.match(body, /Shipping address line 1 \(Required\):/);
assert.match(body, /Shipping address line 2 \(optional\):/);
assert.match(body, /Billing address same as shipping\? \(Required: Yes \/ No\):/);
assert.match(body, /Billing first name \(Required if No\):/);
assert.match(body, /Billing last name \(Required if No\):/);
assert.match(body, /Billing company \(optional\):/);
assert.match(body, /Billing country \(Required if No\):/);
assert.match(body, /Billing state \/ province \(Required if No\):/);
assert.match(body, /Billing city \(Required if No\):/);
assert.match(body, /Billing postal code \(Required if No\):/);
assert.match(body, /Billing address line 1 \(Required if No\):/);
assert.match(body, /Billing address line 2 \(optional\):/);
assert.match(body, /Current product subtotal before discounts, shipping, tax and fees: USD\s*123\.45/);
assert.match(body, /Coupon codes shown in cart: DENTALL10/);
assert.match(body, /DentAll will confirm shipping, any seller-collected tax, and other charges included in the order before payment\./);
assert.match(body, /Import duties, import taxes, customs clearance charges, and carrier brokerage or disbursement fees are excluded from the DentAll order total/);
assert.match(body, /paid by the customer directly to customs or the carrier when assessed\./);

const clickState = { prevented: false, stopped: false };
filters.__testQuoteClickHandler({
	target: { closest: () => ({ href: mailto }) },
	preventDefault: () => { clickState.prevented = true; },
	stopPropagation: () => { clickState.stopped = true; },
});
assert.deepEqual(clickState, { prevented: true, stopped: true });
assert.equal(filters.__testWindow.location.href, mailto);

const reservedRecipientFilters = loadFilters('sales?bcc=other@example.com');
const reservedRecipientLink = reservedRecipientFilters.proceedToCheckoutButtonLink(
	'/checkout/',
	{},
	args
);
assert.match(reservedRecipientLink, /^mailto:sales%3Fbcc%3Dother@example\.com\?/);
assert.equal(new URL(reservedRecipientLink).searchParams.get('bcc'), null);

physicalCart.cartItems[0].quantity = 7;
const updatedBody = new URL(
	filters.proceedToCheckoutButtonLink('/checkout/', {}, args)
).searchParams.get('body');
assert.match(updatedBody, /Quantity: 7/);

const virtualArgs = { cart: { cartNeedsShipping: false } };
assert.equal(
	filters.proceedToCheckoutButtonLabel('Proceed to Checkout', {}, virtualArgs),
	'Proceed to Checkout'
);
assert.equal(
	filters.proceedToCheckoutButtonLink('/checkout/', {}, virtualArgs),
	'/checkout/'
);

const noRatePhysicalArgs = {
	cart: {
		cartNeedsShipping: false,
		extensions: { 'dentall/shipping-quote': { required: true } },
		cartItems: [],
		cartTotals: {},
	},
};
assert.equal(
	filters.proceedToCheckoutButtonLabel('Proceed to Checkout', {}, noRatePhysicalArgs),
	'Request Shipping Quote by Email'
);

const rawStoreCartArgs = {
	cart: {
		needsShipping: false,
		extensions: { 'dentall/shipping-quote': { required: true } },
		items: physicalCart.cartItems,
		coupons: physicalCart.cartCoupons,
		totals: physicalCart.cartTotals,
	},
};
const rawStoreMail = new URL(
	filters.proceedToCheckoutButtonLink('/checkout/', {}, rawStoreCartArgs)
).searchParams.get('body');
assert.match(rawStoreMail, /SKU: KIT-01/);
assert.match(rawStoreMail, /USD\s*123\.45/);

const unavailableFilters = loadFilters('');
assert.equal(
	unavailableFilters.proceedToCheckoutButtonLabel('Proceed to Checkout', {}, args),
	'Shipping Quote Email Unavailable'
);
assert.equal(
	unavailableFilters.proceedToCheckoutButtonLink('/checkout/', {}, args),
	'https://example.com/cart/'
);

console.log(JSON.stringify({ status: 'pass', assertions: 46 }));
