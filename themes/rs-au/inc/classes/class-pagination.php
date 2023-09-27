<?php
/**
 * Class Pagination
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-24
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Class Pagination
 */
class Pagination {

	// use Singleton;

	/**
	 * Class constructor.
	 *
	 * @since 2018-04-24
	 */
	public function __construct() {
		add_filter( 'next_posts_link_attributes', array( $this, 'next_posts_link_attributes' ) );
		add_filter( 'previous_posts_link_attributes', array( $this, 'previous_posts_link_attributes' ) );

		// Ignore content pagination. Old RS content was paginated and the new theme does not support it (it's also not wanted).
		add_filter( 'content_pagination', array( $this, 'ignore_content_pagination' ) );
	}

	/**
	 * Rolling Stone Next Posts Link Attributes
	 *
	 * Adds a class to the next post links.
	 *
	 * @since 2018-05-07
	 *
	 * @return string
	 */
	public function next_posts_link_attributes() {
		$class = 'c-pagination__btn c-pagination__btn--right c-btn c-btn--pagination t-semibold t-semibold--upper';
		if ( rollingstone_is_country() ) {
			$class .= ' c-btn--pagination--country';
		}

		return 'class="' . $class . '"';
	}

	/**
	 * Rolling Stone Previous Posts Link Attributes
	 *
	 * Adds a class to the prev post links.
	 *
	 * @since 2018-05-07
	 *
	 * @return string
	 */
	public function previous_posts_link_attributes() {
		$class = 'c-pagination__btn c-btn c-btn--pagination t-semibold t-semibold--upper';
		if ( rollingstone_is_country() ) {
			$class .= ' c-btn--pagination--country';
		}

		return 'class="' . $class . '"';
	}

	/**
	 * Ignore content pagination.
	 *
	 * @param array $pages pages.
	 * @return array
	 */
	public function ignore_content_pagination( $pages ) {

		if ( ! is_array( $pages ) ) {
			$pages = [];
		}

		$pages = [ join( '', $pages ) ];
		return $pages;
	}

}

new Pagination();
