<?php
/**
 * Class Reviews
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-18
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;
use \PMC\Core\Inc\Related;
use \PMC\Review\Review;

/**
 * Class Reviews
 */
class Reviews {

	use Singleton;

	/**
	 * Class constructor.
	 *
	 * @since 2018-05-18
	 */
	protected function __construct() {

		$this->setup_hooks();

	}

	/**
	 * Adds hooks related to reviews.
	 */
	protected function setup_hooks() {

		add_filter( 'pmc_review_category_slugs', array( $this, 'review_categories' ) );
		add_filter( 'pmc_review_json_id', array( $this, 'review_json_id' ) );
		add_filter( 'rollingstone_related_box_class', array( $this, 'filter_related_box_class' ) );
		add_filter( 'rollingstone_related_box_title', array( $this, 'filter_related_box_title' ) );
		add_filter( 'pmc_carousel_items_fallback_taxonomy', array( $this, 'filter_carousel_items_fallback_taxonomy' ) );
		add_filter( 'pmc_carousel_items_fallback_term', array( $this, 'filter_carousel_items_fallback_term' ) );

	}

	/**
	 * Sets the carousel taxonomy fallback to category if the current post is a review.
	 *
	 * @param string $taxonomy The taxonomy.
	 * @return string The filtered taxonomy.
	 */
	public function filter_carousel_items_fallback_taxonomy( $taxonomy ) {

		if ( has_category( $this->review_categories() ) ) {
			return 'category';
		}

		return $taxonomy;
	}

	/**
	 * Sets the carousel item fallback term to the current post's review category if one exists.
	 *
	 * @param string $taxonomy The fallback term slug.
	 * @return string The filtered term slug.
	 */
	public function filter_carousel_items_fallback_term( $term ) {

		foreach ( $this->review_categories() as $category ) {

			if ( has_category( $category ) ) {
				return $category;
			}

		}

		return $term;
	}

	/**
	 * Filter the categories which should allow review data.
	 *
	 * @param array $categories List of categories.
	 * @return array
	 */
	public function review_categories( $categories = [] ) {
		return array(
			'movie-reviews',
			'music-album-reviews',
			'music-live-reviews',
			'tv-reviews',
		);
	}

	/**
	 * Get the slug of the current post's category matching one of the review categories.
	 *
	 * @return string The slug or an empty string on failure.
	 */
	public function get_review_subcategory_slug() {

		$categories = get_the_category();

		foreach ( $categories as $category ) {
			if ( in_array( $category->slug, (array) $this->review_categories(), true ) ) {
				return $category->slug;
			}
		}

		return '';
	}

	/**
	 * Supplies an alternative ID attribute for the JSON data element.
	 *
	 * @return string The filtered ID.
	 */
	public function review_json_id( $id ) {

		foreach ( $this->review_categories() as $slug ) {
			if ( has_category( $slug ) ) {
				// Use the singular form of the slug, e.g. pmc-movie-review-snippet.
				$singular_slug = preg_replace( '/s$/', '', $slug );

				return "pmc-$singular_slug-snippet";
			}
		}

		return $id;
	}

	/**
	 * Adds modifier class to the c-related component on reviews.
	 *
	 * @param string $class A CSS class attribute.
	 * @return string The filtered class attribute.
	 */
	public function filter_related_box_class( $class ) {

		if ( ! has_category( $this->review_categories(), $GLOBALS['post'] ) ) {
			return $class;
		}

		return "$class c-related--inverted";

	}

	/**
	 * Filters the title on the related box.
	 *
	 * @param string $title The box title.
	 * @return string The filtered title.
	 */
	public function filter_related_box_title( $title ) {

		if ( ! has_category( $this->review_categories(), $GLOBALS['post'] ) ) {
			return $title;
		}

		return __( 'Related Reviews', 'pmc-rollingstone' );

	}

	/**
	 * Prefixed home_url if given url is relative URL.
	 *
	 * @param string $relative_url
	 *
	 * @return string
	 */
	public function maybe_get_full_url( string $relative_url ) : string {

		$domain       = wp_parse_url( $relative_url );
		$relative_url = ( empty( $domain['host'] ) ) ? home_url( $relative_url ) : $relative_url;

		return $relative_url;

	}

}
