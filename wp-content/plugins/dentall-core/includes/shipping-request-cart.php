<?php
/** 报价提交仅消费本次会话里与申请完全相符的购物车。 */

defined( 'ABSPATH' ) || exit;

/** Woo 行键标识配置；再核对商品、变体与数量，兼容已登录用户的持久车没有行小计。 */
function dentall_core_quote_cart_matches_rows( $cart, $items ) {
	if ( ! is_array( $cart ) || ! $items || count( $cart ) !== count( $items ) ) {
		return false;
	}
	foreach ( $items as $key => $item ) {
		$row = $cart[ $key ] ?? array();
		if (
			(int) ( $row['product_id'] ?? 0 ) !== (int) $item['product_id']
			|| (int) ( $row['variation_id'] ?? 0 ) !== (int) $item['variation_id']
			|| (string) ( $row['quantity'] ?? '' ) !== (string) $item['quantity']
			|| ( $row['variation'] ?? array() ) !== $item['attributes']
		) {
			return false;
		}
	}
	return true;
}

/** 原生 save_data() 无条件整行覆盖。仅在申请与订单均落库后，以原值为条件原子清理。 */
function dentall_core_clear_submitted_quote_cart( $snapshot ) {
	global $wpdb;
	$session = WC()->session;
	if ( ! $session || 'WC_Session_Handler' !== get_class( $session ) || ! $session->has_session() ) {
		return false;
	}
	$stored = $session->get_session_data();
	if ( ! dentall_core_quote_cart_matches_rows( maybe_unserialize( $stored['cart'] ?? null ), $snapshot['items'] )
		|| maybe_unserialize( $stored['applied_coupons'] ?? array() ) !== $snapshot['coupons'] ) {
		return false;
	}

	// 对应 WC_Cart_Session::destroy_cart_session()；保留地址、通知和其他插件的会话数据。
	$cart_keys = array( 'cart', 'cart_totals', 'applied_coupons', 'coupon_discount_totals', 'coupon_discount_tax_totals',
		'removed_cart_contents', 'order_awaiting_payment', 'store_api_draft_order', 'shipping_method_counts', 'previous_shipping_methods', 'chosen_shipping_methods' );
	$next = array_diff_key( $stored, array_flip( $cart_keys ) );
	foreach ( array_keys( $next ) as $key ) {
		if ( 0 === strpos( $key, 'shipping_for_package_' ) ) {
			unset( $next[ $key ] );
		}
	}
	$table = $wpdb->prefix . 'woocommerce_sessions';
	$id    = $session->get_customer_id();
	$user  = get_current_user_id();
	if ( $user && apply_filters( 'woocommerce_persistent_cart_enabled', true ) ) {
		$meta_key = '_woocommerce_persistent_cart_' . get_current_blog_id();
		$saved    = get_user_meta( $user, $meta_key, true );
		if ( '' !== $saved && ! dentall_core_quote_cart_matches_rows( $saved['cart'] ?? null, $snapshot['items'] ) ) {
			return false;
		}
		// 同一 SQL 同时比较两份旧值，避免另一标签先写 usermeta、稍后才保存会话时丢商品。
		$query = $wpdb->prepare(
			'UPDATE %i s LEFT JOIN %i m ON m.user_id = %d AND m.meta_key = %s
			 SET s.session_value = %s, m.meta_value = %s
			 WHERE s.session_key = %s AND BINARY s.session_value = BINARY %s
			 AND ((m.umeta_id IS NULL AND %d = 1) OR BINARY m.meta_value = BINARY %s)',
			$table, $wpdb->usermeta, $user, $meta_key, maybe_serialize( $next ), maybe_serialize( array( 'cart' => array() ) ),
			$id, maybe_serialize( $stored ), (int) ( '' === $saved ), maybe_serialize( $saved )
		);
	} else {
		$query = $wpdb->prepare(
			'UPDATE %i SET session_value = %s WHERE session_key = %s AND BINARY session_value = BINARY %s',
			$table, maybe_serialize( $next ), $id, maybe_serialize( $stored )
		);
	}
	if ( ! $wpdb->query( $query ) ) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- 两条分支均已 prepare。
		return false;
	}
	wp_cache_delete( WC_Cache_Helper::get_cache_prefix( WC_SESSION_CACHE_GROUP ) . $id, WC_SESSION_CACHE_GROUP );
	if ( $user ) {
		wp_cache_delete( $user, 'user_meta' );
	}
	// 持久车已经原子更新；这里仅同步当前请求内的 Woo 对象并发送原生购物车事件。
	WC()->cart->empty_cart( false );
	return true;
}
