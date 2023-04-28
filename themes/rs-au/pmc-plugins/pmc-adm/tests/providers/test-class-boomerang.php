<?php
/**
 * Unit test cases for Boomerang_Provider class
 *
 * @author  Dhaval Parekh <dhaval.parekh@rtcamp.com>
 *
 * @package pmc-adm
 */

use PMC\Unit_Test\Mock\Mocker;
use PMC\Unit_Test\Utility;

/**
 * @group pmc-adm
 * @coversDefaultClass Boomerang_Provider
 */
class Test_Boomerang_Provider extends \WP_UnitTestCase {

	/**
	 * @var Boomerang_Provider
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
		$this->_instance = $instances['PMC_Ads']->get_provider( 'boomerang' );

		$this->_mocker = new Mocker();
	}

	/**
	 * To prevent all upload files from deletion, since set $ignore_files = true
	 * we override the function and do nothing here
	 */
	public function remove_added_uploads() {
	}

	/**
	 * @covers ::get_admin_templates
	 */
	public function test_get_admin_templates() {

		$this->assertEquals(
			Utility::get_hidden_property( $this->_instance, '_admin_templates' ),
			$this->_instance->get_admin_templates()
		);

	}

	/**
	 * @covers ::include_assets
	 */
	public function test_include_assets() {

		$this->assertEquals(
			10,
			has_action( 'wp_head', [ $this->_instance, 'load_boomerang_scripts' ] )
		);
	}

	/**
	 * Test case are working fine, but some how it's breaking in pipeline.
	 *
	 * @covers ::_get_primary_term()
	 */
	public function test_get_primary_term() {

		$this->assertFalse( Utility::invoke_hidden_method( $this->_instance, '_get_primary_term', [
			false,
			'category',
		] ) );

		$this->assertFalse( Utility::invoke_hidden_method( $this->_instance, '_get_primary_term', [
			'empty_string',
			'category',
		] ) );

		// Mock Post.
		$parent_category = $this->factory()->category->create_and_get(
			[
				'slug' => 'parent-term',
			]
		);

		$child_category = $this->factory()->category->create_and_get(
			[
				'slug'   => 'child-term',
				'parent' => $parent_category->term_id,
			]
		);

		$args = ['post_category' => [ $parent_category->term_id, $child_category->term_id ] ];
		$post = $this->_mocker->mock_global_wp_post( $this, $args );

		// Clear cache because `PMC_Primary_Taxonomy` is using cache.
		$this->flush_cache();

		// Assertion.
		$output = Utility::invoke_hidden_method( $this->_instance, '_get_primary_term', [ $post, 'category' ] );
		$this->assertEquals( $parent_category->slug, $output->slug );

		// Restore post.
		$this->_mocker->restore_global_wp_post();
	}

	/**
	 * @cover ::prepare_boomerang_global_settings
	 */
	public function test_prepare_boomerang_global_settings_for_home_page() {

		$script_url = Utility::get_hidden_property( $this->_instance, '_script_url' );
		Utility::set_and_get_hidden_property( $this->_instance, '_script_url', false );

		$this->assertFalse( $this->_instance->prepare_boomerang_global_settings() );

		Utility::set_and_get_hidden_property( $this->_instance, '_script_url', $script_url );

		// Mock Global wp_query.
		$this->_mocker->mock_global_wp_query( [ 'is_home' => true ] );

		// Assertion.
		$output = $this->_instance->prepare_boomerang_global_settings();

		$this->assertEquals( $output['vertical'], 'home' );
		$this->assertEquals( $output['targeting_data'], [
			'ci' => 'HOM',
			'cn' => 'homepage',
			'pt' => 'home',
		] );

		// Restore WP_Query.
		$this->_mocker->restore_global_wp_query();
	}

	/**
	 * @cover ::prepare_boomerang_global_settings
	 */
	public function test_prepare_boomerang_global_settings_for_archive_page() {

		// Mock global WP_Query
		$term = $this->factory()->category->create_and_get( [
			'slug' => 'parent-term',
		] );

		$this->_mocker->mock_global_wp_query( [
			'is_archive'        => true,
			'queried_object_id' => $term->term_id,
			'queried_object'    => $term,
		] );

		// Assertion.
		$output = $this->_instance->prepare_boomerang_global_settings();

		$this->assertEquals( $output['vertical'], $term->slug );
		$this->assertEquals( $output['targeting_data'], [
			'ci' => 'category-' . $term->term_id,
			'pt' => 'landing',
		] );

		// Archive page of child term
		$child_term = $this->factory()->category->create_and_get( [
			'slug'   => 'child-term',
			'parent' => $term->term_id,
		] );

		$this->_mocker->mock_global_wp_query( [
			'is_archive'        => true,
			'queried_object_id' => $child_term->term_id,
			'queried_object'    => $child_term,
		] );

		// Assertion.
		$output = $this->_instance->prepare_boomerang_global_settings();

		$this->assertEquals( $output['vertical'], $term->slug );
		$this->assertEquals( $output['targeting_data'], [
			'ci' => 'category-' . $term->term_id,
			'pt' => 'landing',
		] );

		// Restore WP_Query.
		$this->_mocker->restore_global_wp_query();
	}

	/**
	 * @cover ::prepare_boomerang_global_settings
	 */
	public function test_prepare_boomerang_global_settings_for_single_page() {

		// Mock global WP_Post
		$term = $this->factory()->category->create_and_get( [
			'slug' => 'term',
		] );

		$tags   = [];
		$tags[] = $this->factory()->tag->create_and_get();
		$tags[] = $this->factory()->tag->create_and_get();

		$post = $this->_mocker->mock_global_wp_post( $this, [] );

		wp_set_object_terms( $post->ID, [ $term->slug ], 'category' );
		wp_set_object_terms( $post->ID, wp_list_pluck( $tags, 'slug' ), 'post_tag' );

		$this->flush_cache();

		// Assertion.
		$output = $this->_instance->prepare_boomerang_global_settings();

		$this->assertEquals( $output['targeting_data'], [
			'pt'  => 'article',
			'ci'  => 'ART-' . $post->ID,
			'tag' => wp_list_pluck( $tags, 'slug' ),
		] );

		// For gallery
		$gallery = $this->_mocker->mock_global_wp_post( $this, [ 'post_type' => 'pmc-gallery' ] );

		wp_set_object_terms( $gallery->ID, [ $term->slug ], 'category' );
		wp_set_object_terms( $gallery->ID, wp_list_pluck( $tags, 'slug' ), 'post_tag' );

		// Assertion.
		$output = $this->_instance->prepare_boomerang_global_settings();

		$this->assertEquals( $output['targeting_data'], [
			'pt'  => 'slideshow',
			'ci'  => 'ART-' . $gallery->ID,
			'tag' => wp_list_pluck( $tags, 'slug' ),
		] );

		// For Video
		register_post_type( 'video' );

		$video = $this->_mocker->mock_global_wp_post( $this, [ 'post_type' => 'video' ] );

		wp_set_object_terms( $video->ID, [ $term->slug ], 'category' );
		wp_set_object_terms( $video->ID, wp_list_pluck( $tags, 'slug' ), 'post_tag' );

		// Assertion.
		$output = $this->_instance->prepare_boomerang_global_settings();

		$this->assertEquals( $output['targeting_data'], [
			'pt'  => $video->post_type,
			'ci'  => 'ART-' . $video->ID,
			'tag' => wp_list_pluck( $tags, 'slug' ),
		] );

		unregister_post_type( 'video' );

		// Restore WP_Post.
		$this->_mocker->restore_global_wp_post();
	}

	/**
	 * @cover ::prepare_boomerang_global_settings
	 */
	public function test_prepare_boomerang_global_settings_for_other_page() {

		$this->_mocker->mock_global_wp_query( [
			'is_author' => true,
		] );

		// Assertion.
		$output = $this->_instance->prepare_boomerang_global_settings();

		$this->assertEquals( $output['vertical'], 'ros' );

		$this->_mocker->restore_global_wp_query();

	}

	/**
	 * This is in failed group because, all test cases related to AMP is not working.
	 * We have ticket for that to investigate : WI-688
	 *
	 * @cover ::prepare_boomerang_global_settings
	 */
	public function test_prepare_boomerang_global_settings_for_amp_page() {

		$post = $this->_mocker->mock_global_wp_post( $this, [] );

		$this->_mocker->simulate_amp_request();

		// Assertion.
		$output = $this->_instance->prepare_boomerang_global_settings();

		$this->assertNotEmpty( $output['targeting_data']['plat'] );
		$this->assertEquals( 'amp', $output['targeting_data']['plat'] );

		$this->_mocker->restore_global_wp_post();
	}

	/**
	 * @covers ::load_boomerang_scripts
	 */
	public function test_load_boomerang_scripts() {

		$script_url = Utility::get_hidden_property( $this->_instance, '_script_url' );
		Utility::set_and_get_hidden_property( $this->_instance, '_script_url', false );

		$this->assertFalse( $this->_instance->load_boomerang_scripts() );

		Utility::set_and_get_hidden_property( $this->_instance, '_script_url', $script_url );

		$this->_mocker->mock_global_wp_query( [ 'is_home' => true ] );

		$output = Utility::buffer_and_return( [ $this->_instance, 'load_boomerang_scripts' ] );

		$this->assertContains( "blogherads.setConf( 'vertical', 'home' );", $output );
		$this->assertContains( "blogherads.setTargeting( 'ci', 'HOM' );", $output );
		$this->assertContains( "blogherads.setTargeting( 'cn', 'homepage' )", $output );
		$this->assertContains( "blogherads.setTargeting( 'pt', 'home' )", $output );

		$this->_mocker->restore_global_wp_query();
	}

	/**
	 * @covers ::wp_enqueue_scripts
	 */
	public function test_wp_enqueue_scripts() {

		$script_url = Utility::get_hidden_property( $this->_instance, '_script_url' );
		Utility::set_and_get_hidden_property( $this->_instance, '_script_url', false );

		Utility::buffer_and_return( 'do_action', [ 'wp_enqueue_scripts' ] );

		$this->assertFalse( wp_script_is( 'pmc-async-adm-boomerang-theme-script' ) );
		$this->assertFalse( wp_script_is( 'pmc-async-adm-boomerang-script' ) );

		Utility::set_and_get_hidden_property( $this->_instance, '_script_url', $script_url );

		Utility::buffer_and_return( 'do_action', [ 'wp_enqueue_scripts' ] );

		$this->assertTrue( wp_script_is( 'pmc-async-adm-boomerang-theme-script' ) );
		$this->assertTrue( wp_script_is( 'pmc-async-adm-boomerang-script' ) );

	}

	/**
	 * @covers ::_prepare_ad_data
	 */
	public function test_prepare_ad_data() {

		$this->assertFalse( Utility::invoke_hidden_method( $this->_instance, '_prepare_ad_data', [ false ] ) );

		$this->assertEquals( 'empty_string', Utility::invoke_hidden_method( $this->_instance, '_prepare_ad_data', [ 'empty_string' ] ) );

		$ad_data = [
			'width'            => 780,
			'height'           => 90,
			'priority'         => 10,
			'slot-type'        => 'normal',
			'provider'         => 'boomerang',
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
			'targeting_data'   => [
				[
					'key'   => 'pos',
					'value' => 'top',
				],
			],
		];

		$output = Utility::invoke_hidden_method( $this->_instance, '_prepare_ad_data', [ $ad_data ] );

		$this->assertContains( 'leaderboard-div-boom-uid', $output['div-id'] );
		$this->assertEquals( '1234', $output['key'] );
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
			'provider'         => 'boomerang',
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
			'targeting_data'   => [
				[
					'key'   => 'pos',
					'value' => 'top',
				],
			],
		];

		$script_url = Utility::get_hidden_property( $this->_instance, '_script_url' );
		Utility::set_and_get_hidden_property( $this->_instance, '_script_url', false );

		$this->assertFalse( $this->_instance->render_ad( $ad_data ) );

		Utility::set_and_get_hidden_property( $this->_instance, '_script_url', $script_url );

		$this->_mocker->mock_global_wp_query( [ 'is_home' => true ] );

		// Assertion.
		Utility::buffer_and_return( [ $this->_instance, 'load_boomerang_scripts' ] );

		$output = $this->_instance->render_ad( $ad_data );

		$this->assertContains( 'leaderboard-div-boom', $output );
		$this->assertContains( "defineSlot( 'banner', 'leaderboard-div-boom-uid", $output );
		$this->assertContains( ".setTargeting( 'pos', 'top' )", $output );

		$output = Utility::buffer_and_return( [ $this->_instance, 'render_ad' ], [ $ad_data, true ] );

		$this->assertContains( 'leaderboard-div-boom', $output );
		$this->assertContains( "defineSlot( 'banner', 'leaderboard-div-boom-uid", $output );
		$this->assertContains( ".setTargeting( 'pos', 'top' )", $output );

		// Restore WP_Query.
		$this->_mocker->restore_global_wp_query();
	}

	/**
	 * @covers ::render_ad()
	 *
	 * @throws ErrorException
	 */
	public function test_render_ad_for_adhesion_ad_unit() {

		// Mock Ad.
		$ad_data = [
			'width'            => 780,
			'height'           => 90,
			'priority'         => 10,
			'slot-type'        => 'normal',
			'provider'         => 'boomerang',
			'status'           => 'Active',
			'start'            => '',
			'end'              => '',
			'css-class'        => '',
			'is-ad-rotatable'  => '',
			'ad-group'         => 'default',
			'duration'         => 8,
			'timegap'          => 24,
			'device'           => 'Desktop',
			'title'            => 'B Bottom Adhesion ad unit',
			'location'         => 'desktop-bottom-sticky-ad',
			'is_lazy_load'     => '',
			'adunit-order'     => '',
			'logical_operator' => 'or',
			'ad-display-type'  => 'banner',
			'sitename'         => 'example.org',
			'div-id'           => 'leaderboard-div-boom',
			'ad-width'         => '[780,90]',
			'targeting_data'   => [
				[
					'key'   => 'pos',
					'value' => 'top',
				],
			],
		];

		$this->_mocker->mock_global_wp_post( $this );

		// Assertion.
		Utility::buffer_and_return( [ $this->_instance, 'load_boomerang_scripts' ] );

		$output = $this->_instance->render_ad( $ad_data );

		$this->assertContains( ".defineSlot( 'banner', 'leaderboard-div-boom-uid", $output );
		$this->assertContains( '{"pos":"top"} )', $output );

		$this->_mocker->restore_global_wp_post();
	}

}
