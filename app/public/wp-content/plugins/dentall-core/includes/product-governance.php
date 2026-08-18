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
 * 对Website Manager隐藏商品原始自定义字段面板。
 *
 * WooCommerce商品元数据应通过其原生字段和CRUD/API维护。该界面防护用于降低
 * 误改total_sales等技术字段的风险，不作为阻止恶意请求的服务端安全边界。
 *
 * @return void
 */
function dentall_core_remove_website_manager_product_custom_fields_meta_box() {
	if ( current_user_can( DENTALL_WEBSITE_MANAGER_MARKER ) ) {
		remove_meta_box( 'postcustom', 'product', 'normal' );
	}
}
add_action( 'add_meta_boxes_product', 'dentall_core_remove_website_manager_product_custom_fields_meta_box', PHP_INT_MAX );

/**
 * 判断当前请求是否属于WooCommerce原生商品导出流程。
 *
 * WooCommerce复用WordPress全局`export`能力。这里把授权限制在商品列表、
 * 商品导出页面及其AJAX请求，避免Website Manager获得全站内容导出权限。
 *
 * @return bool
 */
function dentall_core_is_product_export_request() {
	global $pagenow;

	if ( wp_doing_ajax() ) {
		$action = isset( $_REQUEST['action'] )
			? sanitize_key( wp_unslash( $_REQUEST['action'] ) )
			: '';

		return 'woocommerce_do_ajax_product_export' === $action;
	}

	if ( 'edit.php' !== $pagenow ) {
		return false;
	}

	$post_type = isset( $_GET['post_type'] )
		? sanitize_key( wp_unslash( $_GET['post_type'] ) )
		: '';
	$page      = isset( $_GET['page'] )
		? sanitize_key( wp_unslash( $_GET['page'] ) )
		: '';

	return 'product' === $post_type
		&& ( '' === $page || 'product_exporter' === $page );
}

/**
 * 仅为Website Manager的WooCommerce商品导出请求临时授予`export`能力。
 *
 * 角色数据库不会保存该能力，因此WordPress原生内容导出和其他插件导出
 * 不会仅因本功能而开放。WooCommerce继续负责nonce与商品CSV生成。
 *
 * @param array<string, bool> $allcaps 用户的最终能力集合。
 * @param string[]            $caps    当前检查需要的原始能力。
 * @param array<int, mixed>   $args    当前能力检查参数。
 * @param WP_User             $user    当前用户。
 * @return array<string, bool>
 */
function dentall_core_grant_website_manager_product_export( $allcaps, $caps, $args, $user ) {
	unset( $args, $user );

	if (
		! in_array( 'export', $caps, true )
		|| empty( $allcaps[ DENTALL_WEBSITE_MANAGER_MARKER ] )
		|| ! dentall_core_is_product_export_request()
	) {
		return $allcaps;
	}

	$allcaps['export'] = true;

	return $allcaps;
}
add_filter( 'user_has_cap', 'dentall_core_grant_website_manager_product_export', 10, 4 );

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
