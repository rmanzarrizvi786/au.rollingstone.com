<?php
/**
 * Artists
 *
 * Artists related functionality.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-23
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Class Artists
 *
 * @see \PMC\Global_Functions\Traits\Singleton
 */
class Artists {

	use Singleton;

	const ARTIST_POST_TYPE = 'pmc_artist';

	/**
	 * Class constructor.
	 *
	 * @since 2018-05-23
	 */
	protected function __construct() {
		add_filter( 'init', [ $this, 'register_post_type' ] );
	}

	/**
	 * Register post type.
	 */
	public function register_post_type() {
		register_post_type(
			self::ARTIST_POST_TYPE, array(
				'labels'      => array(
					'name'               => __( 'Artists', 'pmc-rollingstone' ),
					'singular_name'      => __( 'Artist', 'pmc-rollingstone' ),
					'add_new'            => _x( 'Add New', 'Artist', 'pmc-rollingstone' ),
					'add_new_item'       => __( 'Add New Artist', 'pmc-rollingstone' ),
					'edit_item'          => __( 'Edit Artist', 'pmc-rollingstone' ),
					'new_item'           => __( 'New Artist', 'pmc-rollingstone' ),
					'view_item'          => __( 'View Artist', 'pmc-rollingstone' ),
					'search_items'       => __( 'Search Artists', 'pmc-rollingstone' ),
					'not_found'          => __( 'No Artists found.', 'pmc-rollingstone' ),
					'not_found_in_trash' => __( 'No Artists found in Trash.', 'pmc-rollingstone' ),
					'all_items'          => __( 'Artists', 'pmc-rollingstone' ),
				),
				'public'      => true,
				'supports'    => array( 'title', 'editor', 'thumbnail' ),
				'has_archive' => 'artists',
				'rewrite'     => array(
					'slug' => 'artist',
				),
				'menu_icon'   => 'dashicons-admin-users',
			)
		);
	}
}
