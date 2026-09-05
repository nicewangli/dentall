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
