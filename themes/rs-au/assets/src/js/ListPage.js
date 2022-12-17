export default class ListPage {
	constructor ( el ) {
		this.el = el;

		this.initList();
		this.initPermalink();
		this.initScroll();
		this.initLoadPrevious();
		this.initProgressBar();
		this.initNavBar();
	}

	/*
	 * List and list items handling.
	 */
	initList() {
		this._currentItem    = null;
		this.hiddenContainer = this.el.querySelector( '[data-list-hidden]' );
		this.items           = [ ... this.el.querySelectorAll( '[data-list-item]' ) ];

		this.firstVisibleItem = this.firstItem;
		if ( this.hiddenContainer ) {
			this.firstVisibleItem = this.items.find( item => ! this.hiddenContainer.contains( item ) );
		}
	}

	get totalItems() {
		return this.el.dataset.listTotal;
	}

	get firstItem() {
		return this.items[0];
	}

	get lastItem() {
		return this.items[ this.items.length - 1 ];
	}

	get firstVisibleItem() {
		return this._firstVisibleItem;
	}

	set firstVisibleItem( item ) {
		return this._firstVisibleItem = item;
	}

	get currentItem() {
		return this._currentItem;
	}

	set currentItem( item ) {
		this._currentItem = item;

		this.updateState();
		this.updateProgressBar();
		this.updateNavBar();

		return this._currentItem;
	}

	get currentPosition() {
		const item = ( this.currentItem ) ? this.currentItem : this.firstVisibleItem;
		return parseInt( item.dataset.listItem, 10 );
	}

	/*
	 * Permalinks handling.
	 */

	initPermalink() {
		this.listTitle = document.head.querySelector( 'title' ).innerText;

		if ( ! this.listPermalink ) {
			this.listPermalink = window.location.href;
		}
	}

	get listPermalink() {
		return this.el.dataset.listPermalink;
	}

	set listPermalink( permalink ) {
		return this.el.dataset.listPermalink = permalink;
	}

	get currentTitle() {
		if ( null === this.currentItem || ! this.currentItem.dataset.listTitle ) {
			return this.listPermalink;
		}

		return this.currentItem.dataset.listTitle;
	}

	get currentPermalink() {
		if ( null === this.currentItem || ! this.currentItem.dataset.listPermalink ) {
			return this.listPermalink;
		}
		const { listPermalink } = this.currentItem.dataset;

		if ( -1 === listPermalink.indexOf( '?' ) ) {
			return listPermalink;
		}

		const [ base, params ] = listPermalink.split( '?' );
		const locationSearch   = this.mergeSearchParams( window.location.search, params );

		return base + '?' + locationSearch;
	}

	updateState() {

		var itemAuthor,
			itemId,
			pmcGaDimensionsCopy;

		window.history.replaceState( null, this.currentTitle, this.currentPermalink );

		//Track View
		if ( 'undefined' !== typeof global_urlhashchanged && jQuery.isFunction( global_urlhashchanged ) ) {

			if ( 'undefined' !== typeof pmc_ga_mapped_dimensions &&
				'undefined' !== typeof pmc_ga_mapped_dimensions.author &&
				'undefined' !== typeof pmc_ga_mapped_dimensions['child-post-id'] &&
				'undefined' !== typeof pmc_ga_mapped_dimensions['page-subtype'] &&
				'undefined' !== typeof this.currentItem &&
				'undefined' !== typeof this.currentItem.dataset &&
				'undefined' !== typeof this.currentItem.dataset.listItemAuthors &&
				'undefined' !== typeof this.currentItem.dataset.listItemId &&
				'undefined' !== typeof pmc_ga_dimensions
			) {
				itemAuthor = this.currentItem.dataset.listItemAuthors;
				itemId = this.currentItem.dataset.listItemId;
				pmcGaDimensionsCopy = pmc_ga_dimensions;
				pmcGaDimensionsCopy[ 'dimension' + pmc_ga_mapped_dimensions['page-subtype'] ] = 'single-pmc_list_item';
				pmcGaDimensionsCopy[ 'dimension' + pmc_ga_mapped_dimensions.author ] = itemAuthor;
				pmcGaDimensionsCopy[ 'dimension' + pmc_ga_mapped_dimensions['child-post-id'] ] = itemId;
				ga( 'set', pmcGaDimensionsCopy );
			}

			try {
				global_urlhashchanged();
			} catch ( e ) {}

		}
	}

	mergeSearchParams( baseLocationSearch, newLocationSearch ) {
		if ( 'undefined' === typeof URLSearchParams ) {
			return newLocationSearch;
		}

		const baseParams = new URLSearchParams( baseLocationSearch );
		const newParams  = new URLSearchParams( newLocationSearch );

		newParams.forEach( ( value, key ) => baseParams.set( key, value ) );

		return baseParams;
	}

	/*
	 * Scroll / intersection handling.
	 */

	initScroll() {
		const options = {
			root: null,
			rootMargin: '150px 0px 0px 0px',
			threshold: [ 0.5 ]
		};

		this.addIntersectionObserver = this.addIntersectionObserver.bind( this );
		this.onIntersect = this.onIntersect.bind( this );

		this.observer = new IntersectionObserver( this.onIntersect, options );
		this.scrollY = window.scrollY;

		document.addEventListener( 'DOMContentLoaded', this.addIntersectionObserver );
	}

	addIntersectionObserver() {
		this.items.forEach( item => this.observer.observe( item ) );

		// Remove auto focus helper element if it exists.
		const autoFocusEl = this.el.querySelector( '[data-list-autofocus]' );
		if ( autoFocusEl ) {
			autoFocusEl.remove();
		}
	}

	refreshIntersectionObserver() {
		this.items.forEach( item => this.observer.unobserve( item ) );
		this.addIntersectionObserver();
	}

	onIntersect( entries ) {
		const currentEntry = entries.reverse().find( entry => entry.isIntersecting );
		if ( ! currentEntry ) {
			return;
		}

		const item = currentEntry.target;

		if ( null !== this.currentItem ) {
			const { y }   = currentEntry.intersectionRect;
			const isStart = item === this.firstVisibleItem && 0 < y;
			const isEnd   = item === this.lastItem && 0 > y;

			const currentScrollY  = window.scrollY;
			const isScrollingDown = currentScrollY > this.scrollY;
			this.scrollY          = currentScrollY;

			const currentIndex = this.items.indexOf( this.currentItem );
			const newIndex     = this.items.indexOf( item );

			if ( ( isScrollingDown && newIndex <= currentIndex ) || ( ! isScrollingDown && newIndex >= currentIndex ) ) {
				return;
			}

			if ( isStart || isEnd ) {
				this.currentItem = null;
				return;
			}
		}

		if ( item !== this.currentItem ) {
			this.currentItem = item;
		}
	}

	/*
	 * Load Previous button handling.
	 */

	initLoadPrevious() {
		this.loadPreviousButton = this.el.querySelector( '[data-list-load-previous]' );

		if ( ! this.loadPreviousButton ) {
			return;
		}

		this.loadPrevious = this.loadPrevious.bind( this );
		this.loadPreviousButton.addEventListener( 'click', this.loadPrevious );
	}

	loadPrevious( e ) {
		e.preventDefault();
		this.loadPreviousButton.removeEventListener( 'click', this.loadPrevious );

		if ( ! this.hiddenContainer ) {
			return;
		}

		this.hiddenContainer.hidden = false;
		this.loadPreviousButton.remove();
		delete this.loadPreviousButton;
		this.refreshIntersectionObserver();

		window.scrollTo( 0, window.scrollY + this.hiddenContainer.offsetHeight );
	}

	/*
	 * Progress bar handling.
	 */

	initProgressBar() {
		this.progressBar = this.el.querySelector( '[data-list-progress-bar]' );

		// Do not activate progress bar if it's hidden.
		if ( this.progressBar && 'none' === window.getComputedStyle( this.progressBar ).display ) {
			this.progressBar = null;
			return;
		}

		this.updateProgressBar();
	}

	updateProgressBar() {
		if ( ! this.progressBar ) {
			return;
		}

		let currentProgress = 0;
		if ( this.currentRange ) {
			if ( 'asc' === this.currentRange.order ) {
				currentProgress = this.currentPosition / ( this.totalItems - 1 );
			} else {
				currentProgress = ( this.totalItems - this.currentPosition + 1 ) / this.totalItems;
			}
		}

		this.progressBar.style.transform = `scaleX(${ currentProgress })`;
	}

	/*
	 * Navigation bar handling.
	 */

	initNavBar() {
		this._currentRange = null;
		this.nav           = this.el.querySelector( '[data-list-nav]' );
		if ( null === this.nav ) {
			return;
		}

		this.navLinks = [ ... this.nav.querySelectorAll( '[data-list-nav-item]' ) ];

		this.defineRanges();
		this.updateNavBar();

		this.nav.addEventListener( 'click', e => this.onNavClick( e ) );
	}

	get currentRange() {
		return this._currentRange;
	}

	set currentRange( range ) {
		if ( this._currentRange ) {
			this._currentRange.el.classList.remove( 'is-active' );
		}
		range.el.classList.add( 'is-active' );

		return this._currentRange = range;
	}

	defineRanges() {
		if ( ! this.navLinks ) {
			return;
		}

		this.ranges = this.navLinks.map( el => {
			const start = parseInt( el.dataset.listRangeStart, 10 );
			const end   = parseInt( el.dataset.listRangeEnd, 10 );

			if ( start < end ) {
				return { el, start, end, order: 'asc' };
			} else {
				return { el, start: end, end: start, order: 'desc' };
			}
		} );
	}

	updateNavBar() {
		if ( ! this.ranges || ! this.currentPosition ) {
			return;
		}

		const range = this.ranges.find( r => this.currentPosition >= r.start && this.currentPosition <= r.end );
		if ( range !== this.currentRange ) {
			this.currentRange = range;
		}
	}

	onNavClick( e ) {
		const { target } = e;

		if ( target.dataset && undefined !== target.dataset.listNavItem ) {
			const rangeStart = parseInt( target.dataset.listRangeStart, 10 );
			const targetItem = this.items.find( item => rangeStart === parseInt( item.dataset.listItem, 10 ) );

			if ( undefined !== targetItem ) {
				e.preventDefault();

				const topOffset = 150;
				window.scrollTo( 0, targetItem.offsetTop + topOffset );

				const currentItemResetDelay = 20;
				setTimeout( () => this.currentItem = targetItem, currentItemResetDelay );
			}
		}
	}
}
