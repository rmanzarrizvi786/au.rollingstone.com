<?php

class PMC_Feature {

	// we need to track if scripts has been render do we don't output duplicate code
	private static $_scripts_rendered = false;

	private function __construct() {}

	/**
	 * @codeCoverageIgnore
	 */
	public static function load() {
		//Calling pii_redirect() here instead of constructor because this class 'PMC_Feature' never initialised,
		//therefore constructor is not called.
		self::pii_redirect();
		//add_action( 'wp_print_footer_scripts', array( 'PMC_Feature', 'js_copy_text' ) );

		//load up our utility JS lib in admin and on frontend
		add_action( 'admin_print_scripts', array( 'PMC_Feature', 'top_js_loader' ), 1 );
		// use action wp_print_scripts instead of wp_head to make scripts rendered after stylesheets
		add_action( 'wp_print_scripts', array( 'PMC_Feature', 'top_js_loader' ), 1 );

		add_action( 'wp_enqueue_scripts', array( 'PMC_Feature', 'enqueue_stuff' ) );

		add_action( 'init', array( 'PMC_Feature', 'init' ) );

		// register scripts need to run at a later priority to allow custom cdn override
		// @see class PMC_CDN
		add_action( 'init', array( 'PMC_Feature', 'register_scripts' ), 99 );

		add_action( 'jetpack_open_graph_tags', array( 'PMC_Feature', 'open_graph_tags' ), 15 );

		add_action( 'transition_post_status', array( 'PMC_Feature', 'pmc_selective_pushpress_ping' ), 9, 3 );

		//Disable this for now till we get optin-optout functionality
		//add_filter( 'request', array( get_called_class(), 'feed_add_post_type' ) );

		//Make sure that Link To on Media Manager settings, when images are uploaded/inserted on post always defaults to none.
		add_filter( 'pre_option_image_default_link_type', array( get_called_class(), 'image_default_link_type' ) );

		add_action( 'pmc_tags_head', array( get_called_class(), 'pmc_amazon_script_head' ) );

		add_action( 'pmc_tags_head', array( get_called_class(), 'pmc_hotjar_script_head' ) );

		add_action( 'pmc-tags-footer' , array( get_called_class(), "pmc_tags_footer" ) );

		add_action( 'pmc_cheezcap_groups', array( get_called_class(), "cheezcap_groups" ) );

		add_action( 'custom_metadata_manager_init_metadata', array( get_called_class(), 'init_custom_fields' ) );

		add_action( 'wp_head', array( 'PMC_Feature', 'disable_widont' ), 0 );

		add_action( 'wp_head', array( 'PMC_Feature', 're_enable_widont' ), 9999 );

		add_filter( 'jetpack_photon_pre_args', array( get_called_class(), 'photon_pre_args_for_mobile' ), 10, 3 );

		add_action( 'wp_head', array( __CLASS__, 'add_meta_tags' ) );

		add_action( 'template_redirect', array( __CLASS__, 'redirect_to_lowercase' ) );

		// hook that is called by the wp_handle_upload. We can examine and alter a filename before it's moved to it's final location
		// PMCEED-1610
		add_action( 'wp_handle_upload_prefilter', array( get_called_class(), 'pmc_sanitize_upload_filename' ) );

		/**
		 * remove expensive JOINS in co-authors plus
		 *
		 * @since 2015-08-10 Amit Sannad ref:https://wordpressvip.zendesk.com/requests/43624
		 */
		add_filter( 'coauthors_plus_should_query_post_author', '__return_false' );

		add_filter( 'pre_option_jwplayer_content_mask', array( __CLASS__, 'maybe_override_jwplayer_content_mask' ) );

		add_filter( 'rest_api_allowed_post_types', array( get_called_class(), 'filter_allow_custom_post_types_in_rest_api' ) );

		add_filter( 'pmc_rest_api_post_types', array( get_called_class(), 'filter_pmc_rest_api_post_types' ) );

		add_filter( 'xmlrpc_methods', array( get_called_class(), 'remove_xmlrpc_methods' ) );

		/**
		 * Replace control characters
		 * @since 2015-10-27 - Javier Martinez - PMCVIP-359 - Remove control characters
		 */
		add_filter( 'the_content', array( get_called_class(), 'filter_replace_control_characters' ) );
		add_filter( 'the_content_feed', array( get_called_class(), 'filter_replace_control_characters' ) );
		add_filter( 'the_excerpt_rss', array( get_called_class(), 'filter_replace_control_characters' ) );
		add_filter( 'the_excerpt', array( get_called_class(), 'filter_replace_control_characters' ) );

		// Delay the update term counts operation typically performed while updating a post.
		// Core's implementation for term count updates runs for each taxonomy
		// when a post is published/updated. This operation takes ~8s on average on sites
		// with massive post and term counts. By delaying the process we can speed up
		// post saving, and run the term count update in the background afterwards.
		//
		// This implementation stores changed post's IDs in an option
		// and every 10 minutes we loop through those and do the term counts.
		//
		// See PMCVIP-364, PMCVIP-684, PMCVIP-634
		if ( apply_filters( 'pmc_delay_post_term_count_update', true ) ) {

			// Remove the default term count update which occurs while publishing/editing a post
			remove_action( 'transition_post_status', '_update_term_count_on_transition_post_status', 10 );

			// Add our delayed term counts callback to
			// the list of delayed tasks which run every 10 minutes
			// this way we don't slow down the actual save_post
			// We're transitioning to this method. Once fully transitioned
			// we'll delete the other delayed term counting cron stuff in this class
			add_filter( 'pmc_delayed_save_post_tasks', function( $tasks ) {

				$tasks['PMC_Feature::_update_term_count_on_transition_post_status'] = array(
					'callback' => array(
						get_called_class(),
						'_update_term_count_on_transition_post_status',
					),
				);

				return $tasks;

			}, 10, 1 );
		}

		// Force 2FA and WPCOM SSO for VIP Go sites
		// @see https://jetpack.com/support/sso/
		if ( defined( 'PMC_IS_VIP_GO_SITE' ) && true === PMC_IS_VIP_GO_SITE ) {

			// Automatically link local accounts to WPCOM accounts by email
			add_filter( 'jetpack_sso_match_by_email', '__return_true', 99999 );

			// Bypass the local login screen and send users to WPCOM SSO
			add_filter( 'jetpack_sso_bypass_login_forward_wpcom', '__return_true', 99999 );

			// Disable and hide the default login form
			add_filter( 'jetpack_remove_login_form', '__return_true', 99999 );

			// Require WPCOM accounts to have 2FA enabled
			add_filter( 'jetpack_sso_require_two_step', '__return_true', 99999 );

		}
	}

	/**
	 * Make one function to call all init action.
	 * @since 2015-01-22 Amit Sannad
	 * @version 2016-07-06 Archana Mandhare PMCVIP-1914 Added function block spam referers
	 */
	public static function init() {

		self::enable_srcset_testing();
		self::block_spam_referers();
		self::handle_partner_file_404();
		self::add_body_classes();
		self::pmc_set_image_quality_for_mobile();

	}

	/**
	 * Enable responsive images for users in a specific cache group.
	 * Used for testing responsive images from WP 4.4 being rolled out on WPCOM
	 * Set the "enable_srcset" cookie
	 * To test for one hour, run this in JS console:
	 *  pmc.cookie.set('enable_srcset', 'yes', 3600 );
	 *
	 * Remove this after the responsive image code goes live on WPCOM
	 *
	 * @since 2016-08-23
	 * @version 2016-08-23 Corey Gilmore PPT-6976
	 *
	 * @see http://wordpressvip.zendesk.com/hc/requests/48288
	 * @see https://make.wordpress.org/core/2015/11/10/responsive-images-in-wordpress-4-4/
	 *
	 */
	public static function enable_srcset_testing() {

		//2016-10-07 Amit Sannad
		//Disable Native Responsive Images on WordPress.com
		//https://lobby.vip.wordpress.com/2016/10/07/responsive-images-srcset-and-sizes-ready-for-testing/
		add_filter( 'wpcom_enable_srcset_and_sizes', '__return_false' );

		$func_body = 'return isset( $_COOKIE["enable_srcset"] ) && in_array( $_COOKIE["enable_srcset"], array( true, 1, "1", "yes" ), true );';
		if ( function_exists( 'vary_cache_on_function' ) ) {
			vary_cache_on_function( $func_body );
		}
		$func = @create_function( '', $func_body ); // @codingStandardsIgnoreLine: Ignored as part of WI-544 VIP approved in ticket https://wordpressvip.zendesk.com/hc/en-us/requests/78347
		if ( true === call_user_func( $func ) ) {
			add_filter( 'wpcom_enable_srcset_and_sizes', '__return_true', 11 );
			pmc_add_body_class( 'srcset-enabled' );
		}
	}

	/**
	 * Function string to allow identifying referers that are spamming out sites by vary_cache_on_function
	 * This creates a cache group
	 *
	 * Note:: This block of code will be pass to vary_cache_on_function() In vary cache can only include native PHP functions.
	 *  - Functions defined in WordPress, plugins, and themes are not available and MUST NOT be used.
	 * Ref: https://github.com/Automattic/batcache/blob/6c222ff6ce0fc4eef495fd16f6b764e72878e5d6/advanced-cache.php#L19
	 *
	 * @param bool $function_return Instead of exit use return statement inside function body.
	 *
	 * @since 2016-07-06
	 * @version 2016-07-06 Archana Mandhare PMCVIP-1914
	 * @version 2019-03-07 Sagar Bhatt      SADE-120
	 */
	public static function block_spam_referers( $function_return = false ) {

		$block_spam_referers_func_body = <<<'EOD'
		$blacklist_referers = [
			'steep.tv',
			'oldhomedays.xyz',
			'rhymmegkickof.org',
			'ratcattrivobeat.us',
			'nunavriffli.net',
			'isincaginoer.info',
			'myvupelkrli.net',
			'fishnpcwortiz.net',
			'adrfyacerbyt.info',
			'begtnfhitcbeat.shop',
			'loryphiblogcz.xyz',
			'yakivorrpixz.space',
			'siamdemilly.one',
			'zhoucurbthouly.us',
			'gabstimorbof.space',
			'sianagedpodtgual.shop',
			'camluiktagsli.us',
			'thigekwindty.one',
			'vegpdakayyobyt.pro',
			'alafoalzoomfti.com',
			'hanwekabrowser.blue',
			'aixohhsnuteed.us',
			'mldozooxole.blue',
			'twipsbwkair.info',
			'jetspmeadfiscer.xyz',
			'fccnocjetvibyt.blue',
			'ansevpodmale.shop',
			'dasmethzooliof.shop',
			'eiliseemconnbit.shop',
			'snapladamotttube.space',
			'topbwvukgual.xyz',
			'skynyoiloviseed.us',
			'pixofonofrz.net',
			'nsawaxjetbeiz.net',
			'hitcaotcbotly.com',
			'hitbpaxaabyt.info',
			'plavudoisnobead.com',
			'eqpijgembely.info',
			'trivomphjugti.net',
		];
		if ( ! empty( $_SERVER["HTTP_REFERER"] ) ) {
			$host = parse_url( $_SERVER["HTTP_REFERER"] );
			$host = isset( $host["host"] ) ? $host["host"] : false;

			if ( ! empty( $host ) && in_array( strtolower( $host ), $blacklist_referers ) ) {
				header( 'HTTP/1.1 403 Forbidden' );
				?>
				<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
				<html>
					<head>
						<title>403 Forbidden</title>
					</head>
					<body>
						<h1>Access Forbidden</h1>
						<p>Access forbidden. If you believe you have reached this page in error please refresh the page and try again.</p>
					</body>
				</html>
				<?php
				exit();
			} else {
				return false;
			}
		}

EOD;
// DO NOT INDENT THE EOD; LINE

		if ( function_exists( 'vary_cache_on_function' ) ) {
			vary_cache_on_function( $block_spam_referers_func_body );
		}

		if ( $function_return ) {
			$block_spam_referers_func_body = str_replace( 'exit();', 'return true;', $block_spam_referers_func_body );
		}

		$block_spam_referers_func = @create_function( '', $block_spam_referers_func_body ); // @codingStandardsIgnoreLine: Ignored as part of WI-544 VIP approved in ticket https://wordpressvip.zendesk.com/hc/en-us/requests/78347
		return call_user_func( $block_spam_referers_func );

	}

	/**
	 * Combine same filters to one funciton.
	 * @since 2015-05-06 Amit Sannad
	 */
	public static function pmc_tags_footer() {
		self::pmc_amazon_script_footer();
		self::add_polar_tags();
	}

	/*
	 * @ticket PPT-3627
	 * @since 2014-11-5 Archana Mandhare
	 * Remove the widont filter during wp_head
	 */
	public static function disable_widont() {
		// Remove if the filter is added
		$GLOBALS['pmc_has_widont'] = has_filter( 'the_title', 'widont' );
		if ( $GLOBALS['pmc_has_widont'] !== false ) {
			remove_filter( 'the_title', 'widont', $GLOBALS['pmc_has_widont'] );
		}
	}

	/*
	 * @ticket PPT-3627
	 * @since 2014-11-5 Archana Mandhare
	 * Re-enable the widont filter after wp_head finishes
	 */
	public static function re_enable_widont() {
		// Re-enable the widont filter only if it was already added
		if ( $GLOBALS['pmc_has_widont'] !== false ) {
			add_filter( 'the_title', 'widont', $GLOBALS['pmc_has_widont'] );
		}
	}

	public static function js_copy_text() {
		if( ! is_singular() ) {
			return;
		}

		$post_class = '.single .post';

		if( defined( 'PMC_SITE_NAME' ) ) {

			switch( PMC_SITE_NAME ) {
				case 'hollywoodlife':
					$post_class = '.main-post';
					break;
				case 'movieline':
					$post_class = '.postContentWrapper';
					break;
				case 'tvline':
					$post_class = '.single-article-content';
					break;

			}
		}

		?>
		<div id="pmc-js-copy-text" style="display: none"></div>
		<script type="text/javascript">

		jQuery(document).ready( function(){

			jQuery(document).on( "cut copy", "<?php echo $post_class; ?>",
				function( e ) {
					if(window.getSelection){
						var selected_text = window.getSelection();

						var link = "<br /><br /> Read More at: "+document.location.href+"#utm_source=copypaste&utm_campaign=referral";

						var copy_text = selected_text + link;

						jQuery("#pmc-js-copy-text").css( {'display' : 'block',
							'position' : 'absolute',
							'left': '-99999px',
							'background-color': 'white',
							'color': 'black',
							'font-size': '14px'}
						);

						jQuery("#pmc-js-copy-text").html( copy_text );

						selected_text.selectAllChildren( document.getElementById('pmc-js-copy-text') );
					}

				}
			);

		});
		</script>

		<?php
	}

	/**
	 * Loader for common scripts/styles in <head>
	 * For front-end only
	 *
	 */
	public static function enqueue_stuff() {
		if ( is_admin() ) {
			return false;
		}

		// As discussed in dev chat on 2013-08-26
		// Waypoints are used by themes for event tracking in GA, fires when an element comes into view
		wp_enqueue_script( 'waypoints', pmc_global_functions_url( '/js/waypoints.js' ), array( 'jquery' ), '1.1.7', true );
		wp_enqueue_script( 'pmc-remove-tracking', pmc_global_functions_url( '/js/pmc-remove-tracking.js' ), array( 'jquery' ) );

		// General event handlers and generic methods.
		wp_enqueue_script( 'pmc-global-functions', pmc_global_functions_url( '/js/pmc-global.min.js' ), array( 'jquery' ) );

		wp_script_add_data( 'pmc-remove-tracking', 'cfasync', true );

		//Load global styles for all sites
		wp_enqueue_style( 'pmc-global-css-overrides', pmc_global_functions_url( '/css/pmc-global-overrides.css' ) );
	}

	/**
	 * Register utility scripts which can be enqueued on a site as needed
	 *
	 * @since 2013-11-21 Amit Gupta
	 */
	public static function register_scripts() {
		$scripts = array(
			'encoder' => array(
				'dependencies' => array(),
				'version' => '',
				'in_footer' => false,
			),
			'pmc-hooks' => array(
				'dependencies' => array(),
				'version' => '',
				'in_footer' => false,
			),
		);

		foreach ( $scripts as $name => $params ) {
			wp_register_script( $name, pmc_global_functions_url( '/js/' . $name . '.js' ), $params['dependencies'], $params['version'], $params['in_footer'] );
		}

	}

	/**
	 * Loader for essential scripts like pmc-utils.js on top in <head>
	 *
	 * using wp_head action to print javascript instead of wp_enqueue_scripts as
	 * these scripts need to be loaded before anything else so that they're
	 * available to all our javascript below
	 *
	 * @since 2012-08-16 Amit Gupta
	 * @version 2012-08-16 Amit Gupta
	 * @version 2013-05-23 Amit Gupta
	 * @version 2013-11-21 Amit Gupta
	 * @version 2014-02-18 Amit Gupta - added geoPosition.js
	 * @version 2016-11-10 Brandon Camenisch - Removed geoPosition.js
	 * @version 2018-06-05 Piotr Delawski - added AMP exclusion
	 */
	public static function top_js_loader() {

		// Prevent scripts inlining in the AMP context.
		if ( function_exists( 'is_amp_endpoint' ) && did_action( 'parse_query' ) && is_amp_endpoint() ) {
			return;
		}

		// prevent script from rendering multiple time
		if ( !empty( self::$_scripts_rendered ) ) {
			return;
		}

		self::$_scripts_rendered = true;

		// load up pmc-utils.js js library
		$scripts = array(
			'pmc-utils',
		);

		for ( $i = 0; $i < count( $scripts ); $i++ ) {
			$src = apply_filters( 'script_loader_src', pmc_global_functions_url( '/js/' . $scripts[ $i ] . '.js' ), $scripts[ $i ] );
			echo '<script type="text/javascript" src="' . esc_url( $src ) . '"></script>';
		}

		//setup flag for pageview count in analytics
		if( ! is_admin() ) {
?>
			<script type="text/javascript">
			var pmc_do_analytics_pagecount = true;		//flag to allow analytics code to count a page view
			var pmc_common_urls = {
				parent_theme_uri: '<?php echo esc_js( trailingslashit( get_template_directory_uri() ) ); ?>',
				current_theme_uri: '<?php echo esc_js( trailingslashit( get_stylesheet_directory_uri() ) ); ?>',
				fb_channel_uri: '<?php echo esc_js( plugins_url( 'partner/facebook/channel.html', dirname( __DIR__ ) ) ); ?>'
			};
			</script>
<?php
		}
	}


	/**
	 * Helper function to get an array of all the files in the partners directory recursively
	 * Returns array of form: Absolute path to file => file
	 *
	 * @param string $dir
	 * @return array
	 */
	private static function get_files_in_dir( $dir ) {

		$result = array();

		$cdir = scandir( $dir );
		foreach ( $cdir as $key => $value ) {
		   if ( ! in_array( $value, array( ".", ".." ) ) ) {

			  if ( is_dir( $dir . DIRECTORY_SEPARATOR . $value ) ) {
				 $result = array_merge( $result, PMC_Feature::get_files_in_dir( $dir . DIRECTORY_SEPARATOR . $value ) );
			  } else {
				 $result[$dir . '/' . $value] = $value;
			  }

		   }
		}

		return $result;
	}


	/**
	 * Handle 404 issue of old bridge files.
	 * Detect partner bridge file on 404 pages and serve static content
	 */
	public static function handle_partner_file_404() {

		$bypass = apply_filters( 'pmc-handle-partner-file-404-bypass', false );
		if ( $bypass === true ) {
			return;
		}

		$url_path = $_SERVER['REQUEST_URI'];

		// strip off the query vars so it doesn't mess up pathinfo(); save the query vars in case we do a redirect
		$query_var_start = strpos( $url_path, '?' );
		$query_vars = '';
		if ( false !== $query_var_start ) {
			$query_vars = substr( $url_path, $query_var_start );
			$url_path = substr( $url_path, 0, $query_var_start );
		}

		$path_info = pathinfo( $url_path );

		$ext = '';
		if( isset( $path_info['extension'] ) ){
			$ext = $path_info['extension'];
		}

		$extension_array = array( 'html', 'htm', 'js' );

		if ( in_array( strtolower( $ext ), $extension_array ) ) {

			// @change 2016-05-28 Corey Gilmore - crude workaround for deprecated function on Go
			if( defined ('PMC_IS_VIP_GO_SITE' ) && PMC_IS_VIP_GO_SITE === true ) {
				$partner_path = dirname( PMC_GLOBAL_FUNCTIONS_PATH ) . '/partner';
			} else {
				$partner_path = wpcom_vip_theme_dir( "pmc-plugins/partner" );
			}
			$requested_file = $path_info['basename'];

			// cache retrieving partner files because of it's slow performance
			if ( false === ( $partner_files = get_transient( 'partner_files' ) ) ) {
				$partner_files = PMC_Feature::get_files_in_dir( $partner_path );
				set_transient( 'partner_files', $partner_files, ( 60 * 60 ) ); // expires in one hour
			}

			// try to find a partner file to redirect to
			foreach ( $partner_files as $file_path => $file_name ) {
				if ( strtolower( $file_name ) == strtolower( $requested_file ) ) {

					// turn absolute path into a valid URL
					$redirected_url = str_replace( $partner_path, '', $file_path );
					$redirected_url = wpcom_vip_noncdn_uri( $partner_path ) . $redirected_url . $query_vars;

					// issue 301 redirect
					wp_safe_redirect( $redirected_url, 301 );
					exit;
				}
			}

			wp_die( '404', '', array( 'response' => 404 ) );
		}
	}


	/**
	 * Don't send PuSH pings for published posts that are being updated
	 *
	 * @since   2013-06-03 Corey Gilmore
	 * @version 2013-06-03 Corey Gilmore
	 * @see     https://wordpressvip.zendesk.com/requests/17766
	 *
	 */
	public static function pmc_selective_pushpress_ping( $new_status, $old_status, $post ) {

		// @todo Corey Gilmore 2013-06-03 add a basic time check, so posts younger than N minutes will still ping?
		if ( $post->post_type == 'post' && $old_status == 'publish' && $new_status == 'publish' ) {
			add_filter( 'wpcom_pushpress_should_send_ping', '__return_false' );
		}

	}

	public static function make_link_https( $link ) {
		$link = set_url_scheme( $link, 'https' );
		return $link;
	}

	public static function open_graph_tags( $tags ) {
		// This is almost never correct, if it isn't a Facebook URL, unset it. #PPT-1857
		if( isset( $tags['article:author'] ) && stripos( $tags['article:author'], 'facebook.com' ) === false ) {
			unset( $tags['article:author'] );
		}

		// Never set the article:publisher to WordPresscom – this is 100% incorrect behavior
		if( isset( $tags['article:publisher'] ) && stripos( $tags['article:publisher'], 'WordPresscom' ) !== false ) {
			unset( $tags['article:publisher'] );
		}

		/**
		 * Every LOB should hook this filter and return a fully-qualified https://facebook.com/PageName URL
		 *
		 */
		$article_publisher = apply_filters( 'pmc_open_graph_tags_article:publisher', false, $tags );
		if( !empty( $article_publisher ) ) {
			// Safety check, if someone returns 'PageName', then prepend https://facebook.com/
			if( is_string( $article_publisher ) && !parse_url( $article_publisher, PHP_URL_HOST ) ) {
				$article_publisher = 'https://facebook.com/' . $article_publisher;
			}
			$tags['article:publisher'] = esc_url( $article_publisher );
		}

		return $tags;
	}

	public static function feed_add_post_type( $query_string ) {

		if ( isset( $query_string['feed'] ) && ! isset( $qv['post_type'] ) ) {
			$query_string['post_type'] = array( 'post', 'pmc-gallery' );
		}

		return $query_string;
	}

	/** When selecting a media from your WordPress Media Library, by default the Link URL selected, is the File URL. Set that to None always.
	 * @return string
	 */
	public static function image_default_link_type() {
		return apply_filters( "pmc_feature_image_default_link_type", "none" );
	}

	/**
	 * @since 2014-09-03 Corey Gilmore Add css classes to body for varius device type e.g desktop/mobile/tablet.
	 */
	public static function add_body_classes() {
		if ( is_admin() ) {
			return;
		}

		$classes   = array();
		$classes[] = PMC::is_desktop() ? 'pmc-desktop' : 'pmc-no-desktop';
		$classes[] = PMC::is_tablet() ? 'pmc-tablet' : 'pmc-no-tablet';
		$classes[] = PMC::is_mobile() ? 'pmc-mobile' : 'pmc-no-mobile';

		// Add Geolocation info on body class
		if ( function_exists( 'pmc_geo_get_user_location' ) ) {
			$classes[] = "geo-" . pmc_geo_get_user_location();
		}

		pmc_add_body_class( $classes );
	}

	/**
	 * @since 2014-09-15 Amit Sannad
	 * Add global cheezcap group that are part of ALL LOB's
	 */
	public static function cheezcap_groups( $cheezcap_groups = array() ) {

		if ( empty( $cheezcap_groups ) || ! is_array( $cheezcap_groups ) ) {
			$cheezcap_groups = array();
		}

		// Needed for compatibility with BGR_CheezCap
		if( class_exists( 'BGR_CheezCapGroup' ) ) {
			$cheezcap_group_class = 'BGR_CheezCapGroup';
		} else {
			$cheezcap_group_class = 'CheezCapGroup';

		}

		$cheezcap_options = array(
				new CheezCapDropdownOption( 'Polar Tags', 'Turn on Polar tags PPT-4671', 'pmc_polar_tags', array(
					'disabled',
					'0b838751257a4dbd8dc653bd01aadcc2',
					'1c951dcc94c9403cb8154ac4a1add42d',
					'82441bfdc0f6457395f2956707188ad9'
				), 0, // First option => Disabled
					array( 'Disabled', 'BGR', 'TVLine', 'HollywoodLife' ) ),
				new CheezCapTextOption( 'HotJar Analytics ID', 'Set hotJar analytics accound ID to enable it.', 'pmc_hotjar_analytics_id', "" )
			);

		$cheezcap_options = apply_filters( 'pmc_global_cheezcap_options', $cheezcap_options );
		$cheezcap_groups[] = new $cheezcap_group_class( "Global Theme Options", "pmc_global_cheezcap", $cheezcap_options );

		return $cheezcap_groups;
	}

	/**
	 * PPT-3451 Add word count to post meta when an article is saved
	 * @since 2014-10-15 Amit Sannad
	 * @needs-unit-test
	 */
	public static function init_custom_fields() {

		$grp_args = array(
			'label'   => 'PMC Metabox',
			'context' => 'side',
		);

		$post_type = array( 'post', 'vl-english', 'exclusive' );

		$post_type = apply_filters( 'pmc_feature_meta_post_type', $post_type );

		x_add_metadata_group( '_pmc_feature_meta_group', $post_type, $grp_args );

		// adds a field with a custom display callback (see below)
		x_add_metadata_field(
			'_pmc_content_word_count', $post_type, array(
				'group'            => '_pmc_feature_meta_group',
				'display_callback' => array( get_called_class(), 'meta_hidden_callback' ),
			)
		);
	}


	/**
	 * PPT-3451 Add word count to post meta when an article is saved
	 * Wanted to enqueue admin js but then decided against it since I needed php var.
	 *
	 * @param $field_slug
	 * @param $field
	 * @param $object_type
	 * @param $object_id
	 * @param $value
	 *
	 * @since 2014-10-15 Amit Sannad
	 * @needs-unit-test
	 */
	public static function meta_hidden_callback( $field_slug, $field, $object_type, $object_id, $value ) {
		$count = 0;
		if ( ! empty( $value[0] ) ) {
			$count = $value[0];
		}
		//Don't want to display meta box as right now this just contains hidden field. In future if we add more meta box we can remove the display none.
		?>
		<style> #_pmc_feature_meta_group {
				display: none;
			}
		</style>
		<script type="text/javascript">
			jQuery(document).ready(
				function () {
					try {
						jQuery(document).on("wpcountwords", function (e, d) {
							//Had to use settimeout as the count is not updated yet and none of the parameters provide count information.
							setTimeout(function () {
								try {
									var pmc_wc_count = jQuery('#wp-word-count .word-count').text();
									jQuery('.<?php echo esc_js($field_slug) ?>').val(pmc_wc_count);
								} catch (exp) {
								}
							}, 1000);
						});

						// remove toggle from screen options
						jQuery('label[for="_pmc_feature_meta_group-hide"]').remove();
					} catch (ex) {
					}
				}
			)
		</script>
		<input class="<?php echo esc_attr( $field_slug ); ?>" type="hidden" name="<?php echo esc_attr( $field_slug ); ?>" value="<?php echo esc_attr( $count ); ?>" />
	<?php
	}

	/**
	 * Add Amazon code to the head of every page on every LOB as part of PPT-3565
	 *
	 * @version 2015-07-28 Corey Gilmore Remove protocol from script tag - Amazon suddenly supports https! :party:
	 *
	 * @since 2014-10-17 Adaeze Esiobu PPT-3565
	 *
	 * @author Adaeze Esiobu
	 */
	public static function pmc_amazon_script_head(){
		if( !apply_filters( 'pmc_amazon_ads_enabled', true ) ) {
			return false;
		}
		?>
		<!-- Amazon adcode begin-->
		<script data-cfasync='true' type='text/javascript' src='//c.amazon-adsystem.com/aax2/amzn_ads.js'></script>
		<script data-cfasync='true' type='text/javascript'>
			try {
				amznads.getAds('3157');
			} catch(e) { /*ignore*/}
		</script>
		<!-- Amazon adcode end-->
		<?php
	}

	/**
	 * @static
	 * pass the Amazon Key Value pairs to GPT for Step 2 PPT-3565
	 */
	public static function pmc_amazon_script_footer(){
		if( !apply_filters( 'pmc_amazon_ads_enabled', true ) ) {
			return false;
		}
		?>
		<script data-cfasync='true' type='text/javascript'>
			try {
				amznads.setTargetingForGPTAsync('amznslots');
			} catch(e) { /*ignore*/}
		</script>
		<?php
	}

	/**
	 * Adds hotJar analytics code for the sites for which the hotJar account ID is given in
	 *  Theme Options -> Global Theme Options -> HotJar Analytics ID
	 *
	 * @since 2017-07-20 PMCER-140
	 *
	 * @author Divyaraj Masani
	 */
	public static function pmc_hotjar_script_head(){

		$pmc_hotjar_analytics_id = trim( cheezcap_get_option( 'pmc_hotjar_analytics_id' ) );

		if ( empty( $pmc_hotjar_analytics_id ) ) {
			return;
		}

		if ( ! PMC::is_production() ) {
			return;
		}
		?>
		<!-- Hotjar Tracking Code for <?php echo esc_url( site_url() ); ?> -->
		<script>
			(function(h,o,t,j,a,r){
				h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
				h._hjSettings={hjid:<?php echo esc_js( $pmc_hotjar_analytics_id ); ?>,hjsv:5};
				a=o.getElementsByTagName('head')[0];
				r=o.createElement('script');r.async=1;
				r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
				a.appendChild(r);
			})(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
		</script>
		<?php
	}

	/**
	 * Force change quality of image on Mobile. Uses filter on jetpack_photon_url called as jetpack_photon_pre_args
	 *
	 * @param $args
	 * @param $image_url
	 * @param $scheme
	 *
	 * @return array
	 * @since 2015-01-13 Amit Sannad For PPT-4033
	 */
	public static function photon_pre_args_for_mobile( $args, $image_url, $scheme ) {

		$enabled = apply_filters( 'pmc_photon_pre_args_for_mobile', false );

		if ( empty( $enabled ) ) {
			return $args;
		}

		if ( PMC::is_mobile() && is_array( $args ) ) {

			$args['strip']   = "all";
			$args['quality'] = 80;
		}

		return $args;
	}

	/**
	 * Force change quality of image on Mobile. Uses wpcom_vip_set_image_quality function on VIP. This we have to do along with photon_pre_args_for_mobile since on VIP jetpack_photon_pre_args filter is not working. We are also using same filter inside function in both pmc_set_image_quality_for_mobile & photon_pre_args_for_mobile, because they are doing same thing.
	 * @since 2015-01-22 Amit Sannad For PPT-4033
	 * @change 2016-05-28 Corey Gilmore Add function_exists('wpcom_vip_set_image_quality') check for VIP Go
	 */
	public static function pmc_set_image_quality_for_mobile() {

		$enabled = apply_filters( 'pmc_photon_pre_args_for_mobile', false );

		//Dont run this on non vip.
		if ( !PMC::is_production() ) {
			return;
		}

		if ( function_exists( 'wpcom_vip_set_image_quality' ) && !empty( $enabled ) && PMC::is_mobile() && !is_feed() ) {
			wpcom_vip_set_image_quality( 80, 'all' );
		}
	}

	/**
	 * Called on 'wp_head' hook, this function outputs author
	 * meta tag with author name.
	 *
	 * @ticket PPT-4199
	 * @since 2015-02-11 Amit Gupta
	 *
	 * @return void
	 */
	public static function add_meta_tags() {
		/*
		 * Only for front-end
		 */
		if ( is_admin() ) {
			return;
		}

		if ( is_single() && get_the_ID() !== false ) {
			/*
			 * Output meta tag with author list
			 */
			$authors = PMC::get_post_authors_list( get_the_ID(), 'all', 'display_name', 'user_login' );

			if ( ! empty( $authors ) ) {
				printf( '<meta name="author" content="%s">', esc_attr( $authors ) );
			}
		}
	}

	/**
	 * PPT-4437 Redirect uppercase urls to lowercase url.
	 * @since 2015-03-31 Amit Sannad
	 *
	 */
	public static function redirect_to_lowercase() {

		if ( is_admin() || is_404() ) {
			return;
		}

		if ( is_singular( 'attachment' ) ) {
			return;
		}

		if ( is_singular( 'pmc-gallery' ) ) {
			return;
		}

		if ( is_archive() || is_singular() ) {
			$url_parts = parse_url( $_SERVER['REQUEST_URI'] );
			$url_path = $url_parts['path'];
			// check part of the url path for upper case
			if ( preg_match( "/[A-Z]/", $url_path ) > 0 ) {
				// make url path lower case
				$redirect_url = strtolower( $url_path );
				// just in case something wrong with preg_match...
				if ( $redirect_url == $url_path ) {
					return;
				}
				// re-attach query string if available.
				if ( isset( $url_parts['query'] ) ) {
					$redirect_url .= '?' . $url_parts['query'];
				}
				wp_safe_redirect( $redirect_url, 301 );
			}
		}

	}

	/**
	 * PPT-4671 Add Polar - tags to site.
	 *
	 * @change 2015-07-28 Corey Gilmore Remove protocol from mediavoice script URL.
	 *
	 * @since 2015-05-06 Amit Sannad
	 *
	 */
	public static function add_polar_tags() {

		try {
			//cheezcap_get_option throws exception since its directly using $cap->option without check. So if the site which has not set the value code will bark.
			$polar_tag_id = cheezcap_get_option( 'pmc_polar_tags', false );
		}
		catch ( Exception $e ) {
			$polar_tag_id = "";
		}

		if ( empty( $polar_tag_id ) || 'disabled' == $polar_tag_id ) {
			return;
		}

		?>
		<script data-cfasync='true' async src="//plugin.mediavoice.com/mediaconductor/mc.js"></script>
		<script data-cfasync='true'>
			window.mediaconductor=window.mediaconductor||function(){(mediaconductor.q=mediaconductor.q||[]).push(arguments);}
			mediaconductor("init", "<?php echo esc_js($polar_tag_id); ?>");
			mediaconductor("exec");
		</script>
	<?php
	}

	/**
	 * This function short circuits the 'jwplayer_content_mask' option fetch if
	 * current URL is on HTTPS and returns the default JWPlayer content mask
	 * because our custom content masks run into problems with browsers due to non-availability
	 * of SSL Certificates for the custom content mask domains.
	 *
	 * @ticket PPT-5212
	 *
	 * @since 2015-07-29 Amit Gupta
	 */
	public static function maybe_override_jwplayer_content_mask( $default ) {
		/**
		 * For when WP autoloads options before get_current_screen() is defined.
		 * @ticket https://wordpressvip.zendesk.com/requests/44090
		 */
		if ( ! function_exists( 'get_current_screen' ) ) {
			return $default;
		}

		$current_screen = get_current_screen();

		if (
			( ( empty( $current_screen ) || ! is_a( $current_screen, 'WP_Screen' ) || $current_screen->id == 'options-media' ) && is_admin() )
			|| ! defined( 'JWPLAYER_CONTENT_MASK' ) || ! PMC::is_https()
		) {
			return $default;
		}

		/*
		 * Current URL is not media options page and is on HTTPS so use default
		 * JWPlayer content mask
		 */
		return JWPLAYER_CONTENT_MASK;
	}

	/**
	 * Post types besides post and page need to be whitelisted using the rest_api_allowed_post_types filter
	 * in order to access them via the public REST API
	 *
	 * @see: https://developer.wordpress.com/docs/api/
	 * @since 2015-09-03
	 * @version 2015-09-03 Archana Mandhare PPT-5369
	 *
	 * @param array $allowed_post_types Array containing the allowed post_types
	 *
	 * @return array $allowed_post_types Array containing the allowed post_types
	 */
	public static function filter_allow_custom_post_types_in_rest_api( $allowed_post_types ) {

		/*
		 * Get all the post types using get_post_types()
		 * Private CPT can be accessed only with authentication
		 * Public CPT can be accessed with or without authentication
		 * Hence we can expose all post types and fetch data using authentication header.
		 */
		$all_post_types = get_post_types();

		$rest_api_post_types = apply_filters( 'pmc_rest_api_post_types', $all_post_types );

		$allowed_post_types = array_unique( array_merge( $allowed_post_types, $rest_api_post_types ) );

		return $allowed_post_types;

	}

	/**
	 * This filter should return only the post types that we want WP PUBLIC REST API should support
	 * If we still want certain custom post types to NOT be downloaded we can use this filter to remove those out
	 * from $rest_api_post_types array.
	 *
	 * @see: https://developer.wordpress.com/docs/api/
	 * @since 2015-09-03
	 * @version 2015-09-03 Archana Mandhare PPT-5369
	 *
	 * @param array $rest_api_post_types Array containing the allowed post_types
	 *
	 * @return array $rest_api_post_types Array containing the allowed post_types
	 */
	public static function filter_pmc_rest_api_post_types( $rest_api_post_types ) {

		$blacklisted = array(
			'attachment',
			'revision',
			'safecss',
			'feedback',
			'wp-help',
			'redirect_rule',
			'vip-legacy-redirect',
			'dashboard-note',
			'nav_menu_item',
		);

		$rest_api_post_types = array_diff( $rest_api_post_types, $blacklisted );

		return $rest_api_post_types;

	}

	/**
	 * One of the hidden features of XML-RPC is that you can use the system.multicall method to execute multiple
	 * methods inside a single request. That’s very useful as it allow application to pass multiple commands within one
	 * HTTP request. This features is used in brute forcing logins in an attempt to determine administrative user
	 * credentials via xml rpc calls.
	 *
	 * ref:https://blog.sucuri.net/2015/10/brute-force-amplification-attacks-against-wordpress-xmlrpc.html
	 * https://wordpressvip.zendesk.com/requests/45828
	 * https://pop.co/blog/protecting-your-wordpress-blog-from-xmlrpc-brute-force-amplification-attacks/
	 *
	 * @since 2015-10-13 Amit Sannad
	 */
	public static function remove_xmlrpc_methods( $methods ) {

		if ( defined( 'WPCOM_IS_VIP_ENV' ) && true === WPCOM_IS_VIP_ENV ) {
			return $methods;
		}
		unset( $methods['system.multicall'] );
		unset( $methods['system.listMethods'] );
		unset( $methods['system.getCapabilities'] );

		return $methods;
	}

	/**
	 * Update the custom taxonomies' term counts when a post's status is changed.
	 *
	 * For example, default posts term counts (for custom taxonomies) don't include
	 * private / draft posts.
	 *
	 * NOTE: This function was copied from wp-includes/post-functions.php
	 *
	 * By using our own version we can pass just the post_id, which saves us
	 * option space in the batch_term_counting() method above.
	 *
	 * Also of note, we're using get_the_terms instead of wp_get_object_terms because
	 * the former is cached whereas the later is not.
	 *
	 * @since 3.3.0
	 * @access private
	 *
	 * @param int $post_id Post ID
	 *
	 * @return null
	 */
	static function _update_term_count_on_transition_post_status( $post_id ) {

		$post = get_post( $post_id );

		// Update counts for the post's terms.
		foreach ( (array) get_object_taxonomies( $post->post_type ) as $taxonomy ) {

			$terms = get_the_terms( $post->ID, $taxonomy );

			if ( false !== $terms && is_wp_error( $terms ) ) {
				$tt_ids = wp_list_pluck( $terms, 'term_id' );
				wp_update_term_count( $tt_ids, $taxonomy );
			}
		}
	}

	/*
	 * @param $content
	 * @return mixed
	 * @since 2015-10-26 - Javier Martinez - PMCVIP-459 - Remove control characters
	 */
	public static function filter_replace_control_characters( $content ){

		if( empty( $content ) ){
			return $content;
		}

		return PMC::replace_control_characters( $content );
	}

	/**
	 * Stripping email address from query parameter.
	 * It's been noticed on VY and Deadline that third party newsletters
	 * are linking to articles with email appended in the url.
	 * And this is causing PII violation when ads served on those pages.
	 * EX: http://variety.com/2017/digital/news/katie-couric-leaving-yahoo-1202509944/?email=test%@test.com.
	 *
	 * @since 2017-07-31 - Vinod Tella - PMCRS-617.
	 *
	 */
	public static function pii_redirect() {

		$uri = urldecode( esc_url_raw( $_SERVER['REQUEST_URI'] ) );
		$redirect = false;
		$redirect_url = '';

		/**
		 * Define query string keys which should be skipped.
		 *
		 * These are keys which are present in calls to lob.com?key=val
		 * that must be present and contain an email address.
		 *
		 * @param array An array of query string keys to ignore when looking for an email address.
		 */
		$exclude_list = apply_filters( 'pmc_pii_redirect_key_exclusions', [] );

		//First checking if @ is present in the uri
		if ( false !== strpos( $uri, '@' ) ) {
			$url_parts = wp_parse_url( $uri );
		 	$url_path  = $url_parts['path'];
			$url_query = $url_parts['query'];

			//At this point we are only checking query parameters i.e ?abc=xxx@xxx.com&xyz=1.
			//Performing simple check by checking $url_query has both @ and . then excluding that parameter from url.
			//Once above condition is true then performing regex match.
			if ( ! empty( $url_query) && false !== strpos( $url_query, '@' )
				 && false !== strpos( $url_query, '.' ) && false === strpos( $uri, '/wp-admin' ) ) {

				wp_parse_str( $url_query , $new_url_query );
				foreach ( $new_url_query as $key=>$val ) {

					// Skip if the current key has been excluded from filtering
					if ( ! empty( $exclude_list ) && is_array( $exclude_list ) ) {
						if ( in_array( $key, $exclude_list ) ) {
							continue;
						}
					}

					if ( filter_var( $val, FILTER_VALIDATE_EMAIL ) ) {
						unset( $new_url_query[ $key ] );
						$redirect = true;
					}
				}

				$new_url_query_str = ( ! empty( $new_url_query ) ) ? http_build_query ( $new_url_query ) : '';
				$redirect_url = ( ! empty( $new_url_query_str ) ) ? add_query_arg( $new_url_query, $url_path ) : $url_path;

				if ( true === $redirect && ! empty( $redirect_url ) ) {
					wp_safe_redirect( $redirect_url, 301 );
					exit;
				}
			}
		}

	}

	/**
	 *  Return a clean upload filename that is sanitized and has special characters removed
	 *
	 * @param  array $file filename
	 * @return array $file with sanitized name
	 *
	 * @since 2019-01-15 - MJ Zorick - PMCEED-1610.
	 */
	public static function pmc_sanitize_upload_filename( $file ) {

		if ( ! $file || ! is_array( $file ) ) {
			return $file;
		}

		// remove ™, ®, ©, etc. Add here as more characters found.
		$regex        = '/(™|®|©|&trade;|&reg;|&copy;|&#8482;|&#174;|&#169;)/';
		$file['name'] = preg_replace( $regex, '', $file['name'] );
		$file['name'] = sanitize_file_name( $file['name'] );

		return $file;
	}

//End of Class
}

PMC_Feature::load();

//EOF
