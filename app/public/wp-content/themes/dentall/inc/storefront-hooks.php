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
