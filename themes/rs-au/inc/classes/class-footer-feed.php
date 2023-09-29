<?php
/**
 * Footer Feed.
 *
 * Used for building the footer feed for posts across the PMC brands.
 *
 * @package pmc-rollingstone-2017
 * @since 2018-04-11
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Class Footer_Feed
 *
 * @since 2018-04-11
 * @see \PMC\Global_Functions\Traits\Singleton
 */
class Footer_Feed {

	use Singleton;

	/**
	 * Class constructor.
	 *
	 * @since 2018-04-11
	 */
	protected function __construct() {

		$this->_setup_hooks();

	}

	/**
	 * Initialize Hooks and filters.
	 */
	protected function _setup_hooks() {

		add_filter( 'pmc_master_footer_feed_callback', array( $this, 'set_footer_feed' ) );
		add_filter( 'pmc_footer_list_of_feeds', array( $this, 'set_footer_list' ) );
		add_action( 'wp_feed_options', array( $this, 'modify_feed_options' ) );
		add_filter( 'pmc_footer_feed_image_domains', array( $this, 'set_footer_image_domain' ) );

	}

	/**
	 * Set Footer Feed List
	 *
	 * @since 2018-04-11
	 * @return array
	 */
	public function set_footer_list() {

		return array(
			0 => array(
				'feed_source_url' => 'https://hollywoodlife.com/feed/pmc_footer/',
				'feed_title'      => 'HollywoodLife',
				'css_classes'     => array(),
			),
			1 => array(
				'feed_source_url' => 'https://wwd.com/custom-feed/wwd_footer/',
				'feed_title'      => 'WWD',
				'css_classes'     => array(),
			),
			2 => array(
				'feed_source_url' => 'https://deadline.com/custom-feed/pmc_footer-2/',
				'feed_title'      => 'Deadline',
				'css_classes'     => array(),
			),
			3 => array(
				'feed_source_url' => 'https://indiewire.com/custom-feed/pmc_footer/',
				'feed_title'      => 'Indiewire',
				'css_classes'     => array(),
			),
			4 => array(
				'feed_source_url' => 'https://www.goldderby.com/custom-feed/pmc_footer/',
				'feed_title'      => 'GoldDerby',
				'css_classes'     => array(),
			),
		);

	}

	/**
	 * Sets the callback for the footer.
	 *
	 * @since 2018-04-11
	 * @return string
	 */
	public function set_footer_feed() {

		return 'rollingstone_render_footer_feed';

	}

	/**
	 * Builds the footer feed data.
	 *
	 * @since 2018-04-11
	 * @param array $args The callback params.
	 * @return array $item
	 */
	public static function build_footer_feed( $args ) {

		$item = array();

		$source_url = esc_url_raw( $args['feed_source_url'], array( 'http', 'https' ) );

		// If there is no source, bail.
		if ( ! $source_url ) {
			return false;
		} else {
			$item['source']['url'] = $source_url;
		}

		if ( isset( $args['feed_title'] ) ) {
			$item['source']['name'] = $args['feed_title'];
		}

		add_filter( 'wp_feed_cache_transient_lifetime', 'pmc_set_transient_to_thirty_minutes' );
		$feed = fetch_feed( $source_url );
		remove_filter( 'wp_feed_cache_transient_lifetime', 'pmc_set_transient_to_thirty_minutes' );

		if ( is_wp_error( $feed ) ) {
			return false;
		}

		$max_items = $feed->get_item_quantity( 1 );
		$rss_items = $feed->get_items( 0, $max_items );

		// Allow LOB to alter the image size w/o overriding the entire function.
		list( $image_width, $image_height ) = apply_filters( 'pmc_footer_feed_image_size', array( 230, 135 ) );

		// This is a loop, but it's really only looping over a single item.
		foreach ( $rss_items as $feed_item ) {
			// Excerpt.
			$item['title'] = $feed_item->get_title();
			$item['date']  = human_time_diff( strtotime( $feed_item->get_date() ), current_time( 'timestamp' ) );
			$item['url']   = $feed_item->get_permalink();

			$item['image'] = pmc_master_get_footer_image( $feed_item, $image_width, $image_height, $feed_item->feed->feed_url );

			if ( empty( $item['image'] ) ) {
				// If we don't have an image in feed then use fallback image.
				$item['image'] = get_template_directory_uri() . '/static/images/trans.gif';
				$item['image'] = apply_filters( 'pmc_footer_feed_default_image', $item['image'] );
			}
		}

		return $item;

	}

	/**
	 * Modifies feed options.
	 *
	 * @since 2017-08-06 Milind More CDWE-480
	 * @param object $feed SimplePie feed Object.
	 */
	public function modify_feed_options( $feed ) {

		// Set useragent to prevent 403.
		$feed->set_useragent( 'Mozilla/4.0 ' . SIMPLEPIE_USERAGENT );

	}

	/**
	 * Adds valid domain for footer feeds images.
	 *
	 * @since 2017-08-06 Milind More CDWE-480
	 * @param array $domains array of domains.
	 * @return array $domains updated array.
	 */
	public function set_footer_image_domain( $domains ) {

		if ( empty( $domains ) || ! is_array( $domains ) ) {
			$domains = array();
		}

		if ( ! in_array( 'tvline.com', (array) $domains, true ) ) {
			$domains[] = 'tvline.com';
		}

		return $domains;
	}

}
