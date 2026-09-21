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
			__( 'Customer and shipping details', 'dentall' ),
			__( 'Required unless marked optional.', 'dentall' ),
			__( 'Billing email (Required; enter an accurate email address):', 'dentall' ),
			__( 'Phone / WhatsApp (optional):', 'dentall' ),
			__( 'Shipping first name (Required):', 'dentall' ),
			__( 'Shipping last name (Required):', 'dentall' ),
			__( 'Shipping company (optional):', 'dentall' ),
			__( 'Shipping country (Required):', 'dentall' ),
			__( 'Shipping state / province (Required):', 'dentall' ),
			__( 'Shipping city (Required):', 'dentall' ),
			__( 'Shipping postal code (Required):', 'dentall' ),
			__( 'Shipping address line 1 (Required):', 'dentall' ),
			__( 'Shipping address line 2 (optional):', 'dentall' ),
			__( 'Preferred delivery speed (optional):', 'dentall' ),
			'',
			__( 'Billing address', 'dentall' ),
			__( 'Billing address same as shipping? (Required: Yes / No):', 'dentall' ),
			__( 'If No, complete the billing address below.', 'dentall' ),
			__( 'Billing first name (Required if No):', 'dentall' ),
			__( 'Billing last name (Required if No):', 'dentall' ),
			__( 'Billing company (optional):', 'dentall' ),
			__( 'Billing country (Required if No):', 'dentall' ),
			__( 'Billing state / province (Required if No):', 'dentall' ),
			__( 'Billing city (Required if No):', 'dentall' ),
			__( 'Billing postal code (Required if No):', 'dentall' ),
			__( 'Billing address line 1 (Required if No):', 'dentall' ),
			__( 'Billing address line 2 (optional):', 'dentall' ),
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
			__( 'DentAll will confirm shipping, any seller-collected tax, and other charges included in the order before payment.', 'dentall' ),
			__( 'Import duties, import taxes, customs clearance charges, and carrier brokerage or disbursement fees are excluded from the DentAll order total and must be paid by the customer directly to customs or the carrier when assessed.', 'dentall' ),
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
