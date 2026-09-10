<?php
/**
 * 标准商品的人工运费邮件报价与结账边界。
 */

defined( 'ABSPATH' ) || exit;

const DENTALL_SHIPPING_QUOTE_EMAIL_OPTION = 'dentall_shipping_quote_email';

/**
 * 取得已验证的报价收件邮箱。
 *
 * 空值表示尚未完成环境配置；不得静默回退到站点管理员邮箱。
 *
 * @return string
 */
function dentall_core_get_shipping_quote_email() {
	$value = get_option( DENTALL_SHIPPING_QUOTE_EMAIL_OPTION, '' );

	if ( ! is_string( $value ) ) {
		return '';
	}

	$email = sanitize_email( $value );

	return $email === $value && is_email( $email ) ? $email : '';
}

/**
 * 在WooCommerce Shipping设置中增加最小报价配置区。
 *
 * @param array<string, string> $sections Shipping设置区段。
 * @return array<string, string>
 */
function dentall_core_add_shipping_quote_section( $sections ) {
	$sections['dentall_shipping_quote'] = __( 'Manual shipping quote', 'dentall-core' );

	return $sections;
}
add_filter( 'woocommerce_get_sections_shipping', 'dentall_core_add_shipping_quote_section' );

/**
 * 输出人工报价设置。
 *
 * @param array<int, array<string, mixed>> $settings 既有设置。
 * @param string                           $section  当前区段。
 * @return array<int, array<string, mixed>>
 */
function dentall_core_get_shipping_quote_settings( $settings, $section ) {
	if ( 'dentall_shipping_quote' !== $section ) {
		return $settings;
	}

	return array(
		array(
			'title' => __( 'Manual shipping quote', 'dentall-core' ),
			'desc'  => __( 'Customers request shipping by email. Staff confirm Shipping, Tax and Fee on a pending WooCommerce order before sending its payment link.', 'dentall-core' ),
			'id'    => 'dentall_shipping_quote_options',
			'type'  => 'title',
		),
		array(
			'title'    => __( 'Quote recipient email', 'dentall-core' ),
			'desc'     => __( 'Use a company-controlled inbox. Leaving this empty safely disables the cart email link; the site administrator email is never used as a fallback.', 'dentall-core' ),
			'id'       => DENTALL_SHIPPING_QUOTE_EMAIL_OPTION,
			'type'     => 'email',
			'css'      => 'min-width: 22rem;',
			'default'  => '',
			'desc_tip' => false,
		),
		array(
			'id'   => 'dentall_shipping_quote_options',
			'type' => 'sectionend',
		),
	);
}
add_filter( 'woocommerce_get_settings_shipping', 'dentall_core_get_shipping_quote_settings', 10, 2 );

/**
 * 拒绝保存格式错误的报价邮箱，同时允许显式清空配置。
 *
 * @param mixed                    $value     WooCommerce预处理后的值。
 * @param array<string, mixed>     $option    设置定义。
 * @param mixed                    $raw_value 原始提交值。
 * @return string
 */
function dentall_core_sanitize_shipping_quote_email( $value, $option, $raw_value ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	if ( ! is_string( $raw_value ) ) {
		return dentall_core_get_shipping_quote_email();
	}

	$raw_email = trim( $raw_value );

	if ( '' === $raw_email ) {
		return '';
	}

	$email = sanitize_email( $raw_email );

	if ( $email === $raw_email && is_email( $email ) ) {
		return $email;
	}

	if ( class_exists( 'WC_Admin_Settings' ) ) {
		WC_Admin_Settings::add_error( __( 'Enter a valid shipping quote email address.', 'dentall-core' ) );
	}

	return dentall_core_get_shipping_quote_email();
}
add_filter(
	'woocommerce_admin_settings_sanitize_option_' . DENTALL_SHIPPING_QUOTE_EMAIL_OPTION,
	'dentall_core_sanitize_shipping_quote_email',
	10,
	3
);

/**
 * 判断当前购物车是否必须先完成人工运费报价。
 *
 * @param WC_Cart|null $cart 购物车；缺省时使用当前WooCommerce会话。
 * @return bool
 */
function dentall_core_cart_requires_shipping_quote( $cart = null ) {
	if ( null === $cart && function_exists( 'WC' ) ) {
		$cart = WC()->cart;
	}

	if ( ! $cart instanceof WC_Cart || $cart->is_empty() ) {
		return false;
	}

	foreach ( $cart->get_cart() as $cart_item ) {
		$product = isset( $cart_item['data'] ) ? $cart_item['data'] : null;

		if ( $product instanceof WC_Product && $product->needs_shipping() ) {
			return true;
		}
	}

	return false;
}

/**
 * 声明Cart Store API中的人工报价状态字段。
 *
 * WooCommerce在没有任何配送方式时会让WC_Cart::needs_shipping()返回false；
 * 这里按商品自身配送属性输出独立事实，保证人工报价模式仍可动态识别实体商品。
 *
 * @return array<string, array<string, mixed>>
 */
function dentall_core_get_shipping_quote_store_api_schema() {
	return array(
		'required' => array(
			'description' => __( 'Whether this cart must receive a manual shipping quote before checkout.', 'dentall-core' ),
			'type'        => 'boolean',
			'readonly'    => true,
		),
	);
}

/**
 * 输出当前购物车的人工报价状态。
 *
 * @return array<string, bool>
 */
function dentall_core_get_shipping_quote_store_api_data() {
	return array(
		'required' => dentall_core_cart_requires_shipping_quote(),
	);
}

/**
 * 使用WooCommerce公开扩展点登记Cart Store API字段。
 *
 * @return void
 */
function dentall_core_register_shipping_quote_store_api_data() {
	if ( ! function_exists( 'woocommerce_store_api_register_endpoint_data' ) ) {
		return;
	}

	woocommerce_store_api_register_endpoint_data(
		array(
			'endpoint'        => 'cart',
			'namespace'       => 'dentall/shipping-quote',
			'schema_callback' => 'dentall_core_get_shipping_quote_store_api_schema',
			'data_callback'   => 'dentall_core_get_shipping_quote_store_api_data',
		)
	);
}
add_action( 'woocommerce_blocks_loaded', 'dentall_core_register_shipping_quote_store_api_data' );

/**
 * 阻止访客通过普通Checkout页面绕过人工报价。
 *
 * 人工订单付款链接和订单完成页属于既有订单流程，必须保持可用。
 *
 * @return void
 */
function dentall_core_redirect_unquoted_checkout() {
	if (
		is_admin()
		|| wp_doing_ajax()
		|| ! function_exists( 'is_checkout' )
		|| ! is_checkout()
		|| ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-pay' ) )
		|| ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) )
		|| ! dentall_core_cart_requires_shipping_quote()
	) {
		return;
	}

	wc_add_notice(
		__( 'Shipping must be confirmed by email before this order can proceed to payment.', 'dentall-core' ),
		'notice'
	);

	wp_safe_redirect( wc_get_cart_url() );
	exit;
}
add_action( 'template_redirect', 'dentall_core_redirect_unquoted_checkout', 20 );

/**
 * 阻止经典结账AJAX直接提交需配送购物车。
 *
 * @return void
 */
function dentall_core_block_unquoted_classic_checkout() {
	if ( dentall_core_cart_requires_shipping_quote() ) {
		wc_add_notice(
			__( 'Shipping must be confirmed by email before this order can proceed to payment.', 'dentall-core' ),
			'error'
		);
	}
}
add_action( 'woocommerce_checkout_process', 'dentall_core_block_unquoted_classic_checkout', 1 );

/**
 * 在REST回调执行期间记录实际子请求路由。
 *
 * WordPress Batch会在同一HTTP请求内派发子请求，外层REQUEST_URI无法代表
 * 正在执行的Checkout路由，因此用栈保留嵌套路由，并在回调结束后恢复。
 *
 * @param mixed           $response 当前REST响应。
 * @param array<mixed>    $handler  当前路由处理器。
 * @param WP_REST_Request $request  当前REST请求。
 * @return mixed
 */
function dentall_core_track_rest_route_before_callback( $response, $handler, $request ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	if ( $request instanceof WP_REST_Request ) {
		$route = $request->get_route();

		if ( is_string( $route ) ) {
			if ( ! isset( $GLOBALS['dentall_core_rest_route_stack'] ) || ! is_array( $GLOBALS['dentall_core_rest_route_stack'] ) ) {
				$GLOBALS['dentall_core_rest_route_stack'] = array();
			}

			$GLOBALS['dentall_core_rest_route_stack'][] = $route;
		}
	}

	return $response;
}
add_filter( 'rest_request_before_callbacks', 'dentall_core_track_rest_route_before_callback', 10, 3 );

/**
 * 清理当前REST子请求路由。
 *
 * @param mixed           $response 当前REST响应。
 * @param array<mixed>    $handler  当前路由处理器。
 * @param WP_REST_Request $request  当前REST请求。
 * @return mixed
 */
function dentall_core_track_rest_route_after_callback( $response, $handler, $request ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	if (
		$request instanceof WP_REST_Request
		&& isset( $GLOBALS['dentall_core_rest_route_stack'] )
		&& is_array( $GLOBALS['dentall_core_rest_route_stack'] )
	) {
		array_pop( $GLOBALS['dentall_core_rest_route_stack'] );
	}

	return $response;
}
add_filter( 'rest_request_after_callbacks', 'dentall_core_track_rest_route_after_callback', 10, 3 );

/**
 * 判断待付款订单是否包含需配送或已无法解析的商品。
 *
 * 无法解析历史商品时采用安全失败，避免商品删除后丢失人工报价边界。
 *
 * @param WC_Order|null $order 订单。
 * @return bool
 */
function dentall_core_order_requires_shipping_quote( $order ) {
	if ( ! $order instanceof WC_Order || ! $order->needs_payment() ) {
		return false;
	}

	foreach ( $order->get_items( 'line_item' ) as $item ) {
		$product = is_callable( array( $item, 'get_product' ) ) ? $item->get_product() : null;

		if ( ! $product instanceof WC_Product || $product->needs_shipping() ) {
			return true;
		}
	}

	return false;
}

/**
 * 判断订单是否已包含人工确认后的原生Shipping明细。
 *
 * @param WC_Order|null $order 订单。
 * @return bool
 */
function dentall_core_order_has_quoted_shipping( $order ) {
	return dentall_core_order_requires_shipping_quote( $order )
		&& ! empty( $order->get_items( 'shipping' ) );
}

/**
 * 比较付款请求与人工报价订单中的配送地址。
 *
 * @param WP_REST_Request $request 付款请求。
 * @param WC_Order        $order   已报价订单。
 * @return bool
 */
function dentall_core_order_shipping_address_matches_request( $request, $order ) {
	$billing  = $request->get_param( 'billing_address' );
	$shipping = $request->get_param( 'shipping_address' );

	if ( ! is_array( $shipping ) ) {
		$shipping = is_array( $billing ) ? $billing : array();
	}

	$fields = array( 'company', 'country', 'state', 'postcode', 'city', 'address_1', 'address_2' );

	foreach ( $fields as $field ) {
		$request_value = isset( $shipping[ $field ] ) && is_scalar( $shipping[ $field ] )
			? sanitize_text_field( (string) $shipping[ $field ] )
			: '';
		$getter        = 'get_shipping_' . $field;
		$order_value   = is_callable( array( $order, $getter ) ) ? sanitize_text_field( (string) $order->{$getter}() ) : '';

		if ( $request_value !== $order_value ) {
			return false;
		}
	}

	return true;
}

/**
 * 当Woo按账单地址计税时，锁定会决定税额的账单地域字段。
 *
 * 按配送地址计税已由完整配送地址锁覆盖；按店铺基准地址计税不依赖客户地址。
 *
 * @param WP_REST_Request $request 付款请求。
 * @param WC_Order        $order   已报价订单。
 * @return bool
 */
function dentall_core_order_tax_address_matches_request( $request, $order ) {
	if ( 'billing' !== get_option( 'woocommerce_tax_based_on', 'shipping' ) ) {
		return true;
	}

	$billing = $request->get_param( 'billing_address' );

	if ( ! is_array( $billing ) ) {
		return false;
	}

	foreach ( array( 'country', 'state', 'postcode', 'city' ) as $field ) {
		$request_value = isset( $billing[ $field ] ) && is_scalar( $billing[ $field ] )
			? sanitize_text_field( (string) $billing[ $field ] )
			: '';
		$getter        = 'get_billing_' . $field;
		$order_value   = is_callable( array( $order, $getter ) ) ? sanitize_text_field( (string) $order->{$getter}() ) : '';

		if ( $request_value !== $order_value ) {
			return false;
		}
	}

	return true;
}

/**
 * 锁定已人工报价订单的配送地址，避免客户用旧运费改送新目的地。
 *
 * 该Filter在REST权限检查通过后、Woo更新订单前运行；经典order-pay页面不受影响。
 *
 * @param mixed           $dispatch_result 既有短路结果。
 * @param WP_REST_Request $request         当前REST请求。
 * @param string          $route           匹配到的路由表达式。
 * @param array<mixed>    $handler         当前路由处理器。
 * @return mixed
 */
function dentall_core_lock_quoted_order_shipping_address( $dispatch_result, $request, $route, $handler ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	if ( null !== $dispatch_result || ! $request instanceof WP_REST_Request || 'POST' !== $request->get_method() ) {
		return $dispatch_result;
	}

	$request_route = $request->get_route();

	if ( ! is_string( $request_route ) || 1 !== preg_match( '#^/wc/store(?:/v[0-9]+)?/checkout/([0-9]+)$#i', $request_route, $matches ) ) {
		return $dispatch_result;
	}

	$order = wc_get_order( absint( $matches[1] ) );

	if ( ! dentall_core_order_requires_shipping_quote( $order ) ) {
		return $dispatch_result;
	}

	if ( ! dentall_core_order_has_quoted_shipping( $order ) ) {
		return new WP_Error(
			'dentall_shipping_quote_required',
			__( 'Shipping must be confirmed before this order can proceed to payment.', 'dentall-core' ),
			array( 'status' => 409 )
		);
	}

	if (
		dentall_core_order_shipping_address_matches_request( $request, $order )
		&& dentall_core_order_tax_address_matches_request( $request, $order )
	) {
		return $dispatch_result;
	}

	return new WP_Error(
		'dentall_shipping_quote_address_locked',
		__( 'The shipping and tax location is locked to the approved quote. Contact us for a revised quote before payment.', 'dentall-core' ),
		array( 'status' => 409 )
	);
}
add_filter( 'rest_dispatch_request', 'dentall_core_lock_quoted_order_shipping_address', 10, 4 );

/**
 * 阻止经典order-pay为缺少Shipping明细的实体订单收款。
 *
 * @param WC_Order $order 待付款订单。
 * @return void
 */
function dentall_core_block_unquoted_order_pay_action( $order ) {
	if ( ! dentall_core_order_requires_shipping_quote( $order ) || dentall_core_order_has_quoted_shipping( $order ) ) {
		return;
	}

	wc_add_notice(
		__( 'Shipping must be confirmed before this order can proceed to payment.', 'dentall-core' ),
		'error'
	);

	wp_safe_redirect( $order->get_checkout_payment_url() );
	exit;
}
add_action( 'woocommerce_before_pay_action', 'dentall_core_block_unquoted_order_pay_action', 1 );

/**
 * 判断当前REST请求是否为会从购物车创建订单的结账端点。
 *
 * @return bool
 */
function dentall_core_is_cart_checkout_store_api_request() {
	if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
		return false;
	}

	$route = '';

	if ( ! empty( $GLOBALS['dentall_core_rest_route_stack'] ) && is_array( $GLOBALS['dentall_core_rest_route_stack'] ) ) {
		$current_route = end( $GLOBALS['dentall_core_rest_route_stack'] );
		$route         = is_string( $current_route ) ? $current_route : '';
	} elseif ( isset( $GLOBALS['wp']->query_vars['rest_route'] ) && is_string( $GLOBALS['wp']->query_vars['rest_route'] ) ) {
		$route = $GLOBALS['wp']->query_vars['rest_route'];
	} elseif ( isset( $_GET['rest_route'] ) && is_string( $_GET['rest_route'] ) ) {
		$route = wp_unslash( $_GET['rest_route'] );
	} elseif ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$route = (string) wp_parse_url(
			sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ),
			PHP_URL_PATH
		);
	}

	$route = untrailingslashit( '/' . ltrim( sanitize_text_field( $route ), '/' ) );

	return 1 === preg_match(
		'#^/wc/(?:store(?:/v[0-9]+)?/checkout|agentic/v[0-9]+/checkout_sessions/[a-zA-Z0-9._-]+/complete)$#i',
		$route
	);
}

/**
 * 在Store API创建草稿订单之前阻止未报价购物车提交。
 *
 * @param WP_Error $errors Store API购物车错误容器。
 * @param WC_Cart  $cart   当前购物车。
 * @return void
 */
function dentall_core_block_unquoted_store_api_checkout( $errors, $cart ) {
	if (
		! $errors instanceof WP_Error
		|| ! dentall_core_is_cart_checkout_store_api_request()
		|| ! dentall_core_cart_requires_shipping_quote( $cart )
	) {
		return;
	}

	$errors->add(
		'dentall_shipping_quote_required',
		__( 'Shipping must be confirmed by email before this order can proceed to payment.', 'dentall-core' )
	);
}
add_action( 'woocommerce_store_api_cart_errors', 'dentall_core_block_unquoted_store_api_checkout', 10, 2 );
