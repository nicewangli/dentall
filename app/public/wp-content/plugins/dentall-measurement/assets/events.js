( function () {
	'use strict';

	const config = window.dentallMeasurement;
	if ( ! config ) {
		return;
	}
	const seen = new Set();
	const allowed = new Set( [ 'view_item_list', 'select_item', 'view_item', 'add_to_cart', 'view_cart', 'begin_checkout', 'add_shipping_info', 'add_payment_info', 'purchase', 'view_search_results' ] );
	const itemKeys = [ 'item_id', 'item_name', 'item_variant', 'price', 'quantity', 'index', 'item_list_id' ];
	const ecommerceKeys = [ 'currency', 'value', 'tax', 'shipping', 'transaction_id', 'shipping_tier', 'payment_type', 'item_list_id' ];
	window.dataLayer = window.dataLayer || [];

	function hasConsent() {
		return window.dentallMeasurementConsent && window.dentallMeasurementConsent.hasConsent();
	}

	function pick( source, keys ) {
		const result = {};
		keys.forEach( function ( key ) {
			if ( source[ key ] !== undefined ) {
				result[ key ] = source[ key ];
			}
		} );
		return result;
	}

	function emit( payload ) {
		if ( ! hasConsent() || ! payload || ! allowed.has( payload.event ) || ( payload.id && seen.has( payload.id ) ) ) {
			return;
		}
		const event = { event: payload.event, dentall_consent_revision: window.dentallMeasurementConsent.revision };
		if ( payload.ecommerce ) {
			event.ecommerce = pick( payload.ecommerce, ecommerceKeys );
			event.ecommerce.items = ( payload.ecommerce.items || [] ).map( function ( item ) {
				return pick( item, itemKeys );
			} );
		}
		if ( payload.event === 'purchase' ) {
			const transaction = event.ecommerce && event.ecommerce.transaction_id;
			if ( ! transaction ) {
				return;
			}
			// 存储不可用时宁可漏记，也不把刷新伪称为一次可靠的新购买。
			try {
				const key = 'dentall.measurement.purchase.' + transaction;
				if ( window.sessionStorage.getItem( key ) ) {
					return;
				}
				window.sessionStorage.setItem( key, '1' );
			} catch ( error ) {
				return;
			}
		}
		if ( payload.event === 'view_search_results' ) {
			event.search_results_count = payload.search_results_count;
		}
		if ( payload.id ) {
			seen.add( payload.id );
		}
		window.dataLayer.push( { ecommerce: null } );
		window.dataLayer.push( event );
	}

	function parse( value ) {
		try {
			return JSON.parse( value );
		} catch ( error ) {
			return null;
		}
	}

	( config.events || [] ).forEach( emit );
	if ( config.page === 'product' && config.product ) {
		const ecommerce = { currency: config.currency, items: [ config.product ] };
		if ( Number.isFinite( config.product.price ) ) {
			ecommerce.value = config.product.price * config.product.quantity;
		}
		emit( { event: 'view_item', ecommerce: ecommerce } );
	}

	const lists = new Map();
	document.querySelectorAll( '[data-dentall-measurement-item]' ).forEach( function ( marker ) {
		const list = marker.closest( 'ul.products' );
		const item = parse( marker.dataset.dentallMeasurementItem );
		if ( ! list || ! item ) {
			return;
		}
		if ( ! lists.has( list ) ) {
			lists.set( list, { item_list_id: marker.dataset.dentallMeasurementList || config.page, items: [] } );
		}
		const group = lists.get( list );
		group.items.push( Object.assign( {}, item, { index: group.items.length } ) );
	} );
	lists.forEach( function ( ecommerce ) {
		emit( { event: 'view_item_list', ecommerce: ecommerce } );
	} );

	document.addEventListener( 'click', function ( click ) {
		const link = click.target.closest( 'a.woocommerce-LoopProduct-link' );
		const product = link && link.closest( 'li.product' );
		const marker = product && product.querySelector( '[data-dentall-measurement-item]' );
		if ( ! marker || click.defaultPrevented ) {
			return;
		}
		const item = parse( marker.dataset.dentallMeasurementItem );
		const list = lists.get( marker.closest( 'ul.products' ) );
		if ( item && list ) {
			emit( { event: 'select_item', ecommerce: { item_list_id: list.item_list_id, items: [ item ] } } );
		}
	} );

	// 经典 AJAX 只消费 Woo 成功响应中的服务端载荷，不读取按钮价格或估算数量差。
	if ( window.jQuery ) {
		window.jQuery( document.body ).on( 'added_to_cart.dentallMeasurement', function ( event, fragments ) {
			const html = fragments && fragments[ 'span.dentall-measurement-cart-event' ];
			if ( ! html ) {
				return;
			}
			const template = document.createElement( 'template' );
			template.innerHTML = html;
			const marker = template.content.querySelector( '[data-dentall-measurement-event]' );
			const events = marker && parse( marker.dataset.dentallMeasurementEvent );
			if ( Array.isArray( events ) ) {
				events.forEach( emit );
			}
		} );
	}

	if ( ! config.blocks ) {
		if ( config.cart && [ 'cart', 'checkout', 'order-pay' ].includes( config.page ) ) {
			emit( { event: config.page === 'cart' ? 'view_cart' : 'begin_checkout', ecommerce: config.cart } );
		}
		const payForm = config.page === 'order-pay' && config.cart && document.querySelector( '#order_review' );
		if ( payForm ) {
			let submitted = false;
			payForm.addEventListener( 'submit', function ( submit ) {
				// 等其它网关监听器处理完取消提交；仅记录提交，不代表收款成功。
				window.queueMicrotask( function () {
					const method = payForm.querySelector( 'input[name="payment_method"]:checked' );
					if ( ! hasConsent() || submitted || submit.defaultPrevented || ! method || method.disabled || ! method.value ) {
						return;
					}
					submitted = true;
					emit( { event: 'add_payment_info', ecommerce: Object.assign( {}, config.cart, { payment_type: method.value } ) } );
				} );
			} );
		}
		return;
	}
	const data = window.wp && window.wp.data;
	if ( ! data ) {
		return;
	}
	const cartStore = data.select( 'wc/store/cart' );
	const checkoutStore = data.select( 'wc/store/checkout' );
	const paymentStore = data.select( 'wc/store/payment' );
	const validationStore = data.select( 'wc/store/validation' );
	if ( ! cartStore || ! cartStore.getCartData ) {
		return;
	}
	let viewed = false;
	let initialized = false;
	let addConfirmed = false;
	const pendingAdds = new Map();
	let shippingPending = false;
	let lastAddress = '';
	let lastShipping = '';
	let paymentRecorded = false;
	document.body.addEventListener( 'wc-blocks_added_to_cart', function ( event ) {
		if ( hasConsent() && initialized && event.detail && event.detail.preserveCartData === true ) {
			addConfirmed = true;
			observeCart();
		}
	} );

	function addressSignature( cart ) {
		// 仅在内存比较运费所需地址，绝不写入事件、日志或浏览器存储。
		return JSON.stringify( [ 'country', 'state', 'postcode', 'city', 'address_1', 'address_2' ].map( function ( key ) {
			return ( cart.shippingAddress || {} )[ key ] || '';
		} ) );
	}

	function cartSnapshot( cart ) {
		const totals = cart.totals;
		if ( ! totals || ! totals.currency_code || ! Array.isArray( cart.items ) ) {
			return null;
		}
		const minor = Number( totals.currency_minor_unit );
		if ( ! Number.isInteger( minor ) || minor < 0 || minor > 6 ) {
			return null;
		}
		const divisor = Math.pow( 10, minor );
		let value = 0;
		const items = cart.items.map( function ( item ) {
			const quantity = Number( item.quantity );
			const line = Number( item.totals && item.totals.line_total ) / divisor;
			if ( ! item.id || ! Number.isFinite( quantity ) || quantity <= 0 || ! Number.isFinite( line ) || line < 0 ) {
				return null;
			}
			value += Number( item.totals.line_total );
			const result = { item_id: String( item.id ), item_name: item.name, quantity: quantity, price: line / quantity };
			if ( item.variation && item.variation.length ) {
				result.item_variant = item.variation.map( function ( variation ) {
					return variation.attribute + ': ' + variation.value;
				} ).join( ', ' );
			}
			return result;
		} );
		if ( items.some( function ( item ) { return ! item; } ) ) {
			return null;
		}
		// 只选择商品和金额字段；地址、客户、支付数据及任意 extensions 不向外展开。
		return { currency: totals.currency_code, value: value / divisor, items: items };
	}

	function observeCart() {
		if ( ! hasConsent() ) {
			// 未同意期间不积压分析状态；重新同意后只观察当前状态，不回放旧加购或提交。
			pendingAdds.clear();
			addConfirmed = false;
			shippingPending = false;
			initialized = false;
			return;
		}
		const cart = cartStore.getCartData();
		const extension = cart.extensions && cart.extensions[ 'dentall-measurement' ];
		const events = extension && Array.isArray( extension.events ) ? extension.events : [];
		const address = addressSignature( cart );
		const busy = cartStore.isCartDataStale() || cartStore.isCustomerDataUpdating() || cartStore.isShippingRateBeingSelected() || cartStore.hasPendingItemsOperations();
		if ( ! initialized ) {
			// Woo 会恢复 localStorage 购物车；首次初始化及此前的旧加购不能重播。
			events.forEach( function ( event ) {
				if ( event.id ) {
					seen.add( event.id );
				}
			} );
			if ( ! cartStore.hasFinishedResolution( 'getCartData', [] ) || busy ) {
				return;
			}
			initialized = true;
			lastAddress = address;
			paymentRecorded = Boolean( checkoutStore && checkoutStore.isProcessing() );
		} else {
			events.forEach( function ( event ) {
				if ( event.id && ! seen.has( event.id ) ) {
					pendingAdds.set( event.id, event );
				}
			} );
			if ( addConfirmed && pendingAdds.size ) {
				pendingAdds.forEach( emit );
				pendingAdds.clear();
				addConfirmed = false;
			}
		}
		if ( cartStore.isShippingRateBeingSelected() || ( cartStore.isCustomerDataUpdating() && address !== lastAddress ) ) {
			shippingPending = true;
		}
		if ( busy ) {
			return;
		}
		const ecommerce = cartSnapshot( cart );
		if ( ! ecommerce || ! ecommerce.items.length ) {
			return;
		}
		if ( ! viewed && ( config.page === 'cart' || config.page === 'checkout' ) ) {
			viewed = true;
			emit( { event: config.page === 'cart' ? 'view_cart' : 'begin_checkout', ecommerce: ecommerce } );
		}
		if ( config.page !== 'checkout' ) {
			return;
		}
		const shipping = ( cart.shippingRates || [] ).flatMap( function ( pack ) {
			return ( pack.shipping_rates || [] ).filter( function ( rate ) { return rate.selected; } );
		} );
		const signature = address + '|' + shipping.map( function ( rate ) { return rate.rate_id; } ).join( '|' );
		if ( shippingPending ) {
			shippingPending = false;
			if ( cart.needsShipping && cart.hasCalculatedShipping && shipping.length && signature !== lastShipping && ! ( cart.errors || [] ).length && ! cartStore.getCartErrors().length ) {
				lastAddress = address;
				lastShipping = signature;
				emit( { event: 'add_shipping_info', ecommerce: Object.assign( {}, ecommerce, { shipping_tier: shipping.map( function ( rate ) { return rate.name; } ).join( ', ' ) } ) } );
			}
		}
		// processing会先于原生校验短暂出现；等字段、运费和支付均可提交再记录。
		const shippingReady = ! cart.needsShipping || ( cart.hasCalculatedShipping && ( cart.shippingRates || [] ).length > 0 && cart.shippingRates.every( function ( pack ) {
			return ( pack.shipping_rates || [] ).some( function ( rate ) { return rate.selected; } );
		} ) );
		const processing = checkoutStore && checkoutStore.isProcessing();
		if ( ! processing ) {
			paymentRecorded = false;
		}
		const submitting = processing && cart.needsPayment && paymentStore && validationStore
			&& ! checkoutStore.hasError() && ! checkoutStore.isCalculating() && paymentStore.isPaymentReady() && ! paymentStore.hasPaymentError()
			&& ! validationStore.hasValidationErrors() && shippingReady && ! ( cart.errors || [] ).length && ! cartStore.getCartErrors().length;
		if ( submitting && ! paymentRecorded ) {
			const method = paymentStore.getActivePaymentMethod();
			if ( method ) {
				emit( { event: 'add_payment_info', ecommerce: Object.assign( {}, ecommerce, { payment_type: method } ) } );
				paymentRecorded = true;
			}
		}
	}
	document.addEventListener( 'dentall_measurement_consent', observeCart );
	data.subscribe( observeCart );
	observeCart();
}() );
