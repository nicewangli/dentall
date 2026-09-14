<?php
/**
 * CR-013首页与全站展示只读审计。
 *
 * 运行：php -d mysqli.default_port=<Local端口> C:/wp-cli/wp-cli.phar eval-file
 * project-docs/tests/staging-home-presentation-audit.php --path=app/public
 */

$site_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

if ( 'local' !== wp_get_environment_type() || 'dentall.local' !== $site_host ) {
	WP_CLI::error( '安全中止：本审计只允许在WP_ENVIRONMENT_TYPE=local且主机为dentall.local时运行。' );
}

$checks       = array();
$front_page_id = (int) get_option( 'page_on_front' );
$meta_keys    = get_registered_meta_keys( 'post', 'page' );
$defaults     = dentall_core_get_home_trust_defaults();
$sanitized    = dentall_core_sanitize_home_trust_metrics(
	array(
		array(
			'value'       => '<script>alert(1)</script>123',
			'label'       => str_repeat( 'L', 140 ),
			'description' => str_repeat( 'D', 180 ),
		),
	)
);

$checks['front_page_exists'] = $front_page_id > 0 && 'publish' === get_post_status( $front_page_id );
$checks['trust_defaults_have_fixed_five_icons'] = 5 === count( $defaults )
	&& array( 'professionals', 'globe', 'box', 'smile', 'lock' ) === wp_list_pluck( $defaults, 'icon' );
$checks['trust_sanitizes_markup_and_lengths'] = false === strpos( $sanitized[0]['value'], '<script' )
	&& 120 === strlen( $sanitized[0]['label'] )
	&& 160 === strlen( $sanitized[0]['description'] );
$checks['trust_meta_is_private_and_revisioned'] = isset(
	$meta_keys[ DENTALL_CORE_HOME_TRUST_META ],
	$meta_keys[ DENTALL_CORE_HOME_TRUST_ENABLED_META ]
)
	&& false === $meta_keys[ DENTALL_CORE_HOME_TRUST_META ]['show_in_rest']
	&& true === $meta_keys[ DENTALL_CORE_HOME_TRUST_META ]['revisions_enabled']
	&& false === $meta_keys[ DENTALL_CORE_HOME_TRUST_ENABLED_META ]['show_in_rest']
	&& true === $meta_keys[ DENTALL_CORE_HOME_TRUST_ENABLED_META ]['revisions_enabled'];

$administrator_role = get_role( 'administrator' );
$manager_role       = get_role( DENTALL_WEBSITE_MANAGER_ROLE );
$editor_role        = get_role( DENTALL_CONTENT_ROLE );

$checks['trust_role_boundary_is_narrow'] = $administrator_role instanceof WP_Role
	&& $manager_role instanceof WP_Role
	&& $editor_role instanceof WP_Role
	&& ! empty( $administrator_role->capabilities['manage_options'] )
	&& ! empty( $manager_role->capabilities[ DENTALL_WEBSITE_MANAGER_MARKER ] )
	&& ! empty( $manager_role->capabilities['edit_pages'] )
	&& empty( $manager_role->capabilities['edit_theme_options'] )
	&& empty( $editor_role->capabilities[ DENTALL_WEBSITE_MANAGER_MARKER ] );

$trust_metrics = dentall_get_homepage_trust_metrics();

ob_start();
dentall_homepage_trust_metrics();
$trust_html = ob_get_clean();

$checks['trust_renders_five_items'] = 5 === count( $trust_metrics )
	&& 5 === substr_count( $trust_html, 'dentall-home-trust__item' );

ob_start();
dentall_announcement_bar();
$announcement_html = ob_get_clean();

$checks['announcement_matches_manual_quote_flow'] = false !== strpos( $announcement_html, 'Shipping Quotes Based on Order Quantity' )
	&& false !== strpos( $announcement_html, 'Email Support for Shipping Confirmation' )
	&& false === stripos( $announcement_html, 'free shipping' )
	&& false === stripos( $announcement_html, 'TEST' );

ob_start();
dentall_render_newsletter();
$newsletter_html = ob_get_clean();

$checks['newsletter_is_visible_but_non_submitting'] = false !== strpos( $newsletter_html, 'Get the latest deals &amp; product updates' )
	&& false !== strpos( $newsletter_html, 'Join our newsletter.' )
	&& false !== strpos( $newsletter_html, 'Enter your email' )
	&& false !== strpos( $newsletter_html, '>Subscribe<' )
	&& false === stripos( $newsletter_html, '<form' )
	&& false === preg_match( '/\sname=(["\'])/i', $newsletter_html )
	&& 2 === substr_count( $newsletter_html, ' disabled' )
	&& false === stripos( $newsletter_html, 'TEST' );

ob_start();
dentall_render_site_footer();
$footer_html = ob_get_clean();
$social_start = strpos( $footer_html, '<ul class="dentall-footer-social"' );
$social_end   = false !== $social_start ? strpos( $footer_html, '</ul>', $social_start ) : false;
$social_html  = false !== $social_end ? substr( $footer_html, $social_start, $social_end - $social_start ) : '';

$checks['footer_has_logo_and_four_display_icons'] = false !== strpos( $footer_html, 'dentall-footer__logo-image' )
	&& 4 === substr_count( $social_html, 'dentall-footer-social__icon' )
	&& false === stripos( $social_html, '<a ' );
$checks['footer_omits_payment_badges'] = false === preg_match( '/\b(Visa|Mastercard|PayPal|Apple Pay|Google Pay)\b/i', $footer_html );
$checks['footer_uses_dynamic_copyright'] = sprintf(
	'© %1$s %2$s. All rights reserved.',
	wp_date( 'Y' ),
	get_bloginfo( 'name' )
) === dentall_footer_copyright_text( '' );

$checks['solution_ctas_match_design_order'] = array( 'Shop Now', 'Shop Now', 'Get Started', 'Explore Now' ) === array_map(
	'dentall_get_homepage_solution_cta',
	array( 0, 1, 2, 3 )
);

$theme = wp_get_theme( 'dentall' );
$checks['release_versions_are_current'] = '0.42.0' === $theme->get( 'Version' )
	&& '0.3.0' === get_file_data( DENTALL_CORE_PLUGIN_FILE, array( 'Version' => 'Version' ) )['Version'];

foreach ( $checks as $name => $passed ) {
	WP_CLI::line( sprintf( "%s\t%s", $passed ? 'PASS' : 'FAIL', $name ) );
}

if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( 'CR-013首页与全站展示审计未通过。' );
}

WP_CLI::success( 'CR-013首页与全站展示审计通过。' );
