<?php
/**
 * @group pmc-adm
 * @group pmc-adm-dynamic-zone
 */
class Test_Class_PMC_Ad_Dynamic_Zone extends WP_UnitTestCase {

	function setUp() {
		// to speeed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;
		parent::setUp();
		// do not report on warning to avoid unit test reporting on: Cannot modify header information - headers already sent by'
		error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * @covers PMC_Ad_Dynamic_Zone::_init
	 * @covers PMC_Ad_Dynamic_Zone::action_init
	 */
	public function test_init(){
		$instance = PMC_Ad_Dynamic_Zone::get_instance();

		$filters = array(
			'init' => 'action_init',
			'pmc_adm_google_publisher_slot' => 'filter_pmc_adm_google_publisher_slot',
			'pmc_ad_provider_fields' => 'filter_pmc_ad_provider_fields',
		);

		foreach ( $filters as $key => $value ) {
			$this->assertNotEquals(
				false,
				has_filter( $key, array( $instance, $value ) ) ,
				sprintf('PMC_Ad_Dynamic_Zone::_init or action_init failed registering filter/action "%1$s" to PMC_Ad_Dynamic_Zone::%2$s', $key, $value )
			);
		}

	}

	/**
	 * @covers PMC_Ad_Dynamic_Zone::filter_pmc_ad_provider_fields
	 */
	public function test_filter_pmc_ad_provider_fields(){

		$fields = array(
			'dynamic_slot' => array(),
		);

		$provider_fields = apply_filters('pmc_ad_provider_fields', $fields, 'test' );

		$this->assertArrayHasKey(
			'dynamic_slot',
			$provider_fields,
			'PMC_Ad_Dynamic_Zone::filter_pmc_ad_provider_fields is missing dynamic_slot key'
		);

		$this->assertArrayHasKey(
			'options',
			$provider_fields['dynamic_slot'],
			'PMC_Ad_Dynamic_Zone::filter_pmc_ad_provider_fields failed to obtain dynamic_slot options'
		);

		$this->assertArrayHasKey(
			'default',
			$provider_fields['dynamic_slot'],
			'PMC_Ad_Dynamic_Zone::filter_pmc_ad_provider_fields failed to obtain default dynamic_slot options'
		);

		return $provider_fields;

	}

	/**
	 * @covers PMC_Ad_Dynamic_Zone::filter_pmc_adm_google_publisher_slot
	 */
	function test_ad_slot_adjustment(){

		$slot = sprintf( '/%s/%s/%s', 'key', 'sitename', 'zone' );

		$ad = array(
			'dynamic_slot' 	=> '{key}/{site}/{zone}',
			'key'			=> 'test-key',
			'sitename'		=> 'test-site',
			'zone'			=> 'test-zone'
		);

		$publisher_slot = apply_filters(
			'pmc_adm_google_publisher_slot',
			$slot,
			$ad
		);

		$this->assertEquals(
			'test-key/test-site/test-zone',
			$publisher_slot,
			'Failed to adjust ad slot'
		);

		// Now we should expect the original slot string
		unset($ad['dynamic_slot']);

		$publisher_slot = apply_filters(
			'pmc_adm_google_publisher_slot',
			$slot,
			$ad
		);

		$this->assertEquals(
			$slot,
			$publisher_slot,
			'Failed to adjust ad slot'
		);


	}

	/**
	 * @covers PMC_Ad_Dynamic_Zone::register
	 * @covers PMC_Ad_Dynamic_Zone::_init
	 * @covers PMC_Ad_Dynamic_Zone::register
	 */
	public function test_register(){

		$register_callbacks = array(
			'PMC'					=>	'get_pagezone',
			'PMC_Ad_Dynamic_Zone' 	=>	'get_term',
			'PMC_Ad_Dynamic_Zone' 	=>  'get_vertical',
			'PMC_Ad_Dynamic_Zone'	=> 	'get_category',
			'PMC_Ad_Dynamic_Zone' 	=>  'get_loginstatus',
		);

		foreach( $register_callbacks as $class => $method ){

				$this->assertTrue(
				method_exists($class, $method),
				'Class ' . $class . ' does not have method ' . $method
			);

		}

	}

	/**
	 * @covers PMC_Ad_Dynamic_Zone::get_term
	 */
	public function test_get_term(){

		$instance = PMC_Ad_Dynamic_Zone::get_instance();

		// Create our test taxonomy
		$terms = array('film', 'tv', 'digital', 'awards','video','dirt','jobs');
		$tax = 'vertical';
		register_taxonomy( $tax, 'post' );

		// Test the terms don't exist yet
		foreach( $terms as $term ){
			$this->go_to( "/?vertical=" . $term );
			$term_slug = $instance->get_term();
			$this->assertEquals('', $term_slug );
		}

		// Create our test post
		$post_id = $this->factory->post->create();

		// Assign some terms to the new taxonomy
		wp_set_post_terms( $post_id, $terms , $tax );

		// Test the terms
		foreach( $terms as $term ){
			$this->go_to( "/?vertical=" . $term );
			$term_slug = $instance->get_term();
			$this->assertEquals($term, $term_slug );
		}

		// clean up
		unset($GLOBALS['wp_taxonomies'][$tax]);

	}

	/**
	 * @covers PMC_Ad_Dynamic_Zone::get_category
	 */
	public function test_get_category(){
		$instance = PMC_Ad_Dynamic_Zone::get_instance();

		$post_id = $this->factory->post->create();
		$this->go_to( "/?p=" . $post_id );
		$category_slug = $instance->get_category();

		$this->assertEquals( 'uncategorized', $category_slug);

		$category_id = $this->factory->category->create( [ 'slug' => 'top-category' ] );

		$args = [
			'slug'   => 'sub-category',
			'parent' => $category_id,
		];

		$sub_category_id = $this->factory->category->create( $args );

		// Asserting Sub Category page.
		$this->go_to( get_term_link( $sub_category_id, 'category' ) );
		$this->assertTrue( is_category() );
		$category_slug = $instance->get_category();
		$this->assertEquals( 'top-category', $category_slug );

		// Asserting Top Category page.
		$this->go_to( get_term_link( $category_id, 'category' ) );
		$this->assertTrue( is_category() );
		$category_slug = $instance->get_category();
		$this->assertEquals( 'top-category', $category_slug );

	}

	/**
	 * @covers PMC_Ad_Dynamic_Zone::get_vertical
	 */
	public function test_get_vertical(){

		$instance = PMC_Ad_Dynamic_Zone::get_instance();

		// Create our test taxonomy
		$terms = array('term1','term2','term3');
		$tax = 'vertical';
		register_taxonomy( $tax, 'post' );

		// Test the terms don't exist yet
		foreach( $terms as $term ){
			wp_insert_term( $term, 'vertical' );
			// fake queried object
			$GLOBALS['wp_query']->queried_object = get_term_by( 'name', $term, 'vertical' );
			$term_slug = $instance->get_vertical();
			$this->assertEquals($term, $term_slug, sprintf( "%s != %s, dynamic zone cannot detect vertical taxonomy", $term, $term_slug ) );
		}

		// Create our test post
		$post_id = $this->factory->post->create();

		// Assign some terms to the new taxonomy
		wp_set_post_terms( $post_id, $terms , $tax );
		$GLOBALS['wp_query']->queried_object = null;
		$GLOBALS['post'] = get_post( $post_id );

		$vertical_slug = $instance->get_vertical();
		$this->assertTrue(in_array($vertical_slug,$terms),'dynamic zone cannot detect vertical assigned to post');

	}

	/**
	 * @covers PMC_Ad_Dynamic_Zone::get_dynamic_slots
	 */
	public function test_dynamic_slots(){
		$slots = array(
			'{key}/{site}/{zone}'            => '{key}/{site}/{zone}',
			'{key}/{site}/{zone}/{vertical}' => '{key}/{site}/{zone}/{vertical}',
			'{key}/{site}/{vertical}'        => '{key}/{site}/{vertical}',
			'{key}/{site}/{vertical}/{zone}' => '{key}/{site}/{vertical}/{zone}',
			'{key}/{site}/{uri-part}'        => '{key}/{site}/{uri-part}',
			'{key}/{site}/{zone}/{category}' => '{key}/{site}/{zone}/{category}',
			'{key}/{site}/{category}'        => '{key}/{site}/{category}',
			'{key}/{site}/{category}/{zone}' => '{key}/{site}/{category}/{zone}',
		);

		$instance = PMC_Ad_Dynamic_Zone::get_instance();
		$dynamic_slots = $instance->get_dynamic_slots();

		$this->assertArraySubset($slots, $dynamic_slots);
	}

	/**
	 * @covers PMC_Ad_Dynamic_Zone::get_dynamic_slot_default
	 */
	public function test_default_slot(){
		$instance = PMC_Ad_Dynamic_Zone::get_instance();
		$default_slot = $instance->get_dynamic_slot_default();

		$this->assertEquals('{key}/{site}/{zone}', $default_slot);
	}

	/**
	 * @covers PMC_Ad_Dynamic_Zone::filter_pmc_adm_google_publisher_slot
	 */
	public function test_filter_pmc_adm_google_publisher_slot() {

		//mocking ad object
		$ad = [
			'width'        => '728',
			'height'       => '90',
			'status'       => 'active',
			'device'       => '728',
			'location'     => 'leaderboard',
			'sitename'     => 'Variety',
			'zone'         => 'home/leaderboard',
			'dynamic_slot' => '{key}/{site}/{zone}/{category}',
		];

		//Asserting dynamic ad slot format '{key}/{site}/{zone}/{category}'
		$post_id = $this->factory->post->create();
		$this->go_to( '/?p=' . $post_id );
		$category = get_the_category();
		$this->assertEquals( 'uncategorized', $category[0]->slug );

		$filtered_slot = apply_filters( 'pmc_adm_google_publisher_slot', '', $ad );
		$expected_slot = sprintf( '%s/%s/%s', $ad['sitename'], $ad['zone'], $category[0]->slug );

		$this->assertContains( $expected_slot, $filtered_slot );

		//Asserting dynamic ad slot format '{key}/{site}/{category}/{zone}'
		$ad['dynamic_slot'] = '{key}/{site}/{category}/{zone}';
		$filtered_slot      = apply_filters( 'pmc_adm_google_publisher_slot', '', $ad );
		$expected_slot      = sprintf( '%s/%s/%s', $ad['sitename'], $category[0]->slug, $ad['zone'] );

		$this->assertContains( $expected_slot, $filtered_slot );

		//Asserting dynamic ad slot format '{key}/{site}/{category}'
		$ad['dynamic_slot'] = '{key}/{site}/{category}';
		$filtered_slot      = apply_filters( 'pmc_adm_google_publisher_slot', '', $ad );
		$expected_slot      = sprintf( '%s/%s', $ad['sitename'], $category[0]->slug );

		$this->assertContains( $expected_slot, $filtered_slot );

	}

}