<?php

defined( 'ABSPATH' ) || exit;

function dentall_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus(
		array(
			'primary' => __( 'Primary menu', 'dentall' ),
		)
	);
}
add_action( 'after_setup_theme', 'dentall_setup' );

function dentall_enqueue_assets() {
	wp_enqueue_style(
		'dentall-style',
		get_stylesheet_uri(),
		array(),
		'0.1.0'
	);
}
add_action( 'wp_enqueue_scripts', 'dentall_enqueue_assets' );
