<?php
namespace PMC\Global_Functions\Tests;

use PMC\Unit_Test\Utility;

/**
 * Unit test cases for \PMC\Global_Functions\HTTPS
 *
 * @author  Markham F Rollins IV <mrollins@pmc.com>
 *
 * @since   2018-05-24
 *
 * @group pmc-global-functions
 *
 * @coversDefaultClass \PMC\Global_Functions\HTTPS
 *
 * @package pmc-global-functions
 */
class Test_Https extends Base {

	protected $_instance;

	function setUp() {
		// to speed up unit test, we bypass files scanning on upload folder.
		self::$ignore_files = true;
		parent::setUp();

		// Force SSL mode
		$_SERVER['HTTPS'] = 'on';

		// Need to destroy the singleton and re-instantiate
		Utility::set_and_get_hidden_static_property( '\PMC\Global_Functions\HTTPS', '_instance', null );
		$this->_instance = \PMC\Global_Functions\HTTPS::get_instance(); // This exists as it's dependent on the modifying of $_SERVER
	}

	/**
	 * @covers ::__construct
	 */
	public function test__construct() {

		$hooks = array(
			[
				'type'     => 'filter',
				'name'     => 'wp_headers',
				'priority' => 10,
				'listener' => 'add_csp_headers',
				'instance' => true,
			],
			[
				'type'     => 'filter',
				'name'     => 'rel_canonical',
				'priority' => 10,
				'listener' => 'wpcom_vip_force_https_canonical',
				'instance' => false,
			],

		);

		foreach ( $hooks as $hook ) {
			$function = $hook['listener'];
			if ( true === $hook['instance'] ) {
				$function = [ $this->_instance, $hook['listener'] ];
			}

			$this->assertEquals(
				$hook['priority'],
				call_user_func( sprintf( 'has_%s', $hook['type'] ), $hook['name'], $function ),
				sprintf( '\PMC\Global_Functions\HTTPS->__construct() failed to register %1$s "%2$s" to %3$s()', $hook['type'], $hook['name'], $hook['listener'] )
			);

		}
	}

	/**
	 * Verify that the new CSPRO and CSP headers are begin returned from the filter. No sent headers test is present.
	 *
	 * @covers ::add_csp_headers()
	 */
	public function test_add_csp_headers() {
		$this->assertArrayHasKey( 'Content-Security-Policy-Report-Only', $this->_instance->add_csp_headers( [] ) );
		$this->assertArrayHasKey( 'Content-Security-Policy', $this->_instance->add_csp_headers( [] ) );
	}

}
