/* 分类折叠仅增强同一棵原生菜单；链接和桌面下拉仍由WordPress/Storefront负责。 */
(() => {
	'use strict';

	const item = document.querySelector('#site-navigation .dentall-menu-categories');
	const button = item?.querySelector(':scope > .dentall-category-toggle');
	const submenu = item?.querySelector(':scope > .sub-menu');
	if (!button || !submenu) return;

	submenu.id = button.getAttribute('aria-controls');
	item.classList.add('has-category-toggle');
	button.hidden = false;
	button.addEventListener('click', () => {
		button.setAttribute('aria-expanded', String(button.getAttribute('aria-expanded') !== 'true'));
	});

	// 每次重新打开主菜单都回到紧凑状态，不把上次的长分类列表带入。
	document.querySelector('#site-navigation-menu-toggle')?.addEventListener('click', () => {
		button.setAttribute('aria-expanded', 'false');
	});
	item.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && button.offsetParent && button.getAttribute('aria-expanded') === 'true') {
			event.preventDefault();
			button.setAttribute('aria-expanded', 'false');
			button.focus();
		}
	});
})();
