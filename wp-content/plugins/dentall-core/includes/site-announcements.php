<?php
/**
 * 全站公告使用独立保存动作，不为网站负责人开放 WordPress 的通用设置页。
 */

defined( 'ABSPATH' ) || exit;

function dentall_core_announcements_capability() {
	return current_user_can( DENTALL_WEBSITE_MANAGER_MARKER ) ? DENTALL_WEBSITE_MANAGER_MARKER : 'manage_options';
}

add_action( 'admin_menu', static function () {
	$title = __( '全站公告', 'dentall-core' );
	add_submenu_page(
		'edit.php?post_type=page',
		$title,
		$title,
		dentall_core_announcements_capability(),
		'dentall-site-announcements',
		'dentall_core_render_announcements_page'
	);
} );

function dentall_core_render_announcements_page() {
	$stored   = get_option( 'dentall_site_announcements' );
	$messages = is_array( $stored ) ? array_filter( $stored, 'is_string' ) : array();
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<?php if ( isset( $_GET['saved'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( '全站公告已保存。', 'dentall-core' ); ?></p></div>
		<?php endif; ?>
		<p><?php esc_html_e( '输入英文短句，每条最多 80 个字符。留空即可隐藏该条，手机和平板竖屏显示第一条有内容的公告。', 'dentall-core' ); ?></p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="action" value="dentall_save_site_announcements">
			<?php wp_nonce_field( 'dentall_save_site_announcements' ); ?>
			<?php for ( $i = 0; $i < 3; $i++ ) : ?>
				<p>
					<label for="dentall-notice-<?php echo (int) $i; ?>"><?php printf( esc_html__( '公告 %d', 'dentall-core' ), $i + 1 ); ?></label><br>
					<input class="regular-text" type="text" maxlength="80" id="dentall-notice-<?php echo (int) $i; ?>" name="dentall_site_announcements[<?php echo (int) $i; ?>]" value="<?php echo esc_attr( $messages[ $i ] ?? '' ); ?>">
				</p>
			<?php endfor; ?>
			<?php submit_button( __( '保存公告', 'dentall-core' ) ); ?>
		</form>
	</div>
	<?php
}

function dentall_core_save_site_announcements() {
	if ( ! current_user_can( dentall_core_announcements_capability() ) ) {
		wp_die( esc_html__( '无权编辑全站公告。', 'dentall-core' ), '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'dentall_save_site_announcements' );

	$raw = isset( $_POST['dentall_site_announcements'] ) ? wp_unslash( $_POST['dentall_site_announcements'] ) : null;
	if ( ! is_array( $raw ) || array_keys( $raw ) !== array( 0, 1, 2 ) || count( array_filter( $raw, 'is_string' ) ) !== 3 ) {
		wp_die( esc_html__( '请返回后重新提交完整表单。', 'dentall-core' ), '', array( 'response' => 400, 'back_link' => true ) );
	}

	$messages = array_map( 'sanitize_text_field', $raw );
	foreach ( $messages as $message ) {
		if ( mb_strlen( $message, 'UTF-8' ) > 80 ) {
			wp_die( esc_html__( '每条公告最多 80 个字符。', 'dentall-core' ), '', array( 'response' => 400, 'back_link' => true ) );
		}
	}
	if ( update_option( 'dentall_site_announcements', $messages ) ) {
		// 页头影响全站 HTML；只清站点的页面缓存及 Varnish，不清对象缓存或静态资源。
		if ( is_callable( array( 'Breeze_PurgeCache', 'breeze_cache_flush' ) ) ) {
			Breeze_PurgeCache::breeze_cache_flush( false, false, true );
		}
		do_action( 'breeze_clear_varnish' );
	} elseif ( get_option( 'dentall_site_announcements' ) !== $messages ) {
		wp_die( esc_html__( '全站公告保存失败，请重试。', 'dentall-core' ), '', array( 'response' => 500, 'back_link' => true ) );
	}

	wp_safe_redirect( admin_url( 'edit.php?post_type=page&page=dentall-site-announcements&saved=1' ) );
	exit;
}
add_action( 'admin_post_dentall_save_site_announcements', 'dentall_core_save_site_announcements' );
