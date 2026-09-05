(() => {
	'use strict';

	const dialog = document.querySelector('#dentall-catalog-filter-dialog');
	const filters = document.querySelector('#dentall-catalog-filters');
	const trigger = document.querySelector('[data-dentall-filter-toggle]');
	const header = document.querySelector('[data-dentall-filter-header]');
	const closeButton = document.querySelector('[data-dentall-filter-close]');
	const title = document.querySelector('#dentall-catalog-filter-dialog-title');

	/* 原生dialog不可用时不暴露失效按钮；服务端目录和PC侧栏仍可使用。 */
	if (
		!dialog
		|| !filters
		|| !trigger
		|| !header
		|| !closeButton
		|| !title
		|| typeof dialog.showModal !== 'function'
	) {
		return;
	}

	const desktop = window.matchMedia('(min-width: 75rem)');
	let backdropPointerStarted = false;
	let invalidRangeOpened = false;

	const setScrollLock = (locked) => {
		document.documentElement.classList.toggle('dentall-catalog-filter-is-open', locked);
		document.body.classList.toggle('dentall-catalog-filter-is-open', locked);
	};

	const focusAfterPaint = (element) => {
		window.requestAnimationFrame(() => {
			if (dialog.open && !desktop.matches) {
				element.focus({ preventScroll: true });
			}
		});
	};

	const openDrawer = (focusTarget = title) => {
		if (desktop.matches || dialog.open) {
			return;
		}

		dialog.showModal();
		trigger.setAttribute('aria-expanded', 'true');
		setScrollLock(true);
		focusAfterPaint(focusTarget);
	};

	const closeDrawer = (shouldReturnFocus = true) => {
		if (dialog.open) {
			dialog.close();
		}

		trigger.setAttribute('aria-expanded', 'false');
		setScrollLock(false);

		if (shouldReturnFocus && !desktop.matches && !trigger.hidden) {
			window.requestAnimationFrame(() => {
				if (!dialog.open && !desktop.matches && !trigger.hidden) {
					trigger.focus({ preventScroll: true });
				}
			});
		}
	};

	const syncLayout = () => {
		const activeElement = document.activeElement;
		const focusWasInFilters = filters.contains(activeElement);

		if (desktop.matches) {
			const filtersWereInDialog = filters.parentNode === dialog;
			const shouldRefocus = filtersWereInDialog && (focusWasInFilters || activeElement === trigger);

			closeDrawer(false);

			/* 只移动D50同一份筛选aside，不复制控件或查询。 */
			if (filtersWereInDialog) {
				dialog.parentNode.insertBefore(filters, dialog);
			}

			header.hidden = true;
			trigger.hidden = true;

			if (shouldRefocus) {
				window.requestAnimationFrame(() => {
					if (desktop.matches && filters.parentNode !== dialog) {
						filters.focus({ preventScroll: true });
					}
				});
			}

			return;
		}

		trigger.hidden = false;

		if (focusWasInFilters && !dialog.open) {
			trigger.focus({ preventScroll: true });
		}

		if (filters.parentNode !== dialog) {
			dialog.append(filters);
		}

		header.hidden = false;

		const invalidField = filters.querySelector('[aria-invalid="true"]');

		if (invalidField && !invalidRangeOpened) {
			invalidRangeOpened = true;
			openDrawer(invalidField);
		}
	};

	trigger.addEventListener('click', () => openDrawer());
	closeButton.addEventListener('click', () => closeDrawer());
	dialog.addEventListener('keydown', (event) => {
		if (event.key === 'Escape') {
			event.preventDefault();
			closeDrawer();
		}
	});
	dialog.addEventListener('cancel', (event) => {
		event.preventDefault();
		closeDrawer();
	});

	dialog.addEventListener('pointerdown', (event) => {
		backdropPointerStarted = event.target === dialog;
	});

	dialog.addEventListener('pointerup', (event) => {
		/* 按下和抬起都在遮罩区才关闭，避免拖动经过面板时误关。 */
		if (backdropPointerStarted && event.target === dialog) {
			closeDrawer();
		}

		backdropPointerStarted = false;
	});

	dialog.addEventListener('pointercancel', () => {
		backdropPointerStarted = false;
	});

	desktop.addEventListener('change', syncLayout);
	window.addEventListener('pageshow', syncLayout);
	window.addEventListener('pagehide', () => {
		invalidRangeOpened = false;
		closeDrawer(false);
	});

	syncLayout();
})();
