<?php

defined( 'ABSPATH' ) || exit;

/** D50筛选只进入Shop和商品分类，不改变商品搜索或其他taxonomy。 */
function dentall_is_catalog_filter_archive() {
	return function_exists( 'is_shop' )
		&& function_exists( 'is_product_category' )
		&& ! is_search()
		&& ( is_shop() || is_product_category() );
}

/** 返回第一版允许展示的商品属性。 */
function dentall_catalog_filter_attributes() {
	return array(
		'size'  => __( 'Size', 'dentall' ),
		'shade' => __( 'Shade', 'dentall' ),
	);
}

/** 返回当前Shop或商品分类的无参数归档URL。 */
function dentall_catalog_filter_base_url() {
	if ( is_product_category() ) {
		$term_url = get_term_link( get_queried_object() );

		if ( ! is_wp_error( $term_url ) ) {
			return $term_url;
		}
	}

	return wc_get_page_permalink( 'shop' );
}

/** 将公开价格参数规范为WooCommerce当前小数位允许的非负值。 */
function dentall_catalog_filter_price_value( $value ) {
	if ( ! is_string( $value ) ) {
		return null;
	}

	$value = trim( wp_unslash( $value ) );

	if ( '' === $value || strlen( $value ) > 64 || 1 !== preg_match( '/^(?:\d+(?:\.\d*)?|\.\d+)$/D', $value ) ) {
		return null;
	}

	$decimals = wc_get_price_decimals();
	$fraction = false !== strpos( $value, '.' ) ? substr( $value, strpos( $value, '.' ) + 1 ) : '';

	if ( strlen( rtrim( $fraction, '0' ) ) > $decimals ) {
		return null;
	}

	$value = wc_format_decimal( $value, $decimals );

	return '' !== $value && (float) $value >= 0 ? $value : null;
}

/**
 * 只传播D49冻结的筛选与排序参数；分类切换和条件变化始终回第一页。
 *
 * @param array<string, mixed> $source 查询参数来源。
 * @return array<string, string>
 */
function dentall_catalog_filter_sanitize_query_args( $source ) {
	static $valid_brand_ids = null;

	$clean = array();

	foreach ( array( 'min_price', 'max_price' ) as $key ) {
		if ( array_key_exists( $key, $source ) ) {
			$value = dentall_catalog_filter_price_value( $source[ $key ] );

			if ( null !== $value ) {
				$clean[ $key ] = $value;
			}
		}
	}

	if (
		isset( $source['filter_product_brand'] )
		&& is_string( $source['filter_product_brand'] )
	) {
		$value = trim( wp_unslash( $source['filter_product_brand'] ) );

		if ( strlen( $value ) <= 512 && 1 === preg_match( '/^\d+(?:,\d+)*$/D', $value ) ) {
			$requested = array_values( array_unique( array_filter( array_map( 'absint', explode( ',', $value ) ) ) ) );

			if ( null === $valid_brand_ids ) {
				$valid_brand_ids = get_terms(
					array(
						'taxonomy'   => 'product_brand',
						'hide_empty' => true,
						'fields'     => 'ids',
					)
				);
				$valid_brand_ids = is_wp_error( $valid_brand_ids ) ? array() : array_map( 'absint', $valid_brand_ids );
			}

			$brand_ids = array_values( array_intersect( $requested, $valid_brand_ids ) );
			sort( $brand_ids, SORT_NUMERIC );

			if ( $brand_ids ) {
				$clean['filter_product_brand'] = implode( ',', $brand_ids );
			}
		}
	}

	foreach ( array_keys( dentall_catalog_filter_attributes() ) as $attribute ) {
		$filter_key = 'filter_' . $attribute;

		if ( empty( $source[ $filter_key ] ) || ! is_string( $source[ $filter_key ] ) ) {
			continue;
		}

		$allowed = get_terms(
			array(
				'taxonomy'   => wc_attribute_taxonomy_name( $attribute ),
				'hide_empty' => false,
				'fields'     => 'slugs',
			)
		);

		if ( is_wp_error( $allowed ) || empty( $allowed ) ) {
			continue;
		}

		$requested = explode( ',', sanitize_text_field( wp_unslash( $source[ $filter_key ] ) ) );
		$requested = array_unique( array_filter( array_map( 'sanitize_title', $requested ) ) );
		$requested = array_values( array_intersect( $requested, $allowed ) );

		if ( $requested ) {
			sort( $requested, SORT_STRING );
			$clean[ $filter_key ]                 = implode( ',', $requested );
			$clean[ 'query_type_' . $attribute ] = 'or';
		}
	}

	if ( isset( $source['orderby'] ) && is_string( $source['orderby'] ) ) {
		$orderby = sanitize_text_field( wp_unslash( $source['orderby'] ) );

		if ( in_array( $orderby, array( 'menu_order', 'popularity', 'rating', 'date', 'price', 'price-desc' ), true ) ) {
			$clean['orderby'] = $orderby;
		}
	}

	return $clean;
}

/** 返回当前请求可继续传播的白名单状态。 */
function dentall_catalog_filter_current_query_args() {
	static $args = null;

	if ( null === $args ) {
		$args = dentall_catalog_filter_sanitize_query_args( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	return $args;
}

/** 在WooCommerce读取公开GET前统一属性OR语义与品牌边界。 */
function dentall_catalog_filter_prepare_query_args( $query ) {
	if ( is_admin() || ! class_exists( 'WC_Query' ) || ! $query instanceof WP_Query || ! $query->is_main_query() ) {
		return;
	}

	$is_catalog = ! $query->is_search()
		&& ( $query->is_post_type_archive( 'product' ) || $query->is_tax( 'product_cat' ) );

	if ( ! $is_catalog ) {
		foreach ( array_keys( $_GET ) as $key ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (
				is_string( $key )
				&& (
					'min_price' === $key
					|| 'max_price' === $key
					|| 0 === strpos( $key, 'filter_' )
					|| 0 === strpos( $key, 'query_type_' )
				)
			) {
				unset( $_GET[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
		}

		WC_Query::reset_chosen_attributes();
		return;
	}

	$clean = dentall_catalog_filter_sanitize_query_args( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$keys  = array( 'filter_product_brand' );

	foreach ( array_keys( dentall_catalog_filter_attributes() ) as $attribute ) {
		$keys[] = 'filter_' . $attribute;
		$keys[] = 'query_type_' . $attribute;
	}

	$key_map    = array_fill_keys( $keys, true );
	$raw_args   = array_intersect_key( $_GET, $key_map ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$clean_args = array_intersect_key( $clean, $key_map );
	$unknown    = array();
	ksort( $raw_args );
	ksort( $clean_args );

	foreach ( array_keys( $_GET ) as $key ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (
			is_string( $key )
			&& ( 0 === strpos( $key, 'filter_' ) || 0 === strpos( $key, 'query_type_' ) )
			&& ! isset( $key_map[ $key ] )
		) {
			$unknown[] = $key;
		}
	}

	if ( $raw_args !== $clean_args || $unknown ) {
		$GLOBALS['dentall_catalog_filter_query_needs_redirect'] = true;
	}

	foreach ( array_keys( dentall_catalog_filter_attributes() ) as $attribute ) {
		$filter_key = 'filter_' . $attribute;
		$query_key  = 'query_type_' . $attribute;

		if ( ! array_key_exists( $filter_key, $_GET ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			continue;
		}

		if ( isset( $clean[ $filter_key ] ) ) {
			$_GET[ $filter_key ] = $clean[ $filter_key ]; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$_GET[ $query_key ]  = 'or'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		} else {
			$_GET[ $filter_key ] = ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$_GET[ $query_key ]  = ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
	}

	if ( array_key_exists( 'filter_product_brand', $_GET ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$_GET['filter_product_brand'] = $clean['filter_product_brand'] ?? ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	foreach ( $unknown as $key ) {
		$_GET[ $key ] = ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	WC_Query::reset_chosen_attributes();
}

/** 使用无分页归档与白名单参数生成筛选URL。 */
function dentall_catalog_filter_url( $base_url, $args ) {
	return $base_url ? str_replace( '%2C', ',', add_query_arg( $args, $base_url ) ) : '';
}

/** 返回价格、属性与品牌的当前已选条件及逐项移除URL。 */
function dentall_catalog_filter_active_items() {
	$args  = dentall_catalog_filter_current_query_args();
	$items = array();

	if ( array_key_exists( 'min_price', $args ) || array_key_exists( 'max_price', $args ) ) {
		$minimum = array_key_exists( 'min_price', $args )
			? html_entity_decode( wp_strip_all_tags( wc_price( (float) $args['min_price'] ) ), ENT_QUOTES | ENT_HTML5, get_bloginfo( 'charset' ) )
			: '';
		$maximum = array_key_exists( 'max_price', $args )
			? html_entity_decode( wp_strip_all_tags( wc_price( (float) $args['max_price'] ) ), ENT_QUOTES | ENT_HTML5, get_bloginfo( 'charset' ) )
			: '';

		if ( $minimum && $maximum ) {
			/* translators: 1: minimum price, 2: maximum price. */
			$label = sprintf( __( 'Price: %1$s–%2$s', 'dentall' ), $minimum, $maximum );
		} elseif ( $minimum ) {
			/* translators: %s: minimum price. */
			$label = sprintf( __( 'Price: from %s', 'dentall' ), $minimum );
		} else {
			/* translators: %s: maximum price. */
			$label = sprintf( __( 'Price: up to %s', 'dentall' ), $maximum );
		}

		$next_args = $args;
		unset( $next_args['min_price'], $next_args['max_price'] );

		$items[] = array(
			'label' => $label,
			'url'   => dentall_catalog_filter_url( dentall_catalog_filter_base_url(), $next_args ),
		);
	}

	foreach ( dentall_catalog_filter_attributes() as $attribute => $group_label ) {
		$filter_key = 'filter_' . $attribute;

		if ( empty( $args[ $filter_key ] ) ) {
			continue;
		}

		$selected = explode( ',', $args[ $filter_key ] );

		foreach ( $selected as $term_slug ) {
			$term = get_term_by( 'slug', $term_slug, wc_attribute_taxonomy_name( $attribute ) );

			if ( ! $term ) {
				continue;
			}

			$remaining = array_values( array_diff( $selected, array( $term_slug ) ) );
			$next_args = $args;

			if ( $remaining ) {
				$next_args[ $filter_key ] = implode( ',', $remaining );
			} else {
				unset( $next_args[ $filter_key ], $next_args[ 'query_type_' . $attribute ] );
			}

			/* translators: 1: filter group name, 2: selected term name. */
			$label = sprintf( __( '%1$s: %2$s', 'dentall' ), $group_label, $term->name );
			$items[] = array(
				'label' => $label,
				'url'   => dentall_catalog_filter_url( dentall_catalog_filter_base_url(), $next_args ),
			);
		}
	}

	if ( ! empty( $args['filter_product_brand'] ) ) {
		$selected = array_map( 'absint', explode( ',', $args['filter_product_brand'] ) );

		foreach ( $selected as $brand_id ) {
			$term = get_term( $brand_id, 'product_brand' );

			if ( ! $term || is_wp_error( $term ) ) {
				continue;
			}

			$remaining = array_values( array_diff( $selected, array( $brand_id ) ) );
			$next_args = $args;

			if ( $remaining ) {
				$next_args['filter_product_brand'] = implode( ',', $remaining );
			} else {
				unset( $next_args['filter_product_brand'] );
			}

			/* translators: %s: selected brand name. */
			$label = sprintf( __( 'Brand: %s', 'dentall' ), $term->name );
			$items[] = array(
				'label' => $label,
				'url'   => dentall_catalog_filter_url( dentall_catalog_filter_base_url(), $next_args ),
			);
		}
	}

	return $items;
}

/** 在商品结果区集中输出已选条件与保留当前分类、排序的清除入口。 */
function dentall_catalog_filter_active_output() {
	static $rendered = false;

	if ( $rendered || ! dentall_is_catalog_filter_archive() ) {
		return;
	}

	$items = dentall_catalog_filter_active_items();

	if ( ! $items ) {
		return;
	}

	$args       = dentall_catalog_filter_current_query_args();
	$reset_args = isset( $args['orderby'] ) ? array( 'orderby' => $args['orderby'] ) : array();
	$reset_url  = dentall_catalog_filter_url( dentall_catalog_filter_base_url(), $reset_args );
	$rendered   = true;
	?>
	<nav class="dentall-catalog-active-filters" aria-labelledby="dentall-active-filters-title">
		<div class="dentall-catalog-active-filters__header">
			<h2 id="dentall-active-filters-title" class="dentall-catalog-active-filters__title"><?php esc_html_e( 'Active filters', 'dentall' ); ?></h2>
			<a class="dentall-catalog-active-filters__clear" rel="nofollow" href="<?php echo esc_url( $reset_url ); ?>"><?php esc_html_e( 'Clear filters', 'dentall' ); ?></a>
		</div>
		<ul class="dentall-catalog-active-filters__list">
			<?php foreach ( $items as $item ) : ?>
				<?php
				/* translators: %s: visible filter label. */
				$remove_label = sprintf( __( 'Remove %s filter.', 'dentall' ), $item['label'] );
				?>
				<li>
					<a rel="nofollow" href="<?php echo esc_url( $item['url'] ); ?>" aria-label="<?php echo esc_attr( $remove_label ); ?>">
						<span><?php echo esc_html( $item['label'] ); ?></span>
						<span class="dentall-catalog-active-filters__remove" aria-hidden="true">&times;</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}

/** 让分类链接保留有效筛选，但不传播未知参数或旧分页。 */
function dentall_catalog_filter_category_link_attributes( $attributes, $category ) {
	if ( 'product_cat' !== $category->taxonomy ) {
		return $attributes;
	}

	$term_url = get_term_link( $category );

	if ( ! is_wp_error( $term_url ) ) {
		$attributes['href'] = dentall_catalog_filter_url( $term_url, dentall_catalog_filter_current_query_args() );
	}

	return $attributes;
}

/** 输出动态商品分类入口，不显示D50范围外的计数。 */
function dentall_catalog_filter_categories() {
	$shop_url = dentall_catalog_filter_url(
		wc_get_page_permalink( 'shop' ),
		dentall_catalog_filter_current_query_args()
	);

	add_filter( 'category_list_link_attributes', 'dentall_catalog_filter_category_link_attributes', 20, 2 );
	$items = wp_list_categories(
		array(
			'current_category' => is_product_category() ? get_queried_object_id() : 0,
			'echo'             => false,
			'hide_empty'       => true,
			'hierarchical'     => true,
			'orderby'          => 'name',
			'pad_counts'       => true,
			'show_count'       => false,
			'show_option_none' => '',
			'taxonomy'         => 'product_cat',
			'title_li'         => '',
		)
	);
	remove_filter( 'category_list_link_attributes', 'dentall_catalog_filter_category_link_attributes', 20 );
	?>
	<nav class="dentall-catalog-filter dentall-catalog-filter--first" aria-labelledby="dentall-filter-categories-title">
		<h2 id="dentall-filter-categories-title" class="dentall-catalog-filter__title"><?php esc_html_e( 'Categories', 'dentall' ); ?></h2>
		<ul class="dentall-catalog-filter__list dentall-catalog-filter__categories">
			<li class="<?php echo esc_attr( is_shop() ? 'cat-item current-cat' : 'cat-item' ); ?>">
				<a href="<?php echo esc_url( $shop_url ); ?>" <?php echo is_shop() ? 'aria-current="page"' : ''; ?>><?php esc_html_e( 'All products', 'dentall' ); ?></a>
			</li>
			<?php echo wp_kses_post( $items ); ?>
		</ul>
	</nav>
	<?php
}

/** 输出无JavaScript的Min/Max价格表单。 */
function dentall_catalog_filter_price() {
	$args      = dentall_catalog_filter_current_query_args();
	$min_price = isset( $args['min_price'] ) ? $args['min_price'] : '';
	$max_price = isset( $args['max_price'] ) ? $args['max_price'] : '';
	$range_error = '' !== $min_price && '' !== $max_price && (float) $min_price > (float) $max_price;
	$currency    = get_woocommerce_currency();
	$step        = wc_get_price_decimals() ? '0.' . str_repeat( '0', wc_get_price_decimals() - 1 ) . '1' : '1';
	/* translators: %s: active WooCommerce currency code. */
	$minimum_label = sprintf( __( 'Minimum (%s)', 'dentall' ), $currency );
	/* translators: %s: active WooCommerce currency code. */
	$maximum_label = sprintf( __( 'Maximum (%s)', 'dentall' ), $currency );
	/* translators: %s: active WooCommerce currency code. */
	$help = sprintf( __( 'Enter one or both prices in %s.', 'dentall' ), $currency );
	$fields      = array(
		'min_price' => array( $minimum_label, $min_price ),
		'max_price' => array( $maximum_label, $max_price ),
	);
	?>
	<section class="dentall-catalog-filter" aria-labelledby="dentall-filter-price-title">
		<h2 id="dentall-filter-price-title" class="dentall-catalog-filter__title"><?php esc_html_e( 'Price', 'dentall' ); ?></h2>
		<form class="dentall-price-filter" method="get" action="<?php echo esc_url( dentall_catalog_filter_base_url() ); ?>">
			<div class="dentall-price-filter__fields">
				<?php foreach ( $fields as $key => $field ) : ?>
					<p>
						<label for="dentall-<?php echo esc_attr( str_replace( '_', '-', $key ) ); ?>"><?php echo esc_html( $field[0] ); ?></label>
						<input id="dentall-<?php echo esc_attr( str_replace( '_', '-', $key ) ); ?>" name="<?php echo esc_attr( $key ); ?>" type="number" min="0" step="<?php echo esc_attr( $step ); ?>" inputmode="decimal" value="<?php echo esc_attr( $field[1] ); ?>" aria-describedby="<?php echo esc_attr( $range_error ? 'dentall-price-help dentall-price-error' : 'dentall-price-help' ); ?>" aria-invalid="<?php echo esc_attr( $range_error ? 'true' : 'false' ); ?>">
					</p>
				<?php endforeach; ?>
			</div>
			<p id="dentall-price-help" class="dentall-price-filter__help"><?php echo esc_html( $help ); ?></p>
			<?php if ( $range_error ) : ?>
				<p id="dentall-price-error" class="dentall-price-filter__error" role="alert"><?php esc_html_e( 'Minimum price cannot exceed maximum price.', 'dentall' ); ?></p>
			<?php endif; ?>
			<?php foreach ( array_diff_key( $args, array_flip( array( 'min_price', 'max_price' ) ) ) as $key => $value ) : ?>
				<input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>">
			<?php endforeach; ?>
			<button type="submit"><?php esc_html_e( 'Apply', 'dentall' ); ?></button>
		</form>
	</section>
	<?php
}

/** 将WooCommerce属性Widget的入口收敛到D49白名单URL。 */
function dentall_catalog_filter_widget_base_url( $url = '' ) {
	return dentall_catalog_filter_url( dentall_catalog_filter_base_url(), dentall_catalog_filter_current_query_args() );
}

/** 单个属性最后一项被移除时，同步移除失去主参数的query_type。 */
function dentall_catalog_filter_layered_nav_url( $url, $term, $taxonomy ) {
	$query = wp_parse_url( $url, PHP_URL_QUERY );
	$args  = array();
	$slug  = wc_attribute_taxonomy_slug( $taxonomy );

	if ( is_string( $query ) ) {
		wp_parse_str( $query, $args );
	}

	return empty( $args[ 'filter_' . $slug ] ) ? remove_query_arg( 'query_type_' . $slug, $url ) : $url;
}

/** 为属性补充数量与机器可读状态；可见勾选由CSS伪元素表达。 */
function dentall_catalog_filter_layered_nav_term_html( $html, $term, $link, $count ) {
	if ( ! $link ) {
		return $html;
	}

	$chosen   = WC_Query::get_layered_nav_chosen_attributes();
	$selected = $link && isset( $chosen[ $term->taxonomy ]['terms'] )
		&& in_array( $term->slug, $chosen[ $term->taxonomy ]['terms'], true );
	$label    = '';

	if ( $selected ) {
		/* translators: %s: selected product attribute term name. */
		$label = sprintf( __( '%s selected; activate to remove.', 'dentall' ), $term->name );
	}

	return sprintf(
		'<a rel="nofollow" href="%1$s"%2$s>%3$s</a> <span class="count">(%4$d)</span>',
		esc_url( $link ),
		$selected ? ' aria-current="true" aria-label="' . esc_attr( $label ) . '"' : '',
		esc_html( $term->name ),
		absint( $count )
	);
}

/**
 * WooCommerce 的分面数量查询不会完整继承主商品查询的价格与Lookup属性条件，需补齐“其他条件”。
 *
 * @param array<string, string> $query WooCommerce 分面数量 SQL 片段。
 * @return array<string, string>
 */
function dentall_catalog_filter_count_query_constraints( $query ) {
	global $wpdb;

	$args             = dentall_catalog_filter_current_query_args();
	$counted_taxonomy = isset( $GLOBALS['dentall_catalog_filter_count_taxonomy'] )
		? (string) $GLOBALS['dentall_catalog_filter_count_taxonomy']
		: '';
	$attribute_lookup = $wpdb->prefix . 'wc_product_attributes_lookup';

	if (
		! is_array( $query )
		|| ! isset( $query['join'], $query['where'] )
	) {
		return $query;
	}

	if ( isset( $args['min_price'] ) || isset( $args['max_price'] ) ) {
		$minimum = isset( $args['min_price'] ) ? (float) $args['min_price'] : 0;
		$maximum = isset( $args['max_price'] ) ? (float) $args['max_price'] : PHP_INT_MAX;

		if (
			wc_tax_enabled()
			&& \Automattic\WooCommerce\Enums\TaxDisplayMode::INCLUSIVE === get_option( 'woocommerce_tax_display_shop' )
			&& ! wc_prices_include_tax()
		) {
			$tax_rates = WC_Tax::get_rates( apply_filters( 'woocommerce_price_filter_widget_tax_class', '' ) );

			if ( $tax_rates ) {
				$minimum -= WC_Tax::get_tax_total( WC_Tax::calc_inclusive_tax( $minimum, $tax_rates ) );
				$maximum -= WC_Tax::get_tax_total( WC_Tax::calc_inclusive_tax( $maximum, $tax_rates ) );
			}
		}

		if ( false === strpos( $query['join'], 'dentall_filter_price_lookup' ) ) {
			$query['join'] .= " LEFT JOIN {$wpdb->wc_product_meta_lookup} dentall_filter_price_lookup ON {$wpdb->posts}.ID = dentall_filter_price_lookup.product_id ";
		}

		$query['where'] .= $wpdb->prepare(
			' AND NOT (%f < dentall_filter_price_lookup.min_price OR %f > dentall_filter_price_lookup.max_price) ',
			$maximum,
			$minimum
		);
	}

	if ( 'yes' !== get_option( 'woocommerce_attribute_lookup_enabled' ) ) {
		return $query;
	}

	foreach ( array_keys( dentall_catalog_filter_attributes() ) as $attribute ) {
		$taxonomy = wc_attribute_taxonomy_name( $attribute );
		$filter   = 'filter_' . $attribute;

		if ( $taxonomy === $counted_taxonomy || empty( $args[ $filter ] ) ) {
			continue;
		}

		$term_ids = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'fields'     => 'ids',
				'slug'       => explode( ',', $args[ $filter ] ),
			)
		);

		if ( is_wp_error( $term_ids ) || ! $term_ids ) {
			$query['where'] .= ' AND 1=0 ';
			continue;
		}

		$alias        = 'dentall_filter_' . sanitize_key( $taxonomy );
		$stock_clause = apply_filters( 'woocommerce_product_attributes_filterer_hide_out_of_stock', 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) )
			? " AND {$alias}.in_stock=1"
			: '';
		$query['where'] .= $wpdb->prepare(
			" AND EXISTS (
				SELECT 1 FROM {$attribute_lookup} {$alias}
				WHERE {$alias}.product_or_parent_id={$wpdb->posts}.ID
				AND {$alias}.taxonomy=%s
				AND {$alias}.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . "){$stock_clause}
			) ",
			$taxonomy
		);
	}

	return $query;
}

/** 输出Size和Shade的WooCommerce原生链接式属性筛选。 */
function dentall_catalog_filter_attributes_output() {
	if ( ! class_exists( 'WC_Widget_Layered_Nav' ) ) {
		return;
	}

	$original_get = $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$_GET         = dentall_catalog_filter_current_query_args(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	add_filter( 'woocommerce_widget_get_current_page_url', 'dentall_catalog_filter_widget_base_url', 20 );
	add_filter( 'woocommerce_layered_nav_link', 'dentall_catalog_filter_layered_nav_url', 20, 3 );
	add_filter( 'woocommerce_layered_nav_term_html', 'dentall_catalog_filter_layered_nav_term_html', 20, 4 );
	add_filter( 'woocommerce_get_filtered_term_product_counts_query', 'dentall_catalog_filter_count_query_constraints', 20 );

	try {
		foreach ( dentall_catalog_filter_attributes() as $attribute => $label ) {
			$GLOBALS['dentall_catalog_filter_count_taxonomy'] = wc_attribute_taxonomy_name( $attribute );
			the_widget(
				'WC_Widget_Layered_Nav',
				array(
					'attribute'    => $attribute,
					'display_type' => 'list',
					'query_type'   => 'or',
					'title'        => $label,
				),
				array(
					'before_widget' => '<section class="dentall-catalog-filter %s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="dentall-catalog-filter__title">',
					'after_title'   => '</h2>',
				)
			);
		}
	} finally {
		remove_filter( 'woocommerce_widget_get_current_page_url', 'dentall_catalog_filter_widget_base_url', 20 );
		remove_filter( 'woocommerce_layered_nav_link', 'dentall_catalog_filter_layered_nav_url', 20 );
		remove_filter( 'woocommerce_layered_nav_term_html', 'dentall_catalog_filter_layered_nav_term_html', 20 );
		remove_filter( 'woocommerce_get_filtered_term_product_counts_query', 'dentall_catalog_filter_count_query_constraints', 20 );
		unset( $GLOBALS['dentall_catalog_filter_count_taxonomy'] );
		$_GET = $original_get; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
}

/** 将WooCommerce品牌Widget链接收敛为D52白名单，并移除其内部辅助参数。 */
function dentall_catalog_filter_brand_link( $url ) {
	$query = wp_parse_url( $url, PHP_URL_QUERY );
	$args  = array();

	if ( is_string( $query ) ) {
		wp_parse_str( $query, $args );
	}

	return dentall_catalog_filter_url(
		dentall_catalog_filter_base_url(),
		dentall_catalog_filter_sanitize_query_args( $args )
	);
}

/** 为原生品牌Widget链接补齐nofollow与已选项的机器可读移除语义。 */
function dentall_catalog_filter_brand_markup( $html ) {
	$updated = preg_replace_callback(
		'~<li class="wc-layered-nav-term(?P<chosen> chosen)? ?">\s*<a href="(?P<href>[^"]+)">(?P<label>[^<]*)</a>~',
		static function ( $matches ) {
			$selected = ! empty( $matches['chosen'] );
			$label    = html_entity_decode( $matches['label'], ENT_QUOTES | ENT_HTML5, get_bloginfo( 'charset' ) );
			$state    = '';

			if ( $selected ) {
				/* translators: %s: selected product brand name. */
				$accessible_label = sprintf( __( '%s selected; activate to remove.', 'dentall' ), $label );
				$state            = ' aria-current="true" aria-label="' . esc_attr( $accessible_label ) . '"';
			}

			return sprintf(
				'<li class="wc-layered-nav-term%1$s"><a rel="nofollow" href="%2$s"%3$s>%4$s</a>',
				$selected ? ' chosen' : '',
				esc_url( $matches['href'] ),
				$state,
				esc_html( $label )
			);
		},
		$html
	);

	return is_string( $updated ) ? $updated : $html;
}

/** 输出WooCommerce原生文字品牌筛选；没有已关联品牌时保持诚实空输出。 */
function dentall_catalog_filter_brand_output() {
	if ( ! class_exists( 'WC_Widget_Brand_Nav' ) || ! taxonomy_exists( 'product_brand' ) ) {
		return;
	}

	$original_get = $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$_GET         = dentall_catalog_filter_current_query_args(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	add_filter( 'woocommerce_layered_nav_link', 'dentall_catalog_filter_brand_link', 30 );
	add_filter( 'woocommerce_get_filtered_term_product_counts_query', 'dentall_catalog_filter_count_query_constraints', 20 );
	$GLOBALS['dentall_catalog_filter_count_taxonomy'] = 'product_brand';

	try {
		ob_start();
		the_widget(
			'WC_Widget_Brand_Nav',
			array(
				'display_type' => 'list',
				'title'        => __( 'Brand', 'dentall' ),
			),
			array(
				'before_widget' => '<section class="dentall-catalog-filter %s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="dentall-catalog-filter__title">',
				'after_title'   => '</h2>',
			)
		);
		echo wp_kses_post( dentall_catalog_filter_brand_markup( ob_get_clean() ) );
	} finally {
		remove_filter( 'woocommerce_layered_nav_link', 'dentall_catalog_filter_brand_link', 30 );
		remove_filter( 'woocommerce_get_filtered_term_product_counts_query', 'dentall_catalog_filter_count_query_constraints', 20 );
		unset( $GLOBALS['dentall_catalog_filter_count_taxonomy'] );
		$_GET = $original_get; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
}

/** 排序模板渲染期间仅暴露白名单参数，避免其隐藏字段复制任意GET值。 */
function dentall_catalog_filter_before_ordering_template( $template_name ) {
	if ( dentall_is_catalog_filter_archive() && 'loop/orderby.php' === $template_name ) {
		$GLOBALS['dentall_catalog_filter_original_get'] = $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$_GET = dentall_catalog_filter_current_query_args(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
}

/** 排序模板结束后恢复原请求，避免改变其他组件读取到的请求上下文。 */
function dentall_catalog_filter_after_ordering_template( $template_name ) {
	if ( dentall_is_catalog_filter_archive() && 'loop/orderby.php' === $template_name && isset( $GLOBALS['dentall_catalog_filter_original_get'] ) && is_array( $GLOBALS['dentall_catalog_filter_original_get'] ) ) {
		$_GET = $GLOBALS['dentall_catalog_filter_original_get']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		unset( $GLOBALS['dentall_catalog_filter_original_get'] );
	}
}

/** 分页链接只继承白名单筛选状态，不复制当前请求中的未知参数。 */
function dentall_catalog_filter_pagination_link( $url ) {
	if ( ! dentall_is_catalog_filter_archive() ) {
		return $url;
	}

	$query = wp_parse_url( $url, PHP_URL_QUERY );
	$raw   = array();

	if ( is_string( $query ) ) {
		wp_parse_str( $query, $raw );
	}

	$allowed    = dentall_catalog_filter_current_query_args();
	$route_keys = array( 'paged', 'page', 'product-page', 'post_type', 'product_cat' );
	$remove     = array_diff( array_keys( $raw ), array_keys( $allowed ), $route_keys );

	if ( $remove ) {
		$url = remove_query_arg( $remove, $url );
	}

	return dentall_catalog_filter_url( $url, $allowed );
}

/** 输出窄屏筛选入口；正常结果与空结果共用同一个按钮。 */
function dentall_catalog_filter_toggle() {
	static $rendered = false;

	if ( $rendered || ! dentall_is_catalog_filter_archive() ) {
		return;
	}

	$rendered = true;
	?>
	<button
		type="button"
		class="dentall-catalog-filter-toggle"
		aria-controls="dentall-catalog-filter-dialog"
		aria-expanded="false"
		aria-haspopup="dialog"
		data-dentall-filter-toggle
		hidden
	>
		<?php esc_html_e( 'Filter', 'dentall' ); ?>
	</button>
	<?php
}

/** 在归档Header后打开筛选与结果布局；筛选控件始终只输出一份。 */
function dentall_catalog_filters_open() {
	if ( ! dentall_is_catalog_filter_archive() ) {
		return;
	}
	?>
	<div class="dentall-catalog-layout">
		<aside id="dentall-catalog-filters" class="dentall-catalog-filters" aria-label="<?php esc_attr_e( 'Product filters', 'dentall' ); ?>" tabindex="-1">
			<header class="dentall-catalog-filter-drawer__header" data-dentall-filter-header hidden>
				<h2 id="dentall-catalog-filter-dialog-title" class="dentall-catalog-filter-drawer__title" tabindex="-1">
					<?php esc_html_e( 'Filter products', 'dentall' ); ?>
				</h2>
				<button type="button" class="dentall-catalog-filter-close" data-dentall-filter-close>
					<?php esc_html_e( 'Close', 'dentall' ); ?>
				</button>
			</header>
			<?php dentall_catalog_filter_categories(); ?>
			<?php dentall_catalog_filter_brand_output(); ?>
			<?php dentall_catalog_filter_price(); ?>
			<?php dentall_catalog_filter_attributes_output(); ?>
		</aside>
		<dialog id="dentall-catalog-filter-dialog" class="dentall-catalog-filter-dialog" aria-labelledby="dentall-catalog-filter-dialog-title"></dialog>
		<div class="dentall-catalog-results">
	<?php
}

/** 在Storefront关闭main前闭合D50布局。 */
function dentall_catalog_filters_close() {
	if ( dentall_is_catalog_filter_archive() ) {
		echo '</div></div>';
	}
}

/** 非法价格或非规范筛选参数302到白名单第一页URL。 */
function dentall_catalog_filter_redirect_invalid_query_args() {
	if ( ! dentall_is_catalog_filter_archive() ) {
		return;
	}

	$needs_redirect = ! empty( $GLOBALS['dentall_catalog_filter_query_needs_redirect'] );

	foreach ( array( 'min_price', 'max_price' ) as $key ) {
		if (
			array_key_exists( $key, $_GET ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			&& (
				! is_string( $_GET[ $key ] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				|| null === dentall_catalog_filter_price_value( $_GET[ $key ] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			)
		) {
			$needs_redirect = true;
			break;
		}
	}

	if ( ! $needs_redirect ) {
		return;
	}

	$target = dentall_catalog_filter_url(
		dentall_catalog_filter_base_url(),
		dentall_catalog_filter_current_query_args()
	);

	nocache_headers();

	if ( wp_safe_redirect( $target, 302, 'DentAll' ) ) {
		exit;
	}
}

/** Shop、商品分类与品牌归档不输出Storefront全站Sidebar Widget区域。 */
function dentall_catalog_filter_disable_storefront_sidebar() {
	if ( dentall_is_catalog_filter_archive() || is_tax( 'product_brand' ) ) {
		remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
	}
}

add_action( 'woocommerce_shop_loop_header', 'dentall_catalog_filters_open', 20 );
add_action( 'woocommerce_after_main_content', 'dentall_catalog_filters_close', 5 );
add_action( 'woocommerce_before_shop_loop', 'dentall_catalog_filter_toggle', 10 );
add_action( 'woocommerce_before_shop_loop', 'dentall_catalog_filter_active_output', 32 );
add_action( 'woocommerce_no_products_found', 'dentall_catalog_filter_toggle', 5 );
add_action( 'woocommerce_no_products_found', 'dentall_catalog_filter_active_output', 6 );
add_action( 'template_redirect', 'dentall_catalog_filter_redirect_invalid_query_args', 2 );
add_action( 'wp', 'dentall_catalog_filter_disable_storefront_sidebar', 20 );
add_action( 'woocommerce_before_template_part', 'dentall_catalog_filter_before_ordering_template', 20 );
add_action( 'woocommerce_after_template_part', 'dentall_catalog_filter_after_ordering_template', 20 );
add_filter( 'paginate_links', 'dentall_catalog_filter_pagination_link', 20 );
add_action( 'pre_get_posts', 'dentall_catalog_filter_prepare_query_args', 1 );
