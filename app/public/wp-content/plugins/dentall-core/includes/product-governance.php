<?php
/**
 * DentAll商品标签、全局属性和Shipping Class治理边界。
 */

defined( 'ABSPATH' ) || exit;

/**
 * 内容试录员暂不维护商品标签，移除会误导其创建标签的自由输入框。
 *
 * @return void
 */
function dentall_core_remove_content_editor_product_tag_meta_box() {
	if ( current_user_can( 'dentall_content_editor' ) ) {
		remove_meta_box( 'tagsdiv-product_tag', 'product', 'side' );
	}
}
add_action( 'add_meta_boxes_product', 'dentall_core_remove_content_editor_product_tag_meta_box', PHP_INT_MAX );

/**
 * 服务端拒绝内容试录员创建商品标签，避免绕过后台界面。
 *
 * @param string|WP_Error $term     准备创建的术语名称或上游错误。
 * @param string          $taxonomy 目标分类法。
 * @return string|WP_Error
 */
function dentall_core_prevent_content_editor_product_tag_creation( $term, $taxonomy ) {
	if ( 'product_tag' === $taxonomy && current_user_can( 'dentall_content_editor' ) ) {
		return new WP_Error(
			'dentall_product_tag_creation_denied',
			__( 'You are not allowed to create product tags.', 'dentall-core' )
		);
	}

	return $term;
}
add_filter( 'pre_insert_term', 'dentall_core_prevent_content_editor_product_tag_creation', 10, 2 );

/**
 * 将Shipping Class管理从普通商品分类与属性管理权限中拆开。
 *
 * WooCommerce默认让Shipping Class与商品分类共用product_terms能力。这里保留
 * Shipping Class的新建、编辑和删除继续明确要求manage_woocommerce；当前
 * Website Manager已获授权，因此可以管理运费类别。
 *
 * @param array<string, mixed> $args Shipping Class分类法参数。
 * @return array<string, mixed>
 */
function dentall_core_restrict_shipping_class_management( $args ) {
	$args['capabilities'] = array(
		'manage_terms' => 'manage_woocommerce',
		'edit_terms'   => 'manage_woocommerce',
		'delete_terms' => 'manage_woocommerce',
		'assign_terms' => 'assign_product_terms',
	);

	return $args;
}
add_filter( 'woocommerce_taxonomy_args_product_shipping_class', 'dentall_core_restrict_shipping_class_management' );

/**
 * 判断当前请求是否尝试删除整个WooCommerce全局属性定义。
 *
 * WooCommerce属性页面使用manage_product_terms控制入口，但删除处理未单独检查
 * delete_product_terms，因此需要在动作执行前补充项目安全边界。
 *
 * @return bool
 */
function dentall_core_is_global_attribute_delete_request() {
	global $pagenow;

	if ( 'edit.php' !== $pagenow ) {
		return false;
	}

	$post_type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : '';
	$page      = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

	return 'product' === $post_type
		&& 'product_attributes' === $page
		&& isset( $_GET['delete'] );
}

/**
 * 要求Website Manager删除整个全局属性定义时具备明确的术语删除能力。
 *
 * WooCommerce当前删除处理只检查nonce，未单独检查delete_product_terms。这里补上
 * capability边界；当前Website Manager已获该能力，因此不会被阻止。
 *
 * @return void
 */
function dentall_core_block_website_manager_attribute_deletion() {
	if ( ! current_user_can( DENTALL_WEBSITE_MANAGER_MARKER )
		|| current_user_can( 'delete_product_terms' )
		|| ! dentall_core_is_global_attribute_delete_request()
	) {
		return;
	}

	wp_die(
		esc_html__( 'You are not allowed to delete global product attributes.', 'dentall-core' ),
		esc_html__( 'Access denied', 'dentall-core' ),
		array( 'response' => 403 )
	);
}
add_action( 'admin_init', 'dentall_core_block_website_manager_attribute_deletion', 1 );
