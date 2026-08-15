<?php
/**
 * D12 C4 商品回收站与恢复审计。
 *
 * 以Website Manager身份执行publish -> trash -> publish，仅作用于固定TEST商品44。
 * 运行：wp eval-file project-docs/tests/day12-c4-trash-restore-audit.php --path=app/public
 */

$user    = get_user_by( 'login', 'dentall_d12_manager' );
$product = wc_get_product( 44 );

if ( false === $user
	|| array( DENTALL_WEBSITE_MANAGER_ROLE ) !== $user->roles
	|| ! user_can( $user, DENTALL_WEBSITE_MANAGER_MARKER )
	|| user_can( $user, 'manage_options' )
	|| ! $product instanceof WC_Product_Simple
	|| 'TEST D12 Simple Fixed Pack' !== $product->get_name()
	|| 'TEST-D12-SIMPLE-001' !== $product->get_sku()
) {
	WP_CLI::error( '安全检查失败：测试账号或商品44不符合预期。' );
}

wp_set_current_user( $user->ID );

if ( ! current_user_can( 'delete_post', $product->get_id() ) ) {
	WP_CLI::error( 'Website Manager没有将商品移入回收站的对象级权限。' );
}

$trashed = wp_trash_post( $product->get_id() );

if ( ! $trashed instanceof WP_Post || 'trash' !== get_post_status( $product->get_id() ) ) {
	WP_CLI::error( '商品未成功进入回收站。' );
}

WP_CLI::log( 'trash=PASS' );

if ( ! current_user_can( 'delete_post', $product->get_id() ) ) {
	WP_CLI::error( 'Website Manager在回收站状态下缺少恢复所需的对象级权限。' );
}

$restored = wp_untrash_post( $product->get_id() );

if ( ! $restored instanceof WP_Post || 'publish' !== get_post_status( $product->get_id() ) ) {
	WP_CLI::error( '商品未成功恢复到发布状态。' );
}

WP_CLI::log( 'restore_publish=PASS' );
WP_CLI::success( 'D12 C4商品回收站与恢复审计通过。' );
