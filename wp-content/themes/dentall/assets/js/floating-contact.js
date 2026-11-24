/* CSS负责显示详情；Escape仅关闭提示，不移动焦点或改变原生链接行为。 */
(() => {
	'use strict';
	const links = document.querySelectorAll('.dentall-floating-contact__links a');
	if (!links.length) return;
	for (const link of links) {
		const reset = () => link.classList.remove('is-detail-dismissed');
		link.addEventListener('pointerenter', reset);
		link.addEventListener('focus', reset);
	}
	document.addEventListener('keydown', (event) => {
		if (event.key !== 'Escape') return;
		for (const link of links) {
			if (link.matches(':hover, :focus-visible')) {
				link.classList.add('is-detail-dismissed');
			}
		}
	});
})();
