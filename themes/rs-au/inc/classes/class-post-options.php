<?php
/**
 * Class to enable different post options for this theme
 *
 * This is a manifest only. Each post option and its functionality must be
 * in its own class and that class should be instantiated here.
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-07-02
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Ignoring coverage for this class as it is only a manifest.
 * Each post option class instantiated here has its own unit tests.
 *
 * @codeCoverageIgnore
 */
class Post_Options {

	use Singleton;

	/**
	 * Class constructor
	 *
	 * @codeCoverageIgnore
	 */
	protected function __construct() {

		$this->_enable();

	}

	/**
	 * Method to enable post options
	 *
	 * @return void
	 *
	 * @codeCoverageIgnore
	 */
	protected function _enable() : void {

		\Rolling_Stone\Inc\Options\Ecomm_Disclaimer::get_instance();
		\Rolling_Stone\Inc\Options\Pro_Publica_Pixel::get_instance();

	}

}    //end class

//EOF
