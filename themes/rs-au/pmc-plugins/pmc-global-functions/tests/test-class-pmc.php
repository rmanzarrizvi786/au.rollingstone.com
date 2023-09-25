<?php
/**
 * PHPUnit tests for class \PMC
 *
 * @since 2015-07-24 PPT-5181 Mike Auteri
 * @version 2015-11-02 Amit Gupta - added tests test_get_current_site_name() & test_is_current_site_name()
 */
namespace PMC\Global_Functions\Tests;

use \PMC\Unit_Test\Utility;
use \PMC;

/**
 * @group pmc-global-functions
 * @group pmc-global-functions-test-pmc
 *
 * @requires PHP 7.2
 * @coversDefaultClass \PMC
 */
class Tests_Class_PMC extends Base {

	public function setUp() {
		parent::setUp();
	}

	/**
	 * Test Strip Disallowed URLs function
	 *
	 * @dataProvider data_ctrl_content
	 *
	 * @covers ::strip_disallowed_urls()
	 */
	public function test_strip_disallowed_urls( $cont, $lks ) {
		foreach( $cont as $key => $content ) {
			global $links;
			$links = $lks[$key];
			$new_content = PMC::strip_disallowed_urls( $content );

			// Check new content is the same with no manipulation
			$this->assertEquals( $content, $new_content, 'PMC::strip_disallowed_urls failed to match exactly with no filters to manipulate content.' );

			// Add internal filter
			add_filter( 'pmc_strip_disallowed_urls', function( $args, $post_id ) {
				global $links;

				foreach( $links['internal'] as $internal ) {
					$args['site_hosts'][] = preg_replace( '/^www\./', '', parse_url( $internal, PHP_URL_HOST ) );
				}

				foreach( $links['whitelist'] as $whitelist ) {
					$args['external_url_whitelist'][] = preg_replace( '/^www\./', '', parse_url( $whitelist, PHP_URL_HOST ) );
				}

				// Test pmc_external_strip_all_boolean filter
				$args['external_strip_all_boolean'] = true;

				return $args;
			}, 10, 2);


			$new_content = PMC::strip_disallowed_urls( $content );
			foreach( $links['whitelist'] as $whitelist ) {
				$this->assertNotContains( $whitelist, $new_content, sprintf( 'PMC::strip_disallowed_urls pmc_external_strip_all_boolean test failed to remove external URL: "%1$s".', $whitelist ) );
			}
			foreach( $links['internal'] as $internal ) {
				$this->assertContains( $internal, $new_content, sprintf( 'PMC::strip_disallowed_urls pmc_external_strip_all_boolean test failed and removed internal URL: "%1$s".', $internal ) );
			}

			add_filter( 'pmc_strip_disallowed_urls', function( $args, $post_id ) {
				$args['external_strip_all_boolean'] = false; // back to false

				// Test external_url_whitelist_boolean as true
				$args['external_url_whitelist_boolean'] = true;
				return $args;
			}, 10, 2 );

			$new_content = PMC::strip_disallowed_urls( $content );
			foreach( $links['whitelist'] as $whitelist ) {
				$this->assertContains( $whitelist, $new_content, sprintf( 'PMC::strip_disallowed_urls pmc_external_url_whitelist_boolean test failed and removed whitelisted URL: "%1$s".', $whitelist ) );
			}
			foreach( $links['internal'] as $internal ) {
				$this->assertContains( $internal, $new_content, sprintf( 'PMC::strip_disallowed_urls pmc_external_url_whitelist_boolean test failed and removed internal URL: "%1$s"', $internal ) );
			}

			add_filter( 'pmc_strip_disallowed_urls', function( $args, $post_id ) {
				// Test external_url_link_source_boolean as true
				$args['external_url_link_source_boolean'] = true;
				return $args;
			}, 10, 2 );

			$new_content = PMC::strip_disallowed_urls( $content );
			foreach( $links['whitelist'] as $whitelist ) {
				$this->assertContains( $whitelist, $new_content, sprintf( 'PMC::strip_disallowed_urls pmc_external_url_link_source_boolean test failed and removed whitelisted URL: "%1$s".', $whitelist ) );
			}
			foreach( $links['internal'] as $internal ) {
				$this->assertContains( $internal, $new_content, sprintf( 'PMC::strip_disallowed_urls pmc_external_url_link_source_boolean failed and removed internal URL: "%1$s".', $internal ) );
			}

			remove_all_filters( 'pmc_strip_disallowed_urls' );
		}
	}

	public function data_ctrl_content() {
		$content_1 = file_get_contents( __DIR__ . '/assets/content_1.html' );
		$content_2 = file_get_contents( __DIR__ . '/assets/content_2.html' );

		return array(
			array(
				array(
					$content_1,
					$content_2
				),
				array(
					// $content_1
					array(
						'whitelist' => array(
							'http://www.wired.com/2015/07/hackers-remotely-kill-jeep-highway/',
						),
						'internal' => array(
							'http://www.bgr.com/2012/07/13/charlie-miller-nfc-hacking-mobile-payments/',
						),
					),
					// $content_2
					array(
						'whitelist' => array(
							'http://www.wwe.com/superstars/hulkhogan',

						),
						'internal' => array(
							'http://variety.com/2014/biz/news/wrestlemania-celebrates-30-years-as-wwe-pins-down-big-deals-bright-future-1201151246/',
						),
					),
				)
			),
		);
	}

	/**
	 * Data provider to provide integers and their negative values
	 *
	 * @return array
	 */
	public function data_integers_to_negate() : array {

		return [

			[
				2,
				-2,
			],
			[
				-32,
				-32,
			],
			[
				0,
				0,
			],

		];

	}

	/**
	 * Function to mock active theme returned by get_stylesheet_directory()
	 *
	 * @since 2015-11-02 Amit Gupta
	 */
	protected function _mock_site_theme() {

		add_filter( 'stylesheet_directory', function() {
			return 'themes/vip/pmc-variety-2014';
		}, 99 );

	}

	/**
	 * @covers ::get_current_site_name()
	 *
	 * @since 2015-11-02 Amit Gupta
	 */
	public function test_get_current_site_name() {

		/**
		 * force this test only for non-production environments
		 *
		 * @ticket https://wordpressvip.zendesk.com/requests/46628
		 */
		if ( PMC::is_production() ) {
			return;
		}

		//set current theme to Variety
		$this->_mock_site_theme();

		$this->assertEquals( 'variety', PMC::get_current_site_name() );

	}

	/**
	 * @covers ::is_current_site_name()
	 *
	 * @since 2015-11-02 Amit Gupta
	 */
	public function test_is_current_site_name() {

		/**
		 * force this test only for non-production environments
		 *
		 * @ticket https://wordpressvip.zendesk.com/requests/46628
		 */
		if ( PMC::is_production() ) {
			return;
		}

		//set current theme to Variety
		$this->_mock_site_theme();

		$this->assertTrue( PMC::is_current_site_name( 'variety' ) );

	}

	/**
	 * @covers PMC::has_linked_gallery
	 */
	public function test_has_linked_gallery_returns_true_or_false() {
		list( $post_id, $gallery_id ) = $this->linked_gallery_tests_provider();

		$this->assertTrue( PMC::has_linked_gallery( $post_id ) );
	}

	/**
	 * @covers PMC::has_linked_gallery
	 */
	public function test_get_linked_gallery_returns_object_of_data() {
		list( $post_id, $gallery_id ) = $this->linked_gallery_tests_provider();

		$gallery_data = PMC::get_linked_gallery( $post_id );
		$this->assertInstanceOf( 'stdClass', $gallery_data );
		$this->assertNotEmpty( $gallery_data );
		$this->assertEquals( $gallery_data->url, get_permalink( $gallery_id ) );
		$this->assertEquals( $gallery_data->id, $gallery_id );
		$this->assertEquals( $gallery_data->title, get_the_title( $gallery_id ) );
		$this->assertEmpty( $gallery_data->items );
	}

	/**
	 * @covers PMC::get_linked_gallery
	 */
	public function test_get_linked_gallery_with_items_returns_object_of_data() {
		list( $post_id, $gallery_id ) = $this->linked_gallery_tests_provider();

		$gallery_data = PMC::get_linked_gallery( $post_id, true );
		$this->assertInstanceOf( 'stdClass', $gallery_data );
		$this->assertNotEmpty( $gallery_data );
		$this->assertEquals( $gallery_data->url, get_permalink( $gallery_id ) );
		$this->assertEquals( $gallery_data->id, $gallery_id );
		$this->assertEquals( $gallery_data->title, get_the_title( $gallery_id ) );
		$this->assertNotEmpty( $gallery_data->items );
		$this->assertCount( 10, $gallery_data->items );
	}

	/**
	 * @covers PMC::get_linked_gallery_items
	 */
	public function test_get_linked_gallery_items_returns_array_of_attachment_ids() {
		list( $post_id, $gallery_id ) = $this->linked_gallery_tests_provider();
		$gallery_items = PMC::get_linked_gallery_items( $post_id );
		$this->assertNotEmpty( $gallery_items );
		$this->assertCount( 10, $gallery_items );
	}

	/**
	 * @covers PMC::get_gallery_items
	 */
	public function test_get_gallery_items() {
		list( $post_id, $gallery_id ) = $this->linked_gallery_tests_provider();
		$gallery_items = PMC::get_gallery_items( $gallery_id );
		$this->assertNotEmpty( $gallery_items );
		$this->assertCount( 10, $gallery_items );
	}

	/**
	 * Create a post, a gallery, and link them together
	 *
	 * Called as a data provider for test methods
	 *
	 * @return array Array containing the linked gallery post id and gallery id
	 */
	public function linked_gallery_tests_provider() {

		// Create a test post, a gallery, and link them together to test
		// the helper function related to these items.
		$post_id = $this->factory->post->create( array(
			'post_status' => 'publish',
			'post_title'  => 'Testing post'
		) );

		$gallery_post_id = $this->factory->post->create( array(
			'post_status' => 'publish',
			'post_title'  => 'Testing gallery',
			'post_type'   => 'pmc-gallery',
		) );

		$gallery_attachment_ids = $this->factory->post->create_many( 10, array(
			'post_status' => 'publish',
			'post_type'   => 'attachment',
		) );

		// Setup the relationships between the post and it's gallery
		add_post_meta( $post_id, 'pmc-gallery-linked-gallery', json_encode( array(
			'id'    => $gallery_post_id,
			'url'   => get_permalink( $gallery_post_id ),
			'title' => get_the_title( $gallery_post_id ),
		) ) );

		add_post_meta( $gallery_post_id, 'pmc-gallery-linked-post_id', $post_id );
		add_post_meta( $gallery_post_id, 'pmc-gallery', $gallery_attachment_ids );

		return [ $post_id, $gallery_post_id ];
	}

	/**
	 * Verify php7 fixes
	 */
	public function test_get_post_authors() {
		global $coauthors_plus;

		// We need to flush the cache to prevent any caching that may cause test to failed due to caching
		$this->flush_cache();

		$post_id = $this->factory->post->create( array(
			'post_status' => 'publish',
			'post_title'  => 'Test get post authors',
			'post_type'   => 'post',
		) );


		$guest_author_id = $coauthors_plus->guest_authors->create( array(
			'display_name' => 'display_name',
			'user_login'   => 'user_login_test_get_post_authors',
			'user_email'   => 'user_email',
			'first_name'   => 'first_name',
			'last_name'    => 'last_name',
			'_pmc_title'   => 'title',
		) );

		$this->assertFalse( is_wp_error( $guest_author_id ) );

		$coauthors_plus->add_coauthors( $post_id, array( 'user_login_test_get_post_authors' ), false );
		$authors = PMC::get_post_authors( $post_id, 'all', ['ID','display_name'] );
		$this->assertTrue( is_array( $authors ) );
		$this->assertArraySubset( [
					$guest_author_id => [
						'ID'           => $guest_author_id,
						'display_name' => 'display_name',
					]
				], $authors );

	}

	/**
	 * This will only test for CLI version not for HTTP version.
	 * Because Unit Test cases will execute on CLI.
	 *
	 * Even purpose of PMC::filter_input() is give filter_input() support in CLI.
	 * For Http it will same output as filter_input()
	 *
	 * @covers ::filter_input()
	 */
	public function test_filter_input_for_cli() {

		/**
		 * Test for Global $_GET.
		 */
		$input = 'input_GET';

		// Mock Global $_GET variable.
		$_GET['VARIABLE'] = $input;

		$output = PMC::filter_input( INPUT_GET, 'VARIABLE', FILTER_SANITIZE_STRING );

		$this->assertEquals( $output, $input );

		/**
		 * Test for Global $_POST.
		 */
		$input = 'input_POST';

		// Mock Global $_POST variable.
		$_POST['VARIABLE'] = $input;

		$output = PMC::filter_input( INPUT_POST, 'VARIABLE', FILTER_SANITIZE_STRING );

		$this->assertEquals( $output, $input );

		/**
		 * Test for Global $_COOKIE.
		 */
		$input = 'input_COOKIE';

		// Mock Global $_POST variable.
		$_COOKIE['VARIABLE'] = $input;

		$output = PMC::filter_input( INPUT_COOKIE, 'VARIABLE', FILTER_SANITIZE_STRING );

		$this->assertEquals( $output, $input );

		/**
		 * Test for Global SERVER.
		 */
		$input = '/path/to/script';

		// Mock Global $_SERVER variable.
		$_SERVER['REQUEST_URI'] = $input;

		$output = PMC::filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING );

		$this->assertEquals( $output, $input );

		/**
		 * Test for Global $_ENV.
		 */
		$input = 'input_ENV';

		// Mock Global $_POST variable.
		$_ENV['VARIABLE'] = $input;

		$output = PMC::filter_input( INPUT_ENV, 'VARIABLE', FILTER_SANITIZE_STRING );

		$this->assertEquals( $output, $input );

	}

	/**
	 * @covers ::get_word_count
	 */
	public function test_get_word_count_with_invalid_type_input() {

		$method_name = 'get_word_count';
		$error_msg   = sprintf(
			'%s::%s() failed to throw %s exception on input of %%s type',
			\PMC::class,
			$method_name,
			\TypeError::class
		);

		// Test with array input
		Utility::assert_error(
			\TypeError::class,
			[ \PMC::class, $method_name ],
			[ [ 'abc' ] ],
			sprintf(
				$error_msg,
				'array'
			)
		);

		$test_obj = new \stdClass();

		// Test with object input
		Utility::assert_error(
			\TypeError::class,
			[ \PMC::class, $method_name ],
			[ $test_obj ],
			sprintf(
				$error_msg,
				'object'
			)
		);

	}

	/**
	 * @covers ::get_word_count
	 */
	public function test_get_word_count_with_empty_input() {

		$output = PMC::get_word_count( '' );

		$this->assertTrue( is_int( $output ) );
		$this->assertEquals( 0, $output );

	}

	/**
	 * @covers ::get_word_count
	 */
	public function test_get_word_count_for_success() {

		/*
		 * Test with plain text
		 */
		$text_to_test = 'Some sample text';

		$output = PMC::get_word_count( $text_to_test );

		$this->assertTrue( is_int( $output ) );
		$this->assertEquals( 3, $output );

		/*
		 * Test with HTML
		 */
		$text_to_test = 'Some <strong>sample </strong> text';

		$output = PMC::get_word_count( $text_to_test );

		$this->assertTrue( is_int( $output ) );
		$this->assertEquals( 3, $output );

		/*
		 * Test with text containing shortcode
		 */
		$text_to_test = 'Some sample [gallery id="123" size="medium"] text';

		$output = PMC::get_word_count( $text_to_test );

		$this->assertTrue( is_int( $output ) );
		$this->assertEquals( 3, $output );

	}

	/**
	 * @covers ::is_file_path_valid
	 */
	public function test_is_file_path_valid_with_invalid_type_input() {

		$method_name = 'is_file_path_valid';
		$error_msg   = sprintf(
			'%s::%s() failed to throw %s exception on input of %%s type',
			\PMC::class,
			$method_name,
			\TypeError::class
		);

		// Test with array input
		Utility::assert_error(
			\TypeError::class,
			[ \PMC::class, $method_name ],
			[ [ 'abc' ] ],
			sprintf(
				$error_msg,
				'array'
			)
		);

		$test_obj = new \stdClass();

		// Test with object input
		Utility::assert_error(
			\TypeError::class,
			[ \PMC::class, $method_name ],
			[ $test_obj ],
			sprintf(
				$error_msg,
				'object'
			)
		);

	}

	/**
	 * @covers ::is_file_path_valid
	 */
	public function test_is_file_path_valid_with_empty_input() {

		$output = PMC::is_file_path_valid( '' );

		$this->assertTrue( is_bool( $output ) );
		$this->assertFalse( $output );

	}

	/**
	 * @covers ::is_file_path_valid
	 */
	public function test_is_file_path_valid_with_non_existent_file() {

		$output = PMC::is_file_path_valid( '/some/test/file/which/does/not/exist.txt' );

		$this->assertTrue( is_bool( $output ) );
		$this->assertFalse( $output );

	}

	/**
	 * @covers ::is_file_path_valid
	 */
	public function test_is_file_path_valid_with_invalid_path() {

		$file_path_to_test = sprintf(
			'%s/../../pmc-global-functions/tests/assets/dummy-text-file.txt',
			PMC_GLOBAL_FUNCTIONS_TESTS_ROOT
		);

		$output = PMC::is_file_path_valid( $file_path_to_test );

		$this->assertTrue( is_bool( $output ) );
		$this->assertFalse( $output );

	}

	/**
	 * @covers ::is_file_path_valid
	 */
	public function test_is_file_path_valid_for_success() {

		$file_path_to_test = sprintf(
			'%s/assets/dummy-text-file.txt',
			PMC_GLOBAL_FUNCTIONS_TESTS_ROOT
		);

		$output = PMC::is_file_path_valid( $file_path_to_test );

		$this->assertTrue( is_bool( $output ) );
		$this->assertTrue( $output );

	}

	/**
	 * @covers ::get_negative_int
	 *
	 * @dataProvider data_integers_to_negate
	 */
	public function test_get_negative_int( int $number_to_convert, int $number_expected ) : void {

		$output_to_test = PMC::get_negative_int( $number_to_convert );

		$this->assertEquals( $number_expected, $output_to_test );

	}

	/**
	 * @covers ::is_current_domain
	 */
	public function test_is_current_domain() : void {

		$this->assertTrue( PMC::is_current_domain( home_url() ) );
		$this->assertTrue( PMC::is_current_domain( home_url( 'test-post' ) ) );
		$this->assertTrue( PMC::is_current_domain( trailingslashit( home_url( 'test-post' ) ) ) );

		$this->assertFalse( PMC::is_current_domain( 'https://foobar.com/test-post/' ) );
		$this->assertFalse( PMC::is_current_domain( 'foobar' ) );
		$this->assertFalse( PMC::is_current_domain( '' ) );
		$this->assertFalse( PMC::is_current_domain( (string) null ) );
		$this->assertFalse( PMC::is_current_domain( (string) 123 ) );

	}

	/**
	 * @covers ::is_cxsense_bot
	 * @covers ::is_cxense_bot_function_string
	 */
	public function test_is_cxsense_bot() {

		$old_user_agent = $_SERVER['HTTP_USER_AGENT'];

		$vary_cache_on_function = @create_function( '', PMC::is_cxense_bot_function_string() ); // @codingStandardsIgnoreLine

		// Mock user agent.
		$_SERVER['HTTP_USER_AGENT'] = 'Custom-User-Agent';
		$this->assertFalse( PMC::is_cxsense_bot() );
		$this->assertFalse( $vary_cache_on_function() );

		// Mock user agent.
		$_SERVER['HTTP_USER_AGENT'] = 'cxensebot';
		$this->assertTrue( PMC::is_cxsense_bot() );
		$this->assertTrue( $vary_cache_on_function() );
		// Restore User Agent.
		$_SERVER['HTTP_USER_AGENT'] = $old_user_agent;


	}

	public function data_allowed_html() {
		return [
			[
				'assertArrayHasKey',
				'b',
				[ 'b' ],
				[],
			],
			[
				'assertEquals',
				wp_kses_allowed_html( 'post' ),
				[],
				[],
			],
			[
				'assertEquals',
				[ 'b' => [] ],
				[ 'b' ],
				[ 'b' => [] ],
				true,
			],
			[
				'assertArraySubset',
				[ 'style' => [] ],
				[],
				[ 'style' => [] ],
				false,
			],
			[
				'assertArraySubset',
				[
					'style' => [
						'type' => true,
					]
				],
				[],
				[
					'style' => [
						'type' => true,
					]
				],
				false,
			],
		];
	}

	/**
	 * @covers ::allowed_html
	 * @dataProvider data_allowed_html
	 *
	 * @param       $assert
	 * @param       $expects
	 * @param       $limit
	 * @param array $expand
	 * @param bool  $force
	 */
	public function test_allowed_html( $assert, $expects, $limit, $expand = [], $force = false ) {
		$output = PMC::allowed_html( 'post', $limit, $expand, $force );
		$this->$assert( $expects, $output );
	}

	/**
	 * @covers ::_kses_excerpt_allowed_html
	 */
	public function test__kses_excerpt_allowed_html() {
		$allowed_html = PMC::_kses_excerpt_allowed_html( [], '' );
		$this->assertSame( [], $allowed_html );

		$allowed_html = PMC::_kses_excerpt_allowed_html( [ 'a' => [] ], 'test' );
		$this->assertSame( [ 'a' => [] ], $allowed_html );

		$compare      = [ 'a', 'b', 'em', 'i', 's', 'strike', 'strong' ];
		$allowed_html = PMC::_kses_excerpt_allowed_html( [], 'pmc-excerpt' );

		foreach ( $compare as $key ) {
			$this->assertArrayHasKey( $key, $allowed_html );
		}
	}

}	//end class

//EOF
