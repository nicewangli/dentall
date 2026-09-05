<?php
/**
 * D52品牌数据、权限与URL白名单只读审计。
 *
 * 运行：php -d mysqli.default_port=<Local端口> C:/wp-cli/wp-cli.phar eval-file
 * project-docs/tests/day52-brand-audit.php --path=app/public
 *
 * 本脚本不创建、修改或删除品牌、商品、角色、选项或缓存。
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

$site_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

if ( 'local' !== wp_get_environment_type() || 'dentall.local' !== $site_host ) {
	WP_CLI::error( '安全中止：本审计只允许在WP_ENVIRONMENT_TYPE=local且主机为dentall.local时运行。' );
}

if ( ! function_exists( 'dentall_catalog_filter_sanitize_query_args' ) ) {
	WP_CLI::error( '未加载DentAll目录筛选模块。' );
}

$checks   = array();
$taxonomy = get_taxonomy( 'product_brand' );
$manager  = get_role( DENTALL_WEBSITE_MANAGER_ROLE );
$editor   = get_role( DENTALL_CONTENT_ROLE );

$checks['woocommerce_native_brand_taxonomy'] = $taxonomy
	&& $taxonomy->public
	&& $taxonomy->hierarchical
	&& $taxonomy->show_ui
	&& $taxonomy->show_in_rest
	&& 'manage_product_terms' === $taxonomy->cap->manage_terms
	&& 'assign_product_terms' === $taxonomy->cap->assign_terms;

$checks['website_manager_can_manage_and_assign_brands'] = $manager
	&& $manager->has_cap( 'manage_product_terms' )
	&& $manager->has_cap( 'edit_product_terms' )
	&& $manager->has_cap( 'delete_product_terms' )
	&& $manager->has_cap( 'assign_product_terms' );

$checks['content_editor_can_only_assign_existing_brands'] = $editor
	&& $editor->has_cap( 'assign_product_terms' )
	&& ! $editor->has_cap( 'manage_product_terms' )
	&& ! $editor->has_cap( 'edit_product_terms' )
	&& ! $editor->has_cap( 'delete_product_terms' );

$yoast_titles = get_option( 'wpseo_titles', array() );
$checks['brand_archives_are_noindex_in_local'] = is_array( $yoast_titles )
	&& true === ( $yoast_titles['noindex-tax-product_brand'] ?? false );
$checks['brand_permalink_uses_woo_default_base'] = '' === (string) get_option( 'woocommerce_brand_permalink', '' );

if ( ! class_exists( 'WC_Brands_Admin' ) ) {
	require_once WC_ABSPATH . 'includes/admin/class-wc-admin-brands.php';
}

$brand_label        = __( 'Brands', 'woocommerce' );
$csv_export_columns = apply_filters( 'woocommerce_product_export_column_names', array() );
$csv_import_columns = apply_filters( 'woocommerce_csv_product_import_mapping_default_columns', array() );
$checks['woocommerce_native_brand_csv_contract'] = $brand_label === ( $csv_export_columns['brand_ids'] ?? '' )
	&& 'brand_ids' === ( $csv_import_columns[ $brand_label ] ?? '' );

$terms = get_terms(
	array(
		'taxonomy'   => 'product_brand',
		'hide_empty' => false,
	)
);

if ( is_wp_error( $terms ) ) {
	WP_CLI::error( '读取product_brand词项失败：' . $terms->get_error_message() );
}

$filterable_terms = get_terms(
	array(
		'taxonomy'   => 'product_brand',
		'hide_empty' => true,
	)
);

if ( is_wp_error( $filterable_terms ) ) {
	WP_CLI::error( '读取可筛选product_brand词项失败：' . $filterable_terms->get_error_message() );
}

$term_ids       = array_map( 'absint', wp_list_pluck( $terms, 'term_id' ) );
$filterable_ids = array_map( 'absint', wp_list_pluck( $filterable_terms, 'term_id' ) );
$requested_ids  = array_reverse( $filterable_ids );
$requested_ids  = $requested_ids ? array_merge( $requested_ids, array( reset( $requested_ids ) ) ) : array();
$sanitized_args = dentall_catalog_filter_sanitize_query_args(
	array(
		'filter_product_brand' => implode( ',', $requested_ids ),
		'filtering'            => '1',
		'unknown'              => 'drop-me',
	)
);
$expected_ids = $filterable_ids;
sort( $expected_ids, SORT_NUMERIC );

$checks['valid_brand_ids_are_deduplicated_and_sorted'] = $expected_ids
	? implode( ',', $expected_ids ) === $sanitized_args['filter_product_brand']
	: ! isset( $sanitized_args['filter_product_brand'] );
$checks['helper_and_unknown_args_are_not_propagated'] = ! isset( $sanitized_args['filtering'], $sanitized_args['unknown'] );
$checks['unknown_or_malformed_brand_ids_are_dropped'] = array() === dentall_catalog_filter_sanitize_query_args(
	array( 'filter_product_brand' => '999999999' )
)
	&& array() === dentall_catalog_filter_sanitize_query_args(
		array( 'filter_product_brand' => '1,invalid' )
	);
$empty_brand_ids = array_values( array_diff( $term_ids, $filterable_ids ) );
$checks['empty_brand_ids_are_not_filterable'] = ! $empty_brand_ids
	|| array() === dentall_catalog_filter_sanitize_query_args(
		array( 'filter_product_brand' => implode( ',', $empty_brand_ids ) )
	);

$selected_markup = dentall_catalog_filter_brand_markup(
	'<li class="wc-layered-nav-term chosen"><a href="https://dentall.local/shop/">Example &amp; Brand</a></li>'
);
$checks['selected_brand_link_has_accessible_remove_state'] = false !== strpos( $selected_markup, 'rel="nofollow"' )
	&& false !== strpos( $selected_markup, 'aria-current="true"' )
	&& false !== strpos( $selected_markup, 'aria-label="Example &amp; Brand selected; activate to remove."' );

$original_get = $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$had_wp_query = isset( $GLOBALS['wp_query'] );
$original_wp_query = $had_wp_query ? $GLOBALS['wp_query'] : null;
$had_wp_the_query = isset( $GLOBALS['wp_the_query'] );
$original_wp_the_query = $had_wp_the_query ? $GLOBALS['wp_the_query'] : null;
$shop_query                       = new WP_Query();
$shop_query->is_archive           = true;
$shop_query->is_post_type_archive = true;
$shop_query->query_vars['post_type'] = 'product';
$GLOBALS['wp_query']              = $shop_query;
$GLOBALS['wp_the_query']          = $shop_query;
$query_cases  = array(
	'array'     => array( reset( $expected_ids ) ?: 1 ),
	'malformed' => ( reset( $expected_ids ) ?: 1 ) . ',invalid',
);

$checks['brand_query_guard_runs_before_woocommerce'] = 1 === has_action(
	'pre_get_posts',
	'dentall_catalog_filter_prepare_query_args'
);
$checks['malformed_brand_queries_are_safe_and_remain_noindexable'] = true;

foreach ( $query_cases as $query_value ) {
	$_GET     = array( 'filter_product_brand' => $query_value ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	dentall_catalog_filter_prepare_query_args( $shop_query );
	$tax_query = apply_filters( 'woocommerce_product_query_tax_query', array(), WC()->query );
	$robots    = apply_filters(
		'wp_robots',
		array(
			'index'    => true,
			'nofollow' => true,
		)
	);
	$brand_tax_queries = array_filter(
		$tax_query,
		static function ( $query ) {
			return is_array( $query ) && 'product_brand' === ( $query['taxonomy'] ?? '' );
		}
	);

	if (
		! is_shop()
		|| '' !== $_GET['filter_product_brand'] // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		|| $brand_tax_queries
		|| empty( $robots['noindex'] )
		|| empty( $robots['follow'] )
		|| isset( $robots['index'], $robots['nofollow'] )
	) {
		$checks['malformed_brand_queries_are_safe_and_remain_noindexable'] = false;
		break;
	}
}

if ( $had_wp_query ) {
	$GLOBALS['wp_query'] = $original_wp_query;
} else {
	unset( $GLOBALS['wp_query'] );
}
if ( $had_wp_the_query ) {
	$GLOBALS['wp_the_query'] = $original_wp_the_query;
} else {
	unset( $GLOBALS['wp_the_query'] );
}
$_GET = $original_get; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

if ( $expected_ids ) {
	$non_catalog_query              = new WP_Query();
	$GLOBALS['wp_query']            = $non_catalog_query;
	$GLOBALS['wp_the_query']        = $non_catalog_query;
	$_GET = array( // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		'filter_product_brand' => implode( ',', $expected_ids ),
	);
	dentall_catalog_filter_prepare_query_args( $non_catalog_query );
	$non_catalog_tax_query = apply_filters( 'woocommerce_product_query_tax_query', array(), WC()->query );
	$non_catalog_brand_queries = array_filter(
		$non_catalog_tax_query,
		static function ( $query ) {
			return is_array( $query ) && 'product_brand' === ( $query['taxonomy'] ?? '' );
		}
	);
	$checks['valid_brand_query_is_ignored_outside_catalog'] = '' === $_GET['filter_product_brand'] // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		&& ! $non_catalog_brand_queries;

	/* WP-CLI没有主查询；复用显式Shop归档上下文验证完整Hook顺序。 */
	$GLOBALS['wp_query']              = $shop_query;
	$GLOBALS['wp_the_query']          = $shop_query;
	$_GET = array( // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		'filter_product_brand' => implode( ',', array_merge( array_reverse( $expected_ids ), array( reset( $expected_ids ) ) ) ),
	);
	dentall_catalog_filter_prepare_query_args( $shop_query );
	$valid_tax_query = apply_filters( 'woocommerce_product_query_tax_query', array(), WC()->query );
	$valid_brand_tax_queries = array_values(
		array_filter(
			$valid_tax_query,
			static function ( $query ) {
				return is_array( $query ) && 'product_brand' === ( $query['taxonomy'] ?? '' );
			}
		)
	);
	$checks['valid_brand_query_reaches_woocommerce_on_shop_canonicalized'] = is_shop()
		&& implode( ',', $expected_ids ) === $_GET['filter_product_brand'] // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		&& 1 === count( $valid_brand_tax_queries )
		&& $expected_ids === array_values( $valid_brand_tax_queries[0]['terms'] );
	if ( $had_wp_query ) {
		$GLOBALS['wp_query'] = $original_wp_query;
	} else {
		unset( $GLOBALS['wp_query'] );
	}
	if ( $had_wp_the_query ) {
		$GLOBALS['wp_the_query'] = $original_wp_the_query;
	} else {
		unset( $GLOBALS['wp_the_query'] );
	}
	$_GET = $original_get; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	$raw_link = add_query_arg(
		array(
			'filtering'            => '1',
			'filter_product_brand' => implode( ',', array_reverse( $expected_ids ) ),
			'unknown'              => 'drop-me',
		),
		wc_get_page_permalink( 'shop' )
	);
	$clean_link_args = array();
	wp_parse_str( (string) wp_parse_url( dentall_catalog_filter_brand_link( $raw_link ), PHP_URL_QUERY ), $clean_link_args );
	$checks['brand_widget_links_use_only_whitelisted_args'] = array(
		'filter_product_brand' => implode( ',', $expected_ids ),
	) === $clean_link_args;
} else {
	$checks['valid_brand_query_is_ignored_outside_catalog'] = true;
	$checks['valid_brand_query_reaches_woocommerce_on_shop_canonicalized'] = true;
	$checks['brand_widget_links_use_only_whitelisted_args'] = true;
}

$published_products = wc_get_products(
	array(
		'status' => 'publish',
		'limit'  => -1,
		'return' => 'ids',
	)
);
$brand_assignments = array();
$checks['native_brand_csv_export_matches_assignments'] = true;

foreach ( $published_products as $product_id ) {
	$brand_assignments[ $product_id ] = wp_get_post_terms(
		$product_id,
		'product_brand',
		array( 'fields' => 'ids' )
	);
	$csv_brand_value = apply_filters(
		'woocommerce_product_export_product_column_brand_ids',
		'',
		wc_get_product( $product_id )
	);

	if ( is_wp_error( $brand_assignments[ $product_id ] ) || (bool) $brand_assignments[ $product_id ] !== ( '' !== $csv_brand_value ) ) {
		$checks['native_brand_csv_export_matches_assignments'] = false;
	}
}

$checks['published_products_have_at_most_one_primary_brand'] = ! array_filter(
	$brand_assignments,
	static function ( $ids ) {
		return is_wp_error( $ids ) || count( $ids ) > 1;
	}
);
$checks['brand_terms_are_flat'] = ! array_filter(
	$terms,
	static function ( $term ) {
		return 0 !== (int) $term->parent;
	}
);

WP_CLI::line(
	sprintf(
		"INFO\tbrands=%d published_products=%d assigned_products=%d",
		count( $terms ),
		count( $published_products ),
		count( array_filter( $brand_assignments ) )
	)
);

foreach ( $checks as $name => $passed ) {
	WP_CLI::line( sprintf( "%s\t%s", $passed ? 'PASS' : 'FAIL', $name ) );
}

if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( 'D52品牌只读审计未通过。' );
}

WP_CLI::success( 'D52品牌只读审计通过。' );
