( function () {
	'use strict';

	let ready = false;
	let current;
	let revision = 0;
	window.dataLayer = window.dataLayer || [];

	function hasConsent( expectedRevision ) {
		try {
			return ( arguments.length === 0 || expectedRevision === revision ) && ready && window.wp_consent_type === 'optin'
				&& typeof window.cmplz_has_consent === 'function' && typeof window.wp_has_consent === 'function'
				&& window.cmplz_has_consent( 'statistics' ) === true && window.wp_has_consent( 'statistics' ) === true;
		} catch ( error ) {
			return false;
		}
	}

	function update() {
		const next = hasConsent();
		if ( next === current ) {
			return;
		}
		current = next;
		// 只接受当前同意版本，避免慢加载 GTM 回放撤回前的旧事件。
		revision++;
		// GTM 先同步 Consent API 与 ga-disable，再允许业务监听器产生同意后的事件。
		window.dataLayer.push( { event: 'dentall_consent_update', dentall_analytics_consent: next ? 'granted' : 'denied', dentall_consent_revision: revision } );
		document.dispatchEvent( new CustomEvent( 'dentall_measurement_consent', { detail: next } ) );
	}

	window.dentallMeasurementConsent = { hasConsent: hasConsent, get revision() { return revision; } };
	document.addEventListener( 'cmplz_cookie_warning_loaded', function () {
		ready = true;
		update();
	} );
	document.addEventListener( 'cmplz_status_change', update );
	document.addEventListener( 'wp_listen_for_consent_change', update );
	// 返回缓存页面或其它标签页改过选择后，先重新同步同意，再让 Google 恢复页面活动。
	window.addEventListener( 'pageshow', update );
	window.addEventListener( 'focus', update );
	document.addEventListener( 'visibilitychange', update );
	update();
}() );
