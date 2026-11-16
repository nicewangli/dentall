<?php
/**
 * 站内运费申请：复用Fluent Forms条目和通知，只信任当前Woo会话的购物车。
 */

defined( 'ABSPATH' ) || exit;

// 固定token锁保存在DB；外部对象缓存启用时仍复用WP每日任务清理已到期DB transient。
add_action( 'delete_expired_transients', function () {
	if ( wp_using_ext_object_cache() ) {
		delete_expired_transients( true );
	}
} );

/** 判断当前表单是否为独立配置的运费申请表单。 */
function dentall_core_is_shipping_request_form( $form_id ) {
	$configured_id = dentall_core_get_shipping_request_form_id();
	return $configured_id > 0 && $configured_id === absint( $form_id );
}

/**
 * 构建服务器快照与会话绑定nonce；参考价格不会锁价或预留库存。
 *
 * @return array|WP_Error 文字供人阅读，结构化项目供创建订单；绝不反向解析文字。
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
			'attributes'   => $item['variation'],
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
	foreach ( $items as $key => $item ) {
		$items[ $key ]['subtotal'] = wc_format_decimal( $cart->cart_contents[ $key ]['line_subtotal'] );
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
	$lines[] = sprintf( __( 'Reference product total after cart discounts, excluding tax: %s', 'dentall-core' ), wc_price( $cart->get_cart_contents_total(), array( 'currency' => $currency ) ) );
	$lines[] = __( 'The pending quote uses product prices before coupons. Coupons, shipping, tax and the final total require staff confirmation. Prices and stock are not reserved.', 'dentall-core' );
	$text = wp_strip_all_tags( html_entity_decode( implode( "\n", $lines ), ENT_QUOTES, 'UTF-8' ) );
	$fingerprint = hash( 'sha256', wp_json_encode( array( $items, $currency, $cart->get_totals(), $cart->get_applied_coupons() ) ) );
	$timestamp   = time();
	$action      = 'dentall_shipping_request|' . WC()->session->get_customer_id() . '|' . $fingerprint . '|' . $timestamp;

	return array(
		'text' => $text, 'token' => $timestamp . ':' . wp_create_nonce( $action ), 'fingerprint' => $fingerprint,
		'items' => $items, 'currency' => $currency, 'coupons' => $cart->get_applied_coupons(),
	);
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
	if ( ! dentall_core_is_shipping_request_form( $form->id ?? 0 ) ) {
		return $field;
	}
	$address_fields = array( 'first_name', 'last_name', 'company', 'country', 'state', 'city', 'postcode', 'address_1', 'address_2' );
	$billing_fields = array_map( static fn( $part ) => 'billing_' . $part, $address_fields );
	if ( ! is_user_logged_in() || ! in_array( $name, array_merge( array( 'email' ), $address_fields, $billing_fields ), true ) ) {
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
			$billing_getter = 'get_billing_' . $part;
			$values[ 'billing_' . $part ] = $customer->{$billing_getter}();
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
add_filter( 'fluentform/rendering_field_data_select_country', 'dentall_core_prefill_shipping_request_customer', 10, 2 );

/** 按Woo国家地址合同校验两组地址；配送国家白名单在调用处额外限制。 */
function dentall_core_validate_shipping_request_address( &$clean, &$errors, $prefix, $address_type ) {
	$country_key = $prefix . 'country';
	$country = strtoupper( $clean[ $country_key ] );
	$clean[ $country_key ] = $country;
	if ( ! dentall_core_quote_country_exists( $country ) ) {
		$errors[ $country_key ][] = __( 'Select a valid country.', 'dentall-core' );
		return;
	}
	foreach ( dentall_core_get_quote_required_address_fields( $address_type, $country ) as $part ) {
		if ( '' === $clean[ $prefix . $part ] ) {
			$errors[ $prefix . $part ][] = __( 'This address field is required for the selected country.', 'dentall-core' );
		}
	}
	$state_key = $prefix . 'state';
	$states = WC()->countries->get_states( $country );
	if ( '' !== $clean[ $state_key ] && is_array( $states ) && $states ) {
		$state = strtoupper( $clean[ $state_key ] );
		foreach ( $states as $code => $label ) {
			if ( 0 === strcasecmp( $clean[ $state_key ], $label ) ) {
				$state = $code;
				break;
			}
		}
		if ( ! isset( $states[ $state ] ) ) {
			$errors[ $state_key ][] = __( 'Enter a valid state or province name or code for the selected country.', 'dentall-core' );
		}
		$clean[ $state_key ] = $state;
	}
	$postcode_key = $prefix . 'postcode';
	$clean[ $postcode_key ] = wc_format_postcode( $clean[ $postcode_key ], $country );
	if ( '' !== $clean[ $postcode_key ] && ( ! WC_Validation::is_postcode( $clean[ $postcode_key ], $country ) || ( 'AU' === $country && 1 !== preg_match( '/^[0-9]{4}$/', $clean[ $postcode_key ] ) ) ) ) {
		$errors[ $postcode_key ][] = __( 'Enter a valid postal code for the selected country.', 'dentall-core' );
	}
}

/**
 * 独立校验资料、会话和购物车，保留60秒节流并原子消费本次表单token。
 * token锁固定落DB；外部对象缓存可驱逐，不能独自承担交易幂等。
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
	$clean['billing_same_address'] = $data['billing_same_address'] ?? '';
	if ( ! in_array( $clean['billing_same_address'], array( 'yes', 'no' ), true ) ) {
		$errors['billing_same_address'][] = __( 'Confirm whether your billing address is the same as your shipping address.', 'dentall-core' );
	}
	$address_fields = array( 'first_name', 'last_name', 'company', 'country', 'state', 'city', 'postcode', 'address_1', 'address_2' );
	if ( 'no' === $clean['billing_same_address'] ) {
		foreach ( $address_fields as $part ) {
			$name = 'billing_' . $part;
			$value = isset( $data[ $name ] ) && is_string( $data[ $name ] ) ? trim( $data[ $name ] ) : '';
			$clean[ $name ] = sanitize_text_field( $value );
			if ( strlen( $value ) > $fields[ $part ]['limit'] ) {
				$errors[ $name ][] = __( 'Enter a value within the field length limit.', 'dentall-core' );
			}
		}
		dentall_core_validate_shipping_request_address( $clean, $errors, 'billing_', 'billing' );
	}
	if ( ! is_email( $clean['email'] ) || sanitize_email( $clean['email'] ) !== $clean['email'] ) {
		$errors['email'][] = __( 'Enter a valid email address.', 'dentall-core' );
	}
	if ( ! isset( $data['privacy_consent'] ) || ! is_array( $data['privacy_consent'] ) || array( 'shipping_request_v2' ) !== array_values( $data['privacy_consent'] ) ) {
		$errors['privacy_consent'][] = __( 'Please confirm how we may use your details for this shipping request.', 'dentall-core' );
	}
	// 版本化确认与Entry原生created_at共同留存，不扩展为营销同意。
	$clean['privacy_consent'] = array( 'shipping_request_v2' );
	$clean['whatsapp'] = preg_replace( '/[\s().-]/', '', $clean['whatsapp'] );
	if ( 1 !== preg_match( '/^\+[1-9][0-9]{6,14}$/', $clean['whatsapp'] ) ) {
		$errors['whatsapp'][] = __( 'Include + and the country code, followed by 7 to 15 digits.', 'dentall-core' );
	}
	$clean['country'] = strtoupper( $clean['country'] );
	if ( ! in_array( $clean['country'], DENTALL_SHIPPING_QUOTE_ALLOWED_COUNTRIES, true ) ) {
		$errors['country'][] = __( 'Select United States, Canada or Australia.', 'dentall-core' );
	} else {
		dentall_core_validate_shipping_request_address( $clean, $errors, '', 'shipping' );
	}
	if ( 'yes' === $clean['billing_same_address'] ) {
		foreach ( $address_fields as $part ) {
			$clean[ 'billing_' . $part ] = $clean[ $part ];
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

	$session_id = (string) WC()->session->get_customer_id();
	foreach ( array( 'session' => MINUTE_IN_SECONDS, 'token' => $timestamp + 31 * MINUTE_IN_SECONDS - time() ) as $type => $duration ) {
		$token_lock = 'token' === $type;
		$key = 'dentall_shipping_request_' . ( $token_lock ? 'token_' : '' ) . hash( 'sha256', $session_id . ( $token_lock ? '|' . $timestamp : '' ) );
		$locked = false;
		if ( $token_lock || false === get_transient( $key ) ) {
			if ( ! $token_lock && wp_using_ext_object_cache() ) {
				$locked = wp_cache_add( $key, 'pending', 'transient', $duration );
			} else {
				// DB唯一键覆盖慢请求和旧会话并发；多留60秒避免token有效期边界竞态。
				// 复用WP每日过期transient清理，兼容外部对象缓存。
				add_option( '_transient_timeout_' . $key, time() + $duration, '', false );
				$locked = add_option( '_transient_' . $key, 'pending', '', false );
			}
		}
		if ( ! $locked ) {
			$errors['dentall_request_token'][] = $token_lock
				? __( 'This form is already being processed or has been saved. If you did not receive confirmation, contact us before starting another request.', 'dentall-core' )
				: __( 'A request was just submitted. Please wait one minute before trying again.', 'dentall-core' );
			return $errors;
		}
	}

	$clean['dentall_cart_snapshot'] = $snapshot['text'];
	// 先持久化保守状态：进程异常退出也不会留下“订单已创建”的误导记录。
	$clean['dentall_order_status'] = 'pending';
	$clean['dentall_order_reference'] = __( 'Order creation requires staff review. Do not create a second order before checking this entry.', 'dentall-core' );
	$GLOBALS['dentall_shipping_request_validated'] = array( 'data' => $clean, 'timestamp' => $timestamp, 'snapshot' => $snapshot );
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

/**
 * 在FF原生通知前创建待报价订单，并把订单引用返回给通知和Entry明细。
 * FF会调用此过滤器两次；持久状态及原子锁禁止重复建单，也不自动重试半成品。
 */
function dentall_core_create_shipping_request_order( $data, $entry_id, $form ) {
	$validated = $GLOBALS['dentall_shipping_request_validated'] ?? null;
	if ( ! $entry_id || ! dentall_core_is_shipping_request_form( $form->id ?? 0 ) || ! is_array( $validated ) ) {
		return $data;
	}

	$entry = \FluentForm\App\Models\Submission::where( 'form_id', $form->id )->find( $entry_id );
	$saved = $entry ? json_decode( $entry->response, true ) : null;
	if ( ! is_array( $saved ) ) {
		return $data;
	}
	if ( 'pending' !== ( $saved['dentall_order_status'] ?? '' ) ) {
		return $saved;
	}

	$lock = 'dentall_shipping_request_order_' . absint( $entry_id );
	if ( ! add_option( $lock, time(), '', false ) ) {
		return $saved;
	}
	$order = null;
	$persisted = false;
	try {
		// 锁前读可能已过时：另一请求刚完成并释放锁时，必须再次检查持久状态。
		$entry = \FluentForm\App\Models\Submission::where( 'form_id', $form->id )->find( $entry_id );
		$saved = $entry ? json_decode( $entry->response, true ) : null;
		if ( ! is_array( $saved ) ) {
			throw new RuntimeException( 'entry_unavailable' );
		}
		if ( 'pending' !== ( $saved['dentall_order_status'] ?? '' ) ) {
			delete_option( $lock );
			return $saved;
		}
		// 仅从这次校验获得的规范化字段和结构化购物车创建订单，不读取隐藏金额。
		$data = $validated['data'];
		$data['dentall_order_status'] = 'creating';
		$entry->response = wp_json_encode( $data, JSON_UNESCAPED_UNICODE );
		if ( ! $entry->save() ) {
			throw new RuntimeException( 'entry_state' );
		}

		$snapshot = $validated['snapshot'];
		$order = new WC_Order();
		$order->set_created_via( 'dentall-shipping-request' );
		$order->set_status( 'pending' );
		$order->set_customer_id( get_current_user_id() );
		$order->set_currency( $snapshot['currency'] );
		$order->set_prices_include_tax( wc_prices_include_tax() );
		foreach ( array( 'shipping' => '', 'billing' => 'billing_' ) as $type => $prefix ) {
			$address = array();
			foreach ( array( 'first_name', 'last_name', 'company', 'country', 'state', 'city', 'postcode', 'address_1', 'address_2' ) as $part ) {
				$address[ $part ] = $data[ $prefix . $part ];
			}
			$order->set_address( $address, $type );
		}
		$order->set_billing_email( $data['email'] );
		$order->set_customer_note( $data['message'] );
		$order->update_meta_data( '_dentall_shipping_request_entry_id', absint( $entry_id ) );
		$order->update_meta_data( '_dentall_shipping_request_form_id', absint( $form->id ) );
		$order->update_meta_data( '_dentall_shipping_request_state', 'creating' );
		$order->update_meta_data( '_dentall_shipping_request_whatsapp', $data['whatsapp'] );
		$order->update_meta_data( '_dentall_shipping_request_snapshot', $snapshot['text'] );
		$order->update_meta_data( '_dentall_shipping_request_coupons', $snapshot['coupons'] );
		foreach ( $snapshot['items'] as $row ) {
			$product = wc_get_product( $row['variation_id'] ?: $row['product_id'] );
			if ( ! $product instanceof WC_Product ) {
				throw new RuntimeException( 'product_unavailable' );
			}
			$item = new WC_Order_Item_Product();
			$item->set_product( $product );
			$item->set_variation( $row['attributes'] );
			$item->set_quantity( $row['quantity'] );
			$item->set_subtotal( $row['subtotal'] );
			$item->set_total( $row['subtotal'] );
			$order->add_item( $item );
		}
		// 尚无Shipping、税费或coupon项；此步骤不运行Checkout、不占用库存或coupon次数。
		$order->calculate_totals( false );
		if ( ! $order->get_id() ) {
			throw new RuntimeException( 'order_not_saved' );
		}
		$order->add_order_note( sprintf(
			/* translators: 1: form entry ID, 2: manually supplied WhatsApp number, 3: server cart snapshot. */
			__( "Shipping request entry #%1\$d. Awaiting staff quote; payment is not active. WhatsApp (manual contact only): %2\$s. Coupons have NOT been applied: confirm discounts, shipping and tax before activating the quote.\n\n%3\$s", 'dentall-core' ),
			$entry_id, $data['whatsapp'], $snapshot['text']
		) );
		$order->update_meta_data( '_dentall_shipping_request_state', 'created' );
		$order->save();
		$data['dentall_order_status'] = 'created';
		$data['dentall_order_id'] = $order->get_id();
		$data['dentall_order_reference'] = sprintf(
			/* translators: 1: order number, 2: authenticated admin order URL. */
			__( 'Order #%1$s — awaiting staff quote; payment is not active. Review coupons, shipping and tax: %2$s', 'dentall-core' ),
			$order->get_order_number(), $order->get_edit_order_url()
		);
	} catch ( Throwable $error ) {
		$data['dentall_order_status'] = 'failed';
		$data['dentall_order_id'] = $order instanceof WC_Order ? $order->get_id() : 0;
		$data['dentall_order_reference'] = sprintf(
			/* translators: 1: form entry ID, 2: optional existing order admin URL for manual recovery. */
			__( 'ACTION REQUIRED: entry #%1$d was saved, but its order could not be fully prepared. Inspect this entry and any partial order before manual recovery; do not ask the customer to resubmit. %2$s', 'dentall-core' ),
			$entry_id, ! empty( $data['dentall_order_id'] ) ? $order->get_edit_order_url() : ''
		);
		wc_get_logger()->error( 'Shipping request order requires manual recovery.', array( 'source' => 'dentall-shipping-request', 'entry_id' => absint( $entry_id ), 'order_id' => $data['dentall_order_id'] ) );
	}
	try {
		$entry->response = wp_json_encode( $data, JSON_UNESCAPED_UNICODE );
		$persisted = (bool) $entry->save();
		if ( ! $persisted ) {
			throw new RuntimeException( 'entry_reference' );
		}
	} catch ( Throwable $error ) {
		// 保留原子锁阻止自动重建；已保存Entry的保守状态仍提示工作人员检查。
		wc_get_logger()->error( 'Shipping request entry reference could not be saved.', array( 'source' => 'dentall-shipping-request', 'entry_id' => absint( $entry_id ) ) );
	}
	if ( $persisted ) {
		delete_option( $lock );
	}
	return $data;
}
add_filter( 'fluentform/submission_form_data', 'dentall_core_create_shipping_request_order', 1, 3 );

/** 仅修改提交响应；通用message_parse也处理公司邮件，不能用于此处。 */
function dentall_core_shipping_request_confirmation( $response, $form, $confirmation, $entry_id, $data ) {
	if ( ! dentall_core_is_shipping_request_form( $form->id ?? 0 ) ) {
		return $response;
	}
	$text = 'created' === ( $data['dentall_order_status'] ?? '' )
		? __( 'Your request #%d is saved as a pending quote. Our team will confirm the final costs and send a payment link. Prices and stock are not reserved.', 'dentall-core' )
		: __( 'Your request #%d has been received and needs staff attention before a quote can be prepared. Please do not submit it again; our team will follow up.', 'dentall-core' );
	$response['message'] = '<p>' . esc_html( sprintf( $text, $entry_id ) ) . '</p>';
	return $response;
}
add_filter( 'fluentform/submission_confirmation', 'dentall_core_shipping_request_confirmation', 10, 5 );

/** Entry已保存、建单之前持久消费令牌；后续建单或通知中断也不能复用旧申请。 */
function dentall_core_record_shipping_request_submission( $entry_id, $data, $form ) {
	if ( $entry_id && dentall_core_is_shipping_request_form( $form->id ?? 0 ) && isset( $GLOBALS['dentall_shipping_request_validated'] ) ) {
		WC()->session->set( 'dentall_shipping_request_submitted_at', $GLOBALS['dentall_shipping_request_validated']['timestamp'] );
		WC()->session->save_data();
	}
}
add_action( 'fluentform/before_form_actions_processing', 'dentall_core_record_shipping_request_submission', 1, 3 );

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
