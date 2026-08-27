<?php

defined( 'ABSPATH' ) || exit;

/**
 * 初始化DentAll子主题自身能力。
 *
 * Storefront已经注册WooCommerce支持、菜单位置及通用主题能力，子主题不重复注册。
 *
 * @return void
 */
function dentall_theme_setup() {
	load_child_theme_textdomain( 'dentall', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'dentall_theme_setup', 20 );

/**
 * 加载全站壳层样式。
 *
 * Header、导航和Footer具有相同的全站加载生命周期，统一放入site-shell.css；
 * 依赖Storefront已经登记的子主题基础样式，确保Design Token和覆盖顺序稳定。
 *
 * @return void
 */
function dentall_enqueue_site_shell_styles() {
	$theme = wp_get_theme( get_stylesheet() );

	wp_enqueue_style(
		'dentall-site-shell',
		get_stylesheet_directory_uri() . '/assets/css/site-shell.css',
		array( 'storefront-child-style' ),
		$theme->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'dentall_enqueue_site_shell_styles', 40 );
