<?php
/**
 * Plugin Name: DentAll Measurement
 * Description: DentAll 的最小 WooCommerce 事件数据源；不加载 Google 标签。
 * Version: 0.2.0
 * Requires at least: 7.0
 * Requires PHP: 8.2
 * Requires Plugins: woocommerce
 * Text Domain: dentall-measurement
 * License: GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

const DENTALL_MEASUREMENT_FILE    = __FILE__;
const DENTALL_MEASUREMENT_VERSION = '0.2.0';

function dentall_measurement_load() {
	if ( class_exists( 'WooCommerce' ) ) {
		require_once __DIR__ . '/includes/consent.php';
		require_once __DIR__ . '/includes/events.php';
		require_once __DIR__ . '/includes/purchase.php';
	}
}
add_action( 'plugins_loaded', 'dentall_measurement_load' );

function dentall_measurement_declare_compatibility() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}
add_action( 'before_woocommerce_init', 'dentall_measurement_declare_compatibility' );
