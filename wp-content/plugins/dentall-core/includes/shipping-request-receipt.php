<?php
/** 报价申请客户回执。初次提交的商品快照独立于后续人工报价及付款邮件。 */

defined( 'ABSPATH' ) || exit;

/** 只给申请回执使用站点配色，交由 Woo 将样式内联到邮件。 */
add_filter( 'woocommerce_email_styles', function ( $css, $email ) {
	if ( 'dentall_shipping_request_receipt' === $email->id ) {
		$css .= '
			#template_container, #body_content { background-color: #fff; }
			#template_header { background-color: #082346; }
			#header_wrapper h1 { color: #fff; }
			#body_content h2, #body_content a, .email-logo-text { color: #082346; }
			#body_content th.td { background-color: #eef4fb; color: #082346; }
		';
	}
	return $css;
}, 10, 2 );

/** 邮件客户端不会加载主题 CSS，表格宽度及长词换行需随邮件一同发送。 */
function dentall_core_shipping_request_receipt_body( $entry_id, $first_name, $items, $reply_to ) {
	$heading = sprintf( __( 'Quote request #%d received', 'dentall-core' ), $entry_id );
	$html = wc_get_template_html( 'emails/email-header.php', array( 'email_heading' => $heading ) );
	$html .= '<p>' . esc_html( sprintf( __( 'Hi %s,', 'dentall-core' ), $first_name ) ) . '</p>';
	$html .= '<p>' . esc_html( sprintf( __( 'We’ve received your quote request #%d. Here’s a copy of the items you submitted.', 'dentall-core' ), $entry_id ) ) . '</p>';
	$html .= '<table class="td font-family" width="100%" cellpadding="6" cellspacing="0" style="table-layout:fixed"><thead><tr>'
		. '<th class="td text-align-left" scope="col">' . esc_html__( 'Requested items', 'dentall-core' ) . '</th>'
		. '<th class="td text-align-right" scope="col" width="18%">' . esc_html__( 'Qty', 'dentall-core' ) . '</th></tr></thead><tbody>';
	foreach ( $items as $item ) {
		$html .= '<tr><td class="td text-align-left" style="overflow-wrap:anywhere;word-wrap:break-word">'
			. esc_html( $item['name'] );
		$variation = trim( html_entity_decode( wp_strip_all_tags( $item['variation'] ), ENT_QUOTES, 'UTF-8' ) );
		if ( '' !== $variation ) {
			$html .= '<br><small>' . nl2br( esc_html( $variation ) ) . '</small>';
		}
		if ( '' !== $item['sku'] ) {
			$html .= '<br><small>' . esc_html( sprintf( __( 'SKU: %s', 'dentall-core' ), $item['sku'] ) ) . '</small>';
		}
		$html .= '</td><td class="td text-align-right">' . esc_html( $item['quantity'] ) . '</td></tr>';
	}
	$html .= '</tbody></table><h2>' . esc_html__( 'What happens next?', 'dentall-core' ) . '</h2>';
	$html .= '<p>' . esc_html__( 'Our team will review your items and confirm shipping. We’ll send you a final quote with the total and a payment link. No payment is due at this stage.', 'dentall-core' ) . '</p>';
	$html .= '<p>' . esc_html( sprintf( __( 'Need to change an item or quantity? Just reply to this email with the changes you’d like and mention #%d.', 'dentall-core' ), $entry_id ) ) . '</p>';
	$html .= '<p>' . esc_html__( 'DentAll Dental Solutions', 'dentall-core' ) . '<br><a href="' . esc_url( 'mailto:' . $reply_to ) . '">' . esc_html( $reply_to ) . '</a></p>';
	return $html . wc_get_template_html( 'emails/email-footer.php' );
}

/** 仅由待建条目首次成功转为已关联订单的分支调用；邮件失败也要让原来的公司通知继续。 */
function dentall_core_send_shipping_request_receipt( $entry_id, $order, $data, $items ) {
	$accepted = false;
	try {
		$reply_to = dentall_core_get_shipping_quote_email();
		if ( $reply_to && is_email( $data['email'] ) ) {
			// 申请表无需经过原生结账，邮件类可能尚未加载。
			WC()->mailer();
			$mail = new WC_Email();
			$mail->id = 'dentall_shipping_request_receipt';
			$mail->email_type = 'html';
			$body = dentall_core_shipping_request_receipt_body( $entry_id, $data['first_name'], $items, $reply_to );
			$accepted = wp_mail(
				$data['email'],
				sprintf( __( 'DentAll | Quote request #%d received', 'dentall-core' ), $entry_id ),
				$mail->style_inline( $body ),
				array( 'Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $reply_to )
			);
		}
	} catch ( Throwable $error ) {
		// 邮件插件异常可能发生在交付途中；由业务先查现有 SMTP 日志，避免重复发送。
	}
	$context = array( 'source' => 'dentall-shipping-request', 'entry_id' => $entry_id, 'order_id' => $order->get_id() );
	if ( ! $accepted ) {
		wc_get_logger()->warning( 'Customer receipt handoff was not confirmed; check the mail log before following up.', $context );
	}
	try {
		$note = $accepted
			? __( 'Customer receipt for quote request #%d was accepted by the mail system.', 'dentall-core' )
			: __( 'Customer receipt for quote request #%d needs review. Check the mail log and follow up with the customer.', 'dentall-core' );
		$order->add_order_note( sprintf( $note, $entry_id ) );
	} catch ( Throwable $error ) {
		wc_get_logger()->warning( 'Could not record the customer receipt status on the order.', $context );
	}
}
