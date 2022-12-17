<?php
/**
 * Base class for all unit test for this plugin
 */
namespace PMC\Top_Videos_V2\Tests;

use PMC\Unit_Test\Utility;

abstract class Base extends \PMC\Unit_Test\Base {

	// We need to define this function to load and activate our plugin
	protected function _load_plugin() {

		$instance = false;

		if ( class_exists( '\PMC\Top_Videos_V2\PMC_Top_Videos' ) ) {
			// Check to make sure plugin has not been loaded and activated
			$instance = Utility::get_hidden_static_property( '\PMC\Top_Videos_V2\PMC_Top_Videos', '_instance' );
			$instance = ( ! empty( $instance['PMC\Top_Videos_V2\PMC_Top_Videos'] ) ) ? $instance['PMC\Top_Videos_V2\PMC_Top_Videos'] : false;
		}

		if ( empty( $instance ) ) {

			// first we need to load our plugin
			pmc_load_plugin( 'pmc-top-videos-v2', 'pmc-plugins' );

		}

	}

}
