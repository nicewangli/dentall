<?php

defined( 'ABSPATH' ) || exit;

/**
 * 仅在未登录的My Account首页选择登录或注册视图，保留WooCommerce原生表单。
 *
 * @return string
 */
function dentall_account_auth_view() {
	if (
		! function_exists( 'is_account_page' )
		|| ! is_account_page()
		|| is_user_logged_in()
		|| ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url() )
	) {
		return '';
	}

	if (
		'yes' === get_option( 'woocommerce_enable_myaccount_registration' )
		&& isset( $_GET['dentall_auth'] )
		&& is_string( $_GET['dentall_auth'] )
		&& 'register' === wp_unslash( $_GET['dentall_auth'] )
	) {
		return 'register';
	}

	return 'login';
}

/**
 * 未登录账户首页与找回密码端点均使用完整宽度，不显示博客侧栏。
 *
 * @return bool
 */
function dentall_account_auth_needs_full_width() {
	return (bool) dentall_account_auth_view()
		|| (
			function_exists( 'is_account_page' )
			&& is_account_page()
			&& ! is_user_logged_in()
			&& function_exists( 'is_wc_endpoint_url' )
			&& is_wc_endpoint_url( 'lost-password' )
		);
}

/**
 * 区分账户首页构图与找回密码端点共用的全宽骨架。
 *
 * @param array $classes Body classes.
 * @return array
 */
function dentall_account_auth_body_class( $classes ) {
	if ( dentall_account_auth_view() ) {
		$classes[] = 'dentall-auth-page';
	}
	if ( dentall_account_auth_needs_full_width() ) {
		$classes[] = 'storefront-full-width-content';
	}

	return $classes;
}
add_filter( 'body_class', 'dentall_account_auth_body_class' );

/**
 * 账户认证页不输出Storefront的博客侧栏，给表单与主视觉完整宽度。
 *
 * @return void
 */
function dentall_account_auth_disable_sidebar() {
	if ( dentall_account_auth_needs_full_width() ) {
		remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
	}
}
add_action( 'wp', 'dentall_account_auth_disable_sidebar', 20 );

/**
 * 为Woo原生表单增加共用视觉舞台和可访问的页面标题。
 *
 * @return void
 */
function dentall_account_auth_open() {
	$view = dentall_account_auth_view();
	if ( ! $view ) {
		return;
	}

	if ( 'register' === $view ) {
		$title = __( 'Create Your Account', 'dentall' );
		$intro = __( 'Register with your email to view your orders.', 'dentall' );
	} else {
		$title = __( 'Welcome Back', 'dentall' );
		$intro = __( 'Sign in to your DentAll account.', 'dentall' );
	}

	echo '<div class="dentall-auth dentall-auth--' . esc_attr( $view ) . '"><div class="dentall-auth__layout">';
	echo '<header class="dentall-auth__intro"><h1>' . esc_html( $title ) . '</h1><p>' . esc_html( $intro ) . '</p></header>';
	echo '<div class="dentall-auth__panel">';
}
add_action( 'woocommerce_before_customer_login_form', 'dentall_account_auth_open' );

/**
 * 关闭账户视觉舞台；表单的Nonce、提交及错误仍由WooCommerce处理。
 *
 * @return void
 */
function dentall_account_auth_close() {
	if ( dentall_account_auth_view() ) {
		echo '</div></div></div>';
	}
}
add_action( 'woocommerce_after_customer_login_form', 'dentall_account_auth_close' );

/**
 * 在登录表单末尾提供注册入口，不新建账户路由。
 *
 * @return void
 */
function dentall_account_auth_register_link() {
	if ( 'login' !== dentall_account_auth_view() || 'yes' !== get_option( 'woocommerce_enable_myaccount_registration' ) ) {
		return;
	}

	$url = add_query_arg( 'dentall_auth', 'register', wc_get_page_permalink( 'myaccount' ) );
	echo '<p class="dentall-auth__switch">' . esc_html__( "Don't have an account?", 'dentall' ) . ' <a href="' . esc_url( $url ) . '">' . esc_html__( 'Sign up', 'dentall' ) . '</a></p>';
}
add_action( 'woocommerce_login_form_end', 'dentall_account_auth_register_link' );

/**
 * 注册后提供返回登录入口。
 *
 * @return void
 */
function dentall_account_auth_login_link() {
	if ( 'register' !== dentall_account_auth_view() ) {
		return;
	}

	echo '<p class="dentall-auth__switch">' . esc_html__( 'Already have an account?', 'dentall' ) . ' <a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '">' . esc_html__( 'Sign in', 'dentall' ) . '</a></p>';
}
add_action( 'woocommerce_register_form_end', 'dentall_account_auth_login_link' );
