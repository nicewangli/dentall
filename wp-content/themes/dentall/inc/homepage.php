<?php

defined( 'ABSPATH' ) || exit;

/**
 * 配置 DentAll 首页已验收的区块与专用菜单位置。
 *
 * 后续首页区块仍按各自职责逐步挂载，避免父主题的示例商品区块在尚未验收时
 * 自动出现在首页。
 *
 * @return void
 */
function dentall_configure_homepage() {
	register_nav_menu( 'homepage_categories', __( 'Homepage categories', 'dentall' ) );
	register_nav_menu( 'homepage_solutions', __( 'Homepage solutions', 'dentall' ) );

	remove_action( 'homepage', 'storefront_homepage_content', 10 );
	remove_action( 'homepage', 'storefront_product_categories', 20 );
	remove_action( 'homepage', 'storefront_recent_products', 30 );
	remove_action( 'homepage', 'storefront_featured_products', 40 );
	remove_action( 'homepage', 'storefront_popular_products', 50 );
	remove_action( 'homepage', 'storefront_on_sale_products', 60 );
	remove_action( 'homepage', 'storefront_best_selling_products', 70 );
	remove_action( 'homepage', 'storefront_woocommerce_brands_homepage_section', 80 );

	add_action( 'homepage', 'dentall_homepage_hero', 10 );
	add_action( 'homepage', 'dentall_homepage_categories', 20 );
	add_action( 'homepage', 'dentall_homepage_solutions', 30 );
	add_action( 'homepage', 'dentall_homepage_best_sellers', 40 );
	add_action( 'homepage', 'dentall_homepage_trust_metrics', 50 );
}
add_action( 'after_setup_theme', 'dentall_configure_homepage', 100 );

/**
 * 仅在 Storefront Homepage 模板中加载首页样式。
 *
 * @return void
 */
function dentall_enqueue_homepage_assets() {
	if ( ! is_page_template( 'template-homepage.php' ) ) {
		return;
	}

	$theme = wp_get_theme( get_stylesheet() );

	// 父主题脚本只服务其已移除的背景图 Hero，继续加载会增加无效监听与行内尺寸计算。
	wp_dequeue_script( 'storefront-homepage' );

	wp_enqueue_style(
		'dentall-homepage',
		get_stylesheet_directory_uri() . '/assets/css/homepage.css',
		array( 'dentall-site-shell' ),
		$theme->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'dentall_enqueue_homepage_assets', 50 );

/**
 * 按专用菜单顺序返回首页精选的有效商品分类。
 *
 * 菜单只负责选择和排序；名称、图片、链接及是否为空仍以真实product_cat为准。
 * 自定义链接、子分类、空分类、重复项和失效链接不会进入首页输出。
 *
 * @return WP_Term[]
 */
function dentall_get_homepage_categories() {
	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['homepage_categories'] ) ? absint( $locations['homepage_categories'] ) : 0;

	if ( ! $menu_id ) {
		return array();
	}

	$menu_items = wp_get_nav_menu_items( $menu_id );

	if ( ! is_array( $menu_items ) || ! $menu_items ) {
		return array();
	}

	$category_ids = array();
	$seen_ids     = array();

	foreach ( $menu_items as $menu_item ) {
		if ( 'taxonomy' !== $menu_item->type || 'product_cat' !== $menu_item->object ) {
			continue;
		}

		$category_id = absint( $menu_item->object_id );

		if ( ! $category_id || isset( $seen_ids[ $category_id ] ) ) {
			continue;
		}

		$seen_ids[ $category_id ] = true;
		$category_ids[]            = $category_id;
	}

	if ( ! $category_ids ) {
		return array();
	}

	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'include'    => $category_ids,
			'parent'     => 0,
			'hide_empty' => true,
			'pad_counts' => true,
		)
	);

	if ( is_wp_error( $categories ) || ! $categories ) {
		return array();
	}

	// WooCommerce会在WordPress完成hide_empty后按前台可见性重算count，需再次剔除访客空分类。
	$categories = wp_list_filter( $categories, array( 'count' => 0 ), 'NOT' );

	if ( ! $categories ) {
		return array();
	}

	$categories_by_id = array();

	foreach ( $categories as $category ) {
		$categories_by_id[ (int) $category->term_id ] = $category;
	}

	$ordered_categories = array();

	foreach ( $category_ids as $category_id ) {
		if ( ! isset( $categories_by_id[ $category_id ] ) ) {
			continue;
		}

		$category = $categories_by_id[ $category_id ];
		$link     = get_term_link( $category );

		if ( is_wp_error( $link ) || ! is_string( $link ) || '' === $link ) {
			continue;
		}

		$ordered_categories[] = $category;
	}

	return $ordered_categories;
}

/**
 * 在Hero之后输出首页精选商品分类区。
 *
 * CategoryCard继续使用WooCommerce原生模板；区域没有有效分类时保持空输出，
 * 不制造无内容标题、占位卡或异步Loading状态。
 *
 * @return void
 */
function dentall_homepage_categories() {
	if (
		! function_exists( 'woocommerce_product_loop_start' )
		|| ! function_exists( 'woocommerce_product_loop_end' )
		|| ! function_exists( 'wc_get_template' )
	) {
		return;
	}

	$categories = dentall_get_homepage_categories();

	if ( ! $categories ) {
		return;
	}

	// WooCommerce 原生分类模板会修改全局循环；精确还原，避免污染同页后续商品区块。
	$had_woocommerce_loop      = array_key_exists( 'woocommerce_loop', $GLOBALS );
	$previous_woocommerce_loop = $had_woocommerce_loop ? $GLOBALS['woocommerce_loop'] : null;

	wc_set_loop_prop( 'columns', min( 9, count( $categories ) ) );

	try {
		?>
		<section class="dentall-home-categories" aria-labelledby="dentall-home-categories-title">
			<div class="dentall-home-categories__inner col-full">
				<h2 id="dentall-home-categories-title" class="screen-reader-text"><?php esc_html_e( 'Shop by category', 'dentall' ); ?></h2>
				<?php
				woocommerce_product_loop_start();

				foreach ( $categories as $category ) {
					wc_get_template(
						'content-product_cat.php',
						array(
							'category' => $category,
						)
					);
				}

				woocommerce_product_loop_end();
				?>
			</div>
		</section>
		<?php
	} finally {
		if ( $had_woocommerce_loop ) {
			$GLOBALS['woocommerce_loop'] = $previous_woocommerce_loop;
		} else {
			unset( $GLOBALS['woocommerce_loop'] );
		}
	}
}

/**
 * 按专用菜单顺序返回首页最多四个有效方案Page。
 *
 * 菜单只负责选择和排序；标题、摘要、特色图、固定链接及发布状态仍以Page为准。
 * 草稿、密码保护、重复项、空标题和失效链接不会占用四个展示名额。
 *
 * @return WP_Post[]
 */
function dentall_get_homepage_solutions() {
	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['homepage_solutions'] ) ? absint( $locations['homepage_solutions'] ) : 0;

	if ( ! $menu_id ) {
		return array();
	}

	$menu_items = wp_get_nav_menu_items( $menu_id );

	if ( ! is_array( $menu_items ) || ! $menu_items ) {
		return array();
	}

	$page_ids = array();
	$seen_ids = array();

	foreach ( $menu_items as $menu_item ) {
		if ( 'post_type' !== $menu_item->type || 'page' !== $menu_item->object ) {
			continue;
		}

		$page_id = absint( $menu_item->object_id );

		if ( ! $page_id || isset( $seen_ids[ $page_id ] ) ) {
			continue;
		}

		$seen_ids[ $page_id ] = true;
		$page_ids[]            = $page_id;
	}

	if ( ! $page_ids ) {
		return array();
	}

	$pages = get_posts(
		array(
			'post_type'        => 'page',
			'post_status'      => 'publish',
			'post__in'         => $page_ids,
			'orderby'          => 'post__in',
			'posts_per_page'   => count( $page_ids ),
			'has_password'     => false,
			'suppress_filters' => false,
		)
	);

	$solutions = array();

	foreach ( $pages as $page ) {
		$title = trim( (string) get_the_title( $page ) );
		$url   = get_permalink( $page );

		if ( '' === $title || ! is_string( $url ) || '' === $url ) {
			continue;
		}

		$solutions[] = $page;

		if ( 4 === count( $solutions ) ) {
			break;
		}
	}

	return $solutions;
}

/**
 * 在精选分类之后输出首页方案区。
 *
 * 0个有效Page时整个区域保持空输出；未确认Solutions总览URL前不显示View all。
 *
 * @return void
 */
function dentall_homepage_solutions() {
	$solutions = dentall_get_homepage_solutions();

	if ( ! $solutions ) {
		return;
	}
	?>
	<section class="dentall-home-solutions dentall-section" aria-labelledby="dentall-home-solutions-title">
		<div class="dentall-home-solutions__inner col-full">
			<h2 id="dentall-home-solutions-title" class="dentall-home-solutions__title"><?php esc_html_e( 'Shop by Solution', 'dentall' ); ?></h2>
			<ul class="dentall-home-solutions__grid dentall-grid">
				<?php foreach ( $solutions as $index => $solution ) : ?>
					<?php
					$solution_id = (int) $solution->ID;
					$title       = get_the_title( $solution );
					$url         = get_permalink( $solution );
					$summary     = trim(
						wp_strip_all_tags(
							strip_shortcodes( (string) get_post_field( 'post_excerpt', $solution_id, 'raw' ) ),
							true
						)
					);
					$image_html  = '';
					$image_id    = get_post_thumbnail_id( $solution_id );
					$classes     = array( 'dentall-solution-card' );

					if ( 0 === $index ) {
						$classes[] = 'dentall-solution-card--featured';
					}

					if ( $image_id ) {
						$image_html = wp_get_attachment_image(
							$image_id,
							'medium_large',
							false,
							array(
								'alt'      => '',
								'loading'  => 'lazy',
								'decoding' => 'async',
								'sizes'    => '(min-width: 75rem) 8rem, (min-width: 64rem) 20vw, 40vw',
							)
						);
					}

					if ( ! $image_html ) {
						$classes[] = 'dentall-solution-card--text-only';
					}
					?>
					<li class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
						<a class="dentall-solution-card__link" href="<?php echo esc_url( $url ); ?>">
							<div class="dentall-solution-card__body">
								<h3 class="dentall-solution-card__title"><?php echo esc_html( $title ); ?></h3>
								<?php if ( '' !== $summary ) : ?>
									<span class="dentall-solution-card__summary"><?php echo esc_html( $summary ); ?></span>
								<?php endif; ?>
								<span class="dentall-solution-card__cta">
									<?php esc_html_e( 'Learn more', 'dentall' ); ?>
									<span aria-hidden="true">→</span>
								</span>
							</div>
							<?php if ( $image_html ) : ?>
								<span class="dentall-solution-card__media" aria-hidden="true">
									<?php
									// wp_get_attachment_image() 已按WordPress图片API生成并转义完整标记。
									echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								</span>
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
}

/**
 * 返回首页累计销量最高的五个可见商品。
 *
 * 排序复用WooCommerce的销量查询，不接受当前URL中的排序参数。查询结果不做长期缓存，
 * 避免订单更新销量后仍命中只按商品文章失效的旧查询结果。
 *
 * @return WC_Product[]
 */
function dentall_get_homepage_best_sellers() {
	if (
		! function_exists( 'WC' )
		|| ! function_exists( 'wc_get_product' )
		|| ! function_exists( 'wc_get_product_visibility_term_ids' )
		|| ! class_exists( 'WC_Query' )
		|| ! WC()->query instanceof WC_Query
	) {
		return array();
	}

	$visibility_term_ids = wc_get_product_visibility_term_ids();
	$excluded_term_ids   = array();

	if ( ! empty( $visibility_term_ids['exclude-from-catalog'] ) ) {
		$excluded_term_ids[] = (int) $visibility_term_ids['exclude-from-catalog'];
	}

	if (
		'yes' === get_option( 'woocommerce_hide_out_of_stock_items', 'no' )
		&& ! empty( $visibility_term_ids['outofstock'] )
	) {
		$excluded_term_ids[] = (int) $visibility_term_ids['outofstock'];
	}

	$query_args = array(
		'post_type'              => 'product',
		'post_status'            => 'publish',
		'has_password'           => false,
		'posts_per_page'         => 5,
		'fields'                 => 'ids',
		'no_found_rows'          => true,
		'ignore_sticky_posts'    => true,
		'cache_results'          => false,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	);

	if ( $excluded_term_ids ) {
		$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $excluded_term_ids,
				'operator' => 'NOT IN',
			),
		);
	}

	// popularity排序通过全局Filter生效；调用前已存在时必须保留，避免破坏同请求的其他商品查询。
	$catalog_query         = WC()->query;
	$popularity_callback   = array( $catalog_query, 'order_by_popularity_post_clauses' );
	$had_popularity_filter = 10 === has_filter( 'posts_clauses', $popularity_callback );
	$ordering_args         = $catalog_query->get_catalog_ordering_args( 'popularity', 'DESC' );
	$query_args['orderby'] = $ordering_args['orderby'];
	$query_args['order']   = $ordering_args['order'];

	if ( ! empty( $ordering_args['meta_key'] ) ) {
		$query_args['meta_key'] = $ordering_args['meta_key']; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
	}

	$product_ids = array();

	try {
		$product_query = new WP_Query( $query_args );
		$product_ids   = $product_query->posts;
	} finally {
		if ( ! $had_popularity_filter ) {
			remove_filter( 'posts_clauses', $popularity_callback, 10 );
		}
	}

	$products = array();

	foreach ( $product_ids as $product_id ) {
		$product = wc_get_product( $product_id );

		if (
			! $product instanceof WC_Product
			|| $product->get_total_sales() < 1
			|| ! $product->is_visible()
		) {
			continue;
		}

		$products[] = $product;
	}

	return $products;
}

/**
 * 在方案区之后输出累计销量商品。
 *
 * 没有真实销量时整段保持空输出；商品卡继续由WooCommerce原生循环模板负责。
 *
 * @return void
 */
function dentall_homepage_best_sellers() {
	if (
		! function_exists( 'woocommerce_product_loop_start' )
		|| ! function_exists( 'woocommerce_product_loop_end' )
		|| ! function_exists( 'wc_get_template_part' )
	) {
		return;
	}

	$products = dentall_get_homepage_best_sellers();

	if ( ! $products ) {
		return;
	}

	$shop_url = '';
	$shop_id  = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;

	if ( $shop_id > 0 && 'publish' === get_post_status( $shop_id ) ) {
		$shop_url = get_permalink( $shop_id );
	}

	$had_woocommerce_loop      = array_key_exists( 'woocommerce_loop', $GLOBALS );
	$previous_woocommerce_loop = $had_woocommerce_loop ? $GLOBALS['woocommerce_loop'] : null;
	$had_post                  = array_key_exists( 'post', $GLOBALS );
	$previous_post             = $had_post ? $GLOBALS['post'] : null;
	$had_product               = array_key_exists( 'product', $GLOBALS );
	$previous_product          = $had_product ? $GLOBALS['product'] : null;

	wc_set_loop_prop( 'columns', count( $products ) );
	wc_set_loop_prop( 'name', 'dentall-home-best-sellers' );

	try {
		?>
		<section class="dentall-home-best-sellers dentall-section" aria-labelledby="dentall-home-best-sellers-title">
			<div class="dentall-home-best-sellers__inner col-full">
				<header class="dentall-home-best-sellers__header">
					<h2 id="dentall-home-best-sellers-title" class="dentall-home-best-sellers__title"><?php esc_html_e( 'Best Sellers', 'dentall' ); ?></h2>
					<?php if ( is_string( $shop_url ) && '' !== $shop_url ) : ?>
						<a class="dentall-home-best-sellers__all" href="<?php echo esc_url( $shop_url ); ?>">
							<?php esc_html_e( 'View all products', 'dentall' ); ?>
							<span aria-hidden="true">→</span>
						</a>
					<?php endif; ?>
				</header>
				<?php
				woocommerce_product_loop_start();

				foreach ( $products as $product ) {
					$post_object = get_post( $product->get_id() );

					if ( ! $post_object instanceof WP_Post ) {
						continue;
					}

					$GLOBALS['post']    = $post_object;
					$GLOBALS['product'] = $product;
					setup_postdata( $post_object );
					wc_get_template_part( 'content', 'product' );
				}

				woocommerce_product_loop_end();
				?>
			</div>
		</section>
		<?php
	} finally {
		if ( $had_woocommerce_loop ) {
			$GLOBALS['woocommerce_loop'] = $previous_woocommerce_loop;
		} else {
			unset( $GLOBALS['woocommerce_loop'] );
		}

		if ( $had_post ) {
			$GLOBALS['post'] = $previous_post;

			if ( $previous_post instanceof WP_Post ) {
				setup_postdata( $previous_post );
			}
		} else {
			unset( $GLOBALS['post'] );
		}

		if ( $had_product ) {
			$GLOBALS['product'] = $previous_product;
		} else {
			unset( $GLOBALS['product'] );
		}
	}
}

/**
 * 返回设计稿中的Local环境信任指标预览数据。
 *
 * 这些数值尚未经过业务证明，只用于还原设计稿；Staging与Production必须保持空输出。
 *
 * @return array[]
 */
function dentall_get_homepage_trust_metrics() {
	if ( 'local' !== wp_get_environment_type() ) {
		return array();
	}

	return array(
		array(
			'icon'        => 'professionals',
			'value'       => __( '10,000+', 'dentall' ),
			'label'       => __( 'Dental Professionals', 'dentall' ),
			'description' => __( 'Trust DentAll', 'dentall' ),
		),
		array(
			'icon'        => 'globe',
			'value'       => __( '100+', 'dentall' ),
			'label'       => __( 'Countries Served', 'dentall' ),
			'description' => __( 'Worldwide', 'dentall' ),
		),
		array(
			'icon'        => 'box',
			'value'       => __( '5,000+', 'dentall' ),
			'label'       => __( 'Quality Products', 'dentall' ),
			'description' => __( 'In Stock', 'dentall' ),
		),
		array(
			'icon'        => 'smile',
			'value'       => __( '99.5%', 'dentall' ),
			'label'       => __( 'Customer Satisfaction', 'dentall' ),
			'description' => __( 'Rate', 'dentall' ),
		),
		array(
			'icon'        => 'lock',
			'value'       => __( 'Secure Payments', 'dentall' ),
			'label'       => '',
			'description' => __( 'Multiple safe payment options', 'dentall' ),
		),
	);
}

/**
 * 输出设计稿信任指标的Local预览。
 *
 * @return void
 */
function dentall_homepage_trust_metrics() {
	$metrics = dentall_get_homepage_trust_metrics();

	if ( ! $metrics ) {
		return;
	}

	$sprite_url = get_stylesheet_directory_uri() . '/assets/images/trust-icons.svg';
	?>
	<section class="dentall-home-trust dentall-section" aria-labelledby="dentall-home-trust-title">
		<div class="dentall-home-trust__inner col-full">
			<h2 id="dentall-home-trust-title" class="screen-reader-text"><?php esc_html_e( 'Why professionals trust DentAll', 'dentall' ); ?></h2>
			<ul
				class="dentall-home-trust__list"
				tabindex="0"
				aria-label="<?php esc_attr_e( 'Trust metrics', 'dentall' ); ?>"
			>
				<?php foreach ( $metrics as $metric ) : ?>
					<li class="dentall-home-trust__item">
						<span class="dentall-home-trust__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" focusable="false">
								<use href="<?php echo esc_url( $sprite_url . '#icon-' . $metric['icon'] ); ?>"></use>
							</svg>
						</span>
						<span class="dentall-home-trust__copy">
							<strong class="dentall-home-trust__value"><?php echo esc_html( $metric['value'] ); ?></strong>
							<?php if ( '' !== $metric['label'] ) : ?>
								<span class="dentall-home-trust__label"><?php echo esc_html( $metric['label'] ); ?></span>
							<?php endif; ?>
							<span class="dentall-home-trust__description"><?php echo esc_html( $metric['description'] ); ?></span>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
}

/**
 * 输出由核心区块内容与页面特色图组成的首页 Hero。
 *
 * 主循环必须由此函数推进；Storefront 原回调被移除后，不再有其他函数调用
 * the_post()。特色图使用 WordPress 图片 API，以保留尺寸、srcset、sizes 与替代文本。
 *
 * @return void
 */
function dentall_homepage_hero() {
	while ( have_posts() ) {
		the_post();

		$image_id        = get_post_thumbnail_id();
		$image_html      = '';
		$has_content     = '' !== trim( (string) get_post_field( 'post_content', get_the_ID(), 'raw' ) );
		$section_classes = array( 'dentall-home-hero' );

		if ( $image_id ) {
			$image_html = wp_get_attachment_image(
				$image_id,
				'full',
				false,
				array(
					'class'         => 'dentall-home-hero__image',
					'loading'       => 'eager',
					'decoding'      => 'async',
					'fetchpriority' => 'high',
					'sizes'         => '(min-width: 1320px) 768px, (min-width: 1200px) calc(63vw - 40px), (min-width: 768px) calc(56vw - 36px), calc(56vw - 22px)',
				)
			);
		}

		if ( ! $has_content && ! $image_html ) {
			continue;
		}

		if ( ! $has_content ) {
			$section_classes[] = 'dentall-home-hero--without-content';
		}

		if ( ! $image_html ) {
			$section_classes[] = 'dentall-home-hero--without-media';
		}
		?>
		<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
			<div class="dentall-home-hero__inner col-full">
				<?php if ( $has_content ) : ?>
				<div class="dentall-home-hero__content entry-content">
					<?php the_content(); ?>
				</div>
				<?php endif; ?>

				<?php if ( $image_html ) : ?>
				<figure class="dentall-home-hero__media">
					<?php
					// wp_get_attachment_image() 已按 WordPress 图片 API 生成并转义完整标记。
					echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</figure>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
