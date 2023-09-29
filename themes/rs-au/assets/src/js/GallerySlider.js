import Slider from './Slider';

export default class WPGallery extends Slider {
	constructor( el ) {
		super( el );
		this.navCounter = el.querySelector( '[data-slider-nav-head]' );
	}

	updateNav() {
		super.updateNav();
		this.setNavCounter();
	}

	setNavCounter() {
		if ( null !== this.navCounter ) {
			this.navCounter.innerText = `${ this.currentId + 1 }`.padStart( 2, '0' );
		}
	}
}
