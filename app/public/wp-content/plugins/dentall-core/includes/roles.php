<?php
/**
 * DentAll项目角色、能力白名单和版本化同步。
 */

defined( 'ABSPATH' ) || exit;

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
		'create_customers'                    => true,
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
		'edit_others_shop_orders'             => true,
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
		'delete_others_shop_coupons'          => true,
		'edit_private_shop_coupons'           => true,
		'edit_published_shop_coupons'         => true,
		'manage_shop_coupon_terms'            => true,
		'edit_shop_coupon_terms'              => true,
		'delete_shop_coupon_terms'            => true,
		'assign_shop_coupon_terms'            => true,
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

register_activation_hook( DENTALL_CORE_PLUGIN_FILE, 'dentall_core_sync_roles' );

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
