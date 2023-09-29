<?php
/**
 * Rolling Stone theme setup.
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2018-03-05
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

class Rolling_Stone {

	use Singleton;

	/**
	 * Class constructor.
	 *
	 * Initializes the theme.
	 */
	protected function __construct() {

		$this->_setup_hooks();
		$this->_load_plugins();
		$this->_setup_theme();

	}

	/**
	 * To setup actions/filters.
	 *
	 * @codeCoverageIgnore
	 *
	 * @return void
	 */
	protected function _setup_hooks() {

		/**
		 * Actions
		 */
		add_action( 'init', [ $this, 'disable_live_chat' ] );
		add_action( 'zoninator_pre_init', [ $this, 'add_post_types_to_zoninator' ] );
		add_action( 'pmc-tags-footer', [ $this, 'add_footer_tags' ] );

		add_action( 'wp_head', [ $this, 'adjust_og_tag_output' ], 1 );

		/**
		 * Filters
		 */
		add_filter( 'vip_live_chat_enabled', '__return_false' ); // disable olark.
		add_filter( 'pmc_inject_wrap_paragraph', '__return_false' );
		add_filter( 'pmc_amazon_ads_enabled', '__return_false' ); // disable render-blocking Amazon Ads.
		add_filter( 'document_title_parts', [ $this, 'get_single_page_post_title' ] ); // hook sets aside title from SEO meta box.
		add_filter( 'vip_go_srcset_enabled', '__return_false' );  // disable VIP Go responsive images as this theme uses its own implementation already

	}

	/**
	 * Load Plugins
	 *
	 * @codeCoverageIgnore
	 */
	private function _load_plugins() {

		/**
		 * Filter the list of plugins to load for this theme.
		 *
		 * @param array $plugins Plugins to be passed to {@see load_pmc_plugins()}.
		 */

		// Plugins should be loaded alphabetically.
		// @NOTE: Please keep this list of plugins alphabetically ordered
		load_pmc_plugins(
			[
				'plugins'     => [
					'apester-interactive-content' => '2.0',
					'jwplayer'                    => '1.6',
				],
				'pmc-plugins' => [
					'ads-txt',
					'mce-table-buttons' => '3.2',
					'options-importer',
					'pmc-410',
					'pmc-amzn-onsite',
					'pmc-amazon-apstag',
					'pmc-automated-related-links',
					'pmc-contentdial',
					'pmc-content-publishing',
					'pmc-floating-video',
					'pmc-gallery-v4',
					'pmc-frontend-components',
					'pmc-google-amp',
					'pmc-iframe-widget',
					'pmc-lists',
					'pmc-protected-embeds',
					'pmc-publication-issue-v2',
					'pmc-quantcast-cmp',
					'pmc-review',
					'pmc-sticky-ads',
					'pmc-sticky-rail-ads',
					'pmc-store-products',
					'pmc-styled-heading',
					'pmc-top-videos-v2',
					'pmc-touts',
					'pmc-zoninator-extended',
					'spotim-comments',
				],
			]
		);

		require_once CHILD_THEME_PATH . '/plugins/megaphone/megaphone.php';

		// Charts.
		require_once CHILD_THEME_PATH . '/plugins/charts/charts.php';

		// Configs.
		require_once CHILD_THEME_PATH . '/plugins/_config/_manifest.php';

	}

	/**
	 * Setup Theme
	 *
	 * Hook into `after_setup_theme` to init child theme functionality after the
	 * parent theme's functions and definitions have loaded.
	 *
	 * @codeCoverageIgnore The actual code should be tested, not if it is called.
	 * @action after_setup_theme
	 */
	protected function _setup_theme() {

		// Callable Functions.
		require_once( CHILD_THEME_PATH . '/inc/helpers/callable-functions.php' );

		// Template Tags.
		require_once( CHILD_THEME_PATH . '/inc/helpers/template-tags.php' );

		Rewrites::get_instance();
		Redirects::get_instance();    // Redirects should come after Rewrites since redirects depend on WP API for permalinks etc
		Post_Options::get_instance();    // Enable post options before other features as they can be dependent on one or more of these
		Assets::get_instance();
		Editorial::get_instance();
		Carousels::get_instance();
		Widgets::get_instance();
		Menus::get_instance();
		Footer_Feed::get_instance();
		Media::get_instance();
		RS_Query::get_instance();
		Pagination::get_instance();
		Archive::get_instance();
		Category::get_instance();
		\Rolling_Stone\Inc\Overrides\Category_Names::get_instance();
		Lists::get_instance();
		Fields::get_instance();
		Reviews::get_instance();
		Featured_Article::get_instance();
		Smart_Crops::get_instance();
		Artists::get_instance();
		Country::get_instance();
		Issues::get_instance();
		Head::get_instance();
		Injection::get_instance();
		Breadcrumbs::get_instance();

	}

	/**
	 * Disable Olark, it breaks up and causes our UI JS to halt
	 * Added on VY RS launch after go ahead from Pete Schiebel in Stormchat
	 *
	 * @since 2017-09-26 Amit Gupta
	 */
	public function disable_live_chat() {

		if ( ! function_exists( 'wpcom_vip_remove_livechat' ) ) {
			return;
		}

		wpcom_vip_remove_livechat();

	}

	/**
	 * To add post type in zoninator.
	 *
	 * @since 2018-07-24 <jignesh.nakrani@rtcamp.com> Jignesh Nakrani
	 *
	 * @return void
	 */
	public function add_post_types_to_zoninator() {

		$available_post_types = array( 'pmc_list' );

		foreach ( $available_post_types as $post_type ) {
			if ( post_type_exists( $post_type ) ) {
				add_post_type_support( $post_type, 'zoninator_zones' );
			}
		}

	}

	/**
	 * Function to override parent theme add_theme_support( 'title-tag' ) support.
	 *
	 * Used hook to set title from seo meta box.
	 *
	 * @since 2018-08-14 Alexander Trinh READS-1394
	 *
	 * @param array $title post title
	 *
	 * @return array $title post seo title
	 */
	public function get_single_page_post_title( $title ) {

		if ( is_single() ) {

			$post_seo_title = get_post_meta( get_the_ID(), 'mt_seo_title', true );

			if ( ! empty( $post_seo_title ) ) {

				$title['title'] = $post_seo_title;
			}
		}
		return $title;
	}

	/**
	 * Add custom tags.
	 *
	 * @author Vishal Dodiya <vishal.dodiya@rtcamp.com>
	 */
	public function add_footer_tags() {

		\PMC::render_template(
			CHILD_THEME_PATH . '/template-parts/footer/tags.php',
			[],
			true
		);
	}

	/**
	 * Adjust priority for Jetpack open graph tags to happen before inline CSS.
	 * The OG tags need to be in the first 40k for FB to see them.
	 *
	 * @return void
	 *
	 * @since 2019-05-17 Aaron Jorbin
	 */
	public function adjust_og_tag_output() : void {

		/*
		 * Change of priority here because this theme has a bunch of inline CSS
		 * which pushes down OG tags and Facebook crawler looks for OG tags
		 * in first 40k chars only. This increase in priority would ensure OG tags
		 * are output before bulk of inline CSS.
		 */
		remove_action( 'wp_head', 'jetpack_og_tags', 10 );

		add_action( 'wp_head', 'jetpack_og_tags', 2 );

	}

}

//EOF
