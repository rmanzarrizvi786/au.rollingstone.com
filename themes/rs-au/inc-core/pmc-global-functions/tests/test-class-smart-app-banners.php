<?php
namespace PMC\Global_Functions\Tests;

use PMC\Unit_Test\Mock\Mocker;
use PMC\Unit_Test\Utility;

/**
 * @group pmc-global-functions
 *
 * Unit test for class Smart_App_Banners
 *
 * @author  Divyaraj Masani
 *
 * @coversDefaultClass PMC\Global_Functions\Smart_App_Banners
 */
class Test_Smart_App_Banners extends Base {

	/**
	 * @var $_instance
	 */
	protected $_instance;

	/**
	 * @var $_mocker
	 */
	protected $_mocker;

	function setUp() {

		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;

		$instance        = Utility::get_hidden_static_property( '\PMC\Global_Functions\Smart_App_Banners', '_instance' );
		$this->_instance = $instance['PMC\Global_Functions\Smart_App_Banners'];

		$this->_mocker = new Mocker();

		parent::setUp();

	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * @covers ::_setup_hooks
	 */
	public function test_setup_hooks() {

		$hooks = array(
			array(
				'type'     => 'action',
				'name'     => 'wp_head',
				'priority' => 8,
				'listener' => 'output_meta_tags',
			),
			array(
				'type'     => 'filter',
				'name'     => 'pmc_cheezcap_groups',
				'priority' => 10,
				'listener' => 'create_smart_banner_cheezcap_group',
			),
		);

		foreach ( $hooks as $hook ) {

			$this->assertEquals(
				$hook['priority'],
				call_user_func( sprintf( 'has_%s', $hook['type'] ), $hook['name'], array( $this->_instance, $hook['listener'] ) ),
				sprintf( 'Failed to register %1$s "%2$s" to %3$s()', $hook['type'], $hook['name'], $hook['listener'] )
			);

		}

	}

	/**
	 * @covers ::_maybe_show_banner
	 */
	public function test_maybe_show_banner() {

		$this->assertFalse( Utility::invoke_hidden_method( $this->_instance, '_maybe_show_banner' ) );

		$this->toggle_smart_banner( true );

		$this->assertTrue( Utility::invoke_hidden_method( $this->_instance, '_maybe_show_banner' ) );

	}

	/**
	 * @covers ::create_smart_banner_cheezcap_group
	 */
	public function test_create_smart_banner_cheezcap_group() {

		global $GLOBALS;

		// Get all the registered cheezcap options.
		$registered_cheezcap_options = array_keys( (array) Utility::get_hidden_property( $GLOBALS['cap'], 'data' ) );

		// CheezCap opitons registered by the class, which needs to be there when initialised.
		$cheezcap_options = [
			'smart_banner_app_activated',
			'smart_banner_app_ios_app_id',
			'smart_banner_app_android_app_id',
		];

		foreach ( $cheezcap_options as $cheezcap_option ) {
			$this->assertContains( $cheezcap_option, $registered_cheezcap_options );
		}

	}

	/**
	 * @covers ::output_meta_tags
	 */
	public function test_output_meta_tags() {

		// Smart Banner disabled.
		$this->toggle_smart_banner( false );

		ob_start();

		do_action( 'wp_head' );

		$string = ob_get_clean();

		$this->assertFalse( strpos( $string, 'manifest.json' ) );

		// Smart Banner enabled.
		$this->toggle_smart_banner( true );

		ob_start();

		do_action( 'wp_head' );

		$string = ob_get_clean();

		$this->assertNotFalse( strpos( $string, 'manifest.json' ) );

	}

	/**
	 * @covers ::manifest_init
	 */
	public function test_manifest_init() {
		global $wp_rewrite;

		$this->assertArrayNotHasKey( 'manifest\.json$', $wp_rewrite->extra_rules_top );

		$this->toggle_smart_banner( true );

		$this->_instance->manifest_init();

		$this->assertArrayHasKey( 'manifest\.json$', $wp_rewrite->extra_rules_top );

	}

	/**
	 * @covers ::add_query_vars
	 */
	public function test_add_query_vars() {

		$query_vars = [];

		$this->assertNotContains( 'manifest_json', $query_vars );

		$this->toggle_smart_banner( true );

		$query_vars = $this->_instance->add_query_vars( $query_vars );

		$this->assertContains( 'manifest_json', $query_vars );

	}

	/**
	 * Helper function to toggle smart banner options.
	 */
	public function toggle_smart_banner( $toggle ) {

		$smart_options = Utility::set_and_get_hidden_property(
			$this->_instance,
			'_smart_banner_options',
			[
				'ios_app_id'              => '123456789',
				'android_app_id'          => 'com.example.google',
				'is_smart_banner_enabled' => $toggle,
			]
		);

		$this->_smart_banner = $toggle;
	}

} //end class

//EOF
