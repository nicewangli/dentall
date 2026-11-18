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
	register_nav_menu( 'footer_contact', __( 'Footer contact links', 'dentall' ) );
	register_nav_menu( 'footer_social', __( 'Footer social links', 'dentall' ) );

	remove_action( 'storefront_footer', 'storefront_footer_widgets', 10 );
	add_action( 'storefront_before_footer', 'dentall_render_newsletter', 10 );
	add_action( 'storefront_footer', 'dentall_render_site_footer', 10 );
	add_filter( 'storefront_credit_link', '__return_false' );
}
add_action( 'after_setup_theme', 'dentall_setup_site_footer', 30 );

/**
 * 页脚空URL分组只作为标签，避免把栏目标题伪装成跳到页顶的链接。
 */
function dentall_footer_group_label( $output, $item, $depth, $args ) {
	if ( 'footer' === ( $args->theme_location ?? '' ) && 0 === $depth && '#' === $item->url ) {
		return '<span class="dentall-footer-menu__label">' . esc_html( $item->title ) . '</span>';
	}
	return $output;
}
add_filter( 'walker_nav_menu_start_el', 'dentall_footer_group_label', 10, 4 );

/**
 * 输出Newsletter原生订阅表单。
 *
 * 字段文案、隐私链接与双重确认由Newsletter配置管理；主题仅负责布局。
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

			<?php if ( shortcode_exists( 'newsletter_form' ) && shortcode_exists( 'newsletter_field' ) ) : ?>
				<?php
				// 使用插件受控表单保留原生提交、必填校验与订阅处理，不自行读写订阅者。
				echo do_shortcode(
					sprintf(
						'[newsletter_form class="dentall-newsletter__form"][newsletter_field name="email"][newsletter_field name="privacy"]<button type="submit">%s</button>[/newsletter_form]',
						esc_html__( 'Subscribe', 'dentall' )
					)
				);
				?>
			<?php else : ?>
				<p class="dentall-newsletter__status"><?php esc_html_e( 'Newsletter subscriptions are temporarily unavailable.', 'dentall' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * 输出Footer品牌、原生菜单维护的联系方式、社媒链接与两级菜单。
 *
 * 菜单未绑定时不回退为全部Page；社媒只展示已绑定的真实账号，不输出占位链接。
 *
 * @return void
 */
function dentall_render_site_footer() {
	$site_name    = get_bloginfo( 'name' );
	$sprite_url   = get_stylesheet_directory_uri() . '/assets/images/footer-icons.svg';
	$social_icons = array(
		'facebook.com'  => 'facebook',
		'instagram.com' => 'instagram',
		'linkedin.com'  => 'linkedin',
		'youtube.com'   => 'youtube',
	);
	$locations    = get_nav_menu_locations();
	$social_links = empty( $locations['footer_social'] )
		? array()
		: wp_get_nav_menu_items( $locations['footer_social'] );
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
			<?php
			wp_nav_menu(
				array(
					'theme_location'       => 'footer_contact',
					'container'            => 'nav',
					'container_class'      => 'dentall-footer-contact-nav',
					'container_aria_label' => __( 'Contact information', 'dentall' ),
					'menu_class'           => 'dentall-footer-contact',
					'menu_id'              => 'dentall-footer-contact',
					'depth'                => 1,
					'fallback_cb'          => false,
				)
			);
			?>
			<?php if ( $social_links ) : ?>
			<ul class="dentall-footer-social" aria-label="<?php esc_attr_e( 'Company social media', 'dentall' ); ?>">
				<?php foreach ( $social_links as $link ) : ?>
					<?php
					$host = preg_replace( '/^www\./', '', strtolower( (string) wp_parse_url( $link->url, PHP_URL_HOST ) ) );
					if ( ! isset( $social_icons[ $host ] ) || 'https' !== wp_parse_url( $link->url, PHP_URL_SCHEME ) ) {
						continue;
					}
					$icon = $social_icons[ $host ];
					?>
					<li>
						<a class="dentall-footer-social__icon" href="<?php echo esc_url( $link->url ); ?>" target="_blank" rel="noopener">
							<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
								<use href="<?php echo esc_url( $sprite_url . '#icon-' . $icon ); ?>"></use>
							</svg>
							<span class="screen-reader-text"><?php echo esc_html( $link->title ); ?> <?php esc_html_e( '(opens in a new tab)', 'dentall' ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
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
