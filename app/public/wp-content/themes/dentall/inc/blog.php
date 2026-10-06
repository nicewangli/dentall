<?php

defined( 'ABSPATH' ) || exit;

/**
 * 判断当前请求是否属于D86博客列表范围。
 *
 * 第一版只接管Posts Page与文章分类；标签、作者、日期和搜索继续保留各自既有边界。
 *
 * @return bool
 */
function dentall_is_blog_archive() {
	return is_home() || is_category();
}

/**
 * 只在博客列表与文章分类加载展示样式。
 *
 * @return void
 */
function dentall_enqueue_blog_assets() {
	if ( ! dentall_is_blog_archive() ) {
		return;
	}

	$theme = wp_get_theme( get_stylesheet() );

	wp_enqueue_style(
		'dentall-blog',
		get_stylesheet_directory_uri() . '/assets/css/blog.css',
		array( 'dentall-site-shell' ),
		$theme->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'dentall_enqueue_blog_assets', 50 );

/**
 * 为博客列表添加稳定的页面作用域class。
 *
 * @param string[] $classes WordPress生成的body class。
 * @return string[]
 */
function dentall_blog_body_classes( $classes ) {
	if ( dentall_is_blog_archive() ) {
		$classes[] = 'dentall-blog-archive';
	}

	return $classes;
}
add_filter( 'body_class', 'dentall_blog_body_classes' );

/**
 * 让Storefront原生循环只在博客范围改用精简卡片和项目分页。
 *
 * 主查询、模板层级和文章对象继续由WordPress与Storefront负责；这里不创建第二个查询，
 * 也不改变标签、搜索、文章详情或其他归档的输出。
 *
 * @return void
 */
function dentall_configure_blog_archive() {
	if ( ! dentall_is_blog_archive() ) {
		return;
	}

	remove_action( 'storefront_loop_post', 'storefront_post_header', 10 );
	remove_action( 'storefront_loop_post', 'storefront_post_content', 30 );
	remove_action( 'storefront_loop_post', 'storefront_post_taxonomy', 40 );
	add_action( 'storefront_loop_post', 'dentall_blog_card', 10 );

	remove_action( 'storefront_loop_after', 'storefront_paging_nav', 10 );
	add_action( 'storefront_loop_after', 'dentall_blog_pagination', 10 );

	remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
}
add_action( 'wp', 'dentall_configure_blog_archive', 20 );

/**
 * 输出Posts Page标题。
 *
 * Storefront的index.php不会输出被指定为Posts Page的Page标题；仅在主查询有文章时补齐
 * 内容H1。空结果继续由父主题content-none.php输出唯一的“Nothing Found”H1。
 *
 * @return void
 */
function dentall_blog_archive_header() {
	if ( ! is_home() || ! have_posts() ) {
		return;
	}

	$posts_page_id = absint( get_option( 'page_for_posts' ) );
	$title         = $posts_page_id ? trim( (string) get_the_title( $posts_page_id ) ) : '';

	if ( '' === $title ) {
		$title = __( 'Blog', 'dentall' );
	}
	?>
	<header class="dentall-blog-header">
		<h1 class="dentall-blog-header__title"><?php echo esc_html( $title ); ?></h1>
	</header>
	<?php
}
add_action( 'storefront_loop_before', 'dentall_blog_archive_header', 5 );

/**
 * 输出博客列表卡片。
 *
 * 公开列表只呈现访客做浏览决定所需的信息；后台账号作者、评论、标签和全文留在各自
 * 已确认的治理或详情范围，避免在D86提前暴露或重复实现。
 *
 * @return void
 */
function dentall_blog_card() {
	$permalink = get_permalink();
	$title     = trim( (string) get_the_title() );
	$excerpt   = trim( (string) get_the_excerpt() );
	$has_image = has_post_thumbnail();
	$classes   = 'dentall-blog-card';

	if ( '' === $title ) {
		$title = __( 'Untitled article', 'dentall' );
	}

	if ( ! $has_image ) {
		$classes .= ' dentall-blog-card--text-only';
	}
	?>
	<div class="<?php echo esc_attr( $classes ); ?>">
		<?php if ( $has_image ) : ?>
			<a
				class="dentall-blog-card__media"
				href="<?php echo esc_url( $permalink ); ?>"
				aria-label="<?php echo esc_attr( sprintf( __( 'View article: %s', 'dentall' ), $title ) ); ?>"
			>
				<?php
				the_post_thumbnail(
					'large',
					array(
						'class'    => 'dentall-blog-card__image',
						'decoding' => 'async',
						'sizes'    => '(min-width: 82.5rem) 25.25rem, (min-width: 64rem) calc((100vw - 7rem) / 3), (min-width: 48rem) calc((100vw - 5.5rem) / 2), calc(100vw - 2.5rem)',
					)
				);
				?>
			</a>
		<?php endif; ?>

		<div class="dentall-blog-card__body">
			<div class="dentall-blog-card__meta">
				<time class="dentall-blog-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
					<?php echo esc_html( get_the_date( _x( 'F j, Y', 'blog card date format', 'dentall' ) ) ); ?>
				</time>
				<?php
				$categories = get_the_category_list(
					/* translators: used between article category links. */
					__( ', ', 'dentall' )
				);
				if ( $categories ) :
					?>
					<span class="dentall-blog-card__categories">
						<?php echo wp_kses_post( $categories ); ?>
					</span>
				<?php endif; ?>
			</div>

			<h2 class="dentall-blog-card__title">
				<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
			</h2>

			<?php if ( '' !== $excerpt ) : ?>
				<p class="dentall-blog-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
			<?php endif; ?>

			<a class="dentall-blog-card__read-more" href="<?php echo esc_url( $permalink ); ?>">
				<?php esc_html_e( 'Read article', 'dentall' ); ?>
				<span class="screen-reader-text">
					<?php
					printf(
						/* translators: %s: article title. */
						esc_html__( ': %s', 'dentall' ),
						esc_html( $title )
					);
					?>
				</span>
			</a>
		</div>
	</div>
	<?php
}

/**
 * 输出博客列表与分类共用的核心数字分页。
 *
 * WordPress继续负责分页URL、当前页和aria-current；这里仅收敛页码密度与可访问名称。
 *
 * @return void
 */
function dentall_blog_pagination() {
	the_posts_pagination(
		array(
			'end_size'           => 1,
			'mid_size'           => 1,
			'prev_text'          => esc_html__( 'Previous', 'dentall' ),
			'next_text'          => esc_html__( 'Next', 'dentall' ),
			'screen_reader_text' => esc_html__( 'Blog pagination', 'dentall' ),
		)
	);
}
