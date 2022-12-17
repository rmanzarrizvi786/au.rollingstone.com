<?php

/**
 * @group pmc-adm
 * @coversDefaultClass PMC_Ads
 *
 * Tests for the Ad Manager Conditionals
 * e.g. only show an ad on is_home() or ! is_home(), etc. etc.
 * Conditionals Covered (and their 'not' clauses):
 * has_category
 * has_tag
 * has_term
 * in_vertical
 * is_404
 * is_archive
 * is_author
 * is_category
 * is_country
 * is_home
 * is_page
 * is_paginated
 * is_post_type_archive
 * is_search
 * is_single
 * is_singular
 * is_tag
 * is_tax
 * is_url_match
 * is_vertical
 */

use PMC\Unit_Test\Utility;
use PMC\Unit_Test\Mock\Mocker;

class Test_Class_PMC_Ads extends WP_UnitTestCase {

	protected $pmc_ads = null;
	protected $pmc_ad_conditionals = null;
	protected $testing_ads = [];

	/**
	 * @var \PMC\Unit_Test\Mock\Mocker
	 */
	protected $_mocker;

	/**
	 * Setup between each test.
	 */
	function setUp() {

		// do not report on warning to avoid unit test reporting on:
		// Cannot modify header information - headers already sent by ..
		error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

		// to speeed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;

		parent::setUp();

		// Ensure that the PMC ADs class caches are not placed in persistent storage.
		// We're effectively telling the class not to cache anything during testing.
		// This way, we can create new ad units in tests and expect to always receive
		// those same ad units (not the cached ads from the first test).
		wp_cache_add_non_persistent_groups( PMC_Ads::cache_group );

		$this->pmc_ads = PMC_Ads::get_instance();
		$this->pmc_ads->add_provider( new Google_Publisher_Provider( 'unittest' ) );
		$this->pmc_ads->add_provider( new Site_Served_Provider( 'ss-unit-test', [] ) );
		$this->pmc_ads->add_locations( [
			'top-leaderboard'    => 'Top Leaderboard',
			'bottom-leaderboard' => 'Bottom Leaderboard',
			'test-ad' => [
				'title'     => 'test-ad-title',
				'providers' => [ 'site-served' ],
			],
			'test-banner-ad' => [
				'title'     => 'test-banner-ad-title',
				'providers' => [ 'site-served', 'google-publisher' ],
			],
		] );

		$this->pmc_ad_conditionals = PMC_Ad_Conditions::get_instance();

		// Create a shared ad for use in each of the below tests
		$this->testing_ads['top-leaderboard']['leaderboard-ad'] = $this->_create_ad( [
			'title'         => 'leaderboard-ad',
			'width'         => 728,
			'height'        => 90,
			'location'      => 'top-leaderboard',
			'ad_conditions' => [],
		] );

		// Create a shared ad for use in each of the below tests
		$this->testing_ads['test-ad']['test-ad-title'] = $this->_create_ad( [
			'title'         => 'test-ad-title',
			'width'         => 728,
			'height'        => 90,
			'location'      => 'test-ad',
			'ad_conditions' => [],
			'ad-image'      => 'http://testsite.com/test-image.jpg',
			'ad-url'        => 'http://testsite.com/',
			'provider'      => 'site-served'
		] );

		// Create a shared ad for use in each of the below tests
		$this->testing_ads['test-banner-ad']['test-banner-ad-ss-title'] = $this->_create_ad( [
			'title'         => 'test-banner-ad-ss-title',
			'width'         => 728,
			'height'        => 90,
			'location'      => 'test-banner-ad',
			'ad_conditions' => [],
			'ad-image'      => 'http://testsite.com/test-image.jpg',
			'ad-url'        => 'http://testsite.com/',
			'provider'      => 'site-served'
		] );

		// Create a shared ad for use in each of the below tests
		$this->testing_ads['test-banner-ad']['test-banner-ad-dfp-title'] = $this->_create_ad( [
			'title'         => 'test-banner-ad-dfp-title',
			'width'         => 728,
			'height'        => 90,
			'location'      => 'test-banner-ad',
			'ad_conditions' => [],
			'provider'      => 'google-publisher',
			'priority'      => 1
		] );

		$this->_mocker = new Mocker();

	}

	/**
	 * TearDown Between Each Test.
	 */
	public function tearDown() {

		unset( $this->pmc_ads );
		unset( $this->pmc_ad_conditionals );
		unset( $this->testing_ads );

		parent::tearDown();
	}

	/**
	 * To prevent all upload files from deletion, since set $ignore_files = true
	 * we override the function and do nothing here.
	 */
	public function remove_added_uploads() {}

	/**
	 * Helper function to create an Advertisement post.
	 *
	 * @param array $ad
	 *
	 * @return integer $post_id
	 */
	protected function _create_ad( $ad = [] ) {

		$defaults['device'] = [ 'Desktop', 'Mobile', 'Tablet' ];
		$defaults['provider'] = DEFAULT_AD_PROVIDER;

		$ad = wp_parse_args( $ad, $defaults );

		$ad_post = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc-ad',
			'post_content' => json_encode( $ad ),
		] );

		update_post_meta( $ad_post->ID, '_ad_location', $ad['location'] );
		update_post_meta( $ad_post->ID, '_ad_provider', $ad['provider'] );

		return $ad_post;
	}

	/**
	 * Update a testing ad's conditions.
	 *
	 * Example:
	 * $this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
	 *     [
	 *         'name'   => 'is_home',
	 *         'result' => true,
	 *         'params' => [],
	 *         'id'     => 1,
	 *     ],
	 * ] );
	 *
	 * @param string $ad_location
	 * @param string $ad_unit_title
	 * @param array $ad_conditions
	 *
	 * @return bool|void
	 */
	protected function _set_ad_conditions( $ad_location = '', $ad_unit_title = '', $ad_conditions = [], $logical_operator = 'or' ) {

		if ( empty( $ad_location ) || empty( $ad_unit_title ) ) {
			return;
		}

		if ( empty( $ad_conditions ) || ! is_array( $ad_conditions ) ) {
			return;
		}

		if ( empty( $this->testing_ads[ $ad_location ] ) ) {
			return;
		}

		if ( empty( $this->testing_ads[ $ad_location ][ $ad_unit_title ] ) ) {
			return;
		}

		if ( ! is_a( $this->testing_ads[ $ad_location ][ $ad_unit_title ], 'WP_Post' ) ) {
			return;
		}

		$ad_post                           = $this->testing_ads[ $ad_location ][ $ad_unit_title ];
		$ad_post_content                   = json_decode( $ad_post->post_content );
		$ad_post_content->ad_conditions    = $ad_conditions;
		$ad_post_content->logical_operator = $logical_operator;
		$ad_post->post_content             = json_encode( $ad_post_content );

		$ad_post_id = wp_update_post( $ad_post );

		if ( 0 === $ad_post_id ) {
			return false;
		} else {
			$this->testing_ads[ $ad_location ][ $ad_unit_title ] = $ad_post;

			return true;
		}
	}

	/**
	 * Assert than an ad unit is displaying in the current context.
	 *
	 * @param string $ad_location The slug of the ad location.
	 * @param string $ad_unit_slug The slug of the ad unit to test for.
	 *
	 * @return null
	 */
	protected function _assert_ad_renders( $ad_location = '', $ad_unit_title = '' ) {

		if ( empty( $ad_location ) || empty( $ad_unit_title ) ) {
			$this->assertTrue( false, 'Missing ad location or ad title for _assert_ad_renders()' );

			return;
		}

		$ads = $this->pmc_ads->get_ads_to_render( $ad_location );

		$this->assertNotEmpty( $ads );
		$this->assertTrue( is_array( $ads ) );

		$ad_titles = wp_list_pluck( $ads, 'title' );
		$this->assertTrue( in_array( $ad_unit_title, $ad_titles ) );
	}

	/**
	 * Verify there are no ads for a given location.
	 *
	 * @param string $ad_location The ad location slug to check within.
	 */
	protected function _assert_ad_does_not_render( $ad_location = '', $ad_unit_title = '' ) {

		if ( empty( $ad_location ) || empty( $ad_unit_title ) ) {
			$this->assertTrue( false, 'Missing ad location or ad title for _assert_ad_does_not_render()' );

			return;
		}

		$ads = $this->pmc_ads->get_ads_to_render( $ad_location );

		$ad_titles = wp_list_pluck( $ads, 'title' );

		$this->assertFalse( in_array( $ad_unit_title, $ad_titles ) );
	}

	/**
	 * @covers ::_init
	 */
	public function test_init() {
		$instance = PMC_Ads::get_instance();

		$this->assertInstanceOf( "PMC_Ads", $instance );

		$filters = array(
			'pmc_global_cheezcap_options' => 'filter_pmc_global_cheezcap_options',
		);

		$actions = array(
			'init'    => 'init',
			'wp_head' => 'render_ad_lighting_wrapper_scripts',
		);

		foreach ( $filters as $key => $value ) {
			$this->assertNotEquals(
				false,
				has_filter( $key, array( $instance, $value ) ),
				sprintf( 'PMC_Ads::_init or action_init failed registering filter "%1$s" to PMC_Ads::%2$s', $key, $value )
			);
		}

		foreach ( $actions as $key => $value ) {
			$this->assertNotEquals(
				false,
				has_filter( $key, array( $instance, $value ) ),
				sprintf( 'PMC_Ads::_init or action_init failed registering action "%1$s" to PMC_Ads::%2$s', $key, $value )
			);
		}
	}

	/**
	 * @covers ::filter_pmc_global_cheezcap_options
	 */
	public function test_has_cheezcap_option() {

		$cheezcap_options = PMC_Ads::get_instance()->filter_pmc_global_cheezcap_options( array() );

		$keys = array( 'pmc_adm_no_ads', 'pmc_adm_ad_lighting_wrapper' );

		$cheezcap_keys = array();
		foreach ( $cheezcap_options as $k => $option ) {
			$cheezcap_keys[] = $option->_key;
		}

		foreach ( $keys as $key ) {
			$this->assertTrue( in_array( $key, $cheezcap_keys ), 'PMC_Ads is missing Cheezcap option: ' . $key );
		}
	}

	/**
	 * @covers ::render_ad_lighting_wrapper_scripts
	 */
	public function test_render_ad_lighting_wrapper_scripts() {

		$scripts_in_question = array(
			'<script src="https://tagan.adlightning.com/penske/op.js" defer></script>',
		);

		// When theme option for ad lighting is disabled (Default value).
		$rendered_scripts = Utility::buffer_and_return( array( $this->pmc_ads, 'render_ad_lighting_wrapper_scripts' ) );

		foreach ( $scripts_in_question as $script ) {
			$this->assertNotContains( $script, $rendered_scripts );
		}

		// When theme option for ad lighting is enabled.
		$this->_mocker->update_cheezcap_option_value( 'pmc_adm_ad_lighting_wrapper', 'enable' );

		$rendered_scripts = Utility::buffer_and_return( array( $this->pmc_ads, 'render_ad_lighting_wrapper_scripts' ) );

		foreach ( $scripts_in_question as $script ) {
			$this->assertContains( $script, $rendered_scripts );
		}

	}

	/**
	 * @covers ::add_provider
	 * @covers ::get_provider
	 * @covers ::get_providers
	 */
	public function test_can_add_providers() {
		$instance = PMC_Ads::get_instance();
		$instance->add_provider( new Google_Publisher_Provider( 'test_key' ) );
		$instance->add_provider( new Site_Served_Provider( 'ss_test_key' ) );

		// We need to test if the provider was actually added
		$this->assertInstanceOf( "Google_Publisher_Provider", $instance->get_provider( 'google-publisher' ) );
		$this->assertInstanceOf( "Site_Served_Provider", $instance->get_provider( 'site-served' ) );

		// Check all providers
		$this->assertArrayHasKey( "google-publisher", $instance->get_providers() );
		$this->assertArrayHasKey( "site-served", $instance->get_providers() );

	}

	/**
	 * @covers ::setup_post_type
	 */
	public function test_has_pmc_ad_post_type() {
		$instance = PMC_Ads::get_instance();
		$instance->setup_post_type();

		$this->assertArrayHasKey( "pmc-ad", get_post_types() );

	}

	/**
	 * @covers ::add_locations
	 */
	public function test_can_add_locations() {
		$instance = PMC_Ads::get_instance();
		$instance->add_locations( array(
			'test-leaderboard'    => 'test-leaderboard',
			'testwidget'          => 'testwidget',
			'test-footer'         => 'test-footer',
			'badtest1',
			1                     => '',
			'test-site-served-ad' => [
				'title'     => 'test-site-served-ad-title',
				'providers' => [ 'site-served' ],
			],
		) );
		$this->assertArrayHasKey( "test-leaderboard", $instance->locations );
		$this->assertArrayHasKey( "testwidget", $instance->locations );
		$this->assertArrayHasKey( "test-footer", $instance->locations );
		$this->assertFalse( array_key_exists( 'badtest1', $instance->locations ) );
		$this->assertFalse( array_key_exists( 1, $instance->locations ) );
		$this->assertArrayHasKey( "test-site-served-ad", $instance->locations );
	}

	/**
	 * @covers ::get_ads
	 * @covers ::render_admin
	 */
	public function test_get_ads() {
		$instance = PMC_Ads::get_instance();

		register_post_type( 'not-pmc-ad', array() );
		$this->factory->post->create_many( 10, array( 'post_type' => 'not-pmc-ad' ) );
		$this->factory->post->create_many( 10, array( 'post_type' => 'pmc-ad' ) );

		$posts_width_ads = $instance->get_ads();
		foreach ( $posts_width_ads as $key => $post ) {

			// Verify only ads of the correct post type are being retrieved.
			$post_type = get_post_type( $post );
			$this->assertTrue(
				$post_type == 'pmc-ad',
				'PMC::get_ads is returning posts that are not of post_type pmc-ad'
			);
		}

		// To check pagination.
		$ad_list = $this->factory->post->create_many( 200, array( 'post_type' => 'pmc-ad' ) );

		// Mock global variable.

		$ads = array();

		$all_ads = $instance->get_ads();

		$ads[0] = $instance->get_ads( false, '', '', array(
			'numberposts' => 10,
		) );

		$ads[1] = $instance->get_ads( false, '', '', array(
			'numberposts' => 10,
			'paged'       => 1,
		) );

		$ads[2] = $instance->get_ads( false, '', '', array(
			'numberposts' => 10,
			'paged'       => 2,
		) );

		$ads[3] = $instance->get_ads( false, '', '', array(
			'numberposts' => 10,
			'paged'       => -1,
		) );

		$cached_ads = array();

		$cached_ads[0] = $instance->get_ads( true, '', '', array(
			'numberposts' => 10,
			'paged'       => 1,
		) );
		$cached_ads[1] = $instance->get_ads( true, '', '', array(
			'numberposts' => 10,
			'paged'       => 2,
		) );

		$this->assertEquals( 160, count( $all_ads ) );
		$this->assertEquals( 10, count( $ads[0] ) );
		$this->assertEquals( $ads[0], $ads[1] );
		$this->assertNotEquals( $ads[2], $ads[1] );
		$this->assertEquals( $ads[3], $ads[1] );
		$this->assertEquals( $cached_ads[0], $ads[1] );
		$this->assertEquals( $cached_ads[1], $ads[2] );

	}

	/**
	 * Test that a proper ad is rendered
	 *
	 * @covers ::get_ads_based_on_role()
	 */
	public function test_get_ads_based_on_role() {

		$u = $this->factory->user->create( array(
			'user_login' => 'admin',
			'user_pass'  => 'admin',
		) );

		$u = wp_set_current_user( $u );

		$u->add_role( 'administrator' );
		$u->add_cap( 'pmc_manage_ads_cap' );
		$u->add_cap( 'pmc_manage_site_served_ads_cap' );
		$u->add_cap( 'pmc_manage_google_publisher_ads_cap' );

		$instance = PMC_Ads::get_instance();

		$all_ads = $instance->get_ads( false, '', '', [
			'numberposts' => 10,
			'paged'       => 1,
		] );

		$ads[0] = $instance->get_ads_based_on_role( [
			'numberposts' => 10,
			'paged'       => 1,
		] );

		$u->remove_role( 'administrator' );
		$u->add_role( 'pmc-audience-marketing' );
		$u->remove_cap( 'pmc_manage_google_publisher_ads_cap' );

		$ads[1] = $instance->get_ads_based_on_role( [
			'numberposts' => 10,
			'paged'       => 1,
		] );

		$u->remove_role( 'pmc-audience-marketing' );
		$u->remove_cap( 'pmc_manage_site_served_ads_cap' );

		$u->add_role( 'pmc-adops-manager' );
		$u->add_cap( 'pmc_manage_google_publisher_ads_cap' );

		$ads[2] = $instance->get_ads_based_on_role( [
			'numberposts' => 10,
			'paged'       => 1,
		] );

		$this->assertEquals( $all_ads, $ads[0], 'Administrator role did not fetch all ads' );
		$this->assertEquals( 4, count( $all_ads ), 'Get ads failed to fetch all ads' );
		$this->assertEquals( 4, count( $ads[0] ), 'Administrator failed to fetch all ads' );
		$this->assertEquals( 2, count( $ads[1] ), 'Site Served provider ads not fetched' );
		$this->assertEquals( 2, count( $ads[2] ), 'Google publisher ads not fetched' );

		foreach( $ads[0] as $ad ) {
			// Verify only ads of the correct post type are being retrieved.
			$post_type = get_post_type( $ad );
			$this->assertTrue( 'pmc-ad' === $post_type, 'PMC::get_ads is returning posts that are not of post_type pmc-ad' );

		}

		foreach( $ads[1] as $ad ) {
			// Verify only ads of the correct post type are being retrieved.
			$provider =  $ad->post_content['provider'];
			$this->assertTrue( 'site-served' === $provider, 'Failed to fetch site-served provider for pmc-audience-marketing role' );

		}

		foreach( $ads[2] as $ad ) {
			// Verify only ads of the correct provider are being retrieved based on role.
			$provider =  $ad->post_content['provider'];
			$this->assertTrue( 'google-publisher' === $provider, 'Failed to fetch google-publisher provider for pmc-adops-manager role' );

		}

		$u->remove_cap( 'pmc_manage_ads_cap' );
		$u->remove_cap( 'pmc_manage_site_served_ads_cap' );
		$u->remove_cap( 'pmc_manage_google_publisher_ads_cap' );
		$u->remove_role( 'administrator' );
		wp_delete_user( $u->ID );
	}

	/**
	 * @covers ::get_current_applicable_device
	 */
	public function test_devices() {
		$_SERVER['HTTP_X_MOBILE_CLASS'] = 'smart';
		$devices                        = [ 'Mobile' ];

		if ( defined( 'PMC_IS_VIP_GO_SITE' ) && PMC_IS_VIP_GO_SITE ) {
			$this->assertEquals( 'Mobile', PMC_Ads::get_current_applicable_device( $devices ) );
		} else {
			$this->assertFalse( PMC_Ads::get_current_applicable_device( $devices ) );
		}
	}

	/**
	 * Tests is_home() condition.
	 *
	 * @covers ::get_ads_to_render
	 * @covers ::fetch_ads
	 */
	public function test_is_home() {

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_home',
				'result' => true,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->_set_ad_conditions( 'test-ad', 'test-ad-title', [
			[
				'name'   => 'is_home',
				'result' => true,
				'params' => [],
				'id'     => 2,
			],
		] );

		$this->go_to( '/' );

		$this->assertTrue( is_home() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
		$this->_assert_ad_renders( 'test-ad', 'test-ad-title' );
	}

	/**
	 * Tests 'not' is_home() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_not_is_home() {

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_home',
				'result' => false,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->_set_ad_conditions( 'test-ad', 'test-ad-title', [
			[
				'name'   => 'is_home',
				'result' => false,
				'params' => [],
				'id'     => 2,
			],
		] );

		$this->go_to( '/' );

		$this->assertTrue( is_home() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );
		$this->_assert_ad_does_not_render( 'test-ad', 'test-ad-title' );

		$this->go_to( '/?s=test' );

		$this->assertTrue( is_search() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
		$this->_assert_ad_renders( 'test-ad', 'test-ad-title' );

	}

	/**
	 * Tests is_single() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_is_single() {

		$post = $this->factory->post->create();

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_single',
				'result' => true,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->_set_ad_conditions( 'test-ad', 'test-ad-title', [
			[
				'name'   => 'is_single',
				'result' => true,
				'params' => [],
				'id'     => 2,
			],
		] );

		$this->go_to( '/?p=' . $post );

		$this->assertTrue( is_single() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
		$this->_assert_ad_renders( 'test-ad', 'test-ad-title' );

		$this->go_to( '/' );

		$this->assertTrue( is_home() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );
		$this->_assert_ad_does_not_render( 'test-ad', 'test-ad-title' );
	}

	/**
	 * Tests 'not' is_single() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_not_is_single() {

		$post = $this->factory->post->create();

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_single',
				'result' => false,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->_set_ad_conditions( 'test-ad', 'test-ad-title', [
			[
				'name'   => 'is_single',
				'result' => false,
				'params' => [],
				'id'     => 2,
			],
		] );

		$this->go_to( '/?p=' . $post );

		$this->assertTrue( is_single() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );
		$this->_assert_ad_does_not_render( 'test-ad', 'test-ad-title' );

		$this->go_to( '/' );

		$this->assertTrue( is_home() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
		$this->_assert_ad_renders( 'test-ad', 'test-ad-title' );
	}

	/**
	 * Tests is_category() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_is_category() {

		$category_id = $this->factory->category->create( [ 'slug' => 'test-category' ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_category',
				'result' => true,
				'params' => [ 'test-category' ],
				'id'     => 1,
			],
		] );

		$this->go_to( get_term_link( $category_id, 'category' ) );

		$this->assertTrue( is_category() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/' );

		$this->assertTrue( is_home() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Tests 'not' is_category() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_not_is_category() {

		$category_id = $this->factory->category->create( [ 'slug' => 'test-category' ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_category',
				'result' => false,
				'params' => [ 'test-category' ],
				'id'     => 1,
			],
		] );

		$this->go_to( get_term_link( $category_id, 'category' ) );

		$this->assertTrue( is_category() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/' );

		$this->assertTrue( is_home() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test has_category() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_has_category() {

		$post        = $this->factory->post->create();
		$category_id = $this->factory->category->create( [ 'slug' => 'test-category' ] );
		wp_set_post_categories( $post, [ $category_id ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'has_category',
				'result' => true,
				'params' => [ 'test-category' ],
				'id'     => 1,
			],
		] );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( is_single() );
		$this->assertTrue( has_category( $category_id, $post ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );

		wp_remove_object_terms( $post, $category_id, 'category' );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( is_single() );
		$this->assertFalse( has_category( $category_id, $post ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

	}

	/**
	 * Test 'not' has_category() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_not_has_category() {

		$post        = $this->factory->post->create();
		$category_id = $this->factory->category->create( [ 'slug' => 'test-category' ] );
		wp_set_post_categories( $post, [ $category_id ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'has_category',
				'result' => false,
				'params' => [ 'test-category' ],
				'id'     => 1,
			],
		] );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( is_single() );
		$this->assertTrue( has_category( $category_id, $post ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$new_post        = $this->factory->post->create();
		$new_category_id = $this->factory->category->create( [ 'slug' => 'test2-category' ] );
		wp_set_post_categories( $post, [ $new_category_id ] );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( is_single() );
		$this->assertFalse( has_category( $category_id, $new_post ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test has_term() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_has_term() {

		$post    = $this->factory->post->create();
		$term_id = $this->factory->category->create( [ 'slug' => 'test-category' ] );
		wp_set_post_categories( $post, [ $term_id ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'has_term',
				'result' => true,
				'params' => [ 'test-category', 'category' ],
				'id'     => 1,
			],
		] );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( is_single() );
		$this->assertTrue( has_term( $term_id, 'category', $post ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );

		$new_post = $this->factory->post->create();

		$this->go_to( get_permalink( $new_post ) );

		$this->assertTrue( is_single() );
		$this->assertFalse( has_term( $term_id, 'category', $new_post ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' has_term() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_not_has_term() {

		$post    = $this->factory->post->create();
		$term_id = $this->factory->category->create( [ 'slug' => 'test-category' ] );
		wp_set_post_categories( $post, [ $term_id ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'has_term',
				'result' => false,
				'params' => [ 'test-category', 'category' ],
				'id'     => 1,
			],
		] );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( is_single() );
		$this->assertTrue( has_term( $term_id, 'category', $post ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		wp_remove_object_terms( $post, $term_id, 'category' );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( is_single() );
		$this->assertFalse( has_term( $term_id, 'category', $post ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test has_tag() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_has_tag() {

		$test_tag_slug = 'test-tag-term';

		$post   = $this->factory->post->create();
		$tag_id = $this->factory->tag->create( [ 'slug' => $test_tag_slug ] );
		wp_set_post_tags( $post, [ $test_tag_slug ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'has_tag',
				'result' => true,
				'params' => [ $test_tag_slug ],
				'id'     => 1,
			],
		] );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( is_single() );
		$this->assertTrue( has_tag( $test_tag_slug, $post ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );

		wp_remove_object_terms( $post, $tag_id, 'post_tag' );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( is_single() );
		$this->assertFalse( has_tag( $test_tag_slug, $post ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' has_tag() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_not_has_tag() {

		$test_tag_slug = 'test-tag-term';

		$post = $this->factory->post->create();
		$this->factory->tag->create( [ 'slug' => $test_tag_slug ] );
		wp_set_post_tags( $post, [ $test_tag_slug ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'has_tag',
				'result' => false,
				'params' => [ $test_tag_slug ],
				'id'     => 1,
			],
		] );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( is_single() );
		$this->assertTrue( has_tag( $test_tag_slug, $post ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$new_post          = $this->factory->post->create();
		$new_test_tag_slug = 'new-tag';
		$this->factory->tag->create( [ 'slug' => $new_test_tag_slug ] );
		wp_set_post_tags( $post, [ $new_test_tag_slug ] );

		$this->go_to( get_permalink( $new_post ) );

		$this->assertTrue( is_single() );
		$this->assertFalse( has_tag( $test_tag_slug, $new_post ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );

	}

	/**
	 * Test in_vertical() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_in_vertical() {

		$test_taxonomy_slug = 'vertical';
		$test_term_slug     = 'test-vertical-term';

		register_taxonomy( $test_taxonomy_slug, get_post_types() );

		$post = $this->factory->post->create();
		$this->factory->term->create_and_get( [
			'taxonomy' => $test_taxonomy_slug,
			'name'     => $test_term_slug
		] );
		wp_set_post_terms( $post, [ $test_term_slug ], $test_taxonomy_slug );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'in_vertical',
				'result' => true,
				'params' => [ $test_term_slug ],
				'id'     => 1,
			],
		] );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( PMC::in_vertical( $test_term_slug ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );

		wp_remove_object_terms( $post, $test_term_slug, $test_taxonomy_slug );

		$this->go_to( get_permalink( $post ) );

		$this->assertFalse( PMC::in_vertical( $test_term_slug ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' in_vertical() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_not_in_vertical() {

		$test_taxonomy_slug = 'vertical';
		$test_term_slug     = 'test-vertical-term';

		register_taxonomy( $test_taxonomy_slug, get_post_types() );

		$post = $this->factory->post->create();
		$this->factory->term->create_and_get( [
			'taxonomy' => $test_taxonomy_slug,
			'name'     => $test_term_slug
		] );
		wp_set_post_terms( $post, [ $test_term_slug ], $test_taxonomy_slug );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'in_vertical',
				'result' => false,
				'params' => [ $test_term_slug ],
				'id'     => 1,
			],
		] );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( PMC::in_vertical( $test_term_slug ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$new_post = $this->factory->post->create();

		$this->go_to( get_permalink( $new_post ) );

		$this->assertFalse( PMC::in_vertical( $test_term_slug ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test is_country() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_is_country() {

		$post = $this->factory->post->create();

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_country',
				'result' => true,
				'params' => [ 'us' ],
				'id'     => 1,
			],
		] );


		$this->go_to( '/' );

		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
		/*Could not test other countries because of the static varibale
		inside the function that would'nt allow to change the country code.
		WPCOM_Geo_Uniques::get_user_location();
		*/
	}

	/**
	 * Test not is_country() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_not_is_country() {

		$post = $this->factory->post->create();

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_country',
				'result' => true,
				'params' => [ 'ca' ],
				'id'     => 1,
			],
		] );


		$this->go_to( '/' );

		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );
		/*Could not test other countries because of the static varibale
		inside the function that would'nt allow to change the country code.
		WPCOM_Geo_Uniques::get_user_location();
		*/
	}

	/**
	 * Test is_404() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_is_404() {

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_404',
				'result' => true,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->go_to( '/?p=1111030332' );

		$this->assertTrue( is_404() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' is_404() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_not_is_404() {

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_404',
				'result' => false,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->go_to( '/?p=5234534543' );

		$this->assertTrue( is_404() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test is_archive() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_is_archive() {

		$category_slug = 'test-category-term';

		$post        = $this->factory->post->create();
		$category_id = $this->factory->category->create( [ 'slug' => $category_slug ] );
		wp_set_post_categories( $post, [ $category_id ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_archive',
				'result' => true,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->go_to( get_term_link( $category_id, 'category' ) );

		$this->assertTrue( is_archive() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' is_archive() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_not_is_archive() {

		$category_slug = 'test-category-term';

		$post        = $this->factory->post->create();
		$category_id = $this->factory->category->create( [ 'slug' => $category_slug ] );
		wp_set_post_categories( $post, [ $category_id ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_archive',
				'result' => false,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->go_to( get_term_link( $category_id, 'category' ) );

		$this->assertTrue( is_archive() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test is_author() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_is_author() {

		$user_id = $this->factory->user->create();
		$this->factory->post->create( [ 'post_author' => $user_id ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_author',
				'result' => true,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->go_to( get_author_posts_url( $user_id ) );

		$this->assertTrue( is_author() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' is_author() condition.
	 *
	 * @covers ::get_ads_to_render
	 */
	function test_not_is_author() {

		$user_id = $this->factory->user->create();
		$this->factory->post->create( [ 'post_author' => $user_id ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_author',
				'result' => false,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->go_to( get_author_posts_url( $user_id ) );

		$this->assertTrue( is_author() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test is_page() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_is_page() {

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_page',
				'result' => true,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->go_to( '/' );

		$this->assertFalse( is_page() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/?page_id=' . $this->factory->post->create( [ 'post_type' => 'page' ] ) );

		$this->assertTrue( is_page() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' is_page() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_not_is_page() {

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_page',
				'result' => false,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->go_to( '/?page_id=' . $this->factory->post->create( [ 'post_type' => 'page', ] ) );

		$this->assertTrue( is_page() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/' );

		$this->assertFalse( is_page() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test is_paginated() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_is_paginated() {

		$this->factory->post->create_many( 2 );
		update_option( 'posts_per_page', 1 );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_paginated',
				'result' => true,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->go_to( '/' );

		$this->assertFalse( is_paged() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/page/2' );

		$this->assertTrue( is_paged() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' is_paginated() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_not_is_paginated() {

		$posts = $this->factory->post->create_many( 2 );
		update_option( 'posts_per_page', 1 );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_paginated',
				'result' => false,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->go_to( '/page/2' );

		$this->assertTrue( is_paged() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/' );

		$this->assertFalse( is_paged() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test is_post_type_archive() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_is_post_type_archive() {

		$test_post_type_name = 'test-post-type';

		register_post_type( $test_post_type_name, [ 'public' => true, 'has_archive' => true ] );
		$this->factory()->post->create_many( 5, [ 'post_type' => $test_post_type_name ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_post_type_archive',
				'result' => true,
				'params' => [ $test_post_type_name ],
				'id'     => 1,
			],
		] );

		$this->go_to( '/' );

		$this->assertFalse( is_post_type_archive() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/?post_type=' . $test_post_type_name );

		$this->assertTrue( is_post_type_archive() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' is_post_type_archive() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_not_is_post_type_archive() {

		$test_post_type_name = 'test-post-type';

		register_post_type( $test_post_type_name, [ 'public' => true, 'has_archive' => true ] );
		$this->factory()->post->create_many( 5, [ 'post_type' => $test_post_type_name ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_post_type_archive',
				'result' => false,
				'params' => [ $test_post_type_name ],
				'id'     => 1,
			],
		] );

		$this->go_to( '/?post_type=' . $test_post_type_name );

		$this->assertTrue( is_post_type_archive() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/' );

		$this->assertFalse( is_post_type_archive() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test is_search() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_is_search() {

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_search',
				'result' => true,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->go_to( '/' );

		$this->assertFalse( is_search() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/?s=test' );

		$this->assertTrue( is_search() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' is_search() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_not_is_search() {

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_search',
				'result' => false,
				'params' => [],
				'id'     => 1,
			],
		] );

		$this->go_to( '/?s=test' );

		$this->assertTrue( is_search() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/' );

		$this->assertFalse( is_search() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test is_singular() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_is_singular() {

		$post_id = $this->factory->post->create();

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_singular',
				'result' => true,
				'params' => [ 'post' ],
				'id'     => 1,
			],
		] );

		$this->go_to( '/' );

		$this->assertFalse( is_singular( 'post' ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( get_permalink( $post_id ) );

		$this->assertTrue( is_singular( 'post' ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' is_singular() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_not_is_singular() {

		$post_id = $this->factory->post->create();

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_singular',
				'result' => false,
				'params' => [ 'post' ],
				'id'     => 1,
			],
		] );

		$this->go_to( get_permalink( $post_id ) );

		$this->assertTrue( is_singular( 'post' ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/' );

		$this->assertFalse( is_singular( 'post' ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test is_tag() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_is_tag() {

		$test_tag_slug = 'test-tag-term';

		$this->factory->tag->create( [ 'slug' => $test_tag_slug ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_tag',
				'result' => true,
				'params' => [ $test_tag_slug ],
				'id'     => 1,
			],
		] );

		$this->go_to( '/' );

		$this->assertFalse( is_tag( $test_tag_slug ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/?tag=' . $test_tag_slug );

		$this->assertTrue( is_tag( $test_tag_slug ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' is_tag() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_not_is_tag() {

		$test_tag_slug = 'test-tag-term';

		$this->factory->tag->create( [ 'slug' => $test_tag_slug ] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_tag',
				'result' => false,
				'params' => [ $test_tag_slug ],
				'id'     => 1,
			],
		] );

		$this->go_to( '/?tag=' . $test_tag_slug );

		$this->assertTrue( is_tag( $test_tag_slug ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/' );

		$this->assertFalse( is_tag( $test_tag_slug ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test is_tax() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_is_tax() {

		$test_taxonomy_slug = 'test-taxonomy';
		$test_term_slug     = 'test-taxonomy-term';

		register_taxonomy( $test_taxonomy_slug, get_post_types() );

		$term = $this->factory->term->create_and_get( [
			'taxonomy' => $test_taxonomy_slug,
			'name'     => $test_term_slug
		] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_tax',
				'result' => true,
				'params' => [ $test_taxonomy_slug, $test_term_slug ],
				'id'     => 1,
			],
		] );

		$this->go_to( '/' );

		$this->assertFalse( is_tax( $test_taxonomy_slug, $test_term_slug ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/?' . $test_taxonomy_slug . '=' . $test_term_slug );

		$this->assertTrue( is_tax( $test_taxonomy_slug, $test_term_slug ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' is_tax() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_not_is_tax() {

		$test_taxonomy_slug = 'test-taxonomy';
		$test_term_slug     = 'test-taxonomy-term';

		register_taxonomy( $test_taxonomy_slug, get_post_types() );

		$term = $this->factory->term->create_and_get( [
			'taxonomy' => $test_taxonomy_slug,
			'name'     => $test_term_slug
		] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_tax',
				'result' => false,
				'params' => [ $test_taxonomy_slug, $test_term_slug ],
				'id'     => 1,
			],
		] );

		$this->go_to( '/?' . $test_taxonomy_slug . '=' . $test_term_slug );

		$this->assertTrue( is_tax( $test_taxonomy_slug, $test_term_slug ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/' );

		$this->assertFalse( is_tax( $test_taxonomy_slug, $test_term_slug ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test is_url_match() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_is_url_match() {

		$url_pattern = 'sodales-nunc';

		$post = $this->factory()->post->create_and_get( [
			'post_title' => 'Nam sit amet lectus sodales nunc blandit ultrices vel sit'
		] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_url_match',
				'result' => true,
				'params' => [ $url_pattern ],
				'id'     => 1,
			],
		] );

		$this->go_to( '/' );

		$this->assertFalse( $this->pmc_ad_conditionals->is_url_match( $url_pattern ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( $this->pmc_ad_conditionals->is_url_match( $url_pattern ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' is_url_match() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_not_is_url_match() {

		$url_pattern = 'pellentesque-bibendum-libero';

		$post = $this->factory()->post->create_and_get( [
			'post_title' => 'Duis fermentum id tortor a aliquet. Pellentesque bibendum libero non.'
		] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_url_match',
				'result' => false,
				'params' => [ $url_pattern ],
				'id'     => 1,
			],
		] );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( $this->pmc_ad_conditionals->is_url_match( $url_pattern ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/' );

		$this->assertFalse( $this->pmc_ad_conditionals->is_url_match( $url_pattern ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test is_vertical() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_is_vertical() {

		$test_term_slug = 'test-vertical-term';

		$term = $this->factory->term->create_and_get( [
			'taxonomy' => 'vertical',
			'name'     => $test_term_slug
		] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_vertical',
				'result' => true,
				'params' => [ $test_term_slug ],
				'id'     => 1,
			],
		] );

		$this->go_to( '/' );

		$this->assertFalse( PMC::is_vertical( $test_term_slug ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/?vertical=' . $test_term_slug );

		$this->assertTrue( PMC::is_vertical( $test_term_slug ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'not' is_vertical() ad condition
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_not_is_vertical() {

		$test_term_slug = 'test-vertical-term';

		$term = $this->factory->term->create_and_get( [
			'taxonomy' => 'vertical',
			'name'     => $test_term_slug
		] );

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_vertical',
				'result' => false,
				'params' => [ $test_term_slug ],
				'id'     => 1,
			],
		] );

		$this->go_to( '/?vertical=' . $test_term_slug );

		$this->assertTrue( PMC::is_vertical( $test_term_slug ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		$this->go_to( '/' );

		$this->assertFalse( PMC::is_vertical( $test_term_slug ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );
	}

	/**
	 * Test 'OR' and 'AND' ad conditions
	 *
	 *
	 * @covers ::get_ads_to_render
	 */
	public function test_or_and_condition() {

		$test_term_slug = 'test-vertical-term';

		$term = $this->factory->term->create_and_get( [
			'taxonomy' => 'vertical',
			'name'     => $test_term_slug
		] );

		//Case 1: is_vertical('test-vertical-term') & is_tax('vertical')
		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_vertical',
				'result' => true,
				'params' => [ $test_term_slug ],
				'id'     => 1,
			],
			[
				'name'   => 'is_tax',
				'result' => true,
				'params' => [ 'vertical' ],
				'id'     => 1,
			],
		], 'and' );

		$this->go_to( '/?vertical=' . $test_term_slug );

		$this->assertTrue( PMC::is_vertical( $test_term_slug ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );

		//Case 2: on home page => is_vertical('test-vertical-term') & is_tax('vertical') should fail
		$this->go_to( '/' );

		$this->assertFalse( PMC::is_vertical( $test_term_slug ) );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		//Case 3: is_vertical('test-vertical-term123') & is_tax('vertical') but on is_vertical('test-vertical-term') page
		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_vertical',
				'result' => true,
				'params' => [ $test_term_slug . "123" ],
				'id'     => 1,
			],
			[
				'name'   => 'is_tax',
				'result' => true,
				'params' => [ 'vertical' ],
				'id'     => 1,
			],
		], 'and' );

		$this->go_to( '/?vertical=' . $test_term_slug );

		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

		//Case 4: is_single() or is_home()  on is_single() page
		$post = $this->factory->post->create();

		$this->_set_ad_conditions( 'top-leaderboard', 'leaderboard-ad', [
			[
				'name'   => 'is_single',
				'result' => true,
				'params' => [],
				'id'     => 1,
			],
			[
				'name'   => 'is_vertical',
				'result' => true,
				'params' => [ $test_term_slug ],
				'id'     => 1,
			],
		], 'or' );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( is_single() );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );

		//Case 5: is_single() or is_home()  on is_vertical() page
		$this->go_to( '/?vertical=' . $test_term_slug );

		$this->assertTrue( PMC::is_vertical( $test_term_slug ) );
		$this->_assert_ad_renders( 'top-leaderboard', 'leaderboard-ad' );

		//Case 5: is_single() or is_home()  on is_home() page
		$this->go_to( '/' );

		$this->assertTrue( is_home() );
		$this->_assert_ad_does_not_render( 'top-leaderboard', 'leaderboard-ad' );

	}

	/**
	 * Test that a proper ad is rendered
	 * @covers \pmc_adm_render_ads
	 */
	public function test_pmc_adm_render_ads() {

		// Test that a site-served ad is rendered
		$ad_html = pmc_adm_render_ads( 'test-ad', '', false );

		$this->assertContains( "<img", $ad_html, 'Cannot find site-served ad' );
		$this->assertContains( "http://testsite.com/", $ad_html, 'Cannot find site-served ad' );
		$this->assertContains( "http://testsite.com/test-image.jpg", $ad_html, 'Cannot find site-served ad' );
		$this->assertContains( 'class="pmc-adm-site-served "', $ad_html, 'Cannot find site-served ad' );

		// Test that a google-publisher ad is rendered
		$ad_html = pmc_adm_render_ads( 'top-leaderboard', '', false );

		$this->assertContains( 'class="pmc-adm-goog-pub-div ', $ad_html, 'Cannot find google publisher ad' );
		$this->assertContains( 'class="adma google-publisher"', $ad_html, 'Cannot find google publisher ad' );

		// There are 2 ads defined for this same location.
		// Test that ad that has a higher priority is rendred that is a google-publisher ad is rendered
		$ad_html = pmc_adm_render_ads( 'test-banner-ad', '', false );

		$this->assertContains( 'class="pmc-adm-goog-pub-div ', $ad_html, 'Should render google publisher ad' );
		$this->assertContains( 'class="adma google-publisher"', $ad_html, 'Should render google publisher ad' );

		// There are 2 ads defined for this same location.
		// Test that ad that has a higher priority is rendred that is a google-publisher ad is rendered
		$ad_html = pmc_adm_render_ads( 'test-banner-ad', '', false, 'google-publisher' );

		$this->assertContains( 'class="pmc-adm-goog-pub-div ', $ad_html, 'Should render google publisher ad' );
		$this->assertContains( 'class="adma google-publisher"', $ad_html, 'Should render google publisher ad' );

		// There are 2 ads defined for this same location.
		// Test that site-served ad is rendered since we have passed the provider explicitly
		$ad_html = pmc_adm_render_ads( 'test-banner-ad', '', false, 'site-served' );

		$this->assertContains( "<img", $ad_html, 'Should render site served ad' );
		$this->assertContains( "http://testsite.com/", $ad_html, 'Should render site served ad' );
		$this->assertContains( "http://testsite.com/test-image.jpg", $ad_html, 'Should render site served ad' );
		$this->assertContains( 'class="pmc-adm-site-served "', $ad_html, 'Should render site served ad' );

	}

	/**
	 * @covers ::get_provider
	 */
	public function test_default_provider() {
		$provider = PMC_Ads::get_instance()->get_provider( false );
		$this->assertNotEmpty( $provider );
	}

	/**
	 * @covers ::action_wp_enqueue_scripts
	 */
	public function test_action_wp_enqueue_scripts() {

		Utility::buffer_and_return( 'do_action', [ 'wp_enqueue_scripts' ] );
		$scripts = wp_scripts();
		$deps = $scripts->registered['pmc-adm-loader']->deps;
		$this->assertNotContains( 'pmc-intersection-observer-polyfill', $deps ); // Assert not have waypoint dependecy.

		wp_deregister_script( 'pmc-adm-loader' ); // To check after lazy load enabled.

		PMC_Cheezcap::get_instance()->set_option( 'pmc_enable_disable_lazy_load', 'enabled' );
		Utility::buffer_and_return( 'do_action', [ 'wp_enqueue_scripts' ] );
		$scripts = wp_scripts();
		$deps = $scripts->registered['pmc-adm-loader']->deps;
		$this->assertContains( 'pmc-intersection-observer-polyfill', $deps ); // Assert have waypoint dependency.
		$this->assertTrue( wp_script_is( 'pmc-adm-loader', 'enqueued' ) );

	}

	/**
	 * @covers ::setup_assets
	 */
	public function test_setup_assets() {

		do_action( 'admin_enqueue_scripts', 'tools_page_ad-manager' );

		$output = Utility::simulate_wp_script_render();
		$expected = 'var pmcadm_floating_preroll_location = ["floating-video-preroll-ad"];';

		$this->assertContains( $expected, $output );
	}

	/**
	 * @covers ::add_index_exchange_wrapper
	 */
	public function test_add_index_exchange_wrapper() {
		//Testing No wrapper tag added to the page
		$output = Utility::buffer_and_return( array( $this->pmc_ads, 'add_index_exchange_wrapper' ) );
		$this->assertNotContains( 'pmc_add_index_wrapper_script', $output );

		//Testing wrapper tag added to the page
		$this->_mocker->update_cheezcap_option_value( 'pmc_hb_index_exchange_wrapper_tag', '123456' );
		$output = Utility::buffer_and_return( array( $this->pmc_ads, 'add_index_exchange_wrapper' ) );
		$this->assertContains( 'pmc_add_index_wrapper_script', $output );

	}

	/**
	 * @covers ::load_ias_script_tag
	 */
	public function test_load_ias_script_tag() {

		$script = 'https://cdn.adsafeprotected.com/iasPET.1.js';

		// When theme option for IAS script is disabled (Default value).
		$html = Utility::buffer_and_return( array( $this->pmc_ads, 'load_ias_script_tag' ) );
		$this->assertNotContains( $script, $html );

		// When theme option for ias is enabled.
		$this->_mocker->update_cheezcap_option_value( 'pmc_adm_ias_script', 'enable' );

		$html = Utility::buffer_and_return( array( $this->pmc_ads, 'load_ias_script_tag' ) );

		$this->assertContains( $script, $html );

		//Set back to default
		PMC_Cheezcap::get_instance()->set_option( 'pmc_adm_ias_script', 'disable' );

	}

	/**
	 * @covers ::action_wp_enqueue_scripts
	 */
	public function test_ias_script() {

		Utility::buffer_and_return( 'do_action', [ 'wp_enqueue_scripts' ] );
		$this->assertFalse( wp_script_is( 'pmc-adm-ias', 'enqueued' ) );

		PMC_Cheezcap::get_instance()->set_option( 'pmc_adm_ias_script', 'enable' );

		Utility::buffer_and_return( 'do_action', [ 'wp_enqueue_scripts' ] );
		$this->assertTrue( wp_script_is( 'pmc-adm-ias', 'enqueued' ) );

		//Set back to default
		PMC_Cheezcap::get_instance()->set_option( 'pmc_adm_ias_script', 'disable' );
	}

	/**
	 * @covers ::add_ad_rizer_js_tag
	 */
	public function test_add_ad_rizer_js_tag() {

		$script = '//run.crtx.info/track.min.js';

		$html = Utility::buffer_and_return( array( $this->pmc_ads, 'add_ad_rizer_js_tag' ) );

		$this->assertNotContains( $script, $html );


		add_filter( 'pmc_adm_ad_rizer_domain', function () {
			return 'abc.com';
		} );

		$html = Utility::buffer_and_return( array( $this->pmc_ads, 'add_ad_rizer_js_tag' ) );

		$this->assertContains( $script, $html );
		$this->assertContains( 'abc.com', $html );

	}

}