<?php
/**
 * Contact原生Page的展示增强。
 */

defined( 'ABSPATH' ) || exit;

/**
 * 联系页沿用Storefront Page模板，仅加载本页的布局与表单微调。
 *
 * @return void
 */
function dentall_enqueue_contact_assets() {
	if ( ! is_page( 'contact-us' ) ) {
		return;
	}

	$theme = wp_get_theme( get_stylesheet() );
	wp_enqueue_style(
		'dentall-contact',
		get_stylesheet_directory_uri() . '/assets/css/contact.css',
		array( 'dentall-site-shell' ),
		$theme->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'dentall_enqueue_contact_assets', 50 );

/**
 * 为Contact页面样式提供稳定作用域。
 *
 * @param string[] $classes 页面class。
 * @return string[]
 */
function dentall_contact_body_class( $classes ) {
	if ( is_page( 'contact-us' ) ) {
		$classes[] = 'dentall-contact';
	}
	return $classes;
}
add_filter( 'body_class', 'dentall_contact_body_class' );

/**
 * 仅在Contact正文前说明有效的定制商品或无效参数。
 *
 * @param string $content 原生Page正文。
 * @return string
 */
function dentall_contact_product_context( $content ) {
	if (
		! is_page( 'contact-us' )
		|| ! is_main_query()
		|| ! in_the_loop()
		|| get_the_ID() !== get_queried_object_id()
		|| ! isset( $_GET['product_id'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- 只读展示参数。
	) {
		return $content;
	}

	$configured = function_exists( 'dentall_core_is_contact_form' )
		&& absint( get_option( 'dentall_contact_form_id', 0 ) ) > 0;
	$product = $configured && function_exists( 'dentall_core_get_requested_contact_product' )
		? dentall_core_get_requested_contact_product()
		: false;

	if ( ! $product ) {
		$notice = '<p class="dentall-contact-context dentall-contact-context--missing">'
			. esc_html__( 'We could not identify that product. Please include its name in your message.', 'dentall' )
			. '</p>';
		return $notice . $content;
	}

	$notice = '<aside class="dentall-contact-context" aria-labelledby="dentall-contact-product-title">'
		. '<h2 id="dentall-contact-product-title">' . esc_html__( 'About this product', 'dentall' ) . '</h2>'
		. '<p><a href="' . esc_url( $product['url'] ) . '">' . esc_html( $product['name'] ) . '</a></p>'
		. '</aside>';

	return $notice . $content;
}
add_filter( 'the_content', 'dentall_contact_product_context', 20 );
