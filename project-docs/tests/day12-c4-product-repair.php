<?php
/**
 * D12 C4 TEST商品中断恢复脚本。
 *
 * 仅用于恢复浏览器中断前尚未提交的全局属性和商品主图。
 * 运行：wp eval-file project-docs/tests/day12-c4-product-repair.php --path=app/public
 */

$product = wc_get_product( 44 );

if ( ! $product instanceof WC_Product_Simple
	|| 'TEST D12 Simple Fixed Pack' !== $product->get_name()
	|| 'TEST-D12-SIMPLE-001' !== $product->get_sku()
) {
	WP_CLI::error( '安全检查失败：商品44不是预期的D12 TEST Simple Product。' );
}

$attribute_taxonomy = 'pa_package-quantity';
$attribute_id       = wc_attribute_taxonomy_id_by_name( $attribute_taxonomy );
$term               = get_term( 19, $attribute_taxonomy );
$image              = get_post( 45 );

if ( 0 >= $attribute_id
	|| ! $term instanceof WP_Term
	|| '20 pcs' !== $term->name
	|| '20-pcs' !== $term->slug
	|| ! $image instanceof WP_Post
	|| 'attachment' !== $image->post_type
	|| 'test-simple-product-front' !== $image->post_title
	|| 'image/webp' !== get_post_mime_type( $image->ID )
) {
	WP_CLI::error( '安全检查失败：预期的属性、术语或图片附件不存在。' );
}

$attributes                  = $product->get_attributes();
$package_quantity            = new WC_Product_Attribute();
$package_quantity->set_id( $attribute_id );
$package_quantity->set_name( $attribute_taxonomy );
$package_quantity->set_options( array( $term->term_id ) );
$package_quantity->set_position( count( $attributes ) );
$package_quantity->set_visible( true );
$package_quantity->set_variation( false );
$attributes[ $attribute_taxonomy ] = $package_quantity;

$product->set_attributes( $attributes );
$product->set_image_id( $image->ID );
$product->save();

WP_CLI::success( '已恢复商品44的Package Quantity属性与商品主图。' );
