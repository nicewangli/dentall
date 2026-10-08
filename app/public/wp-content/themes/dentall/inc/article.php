<?php

defined( 'ABSPATH' ) || exit;

/**
 * 单篇文章保留Storefront正文模板，只替换会公开后台账号的元信息。
 *
 * @return void
 */
function dentall_configure_single_article() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	remove_action( 'storefront_post_header_before', 'storefront_post_meta', 10 );
	add_action( 'storefront_post_header_after', 'dentall_article_byline', 10 );
}
add_action( 'wp', 'dentall_configure_single_article', 20 );

/**
 * 公开署名与后台post_author分离，日期保留WordPress原生文章事实。
 *
 * @return void
 */
function dentall_article_byline() {
	$byline = sprintf(
		/* translators: %s: public editorial team name. */
		__( 'By %s', 'dentall' ),
		__( 'DentAll Editorial Team', 'dentall' )
	);
	?>
	<p class="dentall-article-byline">
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
			<?php echo esc_html( get_the_date( _x( 'F j, Y', 'article date format', 'dentall' ) ) ); ?>
		</time>
		<span><?php echo esc_html( $byline ); ?></span>
		<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
			<a href="<?php echo esc_url( get_comments_link() ); ?>">
				<?php
				echo esc_html(
					get_comments_number_text(
						__( 'Leave a comment', 'dentall' ),
						__( '1 Comment', 'dentall' ),
						__( '% Comments', 'dentall' )
					)
				);
				?>
			</a>
		<?php endif; ?>
	</p>
	<?php
}
