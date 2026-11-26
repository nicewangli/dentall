/* 保留 Fluent Forms 生成的申请编号和可访问提示，页头商品以 Woo 会话为准。 */
document.addEventListener( 'fluentform_submission_success', function ( event ) {
	const result = event.detail?.response?.data?.result;
	if ( ! [ 'cleared', 'retained' ].includes( result?.dentall_quote_cart ) ) {
		return;
	}

	const form = event.detail.form;
	const summary = form.closest( '.dentall-shipping-request' )?.querySelector( '.dentall-shipping-request__summary' );
	const heading = summary?.querySelector( 'h3' );
	const items = summary?.querySelector( 'table' );
	if ( heading && items ) {
		heading.textContent = result.dentall_items_heading;
		summary.replaceChildren( heading, items );
		// Fluent Forms 在成功事件之后同步插入回执；把已提交清单移到编号和继续选购之间。
		queueMicrotask( function () {
			const notice = form.nextElementSibling;
			if ( notice?.classList.contains( 'ff-message-success' ) ) {
				notice.lastElementChild?.before( summary );
			}
		} );
	}

	const $ = window.jQuery;
	const endpoint = window.wc_cart_fragments_params?.wc_ajax_url?.replace( '%%endpoint%%', 'get_refreshed_fragments' );
	if ( ! endpoint ) {
		return;
	}

	// Woo 在浏览器禁止 Web Storage 时不监听刷新事件。此时同样从原生端点取最新片段。
	let refreshing = false;
	function observeRefresh( event, request, settings ) {
		if ( settings.url === endpoint ) {
			refreshing = true;
		}
	}
	$( document ).on( 'ajaxSend', observeRefresh );
	$( document.body ).trigger( 'wc_fragment_refresh' );
	$( document ).off( 'ajaxSend', observeRefresh );
	if ( ! refreshing ) {
		$.post( endpoint ).done( function ( response ) {
			$.each( response?.fragments, function ( selector, html ) {
				$( selector ).replaceWith( html );
			} );
		} );
	}
} );
