<?php
/**
 * Category
 *
 * Category related functionality.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-25
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Class Category
 *
 * @see \PMC\Global_Functions\Traits\Singleton
 */
class Category {

	// use Singleton;

	/**
	 * String prepended to a post's object ID when caching its categories.
	 *
	 * @var string
	 */
	const POST_CATEGORY_CACHE_KEY_PREFIX = 'pmc_rs_categories_';
	const CACHE_GROUP                    = 'rollingstone_categories';

	/**
	 * Class constructor.
	 *
	 * @since 2018-04-25
	 */
	public function __construct() {

		add_filter( 'pmc-linkcontent-sf-types', [ $this, 'playlist_section_front' ] );
		add_action( 'category_template', array( $this, 'category_template' ) );
		add_action( 'set_object_terms', array( $this, 'invalidate_post_category_cache' ), 10, 4 );

	}

	/**
	 * Choose the correct category template to use. Only show the category
	 * template if we're visiting a parent category. Otherwise show the usual
	 * archive category.
	 *
	 * @since 2018-04-25
	 * @action category_template
	 * @param string $template The current template.
	 * @return string
	 */
	public function category_template( $template ) {

		$category = get_query_var( 'cat' );
		$category = get_category( $category );

		// Is this not a parent category?
		if ( ! empty( $category->parent ) ) {
			$template = locate_template( array( 'archive.php' ) );
		}

		return $template;

	}

	/**
	 * Add the video playlist (taxonomy) to the list of supported types (taxonomies).
	 *
	 * @param  array $types List of taxonomies.
	 * @return array
	 */
	public function playlist_section_front( $types ) {

		if ( taxonomy_exists( 'vcategory' ) ) {
			$types[] = 'vcategory';
		}

		return $types;
	}

	/**
	 * Get the cached category and subcategory for a post. If there are multiple categories
	 * and subcategories, we fetch the first.
	 *
	 * @param int $post_id The ID of the post.
	 * @return array|bool
	 */
	public function get_the_post_categories( $post_id = 0 ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$key = sprintf( '%s%d', self::POST_CATEGORY_CACHE_KEY_PREFIX, $post_id );

		$cache = new \PMC_Cache( $key, self::CACHE_GROUP );

		return $cache->expires_in( HOUR_IN_SECONDS )
					->updates_with( [ $this, 'get_the_post_categories_uncached' ], [ $post_id ] )
					->get();

	}

	/**
	 * Get the uncached category and subcategory for a post. If there are multiple categories
	 * and subcategories, we fetch the first.
	 *
	 * @param int $post_id The ID of the post.
	 * @return array
	 */
	public function get_the_post_categories_uncached( $post_id = 0 ) {

		// This has to use `wp_get_object_terms()` because we order them.
		$terms = wp_get_object_terms( $post_id, 'category', [
			'orderby' => 'term_order',
		] );

		$categories = [];

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

			foreach ( $terms as $term ) {

				if ( 0 === $term->parent ) {
					$categories['category'] = $term->term_id;
				} else {
					$categories['subcategory'] = $term->term_id;
				}

			}

		}

		return $categories;

	}

	/**
	 * Deletes the cache of a post's categories when they're modified.
	 *
	 * @param int    $object_id  Object ID.
	 * @param array  $terms      An array of object terms.
	 * @param array  $tt_ids     An array of term taxonomy IDs.
	 * @param string $taxonomy   Taxonomy slug.
	 */
	public function invalidate_post_category_cache( $object_id, $terms, $tt_ids, $taxonomy ) {

		if ( is_admin() && 'category' === $taxonomy ) {
			$cache = new \PMC_Cache( sprintf( '%s%d', self::POST_CATEGORY_CACHE_KEY_PREFIX, $object_id ), self::CACHE_GROUP );
			$cache->invalidate();
		}

	}
}
