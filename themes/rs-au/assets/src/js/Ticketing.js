/* global RS_MAIN */

import { DateTime } from 'luxon';

export default class Ticketing {

	/**
	 * Class constructor
	 *
	 * @param el
	 */
	constructor ( el ) {
		this.el = el;
		this.loadLocation();
	}

	/**
	 * Method to display error on UI if events are not available
	 *
	 * @return void
	 */
	showEventsNotAvailableError() {

		const $    = jQuery;
		const self = this;

		let $items = $( self.el ).find( '.c-ticketing__list--item' );

		$items.eq( 0 )
			.removeClass( 'c-ticketing__list--item' )
			.addClass( 't-align--center' )
			.text( RS_MAIN.ticketing.service_not_available_text );

		$items.eq( 1 ).remove();
		$items.eq( 2 ).remove();

		$items.removeClass( 'c-ticketing__loader' );

	}

	/**
	 * Method to load current location of the viewer
	 *
	 * @return void
	 */
	loadLocation() {
		const $    = jQuery;
		const self = this;

		let $loader   = $( self.el ).find( '.c-ticketing__loader--loading' );
		let $location = $( self.el ).find( '.c-ticketing__location' );
		let name      = RS_MAIN.ticketing.no_location_text;
		let regionId  = null;

		$loader.each( function() {
			let $div = $( '<div />' );

			// phpcs:disable

			$( this ).append( $div.clone(), $div.clone(), $div.clone(), $div.clone() );

			// phpcs:enable
		} );

		$.get( RS_MAIN.ticketing.api_endpoint + 'regions/find', function( data ) {

			name     = data.name || RS_Main.ticketing.no_location_text;
			regionId = data.regionId;

		} ).fail( function() {

			// It failed...

		} ).always( function() {

			$location.find( 'span' ).text( name );

			$location.removeClass( 'c-ticketing__loader' );
			$location.find( 'a, span' ).css( 'display', 'inline-block' );

			self.loadPerformers( regionId );

		} );
	}

	/**
	 * Method to load top performers in the current region of the viewer
	 *
	 * @param regionId ID of the current region of the viewer
	 *
	 * @return void
	 */
	loadPerformers( regionId ) {

		const $    = jQuery;
		const self = this;

		regionId = parseInt( regionId, 10 ) || 7; // regionId default to 7, New York, NY if one does not exist.

		$.get( RS_MAIN.ticketing.api_endpoint + 'performers/top?limit=3&regionId=' + regionId, function( data ) {

			if ( 1 > data.length ) {
				self.showEventsNotAvailableError();
				return;
			}

			let performers = data.map( a => a.id );

			if ( 3 < performers.length ) {
				performers = performers.slice( 0, 3 );
			}

			self.loadList( performers, regionId );

		} ).fail( function() {
			self.showEventsNotAvailableError();
		} );
	}

	/**
	 * Method to load events of the performers passed as parameter
	 *
	 * @param performers An array of performer IDs
	 * @param regionId   ID of the current region of the viewer
	 *
	 * @return void
	 */
	loadList( performers, regionId ) {

		const $    = jQuery;
		const self = this;

		if ( 'undefined' === typeof performers || 1 > performers.length ) {
			self.showEventsNotAvailableError();
			return;
		}

		regionId = ( 'undefined' === typeof regionId ) ? 7 : regionId;    // regionId default to 7, New York, NY if one does not exist

		for ( let i = 0; 3 > i; i++ ) {

			let performerId  = parseInt( performers[ i ], 10 );
			let $event       = null;
			let $eventApiUrl = RS_MAIN.ticketing.api_endpoint + 'events?limit=1&performerId=' + performerId + '&regionId=' + regionId;

			$.get( $eventApiUrl, function( data ) {

				if ( 0 < data.count && true === Array.isArray( data.events ) ) {
					$event = data.events.shift();
				}

			} ).fail( function() {

				// API call failed

			} ).always( function() {

				self.displayList( $event, i );

			} );

		}

	}

	/**
	 * Method to display event details in UI
	 *
	 * @param event Object containing event details
	 * @param pos   Index of the items array in which event details are to be added
	 *
	 * @return void
	 */
	displayList( event, pos ) {

		const $    = jQuery;
		const self = this;

		pos = ( 'undefined' === typeof pos ) ? 0 : parseInt( pos );

		let $items = $( self.el ).find( '.c-ticketing__list--item' );
		let $item  = $items.eq( pos );

		if ( 'object' !== typeof event || $.isEmptyObject( event ) ) {
			$item.remove();    // Remove elements if we have less than 3 events.
			return;
		}

		let dt = DateTime.fromISO( event.localDate );

		$item.find( '.c-ticketing__date > a' ).attr( 'href', encodeURI( event.url ) );
		$item.find( '.c-ticketing__date--day' ).text( dt.toLocaleString( { weekday: 'long' } ) );
		$item.find( '.c-ticketing__date--date' ).text( dt.toLocaleString( { month: 'short', day: 'numeric' } ) );
		$item.find( '.c-ticketing__date--time' ).text( dt.toLocaleString( DateTime.TIME_SIMPLE ) );
		$item.find( '.c-ticketing__details--event > a' ).attr( 'href', encodeURI( event.url ) ).text( event.name );
		$item.find( '.c-ticketing__details--location' ).text( event.venue.name + ' - ' + event.venue.city + ', ' + event.venue.state );
		$item.find( '.c-ticketing__details--purchase > a' ).attr( 'href', encodeURI( event.url ) );
		$item.find( '.c-ticketing__details--purchase, .c-ticketing__date' ).css( 'visibility', 'visible' );

		$item.removeClass( 'c-ticketing__loader' );

	}

}

// EOF
