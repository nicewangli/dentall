<?php
/**
 * DentAll业务角色的后台菜单和直接URL访问边界。
 */

defined( 'ABSPATH' ) || exit;

/**
 * 移除不属于项目业务角色日常流程的后台菜单。
 *
 * 菜单隐藏只改善界面；直接URL仍由下方的请求拦截负责。
 *
 * @return void
 */
function dentall_core_remove_restricted_admin_menus() {
	if ( current_user_can( 'dentall_content_editor' ) ) {
		remove_menu_page( 'edit-comments.php' );
	}

	if ( current_user_can( 'dentall_content_editor' ) || current_user_can( DENTALL_WEBSITE_MANAGER_MARKER ) ) {
		remove_menu_page( 'tools.php' );
	}
}
add_action( 'admin_menu', 'dentall_core_remove_restricted_admin_menus', PHP_INT_MAX );

/**
 * 判断项目业务角色是否应被拒绝访问指定后台页面。
 *
 * @param string $page WordPress后台入口文件名。
 * @return bool
 */
function dentall_core_is_restricted_admin_page( $page ) {
	if ( 'tools.php' === $page ) {
		return current_user_can( 'dentall_content_editor' )
			|| current_user_can( DENTALL_WEBSITE_MANAGER_MARKER );
	}

	return 'edit-comments.php' === $page
		&& current_user_can( 'dentall_content_editor' );
}

/**
 * 阻止内容试录员通过直接URL绕过精简菜单。
 *
 * @return void
 */
function dentall_core_block_restricted_admin_pages() {
	global $pagenow;

	if ( dentall_core_is_restricted_admin_page( (string) $pagenow ) ) {
		wp_die(
			esc_html__( 'You are not allowed to access this page.', 'dentall-core' ),
			esc_html__( 'Access denied', 'dentall-core' ),
			array( 'response' => 403 )
		);
	}
}
add_action( 'admin_init', 'dentall_core_block_restricted_admin_pages', 1 );
