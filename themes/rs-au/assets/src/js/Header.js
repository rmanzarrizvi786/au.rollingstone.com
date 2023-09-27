export default class Header {
	constructor( el ) {
		this.el = el;
		this.el.pmcHeader = this;
		this.container = document.documentElement;

		this.initSticky();
		this.initSearch();
	}

	destroy() {
		this.destroySticky();
		this.destroySearch();
		delete this.el.pmcHeader;

	}

	get stickyClass() {
		return this.el.dataset.headerStickyClass || 'is-header-sticky';
	}

	get readyClass() {
		return this.el.dataset.headerReadyClass || 'is-header-ready';
	}

	get searchClass() {
		return this.el.dataset.headerSearchClass || 'is-search-expanded';
	}

	initSticky() {
		this.observerOptions = {
			root: null,
			rootMargin: '80px',
			threshold: [ 1.0 ]
		};

		this.toggleSticky = this.toggleSticky.bind( this );
		this.observer = new IntersectionObserver( this.toggleSticky, this.observerOptions );
		this.observer.observe( this.el );

		// Delay the initialization of the transition effects on the header.
		setTimeout( () => this.container.classList.add( this.readyClass ), 100 );
	}

	destroySticky() {
		this.observer.disconnect();
		this.container.classList.remove( this.stickyClass, this.readyClass );
	}

	toggleSticky( e ) {
		const ratio = e[0].intersectionRatio;

		if ( 1 <= ratio ) {
			this.container.classList.remove( this.stickyClass );
		} else {
			this.container.classList.add( this.stickyClass );
		}
	}

	initSearch() {
		this.searchTrigger = this.el.querySelector( '[data-header-search-trigger]' );

		if ( null === this.searchTrigger ) {
			return;
		}

		this.expandSearch = this.expandSearch.bind( this );
		this.collapseSearch = this.collapseSearch.bind( this );
		this.searchTrigger.addEventListener( 'click', this.expandSearch );
	}

	destroySearch() {
		document.body.removeEventListener( 'click', this.collapseSearch );
		this.searchTrigger.removeEventListener( 'click', this.expandSearch );
		this.container.classList.remove( this.searchClass );
	}

	expandSearch( e ) {
		e.preventDefault();
		e.stopPropagation();
		this.container.classList.add( this.searchClass );

		this.searchTrigger.removeEventListener( 'click', this.expandSearch );

		// Delay new event listener set up to prevent immediate search form collapsing.
		setTimeout( () => document.body.addEventListener( 'click', this.collapseSearch ), 1 );
	}

	collapseSearch( e ) {

		// Do not collapse if the search form element was clicked.
		if ( e.target === this.searchTrigger || this.searchTrigger.contains( e.target ) ) {
			return;
		}

		this.container.classList.remove( this.searchClass );
		this.searchTrigger.addEventListener( 'click', this.expandSearch );
		document.body.removeEventListener( 'click', this.collapseSearch );
	}
}
