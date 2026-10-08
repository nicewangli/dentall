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
 * 生成不含邮箱或用户名明文的找回密码限频项。
 *
 * 标识符限制同一账户线索60秒内重复发信，不将原始身份数据写入WooCommerce限频表。
 * 来源IP限频留给明确理解代理拓扑的边缘层，避免共享代理地址造成全站误伤。
 *
 * @param string $login 客户提交的邮箱或用户名。
 * @return array<string,int>
 */
function dentall_core_password_reset_rate_limits( $login ) {
	$identity = strtolower( sanitize_user( trim( $login ) ) );
	$salt     = wp_salt( 'auth' );

	return array(
		'dentall_password_reset_identity_' . hash_hmac( 'sha256', $identity, $salt ) => 60,
	);
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

/**
 * 在WooCommerce保存个人资料前维持已验证登录邮箱与密码规则。
 *
 * 原生处理器已经验证Nonce并把目标限定为当前用户；此处只补项目身份合同。
 * 未开放新邮箱验证和重新归户流程前，不允许通过伪造POST更换登录邮箱。
 *
 * @param WP_Error $errors 保存错误。
 * @param object   $user   WooCommerce准备写入的当前用户资料。
 * @return void
 */
function dentall_core_validate_customer_account_details( $errors, $user ) {
	if ( ! is_wp_error( $errors ) || ! is_object( $user ) || ! isset( $user->ID ) ) {
		return;
	}

	$current_user = wp_get_current_user();
	if ( ! in_array( 'customer', $current_user->roles, true ) ) {
		return;
	}

	if ( ! $current_user->exists() || (int) $user->ID !== $current_user->ID ) {
		$errors->add( 'dentall_account_owner', __( 'We could not save these account details.', 'dentall-core' ) );
		return;
	}

	if ( isset( $user->user_email ) && $user->user_email !== $current_user->user_email ) {
		$errors->add( 'dentall_account_email_locked', __( 'Your account email address cannot be changed here.', 'dentall-core' ) );
	}

	if ( isset( $user->user_pass ) && is_string( $user->user_pass ) && mb_strlen( $user->user_pass, 'UTF-8' ) < 12 ) {
		$errors->add( 'dentall_account_password_too_short', __( 'Use at least 12 characters for your new password.', 'dentall-core' ) );
	}
}
add_action( 'woocommerce_save_account_details_errors', 'dentall_core_validate_customer_account_details', 10, 2 );

/**
 * 阻止客户通过WordPress原生REST自改入口绕过账户邮箱与改密合同。
 *
 * 当前用户对自己拥有edit_user能力，核心REST入口可直接写邮箱或密码。客户改密
 * 继续使用要求当前密码的WooCommerce表单；管理员管理客户不受此限制。
 *
 * @param mixed           $response 当前REST响应。
 * @param array           $handler  路由处理器。
 * @param WP_REST_Request $request  REST请求。
 * @return mixed
 */
function dentall_core_guard_customer_rest_account_update( $response, $handler, $request ) {
	if ( is_wp_error( $response ) || ! $request instanceof WP_REST_Request || ! in_array( $request->get_method(), array( 'POST', 'PUT', 'PATCH' ), true ) ) {
		return $response;
	}

	if ( ! preg_match( '#^/wp/v2/users/(?:me|[0-9]+)/?$#', $request->get_route() ) ) {
		return $response;
	}

	$current_user = wp_get_current_user();
	if ( ! $current_user->exists() || ! in_array( 'customer', $current_user->roles, true ) ) {
		return $response;
	}

	// 请求参数可覆盖URL中的用户ID，不能只按路径ID判断是否为本人。
	$email = $request->get_param( 'email' );
	if ( is_string( $email ) && $email !== $current_user->user_email ) {
		return new WP_Error( 'dentall_account_email_locked', __( 'Your account email address cannot be changed here.', 'dentall-core' ), array( 'status' => 403 ) );
	}

	if ( null !== $request->get_param( 'password' ) ) {
		return new WP_Error( 'dentall_account_password_form', __( 'Change your password from your account details page.', 'dentall-core' ), array( 'status' => 403 ) );
	}

	return $response;
}
add_filter( 'rest_request_before_callbacks', 'dentall_core_guard_customer_rest_account_update', 10, 3 );

/**
 * 原生资料表单保留邮箱字段以维持WooCommerce提交合同，并说明第一版限制。
 *
 * @return void
 */
function dentall_core_render_account_email_notice() {
	if ( ! in_array( 'customer', wp_get_current_user()->roles, true ) ) {
		return;
	}

	echo '<p class="dentall-account-email-note" id="dentall-account-email-note">' . esc_html__( 'Your account email address cannot be changed here. Contact us if it needs to be updated.', 'dentall-core' ) . '</p>';
}
add_action( 'woocommerce_edit_account_form_fields', 'dentall_core_render_account_email_notice' );

/**
 * 在WooCommerce写入客户默认地址前，复核国家属于对应的业务范围。
 *
 * 原生下拉按设置展示国家，但原生保存处理器未核对伪造POST的国家。订单报价仍以
 * 独立的订单快照判断；这里仅防止客户默认地址写入不受支持的国家。
 *
 * @param int         $user_id      当前客户ID。
 * @param string      $address_type billing或shipping。
 * @param array       $address      原生表单字段定义。
 * @param WC_Customer $customer     尚未保存的客户对象。
 * @return void
 */
function dentall_core_validate_customer_address_country( $user_id, $address_type, $address, $customer ) {
	unset( $address );
	if ( ! in_array( 'customer', wp_get_current_user()->roles, true ) ) {
		return;
	}

	if ( ! $customer instanceof WC_Customer || (int) $user_id !== get_current_user_id() || $customer->get_id() !== (int) $user_id ) {
		wc_add_notice( __( 'We could not save this address.', 'dentall-core' ), 'error' );
		return;
	}

	if ( ! in_array( $address_type, array( 'billing', 'shipping' ), true ) ) {
		return;
	}

	$get_country = 'get_' . $address_type . '_country';
	$country     = $customer->{$get_country}( 'edit' );
	$countries   = 'shipping' === $address_type
		? WC()->countries->get_shipping_countries()
		: WC()->countries->get_allowed_countries();

	if (
		! is_string( $country )
		|| ! isset( $countries[ $country ] )
		|| ( 'shipping' === $address_type && ! in_array( $country, DENTALL_SHIPPING_QUOTE_ALLOWED_COUNTRIES, true ) )
	) {
		$message = 'shipping' === $address_type
			? __( 'Choose an available shipping country.', 'dentall-core' )
			: __( 'Choose a valid billing country.', 'dentall-core' );
		wc_add_notice( $message, 'error', array( 'id' => $address_type . '_country' ) );
	}
}
add_action( 'woocommerce_after_save_address_validation', 'dentall_core_validate_customer_address_country', 10, 4 );

/**
 * 第一版不提供会替换当前购物车的原生“再次购买”入口。
 *
 * WooCommerce在按钮输出与订单重装购物车时都会查询此状态列表。直接请求还需在
 * WooCommerce加载购物车会话前拦截，避免无效订单ID或空状态返回值影响现有购物车。
 *
 * @return array<int, string>
 */
function dentall_core_disable_order_again() {
	return array();
}
add_filter( 'woocommerce_valid_order_statuses_for_order_again', 'dentall_core_disable_order_again' );

/**
 * 在WooCommerce处理order_again查询前返回购物车，保留原有会话商品。
 *
 * 仅隐藏按钮不足以阻止带Nonce的直接请求；WooCommerce在wp_loaded默认优先级
 * 读取已登录用户的重购参数并可能替换购物车，因此这里提前阻断。
 *
 * @return void
 */
function dentall_core_redirect_order_again_request() {
	if (
		! function_exists( 'wc_get_cart_url' )
		|| ! isset( $_GET['order_again'], $_GET['_wpnonce'] )
		|| ! is_user_logged_in()
	) {
		return;
	}

	wp_safe_redirect( wc_get_cart_url() );
	exit;
}
add_action( 'wp_loaded', 'dentall_core_redirect_order_again_request', 1 );
