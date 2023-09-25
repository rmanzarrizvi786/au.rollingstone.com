<?php
/**
 * Unit test cases for Adsense_Provider class
 *
 * @author  Dhaval Parekh <dhaval.parekh@rtcamp.com>
 *
 * @package pmc-adm
 */

use PMC\Unit_Test\Utility;

/**
 * @coversDefaultClass Adsense_Provider
 */
class Test_Adsense_Provider extends \WP_UnitTestCase {
	/**
	 * @var Adsense_Provider
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
		$this->_instance = $instances['PMC_Ads']->get_provider( 'adsense' );

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
		$ad_data = [
			'publisher_id' => 12345,
			'tag_id'       => 'tag_id',
			'size'         => [
				'height' => 100,
				'width'  => 200,
			],
			'slot-type'    => 'normal',
			'provider'     => 'adsense',
		];

		$return_output = $this->_instance->render_ad( $ad_data );

		$render_output = Utility::buffer_and_return( [ $this->_instance, 'render_ad' ], [ $ad_data, true ] );

		// Trim because `Utility::buffer_and_return()` trim output.
		$this->assertEquals( trim( $return_output ), trim( $render_output ) );

		$this->assertContains( "google_ad_client = '12345';", $return_output );
		$this->assertContains( "google_ad_slot = 'tag_id';", $return_output );
		$this->assertContains( 'google_ad_height = 100;', $return_output );
		$this->assertContains( 'google_ad_width = 200;', $return_output );

	}
}
