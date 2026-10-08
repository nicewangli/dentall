<?php
/**
 * D81/D82 客户资料和默认地址安全合同测试。
 *
 * 运行：php project-docs/tests/day81-day82-account-unit.php
 */

define( 'ABSPATH', __DIR__ );
define( 'DENTALL_SHIPPING_QUOTE_ALLOWED_COUNTRIES', array( 'US', 'CA', 'AU' ) );

$hooks      = array();
$notices    = array();
$assertions = 0;
$current   = null;

function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
	global $hooks;
	$hooks[ $hook ] = array( $callback, $priority, $accepted_args );
}

function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
	add_action( $hook, $callback, $priority, $accepted_args );
}

function is_wp_error( $value ) {
	return $value instanceof WP_Error;
}

function __( $message ) {
	return $message;
}

function esc_html__( $message ) {
	return htmlspecialchars( $message, ENT_QUOTES, 'UTF-8' );
}

function wp_get_current_user() {
	global $current;
	return $current;
}

function get_current_user_id() {
	return wp_get_current_user()->ID;
}

function WC() {
	static $woocommerce;
	if ( null === $woocommerce ) {
		$woocommerce = (object) array( 'countries' => new Test_Countries() );
	}
	return $woocommerce;
}

function wc_add_notice( $message, $type = 'success', $data = array() ) {
	global $notices;
	$notices[] = compact( 'message', 'type', 'data' );
}

class WP_Error {
	private array $errors = array();

	public function __construct( $code = '', $message = '', $data = null ) {
		unset( $data );
		if ( '' !== $code ) {
			$this->add( $code, $message );
		}
	}

	public function add( $code, $message ) {
		$this->errors[ $code ] = $message;
	}

	public function get_error_codes() {
		return array_keys( $this->errors );
	}

	public function get_error_code() {
		return array_key_first( $this->errors );
	}
}

class Test_User {
	public function __construct( public int $ID, public string $user_email, public array $roles ) {}

	public function exists() {
		return 0 !== $this->ID;
	}
}

class WP_REST_Request {
	public function __construct( private string $method, private string $route, private array $params ) {}

	public function get_method() {
		return $this->method;
	}

	public function get_route() {
		return $this->route;
	}

	public function get_param( $name ) {
		return $this->params[ $name ] ?? null;
	}
}

class Test_Countries {
	public function get_shipping_countries() {
		return array( 'US' => 'United States', 'CA' => 'Canada', 'AU' => 'Australia', 'GB' => 'United Kingdom' );
	}

	public function get_allowed_countries() {
		return array( 'US' => 'United States', 'FR' => 'France', 'GB' => 'United Kingdom' );
	}
}

class WC_Customer {
	public function __construct( private int $id, private string $billing_country, private string $shipping_country ) {}

	public function get_id() {
		return $this->id;
	}

	public function get_billing_country( $context = 'view' ) {
		return $this->billing_country;
	}

	public function get_shipping_country( $context = 'view' ) {
		return $this->shipping_country;
	}
}

function same( $expected, $actual, $message ) {
	global $assertions;
	++$assertions;
	if ( $expected !== $actual ) {
		throw new RuntimeException( $message . '，实际：' . var_export( $actual, true ) );
	}
}

function address_errors( $type, $country, $user_id = 81, $customer_id = 81 ) {
	global $notices;
	$notices = array();
	$customer = 'shipping' === $type
		? new WC_Customer( $customer_id, 'US', $country )
		: new WC_Customer( $customer_id, $country, 'US' );
	dentall_core_validate_customer_address_country( $user_id, $type, array(), $customer );
	return array_column( $notices, 'message' );
}

require dirname( __DIR__, 2 ) . '/app/public/wp-content/plugins/dentall-core/includes/customer-account.php';

$current = new Test_User( 81, 'verified@example.test', array( 'customer' ) );
same( array( 'dentall_core_validate_customer_account_details', 10, 2 ), $hooks['woocommerce_save_account_details_errors'] ?? null, '缺少 Woo 资料保存前校验钩子' );
same( array( 'dentall_core_guard_customer_rest_account_update', 10, 3 ), $hooks['rest_request_before_callbacks'] ?? null, '缺少 REST 旁路拦截钩子' );
same( array( 'dentall_core_validate_customer_address_country', 10, 4 ), $hooks['woocommerce_after_save_address_validation'] ?? null, '缺少地址保存前校验钩子' );

$user = (object) array( 'ID' => 81, 'user_email' => 'changed@example.test', 'user_pass' => 'LongPassword12!' );
$errors = new WP_Error();
dentall_core_validate_customer_account_details( $errors, $user );
same( array( 'dentall_account_email_locked' ), $errors->get_error_codes(), '伪造表单更换登录邮箱未被拒绝' );

$user->user_email = 'verified@example.test';
$user->user_pass  = 'ElevenChars';
$errors = new WP_Error();
dentall_core_validate_customer_account_details( $errors, $user );
same( array( 'dentall_account_password_too_short' ), $errors->get_error_codes(), '不足 12 字符的资料改密未被拒绝' );

$user->user_pass = 'TwelveChars!';
$errors = new WP_Error();
dentall_core_validate_customer_account_details( $errors, $user );
same( array(), $errors->get_error_codes(), '至少 12 字符的资料改密被错误拒绝' );

$user->ID = 82;
$errors = new WP_Error();
dentall_core_validate_customer_account_details( $errors, $user );
same( array( 'dentall_account_owner' ), $errors->get_error_codes(), '其他账户资料被允许进入保存流程' );

$rest = static fn( $method, $route, $params = array() ) => dentall_core_guard_customer_rest_account_update( null, array(), new WP_REST_Request( $method, $route, $params ) );
same( 'dentall_account_email_locked', $rest( 'POST', '/wp/v2/users/me', array( 'email' => 'changed@example.test' ) )->get_error_code(), 'REST /me 可更换邮箱' );
same( 'dentall_account_email_locked', $rest( 'PATCH', '/wp/v2/users/81', array( 'email' => 'changed@example.test' ) )->get_error_code(), 'REST 用户 ID 可更换邮箱' );
same( 'dentall_account_email_locked', $rest( 'PATCH', '/wp/v2/users/081', array( 'email' => 'changed@example.test' ) )->get_error_code(), 'REST 前导零 ID 可绕过邮箱限制' );
same( 'dentall_account_password_form', $rest( 'PUT', '/wp/v2/users/me', array( 'password' => 'TwelveChars!' ) )->get_error_code(), 'REST 绕过旧密码验证' );
same( null, $rest( 'POST', '/wp/v2/users/me', array( 'name' => 'TEST Customer' ) ), 'REST 无关资料更新被拦截' );
same( null, $rest( 'GET', '/wp/v2/users/me', array( 'email' => 'changed@example.test' ) ), 'REST 只读请求被拦截' );
same( 'dentall_account_email_locked', $rest( 'POST', '/wp/v2/users/82', array( 'id' => 81, 'email' => 'changed@example.test' ) )->get_error_code(), '请求 ID 覆盖路径 ID 可绕过邮箱限制' );
same( 'dentall_account_password_form', $rest( 'POST', '/wp/v2/users/82', array( 'id' => 81, 'password' => 'TwelveChars!' ) )->get_error_code(), '请求 ID 覆盖路径 ID 可绕过旧密码验证' );
$previous_error = new WP_Error( 'earlier_rest_error', 'Stopped before account guard.' );
same( $previous_error, dentall_core_guard_customer_rest_account_update( $previous_error, array(), new WP_REST_Request( 'POST', '/wp/v2/users/me', array( 'email' => 'changed@example.test' ) ) ), '前置 REST 错误被覆盖' );
$current = new Test_User( 1, 'manager@example.test', array( 'administrator' ) );
same( null, $rest( 'POST', '/wp/v2/users/me', array( 'email' => 'changed@example.test' ) ), '管理员资料更新被客户规则拦截' );
$current = new Test_User( 81, 'verified@example.test', array( 'customer' ) );

foreach ( array( 'US', 'CA', 'AU' ) as $country ) {
	same( array(), address_errors( 'shipping', $country ), $country . ' 默认 Shipping 地址被错误拒绝' );
}
same( array( 'Choose an available shipping country.' ), address_errors( 'shipping', 'GB' ), '界面可选但业务不支持的 Shipping 国家未被拒绝' );
same( array( 'Choose an available shipping country.' ), address_errors( 'shipping', 'XX' ), '伪造的 Shipping 国家未被拒绝' );
same( array(), address_errors( 'billing', 'FR' ), '合法境外 Billing 国家被错误限制' );
same( array( 'Choose a valid billing country.' ), address_errors( 'billing', 'XX' ), '伪造的 Billing 国家未被拒绝' );
same( array( 'We could not save this address.' ), address_errors( 'billing', 'FR', 82 ), '其他客户默认地址通过当前用户校验' );
same( array( 'We could not save this address.' ), address_errors( 'shipping', 'US', 81, 82 ), '客户对象 ID 不匹配仍可保存' );

echo json_encode( array( 'status' => 'pass', 'assertions' => $assertions ), JSON_UNESCAPED_SLASHES ) . PHP_EOL;
