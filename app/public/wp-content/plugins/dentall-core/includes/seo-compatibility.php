<?php
/**
 * DentAll网站级SEO兼容处理。
 */

defined( 'ABSPATH' ) || exit;

/**
 * Yoast负责前台SEO输出时，移除WordPress核心重复的文档Title回调。
 *
 * Yoast通常会自行移除该回调，但Local的WordPress 7.0.4与Yoast 28.2最小
 * 插件组合仍会同时输出两个Title。这里只处理已验证的核心回调；Yoast停用时
 * 不执行；当前主题不声明title-tag支持或Yoast停用时，让WordPress/主题保留原有
 * Title责任，避免主题切换或停用插件后丢失标题。
 * 本函数在wp_head优先级0运行，确保晚于其他组件重新挂载回调、早于核心优先级1输出。
 *
 * @return void
 */
function dentall_core_prevent_duplicate_document_title() {
	if ( ! defined( 'WPSEO_VERSION' ) || ! current_theme_supports( 'title-tag' ) ) {
		return;
	}

	remove_action( 'wp_head', '_block_template_render_title_tag', 1 );
}
add_action( 'wp_head', 'dentall_core_prevent_duplicate_document_title', 0 );

/**
 * 经典商品模板保留WooCommerce面包屑，避免Yoast重复输出另一条路径。
 *
 * get_header('shop')在wp_head之前发生，且只在模板实际渲染时触发。
 * Coming Soon使用普通页头或区块模板，不能仅凭is_product()移除其唯一的面包屑。
 * 原生可见面包屑未挂载时保持原输出；主题或模板机制变化后需重新验证此边界。
 *
 * @param string|null $name 页头模板名称。
 * @return void
 */
function dentall_core_use_native_product_breadcrumb_schema( $name ) {
	if (
		'shop' !== $name
		|| ! defined( 'WPSEO_VERSION' )
		|| ! function_exists( 'is_product' )
		|| ! is_product()
		|| (
			false === has_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb' )
			&& false === has_action( 'storefront_before_content', 'woocommerce_breadcrumb' )
		)
	) {
		return;
	}

	add_filter( 'wpseo_schema_needs_breadcrumb', '__return_false' );
	add_filter( 'wpseo_schema_webpage', 'dentall_core_remove_yoast_breadcrumb_reference' );
}
add_action( 'get_header', 'dentall_core_use_native_product_breadcrumb_schema' );

/**
 * 删除WebPage对已移除Yoast面包屑的引用，避免留下悬空的@id。
 *
 * @param array<string, mixed> $data Yoast WebPage节点。
 * @return array<string, mixed>
 */
function dentall_core_remove_yoast_breadcrumb_reference( $data ) {
	unset( $data['breadcrumb'] );
	return $data;
}

/**
 * 将商品筛选参数页标记为noindex, follow，同时保留Yoast基础归档Canonical。
 *
 * 使用晚于Yoast的wp_robots过滤器，而不修改Yoast内部robots presentation；后者会让
 * Yoast停止输出Canonical。键是否存在即触发，确保空值或非法值也不会形成可索引重复页。
 *
 * @param array<string, bool|string> $robots WordPress robots指令。
 * @return array<string, bool|string>
 */
function dentall_core_noindex_catalog_filter_pages( $robots ) {
	if (
		! function_exists( 'is_shop' )
		|| ! function_exists( 'is_product_category' )
		|| is_search()
		|| ( ! is_shop() && ! is_product_category() )
	) {
		return $robots;
	}

	foreach ( array_keys( $_GET ) as $key ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (
			! is_string( $key )
			|| (
				'min_price' !== $key
				&& 'max_price' !== $key
				&& 0 !== strpos( $key, 'filter_' )
				&& 0 !== strpos( $key, 'query_type_' )
			)
		) {
			continue;
		}

		unset( $robots['index'], $robots['nofollow'] );
		$robots['noindex'] = true;
		$robots['follow']  = true;
		break;
	}

	return $robots;
}
add_filter( 'wp_robots', 'dentall_core_noindex_catalog_filter_pages', PHP_INT_MAX );

/**
 * 依据Yoast目标文章判断，兼容页面请求与按文章ID生成的REST预览。
 *
 * @param object $context Yoast目标页面上下文。
 * @return bool
 */
function dentall_core_is_public_post_seo_context( $context ) {
	return isset( $context->indexable->object_type, $context->indexable->object_sub_type )
		&& 'post' === $context->indexable->object_type
		&& 'post' === $context->indexable->object_sub_type;
}

/**
 * 单篇文章的公开署名是编辑团队，后台作者账号只承担内容归属。
 *
 * 使用独立Organization身份，保留Yoast原有Article日期、图片和publisher事实。
 *
 * @param array<string, mixed> $data Yoast Article节点。
 * @param object               $context Yoast当前页面上下文。
 * @return array<string, mixed>
 */
function dentall_core_article_editorial_author( $data, $context ) {
	if ( ! dentall_core_is_public_post_seo_context( $context ) ) {
		return $data;
	}

	$site_url      = isset( $context->site_url ) ? $context->site_url : home_url( '/' );
	$data['author'] = array(
		'@type' => 'Organization',
		'@id'   => trailingslashit( $site_url ) . '#editorial-team',
		'name'  => __( 'DentAll Editorial Team', 'dentall-core' ),
	);

	return $data;
}
add_filter( 'wpseo_schema_article', 'dentall_core_article_editorial_author', 10, 2 );

/**
 * Article不再引用后台账号时，移除Yoast独立生成的该账号Person节点。
 *
 * @param array<string, mixed> $data    Yoast Author节点。
 * @param object               $context Yoast目标页面上下文。
 * @return array<string, mixed>|false
 */
function dentall_core_hide_article_person_author( $data, $context ) {
	return dentall_core_is_public_post_seo_context( $context ) ? false : $data;
}
add_filter( 'wpseo_schema_author', 'dentall_core_hide_article_person_author', 10, 2 );

/**
 * 若Yoast在WebPage上引用后台作者，移除已不再存在的Person引用。
 *
 * @param array<string, mixed> $data    Yoast WebPage节点。
 * @param object               $context Yoast目标页面上下文。
 * @return array<string, mixed>
 */
function dentall_core_remove_article_webpage_author( $data, $context ) {
	if ( dentall_core_is_public_post_seo_context( $context ) ) {
		unset( $data['author'] );
	}

	return $data;
}
add_filter( 'wpseo_schema_webpage', 'dentall_core_remove_article_webpage_author', 10, 2 );

/**
 * Yoast社交卡片中的个人账号属于后台身份，不用于公开编辑团队署名。
 *
 * @param object $presentation Yoast页面展示对象。
 * @param object $context      Yoast目标页面上下文。
 * @return object
 */
function dentall_core_article_social_presentation( $presentation, $context ) {
	if ( dentall_core_is_public_post_seo_context( $context ) ) {
		$site_twitter                            = ltrim( trim( (string) $presentation->twitter_site ), '@' );
		$presentation->open_graph_article_author = '';
		$presentation->twitter_creator           = 'person' !== $context->site_represents && '' !== $site_twitter
			? '@' . $site_twitter
			: '';
	}

	return $presentation;
}
add_filter( 'wpseo_frontend_presentation', 'dentall_core_article_social_presentation', 10, 2 );

/**
 * HTML作者元标签与可见署名保持一致。
 *
 * @param string $author       Yoast原作者名。
 * @param object $presentation Yoast页面展示对象。
 * @return string
 */
function dentall_core_article_meta_author( $author, $presentation ) {
	return dentall_core_is_public_post_seo_context( $presentation->context )
		? __( 'DentAll Editorial Team', 'dentall-core' )
		: $author;
}
add_filter( 'wpseo_meta_author', 'dentall_core_article_meta_author', 10, 2 );

/**
 * Slack预览的Written by字段不应重新显示后台账号。
 *
 * @param array<string, string> $data         Yoast分享附加字段。
 * @param object                $presentation Yoast页面展示对象。
 * @return array<string, string>
 */
function dentall_core_article_slack_author( $data, $presentation ) {
	$label = __( 'Written by', 'wordpress-seo' );
	if ( dentall_core_is_public_post_seo_context( $presentation->context ) && isset( $data[ $label ] ) ) {
		$data[ $label ] = __( 'DentAll Editorial Team', 'dentall-core' );
	}

	return $data;
}
add_filter( 'wpseo_enhanced_slack_data', 'dentall_core_article_slack_author', 10, 2 );
