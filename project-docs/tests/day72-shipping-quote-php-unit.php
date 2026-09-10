<?php
/**
 * D72人工运费报价Core纯PHP合同测试。
 *
 * 运行：php project-docs/tests/day72-shipping-quote-php-unit.php
 */

define( 'ABSPATH', __DIR__ );
define( 'REST_REQUEST', true );

$test_options = array();
$test_notices = array();
$test_orders  = array();

function add_filter() {}
function add_action() {}
function __( $value ) { return $value; }
function get_option( $key, $default = '' ) { global $test_options; return $test_options[ $key ] ?? $default; }
function sanitize_email( $value ) { return filter_var( $value, FILTER_SANITIZE_EMAIL ); }
function is_email( $value ) { return false !== filter_var( $value, FILTER_VALIDATE_EMAIL ); }
function wp_unslash( $value ) { return $value; }
function sanitize_text_field( $value ) { return is_string( $value ) ? trim( strip_tags( $value ) ) : ''; }
function wp_parse_url( $value, $component ) { return parse_url( $value, $component ); }
function untrailingslashit( $value ) { return rtrim( $value, '/\\' ); }
function wc_add_notice( $message, $type ) { global $test_notices; $test_notices[] = array( $message, $type ); }
function WC() { global $wc_instance; return $wc_instance; }
function wc_get_order( $order_id ) { global $test_orders; return $test_orders[ $order_id ] ?? false; }
function absint( $value ) { return abs( (int) $value ); }

class WC_Admin_Settings {
	public static array $errors = array();
	public static function add_error( $message ) { self::$errors[] = $message; }
}

class WC_Product {
	public function __construct( private bool $shipping ) {}
	public function needs_shipping() { return $this->shipping; }
}

class WC_Cart {
	public function __construct( private bool $empty, private bool $shipping ) {}
	public function is_empty() { return $this->empty; }
	public function get_cart() { return array( array( 'data' => new WC_Product( $this->shipping ) ) ); }
}

class WP_Error {
	public array $errors = array();
	public array $error_data = array();
	public function __construct( $code = '', $message = '', $data = null ) {
		if ( '' !== $code ) {
			$this->add( $code, $message );
			$this->error_data[ $code ] = $data;
		}
	}
	public function add( $code, $message ) { $this->errors[ $code ][] = $message; }
}

class WP_REST_Request {
	public function __construct( private string $route, private string $method = 'GET', private array $params = array() ) {}
	public function get_route() { return $this->route; }
	public function get_method() { return $this->method; }
	public function get_param( $key ) { return $this->params[ $key ] ?? null; }
}

class WC_Order_Item_Product {
	public function __construct( private WC_Product $product ) {}
	public function get_product() { return $this->product; }
}

class WC_Order {
	public function __construct(
		private bool $payment,
		private bool $shipping_line,
		private bool $physical_product,
		private array $shipping_address
	) {}
	public function needs_payment() { return $this->payment; }
	public function get_items( $type ) {
		if ( 'shipping' === $type ) {
			return $this->shipping_line ? array( new stdClass() ) : array();
		}

		return 'line_item' === $type
			? array( new WC_Order_Item_Product( new WC_Product( $this->physical_product ) ) )
			: array();
	}
	public function get_shipping_country() { return $this->shipping_address['country'] ?? ''; }
	public function get_shipping_company() { return $this->shipping_address['company'] ?? ''; }
	public function get_shipping_state() { return $this->shipping_address['state'] ?? ''; }
	public function get_shipping_postcode() { return $this->shipping_address['postcode'] ?? ''; }
	public function get_shipping_city() { return $this->shipping_address['city'] ?? ''; }
	public function get_shipping_address_1() { return $this->shipping_address['address_1'] ?? ''; }
	public function get_shipping_address_2() { return $this->shipping_address['address_2'] ?? ''; }
	public function get_billing_country() { return $this->shipping_address['country'] ?? ''; }
	public function get_billing_state() { return $this->shipping_address['state'] ?? ''; }
	public function get_billing_postcode() { return $this->shipping_address['postcode'] ?? ''; }
	public function get_billing_city() { return $this->shipping_address['city'] ?? ''; }
}

require dirname( __DIR__, 2 ) . '/app/public/wp-content/plugins/dentall-core/includes/shipping-quote.php';

$checks = array();

$test_options[ DENTALL_SHIPPING_QUOTE_EMAIL_OPTION ] = 'quotes@example.com';
$checks['valid_email'] = 'quotes@example.com' === dentall_core_get_shipping_quote_email();
$test_options[ DENTALL_SHIPPING_QUOTE_EMAIL_OPTION ] = 'not-an-email';
$checks['invalid_email_has_no_admin_fallback'] = '' === dentall_core_get_shipping_quote_email();
$test_options[ DENTALL_SHIPPING_QUOTE_EMAIL_OPTION ] = 'quotes @example.com';
$checks['mutated_stored_email_is_rejected'] = '' === dentall_core_get_shipping_quote_email();
$test_options[ DENTALL_SHIPPING_QUOTE_EMAIL_OPTION ] = array( 'unexpected' );
$checks['non_string_email_has_no_warning_or_fallback'] = '' === dentall_core_get_shipping_quote_email();
$test_options[ DENTALL_SHIPPING_QUOTE_EMAIL_OPTION ] = 'old@example.com';
$checks['invalid_save_keeps_previous_value'] = 'old@example.com' === dentall_core_sanitize_shipping_quote_email( '', array(), 'invalid address' );
$checks['sanitizer_changed_save_keeps_previous_value'] = 'old@example.com' === dentall_core_sanitize_shipping_quote_email( '', array(), 'new @example.com' );
$checks['non_string_save_keeps_previous_value'] = 'old@example.com' === dentall_core_sanitize_shipping_quote_email( '', array(), array( 'unexpected' ) );
$checks['invalid_save_reports_error'] = 2 === count( WC_Admin_Settings::$errors );
$checks['empty_save_is_allowed'] = '' === dentall_core_sanitize_shipping_quote_email( '', array(), '' );

$sections = dentall_core_add_shipping_quote_section( array( '' => 'Shipping zones' ) );
$checks['settings_section_registered'] = 'Manual shipping quote' === $sections['dentall_shipping_quote'];
$settings = dentall_core_get_shipping_quote_settings( array(), 'dentall_shipping_quote' );
$checks['email_setting_registered'] = DENTALL_SHIPPING_QUOTE_EMAIL_OPTION === $settings[1]['id']
	&& 'email' === $settings[1]['type'];
$checks['other_shipping_settings_preserved'] = array( array( 'id' => 'existing' ) )
	=== dentall_core_get_shipping_quote_settings( array( array( 'id' => 'existing' ) ), 'options' );

$physical_cart = new WC_Cart( false, true );
$virtual_cart  = new WC_Cart( false, false );
$empty_cart    = new WC_Cart( true, true );
$checks['physical_cart_requires_quote'] = dentall_core_cart_requires_shipping_quote( $physical_cart );
$checks['virtual_cart_keeps_normal_checkout'] = ! dentall_core_cart_requires_shipping_quote( $virtual_cart );
$checks['empty_cart_never_requires_quote'] = ! dentall_core_cart_requires_shipping_quote( $empty_cart );
$wc_instance = (object) array( 'cart' => $physical_cart );
dentall_core_block_unquoted_classic_checkout();
$checks['classic_checkout_adds_blocking_error'] = 1 === count( $test_notices )
	&& 'error' === $test_notices[0][1];
$schema = dentall_core_get_shipping_quote_store_api_schema();
$checks['store_api_schema_is_readonly_boolean'] = 'boolean' === $schema['required']['type']
	&& true === $schema['required']['readonly'];

$GLOBALS['wp'] = (object) array( 'query_vars' => array( 'rest_route' => '/wc/store/v1/checkout' ) );
$checks['cart_checkout_route_detected'] = dentall_core_is_cart_checkout_store_api_request();
$GLOBALS['wp']->query_vars['rest_route'] = '/wc/store/checkout';
$checks['unversioned_cart_checkout_route_detected'] = dentall_core_is_cart_checkout_store_api_request();
$GLOBALS['wp']->query_vars['rest_route'] = '/WC/STORE/V1/CHECKOUT';
$checks['mixed_case_cart_checkout_route_detected'] = dentall_core_is_cart_checkout_store_api_request();
$errors = new WP_Error();
dentall_core_block_unquoted_store_api_checkout( $errors, $physical_cart );
$checks['unversioned_store_api_physical_cart_blocked'] = isset( $errors->errors['dentall_shipping_quote_required'] );

$GLOBALS['wp']->query_vars['rest_route'] = '/wc/store/v1/checkout';
$errors = new WP_Error();
dentall_core_block_unquoted_store_api_checkout( $errors, $physical_cart );
$checks['versioned_store_api_physical_cart_blocked'] = isset( $errors->errors['dentall_shipping_quote_required'] );

$GLOBALS['wp']->query_vars['rest_route'] = '/wc/store/v1/checkout/123';
$checks['order_pay_style_route_not_blocked'] = ! dentall_core_is_cart_checkout_store_api_request();
$errors = new WP_Error();
dentall_core_block_unquoted_store_api_checkout( $errors, $physical_cart );
$checks['non_cart_checkout_route_has_no_error'] = array() === $errors->errors;

$GLOBALS['wp']->query_vars['rest_route'] = '/wc/store/v1/cart';
$errors = new WP_Error();
dentall_core_block_unquoted_store_api_checkout( $errors, $physical_cart );
$checks['cart_read_route_has_no_error'] = array() === $errors->errors;

$GLOBALS['wp']->query_vars['rest_route'] = array( '/wc/store/v1/checkout' );
$checks['non_string_route_is_not_treated_as_checkout'] = ! dentall_core_is_cart_checkout_store_api_request();

$GLOBALS['wp']->query_vars['rest_route'] = '/wc/store/v1/batch';
$outer_request = new WP_REST_Request( '/wc/store/v1/batch' );
$child_request = new WP_REST_Request( '/wc/store/checkout' );
dentall_core_track_rest_route_before_callback( null, array(), $outer_request );
dentall_core_track_rest_route_before_callback( null, array(), $child_request );
$checks['batch_checkout_subrequest_detected'] = dentall_core_is_cart_checkout_store_api_request();
dentall_core_track_rest_route_after_callback( null, array(), $child_request );
$checks['batch_outer_route_restored'] = ! dentall_core_is_cart_checkout_store_api_request();
dentall_core_track_rest_route_after_callback( null, array(), $outer_request );

$GLOBALS['wp']->query_vars['rest_route'] = '/wc/agentic/v1/checkout_sessions/session-123/complete';
$checks['agentic_checkout_completion_detected'] = dentall_core_is_cart_checkout_store_api_request();

$quoted_address = array(
	'country'   => 'US',
	'state'     => 'CA',
	'postcode'  => '90001',
	'city'      => 'Los Angeles',
	'address_1' => '100 Main St',
	'address_2' => '',
);
$test_orders[91] = new WC_Order( true, true, true, $quoted_address );
$same_address_request = new WP_REST_Request(
	'/wc/store/v1/checkout/91',
	'POST',
	array( 'shipping_address' => $quoted_address )
);
$checks['quoted_order_same_address_allowed'] = null === dentall_core_lock_quoted_order_shipping_address(
	null,
	$same_address_request,
	'',
	array()
);
$changed_address         = $quoted_address;
$changed_address['state'] = 'NY';
$changed_address_request = new WP_REST_Request(
	'/wc/store/checkout/91',
	'POST',
	array( 'shipping_address' => $changed_address )
);
$address_error = dentall_core_lock_quoted_order_shipping_address( null, $changed_address_request, '', array() );
$checks['quoted_order_changed_address_blocked'] = $address_error instanceof WP_Error
	&& isset( $address_error->errors['dentall_shipping_quote_address_locked'] );
$mixed_case_changed_address_request = new WP_REST_Request(
	'/WC/STORE/V1/CHECKOUT/91',
	'POST',
	array( 'shipping_address' => $changed_address )
);
$mixed_case_address_error = dentall_core_lock_quoted_order_shipping_address(
	null,
	$mixed_case_changed_address_request,
	'',
	array()
);
$checks['mixed_case_quoted_order_changed_address_blocked'] = $mixed_case_address_error instanceof WP_Error
	&& isset( $mixed_case_address_error->errors['dentall_shipping_quote_address_locked'] );
$test_options['woocommerce_tax_based_on'] = 'billing';
$changed_billing                        = $quoted_address;
$changed_billing['state']               = 'TX';
$changed_billing_request = new WP_REST_Request(
	'/wc/store/v1/checkout/91',
	'POST',
	array(
		'shipping_address' => $quoted_address,
		'billing_address'  => $changed_billing,
	)
);
$billing_error = dentall_core_lock_quoted_order_shipping_address( null, $changed_billing_request, '', array() );
$checks['billing_tax_location_change_blocked'] = $billing_error instanceof WP_Error
	&& isset( $billing_error->errors['dentall_shipping_quote_address_locked'] );
$test_options['woocommerce_tax_based_on'] = 'shipping';
$test_orders[92] = new WC_Order( true, false, true, $quoted_address );
$unquoted_request = new WP_REST_Request(
	'/wc/store/v1/checkout/92',
	'POST',
	array( 'shipping_address' => $changed_address )
);
$unquoted_order_error = dentall_core_lock_quoted_order_shipping_address( null, $unquoted_request, '', array() );
$checks['physical_order_without_shipping_line_blocked'] = $unquoted_order_error instanceof WP_Error
	&& isset( $unquoted_order_error->errors['dentall_shipping_quote_required'] );
$test_orders[93] = new WC_Order( true, false, false, $quoted_address );
$virtual_order_request = new WP_REST_Request(
	'/wc/store/v1/checkout/93',
	'POST',
	array( 'shipping_address' => $changed_address )
);
$checks['virtual_order_without_shipping_line_allowed'] = null === dentall_core_lock_quoted_order_shipping_address(
	null,
	$virtual_order_request,
	'',
	array()
);

$GLOBALS['wp']->query_vars['rest_route'] = '/wc/store/v1/checkout';
$errors = new WP_Error();
dentall_core_block_unquoted_store_api_checkout( $errors, $virtual_cart );
$checks['virtual_store_api_checkout_allowed'] = array() === $errors->errors;

foreach ( $checks as $name => $passed ) {
	if ( ! $passed ) {
		fwrite( STDERR, "FAIL: {$name}\n" );
		exit( 1 );
	}
}

echo json_encode(
	array(
		'status'     => 'pass',
		'assertions' => count( $checks ),
	),
	JSON_UNESCAPED_SLASHES
) . PHP_EOL;
