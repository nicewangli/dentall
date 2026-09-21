<?php
/**
 * 人工运费报价的签发、72小时失效与付款边界。
 */

defined( 'ABSPATH' ) || exit;

const DENTALL_SHIPPING_QUOTE_ISSUED_AT_META     = '_dentall_quote_issued_at';
const DENTALL_SHIPPING_QUOTE_EXPIRES_AT_META    = '_dentall_quote_expires_at';
const DENTALL_SHIPPING_QUOTE_SIGNATURE_META     = '_dentall_quote_signature';
const DENTALL_SHIPPING_QUOTE_TOKEN_META         = '_dentall_quote_token';
const DENTALL_SHIPPING_QUOTE_CLOSED_AT_META     = '_dentall_quote_closed_at';
const DENTALL_SHIPPING_QUOTE_CLOSED_REASON_META = '_dentall_quote_closed_reason';
const DENTALL_SHIPPING_QUOTE_EXPIRY_HOOK        = 'dentall_expire_shipping_quote';
const DENTALL_SHIPPING_QUOTE_ACTION_GROUP       = 'dentall-shipping-quote';
const DENTALL_SHIPPING_QUOTE_LIFETIME           = 72 * HOUR_IN_SECONDS;

/**
 * 以原始状态判断订单，避免展示Filter改变交易守卫。
 *
 * @param WC_Order|null       $order    订单。
 * @param array<string>|string $statuses 允许的状态。
 * @return bool
 */
function dentall_core_shipping_quote_order_has_status( $order, $statuses ) {
	return $order instanceof WC_Order
		&& in_array( (string) $order->get_status( 'edit' ), (array) $statuses, true );
}

/**
 * 判断订单是否属于本项目的后台人工报价流程。
 *
 * 已签发订单始终由本模块管理；未签发订单仅接管Woo后台创建的实体商品订单，
 * 避免把其他来源的普通订单误纳入报价生命周期。
 *
 * @param WC_Order|null $order 订单。
 * @return bool
 */
function dentall_core_is_shipping_quote_candidate( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return false;
	}

	if ( 0 < (int) $order->get_meta( DENTALL_SHIPPING_QUOTE_ISSUED_AT_META, true, 'edit' ) ) {
		return true;
	}

	return dentall_core_order_contains_shipping_products( $order )
		&& is_callable( array( $order, 'get_created_via' ) )
		&& 'admin' === $order->get_created_via( 'edit' );
}

/**
 * 递归稳定化签名数据，避免关联数组键顺序造成误判。
 *
 * @param mixed $value 待稳定化的数据。
 * @return mixed
 */
function dentall_core_normalize_quote_signature_value( $value ) {
	if ( is_object( $value ) ) {
		if ( $value instanceof JsonSerializable ) {
			$object_data = $value->jsonSerialize();
		} elseif ( is_callable( array( $value, 'get_data' ) ) ) {
			$object_data = $value->get_data();
		} else {
			$object_data = get_object_vars( $value );
		}

		return array(
			'object_class' => get_class( $value ),
			'object_data'  => dentall_core_normalize_quote_signature_value( $object_data ),
		);
	}

	if ( ! is_array( $value ) ) {
		return is_scalar( $value ) || null === $value ? $value : gettype( $value );
	}

	foreach ( $value as $key => $item ) {
		$value[ $key ] = dentall_core_normalize_quote_signature_value( $item );
	}

	if ( ! array_is_list( $value ) ) {
		ksort( $value, SORT_STRING );
	}

	return $value;
}

/**
 * 取得一类订单项目的稳定数据。
 *
 * @param WC_Order $order 订单。
 * @param string   $type  WooCommerce订单项目类型。
 * @return array<int, array<string, mixed>>
 */
function dentall_core_get_quote_item_signature_data( $order, $type ) {
	$rows = array();
	$items = $order->get_items( $type );

	ksort( $items, SORT_NUMERIC );

	foreach ( $items as $item_id => $item ) {
		if ( ! is_callable( array( $item, 'get_data' ) ) ) {
			continue;
		}

		$data             = $item->get_data();
		$data['item_id']  = (int) $item_id;
		$rows[]           = dentall_core_normalize_quote_signature_value( $data );
	}

	return $rows;
}

/**
 * 取得订单地址的原始字段，避免展示过滤器改变报价签名。
 *
 * @param WC_Order $order 订单。
 * @param string   $type  地址类型：billing或shipping。
 * @return array<string, string>
 */
function dentall_core_get_quote_address_signature_data( $order, $type ) {
	$fields = array(
		'first_name',
		'last_name',
		'company',
		'country',
		'state',
		'postcode',
		'city',
		'address_1',
		'address_2',
		'phone',
	);

	if ( 'billing' === $type ) {
		$fields[] = 'email';
	}

	$address = array();

	foreach ( $fields as $field ) {
		$getter = 'get_' . $type . '_' . $field;

		if ( is_callable( array( $order, $getter ) ) ) {
			$address[ $field ] = (string) $order->{$getter}( 'edit' );
		}
	}

	return $address;
}

/**
 * 为会改变报价金额或履约条件的数据生成语义签名。
 *
 * @param WC_Order $order 订单。
 * @return string
 */
function dentall_core_get_shipping_quote_signature( $order ) {
	$payload = array(
		'customer_id'      => (int) $order->get_customer_id( 'edit' ),
		'currency'         => (string) $order->get_currency( 'edit' ),
		'line_items'       => dentall_core_get_quote_item_signature_data( $order, 'line_item' ),
		'coupons'          => dentall_core_get_quote_item_signature_data( $order, 'coupon' ),
		'shipping_lines'   => dentall_core_get_quote_item_signature_data( $order, 'shipping' ),
		'fees'             => dentall_core_get_quote_item_signature_data( $order, 'fee' ),
		'taxes'            => dentall_core_get_quote_item_signature_data( $order, 'tax' ),
		'billing_address'  => dentall_core_get_quote_address_signature_data( $order, 'billing' ),
		'shipping_address' => dentall_core_get_quote_address_signature_data( $order, 'shipping' ),
		'totals'           => array(
			'discount_total' => $order->get_discount_total( 'edit' ),
			'discount_tax'   => $order->get_discount_tax( 'edit' ),
			'shipping_total' => $order->get_shipping_total( 'edit' ),
			'shipping_tax'   => $order->get_shipping_tax( 'edit' ),
			'cart_tax'       => $order->get_cart_tax( 'edit' ),
			'total'          => $order->get_total( 'edit' ),
			'total_tax'      => $order->get_total_tax( 'edit' ),
		),
	);

	$json = wp_json_encode( dentall_core_normalize_quote_signature_value( $payload ) );

	return false === $json ? '' : hash( 'sha256', $json );
}

/**
 * 取得当前报价不能付款的原因。空字符串表示可付款或不归本模块管理。
 *
 * @param WC_Order|null $order 订单。
 * @param int|null      $now   测试可注入的UTC时间戳。
 * @return string
 */
function dentall_core_get_shipping_quote_block_reason( $order, $now = null ) {
	if ( ! dentall_core_is_shipping_quote_candidate( $order ) ) {
		return '';
	}

	if ( 0 < (int) $order->get_meta( DENTALL_SHIPPING_QUOTE_CLOSED_AT_META, true, 'edit' ) ) {
		return 'closed';
	}

	if ( ! dentall_core_shipping_quote_order_has_status( $order, array( 'pending', 'failed' ) ) ) {
		return 'status';
	}

	if ( ! dentall_core_order_has_quoted_shipping( $order ) ) {
		return 'shipping';
	}

	if ( ! dentall_core_order_has_required_quote_details( $order ) ) {
		return 'details';
	}

	$issued_at = (int) $order->get_meta( DENTALL_SHIPPING_QUOTE_ISSUED_AT_META, true, 'edit' );
	$expires_at = (int) $order->get_meta( DENTALL_SHIPPING_QUOTE_EXPIRES_AT_META, true, 'edit' );
	$token = (string) $order->get_meta( DENTALL_SHIPPING_QUOTE_TOKEN_META, true, 'edit' );
	$signature = (string) $order->get_meta( DENTALL_SHIPPING_QUOTE_SIGNATURE_META, true, 'edit' );

	if ( $issued_at <= 0 || $expires_at <= $issued_at || '' === $token || 64 !== strlen( $signature ) ) {
		return 'not_issued';
	}

	$current_time = null === $now ? time() : (int) $now;

	if ( $current_time >= $expires_at ) {
		return 'expired';
	}

	if ( ! hash_equals( $signature, dentall_core_get_shipping_quote_signature( $order ) ) ) {
		return 'changed';
	}

	return '';
}

/**
 * 取得对客户安全展示的付款阻断说明。
 *
 * @param string $reason 阻断原因代码。
 * @return string
 */
function dentall_core_get_shipping_quote_block_message( $reason ) {
	$messages = array(
		'closed'     => __( 'This quote was closed and cannot be reopened. Contact us for a new quote.', 'dentall-core' ),
		'expired'    => __( 'This quote has expired. Contact us for a new quote before payment.', 'dentall-core' ),
		'changed'    => __( 'This quote changed after it was issued and can no longer be paid. Contact us for a new quote.', 'dentall-core' ),
		'not_issued' => __( 'This payment link has not been activated. Contact us before payment.', 'dentall-core' ),
		'shipping'   => __( 'This quote does not include a valid positive shipping charge. Contact us before payment.', 'dentall-core' ),
		'details'    => __( 'This quote does not include complete billing and shipping details. Contact us before payment.', 'dentall-core' ),
		'status'     => __( 'This quote can no longer be paid. Contact us for assistance.', 'dentall-core' ),
	);

	return $messages[ $reason ] ?? __( 'This quote cannot be paid. Contact us for assistance.', 'dentall-core' );
}

/**
 * 将仍可收款的旧报价设为不可付款。
 *
 * @param WC_Order $order  订单。
 * @param string   $reason 阻断原因代码。
 * @return bool 是否已经处于不可付款状态，且需要的状态变更成功保存。
 */
function dentall_core_cancel_shipping_quote( $order, $reason ) {
	if ( ! $order instanceof WC_Order ) {
		return false;
	}

	if ( ! dentall_core_shipping_quote_order_has_status( $order, array( 'pending', 'failed' ) ) ) {
		return true;
	}

	$notes = array(
		'closed'            => __( 'This issued quote was already closed and cannot be reopened. Create and confirm a new order before sending another payment request.', 'dentall-core' ),
		'expired'           => __( 'The 72-hour quote validity period ended. This order was cancelled; create and confirm a new order before sending another payment request.', 'dentall-core' ),
		'changed'           => __( 'The issued quote changed after its payment email was sent. This order was cancelled; create and confirm a new order before sending another payment request.', 'dentall-core' ),
		'not_issued'        => __( 'Payment was attempted before this quote was activated by a successful payment email. This order was cancelled; create and confirm a new order.', 'dentall-core' ),
		'shipping'          => __( 'Payment was attempted without a valid positive Shipping line. This order was cancelled; create and confirm a new order.', 'dentall-core' ),
		'details'           => __( 'Payment was attempted without complete billing and shipping details. This order was cancelled; create and confirm a new order.', 'dentall-core' ),
		'activation_failed' => __( 'This payment request could not be activated safely, so the order was cancelled. Investigate the order data and create a replacement quote.', 'dentall-core' ),
		'deactivated'       => __( 'DentAll Core was deactivated, so this active payment quote was cancelled to prevent an unguarded payment. Create and confirm a new order after reactivation.', 'dentall-core' ),
	);

	return true === $order->update_status(
		'cancelled',
		$notes[ $reason ] ?? __( 'This issued quote is no longer payable and was cancelled.', 'dentall-core' )
	);
}

/**
 * 向付款申请邮件说明有效期和进口费用边界。
 *
 * @param WC_Order $order         邮件订单。
 * @param bool     $sent_to_admin 是否发给管理员。
 * @param bool     $plain_text    是否纯文本邮件。
 * @param WC_Email $email         WooCommerce邮件对象。
 * @return void
 */
function dentall_core_add_shipping_quote_email_terms( $order, $sent_to_admin, $plain_text, $email ) {
	if (
		$sent_to_admin
		|| ! $email instanceof WC_Email
		|| 'customer_invoice' !== $email->id
		|| ! dentall_core_is_shipping_quote_candidate( $order )
		|| ! dentall_core_shipping_quote_order_has_status( $order, array( 'pending', 'failed' ) )
	) {
		return;
	}

	$message = __( 'This quote is valid for 72 hours from the first successful sending of this payment request. Sending it again does not extend the deadline.', 'dentall-core' );
	$duties  = __( 'Import duties, import taxes, customs clearance charges and carrier collection fees are not included in the DentAll order total and must be paid directly to customs or the carrier.', 'dentall-core' );

	if ( $plain_text ) {
		echo "\n" . wp_strip_all_tags( $message ) . "\n" . wp_strip_all_tags( $duties ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	echo '<p>' . esc_html( $message ) . '<br>' . esc_html( $duties ) . '</p>';
}
add_action( 'woocommerce_email_after_order_table', 'dentall_core_add_shipping_quote_email_terms', 20, 4 );

/**
 * 阻止向已签发但已失效的待付款报价重发带付款链接的Customer Invoice。
 *
 * Customer Invoice是人工邮件，会绕过WooCommerce常规enabled开关，因此通过
 * 收件人Filter使发送安全失败。未签发订单必须保留收件人，首封邮件才能生成
 * 付款链接并在发送成功后启动报价。
 *
 * @param string        $recipient 收件人。
 * @param WC_Order|null $order     邮件订单。
 * @param WC_Email      $email     邮件对象。
 * @return string
 */
function dentall_core_guard_shipping_quote_invoice_recipient( $recipient, $order, $email ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	if (
		! $order instanceof WC_Order
		|| ! dentall_core_is_shipping_quote_candidate( $order )
		|| ! dentall_core_shipping_quote_order_has_status( $order, array( 'pending', 'failed' ) )
		|| 0 >= (int) $order->get_meta( DENTALL_SHIPPING_QUOTE_ISSUED_AT_META, true, 'edit' )
	) {
		return $recipient;
	}

	return '' === dentall_core_get_shipping_quote_block_reason( $order ) ? $recipient : '';
}
add_filter( 'woocommerce_email_recipient_customer_invoice', 'dentall_core_guard_shipping_quote_invoice_recipient', PHP_INT_MAX, 3 );

/**
 * 首次成功发送付款申请邮件后签发报价；后续重发不改变时间。
 *
 * @param bool     $success 发送结果。
 * @param string   $email_id 邮件ID。
 * @param WC_Email $email WooCommerce邮件对象。
 * @return void
 */
function dentall_core_issue_shipping_quote_after_email( $success, $email_id, $email ) {
	$order = $email instanceof WC_Email ? $email->object : null;

	if (
		! $success
		|| 'customer_invoice' !== $email_id
		|| ! $order instanceof WC_Order
		|| ! dentall_core_is_shipping_quote_candidate( $order )
		|| ! dentall_core_shipping_quote_order_has_status( $order, array( 'pending', 'failed' ) )
		|| 0 < (int) $order->get_meta( DENTALL_SHIPPING_QUOTE_ISSUED_AT_META, true, 'edit' )
	) {
		return;
	}

	$issued_at = time();
	$expires_at = $issued_at + DENTALL_SHIPPING_QUOTE_LIFETIME;
	$token = wp_generate_uuid4();
	$signature = dentall_core_get_shipping_quote_signature( $order );

	if ( '' === $signature ) {
		$order->add_order_note(
			__( 'The quote signature could not be encoded, so this payment request was not activated. This order is being cancelled; investigate the order data and create a replacement quote.', 'dentall-core' )
		);
		dentall_core_cancel_shipping_quote( $order, 'activation_failed' );
		return;
	}

	$order->update_meta_data( DENTALL_SHIPPING_QUOTE_ISSUED_AT_META, $issued_at );
	$order->update_meta_data( DENTALL_SHIPPING_QUOTE_EXPIRES_AT_META, $expires_at );
	$order->update_meta_data( DENTALL_SHIPPING_QUOTE_SIGNATURE_META, $signature );
	$order->update_meta_data( DENTALL_SHIPPING_QUOTE_TOKEN_META, $token );
	$order->delete_meta_data( DENTALL_SHIPPING_QUOTE_CLOSED_AT_META );
	$order->delete_meta_data( DENTALL_SHIPPING_QUOTE_CLOSED_REASON_META );
	$order->save();

	$action_id = function_exists( 'as_schedule_single_action' )
		? as_schedule_single_action(
			$expires_at,
			DENTALL_SHIPPING_QUOTE_EXPIRY_HOOK,
			array( 'order_id' => $order->get_id(), 'token' => $token ),
			DENTALL_SHIPPING_QUOTE_ACTION_GROUP,
			true
		)
		: 0;

	$order->add_order_note(
		sprintf(
			/* translators: 1: UTC expiry time, 2: duration in hours. */
			__( 'Payment quote issued. It expires at %1$s UTC (%2$d hours after this first successful email); resending does not extend it.', 'dentall-core' ),
			gmdate( 'Y-m-d H:i:s', $expires_at ),
			(int) ( DENTALL_SHIPPING_QUOTE_LIFETIME / HOUR_IN_SECONDS )
		)
	);

	if ( ! $action_id ) {
		$order->add_order_note(
			__( 'The quote expiry task could not be scheduled. The real-time payment guard still enforces the saved expiry; investigate Scheduled Actions.', 'dentall-core' )
		);
	}

	if ( ! dentall_core_order_has_quoted_shipping( $order ) || ! dentall_core_order_has_required_quote_details( $order ) ) {
		$order->add_order_note(
			__( 'This payment request was sent with incomplete quote data. Payment remains blocked; cancel this order and create a complete replacement quote.', 'dentall-core' )
		);
	}
}
add_action( 'woocommerce_email_sent', 'dentall_core_issue_shipping_quote_after_email', 20, 3 );

/**
 * 验证经典order-pay请求中的订单密钥，避免按可枚举ID变更订单。
 *
 * @param WC_Order $order 订单。
 * @return bool
 */
function dentall_core_request_has_valid_quote_order_key( $order ) {
	if ( ! isset( $_GET['key'] ) || ! is_string( $_GET['key'] ) ) {
		return false;
	}

	$key = wc_clean( wp_unslash( $_GET['key'] ) );

	return '' !== $key && hash_equals( $order->get_order_key( 'edit' ), $key );
}

/**
 * 在Woo表单处理前实时拦截未签发、过期或被修改的经典付款链接。
 *
 * @return void
 */
function dentall_core_guard_shipping_quote_order_pay_request() {
	global $wp;

	$order_id = isset( $wp->query_vars['order-pay'] ) ? absint( $wp->query_vars['order-pay'] ) : 0;

	if ( ! $order_id ) {
		return;
	}

	$order = wc_get_order( $order_id );

	if ( ! $order instanceof WC_Order || ! dentall_core_request_has_valid_quote_order_key( $order ) ) {
		return;
	}

	$reason = dentall_core_get_shipping_quote_block_reason( $order );

	if ( '' === $reason ) {
		return;
	}

	$is_issued = 0 < (int) $order->get_meta( DENTALL_SHIPPING_QUOTE_ISSUED_AT_META, true, 'edit' );

	/* 未签发草稿只阻断付款，不因预览或无有效nonce的请求产生持久化变更。 */
	if ( $is_issued ) {
		dentall_core_cancel_shipping_quote( $order, $reason );
	}

	wc_add_notice( dentall_core_get_shipping_quote_block_message( $reason ), 'error' );
}
add_action( 'wp', 'dentall_core_guard_shipping_quote_order_pay_request', 19 );

/**
 * 在当前经典付款请求中移除被报价规则阻断的订单状态。
 *
 * 首次付款邮件在签发Hook之前也会调用needs_payment()生成付款链接，因此
 * 不能全局阻断未签发订单。这里仅接管已经通过订单密钥校验的order-pay请求。
 * 使用最高优先级，避免WooCommerce Blocks在999优先级重新加入订单状态。
 *
 * @param array<string> $statuses WooCommerce默认可付款状态。
 * @param WC_Order      $order    订单。
 * @return array<string>
 */
function dentall_core_guard_shipping_quote_payment_statuses( $statuses, $order ) {
	global $wp;

	$order_pay_id = isset( $wp->query_vars['order-pay'] ) ? absint( $wp->query_vars['order-pay'] ) : 0;

	if (
		! dentall_core_is_shipping_quote_candidate( $order )
		|| $order_pay_id !== $order->get_id()
		|| ! dentall_core_request_has_valid_quote_order_key( $order )
	) {
		return $statuses;
	}

	return '' === dentall_core_get_shipping_quote_block_reason( $order ) ? $statuses : array();
}
add_filter( 'woocommerce_valid_order_statuses_for_payment', 'dentall_core_guard_shipping_quote_payment_statuses', PHP_INT_MAX, 2 );

/**
 * 实时拦截Store API对旧报价的付款提交。
 *
 * @param mixed           $dispatch_result 既有短路结果。
 * @param WP_REST_Request $request 当前REST请求。
 * @param string          $route 匹配路由。
 * @param array<mixed>    $handler 路由处理器。
 * @return mixed
 */
function dentall_core_guard_shipping_quote_store_api_payment( $dispatch_result, $request, $route, $handler ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	if ( null !== $dispatch_result || ! $request instanceof WP_REST_Request || 'POST' !== $request->get_method() ) {
		return $dispatch_result;
	}

	$request_route = $request->get_route();

	if ( ! is_string( $request_route ) || 1 !== preg_match( '#^/wc/store(?:/v[0-9]+)?/checkout/([0-9]+)$#i', $request_route, $matches ) ) {
		return $dispatch_result;
	}

	$order = wc_get_order( absint( $matches[1] ) );

	/*
	 * rest_dispatch_request只会在路由permission_callback通过后执行。Guest由Woo校验
	 * order key，已登录客户则可由订单归属授权；这里不能再次强制要求key，否则会
	 * 放过Woo已授权但没有key的客户请求。
	 */
	if ( ! $order instanceof WC_Order ) {
		return $dispatch_result;
	}

	$reason = dentall_core_get_shipping_quote_block_reason( $order );

	if ( '' === $reason ) {
		return $dispatch_result;
	}

	/* 与经典付款入口一致：未签发草稿只拒付，不产生持久化状态变更。 */
	if ( 0 < (int) $order->get_meta( DENTALL_SHIPPING_QUOTE_ISSUED_AT_META, true, 'edit' ) ) {
		dentall_core_cancel_shipping_quote( $order, $reason );
	}

	return new WP_Error(
		'dentall_shipping_quote_unpayable',
		dentall_core_get_shipping_quote_block_message( $reason ),
		array( 'status' => 409 )
	);
}
add_filter( 'rest_dispatch_request', 'dentall_core_guard_shipping_quote_store_api_payment', 9, 4 );

/**
 * 到期任务将仍待付款的报价设为Cancelled。
 *
 * @param int    $order_id 订单ID。
 * @param string $token    签发版本标识。
 * @return void
 */
function dentall_core_expire_shipping_quote( $order_id, $token ) {
	$order = wc_get_order( absint( $order_id ) );

	if (
		! $order instanceof WC_Order
		|| ! is_string( $token )
		|| '' === $token
		|| ! hash_equals( (string) $order->get_meta( DENTALL_SHIPPING_QUOTE_TOKEN_META, true, 'edit' ), $token )
		|| ! dentall_core_shipping_quote_order_has_status( $order, array( 'pending', 'failed' ) )
	) {
		return;
	}

	$expires_at = (int) $order->get_meta( DENTALL_SHIPPING_QUOTE_EXPIRES_AT_META, true, 'edit' );

	if ( $expires_at <= 0 || time() < $expires_at ) {
		return;
	}

	$reason = dentall_core_get_shipping_quote_block_reason( $order );
	dentall_core_cancel_shipping_quote( $order, '' === $reason ? 'expired' : $reason );
}
add_action( DENTALL_SHIPPING_QUOTE_EXPIRY_HOOK, 'dentall_core_expire_shipping_quote', 10, 2 );

/**
 * 已签发报价内容一旦保存为不同签名，立即取消旧单。
 *
 * @param int      $order_id 订单ID。
 * @param WC_Order $order    订单。
 * @return void
 */
function dentall_core_invalidate_changed_shipping_quote( $order_id, $order ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	static $updating = false;

	if (
		$updating
		|| ! $order instanceof WC_Order
		|| ! dentall_core_shipping_quote_order_has_status( $order, array( 'pending', 'failed' ) )
		|| 0 >= (int) $order->get_meta( DENTALL_SHIPPING_QUOTE_ISSUED_AT_META, true, 'edit' )
	) {
		return;
	}

	$signature = (string) $order->get_meta( DENTALL_SHIPPING_QUOTE_SIGNATURE_META, true, 'edit' );

	if ( 64 === strlen( $signature ) && hash_equals( $signature, dentall_core_get_shipping_quote_signature( $order ) ) ) {
		return;
	}

	$updating = true;
	dentall_core_cancel_shipping_quote( $order, 'changed' );
	$updating = false;
}
add_action( 'woocommerce_update_order', 'dentall_core_invalidate_changed_shipping_quote', 20, 2 );

/**
 * 离开待付款状态后永久关闭已签发报价，并清理尚未执行的单次任务。
 *
 * @param int      $order_id 订单ID。
 * @param string   $old_status 原状态。
 * @param string   $new_status 新状态。
 * @param WC_Order $order 订单。
 * @return void
 */
function dentall_core_unschedule_settled_shipping_quote( $order_id, $old_status, $new_status, $order ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	if ( ! $order instanceof WC_Order || in_array( $new_status, array( 'pending', 'failed' ), true ) ) {
		return;
	}

	if (
		0 < (int) $order->get_meta( DENTALL_SHIPPING_QUOTE_ISSUED_AT_META, true, 'edit' )
		&& 0 >= (int) $order->get_meta( DENTALL_SHIPPING_QUOTE_CLOSED_AT_META, true, 'edit' )
	) {
		$order->update_meta_data( DENTALL_SHIPPING_QUOTE_CLOSED_AT_META, time() );
		$order->update_meta_data( DENTALL_SHIPPING_QUOTE_CLOSED_REASON_META, (string) $new_status );
		$order->save_meta_data();
	}

	if (
		! function_exists( 'as_unschedule_action' )
		|| ( function_exists( 'doing_action' ) && doing_action( DENTALL_SHIPPING_QUOTE_EXPIRY_HOOK ) )
	) {
		return;
	}

	$token = (string) $order->get_meta( DENTALL_SHIPPING_QUOTE_TOKEN_META, true, 'edit' );

	if ( '' !== $token ) {
		as_unschedule_action(
			DENTALL_SHIPPING_QUOTE_EXPIRY_HOOK,
			array( 'order_id' => (int) $order_id, 'token' => $token ),
			DENTALL_SHIPPING_QUOTE_ACTION_GROUP
		);
	}
}
add_action( 'woocommerce_order_status_changed', 'dentall_core_unschedule_settled_shipping_quote', 20, 4 );

/**
 * 阻止过期或被修改报价被晚到的payment_complete()重新标为已付款。
 *
 * @param array<string> $statuses WooCommerce默认可完成付款状态。
 * @param WC_Order      $order    订单。
 * @return array<string>
 */
function dentall_core_guard_shipping_quote_payment_complete( $statuses, $order ) {
	if ( ! dentall_core_is_shipping_quote_candidate( $order ) ) {
		return $statuses;
	}

	return '' === dentall_core_get_shipping_quote_block_reason( $order ) ? $statuses : array();
}
add_filter( 'woocommerce_valid_order_statuses_for_payment_complete', 'dentall_core_guard_shipping_quote_payment_complete', PHP_INT_MAX, 2 );

/**
 * 插件停用时先关闭有效付款链接，再清理专属任务组。
 *
 * 停用后实时付款守卫不会运行，因此不能让已签发的pending/failed报价继续
 * 保持WooCommerce原生可付款状态。邮件发送与签发写入之间也可能中断，所以
 * 连尚未签发的后台实体订单候选也要关闭。当前B2B订单量低，停用时用一次
 * HPOS兼容的Woo订单查询同步处理，比保留无守卫付款链接更安全。
 *
 * @return void
 */
function dentall_core_deactivate_shipping_quote_lifecycle() {
	try {
		if ( ! function_exists( 'wc_get_orders' ) ) {
			throw new RuntimeException( 'WooCommerce order queries are unavailable.' );
		}

		$order_ids = wc_get_orders(
			array(
				'type'       => 'shop_order',
				'status'     => array( 'pending', 'failed' ),
				'limit'      => -1,
				'return'     => 'ids',
				'orderby'    => 'ID',
				'order'      => 'ASC',
			)
		);

		if ( ! is_array( $order_ids ) ) {
			throw new UnexpectedValueException( 'WooCommerce did not return an order ID list.' );
		}

		/* 先取得完整ID快照，再改状态，避免边分页边取消造成漏单。 */
		foreach ( $order_ids as $order_id ) {
			$order = wc_get_order( absint( $order_id ) );

			if ( ! $order instanceof WC_Order ) {
				throw new RuntimeException( 'A payable order returned by WooCommerce could not be loaded.' );
			}

			if ( ! dentall_core_is_shipping_quote_candidate( $order ) || ! dentall_core_shipping_quote_order_has_status( $order, array( 'pending', 'failed' ) ) ) {
				continue;
			}

			if ( true !== dentall_core_cancel_shipping_quote( $order, 'deactivated' ) ) {
				throw new RuntimeException( 'An active payment quote could not be cancelled.' );
			}

			$persisted_order = wc_get_order( absint( $order_id ) );

			if ( ! $persisted_order instanceof WC_Order ) {
				throw new RuntimeException( 'A cancelled payment quote could not be read back from WooCommerce.' );
			}

			if ( dentall_core_shipping_quote_order_has_status( $persisted_order, array( 'pending', 'failed' ) ) ) {
				throw new RuntimeException( 'An active payment quote remained payable after cancellation.' );
			}
		}

		if ( function_exists( 'as_unschedule_all_actions' ) ) {
			as_unschedule_all_actions( '', array(), DENTALL_SHIPPING_QUOTE_ACTION_GROUP );
		}
	} catch ( Throwable $error ) {
		error_log( // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			sprintf( 'DentAll Core safe deactivation failed: %s', $error->getMessage() )
		);

		if ( function_exists( 'wp_die' ) ) {
			wp_die(
				esc_html( __( 'DentAll Core could not safely close every active payment quote, so deactivation was stopped. Check the PHP error log and try again.', 'dentall-core' ) ),
				esc_html( __( 'DentAll Core deactivation stopped', 'dentall-core' ) ),
				array( 'response' => 500, 'back_link' => true )
			);
		}

		throw $error;
	}
}
