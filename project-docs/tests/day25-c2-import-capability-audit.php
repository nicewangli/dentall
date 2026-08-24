<?php
/**
 * D25 C2 Website Manager原生导入能力只读审计。
 *
 * 本脚本只切换用户和请求上下文，不上传CSV、不创建商品、不修改角色。
 * 运行：wp eval-file project-docs/tests/day25-c2-import-capability-audit.php --path=app/public
 */

$site_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

if ( 'local' !== wp_get_environment_type() || 'dentall.local' !== $site_host ) {
	WP_CLI::error( '安全中止：本审计只允许在WP_ENVIRONMENT_TYPE=local且主机为dentall.local时运行。' );
}

$manager = get_user_by( 'login', 'dentall_d12_manager' );
$editor  = get_user_by( 'login', 'dentall_d6_editor' );
$admins  = get_users(
	array(
		'role'   => 'administrator',
		'number' => 1,
	)
);
$admin   = $admins ? current( $admins ) : false;

if ( false === $manager || array( DENTALL_WEBSITE_MANAGER_ROLE ) !== $manager->roles ) {
	WP_CLI::error( '未找到符合预期的Website Manager测试账号。' );
}

if ( false === $editor || array( DENTALL_CONTENT_ROLE ) !== $editor->roles || false === $admin ) {
	WP_CLI::error( '未找到权限反向对照账号。' );
}

global $pagenow;

$original_user_id = get_current_user_id();
$original_pagenow = $pagenow;
$original_get     = $_GET;
$original_request = $_REQUEST;
$checks           = array();

try {
	wp_set_current_user( $manager->ID );
	$role = get_role( DENTALL_WEBSITE_MANAGER_ROLE );

	$checks['manager_role_persists_global_import'] = null !== $role
		&& ! empty( $role->capabilities['import'] );
	$checks['manager_role_does_not_persist_export'] = null !== $role
		&& empty( $role->capabilities['export'] );
	$checks['deny_user_management'] = ! current_user_can( 'list_users' )
		&& ! current_user_can( 'create_users' )
		&& ! current_user_can( 'promote_users' );
	$checks['deny_plugin_and_theme_management'] = ! current_user_can( 'activate_plugins' )
		&& ! current_user_can( 'install_plugins' )
		&& ! current_user_can( 'switch_themes' );
	$checks['deny_wordpress_system_settings'] = ! current_user_can( 'manage_options' )
		&& ! current_user_can( 'update_core' );

	$pagenow = 'edit.php';
	$_GET    = array( 'post_type' => 'product' );
	$_REQUEST = $_GET;
	$checks['allow_product_list_import_entry'] = current_user_can( 'import' );
	$checks['allow_product_list_export_entry'] = current_user_can( 'export' );

	$pagenow = 'edit.php';
	$_GET    = array(
		'post_type' => 'product',
		'page'      => 'product_importer',
	);
	$_REQUEST = $_GET;
	$checks['allow_product_importer_screen'] = current_user_can( 'import' );

	$pagenow = 'import.php';
	$_GET    = array();
	$_REQUEST = array();
	$checks['global_import_is_not_product_scoped'] = current_user_can( 'import' );

	$pagenow = 'export.php';
	$_GET    = array();
	$_REQUEST = array();
	$checks['deny_wordpress_content_export'] = ! current_user_can( 'export' );

	$pagenow = 'admin.php';
	$_GET    = array( 'page' => 'wc-settings' );
	$_REQUEST = $_GET;
	$checks['global_import_remains_available_outside_product_screen'] = current_user_can( 'import' );

	$pagenow = 'admin-ajax.php';
	$_GET    = array();
	$_REQUEST = array( 'action' => 'woocommerce_do_ajax_product_import' );
	$checks['allow_woocommerce_product_import_ajax'] = current_user_can( 'import' );

	wp_set_current_user( $editor->ID );
	$pagenow = 'edit.php';
	$_GET    = array(
		'post_type' => 'product',
		'page'      => 'product_importer',
	);
	$_REQUEST = $_GET;
	$checks['content_editor_denied'] = ! current_user_can( 'import' );

	wp_set_current_user( $admin->ID );
	$pagenow = 'import.php';
	$_GET    = array();
	$_REQUEST = array();
	$checks['administrator_native_import_unchanged'] = current_user_can( 'import' );
} finally {
	wp_set_current_user( $original_user_id );
	$pagenow = $original_pagenow;
	$_GET    = $original_get;
	$_REQUEST = $original_request;
}

foreach ( $checks as $name => $passed ) {
	WP_CLI::line( sprintf( "%s\t%s", $passed ? 'PASS' : 'FAIL', $name ) );
}

if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( 'D25 C2商品导入能力边界审计未通过。' );
}

WP_CLI::success( 'D25 C2商品导入能力边界只读审计通过。' );
