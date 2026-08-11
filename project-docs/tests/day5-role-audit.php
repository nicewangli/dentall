<?php
/**
 * D5 内容试录员权限审计脚本。
 *
 * 运行：wp eval-file project-docs/tests/day5-role-audit.php --path=app/public
 */

$user = get_user_by( 'login', 'dentall_d6_editor' );

if ( false === $user ) {
	WP_CLI::error( '未找到 dentall_d6_editor 测试账号。' );
}

wp_set_current_user( $user->ID );

$checks = array(
	'account_role_exact'         => array( DENTALL_CONTENT_ROLE ) === $user->roles,
	'account_no_extra_caps'      => array( DENTALL_CONTENT_ROLE ) === array_keys( array_filter( $user->caps ) ),
	'allow_read'                 => current_user_can( 'read' ),
	'allow_own_post_draft'       => current_user_can( 'edit_posts' ),
	'allow_own_product_draft'    => current_user_can( 'edit_products' ),
	'allow_upload'               => current_user_can( 'upload_files' ),
	'deny_publish_posts'         => ! current_user_can( 'publish_posts' ),
	'deny_publish_products'      => ! current_user_can( 'publish_products' ),
	'deny_edit_others_posts'     => ! current_user_can( 'edit_others_posts' ),
	'deny_edit_others_products'  => ! current_user_can( 'edit_others_products' ),
	'deny_delete_others_posts'   => ! current_user_can( 'delete_others_posts' ),
	'deny_delete_others_products'=> ! current_user_can( 'delete_others_products' ),
	'deny_edit_published_posts'  => ! current_user_can( 'edit_published_posts' ),
	'deny_edit_published_products' => ! current_user_can( 'edit_published_products' ),
	'deny_read_private_posts'    => ! current_user_can( 'read_private_posts' ),
	'deny_read_private_products' => ! current_user_can( 'read_private_products' ),
	'deny_pages'                 => ! current_user_can( 'edit_pages' ),
	'deny_manage_product_terms'  => ! current_user_can( 'manage_product_terms' ),
	'deny_manage_woocommerce'    => ! current_user_can( 'manage_woocommerce' ),
	'deny_orders'                => ! current_user_can( 'edit_shop_orders' ),
	'deny_coupons'               => ! current_user_can( 'edit_shop_coupons' ),
	'deny_users'                 => ! current_user_can( 'list_users' ) && ! current_user_can( 'create_users' ) && ! current_user_can( 'promote_users' ),
	'deny_delete_users'          => ! current_user_can( 'delete_users' ),
	'deny_plugins'               => ! current_user_can( 'activate_plugins' ) && ! current_user_can( 'install_plugins' ),
	'deny_plugin_updates'        => ! current_user_can( 'update_plugins' ),
	'deny_themes'                => ! current_user_can( 'switch_themes' ) && ! current_user_can( 'edit_theme_options' ),
	'deny_theme_updates'         => ! current_user_can( 'update_themes' ),
	'deny_settings'              => ! current_user_can( 'manage_options' ),
	'deny_unfiltered_upload'     => ! current_user_can( 'unfiltered_upload' ),
	'deny_import_export'         => ! current_user_can( 'import' ) && ! current_user_can( 'export' ),
);

// 用真实对象验证meta capability映射，测试结束后由管理员强制清理。
$administrator = get_users(
	array(
		'role'   => 'administrator',
		'number' => 1,
	)
);

if ( empty( $administrator ) ) {
	WP_CLI::error( '未找到用于创建对照对象的管理员。' );
}

wp_set_current_user( $administrator[0]->ID );
$other_post_id = wp_insert_post(
	array(
		'post_author' => $administrator[0]->ID,
		'post_title'  => 'DentAll D5 other draft audit',
		'post_status' => 'draft',
		'post_type'   => 'post',
	)
);
$other_product = new WC_Product_Simple();
$other_product->set_name( 'DentAll D5 other product audit' );
$other_product->set_status( 'draft' );
$other_product_id = $other_product->save();

wp_set_current_user( $user->ID );
$own_post_id = wp_insert_post(
	array(
		'post_author' => $user->ID,
		'post_title'  => 'DentAll D5 own draft audit',
		'post_status' => 'draft',
		'post_type'   => 'post',
	)
);
$own_product = new WC_Product_Simple();
$own_product->set_name( 'DentAll D5 own product audit' );
$own_product->set_status( 'draft' );
$own_product_id = $own_product->save();

$object_ids = array( $own_post_id, $other_post_id, $own_product_id, $other_product_id );

if ( array_filter( $object_ids, 'is_wp_error' ) || in_array( 0, $object_ids, true ) ) {
	WP_CLI::error( '创建权限审计临时对象失败。' );
}

wp_set_current_user( $user->ID );
$checks['allow_edit_own_post']       = current_user_can( 'edit_post', $own_post_id );
$checks['allow_delete_own_post']     = current_user_can( 'delete_post', $own_post_id );
$checks['deny_edit_other_post']      = ! current_user_can( 'edit_post', $other_post_id );
$checks['deny_delete_other_post']    = ! current_user_can( 'delete_post', $other_post_id );
$checks['allow_edit_own_product']    = current_user_can( 'edit_post', $own_product_id );
$checks['allow_delete_own_product']  = current_user_can( 'delete_post', $own_product_id );
$checks['deny_edit_other_product']   = ! current_user_can( 'edit_post', $other_product_id );
$checks['deny_delete_other_product'] = ! current_user_can( 'delete_post', $other_product_id );

$allowed_mimes               = get_allowed_mime_types( $user );
$checks['media_mimes_exact'] = array_keys( $allowed_mimes ) === array( 'jpg|jpeg|jpe', 'png', 'webp' );

$small_upload = apply_filters(
	'wp_handle_upload_prefilter',
	array(
		'name' => 'image.jpg',
		'size' => 1024,
	)
);
$large_upload = apply_filters(
	'wp_handle_upload_prefilter',
	array(
		'name' => 'image.jpg',
		'size' => ( 5 * MB_IN_BYTES ) + 1,
	)
);

$checks['media_small_allowed'] = empty( $small_upload['error'] );
$checks['media_large_denied']  = ! empty( $large_upload['error'] );
$checks['media_limit_display'] = 5 * MB_IN_BYTES === wp_max_upload_size();

$product_tag_result = apply_filters( 'pre_insert_term', 'DentAll D5 forbidden tag', 'product_tag' );
$checks['deny_create_product_tag'] = is_wp_error( $product_tag_result )
	&& 'dentall_product_tag_creation_denied' === $product_tag_result->get_error_code();

wp_set_current_user( $administrator[0]->ID );
wp_delete_post( $own_post_id, true );
wp_delete_post( $other_post_id, true );
foreach ( array( $own_product_id, $other_product_id ) as $product_id ) {
	$product = wc_get_product( $product_id );
	if ( $product ) {
		$product->delete( true );
	}
}
$checks['temporary_objects_removed'] = ! array_filter( $object_ids, 'get_post_status' );

foreach ( $checks as $name => $passed ) {
	WP_CLI::line( sprintf( "%s\t%s", $passed ? 'PASS' : 'FAIL', $name ) );
}

if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( 'D5 权限审计未通过。' );
}

WP_CLI::success( 'D5 权限审计全部通过。' );
