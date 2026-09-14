<?php
/**
 * DentAll首页运营内容设置。
 */

defined( 'ABSPATH' ) || exit;

const DENTALL_CORE_HOME_TRUST_META         = '_dentall_home_trust_metrics';
const DENTALL_CORE_HOME_TRUST_ENABLED_META = '_dentall_home_trust_enabled';
const DENTALL_CORE_HOME_TRUST_NONCE_ACTION = 'dentall_save_home_trust';
const DENTALL_CORE_HOME_TRUST_NONCE_NAME   = 'dentall_home_trust_nonce';

/**
 * 返回首页五个固定图标槽位的初始文案。
 *
 * 图标与版式是设计系统的一部分，业务人员只维护短文本，避免后台输入任意SVG或HTML。
 *
 * @return array<int, array<string, string>>
 */
function dentall_core_get_home_trust_defaults() {
	return array(
		array(
			'icon'        => 'professionals',
			'value'       => __( '10,000+', 'dentall-core' ),
			'label'       => __( 'Dental Professionals', 'dentall-core' ),
			'description' => __( 'Trust DentAll', 'dentall-core' ),
		),
		array(
			'icon'        => 'globe',
			'value'       => __( '100+', 'dentall-core' ),
			'label'       => __( 'Countries Served', 'dentall-core' ),
			'description' => __( 'Worldwide', 'dentall-core' ),
		),
		array(
			'icon'        => 'box',
			'value'       => __( '5,000+', 'dentall-core' ),
			'label'       => __( 'Quality Products', 'dentall-core' ),
			'description' => __( 'In Stock', 'dentall-core' ),
		),
		array(
			'icon'        => 'smile',
			'value'       => __( '99.5%', 'dentall-core' ),
			'label'       => __( 'Customer Satisfaction', 'dentall-core' ),
			'description' => __( 'Rate', 'dentall-core' ),
		),
		array(
			'icon'        => 'lock',
			'value'       => __( 'Secure Payments', 'dentall-core' ),
			'label'       => '',
			'description' => __( 'Multiple safe payment options', 'dentall-core' ),
		),
	);
}

/**
 * 限制后台首页短文本长度，避免异常长内容破坏全站首页布局。
 *
 * @param mixed $value      待清洗值。
 * @param int   $max_length 最大字符数。
 * @return string
 */
function dentall_core_sanitize_home_trust_text( $value, $max_length ) {
	$value = is_scalar( $value ) ? sanitize_text_field( (string) $value ) : '';

	if ( function_exists( 'mb_substr' ) ) {
		return mb_substr( $value, 0, $max_length );
	}

	return substr( $value, 0, $max_length );
}

/**
 * 清洗首页Trust数组，并始终恢复固定图标映射。
 *
 * @param mixed $value 待清洗meta值。
 * @return array<int, array<string, string>>
 */
function dentall_core_sanitize_home_trust_metrics( $value ) {
	$defaults = dentall_core_get_home_trust_defaults();
	$value    = is_array( $value ) ? $value : array();
	$metrics  = array();

	foreach ( $defaults as $index => $default ) {
		$metric = isset( $value[ $index ] ) && is_array( $value[ $index ] )
			? $value[ $index ]
			: $default;

		$metrics[] = array(
			'icon'        => $default['icon'],
			'value'       => dentall_core_sanitize_home_trust_text( $metric['value'] ?? '', 80 ),
			'label'       => dentall_core_sanitize_home_trust_text( $metric['label'] ?? '', 120 ),
			'description' => dentall_core_sanitize_home_trust_text( $metric['description'] ?? '', 160 ),
		);
	}

	return $metrics;
}

/**
 * 判断当前用户能否维护首页Trust内容。
 *
 * @param int $post_id 首页Page ID。
 * @return bool
 */
function dentall_core_can_manage_home_trust( $post_id ) {
	return current_user_can( 'edit_post', $post_id )
		&& ( current_user_can( 'manage_options' ) || current_user_can( DENTALL_WEBSITE_MANAGER_MARKER ) );
}

/**
 * 注册可随Page修订保存、但不公开到REST的首页Trust meta。
 *
 * @return void
 */
function dentall_core_register_home_trust_meta() {
	$common_args = array(
		'object_subtype'    => 'page',
		'single'            => true,
		'show_in_rest'      => false,
		'revisions_enabled' => true,
		'auth_callback'     => static function ( $allowed, $meta_key, $post_id ) {
			return dentall_core_can_manage_home_trust( (int) $post_id );
		},
	);

	register_post_meta(
		'page',
		DENTALL_CORE_HOME_TRUST_META,
		array_merge(
			$common_args,
			array(
				'type'              => 'array',
				'sanitize_callback' => 'dentall_core_sanitize_home_trust_metrics',
			)
		)
	);

	register_post_meta(
		'page',
		DENTALL_CORE_HOME_TRUST_ENABLED_META,
		array_merge(
			$common_args,
			array(
				'type'              => 'boolean',
				'sanitize_callback' => 'rest_sanitize_boolean',
			)
		)
	);
}
add_action( 'init', 'dentall_core_register_home_trust_meta', 20 );

/**
 * 返回首页Page当前可展示的Trust数据。
 *
 * Local尚未保存时使用设计稿初始值；非Local必须显式启用。清空某项主值会隐藏该项。
 *
 * @param int $post_id 首页Page ID；0时读取静态首页设置。
 * @return array<int, array<string, string>>
 */
function dentall_core_get_home_trust_metrics( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : absint( get_option( 'page_on_front' ) );

	if ( ! $post_id ) {
		return array();
	}

	$has_enabled_setting = metadata_exists( 'post', $post_id, DENTALL_CORE_HOME_TRUST_ENABLED_META );

	if ( ! $has_enabled_setting && 'local' !== wp_get_environment_type() ) {
		return array();
	}

	if (
		$has_enabled_setting
		&& ! rest_sanitize_boolean( get_post_meta( $post_id, DENTALL_CORE_HOME_TRUST_ENABLED_META, true ) )
	) {
		return array();
	}

	$metrics = metadata_exists( 'post', $post_id, DENTALL_CORE_HOME_TRUST_META )
		? dentall_core_sanitize_home_trust_metrics( get_post_meta( $post_id, DENTALL_CORE_HOME_TRUST_META, true ) )
		: dentall_core_get_home_trust_defaults();

	return array_values(
		array_filter(
			$metrics,
			static function ( $metric ) {
				return '' !== $metric['value'];
			}
		)
	);
}

/**
 * 只在当前静态首页Page上注册Trust编辑区。
 *
 * @param WP_Post $post 当前Page。
 * @return void
 */
function dentall_core_add_home_trust_meta_box( $post ) {
	if (
		! $post instanceof WP_Post
		|| (int) get_option( 'page_on_front' ) !== (int) $post->ID
		|| ! dentall_core_can_manage_home_trust( $post->ID )
	) {
		return;
	}

	add_meta_box(
		'dentall-home-trust',
		__( 'DentAll homepage trust metrics', 'dentall-core' ),
		'dentall_core_render_home_trust_meta_box',
		'page',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes_page', 'dentall_core_add_home_trust_meta_box' );

/**
 * 输出首页Trust编辑区。
 *
 * @param WP_Post $post 当前首页Page。
 * @return void
 */
function dentall_core_render_home_trust_meta_box( $post ) {
	$defaults = dentall_core_get_home_trust_defaults();
	$metrics  = metadata_exists( 'post', $post->ID, DENTALL_CORE_HOME_TRUST_META )
		? dentall_core_sanitize_home_trust_metrics( get_post_meta( $post->ID, DENTALL_CORE_HOME_TRUST_META, true ) )
		: $defaults;
	$enabled = metadata_exists( 'post', $post->ID, DENTALL_CORE_HOME_TRUST_ENABLED_META )
		? rest_sanitize_boolean( get_post_meta( $post->ID, DENTALL_CORE_HOME_TRUST_ENABLED_META, true ) )
		: 'local' === wp_get_environment_type();

	wp_nonce_field( DENTALL_CORE_HOME_TRUST_NONCE_ACTION, DENTALL_CORE_HOME_TRUST_NONCE_NAME );
	?>
	<p>
		<label>
			<input type="checkbox" name="dentall_home_trust_enabled" value="1" <?php checked( $enabled ); ?>>
			<?php esc_html_e( 'Show the trust metrics section on the homepage', 'dentall-core' ); ?>
		</label>
	</p>
	<p class="description">
		<?php esc_html_e( 'Edit short text only. Icons and the five-slot order are fixed by the approved design.', 'dentall-core' ); ?>
	</p>
	<?php foreach ( $metrics as $index => $metric ) : ?>
		<fieldset>
			<legend><strong><?php echo esc_html( sprintf( __( 'Metric %1$d: %2$s', 'dentall-core' ), $index + 1, $defaults[ $index ]['icon'] ) ); ?></strong></legend>
			<p>
				<label for="dentall-home-trust-value-<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Main value', 'dentall-core' ); ?></label><br>
				<input class="widefat" id="dentall-home-trust-value-<?php echo esc_attr( $index ); ?>" name="dentall_home_trust[<?php echo esc_attr( $index ); ?>][value]" type="text" maxlength="80" value="<?php echo esc_attr( $metric['value'] ); ?>">
			</p>
			<p>
				<label for="dentall-home-trust-label-<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Label', 'dentall-core' ); ?></label><br>
				<input class="widefat" id="dentall-home-trust-label-<?php echo esc_attr( $index ); ?>" name="dentall_home_trust[<?php echo esc_attr( $index ); ?>][label]" type="text" maxlength="120" value="<?php echo esc_attr( $metric['label'] ); ?>">
			</p>
			<p>
				<label for="dentall-home-trust-description-<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Supporting text', 'dentall-core' ); ?></label><br>
				<input class="widefat" id="dentall-home-trust-description-<?php echo esc_attr( $index ); ?>" name="dentall_home_trust[<?php echo esc_attr( $index ); ?>][description]" type="text" maxlength="160" value="<?php echo esc_attr( $metric['description'] ); ?>">
			</p>
		</fieldset>
	<?php endforeach; ?>
	<?php
}

/**
 * 保存首页Trust编辑区。
 *
 * @param int $post_id 当前Page ID。
 * @return void
 */
function dentall_core_save_home_trust_meta( $post_id ) {
	if (
		defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE
		|| wp_is_post_revision( $post_id )
		|| (int) get_option( 'page_on_front' ) !== (int) $post_id
		|| ! isset( $_POST[ DENTALL_CORE_HOME_TRUST_NONCE_NAME ] )
	) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST[ DENTALL_CORE_HOME_TRUST_NONCE_NAME ] ) );

	if (
		! wp_verify_nonce( $nonce, DENTALL_CORE_HOME_TRUST_NONCE_ACTION )
		|| ! dentall_core_can_manage_home_trust( $post_id )
	) {
		return;
	}

	$raw_metrics = isset( $_POST['dentall_home_trust'] )
		? wp_unslash( $_POST['dentall_home_trust'] )
		: array();
	$metrics     = dentall_core_sanitize_home_trust_metrics( $raw_metrics );
	$enabled     = isset( $_POST['dentall_home_trust_enabled'] );

	update_post_meta( $post_id, DENTALL_CORE_HOME_TRUST_META, $metrics );
	update_post_meta( $post_id, DENTALL_CORE_HOME_TRUST_ENABLED_META, $enabled );
}
add_action( 'save_post_page', 'dentall_core_save_home_trust_meta' );
