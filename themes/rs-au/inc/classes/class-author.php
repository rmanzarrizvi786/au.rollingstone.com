<?php
/**
 * Author
 *
 * Author related functionality.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;
use \PMC;
use \PMC_Cache;

/**
 * Class Author
 *
 * @since 2018.1.0
 * @see \PMC\Global_Functions\Traits\Singleton
 */
class Author {

	// use Singleton;

	/**
	 * Get Author Posts
	 *
	 * Gets the three most recent posts by an author.
	 *
	 * @since 2018.1.0
	 * @param int|string $author_name A \WP_User nicename.
	 *
	 * @return array|bool|mixed|string Cached Author posts.
	 */
	public function get_author_posts( $author_name ) {
		return $this->author_posts_query( $author_name );

		$cache_key = sanitize_key( 'rollingstone_author_posts_nicename_' . $author_name );
		$pmc_cache = new \PMC_Cache( $cache_key );

		// Cache for 5 min.
		$cache_data = $pmc_cache->expires_in( 3000 )
								->updates_with(
									array( $this, 'author_posts_query' ), array(
										'author_name' => $author_name,
									)
								)
								->get();

		if ( is_array( $cache_data ) && ! empty( $cache_data ) && ! is_wp_error( $cache_data ) ) {
			return $cache_data;
		}

		return array();
	}

	/**
	 * Author Posts Query
	 *
	 * Runs a \WP_Query to get the three most recent posts
	 * by a given author.
	 *
	 * This uses the author nicename as it is more reliable
	 * than using a User ID due to the use of the coauthors plugin.
	 *
	 * @since 2018.1.0
	 * @param int|string $author_nicename A \WP_User nicename.
	 *
	 * @return array|string Array of posts, else 'none' string.
	 */
	public function author_posts_query( $author_nicename ) {
		$query = new \WP_Query(
			array(
				'post_status'    => 'publish',
				'author_name'    => $author_nicename,
				'posts_per_page' => 3,
			)
		);

		// All we need is the IDs.
		$post_ids = wp_list_pluck( $query->posts, 'ID' );

		/*
		 * Store a string so that the query isn't run on every page load if
		 * the author truly has no posts.
		 */
		if ( empty( $post_ids ) ) {
			return 'none';
		}

		return $post_ids;
	}
}
