<?php

defined( 'ABSPATH' ) || exit;

/**
 * 禁止未分配菜单时回退为全部已发布Page。
 *
 * Storefront默认会为Primary与Handheld位置调用WordPress页面菜单回退，可能把尚未批准进入导航的
 * 已发布Page直接公开。已经在后台分配的菜单不受影响，正式导航结构仍由后续页面与菜单工作维护。
 *
 * @param array $args 菜单渲染参数。
 * @return array
 */
function dentall_disable_page_menu_fallback( $args ) {
	$controlled_locations = array( 'primary', 'handheld' );

	if (
		isset( $args['theme_location'] )
		&& in_array( $args['theme_location'], $controlled_locations, true )
	) {
		$args['fallback_cb'] = false;
	}

	return $args;
}
add_filter( 'wp_nav_menu_args', 'dentall_disable_page_menu_fallback', 20 );

/**
 * 使用WooCommerce原生可见标签输出商品目录排序控件。
 *
 * @return void
 */
function dentall_catalog_ordering_with_label() {
	if ( ! function_exists( 'woocommerce_catalog_ordering' ) ) {
		return;
	}

	woocommerce_catalog_ordering(
		array(
			'useLabel' => true,
		)
	);
}

/**
 * 将Storefront上下两处商品目录排序替换为带可见标签的原生输出。
 *
 * 子主题functions.php早于父主题加载，因此等待after_setup_theme后再替换父主题Hook。
 *
 * @return void
 */
function dentall_enable_catalog_ordering_labels() {
	if ( ! function_exists( 'woocommerce_catalog_ordering' ) ) {
		return;
	}

	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );

	add_action( 'woocommerce_before_shop_loop', 'dentall_catalog_ordering_with_label', 10 );
	add_action( 'woocommerce_after_shop_loop', 'dentall_catalog_ordering_with_label', 10 );
}
add_action( 'after_setup_theme', 'dentall_enable_catalog_ordering_labels', 30 );
