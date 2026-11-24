/* 仅负责展开状态与焦点；其它面板的避让由CSS读取现有DOM状态。 */
(() => {
	'use strict';
	const contact = document.querySelector('.dentall-floating-contact');
	const toggle = contact?.querySelector('.dentall-floating-contact__toggle');
	const links = contact?.querySelector('.dentall-floating-contact__links');
	if (!contact || !toggle || !links) return;
	const desktop = window.matchMedia('(min-width: 90rem)');
	const setOpen = (open) => {
		toggle.setAttribute('aria-expanded', String(open));
		links.hidden = !open && !desktop.matches;
	};
	const syncViewport = () => {
		const active = document.activeElement;
		setOpen(false);
		toggle.hidden = desktop.matches;
		if (getComputedStyle(contact).visibility !== 'visible') return;
		if (desktop.matches && active === toggle) links.querySelector('a')?.focus();
		if (!desktop.matches && links.contains(active)) toggle.focus();
	};
	toggle.addEventListener('click', () => {
		setOpen(toggle.getAttribute('aria-expanded') !== 'true');
	});
	contact.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
			event.preventDefault();
			event.stopPropagation();
			setOpen(false);
			toggle.focus();
		}
	});
	// 外部点击/Tab离开只收起，不抢走正在操作的控件焦点。
	for (const type of ['pointerdown', 'focusin']) {
		document.addEventListener(type, (event) => {
			if (!contact.contains(event.target)) setOpen(false);
		});
	}
	desktop.addEventListener('change', syncViewport);
	syncViewport();
	contact.hidden = false;
})();
