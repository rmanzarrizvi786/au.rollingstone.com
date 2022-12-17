export default class Flyout {
	constructor () {
		this.disableScroll     = this.disableScroll.bind( this );
		this.maybeEnableScroll = this.maybeEnableScroll.bind( this );
		this.preventScroll     = this.preventScroll.bind( this );

		this.container = document.documentElement;
		this.triggers  = [ ... document.querySelectorAll( '[data-flyout]' ) ];
		this.triggers.forEach( el => el.addEventListener( 'click', e => this.handleTriggerClick( e, el ) ) );
	}

	handleTriggerClick ( e, el ) {
		let actionName = el.dataset.flyoutTrigger || 'toggle';
		const flyOutClass = el.dataset.flyout;
		const isClosingNotOpened = 'close' === actionName && ! this.container.classList.contains( flyOutClass );

		if ( 'toggle' === actionName ) {
			actionName = this.container.classList.contains( flyOutClass ) ? 'close' : 'open';
		}

		if ( 'open' === actionName ) {
			if ( undefined !== el.dataset.flyoutScrollFreeze ) {
				this.disableScroll();
			}
			this.container.classList.add( flyOutClass );
		} else {
			this.maybeEnableScroll();
			this.container.classList.remove( flyOutClass );
		}

		if ( ! isClosingNotOpened ) {
			e.preventDefault();
			e.stopPropagation();
		}
	}

	disableScroll() {
		document.body.dataset.scrollTop = window.scrollY;
		document.body.style.overflow = 'hidden';

		window.addEventListener( 'scroll', this.preventScroll );
	}

	maybeEnableScroll() {
		if ( undefined === document.body.dataset.scrollTop ) {
			return;
		}

		window.removeEventListener( 'scroll', this.preventScroll );
		document.body.style.overflow = '';
		delete document.body.dataset.scrollTop;
	}

	preventScroll() {
		window.scroll( 0, document.body.dataset.scrollTop );
	}
}
