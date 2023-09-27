// Header's 'Subscribe' hover event listeners to show or hide subscription banner
function toggleSubscriptionBox() {

	// Intent Interval time in millisecond
	const interval = 200;

	// Hover intent timeout.
	let hoverIntentTimeout = false;

	if ( 'undefined' === typeof window.jQuery ) {
		return;
	}

	jQuery( ( $ ) => { // eslint-disable-line id-length
		// Show
		$( document ).on(
			'mouseenter',
			'.c-cover__cta--header',
			function onMouseEnter( ev ) {
				const element = $( this );
				if ( 'mouseenter' !== ev.type ) {
					return;
				}

				if ( hoverIntentTimeout ) {
					hoverIntentTimeout = window.clearTimeout( hoverIntentTimeout );
				}

				hoverIntentTimeout = window.setTimeout( () => {
					if ( true === element.is( ':hover' ) ) {
						showSubscriptionBox();
					}
				}, interval );
			}
		);

		// Hide
		$( document ).on(
			'mouseleave',
			'.l-header__subscribe',
			( ev ) => {
				if ( 'mouseleave' !== ev.type ) {
					return;
				}

				if ( hoverIntentTimeout ) {
					hoverIntentTimeout = window.clearTimeout( hoverIntentTimeout );
				}

				hideSubscriptionBox();
			}
		);
	});
}

function hideSubscriptionBox() {
	jQuery( '.l-header__wrap--subscribe' ).css( 'opacity', '0' );
	jQuery( '.l-header__wrap--subscribe' ).css( 'visibility', 'hidden' );
}

function showSubscriptionBox() {
	jQuery( '.l-header__wrap--subscribe' ).css( 'opacity', '1' );
	jQuery( '.l-header__wrap--subscribe' ).css( 'visibility', 'visible' );
}

export { toggleSubscriptionBox as default };
