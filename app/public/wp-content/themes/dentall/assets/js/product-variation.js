(( $ ) => {
	'use strict';

	/* 只映射Woo原生禁用样式的语义；保留按钮焦点、原生提示与服务端加购校验。 */
	const syncButtonState = ( $form ) => {
		const $button = $form.find( '.single_add_to_cart_button' );

		$button.attr( 'aria-disabled', $button.hasClass( 'disabled' ) ? 'true' : 'false' );
	};

	$( () => {
		$( 'form.variations_form' ).each( function () {
			const $form = $( this );

			/* Woo延迟初始化；收到原生show/hide事件前不宣告可购买。 */
			$form.find( '.single_add_to_cart_button' ).attr( 'aria-disabled', 'true' );
			$form.on( 'hide_variation.dentall show_variation.dentall', () => {
				syncButtonState( $form );
			} );
		} );
	} );
} )( jQuery );
