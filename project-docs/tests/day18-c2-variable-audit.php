<?php
/**
 * D18 C2 Variable父子职责补充审计脚本。
 *
 * 本脚本只通过WooCommerce CRUD读取既有TEST夹具，不保存商品或修改库存。
 *
 * 运行：wp eval-file project-docs/tests/day18-c2-variable-audit.php --path=app/public
 */

$parent_id = wc_get_product_id_by_sku( 'TEST-D12-VARIABLE-001' );
$product   = wc_get_product( $parent_id );

if ( ! $product instanceof WC_Product_Variable ) {
	WP_CLI::error( '未找到预期的D12 Variable TEST父商品。' );
}

$variation_ids = array_map( 'intval', $product->get_children() );
$variations     = array_filter( array_map( 'wc_get_product', $variation_ids ) );
$child_skus     = array_map(
	static function ( $variation ) {
		return $variation instanceof WC_Product_Variation ? $variation->get_sku() : '';
	},
	$variations
);
$parent_ids     = array_map(
	static function ( $variation ) {
		return $variation instanceof WC_Product_Variation ? $variation->get_parent_id() : 0;
	},
	$variations
);

$checks = array(
	'parent_sku'               => 'TEST-D12-VARIABLE-001' === $product->get_sku(),
	'parent_not_manage_stock'  => ! $product->get_manage_stock(),
	'default_attributes_empty' => array() === $product->get_default_attributes(),
	'three_children'           => 3 === count( $variation_ids )
		&& 3 === count( $variations ),
	'child_ids_unique'         => 3 === count( array_unique( $variation_ids ) ),
	'child_skus_present'       => ! in_array( '', $child_skus, true ),
	'child_skus_unique'        => 3 === count( array_unique( $child_skus ) ),
	'children_share_parent'    => array( $parent_id ) === array_values( array_unique( $parent_ids ) ),
);

foreach ( $checks as $name => $passed ) {
	WP_CLI::log( sprintf( '%s=%s', $name, $passed ? 'PASS' : 'FAIL' ) );
}

if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( 'D18 C2 Variable父子职责补充审计未通过。' );
}

WP_CLI::success( sprintf( 'D18 C2补充审计通过，父商品ID：%d。', $parent_id ) );
