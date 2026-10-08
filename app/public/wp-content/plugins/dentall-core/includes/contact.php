<?php
/**
 * D89联系表单的定制展示商品上下文边界。
 */

defined( 'ABSPATH' ) || exit;

/**
 * 只接受已公开且明确标记为展示型的WooCommerce商品。
 *
 * @param mixed $input 来自URL或表单的商品ID。
 * @return array{id:int,name:string,url:string}|false
 */
function dentall_core_get_contact_product( $input ) {
	if ( ! is_int( $input ) && ! is_string( $input ) ) {
		return false;
	}

	$raw_id = (string) $input;
	$id     = ctype_digit( $raw_id ) && '0' !== $raw_id
		? filter_var( $raw_id, FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) )
		: false;
	if ( false === $id || ! function_exists( 'wc_get_product' ) ) {
		return false;
	}

	$product = wc_get_product( $id );
	if (
		! $product instanceof WC_Product
		|| 'publish' !== $product->get_status()
		|| ! $product->is_visible()
		|| 'display_only' !== $product->get_meta( 'dentall_sales_mode', true )
	) {
		return false;
	}

	$url = get_permalink( $product->get_id() );
	if ( ! $url ) {
		return false;
	}

	return array(
		'id'   => $product->get_id(),
		'name' => $product->get_name(),
		'url'  => $url,
	);
}

/**
 * 表单ID在各环境导入后配置，不绑定插件内部表或默认后台邮箱。
 *
 * @param mixed $form_id Fluent Forms传入的表单ID。
 * @return bool
 */
function dentall_core_is_contact_form( $form_id ) {
	$configured_id = absint( get_option( 'dentall_contact_form_id', 0 ) );
	return $configured_id > 0 && $configured_id === absint( $form_id );
}

/**
 * 仅从查询参数读取ID；商品名称和URL永远由服务端重新获得。
 *
 * @return array{id:int,name:string,url:string}|false
 */
function dentall_core_get_requested_contact_product() {
	if ( ! isset( $_GET['product_id'] ) ) {
		return false;
	}

	return dentall_core_get_contact_product( wp_unslash( $_GET['product_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- 只读公开商品。
}

/**
 * 仅为已配置的Contact表单填入经校验的ID。
 *
 * @param array  $field 表单字段定义。
 * @param object $form  Fluent Forms表单对象。
 * @return array
 */
function dentall_core_prefill_contact_product_id( $field, $form ) {
	if (
		! dentall_core_is_contact_form( $form->id ?? 0 )
		|| 'dentall_product_id' !== ( $field['attributes']['name'] ?? '' )
	) {
		return $field;
	}

	$product = dentall_core_get_requested_contact_product();
	$field['attributes']['value'] = $product ? (string) $product['id'] : '';
	return $field;
}
add_filter( 'fluentform/rendering_field_data_input_hidden', 'dentall_core_prefill_contact_product_id', 10, 2 );

/**
 * 插件入库前重读商品，丢弃客户提交的名称、URL和来源标记。
 *
 * @param array $data    提交数据。
 * @param int   $form_id 表单ID。
 * @return array
 */
function dentall_core_normalize_contact_product( $data, $form_id ) {
	if ( ! dentall_core_is_contact_form( $form_id ) || ! is_array( $data ) ) {
		return $data;
	}

	$product = dentall_core_get_contact_product( $data['dentall_product_id'] ?? null );
	unset( $data['dentall_product_id'], $data['dentall_product_name'], $data['dentall_product_url'], $data['dentall_source'], $data['source'] );

	if ( $product ) {
		$data['dentall_product_id']   = $product['id'];
		$data['dentall_product_name'] = $product['name'];
		$data['dentall_product_url']  = $product['url'];
		$data['dentall_source']       = 'custom_product';
	}

	return $data;
}
add_filter( 'fluentform/insert_response_data', 'dentall_core_normalize_contact_product', 10, 2 );

/**
 * 联系表单独立启用Honeypot；Token需在Fluent Forms全局设置中启用。
 *
 * @param bool $enabled 插件全局设置。
 * @param int  $form_id 表单ID。
 * @return bool
 */
function dentall_core_enable_contact_honeypot( $enabled, $form_id ) {
	return dentall_core_is_contact_form( $form_id ) ? true : $enabled;
}
add_filter( 'fluentform/honeypot_status', 'dentall_core_enable_contact_honeypot', 10, 2 );

/**
 * 邮件只附加再次校验的商品上下文；表单模板不包含可伪造的商品名称或URL字段。
 *
 * @param string $message      插件生成的邮件正文。
 * @param array  $notification 通知配置。
 * @param array  $data         插件提交数据。
 * @param object $form         表单对象。
 * @return string
 */
function dentall_core_append_contact_product_to_email( $message, $notification, $data, $form ) {
	if ( ! dentall_core_is_contact_form( $form->id ?? 0 ) || ! is_array( $data ) ) {
		return $message;
	}

	$product = dentall_core_get_contact_product( $data['dentall_product_id'] ?? null );
	if ( ! $product ) {
		return $message;
	}

	if ( 'yes' === ( $notification['asPlainText'] ?? 'no' ) ) {
		return $message . "\n" . __( 'Product:', 'dentall-core' ) . ' '
			. wp_strip_all_tags( $product['name'] ) . ' (#' . $product['id'] . ') '
			. esc_url_raw( $product['url'] );
	}

	$context = '<p><strong>' . esc_html__( 'Product:', 'dentall-core' ) . '</strong> '
		. '<a href="' . esc_url( $product['url'] ) . '">' . esc_html( $product['name'] ) . '</a>'
		. ' (#' . esc_html( (string) $product['id'] ) . ')</p>';
	$closing = strripos( $message, '</body>' );
	return false === $closing
		? $message . $context
		: substr_replace( $message, $context, $closing, 0 );
}
add_filter( 'fluentform/email_body', 'dentall_core_append_contact_product_to_email', 10, 4 );

/**
 * Contact条目不记录IP和请求头推导的国家信息。
 *
 * @param bool $disabled 原有设置。
 * @param int  $form_id  表单ID。
 * @return bool
 */
function dentall_core_disable_contact_location_log( $disabled, $form_id ) {
	return dentall_core_is_contact_form( $form_id ) ? true : $disabled;
}
add_filter( 'fluentform/disable_ip_logging', 'dentall_core_disable_contact_location_log', 10, 2 );
add_filter( 'fluentform/disable_submission_country_detection', 'dentall_core_disable_contact_location_log', 10, 2 );

/**
 * 商品查询参数只用于一次联系预填，Canonical始终指向Contact原页面。
 *
 * @param string $canonical Yoast计算出的URL。
 * @return string
 */
function dentall_core_contact_canonical( $canonical ) {
	return is_page( 'contact-us' ) ? get_permalink( get_queried_object_id() ) : $canonical;
}
add_filter( 'wpseo_canonical', 'dentall_core_contact_canonical' );
