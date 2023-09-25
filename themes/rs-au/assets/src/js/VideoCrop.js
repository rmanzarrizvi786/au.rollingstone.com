/* eslint-disable */

export default class VideoCrop {

	constructor ( el ) {
		this.el = el;
		this.handleClick = this.handleClick.bind( this );
		this.jwP = el.querySelectorAll( '[id^="jwplayer_"][id$="_div"]' );
		this.JWPlaylists = {};

		this.jwP.forEach( el => this.setJWPlaylist( el ) );
		this.el.addEventListener( 'click', this.handleClick );

		this.isGallery = false;
		this.jwplayerParentElement = false;
		this.jwplayerElementDOMID = false;
		this.jwplayerInstance = false;
		this.isJWPlayerActive = false;
	}

	setJWPlaylist ( el ) {
		let playlist = false;

		if ( 'function' === typeof jwplayer && 'function' === typeof( jwplayer( el ).getPlaylist ) ) {

			playlist = jwplayer( el ).getPlaylist();

			if ( 'undefined' !== typeof playlist ) {
				playlist = playlist.slice();
			} else {
				playlist = false;
			}

		}

		this.JWPlaylists[ el.getAttribute( 'id' ) ] = playlist;
	}

	handleClick ( e ) {
		e.preventDefault();
		this.setVideo( this.el );
		this.el.removeEventListener( 'click', this.handleClick );
	}

	setVideo ( el ) {
		const jwPlayer = el.querySelector( '[id^="jwplayer_"][id$="_div"]' );

		if ( 'undefined' !== typeof jwPlayer && null !== jwPlayer ) {
			this.playJW( jwPlayer );
			return;
		}

		// Before resetting player clone necessary element.
		const iframe = el.querySelector( 'iframe[data-src*="youtu"]' );

		// Remove JWPlayer if current player is not JWPlayer.
		this.resetPlayers();

		if ( 'undefined' !== typeof iframe && null !== iframe ) {
			this.playYoutube( iframe );
		}
	}

	playYoutube ( iframe ) {
		this.getSrc( iframe, src => {
			const newEl = this.setPlayerEl( iframe );
			newEl.setAttribute( 'src', src );
		} );
	}

	playJW ( el ) {

		// We need to do this first, if not, this function will cause setPlayerEl on cloned node to get revert.
		var newplayer = '';

		const id = el.getAttribute('id'),
			play_button = this.el.querySelector('.c-card__badge--play, .c-picture__badge'),
			cover_image = this.el.querySelector('.c-crop__img');
		let _self = this;

		// Only for single article page.
		if ( jQuery('body').hasClass('single-post') ) {

			// Hide button.
			if ( 'undefined' !== typeof play_button && null !== play_button ) {
				play_button.hidden = true;
			}
			if ( 'undefined' !== typeof cover_image && null !== cover_image ) {
				cover_image.hidden = true;
			}

			// Show video player.
			el.parentNode.removeAttribute('hidden');

			if ( 'undefined' !== typeof id &&
				'function' === typeof jwplayer( id ).getState &&
				'playing' !== jwplayer( id ).getState() &&
				'buffering' !== jwplayer( id ).getState() ) {

				// Play video.
				jwplayer( id ).play();
			}

			return;
		}

		let playlist = this.JWPlaylists[ id ];

		if ( false === playlist ) {
			playlist = el.getAttribute( 'data-jsonfeed' );
		}

		if ( 'undefined' === typeof playlist || '' === playlist ) {
			return;
		}

		/**
		 * If it's gallery then use instance that already created.
		 */
		if ( 'undefined' !== typeof( this.isGallery ) && true === this.isGallery ) {
			const playerElement = document.getElementById( this.jwplayerElementDOMID );

			if ( null === playerElement ) {
				return;
			}

			this.resetPlayers();

			this.el.setAttribute('hidden', '');
			this.jwplayerParentElement.removeAttribute('hidden');

			this.jwplayerInstance.setup( {
				playlist: playlist,
				ph: 2
			} );

			// Bind all custom event.
			if ('object' === typeof pmc_video_ads && 'function' === typeof pmc_video_ads.setup_jwplayer) {
				pmc_video_ads.setup_jwplayer(this.jwplayerElementDOMID);
			}

			// setup event tracking
			if ( 'undefined' !== typeof( window.pmc_ga_jwplayer ) && 'function' === typeof( window.pmc_ga_jwplayer.setup_tracking_by_object ) ) {
				window.pmc_ga_jwplayer.setup_tracking_by_object( playerElement );
			}

			this.jwplayerInstance.play();
			this.isJWPlayerActive = true;

			return;
		}

		jwplayer().remove();

		const newEl = this.setPlayerEl( el );
		const newId = id + ( new Date() ).getTime();
		newEl.setAttribute( 'id', newId );
		const playerElement = document.getElementById(newId);

		newplayer = jwplayer( newId ).setup( {
			playlist: playlist,
			ph: 2
		} );

		// Bind all custom event.
		if ('object' === typeof pmc_video_ads && 'function' === typeof pmc_video_ads.setup_jwplayer) {
			pmc_video_ads.setup_jwplayer(newId);
		}


		if ( 'undefined' !== typeof( window.pmc_ga_jwplayer ) && 'function' === typeof( window.pmc_ga_jwplayer.setup_tracking_by_object ) ) {
			window.pmc_ga_jwplayer.setup_tracking_by_object( playerElement );
		}

		newplayer.play();

	}

	/**
	 * To reset all players.
	 */
	resetPlayers() {

		if ( true === this.isJWPlayerActive ) {
			this.jwplayerInstance.pause();
			this.jwplayerParentElement.setAttribute('hidden', '');
			this.isJWPlayerActive = false;
		}

		this.el.innerHTML = '';
		this.el.removeAttribute( 'hidden' );

	}

	setPlayerEl ( el ) {
		const clonedEl = el.cloneNode();
		this.el.innerHTML = '';
		this.el.appendChild( clonedEl );
		return clonedEl;
	}

	getSrc ( el, cb ) {
		let src = el.dataset.src || el.getAttribute( 'src' ) || '';

		// Add `autoplay=1` to the `src` so that the user doesn't have to click twice.
		if ( '' !== src ) {
			if ( -1 !== src.indexOf( 'autoplay' ) ) {
				src = src.replace( /autoplay=[01]/i, 'autoplay=1' );
			} else {
				src = `${src}&autoplay=1`;
			}

			cb( src );
		}
	}
}
