<?php
/**
 * Unit test cases for PMC_Ads_Contextual_Player_Ad class
 *
 * @author jignesh Nakrani <jignesh.nakrani@rtcamp.com>
 *
 * @package pmc-adm
 */

use PMC\Unit_Test\Mock\Mocker;
use PMC\Unit_Test\Utility;

/**
 * @group pmc-adm
 * @coversDefaultClass PMC_Ads_Contextual_Player_Ad
 */
class Test_PMC_Ads_Contextual_Player_Ad extends WP_UnitTestCase {


	/**
	 * @var PMC_Ads_Contextual_Player_Ad
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

		$instances       = Utility::get_hidden_static_property( 'PMC_Ads_Contextual_Player_Ad', '_instance' );
		$this->_instance = $instances['PMC_Ads_Contextual_Player_Ad'];

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

		Utility::invoke_hidden_method( $this->_instance, '__construct' );

		$hooks = [
			'pmc_inject_content_paragraphs' => 'inject_contextual_player_ad_markup',
			'pmc_adm_locations'             => 'add_contextual_player_ad_location',
			'the_content'                   => 'inject_contextual_player_at_bottom',
		];

		foreach ( $hooks as $key => $value ) {

			$this->assertNotEquals(
				false,
				has_filter( $key, [ $this->_instance, $value, ] ),
				sprintf( 'PMC_Ads_Contextual_Player_Ad::__construct failed registering filter "%1$s" to PMC_Ads_Contextual_Player_Ad::%2$s', $key, $value )
			);

		}

	}

	/**
	 * @covers ::inject_contextual_player_ad_markup
	 * @covers ::clean_up_content
	 * @covers ::get_contextual_player_ad
	 * @covers ::_should_contextual_player_render
	 */
	public function test_inject_contextual_player_ad_markup_condtion_check() {

		$paragraphs = [];

		$this->assertEquals( $paragraphs, $this->_instance->inject_contextual_player_ad_markup( $paragraphs ) );

		// When post have less content.
		$this->_mocker->mock_global_wp_post( $this );

		$this->assertEquals( $paragraphs, $this->_instance->inject_contextual_player_ad_markup( $paragraphs ) );

		$this->_mocker->simulate_amp_request();

		$this->assertEquals( $paragraphs, $this->_instance->inject_contextual_player_ad_markup( $paragraphs ) );

		// When post don't have any jwplayer shortcode.
		$content = 'Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.' . PHP_EOL;

		$post = $this->_mocker->mock_global_wp_post( $this,
			[
				'post_content' => str_repeat( $content, 10 ),
			]
		);

		$output = $this->_instance->inject_contextual_player_ad_markup( $paragraphs );
		$this->assertEquals( $output, $paragraphs );

		$this->_mocker->restore_global_wp_post();
	}

	/**
	 * @covers ::inject_contextual_player_at_bottom
	 * @covers ::get_contextual_player_ad_markup
	 * @covers ::_should_contextual_player_render
	 * @covers ::clean_up_content
	 */
	public function test_inject_contextual_player_at_bottom() {

		$this->setup_ad( 'bottom' );

		// When post don't have any jwplayer shortcode.
		$content = 'Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.' . PHP_EOL . PHP_EOL;

		$post = $this->_mocker->mock_global_wp_post( $this,
			[
				'post_content' => str_repeat( $content, 10 ),
			]
		);

		$output = $this->_instance->inject_contextual_player_at_bottom( $post->post_content );

		$this->AssertNotEmpty( $output );
		$this->assertContains( 'https:\/\/cdn.jwplayer.com\/v2\/playlists\/XYZ123?semantic=true&backfill=true&search=__CONTEXTUAL__', $output );
		$this->assertContains( 'https://content.jwplatform.com/libraries/ABC123.js', $output );

		add_post_meta( $post->ID, '_pmc_featured_video_override_data', '[jwplayer xyz987-abc987]' );

		$output = $this->_instance->inject_contextual_player_at_bottom( $post->post_content );
		$this->assertNotContains( 'https:\/\/cdn.jwplayer.com\/v2\/playlists\/XYZ123?semantic=true&backfill=true&search=__CONTEXTUAL__', $output );

		$this->_mocker->restore_global_wp_post();
	}

	/**
	 * @covers ::inject_contextual_player_ad_markup
	 * @covers ::get_contextual_player_ad_markup
	 * @covers ::_should_contextual_player_render
	 * @covers ::clean_up_content
	 */
	public function test_inject_contextual_player_ad_markup_at_mid() {

		$this->setup_ad( 'mid' );

		// When post don't have any jwplayer shortcode.
		$content = 'Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.' . PHP_EOL . PHP_EOL;

		$post = $this->_mocker->mock_global_wp_post( $this,
			[
				'post_content' => str_repeat( $content, 10 ),
			]
		);

		$output = $this->_instance->inject_contextual_player_ad_markup( [] );

		$this->AssertNotEmpty( $output[4][0] );
		$this->assertContains( 'https:\/\/cdn.jwplayer.com\/v2\/playlists\/XYZ123?semantic=true&backfill=true&search=__CONTEXTUAL__', $output[4][0] );
		$this->assertContains( 'https://content.jwplatform.com/libraries/ABC123.js', $output[4][0] );

		add_post_meta( $post->ID, '_pmc_featured_video_override_data', '[jwplayer xyz987-abc987]' );

		$output = $this->_instance->inject_contextual_player_ad_markup();
		$this->AssertEmpty( $output[2][0] );

		$this->_mocker->restore_global_wp_post();
	}

	/**
	 * @covers ::get_contextual_player_ad_markup
	 * @covers ::get_contextual_player_ad
	 */
	public function test_get_contextual_player_ad_markup() {

		// Empty ads test
		$ads = $this->_instance->get_contextual_player_ad_markup();
		$this->assertNull( $ads );

		$this->setup_ad( 'bottom' );

		// When post don't have any jwplayer shortcode.
		$content = 'Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.' . PHP_EOL;

		$post = $this->factory->post->create(
			[
				'post_content' => str_repeat( $content, 10 ),
			]);
		$this->_mocker->mock_global_wp_query(
			[
				'is_single'         => true,
				'queried_object_id' => $post,
				'queried_object'    => get_post( $post ),
			]
		);
		$GLOBALS['post'] = get_post( $post );

		$output = $this->_instance->get_contextual_player_ad_markup();
		$this->assertContains( 'https:\/\/cdn.jwplayer.com\/v2\/playlists\/XYZ123?semantic=true&backfill=true&search=__CONTEXTUAL__', $output );
		$this->assertContains( 'https://content.jwplatform.com/libraries/ABC123.js', $output );

		$this->_mocker->restore_global_wp_query();
	}

	/**
	 * @covers ::add_contextual_player_ad_location
	 */
	public function test_add_contextual_player_ad_location() {

		$locations = apply_filters( 'pmc_adm_locations', [] );

		$expected['contextual-matching-player-ad'] = [
			'title'     => esc_html__( 'Contextual Matching Player Ad', 'pmc-adm' ),
			'providers' => [ 'google-publisher' ],
		];

		$this->assertArraySubset( $expected, $locations );
	}

	/**
	 * Generates dummy contextual player ad config
	 *
	 * @param string $position Contextual player position in post
	 *
	 * @return mixed
	 */
	public function setup_ad( $position = 'mid' ) {

		$instance = PMC_Ads::get_instance();
		$instance->add_provider( new Google_Publisher_Provider( 'unittest' ) );
		$instance->add_locations(
			[
				'contextual-matching-player-ad' => 'Contextual Matching Player Ad',
			]
		);

		$ad = [
			'provider'                   => 'google-publisher',
			'device'                     => [ 'Desktop', 'Mobile', 'Tablet' ],
			'slot-type'                  => 'oop',
			'zone'                       => 'unittest',
			'sitename'                   => 'unittest',
			'ad-width'                   => '[[1,1]]',
			'dynamic_slot'               => 'unittest',
			'width'                      => 1,
			'height'                     => 1,
			'location'                   => 'contextual-matching-player-ad',
			'div-id'                     => 'unittest',
			'contextual-player-id'       => 'ABC123',
			'playlist-id'                => 'XYZ123',
			'contextual-player-position' => $position,
		];

		$p = $this->factory->post->create(
			[
				'post_type'    => 'pmc-ad',
				'post_content' => json_encode( $ad ),
			]
		);

		update_post_meta( $p, '_ad_location', 'contextual-matching-player-ad' );

		return $p;
	}


	/**
	 * @covers ::is_floating_player_enabled
	 */
	public function test_is_floating_player_enabled() {

		$this->assertFalse( $this->_instance->is_floating_player_enabled() );

		update_option( sprintf( 'cap_%s-enabled', 'pmc-floating-video' ), 1 );

		// Test with a pmc-gallery type post
		$this->_mocker->mock_global_wp_post( $this );

		Utility::simulate_is_mobile();

		add_filter( 'pmc_floating_video_mobile', '__return_true' );

		$this->assertTrue( $this->_instance->is_floating_player_enabled() );

		$this->_mocker->restore_global_wp_post();

	}
}
