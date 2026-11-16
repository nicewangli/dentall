( function () {
	'use strict';

	const blocksCheckout = window.wc && window.wc.blocksCheckout;
	const i18n = window.wp && window.wp.i18n;
	const config = window.dentallShippingQuote || {};

	if ( ! blocksCheckout || ! i18n ) {
		return;
	}

	const { __ } = i18n;

	function cartNeedsQuote( args ) {
		const cart = args && args.cart;
		const quote = cart && cart.extensions && cart.extensions[ 'dentall/shipping-quote' ];

		// 服务端事实不依赖自动运费计算；实体购物车仍需人工报价。
		return quote && typeof quote.required === 'boolean'
			? quote.required
			: Boolean( cart && ( cart.cartNeedsShipping || cart.needsShipping ) );
	}

	blocksCheckout.registerCheckoutFilters( 'dentall/shipping-quote', {
		proceedToCheckoutButtonLabel: ( defaultValue, extensions, args ) => cartNeedsQuote( args )
			? __( 'Request Shipping Quote', 'dentall' ) : defaultValue,
		proceedToCheckoutButtonLink: ( defaultValue, extensions, args ) => cartNeedsQuote( args )
			? config.requestUrl : defaultValue,
		totalLabel: ( defaultValue, extensions, args ) => cartNeedsQuote( args )
			? __( 'Estimated total — shipping pending', 'dentall' ) : defaultValue,
	} );
}() );
