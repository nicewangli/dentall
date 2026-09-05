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

/**
 * 按页面身份加载商品目录资源。
 *
 * Shop、商品taxonomy与商品搜索共享目录样式；移动筛选脚本只进入实际输出筛选DOM的
 * Shop与商品分类。普通WordPress搜索继续使用Storefront自身资源。
 *
 * @return void
 */
function dentall_enqueue_catalog_assets() {
	if (
		! function_exists( 'is_shop' )
		|| ! function_exists( 'is_product_taxonomy' )
	) {
		return;
	}

	$is_catalog_archive = ! is_search() && ( is_shop() || is_product_taxonomy() );
	$is_product_search  = is_search()
		&& is_post_type_archive( 'product' )
		&& 'product' === get_query_var( 'post_type' );

	if ( ! $is_catalog_archive && ! $is_product_search ) {
		return;
	}

	$theme = wp_get_theme( get_stylesheet() );

	wp_enqueue_style(
		'dentall-catalog',
		get_stylesheet_directory_uri() . '/assets/css/catalog.css',
		array( 'dentall-site-shell' ),
		$theme->get( 'Version' )
	);

	if ( function_exists( 'dentall_is_catalog_filter_archive' ) && dentall_is_catalog_filter_archive() ) {
		wp_enqueue_script(
			'dentall-catalog-filters',
			get_stylesheet_directory_uri() . '/assets/js/catalog-filters.js',
			array(),
			$theme->get( 'Version' ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'dentall_enqueue_catalog_assets', 45 );

/**
 * 只在WooCommerce商品详情页加载详情结构样式。
 *
 * D55保留WooCommerce与Storefront原生模板和Hook，只调整顶层PC骨架；
 * D56图库样式沿用同一按页资源，摘要字段与购买交互继续由后续Day分别负责。
 *
 * @return void
 */
function dentall_enqueue_product_detail_assets() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	$theme = wp_get_theme( get_stylesheet() );

	wp_enqueue_style(
		'dentall-product-detail',
		get_stylesheet_directory_uri() . '/assets/css/product-detail.css',
		array( 'dentall-site-shell' ),
		$theme->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'dentall_enqueue_product_detail_assets', 50 );
