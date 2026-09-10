(( $ ) => {
	'use strict';

	const config = window.dentallVariationStatus || {};
	const requestTimeout = Number.parseInt( config.requestTimeout, 10 ) || 15000;
	const checkingText = 'string' === typeof config.checkingText ? config.checkingText : '';
	const errorText = 'string' === typeof config.errorText ? config.errorText : '';

	/* 只映射Woo原生禁用样式的语义；保留按钮焦点、原生提示与服务端加购校验。 */
	const syncButtonState = ( $form ) => {
		const $button = $form.find( '.single_add_to_cart_button' );

		$button.attr( 'aria-disabled', $button.hasClass( 'disabled' ) ? 'true' : 'false' );
	};

	const getSelectionSignature = ( $form ) => JSON.stringify(
		$form.find( '.variations select' ).map( function () {
			return [ this.name, this.value ];
		} ).get()
	);

	const setStatus = ( $form, message, isError = false ) => {
		let $status = $form.children( '.dentall-variation-status' );

		if ( ! $status.length ) {
			$status = $( '<div>', {
				class: 'dentall-variation-status dentall-notice',
				role: 'status',
				'aria-live': 'polite',
				'aria-atomic': 'true',
				hidden: true,
			} ).insertBefore( $form.find( '.single_variation_wrap' ).first() );
		}

		$status
			.toggleClass( 'dentall-notice--error', isError )
			.text( message )
			.prop( 'hidden', ! message );
	};

	const clearTimer = ( state ) => {
		if ( state.timeoutId ) {
			window.clearTimeout( state.timeoutId );
			state.timeoutId = 0;
		}
	};

	const setBusy = ( $form, isBusy ) => {
		$form
			.find( '.single_variation_wrap' )
			.first()
			.attr( 'aria-busy', isBusy ? 'true' : null );
	};

	const hideDisplayedVariation = ( $form, preserveVariationId = false ) => {
		if ( ! preserveVariationId ) {
			$form
				.find( 'input[name="variation_id"], input.variation_id' )
				.val( '' )
				.trigger( 'change' );
		}
		$form
			.find( '.single_variation' )
			.stop( true, true )
			.empty()
			.hide()
			.trigger( 'hide_variation' );
		syncButtonState( $form );
	};

	const finishRequestState = ( $form, state ) => {
		clearTimer( state );
		state.xhr = null;
		state.phase = 'idle';
		state.variationId = '';
		state.timedOut = false;
		setBusy( $form, false );
		setStatus( $form, '' );
	};

	const resetDisplayedVariation = ( $form, state ) => {
		state.suppressReset = true;
		$form.trigger( 'reset_data' );
		state.suppressReset = false;
		hideDisplayedVariation( $form );
	};

	const trackAjaxRequest = ( $form, state ) => {
		const variationForm = state.variationForm;
		const xhr = variationForm && variationForm.xhr;

		if (
			! variationForm
			|| ! variationForm.useAjax
			|| ! xhr
			|| 'function' !== typeof xhr.done
			|| 'function' !== typeof xhr.fail
			|| 'function' !== typeof xhr.abort
			|| state.xhr === xhr
		) {
			return;
		}

		clearTimer( state );
		state.xhr = xhr;
		state.signature = getSelectionSignature( $form );
		state.phase = 'pending';
		state.variationId = '';
		state.timedOut = false;
		resetDisplayedVariation( $form, state );
		setBusy( $form, true );
		setStatus( $form, checkingText );

		state.timeoutId = window.setTimeout( () => {
			if ( state.xhr === xhr && 'pending' === state.phase ) {
				state.timedOut = true;
				xhr.abort( 'timeout' );
			}
		}, requestTimeout );

		xhr.done( ( variation ) => {
			if ( state.xhr !== xhr || state.signature !== getSelectionSignature( $form ) ) {
				return;
			}

			clearTimer( state );
			if ( variation && variation.variation_id ) {
				state.phase = 'resolved';
				state.variationId = String( variation.variation_id );
				return;
			}

			finishRequestState( $form, state );
		} );

		xhr.fail( ( request, textStatus ) => {
			if ( state.xhr !== xhr || state.signature !== getSelectionSignature( $form ) ) {
				return;
			}

			if (
				( 'abort' === textStatus || 'abort' === request.statusText )
				&& ! state.timedOut
			) {
				finishRequestState( $form, state );
				return;
			}

			clearTimer( state );
			state.phase = 'error';
			state.variationId = '';
			setBusy( $form, false );
			hideDisplayedVariation( $form );
			setStatus(
				$form,
				errorText,
				true
			);
		} );
	};

	$( () => {
		$( 'form.variations_form' ).each( function () {
			const $form = $( this );
			const state = {
				variationForm: null,
				xhr: null,
				signature: '',
				phase: 'idle',
				variationId: '',
				timeoutId: 0,
				timedOut: false,
				suppressReset: false,
			};

			$form.find( '.single_add_to_cart_button' ).attr( 'aria-disabled', 'true' );

			$form.on( 'wc_variation_form.dentall', ( event, variationForm ) => {
				state.variationForm = variationForm;
				trackAjaxRequest( $form, state );
			} );

			/* Woo的同名处理器先创建或取消请求；DentAll随后只观察当前表单实例。 */
			$form.on( 'check_variations.dentall', () => {
				const variationForm = state.variationForm;

				if ( ! variationForm || ! variationForm.useAjax ) {
					return;
				}

				const attributes = variationForm.getChosenAttributes();

				if ( ! attributes || attributes.count !== attributes.chosenCount ) {
					if ( state.xhr && 'pending' === state.phase ) {
						state.xhr.abort();
					}
					finishRequestState( $form, state );
					return;
				}

				trackAjaxRequest( $form, state );
			} );

			$form.on( 'reset_data.dentall', () => {
				if (
					state.variationForm
					&& state.variationForm.useAjax
					&& ! state.suppressReset
				) {
					if ( state.xhr && 'pending' === state.phase ) {
						state.xhr.abort();
					}
					finishRequestState( $form, state );
				}
			} );

			$form.on( 'hide_variation.dentall', () => {
				syncButtonState( $form );
			} );

			$form.on( 'show_variation.dentall', ( event, variation ) => {
				if ( ! state.variationForm || ! state.variationForm.useAjax ) {
					syncButtonState( $form );
					return;
				}

				if (
					( 'resolved' !== state.phase && 'shown' !== state.phase )
					|| state.signature !== getSelectionSignature( $form )
					|| state.variationId !== String( variation && variation.variation_id )
				) {
					event.stopImmediatePropagation();
					hideDisplayedVariation(
						$form,
						'resolved' === state.phase || 'shown' === state.phase
					);
					return;
				}

				state.phase = 'shown';
				state.xhr = null;
				setBusy( $form, false );
				setStatus( $form, '' );
				syncButtonState( $form );
			} );
		} );
	} );
} )( jQuery );
