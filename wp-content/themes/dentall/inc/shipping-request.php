<?php
/**
 * 在现有购物车URL中展示运费申请，不另建可索引页面或第二套购物车。
 */

defined( 'ABSPATH' ) || exit;

/**
 * 动态短代码不在Page正文中登记，仅为申请视图提前加载插件原生样式。
 *
 * @param bool $load 插件的原始加载决定。
 * @return bool
 */
function dentall_preload_shipping_request_form_assets( $load ) {
	return $load || (
		function_exists( 'is_cart' ) && is_cart()
		&& '1' === ( $_GET['shipping-request'] ?? '' ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- 只决定资源加载。
		&& function_exists( 'dentall_core_get_shipping_request_form_id' )
		&& dentall_core_get_shipping_request_form_id() > 0
	);
}
add_filter( 'fluentform/load_styles', 'dentall_preload_shipping_request_form_assets' );

/**
 * 只有当前购物车主内容能切换为申请表；查询参数不参与身份或金额判定。
 *
 * @param string $content 原生购物车内容。
 * @return string
 */
function dentall_render_shipping_request( $content ) {
	if (
		! function_exists( 'is_cart' ) || ! is_cart()
		|| ! is_main_query() || ! in_the_loop()
		|| get_the_ID() !== get_queried_object_id()
		|| ! isset( $_GET['shipping-request'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- 只选择展示模式。
		|| '1' !== $_GET['shipping-request'] // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	) {
		return $content;
	}

	$form_id = function_exists( 'dentall_core_get_shipping_request_form_id' )
		? dentall_core_get_shipping_request_form_id() : 0;
	$snapshot = $form_id && shortcode_exists( 'fluentform' )
		? dentall_core_get_shipping_request_snapshot() : null;
	$output = '<section class="dentall-shipping-request" aria-labelledby="dentall-shipping-request-title">'
		. '<p><a href="' . esc_url( wc_get_cart_url() ) . '">'
		. esc_html__( 'Back to cart', 'dentall' ) . '</a></p>'
		. '<h2 id="dentall-shipping-request-title">' . esc_html__( 'Request a shipping quote', 'dentall' ) . '</h2>';

	if ( ! $snapshot || is_wp_error( $snapshot ) ) {
		$message = is_wp_error( $snapshot ) ? $snapshot->get_error_message()
			: __( 'The request form is currently unavailable. Please contact our team using the email below.', 'dentall' );
		$output .= '<p role="status">' . esc_html( $message ) . '</p>';
	} else {
		// 摘要稍后会经过WordPress短代码过滤，方括号必须继续保持纯文本。
		$summary = str_replace( array( '[', ']' ), array( '&#91;', '&#93;' ), esc_html( $snapshot['text'] ) );
		$output .= '<p>' . esc_html__( 'Submit your contact and delivery details. Our team will confirm shipping and all order charges before sending a payment link. The 72-hour payment period starts when we first successfully send that quotation email.', 'dentall' ) . '</p>'
			. '<noscript><p>' . esc_html__( 'Please enable JavaScript to submit this form, or use the contact email below.', 'dentall' ) . '</p></noscript>'
			. '<aside class="dentall-shipping-request__summary" aria-labelledby="dentall-shipping-request-summary-title">'
			. '<h3 id="dentall-shipping-request-summary-title">' . esc_html__( 'Your cart for this request', 'dentall' ) . '</h3>'
			. '<p>' . nl2br( $summary ) . '</p></aside>';
		$privacy_url = get_privacy_policy_url();
		if ( $privacy_url ) {
			$output .= '<p><a href="' . esc_url( $privacy_url ) . '">'
				. esc_html__( 'Privacy Policy', 'dentall' ) . '</a></p>';
		}
		$output .= '[fluentform id="' . absint( $form_id ) . '"]';
	}

	$email = function_exists( 'dentall_core_get_shipping_quote_email' ) ? dentall_core_get_shipping_quote_email() : '';
	if ( $email ) {
		$output .= '<p class="dentall-shipping-request__contact">'
			. esc_html__( 'You can also email our team:', 'dentall' ) . ' '
			. '<a href="' . esc_url( 'mailto:' . $email ) . '">' . esc_html( $email ) . '</a>'
			. ' ' . esc_html__( 'If your email app does not open, copy this address into your webmail.', 'dentall' ) . '</p>';
	}
	return $output . '</section>';
}
// 在do_blocks之前替换Cart内容，短代码仍由WordPress稍后按原生顺序解析。
add_filter( 'the_content', 'dentall_render_shipping_request', 8 );
