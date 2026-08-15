<?php

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="dentall-skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'dentall' ); ?></a>
<header class="dentall-header">
	<div class="dentall-container dentall-header__inner">
		<a class="dentall-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
		<nav class="dentall-nav" aria-label="<?php esc_attr_e( 'Primary menu', 'dentall' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
	</div>
</header>
