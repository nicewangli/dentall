<?php
/**
 * D53 30个有效品牌Local TEST夹具。
 *
 * 创建：wp eval-file project-docs/tests/day53-brand-fixture.php setup --path=app/public
 * 清理：wp eval-file project-docs/tests/day53-brand-fixture.php cleanup --path=app/public
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

$site_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

if ( 'local' !== wp_get_environment_type() || 'dentall.local' !== $site_host ) {
	WP_CLI::error( '安全中止：本夹具只允许在WP_ENVIRONMENT_TYPE=local且主机为dentall.local时运行。' );
}

if ( ! taxonomy_exists( 'product_brand' ) ) {
	WP_CLI::error( 'product_brand分类法未加载。' );
}

$mode = isset( $args[0] ) ? sanitize_key( $args[0] ) : '';

if ( ! in_array( $mode, array( 'setup', 'cleanup' ), true ) ) {
	WP_CLI::error( '必须明确传入setup或cleanup。' );
}

const DENTALL_D53_FIXTURE_META = '_dentall_day53_fixture';

/** 清除与本夹具结果有关的WooCommerce瞬态缓存。 */
function dentall_d53_clear_filter_caches() {
	wc_delete_product_transients();

	foreach ( array( 'product_brand', 'pa_size', 'pa_shade' ) as $taxonomy ) {
		delete_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ) );
	}
}

/** 返回固定品牌slug。 */
function dentall_d53_brand_slug( $number ) {
	return sprintf( 'test-d53-brand-%02d', $number );
}

/** 返回固定品牌名；第30项覆盖长文本与HTML特殊字符。 */
function dentall_d53_brand_name( $number ) {
	return 30 === $number
		? 'TEST D53 Brand 30 – Extra Long & "Quoted" Name'
		: sprintf( 'TEST D53 Brand %02d', $number );
}

/** 返回已存在且带本夹具标记的品牌，或在不安全冲突时中止。 */
function dentall_d53_fixture_brand( $number ) {
	$term = get_term_by( 'slug', dentall_d53_brand_slug( $number ), 'product_brand' );

	if ( ! $term instanceof WP_Term ) {
		return null;
	}

	if ( '1' !== get_term_meta( $term->term_id, DENTALL_D53_FIXTURE_META, true ) ) {
		WP_CLI::error( sprintf( '品牌slug %s已被非D53数据占用。', $term->slug ) );
	}

	return $term;
}

/** 返回固定TEST商品，或在SKU冲突时中止。 */
function dentall_d53_fixture_product( $number ) {
	$product_id = wc_get_product_id_by_sku( sprintf( 'TEST-D53-BRAND-%02d', $number ) );

	if ( ! $product_id ) {
		return null;
	}

	if ( '1' !== get_post_meta( $product_id, DENTALL_D53_FIXTURE_META, true ) ) {
		WP_CLI::error( sprintf( 'TEST-D53-BRAND-%02d已被非D53商品占用。', $number ) );
	}

	$product = wc_get_product( $product_id );

	if ( ! $product instanceof WC_Product_Simple ) {
		WP_CLI::error( sprintf( 'D53夹具商品%d类型异常。', $product_id ) );
	}

	return $product;
}

/** 验证复用的D12 TEST商品未被真实品牌占用。 */
function dentall_d53_reference_products() {
	$expected = array(
		44 => 'TEST-D12-SIMPLE-001',
		46 => 'TEST-D12-VARIABLE-001',
	);
	$products = array();

	foreach ( $expected as $product_id => $sku ) {
		$product = wc_get_product( $product_id );

		if ( ! $product instanceof WC_Product || $sku !== $product->get_sku() || 'publish' !== $product->get_status() ) {
			WP_CLI::error( sprintf( 'D53依赖的D12 TEST商品%d不存在或已变化。', $product_id ) );
		}

		$brand_ids = wp_get_post_terms( $product_id, 'product_brand', array( 'fields' => 'ids' ) );

		if ( is_wp_error( $brand_ids ) ) {
			WP_CLI::error( $brand_ids->get_error_message() );
		}

		foreach ( $brand_ids as $brand_id ) {
			if ( '1' !== get_term_meta( $brand_id, DENTALL_D53_FIXTURE_META, true ) ) {
				WP_CLI::error( sprintf( 'D12 TEST商品%d已有非D53品牌，拒绝覆盖。', $product_id ) );
			}
		}

		$products[ $product_id ] = $product;
	}

	return $products;
}

$reference_products = dentall_d53_reference_products();
$fixture_brands     = array();
$fixture_products   = array();

/* 删除前先完成全部所有权检查，避免清到一半才发现冲突。 */
for ( $number = 1; $number <= 30; $number++ ) {
	$fixture_brands[ $number ] = dentall_d53_fixture_brand( $number );

	if ( $number >= 3 ) {
		$fixture_products[ $number ] = dentall_d53_fixture_product( $number );
	}
}

if ( 'cleanup' === $mode ) {
	$allowed_object_ids = array_map( 'absint', array_keys( $reference_products ) );

	foreach ( array_filter( $fixture_products ) as $product ) {
		$allowed_object_ids[] = $product->get_id();
	}

	foreach ( array_filter( $fixture_brands ) as $term ) {
		$object_ids = get_objects_in_term( $term->term_id, 'product_brand' );

		if ( is_wp_error( $object_ids ) ) {
			WP_CLI::error( $object_ids->get_error_message() );
		}

		$unexpected = array_diff( array_map( 'absint', $object_ids ), $allowed_object_ids );

		if ( $unexpected ) {
			WP_CLI::error(
				sprintf(
					'D53品牌%d被非夹具对象关联，拒绝清理：%s。',
					$term->term_id,
					implode( ',', $unexpected )
				)
			);
		}
	}

	foreach ( array_keys( $reference_products ) as $product_id ) {
		$fixture_brand_ids = wp_get_post_terms( $product_id, 'product_brand', array( 'fields' => 'ids' ) );
		$fixture_brand_ids = array_filter(
			is_wp_error( $fixture_brand_ids ) ? array() : $fixture_brand_ids,
			static function ( $term_id ) {
				return '1' === get_term_meta( $term_id, DENTALL_D53_FIXTURE_META, true );
			}
		);

		if ( $fixture_brand_ids ) {
			wp_remove_object_terms( $product_id, $fixture_brand_ids, 'product_brand' );
		}
	}

	for ( $number = 3; $number <= 30; $number++ ) {
		$product = $fixture_products[ $number ];

		if ( $product ) {
			$product->delete( true );
		}
	}

	for ( $number = 1; $number <= 30; $number++ ) {
		$term = $fixture_brands[ $number ];

		if ( $term ) {
			$result = wp_delete_term( $term->term_id, 'product_brand' );

			if ( is_wp_error( $result ) ) {
				WP_CLI::error( $result->get_error_message() );
			}
		}
	}

	dentall_d53_clear_filter_caches();
	WP_CLI::success( 'D53 TEST夹具已强制清理。' );
	return;
}

$category = get_term( 18, 'product_cat' );

if ( ! $category instanceof WP_Term || 'TEST D12 Products' !== $category->name ) {
	WP_CLI::error( 'TEST D12 Products分类不存在或已变化。' );
}

$brand_ids = array();

for ( $number = 1; $number <= 30; $number++ ) {
	$term = $fixture_brands[ $number ];

	if ( ! $term ) {
		$result = wp_insert_term(
			dentall_d53_brand_name( $number ),
			'product_brand',
			array( 'slug' => dentall_d53_brand_slug( $number ) )
		);

		if ( is_wp_error( $result ) ) {
			WP_CLI::error( $result->get_error_message() );
		}

		$term = get_term( $result['term_id'], 'product_brand' );
		update_term_meta( $term->term_id, DENTALL_D53_FIXTURE_META, '1' );
	} elseif ( dentall_d53_brand_name( $number ) !== $term->name ) {
		$result = wp_update_term(
			$term->term_id,
			'product_brand',
			array( 'name' => dentall_d53_brand_name( $number ) )
		);

		if ( is_wp_error( $result ) ) {
			WP_CLI::error( $result->get_error_message() );
		}
	}

	$brand_ids[ $number ] = (int) $term->term_id;
}

wp_set_object_terms( 44, array( $brand_ids[1] ), 'product_brand', false );
wp_set_object_terms( 46, array( $brand_ids[2] ), 'product_brand', false );

for ( $number = 3; $number <= 30; $number++ ) {
	$product = $fixture_products[ $number ];
	$product = $product ?: new WC_Product_Simple();
	$matrix  = array(
		3 => array( 'pa_size' => 'small-98-mm', 'pa_shade' => 'light' ),
		4 => array( 'pa_size' => 'small-98-mm', 'pa_shade' => 'medium' ),
		5 => array( 'pa_size' => 'large-105-mm', 'pa_shade' => 'light' ),
	);
	$attributes = array();

	foreach ( $matrix[ $number ] ?? array() as $taxonomy => $term_slug ) {
		$term = get_term_by( 'slug', $term_slug, $taxonomy );

		if ( ! $term instanceof WP_Term ) {
			WP_CLI::error( sprintf( 'D53矩阵依赖的%s:%s不存在。', $taxonomy, $term_slug ) );
		}

		$attribute = new WC_Product_Attribute();
		$attribute->set_id( wc_attribute_taxonomy_id_by_name( $taxonomy ) );
		$attribute->set_name( $taxonomy );
		$attribute->set_options( array( $term->term_id ) );
		$attribute->set_visible( true );
		$attribute->set_variation( false );
		$attributes[] = $attribute;
	}

	$product->set_name( sprintf( 'TEST D53 Brand Product %02d', $number ) );
	$product->set_status( 'publish' );
	$product->set_sku( sprintf( 'TEST-D53-BRAND-%02d', $number ) );
	$product->set_description( 'TEST DATA ONLY. Used to validate the Day53 brand facet at the expected first-release scale.' );
	$product->set_short_description( 'TEST DATA ONLY.' );
	$product->set_regular_price( (string) ( 100 + $number ) );
	$product->set_manage_stock( false );
	$product->set_stock_status( 4 === $number ? 'outofstock' : 'instock' );
	$product->set_catalog_visibility( 'visible' );
	$product->set_category_ids( array( 18 ) );
	$product->set_attributes( $attributes );
	$product_id = $product->save();

	update_post_meta( $product_id, DENTALL_D53_FIXTURE_META, '1' );
	wp_set_object_terms( $product_id, array( $brand_ids[ $number ] ), 'product_brand', false );

	foreach ( $matrix[ $number ] ?? array() as $taxonomy => $term_slug ) {
		wp_set_object_terms( $product_id, array( $term_slug ), $taxonomy, false );
	}
}

dentall_d53_clear_filter_caches();

$effective_brands = get_terms(
	array(
		'taxonomy'   => 'product_brand',
		'hide_empty' => true,
	)
);

WP_CLI::line( sprintf( 'INFO\tfixture_brands=%d effective_brands=%d', count( $brand_ids ), is_wp_error( $effective_brands ) ? 0 : count( $effective_brands ) ) );
WP_CLI::success( 'D53 30品牌TEST夹具已建立。' );
