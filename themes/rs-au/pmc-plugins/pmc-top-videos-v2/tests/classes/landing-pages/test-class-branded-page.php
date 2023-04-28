<?php
/**
 * Unit tests for \PMC\Top_Videos_V2\Landing_Pages\Branded_Page
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since 2019-10-01
 *
 * @package pmc-top-videos-v2
 */

namespace PMC\Top_Videos_V2\Tests\Landing_Pages;

use \PMC\Top_Videos_V2\Tests\Base;
use \PMC\Top_Videos_V2\Landing_Pages\Branded_Page;
use \PMC\Unit_Test\Utility;


/**
 * @group pmc-top-videos-v2
 * @group pmc-top-videos-v2-landing-pages-branded-page
 *
 * @coversDefaultClass \PMC\Top_Videos_V2\Landing_Pages\Branded_Page
 */
class Test_Class_Branded_Page extends Base {

	/**
	 * @var \PMC\Top_Videos_V2\Landing_Pages\Branded_Page
	 */
	protected $_instance;

	public function setUp() {

		parent::setUp();

		$this->_instance = Branded_Page::get_instance();


		$this->_default_vars = [
			'_post_types',
			'_fields',
			'_default_fields',
		];

		$this->_take_snapshot( $this->_instance );

	}

	public function tearDown() {

		$this->_restore_snapshot( $this->_instance );

		parent::tearDown();

	}

	/**
	 * Data provider to get invalid post IDs
	 *
	 * @return array
	 */
	public function data_get_invalid_post_ids() : array {

		return [
			[ 0 ],
			[ 1234567890 ],
		];

	}

	/**
	 * Data provider to get invalid method names
	 *
	 * @return array
	 */
	public function data_get_invalid_method_names() : array {

		return [
			[ '' ],
			[ 'get_car' ],
			[ 'get_new_car' ],
		];

	}

	/**
	 * Data provider to get invalid method names
	 *
	 * @return array
	 */
	public function data_get_invalid_carousel_names() : array {

		return [
			[ '' ],
			[ 'top' ],
			[ 'last' ],
		];

	}

	/**
	 * Helper method to provide mock settings array with landing page enabled
	 *
	 * @return array
	 */
	protected function _data_get_settings_with_landing_page_enabled() : array {

		return [
			'enable_landing_page'      => 'yes',
			'top_banner_image_url'     => 'https://example.com/images/test-1.jpg',
			'top_banner_mob_image_url' => 'https://example.com/images/test-1-small.jpg',
			'top_banner_link_url'      => 'https://example.com/some/where/to/go/',
			'first_carousel'           => 'some-carousel-term-1',
			'first_playlist_title'     => 'Some Playlist Title',
			'first_playlist'           => 'some-playlist-term-1',
			'second_carousel_title'    => 'Some Carousel Title 2',
			'second_carousel'          => 'some-carousel-term-2',
			'js_tag_title'             => 'Some JS Module Title',
			'js_tag_url'               => 'https://example.com/ads/tag/install/',
		];

	}

	/**
	 * Data provider to get settings with landing page enabled
	 *
	 * @return array
	 */
	public function data_get_settings_with_landing_page_enabled() : array {

		return [
			[
				$this->_data_get_settings_with_landing_page_enabled(),
			],
		];

	}

	/**
	 * Data provider for enforce_fields_limit() to provide numbers and their expected enforced values
	 *
	 * @return array
	 */
	public function data_get_numbers_and_expected_enforced_results() : array {

		return [
			[
				-1,
				0,
			],
			[
				2,
				2,
			],
			[
				6,
				5,
			],
		];

	}

	/**
	 * Helper method to return mock posts array simulating posts returned from a carousel
	 *
	 * @return array
	 */
	public function get_dummy_posts_for_carousel() : array {

		$posts = [];

		for ( $i = 0; $i < 4; $i++ ) {

			$post    = $this->factory->post->create_and_get();
			$posts[] = [
				'ID'        => $post->ID,
				'parent_ID' => $post->post_parent,
				'title'     => $post->post_title,
				'excerpt'   => $post->post_excerpt,
			];

		}

		$post    = $this->factory->post->create_and_get();
		$posts[] = [
			'ID'        => 0,
			'parent_ID' => $post->ID,
			'title'     => $post->post_title,
			'excerpt'   => $post->post_excerpt,
		];

		return $posts;

	}

	/**
	 * Helper method to return mock posts array simulating posts returned from a playlist
	 *
	 * @return array
	 */
	public function get_dummy_posts_for_playlist() : array {

		$posts = [];

		for ( $i = 0; $i < 5; $i++ ) {

			$post    = $this->factory->post->create_and_get();
			$posts[] = $post;

		}

		return $posts;

	}

	/**
	 * Method hooked on 'pmc_top_videos_branded_landing_page_fields' filter to provide dummy data
	 *
	 * @return array
	 */
	public function get_field_counts() : array {

		return [
			'carousel' => 5,
			'playlist' => 2,
		];

	}

	/**
	 * @covers ::_setup_hooks
	 */
	public function test__setup_hooks() {

		Utility::invoke_hidden_method( $this->_instance, '_setup_hooks' );

		$hooks = [
			[
				'type'     => 'action',
				'name'     => 'fm_post_page',
				'priority' => 10,
				'listener' => [ $this->_instance, 'add_metabox' ],
			],
		];

		$current_class = get_class( $this->_instance );

		foreach ( $hooks as $hook ) {

			$listener = '';

			if ( is_string( $hook['listener'] ) ) {
				$listener = sprintf( '%s()', $hook['listener'] );
			} elseif ( is_array( $hook['listener'] ) ) {

				if ( is_object( $hook['listener'][0] ) ) {
					$listener_class = get_class( $hook['listener'][0] );
				} else {
					$listener_class = $hook['listener'][0];
				}

				$listener = sprintf( '%s::%s()', $listener_class, $hook['listener'][1] );

			}

			$this->assertEquals(
				$hook['priority'],
				call_user_func(
					sprintf( 'has_%s', $hook['type'] ),
					$hook['name'],
					$hook['listener']
				),
				sprintf(
					'%1$s::_setup_hooks() failed to register %2$s "%3$s" to %4$s()',
					$current_class,
					$hook['type'],
					$hook['name'],
					$listener
				)
			);

		}

	}

	/**
	 * @covers ::enforce_fields_limit
	 *
	 * @dataProvider data_get_numbers_and_expected_enforced_results
	 */
	public function test_enforce_fields_limit( int $num, int $expected ) : void {

		/*
		 * Set up
		 */
		$output = $this->_instance->enforce_fields_limit( $num );

		/*
		 * Assertions
		 */
		$this->assertEquals( $expected, $output );

	}

	/**
	 * @covers ::_set_fields
	 */
	public function test__set_fields_when_value_is_already_set() : void {

		/*
		 * Set up
		 */
		$mock_value = [ 'abc' ];
		Utility::set_and_get_hidden_property( $this->_instance, '_fields', $mock_value );

		$output = Utility::invoke_hidden_method( $this->_instance, '_set_fields' );

		/*
		 * Assertions
		 */
		$this->assertFalse( $output );

	}

	/**
	 * @covers ::_set_fields
	 */
	public function test__set_fields_when_filter_gets_non_array_value() : void {

		/*
		 * Set up
		 */
		add_filter( 'pmc_top_videos_branded_landing_page_fields', '__return_true' );

		/*
		 * Assertions
		 */
		Utility::assert_exception(
			\ErrorException::class,
			[
				Utility::class,
				'invoke_hidden_method',
			],
			[
				$this->_instance,
				'_set_fields',
			]
		);

		/*
		 * Clean up
		 */
		remove_filter( 'pmc_top_videos_branded_landing_page_fields', '__return_true' );

	}

	/**
	 * @covers ::_set_fields
	 */
	public function test__set_fields_for_success() : void {

		/*
		 * Set up
		 */
		add_filter( 'pmc_top_videos_branded_landing_page_fields', [ $this, 'get_field_counts' ] );

		$output = Utility::invoke_hidden_method( $this->_instance, '_set_fields' );

		$fields_expected = $this->get_field_counts();
		$fields_actual   = Utility::get_hidden_property( $this->_instance, '_fields' );

		/*
		 * Assertions
		 */
		$this->assertTrue( $output );
		$this->assertEquals( $fields_expected, $fields_actual );

		/*
		 * Clean up
		 */
		remove_filter( 'pmc_top_videos_branded_landing_page_fields', [ $this, 'get_field_counts' ] );

	}

	/**
	 * @covers ::add_metabox
	 *
	 * @throws \FM_Developer_Exception
	 */
	public function test_add_metabox() : void {

		/*
		 * Set up
		 */
		$allowed_post_types = [ 'page' ];
		$title_expected     = __( 'Branded Video Landing Page Settings', 'pmc-top-videos-v2' );
		$output             = $this->_instance->add_metabox();

		/*
		 * Assertions
		 */
		$this->assertInstanceOf( '\Fieldmanager_Context_Post', $output );
		$this->assertInstanceOf( '\Fieldmanager_Group', $output->fm );
		$this->assertEquals( $allowed_post_types, $output->post_types );
		$this->assertEquals( $title_expected, $output->title );
		$this->assertEquals( 'normal', $output->context );
		$this->assertEquals( 'default', $output->priority );

	}

	/**
	 * @covers ::_get_terms_dropdown_list
	 */
	public function test__get_terms_dropdown_list() : void {

		/*
		 * Set up
		 */
		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_get_terms_dropdown_list',
			[
				'category',
			]
		);

		/*
		 * Assertions
		 */
		$this->assertTrue( is_array( $output ) );
		$this->assertNotEmpty( $output );
		$this->assertContains(
			__( 'Select one', 'pmc-top-videos-v2' ),
			$output
		);

	}

	/**
	 * @covers ::get_settings
	 *
	 * @dataProvider data_get_invalid_post_ids
	 */
	public function test_get_settings_when_post_id_is_invalid( int $post_id ) : void {

		/*
		 * Set up
		 */
		$output = $this->_instance->get_settings( $post_id );

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output );

	}

	/**
	 * @covers ::get_settings
	 * @covers ::_get_settings_raw
	 *
	 * @dataProvider data_get_settings_with_landing_page_enabled
	 */
	public function test_get_settings_for_success( array $meta ) : void {

		/*
		 * Set up
		 */
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);

		update_post_meta( $post_id, Branded_Page::ID, $meta );

		$output = $this->_instance->get_settings( $post_id );

		/*
		 * Assertions
		 */
		$this->assertNotEmpty( $output );
		$this->assertArrayHasKey( 'enable_landing_page', $output );
		$this->assertArrayHasKey( 'top_banner_image_url', $output );

		/*
		 * Clean up
		 */
		wp_delete_post( $post_id, true );

	}

	/**
	 * @covers ::is_enabled_for_post
	 *
	 * @dataProvider data_get_invalid_post_ids
	 */
	public function test_is_enabled_for_post_when_post_id_is_invalid( int $post_id ) : void {

		/*
		 * Set up
		 */
		$output = $this->_instance->is_enabled_for_post( $post_id );

		/*
		 * Assertions
		 */
		$this->assertFalse( $output );

	}

	/**
	 * @covers ::is_enabled_for_post
	 *
	 * @dataProvider data_get_settings_with_landing_page_enabled
	 */
	public function test_is_enabled_for_post_for_success( array $meta ) : void {

		/*
		 * Set up
		 */
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);

		update_post_meta( $post_id, Branded_Page::ID, $meta );

		$output = $this->_instance->is_enabled_for_post( $post_id );

		/*
		 * Assertions
		 */
		$this->assertTrue( $output );

		/*
		 * Clean up
		 */
		wp_delete_post( $post_id, true );

	}

	/**
	 * @covers ::__call
	 *
	 * @dataProvider data_get_invalid_method_names
	 */
	public function test___call_when_method_name_is_invalid( string $method_name ) : void {

		/*
		 * Set up
		 */
		$output = call_user_func_array(
			[ $this->_instance, '__call' ],
			[
				$method_name,
				[],
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output );

	}

	/**
	 * @covers ::get_carousel_uncached
	 *
	 * @dataProvider data_get_invalid_carousel_names
	 */
	public function test_get_carousel_uncached_when_name_is_invalid( string $carousel ) : void {

		/*
		 * Set up
		 */
		$meta    = $this->_data_get_settings_with_landing_page_enabled();
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);

		update_post_meta( $post_id, Branded_Page::ID, $meta );

		$output = $this->_instance->get_carousel_uncached( $carousel, $post_id );

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output );

		/*
		 * Clean up
		 */
		wp_delete_post( $post_id, true );

	}

	/**
	 * @covers ::get_carousel_uncached
	 */
	public function test_get_carousel_uncached_when_carousel_is_empty() : void {

		/*
		 * Set up
		 */
		$meta    = $this->_data_get_settings_with_landing_page_enabled();
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);

		update_post_meta( $post_id, Branded_Page::ID, $meta );

		$output = $this->_instance->get_carousel_uncached( 'first', $post_id );

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output );

		/*
		 * Clean up
		 */
		wp_delete_post( $post_id, true );

	}

	/**
	 * @covers ::__call
	 * @covers ::_get_carousel
	 * @covers ::get_carousel_uncached
	 */
	public function test_get_carousel_uncached_for_success() : void {

		/*
		 * Set up
		 */
		add_filter( 'pmc_carousel_render_short_circuit', [ $this, 'get_dummy_posts_for_carousel' ] );

		$name    = 'first';
		$count   = 10;
		$meta    = $this->_data_get_settings_with_landing_page_enabled();
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);

		$cache_key = sprintf(
			'%s-carousel-%s-%d-%d',
			get_class( $this->_instance ),
			$name,
			$post_id,
			$count
		);

		update_post_meta( $post_id, Branded_Page::ID, $meta );

		$output = $this->_instance->get_first_carousel( $post_id, $count );

		/*
		 * Assertions
		 */
		$this->assertNotEmpty( $output );
		$this->assertArrayHasKey( 'title', $output );
		$this->assertArrayHasKey( 'posts', $output );
		$this->assertNotEmpty( $output['posts'] );
		$this->assertTrue( is_array( $output['posts'] ) );

		/*
		 * Clean up
		 */
		wp_delete_post( $post_id, true );
		( new \PMC_Cache( $cache_key ) )->invalidate();

		remove_filter( 'pmc_carousel_render_short_circuit', [ $this, 'get_dummy_posts_for_carousel' ] );

	}

	/**
	 * @covers ::get_playlist_uncached
	 *
	 * @dataProvider data_get_invalid_carousel_names
	 */
	public function test_get_playlist_uncached_when_name_is_invalid( string $playlist ) : void {

		/*
		 * Set up
		 */
		$meta    = $this->_data_get_settings_with_landing_page_enabled();
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);

		update_post_meta( $post_id, Branded_Page::ID, $meta );

		$output = $this->_instance->get_playlist_uncached( $playlist, $post_id );

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output );

		/*
		 * Clean up
		 */
		wp_delete_post( $post_id, true );

	}

	/**
	 * @covers ::get_playlist_uncached
	 */
	public function test_get_playlist_uncached_when_playlist_is_empty() : void {

		/*
		 * Set up
		 */
		$meta    = $this->_data_get_settings_with_landing_page_enabled();
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);

		update_post_meta( $post_id, Branded_Page::ID, $meta );

		$output = $this->_instance->get_playlist_uncached( 'first', $post_id );

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output );

		/*
		 * Clean up
		 */
		wp_delete_post( $post_id, true );

	}

	/**
	 * @covers ::__call
	 * @covers ::_get_playlist
	 * @covers ::get_playlist_uncached
	 */
	public function test_get_playlist_uncached_for_success() : void {

		/*
		 * Set up
		 */
		add_filter( 'the_posts', [ $this, 'get_dummy_posts_for_playlist' ] );

		$name    = 'first';
		$count   = 10;
		$meta    = $this->_data_get_settings_with_landing_page_enabled();
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);

		$cache_key = sprintf(
			'%s-playlist-%s-%d-%d',
			get_class( $this->_instance ),
			$name,
			$post_id,
			$count
		);

		update_post_meta( $post_id, Branded_Page::ID, $meta );

		$output = $this->_instance->get_first_playlist( $post_id, $count );

		/*
		 * Assertions
		 */
		$this->assertNotEmpty( $output );
		$this->assertArrayHasKey( 'title', $output );
		$this->assertArrayHasKey( 'posts', $output );
		$this->assertNotEmpty( $output['posts'] );
		$this->assertTrue( is_array( $output['posts'] ) );

		/*
		 * Clean up
		 */
		wp_delete_post( $post_id, true );
		( new \PMC_Cache( $cache_key ) )->invalidate();

		remove_filter( 'the_posts', [ $this, 'get_dummy_posts_for_playlist' ] );

	}

	/**
	 * @covers ::render_banner
	 *
	 * @dataProvider data_get_invalid_post_ids
	 *
	 * @throws \ErrorException
	 */
	public function test_render_banner_when_post_id_is_invalid( int $post_id ) : void {

		/*
		 * Set up
		 */
		$output = Utility::buffer_and_return(
			[ $this->_instance, 'render_banner' ],
			[
				$post_id,
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output );

	}

	/**
	 * @covers ::render_banner
	 *
	 * @dataProvider data_get_settings_with_landing_page_enabled
	 *
	 * @throws \ErrorException
	 */
	public function test_render_banner_when_banner_not_set( array $meta ) : void {

		/*
		 * Set up
		 */
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);

		$meta['top_banner_image_url']     = '';
		$meta['top_banner_mob_image_url'] = '';
		$meta['top_banner_link_url']      = '';

		update_post_meta( $post_id, Branded_Page::ID, $meta );

		$output = Utility::buffer_and_return(
			[ $this->_instance, 'render_banner' ],
			[
				$post_id,
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output );

		/*
		 * Clean up
		 */
		wp_delete_post( $post_id, true );

	}

	/**
	 * @covers ::render_banner
	 *
	 * @dataProvider data_get_settings_with_landing_page_enabled
	 *
	 * @throws \ErrorException
	 */
	public function test_render_banner_for_success_when_banner_link_not_set( array $meta ) : void {

		/*
		 * Set up
		 */
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);

		$meta['top_banner_link_url'] = '';

		update_post_meta( $post_id, Branded_Page::ID, $meta );

		$output = Utility::buffer_and_return(
			[ $this->_instance, 'render_banner' ],
			[
				$post_id,
			]
		);

		/*
		 * Assertions
		 */
		$this->assertNotEmpty( $output );
		$this->assertContains( '<img src', $output );
		$this->assertNotContains( '<a href', $output );

		/*
		 * Clean up
		 */
		wp_delete_post( $post_id, true );

	}

	/**
	 * @covers ::render_banner
	 *
	 * @dataProvider data_get_settings_with_landing_page_enabled
	 *
	 * @throws \ErrorException
	 */
	public function test_render_banner_for_success_when_banner_link_is_set( array $meta ) : void {

		/*
		 * Set up
		 */
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);

		update_post_meta( $post_id, Branded_Page::ID, $meta );

		$output = Utility::buffer_and_return(
			[ $this->_instance, 'render_banner' ],
			[
				$post_id,
			]
		);

		/*
		 * Assertions
		 */
		$this->assertNotEmpty( $output );
		$this->assertContains( '<img src', $output );
		$this->assertContains( '<a href', $output );

		/*
		 * Clean up
		 */
		wp_delete_post( $post_id, true );

	}

}    //end class

//EOF
