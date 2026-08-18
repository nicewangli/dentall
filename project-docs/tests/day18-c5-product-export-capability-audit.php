<?php
/**
 * D18 C5 Website Manager商品导出能力边界审计。
 *
 * 本脚本只切换请求上下文并读取能力，不生成CSV、不修改商品或角色。
 * 运行：wp eval-file project-docs/tests/day18-c5-product-export-capability-audit.php --path=app/public
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

global $pagenow;

$original_pagenow = $pagenow;
$original_get     = $_GET;
$checks           = array();

try {
	$role = get_role( DENTALL_WEBSITE_MANAGER_ROLE );
	$checks['role_does_not_persist_export'] = null !== $role
		&& empty( $role->capabilities['export'] );

	$pagenow = 'edit.php';
	$_GET    = array( 'post_type' => 'product' );
	$checks['allow_product_list_export'] = current_user_can( 'export' );

	$pagenow = 'edit.php';
	$_GET    = array(
		'post_type' => 'product',
		'page'      => 'product_exporter',
	);
	$checks['allow_product_exporter'] = current_user_can( 'export' );

	$pagenow = 'export.php';
	$_GET    = array();
	$checks['deny_wordpress_content_export'] = ! current_user_can( 'export' );

	$pagenow = 'edit.php';
	$_GET    = array( 'post_type' => 'post' );
	$checks['deny_unrelated_admin_export'] = ! current_user_can( 'export' );

	$checks['deny_user_management'] = ! current_user_can( 'list_users' );
	$checks['deny_plugin_management'] = ! current_user_can( 'activate_plugins' );
} finally {
	$pagenow = $original_pagenow;
	$_GET    = $original_get;
}

foreach ( $checks as $name => $passed ) {
	WP_CLI::line( sprintf( "%s\t%s", $passed ? 'PASS' : 'FAIL', $name ) );
}

if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( 'D18 C5 Website Manager商品导出能力边界审计未通过。' );
}

WP_CLI::success( 'D18 C5 Website Manager商品导出能力边界审计通过。' );
