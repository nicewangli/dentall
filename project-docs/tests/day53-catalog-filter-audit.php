<?php
/**
 * D53目录已选条件、动态计数、URL与查询形态集成审计。
 *
 * 运行：wp eval-file project-docs/tests/day53-catalog-filter-audit.php <场景> --path=app/public
 * 场景：full、low、mid、matrix、matrix_order、small、medium、combo、multi、multi_direct、multi_and、zero、reverse、category、search、search_filters。
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

global $wpdb, $wp, $wp_query;

if (
	'local' !== wp_get_environment_type()
	|| 'dentall.local' !== wp_parse_url( home_url( '/' ), PHP_URL_HOST )
	|| ! class_exists( 'DOMDocument' )
) {
	WP_CLI::error( '安全中止：本审计只允许在具备DOMDocument的dentall.local Local环境运行。' );
}

$brand_ids = array();

for ( $number = 1; $number <= 30; $number++ ) {
	$term = get_term_by( 'slug', sprintf( 'test-d53-brand-%02d', $number ), 'product_brand' );

	if ( ! $term instanceof WP_Term || '1' !== get_term_meta( $term->term_id, '_dentall_day53_fixture', true ) ) {
		WP_CLI::error( sprintf( 'D53品牌%02d夹具不存在或所有权标记异常。', $number ) );
	}

	$brand_ids[ $number ] = (int) $term->term_id;
}

$scenario = isset( $args[0] ) ? sanitize_key( $args[0] ) : 'full';
$scenarios = array(
	'full'     => array(),
	'low'      => array( 'min_price' => '0', 'max_price' => '25' ),
	'mid'      => array( 'min_price' => '25', 'max_price' => '40' ),
	'matrix'   => array( 'min_price' => '100', 'max_price' => '120' ),
	'matrix_order' => array( 'max_price' => '120', 'min_price' => '100' ),
	'small'    => array( 'min_price' => '100', 'max_price' => '120', 'filter_size' => 'small-98-mm', 'query_type_size' => 'or' ),
	'medium'   => array( 'min_price' => '100', 'max_price' => '120', 'filter_shade' => 'medium', 'query_type_shade' => 'or' ),
	'combo'    => array(
		'min_price'            => '100',
		'max_price'            => '120',
		'filter_size'          => 'small-98-mm',
		'query_type_size'      => 'or',
		'filter_shade'         => 'light',
		'query_type_shade'     => 'or',
		'filter_product_brand' => (string) $brand_ids[3],
		'orderby'              => 'price-desc',
	),
	'multi'    => array(
		'filter_size'          => 'large-105-mm,small-98-mm',
		'query_type_size'      => 'or',
		'filter_product_brand' => $brand_ids[3] . ',' . $brand_ids[4],
	),
	'multi_direct' => array(
		'filter_size' => 'large-105-mm,small-98-mm',
	),
	'multi_and' => array(
		'filter_size'     => 'large-105-mm,small-98-mm',
		'query_type_size' => 'and',
	),
	'zero'     => array(
		'min_price'            => '100',
		'max_price'            => '120',
		'filter_size'          => 'large-105-mm',
		'query_type_size'      => 'or',
		'filter_product_brand' => (string) $brand_ids[4],
	),
	'reverse'  => array( 'min_price' => '50', 'max_price' => '10' ),
	'category' => array(
		'min_price'            => '100',
		'max_price'            => '120',
		'filter_size'          => 'small-98-mm',
		'query_type_size'      => 'or',
		'filter_product_brand' => (string) $brand_ids[3],
		'orderby'              => 'price-desc',
	),
	'search'   => array( 's' => 'TEST', 'post_type' => 'product' ),
	'search_filters' => array(
		's'                    => 'TEST',
		'post_type'            => 'product',
		'min_price'            => '0',
		'max_price'            => '25',
		'filter_size'          => 'large-105-mm',
		'query_type_size'      => 'or',
		'filter_product_brand' => (string) $brand_ids[4],
	),
);

if ( ! isset( $scenarios[ $scenario ] ) ) {
	WP_CLI::error( '未知D53审计场景。' );
}

$request    = $scenarios[ $scenario ];
$is_search  = in_array( $scenario, array( 'search', 'search_filters' ), true );
$is_category = 'category' === $scenario;
$main_query = $request;

if ( ! $is_search ) {
	$main_query['post_type'] = 'product';
}

if ( $is_category ) {
	$main_query['taxonomy'] = 'product_cat';
	$main_query['term']     = 'test-d12-products';
}

$_GET                     = $request; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$_SERVER['HTTP_HOST']      = 'dentall.local';
$_SERVER['SERVER_NAME']    = 'dentall.local';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI']    = $is_category ? '/product-category/test-d12-products/' : '/shop/';

if ( $request ) {
	$_SERVER['REQUEST_URI'] .= '?' . http_build_query( $request, '', '&' );
}

if ( $is_category ) {
	$wp->main();
} else {
	$wp->main( http_build_query( $main_query, '', '&' ) );
}

$queries      = array();
$query_logger = static function ( $sql ) use ( &$queries ) {
	$queries[] = $sql;

	return $sql;
};

add_filter( 'query', $query_logger, PHP_INT_MAX );
$query_start = $wpdb->num_queries;

ob_start();
wc_get_template( 'archive-product.php' );
$html = ob_get_clean();

$render_query_count = $wpdb->num_queries - $query_start;
remove_filter( 'query', $query_logger, PHP_INT_MAX );

libxml_use_internal_errors( true );
$document = new DOMDocument();
$document->loadHTML( $html );
$xpath = new DOMXPath( $document );

$facet = static function ( $title ) use ( $xpath ) {
	$values = array();
	$items  = $xpath->query(
		'//section[contains(concat(" ", normalize-space(@class), " "), " dentall-catalog-filter ")][h2[normalize-space()="' . $title . '"]]//li'
	);

	if ( ! $items ) {
		return $values;
	}

	foreach ( $items as $item ) {
		$label_node = $xpath->query( './a | ./span[not(contains(concat(" ", normalize-space(@class), " "), " count "))]', $item );
		$count_node = $xpath->query( './span[contains(concat(" ", normalize-space(@class), " "), " count ")]', $item );

		if ( ! $label_node || ! $label_node->length ) {
			continue;
		}

		$label = trim( $label_node->item( 0 )->nodeValue );
		$count = $count_node && $count_node->length
			? absint( preg_replace( '/\D+/', '', $count_node->item( 0 )->nodeValue ) )
			: null;
		$values[ $label ] = array(
			'count'    => $count,
			'selected' => false !== strpos( ' ' . $item->getAttribute( 'class' ) . ' ', ' chosen ' ),
			'href'     => $label_node->item( 0 ) instanceof DOMElement ? $label_node->item( 0 )->getAttribute( 'href' ) : '',
		);
	}

	return $values;
};

$active_items = array();
$active_nodes = $xpath->query( '//nav[contains(concat(" ", normalize-space(@class), " "), " dentall-catalog-active-filters ")]//ul/li/a' );

if ( $active_nodes ) {
	foreach ( $active_nodes as $active_node ) {
		$label = $xpath->query( './span[1]', $active_node );
		$active_items[] = array(
			'label' => $label && $label->length ? trim( $label->item( 0 )->nodeValue ) : '',
			'href'  => $active_node->getAttribute( 'href' ),
			'aria'  => $active_node->getAttribute( 'aria-label' ),
			'rel'   => $active_node->getAttribute( 'rel' ),
		);
	}
}

$clear_node = $xpath->query( '//a[contains(concat(" ", normalize-space(@class), " "), " dentall-catalog-active-filters__clear ")]' );
$clear_url  = $clear_node && $clear_node->length ? $clear_node->item( 0 )->getAttribute( 'href' ) : '';
$clear_rel  = $clear_node && $clear_node->length ? $clear_node->item( 0 )->getAttribute( 'rel' ) : '';
$id_nodes   = $xpath->query( '//*[@id]' );
$ids        = array();

if ( $id_nodes ) {
	foreach ( $id_nodes as $id_node ) {
		$ids[] = $id_node->getAttribute( 'id' );
	}
}

$brand_child_queries = array_filter(
	$queries,
	static function ( $sql ) {
		return 1 === preg_match( "/\\btt\\.parent\\s*=\\s*['\"]?[1-9]\\d*/i", $sql )
			|| 1 === preg_match( '/\\btt\\.parent\\s+IN\\s*\\([^)]*[1-9]\\d*[^)]*\\)/i', $sql );
	}
);

$checks = array(
	'no_duplicate_ids'                => count( $ids ) === count( array_unique( $ids ) ),
	'no_php_error_markup'              => false === stripos( $html, 'fatal error' ) && false === stripos( $html, 'warning:' ),
	'categories_have_no_counts'        => 0 === (int) $xpath->evaluate( 'count(//nav[@aria-labelledby="dentall-filter-categories-title"]//*[contains(concat(" ", normalize-space(@class), " "), " count ")])' ),
	'price_has_no_pseudo_count'         => 0 === (int) $xpath->evaluate( 'count(//section[@aria-labelledby="dentall-filter-price-title"]//*[contains(concat(" ", normalize-space(@class), " "), " count ")])' ),
	'brand_has_no_child_term_queries'      => 0 === count( $brand_child_queries ),
	'unsafe_public_values_are_dropped'      => array( 'orderby' => 'price-desc' ) === dentall_catalog_filter_sanitize_query_args(
		array(
			'min_price'            => array( '0' ),
			'max_price'            => '1e3',
			'filter_size'          => array( 'small-98-mm' ),
			'filter_shade'         => '<script>alert(1)</script>',
			'filter_product_brand' => $brand_ids[1] . ',invalid',
			'orderby'              => 'price-desc',
			'foo'                  => 'drop-me',
		)
	),
);

$brand = $facet( 'Brand' );
$size  = $facet( 'Size' );
$shade = $facet( 'Shade' );
$active_labels = wp_list_pluck( $active_items, 'label' );
$active_by_label = array_column( $active_items, null, 'label' );
$url_args = static function ( $url ) {
	$query = wp_parse_url( $url, PHP_URL_QUERY );
	$args  = array();

	if ( is_string( $query ) ) {
		wp_parse_str( $query, $args );
	}

	ksort( $args );

	return $args;
};
$product_titles = array();
$title_nodes = $xpath->query( '//*[contains(concat(" ", normalize-space(@class), " "), " woocommerce-loop-product__title ")]' );

if ( $title_nodes ) {
	foreach ( $title_nodes as $title_node ) {
		$product_titles[] = trim( $title_node->nodeValue );
	}
}

$robots_node   = $xpath->query( '//meta[@name="robots"]' );
$canonical_node = $xpath->query( '//link[@rel="canonical"]' );
$robots        = $robots_node && $robots_node->length ? $robots_node->item( 0 )->getAttribute( 'content' ) : '';
$canonical     = $canonical_node && $canonical_node->length ? $canonical_node->item( 0 )->getAttribute( 'href' ) : '';
$has_filters   = (bool) array_intersect_key(
	$request,
	array_flip( array( 'min_price', 'max_price', 'filter_size', 'filter_shade', 'filter_product_brand' ) )
);

if ( ! $is_search ) {
	$expected_canonical = $is_category
		? home_url( '/product-category/test-d12-products/' )
		: wc_get_page_permalink( 'shop' );
	$checks['seo_contract_matches_filter_state'] = false !== strpos( $robots, $has_filters ? 'noindex' : 'index' )
		&& false !== strpos( $robots, 'follow' )
		&& untrailingslashit( $expected_canonical ) === untrailingslashit( $canonical );
}

$checks['filter_choice_links_are_nofollow'] = 0 === (int) $xpath->evaluate(
	'count(//section[(contains(concat(" ", normalize-space(@class), " "), " widget_brand_nav ") or contains(concat(" ", normalize-space(@class), " "), " widget_layered_nav "))]//a[not(contains(concat(" ", normalize-space(@rel), " "), " nofollow "))])'
);

if ( $active_items ) {
	$checks['active_action_links_are_nofollow'] = 'nofollow' === $clear_rel
		&& ! array_filter( $active_items, static function ( $item ) { return 'nofollow' !== $item['rel']; } );
}

switch ( $scenario ) {
	case 'full':
		$checks['full_parent_product_count'] = 30 === (int) $wp_query->found_posts;
		$checks['all_30_brands_show_once'] = 30 === count( $brand ) && ! array_filter( wp_list_pluck( $brand, 'count' ), static function ( $count ) { return 1 !== $count; } );
		$checks['full_attribute_counts'] = 3 === ( $size['Small 98 mm']['count'] ?? null )
			&& 2 === ( $size['Large 105 mm']['count'] ?? null )
			&& 3 === ( $shade['Light']['count'] ?? null )
			&& 2 === ( $shade['Medium']['count'] ?? null );
		$checks['no_empty_active_summary'] = ! $active_items && '' === $clear_url;
		break;

	case 'low':
		$checks['low_price_result'] = 1 === (int) $wp_query->found_posts && array( 'TEST D12 Simple Fixed Pack' ) === $product_titles;
		$checks['low_price_counts'] = array( 'TEST D53 Brand 01' ) === array_keys( $brand ) && 1 === $brand['TEST D53 Brand 01']['count'] && ! $size && ! $shade;
		$checks['zero_bound_is_active'] = array( 'Price: $0.00–$25.00' ) === $active_labels;
		break;

	case 'mid':
		$checks['variable_is_one_parent_result'] = 1 === (int) $wp_query->found_posts && array( 'TEST D12 Variable Size Shade' ) === $product_titles;
		$checks['variable_facet_counts_are_one'] = array( 'TEST D53 Brand 02' ) === array_keys( $brand )
			&& ! array_filter( wp_list_pluck( array_merge( $size, $shade ), 'count' ), static function ( $count ) { return 1 !== $count; } );
		break;

	case 'matrix':
	case 'matrix_order':
		$checks['matrix_result_count'] = 18 === (int) $wp_query->found_posts;
		$checks['matrix_attribute_counts'] = 2 === ( $size['Small 98 mm']['count'] ?? null )
			&& 1 === ( $size['Large 105 mm']['count'] ?? null )
			&& 2 === ( $shade['Light']['count'] ?? null )
			&& 1 === ( $shade['Medium']['count'] ?? null );
		$checks['matrix_brand_range'] = 18 === count( $brand ) && isset( $brand['TEST D53 Brand 03'], $brand['TEST D53 Brand 20'] );
		break;

	case 'small':
		$checks['other_attribute_respects_size'] = 2 === (int) $wp_query->found_posts
			&& 1 === ( $shade['Light']['count'] ?? null )
			&& 1 === ( $shade['Medium']['count'] ?? null );
		$checks['brand_respects_size'] = array( 'TEST D53 Brand 03', 'TEST D53 Brand 04' ) === array_keys( $brand );
		break;

	case 'medium':
		$checks['size_respects_shade'] = 1 === (int) $wp_query->found_posts
			&& 1 === ( $size['Small 98 mm']['count'] ?? null )
			&& ! isset( $size['Large 105 mm'] );
		$checks['brand_respects_shade'] = array( 'TEST D53 Brand 04' ) === array_keys( $brand );
		break;

	case 'combo':
		$checks['combined_result'] = 1 === (int) $wp_query->found_posts && array( 'TEST D53 Brand Product 03' ) === $product_titles;
		$checks['all_active_dimensions_present'] = array(
			'Price: $100.00–$120.00',
			'Size: Small 98 mm',
			'Shade: Light',
			'TEST D53 Brand 03',
		) === array_map(
			static function ( $label ) {
				return str_replace( 'Brand: ', '', $label );
			},
			$active_labels
		);
		$checks['clear_keeps_only_sort'] = array( 'orderby' => 'price-desc' ) === wp_parse_args( wp_parse_url( $clear_url, PHP_URL_QUERY ) );
		$checks['remove_links_keep_other_state'] = 4 === count( $active_items ) && ! array_filter(
			$active_items,
			static function ( $item ) {
				return false !== strpos( $item['href'], 'filtering=' ) || false !== strpos( $item['href'], 'paged=' ) || '' === $item['aria'];
			}
		);
		$combo_expected = dentall_catalog_filter_sanitize_query_args( $request );
		$combo_price    = $combo_expected;
		$combo_size     = $combo_expected;
		$combo_shade    = $combo_expected;
		$combo_brand    = $combo_expected;
		unset( $combo_price['min_price'], $combo_price['max_price'] );
		unset( $combo_size['filter_size'], $combo_size['query_type_size'] );
		unset( $combo_shade['filter_shade'], $combo_shade['query_type_shade'] );
		unset( $combo_brand['filter_product_brand'] );
		ksort( $combo_price );
		ksort( $combo_size );
		ksort( $combo_shade );
		ksort( $combo_brand );
		$checks['each_combo_chip_removes_only_its_dimension'] = $combo_price === $url_args( $active_by_label['Price: $100.00–$120.00']['href'] ?? '' )
			&& $combo_size === $url_args( $active_by_label['Size: Small 98 mm']['href'] ?? '' )
			&& $combo_shade === $url_args( $active_by_label['Shade: Light']['href'] ?? '' )
			&& $combo_brand === $url_args( $active_by_label['Brand: TEST D53 Brand 03']['href'] ?? '' );
		break;

	case 'multi':
		$checks['multi_terms_are_independent'] = 4 === count( $active_items )
			&& in_array( 'Size: Large 105 mm', $active_labels, true )
			&& in_array( 'Size: Small 98 mm', $active_labels, true )
			&& in_array( 'Brand: TEST D53 Brand 03', $active_labels, true )
			&& in_array( 'Brand: TEST D53 Brand 04', $active_labels, true );
		$checks['multi_removal_keeps_sibling_terms'] = array(
			'filter_product_brand' => $brand_ids[3] . ',' . $brand_ids[4],
			'filter_size'          => 'small-98-mm',
			'query_type_size'      => 'or',
		) === $url_args( $active_by_label['Size: Large 105 mm']['href'] ?? '' )
			&& array(
				'filter_product_brand' => (string) $brand_ids[4],
				'filter_size'          => 'large-105-mm,small-98-mm',
				'query_type_size'      => 'or',
			) === $url_args( $active_by_label['Brand: TEST D53 Brand 03']['href'] ?? '' );
		break;

	case 'multi_direct':
	case 'multi_and':
		$checks['direct_attribute_query_uses_project_or_semantics'] = 4 === (int) $wp_query->found_posts
			&& 'or' === ( $_GET['query_type_size'] ?? '' )
			&& 2 === count( array_filter( $active_labels, static function ( $label ) { return 0 === strpos( $label, 'Size: ' ); } ) );
		break;

	case 'zero':
		$checks['zero_result_keeps_active_summary'] = 0 === (int) $wp_query->found_posts && 3 === count( $active_items );
		$checks['zero_selected_size_removable'] = isset( $size['Large 105 mm'] ) && $size['Large 105 mm']['selected'] && 0 === $size['Large 105 mm']['count'];
		$checks['zero_selected_brand_removable'] = isset( $brand['TEST D53 Brand 04'] ) && $brand['TEST D53 Brand 04']['selected'] && 0 === $brand['TEST D53 Brand 04']['count'];
		$checks['other_dimension_alternatives_remain'] = 1 === ( $size['Small 98 mm']['count'] ?? null ) && 1 === ( $brand['TEST D53 Brand 05']['count'] ?? null );
		break;

	case 'reverse':
		$checks['reverse_range_is_recoverable'] = 0 === (int) $wp_query->found_posts
			&& array( 'Price: $50.00–$10.00' ) === $active_labels
			&& 1 === (int) $xpath->evaluate( 'count(//*[@id="dentall-price-error" and @role="alert"])' )
			&& 2 === (int) $xpath->evaluate( 'count(//input[@aria-invalid="true"])' )
			&& untrailingslashit( wc_get_page_permalink( 'shop' ) ) === untrailingslashit( $clear_url );
		break;

	case 'category':
		$checks['category_context_preserved'] = is_product_category( 'test-d12-products' ) && 1 === (int) $wp_query->found_posts;
		$checks['category_clear_keeps_route_and_sort'] = 'test-d12-products' === basename( untrailingslashit( wp_parse_url( $clear_url, PHP_URL_PATH ) ) )
			&& array( 'orderby' => 'price-desc' ) === wp_parse_args( wp_parse_url( $clear_url, PHP_URL_QUERY ) );
		break;

	case 'search':
	case 'search_filters':
		$checks['search_isolation'] = is_search()
			&& 30 === (int) $wp_query->found_posts
			&& ! array_intersect_key(
				$_GET, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				array_flip( array( 'min_price', 'max_price', 'filter_size', 'query_type_size', 'filter_product_brand' ) )
			)
			&& 0 === (int) $xpath->evaluate( 'count(//*[contains(concat(" ", normalize-space(@class), " "), " dentall-catalog-filters ")])' )
			&& 0 === (int) $xpath->evaluate( 'count(//nav[contains(concat(" ", normalize-space(@class), " "), " dentall-catalog-active-filters ")])' )
			&& 0 === (int) $xpath->evaluate( 'count(//*[@data-dentall-filter-toggle])' );
		break;
}

libxml_clear_errors();

$count_queries = count(
	array_filter(
		$queries,
		static function ( $sql ) {
			return false !== stripos( $sql, 'term_count_id' );
		}
	)
);
$checks['count_query_upper_bound'] = $count_queries <= 3;

WP_CLI::line(
	sprintf(
		"INFO\tscenario=%s found=%d brands=%d active=%d render_queries=%d count_queries=%d brand_child_queries=%d bytes=%d",
		$scenario,
		(int) $wp_query->found_posts,
		count( $brand ),
		count( $active_items ),
		$render_query_count,
		$count_queries,
		count( $brand_child_queries ),
		strlen( $html )
	)
);

if ( $is_category ) {
	$queried = get_queried_object();
	WP_CLI::line(
		sprintf(
			"INFO\tcategory=%s taxonomy=%s slug=%s clear=%s",
			is_product_category() ? 'yes' : 'no',
			$queried instanceof WP_Term ? $queried->taxonomy : '-',
			$queried instanceof WP_Term ? $queried->slug : '-',
			$clear_url
		)
	);
}

foreach ( $checks as $name => $passed ) {
	WP_CLI::line( sprintf( "%s\t%s", $passed ? 'PASS' : 'FAIL', $name ) );
}

if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( sprintf( 'D53 %s场景审计未通过。', $scenario ) );
}

WP_CLI::success( sprintf( 'D53 %s场景审计通过。', $scenario ) );
