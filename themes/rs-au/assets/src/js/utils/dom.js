export const matchesSelector = ( selector, el ) => el.matches && el.matches( selector );

export const delegateEvent = ( parent, event, selector, callback ) => {
	parent.addEventListener( event, e => {
		const matchingNode = matchesSelector( selector, e.target ) ? e.target : e.target.closest( selector );

		if ( matchingNode ) {
			callback( e, matchingNode );
		}
	} );
};

export const triggerEvent = ( el, type ) => {
	const e = document.createEvent( 'HTMLEvents' );
	e.initEvent( type, false, true );
	el.dispatchEvent( e );
};
