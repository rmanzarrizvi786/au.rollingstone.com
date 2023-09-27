export default class Section {
	constructor ( el ) {
		this.el = el;
		this.toggle = el.querySelector( '[data-section-toggle]' );
		this.name = this.el.dataset.section;
		this.localStorageName = 'PMC_RS_homeState';
		this.state = this.getState();
		this.isOpened = undefined !== this.state[this.name] ? this.state[this.name] : true;
		this.init();
	}

	init () {
		this.toggle.addEventListener( 'click', e => this.handleToggle( e ) );
	}

	handleToggle ( e ) {
		e.preventDefault();
		this.toggleSection();
	}

	toggleSection () {
		this.setSectionAppereance();
		this.isOpened = ! this.isOpened;
		this.saveState();
	}

	setSectionAppereance () {
		if ( this.isOpened ) {
			this.close();
		} else {
			this.open();
		}
	}

	open () {
		this.el.classList.remove( 'is-closed' );
		this.el.classList.remove( 'is-closing' );
	}

	close () {
		this.el.classList.add( 'is-closing' );
		setTimeout( () => this.el.classList.add( 'is-closed' ), 500 );
	}

	saveState () {
		this.state = this.getState();
		this.state[this.name] = this.isOpened;
		localStorage.setItem( this.localStorageName, JSON.stringify( this.state ) );
	}

	getState () {
		return JSON.parse( localStorage.getItem( this.localStorageName ) || '{}' );
	}
}
