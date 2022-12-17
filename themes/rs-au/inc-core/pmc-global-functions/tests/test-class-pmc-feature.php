<?php
namespace PMC\Global_Functions\Tests;

use PMC_Feature;
use Exception;
use PMC;

/**
 * @group pmc-global-functions
 *
 * Unit test for class PMC_Feature
 *
 * Author: Amit Gupta <agupta@pmc.com>
 *
 * @requires PHP 5.3
 * @coversDefaultClass PMC_Feature
 */
class Test_Class_PMC_Feature extends Base {

	const JWPLAYER_CONTENT_MASK = 'video-cdn.tvline.com';

	protected $_test_uri;

	function setUp() {
		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;

		/*
		 * Load any plugins needed for tests of this particular class
		 */
		$this->_load_external_plugins();

		parent::setUp();

		remove_all_actions( 'deprecated_function_run' );
		remove_all_actions( 'deprecated_argument_run' );
		remove_all_actions( 'deprecated_hook_run' );

	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	protected function _load_external_plugins() {
		/*
		 * Load JWPlayer vip plugin
		 */
		pmc_load_plugin( 'jwplayer' );

		/*
		 * Add option 'jwplayer_content_mask'
		 */
		update_option( 'jwplayer_content_mask', self::JWPLAYER_CONTENT_MASK );
	}

	/**
	 * Adding helper hook function on wp_redirect which will throw exception with valid and invalid error message.
	 * @param $location
	 * @param $status
	 *
	 * @throws Exception
	 */
	public function assert_redirect_destination_is_correct( $location, $status ) {

		$this->assertEquals(
			301,
			$status,
			sprintf( 'PMC_Feature::pii_redirect did not set redirect status to 301 for the URI "%s"', $this->_test_uri )
		);
		if ( ! empty( $location ) ) {
			throw new Exception('Valid Redirect URL');
		} else {
			throw new Exception('Invalid Redirect URL');
		}

	}

	/**
	 * @covers ::load()
	 */
	public function test_if_hooks_are_setup() {
		$hooks = array(
			'pre_option_jwplayer_content_mask' => 'maybe_override_jwplayer_content_mask',
			'rest_api_allowed_post_types' => 'filter_allow_custom_post_types_in_rest_api',
			'the_content' => 'filter_replace_control_characters',
			'the_content_feed' => 'filter_replace_control_characters',
			'the_excerpt_rss' => 'filter_replace_control_characters',
			'the_excerpt' => 'filter_replace_control_characters',
		);

		foreach ( $hooks as $hook => $listener ) {

			$this->assertGreaterThanOrEqual(
				10,
				has_filter( $hook, array( 'PMC_Feature', $listener ) ),
				sprintf( 'PMC_Feature::load() failed to register hook "%1$s" to PMC_Feature::%2$s()', $hook, $listener )
			);

		}


		$actions = array(
			array(
				'name'     => 'admin_print_scripts',
				'priority' => 1,
				'listener' => 'top_js_loader',
			),
			array(
				'name'     => 'wp_print_scripts',
				'priority' => 1,
				'listener' => 'top_js_loader',
			),
			array(
				'name'     => 'wp_enqueue_scripts',
				'priority' => 10,
				'listener' => 'enqueue_stuff',
			),
			array(
				'name'     => 'init',
				'priority' => 10,
				'listener' => 'init',
			),
			array(
				'name'     => 'init',
				'priority' => 99,
				'listener' => 'register_scripts',
			),
			array(
				'name'     => 'jetpack_open_graph_tags',
				'priority' => 15,
				'listener' => 'open_graph_tags',
			),
			array(
				'name'     => 'transition_post_status',
				'priority' => 9,
				'listener' => 'pmc_selective_pushpress_ping',
			),
			array(
				'name'     => 'pmc_tags_head',
				'priority' => 10,
				'listener' => 'pmc_amazon_script_head',
			),
			array(
				'name'     => 'pmc_tags_head',
				'priority' => 10,
				'listener' => 'pmc_hotjar_script_head',
			),
			array(
				'name'     => 'pmc_cheezcap_groups',
				'priority' => 10,
				'listener' => 'cheezcap_groups',
			),
			array(
				'name'     => 'custom_metadata_manager_init_metadata',
				'priority' => 10,
				'listener' => 'init_custom_fields',
			),
			array(
				'name'     => 'wp_head',
				'priority' => 0,
				'listener' => 'disable_widont',
			),
			array(
				'name'     => 'wp_head',
				'priority' => 9999,
				'listener' => 're_enable_widont',
			),
			array(
				'name'     => 'wp_head',
				'priority' => 10,
				'listener' => 'add_meta_tags',
			),
			array(
				'name'     => 'template_redirect',
				'priority' => 10,
				'listener' => 'redirect_to_lowercase',
			),
		);

		foreach ( $actions as $hook ) {

			$this->assertGreaterThanOrEqual(
				$hook['priority'],
				has_action( $hook['name'], array( 'PMC_Feature', $hook['listener'] ) ),
				sprintf( 'PMC_Feature::load() failed to register hook "%1$s" to PMC_Feature::%2$s()', $hook['name'], $hook['listener'] )
			);

		}
	}

	/**
	 * @covers ::maybe_override_jwplayer_content_mask()
	 */
	public function test_jwplayer_content_mask_shortcircuit_on_non_https_url() {
		unset( $_SERVER['HTTPS'] );
		$this->assertEquals( self::JWPLAYER_CONTENT_MASK, PMC_Feature::maybe_override_jwplayer_content_mask( self::JWPLAYER_CONTENT_MASK ), 'Unable to get JWPlayer Content Mask working on non HTTPS URL' );
	}

	/**
	 * @covers ::maybe_override_jwplayer_content_mask()
	 */
	public function test_jwplayer_content_mask_shortcircuit_on_https_url() {
		/*
		 * simulate HTTPS for the benefit of PMC::is_https()
		 */
		$_SERVER['HTTPS'] = 1;

		$this->assertEquals( JWPLAYER_CONTENT_MASK, PMC_Feature::maybe_override_jwplayer_content_mask( self::JWPLAYER_CONTENT_MASK ), 'Unable to get JWPlayer Content Mask override on HTTPS URL' );
	}

	/**
	 * @covers ::maybe_override_jwplayer_content_mask()
	 */
	public function test_jwplayer_content_mask_shortcircuit_on_media_options_page() {
		/*
		 * simulate media options screen in wp-admin
		 */
		set_current_screen( 'options-media' );

		$this->assertEquals( self::JWPLAYER_CONTENT_MASK, PMC_Feature::maybe_override_jwplayer_content_mask( self::JWPLAYER_CONTENT_MASK ), 'Unable to get JWPlayer Content Mask override on media options page' );
	}

	public function test_replacing_control_characters(){

		$string = PMC::replace_control_characters( 'Test \xA0 string with \x00 control characters' );

		$this->assertTrue($string == 'Test &nbsp; string with  control characters' );

	}

	/**
	 * @covers ::pii_redirect()
	 */
	public function test_pii_redirect() {

		//Clean URL test
		$this->_test_uri = '/?var1=123';
		$this->go_to( $this->_test_uri );
		$this->assertNull( PMC_Feature::pii_redirect() );

		//URL with PII information. ex:email
		$this->_test_uri = '/?dd=abc@xyz.com';
		$this->go_to( $this->_test_uri );

		$this->assert_redirect_to( '/', function() {
			\PMC_Feature::pii_redirect();
		}, 301 );

	}


	/**
	 * @covers ::pmc_sanitize_upload_filename
	 */
	public function test_pmc_sanitize_upload_filename() {

		$string_file = 'I am a string and not an array.';
		$same_file   = PMC_Feature::pmc_sanitize_upload_filename( $string_file );
		// $string_file should be returned as is
		$this->assertEquals( $string_file, $same_file);

		$file = array(
			'image'    =>  array(
				'name'      =>  'dummy-image-with-clean-name.jpg',
				'tmp_name'  => __DIR__ . '/assets/dummy-image-with-clean-name.jpg',
				'type'      =>  'image/jpeg',
				'size'      =>  499,
				'error'     =>  0
			)
		);

		// action exists
		$this->assertEquals( 10, has_action( 'wp_handle_upload_prefilter', 'PMC_Feature::pmc_sanitize_upload_filename' ) );

		$upload = $file['image'];

		$same_upload_name = PMC_Feature::pmc_sanitize_upload_filename( $upload );
		// name not changed
		$this->assertEquals( 'dummy-image-with-clean-name.jpg', $same_upload_name['name'] );

		$invalid_name = 'dummy-image-with-™-in-name.jpg';
		$tmp_file     = sys_get_temp_dir() . '/'. $invalid_name;
		if ( file_exists( $tmp_file ) ) {
			unlink( $tmp_file );
		}
		copy( __DIR__ . '/assets/dummy-image-with-clean-name.jpg', $tmp_file );

		$upload['name']     = $invalid_name;
		$upload['tmp_name'] = $tmp_file;

		// check that TM was removed
		$changed_upload_name = PMC_Feature::pmc_sanitize_upload_filename( $upload );
		$this->assertEquals( 'dummy-image-with-in-name.jpg', $changed_upload_name['name'] );

		unlink( $tmp_file );

	}

	/**
	 * @covers ::block_spam_referers
	 */
	public function test_block_spam_referers() {

		global $_SERVER;

		unset( $_SERVER['HTTP_REFERER'] );
		$outut = PMC_Feature::block_spam_referers();
		$this->assertNull( $outut );

		// Set HTTP_REFERER to valid URL.
		$_SERVER['HTTP_REFERER'] = 'https://pmc.com/';

		$this->assertFalse( PMC_Feature::block_spam_referers() );

		// Set HTTP_REFERER to on of blocked URL.
		$_SERVER['HTTP_REFERER'] = 'http://topbwvukgual.xyz/';

		$ouptut = \PMC\Unit_Test\Utility::buffer_and_return( function() {
			$this->assertTrue( PMC_Feature::block_spam_referers( true ) );
		} );

		$this->assertContains( '<p>Access forbidden. If you believe you have reached this page in error please refresh the page and try again.</p>', (string) $ouptut );

		// Set HTTP_REFERER to on of blocked URL.
		$_SERVER['HTTP_REFERER'] = 'http://trivomphjugti.net/';

		$ouptut = \PMC\Unit_Test\Utility::buffer_and_return( function() {
			$this->assertTrue( PMC_Feature::block_spam_referers( true ) );
		} );

		$this->assertContains( '<p>Access forbidden. If you believe you have reached this page in error please refresh the page and try again.</p>', (string) $ouptut );

		unset( $_SERVER['HTTP_REFERER'] );
	}

	/**
	 * Unit test for all deprecated & removed functions
	 */
	public function test_deprecated_functions_removed() {
		// Make sure PMC_Feature::make_link_https exists, but not PMC_Feature::make_link_http
		$this->assertTrue( method_exists ( 'PMC_Feature', 'make_link_https' ) );
		$this->assertFalse( method_exists ( 'PMC_Feature', 'make_link_http' ) );
		$this->mock_in_admin( true );
		do_action( 'admin_init');
		//
		$this->assertEquals( 'https://unitest', apply_filters( 'preview_post_link', 'https://unitest' ) );
		$this->assertEquals( 'https://unitest', apply_filters( 'preview_page_link', 'https://unitest' ) );
	}

}	//end class


//EOF
