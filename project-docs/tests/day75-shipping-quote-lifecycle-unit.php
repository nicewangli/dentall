<?php
/**
 * D75人工运费报价生命周期纯PHP合同测试。
 *
 * 运行：php -n project-docs/tests/day75-shipping-quote-lifecycle-unit.php
 */

define( 'ABSPATH', __DIR__ );
define( 'HOUR_IN_SECONDS', 3600 );

$test_orders             = array();
$test_notices            = array();
$test_scheduled_actions  = array();
$test_unscheduled_actions = array();
$test_unscheduled_groups = array();
$test_uuid_sequence      = 0;
$test_json_encode_failure = false;
$test_filters             = array();
$test_order_queries       = array();
$test_wc_get_orders_results = array();
$test_wc_get_orders_failure = false;
$test_wp_die_calls        = array();
$test_order_read_sequences = array();

function add_action() {}
function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
	global $test_filters;
	$test_filters[] = compact( 'hook', 'callback', 'priority', 'accepted_args' );
}
function __( $value ) { return $value; }
function esc_html( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
function wp_strip_all_tags( $value ) { return strip_tags( (string) $value ); }
function wp_json_encode( $value ) {
	global $test_json_encode_failure;
	return $test_json_encode_failure ? false : json_encode( $value, JSON_UNESCAPED_SLASHES );
}
function wp_unslash( $value ) { return $value; }
function wc_clean( $value ) { return is_scalar( $value ) ? trim( strip_tags( (string) $value ) ) : ''; }
function sanitize_text_field( $value ) { return is_scalar( $value ) ? trim( strip_tags( (string) $value ) ) : ''; }
function absint( $value ) { return abs( (int) $value ); }
function wc_get_order( $order_id ) {
	global $test_orders, $test_order_read_sequences;
	if ( ! empty( $test_order_read_sequences[ $order_id ] ) ) {
		return array_shift( $test_order_read_sequences[ $order_id ] );
	}
	return $test_orders[ $order_id ] ?? false;
}
function wc_get_orders( $args ) {
	global $test_order_queries, $test_wc_get_orders_results, $test_wc_get_orders_failure;
	$test_order_queries[] = $args;
	if ( $test_wc_get_orders_failure ) {
		throw new RuntimeException( 'TEST order query failure.' );
	}
	return $test_wc_get_orders_results;
}
function wp_die( $message, $title = '', $args = array() ) {
	global $test_wp_die_calls;
	$test_wp_die_calls[] = compact( 'message', 'title', 'args' );
	throw new RuntimeException( 'TEST wp_die.' );
}
function wc_add_notice( $message, $type ) { global $test_notices; $test_notices[] = array( $message, $type ); }
function wp_generate_uuid4() {
	global $test_uuid_sequence;
	++$test_uuid_sequence;

	return '11111111-1111-4111-8111-' . str_pad( (string) $test_uuid_sequence, 12, '0', STR_PAD_LEFT );
}

function as_schedule_single_action( $timestamp, $hook, $args, $group, $unique ) {
	global $test_scheduled_actions;
	$test_scheduled_actions[] = array(
		'timestamp' => $timestamp,
		'hook'      => $hook,
		'args'      => $args,
		'group'     => $group,
		'unique'    => $unique,
	);

	return count( $test_scheduled_actions );
}

function as_unschedule_action( $hook, $args, $group ) {
	global $test_unscheduled_actions;
	$test_unscheduled_actions[] = array(
		'hook'  => $hook,
		'args'  => $args,
		'group' => $group,
	);
}

function as_unschedule_all_actions( $hook, $args, $group ) {
	global $test_unscheduled_groups;
	$test_unscheduled_groups[] = array(
		'hook'  => $hook,
		'args'  => $args,
		'group' => $group,
	);
}

class WP_Error {
	public array $errors = array();
	public array $error_data = array();

	public function __construct( $code = '', $message = '', $data = null ) {
		if ( '' !== $code ) {
			$this->errors[ $code ][] = $message;
			$this->error_data[ $code ] = $data;
		}
	}
}

class WP_REST_Request {
	public function __construct( private string $route, private string $method = 'GET', private array $params = array() ) {}
	public function get_route() { return $this->route; }
	public function get_method() { return $this->method; }
	public function get_param( $key ) { return $this->params[ $key ] ?? null; }
}

class WC_Email {
	public function __construct( public string $id, public $object = null ) {}
}

class WC_Meta_Data_Stub implements JsonSerializable {
	public function __construct( public array $data ) {}
	public function jsonSerialize(): mixed { return $this->data; }
}

class WC_Order_Item_Stub {
	public function __construct( public array $data ) {}
	public function get_data() { return $this->data; }
}

class WC_Order {
	public string $status = 'pending';
	public string $created_via = 'admin';
	public bool $contains_shipping = true;
	public bool $has_quoted_shipping = true;
	public bool $has_required_quote_details = true;
	public string $currency = 'USD';
	public int $customer_id = 0;
	public string $order_key;
	public array $meta = array();
	public array $items = array();
	public array $addresses = array();
	public array $totals = array();
	public array $notes = array();
	public array $status_updates = array();
	public array $getter_contexts = array();
	public array $view_meta_overrides = array();
	public ?string $view_status_override = null;
	public ?string $view_created_via_override = null;
	public ?string $view_order_key_override = null;
	public int $save_count = 0;
	public int $save_meta_count = 0;
	public bool $status_update_result = true;

	public function __construct( private int $id ) {
		$this->order_key = 'wc_order_' . $id;
	}

	public function get_id() { return $this->id; }
	public function get_customer_id( $context = 'view' ) {
		$this->getter_contexts[] = array( 'customer_id', $context );
		return $this->customer_id;
	}
	public function get_created_via( $context = 'view' ) {
		$this->getter_contexts[] = array( 'created_via', $context );
		return 'view' === $context && null !== $this->view_created_via_override
			? $this->view_created_via_override
			: $this->created_via;
	}
	public function get_meta( $key, $single = true, $context = 'view' ) {
		$this->getter_contexts[] = array( 'meta:' . $key, $context );
		return 'view' === $context && array_key_exists( $key, $this->view_meta_overrides )
			? $this->view_meta_overrides[ $key ]
			: ( $this->meta[ $key ] ?? '' );
	}
	public function update_meta_data( $key, $value ) { $this->meta[ $key ] = $value; }
	public function delete_meta_data( $key ) { unset( $this->meta[ $key ] ); }
	public function save() { ++$this->save_count; }
	public function save_meta_data() { ++$this->save_meta_count; }
	public function add_order_note( $note ) { $this->notes[] = $note; }
	public function get_status( $context = 'view' ) {
		$this->getter_contexts[] = array( 'status', $context );
		return 'view' === $context && null !== $this->view_status_override
			? $this->view_status_override
			: $this->status;
	}
	public function has_status( $statuses ) { return in_array( $this->get_status(), (array) $statuses, true ); }
	public function update_status( $status, $note = '' ) {
		$this->status_updates[] = array( 'status' => $status, 'note' => $note );
		$this->status           = $status;
		return $this->status_update_result;
	}
	public function get_items( $type ) { return $this->items[ $type ] ?? array(); }
	private function record_total_getter( $name, $context ) {
		$this->getter_contexts[] = array( $name, $context );
		return $this->totals[ $name ];
	}
	public function get_currency( $context = 'view' ) {
		$this->getter_contexts[] = array( 'currency', $context );
		return $this->currency;
	}
	public function get_discount_total( $context = 'view' ) { return $this->record_total_getter( 'discount_total', $context ); }
	public function get_discount_tax( $context = 'view' ) { return $this->record_total_getter( 'discount_tax', $context ); }
	public function get_shipping_total( $context = 'view' ) { return $this->record_total_getter( 'shipping_total', $context ); }
	public function get_shipping_tax( $context = 'view' ) { return $this->record_total_getter( 'shipping_tax', $context ); }
	public function get_cart_tax( $context = 'view' ) { return $this->record_total_getter( 'cart_tax', $context ); }
	public function get_total( $context = 'view' ) { return $this->record_total_getter( 'total', $context ); }
	public function get_total_tax( $context = 'view' ) { return $this->record_total_getter( 'total_tax', $context ); }
	public function get_order_key( $context = 'view' ) {
		$this->getter_contexts[] = array( 'order_key', $context );
		return 'view' === $context && null !== $this->view_order_key_override
			? $this->view_order_key_override
			: $this->order_key;
	}
	public function __call( $name, $arguments ) {
		if ( preg_match( '/^get_(billing|shipping)_(.+)$/', $name, $matches ) ) {
			$context = $arguments[0] ?? 'view';
			$this->getter_contexts[] = array( $matches[1] . '_' . $matches[2], $context );
			return $this->addresses[ $matches[1] ][ $matches[2] ] ?? '';
		}

		throw new BadMethodCallException( $name );
	}
}

/**
 * 生成可签发的后台实体订单；每次都建立独立订单项目对象，便于安全变异。
 */
function dentall_test_make_order( $id ) {
	$order = new WC_Order( $id );
	$order->items = array(
		'line_item' => array(
			101 => new WC_Order_Item_Stub(
				array(
					'product_id'   => 10,
					'variation_id' => 0,
					'quantity'     => 2,
					'subtotal'     => '100.00',
					'total'        => '90.00',
					'total_tax'    => '9.00',
					'meta_data'    => array(
						new WC_Meta_Data_Stub(
							array(
								'id'    => 601,
								'key'   => 'Size',
								'value' => 'Large',
							)
						),
					),
				)
			),
		),
		'coupon' => array(
			201 => new WC_Order_Item_Stub(
				array(
					'code'         => 'WELCOME10',
					'discount'     => '10.00',
					'discount_tax' => '1.00',
				)
			),
		),
		'shipping' => array(
			301 => new WC_Order_Item_Stub(
				array(
					'method_id' => 'dentall_manual_quote',
					'total'     => '25.00',
					'total_tax' => '2.50',
				)
			),
		),
		'fee' => array(
			401 => new WC_Order_Item_Stub(
				array(
					'name'      => 'Handling',
					'total'     => '5.00',
					'total_tax' => '0.50',
				)
			),
		),
		'tax' => array(
			501 => new WC_Order_Item_Stub(
				array(
					'rate_id'            => 7,
					'label'              => 'Sales tax',
					'tax_total'          => '9.50',
					'shipping_tax_total' => '2.50',
				)
			),
		),
	);
	$order->addresses = array(
		'billing' => array(
			'first_name' => 'Morgan',
			'last_name'  => 'Chen',
			'company'    => 'DentAll Procurement',
			'country'    => 'US',
			'state'      => 'TX',
			'postcode'   => '73301',
			'city'       => 'Austin',
			'address_1'  => '200 Billing Rd',
			'address_2'  => 'Floor 3',
			'email'      => 'morgan@example.com',
			'phone'      => '+1 555 0200',
		),
		'shipping' => array(
			'first_name' => 'Ava',
			'last_name'  => 'Lee',
			'company'    => 'Westside Dental',
			'country'    => 'US',
			'state'      => 'CA',
			'postcode'   => '90001',
			'city'       => 'Los Angeles',
			'address_1'  => '100 Main St',
			'address_2'  => 'Suite 2',
			'phone'      => '+1 555 0100',
		),
	);
	$order->totals = array(
		'discount_total' => '10.00',
		'discount_tax'   => '1.00',
		'shipping_total' => '25.00',
		'shipping_tax'   => '2.50',
		'cart_tax'       => '9.50',
		'total'          => '132.00',
		'total_tax'      => '12.00',
	);

	return $order;
}

/* shipping-quote.php的四个基础合同在本测试中由订单状态直接模拟。 */
function dentall_core_order_contains_shipping_products( $order ) {
	return $order instanceof WC_Order && $order->contains_shipping;
}

function dentall_core_order_requires_shipping_quote( $order ) {
	return $order instanceof WC_Order
		&& $order->has_status( array( 'pending', 'failed' ) )
		&& dentall_core_order_contains_shipping_products( $order );
}

function dentall_core_order_has_quoted_shipping( $order ) {
	return $order instanceof WC_Order && $order->has_quoted_shipping;
}

function dentall_core_order_has_required_quote_details( $order ) {
	return $order instanceof WC_Order && $order->has_required_quote_details;
}

require dirname( __DIR__, 2 ) . '/app/public/wp-content/plugins/dentall-core/includes/shipping-quote-lifecycle.php';

/**
 * 为订单建立一份固定时间的已签发报价，避免依赖真实邮件发送。
 */
function dentall_test_stamp_quote( $order, $issued_at, $expires_at, $token ) {
	$order->update_meta_data( DENTALL_SHIPPING_QUOTE_ISSUED_AT_META, $issued_at );
	$order->update_meta_data( DENTALL_SHIPPING_QUOTE_EXPIRES_AT_META, $expires_at );
	$order->update_meta_data( DENTALL_SHIPPING_QUOTE_SIGNATURE_META, dentall_core_get_shipping_quote_signature( $order ) );
	$order->update_meta_data( DENTALL_SHIPPING_QUOTE_TOKEN_META, $token );
}

$checks = array();

$admin_physical_order = dentall_test_make_order( 100 );
$checks['admin_physical_order_is_candidate'] = dentall_core_is_shipping_quote_candidate( $admin_physical_order );
$admin_virtual_order                    = dentall_test_make_order( 101 );
$admin_virtual_order->contains_shipping = false;
$checks['admin_virtual_order_is_not_candidate'] = ! dentall_core_is_shipping_quote_candidate( $admin_virtual_order );
$checkout_physical_order              = dentall_test_make_order( 102 );
$checkout_physical_order->created_via = 'checkout';
$checks['checkout_physical_order_is_not_candidate_before_issue'] = ! dentall_core_is_shipping_quote_candidate( $checkout_physical_order );
$checks['non_order_is_not_candidate'] = ! dentall_core_is_shipping_quote_candidate( null );
$display_filtered_candidate = dentall_test_make_order( 103 );
dentall_test_stamp_quote( $display_filtered_candidate, time() - 60, time() + 3600, 'display-filter-token' );
$display_filtered_candidate->created_via = 'checkout';
$display_filtered_candidate->view_meta_overrides[ DENTALL_SHIPPING_QUOTE_ISSUED_AT_META ] = '';
$checks['candidate_uses_raw_lifecycle_meta'] = dentall_core_is_shipping_quote_candidate( $display_filtered_candidate );
$display_filtered_admin = dentall_test_make_order( 104 );
$display_filtered_admin->view_created_via_override = 'checkout';
$checks['candidate_uses_raw_created_via'] = dentall_core_is_shipping_quote_candidate( $display_filtered_admin );
$display_filtered_valid = dentall_test_make_order( 105 );
dentall_test_stamp_quote( $display_filtered_valid, time() - 60, time() + 3600, 'display-filter-valid-token' );
$display_filtered_valid->view_status_override = 'cancelled';
$display_filtered_valid->view_meta_overrides = array(
	DENTALL_SHIPPING_QUOTE_ISSUED_AT_META     => '',
	DENTALL_SHIPPING_QUOTE_EXPIRES_AT_META    => time() - 60,
	DENTALL_SHIPPING_QUOTE_SIGNATURE_META     => '',
	DENTALL_SHIPPING_QUOTE_TOKEN_META         => '',
	DENTALL_SHIPPING_QUOTE_CLOSED_AT_META     => time(),
	DENTALL_SHIPPING_QUOTE_CLOSED_REASON_META => 'filtered',
);
$checks['payment_guard_uses_raw_status_and_lifecycle_meta'] = '' === dentall_core_get_shipping_quote_block_reason( $display_filtered_valid );
$display_filtered_valid->view_order_key_override = 'filtered-order-key';
$_GET = array( 'key' => $display_filtered_valid->order_key );
$checks['classic_key_guard_uses_raw_order_key'] = dentall_core_request_has_valid_quote_order_key( $display_filtered_valid );
$_GET = array();
$payment_filter_registrations = array_values(
	array_filter(
		$test_filters,
		function ( $filter ) {
			return in_array(
				$filter['hook'],
				array(
					'woocommerce_valid_order_statuses_for_payment',
					'woocommerce_valid_order_statuses_for_payment_complete',
				),
				true
			);
		}
	)
);
$checks['payment_guards_register_after_woocommerce_draft_status_filter'] = 2 === count( $payment_filter_registrations )
	&& PHP_INT_MAX === $payment_filter_registrations[0]['priority']
	&& PHP_INT_MAX === $payment_filter_registrations[1]['priority']
	&& 2 === $payment_filter_registrations[0]['accepted_args']
	&& 2 === $payment_filter_registrations[1]['accepted_args'];

$issued_order             = dentall_test_make_order( 110 );
$issued_email             = new WC_Email( 'customer_invoice', $issued_order );
$issued_order->meta[ DENTALL_SHIPPING_QUOTE_CLOSED_AT_META ] = time() - 60;
$issued_order->meta[ DENTALL_SHIPPING_QUOTE_CLOSED_REASON_META ] = 'copied';
$issued_order->view_meta_overrides[ DENTALL_SHIPPING_QUOTE_ISSUED_AT_META ] = time() - 3600;
$issue_started_at         = time();
dentall_core_issue_shipping_quote_after_email( true, 'customer_invoice', $issued_email );
$issue_finished_at        = time();
unset( $issued_order->view_meta_overrides[ DENTALL_SHIPPING_QUOTE_ISSUED_AT_META ] );
$issued_at                = (int) $issued_order->get_meta( DENTALL_SHIPPING_QUOTE_ISSUED_AT_META, true );
$expires_at               = (int) $issued_order->get_meta( DENTALL_SHIPPING_QUOTE_EXPIRES_AT_META, true );
$issued_signature         = (string) $issued_order->get_meta( DENTALL_SHIPPING_QUOTE_SIGNATURE_META, true );
$issued_token             = (string) $issued_order->get_meta( DENTALL_SHIPPING_QUOTE_TOKEN_META, true );
$checks['first_successful_invoice_sets_issued_at'] = $issued_at >= $issue_started_at && $issued_at <= $issue_finished_at;
$checks['first_successful_invoice_sets_72_hour_expiry'] = $issued_at + DENTALL_SHIPPING_QUOTE_LIFETIME === $expires_at;
$checks['first_successful_invoice_sets_signature'] = 64 === strlen( $issued_signature )
	&& hash_equals( $issued_signature, dentall_core_get_shipping_quote_signature( $issued_order ) );
$checks['first_successful_invoice_sets_token'] = '11111111-1111-4111-8111-000000000001' === $issued_token;
$checks['first_successful_invoice_ignores_display_meta_filter'] = 0 < $issued_at;
$checks['first_successful_invoice_clears_inherited_closure'] = ! isset(
	$issued_order->meta[ DENTALL_SHIPPING_QUOTE_CLOSED_AT_META ],
	$issued_order->meta[ DENTALL_SHIPPING_QUOTE_CLOSED_REASON_META ]
);
$checks['first_successful_invoice_saves_once'] = 1 === $issued_order->save_count;
$checks['first_successful_invoice_schedules_once'] = 1 === count( $test_scheduled_actions );
$checks['scheduled_expiry_contract_is_exact'] = array(
	'timestamp' => $expires_at,
	'hook'      => DENTALL_SHIPPING_QUOTE_EXPIRY_HOOK,
	'args'      => array( 'order_id' => 110, 'token' => $issued_token ),
	'group'     => DENTALL_SHIPPING_QUOTE_ACTION_GROUP,
	'unique'    => true,
) === $test_scheduled_actions[0];

$first_issue_meta = $issued_order->meta;
dentall_core_issue_shipping_quote_after_email( true, 'customer_invoice', $issued_email );
$checks['resend_does_not_extend_or_replace_quote'] = $first_issue_meta === $issued_order->meta;
$checks['resend_does_not_save_again'] = 1 === $issued_order->save_count;
$checks['resend_does_not_schedule_again'] = 1 === count( $test_scheduled_actions );
$checks['first_invoice_keeps_recipient_before_quote_is_issued'] = 'buyer@example.test'
	=== dentall_core_guard_shipping_quote_invoice_recipient(
		'buyer@example.test',
		$admin_physical_order,
		new WC_Email( 'customer_invoice', $admin_physical_order )
	);
$missing_shipping_email_order = dentall_test_make_order( 117 );
$missing_shipping_email_order->has_quoted_shipping = false;
$checks['first_invoice_without_positive_shipping_clears_recipient'] = ''
	=== dentall_core_guard_shipping_quote_invoice_recipient(
		'buyer@example.test',
		$missing_shipping_email_order,
		new WC_Email( 'customer_invoice', $missing_shipping_email_order )
	);
$missing_details_email_order = dentall_test_make_order( 118 );
$missing_details_email_order->has_required_quote_details = false;
$checks['first_invoice_without_required_details_clears_recipient'] = ''
	=== dentall_core_guard_shipping_quote_invoice_recipient(
		'buyer@example.test',
		$missing_details_email_order,
		new WC_Email( 'customer_invoice', $missing_details_email_order )
	);
$scheduled_before_incomplete_callback = count( $test_scheduled_actions );
dentall_core_issue_shipping_quote_after_email(
	true,
	'customer_invoice',
	new WC_Email( 'customer_invoice', $missing_details_email_order )
);
$checks['incomplete_invoice_callback_does_not_issue_or_schedule'] = array() === $missing_details_email_order->meta
	&& 0 === $missing_details_email_order->save_count
	&& $scheduled_before_incomplete_callback === count( $test_scheduled_actions );
$checks['valid_issued_quote_keeps_invoice_recipient'] = 'buyer@example.test'
	=== dentall_core_guard_shipping_quote_invoice_recipient(
		'buyer@example.test',
		$issued_order,
		$issued_email
	);
$closed_invoice_order = dentall_test_make_order( 113 );
dentall_test_stamp_quote( $closed_invoice_order, time() - 60, time() + 3600, 'closed-invoice-token' );
$closed_invoice_order->meta[ DENTALL_SHIPPING_QUOTE_CLOSED_AT_META ] = time();
$checks['closed_reopened_quote_clears_invoice_recipient'] = ''
	=== dentall_core_guard_shipping_quote_invoice_recipient(
		'buyer@example.test',
		$closed_invoice_order,
		new WC_Email( 'customer_invoice', $closed_invoice_order )
	);
$expired_invoice_order = dentall_test_make_order( 114 );
dentall_test_stamp_quote( $expired_invoice_order, time() - 7200, time() - 3600, 'expired-invoice-token' );
$checks['expired_quote_clears_invoice_recipient'] = ''
	=== dentall_core_guard_shipping_quote_invoice_recipient(
		'buyer@example.test',
		$expired_invoice_order,
		new WC_Email( 'customer_invoice', $expired_invoice_order )
	);

$failed_email_order = dentall_test_make_order( 111 );
dentall_core_issue_shipping_quote_after_email(
	false,
	'customer_invoice',
	new WC_Email( 'customer_invoice', $failed_email_order )
);
$checks['failed_invoice_delivery_does_not_issue'] = array() === $failed_email_order->meta
	&& 0 === $failed_email_order->save_count
	&& 1 === count( $test_scheduled_actions );
dentall_core_issue_shipping_quote_after_email(
	true,
	'customer_invoice',
	new WC_Email( 'customer_invoice', $failed_email_order )
);
$checks['failed_then_successful_same_order_issues_once'] = 0 < (int) $failed_email_order->get_meta( DENTALL_SHIPPING_QUOTE_ISSUED_AT_META, true )
	&& 1 === $failed_email_order->save_count
	&& 2 === count( $test_scheduled_actions );
$wrong_email_order = dentall_test_make_order( 112 );
dentall_core_issue_shipping_quote_after_email( true, 'new_order', new WC_Email( 'new_order', $wrong_email_order ) );
$checks['wrong_email_type_does_not_issue'] = array() === $wrong_email_order->meta
	&& 0 === $wrong_email_order->save_count
	&& 2 === count( $test_scheduled_actions );

$encoding_failure_order = dentall_test_make_order( 115 );
$test_json_encode_failure = true;
dentall_core_issue_shipping_quote_after_email(
	true,
	'customer_invoice',
	new WC_Email( 'customer_invoice', $encoding_failure_order )
);
$test_json_encode_failure = false;
$checks['signature_encoding_failure_cancels_without_issuing_or_scheduling'] = array() === $encoding_failure_order->meta
	&& 0 === $encoding_failure_order->save_count
	&& 2 === count( $test_scheduled_actions )
	&& 1 === count( $encoding_failure_order->notes )
	&& 'cancelled' === $encoding_failure_order->status
	&& 1 === count( $encoding_failure_order->status_updates );

$checks['expires_minus_one_second_is_payable'] = '' === dentall_core_get_shipping_quote_block_reason(
	$issued_order,
	$expires_at - 1
);
$checks['exact_expiry_second_is_blocked'] = 'expired' === dentall_core_get_shipping_quote_block_reason(
	$issued_order,
	$expires_at
);

$signature_order = dentall_test_make_order( 120 );
$base_signature  = dentall_core_get_shipping_quote_signature( $signature_order );
$checks['signature_reads_raw_edit_context_only'] = 30 === count( $signature_order->getter_contexts )
	&& array() === array_filter(
		$signature_order->getter_contexts,
		function ( $getter ) {
			return 'edit' !== $getter[1];
		}
	);
$signature_mutations = array(
	'product_change_updates_signature' => function ( $order ) { $order->items['line_item'][101]->data['product_id'] = 11; },
	'quantity_change_updates_signature' => function ( $order ) { $order->items['line_item'][101]->data['quantity'] = 3; },
	'line_item_meta_change_updates_signature' => function ( $order ) { $order->items['line_item'][101]->data['meta_data'][0]->data['value'] = 'Small'; },
	'coupon_change_updates_signature' => function ( $order ) { $order->items['coupon'][201]->data['discount'] = '15.00'; },
	'billing_address_change_updates_signature' => function ( $order ) { $order->addresses['billing']['address_1'] = '300 Changed Rd'; },
	'shipping_address_change_updates_signature' => function ( $order ) { $order->addresses['shipping']['address_1'] = '400 Changed St'; },
	'shipping_line_change_updates_signature' => function ( $order ) { $order->items['shipping'][301]->data['total'] = '30.00'; },
	'tax_change_updates_signature' => function ( $order ) { $order->items['tax'][501]->data['tax_total'] = '10.50'; },
	'fee_change_updates_signature' => function ( $order ) { $order->items['fee'][401]->data['total'] = '7.00'; },
	'customer_change_updates_signature' => function ( $order ) { $order->customer_id = 77; },
	'order_total_change_updates_signature' => function ( $order ) { $order->totals['total'] = '134.00'; },
);

foreach ( $signature_mutations as $name => $mutate ) {
	$changed_order = dentall_test_make_order( 121 );
	$mutate( $changed_order );
	$checks[ $name ] = $base_signature !== dentall_core_get_shipping_quote_signature( $changed_order );
}

$test_json_encode_failure = true;
$checks['signature_encoding_failure_blocks_with_empty_signature'] = '' === dentall_core_get_shipping_quote_signature(
	dentall_test_make_order( 124 )
);
$test_json_encode_failure = false;

$changed_quote_order = dentall_test_make_order( 122 );
dentall_test_stamp_quote( $changed_quote_order, time() - 60, time() + 3600, 'changed-token' );
$changed_quote_order->items['line_item'][101]->data['quantity'] = 4;
$checks['changed_signature_blocks_payment'] = 'changed' === dentall_core_get_shipping_quote_block_reason( $changed_quote_order );

$virtualized_order = dentall_test_make_order( 123 );
dentall_test_stamp_quote( $virtualized_order, time() - 60, time() + 3600, 'virtualized-token' );
$virtualized_order->contains_shipping = false;
$checks['issued_quote_remains_candidate_after_product_becomes_virtual'] = dentall_core_is_shipping_quote_candidate( $virtualized_order );
$checks['virtualized_issued_quote_still_expires'] = 'expired' === dentall_core_get_shipping_quote_block_reason(
	$virtualized_order,
	time() + 3600
);

$expired_action_order = dentall_test_make_order( 130 );
dentall_test_stamp_quote( $expired_action_order, time() - DENTALL_SHIPPING_QUOTE_LIFETIME - 60, time() - 60, 'expire-token' );
$test_orders[130] = $expired_action_order;
dentall_core_expire_shipping_quote( 130, 'expire-token' );
$checks['expiry_action_with_correct_token_cancels'] = 'cancelled' === $expired_action_order->status
	&& 1 === count( $expired_action_order->status_updates );
dentall_core_expire_shipping_quote( 130, 'expire-token' );
$checks['repeated_expiry_action_is_idempotent'] = 'cancelled' === $expired_action_order->status
	&& 1 === count( $expired_action_order->status_updates );

$wrong_token_order = dentall_test_make_order( 131 );
dentall_test_stamp_quote( $wrong_token_order, time() - DENTALL_SHIPPING_QUOTE_LIFETIME - 60, time() - 60, 'right-token' );
$test_orders[131] = $wrong_token_order;
dentall_core_expire_shipping_quote( 131, 'wrong-token' );
$checks['expiry_action_with_wrong_token_is_ignored'] = 'pending' === $wrong_token_order->status
	&& array() === $wrong_token_order->status_updates;

$paid_order         = dentall_test_make_order( 132 );
$paid_order->status = 'processing';
dentall_test_stamp_quote( $paid_order, time() - DENTALL_SHIPPING_QUOTE_LIFETIME - 60, time() - 60, 'paid-token' );
$test_orders[132] = $paid_order;
dentall_core_expire_shipping_quote( 132, 'paid-token' );
$checks['expiry_action_does_not_touch_paid_order'] = 'processing' === $paid_order->status
	&& array() === $paid_order->status_updates;

$settled_order = dentall_test_make_order( 140 );
dentall_test_stamp_quote( $settled_order, time() - 60, time() + 3600, 'settled-token' );
dentall_core_unschedule_settled_shipping_quote( 140, 'pending', 'processing', $settled_order );
$checks['leaving_pending_unschedules_exact_action'] = array(
	'hook'  => DENTALL_SHIPPING_QUOTE_EXPIRY_HOOK,
	'args'  => array( 'order_id' => 140, 'token' => 'settled-token' ),
	'group' => DENTALL_SHIPPING_QUOTE_ACTION_GROUP,
) === $test_unscheduled_actions[0];
$checks['leaving_pending_persists_irreversible_closure'] = 0 < (int) $settled_order->get_meta( DENTALL_SHIPPING_QUOTE_CLOSED_AT_META, true )
	&& 'processing' === $settled_order->get_meta( DENTALL_SHIPPING_QUOTE_CLOSED_REASON_META, true )
	&& 1 === $settled_order->save_meta_count;
$settled_order->status = 'pending';
$checks['closed_quote_cannot_be_reopened_to_pending'] = 'closed' === dentall_core_get_shipping_quote_block_reason( $settled_order );
dentall_core_unschedule_settled_shipping_quote( 140, 'pending', 'failed', $settled_order );
$checks['remaining_payable_does_not_unschedule'] = 1 === count( $test_unscheduled_actions );

$payment_statuses = array( 'pending', 'failed' );
$checks['payment_complete_clears_statuses_for_reopened_closed_quote'] = array()
	=== dentall_core_guard_shipping_quote_payment_complete( $payment_statuses, $settled_order );
$valid_payment_order = dentall_test_make_order( 150 );
dentall_test_stamp_quote( $valid_payment_order, time() - 60, time() + 3600, 'valid-payment-token' );
$checks['payment_complete_keeps_statuses_for_valid_quote'] = $payment_statuses
	=== dentall_core_guard_shipping_quote_payment_complete( $payment_statuses, $valid_payment_order );
$expired_payment_order = dentall_test_make_order( 151 );
dentall_test_stamp_quote(
	$expired_payment_order,
	time() - DENTALL_SHIPPING_QUOTE_LIFETIME - 60,
	time() - 60,
	'expired-payment-token'
);
$checks['payment_complete_clears_statuses_for_expired_quote'] = array()
	=== dentall_core_guard_shipping_quote_payment_complete( $payment_statuses, $expired_payment_order );
$changed_payment_order = dentall_test_make_order( 152 );
dentall_test_stamp_quote( $changed_payment_order, time() - 60, time() + 3600, 'changed-payment-token' );
$changed_payment_order->totals['total'] = '999.00';
$checks['payment_complete_clears_statuses_for_changed_quote'] = array()
	=== dentall_core_guard_shipping_quote_payment_complete( $payment_statuses, $changed_payment_order );
$unissued_payment_order = dentall_test_make_order( 153 );
$checks['payment_complete_clears_statuses_for_unissued_quote'] = array()
	=== dentall_core_guard_shipping_quote_payment_complete( $payment_statuses, $unissued_payment_order );
$GLOBALS['wp'] = (object) array( 'query_vars' => array() );
$_GET = array();
$checks['needs_payment_stays_available_while_first_email_is_rendered'] = $payment_statuses
	=== dentall_core_guard_shipping_quote_payment_statuses( $payment_statuses, $unissued_payment_order );
$GLOBALS['wp']->query_vars['order-pay'] = 153;
$_GET = array( 'key' => $unissued_payment_order->get_order_key() );
$checks['needs_payment_clears_statuses_for_unissued_quote'] = array()
	=== dentall_core_guard_shipping_quote_payment_statuses( $payment_statuses, $unissued_payment_order );
$GLOBALS['wp']->query_vars['order-pay'] = 150;
$_GET = array( 'key' => $valid_payment_order->get_order_key() );
$checks['needs_payment_keeps_statuses_for_valid_quote'] = $payment_statuses
	=== dentall_core_guard_shipping_quote_payment_statuses( $payment_statuses, $valid_payment_order );

$classic_missing_key_order = dentall_test_make_order( 160 );
dentall_test_stamp_quote(
	$classic_missing_key_order,
	time() - DENTALL_SHIPPING_QUOTE_LIFETIME - 60,
	time() - 60,
	'classic-missing-key-token'
);
$test_orders[160] = $classic_missing_key_order;
$GLOBALS['wp'] = (object) array( 'query_vars' => array( 'order-pay' => 160 ) );
$_GET = array();
$_POST = array();
$_SERVER['REQUEST_METHOD'] = 'GET';
$notices_before = count( $test_notices );
dentall_core_guard_shipping_quote_order_pay_request();
$checks['classic_guard_missing_key_does_not_change_order'] = 'pending' === $classic_missing_key_order->status
	&& $notices_before === count( $test_notices );

$classic_wrong_key_order = dentall_test_make_order( 161 );
dentall_test_stamp_quote(
	$classic_wrong_key_order,
	time() - DENTALL_SHIPPING_QUOTE_LIFETIME - 60,
	time() - 60,
	'classic-wrong-key-token'
);
$test_orders[161] = $classic_wrong_key_order;
$GLOBALS['wp']->query_vars['order-pay'] = 161;
$_GET = array( 'key' => 'wrong-key' );
dentall_core_guard_shipping_quote_order_pay_request();
$checks['classic_guard_wrong_key_does_not_change_order'] = 'pending' === $classic_wrong_key_order->status
	&& $notices_before === count( $test_notices );

$classic_valid_key_order = dentall_test_make_order( 162 );
dentall_test_stamp_quote(
	$classic_valid_key_order,
	time() - DENTALL_SHIPPING_QUOTE_LIFETIME - 60,
	time() - 60,
	'classic-valid-key-token'
);
$test_orders[162] = $classic_valid_key_order;
$GLOBALS['wp']->query_vars['order-pay'] = 162;
$_GET = array( 'key' => $classic_valid_key_order->get_order_key() );
dentall_core_guard_shipping_quote_order_pay_request();
$checks['classic_guard_correct_key_cancels_expired_order'] = 'cancelled' === $classic_valid_key_order->status
	&& $notices_before + 1 === count( $test_notices )
	&& 'error' === $test_notices[ $notices_before ][1];

$classic_unissued_preview_order = dentall_test_make_order( 163 );
$test_orders[163] = $classic_unissued_preview_order;
$GLOBALS['wp']->query_vars['order-pay'] = 163;
$_GET = array( 'key' => $classic_unissued_preview_order->get_order_key() );
$_POST = array();
$_SERVER['REQUEST_METHOD'] = 'GET';
$preview_notices_before = count( $test_notices );
dentall_core_guard_shipping_quote_order_pay_request();
$checks['classic_get_preview_blocks_without_closing_unissued_draft'] = 'pending' === $classic_unissued_preview_order->status
	&& array() === $classic_unissued_preview_order->status_updates
	&& $preview_notices_before + 1 === count( $test_notices );

$_POST = array( 'woocommerce_pay' => '1' );
$_SERVER['REQUEST_METHOD'] = 'POST';
dentall_core_guard_shipping_quote_order_pay_request();
$checks['classic_post_without_issued_quote_stays_blocked_without_mutation'] = 'pending' === $classic_unissued_preview_order->status
	&& array() === $classic_unissued_preview_order->status_updates;

$rest_missing_key_order = dentall_test_make_order( 170 );
dentall_test_stamp_quote(
	$rest_missing_key_order,
	time() - DENTALL_SHIPPING_QUOTE_LIFETIME - 60,
	time() - 60,
	'rest-missing-key-token'
);
$test_orders[170] = $rest_missing_key_order;
$rest_missing_key_result = dentall_core_guard_shipping_quote_store_api_payment(
	null,
	new WP_REST_Request( '/wc/store/v1/checkout/170', 'POST' ),
	'',
	array()
);
$checks['rest_guard_enforces_authorized_request_without_key'] = $rest_missing_key_result instanceof WP_Error
	&& 409 === $rest_missing_key_result->error_data['dentall_shipping_quote_unpayable']['status']
	&& 'cancelled' === $rest_missing_key_order->status;

$rest_wrong_key_order = dentall_test_make_order( 171 );
dentall_test_stamp_quote(
	$rest_wrong_key_order,
	time() - DENTALL_SHIPPING_QUOTE_LIFETIME - 60,
	time() - 60,
	'rest-wrong-key-token'
);
$test_orders[171] = $rest_wrong_key_order;
$rest_wrong_key_result = dentall_core_guard_shipping_quote_store_api_payment(
	null,
	new WP_REST_Request( '/wc/store/v1/checkout/171', 'POST', array( 'key' => 'wrong-key' ) ),
	'',
	array()
);
$checks['rest_guard_does_not_repeat_permission_callback_key_rules'] = $rest_wrong_key_result instanceof WP_Error
	&& 409 === $rest_wrong_key_result->error_data['dentall_shipping_quote_unpayable']['status']
	&& 'cancelled' === $rest_wrong_key_order->status;

$rest_valid_key_order = dentall_test_make_order( 172 );
dentall_test_stamp_quote(
	$rest_valid_key_order,
	time() - DENTALL_SHIPPING_QUOTE_LIFETIME - 60,
	time() - 60,
	'rest-valid-key-token'
);
$test_orders[172] = $rest_valid_key_order;
$rest_valid_key_result = dentall_core_guard_shipping_quote_store_api_payment(
	null,
	new WP_REST_Request(
		'/wc/store/v1/checkout/172',
		'POST',
		array( 'key' => $rest_valid_key_order->get_order_key() )
	),
	'',
	array()
);
$checks['rest_guard_correct_key_cancels_expired_order'] = $rest_valid_key_result instanceof WP_Error
	&& isset( $rest_valid_key_result->errors['dentall_shipping_quote_unpayable'] )
	&& 409 === $rest_valid_key_result->error_data['dentall_shipping_quote_unpayable']['status']
	&& 'cancelled' === $rest_valid_key_order->status;

$rest_unissued_order = dentall_test_make_order( 173 );
$test_orders[173] = $rest_unissued_order;
$rest_unissued_result = dentall_core_guard_shipping_quote_store_api_payment(
	null,
	new WP_REST_Request( '/wc/store/v1/checkout/173', 'POST' ),
	'',
	array()
);
$checks['rest_guard_blocks_unissued_draft_without_mutation'] = $rest_unissued_result instanceof WP_Error
	&& 409 === $rest_unissued_result->error_data['dentall_shipping_quote_unpayable']['status']
	&& 'pending' === $rest_unissued_order->status
	&& array() === $rest_unissued_order->status_updates;

$deactivation_active = dentall_test_make_order( 180 );
dentall_test_stamp_quote( $deactivation_active, time() - 60, time() + 3600, 'deactivation-active-token' );
$deactivation_active->view_status_override = 'completed';
$deactivation_unissued = dentall_test_make_order( 181 );
$deactivation_unissued->view_created_via_override = 'checkout';
$deactivation_settled = dentall_test_make_order( 182 );
dentall_test_stamp_quote( $deactivation_settled, time() - 60, time() + 3600, 'deactivation-settled-token' );
$deactivation_settled->status = 'completed';
$deactivation_checkout = dentall_test_make_order( 183 );
$deactivation_checkout->created_via = 'checkout';
$test_orders[180] = $deactivation_active;
$test_orders[181] = $deactivation_unissued;
$test_orders[182] = $deactivation_settled;
$test_orders[183] = $deactivation_checkout;
$test_wc_get_orders_results = array( 180, 181, 182, 183 );

dentall_core_deactivate_shipping_quote_lifecycle();
$checks['deactivation_queries_issued_payable_orders_via_wc_api'] = array(
	'type'       => 'shop_order',
	'status'     => array( 'pending', 'failed' ),
	'limit'      => -1,
	'return'     => 'ids',
	'orderby'    => 'ID',
	'order'      => 'ASC',
) === $test_order_queries[0];
$checks['deactivation_cancels_active_issued_quote'] = 'cancelled' === $deactivation_active->status
	&& str_contains( $deactivation_active->status_updates[0]['note'], 'deactivated' );
$checks['deactivation_cancels_unissued_admin_quote_candidate'] = 'cancelled' === $deactivation_unissued->status;
$checks['deactivation_uses_raw_status_and_created_via'] = 'cancelled' === $deactivation_active->status
	&& 'cancelled' === $deactivation_unissued->status;
$checks['deactivation_does_not_change_settled_quote'] = 'completed' === $deactivation_settled->status;
$checks['deactivation_does_not_cancel_checkout_order'] = 'pending' === $deactivation_checkout->status;
$checks['deactivation_unschedules_lifecycle_group'] = array(
	'hook'  => '',
	'args'  => array(),
	'group' => DENTALL_SHIPPING_QUOTE_ACTION_GROUP,
) === $test_unscheduled_groups[0];

$unscheduled_group_count = count( $test_unscheduled_groups );
$test_wc_get_orders_failure = true;
$checks['deactivation_failure_stops_before_task_cleanup'] = false;
try {
	dentall_core_deactivate_shipping_quote_lifecycle();
} catch ( RuntimeException $error ) {
	$checks['deactivation_failure_stops_before_task_cleanup'] = 'TEST wp_die.' === $error->getMessage()
		&& $unscheduled_group_count === count( $test_unscheduled_groups )
		&& 1 === count( $test_wp_die_calls );
}
$test_wc_get_orders_failure = false;

$deactivation_save_failure = dentall_test_make_order( 184 );
dentall_test_stamp_quote( $deactivation_save_failure, time() - 60, time() + 3600, 'deactivation-save-failure-token' );
$deactivation_save_failure->status_update_result = false;
$test_orders[184] = $deactivation_save_failure;
$test_wc_get_orders_results = array( 184 );
$unscheduled_group_count = count( $test_unscheduled_groups );
$wp_die_count = count( $test_wp_die_calls );
$checks['deactivation_status_save_failure_stops_before_task_cleanup'] = false;
try {
	dentall_core_deactivate_shipping_quote_lifecycle();
} catch ( RuntimeException $error ) {
	$checks['deactivation_status_save_failure_stops_before_task_cleanup'] = 'TEST wp_die.' === $error->getMessage()
		&& 'cancelled' === $deactivation_save_failure->status
		&& $unscheduled_group_count === count( $test_unscheduled_groups )
		&& $wp_die_count + 1 === count( $test_wp_die_calls );
}

$test_wc_get_orders_results = array( 185 );
$unscheduled_group_count = count( $test_unscheduled_groups );
$wp_die_count = count( $test_wp_die_calls );
$checks['deactivation_unreadable_query_result_stops_before_task_cleanup'] = false;
try {
	dentall_core_deactivate_shipping_quote_lifecycle();
} catch ( RuntimeException $error ) {
	$checks['deactivation_unreadable_query_result_stops_before_task_cleanup'] = 'TEST wp_die.' === $error->getMessage()
		&& $unscheduled_group_count === count( $test_unscheduled_groups )
		&& $wp_die_count + 1 === count( $test_wp_die_calls );
}

$deactivation_readback_failure = dentall_test_make_order( 186 );
dentall_test_stamp_quote( $deactivation_readback_failure, time() - 60, time() + 3600, 'deactivation-readback-failure-token' );
$test_order_read_sequences[186] = array( $deactivation_readback_failure, false );
$test_wc_get_orders_results = array( 186 );
$unscheduled_group_count = count( $test_unscheduled_groups );
$wp_die_count = count( $test_wp_die_calls );
$checks['deactivation_readback_failure_stops_before_task_cleanup'] = false;
try {
	dentall_core_deactivate_shipping_quote_lifecycle();
} catch ( RuntimeException $error ) {
	$checks['deactivation_readback_failure_stops_before_task_cleanup'] = 'TEST wp_die.' === $error->getMessage()
		&& 'cancelled' === $deactivation_readback_failure->status
		&& $unscheduled_group_count === count( $test_unscheduled_groups )
		&& $wp_die_count + 1 === count( $test_wp_die_calls );
}

$verified_customer_quote = dentall_test_make_order( 187 );
dentall_test_stamp_quote( $verified_customer_quote, time() - 60, time() + 3600, 'verified-customer-token' );
$verified_customer_quote->customer_id = 77;
dentall_core_invalidate_changed_shipping_quote( 187, $verified_customer_quote );
$checks['verified_email_auto_assignment_closes_old_guest_quote'] = 'cancelled' === $verified_customer_quote->status
	&& 1 === count( $verified_customer_quote->status_updates )
	&& str_contains( $verified_customer_quote->status_updates[0]['note'], 'changed after its payment email' );

$failed_checks = array();

foreach ( $checks as $name => $passed ) {
	if ( ! $passed ) {
		$failed_checks[] = $name;
	}
}

if ( $failed_checks ) {
	foreach ( $failed_checks as $name ) {
		fwrite( STDERR, "FAIL: {$name}\n" );
	}

	exit( 1 );
}

echo json_encode(
	array(
		'status'     => 'pass',
		'assertions' => count( $checks ),
	),
	JSON_UNESCAPED_SLASHES
) . PHP_EOL;
