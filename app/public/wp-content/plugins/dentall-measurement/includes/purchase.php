<?php
/** PayPal 捕获证明与订单收据访问；订单状态本身不是收款证明。 */
defined( 'ABSPATH' ) || exit;

function dentall_measurement_capture_matches_order( WC_Order $order, $proof ) {
	return is_array( $proof ) && 'ppcp-gateway' === $order->get_payment_method()
		&& $order->has_status( wc_get_is_paid_statuses() ) && (float) $order->get_total() > 0
		&& isset( $proof['capture_id'], $proof['amount'], $proof['currency'] )
		&& is_string( $proof['capture_id'] ) && preg_match( '/^[A-Z0-9]{1,64}$/', $proof['capture_id'] )
		&& is_string( $proof['amount'] ) && preg_match( '/^\d+(?:\.\d+)?$/', $proof['amount'] )
		&& wc_format_decimal( $proof['amount'], wc_get_price_decimals() ) === $proof['amount']
		&& $proof['currency'] === $order->get_currency()
		&& wc_add_number_precision( $proof['amount'] ) === wc_add_number_precision( $order->get_total() );
}

function dentall_measurement_record_capture( $order, $capture ) {
	if ( ! $order instanceof WC_Order || ! $capture instanceof \WooCommerce\PayPalCommerce\ApiClient\Entity\Capture ) {
		return;
	}
	// 4.1.3的标准订单custom_id就是Woo订单ID；不接受预下单session标识或别单捕获。
	if ( 'COMPLETED' !== $capture->status()->name() || ! $capture->final_capture() || (string) $order->get_id() !== $capture->custom_id() ) {
		return;
	}
	$proof = array(
		'capture_id' => $capture->id(),
		'amount'     => $capture->amount()->value_str(),
		'currency'   => $capture->amount()->currency_code(),
	);
	if ( ! dentall_measurement_capture_matches_order( $order, $proof ) ) {
		return;
	}
	$order->update_meta_data( '_dentall_measurement_paypal_capture', $proof );
	$order->save_meta_data();
}
add_action( 'woocommerce_paypal_payments_order_captured', 'dentall_measurement_record_capture', 20, 2 );

function dentall_measurement_can_read_order( WC_Order $order ) {
	if ( ! isset( $_GET['key'] ) || ! is_string( $_GET['key'] ) ) {
		return false;
	}
	$key = wc_clean( wp_unslash( $_GET['key'] ) );
	return '' !== $order->get_order_key() && hash_equals( $order->get_order_key(), $key )
		&& ( ! $order->get_customer_id() || (int) $order->get_customer_id() === get_current_user_id() );
}

function dentall_measurement_order_ecommerce( WC_Order $order ) {
	$items = array();
	$total = 0;
	foreach ( $order->get_items( 'line_item' ) as $line ) {
		$quantity = $line->get_quantity();
		if ( $quantity <= 0 || ! apply_filters( 'woocommerce_order_item_visible', true, $line ) ) {
			return null;
		}
		$items[] = array(
			'item_id'   => (string) ( $line->get_variation_id() ?: $line->get_product_id() ),
			'item_name' => wp_strip_all_tags( $line->get_name() ),
			'quantity'  => (float) $quantity,
			'price'     => (float) wc_format_decimal( $line->get_total() / $quantity, wc_get_price_decimals() + 4 ),
		);
		$total += wc_add_number_precision( $line->get_total() );
	}
	if ( ! $items ) {
		return null;
	}
	return array(
		'currency' => $order->get_currency(),
		'value'    => (float) wc_remove_number_precision( $total ),
		'tax'      => (float) $order->get_total_tax(),
		'shipping' => (float) $order->get_shipping_total(),
		'items'    => $items,
	);
}

function dentall_measurement_order_pay( $order ) {
	// Woo在此Hook之前已核key、买家和Guest邮箱；不能提前从URL读取订单输出。
	if ( dentall_measurement_is_public_request() && $order instanceof WC_Order && dentall_measurement_can_read_order( $order ) ) {
		$GLOBALS['dentall_measurement_order_pay'] = dentall_measurement_order_ecommerce( $order );
	}
}
add_action( 'before_woocommerce_pay_form', 'dentall_measurement_order_pay' );

function dentall_measurement_purchase( $order_id ) {
	if ( ! dentall_measurement_has_consent() || ! dentall_measurement_is_public_request() || ! is_order_received_page() ) {
		return;
	}
	$order = wc_get_order( absint( $order_id ) );
	if ( ! $order instanceof WC_Order || ! dentall_measurement_can_read_order( $order ) ) {
		return;
	}
	$proof = $order->get_meta( '_dentall_measurement_paypal_capture', true );
	if ( ! dentall_measurement_capture_matches_order( $order, $proof ) || $order->get_transaction_id() !== $proof['capture_id'] ) {
		// 单独的网关captured标记/交易号没有金额证明；Webhook路径未验前不推断成功。
		return;
	}
	$ecommerce = dentall_measurement_order_ecommerce( $order );
	if ( ! $ecommerce ) {
		return;
	}
	$ecommerce['transaction_id'] = (string) $order->get_id();
	$GLOBALS['dentall_measurement_events'][] = array(
		'event'     => 'purchase',
		'ecommerce' => $ecommerce,
	);
}
add_action( 'woocommerce_thankyou', 'dentall_measurement_purchase' );
