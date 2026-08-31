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
	add_action( 'storefront_before_footer', 'dentall_render_newsletter_preview', 10 );
	add_action( 'storefront_footer', 'dentall_render_site_footer', 10 );
	add_filter( 'storefront_credit_link', '__return_false' );
}
add_action( 'after_setup_theme', 'dentall_setup_site_footer', 30 );

/**
 * 输出仅用于Local视觉验收、明确不可提交的Newsletter壳层。
 *
 * 未选择服务商和数据规则前不输出form、name或任何提交端点，避免产生邮箱数据。
 *
 * @return void
 */
function dentall_render_newsletter_preview() {
	if ( 'local' !== wp_get_environment_type() ) {
		return;
	}
	?>
	<section class="dentall-newsletter" aria-labelledby="dentall-newsletter-title">
		<div class="col-full dentall-newsletter__inner">
			<div class="dentall-newsletter__content">
				<p class="dentall-newsletter__eyebrow"><?php esc_html_e( '[TEST] Preview only', 'dentall' ); ?></p>
				<h2 id="dentall-newsletter-title"><?php esc_html_e( 'Join the DentAll newsletter', 'dentall' ); ?></h2>
				<p id="dentall-newsletter-status"><?php esc_html_e( 'Newsletter sign-up is not connected in this Local preview.', 'dentall' ); ?></p>
			</div>

			<div class="dentall-newsletter__preview" aria-describedby="dentall-newsletter-status">
				<label for="dentall-newsletter-email"><?php esc_html_e( 'Email address', 'dentall' ); ?></label>
				<input id="dentall-newsletter-email" type="email" placeholder="<?php echo esc_attr__( 'name@example.com', 'dentall' ); ?>" autocomplete="email" disabled>
				<button type="button" disabled><?php esc_html_e( 'Subscribe (disabled)', 'dentall' ); ?></button>
			</div>
		</div>
	</section>
	<?php
}

/**
 * 输出Footer品牌与单一两级菜单。
 *
 * 菜单未绑定时不回退为全部Page；Local显示TEST提示，非Local保持安静空状态。
 * 社交账号与支付方式没有正式事实，因此本函数不输出对应占位项。
 *
 * @return void
 */
function dentall_render_site_footer() {
	$site_name = get_bloginfo( 'name' );
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
			<?php elseif ( 'local' === wp_get_environment_type() ) : ?>
				<p class="dentall-footer__menu-status"><?php esc_html_e( '[TEST] Footer navigation is not assigned.', 'dentall' ); ?></p>
			<?php endif; ?>
		</div>

		<div class="dentall-footer__brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $home_label ); ?>">
				<?php echo esc_html( $site_name ); ?>
			</a>
		</div>
	</div>
	<?php
}
