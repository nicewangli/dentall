<?php
/**
 * D76 PayPal入口合同：复用D75夹具，不发送HTTP或真实邮件。
 * 运行：php -n project-docs/tests/day76-paypal-quote-unit.php
 */

ob_start();
require __DIR__ . '/day75-shipping-quote-lifecycle-unit.php';
$lifecycle_result = json_decode( trim( ob_get_clean() ), true );
$checks = array();
$test_paypal_capability = true;
$test_paypal_cart_requires_quote = false;

function current_user_can( $capability, ...$args ) {
	global $test_paypal_capability;
	return 'pay_for_order' === $capability && $test_paypal_capability;
}
function dentall_core_cart_requires_shipping_quote() {
	global $test_paypal_cart_requires_quote;
	return $test_paypal_cart_requires_quote;
}
require dirname( __DIR__, 2 ) . '/app/public/wp-content/plugins/dentall-core/includes/shipping-quote-paypal.php';

function dentall_test_paypal_is_rejected( $callback ) {
	try {
		$callback();
		return false;
	} catch ( RuntimeException $error ) {
		return '' !== $error->getMessage();
	}
}

function dentall_test_paypal_snapshot( $order ) {
	$data = get_object_vars( $order );
	unset( $data['getter_contexts'] ); // 夹具的读取追踪不属于订单持久化数据。
	return serialize( $data );
}

class Dentall_Test_PayPal_Unit {
	public function __construct( private string $order_id ) {}
	public function custom_id() { return $this->order_id; }
}
class Dentall_Test_PayPal_Order {
	public function __construct( private array $units ) {}
	public function purchase_units() { return $this->units; }
}

$quote = dentall_test_make_order( 300 );
dentall_test_stamp_quote( $quote, time() - 60, time() + 3600, 'paypal-unit-token' );
$test_orders[300] = $quote;
$data = array( 'context' => 'pay-now', 'order_id' => 300, 'order_key' => $quote->get_order_key() );
$snapshot = dentall_test_paypal_snapshot( $quote );
$checks['valid_quote_creation_is_allowed'] = ! dentall_test_paypal_is_rejected(
	fn() => dentall_core_guard_paypal_quote_creation( $data )
);
$checks['creation_does_not_mutate_order'] = $snapshot === dentall_test_paypal_snapshot( $quote );

foreach ( array( 0, -300, '300junk', array( 300 ), null ) as $invalid_id ) {
	$invalid_data = array_replace( $data, array( 'order_id' => $invalid_id ) );
	$checks[ 'invalid_id_' . count( $checks ) ] = dentall_test_paypal_is_rejected(
		fn() => dentall_core_guard_paypal_quote_creation( $invalid_data )
	);
}
$checks['wrong_key_is_rejected'] = dentall_test_paypal_is_rejected(
	fn() => dentall_core_guard_paypal_quote_creation( array_replace( $data, array( 'order_key' => 'wrong' ) ) )
);
$test_paypal_capability = false;
$checks['key_without_payment_permission_is_rejected'] = dentall_test_paypal_is_rejected(
	fn() => dentall_core_guard_paypal_quote_creation( $data )
);
$test_paypal_capability = true;
$checks['unauthorized_requests_do_not_mutate_order'] = $snapshot === dentall_test_paypal_snapshot( $quote );

foreach ( array( 'product', 'cart', 'cart-block', 'checkout' ) as $context ) {
	$test_paypal_cart_requires_quote = true;
	$checks[ 'physical_cart_rejects_' . $context ] = dentall_test_paypal_is_rejected(
		fn() => dentall_core_guard_paypal_quote_creation( array( 'context' => $context ) )
	);
}
$test_paypal_cart_requires_quote = false;
$paypal_order = new Dentall_Test_PayPal_Order( array( new Dentall_Test_PayPal_Unit( '300' ) ) );
$checks['valid_capture_can_reach_native_request'] = ! dentall_test_paypal_is_rejected(
	fn() => dentall_core_guard_paypal_quote_capture( $paypal_order )
);
$quote->status = 'on-hold';
$checks['on_hold_cannot_create_second_payment'] = dentall_test_paypal_is_rejected(
	fn() => dentall_core_guard_paypal_quote_creation( $data )
);
$checks['approved_inflight_order_can_be_processed'] = true === dentall_core_guard_paypal_quote_processing( true, null, $quote );
$checks['existing_false_processing_decision_is_preserved'] = false === dentall_core_guard_paypal_quote_processing( false, null, $quote );

foreach ( array( 'expired', 'changed', 'closed', 'cancelled', 'not_issued', 'non_admin_physical' ) as $failure ) {
	$invalid = dentall_test_make_order( 301 );
	dentall_test_stamp_quote( $invalid, time() - 60, time() + 3600, 'invalid-paypal-token' );
	if ( 'expired' === $failure ) {
		$invalid->meta[ DENTALL_SHIPPING_QUOTE_ISSUED_AT_META ] = time() - 7200;
		$invalid->meta[ DENTALL_SHIPPING_QUOTE_EXPIRES_AT_META ] = time() - 1;
	} elseif ( 'changed' === $failure ) {
		$invalid->totals['total'] = '999.00';
	} elseif ( 'closed' === $failure ) {
		$invalid->meta[ DENTALL_SHIPPING_QUOTE_CLOSED_AT_META ] = time();
	} elseif ( 'cancelled' === $failure ) {
		$invalid->status = 'cancelled';
	} else {
		$invalid->meta = array();
		$invalid->created_via = 'non_admin_physical' === $failure ? 'checkout' : 'admin';
	}
	$test_orders[301] = $invalid;
	$invalid_snapshot = dentall_test_paypal_snapshot( $invalid );
	$invalid_paypal = new Dentall_Test_PayPal_Order( array( new Dentall_Test_PayPal_Unit( '301' ) ) );
	$checks[ $failure . '_processing_is_blocked' ] = dentall_test_paypal_is_rejected(
		fn() => dentall_core_guard_paypal_quote_processing( true, null, $invalid )
	);
	$checks[ $failure . '_capture_is_blocked' ] = dentall_test_paypal_is_rejected(
		fn() => dentall_core_guard_paypal_quote_capture( $invalid_paypal )
	);
	$checks[ $failure . '_is_read_only' ] = $invalid_snapshot === dentall_test_paypal_snapshot( $invalid );
}
foreach ( array( 'processing', 'completed' ) as $paid_status ) {
	$quote->status = $paid_status;
	$checks[ $paid_status . '_duplicate_approval_skips_processing' ] = false === dentall_core_guard_paypal_quote_processing( true, null, $quote );
	$checks[ $paid_status . '_cannot_be_captured_again' ] = dentall_test_paypal_is_rejected(
		fn() => dentall_core_guard_paypal_quote_capture( $paypal_order )
	);
}
foreach ( array( array(), array( new Dentall_Test_PayPal_Unit( 'unknown' ) ), array( new Dentall_Test_PayPal_Unit( '99999' ) ) ) as $units ) {
	$checks[ 'unmapped_capture_' . count( $checks ) ] = dentall_test_paypal_is_rejected(
		fn() => dentall_core_guard_paypal_quote_capture( new Dentall_Test_PayPal_Order( $units ) )
	);
}
$virtual = dentall_test_make_order( 302 );
$virtual->contains_shipping = false;
$checks['non_quote_virtual_order_keeps_native_processing'] = ! dentall_test_paypal_is_rejected(
	fn() => dentall_core_guard_paypal_quote_processing( true, null, $virtual )
);
$failed = array_keys( array_filter( $checks, fn( $passed ) => ! $passed ) );
echo json_encode( array( 'status' => $failed ? 'fail' : 'pass', 'assertions' => count( $checks ), 'failed' => $failed, 'lifecycle_assertions' => $lifecycle_result['assertions'] ), JSON_UNESCAPED_SLASHES ) . PHP_EOL;
exit( $failed ? 1 : 0 );
