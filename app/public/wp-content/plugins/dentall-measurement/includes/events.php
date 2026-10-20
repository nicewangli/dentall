<?php
/** WooCommerce 页面与成功加购的数据；浏览器只接收明确允许的字段。 */
defined( 'ABSPATH' ) || exit;

function dentall_measurement_is_public_request() {
	return ! is_admin() && ! wp_doing_ajax() && ! is_preview()
		&& ! ( defined( 'REST_REQUEST' ) && REST_REQUEST )
		&& ! current_user_can( 'manage_woocommerce' ) && ! current_user_can( 'manage_options' );
}

function dentall_measurement_item( WC_Product $product, $quantity = 1, $line_total = null ) {
	$item = array(
		'item_id'   => (string) $product->get_id(),
		'item_name' => wp_strip_all_tags( $product->get_name() ),
		'quantity'  => (float) $quantity,
	);
	if ( null !== $line_total && $quantity > 0 ) {
		$item['price'] = (float) wc_format_decimal( $line_total / $quantity, wc_get_price_decimals() + 4 );
	} elseif ( '' !== $product->get_price() && ! $product->is_type( 'variable' ) ) {
		$item['price'] = (float) wc_get_price_excluding_tax( $product );
	}
	return $item;
}

function dentall_measurement_cart() {
	$data = array( 'currency' => get_woocommerce_currency(), 'value' => 0, 'items' => array() );
	if ( ! WC()->cart ) {
		return $data;
	}
	$total = 0;
	foreach ( WC()->cart->get_cart() as $line ) {
		$data['items'][] = dentall_measurement_item( $line['data'], $line['quantity'], $line['line_total'] );
		$total         += wc_add_number_precision( $line['line_total'] );
	}
	$data['value'] = (float) wc_remove_number_precision( $total );
	return $data;
}

function dentall_measurement_loop_item() {
	global $product;
	if ( ! wp_script_is( 'dentall-measurement', 'enqueued' ) || ! dentall_measurement_is_public_request() || ! $product instanceof WC_Product || post_password_required( $product->get_id() ) ) {
		return;
	}
	printf(
		'<span hidden data-dentall-measurement-item="%s" data-dentall-measurement-list="%s"></span>',
		esc_attr( wp_json_encode( dentall_measurement_item( $product ) ) ),
		esc_attr( wc_get_loop_prop( 'name' ) ?: 'catalog' )
	);
}
add_action( 'woocommerce_before_shop_loop_item', 'dentall_measurement_loop_item', 5 );

function dentall_measurement_cart_add_event( $line, $quantity ) {
	$item  = dentall_measurement_item( $line['data'], $line['quantity'], $line['line_total'] );
	$item['quantity'] = (float) $quantity;
	return array(
		'id'        => wp_generate_uuid4(),
		'event'     => 'add_to_cart',
		'ecommerce' => array(
			'currency' => get_woocommerce_currency(),
			'value'    => (float) wc_format_decimal( $item['price'] * $quantity, wc_get_price_decimals() ),
			'items'    => array( $item ),
		),
	);
}

function dentall_measurement_capture_cart_add( $key, $product_id, $quantity ) {
	if ( ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || current_user_can( 'manage_woocommerce' ) || current_user_can( 'manage_options' ) || ! WC()->cart ) {
		return;
	}
	$line = WC()->cart->get_cart_item( $key );
	if ( ! $line || $quantity <= 0 || $line['quantity'] <= 0 ) {
		return;
	}
	$event = dentall_measurement_cart_add_event( $line, $quantity );
	if ( wp_doing_ajax() ) {
		$GLOBALS['dentall_measurement_cart_events'][] = $event;
	} elseif ( WC()->session ) {
		$pending   = array_slice( (array) WC()->session->get( 'dentall_measurement_add', array() ), -19 );
		$pending[] = $event;
		WC()->session->set( 'dentall_measurement_add', $pending );
	}
}
add_action( 'woocommerce_add_to_cart', 'dentall_measurement_capture_cart_add', 30, 3 );

function dentall_measurement_cart_fragments( $fragments ) {
	if ( ! empty( $GLOBALS['dentall_measurement_cart_events'] ) ) {
		$fragments['span.dentall-measurement-cart-event'] = sprintf(
			'<span hidden class="dentall-measurement-cart-event" data-dentall-measurement-event="%s"></span>',
			esc_attr( wp_json_encode( $GLOBALS['dentall_measurement_cart_events'] ) )
		);
	}
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'dentall_measurement_cart_fragments' );

function dentall_measurement_before_store_add( $data, $request ) {
	// 公共Filter位于Store API授权及Cart加载之后；只观察本次请求，不改入参。
	if ( '/wc/store/v1/cart/add-item' === $request->get_route() && ! current_user_can( 'manage_woocommerce' ) && ! current_user_can( 'manage_options' ) ) {
		$quantities = array();
		foreach ( WC()->cart->get_cart() as $key => $line ) {
			$quantities[ $key ] = (float) $line['quantity'];
		}
		$GLOBALS['dentall_measurement_store_add'] = array( 'request' => spl_object_id( $request ), 'quantities' => $quantities );
	}
	return $data;
}
add_filter( 'woocommerce_store_api_add_to_cart_data', 'dentall_measurement_before_store_add', 100, 2 );

function dentall_measurement_after_store_add( $response, $handler, $request ) {
	$before = $GLOBALS['dentall_measurement_store_add'] ?? null;
	if ( ! $before || $before['request'] !== spl_object_id( $request ) || '/wc/store/v1/cart/add-item' !== $request->get_route()
		|| 'POST' !== $request->get_method() || ! $response instanceof WP_REST_Response || 201 !== $response->get_status() ) {
		return $response;
	}
	$events = array();
	foreach ( WC()->cart->get_cart() as $key => $line ) {
		$added = (float) $line['quantity'] - ( $before['quantities'][ $key ] ?? 0 );
		if ( $added > 0 ) {
			$events[] = dentall_measurement_cart_add_event( $line, $added );
		}
	}
	// 仅成功add-item响应携带真实增量；GET、失败、数量编辑均不产生事件。
	$data = $response->get_data();
	$extensions = (array) ( $data['extensions'] ?? array() );
	$extensions['dentall-measurement'] = array( 'events' => $events );
	$data['extensions'] = (object) $extensions;
	$response->set_data( $data );
	return $response;
}
add_filter( 'rest_request_after_callbacks', 'dentall_measurement_after_store_add', 10, 3 );

function dentall_measurement_assets() {
	if ( ! dentall_measurement_is_public_request() || ! ( is_front_page() || is_shop() || is_product_taxonomy() || is_product() || is_search() || is_cart() || is_checkout() ) ) {
		return;
	}
	$dependencies = array( 'jquery' );
	if ( ( is_cart() && has_block( 'woocommerce/cart' ) ) || ( is_checkout() && ! is_wc_endpoint_url() && has_block( 'woocommerce/checkout' ) ) ) {
		$dependencies = array( 'jquery', 'wp-data', 'wc-blocks-data-store' );
	}
	wp_enqueue_script( 'dentall-measurement', plugins_url( 'assets/events.js', DENTALL_MEASUREMENT_FILE ), $dependencies, DENTALL_MEASUREMENT_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'dentall_measurement_assets', 60 );

function dentall_measurement_page_data() {
	if ( ! wp_script_is( 'dentall-measurement', 'enqueued' ) ) {
		return;
	}
	$page = is_product() ? 'product' : 'catalog';
	if ( is_cart() ) {
		$page = 'cart';
	} elseif ( is_checkout() ) {
		$page = is_order_received_page() ? 'order-received' : ( is_wc_endpoint_url( 'order-pay' ) ? 'order-pay' : 'checkout' );
	} elseif ( is_search() ) {
		$page = 'search';
	}
	$events = $GLOBALS['dentall_measurement_events'] ?? array();
	if ( WC()->session ) {
		$events = array_merge( $events, (array) WC()->session->get( 'dentall_measurement_add', array() ) );
		WC()->session->__unset( 'dentall_measurement_add' );
	}
	$data = array( 'page' => $page, 'currency' => get_woocommerce_currency(), 'events' => $events, 'blocks' => false );
	if ( in_array( $page, array( 'cart', 'checkout' ), true ) ) {
		$data['blocks'] = has_block( 'woocommerce/' . $page );
		if ( ! $data['blocks'] ) {
			$data['cart'] = dentall_measurement_cart();
		}
	} elseif ( 'order-pay' === $page && ! empty( $GLOBALS['dentall_measurement_order_pay'] ) ) {
		$data['cart'] = $GLOBALS['dentall_measurement_order_pay'];
	}
	if ( 'product' === $page && ! post_password_required() ) {
		$product = wc_get_product( get_queried_object_id() );
		if ( $product && 'publish' === $product->get_status() ) {
			$data['product'] = dentall_measurement_item( $product );
		}
	}
	if ( 'search' === $page ) {
		global $wp_query;
		// 不把自由输入的查询词或含查询词URL放入测量数据，避免携带个人信息。
		$data['events'][] = array( 'event' => 'view_search_results', 'search_results_count' => (int) $wp_query->found_posts );
	}
	echo '<span hidden class="dentall-measurement-cart-event"></span>';
	// 仅运行时白名单数据经WordPress接口注入，静态逻辑留在独立JS文件。
	wp_add_inline_script( 'dentall-measurement', 'window.dentallMeasurement=' . wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . ';', 'before' );
}
add_action( 'wp_footer', 'dentall_measurement_page_data', 5 );
