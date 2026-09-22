<?php
/**
 * D79账户与订单归属隔离Local夹具／审计脚本。
 *
 * 仅通过WP-CLI eval-file在Day79私有副本中运行。动作由
 * DENTALL_DAY79_ACTION指定，私有清单由DENTALL_DAY79_MANIFEST指定。
 */

defined( 'ABSPATH' ) || exit;

$day79_action        = (string) getenv( 'DENTALL_DAY79_ACTION' );
$day79_manifest_path = (string) getenv( 'DENTALL_DAY79_MANIFEST' );

if (
	'local' !== wp_get_environment_type()
	|| '127.0.0.1' !== wp_parse_url( home_url( '/' ), PHP_URL_HOST )
	|| 'dentall_day79' !== DB_NAME
	|| false === strpos( str_replace( '\\', '/', $day79_manifest_path ), '/.codex-tmp/day79-runtime/' )
) {
	throw new RuntimeException( 'D79脚本只能在指定隔离Local与私有清单中运行。' );
}

define( 'DENTALL_DAY79_MANIFEST_PATH', $day79_manifest_path );

/**
 * 输出不含密码、Cookie、邮箱、Nonce或订单Key的审计结果。
 *
 * @param array<string,mixed> $result 结果。
 * @return void
 */
function day79_output( $result ) {
	echo wp_json_encode( $result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . PHP_EOL;
}

/**
 * 读取私有夹具清单。
 *
 * @return array<string,mixed>
 */
function day79_read_manifest() {
	if ( ! is_file( DENTALL_DAY79_MANIFEST_PATH ) ) {
		throw new RuntimeException( 'D79私有夹具清单不存在。' );
	}

	$data = json_decode( (string) file_get_contents( DENTALL_DAY79_MANIFEST_PATH ), true );
	if ( ! is_array( $data ) || empty( $data['marker'] ) ) {
		throw new RuntimeException( 'D79私有夹具清单无效。' );
	}

	return $data;
}

/**
 * 写入仅位于ACL私有目录的夹具清单。
 *
 * @param array<string,mixed> $manifest 清单。
 * @return void
 */
function day79_write_manifest( $manifest ) {
	$result = file_put_contents(
		DENTALL_DAY79_MANIFEST_PATH,
		wp_json_encode( $manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ),
		LOCK_EX
	);
	if ( false === $result ) {
		throw new RuntimeException( 'D79私有夹具清单写入失败。' );
	}
}

/**
 * 创建一个不会发送真实邮件的隔离用户。
 *
 * @param string $email    邮箱。
 * @param string $password 密码。
 * @param string $role     角色。
 * @param string $marker   随机标记。
 * @return int
 */
function day79_create_user( $email, $password, $role, $marker ) {
	$user_id = wp_insert_user(
		array(
			'user_login'   => sanitize_user( strtok( $email, '@' ), true ),
			'user_email'   => $email,
			'user_pass'    => $password,
			'display_name' => 'TEST D79 ' . strtoupper( $role ),
			'role'         => $role,
		)
	);
	if ( is_wp_error( $user_id ) ) {
		throw new RuntimeException( 'D79隔离用户创建失败：' . $user_id->get_error_code() );
	}

	update_user_meta( $user_id, '_dentall_day79_test', $marker );
	return (int) $user_id;
}

/**
 * 创建最小Simple商品。
 *
 * @param string $name    名称。
 * @param string $marker  随机标记。
 * @param bool   $virtual 是否纯虚拟。
 * @return int
 */
function day79_create_product( $name, $marker, $virtual ) {
	$product = new WC_Product_Simple();
	$product->set_name( $name );
	$product->set_slug( sanitize_title( $name . '-' . $marker ) );
	$product->set_status( 'publish' );
	$product->set_catalog_visibility( 'hidden' );
	$product->set_regular_price( '25.00' );
	$product->set_virtual( $virtual );
	$product->set_manage_stock( false );
	$product->add_meta_data( '_dentall_day79_test', $marker, true );
	return (int) $product->save();
}

/**
 * 创建HPOS兼容的代表订单。
 *
 * @param int    $product_id 商品ID。
 * @param string $email      Billing邮箱。
 * @param int    $customer_id 客户ID，0表示Guest。
 * @param string $marker     随机标记。
 * @param bool   $shipping   是否加入已报价Shipping line。
 * @param int    $age_seconds 订单时间向前偏移秒数。
 * @return int
 */
function day79_create_order( $product_id, $email, $customer_id, $marker, $shipping, $age_seconds = 3600 ) {
	$order = wc_create_order(
		array(
			'customer_id' => $customer_id,
			'status'      => 'pending',
			'created_via' => 'dentall-day79-test',
		)
	);
	if ( is_wp_error( $order ) ) {
		throw new RuntimeException( 'D79隔离订单创建失败。' );
	}
	$order->add_meta_data( '_dentall_day79_test', $marker, true );
	$order->save();

	$product = wc_get_product( $product_id );
	$order->add_product( $product, 1 );
	$order->set_currency( 'USD' );
	$order->set_payment_method( 'bacs' );
	$order->set_payment_method_title( 'TEST bank transfer' );
	$order->set_address(
		array(
			'first_name' => 'TEST',
			'last_name'  => 'D79',
			'company'    => 'DentAll Test',
			'address_1'  => '79 Baseline Street',
			'address_2'  => 'Suite 79',
			'city'       => 'Austin',
			'state'      => 'TX',
			'postcode'   => '78701',
			'country'    => 'US',
			'email'      => $email,
			'phone'      => '+1 202 555 0179',
		),
		'billing'
	);
	$order->set_address(
		array(
			'first_name' => 'TEST',
			'last_name'  => 'D79',
			'company'    => 'DentAll Test',
			'address_1'  => '79 Quoted Avenue',
			'address_2'  => 'Dock 2',
			'city'       => 'Austin',
			'state'      => 'TX',
			'postcode'   => '78702',
			'country'    => 'US',
		),
		'shipping'
	);

	if ( $shipping ) {
		$item = new WC_Order_Item_Shipping();
		$item->set_method_title( 'TEST D79 quoted shipping' );
		$item->set_method_id( 'dentall_day79_quote' );
		$item->set_total( '12.34' );
		$order->add_item( $item );
	}

	$order->set_date_created( time() - $age_seconds );
	$order->calculate_totals( false );
	return (int) $order->save();
}

/**
 * 返回订单中必须保持不变的报价快照。
 *
 * @param WC_Order $order 订单。
 * @return array<string,mixed>
 */
function day79_order_snapshot( $order ) {
	$shipping_total = '0';
	foreach ( $order->get_items( 'shipping' ) as $item ) {
		$shipping_total = wc_format_decimal( (float) $shipping_total + (float) $item->get_total(), '' );
	}

	return array(
		'billing'        => $order->get_address( 'billing' ),
		'shipping'       => $order->get_address( 'shipping' ),
		'shipping_total' => $shipping_total,
		'order_total'    => $order->get_total(),
		'currency'       => $order->get_currency(),
	);
}

/**
 * 按随机标记和私有清单清理夹具；prepare中途失败时也能回收已创建对象。
 *
 * @param array<string,mixed> $manifest 清单。
 * @return array<string,int>
 */
function day79_delete_marked_fixtures( $manifest ) {
	if ( ! function_exists( 'wp_delete_user' ) ) {
		require_once ABSPATH . 'wp-admin/includes/user.php';
	}

	$marker  = (string) $manifest['marker'];
	$deleted = array( 'orders' => 0, 'products' => 0, 'users' => 0 );
	$order_ids = array_merge(
		array_values( $manifest['orders'] ?? array() ),
		wc_get_orders(
			array(
				'limit'      => -1,
				'meta_key'   => '_dentall_day79_test',
				'meta_value' => $marker,
				'return'     => 'ids',
			)
		)
	);
	foreach ( array_unique( array_map( 'absint', $order_ids ) ) as $order_id ) {
		$order = wc_get_order( $order_id );
		if ( $order && $marker === $order->get_meta( '_dentall_day79_test' ) ) {
			$order->delete( true );
			$deleted['orders']++;
		}
	}

	$product_ids = array_merge(
		array_values( $manifest['products'] ?? array() ),
		wc_get_products(
			array(
				'limit'      => -1,
				'meta_key'   => '_dentall_day79_test',
				'meta_value' => $marker,
				'return'     => 'ids',
			)
		)
	);
	foreach ( array_unique( array_map( 'absint', $product_ids ) ) as $product_id ) {
		$product = wc_get_product( $product_id );
		if ( $product && $marker === $product->get_meta( '_dentall_day79_test' ) ) {
			$product->delete( true );
			$deleted['products']++;
		}
	}

	$user_ids = array_merge(
		array_values( $manifest['users'] ?? array() ),
		get_users(
			array(
				'meta_key'   => '_dentall_day79_test',
				'meta_value' => $marker,
				'fields'     => 'ids',
			)
		)
	);
	foreach ( array_values( $manifest['emails'] ?? array() ) as $email ) {
		$user = get_user_by( 'email', $email );
		if ( $user instanceof WP_User ) {
			$user_ids[] = $user->ID;
		}
	}
	foreach ( array_unique( array_map( 'absint', $user_ids ) ) as $user_id ) {
		$user = get_user_by( 'id', $user_id );
		if (
			$user
			&& (
				$marker === get_user_meta( $user_id, '_dentall_day79_test', true )
				|| in_array( $user->user_email, array_values( $manifest['emails'] ?? array() ), true )
			)
		) {
			wp_delete_user( $user_id );
			$deleted['users']++;
		}
	}

	return $deleted;
}

if ( 'prepare' === $day79_action ) {
	if ( is_file( DENTALL_DAY79_MANIFEST_PATH ) ) {
		throw new RuntimeException( 'D79夹具已存在，拒绝重复创建。' );
	}

	$manifest = array(
		'marker'          => 'd79-' . strtolower( wp_generate_password( 10, false, false ) ),
		'emails'          => array(),
		'passwords'       => array(),
		'users'           => array(),
		'products'        => array(),
		'orders'          => array(),
		'order_keys'      => array(),
		'quoted_snapshot' => array(),
	);
	$manifest['emails'] = array(
		'a'     => $manifest['marker'] . '-a@example.test',
		'b'     => $manifest['marker'] . '-b@example.test',
		'c'     => $manifest['marker'] . '-c@example.test',
		'other' => $manifest['marker'] . '-other@example.test',
		'wm'    => $manifest['marker'] . '-wm@example.test',
	);
	$manifest['passwords'] = array(
		'b'  => wp_generate_password( 24, true, true ),
		'c'  => wp_generate_password( 24, true, true ),
		'wm' => wp_generate_password( 24, true, true ),
	);
	day79_write_manifest( $manifest );

	try {
		$manifest['users']['b'] = day79_create_user( $manifest['emails']['b'], $manifest['passwords']['b'], 'customer', $manifest['marker'] );
		day79_write_manifest( $manifest );
		$manifest['users']['c'] = day79_create_user( $manifest['emails']['c'], $manifest['passwords']['c'], 'customer', $manifest['marker'] );
		day79_write_manifest( $manifest );
		$manifest['users']['wm'] = day79_create_user( $manifest['emails']['wm'], $manifest['passwords']['wm'], DENTALL_WEBSITE_MANAGER_ROLE, $manifest['marker'] );
		day79_write_manifest( $manifest );
		$manifest['products']['physical'] = day79_create_product( 'TEST D79 Physical', $manifest['marker'], false );
		day79_write_manifest( $manifest );
		$manifest['products']['virtual'] = day79_create_product( 'TEST D79 Virtual', $manifest['marker'], true );
		day79_write_manifest( $manifest );

		$order_definitions = array(
			'a_physical_old' => array( 'physical', 'a', 0, true, 3600 ),
			'a_physical_new' => array( 'physical', 'a', 0, true, 60 ),
			'a_virtual'      => array( 'virtual', 'a', 0, false, 3600 ),
			'other_guest'   => array( 'virtual', 'other', 0, false, 3600 ),
			'assigned_b'    => array( 'virtual', 'a', $manifest['users']['b'], false, 3600 ),
			'b_own'         => array( 'virtual', 'b', $manifest['users']['b'], false, 3600 ),
		);
		foreach ( $order_definitions as $name => $definition ) {
			$manifest['orders'][ $name ] = day79_create_order(
				$manifest['products'][ $definition[0] ],
				$manifest['emails'][ $definition[1] ],
				$definition[2],
				$manifest['marker'],
				$definition[3],
				$definition[4]
			);
			day79_write_manifest( $manifest );
		}

		$manifest['order_keys'] = array_map(
			static function ( $order_id ) {
				return wc_get_order( $order_id )->get_order_key();
			},
			$manifest['orders']
		);
		$manifest['quoted_snapshot'] = day79_order_snapshot( wc_get_order( $manifest['orders']['a_physical_old'] ) );
		day79_write_manifest( $manifest );
	} catch ( Throwable $error ) {
		$deleted = day79_delete_marked_fixtures( $manifest );
		day79_output( array( 'status' => 'prepare_failed', 'cleanup' => $deleted ) );
		throw $error;
	}

	day79_output( array( 'status' => 'prepared', 'orders' => count( $manifest['orders'] ), 'users' => 3, 'products' => 2 ) );
	exit;
}

$manifest = day79_read_manifest();
$a_user   = get_user_by( 'email', $manifest['emails']['a'] );

if ( 'audit' === $day79_action ) {
	$order_customers = array();
	foreach ( $manifest['orders'] as $name => $order_id ) {
		$order_customers[ $name ] = wc_get_order( $order_id ) ? wc_get_order( $order_id )->get_customer_id() : null;
	}

	$quoted_order = wc_get_order( $manifest['orders']['a_physical_old'] );
	$wm_user      = get_user_by( 'id', $manifest['users']['wm'] );
	$a_id          = $a_user instanceof WP_User ? $a_user->ID : -1;
	$result        = array(
		'status'            => 'audited',
		'a_exists'          => $a_user instanceof WP_User,
		'a_role'            => $a_user instanceof WP_User ? array_values( $a_user->roles ) : array(),
		'order_customers'    => $order_customers,
		'quoted_unchanged'   => $quoted_order instanceof WC_Order && $manifest['quoted_snapshot'] === day79_order_snapshot( $quoted_order ),
		'customer_caps'      => array(
			'read'               => $a_user instanceof WP_User && user_can( $a_user, 'read' ),
			'manage_woocommerce' => $a_user instanceof WP_User && user_can( $a_user, 'manage_woocommerce' ),
			'edit_users'         => $a_user instanceof WP_User && user_can( $a_user, 'edit_users' ),
			'install_plugins'    => $a_user instanceof WP_User && user_can( $a_user, 'install_plugins' ),
		),
		'website_manager_caps' => array(
			'manage_woocommerce' => $wm_user instanceof WP_User && user_can( $wm_user, 'manage_woocommerce' ),
			'edit_shop_orders'   => $wm_user instanceof WP_User && user_can( $wm_user, 'edit_shop_orders' ),
			'create_customers'   => $wm_user instanceof WP_User && user_can( $wm_user, 'create_customers' ),
			'edit_users'         => $wm_user instanceof WP_User && user_can( $wm_user, 'edit_users' ),
			'install_plugins'    => $wm_user instanceof WP_User && user_can( $wm_user, 'install_plugins' ),
			'switch_themes'      => $wm_user instanceof WP_User && user_can( $wm_user, 'switch_themes' ),
		),
		'settings'           => array(
			'wp_registration'      => (bool) get_option( 'users_can_register' ),
			'guest_checkout'       => get_option( 'woocommerce_enable_guest_checkout' ),
			'account_registration' => get_option( 'woocommerce_enable_myaccount_registration' ),
			'checkout_registration' => get_option( 'woocommerce_enable_signup_and_login_from_checkout' ),
			'generated_username'   => get_option( 'woocommerce_registration_generate_username' ),
			'generated_password'   => get_option( 'woocommerce_registration_generate_password' ),
		),
	);
	day79_output( $result );

	$expected_orders = array(
		'a_physical_old' => $a_id,
		'a_physical_new' => $a_id,
		'a_virtual'      => $a_id,
		'other_guest'   => 0,
		'assigned_b'    => (int) $manifest['users']['b'],
		'b_own'         => (int) $manifest['users']['b'],
	);
	$expected_customer_caps = array(
		'read'               => true,
		'manage_woocommerce' => false,
		'edit_users'         => false,
		'install_plugins'    => false,
	);
	$expected_manager_caps = array(
		'manage_woocommerce' => true,
		'edit_shop_orders'   => true,
		'create_customers'   => true,
		'edit_users'         => false,
		'install_plugins'    => false,
		'switch_themes'      => false,
	);
	$expected_settings = array(
		'wp_registration'      => false,
		'guest_checkout'       => 'yes',
		'account_registration' => 'yes',
		'checkout_registration' => 'no',
		'generated_username'   => 'yes',
		'generated_password'   => 'yes',
	);
	if (
		! $result['a_exists']
		|| array( 'customer' ) !== $result['a_role']
		|| $expected_orders !== $result['order_customers']
		|| ! $result['quoted_unchanged']
		|| $expected_customer_caps !== $result['customer_caps']
		|| $expected_manager_caps !== $result['website_manager_caps']
		|| $expected_settings !== $result['settings']
	) {
		throw new RuntimeException( 'D79账户归属、能力或设置审计失败。' );
	}
	exit;
}

if ( 'create_later_guest' === $day79_action ) {
	if ( ! $a_user instanceof WP_User ) {
		throw new RuntimeException( 'A账户尚不存在。' );
	}
	if ( ! empty( $manifest['orders']['a_later_guest'] ) ) {
		throw new RuntimeException( '后置Guest订单已存在。' );
	}
	$manifest['orders']['a_later_guest'] = day79_create_order(
		$manifest['products']['virtual'],
		$manifest['emails']['a'],
		0,
		$manifest['marker'],
		false,
		60
	);
	$manifest['order_keys']['a_later_guest'] = wc_get_order( $manifest['orders']['a_later_guest'] )->get_order_key();
	day79_write_manifest( $manifest );
	day79_output( array( 'status' => 'later_guest_created', 'customer_id' => 0 ) );
	exit;
}

if ( 'manual_link' === $day79_action ) {
	if ( ! $a_user instanceof WP_User || empty( $manifest['orders']['a_later_guest'] ) ) {
		throw new RuntimeException( '手工关联前置条件不足。' );
	}
	$order = wc_get_order( $manifest['orders']['a_later_guest'] );
	$order->set_customer_id( $a_user->ID );
	$order->save();
	$linked = $a_user->ID === wc_get_order( $order->get_id() )->get_customer_id();
	day79_output( array( 'status' => 'manually_linked', 'linked' => $linked ) );
	if ( ! $linked ) {
		throw new RuntimeException( 'D79后置Guest订单手工关联失败。' );
	}
	exit;
}

if ( 'change_account_address' === $day79_action ) {
	if ( ! $a_user instanceof WP_User ) {
		throw new RuntimeException( 'A账户尚不存在。' );
	}
	$customer = new WC_Customer( $a_user->ID );
	$customer->set_billing_address_1( '999 Changed Account Road' );
	$customer->set_billing_city( 'Dallas' );
	$customer->set_shipping_address_1( '1000 Changed Shipping Road' );
	$customer->set_shipping_city( 'Houston' );
	$customer->save();
	$order = wc_get_order( $manifest['orders']['a_physical_old'] );
	$result = array(
		'status'           => 'account_address_changed',
		'quoted_unchanged' => $manifest['quoted_snapshot'] === day79_order_snapshot( $order ),
	);
	day79_output( $result );
	if ( ! $result['quoted_unchanged'] ) {
		throw new RuntimeException( 'D79账户地址修改污染了既有订单快照。' );
	}
	exit;
}

if ( 'cleanup' === $day79_action ) {
	$deleted = day79_delete_marked_fixtures( $manifest );
	day79_output( array( 'status' => 'cleaned', 'deleted' => $deleted ) );
	exit;
}

if ( 'cleanup_audit' === $day79_action ) {
	$user_ids = get_users(
		array(
			'meta_key'   => '_dentall_day79_test',
			'meta_value' => $manifest['marker'],
			'fields'     => 'ids',
		)
	);
	foreach ( array_values( $manifest['emails'] ) as $email ) {
		$user = get_user_by( 'email', $email );
		if ( $user instanceof WP_User ) {
			$user_ids[] = $user->ID;
		}
	}
	$result = array(
		'status'   => 'cleanup_audited',
		'users'    => count( array_unique( array_map( 'absint', $user_ids ) ) ),
		'orders'   => count(
			wc_get_orders(
				array(
					'limit'      => -1,
					'meta_key'   => '_dentall_day79_test',
					'meta_value' => $manifest['marker'],
					'return'     => 'ids',
				)
			)
		),
		'products' => count(
			wc_get_products(
				array(
					'limit'      => -1,
					'meta_key'   => '_dentall_day79_test',
					'meta_value' => $manifest['marker'],
					'return'     => 'ids',
				)
			)
		),
	);
	day79_output( $result );
	if ( 0 !== $result['users'] || 0 !== $result['orders'] || 0 !== $result['products'] ) {
		throw new RuntimeException( 'D79夹具清理审计发现残留。' );
	}
	exit;
}

throw new RuntimeException( '未知D79动作。' );
