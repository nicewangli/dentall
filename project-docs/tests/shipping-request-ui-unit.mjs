// 用Woo公开过滤器合同检查实体、虚拟、空车和加载状态，不启动浏览器。
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { runInNewContext } from 'node:vm';
import test from 'node:test';

const source = readFileSync(new URL('../../wp-content/themes/dentall/assets/js/shipping-quote.js', import.meta.url), 'utf8');
const requestUrl = 'https://example.test/cart/?shipping-request=1';
let filters;
runInNewContext(source, { window: {
  wc: { blocksCheckout: { registerCheckoutFilters: (name, callbacks) => {
    assert.equal(name, 'dentall/shipping-quote');
    filters = callbacks;
  } } },
  wp: { i18n: { __: text => text } },
  dentallShippingQuote: { requestUrl },
} });

const cases = [
  ['人工报价不依赖自动运费', { cartNeedsShipping: false, extensions: { 'dentall/shipping-quote': { required: true } } }, true],
  ['明确虚拟购物车保持原生', { cartNeedsShipping: true, extensions: { 'dentall/shipping-quote': { required: false } } }, false],
  ['空购物车保持原生', { cartNeedsShipping: false }, false],
  ['兼容缺扩展但需配送的购物车', { cartNeedsShipping: true }, true],
  ['尚未读到购物车不提前改变付款入口', undefined, false],
];
for (const [name, cart, quote] of cases) {
  test(name, () => {
    assert.equal(filters.proceedToCheckoutButtonLink('/checkout/', {}, { cart }), quote ? requestUrl : '/checkout/');
    assert.equal(filters.proceedToCheckoutButtonLabel('Checkout', {}, { cart }), quote ? 'Request Shipping Quote' : 'Checkout');
    assert.equal(filters.totalLabel('Total', {}, { cart }), quote ? 'Estimated total — shipping pending' : 'Total');
  });
}
test('展示层不覆盖金额计算与付款值', () => assert.equal(filters.totalValue, undefined));
test('依赖未就绪时不抛异常', () => {
  assert.doesNotThrow(() => runInNewContext(source, { window: {} }));
});
