<?php

defined( 'ABSPATH' ) || exit;

/**
 * 禁止未分配菜单时回退为全部已发布Page。
 *
 * Storefront默认会为Primary与Handheld位置调用WordPress页面菜单回退，可能把尚未批准进入导航的
 * 已发布Page直接公开。已经在后台分配的菜单不受影响，正式导航结构仍由后续页面与菜单工作维护。
 *
 * @param array $args 菜单渲染参数。
 * @return array
 */
function dentall_disable_page_menu_fallback( $args ) {
	$controlled_locations = array( 'primary', 'handheld' );

	if (
		isset( $args['theme_location'] )
		&& in_array( $args['theme_location'], $controlled_locations, true )
	) {
		$args['fallback_cb'] = false;
	}

	return $args;
}
add_filter( 'wp_nav_menu_args', 'dentall_disable_page_menu_fallback', 20 );

/**
 * 输出仅供Local骨架验证的公告栏。
 *
 * 正式文案尚未获得业务确认，因此其他环境不输出占位内容。
 *
 * @return void
 */
function dentall_announcement_bar() {
	if ( 'local' !== wp_get_environment_type() ) {
		return;
	}

	$currency_code   = function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : '';
	$currency_symbol = $currency_code && function_exists( 'get_woocommerce_currency_symbol' )
		? get_woocommerce_currency_symbol( $currency_code )
		: '';
	?>
	<aside class="dentall-announcement" aria-label="<?php esc_attr_e( 'Store announcement', 'dentall' ); ?>">
		<div class="col-full dentall-announcement__inner">
			<ul class="dentall-announcement__messages" aria-label="<?php esc_attr_e( 'Store notices', 'dentall' ); ?>">
				<li><?php esc_html_e( '[TEST] Free Shipping on Orders Over $199', 'dentall' ); ?></li>
				<li><?php esc_html_e( '[TEST] 5–10-Day Easy Returns', 'dentall' ); ?></li>
				<li><?php esc_html_e( '[TEST] Trusted by 10,000+ Dental Professionals', 'dentall' ); ?></li>
			</ul>
			<ul class="dentall-announcement__utilities" aria-label="<?php esc_attr_e( 'Store preferences and help', 'dentall' ); ?>">
				<?php if ( $currency_code ) : ?>
					<li class="dentall-announcement__utility--currency">
						<?php echo esc_html( trim( $currency_code . ' ' . $currency_symbol ) ); ?>
					</li>
				<?php endif; ?>
				<li class="dentall-announcement__utility--language"><?php esc_html_e( 'English', 'dentall' ); ?></li>
				<li class="dentall-announcement__utility--help"><?php esc_html_e( 'Help Center', 'dentall' ); ?></li>
			</ul>
		</div>
	</aside>
	<?php
}
add_action( 'storefront_before_header', 'dentall_announcement_bar', 10 );

/**
 * 在Local没有正式Custom Logo时输出占位Logo。
 *
 * 正式Logo仍由WordPress原生Custom Logo管理；一旦后台设置Logo，或请求不在Local，
 * 立即回到Storefront原生品牌输出，不让临时素材进入其他环境。
 *
 * @return void
 */
function dentall_site_branding() {
	if ( 'local' !== wp_get_environment_type() || has_custom_logo() ) {
		storefront_site_branding();
		return;
	}

	$logo_url = get_stylesheet_directory_uri() . '/assets/images/logo-placeholder-v2.png';
	$logo_alt = sprintf(
		/* translators: %s: site name. */
		__( '%s placeholder logo', 'dentall' ),
		get_bloginfo( 'name' )
	);
	?>
	<div class="site-branding">
		<?php if ( is_home() ) : ?>
			<h1 class="logo">
		<?php endif; ?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
			<img
				class="custom-logo dentall-placeholder-logo"
				src="<?php echo esc_url( $logo_url ); ?>"
				width="1024"
				height="240"
				alt="<?php echo esc_attr( $logo_alt ); ?>"
			>
		</a>
		<?php if ( is_home() ) : ?>
			</h1>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * 输出WooCommerce账户入口。
 *
 * 第一版保持游客与登录用户共用同一入口，不提前加入用户名、状态文案或下拉菜单。
 *
 * @return void
 */
function dentall_header_account_link() {
	if ( ! function_exists( 'wc_get_page_permalink' ) ) {
		return;
	}

	$account_url = wc_get_page_permalink( 'myaccount', '' );

	if ( empty( $account_url ) ) {
		return;
	}
	?>
	<div class="dentall-header-account">
		<a href="<?php echo esc_url( $account_url ); ?>">
			<span><?php esc_html_e( 'Account', 'dentall' ); ?></span>
		</a>
	</div>
	<?php
}

/**
 * 输出保留WooCommerce动态数量的购物车链接。
 *
 * @return void
 */
function dentall_cart_link() {
	if ( ! function_exists( 'storefront_woo_cart_available' ) || ! storefront_woo_cart_available() ) {
		return;
	}

	$item_count = WC()->cart->get_cart_contents_count();
	$count_text = sprintf(
		/* translators: %d: number of items in cart. */
		_n( '%d item in cart', '%d items in cart', $item_count, 'dentall' ),
		$item_count
	);
	$link_label = sprintf(
		/* translators: %s: localized cart item count. */
		__( 'View your shopping cart, %s', 'dentall' ),
		$count_text
	);
	?>
	<a
		class="cart-contents"
		href="<?php echo esc_url( wc_get_cart_url() ); ?>"
		aria-label="<?php echo esc_attr( $link_label ); ?>"
	>
		<span class="dentall-cart-label"><?php esc_html_e( 'Cart', 'dentall' ); ?></span>
		<span class="dentall-cart-count" aria-hidden="true"><?php echo esc_html( $item_count ); ?></span>
	</a>
	<?php
}

/**
 * 复用Storefront购物车容器和Mini Cart，只替换顶部链接的展示结构。
 *
 * @return void
 */
function dentall_header_cart() {
	if ( ! function_exists( 'storefront_is_woocommerce_activated' ) || ! storefront_is_woocommerce_activated() ) {
		return;
	}
	?>
	<ul id="site-header-cart" class="site-header-cart menu">
		<li class="<?php echo esc_attr( is_cart() ? 'current-menu-item' : '' ); ?>">
			<?php dentall_cart_link(); ?>
		</li>
		<li>
			<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
		</li>
	</ul>
	<?php
}

/**
 * 让WooCommerce AJAX fragments继续以同一个a.cart-contents替换顶部购物车链接。
 *
 * @param array $fragments 待刷新的HTML片段。
 * @return array
 */
function dentall_cart_link_fragment( $fragments ) {
	ob_start();
	dentall_cart_link();
	$fragments['a.cart-contents'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'dentall_cart_link_fragment', 20 );

/**
 * 为D33 Header Cart结构使用独立的浏览器fragment缓存键。
 *
 * WooCommerce会从sessionStorage恢复经典fragment。若继续沿用D31的缓存键，旧的
 * a.cart-contents可能在页面加载后覆盖新徽标，直至下一次fragment刷新。
 *
 * @param string $fragment_name WooCommerce默认fragment存储键。
 * @return string
 */
function dentall_cart_fragment_name( $fragment_name ) {
	return $fragment_name . '_dentall_header_v1';
}
add_filter( 'woocommerce_cart_fragment_name', 'dentall_cart_fragment_name', 20 );

/**
 * 配置Storefront全站Header与移动端动作入口。
 *
 * Primary导航提前进入父主题Header容器，让手机和PC复用同一菜单DOM；搜索、账户和购物车
 * 继续沿用原生输出与WooCommerce fragments。Header已提供这些入口后，移除重复且存在键盘
 * 可访问性问题的Storefront Handheld Footer。
 *
 * @return void
 */
function dentall_configure_storefront_shell() {
	/* D33只保留Primary菜单DOM，防止后台误绑Handheld后静默输出第二棵导航树。 */
	unregister_nav_menu( 'handheld' );

	if (
		function_exists( 'storefront_primary_navigation_wrapper' )
		&& function_exists( 'storefront_primary_navigation' )
		&& function_exists( 'storefront_primary_navigation_wrapper_close' )
	) {
		remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper', 42 );
		remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
		remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper_close', 68 );

		add_action( 'storefront_header', 'storefront_primary_navigation_wrapper', 10 );
		add_action( 'storefront_header', 'storefront_primary_navigation', 11 );
		add_action( 'storefront_header', 'storefront_primary_navigation_wrapper_close', 12 );
	}

	if ( function_exists( 'storefront_site_branding' ) ) {
		remove_action( 'storefront_header', 'storefront_site_branding', 20 );
		add_action( 'storefront_header', 'dentall_site_branding', 20 );
	}

	add_action( 'storefront_header', 'dentall_header_account_link', 40 );

	remove_action( 'storefront_header', 'storefront_header_cart', 60 );
	add_action( 'storefront_header', 'dentall_header_cart', 40 );
	remove_filter( 'woocommerce_add_to_cart_fragments', 'storefront_cart_link_fragment' );

	if ( function_exists( 'storefront_handheld_footer_bar' ) ) {
		remove_action( 'storefront_footer', 'storefront_handheld_footer_bar', 999 );
	}
}
add_action( 'after_setup_theme', 'dentall_configure_storefront_shell', 40 );

/**
 * 使用WooCommerce原生可见标签输出商品目录排序控件。
 *
 * @return void
 */
function dentall_catalog_ordering_with_label() {
	if ( ! function_exists( 'woocommerce_catalog_ordering' ) ) {
		return;
	}

	woocommerce_catalog_ordering(
		array(
			'useLabel' => true,
		)
	);
}

/**
 * 将Storefront上下两处商品目录排序替换为带可见标签的原生输出。
 *
 * 子主题functions.php早于父主题加载，因此等待after_setup_theme后再替换父主题Hook。
 *
 * @return void
 */
function dentall_enable_catalog_ordering_labels() {
	if ( ! function_exists( 'woocommerce_catalog_ordering' ) ) {
		return;
	}

	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );

	add_action( 'woocommerce_before_shop_loop', 'dentall_catalog_ordering_with_label', 10 );
	add_action( 'woocommerce_after_shop_loop', 'dentall_catalog_ordering_with_label', 10 );
}
add_action( 'after_setup_theme', 'dentall_enable_catalog_ordering_labels', 30 );
