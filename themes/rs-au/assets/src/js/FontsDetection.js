// FontFaceObserver https://github.com/bramstein/fontfaceobserver.
import FontFaceObserver from './vendor/fontfaceobserver.standalone';

const FontsDetection = {
	init( fonts ) {
		const timeout  = 15000;
		const promises = [];

		document.documentElement.classList.add( 'fonts-loading' );

		fonts.forEach( font => {
			if ( 0 === font.length || '' === font[0] ) {
				return;
			}
			promises.push( new FontFaceObserver( font[0], font[1] ).load( null, timeout ) );
		} );

		if ( 0 !== fonts.length ) {
			Promise.all( promises ).then( function() {
				document.documentElement.classList.remove( 'fonts-loading' );
				document.documentElement.classList.add( 'fonts-loaded' );
			} );
		}
	}
};

export default FontsDetection;
