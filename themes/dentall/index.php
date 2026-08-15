<?php

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main-content" class="dentall-main">
	<div class="dentall-container">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'dentall-card' ); ?>>
					<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p><?php esc_html_e( '暂无内容。', 'dentall' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
