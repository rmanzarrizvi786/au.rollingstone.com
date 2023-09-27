export default class Dropdown {
	constructor( el ) {
		this.el = el;
		this.el.pmcDropdown = this;
		this.classBase = this.el.classList.item( 0 );

		if ( null !== this.list ) {
			this.updateHeight();
		}

		this.toggle = this.toggle.bind( this );
		this.el.addEventListener( 'click', this.toggle );
	}

	destroy() {
		this.el.removeEventListener( 'click', this.toggle );
		this.hideItems();
		this.el.classList.remove( 'is-expanded' );
		this.el.style.height = '';
		if ( undefined !== this.el.dataset.collapsedHeight ) {
			delete this.el.dataset.collapsedHeight;
		}
		if ( undefined !== this.el.dataset.expandedHeight ) {
			delete this.el.dataset.expandedHeight;
		}
		delete this.el.pmcDropdown;
	}

	get duration() {
		return this.el.dataset.dropdownDuration || 50;
	}

	get collapseDelay() {
		return this.el.dataset.dropdownCollapseDelay || 375;
	}

	get collapsed() {
		return ! this.el.classList.contains( 'is-expanded' );
	}

	get isOverlay() {
		const position = window.getComputedStyle( this.list, null ).position;
		return ( 'absolute' === position ) || ( 'fixed' === position );
	}

	get activeItem() {
		return this.el.querySelector( '.is-active' );
	}

	get list() {
		return this.el.querySelector( `.${ this.classBase }__list` );
	}

	get items() {
		return [ ... this.el.querySelectorAll( `.${ this.classBase }__item` ) ];
	}

	isItem( node ) {
		return node.classList.contains( `${ this.classBase }__item` );
	}

	toggle( e ) {
		const item = this.findParentItem( e.target );

		// Open the list if it's collapsed.
		if ( this.collapsed ) {
			if ( null !== item ) {
				e.preventDefault();
				this.expandList();
			}
			return;
		}

		// Prevent default if active item was clicked again.
		if ( this.isActive( item ) ) {
			e.preventDefault();
		} else {
			this.setActive( item );
		}

		this.collapseList();
	}

	expandList() {
		this.el.classList.add( 'is-expanded' );
		this.setHeight();
		this.revealItems();
	}

	collapseList() {
		setTimeout( () => {
			this.el.classList.remove( 'is-expanded' );
			this.setHeight();
			this.hideItems();
		}, this.collapseDelay );
	}

	updateHeight() {
		if ( this.isOverlay ) {
			return;
		}

		this.el.dataset.collapsedHeight = `${ this.activeItem.offsetHeight }px`;
		this.el.dataset.expandedHeight = `${ this.list.offsetHeight }px`;
		this.setHeight();
	}

	setHeight() {
		if ( this.isOverlay ) {
			return;
		}

		this.el.style.height = ( this.collapsed ) ? this.el.dataset.collapsedHeight : this.el.dataset.expandedHeight;
	}

	revealItems() {
		let items = this.items.filter( item => ! item.classList.contains( 'is-active' ) );
		const shiftAndShow = () => {
			const item = items.shift();
			item.classList.add( 'is-visible' );
		};

		if ( 0 === items.length ) {
			return;
		}

		shiftAndShow();

		const interval = setInterval( () => {
			if ( 0 === items.length ) {
				clearInterval( interval );
				return;
			}
			shiftAndShow();
		}, this.duration );
	}

	hideItems() {
		this.items.forEach( item => item.classList.remove( 'is-visible' ) );
	}

	findParentItem( child ) {
		let currentNode = child;

		while ( ! this.isItem( currentNode ) ) {
			if ( currentNode === this.list || currentNode === this.el ) {
				return null;
			}

			currentNode = currentNode.parentNode;
		}

		return currentNode;
	}

	isActive( item ) {
		return item.classList.contains( 'is-active' );
	}

	setActive( item ) {
		setTimeout( () => {
			this.activeItem.classList.remove( 'is-active' );
			item.classList.add( 'is-active' );
		}, this.collapseDelay );
	}
}
