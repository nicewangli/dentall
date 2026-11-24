/* 全站壳层增强；与是否配置分类菜单无关，不介入商城数据或表单。 */
(() => {
	'use strict';

	const header = document.querySelector('.site-header');
	const purchaseBar = document.querySelector('.storefront-sticky-add-to-cart');
	const button = document.querySelector('.dentall-back-to-top');

	// 实际高度覆盖长菜单、字体加载和缩放；仅PC样式使用该值避让锚点。
	if (header) {
		const measureHeader = () => {
			document.documentElement.style.setProperty('--dentall-header-height', `${header.offsetHeight}px`);
			if (purchaseBar) document.documentElement.style.setProperty('--dentall-purchase-bar-height', `${purchaseBar.offsetHeight}px`);
		};
		const observer = new ResizeObserver(measureHeader);
		observer.observe(header);
		if (purchaseBar) observer.observe(purchaseBar);
		measureHeader();
	}

	if (!button) return;
	const updateButton = () => {
		const scrollRange = document.documentElement.scrollHeight - window.innerHeight;
		const hidden = window.scrollY <= Math.min(window.innerHeight, scrollRange / 2);
		button.hidden = hidden;
	};

	button.addEventListener('click', () => {
		window.scrollTo({
			top: 0,
			behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth',
		});
	});
	window.addEventListener('scroll', updateButton, { passive: true });
	window.addEventListener('resize', updateButton);
	updateButton();
})();
