/* Custom Modules */
import './vendor/Array.from.js';
import './vendor/Array.find.js';
import './vendor/Element.closest.js';
import './vendor/String.padStart.js';
import Collapsible from './Collapsible';
import Dropdown from './Dropdown';
import Flyout from './Flyout';
import FontsDetection from './FontsDetection';
import Header from './Header';
import toggleSubscriptionBox from './HeaderHover';
import LazyLoad from './LazyLoad';
import LineFrame from './LineFrame';
import LineMenu from './LineMenu';
import ListPage from './ListPage';
import Mega from './Mega';
import Ripple from './Ripple';
import Section from './Section';
import Slider from './Slider';
import MobileSlider from './MobileSlider';
import VideoCrop from './VideoCrop';
import VideoGallery from './VideoGallery';
import GallerySlider from './GallerySlider';
import Ticketing from './Ticketing';

( function() {
	var playerInstance;
	var pmcScrollTrack = 1450;

	LazyLoad.init( 'img:not(.critical)' );
	FontsDetection.init( [
		[ 'Graphik', { weight: 400 } ],
		[ 'Graphik', { weight: 500 } ],
		[ 'Graphik', { weight: 600 } ],
		[ 'Graphik', { weight: 700 } ],
		[ 'Graphik Super', { weight: 800 } ]
	] );

	const dropdowns      = [ ... document.querySelectorAll( '[data-dropdown]' ) ];
	const collapsibles   = [ ... document.querySelectorAll( '[data-collapsible]' ) ];
	const flyouts        = [ ... document.querySelectorAll( '[data-flyout]' ) ];
	const headers        = [ ... document.querySelectorAll( '[data-header]' ) ];
	const lineFrames     = [ ... document.querySelectorAll( '[data-line-frame]' ) ];
	const lineMenus      = [ ... document.querySelectorAll( '[data-line-menu]' ) ];
	const listPages      = [ ... document.querySelectorAll( '[data-list-page]' ) ];
	const megaMenus      = [ ... document.querySelectorAll( '[data-mega-menu]' ) ];
	const ripples        = [ ... document.querySelectorAll( '[data-ripple]' ) ];
	const sections       = [ ... document.querySelectorAll( '[data-section]' ) ];
	const sliders        = [ ... document.querySelectorAll( '[data-slider]' ) ];
	const gallerySliders = [ ... document.querySelectorAll( '[data-gallery-slider]' ) ];
	const videoCrops     = [ ... document.querySelectorAll( '[data-video-crop]' ) ];
	const videoGalleries = [ ... document.querySelectorAll( '[data-video-gallery]' ) ];
	const ticketing      = [ ... document.querySelectorAll( '[data-ticketing]' ) ];

	if ( 900 < window.innerWidth ) {
		toggleSubscriptionBox();
	}
	new Flyout();
	collapsibles.forEach( el => el.pmcCollapsible = new Collapsible( el ) );
	listPages.forEach( el => el.pmcListPage = new ListPage( el ) );
	megaMenus.forEach( el => el.pmcMegaMenu = new Mega( el ) );
	sections.forEach( el => el.pmcSection = new Section( el ) );
	videoCrops.forEach( el => el.pmcVideoCrop = new VideoCrop( el ) );
	videoGalleries.forEach( el => el.pmcVideoGallery = new VideoGallery( el, el.querySelector( '[data-video-crop]' ) ) );
	ticketing.forEach( el => el.pmcTicketing = new Ticketing( el ) );

	const onSafeResize = function() {
		const width = window.innerWidth;

		headers.forEach( el => {
			if ( 768 <= width && undefined === el.pmcHeader ) {
				new Header( el );
			} else if ( 768 > width && undefined !== el.pmcHeader ) {
				el.pmcHeader.destroy();
			}
		} );

		lineFrames.forEach(  el => {
			if ( undefined === el.LineFrame ) {
				new LineFrame( el );
			} else {
				el.LineFrame.setLineLength();
			}
		} );

		lineMenus.forEach( el => {
			if ( 768 <= width ) {
				if ( undefined === el.pmcLineMenu ) {
					new LineMenu( el );
				} else {
					el.pmcLineMenu.refresh();
				}
			} else if ( 768 > width && undefined !== el.pmcLineMenu ) {
				el.pmcLineMenu.destroy();
			}
		} );

		dropdowns.forEach( el => {
			if ( 768 > width && undefined === el.pmcDropdown ) {
				new Dropdown( el );
			} else if ( 768 <= width && undefined !== el.pmcDropdown ) {
				el.pmcDropdown.destroy();
			}
		} );

		ripples.forEach( el => {
			if ( 768 > width && undefined === el.pmcRipple ) {
				new Ripple( el );
			} else if ( 768 <= width && undefined !== el.pmcRipple ) {
				el.pmcRipple.destroy();
			}
		} );

		sliders.forEach( el => {
			if ( 768 <= width ) {
				if ( undefined === el.pmcSlider ) {
					el.pmcSlider = new Slider( el );
					el.pmcSlider.init();
				} else {
					el.pmcSlider.setVals();
					el.pmcSlider.move();
				}

				if ( undefined !== el.pmcMobileSlider ) {
					el.pmcMobileSlider.destroy();
				}

			} else if ( 768 > width ) {
				if ( undefined !== el.pmcSlider ) {
					el.pmcSlider.destroy();
				}

				if ( undefined === el.pmcMobileSlider  ) {
					el.pmcMobileSlider = new MobileSlider( el );
					el.pmcMobileSlider.init();
				}
			}
		} );


		gallerySliders.forEach( el => {
			if ( 768 <= width ) {
				if ( undefined === el.pmcSlider ) {
					el.pmcSlider = new GallerySlider( el );
					el.pmcSlider.init();
				} else {
					el.pmcSlider.setVals();
					el.pmcSlider.move();
				}
			} else if ( 768 > width && undefined !== el.pmcSlider ) {
				el.pmcSlider.destroy();
			}
		} );
	};

	window.addEventListener( 'resize', () => {
		requestAnimationFrame( onSafeResize );
	} );

	document.addEventListener( 'DOMContentLoaded', () => {
		onSafeResize();
	} );

	const prepareSiteForSkinAd = function ( event ) {

		var parametersMessagePattern = 'pmcadm:dfp:skinad:parameters';
		var serializedParameters     = '';

		if ( 'string' === typeof event.data && 'object' === typeof pmc_dfp_skin ) {

			if ( parametersMessagePattern === event.data.substring( 0, parametersMessagePattern.length ) ) {
				serializedParameters = event.data.substring( parametersMessagePattern.length );
				if ( serializedParameters ) {
					document.documentElement.classList.add( 'has-side-skins' );
					document.getElementById( 'site_wrap' ).style.maxWidth  = '1000px';
					pmc_dfp_skin.refresh_skin_rails();

					//Fix for JWplayer preroll ad size issue when skin ads are served
					window.dispatchEvent( new Event( 'resize' ) );
				}

			}
		}
	};

	window.addEventListener( 'message', ( e ) => {
		prepareSiteForSkinAd( e );
	} );

	const triggerComscore = function () {

		var pageType = '';
		if ( 'object' === typeof pmc_meta && 'undefined' !== pmc_meta['page-type'] ) {
			pageType = pmc_meta['page-type'];
		}

		//Trigger Comscore for every 1450 pixels scrolled on article pages
		if ( pmcScrollTrack < window.scrollY &&
			'object' === typeof COMSCORE  &&
			'object' === typeof pmc_comscore &&
			'article' === pageType ) {
			pmcScrollTrack =  window.scrollY + 1450;

			// Track PV in comScore
			// For content changes where the page does not reload
			// we need to send both the beacon and the fake pageview
			try {

				// Send the comScore beacon
				setTimeout( function() {
					var url = 'http' + ( /^https:/.test( document.location.href ) ? 's' : '' ) + '://beacon.scorecardresearch.com/scripts/beacon.dll' + '?c1=2&amp;c2=6035310&amp;c3=&amp;c4=&amp;c5=&amp;c6=&amp;c7=' + escape( document.location.href ) + '&amp;c8=' + escape( document.title ) + '&amp;c9=' + escape( document.referrer ) + '&amp;c10=' + escape( screen.width + 'x' + screen.height ) + '&amp;rn=' + ( new Date() ).getTime(); var i = new Image(); i.src = url;
				}, 1 );
				COMSCORE.beacon( { c1: '2', c2: '6035310', c3: '', c4: '', c5: '', c6: '', c15: '' } );
			} catch ( err ) {}

			// Send the comScore fake pageview
			if ( 'function' === typeof pmc_comscore.pageview ) {
				pmc_comscore.pageview();
			}
		}

	};

	window.addEventListener( 'scroll', () => {
		triggerComscore();
	} );

	window.addEventListener( 'message', ( event ) => {
		var parametersMessagePattern = 'pmcadm:dfp:custom:leader:videoid1';
		var ad_str_arr = [];
		var video_id   = '';
		if ( 'string' === typeof event.data ) {
			if ( parametersMessagePattern === event.data.substring( 0, parametersMessagePattern.length ) ) {
				ad_str_arr     = event.data.split( '-' );
				video_id       = ad_str_arr[1];
				if ( 'function' === typeof jwplayer ) {
					playerInstance = jwplayer( video_id );
					setTimeout(
						function () {
							var video  = video_id.split( '_' );
							var width  = 450;
							var height = 250;
							if ( ad_str_arr[2] && 'Blockbuster' === ad_str_arr[2] ) {
								width  = 360;
								height = 220;
							}
							playerInstance.setup( {
								'playlist': 'https:\/\/content.jwplatform.com\/feeds\/' + video[1] + '.json',
								'ph': 2,
								'autostart': true,
								'mute': true,
								'width': width,
								'height': height
							} );
						},
						1000
					);
				}
			}
		}
	} );

	window.addEventListener( 'message', ( e ) => {

		var parametersMessagePattern = 'pmcadm:dfp:crownad:parameters',
			ad_str_arr  = [],
			video_id    = '',
			crownAd     = '',
			crownPlayerInstance;
		if ( 'string' === typeof e.data ) {

			if ( parametersMessagePattern === e.data.substring( 0, parametersMessagePattern.length ) ) {

				ad_str_arr = e.data.split( parametersMessagePattern );
				if ( ad_str_arr[1] ) {
					crownAd = JSON.parse( ad_str_arr[1] );

					if ( 'object' === typeof crownAd && crownAd.video_id ) {
						if ( 'function' === typeof jwplayer ) {
							crownPlayerInstance = jwplayer( crownAd.div_id );
							setTimeout(
								function () {
									crownPlayerInstance.setup( {
										'playlist': 'https://content.jwplatform.com/feeds/' + crownAd.video_id + '.json',
										'ph': 2,
										'autostart': true,
										'mute': true,
										'width': '100%',
										'aspectratio': '16:9'
									} );

									if ( crownAd.clickthrough_url ) {
										crownPlayerInstance.on( 'displayClick', function ( e ) {
											window.open( crownAd.clickthrough_url, '_blank' );
										} );
									}
								},
								1000
							);
						}
					}
				}

			}
		}
	} );

	add_filter( 'pmc-google-analytics-tracking-label', function ( label, $element ) {

		label = jQuery( $element.parent() ).data( 'label' ) || '';

		if ( pmc.is_empty( label ) ) {
			label = jQuery( $element ).data( 'label' ) || '';
		}

		return label;

	} );

}() );
