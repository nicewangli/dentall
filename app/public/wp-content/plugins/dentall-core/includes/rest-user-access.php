<?php
/**
 * WordPress公开Users REST与DentAll编辑团队署名的访问边界。
 */

defined( 'ABSPATH' ) || exit;

/**
 * 阻止外部读者从原生Users端点取回后台作者身份。
 *
 * 保留客户本人资料与内容编辑人员的原生权限；文章的真实作者ID不变。
 * 文章的`_embed=author`也会再次派发到这里，因此不能只过滤列表查询。
 *
 * @param mixed           $response 当前REST响应。
 * @param array           $handler  当前路由处理器。
 * @param WP_REST_Request $request  当前REST请求。
 * @return mixed
 */
function dentall_core_guard_public_user_rest_read( $response, $handler, $request ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	if (
		is_wp_error( $response )
		|| ! $request instanceof WP_REST_Request
		|| ! in_array( $request->get_method(), array( 'GET', 'HEAD' ), true )
	) {
		return $response;
	}

	if ( ! preg_match( '#^/wp/v2/users(?:/([0-9]+))?/?$#i', $request->get_route(), $matches ) ) {
		return $response;
	}

	if ( isset( $matches[1] ) ) {
		$current_user_id = get_current_user_id();
		// GET参数可覆盖路由中的id；两个值都必须指向本人。
		if (
			$current_user_id > 0
			&& (int) $matches[1] === $current_user_id
			&& is_numeric( $request['id'] )
			&& (int) $request['id'] === $current_user_id
		) {
			return $response;
		}
	}

	if ( current_user_can( 'edit_posts' ) || current_user_can( 'list_users' ) ) {
		return $response;
	}

	return new WP_Error(
		'dentall_user_profile_private',
		__( 'You are not allowed to view user profiles.', 'dentall-core' ),
		array( 'status' => 403 )
	);
}
add_filter( 'rest_request_before_callbacks', 'dentall_core_guard_public_user_rest_read', 11, 3 );
