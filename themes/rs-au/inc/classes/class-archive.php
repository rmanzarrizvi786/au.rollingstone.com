<?php
/**
 * Archive
 *
 * Archive related functionality.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-16
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Class Archive
 *
 * @see \PMC\Global_Functions\Traits\Singleton
 */
class Archive {

	use Singleton;

	/**
	 * Class constructor.
	 */
	protected function __construct() {
		add_filter( 'excerpt_length', [ $this, 'set_excerpt_length' ] );
		add_filter( 'get_the_archive_title', [ $this, 'remove_archive_prefix' ] );
		add_action( 'pre_get_posts', [ $this, 'playlist_archive_total' ] );
	}

	/**
	 * Set the excerpt length.
	 *
	 * @return int
	 */
	public function set_excerpt_length() {
		return 16;
	}

	/**
	 * Remove the prefix from the archive title.
	 *
	 * @param string $title The title.
	 *
	 * @return mixed
	 */
	public function remove_archive_prefix( $title ) {
		$split = explode( ':', $title );

		if ( ! empty( $split[1] ) && is_array( $split[1] ) && 2 === count( $split ) ) {
			return $split[1];
		}

		return $title;
	}

	/**
	 * Playlist list 20 posts per page.
	 *
	 * @param object $query The main query.
	 *
	 * @return void
	 */
	public function playlist_archive_total( $query ) {

		// Bail if not the main query or if it is admin.
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( is_tax( 'vcategory' ) ) {
			$query->set( 'posts_per_page', 20 );
		}
	}
}
