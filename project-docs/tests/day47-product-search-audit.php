<?php
/**
 * D47商品搜索只读集成审计。
 *
 * 运行示例：
 * php -d mysqli.default_port=10011 C:\wp-cli\wp-cli.phar eval-file \
 * project-docs/tests/day47-product-search-audit.php TEST 1 relevance --path=app/public
 *
 * 本脚本只审计主查询、真实模板DOM与SEO输出；template_redirect必须另用真实HTTP请求验证。
 */

defined( 'ABSPATH' ) || exit;

global $wp, $wp_query, $wp_the_query;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

if ( ! class_exists( 'DOMDocument' ) ) {
	WP_CLI::error( '缺少DOMDocument扩展，无法执行D47模板DOM审计。' );
}

$search  = isset( $args[0] ) ? (string) $args[0] : 'TEST';
$paged   = isset( $args[1] ) ? max( 1, absint( $args[1] ) ) : 1;
$orderby = isset( $args[2] ) ? sanitize_key( $args[2] ) : 'relevance';
$request = array(
	's'         => $search,
	'post_type' => 'product',
);

if ( $paged > 1 ) {
	$request['paged'] = $paged;
}

if ( 'relevance' !== $orderby ) {
	$request['orderby'] = $orderby;
}

$_GET                     = $request;
$_SERVER['HTTP_HOST']      = 'dentall.local';
$_SERVER['SERVER_NAME']    = 'dentall.local';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI']    = '/?' . http_build_query( $request, '', '&' );

$wp->main( http_build_query( $request, '', '&' ) );

ob_start();
wc_get_template( 'archive-product.php' );
$html = ob_get_clean();

libxml_use_internal_errors( true );

$document = new DOMDocument();
$document->loadHTML( $html );
$xpath = new DOMXPath( $document );

$count_nodes = static function ( $query ) use ( $xpath ) {
	$nodes = $xpath->query( $query );

	return $nodes ? $nodes->length : 0;
};

$first_value = static function ( $query ) use ( $xpath ) {
	$nodes = $xpath->query( $query );

	return $nodes && $nodes->length ? trim( $nodes->item( 0 )->nodeValue ) : '';
};

$attribute_values = static function ( $query, $attribute ) use ( $xpath ) {
	$values = array();
	$nodes  = $xpath->query( $query );

	if ( ! $nodes ) {
		return $values;
	}

	foreach ( $nodes as $node ) {
		$values[] = $node->getAttribute( $attribute );
	}

	return $values;
};

$ids        = $attribute_values( '//*[@id]', 'id' );
$duplicates = array_keys( array_filter( array_count_values( $ids ), static function ( $count ) {
	return $count > 1;
} ) );
$ordering_inputs = array();
$input_nodes     = $xpath->query( '//form[contains(concat(" ", normalize-space(@class), " "), " woocommerce-ordering ")]//input[@name]' );

if ( $input_nodes ) {
	foreach ( $input_nodes as $input_node ) {
		$ordering_inputs[ $input_node->getAttribute( 'name' ) ] = $input_node->getAttribute( 'value' );
	}
}

$header_search_inputs = array();
$search_input_nodes   = $xpath->query( '//form[contains(concat(" ", normalize-space(@class), " "), " woocommerce-product-search ")]//input[@name]' );

if ( $search_input_nodes ) {
	foreach ( $search_input_nodes as $search_input_node ) {
		$header_search_inputs[] = array(
			'name'  => $search_input_node->getAttribute( 'name' ),
			'type'  => $search_input_node->getAttribute( 'type' ),
			'value' => $search_input_node->getAttribute( 'value' ),
		);
	}
}

$product_titles = array();
$title_nodes     = $xpath->query( '//*[contains(concat(" ", normalize-space(@class), " "), " woocommerce-loop-product__title ")]' );

if ( $title_nodes ) {
	foreach ( $title_nodes as $title_node ) {
		$product_titles[] = trim( $title_node->nodeValue );
	}
}

$result = array(
	'query'                 => $request,
	'found_posts'           => (int) $wp_query->found_posts,
	'post_count'            => (int) $wp_query->post_count,
	'max_num_pages'         => (int) $wp_query->max_num_pages,
	'query_orderby'         => $wp_query->get( 'orderby' ),
	'query_order'           => $wp_query->get( 'order' ),
	'is_search'             => $wp_query->is_search(),
	'is_product_archive'    => $wp_query->is_post_type_archive( 'product' ),
	'is_404'                => $wp_query->is_404(),
	'body_classes'          => get_body_class(),
	'h1_count'              => $count_nodes( '//h1' ),
	'h1_text'               => $first_value( '//h1' ),
	'breadcrumb_count'      => $count_nodes( '//*[contains(concat(" ", normalize-space(@class), " "), " woocommerce-breadcrumb ")]' ),
	'breadcrumb_text'       => $first_value( '//*[contains(concat(" ", normalize-space(@class), " "), " woocommerce-breadcrumb ")]' ),
	'product_count'         => $count_nodes( '//ul[contains(concat(" ", normalize-space(@class), " "), " products ")]/li[contains(concat(" ", normalize-space(@class), " "), " product ")]' ),
	'sorting_wrappers'      => $count_nodes( '//*[contains(concat(" ", normalize-space(@class), " "), " storefront-sorting ")]' ),
	'ordering_forms'        => $count_nodes( '//form[contains(concat(" ", normalize-space(@class), " "), " woocommerce-ordering ")]' ),
	'ordering_inputs'       => $ordering_inputs,
	'result_counts'         => $count_nodes( '//*[contains(concat(" ", normalize-space(@class), " "), " woocommerce-result-count ")]' ),
	'pagination_navs'       => $count_nodes( '//nav[contains(concat(" ", normalize-space(@class), " "), " woocommerce-pagination ")]' ),
	'pagination_links'      => $attribute_values( '//nav[contains(concat(" ", normalize-space(@class), " "), " woocommerce-pagination ")]//a', 'href' ),
	'catalog_styles'        => $count_nodes( '//link[@id="dentall-catalog-css"]' ),
	'product_titles'        => $product_titles,
	'empty_statuses'        => $count_nodes( '//*[@role="status"]' ),
	'empty_actions'         => $count_nodes( '//nav[contains(concat(" ", normalize-space(@class), " "), " dentall-search-empty-actions ")]' ),
	'empty_action_hrefs'    => $attribute_values( '//nav[contains(concat(" ", normalize-space(@class), " "), " dentall-search-empty-actions ")]//a', 'href' ),
	'injected_markup'       => $count_nodes( '//h1//script | //*[contains(concat(" ", normalize-space(@class), " "), " woocommerce-breadcrumb ")]//script' ),
	'robots'                => $attribute_values( '//meta[@name="robots"]', 'content' ),
	'canonical'             => $attribute_values( '//link[@rel="canonical"]', 'href' ),
	'rel_next'              => $attribute_values( '//link[@rel="next"]', 'href' ),
	'rel_prev'              => $attribute_values( '//link[@rel="prev"]', 'href' ),
	'header_search_forms'   => $count_nodes( '//form[contains(concat(" ", normalize-space(@class), " "), " woocommerce-product-search ")]' ),
	'header_search_methods' => $attribute_values( '//form[contains(concat(" ", normalize-space(@class), " "), " woocommerce-product-search ")]', 'method' ),
	'header_search_actions' => $attribute_values( '//form[contains(concat(" ", normalize-space(@class), " "), " woocommerce-product-search ")]', 'action' ),
	'header_search_inputs'  => $header_search_inputs,
	'duplicate_ids'         => $duplicates,
);

libxml_clear_errors();

WP_CLI::line( wp_json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
