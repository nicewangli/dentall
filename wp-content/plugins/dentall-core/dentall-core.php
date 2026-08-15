<?php
/**
 * Plugin Name: DentAll Core
 * Description: DentAll 商城跨主题的最小业务能力。
 * Version: 0.2.1
 * Requires at least: 7.0
 * Requires PHP: 8.2
 * Text Domain: dentall-core
 */

defined( 'ABSPATH' ) || exit;

const DENTALL_CORE_ROLE_VERSION       = '5';
const DENTALL_CORE_ROLE_OPTION        = 'dentall_core_role_version';
const DENTALL_CONTENT_ROLE            = 'dentall_content_editor';
const DENTALL_WEBSITE_MANAGER_ROLE    = 'dentall_website_manager';
const DENTALL_WEBSITE_MANAGER_MARKER  = 'dentall_website_manager';

/**
 * 返回内容试录员的明确能力白名单。
 *
 * 该角色只负责自己的草稿，不具备发布、编辑他人内容或商城管理能力。
 * 自定义能力 dentall_content_editor 仅作为项目角色标记使用。
 *
 * @return array<string, bool>
 */
function dentall_core_get_content_editor_capabilities() {
	return array(
		'read'                   => true,
		'edit_posts'             => true,
		'delete_posts'           => true,
		'upload_files'           => true,
		'edit_products'          => true,
		'delete_products'        => true,
		'assign_product_terms'   => true,
		'dentall_content_editor' => true,
	);
}

/**
 * 返回Website Manager当前阶段的商城运营能力白名单。
 *
 * Website Manager负责完整的业务内容与商城运营，包括文章、页面、媒体、评论、
 * 商品、订单和优惠券。WordPress用户、插件、主题、代码和系统管理权限仍不授予。
 *
 * @return array<string, bool>
 */
function dentall_core_get_website_manager_capabilities() {
	return array(
		'read'                               => true,
		'upload_files'                       => true,
		'edit_posts'                          => true,
		'edit_others_posts'                   => true,
		'publish_posts'                       => true,
		'read_private_posts'                  => true,
		'edit_private_posts'                  => true,
		'edit_published_posts'                => true,
		'delete_posts'                        => true,
		'delete_private_posts'                => true,
		'delete_published_posts'              => true,
		'delete_others_posts'                 => true,
		'edit_pages'                          => true,
		'edit_others_pages'                   => true,
		'publish_pages'                       => true,
		'read_private_pages'                  => true,
		'edit_private_pages'                  => true,
		'edit_published_pages'                => true,
		'delete_pages'                        => true,
		'delete_private_pages'                => true,
		'delete_published_pages'              => true,
		'delete_others_pages'                 => true,
		'manage_categories'                   => true,
		'moderate_comments'                   => true,
		'wpseo_edit_advanced_metadata'        => true,
		'manage_woocommerce'                 => true,
		'create_customers'                   => true,
		'view_woocommerce_reports'           => true,
		'edit_product'                       => true,
		'read_product'                       => true,
		'delete_product'                     => true,
		'edit_products'                      => true,
		'edit_others_products'               => true,
		'publish_products'                   => true,
		'read_private_products'              => true,
		'edit_private_products'              => true,
		'edit_published_products'            => true,
		'delete_products'                    => true,
		'delete_private_products'            => true,
		'delete_published_products'          => true,
		'delete_others_products'             => true,
		'manage_product_terms'               => true,
		'edit_product_terms'                 => true,
		'delete_product_terms'               => true,
		'assign_product_terms'               => true,
		'edit_shop_order'                    => true,
		'read_shop_order'                    => true,
		'delete_shop_order'                  => true,
		'edit_shop_orders'                   => true,
		'edit_others_shop_orders'            => true,
		'publish_shop_orders'                => true,
		'read_private_shop_orders'           => true,
		'delete_shop_orders'                 => true,
		'delete_private_shop_orders'         => true,
		'delete_published_shop_orders'       => true,
		'delete_others_shop_orders'          => true,
		'edit_private_shop_orders'           => true,
		'edit_published_shop_orders'         => true,
		'manage_shop_order_terms'            => true,
		'edit_shop_order_terms'              => true,
		'delete_shop_order_terms'            => true,
		'assign_shop_order_terms'            => true,
		'edit_shop_coupon'                   => true,
		'read_shop_coupon'                   => true,
		'delete_shop_coupon'                 => true,
		'edit_shop_coupons'                  => true,
		'edit_others_shop_coupons'           => true,
		'publish_shop_coupons'               => true,
		'read_private_shop_coupons'          => true,
		'delete_shop_coupons'                => true,
		'delete_private_shop_coupons'        => true,
		'delete_published_shop_coupons'      => true,
		'delete_others_shop_coupons'         => true,
		'edit_private_shop_coupons'          => true,
		'edit_published_shop_coupons'        => true,
		'manage_shop_coupon_terms'           => true,
		'edit_shop_coupon_terms'             => true,
		'delete_shop_coupon_terms'           => true,
		'assign_shop_coupon_terms'           => true,
		DENTALL_WEBSITE_MANAGER_MARKER       => true,
	);
}

/**
 * 使用明确白名单创建或同步一个项目角色。
 *
 * 使用白名单覆盖现有能力，避免后续版本移除某项权限时旧角色继续残留。
 *
 * @param string              $role_name    角色键名。
 * @param string              $display_name 后台显示名称。
 * @param array<string, bool> $capabilities 明确能力白名单。
 * @return bool
 */
function dentall_core_sync_role( $role_name, $display_name, $capabilities ) {
	$role = get_role( $role_name );

	if ( null === $role ) {
		add_role( $role_name, $display_name, $capabilities );
		$role = get_role( $role_name );
	}

	if ( null === $role ) {
		return false;
	}

	foreach ( array_keys( $role->capabilities ) as $capability ) {
		if ( ! array_key_exists( $capability, $capabilities ) ) {
			$role->remove_cap( $capability );
		}
	}

	foreach ( $capabilities as $capability => $grant ) {
		$role->add_cap( $capability, $grant );
	}

	return true;
}

/**
 * 创建或同步当前项目角色。
 *
 * 只有两个角色都成功同步后才提升版本号，避免部分失败被错误标记为完成。
 *
 * @return void
 */
function dentall_core_sync_roles() {
	$content_editor_synced = dentall_core_sync_role(
		DENTALL_CONTENT_ROLE,
		__( 'DentAll Content Editor', 'dentall-core' ),
		dentall_core_get_content_editor_capabilities()
	);
	$website_manager_synced = dentall_core_sync_role(
		DENTALL_WEBSITE_MANAGER_ROLE,
		__( 'DentAll Website Manager', 'dentall-core' ),
		dentall_core_get_website_manager_capabilities()
	);

	if ( $content_editor_synced && $website_manager_synced ) {
		update_option( DENTALL_CORE_ROLE_OPTION, DENTALL_CORE_ROLE_VERSION, false );
	}
}

register_activation_hook( __FILE__, 'dentall_core_sync_roles' );

/**
 * 仅在角色定义版本变化时重新同步，避免每个请求重复写数据库。
 *
 * @return void
 */
function dentall_core_maybe_sync_roles() {
	if ( DENTALL_CORE_ROLE_VERSION !== get_option( DENTALL_CORE_ROLE_OPTION ) ) {
		dentall_core_sync_roles();
	}
}
add_action( 'init', 'dentall_core_maybe_sync_roles', 5 );

/**
 * 判断当前用户是否属于第一阶段受限媒体角色。
 *
 * Website Manager与Content Editor在D12都只能上传安全位图；PDF等格式在完成
 * 独立权限与公开访问测试后再按计划开放。
 *
 * @return bool
 */
function dentall_core_is_restricted_media_user() {
	return current_user_can( 'dentall_content_editor' )
		|| current_user_can( DENTALL_WEBSITE_MANAGER_MARKER );
}

/**
 * 内容试录员只允许上传第一阶段需要的安全位图格式。
 *
 * @param array<string, string> $mime_types 当前允许的 MIME 类型。
 * @return array<string, string>
 */
function dentall_core_limit_content_editor_mime_types( $mime_types ) {
	if ( ! dentall_core_is_restricted_media_user() ) {
		return $mime_types;
	}

	$allowed_keys = array( 'jpg|jpeg|jpe', 'png', 'webp' );

	return array_intersect_key( $mime_types, array_flip( $allowed_keys ) );
}
add_filter( 'upload_mimes', 'dentall_core_limit_content_editor_mime_types', PHP_INT_MAX );

/**
 * 让媒体界面显示与服务端校验一致的5MB上限。
 *
 * @param int $size 当前上传大小上限，单位为字节。
 * @return int
 */
function dentall_core_limit_content_editor_upload_size_display( $size ) {
	if ( ! dentall_core_is_restricted_media_user() ) {
		return $size;
	}

	return min( (int) $size, 5 * MB_IN_BYTES );
}
add_filter( 'upload_size_limit', 'dentall_core_limit_content_editor_upload_size_display', PHP_INT_MAX );

/**
 * 限制试录图片大小，先阻止未经压缩的大文件进入内容生产线。
 *
 * @param array<string, mixed> $file 上传文件信息。
 * @return array<string, mixed>
 */
function dentall_core_limit_content_editor_upload_size( $file ) {
	if ( ! dentall_core_is_restricted_media_user() ) {
		return $file;
	}

	$maximum_size = 5 * MB_IN_BYTES;
	$file_size    = isset( $file['size'] ) ? (int) $file['size'] : 0;

	// REST原始请求通过sideload处理，文件数组可能没有size，必须读取临时文件。
	if ( 0 >= $file_size && isset( $file['tmp_name'] ) && is_string( $file['tmp_name'] ) && is_file( $file['tmp_name'] ) ) {
		$detected_size = filesize( $file['tmp_name'] );
		$file_size     = false === $detected_size ? 0 : (int) $detected_size;
	}

	if ( $file_size > $maximum_size ) {
		$file['error'] = __( 'Please compress this image to 5 MB or less before uploading.', 'dentall-core' );
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'dentall_core_limit_content_editor_upload_size', PHP_INT_MAX );
add_filter( 'wp_handle_sideload_prefilter', 'dentall_core_limit_content_editor_upload_size', PHP_INT_MAX );

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

/**
 * 移除不属于项目业务角色日常流程的后台菜单。
 *
 * 菜单隐藏只改善界面；直接URL仍由下方的请求拦截负责。
 *
 * @return void
 */
function dentall_core_remove_restricted_admin_menus() {
	if ( current_user_can( 'dentall_content_editor' ) ) {
		remove_menu_page( 'edit-comments.php' );
	}

	if ( current_user_can( 'dentall_content_editor' ) || current_user_can( DENTALL_WEBSITE_MANAGER_MARKER ) ) {
		remove_menu_page( 'tools.php' );
	}
}
add_action( 'admin_menu', 'dentall_core_remove_restricted_admin_menus', PHP_INT_MAX );

/**
 * 判断项目业务角色是否应被拒绝访问指定后台页面。
 *
 * @param string $page WordPress后台入口文件名。
 * @return bool
 */
function dentall_core_is_restricted_admin_page( $page ) {
	if ( 'tools.php' === $page ) {
		return current_user_can( 'dentall_content_editor' )
			|| current_user_can( DENTALL_WEBSITE_MANAGER_MARKER );
	}

	return 'edit-comments.php' === $page
		&& current_user_can( 'dentall_content_editor' );
}

/**
 * 阻止内容试录员通过直接URL绕过精简菜单。
 *
 * @return void
 */
function dentall_core_block_restricted_admin_pages() {
	global $pagenow;

	if ( dentall_core_is_restricted_admin_page( (string) $pagenow ) ) {
		wp_die(
			esc_html__( 'You are not allowed to access this page.', 'dentall-core' ),
			esc_html__( 'Access denied', 'dentall-core' ),
			array( 'response' => 403 )
		);
	}
}
add_action( 'admin_init', 'dentall_core_block_restricted_admin_pages', 1 );
