/* eslint-disable */

import { delegateEvent } from './utils/dom';

export default class VideoGallery {
	constructor ( el, video ) {
		this.el = el;
		this.video = video;
		this.heading = el.querySelector( '[data-video-gallery-card-heading]' );
		this.tag = el.querySelector( '[data-video-gallery-card-tag]' );
		this.lead = el.querySelector( '[data-video-gallery-card-lead]' );
		this.jwP = el.querySelectorAll( '[id^="jwplayer_"][id$="_div"]' );

		if ( undefined === this.video.pmcVideoCrop ) {
			new Error( 'pmcVideoGallery depends on pmcVideoCrop. Please define it first.' );
		}

		this.jwP.forEach( el => this.setJWPlaylist( el ) );
		delegateEvent( this.el, 'click', '[data-video-gallery-thumb]', this.handleThumbClick.bind( this ) );

		document.addEventListener( 'DOMContentLoaded', () => {
			this.initializeJWPlayerSetup();
		} );
	}

	/**
	 * To get new number each time it call.
	 * Used in this.initializeJWPlayerSetup() to create dynamic element's ID.
	 *
	 * @return {number}
	 */
	static getCounter() {
		if ( isNaN( VideoGallery.counter ) ) {
			VideoGallery.counter = 0;
		}

		return VideoGallery.counter++;
	}

	/**
	 * To initially setup JWPlayer.
	 */
	initializeJWPlayerSetup() {

		const firstPlayerMarkup = this.el.querySelector( '[data-jsonfeed]' );

		/**
		 * To create dynamic element for jwplayer in each gallery video player.
		 * and to give dynamic ID to jwplayer DOM.
		 */
		const jwplayerElement = document.createElement( 'div' );
		const jwplayerParentElement = document.createElement( 'div' );
		const jwplayerElementDOMID = 'jwplayer_' + VideoGallery.getCounter() + '_div';
		let jwplayerInstance = false;
		let playlist = false;

		// Set ID to player DOM
		jwplayerElement.setAttribute( 'id', jwplayerElementDOMID );

		// Set properties to player container.
		jwplayerParentElement.setAttribute( 'class', 'jwplayer_container' );
		jwplayerParentElement.setAttribute( 'hidden', '' );
		jwplayerParentElement.appendChild( jwplayerElement );

		// Append player DOM into parent element.
		this.video.parentElement.appendChild( jwplayerParentElement );

		jwplayerInstance = jwplayer( jwplayerElementDOMID );

		/**
		 * Find first JWPlayer if there is any.
		 * and initialize it with it.
		 */
		if ( null !== firstPlayerMarkup ) {
			playlist = firstPlayerMarkup.getAttribute( 'data-jsonfeed' );
			if ( 'string' === typeof playlist ) {
				jwplayerInstance.setup( {
					playlist: playlist,
					ph: 2,
					autostart: false
				} );
			}
		}

		/**
		 * Add elements to VideoCrop object.
		 * So it's can also identify that this is video gallery
		 * and it won't create multiple instance for all JWPlayer and use single instance.
		 *
		 * @see VideoCrop.playJW
		 */
		this.video.pmcVideoCrop.isGallery = true;
		this.video.pmcVideoCrop.jwplayerElementDOMID = jwplayerElementDOMID;
		this.video.pmcVideoCrop.jwplayerElement = jwplayerElement;
		this.video.pmcVideoCrop.jwplayerParentElement = jwplayerParentElement;
		this.video.pmcVideoCrop.jwplayerInstance = jwplayerInstance;

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

		this.video.pmcVideoCrop.JWPlaylists[ el.getAttribute( 'id' ) ] = playlist;
	}

	handleThumbClick ( e, el ) {
		e.preventDefault();
		this.setActiveThumb( el );
		this.setCard( el.dataset );
		this.video.pmcVideoCrop.setVideo( el );
	}

	setCard ( data ) {
		if ( null !== this.heading ) {
			this.heading.innerText = data.heading;
			if ( 'undefined' !== typeof data.permalink ) {
				this.heading.setAttribute( 'href', data.permalink );
			}
		}

		if ( null !== this.tag ) {
			this.tag.innerText = data.tag;
			if ( 'undefined' !== typeof data.tagPermalink ) {
				this.tag.setAttribute( 'href', data.tagPermalink );
			}
		}

		if ( null !== this.lead ) {
			this.lead.innerText = data.lead;
		}
	}

	setActiveThumb ( el ) {
		const activeThumb = this.el.querySelector( '.is-active' ) || this.el.querySelector( '.is-centered' );
		if ( activeThumb ) {
			activeThumb.classList.remove( 'is-active' );
			activeThumb.classList.remove( 'is-centered' );
		}
		el.classList.add( 'is-active' );
		this.activeThumb = el;
	}
}
