<?php
/** 只接受已配置的 opt-in CMP；Consent API 在没有 CMP 时的默认允许不适用于测量。 */
defined( 'ABSPATH' ) || exit;
add_filter( 'wp_consent_api_registered_' . plugin_basename( DENTALL_MEASUREMENT_FILE ), '__return_true' );

function dentall_measurement_has_consent() {
	if ( ! class_exists( 'COMPLIANZ' ) || ! isset( COMPLIANZ::$banner_loader ) || ! function_exists( 'cmplz_has_consent' ) || ! function_exists( 'wp_get_consent_type' ) || ! function_exists( 'wp_has_consent' ) ) {
		return false;
	}
	try {
		$banner = COMPLIANZ::$banner_loader;
		$policy = $_COOKIE[ $banner->get_cookie_prefix() . 'policy_id' ] ?? '';
		// 浏览器会在初始化时重置过期选择；请求端也不能把旧 policy 的允许当成当前同意。
		return is_string( $policy ) && (string) $banner->get_active_policy_id() === $policy
			&& 'optin' === wp_get_consent_type() && cmplz_has_consent( 'statistics' ) && wp_has_consent( 'statistics', plugin_basename( DENTALL_MEASUREMENT_FILE ) );
	} catch ( Throwable $error ) {
		return false;
	}
}

function dentall_measurement_consent_assets() {
	if ( dentall_measurement_is_public_request() ) {
		// 必须在页头监听 CMP 初始化；函数已定义不代表策略检查与用户选择已就绪。
		wp_enqueue_script( 'dentall-measurement-consent', plugins_url( 'assets/consent.js', DENTALL_MEASUREMENT_FILE ), array(), DENTALL_MEASUREMENT_VERSION, false );
	}
}
add_action( 'wp_enqueue_scripts', 'dentall_measurement_consent_assets', 50 );

function dentall_measurement_clear_denied_queue() {
	if ( WC()->session && ! dentall_measurement_has_consent() ) {
		WC()->session->__unset( 'dentall_measurement_add' );
	}
}
add_action( 'woocommerce_cart_loaded_from_session', 'dentall_measurement_clear_denied_queue' );
