(function ($, wpData, wcBlocksData, cartFragmentsParams) {
	'use strict';

	if (
		! $
		|| ! wpData
		|| ! wcBlocksData
		|| ! wcBlocksData.cartStore
		|| ! cartFragmentsParams
		|| ! cartFragmentsParams.wc_ajax_url
		|| ! document.body.classList.contains('woocommerce-cart')
	) {
		return;
	}

	const cartStore = wcBlocksData.cartStore;
	const $body = $(document.body);
	const $document = $(document);
	const eventNamespace = '.dentallCartHeaderSync';
	const fragmentRequestUrl = new URL(
		String(cartFragmentsParams.wc_ajax_url).replace(
			'%%endpoint%%',
			'get_refreshed_fragments'
		),
		window.location.href
	).href;
	// Woo还会因首屏缓存、跨标签页和BFCache自行刷新；按精确端点跟踪整个批次，
	// 只在旧响应全部落地后有界纠正，避免较晚的旧HTML覆盖本次Cart Store变化。
	const activeFragmentRequests = new Map();
	let cartRevision = 0;
	let latestFragmentRequestRevision = -1;
	let highestValidFragmentRevision = -1;
	let lastValidFragmentRevision = -1;
	let reconciliationPending = false;
	let lastRefreshRequestRevision = -1;
	let unsubscribeCartStore = null;

	function getItemSignature(cartData) {
		const items = cartData && Array.isArray(cartData.items) ? cartData.items : [];

		return JSON.stringify(
			items
				.map((item) => [String(item.key), Number(item.quantity)])
				.sort((first, second) => first[0].localeCompare(second[0]))
		);
	}

	function isFragmentRequest(settings) {
		if (! settings || ! settings.url) {
			return false;
		}

		try {
			return new URL(settings.url, window.location.href).href === fragmentRequestUrl;
		} catch (error) {
			return false;
		}
	}

	function hasHeaderFragment(request) {
		const data = request.responseJSON;

		return Boolean(
			data
			&& data.fragments
			&& Object.prototype.hasOwnProperty.call(
				data.fragments,
				'span.dentall-cart-content'
			)
		);
	}

	function requestFragmentRefresh() {
		if (
			! reconciliationPending
			|| activeFragmentRequests.size > 0
			|| lastRefreshRequestRevision >= cartRevision
		) {
			return;
		}

		lastRefreshRequestRevision = cartRevision;
		$body.trigger('wc_fragment_refresh');

		if (activeFragmentRequests.size === 0) {
			// Woo在Web Storage不可用时不监听刷新事件；保留服务端Header并释放门闩。
			reconciliationPending = false;
		}
	}

	$document.on(`ajaxSend${eventNamespace}`, (event, request, settings) => {
		if (! isFragmentRequest(settings)) {
			return;
		}

		activeFragmentRequests.set(request, cartRevision);
		latestFragmentRequestRevision = cartRevision;
	});

	$document.on(`ajaxComplete${eventNamespace}`, (event, request) => {
		if (! activeFragmentRequests.has(request)) {
			return;
		}

		const requestRevision = activeFragmentRequests.get(request);

		if (hasHeaderFragment(request)) {
			highestValidFragmentRevision = Math.max(
				highestValidFragmentRevision,
				requestRevision
			);
			lastValidFragmentRevision = requestRevision;
		}

		activeFragmentRequests.delete(request);

		if (activeFragmentRequests.size > 0 || ! reconciliationPending) {
			return;
		}

		if (lastValidFragmentRevision >= cartRevision) {
			reconciliationPending = false;
			return;
		}

		if (
			latestFragmentRequestRevision < cartRevision
			|| highestValidFragmentRevision >= cartRevision
		) {
			requestFragmentRefresh();
			return;
		}

		// 当前revision的响应失败或缺少Header fragment；等待下一次真实商品变化恢复。
		reconciliationPending = false;
	});

	wpData.resolveSelect(cartStore).getCartData().then((initialCartData) => {
		let previousItemSignature = getItemSignature(initialCartData);

		unsubscribeCartStore = wpData.subscribe(() => {
			const nextItemSignature = getItemSignature(
				wpData.select(cartStore).getCartData()
			);

			if (nextItemSignature === previousItemSignature) {
				return;
			}

			previousItemSignature = nextItemSignature;
			cartRevision += 1;
			reconciliationPending = true;
			requestFragmentRefresh();
		}, cartStore);
	}).catch(() => {
		// 初始Store API失败时保留服务端Header，等待下次导航恢复。
		$document.off(eventNamespace);
	});

	window.addEventListener('pagehide', (event) => {
		if (event.persisted) {
			return;
		}

		if (unsubscribeCartStore) {
			unsubscribeCartStore();
		}

		$document.off(eventNamespace);
	});
})(
	window.jQuery,
	window.wp && window.wp.data,
	window.wc && window.wc.wcBlocksData,
	window.wc_cart_fragments_params
);
