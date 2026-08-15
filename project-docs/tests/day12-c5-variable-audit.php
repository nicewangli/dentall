<?php
/**
 * D12 C5 Variable Product持久化审计脚本。
 *
 * 运行：wp eval-file project-docs/tests/day12-c5-variable-audit.php --path=app/public
 */

$parent_id = wc_get_product_id_by_sku( 'TEST-D12-VARIABLE-001' );
$product   = wc_get_product( $parent_id );

if ( ! $product instanceof WC_Product_Variable
	|| 'TEST D12 Variable Size Shade' !== $product->get_name()
) {
	WP_CLI::error( '未找到预期的D12 C5 Variable Product。' );
}

$attributes = $product->get_attributes();
$data_store = WC_Data_Store::load( 'product' );
$category   = get_term( 18, 'product_cat' );
$size_slugs = isset( $attributes['pa_size'] )
	? get_terms(
		array(
			'taxonomy'   => 'pa_size',
			'include'    => $attributes['pa_size']->get_options(),
			'hide_empty' => false,
			'fields'     => 'slugs',
		)
	)
	: array();
$shade_slugs = isset( $attributes['pa_shade'] )
	? get_terms(
		array(
			'taxonomy'   => 'pa_shade',
			'include'    => $attributes['pa_shade']->get_options(),
			'hide_empty' => false,
			'fields'     => 'slugs',
		)
	)
	: array();

if ( is_wp_error( $size_slugs ) || is_wp_error( $shade_slugs ) ) {
	WP_CLI::error( '无法读取D12 C5全局属性选项。' );
}

sort( $size_slugs );
sort( $shade_slugs );
$matrix     = array(
	'small_light'  => array( 'attribute_pa_size' => 'small-98-mm', 'attribute_pa_shade' => 'light' ),
	'small_medium' => array( 'attribute_pa_size' => 'small-98-mm', 'attribute_pa_shade' => 'medium' ),
	'large_light'  => array( 'attribute_pa_size' => 'large-105-mm', 'attribute_pa_shade' => 'light' ),
	'large_medium' => array( 'attribute_pa_size' => 'large-105-mm', 'attribute_pa_shade' => 'medium' ),
);
$variation_ids = array();

foreach ( $matrix as $key => $match_attributes ) {
	$variation_ids[ $key ] = (int) $data_store->find_matching_product_variation( $product, $match_attributes );
}

$small_light  = wc_get_product( $variation_ids['small_light'] );
$small_medium = wc_get_product( $variation_ids['small_medium'] );
$large_light  = wc_get_product( $variation_ids['large_light'] );
$image_ids    = array(
	'parent'       => (int) $product->get_image_id(),
	'small_light'  => $small_light instanceof WC_Product_Variation ? (int) $small_light->get_image_id( 'edit' ) : 0,
	'small_medium' => $small_medium instanceof WC_Product_Variation ? (int) $small_medium->get_image_id( 'edit' ) : 0,
	'large_light'  => $large_light instanceof WC_Product_Variation ? (int) $large_light->get_image_id( 'edit' ) : 0,
);
$expected_alts = array(
	'parent'       => 'TEST variable dental product bottle, front view',
	'small_light'  => 'TEST variable dental product bottle with a warm tint',
	'small_medium' => 'TEST variable dental product bottle with a green tint',
	'large_light'  => 'TEST variable dental product bottle with a purple tint',
);
$images_valid = true;

foreach ( $image_ids as $key => $image_id ) {
	$path     = get_attached_file( $image_id );
	$metadata = wp_get_attachment_metadata( $image_id );
	$size     = is_string( $path ) && is_file( $path ) ? filesize( $path ) : false;

	$images_valid = $images_valid
		&& 0 < $image_id
		&& 'image/webp' === get_post_mime_type( $image_id )
		&& is_array( $metadata )
		&& 1254 === (int) ( $metadata['width'] ?? 0 )
		&& 1254 === (int) ( $metadata['height'] ?? 0 )
		&& false !== $size
		&& 0 < $size
		&& 5 * MB_IN_BYTES >= $size
		&& $expected_alts[ $key ] === get_post_meta( $image_id, '_wp_attachment_image_alt', true );
}

$checks = array(
	'parent_status_publish'       => 'publish' === $product->get_status(),
	'parent_category'             => in_array( 18, $product->get_category_ids(), true )
		&& $category instanceof WP_Term
		&& 'TEST D12 Products' === $category->name,
	'parent_no_stock_quantity'    => ! $product->get_manage_stock(),
	'parent_shipping'             => '2' === $product->get_weight()
		&& '8' === $product->get_length()
		&& '8' === $product->get_width()
		&& '3' === $product->get_height(),
	'parent_price_range'          => '39.99' === $product->get_variation_price( 'min', false )
		&& '49.99' === $product->get_variation_price( 'max', false ),
	'size_attribute'              => isset( $attributes['pa_size'] )
		&& $attributes['pa_size']->get_variation()
		&& $attributes['pa_size']->get_visible()
		&& array( 'large-105-mm', 'small-98-mm' ) === $size_slugs,
	'shade_attribute'             => isset( $attributes['pa_shade'] )
		&& $attributes['pa_shade']->get_variation()
		&& $attributes['pa_shade']->get_visible()
		&& array( 'light', 'medium' ) === $shade_slugs,
	'three_variations_only'       => 3 === count( $product->get_children() ),
	'three_legal_combinations'    => 0 < $variation_ids['small_light']
		&& 0 < $variation_ids['small_medium']
		&& 0 < $variation_ids['large_light'],
	'illegal_combination_missing' => 0 === $variation_ids['large_medium'],
	'small_light_data'            => $small_light instanceof WC_Product_Variation
		&& 'TEST-D12-VAR-SM-LT' === $small_light->get_sku()
		&& 'publish' === $small_light->get_status()
		&& $small_light->get_manage_stock()
		&& 'no' === $small_light->get_backorders()
		&& '39.99' === $small_light->get_regular_price()
		&& '39.99' === $small_light->get_price()
		&& 5 === $small_light->get_stock_quantity()
		&& 'instock' === $small_light->get_stock_status(),
	'small_medium_data'           => $small_medium instanceof WC_Product_Variation
		&& 'TEST-D12-VAR-SM-MD' === $small_medium->get_sku()
		&& 'publish' === $small_medium->get_status()
		&& $small_medium->get_manage_stock()
		&& 'no' === $small_medium->get_backorders()
		&& '39.99' === $small_medium->get_regular_price()
		&& '39.99' === $small_medium->get_price()
		&& 0 === $small_medium->get_stock_quantity()
		&& 'outofstock' === $small_medium->get_stock_status(),
	'large_light_data'            => $large_light instanceof WC_Product_Variation
		&& 'TEST-D12-VAR-LG-LT' === $large_light->get_sku()
		&& 'publish' === $large_light->get_status()
		&& $large_light->get_manage_stock()
		&& 'no' === $large_light->get_backorders()
		&& '49.99' === $large_light->get_regular_price()
		&& '49.99' === $large_light->get_price()
		&& 3 === $large_light->get_stock_quantity()
		&& 'instock' === $large_light->get_stock_status(),
	'inherited_shipping_raw'      => $small_light instanceof WC_Product_Variation
		&& '' === $small_light->get_weight( 'edit' )
		&& '' === $small_light->get_length( 'edit' )
		&& '' === $small_light->get_width( 'edit' )
		&& '' === $small_light->get_height( 'edit' )
		&& '' === $small_medium->get_weight( 'edit' )
		&& '' === $small_medium->get_length( 'edit' )
		&& '' === $small_medium->get_width( 'edit' )
		&& '' === $small_medium->get_height( 'edit' ),
	'inherited_shipping_effective' => $small_light instanceof WC_Product_Variation
		&& $small_medium instanceof WC_Product_Variation
		&& '2' === $small_light->get_weight()
		&& '8' === $small_light->get_length()
		&& '8' === $small_light->get_width()
		&& '3' === $small_light->get_height()
		&& '2' === $small_medium->get_weight()
		&& '8' === $small_medium->get_length()
		&& '8' === $small_medium->get_width()
		&& '3' === $small_medium->get_height(),
	'large_shipping_override'     => $large_light instanceof WC_Product_Variation
		&& '2.5' === $large_light->get_weight( 'edit' )
		&& '9' === $large_light->get_length( 'edit' )
		&& '9' === $large_light->get_width( 'edit' )
		&& '4' === $large_light->get_height( 'edit' ),
	'variation_images'            => $images_valid,
);

foreach ( $checks as $name => $passed ) {
	WP_CLI::log( sprintf( '%s=%s', $name, $passed ? 'PASS' : 'FAIL' ) );
}

if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( 'D12 C5 Variable Product审计未通过。' );
}

WP_CLI::success( sprintf( 'D12 C5 Variable Product审计通过，父商品ID：%d。', $parent_id ) );
