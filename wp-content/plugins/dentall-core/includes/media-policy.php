<?php
/**
 * DentAll业务角色的媒体格式与上传大小边界。
 */

defined( 'ABSPATH' ) || exit;

/**
 * 判断当前用户是否属于第一阶段受限媒体角色。
 *
 * Website Manager与Content Editor在D12都只能上传安全位图；PDF等格式在完成
 * 独立权限与公开访问测试后再按计划开放。
 *
 * @return bool
 */
function dentall_core_is_restricted_media_user() {
	return current_user_can( 'dentall_content_editor' )
		|| current_user_can( DENTALL_WEBSITE_MANAGER_MARKER );
}

/**
 * 业务内容角色只允许上传白名单内的媒体格式。
 *
 * Website Manager额外允许CSV，以便使用WooCommerce原生商品导入功能；
 * Content Editor仍只允许安全位图格式。
 *
 * @param array<string, string> $mime_types 当前允许的 MIME 类型。
 * @return array<string, string>
 */
function dentall_core_limit_content_editor_mime_types( $mime_types ) {
	if ( ! dentall_core_is_restricted_media_user() ) {
		return $mime_types;
	}

	$allowed_keys       = array( 'jpg|jpeg|jpe', 'png', 'webp' );
	$allowed_mime_types = array_intersect_key( $mime_types, array_flip( $allowed_keys ) );

	if ( current_user_can( DENTALL_WEBSITE_MANAGER_MARKER ) ) {
		$allowed_mime_types['csv'] = 'text/csv';
	}

	return $allowed_mime_types;
}
add_filter( 'upload_mimes', 'dentall_core_limit_content_editor_mime_types', PHP_INT_MAX );

/**
 * 让媒体界面显示与服务端校验一致的5MB上限。
 *
 * @param int $size 当前上传大小上限，单位为字节。
 * @return int
 */
function dentall_core_limit_content_editor_upload_size_display( $size ) {
	if ( ! dentall_core_is_restricted_media_user() ) {
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
	if ( ! dentall_core_is_restricted_media_user() ) {
		return $file;
	}

	$maximum_size = 5 * MB_IN_BYTES;
	$file_size    = isset( $file['size'] ) ? (int) $file['size'] : 0;

	// REST原始请求通过sideload处理，文件数组可能没有size，必须读取临时文件。
	if ( 0 >= $file_size && isset( $file['tmp_name'] ) && is_string( $file['tmp_name'] ) && is_file( $file['tmp_name'] ) ) {
		$detected_size = filesize( $file['tmp_name'] );
		$file_size     = false === $detected_size ? 0 : (int) $detected_size;
	}

	if ( $file_size > $maximum_size ) {
		$file['error'] = __( 'Please compress this image to 5 MB or less before uploading.', 'dentall-core' );
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'dentall_core_limit_content_editor_upload_size', PHP_INT_MAX );
add_filter( 'wp_handle_sideload_prefilter', 'dentall_core_limit_content_editor_upload_size', PHP_INT_MAX );
