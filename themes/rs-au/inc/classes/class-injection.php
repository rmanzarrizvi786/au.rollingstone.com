<?php
/**
 * Class Injection
 *
 * Handlers for the Injection functionality.
 *
 * @package pmc-rollingstone-2018
 * @since   2018-06-11
 */

namespace Rolling_Stone\Inc;

use PMC\Global_Functions\Traits\Singleton;

/**
 * Class Injection
 *
 * @since 2018-06-11
 * @see   \PMC\Global_Functions\Traits\Singleton
 */
class Injection {

	use Singleton;

	/**
	 * Class constructor.
	 */
	protected function __construct() {

		/**
		 * Actions
		 */
		add_action( 'init', [ $this, 'action_init' ] );

	}

	/**
	 * Callback function of init action
	 *
	 * @action init
	 *
	 * @return void
	 */
	public function action_init() {
		// Remove filters of pmc-core-v2
		remove_filter( 'pmc_inject_content_paragraphs', [ \PMC\Core\Inc\Injection::get_instance(), 'inject' ] );

	}

}
