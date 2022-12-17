<?php
/**
 * Breadcrumbs
 *
 * Breadcrumbs related functionality.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-21
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Class Breadcrumbs
 *
 * @see \PMC\Global_Functions\Traits\Singleton
 */
class Breadcrumbs {

	// use Singleton;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_filter( 'breadcrumb_primary_category_id', [ $this, 'get_primary_category_id' ] );
		add_filter( 'breadcrumb_secondary_category_id', [ $this, 'get_secondary_category_id' ] );
	}

	/**
	 * Get the primary category ID.
	 *
	 * @param int $id The original ID.
	 * @return int
	 */
	public function get_primary_category_id( $id ) {
		$category = rollingstone_get_the_category();

		if ( ! empty( $category->term_id ) && $category->term_id > 0 ) {
			return $category->term_id;
		}

		return $id;
	}

	/**
	 * Get the secondary category ID.
	 *
	 * @param int $id The original ID.
	 * @return int
	 */
	public function get_secondary_category_id( $id ) {
		$category = rollingstone_get_the_subcategory();

		if ( ! empty( $category->term_id ) && $category->term_id > 0 ) {
			return $category->term_id;
		}

		return $id;
	}
}

new Breadcrumbs();
