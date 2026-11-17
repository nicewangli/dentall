<?php
/**
 * Template Name: 品牌目录
 *
 * Page负责可编辑标题与介绍；目录只读取WooCommerce原生品牌，不维护第二份品牌数据。
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div id="primary" class="content-area dentall-brand-directory">
	<main id="main" class="site-main">
		<?php while ( have_posts() ) : ?>
			<?php
			the_post();
			do_action( 'storefront_page_before' );
			get_template_part( 'content', 'page' );
			if ( post_password_required() ) {
				continue;
			}

			$brands = taxonomy_exists( 'product_brand' )
				? get_terms( array( 'taxonomy' => 'product_brand', 'hide_empty' => true, 'orderby' => 'name' ) )
				: array();
			?>
			<?php if ( is_wp_error( $brands ) ) : ?>
				<p><?php esc_html_e( 'Brands are temporarily unavailable. Please try again later.', 'dentall' ); ?></p>
			<?php elseif ( empty( $brands ) ) : ?>
				<p><?php esc_html_e( 'No brands are available yet.', 'dentall' ); ?></p>
			<?php else : ?>
				<?php
				// 一次预热Logo附件与元数据，避免有图品牌逐项触发附件查询。
				$logo_ids = array();
				foreach ( $brands as $brand ) {
					$logo_ids[] = absint( get_term_meta( $brand->term_id, 'thumbnail_id', true ) );
				}
				$logo_ids = array_unique( array_filter( $logo_ids ) );
				if ( $logo_ids ) {
					get_posts(
						array(
							'post_type'              => 'attachment',
							'post_status'            => 'inherit',
							'post__in'               => $logo_ids,
							'numberposts'            => count( $logo_ids ),
							'update_post_term_cache' => false,
						)
					);
				}
				?>
				<ul class="dentall-brand-directory__list" aria-label="<?php esc_attr_e( 'Product brands', 'dentall' ); ?>">
					<?php foreach ( $brands as $brand ) : ?>
						<?php
						$url = get_term_link( $brand );
						if ( is_wp_error( $url ) ) {
							continue;
						}
						?>
						<li>
							<a href="<?php echo esc_url( $url ); ?>">
								<?php
								// 无Logo时API返回空串，始终保留可见品牌名，不伪造品牌素材。
								echo wp_get_attachment_image(
									absint( get_term_meta( $brand->term_id, 'thumbnail_id', true ) ),
									'medium',
									false,
									array( 'alt' => '', 'loading' => 'lazy', 'decoding' => 'async' )
								);
								?>
								<span><?php echo esc_html( $brand->name ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
				<p><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Browse all products', 'dentall' ); ?></a></p>
			<?php endif; ?>
		<?php endwhile; ?>
	</main>
</div>
<?php get_footer(); ?>
