<?php
/**
 * Unit test for class PMC_Must_Have_Plugins
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-01-20
 */

namespace PMC\Global_Functions\Tests;

use \PMC_Must_Have_Plugins;

/**
 *
 * @group pmc-global-functions
 * @group pmc-global-functions-pmc-must-have-plugins
 *
 * @requires PHP 7.2
 * @coversDefaultClass \PMC_Must_Have_Plugins
 */
class Test_PMC_Must_Have_Plugins extends Base {

	function setUp() {

		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;

		parent::setUp();

	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * Method to loop over the provided array of plugins and assert if they have been
	 * loaded or not. If not then the assertion for that plugin fails.
	 *
	 * @param array $data An array of plugin names and identifier to check
	 * @return void
	 */
	protected function _check_if_plugins_have_been_loaded( array $data ) : void {

		foreach ( $data as $plugin => $plugin_details ) {

			if ( empty( $plugin_details['type'] ) || empty( $plugin_details['to_check'] ) ) {
				$value_to_test = false;
			} else {

				$callback      = sprintf( '%s_exists', $plugin_details['type'] );
				$value_to_test = $callback( $plugin_details['to_check'] );

			}

			$error_string = sprintf( 'Plugin "%1$s" not loaded', $plugins );

			$this->assertTrue( $value_to_test, $error_string );

		}

	}

	/**
	 * @covers ::load_vip_plugins
	 */
	public function test_load_vip_plugins() {

		$plugins = [

			'cheezcap'        => [
				'type'     => 'class',
				'to_check' => '\CheezCap',
			],

			'pmc-geo-uniques' => [
				'type'     => 'function',
				'to_check' => 'pmc_geo_add_location',
			],

		];

		//call the function to test
		PMC_Must_Have_Plugins::load_vip_plugins();

		// Lets make sure all plugins are loaded
		$this->_check_if_plugins_have_been_loaded( $plugins );

	}

	/**
	 * @covers ::load_pmc_plugins
	 */
	public function test_load_pmc_plugins() {

		$plugins = [

			'pmc-tracking'          => [
				'type'     => 'class',
				'to_check' => '\PMC_Tracking',
			],
			'pmc-js-libraries'      => [
				'type'     => 'function',
				'to_check' => 'pmc_js_libraries_get_registered_scripts',
			],
			'pmc-krux-tag'          => [
				'type'     => 'class',
				'to_check' => '\PMC_Krux_Tag',
			],
			'pmc-page-meta'         => [
				'type'     => 'class',
				'to_check' => '\PMC_Page_Meta',
			],
			'pmc-google-tagmanager' => [
				'type'     => 'class',
				'to_check' => '\PMC_Google_Tagmanager',
			],
			'pmc-disable-live-chat' => [
				'type'     => 'function',
				'to_check' => 'pmc_disable_live_chat_loader',
			],
			'pmc-linkcount'         => [
				'type'     => 'class',
				'to_check' => '\PMC_LinkCount',
			],
			'pmc-taxonomy-export'   => [
				'type'     => 'function',
				'to_check' => 'pmc_taxonomy_export_loader',
			],
			'pmc-omni'              => [
				'type'     => 'function',
				'to_check' => 'pmc_omni_loader',
			],
			'pmc-post-reviewer'     => [
				'type'     => 'function',
				'to_check' => 'pmc_post_reviewer_loader',
			],

		];

		//call the function to test
		PMC_Must_Have_Plugins::load_pmc_plugins();

		// Lets make sure all plugins are loaded
		$this->_check_if_plugins_have_been_loaded( $plugins );

	}

}    //end class


//EOF
