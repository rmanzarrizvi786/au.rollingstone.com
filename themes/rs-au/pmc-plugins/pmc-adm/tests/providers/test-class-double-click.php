<?php
/**
 * Unit test cases for DoubleClick_Provider class
 *
 * @author  Dhaval Parekh <dhaval.parekh@rtcamp.com>
 *
 * @package pmc-adm
 */

use PMC\Unit_Test\Utility;

/**
 * @coversDefaultClass DoubleClick_Provider
 */
class Test_DoubleClick_Provider extends \WP_UnitTestCase {
	/**
	 * @var DoubleClick_Provider
	 */
	protected $_instance = false;

	/**
	 * Setup Method.
	 */
	public function setUp() {
		// To speed up unit test, we bypass files scanning on upload folder.
		self::$ignore_files = true;
		parent::setUp();

		$instances       = Utility::get_hidden_static_property( 'PMC_Ads', '_instance' );
		$this->_instance = $instances['PMC_Ads']->get_provider( 'double-click' );

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
			'provider'         => 'double-click',
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
			'type'             => '',
			'targeting_data'   => [
				[
					'key'   => 'pos',
					'value' => 'top',
				],
			],
		];

		$return_output = $this->_instance->render_ad( $ad_data );

		$render_output = Utility::buffer_and_return( [ $this->_instance, 'render_ad' ], [ $ad_data, true ] );

		$this->assertContains( sprintf( '//ad.doubleclick.net/%s/%s/%s', '1234', $ad_data['type'], $ad_data['sitename'] ), $return_output );
		$this->assertContains( 'data-device="Desktop"', $return_output );
		$this->assertContains( 'data-adheight="90"', $return_output );
		$this->assertContains( 'data-adwidth="780"', $return_output );

		$this->assertContains( 'data-device="Desktop"', $render_output );
		$this->assertContains( 'data-adheight="90"', $render_output );
		$this->assertContains( 'data-adwidth="780"', $render_output );

		// Second test.
		$ad_data['type'] = 'adj';
		$output          = $this->_instance->render_ad( $ad_data );

		$this->assertContains( sprintf( '<script type="text/javascript" src="//ad.doubleclick.net/%s/%s/%s', '1234', $ad_data['type'], $ad_data['sitename'] ), $output );

	}

}
