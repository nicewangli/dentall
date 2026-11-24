<?php
/** 响应式联系入口：共享页脚菜单数据，不建立第二份联系方式。 */

defined( 'ABSPATH' ) || exit;

/** 同一请求仅查询一次；交易、账户和完整联系表单页不输出或加载资源。 */
function dentall_get_floating_contact_links() {
	static $links = null;
	if ( null !== $links ) {
		return $links;
	}
	$links = array();
	if (
		is_admin() || is_page( 'contact-us' )
		|| ( function_exists( 'is_login' ) && is_login() )
		|| ( function_exists( 'is_cart' ) && is_cart() ) // 包含既有shipping-request展示模式。
		|| ( function_exists( 'is_checkout' ) && is_checkout() )
		|| ( function_exists( 'is_account_page' ) && is_account_page() )
	) {
		return $links;
	}
	$locations = get_nav_menu_locations();
	$items     = empty( $locations['footer_contact'] ) ? array() : wp_get_nav_menu_items( $locations['footer_contact'] );
	foreach ( is_array( $items ) ? $items : array() as $item ) {
		$title = trim( wp_strip_all_tags( $item->title ) );
		if ( (int) $item->menu_item_parent || '' === $title ) {
			continue;
		}
		$url = wp_parse_url( $item->url );
		if ( ! is_array( $url ) || isset( $url['user'] ) || isset( $url['pass'] ) || isset( $url['port'] ) ) {
			continue;
		}
		if ( 'mailto' === strtolower( $url['scheme'] ?? '' ) && empty( $url['host'] ) && is_email( rawurldecode( $url['path'] ?? '' ) ) ) {
			$icon  = 'mail';
			$label = __( 'Email', 'dentall' );
		} elseif ( 'https' === strtolower( $url['scheme'] ?? '' ) && 'wa.me' === strtolower( $url['host'] ?? '' ) && preg_match( '~^/[1-9][0-9]{6,14}/?$~D', $url['path'] ?? '' ) ) {
			$icon  = 'whatsapp';
			$label = __( 'WhatsApp', 'dentall' );
		} else {
			continue;
		}
		if ( ! isset( $links[ $icon ] ) ) {
			$links[ $icon ] = array( 'url' => $item->url, 'title' => $title, 'label' => $label, 'new_tab' => '_blank' === $item->target );
		}
	}
	return $links;
}

/** 独立展示生命周期；只有有效联系数据且页面允许时才加载。 */
function dentall_enqueue_floating_contact() {
	if ( ! dentall_get_floating_contact_links() ) {
		return;
	}
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'dentall-floating-contact', get_stylesheet_directory_uri() . '/assets/css/floating-contact.css', array( 'dentall-site-shell' ), $version );
	wp_enqueue_script( 'dentall-floating-contact', get_stylesheet_directory_uri() . '/assets/js/floating-contact.js', array(), $version, array( 'in_footer' => true, 'strategy' => 'defer' ) );
}
add_action( 'wp_enqueue_scripts', 'dentall_enqueue_floating_contact', 60 );

/** 初始隐藏至脚本就绪；无JS时继续使用原页脚，避免新增失效按钮。 */
function dentall_render_floating_contact() {
	$links = dentall_get_floating_contact_links();
	if ( ! $links ) {
		return;
	}
	$sprite = add_query_arg( 'ver', wp_get_theme()->get( 'Version' ), get_stylesheet_directory_uri() . '/assets/images/footer-icons.svg' );
	?>
	<nav class="dentall-floating-contact" aria-label="<?php esc_attr_e( 'Contact options', 'dentall' ); ?>" hidden>
		<button class="dentall-floating-contact__toggle" type="button" aria-expanded="false" aria-controls="dentall-floating-contact-links"><?php esc_html_e( 'Contact', 'dentall' ); ?></button>
		<ul id="dentall-floating-contact-links" class="dentall-floating-contact__links" hidden>
			<?php foreach ( $links as $icon => $link ) : ?>
			<li>
				<a href="<?php echo esc_url( $link['url'] ); ?>" aria-label="<?php echo esc_attr( $link['label'] . ': ' . $link['title'] ); ?>"<?php if ( $link['new_tab'] ) : ?> target="_blank" rel="noopener noreferrer"<?php endif; ?>>
					<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><use href="<?php echo esc_url( $sprite . '#icon-' . $icon ); ?>"></use></svg>
					<span class="dentall-floating-contact__label" aria-hidden="true"><strong><?php echo esc_html( $link['label'] ); ?></strong><span><?php echo esc_html( $link['title'] ); ?></span></span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}
add_action( 'wp_footer', 'dentall_render_floating_contact', 10 );
