<?php
/**
 * D95 真实 Woo CRUD/HPOS 合同；仅通过隔离 Local 的 wp eval-file 运行。
 * 模拟网关实体与 Hook 不等于 PayPal Sandbox 收款验收。
 */
defined( 'ABSPATH' ) || exit;
use WooCommerce\PayPalCommerce\ApiClient\Entity\Amount;
use WooCommerce\PayPalCommerce\ApiClient\Entity\Capture;
use WooCommerce\PayPalCommerce\ApiClient\Entity\CaptureStatus;
use WooCommerce\PayPalCommerce\ApiClient\Entity\Money;
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type()
    || ! in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( '127.0.0.1', 'localhost' ), true )
    || ! defined( 'DISABLE_WP_CRON' ) || ! DISABLE_WP_CRON
    || ! defined( 'WP_HTTP_BLOCK_EXTERNAL' ) || true !== WP_HTTP_BLOCK_EXTERNAL
    || PHP_INT_MAX !== has_filter( 'pre_wp_mail', '__return_true' )
    || ! function_exists( 'dentall_measurement_record_capture' ) ) {
    throw new RuntimeException( '仅允许已阻断邮件、Cron且启用测量插件的隔离 Local。' );
}
$remote = wp_remote_get( 'https://example.invalid/d95-contract', array( 'timeout' => 1 ) );
if ( ! is_wp_error( $remote ) || ! in_array( $remote->get_error_code(), array( 'day9294_isolated_runtime', 'http_request_not_executed' ), true ) ) {
    throw new RuntimeException( '运行前必须阻断外部 WordPress HTTP。' );
}
$checks = 0;
$product = null;
$order = null;
$verify = static function ( $ok, $message ) use ( &$checks ) {
    ++$checks;
    if ( ! $ok ) {
        throw new RuntimeException( '断言失败：' . $message );
    }
};
$capture = static function ( $id, $amount, $currency = 'USD', $status = 'COMPLETED', $final = true ) {
    return new Capture( 'TESTD95CAPTURE', new CaptureStatus( $status ), new Amount( new Money( (float) $amount, $currency ) ), $final, '', '', (string) $id, null, null );
};
try {
    $product = new WC_Product_Simple();
    $product->set_name( 'TEST D95 measurement contract' );
    $product->set_status( 'private' );
    $product->set_virtual( true );
    $product->set_regular_price( '24.99' );
    $product->save();
    $order = wc_create_order( array( 'status' => 'pending', 'created_via' => 'd95-contract' ) );
    $order->set_currency( 'USD' );
    $order->set_payment_method( 'ppcp-gateway' );
    $order->add_product( $product, 2 );
    $order->calculate_totals( false );
    $order->save();
    $proof = array( 'capture_id' => 'TESTD95CAPTURE', 'amount' => '49.98', 'currency' => 'USD' );
    foreach ( array( 'pending', 'on-hold', 'failed', 'cancelled', 'processing', 'completed' ) as $status ) {
        $order->set_status( $status );
        $order->save();
        $verify( ! dentall_measurement_capture_matches_order( $order, $order->get_meta( '_dentall_measurement_paypal_capture', true ) ), '状态本身不能证明收款' );
    }
    foreach ( array( 'pending', 'on-hold', 'failed', 'cancelled' ) as $status ) {
        $order->set_status( $status );
        $verify( ! dentall_measurement_capture_matches_order( $order, $proof ), '未付终态不允许证明' );
    }
    $order->set_status( 'processing' );
    $order->save();
    foreach ( array(
        $capture( $order->get_id(), '49.98', 'USD', 'PENDING' ),
        $capture( $order->get_id(), '49.98', 'USD', 'FAILED' ),
        $capture( $order->get_id(), '49.98', 'USD', 'COMPLETED', false ),
        $capture( $order->get_id() + 1, '49.98' ),
        $capture( $order->get_id(), '49.97' ),
        $capture( $order->get_id(), '49.98', 'CAD' ),
    ) as $invalid ) {
        do_action( 'woocommerce_paypal_payments_order_captured', $order, $invalid );
        $verify( ! $order->get_meta( '_dentall_measurement_paypal_capture', true ), '不完整/不匹配捕获不得写入证明' );
    }
    dentall_measurement_record_capture( $order, new stdClass() );
    $verify( ! $order->get_meta( '_dentall_measurement_paypal_capture', true ), '错误实体类型失败关闭' );
    $order->set_payment_method( 'cod' );
    do_action( 'woocommerce_paypal_payments_order_captured', $order, $capture( $order->get_id(), '49.98' ) );
    $verify( ! $order->get_meta( '_dentall_measurement_paypal_capture', true ), '其它网关不接受PayPal证明' );
    $order->set_payment_method( 'ppcp-gateway' );
    $order->set_transaction_id( 'TESTD95CAPTURE' );
    $order->save();
    do_action( 'woocommerce_paypal_payments_order_captured', $order, $capture( $order->get_id(), '49.98' ) );
    $fresh = wc_get_order( $order->get_id() );
    $verify( $fresh->get_meta( '_dentall_measurement_paypal_capture', true ) === $proof, '完成捕获Hook写入且仅含三项最小证明' );
    $verify( dentall_measurement_capture_matches_order( $fresh, $proof ), '金额/币种/网关/状态正确的证明可用' );
    $bad = $proof;
    $bad['amount'] = '049.980';
    $verify( ! dentall_measurement_capture_matches_order( $fresh, $bad ), '非规范金额失败关闭' );
    $verify( \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled(), '本次运行覆盖HPOS' );
} finally {
    if ( $order instanceof WC_Order ) {
        $order->delete( true );
    }
    if ( $product instanceof WC_Product ) {
        $product->delete( true );
    }
    unset( $GLOBALS['dentall_measurement_events'], $GLOBALS['dentall_measurement_cart_events'] );
}
WP_CLI::success( sprintf( 'D95测量合同 %d/%d；TEST对象已由CRUD精确删除。', $checks, $checks ) );
