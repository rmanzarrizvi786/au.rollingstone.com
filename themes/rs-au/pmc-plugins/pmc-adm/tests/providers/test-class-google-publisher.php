<?php
/**
 * Unit test cases for Google_Publisher_Provider class
 *
 * @author  Dhaval Parekh <dhaval.parekh@rtcamp.com>
 *
 * @package pmc-adm
 */

use PMC\Unit_Test\Utility;
use \PMC\Unit_Test\Mock\Mocker;

/**
 * @group pmc-adm
 * @coversDefaultClass Google_Publisher_Provider
 */
class Test_Class_Google_Publisher_Providers extends WP_UnitTestCase {

	/**
	 * @var Google_Publisher_Provider
	 */
	protected $_instance = false;

	/**
	 * Setup between each test.
	 */
	function setUp() {

		// do not report on warning to avoid unit test reporting on:
		// Cannot modify header information - headers already sent by ..
		error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR ); // // @codingStandardsIgnoreLine
		// Ensure that the PMC ADs class caches are not placed in persistent storage.
		// We're effectively telling the class not to cache anything during testing.
		// This way, we can create new ad units in tests and expect to always receive
		// those same ad units (not the cached ads from the first test).
		wp_cache_add_non_persistent_groups( PMC_Ads::cache_group );

		// to speeed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;
		$this->_mocker      = new Mocker();

		$instances       = Utility::get_hidden_static_property( 'PMC_Ads', '_instance' );
		$this->_instance = $instances['PMC_Ads']->get_provider( 'google-publisher' );

		parent::setUp();
	}

	/**
	 * TearDown Between Each Test.
	 */
	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * To prevent all upload files from deletion, since set $ignore_files = true
	 * we override the function and do nothing here.
	 */
	public function remove_added_uploads() {
	}

	/**
	 * @covers ::get_keywords
	 */
	public function test_get_keywords() {

		$self     = $this;
		$instance = new \Google_Publisher_Provider( 'unit-test' );

		add_filter( 'pmc_adm_custom_keywords_taxonomies', function ( $keys ) use ( $self ) {
			$self->filter_called = true;

			return $keys;
		} );

		$p        = $this->factory->post->create();
		$permlink = get_permalink( $p );
		$this->go_to( wp_parse_url( $permlink, PHP_URL_PATH ) );
		$self->filter_called = false;
		$keywords            = $instance->get_keywords();
		$this->assertTrue( $self->filter_called, 'expecting filter to run pmc_adm_custom_keywords_taxonomies' );

		unset( $GLOBALS['post'] );
		$this->assertTrue( is_single() );
		$this->assertEmpty( $GLOBALS['post'] );

		$self->filter_called = false;
		$keywords            = $instance->get_keywords();
		$this->assertFalse( $self->filter_called, ' unexpected filter pmc_adm_custom_keywords_taxonomies' );
	}

	/**
	 * @covers ::get_topics
	 */
	public function test_get_topics() {

		$instance = new \Google_Publisher_Provider( 'unit-test' );

		register_taxonomy( 'editorial', [ 'post', 'pmc_top_video', 'pmc-gallery', 'pmc_list' ], [
			'public'  => true,
			'show_ui' => true,
		] );

		$term = $this->factory()->term->create_and_get( [ 'taxonomy' => 'editorial' ] );
		$post = $this->_mocker->mock_global_wp_post( $this, array() );
		wp_set_post_terms( $post->ID, array( $term->term_id ), 'editorial', false );

		$keywords = $instance->get_topics( 2 );
		$expected = [ $term->slug ];

		$this->assertEqualSets( $expected, $keywords );

		$this->_mocker->restore_global_wp_post();

		$keywords = $instance->get_topics( 2 );
		$this->assertEqualSets( [], $keywords );
	}

	/**
	 * @covers ::reorder_adlist
	 */
	public function test_reorder_adlist() {

		$ad_list['default'] = array(
			array(
				'targeting'    => array(),
				'slot'         => 'test/slot1',
				'id'           => 'testid1',
				'location'     => 'mid-article',
				'adunit-order' => '15',
			),
			array(
				'targeting'    => array(),
				'slot'         => 'test/slot2',
				'id'           => 'testid2',
				'location'     => 'leaderboard',
				'adunit-order' => '1',
			),
			array(
				'targeting'    => array(),
				'slot'         => 'test/slot3',
				'id'           => 'testid3',
				'location'     => 'responsive-skin-ad',
				'adunit-order' => '2',
			),

		);

		$instance = new \Google_Publisher_Provider( 'unit-test' );
		$result   = $instance->reorder_adlist( array() );

		$this->assertEmpty( $result );
		$result = $instance->reorder_adlist( $ad_list );
		$this->assertEquals( 'leaderboard', $result['default'][0]['location'] );
		$this->assertEquals( 'responsive-skin-ad', $result['default'][1]['location'] );
		$this->assertEquals( 'mid-article', $result['default'][2]['location'] );

	}

	/**
	 * @covers ::prepare_ad_data
	 * @covers ::prepare_ad_settings
	 */
	public function test_prepare_ad_data() {
		$mock_ad_config[0] = [
			'width'          => '720',
			'height'         => '90',
			'status'         => 'Active',
			'ad_conditions'  => [],
			'targeting_data' => [
				[
					'key'   => 'pos',
					'value' => 'top',
				],
				[
					'key'   => 'pos',
					'value' => 'atf',
				],
			],
		];

		$instance       = new \Google_Publisher_Provider( 'unit-test' );
		$result         = $instance->prepare_ad_settings( $mock_ad_config );
		$targeting_keys = $result['ad_list']['default'][0]['targeting'];
		$expected       = [
			'pos' => [
				'top',
				'atf',
			],
		];

		$this->assertEqualSets( $expected, $targeting_keys );
	}

	/**
	 * @covers ::render_ad()
	 */
	public function test_render_ad() {

		// Mock Ad.
		$ad_data = [
			'width'            => 780,
			'height'           => 90,
			'priority'         => 10,
			'slot-type'        => 'normal',
			'provider'         => 'google-publisher',
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

		$return_output = $this->_instance->render_ad( $ad_data );

		$render_output = Utility::buffer_and_return( [ $this->_instance, 'render_ad' ], [ $ad_data, true ] );

		$this->assertContains( '<div id="leaderboard-div-boom-', $return_output );
		$this->assertContains( 'adw-780 adh-90', $return_output );

		$this->assertContains( '<div id="leaderboard-div-boom-', $render_output );
		$this->assertContains( 'adw-780 adh-90', $render_output );

	}

	/**
	 * @covers ::prepare_ad_settings
	 */
	public function test_targetting_key_ci() {
		$post = $this->factory->post->create();

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( is_single() );

		// Mock Ad.
		$mock_ad_config[0] = [
			'width'          => '720',
			'height'         => '90',
			'status'         => 'Active',
			'ad_conditions'  => [],
			'targeting_data' => [
				[
					'key'   => 'pos',
					'value' => 'top',
				],
				[
					'key'   => 'pos',
					'value' => 'atf',
				],
			],
		];

		$result       = $this->_instance->prepare_ad_settings( $mock_ad_config );
		$exp_ci_value = sprintf('%s-%s', esc_html( get_bloginfo( 'name' ) ), $post );

		$this->assertEquals( $exp_ci_value, $result['ad_targetings']['ci'] );

	}

	/**
	 * ::action_wp_enqueue_scripts
	 */
	public function test_action_wp_enqueue_scripts() {

		// Need to unset Boomrang ad-provider to enqueue GPT.js script
		$instances = Utility::get_hidden_static_property( 'PMC_Ads', '_instance' );
		$providers = $instances['PMC_Ads']->get_providers();
		unset( $providers['boomerang'] );
		Utility::set_and_get_hidden_property( $instances['PMC_Ads'], '_providers', $providers );

		wp_dequeue_script( 'pmc-async-adm-gpt' );
		Utility::buffer_and_return( 'do_action', [ 'wp_enqueue_scripts', '' ] );
		$this->assertTrue( wp_script_is( 'pmc-async-adm-gpt', 'enqueued' ) );

	}

	/**
	 * ::action_wp_enqueue_scripts
	 */
	public function test_action_wp_enqueue_scripts_skip() {

		wp_dequeue_script( 'pmc-async-adm-gpt' );
		add_filter( 'pmc_adm_load_google_gpt_script_js', '__return_false' );
		Utility::buffer_and_return( 'do_action', [ 'wp_enqueue_scripts', '' ] );
		$this->assertFalse( wp_script_is( 'pmc-async-adm-gpt', 'enqueued' ) );

	}

}
