<?php
/**
 * 人工报价与WooCommerce PayPal Payments原生入口的适配。
 *
 * 网关可能在payment_complete之前已扣款，必须在创建及外部收款前复核。
 * 创建与Capture前只校验；已验签完成通知的failed恢复会只读查PayPal，
 * 再交Woo原生API记账，不重新扣款。
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

/**
 * 将已验签通知绑定到当前报价及PayPal只读返回的唯一最终Capture。
 *
 * 不接受舍入后才相等的金额，不用通知中的custom_id单独证明订单归属。
 *
 * @return object|null PayPal原生Capture实体；不匹配时返回null。
 */
function dentall_core_get_matching_paypal_quote_capture( $order, $paypal_order, $resource ) {
	$units    = $paypal_order->purchase_units();
	$unit     = 1 === count( $units ) ? reset( $units ) : null;
	$payments = $unit ? $unit->payments() : null;
	$captures = $payments ? $payments->captures() : array();
	$capture  = 1 === count( $captures ) ? reset( $captures ) : null;
	$value    = $resource['amount']['value'] ?? null;
	$currency = $resource['amount']['currency_code'] ?? null;
	$decimals = wc_get_price_decimals();
	$pattern  = $decimals > 0 ? '/^[0-9]+(?:\.[0-9]{1,' . $decimals . '})?$/D' : '/^[0-9]+$/D';
	$raw_total = (string) $order->get_total( 'edit' );
	$total     = wc_format_decimal( $raw_total, $decimals );

	if ( ! $capture || (float) $total <= 0 || 'CAPTURE' !== $paypal_order->intent() || 'COMPLETED' !== $paypal_order->status()->name()
		|| (string) $order->get_id() !== $unit->custom_id()
		|| $paypal_order->id() !== $order->get_meta( '_ppcp_paypal_order_id', true, 'edit' )
		|| $paypal_order->id() !== ( $resource['supplementary_data']['related_ids']['order_id'] ?? null )
		|| (string) $order->get_id() !== ( $resource['custom_id'] ?? null )
		|| $capture->id() !== ( $resource['id'] ?? null ) || 'COMPLETED' !== ( $resource['status'] ?? null )
		|| ( isset( $resource['final_capture'] ) && true !== $resource['final_capture'] )
		|| 'COMPLETED' !== $capture->status()->name() || ! $capture->final_capture()
		|| ! is_string( $value ) || 1 !== preg_match( $pattern, $value )
		|| 1 !== preg_match( $pattern, $raw_total )
		|| $currency !== $order->get_currency( 'edit' ) || $currency !== $capture->amount()->currency_code()
		|| wc_format_decimal( $value, $decimals ) !== $total
		|| 1 !== preg_match( $pattern, $capture->amount()->value_str() )
		|| wc_format_decimal( $capture->amount()->value_str(), $decimals ) !== $total
		|| ( '' !== $capture->custom_id() && (string) $order->get_id() !== $capture->custom_id() )
	) {
		return null;
	}
	return $capture;
}

/**
 * 修复4.1.2完成通知跳过合法failed报价的恢复缺口，不重新Capture。
 *
 * WP在permission_callback成功后才运行此Filter；仅接受原生验签控制器。
 * 请求级MySQL命名锁不写订单内部表，也不声称锁住其他后台操作。
 *
 * @return mixed 原响应，或本次已核对的处理结果。
 */
function dentall_core_reconcile_failed_paypal_quote( $response, $request, $route, $handler ) {
	$callback   = $handler['callback'] ?? null;
	$permission = $handler['permission_callback'] ?? null;
	if ( null !== $response || ! is_array( $callback ) || ! is_array( $permission )
		|| ! ( ( $callback[0] ?? null ) instanceof \WooCommerce\PayPalCommerce\Webhooks\IncomingWebhookEndpoint )
		|| ( $permission[0] ?? null ) !== $callback[0]
		|| 'handle_request' !== ( $callback[1] ?? null ) || 'verify_request' !== ( $permission[1] ?? null )
		|| '/paypal/v1/incoming' !== $route
		|| ( defined( 'PAYPAL_WEBHOOK_REQUEST_VERIFICATION' ) && ! PAYPAL_WEBHOOK_REQUEST_VERIFICATION )
	) {
		return $response;
	}
	$data     = $request->get_json_params();
	$simulation = get_option( 'ppcp-webhook-simulation', array() );
	/* 原生模拟事件会跳过验签，且在handler内部才被截住；这里不能提前补记。 */
	if ( ! is_string( $data['id'] ?? null ) || '' === $data['id'] || $data['id'] === ( $simulation['id'] ?? null ) ) {
		return $response;
	}
	$resource = $data['resource'] ?? null;
	$order_id = is_array( $resource ) ? ( $resource['custom_id'] ?? null ) : null;
	if ( 'PAYMENT.CAPTURE.COMPLETED' !== ( $data['event_type'] ?? null ) || ! is_string( $order_id )
		|| 1 !== preg_match( '/^[1-9][0-9]*$/D', $order_id ) ) {
		return $response;
	}
	$order = wc_get_order( (int) $order_id );
	if ( ! dentall_core_is_shipping_quote_candidate( $order ) || ! dentall_core_shipping_quote_order_has_status( $order, 'failed' ) ) {
		return $response;
	}

	$error = new WP_Error( 'dentall_paypal_quote_reconciliation', __( 'This payment requires reconciliation. Do not pay again. Contact us for assistance.', 'dentall-core' ), array( 'status' => 409 ) );
	global $wpdb;
	$lock = 'dentall_capture_' . substr( hash( 'sha256', DB_NAME . ':' . $wpdb->prefix . ':' . $order_id ), 0, 40 );
	if ( '1' !== (string) $wpdb->get_var( $wpdb->prepare( 'SELECT GET_LOCK(%s, 0)', $lock ) ) ) {
		return new WP_Error( 'dentall_paypal_quote_busy', __( 'This payment is being reconciled. Do not pay again.', 'dentall-core' ), array( 'status' => 503 ) );
	}
	try {
		/* 绕过Factory的请求内对象缓存；目标HPOS datastore caching须保持关闭。 */
		if ( \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_datastore_cache_enabled() ) {
			return $error;
		}
		$order = new WC_Order( (int) $order_id );
		if ( ! dentall_core_shipping_quote_order_has_status( $order, 'failed' )
			|| 'ppcp-gateway' !== $order->get_payment_method( 'edit' ) || $order->get_date_paid( 'edit' )
			|| 'CAPTURE' !== $order->get_meta( '_ppcp_paypal_intent', true, 'edit' )
			|| (float) $order->get_total_refunded() > 0
			|| '' !== dentall_core_get_shipping_quote_block_reason( $order, null, true ) ) {
			return $error;
		}
		if ( (int) $order->get_meta( '_ppcp_processing', true, 'edit' ) >= time() ) {
			return new WP_Error( 'dentall_paypal_quote_busy', __( 'This payment is being reconciled. Do not pay again.', 'dentall-core' ), array( 'status' => 503 ) );
		}
		$paypal_id = $order->get_meta( '_ppcp_paypal_order_id', true, 'edit' );
		if ( ! is_string( $paypal_id ) || 1 !== preg_match( '/^[A-Z0-9]{1,64}$/D', $paypal_id ) ) {
			return $error;
		}
		/* 原生公开API只读核验；不调用capture、authorize或退款端点。 */
		$paypal_order = \WooCommerce\PayPalCommerce\Api\ppcp_get_paypal_order( $paypal_id );
		$order        = new WC_Order( (int) $order_id );
		$capture      = dentall_core_get_matching_paypal_quote_capture( $order, $paypal_order, $resource );
		if ( ! $capture || ! dentall_core_shipping_quote_order_has_status( $order, 'failed' )
			|| 'ppcp-gateway' !== $order->get_payment_method( 'edit' )
			|| 'CAPTURE' !== $order->get_meta( '_ppcp_paypal_intent', true, 'edit' )
			|| $order->get_date_paid( 'edit' ) || (float) $order->get_total_refunded() > 0
			|| '' !== dentall_core_get_shipping_quote_block_reason( $order, null, true )
			|| ( '' !== $order->get_transaction_id( 'edit' ) && $capture->id() !== $order->get_transaction_id( 'edit' ) ) ) {
			return $error;
		}
		/* 远端GET期间wpdb可能重连；副作用前确认本连接仍持锁，原生处理也未启动。 */
		if ( '1' !== (string) $wpdb->get_var( $wpdb->prepare( 'SELECT IS_USED_LOCK(%s) = CONNECTION_ID()', $lock ) )
			|| (int) $order->get_meta( '_ppcp_processing', true, 'edit' ) >= time() ) {
			return new WP_Error( 'dentall_paypal_quote_busy', __( 'This payment is being reconciled. Do not pay again.', 'dentall-core' ), array( 'status' => 503 ) );
		}
		/* pre_payment_complete之后再核同一绑定，防止新PayPal订单/TXID覆盖本次Capture。 */
		$binding_allowed = false;
		$binding_guard = static function ( $statuses, $pay_order ) use ( $order_id, $paypal_order, $resource, $lock, &$binding_allowed ) {
			if ( (int) $order_id !== $pay_order->get_id() ) {
				return $statuses;
			}
			global $wpdb;
			$fresh = new WC_Order( $pay_order );
			$binding_allowed = in_array( 'failed', $statuses, true ) && 'failed' === $fresh->get_status( 'edit' )
				&& 'ppcp-gateway' === $fresh->get_payment_method( 'edit' )
				&& 'CAPTURE' === $fresh->get_meta( '_ppcp_paypal_intent', true, 'edit' )
				&& ! $fresh->get_date_paid( 'edit' ) && (float) $fresh->get_total_refunded() <= 0
				&& ( '' === $fresh->get_transaction_id( 'edit' ) || $resource['id'] === $fresh->get_transaction_id( 'edit' ) )
				&& (int) $fresh->get_meta( '_ppcp_processing', true, 'edit' ) < time()
				&& null !== dentall_core_get_matching_paypal_quote_capture( $fresh, $paypal_order, $resource )
				&& '1' === (string) $wpdb->get_var( $wpdb->prepare( 'SELECT IS_USED_LOCK(%s) = CONNECTION_ID()', $lock ) );
			return $binding_allowed ? $statuses : array();
		};
		add_filter( 'woocommerce_valid_order_statuses_for_payment_complete', $binding_guard, PHP_INT_MAX, 2 );
		try {
			$order->payment_complete( $capture->id() );
		} finally {
			remove_filter( 'woocommerce_valid_order_statuses_for_payment_complete', $binding_guard, PHP_INT_MAX );
		}
		$paid = new WC_Order( (int) $order_id );
		/* payment_complete返回true不等于已入账；不改Pending、不在拒绝后save旧对象。 */
		if ( ! $paid->is_paid() || ! $paid->get_date_paid( 'edit' ) || $capture->id() !== $paid->get_transaction_id( 'edit' ) ) {
			return $error;
		}
		if ( $binding_allowed ) {
			do_action( 'woocommerce_paypal_payments_order_captured', $paid, $capture );
		}
		return new WP_REST_Response( array( 'success' => true ) );
	} catch ( Throwable $exception ) {
		wc_get_logger()->error( 'Verified PayPal quote reconciliation did not complete.', array( 'source' => 'dentall-core' ) );
		return new WP_Error( 'dentall_paypal_quote_retry', __( 'This payment could not be reconciled yet. Do not pay again.', 'dentall-core' ), array( 'status' => 503 ) );
	} finally {
		$wpdb->get_var( $wpdb->prepare( 'SELECT RELEASE_LOCK(%s)', $lock ) );
	}
}
add_filter( 'rest_dispatch_request', 'dentall_core_reconcile_failed_paypal_quote', 20, 4 );
