<?php
/**
 * Class to override category names when displayed on front-end
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-06-06
 */

namespace Rolling_Stone\Inc\Overrides;

use \PMC\Global_Functions\Traits\Singleton;
use \WP_Term;

class Category_Names {

	use Singleton;

	const TAXONOMY = 'category';

	/**
	 * Category name map
	 * The key has to be existing category name and the value
	 * must be the name that should be displayed on frontend
	 *
	 * @var array
	 */
	protected $_map = [];

	/**
	 * Class constructor
	 *
	 * @codeCoverageIgnore
	 */
	protected function __construct() {

		$this->_setup_map();

		$this->_setup_hooks();

	}

	/**
	 * Method which defines the rename-map
	 * Only reason for existence of this method is because a class var
	 * cannot use expressions outside a method.
	 *
	 * @return void
	 *
	 * @codeCoverageIgnore
	 */
	protected function _setup_map() : void {

		/*
		 * Disabling PHPCS below because its incorrectly flagging the formatting as bad
		 */

		//phpcs:disable
		$this->_map = [
			__( 'Music Country', 'pmc-rollingstone' )        => __( 'Country Music', 'pmc-rollingstone' ),
			__( 'Music Country Lists', 'pmc-rollingstone' )  => __( 'Country Music Lists', 'pmc-rollingstone' ),
			__( 'Music Country Videos', 'pmc-rollingstone' ) => __( 'Country Music Videos', 'pmc-rollingstone' ),
		];
		//phpcs:enable

	}

	/**
	 * Method to set up listeners to hooks
	 *
	 * @return void
	 *
	 * @codeCoverageIgnore
	 */
	protected function _setup_hooks() : void {

		/*
		 * Filters
		 */
		add_filter( 'get_term', [ $this, 'maybe_override' ], 10, 2 );

	}

	/**
	 * Called by 'get_term' hook, this method overrides category name if an override is defined for a category
	 *
	 * @param \WP_Term $term
	 * @param string   $taxonomy
	 *
	 * @return \WP_Term
	 */
	public function maybe_override( WP_Term $term, string $taxonomy = '' ) : WP_Term {

		if ( is_admin() ) {

			// Let's avoid any change in wp-admin
			// to prevent workflow disruption
			return $term;

		}

		if ( self::TAXONOMY !== $taxonomy ) {
			return $term;
		}

		if ( empty( $this->_map ) || empty( $term->name ) ) {
			return $term;
		}

		if ( array_key_exists( $term->name, $this->_map ) ) {
			$term->name = $this->_map[ $term->name ];
		}

		return $term;

	}

}    //end class

//EOF
