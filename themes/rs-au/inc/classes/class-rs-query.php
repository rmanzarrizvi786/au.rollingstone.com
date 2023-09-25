<?php
/**
 * Rolling Stone Query Object.
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2018-03-13
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Class RS_Query
 */
class RS_Query {

	// use Singleton;

	/**
	 * Post IDs that are used.
	 *
	 * @var array
	 */
	protected $used_posts = [];

	/**
	 * RS_Query constructor.
	 */
	public function __construct() {
		add_action( 'pre_get_posts', [ $this, 'pre_get_posts' ] );
	}

	/**
	 * An important feature of the Rolling Stone site is that all news and archive
	 * feeds for categories should show all content types.
	 *
	 * @param \WP_Query $query The WordPress query object.
	 */
	public function pre_get_posts( $query ) {

		if ( ! is_admin() && $query->is_main_query() && ! is_singular() ) {
			$query->set( 'post_type', array( 'post', 'pmc_top_video', 'pmc-gallery', 'pmc_list' ) );
		}
	}

	/**
	 * Fetch posts and exclude used posts from WP_Query.
	 *
	 * @param string|array $args Array or string for WP_Query.
	 * @return array
	 */
	public function get_posts( $args = '' ) {
		$args = wp_parse_args(
			$args, array(
				'posts_per_page' => 3,
			)
		);

		if ( ! empty( $args['post__not_in'] ) ) {
			$this->used_posts = array_merge( $this->used_posts, $args['post__not_in'] );
		}

		if ( ! empty( $this->used_posts ) ) {
			$args['post__not_in'] = $this->used_posts;
		}

		$query = new \WP_Query( $args );

		if ( $query->have_posts() && ! is_wp_error( $query->posts ) ) {
			foreach ( $query->posts as $the_post ) {
				$this->used_posts[] = $the_post->ID;
			}

			return $query->posts;
		}

		return array();
	}
}

new RS_Query();
