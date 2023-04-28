<?php
/**
 * Unit test cases for Video_Featured
 *
 * @author  Muhammad Muhsin <muhammad.muhsin@rtcamp.com>
 *
 * @package pmc-top-videos
 */

namespace PMC\Top_Videos_V2\Tests;

use \PMC\Top_Videos_V2\Video_Featured;
use \PMC\Unit_Test\Mock\Mocker;
use PMC\Unit_Test\Utility;

require_once( __DIR__ . '/class-base.php' );

/**
 * @coversDefaultClass \PMC\Top_Videos_V2\Video_Featured
 */
class Test_Video_Featured extends Base {

	/**
	 * @var \Deadline\Inc\Widgets\Video_Featured
	 */
	protected $_instance;

	/**
	 * @var \PMC\Unit_Test\Mock\Mocker
	 */
	protected $_mocker;

	function setUp() {

		// to speed up unit test, we bypass files scanning on upload folder.
		self::$ignore_files = true;

		parent::setUp();

		$this->_instance = new Video_Featured();

		$this->_mocker = new Mocker();

	}

	/**
	 * @covers ::__construct
	 */
	public function test__construct() {

		Utility::invoke_hidden_method( $this->_instance, '__construct' );

		$this->assertObjectHasAttribute( 'id_base', $this->_instance );

		$this->assertEquals( 'video_featured', $this->_instance->id_base );
	}

	/**
	 * @covers ::widget
	 */
	public function test_widget_slug_empty() {

		$buffer_output = Utility::buffer_and_return(
			[ $this->_instance, 'widget' ],
			[
				[],
				[],
			]
		);

		// Check if output is empty.
		$this->assertEmpty( $buffer_output );
	}

	/**
	 * @covers ::widget
	 */
	public function test_widget_term_empty() {

		$data          = [
			'title'    => 'Movie Clips',
			'category' => 'movie',
		];
		$buffer_output = Utility::buffer_and_return(
			[ $this->_instance, 'widget' ],
			[
				[],
				$data,
			]
		);

		// Check if output is empty.
		$this->assertEmpty( $buffer_output );
	}

	/**
	 * @covers ::widget
	 */
	public function test_widget() {

		$data = $this->prepare_posts();

		add_filter( 'pmc_top_videos_playlist_videos_count', [ $this, 'pmc_top_videos_playlist_videos_count_modify' ] );

		$params          = [
			'title'    => $data['term']->name,
			'category' => $data['term']->slug,
		];
		$test_string   = $data['post_in_playlist']->post_title;
		$buffer_output = Utility::buffer_and_return(
			[ $this->_instance, 'widget' ],
			[
				[],
				$params,
			]
		);

		// Check if output is empty.
		$this->assertContains( $test_string, $buffer_output );

		$this->_mocker->restore_global_wp_post();
	}

	/**
	 * @covers ::get_playlist_videos
	 */
	public function test_get_playlist_videos() {

		$this->assertEmpty( $this->_instance->get_playlist_videos( '' ) );

		$data = $this->prepare_posts();

		$this->assertEmpty( $this->_instance->get_playlist_videos( $data['term'], 0 ) );

		$output = $this->_instance->get_playlist_videos( $data['term'] );

		$this->assertEquals( $data['post_in_playlist']->post_name, $output[0]->post_name );

		$this->_mocker->restore_global_wp_post();
	}

	/**
	 * @covers ::get_uncached_playlist_videos
	 */
	public function test_get_uncached_playlist_videos() {

		$this->assertEmpty( $this->_instance->get_uncached_playlist_videos( '' ) );

		$data = $this->prepare_posts();

		$this->assertEmpty( $this->_instance->get_uncached_playlist_videos( $data['term'], 0 ) );

		$output = $this->_instance->get_uncached_playlist_videos( $data['term'] );

		$this->assertEquals( $data['post_in_playlist']->post_name, $output[0]->post_name );

		$this->_mocker->restore_global_wp_post();
	}

	/**
	 * @covers ::fieldmanager_children
	 */
	public function test_fieldmanager_children() {

		$settings = Utility::invoke_hidden_method( $this->_instance, 'fieldmanager_children' );

		$this->assertArrayHasKey( 'title', $settings );
		$this->assertArrayHasKey( 'category', $settings );

		$categories = Utility::invoke_hidden_method( $this->_instance, '_get_categories' );

		$this->assertEquals( $categories, $settings['category']->options );
	}


	/**
	 * @covers ::_get_categories
	 */
	public function test__get_categories() {

		$post_id = $this->factory->post->create( [ 'post_type' => 'pmc_top_video' ] );

		$terms = [ 'term1', 'term2' ];
		$tax   = 'vcategory';

		$output_terms = [];

		foreach ( $terms as $term ) {

			$term_obj = $this->factory->term->create_and_get(
				[
					'name'     => $term,
					'taxonomy' => $tax,
				]
			);

			$output_terms[ $term_obj->slug ] = $term_obj->name;

			$this->factory->term->add_post_terms( $post_id, [ $term_obj->term_id ], $tax );
		}

		$this->assertEquals(
			$output_terms,
			Utility::invoke_hidden_method( $this->_instance, '_get_categories' )
		);
	}

	/**
	 * @covers:: _backfill_playlist_videos
	 */
	public function test__backfill_playlist_videos() {

		$data = $this->prepare_posts();

		// Get videos already in playlist.
		$videos_in_playlist = $this->_instance->get_uncached_playlist_videos( $data['term'] );

		$output           = Utility::invoke_hidden_method( $this->_instance, '_backfill_playlist_videos', [ $videos_in_playlist ] );
		$playlist_video   = $output[0];
		$backfilled_video = $output[1];

		$this->assertEquals( $data['post_general']->post_name, $backfilled_video->post_name );
		$this->assertEquals( $data['post_in_playlist']->post_name, $playlist_video->post_name );

		$this->_mocker->restore_global_wp_post();
	}

	/**
	 * Prepare post for video playlist tests.
	 *
	 * @return array
	 * @throws \ErrorException Throws exception from `mock_global_wp_post()` function.
	 */
	public function prepare_posts() {

		register_post_type(
			'pmc_top_video',
			[
				'labels'     => [
					'name' => __( 'Videos', 'pmc-top-videos-v2' ),
				],
				'public'     => true,
				'rewrite'    => [ 'slug' => 'video' ],
				'taxonomies' => [ 'category', 'post_tag', 'vcategory' ],
			]
		);

		register_taxonomy(
			'vcategory',
			'pmc_top_video',
			[
				'labels'            => [
					'name'          => _x( 'Playlists', 'taxonomy general name', 'pmc-top-videos-v2' ),
					'singular_name' => _x( 'Playlist', 'taxonomy singular name', 'pmc-top-videos-v2' ),
				],
				'show_ui'           => true,
				'show_in_nav_menus' => true,
				'show_admin_column' => true,
			]
		);

		// Create new movie vcategory.
		$term = $this->factory->term->create_and_get(
			[
				'name'     => 'Movie Clips',
				'taxonomy' => 'vcategory',
				'slug'     => 'movie',
			]
		);

		$args = [
			'post_type'    => 'pmc_top_video',
			'post_title'   => 'Test Video 1',
			'post_name'    => 'test-video-1',
			'post_content' => 'Test Content 1',
		];
		$post_in_playlist = $this->_mocker->mock_global_wp_post( $this, $args );
		$this->factory->term->add_post_terms( $post_in_playlist->ID, [ $term->term_id ], $term->taxonomy );

		$args2 = [
			'post_type'    => 'pmc_top_video',
			'post_title'   => 'Test Video 2',
			'post_name'    => 'test-video-2',
			'post_content' => 'Test Content 2',
		];
		$post_general = $this->_mocker->mock_global_wp_post( $this, $args2 );

		return [
			'term'             => $term,
			'post_in_playlist' => $post_in_playlist,
			'post_general'     => $post_general,
		];
	}

	/**
	 * Modify videos length filter with wrong value - a string.
	 */
	public function pmc_top_videos_playlist_videos_count_modify() {

		return 'wrong number';
	}
}
