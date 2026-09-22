<?php
/**
 * D80隔离Local密码重置客户夹具。
 *
 * 仅通过WP-CLI eval-file在dentall_day80环回副本中运行。
 */

defined( 'ABSPATH' ) || exit;

$manifest_path = (string) getenv( 'DENTALL_DAY80_MANIFEST' );
$action        = (string) getenv( 'DENTALL_DAY80_ACTION' );
$action        = '' !== $action ? $action : 'prepare';
if (
	'local' !== wp_get_environment_type()
	|| '127.0.0.1' !== wp_parse_url( home_url( '/' ), PHP_URL_HOST )
	|| 'dentall_day80' !== DB_NAME
	|| false === strpos( str_replace( '\\', '/', $manifest_path ), '/.codex-tmp/day80-runtime/' )
) {
	throw new RuntimeException( 'D80夹具只能在指定隔离Local与私有清单中运行。' );
}

if ( 'cleanup' === $action ) {
	if ( ! is_file( $manifest_path ) ) {
		throw new RuntimeException( 'D80夹具清单不存在，拒绝无目标清理。' );
	}

	$manifest = json_decode( (string) file_get_contents( $manifest_path ), true );
	if ( ! is_array( $manifest ) || empty( $manifest['marker'] ) ) {
		throw new RuntimeException( 'D80夹具清单无效，拒绝清理。' );
	}

	if ( ! function_exists( 'wp_delete_user' ) ) {
		require_once ABSPATH . 'wp-admin/includes/user.php';
	}
	$deleted = 0;
	foreach ( array( 'user_id', 'same_user_id', 'other_user_id' ) as $key ) {
		$user_id = isset( $manifest[ $key ] ) ? absint( $manifest[ $key ] ) : 0;
		if ( $user_id && $manifest['marker'] === get_user_meta( $user_id, '_dentall_day80_test', true ) ) {
			wp_delete_user( $user_id );
			$deleted++;
		}
	}

	global $wpdb;
	$rate_limits_deleted = $wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->prefix}wc_rate_limits WHERE rate_limit_key LIKE %s",
			$wpdb->esc_like( 'dentall_password_reset_' ) . '%'
		)
	);
	unlink( $manifest_path );
	echo wp_json_encode(
		array(
			'status'              => 'cleaned',
			'users'               => $deleted,
			'rate_limits_deleted' => (int) $rate_limits_deleted,
		)
	);
	exit;
}

if ( 'prepare' !== $action ) {
	throw new RuntimeException( '未知D80夹具动作。' );
}

if ( is_file( $manifest_path ) ) {
	throw new RuntimeException( 'D80夹具清单已存在，拒绝重复创建。' );
}

$marker         = 'd80-' . strtolower( wp_generate_password( 10, false, false ) );
$email          = $marker . '@example.test';
$password       = wp_generate_password( 24, true, true );
$same_email     = $marker . '-same@example.test';
$same_password  = wp_generate_password( 24, true, true );
$other_email    = $marker . '-other@example.test';
$other_password = wp_generate_password( 24, true, true );
$user_id = wp_insert_user(
	array(
		'user_login'   => $marker,
		'user_email'   => $email,
		'user_pass'    => $password,
		'display_name' => 'TEST D80 Password Reset',
		'role'         => 'customer',
	)
);

if ( is_wp_error( $user_id ) ) {
	throw new RuntimeException( 'D80客户夹具创建失败：' . $user_id->get_error_code() );
}

update_user_meta( $user_id, '_dentall_day80_test', $marker );
$same_user_id = wp_insert_user(
	array(
		'user_login'   => $marker . '-same',
		'user_email'   => $same_email,
		'user_pass'    => $same_password,
		'display_name' => 'TEST D80 Same Customer',
		'role'         => 'customer',
	)
);
if ( is_wp_error( $same_user_id ) ) {
	wp_delete_user( $user_id );
	throw new RuntimeException( 'D80同账户夹具创建失败：' . $same_user_id->get_error_code() );
}
update_user_meta( $same_user_id, '_dentall_day80_test', $marker );

$other_user_id = wp_insert_user(
	array(
		'user_login'   => $marker . '-other',
		'user_email'   => $other_email,
		'user_pass'    => $other_password,
		'display_name' => 'TEST D80 Other Customer',
		'role'         => 'customer',
	)
);
if ( is_wp_error( $other_user_id ) ) {
	wp_delete_user( $user_id );
	wp_delete_user( $same_user_id );
	throw new RuntimeException( 'D80其他客户夹具创建失败：' . $other_user_id->get_error_code() );
}
update_user_meta( $other_user_id, '_dentall_day80_test', $marker );

$manifest = array(
	'marker'         => $marker,
	'email'          => $email,
	'password'       => $password,
	'user_id'        => (int) $user_id,
	'same_email'     => $same_email,
	'same_password'  => $same_password,
	'same_user_id'   => (int) $same_user_id,
	'other_email'    => $other_email,
	'other_password' => $other_password,
	'other_user_id'  => (int) $other_user_id,
);
$written = file_put_contents(
	$manifest_path,
	wp_json_encode( $manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ),
	LOCK_EX
);
if ( false === $written ) {
	wp_delete_user( $user_id );
	wp_delete_user( $same_user_id );
	wp_delete_user( $other_user_id );
	throw new RuntimeException( 'D80私有夹具清单写入失败。' );
}

echo wp_json_encode( array( 'status' => 'prepared', 'users' => 3 ) );
