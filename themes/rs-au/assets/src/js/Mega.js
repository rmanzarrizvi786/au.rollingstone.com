export default class Mega {
	constructor ( el ) {
		this.el = el;
		this.wrap = this.el.querySelector( '[data-mega-menu-wrap]' );
		this.inputs = [ ... this.el.querySelectorAll( 'input' ) ];

		this.inputs.forEach( input => {
			input.addEventListener( 'focus', () => this.handleFocus( input ) );
			input.addEventListener( 'blur', () => this.handleBlur() );
		} );
	}

	handleFocus ( input ) {
		this.wrap.style.paddingBottom = '50vh';
		this.el.scrollTo( 0, input.offsetTop );
	}

	handleBlur () {
		this.wrap.style.paddingBottom = '';
	}
}
