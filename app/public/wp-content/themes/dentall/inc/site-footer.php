<?php

defined( 'ABSPATH' ) || exit;

/**
 * 注册Footer菜单并替换Storefront默认页脚输出。
 *
 * Footer使用一个两级菜单承载栏目与链接，避免把业务URL硬编码进主题；
 * Storefront默认四列Widget与当前数据合同不一致，因此在此职责边界内移除。
 *
 * @return void
 */
function dentall_setup_site_footer() {
	register_nav_menu( 'footer', __( 'Footer navigation', 'dentall' ) );

	remove_action( 'storefront_footer', 'storefront_footer_widgets', 10 );
	add_action( 'storefront_before_footer', 'dentall_render_newsletter', 10 );
	add_action( 'storefront_footer', 'dentall_render_site_footer', 10 );
	add_filter( 'storefront_credit_link', '__return_false' );
}
add_action( 'after_setup_theme', 'dentall_setup_site_footer', 30 );

/**
 * 输出Newsletter展示态。
 *
 * 真实订阅服务尚未接入，因此不输出form、name或提交端点，避免静默收集邮箱数据。
 *
 * @return void
 */
function dentall_render_newsletter() {
	$sprite_url = get_stylesheet_directory_uri() . '/assets/images/footer-icons.svg';
	?>
	<section class="dentall-newsletter" aria-labelledby="dentall-newsletter-title">
		<div class="col-full dentall-newsletter__inner">
			<div class="dentall-newsletter__content">
				<span class="dentall-newsletter__icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" focusable="false">
						<use href="<?php echo esc_url( $sprite_url . '#icon-mail' ); ?>"></use>
					</svg>
				</span>
				<div class="dentall-newsletter__copy">
					<h2 id="dentall-newsletter-title"><?php esc_html_e( 'Get the latest deals & product updates', 'dentall' ); ?></h2>
					<p><?php esc_html_e( 'Join our newsletter.', 'dentall' ); ?></p>
				</div>
			</div>

			<div class="dentall-newsletter__preview" aria-describedby="dentall-newsletter-status">
				<label class="screen-reader-text" for="dentall-newsletter-email"><?php esc_html_e( 'Email address', 'dentall' ); ?></label>
				<input id="dentall-newsletter-email" type="email" placeholder="<?php echo esc_attr__( 'Enter your email', 'dentall' ); ?>" autocomplete="email" disabled>
				<button type="button" disabled><?php esc_html_e( 'Subscribe', 'dentall' ); ?></button>
				<span id="dentall-newsletter-status" class="screen-reader-text"><?php esc_html_e( 'Newsletter subscription is coming soon.', 'dentall' ); ?></span>
			</div>
		</div>
	</section>
	<?php
}

/**
 * 输出Footer品牌、展示态社交图标与单一两级菜单。
 *
 * 菜单未绑定时不回退为全部Page；社交账号尚未确认，因此图标不输出链接。
 * 支付方式尚未验收，本函数不输出支付品牌徽标。
 *
 * @return void
 */
function dentall_render_site_footer() {
	$site_name    = get_bloginfo( 'name' );
	$sprite_url   = get_stylesheet_directory_uri() . '/assets/images/footer-icons.svg';
	$social_icons = array( 'facebook', 'instagram', 'linkedin', 'youtube' );
	/* translators: %s: Site name. */
	$home_label = sprintf( __( '%s home', 'dentall' ), $site_name );
	?>
	<div class="dentall-footer__main">
		<div class="dentall-footer__navigation">
			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<?php
				wp_nav_menu(
					array(
						'theme_location'       => 'footer',
						'container'            => 'nav',
						'container_class'      => 'dentall-footer-nav',
						'container_aria_label' => __( 'Footer navigation', 'dentall' ),
						'menu_class'           => 'dentall-footer-menu',
						'menu_id'              => 'dentall-footer-menu',
						'depth'                => 2,
						'fallback_cb'          => false,
					)
				);
				?>
			<?php endif; ?>
		</div>

		<div class="dentall-footer__brand">
			<a class="dentall-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $home_label ); ?>">
				<?php echo dentall_get_brand_logo_image( 'lazy', 'dentall-footer__logo-image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
			<ul class="dentall-footer-social" aria-hidden="true">
				<?php foreach ( $social_icons as $icon ) : ?>
					<li>
						<span class="dentall-footer-social__icon">
							<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
								<use href="<?php echo esc_url( $sprite_url . '#icon-' . $icon ); ?>"></use>
							</svg>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php
}

/**
 * 使用动态年份输出设计稿版权文案。
 *
 * @param string $copyright Storefront默认版权文案。
 * @return string
 */
function dentall_footer_copyright_text( $copyright ) {
	return sprintf(
		/* translators: 1: Current year. 2: Site name. */
		__( '© %1$s %2$s. All rights reserved.', 'dentall' ),
		wp_date( 'Y' ),
		get_bloginfo( 'name' )
	);
}
add_filter( 'storefront_copyright_text', 'dentall_footer_copyright_text' );
