<?php
/**
 * 客户账户的跨主题安全规则。
 */

defined( 'ABSPATH' ) || exit;

/**
 * 判断当前认证是否来自WooCommerce“我的账户”登录表单。
 *
 * 表单处理发生在主查询建立前，不能可靠使用is_account_page()。WooCommerce专用字段、
 * 提交按钮和有效Nonce共同限定作用域，避免改变wp-login.php或其他插件的登录反馈。
 *
 * @return bool
 */
function dentall_core_is_customer_login_request() {
	$request_method = isset( $_SERVER['REQUEST_METHOD'] ) && is_string( $_SERVER['REQUEST_METHOD'] )
		? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) )
		: '';

	if ( 'POST' !== strtoupper( $request_method ) ) {
		return false;
	}

	if (
		! isset( $_POST['login'], $_POST['username'], $_POST['password'], $_POST['woocommerce-login-nonce'] )
		|| ! is_string( $_POST['login'] )
		|| ! is_string( $_POST['username'] )
		|| ! is_string( $_POST['password'] )
		|| ! is_string( $_POST['woocommerce-login-nonce'] )
	) {
		return false;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['woocommerce-login-nonce'] ) );

	return (bool) wp_verify_nonce( $nonce, 'woocommerce-login' );
}

/**
 * 保存当前WooCommerce登录请求最终允许公开的错误文案。
 *
 * 不传参数时只读；显式传入null时清空。该状态只存在于当前PHP请求。
 *
 * @param string|null $set 新文案。
 * @return string|null
 */
function dentall_core_customer_login_public_error( $set = null ) {
	static $public_error = null;

	if ( func_num_args() ) {
		$public_error = is_string( $set ) && '' !== $set ? $set : null;
	}

	return $public_error;
}

/**
 * 记录需要归一化公开文案的WooCommerce登录失败。
 *
 * 这里只读取原始WP_Error，不修改错误码或对象。因此WordPress随后触发的失败审计、限频、
 * MFA等安全组件仍能收到invalid_username、invalid_email或incorrect_password原码。
 *
 * @param string   $username 登录标识。
 * @param WP_Error $error    WordPress原始认证错误。
 * @return void
 */
function dentall_core_capture_customer_login_failure( $username, $error ) {
	unset( $username );
	dentall_core_customer_login_public_error( null );

	if ( ! dentall_core_is_customer_login_request() || ! is_wp_error( $error ) ) {
		return;
	}

	$credential_errors     = array( 'invalid_username', 'invalid_email', 'incorrect_password' );
	$authentication_errors = $error->get_error_codes();

	if ( ! array_intersect( $credential_errors, $authentication_errors ) ) {
		return;
	}

	$other_errors = array_diff( $authentication_errors, $credential_errors );
	foreach ( $other_errors as $error_code ) {
		$public_error = $error->get_error_message( $error_code );
		if ( is_string( $public_error ) && '' !== $public_error ) {
			dentall_core_customer_login_public_error( $public_error );
			return;
		}
	}

	dentall_core_customer_login_public_error(
		__( 'The login details are incorrect. Check them and try again.', 'dentall-core' )
	);
}
add_action( 'wp_login_failed', 'dentall_core_capture_customer_login_failure', 99, 2 );

/**
 * 统一无效账号与错误密码的公开反馈，避免登录表单泄露账号是否存在。
 *
 * 空字段、未验证邮箱、限频插件等其他错误保留原职责和文案。本规则只改变WooCommerce
 * 最终展示文本，不改变认证结果或原始错误码，也不扩展到D80的找回密码流程。
 *
 * @param string $message WooCommerce准备公开的登录错误。
 * @return string
 */
function dentall_core_normalize_customer_login_error_message( $message ) {
	$public_error = dentall_core_customer_login_public_error();

	return null === $public_error ? $message : $public_error;
}
add_filter( 'login_errors', 'dentall_core_normalize_customer_login_error_message', 99 );
