<?php
/**
 * D79客户登录错误归一化纯PHP合同测试。
 *
 * 运行：php project-docs/tests/day79-login-error-unit.php
 */

define( 'ABSPATH', __DIR__ );

$registered_hooks = array();
$valid_nonce      = 'valid-nonce';
$assertions       = 0;

function add_action( $hook, $callback, $priority, $accepted_args ) {
	global $registered_hooks;
	$registered_hooks[] = compact( 'hook', 'callback', 'priority', 'accepted_args' );
}

function add_filter( $hook, $callback, $priority, $accepted_args = 1 ) {
	global $registered_hooks;
	$registered_hooks[] = compact( 'hook', 'callback', 'priority', 'accepted_args' );
}

function wp_unslash( $value ) {
	return $value;
}

function sanitize_text_field( $value ) {
	return is_string( $value ) ? trim( strip_tags( $value ) ) : '';
}

function wp_verify_nonce( $nonce, $action ) {
	global $valid_nonce;
	return 'woocommerce-login' === $action && $valid_nonce === $nonce ? 1 : false;
}

function is_wp_error( $value ) {
	return $value instanceof WP_Error;
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

function assert_same( $expected, $actual, $message ) {
	global $assertions;
	$assertions++;
	if ( $expected !== $actual ) {
		throw new RuntimeException( $message );
	}
}

function set_login_request( $nonce = 'valid-nonce' ) {
	$_SERVER['REQUEST_METHOD'] = 'POST';
	$_POST                     = array(
		'login'                    => 'Log in',
		'username'                 => 'customer@example.test',
		'password'                 => 'not-a-real-password',
		'woocommerce-login-nonce' => $nonce,
	);
}

require dirname( __DIR__, 2 ) . '/app/public/wp-content/plugins/dentall-core/includes/customer-account.php';

assert_same( 'wp_login_failed', $registered_hooks[0]['hook'], '必须监听WordPress登录失败事件。' );
assert_same( 99, $registered_hooks[0]['priority'], '登录失败记录优先级错误。' );
assert_same( 2, $registered_hooks[0]['accepted_args'], 'wp_login_failed参数数量错误。' );
assert_same( 'login_errors', $registered_hooks[1]['hook'], '必须只过滤最终公开文案。' );
assert_same( 99, $registered_hooks[1]['priority'], '公开文案过滤优先级错误。' );

$generic_message = 'The login details are incorrect. Check them and try again.';
foreach ( array( 'invalid_username', 'invalid_email', 'incorrect_password' ) as $error_code ) {
	set_login_request();
	$original = new WP_Error( $error_code, "Sensitive: $error_code" );
	dentall_core_capture_customer_login_failure( 'customer@example.test', $original );
	assert_same( $error_code, $original->get_error_code(), "$error_code 原始审计错误码被改变。" );
	assert_same(
		$generic_message,
		dentall_core_normalize_customer_login_error_message( "Sensitive: $error_code" ),
		"$error_code 公开文案不同。"
	);
}

set_login_request( 'invalid-nonce' );
$original = new WP_Error( 'incorrect_password', 'Sensitive account detail.' );
dentall_core_capture_customer_login_failure( 'customer@example.test', $original );
assert_same(
	'Sensitive account detail.',
	dentall_core_normalize_customer_login_error_message( 'Sensitive account detail.' ),
	'无效Nonce不得进入归一化作用域。'
);

set_login_request();
$empty_field = new WP_Error( 'empty_username', 'Username is required.' );
dentall_core_capture_customer_login_failure( '', $empty_field );
assert_same(
	'Username is required.',
	dentall_core_normalize_customer_login_error_message( 'Username is required.' ),
	'可修正的空字段错误必须保留。'
);

set_login_request();
$locked = new WP_Error( 'incorrect_password', 'Sensitive account detail.' );
$locked->add( 'too_many_attempts', 'Try again later.' );
dentall_core_capture_customer_login_failure( 'customer@example.test', $locked );
assert_same(
	'Try again later.',
	dentall_core_normalize_customer_login_error_message( $locked->get_error_message() ),
	'限频或其他安全错误不得被通用错误覆盖。'
);

$_SERVER['REQUEST_METHOD'] = array( 'POST' );
$_POST                     = array();
assert_same( false, dentall_core_is_customer_login_request(), '畸形请求方法必须安全失败。' );

set_login_request();
$_POST['username'] = array( 'malformed' );
assert_same( false, dentall_core_is_customer_login_request(), '畸形表单字段必须安全失败。' );

echo wp_json_encode( array( 'status' => 'pass', 'assertions' => $assertions ) );

function wp_json_encode( $value ) {
	return json_encode( $value, JSON_UNESCAPED_SLASHES );
}
