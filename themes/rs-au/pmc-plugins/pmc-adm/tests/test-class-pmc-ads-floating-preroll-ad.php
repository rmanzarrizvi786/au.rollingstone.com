<?php
/**
 * Unit test cases for PMC_Ads_Floating_Preroll_Ad class
 *
 * @author jignesh Nakrani <jignesh.nakrani@rtcamp.com>
 *
 * @package pmc-adm
 */

use PMC\Unit_Test\Mock\Mocker;
use PMC\Unit_Test\Utility;

/**
 * @group pmc-adm
 * @coversDefaultClass PMC_Ads_Floating_Preroll_Ad
 */
class Test_Class_PMC_Ads_Floating_Preroll_Ad extends WP_UnitTestCase {

	/**
	 * @var PMC_Ads_Floating_Preroll_Ad
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

		$instances       = Utility::get_hidden_static_property( 'PMC_Ads_Floating_Preroll_Ad', '_instance' );
		$this->_instance = $instances['PMC_Ads_Floating_Preroll_Ad'];

		$this->_mocker = new Mocker();
	}

	/**
	 * To prevent all upload files from deletion, since set $ignore_files = true
	 * we override the function and do nothing here
	 */
	public function remove_added_uploads() {
	}

	/**
	 * @covers ::__construct
	 */
	public function test__construct() {

		$actions = [
			'pmc-tags-top'       => 'action_add_floating_preroll_ad_markup',
			'wp_enqueue_scripts' => 'localize_floating_preroll_ad_data',
		];

		foreach ( $actions as $key => $value ) {

			$this->assertNotEquals(
				false,
				has_action( $key, [ $this->_instance, $value, ] ),
				sprintf( 'PMC_Ads_Floating_Preroll_Ad::__construct failed registering action "%1$s" to PMC_Ads_Floating_Preroll_Ad::%2$s', $key, $value )
			);

		}

	}

	/**
	 * @covers ::action_add_floating_preroll_ad_markup
	 * @covers ::get_floating_preroll_ad
	 */
	public function test_action_add_floating_preroll_ad_markup() {

		PMC_Cheezcap::get_instance()->register();

		$instance = PMC_Ads::get_instance();
		$instance->add_provider( new Google_Publisher_Provider( 'unittest' ) );
		$instance->add_locations( [
			'floating-video-preroll-ad' => 'Floating Video Preroll Ad',
		] );

		// Empty ads test
		$ads = $this->_instance->action_add_floating_preroll_ad_markup();
		$this->assertNull( $ads );

		$ad = [
			'provider'     => 'google-publisher',
			'device'       => [ 'Desktop', 'Mobile', 'Tablet' ],
			'slot-type'    => 'oop',
			'zone'         => 'unittest',
			'sitename'     => 'unittest',
			'ad-width'     => '[[1,1]]',
			'dynamic_slot' => 'unittest',
			'width'        => 1,
			'height'       => 1,
			'location'     => 'floating-video-preroll-ad',
			'div-id'       => 'unittest',
			'media-id'     => 'xxyyzz',
			'player-id'    => 'aabbcc',
		];

		$p = $this->factory->post->create( [
			'post_type'    => 'pmc-ad',
			'post_content' => json_encode( $ad ),
		] );

		update_post_meta( $p, '_ad_location', 'floating-video-preroll-ad' );

		$this->_mocker->mock_global_wp_query( [
			'is_single'         => true,
			'queried_object_id' => $p,
			'queried_object'    => get_post( $p ),
		] );
		$ads = $this->_instance->get_floating_preroll_ad();

		$this->assertArraySubset( [
			'zone'      => 'unittest',
			'slot-type' => 'oop',
			'location'  => 'floating-video-preroll-ad',
		], $ads, 'Invalid out of page ad type' );

		$html = Utility::buffer_and_return( [ $this->_instance, 'action_add_floating_preroll_ad_markup' ] );
		$this->assertContains( '<div class="floating-preroll-ad"', $html, 'Cannot find floating preroll ad container div' );

		// Restore WP_Query.
		$this->_mocker->restore_global_wp_query();

	}

	/**
	 * @covers ::add_preroll_ad_location
	 */
	public function test_add_preroll_ad_location() {

		$locations = apply_filters( 'pmc_adm_locations', [] );

		$expected['floating-video-preroll-ad'] = [
			'title'     => __( 'Floating Preroll Ad', 'pmc-adm' ),
			'providers' => [ 'google-publisher' ],
		];

		$this->assertArraySubset( $expected, $locations );
	}

}
