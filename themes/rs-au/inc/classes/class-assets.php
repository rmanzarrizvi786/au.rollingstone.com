<?php
/**
 * Assets
 *
 * The enqueueing of child theme assets.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

namespace Rolling_Stone\Inc;

use PMC;
use PMC\Global_Functions\Traits\Singleton;

/**
 * Class Assets
 *
 * @since 2018.1.0
 * @see \PMC\Global_Functions\Traits\Singleton
 */
class Assets {

	use Singleton;

	/**
	 * Class constructor.
	 *
	 * Initializes the theme assets.
	 */
	protected function __construct() {

		// Assets handling.
		add_action( 'wp_head', [ $this, 'add_preload_polyfill' ], 10 );
		add_action( 'wp_enqueue_scripts', [ $this, 'site_css' ] );
		add_action( 'wp_head', [ $this, 'hint_web_fonts' ], 1 );
		add_action( 'wp_head', [ $this, 'inline_web_fonts' ], 2 );
		add_action( 'wp_enqueue_scripts', [ $this, 'dequeue_assets' ], 11 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 11 );

		// Disable JS & CSS concatenation as we're on HTTPS and want defers to work.
		add_filter( 'css_do_concat', '__return_false', 10, 2 );
		add_filter( 'js_do_concat', '__return_false', 10, 2 );

		// Lazyload and defer scripts and styles.
		add_filter( 'script_loader_tag', array( $this, 'defer_scripts' ), 999, 2 );
		add_filter( 'script_loader_tag', array( $this, 'async_scripts' ), 999, 2 );

		// Disable all actions related to emojis
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		add_filter( 'emoji_svg_url', '__return_false' );

		// Misc footer items.
		add_action( 'wp_footer', array( $this, 'inline_icons' ), 10 );

	}

	/**
	 * Dequeue Parent Theme Assets
	 *
	 * @since 2018.1.0
	 */
	public function dequeue_assets() {

		if ( ! is_single() ) {
			// MediaElement Player is not needed outside of the single article.
			wp_deregister_style( 'mediaelement' );
			wp_deregister_style( 'wp-mediaelement' );
			wp_deregister_script( 'wp-mediaelement' );

			// Social share bar is not present outside of the single article.
			wp_dequeue_style( 'pmc-social-share-bar-common-css' );
		}

		//The JWPlayer plugins JS is conflicting with pmc-floating-player.
		if ( ! PMC::is_mobile() && is_single() ) {
			wp_dequeue_script( 'pmc-video-player-library' );
			wp_dequeue_script( 'pmc-video-player-script' );
			wp_dequeue_style( 'jetpack-css' );
		}

		foreach ( array( 'homepage', 'archive', 'site' ) as $base ) {
			wp_dequeue_style( 'pmc-core-' . $base . '-css' );
		}

		wp_dequeue_style( 'pmc-core-woff-webfonts-css' );
		wp_dequeue_style( 'pmc-core-ttf-webfonts-css' );
		wp_dequeue_style( 'pmc-global-css-overrides' );
		wp_dequeue_style( 'pmcfooter' );

		if (
			is_page()
			&& class_exists( '\PMC\Top_Videos_V2\Landing_Pages\Branded_Page', false )
			&& \PMC\Top_Videos_V2\Landing_Pages\Branded_Page::get_instance()->is_enabled_for_post( get_the_ID() )
		) {
			// Dequeue these scripts to prevent pre-roll ads on JW Player on branded landing page
			wp_dequeue_script( 'pmc-video-player-library' );
			wp_dequeue_script( 'pmc-video-player-script' );
		}

		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			wp_deregister_style( 'dashicons' );
			wp_deregister_style( 'admin-bar' );
			wp_deregister_style( 'pmc-helpdesk' );
			wp_deregister_style( 'pmc-global-css-overrides' );
			wp_deregister_style( 'pmcfooter' );
			wp_deregister_style( 'pmc-social-share-bar-common-css' );
			wp_deregister_style( 'pmc-swiftype-style' );
			wp_deregister_style( 'pmc-adm-style' );
		}

		wp_dequeue_script( 'wp-embed' );
		wp_dequeue_script( 'pmc-core-site-js' );
	}

	/**
	 * Enqueue Child Theme Assets
	 *
	 * @since 2018.1.0
	 */
	public function enqueue_scripts() {

		// Main script.
		wp_register_script( 'rollingstone-main', TBM_CDN . '/assets/js/main.js', array( 'jquery', 'iolazy-js', 'polyfill-io-js' ), RS_THEME_VERSION, false );

		// Register IO Lazy used in images lazy loading.
		wp_register_script( 'polyfill-io-js', 'https://cdn.polyfill.io/v2/polyfill.min.js?features=IntersectionObserver,Promise,Fetch,Array.from', array(), null, true );
		wp_register_script( 'iolazy-js', TBM_CDN . '/assets/js/vendor/iolazy.js', array( 'polyfill-io-js' ), null, true );

		// Gallery bundle.
		wp_register_script( 'pmc-core-images-loaded', TBM_CDN . '/assets/js/vendor/imagesloaded.pkgd.js', array(), RS_THEME_VERSION, false );

		wp_deregister_script( 'pmc-core-gallery-bundle' );

		wp_register_style( 'gallery-styles', TBM_CDN . '/assets/css/gallery.css', [], RS_THEME_VERSION, 'all' );

		// Enqueue scripts and stylesheets.
		wp_enqueue_script( 'jquery' );

		if ( Featured_Article::get_instance()->is_featured_article() ) {
			wp_enqueue_script( 'featured-article' );
		}

		wp_enqueue_script( 'rollingstone-main' );

		// Ranker ROIQ Test Script.
		wp_enqueue_script(
			'rollingstone-ranker-roiq',
			'https://cdn.roiq.ranker.com/client/assets/minified/roiq_dfp_targeting.js',
			array(),
			false,
			true
		);

		if ( is_singular( 'pmc-gallery' ) ) {
			wp_enqueue_style( 'gallery-styles' );
			wp_enqueue_script( 'waypoints' );
		}

		wp_enqueue_script( 'rollingstone-newsletter-acquisition', 'https://www.lightboxcdn.com/vendor/f611bb46-e8d6-4b65-af04-d7801b2011c2/lightbox_inline.js', [], RS_THEME_VERSION, false );

		$data = [
			'ticketing' => [
				'api_endpoint'               => ( \PMC::is_production() ) ? home_url( '/api/seats/' ) : 'https://rollingstone.pmcqa.com/api/seats/',
				'no_location_text'           => esc_html__( 'Choose a Location', 'pmc-rollingstone' ),
				'service_not_available_text' => esc_html__( 'Sorry, Service Not Available.', 'pmc-rollingstone' ),
			],
		];

		wp_localize_script( 'rollingstone-main', 'RS_MAIN', $data );
	}

	/**
	 * Inline critical CSS in the page head.
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function site_css() {

		if ( is_home() ) {
			$css_slug = 'home';
		} elseif ( is_page( 'video' ) ) {
			$css_slug = 'video-landing';
		} elseif (
			is_page()
			&& class_exists( '\PMC\Top_Videos_V2\Landing_Pages\Branded_Page', false )
			&& \PMC\Top_Videos_V2\Landing_Pages\Branded_Page::get_instance()->is_enabled_for_post( get_the_ID() )
		) {
			$css_slug = 'video-landing';
		} elseif ( Featured_Article::get_instance()->is_featured_article() ) {
			$css_slug = 'featured-article';
		} elseif ( is_page( 'results' ) ) {
			$css_slug = 'results';
		} elseif ( is_singular( 'pmc_top_video' ) ) {
			$css_slug = 'video-article';
		} elseif ( is_singular( [ 'pmc_list', 'pmc_list_item' ] ) ) {
			$css_slug = 'list';
		} elseif ( rollingstone_is_country() ) {
			$css_slug = 'country';
		} elseif ( is_singular() ) {
			$css_slug = 'single';
		} elseif ( is_tax( 'vcategory' ) ) {
			$css_slug = 'video-tag';
		} elseif ( is_category() ) {
			$category = get_query_var( 'cat' );
			$category = get_category( $category );
			if ( ! empty( $category->parent ) ) {
				$css_slug = 'archive';
			} else {
				$css_slug = 'section-front';
			}
		} elseif ( is_archive() ) {
			$css_slug = 'archive';
		} else {
			$css_slug = 'main';
		}

		wp_enqueue_style( $css_slug , TBM_CDN . '/assets/css/' . $css_slug . '.css', [], RS_THEME_VERSION, 'all' );
	}

	/**
	 * Add `rel="preload"` polyfill.
	 *
	 * This is part of Google's recommendation for lazy loading of style sheets.
	 *
	 * @since 2018.1.0
	 *
	 * @see https://github.com/filamentgroup/loadCSS/blob/master/src/cssrelpreload.js
	 */
	public function add_preload_polyfill() {
		?>
		<script>
			<?php
			// Note that the non-minified resource is available for code review in assets/src/js/vendor/cssrelpreload.js
			\PMC::render_template( TBM_CDN . '/assets/js/vendor/cssrelpreload.js', [], true );
			?>
		</script>
		<?php
	}

	/**
	 * Defer scripts loading.
	 *
	 * Adds defer='defer' attribute to non-whitelisted script tags.
	 *
	 * @param string $tag The tag.
	 * @param string $handle The handle.
	 * @since 2018.1.0
	 * @return string
	 */
	public function defer_scripts( $tag, $handle ) {

		if ( ! is_admin() && ! ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) ) {

			// Whitelist scripts that should not be deferred.
			$render_blocking_scripts = array(
				'jquery',
				'jquery-core',
				'jquery-migrate',
				'pmc-adm-loader',
				'pmc-adm-dfp-events',
				'pmc-adm-sourcebuster',
				'pmc-hooks',
				'pmc-intersection-observer-polyfill',
				'pmc-video-player-ads-js',
				'waypoints',
				'pmc-async-adm-gpt',
			);

			// The admin-bar has inline scripts depending on jQuery.
			if ( is_user_logged_in() ) {
				array_push(
					$render_blocking_scripts,
					'jquery',
					'jquery-core',
					'jquery-migrate'
				);
			}

			if ( ! in_array( $handle, (array) $render_blocking_scripts, true ) ) {
				return str_replace( array( ' src', ' async' ), array( " defer='defer' src", '' ), $tag );
			}
		}

		return $tag;
	}

	/**
	 * Adds async attribute to certain script tags.
	 *
	 * @param string $tag The tag.
	 * @param string $handle The handle.
	 * @since 2018.1.0
	 * @return string
	 */
	public function async_scripts( $tag, $handle ) {

		if ( ! is_admin() && ! ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) ) {
			$scripts_to_async = array(
				'mobile-useragent-info',
			);

			if ( in_array( $handle, (array) $scripts_to_async, true ) ) {
				return str_replace( ' src', ' async src', $tag );
			}
		}

		return $tag;

	}

	/**
	 * Inline social icons.
	 *
	 * @since 2018.1.0
	 */
	public function inline_icons() {
		/*
		 * We're loading SVG assets from `src` here because webpack is stripping data from
		 * the SVG files, which in turn is causing them not to load here and the images to
		 * break on the frontend.
		 *
		 * @todo: Investigate why and change to the `build` directory when working.
		 */
		?>
		<div hidden>
			<?php
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-arrow-down.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-camera.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-chevron.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-comments.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-email.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-external-link.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-facebook.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-hamburger.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-linkedin.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-key.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-list.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-more.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-pinterest.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-play.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-plus.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-print.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-reddit.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-share.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-star.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-star--half.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-thumbnails.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-trending.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-tumblr.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-twitter.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-whatsapp.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-youtube.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/pmc-logo-black.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/pmc-logo-white.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/rs-badge.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/rs-logo.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/logo-rspro.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/logo-rs-live-media.svg', [], true );
			\PMC::render_template( CHILD_THEME_PATH . '/assets/src/images/_dev/icon-instagram.svg', [], true );
			?>
		</div>
		<?php
	}

	/**
	 * Inline custom web fonts.
	 *
	 * @since 2018.1.0
	 */
	public function inline_web_fonts() {
		$fonts_url = TBM_CDN . '/assets/fonts';
		?>
		<style type="text/css" id="web-fonts-css">
				@font-face {
					font-family: 'Graphik Bold Subset';
					src: url( '<?php echo esc_url( $fonts_url ); ?>/Graphik/Graphik-Bold-Subset.woff2' ) format( 'woff2' ),
					url( '<?php echo esc_url( $fonts_url ); ?>/Graphik/Graphik-Bold-Subset.woff' ) format( 'woff' );
					font-weight: 700;
					font-style: normal;
				}

				@font-face {
					font-family: 'Graphik';
					src: url( '<?php echo esc_url( $fonts_url ); ?>/Graphik/Graphik-Regular.woff2' ) format( 'woff2' ),
					url( '<?php echo esc_url( $fonts_url ); ?>/Graphik/Graphik-Regular.woff' ) format( 'woff' );
					font-weight: 300;
					font-style: normal;
					font-display: swap;
				}

				@font-face {
					font-family: 'Graphik';
					src: url( '<?php echo esc_url( $fonts_url ); ?>/Graphik/Graphik-Medium.woff2' ) format( 'woff2' ),
					url( '<?php echo esc_url( $fonts_url ); ?>/Graphik/Graphik-Medium.woff' ) format( 'woff' );
					font-weight: 500;
					font-style: normal;
					font-display: swap;
				}

				@font-face {
					font-family: 'Graphik';
					src: url( '<?php echo esc_url( $fonts_url ); ?>/Graphik/Graphik-Semibold.woff2' ) format( 'woff2' ),
					url( '<?php echo esc_url( $fonts_url ); ?>/Graphik/Graphik-Semibold.woff' ) format( 'woff' );
					font-weight: 600;
					font-style: normal;
					font-display: swap;
				}

				@font-face {
					font-family: 'Graphik';
					src: url( '<?php echo esc_url( $fonts_url ); ?>/Graphik/Graphik-Bold.woff2' ) format( 'woff2' ),
					url( '<?php echo esc_url( $fonts_url ); ?>/Graphik/Graphik-Bold.woff' ) format( 'woff' );
					font-weight: 700;
					font-style: normal;
					font-display: swap;
				}

				@font-face {
					font-family: 'Graphik Super';
					src: url( '<?php echo esc_url( $fonts_url ); ?>/Graphik/Graphik-Super.woff2' ) format( 'woff2' ),
					url( '<?php echo esc_url( $fonts_url ); ?>/Graphik/Graphik-Super.woff' ) format( 'woff' );
					font-weight: 800;
					font-style: normal;
					font-display: swap;
				}

				@font-face {
					font-family: 'Publico Bold Subset';
					src: url( '<?php echo esc_url( $fonts_url ); ?>/Publico/Publico-Bold-Subset.woff2' ) format( 'woff2' ),
					url( '<?php echo esc_url( $fonts_url ); ?>/Publico/Publico-Bold-Subset.woff' ) format( 'woff' );
					font-weight: 700;
					font-style: normal;
				}

				@font-face {
					font-family: 'Publico';
					src: url( '<?php echo esc_url( $fonts_url ); ?>/Publico/Publico-Bold.woff2' ) format( 'woff2' ),
					url( '<?php echo esc_url( $fonts_url ); ?>/Publico/Publico-Bold.woff' ) format( 'woff' );
					font-weight: 700;
					font-style: normal;
					font-display: swap;
				}

		</style>
		<?php
	}

	/**
	 * Hints for custom web fonts.
	 *
	 * @since 2018.1.0
	 */
	public function hint_web_fonts() {
		$fonts_url = TBM_CDN . '/assets/fonts';

		$fonts = [
			'/Graphik/Graphik-Regular',
			'/Graphik/Graphik-Medium',
			'/Graphik/Graphik-Semibold',
			'/Graphik/Graphik-Bold',
			'/Graphik/Graphik-Bold-Subset',
			'/Graphik/Graphik-Super',
			'/Publico/Publico-Bold',
			'/Publico/Publico-Bold-Subset',
		];

		foreach ( $fonts as $font ) {
			?>
			<link rel="preload" href="<?php echo esc_url( $fonts_url ); ?><?php echo esc_attr( $font ); ?>.woff2" as="font" type="font/woff2" crossorigin="anonymous">
			<link rel="preload" href="<?php echo esc_url( $fonts_url ); ?><?php echo esc_attr( $font ); ?>.woff" as="font" type="font/woff2" crossorigin="anonymous">
			<?php
		}
	}
}
