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
