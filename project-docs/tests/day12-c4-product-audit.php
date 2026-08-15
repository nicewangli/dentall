<?php
/**
 * D12 C4 简单商品持久化审计脚本。
 *
 * 运行：wp eval-file project-docs/tests/day12-c4-product-audit.php --path=app/public
 */

$product = wc_get_product( 44 );

if ( ! $product instanceof WC_Product_Simple ) {
	WP_CLI::error( '商品44不存在或不是Simple Product。' );
}

$attributes_by_name = array();

foreach ( $product->get_attributes() as $attribute ) {
	if ( $attribute instanceof WC_Product_Attribute ) {
		$attributes_by_name[ $attribute->get_name() ] = $attribute;
	}
}

$package_attribute = $attributes_by_name['pa_package-quantity'] ?? null;
$package_term      = get_term( 19, 'pa_package-quantity' );
$category_term     = get_term( 18, 'product_cat' );
$image_id          = (int) $product->get_image_id();
$image_path        = get_attached_file( $image_id );
$image_metadata    = wp_get_attachment_metadata( $image_id );
$image_size        = is_string( $image_path ) && is_file( $image_path ) ? filesize( $image_path ) : false;
$checks            = array(
	'status_publish'              => 'publish' === $product->get_status(),
	'name'                        => 'TEST D12 Simple Fixed Pack' === $product->get_name(),
	'regular_price'               => '29.99' === $product->get_regular_price(),
	'sale_price'                  => '24.99' === $product->get_sale_price(),
	'current_price'               => '24.99' === $product->get_price(),
	'sku'                         => 'TEST-D12-SIMPLE-001' === $product->get_sku(),
	'manage_stock'                => $product->get_manage_stock(),
	'stock_quantity'              => 8 === $product->get_stock_quantity(),
	'stock_status'                => 'instock' === $product->get_stock_status(),
	'weight'                      => '1.2' === $product->get_weight(),
	'length'                      => '6' === $product->get_length(),
	'width'                       => '6' === $product->get_width(),
	'height'                      => '2' === $product->get_height(),
	'test_category'               => $category_term instanceof WP_Term
		&& 'TEST D12 Products' === $category_term->name
		&& in_array( 18, $product->get_category_ids(), true ),
	'package_quantity_attribute'  => $package_attribute instanceof WC_Product_Attribute
		&& in_array( 19, $package_attribute->get_options(), true ),
	'attribute_visible'           => $package_attribute instanceof WC_Product_Attribute
		&& $package_attribute->get_visible(),
	'attribute_not_variation'     => $package_attribute instanceof WC_Product_Attribute
		&& ! $package_attribute->get_variation(),
	'package_quantity_term'       => $package_term instanceof WP_Term
		&& '20 pcs' === $package_term->name
		&& '20-pcs' === $package_term->slug,
	'featured_image'              => 45 === $image_id,
	'featured_image_alt'          => 'TEST simple fixed pack front view' === get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
	'featured_image_mime'         => 'image/webp' === get_post_mime_type( $image_id ),
	'featured_image_dimensions'   => is_array( $image_metadata )
		&& 1254 === (int) ( $image_metadata['width'] ?? 0 )
		&& 1254 === (int) ( $image_metadata['height'] ?? 0 ),
	'featured_image_file'         => false !== $image_size
		&& 0 < $image_size
		&& 5 * MB_IN_BYTES >= $image_size,
);

foreach ( $checks as $name => $passed ) {
	WP_CLI::log( sprintf( '%s=%s', $name, $passed ? 'PASS' : 'FAIL' ) );
}

if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( 'D12 C4商品持久化审计未通过。' );
}

WP_CLI::success( 'D12 C4商品持久化审计通过。' );
