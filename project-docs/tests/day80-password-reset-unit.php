<?php
/**
 * D80客户找回密码安全边界纯PHP合同测试。
 *
 * 运行：php project-docs/tests/day80-password-reset-unit.php
 */

define( 'ABSPATH', __DIR__ );

$registered_hooks = array();
$valid_nonce      = 'valid-nonce';
$endpoint_active  = false;
$assertions       = 0;

function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
	global $registered_hooks;
	$registered_hooks[] = compact( 'hook', 'callback', 'priority', 'accepted_args' );
}

function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
	global $registered_hooks;
	$registered_hooks[] = compact( 'hook', 'callback', 'priority', 'accepted_args' );
}

function wp_unslash( $value ) {
	return $value;
}

function sanitize_text_field( $value ) {
	return is_string( $value ) ? trim( strip_tags( $value ) ) : '';
}

function sanitize_user( $value ) {
	$value = preg_replace( '/%[a-fA-F0-9]{2}/', '', $value );
	return preg_replace( '/[^a-zA-Z0-9 _\-@.]/', '', $value );
}

function wp_verify_nonce( $nonce, $action ) {
	global $valid_nonce;
	return $valid_nonce === $nonce && in_array( $action, array( 'woocommerce-login', 'lost_password' ), true ) ? 1 : false;
}

function wp_salt( $scheme ) {
	return 'auth' === $scheme ? 'D80 unit-test salt' : '';
}

function is_wp_error( $value ) {
	return $value instanceof WP_Error;
}

function is_wc_endpoint_url( $endpoint ) {
	global $endpoint_active;
	return 'lost-password' === $endpoint && $endpoint_active;
}

function __( $message ) {
	return $message;
}

class WP_Error {
	private array $errors = array();

	public function __construct( $code = '', $message = '' ) {
		if ( '' !== $code ) {
			$this->add( $code, $message );
		}
	}

	public function add( $code, $message ) {
		$this->errors[ $code ][] = $message;
	}

	public function get_error_codes() {
		return array_keys( $this->errors );
	}

	public function get_error_code() {
		return array_key_first( $this->errors );
	}

	public function get_error_message( $code = '' ) {
		$code = '' !== $code ? $code : $this->get_error_code();
		return isset( $this->errors[ $code ][0] ) ? $this->errors[ $code ][0] : '';
	}
}

class WC_Rate_Limiter {
	public static array $limits = array();
	public static int $set_calls = 0;

	public static function retried_too_soon( $action_id ) {
		return isset( self::$limits[ $action_id ] );
	}

	public static function set_rate_limit( $action_id, $delay ) {
		self::$set_calls++;
		self::$limits[ $action_id ] = $delay;
		return true;
	}
}

function assert_same( $expected, $actual, $message ) {
	global $assertions;
	$assertions++;
	if ( $expected !== $actual ) {
		throw new RuntimeException( $message );
	}
}

function assert_true( $actual, $message ) {
	assert_same( true, (bool) $actual, $message );
}

function find_hook( $hook, $callback ) {
	global $registered_hooks;
	foreach ( $registered_hooks as $registered ) {
		if ( $hook === $registered['hook'] && $callback === $registered['callback'] ) {
			return $registered;
		}
	}
	return null;
}

function set_lost_password_request( $login = 'customer@example.test', $nonce = 'valid-nonce' ) {
	$_POST = array(
		'wc_reset_password'              => 'true',
		'user_login'                    => $login,
		'woocommerce-lost-password-nonce' => $nonce,
	);
	$_REQUEST = $_POST;
}

require dirname( __DIR__, 2 ) . '/app/public/wp-content/plugins/dentall-core/includes/customer-account.php';

$handler = find_hook( 'wp_loaded', 'dentall_core_process_customer_lost_password' );
assert_same( 19, $handler['priority'] ?? null, '找回密码处理必须先于WooCommerce默认优先级20执行。' );
assert_true( find_hook( 'login_form_lostpassword', 'dentall_core_redirect_core_lost_password' ), '缺少核心lostpassword入口归一。' );
assert_true( find_hook( 'login_form_retrievepassword', 'dentall_core_redirect_core_lost_password' ), '缺少核心retrievepassword入口归一。' );
assert_true( find_hook( 'validate_password_reset', 'dentall_core_validate_customer_reset_password' ), '缺少服务端新密码校验。' );

set_lost_password_request();
assert_same( true, dentall_core_is_customer_lost_password_request(), '有效WooCommerce找回请求未被识别。' );
set_lost_password_request( 'customer@example.test', 'invalid-nonce' );
assert_same( false, dentall_core_is_customer_lost_password_request(), '无效Nonce进入了统一结果处理。' );
set_lost_password_request();
unset( $_POST['woocommerce-lost-password-nonce'], $_REQUEST['woocommerce-lost-password-nonce'] );
$_REQUEST['_wpnonce'] = 'valid-nonce';
assert_same( true, dentall_core_is_customer_lost_password_request(), 'WooCommerce支持的_wpnonce备用字段未进入统一处理。' );
set_lost_password_request( '' );
assert_same( false, dentall_core_is_customer_lost_password_request(), '空账号线索不应被吞掉可修正错误。' );
set_lost_password_request();
$_POST['user_login'] = array( 'malformed' );
assert_same( false, dentall_core_is_customer_lost_password_request(), '畸形账号字段未安全失败。' );

$_SERVER['REMOTE_ADDR'] = '203.0.113.80';
$limits                 = dentall_core_password_reset_rate_limits( 'Customer@Example.Test' );
assert_same( 2, count( $limits ), '有效来源应生成身份与来源两道限频。' );
assert_same( array( 60, 10 ), array_values( $limits ), '限频冷却时间与确认范围不符。' );
$stored_keys = implode( ' ', array_keys( $limits ) );
assert_same( false, str_contains( $stored_keys, 'customer@example.test' ), '限频键泄露了邮箱明文。' );
assert_same( false, str_contains( $stored_keys, '203.0.113.80' ), '限频键泄露了IP明文。' );
assert_same( false, dentall_core_throttle_password_reset( $limits ), '首次请求被错误限频。' );
assert_same( 2, WC_Rate_Limiter::$set_calls, '首次请求没有写入两道限频。' );
assert_same( true, dentall_core_throttle_password_reset( $limits ), '重复请求没有命中限频。' );
assert_same( 2, WC_Rate_Limiter::$set_calls, '受限重试刷新了冷却时间。' );

$equivalent_limits = dentall_core_password_reset_rate_limits( 'Customer%00@Example.Test' );
assert_same( array_key_first( $limits ), array_key_first( $equivalent_limits ), 'Woo等价账号输入生成了不同身份限频键。' );
$other_limits = dentall_core_password_reset_rate_limits( 'other@example.test' );
assert_same( true, dentall_core_throttle_password_reset( $other_limits ), '同一来源更换账号线索绕过了来源限频。' );
assert_same( 2, WC_Rate_Limiter::$set_calls, '来源已受限时仍写入了新身份限频键。' );
assert_same( false, isset( WC_Rate_Limiter::$limits[ array_key_first( $other_limits ) ] ), '来源已受限时创建了新身份限频记录。' );

$_SERVER['REMOTE_ADDR'] = 'malformed-ip';
assert_same( 1, count( dentall_core_password_reset_rate_limits( 'same@example.test' ) ), '畸形来源IP不应写入限频键。' );

$endpoint_active = false;
assert_same(
	'Password reset email has been sent.',
	dentall_core_generic_password_reset_heading( 'Password reset email has been sent.', 'Password reset email has been sent.' ),
	'非目标端点不应替换WooCommerce翻译。'
);
$endpoint_active = true;
assert_same(
	'Your password reset request has been received.',
	dentall_core_generic_password_reset_heading( 'Password reset email has been sent.', 'Password reset email has been sent.' ),
	'找回确认标题未归一化。'
);
assert_same(
	'If an account matches the details provided, password reset instructions will be sent to the email address on file. Please allow several minutes before trying again.',
	dentall_core_generic_password_reset_message( 'Original message.' ),
	'找回确认说明未归一化。'
);

$errors = new WP_Error();
$_POST  = array(
	'wc_reset_password' => 'true',
	'password_1'        => 'short-pass',
	'password_2'        => 'short-pass',
);
dentall_core_validate_customer_reset_password( $errors );
assert_same( 'dentall_password_too_short', $errors->get_error_code(), '不足12字符的新密码未被服务端拒绝。' );

$errors              = new WP_Error();
$_POST['password_1'] = '十二字符密码测试安全边界一二三';
$_POST['password_2'] = $_POST['password_1'];
dentall_core_validate_customer_reset_password( $errors );
assert_same( null, $errors->get_error_code(), '不少于12个Unicode字符的新密码被错误拒绝。' );

$errors              = new WP_Error();
$_POST['password_1'] = 'Exactly-12!!';
$_POST['password_2'] = $_POST['password_1'];
dentall_core_validate_customer_reset_password( $errors );
assert_same( null, $errors->get_error_code(), '12字符边界的新密码被错误拒绝。' );

echo json_encode( array( 'status' => 'pass', 'assertions' => $assertions ), JSON_UNESCAPED_SLASHES );
