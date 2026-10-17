<?php
/**
 * 人工报价与WooCommerce PayPal Payments原生入口的适配。
 *
 * 网关可能在payment_complete之前已扣款，必须在创建及外部收款前复核。
 * 本模块只校验，不发送请求、不改订单；异常交给网关原生错误处理。
 */

defined( 'ABSPATH' ) || exit;

/**
 * 复用报价合同核验网关所处理的实体订单。
 *
 * @param WC_Order|null $order         订单。
 * @param bool          $allow_on_hold 是否正在处理既有付款。
 * @return void
 * @throws RuntimeException 报价不能进入网关。
 */
function dentall_core_assert_paypal_shipping_quote( $order, $allow_on_hold = false ) {
	if ( ! $order instanceof WC_Order ) {
		throw new RuntimeException( __( 'This payment cannot be matched to an order. Contact us for assistance.', 'dentall-core' ) );
	}

	if ( ! dentall_core_is_shipping_quote_candidate( $order ) && ! dentall_core_order_contains_shipping_products( $order ) ) {
		return;
	}

	$reason = dentall_core_is_shipping_quote_candidate( $order )
		? dentall_core_get_shipping_quote_block_reason( $order, null, $allow_on_hold )
		: 'not_issued';

	if ( '' !== $reason ) {
		/* Approved Webhook捕获的是网关自己的异常子类；纯合同测试无需加载网关。 */
		$exception_class = class_exists( 'WooCommerce\PayPalCommerce\ApiClient\Exception\RuntimeException' )
			? 'WooCommerce\PayPalCommerce\ApiClient\Exception\RuntimeException' : RuntimeException::class;
		throw new $exception_class( dentall_core_get_shipping_quote_block_message( $reason ) );
	}
}

/**
 * 在PayPal创建请求中先验证原生密钥/付款权限，再读取报价有效性。
 *
 * 该Hook位于插件nonce验证之后、原生pay-now权限检查之前；无权请求不泄露
 * 报价状态，也不能按可枚举的订单ID触发取消或其他持久化变更。
 *
 * @param array $data PayPal Payments已经解析的请求。
 * @return void
 * @throws RuntimeException 请求无权或报价无效。
 */
function dentall_core_guard_paypal_quote_creation( $data ) {
	if ( 'pay-now' !== ( $data['context'] ?? '' ) ) {
		if ( dentall_core_cart_requires_shipping_quote() ) {
			throw new RuntimeException( dentall_core_get_shipping_quote_block_message( 'not_issued' ) );
		}
		return;
	}

	$id       = $data['order_id'] ?? null;
	$order_id = ( is_int( $id ) && $id > 0 ) || ( is_string( $id ) && 1 === preg_match( '/^[1-9][0-9]*$/D', $id ) ) ? (int) $id : 0;
	$order    = wc_get_order( $order_id );
	$key      = $data['order_key'] ?? null;

	if ( ! $order instanceof WC_Order || ! is_string( $key ) || ! $order->key_is_valid( $key ) || ! current_user_can( 'pay_for_order', $order_id ) ) {
		throw new RuntimeException( __( 'You cannot pay for this order. Contact us for assistance.', 'dentall-core' ) );
	}

	dentall_core_assert_paypal_shipping_quote( $order );
}
add_action( 'woocommerce_paypal_payments_create_order_request_started', 'dentall_core_guard_paypal_quote_creation', 20 );

/**
 * 网关返回与已验签Webhook都会在处理订单前调用此Filter。
 *
 * @param bool     $process 既有处理许可。
 * @param mixed    $gateway 网关；Webhook路径为null。
 * @param WC_Order $order   插件已解析的订单。
 * @return bool
 * @throws RuntimeException 报价无效。
 */
function dentall_core_guard_paypal_quote_processing( $process, $gateway, $order ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
	if ( $process ) {
		/* 重复批准通知保留原生成功响应，但不再进入收款；创建与捕获入口仍拒绝旧单。 */
		if ( dentall_core_is_shipping_quote_candidate( $order ) && dentall_core_shipping_quote_order_has_status( $order, array( 'processing', 'completed' ) ) ) {
			return false;
		}
		dentall_core_assert_paypal_shipping_quote( $order, true );
	}
	return $process;
}
add_filter( 'woocommerce_paypal_payments_before_order_process', 'dentall_core_guard_paypal_quote_processing', PHP_INT_MAX, 3 );

/**
 * 在真正的CAPTURE请求前再次读取订单，覆盖客户停留或网关调用期间到期的情况。
 *
 * Purchase Unit的custom_id由插件原生工厂写为Woo订单ID；不从浏览器参数推断。
 * 这里只覆盖OrderEndpoint::capture，授权后捕获及其他付款方式须另行验证。
 *
 * @param object $paypal_order PayPal Payments解析的PayPal订单。
 * @return void
 * @throws RuntimeException 无法对应订单或报价已失效。
 */
function dentall_core_guard_paypal_quote_capture( $paypal_order ) {
	$units = $paypal_order->purchase_units();
	if ( empty( $units ) ) {
		dentall_core_assert_paypal_shipping_quote( null );
	}
	foreach ( $units as $unit ) {
		$order_id = $unit->custom_id();
		$order    = is_string( $order_id ) && 1 === preg_match( '/^[1-9][0-9]*$/D', $order_id )
			? wc_get_order( (int) $order_id ) : false;
		dentall_core_assert_paypal_shipping_quote( $order, true );
	}
}
add_action( 'woocommerce_paypal_payments_before_capture_order', 'dentall_core_guard_paypal_quote_capture' );
