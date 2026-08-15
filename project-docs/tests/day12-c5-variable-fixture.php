<?php
/**
 * D12 C5 Variable Product TEST原型生成脚本。
 *
 * 第一次运行可能只创建Size/Shade全局属性；重新运行后创建术语、媒体、父商品和3个合法Variations。
 * 运行：wp eval-file project-docs/tests/day12-c5-variable-fixture.php --path=app/public
 */

defined( 'ABSPATH' ) || WP_CLI::error( 'WordPress未加载。' );

/**
 * 查找WooCommerce全局属性ID。
 *
 * @param string $slug 属性slug。
 * @return int
 */
function dentall_d12_c5_get_attribute_id( $slug ) {
	foreach ( wc_get_attribute_taxonomies() as $attribute ) {
		if ( $slug === $attribute->attribute_name ) {
			return (int) $attribute->attribute_id;
		}
	}

	return 0;
}

/**
 * 创建或确认一个TEST属性术语。
 *
 * @param string $taxonomy 分类法。
 * @param string $name     名称。
 * @param string $slug     slug。
 * @return int
 */
function dentall_d12_c5_ensure_term( $taxonomy, $name, $slug ) {
	$existing = get_term_by( 'slug', $slug, $taxonomy );

	if ( $existing instanceof WP_Term ) {
		if ( $name !== $existing->name ) {
			WP_CLI::error( sprintf( '术语%s已存在但名称不匹配。', $slug ) );
		}

		return (int) $existing->term_id;
	}

	$result = wp_insert_term(
		$name,
		$taxonomy,
		array( 'slug' => $slug )
	);

	if ( is_wp_error( $result ) ) {
		WP_CLI::error( $result->get_error_message() );
	}

	return (int) $result['term_id'];
}

/**
 * 上传或复用一个固定TEST WebP附件。
 *
 * @param string $source_path 源文件绝对路径。
 * @param string $title       媒体Title。
 * @param string $alt         Alt Text。
 * @param int    $parent_id   父商品ID。
 * @param int    $author_id   上传者ID。
 * @return int
 */
function dentall_d12_c5_ensure_attachment( $source_path, $title, $alt, $parent_id, $author_id ) {
	$slug     = sanitize_title( pathinfo( $source_path, PATHINFO_FILENAME ) );
	$existing = get_page_by_path( $slug, OBJECT, 'attachment' );

	if ( $existing instanceof WP_Post ) {
		if ( 'image/webp' !== get_post_mime_type( $existing->ID ) ) {
			WP_CLI::error( sprintf( '附件%s存在但不是WebP。', $slug ) );
		}

		update_post_meta( $existing->ID, '_wp_attachment_image_alt', $alt );

		return (int) $existing->ID;
	}

	if ( ! is_file( $source_path ) || 5 * MB_IN_BYTES < filesize( $source_path ) ) {
		WP_CLI::error( sprintf( 'TEST图片不存在或超过5MB：%s', $source_path ) );
	}

	$image_size = getimagesize( $source_path );

	if ( false === $image_size
		|| 1254 !== (int) $image_size[0]
		|| 1254 !== (int) $image_size[1]
		|| 'image/webp' !== $image_size['mime']
	) {
		WP_CLI::error( sprintf( 'TEST图片尺寸或MIME不符合预期：%s', $source_path ) );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$temp_path = wp_tempnam( basename( $source_path ) );

	if ( ! is_string( $temp_path ) || ! copy( $source_path, $temp_path ) ) {
		WP_CLI::error( sprintf( '无法准备TEST图片临时文件：%s', $source_path ) );
	}

	$file_array = array(
		'name'     => basename( $source_path ),
		'tmp_name' => $temp_path,
	);
	$attachment_id = media_handle_sideload( $file_array, $parent_id, $title );

	if ( is_wp_error( $attachment_id ) ) {
		@unlink( $temp_path );
		WP_CLI::error( $attachment_id->get_error_message() );
	}

	wp_update_post(
		array(
			'ID'          => $attachment_id,
			'post_author' => $author_id,
		)
	);
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );

	return (int) $attachment_id;
}

/**
 * 创建或更新一个固定TEST Variation。
 *
 * @param int                  $parent_id 父商品ID。
 * @param array<string, mixed> $definition 变体定义。
 * @param int                  $image_id 变体图片ID。
 * @return int
 */
function dentall_d12_c5_upsert_variation( $parent_id, $definition, $image_id ) {
	$variation_id = wc_get_product_id_by_sku( $definition['sku'] );
	$variation    = 0 < $variation_id ? wc_get_product( $variation_id ) : new WC_Product_Variation();

	if ( ! $variation instanceof WC_Product_Variation ) {
		WP_CLI::error( sprintf( 'SKU %s 已被非Variation对象占用。', $definition['sku'] ) );
	}

	if ( 0 < $variation->get_id() && $parent_id !== $variation->get_parent_id() ) {
		WP_CLI::error( sprintf( 'SKU %s 已属于其他父商品。', $definition['sku'] ) );
	}

	$variation->set_parent_id( $parent_id );
	$variation->set_status( 'publish' );
	$variation->set_sku( $definition['sku'] );
	$variation->set_attributes( $definition['attributes'] );
	$variation->set_regular_price( $definition['price'] );
	$variation->set_manage_stock( true );
	$variation->set_stock_quantity( $definition['stock'] );
	$variation->set_backorders( 'no' );
	$variation->set_image_id( $image_id );

	if ( isset( $definition['shipping'] ) ) {
		$variation->set_weight( $definition['shipping']['weight'] );
		$variation->set_length( $definition['shipping']['length'] );
		$variation->set_width( $definition['shipping']['width'] );
		$variation->set_height( $definition['shipping']['height'] );
	} else {
		$variation->set_weight( '' );
		$variation->set_length( '' );
		$variation->set_width( '' );
		$variation->set_height( '' );
	}

	return $variation->save();
}

$manager = get_user_by( 'login', 'dentall_d12_manager' );

if ( false === $manager
	|| array( DENTALL_WEBSITE_MANAGER_ROLE ) !== $manager->roles
	|| ! user_can( $manager, DENTALL_WEBSITE_MANAGER_MARKER )
	|| user_can( $manager, 'manage_options' )
) {
	WP_CLI::error( 'Website Manager测试账号不符合预期。' );
}

wp_set_current_user( $manager->ID );

$created_attribute = false;

foreach ( array( 'size' => 'Size', 'shade' => 'Shade' ) as $slug => $name ) {
	if ( 0 === dentall_d12_c5_get_attribute_id( $slug ) ) {
		$result = wc_create_attribute(
			array(
				'name'         => $name,
				'slug'         => $slug,
				'type'         => 'select',
				'order_by'     => 'menu_order',
				'has_archives' => false,
			)
		);

		if ( is_wp_error( $result ) ) {
			WP_CLI::error( $result->get_error_message() );
		}

		$created_attribute = true;
		WP_CLI::log( sprintf( 'created_attribute=%s', $slug ) );
	}
}

if ( $created_attribute ) {
	WP_CLI::success( '全局属性已创建；请重新运行本脚本以加载新分类法。' );
	return;
}

$size_taxonomy  = 'pa_size';
$shade_taxonomy = 'pa_shade';
$size_id        = dentall_d12_c5_get_attribute_id( 'size' );
$shade_id       = dentall_d12_c5_get_attribute_id( 'shade' );
$small_id       = dentall_d12_c5_ensure_term( $size_taxonomy, 'Small 98 mm', 'small-98-mm' );
$large_id       = dentall_d12_c5_ensure_term( $size_taxonomy, 'Large 105 mm', 'large-105-mm' );
$light_id       = dentall_d12_c5_ensure_term( $shade_taxonomy, 'Light', 'light' );
$medium_id      = dentall_d12_c5_ensure_term( $shade_taxonomy, 'Medium', 'medium' );
$category       = get_term( 18, 'product_cat' );

if ( ! $category instanceof WP_Term || 'TEST D12 Products' !== $category->name ) {
	WP_CLI::error( 'TEST D12 Products分类不存在或已变化。' );
}

$parent_sku = 'TEST-D12-VARIABLE-001';
$parent_id  = wc_get_product_id_by_sku( $parent_sku );
$product    = 0 < $parent_id ? wc_get_product( $parent_id ) : new WC_Product_Variable();

if ( ! $product instanceof WC_Product_Variable ) {
	WP_CLI::error( '父SKU已被非Variable Product对象占用。' );
}

if ( 0 < $product->get_id() && 'TEST D12 Variable Size Shade' !== $product->get_name() ) {
	WP_CLI::error( '现有父商品名称不符合TEST安全约束。' );
}

$product->set_name( 'TEST D12 Variable Size Shade' );
$product->set_status( 'publish' );
$product->set_sku( $parent_sku );
$product->set_description( 'TEST DATA ONLY. Variable Product used to validate a limited Size and Shade matrix. Not a real product and not approved for production.' );
$product->set_short_description( 'TEST Variable Product with three legal Size and Shade combinations.' );
$product->set_category_ids( array( 18 ) );
$product->set_manage_stock( false );
$product->set_weight( '2' );
$product->set_length( '8' );
$product->set_width( '8' );
$product->set_height( '3' );

$size_attribute = new WC_Product_Attribute();
$size_attribute->set_id( $size_id );
$size_attribute->set_name( $size_taxonomy );
$size_attribute->set_options( array( $small_id, $large_id ) );
$size_attribute->set_position( 0 );
$size_attribute->set_visible( true );
$size_attribute->set_variation( true );

$shade_attribute = new WC_Product_Attribute();
$shade_attribute->set_id( $shade_id );
$shade_attribute->set_name( $shade_taxonomy );
$shade_attribute->set_options( array( $light_id, $medium_id ) );
$shade_attribute->set_position( 1 );
$shade_attribute->set_visible( true );
$shade_attribute->set_variation( true );

$product->set_attributes( array( $size_attribute, $shade_attribute ) );
$parent_id = $product->save();

$asset_dir = dirname( __DIR__, 2 ) . DIRECTORY_SEPARATOR . 'dentall资料' . DIRECTORY_SEPARATOR . 'TEST-D12商品原型';
$images    = array(
	'parent'       => dentall_d12_c5_ensure_attachment(
		$asset_dir . DIRECTORY_SEPARATOR . 'test-variable-product-front.webp',
		'TEST Variable Product – Front View',
		'TEST variable dental product bottle, front view',
		$parent_id,
		$manager->ID
	),
	'small_light'  => dentall_d12_c5_ensure_attachment(
		$asset_dir . DIRECTORY_SEPARATOR . 'test-variable-product-small-light-front.webp',
		'TEST Variable Product – Small / Light',
		'TEST variable dental product bottle with a warm tint',
		$parent_id,
		$manager->ID
	),
	'small_medium' => dentall_d12_c5_ensure_attachment(
		$asset_dir . DIRECTORY_SEPARATOR . 'test-variable-product-small-medium-front.webp',
		'TEST Variable Product – Small / Medium',
		'TEST variable dental product bottle with a green tint',
		$parent_id,
		$manager->ID
	),
	'large_light'  => dentall_d12_c5_ensure_attachment(
		$asset_dir . DIRECTORY_SEPARATOR . 'test-variable-product-large-light-front.webp',
		'TEST Variable Product – Large / Light',
		'TEST variable dental product bottle with a purple tint',
		$parent_id,
		$manager->ID
	),
);

$product->set_image_id( $images['parent'] );
$product->save();

$definitions = array(
	array(
		'sku'        => 'TEST-D12-VAR-SM-LT',
		'attributes' => array( $size_taxonomy => 'small-98-mm', $shade_taxonomy => 'light' ),
		'price'      => '39.99',
		'stock'      => 5,
		'image'      => 'small_light',
	),
	array(
		'sku'        => 'TEST-D12-VAR-SM-MD',
		'attributes' => array( $size_taxonomy => 'small-98-mm', $shade_taxonomy => 'medium' ),
		'price'      => '39.99',
		'stock'      => 0,
		'image'      => 'small_medium',
	),
	array(
		'sku'        => 'TEST-D12-VAR-LG-LT',
		'attributes' => array( $size_taxonomy => 'large-105-mm', $shade_taxonomy => 'light' ),
		'price'      => '49.99',
		'stock'      => 3,
		'image'      => 'large_light',
		'shipping'   => array( 'weight' => '2.5', 'length' => '9', 'width' => '9', 'height' => '4' ),
	),
);

$expected_skus = wp_list_pluck( $definitions, 'sku' );

foreach ( $product->get_children() as $child_id ) {
	$child = wc_get_product( $child_id );

	if ( $child instanceof WC_Product_Variation && ! in_array( $child->get_sku(), $expected_skus, true ) ) {
		WP_CLI::error( sprintf( '父商品存在非预期Variation：%s。', $child->get_sku() ) );
	}
}

foreach ( $definitions as $definition ) {
	$variation_id = dentall_d12_c5_upsert_variation( $parent_id, $definition, $images[ $definition['image'] ] );
	WP_CLI::log( sprintf( '%s=%d', $definition['sku'], $variation_id ) );
}

WC_Product_Variable::sync( $parent_id );
wc_delete_product_transients( $parent_id );

WP_CLI::success( sprintf( 'D12 C5 Variable Product已创建或更新，父商品ID：%d。', $parent_id ) );
