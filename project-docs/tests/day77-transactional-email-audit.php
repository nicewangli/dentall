<?php
/**
 * D77 WooCommerce 事务邮件隔离验证。
 *
 * PowerShell用法：先通过 $env:DENTALL_D77_CASE 显式选择success/failure/cleanup。
 * 发送用例还必须通过 $env:DENTALL_D77_EXPECTED_EMAIL 提供已批准地址；该值不得写入本文件或Git。
 * success必须向PHP传入 -d SMTP=127.0.0.1 -d smtp_port=17251；failure端口为17252。
 * cleanup只清理带隔离标记的TEST订单和状态；调用方随后清空FluentSMTP日志、Mailpit并销毁隔离副本。
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( 1 );
}

const DENTALL_D77_STATE_OPTION = 'dentall_d77_email_test_state';
const DENTALL_D77_MARKER_META  = '_dentall_d77_email_test';
const DENTALL_D77_FAILURE_STATE_OPTION = 'dentall_d77_email_failure_test_state';

/**
 * 输出失败并终止，防止测试误落到共享或线上环境。
 *
 * @param string $message 错误信息。
 * @return never
 */
function dentall_d77_fail( $message ) {
	WP_CLI::error( $message );
}

/**
 * 验证隔离环境、邮件配置和权限边界。
 *
 * @param string $case 明确指定的测试用例。
 * @return array{approved_email:string,marker:string,log_table:string}
 */
function dentall_d77_guard_environment( $case ) {
	$marker = (string) get_option( 'dentall_d77_isolated_marker', '' );
	$host   = (string) wp_parse_url( home_url(), PHP_URL_HOST );

	if (
		'local' !== wp_get_environment_type() ||
		'127.0.0.1' !== $host ||
		0 !== strpos( $marker, 'dentall_d77_' ) ||
		! defined( 'DB_NAME' ) ||
		$marker !== DB_NAME
	) {
		dentall_d77_fail( '安全中止：本测试只允许在带 D77 独立标记的 127.0.0.1 Local 副本运行。' );
	}

	if ( in_array( $case, array( 'success', 'failure' ), true ) ) {
		$expected_port = 'success' === $case ? 17251 : 17252;
		if ( '127.0.0.1' !== ini_get( 'SMTP' ) || $expected_port !== (int) ini_get( 'smtp_port' ) ) {
			dentall_d77_fail( '安全中止：SMTP必须显式指向当前用例的127.0.0.1隔离端口。' );
		}

		$expected_email = getenv( 'DENTALL_D77_EXPECTED_EMAIL' );
		if ( false === $expected_email || ! is_email( $expected_email ) ) {
			dentall_d77_fail( '安全中止：必须通过未入库环境变量提供已批准邮箱。' );
		}

		$socket = @fsockopen( '127.0.0.1', $expected_port, $error_code, $error_message, 0.5 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		if ( 'success' === $case && ! is_resource( $socket ) ) {
			dentall_d77_fail( '安全中止：Local Mailpit SMTP端口未监听。' );
		}
		if ( 'failure' === $case && is_resource( $socket ) ) {
			fclose( $socket );
			dentall_d77_fail( '安全中止：故障注入端口正在监听，不能证明连接失败。' );
		}
		if ( is_resource( $socket ) ) {
			fclose( $socket );
		}

		$mailpit_health = wp_remote_get(
			'http://127.0.0.1:18025/readyz',
			array(
				'timeout'     => 2,
				'redirection' => 0,
			)
		);
		if ( is_wp_error( $mailpit_health ) || 200 !== wp_remote_retrieve_response_code( $mailpit_health ) ) {
			dentall_d77_fail( '安全中止：专用Mailpit健康检查未通过。' );
		}
	}

	if ( ! class_exists( 'WooCommerce' ) || ! class_exists( 'WC_Order' ) ) {
		dentall_d77_fail( '安全中止：WooCommerce 未加载。' );
	}

	$active_plugins = (array) get_option( 'active_plugins', array() );
	if ( ! in_array( 'fluent-smtp/fluent-smtp.php', $active_plugins, true ) ) {
		dentall_d77_fail( '安全中止：隔离副本未启用 FluentSMTP。' );
	}

	$approved_email = (string) get_option( 'dentall_shipping_quote_email', '' );
	$domain         = strtolower( (string) substr( strrchr( $approved_email, '@' ), 1 ) );
	if ( ! is_email( $approved_email ) || 'chinaadsdentallab.com' !== $domain ) {
		dentall_d77_fail( '安全中止：报价收件邮箱不是已批准公司域名地址。' );
	}
	if ( isset( $expected_email ) && ! hash_equals( strtolower( $expected_email ), strtolower( $approved_email ) ) ) {
		dentall_d77_fail( '安全中止：运行时配置与未入库的已批准邮箱不一致。' );
	}

	$settings    = get_option( 'fluentmail-settings', array() );
	$connections = isset( $settings['connections'] ) && is_array( $settings['connections'] ) ? $settings['connections'] : array();
	$connection  = 1 === count( $connections ) ? reset( $connections ) : array();
	$provider    = isset( $connection['provider_settings'] ) && is_array( $connection['provider_settings'] ) ? $connection['provider_settings'] : array();
	$misc        = isset( $settings['misc'] ) && is_array( $settings['misc'] ) ? $settings['misc'] : array();

	if (
		1 !== count( $connections ) ||
		'default' !== ( $provider['provider'] ?? '' ) ||
		$approved_email !== ( $provider['sender_email'] ?? '' ) ||
		'yes' !== ( $provider['force_from_email'] ?? '' ) ||
		'yes' !== ( $provider['return_path'] ?? '' ) ||
		! empty( $misc['fallback_connection'] ) ||
		'no' !== ( $misc['simulate_emails'] ?? '' ) ||
		'yes' !== ( $misc['log_emails'] ?? '' ) ||
		'7' !== (string) ( $misc['log_saved_interval_days'] ?? '' )
	) {
		dentall_d77_fail( '安全中止：FluentSMTP Local 连接、日志或备用连接不符合 D77 基线。' );
	}

	$admin_ids = array( 'new_order', 'cancelled_order', 'failed_order' );
	foreach ( $admin_ids as $email_id ) {
		$email_settings = get_option( 'woocommerce_' . $email_id . '_settings', array() );
		if ( 'yes' !== ( $email_settings['enabled'] ?? '' ) || $approved_email !== ( $email_settings['recipient'] ?? '' ) ) {
			dentall_d77_fail( '安全中止：WooCommerce 管理员邮件配置不符合 D77 基线。' );
		}
	}

	if (
		$approved_email !== get_option( 'woocommerce_email_from_address' ) ||
		$approved_email !== get_option( 'woocommerce_email_reply_to_address' ) ||
		'yes' !== get_option( 'woocommerce_email_reply_to_enabled' ) ||
		$approved_email === get_option( 'admin_email' )
	) {
		dentall_d77_fail( '安全中止：WooCommerce From、Reply-To 或 WordPress 管理员邮箱边界不符合 D77 基线。' );
	}

	if ( Automattic\WooCommerce\Utilities\FeaturesUtil::feature_is_enabled( 'deferred_transactional_emails' ) ) {
		dentall_d77_fail( '安全中止：本测试要求关闭延迟事务邮件，以便精确核对发送次数。' );
	}

	global $wpdb;
	$log_table = $wpdb->prefix . 'fsmpt_email_logs';
	if ( $log_table !== $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $log_table ) ) ) {
		dentall_d77_fail( '安全中止：FluentSMTP 日志表不存在。' );
	}

	return array(
		'approved_email' => $approved_email,
		'marker'         => $marker,
		'log_table'      => $log_table,
	);
}

/**
 * 创建不依赖真实商品和库存的 TEST 订单。
 *
 * @param string $label         用例标识。
 * @param string $customer_mail 测试客户邮箱。
 * @param string $marker        隔离标记。
 * @return WC_Order
 */
function dentall_d77_create_order( $label, $customer_mail, $marker ) {
	$order = new WC_Order();
	$order->set_status( 'pending' );
	$order->set_created_via( 'd77-email-audit' );
	$order->set_customer_id( 0 );
	$order->set_currency( 'USD' );
	$order->set_payment_method( 'bacs' );
	$order->set_payment_method_title( 'TEST manual payment' );
	$order->set_billing_first_name( 'D77' );
	$order->set_billing_last_name( 'Customer' );
	$order->set_billing_company( 'TEST Dental Lab' );
	$order->set_billing_address_1( '77 Test Avenue' );
	$order->set_billing_city( 'Seattle' );
	$order->set_billing_state( 'WA' );
	$order->set_billing_postcode( '98101' );
	$order->set_billing_country( 'US' );
	$order->set_billing_email( $customer_mail );
	$order->set_billing_phone( '+1 206 555 0177' );
	$order->set_shipping_first_name( 'D77' );
	$order->set_shipping_last_name( 'Customer' );
	$order->set_shipping_company( 'TEST Dental Lab' );
	$order->set_shipping_address_1( '77 Test Avenue' );
	$order->set_shipping_city( 'Seattle' );
	$order->set_shipping_state( 'WA' );
	$order->set_shipping_postcode( '98101' );
	$order->set_shipping_country( 'US' );
	$order->add_meta_data( DENTALL_D77_MARKER_META, $marker, true );
	$product_item = new WC_Order_Item_Product();
	$product_item->set_name( 'TEST D77 zirconia crown' );
	$product_item->set_quantity( 2 );
	$product_item->set_subtotal( '96.00' );
	$product_item->set_total( '96.00' );
	$order->add_item( $product_item );

	$shipping_item = new WC_Order_Item_Shipping();
	$shipping_item->set_method_title( 'TEST quoted shipping' );
	$shipping_item->set_method_id( 'dentall_d77_test' );
	$shipping_item->set_total( '18.00' );
	$order->add_item( $shipping_item );

	$order->calculate_totals( false );
	$order->save();
	$order->add_order_note( 'D77 隔离邮件审计：' . sanitize_text_field( $label ), false, true );

	return $order;
}

/**
 * 每创建一张订单就持久化ID，使中途失败仍可精确清理。
 *
 * @param array    $context 环境上下文。
 * @param string   $label   用例标识。
 * @param WC_Order $order   TEST订单。
 * @return void
 */
function dentall_d77_remember_order( $context, $label, $order ) {
	$state = get_option( DENTALL_D77_STATE_OPTION, array() );
	if ( ! is_array( $state ) || $context['marker'] !== ( $state['marker'] ?? '' ) ) {
		dentall_d77_fail( '安全中止：D77测试状态缺失或隔离标记不匹配。' );
	}

	$state['order_ids'][ $label ] = $order->get_id();
	update_option( DENTALL_D77_STATE_OPTION, $state, false );
}

/**
 * 返回 FluentSMTP 日志计数。
 *
 * @param string $table 日志表。
 * @return array{total:int,sent:int,failed:int}
 */
function dentall_d77_log_counts( $table ) {
	global $wpdb;

	return array(
		'total'  => (int) $wpdb->get_var( "SELECT COUNT(*) FROM `{$table}`" ), // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		'sent'   => (int) $wpdb->get_var( "SELECT COUNT(*) FROM `{$table}` WHERE `status` = 'sent'" ), // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		'failed' => (int) $wpdb->get_var( "SELECT COUNT(*) FROM `{$table}` WHERE `status` = 'failed'" ), // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	);
}

/**
 * 运行成功、跳过与去重矩阵。
 *
 * @param array $context 环境上下文。
 * @return void
 */
function dentall_d77_run_success( $context ) {
	if ( get_option( DENTALL_D77_STATE_OPTION ) ) {
		dentall_d77_fail( '已有 D77 测试状态；请先运行 cleanup。' );
	}
	update_option(
		DENTALL_D77_STATE_OPTION,
		array(
			'marker'                => $context['marker'],
			'order_ids'             => array(),
			'customer_email_domain' => 'example.test',
			'created_at_gmt'        => gmdate( 'c' ),
		),
		false
	);

	$before_logs = dentall_d77_log_counts( $context['log_table'] );
	$skipped     = array();
	$sent_events = array();
	add_action(
		'woocommerce_email_skipped',
		static function ( $reason, $email_id ) use ( &$skipped ) {
			$skipped[] = array(
				'reason'   => (string) $reason,
				'email_id' => (string) $email_id,
			);
		},
		10,
		2
	);
	add_action(
		'woocommerce_email_sent',
		static function ( $success, $email_id ) use ( &$sent_events ) {
			if ( ! isset( $sent_events[ $email_id ] ) ) {
				$sent_events[ $email_id ] = array(
					'count'        => 0,
					'success_count'=> 0,
				);
			}
			++$sent_events[ $email_id ]['count'];
			if ( $success ) {
				++$sent_events[ $email_id ]['success_count'];
			}
		},
		10,
		2
	);

	$customer_email = 'customer.d77+' . gmdate( 'YmdHis' ) . '@example.test';
	$orders         = array();
	$mailer         = WC()->mailer();
	$emails         = $mailer->get_emails();
	$invalid_address_rejected = false;
	$unsaved_order            = new WC_Order();
	try {
		$unsaved_order->set_billing_email( 'invalid-address' );
	} catch ( WC_Data_Exception $exception ) {
		$invalid_address_rejected = 'order_invalid_billing_email' === $exception->getErrorCode();
	}

	$orders['manual_invoice'] = dentall_d77_create_order( 'manual-invoice', $customer_email, $context['marker'] );
	dentall_d77_remember_order( $context, 'manual_invoice', $orders['manual_invoice'] );
	$emails['WC_Email_Customer_Invoice']->trigger( $orders['manual_invoice']->get_id(), $orders['manual_invoice'] );
	$emails['WC_Email_Customer_Invoice']->trigger( $orders['manual_invoice']->get_id(), $orders['manual_invoice'] );

	$orders['completed'] = dentall_d77_create_order( 'processing-completed', $customer_email, $context['marker'] );
	dentall_d77_remember_order( $context, 'completed', $orders['completed'] );
	$orders['completed']->update_status( 'processing', 'D77 TEST：进入处理中。', true );
	$orders['completed']->update_status( 'completed', 'D77 TEST：完成。', true );

	$orders['failed'] = dentall_d77_create_order( 'pending-failed', $customer_email, $context['marker'] );
	dentall_d77_remember_order( $context, 'failed', $orders['failed'] );
	$orders['failed']->update_status( 'failed', 'D77 TEST：失败。', true );

	$orders['cancelled'] = dentall_d77_create_order( 'processing-cancelled', $customer_email, $context['marker'] );
	dentall_d77_remember_order( $context, 'cancelled', $orders['cancelled'] );
	$orders['cancelled']->update_status( 'processing', 'D77 TEST：进入处理中。', true );
	$orders['cancelled']->update_status( 'cancelled', 'D77 TEST：取消。', true );

	$orders['missing_recipient'] = dentall_d77_create_order( 'missing-recipient', '', $context['marker'] );
	dentall_d77_remember_order( $context, 'missing_recipient', $orders['missing_recipient'] );
	$emails['WC_Email_Customer_Invoice']->trigger( $orders['missing_recipient']->get_id(), $orders['missing_recipient'] );

	$orders['invalid_recipient'] = dentall_d77_create_order( 'invalid-recipient', $customer_email, $context['marker'] );
	dentall_d77_remember_order( $context, 'invalid_recipient', $orders['invalid_recipient'] );
	$invalid_order_id            = $orders['invalid_recipient']->get_id();
	$invalid_recipient_filter    = static function ( $recipient, $object ) use ( $invalid_order_id ) {
		return $object instanceof WC_Order && $invalid_order_id === $object->get_id() ? 'invalid-address' : $recipient;
	};
	add_filter( 'woocommerce_email_recipient_customer_invoice', $invalid_recipient_filter, 10, 2 );
	$emails['WC_Email_Customer_Invoice']->trigger( $orders['invalid_recipient']->get_id(), $orders['invalid_recipient'] );
	remove_filter( 'woocommerce_email_recipient_customer_invoice', $invalid_recipient_filter, 10 );

	$new_order_sent_before_repeat = $orders['completed']->get_new_order_email_sent();
	$emails['WC_Email_New_Order']->trigger( $orders['completed']->get_id(), $orders['completed'] );
	$emails['WC_Email_New_Order']->trigger( $orders['completed']->get_id(), $orders['completed'] );

	$order_ids = array();
	foreach ( $orders as $label => $order ) {
		$order_ids[ $label ] = $order->get_id();
	}

	$state                     = get_option( DENTALL_D77_STATE_OPTION, array() );
	$state['payment_order_id'] = $orders['manual_invoice']->get_id();
	update_option( DENTALL_D77_STATE_OPTION, $state, false );

	$after_logs = dentall_d77_log_counts( $context['log_table'] );
	ksort( $sent_events );
	$expected_events = array(
		'cancelled_order'                  => array( 'count' => 1, 'success_count' => 1 ),
		'customer_cancelled_order'         => array( 'count' => 1, 'success_count' => 1 ),
		'customer_completed_order'         => array( 'count' => 1, 'success_count' => 1 ),
		'customer_failed_order'            => array( 'count' => 1, 'success_count' => 1 ),
		'customer_invoice'                 => array( 'count' => 2, 'success_count' => 2 ),
		'customer_processing_order'        => array( 'count' => 2, 'success_count' => 2 ),
		'failed_order'                     => array( 'count' => 1, 'success_count' => 1 ),
		'new_order'                        => array( 'count' => 2, 'success_count' => 2 ),
	);
	$result     = array(
		'case'                          => 'success',
		'orders_created'                => count( $order_ids ),
		'order_ids'                     => $order_ids,
		'payment_order_id'              => $orders['manual_invoice']->get_id(),
		'expected_new_sent_logs'        => 11,
		'actual_new_sent_logs'          => $after_logs['sent'] - $before_logs['sent'],
		'actual_new_failed_logs'        => $after_logs['failed'] - $before_logs['failed'],
		'actual_new_total_logs'         => $after_logs['total'] - $before_logs['total'],
		'email_events'                  => $sent_events,
		'email_events_match'            => $expected_events === $sent_events,
		'expected_skipped'              => 2,
		'actual_skipped'                => count( $skipped ),
		'skipped_events'                => $skipped,
		'invalid_address_rejected'      => $invalid_address_rejected,
		'new_order_flag_before_repeats' => (bool) $new_order_sent_before_repeat,
		'approved_email_masked'         => 'm***@chinaadsdentallab.com',
	);

	if (
		11 !== $result['actual_new_sent_logs'] ||
		0 !== $result['actual_new_failed_logs'] ||
		11 !== $result['actual_new_total_logs'] ||
		! $result['email_events_match'] ||
		2 !== $result['actual_skipped'] ||
		array(
			array( 'reason' => 'no_recipient', 'email_id' => 'customer_invoice' ),
			array( 'reason' => 'no_recipient', 'email_id' => 'customer_invoice' ),
		) !== $result['skipped_events'] ||
		! $result['invalid_address_rejected'] ||
		! $result['new_order_flag_before_repeats']
	) {
		WP_CLI::line( wp_json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
		dentall_d77_fail( 'D77 成功矩阵未达到预期，请保留隔离副本排查。' );
	}

	WP_CLI::line( wp_json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
}

/**
 * 在调用命令把 SMTP 端口指向关闭端口时，验证失败可观察性。
 *
 * @param array $context 环境上下文。
 * @return void
 */
function dentall_d77_run_failure( $context ) {
	if ( get_option( DENTALL_D77_FAILURE_STATE_OPTION ) ) {
		dentall_d77_fail( '已有D77失败用例状态；请先运行cleanup，避免覆盖恢复索引。' );
	}

	$before = dentall_d77_log_counts( $context['log_table'] );
	$events = array();
	add_action(
		'woocommerce_email_sent',
		static function ( $success, $email_id ) use ( &$events ) {
			$events[] = array(
				'email_id' => (string) $email_id,
				'success'  => (bool) $success,
			);
		},
		10,
		2
	);
	$order  = dentall_d77_create_order( 'smtp-failure', 'customer.d77.failure@example.test', $context['marker'] );
	update_option(
		DENTALL_D77_FAILURE_STATE_OPTION,
		array(
			'marker'   => $context['marker'],
			'order_id' => $order->get_id(),
		),
		false
	);
	$emails = WC()->mailer()->get_emails();

	$emails['WC_Email_Customer_Invoice']->trigger( $order->get_id(), $order );
	$after = dentall_d77_log_counts( $context['log_table'] );

	$marker = $order->get_meta( DENTALL_D77_MARKER_META, true );
	if ( $context['marker'] !== $marker ) {
		dentall_d77_fail( '安全中止：失败用例订单缺少隔离标记，拒绝清理。' );
	}
	$failure_order_id = $order->get_id();
	$order->delete( true );
	if ( wc_get_order( $failure_order_id ) ) {
		dentall_d77_fail( '失败用例订单未能删除；恢复索引已保留。' );
	}
	delete_option( DENTALL_D77_FAILURE_STATE_OPTION );

	$result = array(
		'case'                    => 'failure',
		'expected_new_failed_logs' => 1,
		'actual_new_failed_logs'   => $after['failed'] - $before['failed'],
		'actual_new_sent_logs'     => $after['sent'] - $before['sent'],
		'actual_new_total_logs'    => $after['total'] - $before['total'],
		'email_events'             => $events,
		'fallback_connection_empty'=> true,
		'failure_order_deleted'    => true,
	);

	if (
		1 !== $result['actual_new_failed_logs'] ||
		0 !== $result['actual_new_sent_logs'] ||
		1 !== $result['actual_new_total_logs'] ||
		array( array( 'email_id' => 'customer_invoice', 'success' => false ) ) !== $result['email_events']
	) {
		WP_CLI::line( wp_json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
		dentall_d77_fail( 'SMTP 失败路径未留下预期的单条失败日志。' );
	}

	WP_CLI::line( wp_json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
}

/**
 * 只删除状态文件中列出的、仍带相同隔离标记的 TEST 订单。
 *
 * @param array $context 环境上下文。
 * @return void
 */
function dentall_d77_run_cleanup( $context ) {
	$state         = get_option( DENTALL_D77_STATE_OPTION, false );
	$failure_state = get_option( DENTALL_D77_FAILURE_STATE_OPTION, false );
	$has_main      = is_array( $state );
	$has_failure   = is_array( $failure_state );

	if ( ! $has_main && ! $has_failure ) {
		dentall_d77_fail( '没有可安全清理的D77测试状态。' );
	}
	if ( $has_main && ( $context['marker'] !== ( $state['marker'] ?? '' ) || ! isset( $state['order_ids'] ) || ! is_array( $state['order_ids'] ) ) ) {
		dentall_d77_fail( '安全中止：D77主测试状态无效。' );
	}
	if ( $has_failure && ( $context['marker'] !== ( $failure_state['marker'] ?? '' ) || empty( $failure_state['order_id'] ) ) ) {
		dentall_d77_fail( '安全中止：D77失败用例状态无效。' );
	}

	$orders  = array();
	$missing = array();
	foreach ( $has_main ? $state['order_ids'] : array() as $label => $order_id ) {
		$order = wc_get_order( absint( $order_id ) );
		if ( ! $order ) {
			$missing[ $label ] = absint( $order_id );
			continue;
		}
		if ( $context['marker'] !== $order->get_meta( DENTALL_D77_MARKER_META, true ) ) {
			dentall_d77_fail( '安全中止：测试订单隔离标记不匹配，尚未删除任何订单。' );
		}
		$orders[ $label ] = $order;
	}

	if ( $has_failure ) {
		$failure_order = wc_get_order( absint( $failure_state['order_id'] ) );
		if ( $failure_order && $context['marker'] !== $failure_order->get_meta( DENTALL_D77_MARKER_META, true ) ) {
			dentall_d77_fail( '安全中止：失败用例状态或订单标记不匹配，尚未删除任何订单。' );
		}
		if ( $failure_order ) {
			$orders['failure_probe'] = $failure_order;
		}
	}

	$deleted = array();
	foreach ( $orders as $label => $order ) {
		$deleted[ $label ] = $order->get_id();
		$order->delete( true );
	}

	foreach ( $deleted as $order_id ) {
		if ( wc_get_order( $order_id ) ) {
			dentall_d77_fail( 'D77 TEST订单删除后仍可读取；恢复索引已保留。' );
		}
	}

	delete_option( DENTALL_D77_STATE_OPTION );
	delete_option( DENTALL_D77_FAILURE_STATE_OPTION );
	if ( false !== get_option( DENTALL_D77_STATE_OPTION, false ) || false !== get_option( DENTALL_D77_FAILURE_STATE_OPTION, false ) ) {
		dentall_d77_fail( 'D77 TEST状态未完全删除。' );
	}
	WP_CLI::line(
		wp_json_encode(
			array(
				'case'           => 'cleanup',
				'cleanup_scope'  => 'test_orders_and_state_only',
				'deleted_orders' => $deleted,
				'already_missing'=> $missing,
				'state_removed'  => false === get_option( DENTALL_D77_STATE_OPTION, false ),
			),
			JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		)
	);
}

$case = getenv( 'DENTALL_D77_CASE' );
if ( false === $case || '' === $case ) {
	dentall_d77_fail( '必须显式设置DENTALL_D77_CASE；脚本不会默认发送邮件。' );
}

$context = dentall_d77_guard_environment( $case );

switch ( $case ) {
	case 'success':
		dentall_d77_run_success( $context );
		break;
	case 'failure':
		dentall_d77_run_failure( $context );
		break;
	case 'cleanup':
		dentall_d77_run_cleanup( $context );
		break;
	default:
		dentall_d77_fail( '未知用例；仅允许 success、failure、cleanup。' );
}
