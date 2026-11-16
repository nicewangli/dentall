<?php
/**
 * 站内运费申请：复用Fluent Forms条目和通知，只信任当前Woo会话的购物车。
 */

defined( 'ABSPATH' ) || exit;

/** 判断当前表单是否为独立配置的运费申请表单。 */
function dentall_core_is_shipping_request_form( $form_id ) {
	$configured_id = dentall_core_get_shipping_request_form_id();
	return $configured_id > 0 && $configured_id === absint( $form_id );
}

/**
 * 构建服务器快照与会话绑定nonce；申请不会锁价、预留库存或创建订单。
 *
 * @return array{text:string,token:string,fingerprint:string}|WP_Error
 */
function dentall_core_get_shipping_request_snapshot() {
	if ( ! function_exists( 'WC' ) || ! WC()->session || ! WC()->cart instanceof WC_Cart ) {
		return new WP_Error( 'session', __( 'Your cart session is unavailable. Reload the cart and try again.', 'dentall-core' ) );
	}

	$cart = WC()->cart;
	if ( $cart->is_empty() || ! dentall_core_cart_requires_shipping_quote( $cart ) ) {
		return new WP_Error( 'empty', __( 'Add a product that requires shipping before requesting a quote.', 'dentall-core' ) );
	}

	$items      = array();
	$quantities = $cart->get_cart_item_quantities();
	foreach ( $cart->get_cart() as $key => $item ) {
		$product = wc_get_product( $item['variation_id'] ?: $item['product_id'] );
		$parent  = $product instanceof WC_Product && $product->is_type( 'variation' ) ? wc_get_product( $product->get_parent_id() ) : $product;
		$quantity = $item['quantity'];
		if (
			! $product instanceof WC_Product || ! $parent instanceof WC_Product
			|| 'publish' !== $product->get_status() || 'publish' !== $parent->get_status()
			|| '' !== $parent->get_post_password( 'edit' ) || ! $product->is_purchasable()
			|| ! $product->is_in_stock() || ! is_numeric( $quantity ) || $quantity <= 0
			|| ! $product->has_enough_stock( $quantities[ $product->get_stock_managed_by_id() ] ?? $quantity )
			|| ( $product->is_sold_individually() && 1 !== (int) $quantity )
		) {
			return new WP_Error( 'product', __( 'A cart product is no longer available in the requested quantity. Review your cart and try again.', 'dentall-core' ) );
		}

		// 更新本次请求内的商品对象，使参考金额与刚复核的商品事实一致。
		$cart->cart_contents[ $key ]['data'] = $product;
		$items[ $key ] = array(
			'product_id'   => $item['product_id'],
			'variation_id' => $item['variation_id'],
			'name'         => $product->get_name(),
			'sku'          => $product->get_sku(),
			'variation'    => wc_get_formatted_cart_item_data( $item, true ),
			'quantity'     => wc_stock_amount( $quantity ),
			'price'        => $product->get_price(),
		);
	}

	$stock_check = $cart->check_cart_item_stock();
	if ( is_wp_error( $stock_check ) ) {
		return new WP_Error( 'stock', __( 'Stock has changed. Review your cart and try again.', 'dentall-core' ) );
	}

	$cart->calculate_totals();
	$currency = get_woocommerce_currency();
	$lines    = array( sprintf( __( 'Currency: %s', 'dentall-core' ), $currency ) );
	foreach ( $items as $item ) {
		$lines[] = sprintf(
			/* translators: 1: name, 2: product ID, 3: variation ID, 4: SKU, 5: quantity. */
			__( '%1$s | Product #%2$d | Variation #%3$d | SKU: %4$s | Quantity: %5$s', 'dentall-core' ),
			$item['name'], $item['product_id'], $item['variation_id'], $item['sku'], $item['quantity']
		);
		if ( $item['variation'] ) {
			$lines[] = $item['variation'];
		}
	}
	$lines[] = sprintf( __( 'Reference product subtotal (before discounts): %s', 'dentall-core' ), $cart->get_cart_subtotal() );
	$lines[] = sprintf( __( 'Applied coupon codes: %s', 'dentall-core' ), implode( ', ', $cart->get_applied_coupons() ) ?: __( 'None', 'dentall-core' ) );
	$lines[] = __( 'Shipping, tax and the final total require manual confirmation. This request is not an order or a price or stock reservation.', 'dentall-core' );
	$text = wp_strip_all_tags( html_entity_decode( implode( "\n", $lines ), ENT_QUOTES, 'UTF-8' ) );
	$fingerprint = hash( 'sha256', wp_json_encode( array( $items, $currency, $cart->get_totals(), $cart->get_applied_coupons() ) ) );
	$timestamp   = time();
	$action      = 'dentall_shipping_request|' . WC()->session->get_customer_id() . '|' . $fingerprint . '|' . $timestamp;

	return array( 'text' => $text, 'token' => $timestamp . ':' . wp_create_nonce( $action ), 'fingerprint' => $fingerprint );
}

/** 仅在渲染指定申请表单时生成令牌，不从URL或隐藏字段读取商品事实。 */
function dentall_core_render_shipping_request_token( $field, $form ) {
	if ( dentall_core_is_shipping_request_form( $form->id ?? 0 ) && 'dentall_request_token' === ( $field['attributes']['name'] ?? '' ) ) {
		$snapshot = dentall_core_get_shipping_request_snapshot();
		$field['attributes']['value'] = is_wp_error( $snapshot ) ? '' : $snapshot['token'];
	}
	return $field;
}
add_filter( 'fluentform/rendering_field_data_input_hidden', 'dentall_core_render_shipping_request_token', 10, 2 );

/** 已登录客户只读预填；整组选择地址来源，不将普通电话推断为WhatsApp。 */
function dentall_core_prefill_shipping_request_customer( $field, $form ) {
	$name = $field['attributes']['name'] ?? '';
	$address_fields = array( 'first_name', 'last_name', 'company', 'country', 'state', 'city', 'postcode', 'address_1', 'address_2' );
	if ( ! is_user_logged_in() || ! dentall_core_is_shipping_request_form( $form->id ?? 0 ) || ! in_array( $name, array_merge( array( 'email' ), $address_fields ), true ) ) {
		return $field;
	}

	static $values = null;
	if ( null === $values ) {
		$customer = new WC_Customer( get_current_user_id() );
		$values = array();
		$values['email'] = $customer->get_billing_email() ?: wp_get_current_user()->user_email;
		$address_type = 'billing';
		// 只有默认国家、没有地址内容时仍采用已保存的账单地址。
		foreach ( array( 'address_1', 'address_2', 'city', 'state', 'postcode' ) as $part ) {
			$getter = 'get_shipping_' . $part;
			if ( '' !== trim( (string) $customer->{$getter}() ) ) {
				$address_type = 'shipping';
				break;
			}
		}
		foreach ( $address_fields as $part ) {
			$getter = 'get_' . $address_type . '_' . $part;
			$values[ $part ] = $customer->{$getter}();
		}
		if ( ! in_array( $values['country'], DENTALL_SHIPPING_QUOTE_ALLOWED_COUNTRIES, true ) ) {
			$values['country'] = '';
		}
	}
	$field['attributes']['value'] = $values[ $name ];
	return $field;
}
add_filter( 'fluentform/rendering_field_data_input_text', 'dentall_core_prefill_shipping_request_customer', 10, 2 );
add_filter( 'fluentform/rendering_field_data_input_email', 'dentall_core_prefill_shipping_request_customer', 10, 2 );
add_filter( 'fluentform/rendering_field_data_select', 'dentall_core_prefill_shipping_request_customer', 10, 2 );

/**
 * 独立于客户端规则校验资料、会话和购物车，再获取60秒会话锁。
 * 原生FF防机器人token不是一次性token；锁使用WP原生transient清理机制。
 */
function dentall_core_validate_shipping_request( $errors, $data, $form ) {
	if ( ! dentall_core_is_shipping_request_form( $form->id ?? 0 ) ) {
		// 关闭入口或误配ID后，已导入的申请表也不能通过旧AJAX请求绕过校验。
		$fields = \FluentForm\App\Modules\Form\FormFieldsParser::getInputs( $form );
		if ( isset( $fields['dentall_request_token'] ) ) {
			$errors['dentall_request_token'][] = __( 'Shipping requests are temporarily unavailable. Please contact us.', 'dentall-core' );
		}
		return $errors;
	}

	$clean = array();
	$fields = array(
		'first_name' => array( 'label' => __( 'First name', 'dentall-core' ), 'limit' => 100 ),
		'last_name'  => array( 'label' => __( 'Last name', 'dentall-core' ), 'limit' => 100 ),
		'email'      => array( 'label' => __( 'Email', 'dentall-core' ), 'limit' => 254 ),
		'whatsapp'   => array( 'label' => __( 'WhatsApp number', 'dentall-core' ), 'limit' => 40 ),
		'company'    => array( 'label' => __( 'Company', 'dentall-core' ), 'limit' => 200 ),
		'country'    => array( 'label' => __( 'Shipping country', 'dentall-core' ), 'limit' => 2 ),
		'state'      => array( 'label' => __( 'State / province', 'dentall-core' ), 'limit' => 100 ),
		'city'       => array( 'label' => __( 'City', 'dentall-core' ), 'limit' => 100 ),
		'postcode'   => array( 'label' => __( 'Postal / ZIP code', 'dentall-core' ), 'limit' => 20 ),
		'address_1'  => array( 'label' => __( 'Street address', 'dentall-core' ), 'limit' => 200 ),
		'address_2'  => array( 'label' => __( 'Apartment / suite', 'dentall-core' ), 'limit' => 200 ),
		'message'    => array( 'label' => __( 'Additional details', 'dentall-core' ), 'limit' => 3000 ),
	);
	foreach ( $fields as $name => $settings ) {
		$value = isset( $data[ $name ] ) && is_string( $data[ $name ] ) ? trim( $data[ $name ] ) : '';
		$clean[ $name ] = 'message' === $name ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
		if ( strlen( $value ) > $settings['limit'] || ( '' === $clean[ $name ] && ! in_array( $name, array( 'company', 'address_2', 'message' ), true ) ) ) {
			$errors[ $name ][] = sprintf( __( '%s: enter a valid value within the field length limit.', 'dentall-core' ), $settings['label'] );
		}
	}
	if ( ! is_email( $clean['email'] ) || sanitize_email( $clean['email'] ) !== $clean['email'] ) {
		$errors['email'][] = __( 'Enter a valid email address.', 'dentall-core' );
	}
	if ( ! isset( $data['privacy_consent'] ) || ! is_array( $data['privacy_consent'] ) || array( 'shipping_request_v1' ) !== array_values( $data['privacy_consent'] ) ) {
		$errors['privacy_consent'][] = __( 'Please confirm how we may use your details for this shipping request.', 'dentall-core' );
	}
	// 版本化确认与Entry原生created_at共同留存，不扩展为营销同意。
	$clean['privacy_consent'] = array( 'shipping_request_v1' );
	$clean['whatsapp'] = preg_replace( '/[\s().-]/', '', $clean['whatsapp'] );
	if ( 1 !== preg_match( '/^\+[1-9][0-9]{6,14}$/', $clean['whatsapp'] ) ) {
		$errors['whatsapp'][] = __( 'Include + and the country code, followed by 7 to 15 digits.', 'dentall-core' );
	}
	$clean['country'] = strtoupper( $clean['country'] );
	if ( ! in_array( $clean['country'], DENTALL_SHIPPING_QUOTE_ALLOWED_COUNTRIES, true ) ) {
		$errors['country'][] = __( 'Select United States, Canada or Australia.', 'dentall-core' );
	} elseif ( function_exists( 'WC' ) && WC()->countries ) {
		$states = WC()->countries->get_states( $clean['country'] );
		$state  = strtoupper( $clean['state'] );
		foreach ( (array) $states as $code => $name ) {
			if ( 0 === strcasecmp( $clean['state'], $name ) ) {
				$state = $code;
				break;
			}
		}
		if ( ! isset( $states[ $state ] ) ) {
			$errors['state'][] = __( 'Enter a valid state or province name or code for the selected country.', 'dentall-core' );
		}
		$clean['state'] = $state;
		$clean['postcode'] = wc_format_postcode( $clean['postcode'], $clean['country'] );
		if ( ! WC_Validation::is_postcode( $clean['postcode'], $clean['country'] ) || ( 'AU' === $clean['country'] && 1 !== preg_match( '/^[0-9]{4}$/', $clean['postcode'] ) ) ) {
			$errors['postcode'][] = __( 'Enter a valid postal code for the selected country.', 'dentall-core' );
		}
	}

	$snapshot = dentall_core_get_shipping_request_snapshot();
	$token    = isset( $data['dentall_request_token'] ) && is_string( $data['dentall_request_token'] ) ? $data['dentall_request_token'] : '';
	if ( is_wp_error( $snapshot ) ) {
		$errors['dentall_request_token'][] = $snapshot->get_error_message();
		return $errors;
	}
	$parts     = explode( ':', $token );
	$timestamp = count( $parts ) === 2 && ctype_digit( $parts[0] ) ? (int) $parts[0] : 0;
	$action    = 'dentall_shipping_request|' . WC()->session->get_customer_id() . '|' . $snapshot['fingerprint'] . '|' . $timestamp;
	if ( $timestamp > time() || $timestamp < time() - 30 * MINUTE_IN_SECONDS || ! wp_verify_nonce( $parts[1] ?? '', $action ) ) {
		$errors['dentall_request_token'][] = __( 'Your cart changed or this form expired. Reload the cart and try again.', 'dentall-core' );
	}
	if ( $timestamp <= (int) WC()->session->get( 'dentall_shipping_request_submitted_at', 0 ) ) {
		$errors['dentall_request_token'][] = __( 'This request has already been saved. Reload the cart only if you need to send a new request.', 'dentall-core' );
	}
	if ( $errors ) {
		return $errors;
	}

	$key = 'dentall_shipping_request_' . hash( 'sha256', (string) WC()->session->get_customer_id() );
	$locked = false;
	if ( false === get_transient( $key ) ) {
		if ( wp_using_ext_object_cache() ) {
			$locked = wp_cache_add( $key, 'pending', 'transient', MINUTE_IN_SECONDS );
		} else {
			// add_option的唯一键让并发请求只有一个成功；timeout由WP过期transient清理。
			add_option( '_transient_timeout_' . $key, time() + MINUTE_IN_SECONDS, '', false );
			$locked = add_option( '_transient_' . $key, 'pending', '', false );
		}
	}
	if ( ! $locked ) {
		$errors['dentall_request_token'][] = __( 'A request was just submitted. Please wait one minute before trying again.', 'dentall-core' );
		return $errors;
	}

	$clean['dentall_cart_snapshot'] = $snapshot['text'];
	$GLOBALS['dentall_shipping_request_validated'] = array( 'data' => $clean, 'timestamp' => $timestamp );
	return $errors;
}
add_filter( 'fluentform/validation_errors', 'dentall_core_validate_shipping_request', 20, 3 );

/** 入库和原生通知共享白名单规范化数据；不保存nonce，也不采信提交的快照。 */
function dentall_core_normalize_shipping_request( $data, $form_id ) {
	if ( ! dentall_core_is_shipping_request_form( $form_id ) ) {
		return $data;
	}
	$validated = $GLOBALS['dentall_shipping_request_validated'] ?? null;
	if ( ! is_array( $validated ) ) {
		wp_send_json( array( 'errors' => array( 'dentall_request_token' => array( __( 'Reload the cart and try again.', 'dentall-core' ) ) ) ), 422 );
	}
	return $validated['data'];
}
add_filter( 'fluentform/insert_response_data', 'dentall_core_normalize_shipping_request', 20, 2 );

/** 条目保存后消耗本次及更早的表单令牌；通知失败也不会重复创建条目。 */
function dentall_core_record_shipping_request_submission( $entry_id, $data, $form ) {
	if ( $entry_id && dentall_core_is_shipping_request_form( $form->id ?? 0 ) && isset( $GLOBALS['dentall_shipping_request_validated'] ) ) {
		WC()->session->set( 'dentall_shipping_request_submitted_at', $GLOBALS['dentall_shipping_request_validated']['timestamp'] );
		WC()->session->save_data();
	}
}
add_action( 'fluentform/submission_inserted', 'dentall_core_record_shipping_request_submission', 1, 3 );

/** 申请表独立启用原生Honeypot，不改变既有Contact表单规则。 */
function dentall_core_enable_shipping_request_honeypot( $enabled, $form_id ) {
	return dentall_core_is_shipping_request_form( $form_id ) ? true : $enabled;
}
add_filter( 'fluentform/honeypot_status', 'dentall_core_enable_shipping_request_honeypot', 10, 2 );

/** 为申请表启用FF原生防机器人Token；false为插件注册令牌AJAX处理器时的探测。 */
function dentall_core_enable_shipping_request_spam_token( $enabled, $form_id ) {
	if ( false === $form_id ) {
		return absint( get_option( DENTALL_SHIPPING_REQUEST_FORM_OPTION, 0 ) ) > 0 ? true : $enabled;
	}
	return dentall_core_is_shipping_request_form( $form_id ) ? true : $enabled;
}
add_filter( 'fluentform/token_based_spam_protection_status', 'dentall_core_enable_shipping_request_spam_token', 10, 2 );

/** 仅记录客户主动填写的地址；不额外记录IP或请求头推导的位置。 */
function dentall_core_disable_shipping_request_location_log( $disabled, $form_id ) {
	return dentall_core_is_shipping_request_form( $form_id ) ? true : $disabled;
}
add_filter( 'fluentform/disable_ip_logging', 'dentall_core_disable_shipping_request_location_log', 10, 2 );
add_filter( 'fluentform/disable_submission_country_detection', 'dentall_core_disable_shipping_request_location_log', 10, 2 );
