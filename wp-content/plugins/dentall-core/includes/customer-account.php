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

/**
 * 判断是否为可由DentAll接管公开结果的WooCommerce找回密码请求。
 *
 * 空输入和无效Nonce继续交给WooCommerce处理，便于客户修正表单，也避免绕过CSRF校验。
 * 新密码表单不含user_login，因此不会进入此分支。
 *
 * @return bool
 */
function dentall_core_is_customer_lost_password_request() {
	if (
		! isset( $_POST['wc_reset_password'], $_POST['user_login'] )
		|| ! is_string( $_POST['wc_reset_password'] )
		|| ! is_string( $_POST['user_login'] )
	) {
		return false;
	}

	$nonce_value = isset( $_REQUEST['woocommerce-lost-password-nonce'] )
		? $_REQUEST['woocommerce-lost-password-nonce']
		: ( $_REQUEST['_wpnonce'] ?? '' );
	if ( ! is_string( $nonce_value ) ) {
		return false;
	}

	$nonce = sanitize_text_field( wp_unslash( $nonce_value ) );
	$login = trim( wp_unslash( $_POST['user_login'] ) );

	return '' !== $login && (bool) wp_verify_nonce( $nonce, 'lost_password' );
}

/**
 * 生成不含邮箱、用户名或IP明文的找回密码限频项。
 *
 * 标识符限制同一账户线索60秒内重复发信；服务器直接看到的来源IP增加10秒短闸门。
 * 不信任可伪造的转发头，且不将任何原始身份数据写入WooCommerce限频表。
 *
 * @param string $login 客户提交的邮箱或用户名。
 * @return array<string,int>
 */
function dentall_core_password_reset_rate_limits( $login ) {
	$identity = strtolower( sanitize_user( trim( $login ) ) );
	$salt     = wp_salt( 'auth' );
	$limits   = array(
		'dentall_password_reset_identity_' . hash_hmac( 'sha256', $identity, $salt ) => 60,
	);
	$remote = isset( $_SERVER['REMOTE_ADDR'] ) && is_string( $_SERVER['REMOTE_ADDR'] )
		? trim( wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
		: '';

	if ( false !== filter_var( $remote, FILTER_VALIDATE_IP ) ) {
		$limits[ 'dentall_password_reset_origin_' . hash_hmac( 'sha256', $remote, $salt ) ] = 10;
	}

	return $limits;
}

/**
 * 检查后写入本次找回密码限频，尽量收窄并发重复发信窗口。
 *
 * WooCommerce当前API不提供原子“仅首次写入”操作，真正并发的首次请求仍须由主机层
 * 限频或后续原子存储收口；这里确保顺序重试不会续期或制造额外键。
 *
 * @param array<string,int> $limits 限频项与冷却秒数。
 * @return bool 是否已处于冷却期。
 */
function dentall_core_throttle_password_reset( $limits ) {
	if ( ! class_exists( 'WC_Rate_Limiter' ) ) {
		return false;
	}

	foreach ( $limits as $action_id => $delay ) {
		if ( WC_Rate_Limiter::retried_too_soon( $action_id ) ) {
			return true;
		}
	}

	foreach ( $limits as $action_id => $delay ) {
		WC_Rate_Limiter::set_rate_limit( $action_id, $delay );
	}

	return false;
}

/**
 * 以统一公开结果处理WooCommerce找回密码，避免披露账号是否存在或能否重置。
 *
 * 实际密钥、有效期、单次使用和邮件仍完全由WordPress/WooCommerce负责。限频命中时
 * 不发送邮件，但返回相同确认页；私有投递结果由WooCommerce与FluentSMTP日志审计。
 *
 * @return void
 */
function dentall_core_process_customer_lost_password() {
	if ( ! dentall_core_is_customer_lost_password_request() ) {
		return;
	}

	$login   = wp_unslash( $_POST['user_login'] );
	$limited = dentall_core_throttle_password_reset( dentall_core_password_reset_rate_limits( $login ) );

	if ( ! $limited && class_exists( 'WC_Shortcode_My_Account' ) ) {
		WC_Shortcode_My_Account::retrieve_password();
	}

	if ( function_exists( 'wc_clear_notices' ) ) {
		wc_clear_notices();
	}

	wp_safe_redirect( add_query_arg( 'reset-link-sent', 'true', wc_get_account_endpoint_url( 'lost-password' ) ) );
	exit;
}
add_action( 'wp_loaded', 'dentall_core_process_customer_lost_password', 19 );

/**
 * 将WordPress核心找回请求入口归一到WooCommerce“我的账户”。
 *
 * 核心生成的既有rp/resetpass链接不在此处拦截，避免破坏已经发出的有效邮件。
 *
 * @return void
 */
function dentall_core_redirect_core_lost_password() {
	if ( ! function_exists( 'wc_lostpassword_url' ) ) {
		return;
	}

	wp_safe_redirect( wc_lostpassword_url() );
	exit;
}
add_action( 'login_form_lostpassword', 'dentall_core_redirect_core_lost_password' );
add_action( 'login_form_retrievepassword', 'dentall_core_redirect_core_lost_password' );

/**
 * 只在WooCommerce找回密码确认页替换可枚举的成功标题。
 *
 * @param string $translation 已翻译文本。
 * @param string $text        原始文本。
 * @return string
 */
function dentall_core_generic_password_reset_heading( $translation, $text ) {
	if (
		'Password reset email has been sent.' === $text
		&& function_exists( 'is_wc_endpoint_url' )
		&& is_wc_endpoint_url( 'lost-password' )
	) {
		return __( 'Your password reset request has been received.', 'dentall-core' );
	}

	return $translation;
}
add_filter( 'gettext_woocommerce', 'dentall_core_generic_password_reset_heading', 10, 2 );

/**
 * 为找回密码确认页提供不披露账号状态的说明。
 *
 * @param string $message WooCommerce默认说明。
 * @return string
 */
function dentall_core_generic_password_reset_message( $message ) {
	if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'lost-password' ) ) {
		return __( 'If an account matches the details provided, password reset instructions will be sent to the email address on file. Please allow several minutes before trying again.', 'dentall-core' );
	}

	return $message;
}
add_filter( 'woocommerce_lost_password_confirmation_message', 'dentall_core_generic_password_reset_message' );

/**
 * 在WooCommerce真正写入新密码前执行最低12字符服务端校验。
 *
 * @param WP_Error $errors 密码验证错误。
 * @return void
 */
function dentall_core_validate_customer_reset_password( $errors ) {
	if (
		! is_wp_error( $errors )
		|| ! isset( $_POST['wc_reset_password'], $_POST['password_1'], $_POST['password_2'] )
		|| ! is_string( $_POST['password_1'] )
		|| ! is_string( $_POST['password_2'] )
	) {
		return;
	}

	$password = $_POST['password_1']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- 密码必须保留原始字符，且仅用于长度校验。
	if ( mb_strlen( $password, 'UTF-8' ) < 12 ) {
		$errors->add(
			'dentall_password_too_short',
			__( 'Use at least 12 characters for your new password.', 'dentall-core' )
		);
	}
}
add_action( 'validate_password_reset', 'dentall_core_validate_customer_reset_password', 10, 1 );
