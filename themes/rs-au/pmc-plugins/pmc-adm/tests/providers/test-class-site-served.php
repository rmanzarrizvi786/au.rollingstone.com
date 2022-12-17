<?php
/**
 * Unit test cases for Site_Served_Provider class
 *
 * @author  Dhaval Parekh <dhaval.parekh@rtcamp.com>
 *
 * @package pmc-adm
 */

use PMC\Unit_Test\Mock\Mocker;
use PMC\Unit_Test\Utility;

/**
 * @coversDefaultClass Site_Served_Provider
 */
class Test_Site_Served_Provider extends \WP_UnitTestCase {

	/**
	 * @var Site_Served_Provider
	 */
	protected $_instance = false;

	/**
	 * @var PMC\Unit_Test\Mock\Mocker
	 */
	protected $_mocker = false;

	/**
	 * Setup Method.
	 */
	public function setUp() {
		// To speed up unit test, we bypass files scanning on upload folder.
		self::$ignore_files = true;
		parent::setUp();

		$instances       = Utility::get_hidden_static_property( 'PMC_Ads', '_instance' );
		$this->_instance = $instances['PMC_Ads']->get_provider( 'site-served' );

		$this->_mocker = new Mocker();
	}

	/**
	 * To prevent all upload files from deletion, since set $ignore_files = true
	 * we override the function and do nothing here
	 */
	public function remove_added_uploads() {
	}

	/**
	 * @covers ::render_ad
	 */
	public function test_render_ad() {

		// Mock Ad.
		$ad_data = [
			'width'            => 780,
			'height'           => 90,
			'priority'         => 10,
			'slot-type'        => 'normal',
			'provider'         => 'site-served',
			'status'           => 'Active',
			'start'            => '',
			'end'              => '',
			'css-class'        => '',
			'is-ad-rotatable'  => '',
			'ad-group'         => 'default',
			'duration'         => 8,
			'timegap'          => 24,
			'device'           => 'Desktop',
			'title'            => 'b-leaderboard',
			'location'         => 'header-leaderboard',
			'is_lazy_load'     => '',
			'adunit-order'     => '',
			'logical_operator' => 'or',
			'ad-display-type'  => 'banner',
			'sitename'         => 'example.org',
			'div-id'           => 'leaderboard-div-boom',
			'ad-width'         => '[780,90]',
			'ad-url'           => 'http://ad-url.com/',
			'ad-image'         => 'http://ad-image.com/image.jpg',
			'targeting_data'   => [
				[
					'key'   => 'pos',
					'value' => 'top',
				],
			],
		];

		// Assertion.
		$return_output = $this->_instance->render_ad( $ad_data );

		$render_output = Utility::buffer_and_return( [ $this->_instance, 'render_ad' ], [ $ad_data, true ] );

		// Trim because `Utility::buffer_and_return()` trim output.
		$this->assertEquals( trim( $return_output ), trim( $render_output ) );

		$this->assertContains( 'href="http://ad-url.com/"', $return_output );
		$this->assertContains( 'src="http://ad-image.com/image.jpg"', $return_output );
		$this->assertContains( 'width="780"', $return_output );
		$this->assertContains( 'height="90"', $return_output );
		$this->assertContains( 'pmc-adm-site-served-ad-image', $return_output );

	}

}
