<?php
/**
 * Plugin Name: DentAll Core
 * Description: DentAll 商城跨主题的最小业务能力。
 * Version: 0.1.1
 * Requires at least: 7.0
 * Requires PHP: 8.2
 * Text Domain: dentall-core
 */

defined( 'ABSPATH' ) || exit;

const DENTALL_CORE_ROLE_VERSION = '2';
const DENTALL_CORE_ROLE_OPTION  = 'dentall_core_role_version';
const DENTALL_CONTENT_ROLE      = 'dentall_content_editor';

/**
 * 返回内容试录员的明确能力白名单。
 *
 * 该角色只负责自己的草稿，不具备发布、编辑他人内容或商城管理能力。
 * 自定义能力 dentall_content_editor 仅作为项目角色标记使用。
 *
 * @return array<string, bool>
 */
function dentall_core_get_content_editor_capabilities() {
	return array(
		'read'                   => true,
		'edit_posts'             => true,
		'delete_posts'           => true,
		'upload_files'           => true,
		'edit_products'          => true,
		'delete_products'        => true,
		'assign_product_terms'   => true,
		'dentall_content_editor' => true,
	);
}

/**
 * 创建或同步内容试录员角色。
 *
 * 使用白名单覆盖现有能力，避免后续版本移除某项权限时旧角色继续残留。
 *
 * @return void
 */
function dentall_core_sync_content_editor_role() {
	$capabilities = dentall_core_get_content_editor_capabilities();
	$role         = get_role( DENTALL_CONTENT_ROLE );

	if ( null === $role ) {
		add_role( DENTALL_CONTENT_ROLE, __( 'DentAll Content Editor', 'dentall-core' ), $capabilities );
		$role = get_role( DENTALL_CONTENT_ROLE );
	}

	if ( null !== $role ) {
		foreach ( array_keys( $role->capabilities ) as $capability ) {
			if ( ! array_key_exists( $capability, $capabilities ) ) {
				$role->remove_cap( $capability );
			}
		}

		foreach ( $capabilities as $capability => $grant ) {
			$role->add_cap( $capability, $grant );
		}

		update_option( DENTALL_CORE_ROLE_OPTION, DENTALL_CORE_ROLE_VERSION, false );
	}
}

register_activation_hook( __FILE__, 'dentall_core_sync_content_editor_role' );

/**
 * 仅在角色定义版本变化时重新同步，避免每个请求重复写数据库。
 *
 * @return void
 */
function dentall_core_maybe_sync_roles() {
	if ( DENTALL_CORE_ROLE_VERSION !== get_option( DENTALL_CORE_ROLE_OPTION ) ) {
		dentall_core_sync_content_editor_role();
	}
}
add_action( 'init', 'dentall_core_maybe_sync_roles', 5 );

/**
 * 内容试录员只允许上传第一阶段需要的安全位图格式。
 *
 * @param array<string, string> $mime_types 当前允许的 MIME 类型。
 * @return array<string, string>
 */
function dentall_core_limit_content_editor_mime_types( $mime_types ) {
	if ( ! current_user_can( 'dentall_content_editor' ) ) {
		return $mime_types;
	}

	$allowed_keys = array( 'jpg|jpeg|jpe', 'png', 'webp' );

	return array_intersect_key( $mime_types, array_flip( $allowed_keys ) );
}
add_filter( 'upload_mimes', 'dentall_core_limit_content_editor_mime_types', PHP_INT_MAX );

/**
 * 让媒体界面显示与服务端校验一致的5MB上限。
 *
 * @param int $size 当前上传大小上限，单位为字节。
 * @return int
 */
function dentall_core_limit_content_editor_upload_size_display( $size ) {
	if ( ! current_user_can( 'dentall_content_editor' ) ) {
		return $size;
	}

	return min( (int) $size, 5 * MB_IN_BYTES );
}
add_filter( 'upload_size_limit', 'dentall_core_limit_content_editor_upload_size_display', PHP_INT_MAX );

/**
 * 限制试录图片大小，先阻止未经压缩的大文件进入内容生产线。
 *
 * @param array<string, mixed> $file 上传文件信息。
 * @return array<string, mixed>
 */
function dentall_core_limit_content_editor_upload_size( $file ) {
	if ( ! current_user_can( 'dentall_content_editor' ) ) {
		return $file;
	}

	$maximum_size = 5 * MB_IN_BYTES;
	$file_size    = isset( $file['size'] ) ? (int) $file['size'] : 0;

	if ( $file_size > $maximum_size ) {
		$file['error'] = __( 'Please compress this image to 5 MB or less before uploading.', 'dentall-core' );
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'dentall_core_limit_content_editor_upload_size', PHP_INT_MAX );

/**
 * 内容试录员暂不维护商品标签，移除会误导其创建标签的自由输入框。
 *
 * @return void
 */
function dentall_core_remove_content_editor_product_tag_meta_box() {
	if ( current_user_can( 'dentall_content_editor' ) ) {
		remove_meta_box( 'tagsdiv-product_tag', 'product', 'side' );
	}
}
add_action( 'add_meta_boxes_product', 'dentall_core_remove_content_editor_product_tag_meta_box', PHP_INT_MAX );

/**
 * 服务端拒绝内容试录员创建商品标签，避免绕过后台界面。
 *
 * @param string|WP_Error $term     准备创建的术语名称或上游错误。
 * @param string          $taxonomy 目标分类法。
 * @return string|WP_Error
 */
function dentall_core_prevent_content_editor_product_tag_creation( $term, $taxonomy ) {
	if ( 'product_tag' === $taxonomy && current_user_can( 'dentall_content_editor' ) ) {
		return new WP_Error(
			'dentall_product_tag_creation_denied',
			__( 'You are not allowed to create product tags.', 'dentall-core' )
		);
	}

	return $term;
}
add_filter( 'pre_insert_term', 'dentall_core_prevent_content_editor_product_tag_creation', 10, 2 );
