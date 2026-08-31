<?php
/**
 * D41首页Best Sellers与Trust只读审计。
 *
 * 本脚本不创建订单、不更新销量、不修改商品。正向商品场景通过读取期过滤器模拟，
 * 仅验证查询边界、模板输出和全局状态还原。
 *
 * 运行：php -d mysqli.default_port=<Local端口> C:/wp-cli/wp-cli.phar eval-file
 * project-docs/tests/day41-home-products-trust-audit.php --path=app/public
 */

$site_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

if ( 'local' !== wp_get_environment_type() || 'dentall.local' !== $site_host ) {
	WP_CLI::error( '安全中止：本审计只允许在WP_ENVIRONMENT_TYPE=local且主机为dentall.local时运行。' );
}

$checks = array();

$baseline_products = dentall_get_homepage_best_sellers();

ob_start();
dentall_homepage_best_sellers();
$baseline_html = ob_get_clean();

$checks['baseline_limit_and_sales_boundary'] = count( $baseline_products ) <= 5
	&& ! array_filter(
		$baseline_products,
		function ( $product ) {
			return ! $product instanceof WC_Product || $product->get_total_sales() < 1;
		}
	);
$checks['baseline_empty_output_consistent'] = ( 0 === count( $baseline_products ) )
	? false === strpos( $baseline_html, 'dentall-home-best-sellers dentall-section' )
	: 1 === substr_count( $baseline_html, 'dentall-home-best-sellers dentall-section' );

$published_product_ids = get_posts(
	array(
		'post_type'        => 'product',
		'post_status'      => 'publish',
		'posts_per_page'   => -1,
		'fields'           => 'ids',
		'has_password'     => false,
		'suppress_filters' => false,
	)
);

if ( ! $published_product_ids ) {
	WP_CLI::error( '缺少可用于只读正向场景的已发布Local TEST商品。' );
}

$simulated_sales = array();
$sales_value     = count( $published_product_ids ) + 10;

foreach ( $published_product_ids as $product_id ) {
	$simulated_sales[ (int) $product_id ] = $sales_value--;
}

$original_get       = $_GET;
$captured_sql       = '';
$captured_tax_query = array();

$capture_sql = function ( $sql, $query ) use ( &$captured_sql ) {
	if ( 'product' === $query->get( 'post_type' ) && 5 === (int) $query->get( 'posts_per_page' ) ) {
		$captured_sql = $sql;
	}

	return $sql;
};

$capture_tax_query = function ( $query ) use ( &$captured_tax_query ) {
	if ( 'product' === $query->get( 'post_type' ) && 5 === (int) $query->get( 'posts_per_page' ) ) {
		$captured_tax_query = $query->get( 'tax_query' );
	}
};

$simulate_total_sales = function ( $value, $product ) use ( $simulated_sales ) {
	$product_id = $product instanceof WC_Product ? $product->get_id() : 0;

	return isset( $simulated_sales[ $product_id ] ) ? $simulated_sales[ $product_id ] : $value;
};

$had_loop_before    = array_key_exists( 'woocommerce_loop', $GLOBALS );
$loop_before        = $had_loop_before ? $GLOBALS['woocommerce_loop'] : null;
$had_post_before    = array_key_exists( 'post', $GLOBALS );
$post_before        = $had_post_before ? $GLOBALS['post'] : null;
$had_product_before = array_key_exists( 'product', $GLOBALS );
$product_before     = $had_product_before ? $GLOBALS['product'] : null;
$simulated_products = array();
$simulated_totals   = array();
$simulated_html     = '';

try {
	$_GET['orderby']      = 'price';
	$_GET['rating_filter'] = '5';

	add_filter( 'posts_request', $capture_sql, 10, 2 );
	add_action( 'pre_get_posts', $capture_tax_query );
	add_filter( 'woocommerce_product_get_total_sales', $simulate_total_sales, 10, 2 );

	$simulated_products = dentall_get_homepage_best_sellers();
	$simulated_totals   = array_map(
		function ( $product ) {
			return $product->get_total_sales();
		},
		$simulated_products
	);

	ob_start();
	dentall_homepage_best_sellers();
	$simulated_html = ob_get_clean();
} finally {
	remove_filter( 'posts_request', $capture_sql, 10 );
	remove_action( 'pre_get_posts', $capture_tax_query );
	remove_filter( 'woocommerce_product_get_total_sales', $simulate_total_sales, 10 );
	$_GET = $original_get;
}

$checks['url_ordering_cannot_override_popularity'] = false !== strpos(
	$captured_sql,
	'wc_product_meta_lookup.total_sales DESC'
);
$checks['url_rating_filter_not_injected'] = false === strpos(
	wp_json_encode( $captured_tax_query ),
	'rating_filter'
);
$checks['simulated_products_are_positive_and_limited'] = count( $simulated_products ) > 0
	&& count( $simulated_products ) <= 5
	&& ! array_filter(
		$simulated_totals,
		function ( $total_sales ) {
			return $total_sales < 1;
		}
	);
$checks['simulated_section_and_cards_render_once'] = 1 === substr_count(
	$simulated_html,
	'dentall-home-best-sellers dentall-section'
)
	&& count( $simulated_products ) === substr_count( $simulated_html, '<li class="product ' );
$shop_page_id  = wc_get_page_id( 'shop' );
$shop_page_url = $shop_page_id > 0 ? get_permalink( $shop_page_id ) : '';

$checks['shop_link_uses_published_shop_page'] = $shop_page_id > 0
	&& 'publish' === get_post_status( $shop_page_id )
	&& is_string( $shop_page_url )
	&& '' !== $shop_page_url
	&& 1 === substr_count( $simulated_html, 'href="' . esc_url( $shop_page_url ) . '"' )
	&& 1 === substr_count( $simulated_html, 'View all products' );
$checks['woocommerce_loop_restored'] = $had_loop_before === array_key_exists( 'woocommerce_loop', $GLOBALS )
	&& ( ! $had_loop_before || $loop_before === $GLOBALS['woocommerce_loop'] );
$checks['post_global_restored'] = $had_post_before === array_key_exists( 'post', $GLOBALS )
	&& ( ! $had_post_before || $post_before === $GLOBALS['post'] );
$checks['product_global_restored'] = $had_product_before === array_key_exists( 'product', $GLOBALS )
	&& ( ! $had_product_before || $product_before === $GLOBALS['product'] );

$catalog_query               = WC()->query;
$popularity_callback         = array( $catalog_query, 'order_by_popularity_post_clauses' );
$had_popularity_before_test  = 10 === has_filter( 'posts_clauses', $popularity_callback );
$popularity_filter_preserved = false;

if ( ! $had_popularity_before_test ) {
	add_filter( 'posts_clauses', $popularity_callback, 10 );
}

try {
	dentall_get_homepage_best_sellers();
	$popularity_filter_preserved = 10 === has_filter( 'posts_clauses', $popularity_callback );
} finally {
	if ( ! $had_popularity_before_test ) {
		remove_filter( 'posts_clauses', $popularity_callback, 10 );
	}
}

$checks['preexisting_popularity_filter_preserved'] = $popularity_filter_preserved;

$trust_metrics = dentall_get_homepage_trust_metrics();

ob_start();
dentall_homepage_trust_metrics();
$trust_html = ob_get_clean();

$checks['local_trust_has_five_metrics'] = 5 === count( $trust_metrics )
	&& 5 === substr_count( $trust_html, 'dentall-home-trust__item' )
	&& 5 === substr_count( $trust_html, '<use href=' );
$checks['trust_uses_expected_svg_symbols'] = ! array_diff(
	array( 'professionals', 'globe', 'box', 'smile', 'lock' ),
	wp_list_pluck( $trust_metrics, 'icon' )
);
$checks['trust_scroll_region_has_keyboard_entry'] = 1 === substr_count( $trust_html, 'tabindex="0"' )
	&& 1 === substr_count( $trust_html, 'aria-label="Trust metrics"' );
$checks['homepage_hook_order_is_stable'] = 40 === has_action( 'homepage', 'dentall_homepage_best_sellers' )
	&& 50 === has_action( 'homepage', 'dentall_homepage_trust_metrics' );

WP_CLI::line(
	sprintf(
		"INFO\tbaseline_best_sellers=%d simulated_best_sellers=%d trust_metrics=%d",
		count( $baseline_products ),
		count( $simulated_products ),
		count( $trust_metrics )
	)
);

foreach ( $checks as $name => $passed ) {
	WP_CLI::line( sprintf( "%s\t%s", $passed ? 'PASS' : 'FAIL', $name ) );
}

if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( 'D41首页Best Sellers与Trust只读审计未通过。' );
}

WP_CLI::success( 'D41首页Best Sellers与Trust只读审计通过。' );
