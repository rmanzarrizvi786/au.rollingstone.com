export default class Ripple {
	constructor( el ) {
		this.el = el;
		this.el.pmcRipple = this;

		this.ripple = this.ripple.bind( this );
		this.el.addEventListener( 'click', this.ripple );
	}

	destroy() {
		this.el.removeEventListener( 'click', this.ripple );
		if ( undefined !== this.el.dataset.rippleTrigger ) {
			delete this.el.dataset.rippleTrigger;
		}
		if ( undefined !== this.el.dataset.rippleLocation ) {
			delete this.el.dataset.rippleLocation;
		}
		delete this.el.pmcRipple;
	}

	ripple( e ) {
		if ( undefined !== this.el.dataset.rippleTrigger ) {
			delete this.el.dataset.rippleTrigger;
		}

		const offsetLeft = ( e.clientX - this.el.offsetLeft ) / this.el.offsetWidth;
		let rippleLocation = 'center';

		if ( 0.33 > offsetLeft ) {
			rippleLocation = 'left';
		} else if ( 0.66 < offsetLeft ) {
			rippleLocation = 'right';
		}

		this.el.dataset.rippleLocation = rippleLocation;
		this.el.dataset.rippleTrigger = '';
	}
}
