( function () {
	'use strict';

	const blocksCheckout = window.wc && window.wc.blocksCheckout;
	const i18n = window.wp && window.wp.i18n;
	const config = window.dentallShippingQuote || {};

	if ( ! blocksCheckout || ! i18n ) {
		return;
	}

	const { __, sprintf } = i18n;

	function cartNeedsQuote( args ) {
		const cart = args && args.cart;
		const quoteData = cart
			&& cart.extensions
			&& cart.extensions[ 'dentall/shipping-quote' ];

		if ( quoteData && typeof quoteData.required === 'boolean' ) {
			return quoteData.required;
		}

		return Boolean( cart && ( cart.cartNeedsShipping || cart.needsShipping ) );
	}

	function plainText( value ) {
		const decoder = document.createElement( 'textarea' );

		decoder.innerHTML = String( value || '' );

		return decoder.value.replace( /\s+/g, ' ' ).trim();
	}

	function formatProductSubtotal( totals ) {
		if ( ! totals ) {
			return __( 'Not available', 'dentall' );
		}

		const minorUnit = Number.parseInt( totals.currency_minor_unit, 10 );
		const rawAmount = Number.parseInt( totals.total_items, 10 );
		const currency = plainText( totals.currency_code );

		if ( ! Number.isFinite( rawAmount ) || ! Number.isFinite( minorUnit ) || ! currency ) {
			return __( 'Not available', 'dentall' );
		}

		const amount = rawAmount / ( 10 ** minorUnit );

		try {
			return new Intl.NumberFormat( undefined, {
				style: 'currency',
				currency,
				currencyDisplay: 'code',
				minimumFractionDigits: minorUnit,
				maximumFractionDigits: minorUnit,
			} ).format( amount );
		} catch ( error ) {
			return `${ currency } ${ amount.toFixed( minorUnit ) }`;
		}
	}

	function getVariationText( item ) {
		if ( ! Array.isArray( item.variation ) || item.variation.length === 0 ) {
			return __( 'Standard', 'dentall' );
		}

		return item.variation
			.map( ( variation ) => {
				const attribute = plainText(
					variation.attribute || variation.raw_attribute || variation.name
				);
				const value = plainText( variation.value || variation.display );

				return attribute && value ? `${ attribute }: ${ value }` : value;
			} )
			.filter( Boolean )
			.join( ', ' ) || __( 'Standard', 'dentall' );
	}

	function getCouponText( cart ) {
		const coupons = Array.isArray( cart.cartCoupons ) ? cart.cartCoupons : cart.coupons;

		if ( ! Array.isArray( coupons ) || coupons.length === 0 ) {
			return __( 'None', 'dentall' );
		}

		return coupons
			.map( ( coupon ) => plainText( coupon.code ) )
			.filter( Boolean )
			.join( ', ' ) || __( 'None', 'dentall' );
	}

	function buildMailtoLink( cart ) {
		const recipient = plainText( config.recipient );
		const encodedRecipient = encodeURIComponent( recipient ).replace( /%40/gi, '@' );
		const items = Array.isArray( cart.cartItems ) ? cart.cartItems : cart.items;
		const totals = cart.cartTotals || cart.totals;
		const itemLines = ( Array.isArray( items ) ? items : [] ).map( ( item, index ) => sprintf(
			/* translators: 1: line number, 2: product name, 3: SKU, 4: variation, 5: quantity */
			__( '%1$d. %2$s | SKU: %3$s | Specification: %4$s | Quantity: %5$d', 'dentall' ),
			index + 1,
			plainText( item.name ) || __( 'Unnamed product', 'dentall' ),
			plainText( item.sku ) || __( 'Not provided', 'dentall' ),
			getVariationText( item ),
			Number.parseInt( item.quantity, 10 ) || 0
		) );
		const body = [
			__( 'Hello DentAll team,', 'dentall' ),
			'',
			__( 'Please provide a shipping quote for the cart below.', 'dentall' ),
			'',
			__( 'Customer details', 'dentall' ),
			__( 'Name:', 'dentall' ),
			__( 'Company:', 'dentall' ),
			__( 'Email:', 'dentall' ),
			__( 'WhatsApp (optional):', 'dentall' ),
			__( 'Ship-to country:', 'dentall' ),
			__( 'State / Province:', 'dentall' ),
			__( 'Postal code:', 'dentall' ),
			__( 'Full delivery address:', 'dentall' ),
			__( 'Preferred delivery speed (optional):', 'dentall' ),
			'',
			__( 'Cart items', 'dentall' ),
			...itemLines,
			'',
			sprintf(
				/* translators: %s: formatted product subtotal */
				__( 'Current product subtotal before discounts, shipping, tax and fees: %s', 'dentall' ),
				formatProductSubtotal( totals )
			),
			sprintf(
				/* translators: %s: coupon codes */
				__( 'Coupon codes shown in cart: %s', 'dentall' ),
				getCouponText( cart )
			),
			'',
			__( 'I understand that shipping, taxes and other applicable fees will be confirmed before payment.', 'dentall' ),
		].join( '\r\n' );
		const subject = __( 'Shipping quote request', 'dentall' );

		return `mailto:${ encodedRecipient }?subject=${ encodeURIComponent( subject ) }&body=${ encodeURIComponent( body ) }`;
	}

	function openQuoteEmailWithoutCartLoadingState( event ) {
		const link = event.target && typeof event.target.closest === 'function'
			? event.target.closest( 'a.wc-block-cart__submit-button' )
			: null;

		if ( ! link || ! link.href.startsWith( 'mailto:' ) ) {
			return;
		}

		/*
		 * Cart Block会把任何Proceed链接点击都标记为页面跳转并持续显示loading；
		 * mailto可能只唤起外部客户端而不卸载页面，因此在捕获阶段自行打开邮件。
		 */
		event.preventDefault();
		event.stopPropagation();
		window.location.href = link.href;
	}

	document.addEventListener( 'click', openQuoteEmailWithoutCartLoadingState, true );

	blocksCheckout.registerCheckoutFilters( 'dentall/shipping-quote', {
		proceedToCheckoutButtonLabel: ( defaultValue, extensions, args ) => {
			if ( ! cartNeedsQuote( args ) ) {
				return defaultValue;
			}

			return config.recipient
				? __( 'Request Shipping Quote by Email', 'dentall' )
				: __( 'Shipping Quote Email Unavailable', 'dentall' );
		},
		proceedToCheckoutButtonLink: ( defaultValue, extensions, args ) => {
			if ( ! cartNeedsQuote( args ) ) {
				return defaultValue;
			}

			return config.recipient ? buildMailtoLink( args.cart ) : config.cartUrl;
		},
	} );
}() );
