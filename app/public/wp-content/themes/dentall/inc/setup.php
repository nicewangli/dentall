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
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'after_setup_theme', 'dentall_theme_setup', 20 );

/**
 * 加载全站壳层资源。
 *
 * Header、导航和Footer具有相同的全站加载生命周期，统一放入site-shell.css；
 * 依赖Storefront已经登记的子主题基础样式，确保Design Token和覆盖顺序稳定。D33移除
 * Handheld Footer DOM后，同步移除只为该DOM服务的父主题脚本。
 *
 * @return void
 */
function dentall_enqueue_site_shell_assets() {
	$theme = wp_get_theme( get_stylesheet() );

	wp_enqueue_style(
		'dentall-site-shell',
		get_stylesheet_directory_uri() . '/assets/css/site-shell.css',
		array( 'storefront-child-style' ),
		$theme->get( 'Version' )
	);

	wp_dequeue_script( 'storefront-handheld-footer-bar' );
}
add_action( 'wp_enqueue_scripts', 'dentall_enqueue_site_shell_assets', 40 );
