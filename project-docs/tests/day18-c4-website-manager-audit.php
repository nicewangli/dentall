<?php
/**
 * D18 C4 Website Manager角色能力与WooCommerce CRUD持久化审计。
 *
 * 脚本仅在Local创建一个临时TEST Simple商品，并在同次执行中永久清理。
 * CRUD检查不替代真实后台表单的capability、nonce与请求路径验证。
 * 运行：wp eval-file project-docs/tests/day18-c4-website-manager-audit.php --path=app/public
 */

$site_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

if ( 'local' !== wp_get_environment_type() || 'dentall.local' !== $site_host ) {
	WP_CLI::error( '安全中止：本审计只允许在WP_ENVIRONMENT_TYPE=local且主机为dentall.local时运行。' );
}

$user = get_user_by( 'login', 'dentall_d12_manager' );

if ( false === $user || array( DENTALL_WEBSITE_MANAGER_ROLE ) !== $user->roles ) {
	WP_CLI::error( '未找到符合预期的Website Manager测试账号。' );
}

wp_set_current_user( $user->ID );

$expected_capabilities = array_keys(
	array_filter( dentall_core_get_website_manager_capabilities() )
);
$actual_role           = get_role( DENTALL_WEBSITE_MANAGER_ROLE );
$actual_capabilities   = null === $actual_role
	? array()
	: array_keys( array_filter( $actual_role->capabilities ) );

sort( $expected_capabilities );
sort( $actual_capabilities );

$checks = array(
	'account_role_exact'                => array( DENTALL_WEBSITE_MANAGER_ROLE ) === $user->roles,
	'account_no_extra_caps'             => array( DENTALL_WEBSITE_MANAGER_ROLE ) === array_keys( array_filter( $user->caps ) ),
	'role_whitelist_exact'              => $expected_capabilities === $actual_capabilities,
	'allow_yoast_advanced_metadata'     => current_user_can( 'wpseo_edit_advanced_metadata' ),
	'allow_edit_published_product'      => current_user_can( 'edit_post', 44 ),
	'allow_publish_products'            => current_user_can( 'publish_products' ),
	'deny_manage_options'               => ! current_user_can( 'manage_options' ),
	'deny_user_management'              => ! current_user_can( 'list_users' )
		&& ! current_user_can( 'create_users' )
		&& ! current_user_can( 'promote_users' )
		&& ! current_user_can( 'delete_users' ),
	'deny_plugin_management'            => ! current_user_can( 'activate_plugins' )
		&& ! current_user_can( 'install_plugins' )
		&& ! current_user_can( 'update_plugins' ),
	'deny_theme_management'             => ! current_user_can( 'switch_themes' )
		&& ! current_user_can( 'edit_theme_options' )
		&& ! current_user_can( 'update_themes' ),
);

$existing_sku_id = wc_get_product_id_by_sku( 'TEST-D18-C4-TEMP' );

if ( 0 !== $existing_sku_id ) {
	WP_CLI::error( '发现遗留的C4临时商品，请先人工核对后再运行审计。' );
}

$temporary_product_id = 0;
$product              = null;
$runtime_error        = '';
$cleanup_errors       = array();

try {
	$product = new WC_Product_Simple();
	$product->set_name( 'TEST D18 C4 Temporary Product' );
	$product->set_status( 'draft' );
	$product->set_sku( 'TEST-D18-C4-TEMP' );
	$product->set_regular_price( '1.00' );
	$product->set_stock_status( 'instock' );
	$temporary_product_id = $product->save();

	$draft_product = wc_get_product( $temporary_product_id );
	$checks['allow_edit_temporary_draft'] = current_user_can( 'edit_post', $temporary_product_id );
	$checks['crud_draft_persisted'] = $draft_product instanceof WC_Product_Simple
		&& 'draft' === $draft_product->get_status()
		&& (int) $user->ID === (int) get_post_field( 'post_author', $temporary_product_id );

	if ( $draft_product instanceof WC_Product_Simple ) {
		$draft_product->set_purchase_note( 'TEST D18 C4 persistence marker' );
		$draft_product->set_status( 'publish' );
		$draft_product->save();
	}

	$published_product = wc_get_product( $temporary_product_id );
	$checks['crud_publish_persisted'] = $published_product instanceof WC_Product_Simple
		&& 'publish' === $published_product->get_status()
		&& 'TEST D18 C4 persistence marker' === $published_product->get_purchase_note();

	if ( $published_product instanceof WC_Product_Simple ) {
		$published_product->set_purchase_note( '' );
		$published_product->set_status( 'draft' );
		$published_product->save();
	}

	$restored_draft = wc_get_product( $temporary_product_id );
	$checks['crud_return_to_draft_persisted'] = $restored_draft instanceof WC_Product_Simple
		&& 'draft' === $restored_draft->get_status()
		&& '' === $restored_draft->get_purchase_note();
} catch ( Throwable $throwable ) {
	$runtime_error = $throwable->getMessage();
} finally {
	$cleanup_ids = array( $temporary_product_id );

	if ( $product instanceof WC_Product ) {
		$cleanup_ids[] = $product->get_id();
	}

	try {
		$cleanup_ids[] = wc_get_product_id_by_sku( 'TEST-D18-C4-TEMP' );
	} catch ( Throwable $throwable ) {
		$cleanup_errors[] = sprintf( '按SKU解析清理对象失败：%s', $throwable->getMessage() );
	}

	foreach ( array_unique( array_filter( array_map( 'absint', $cleanup_ids ) ) ) as $cleanup_id ) {
		try {
			$temporary_product = wc_get_product( $cleanup_id );

			if ( $temporary_product ) {
				$temporary_product->delete( true );
			}
		} catch ( Throwable $throwable ) {
			$cleanup_errors[] = sprintf( '临时商品%d清理失败：%s', $cleanup_id, $throwable->getMessage() );
		}
	}
}

$checks['temporary_product_removed'] = 0 < $temporary_product_id
	&& false === get_post_status( $temporary_product_id )
	&& 0 === wc_get_product_id_by_sku( 'TEST-D18-C4-TEMP' );

foreach ( $checks as $name => $passed ) {
	WP_CLI::line( sprintf( "%s\t%s", $passed ? 'PASS' : 'FAIL', $name ) );
}

if ( '' !== $runtime_error ) {
	WP_CLI::warning( sprintf( '运行时异常：%s', $runtime_error ) );
}

foreach ( $cleanup_errors as $cleanup_error ) {
	WP_CLI::warning( $cleanup_error );
}

if ( '' !== $runtime_error || ! empty( $cleanup_errors ) || in_array( false, $checks, true ) ) {
	WP_CLI::error( 'D18 C4 Website Manager审计未通过。' );
}

WP_CLI::success( 'D18 C4 Website Manager审计通过。' );
