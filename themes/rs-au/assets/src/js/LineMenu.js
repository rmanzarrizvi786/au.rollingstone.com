export default class LineMenu {
	constructor( el ) {
		this.el = el;
		this.el.pmcLineMenu = this;
		this.classBase = this.el.classList.item( 0 );

		if ( 'complete' === document.readyState ) {
			this.init();
		} else {
			window.addEventListener( 'load', () => this.init() );
		}
	}

	init() {
		this.addIndicator();
		this.refresh();

		this.onMouseMove    = this.onMouseMove.bind( this );
		this.attachListener = this.attachListener.bind( this );
		this.removeListener = this.removeListener.bind( this );

		this.el.addEventListener( 'mouseenter', this.attachListener );
		this.el.addEventListener( 'mouseleave', this.removeListener );
	}

	refresh() {
		this.calculateOffset();
		this.setInitialState();
	}

	destroy() {
		this.el.removeEventListener( 'mouseenter', this.attachListener );
		this.el.removeEventListener( 'mouseleave', this.removeListener );
		this.el.removeEventListener( 'mousemove', this.onMouseMove );
		this.indicator.remove();
		delete this.el.pmcLineMenu;
	}

	get activeLink() {
		return this.el.querySelector( `.is-active .${ this.classBase }__link` );
	}

	get links() {
		return [ ... this.el.querySelectorAll( `.${ this.classBase }__link` ) ];
	}

	addIndicator() {
		this.indicator = document.createElement( 'div' );
		this.indicator.classList.add( 'line-menu-indicator' );
		this.indicator.style.display = 'none';
		this.el.appendChild( this.indicator );
	}

	attachListener() {
		this.el.addEventListener( 'mousemove', this.onMouseMove );
	}

	removeListener() {
		this.setInitialState();
		this.el.removeEventListener( 'mousemove', this.onMouseMove );
	}

	calculateOffset() {
		this.links.forEach( link => {
			const item = link.parentNode;
			link.dataset.lineMenuLeft = Math.floor( item.offsetLeft + ( item.offsetWidth / 2 ) );
		} );
	}

	setInitialState() {
		this.currentLink = this.activeLink;
		this.makeHover( this.currentLink );
		this.indicator.style.display = '';
	}

	onMouseMove( e ) {
		const link = e.target;

		if ( link === this.currentLink ) {
			return;
		}

		this.makeHover( link );
	}

	makeHover( link ) {
		if ( ! link ) {
			return;
		}
		this.currentLink = link;
		this.indicator.style.transform = `translateX( ${ link.dataset.lineMenuLeft }px )`;
	}
}
