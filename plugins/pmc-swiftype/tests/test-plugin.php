<?php

use \PMC\Unit_Test\Utility;
use \PMC\Unit_Test\Mock\Mocker;


/**
 * @group pmc-swiftype
 *
 * Unit tests for class PMC\Swiftype\Plugin
 *
 * @requires PHP 5.6
 * @coversDefaultClass \PMC\Swiftype\Plugin
 */
class Tests_Class_PMC_Swiftype_Plugin extends WP_UnitTestCase {

	/**
	 * @var \PMC\Unit_Test\Mock\Mocker
	 */
	protected $_mocker;
	protected $_instance;

	public function setUp() {

		// create mocker instance
		$this->_mocker   = new Mocker();
		$this->_instance = \PMC\Unit_Test\Utility::get_hidden_static_property( 'PMC\Swiftype\Plugin', '_instance' )['PMC\Swiftype\Plugin'];

		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;
		parent::setUp();

		// do not report on warning to avoid unit test reporting on: Cannot modify header information - headers already sent by'
		// un-comment code below to fix headers already sent for test that require html output and redirect, etc.
		// error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

	}

	public function test_get_instance() {
		$this->assertInstanceOf( 'PMC\Swiftype\Plugin', $this->_instance );
		return $this->_instance;
	}

	/**
	 * Test actions and filters.
	 *
	 * @covers ::__construct
	 */
	public function test__construct() {

		$filters = [
			'after_setup_theme'         => 'load_plugin_textdomain',
			'pmc_swiftype_date_filters' => 'default_date_filters',
			'robots_txt'                => 'set_crawl_delay',
			'widgets_init'              => 'register_widget',
			'wp_enqueue_scripts'        => 'register_scripts',
			'wp_footer'                 => 'template_partials',
			'wp_head'                   => 'add_meta_tags',
		];

		foreach ( $filters as $key => $value ) {
			$this->assertGreaterThanOrEqual(
				10,
				has_filter( $key, array( $this->_instance, $value ) ),
				sprintf( 'PMC\Swiftype\Plugin::__construct failed registering filter/action "%1$s" to PMC\Swiftype\Plugin::%2$s', $key, $value )
			);
		}
	}

	public function array_keys() {
		return array(
			'engine_key',
			'redirect_to',
			'home_url',
			'placeholder_image',
			'image_size',
			'autocomplete',
			'author_list',
			'date_filters',
			'meta_tags',
			'sort_field',
			'sort_direction',
		);
	}

	/**
	 * Test defaults.
	 * @covers ::defaults
	 */
	public function test_defaults() {
		$defaults = $this->_instance->defaults();
		$keys     = $this->array_keys();

		foreach ( $keys as $key ) {
			$this->assertArrayHasKey( $key, $defaults );
		}

		$this->assertEquals( 'published_at', $defaults['sort_field'] );
		$this->assertEquals( 'desc', $defaults['sort_direction'] );
	}

	/**
	 * Test get settings.
	 * @covers ::get_settings
	 *
	 */
	public function test_get_settings() {
		add_filter(
			'pmc_swiftype_configs', function( $defaults ) {
				$defaults['engine_key'] = '12345';
				return $defaults;
			}
		);

		$settings = $this->_instance->get_settings();

		$this->assertTrue( '12345' === $settings['engine_key'] );
	}

	/**
	 * Test get best image from post.
	 *
	 * @depends test_get_instance
	 * @covers ::get_best_image
	 * @group pmc-phpunit-ignore-failed
	 * @NOTE: Fails with
	 * - 'http://wptests.pmcdev.local/wp-content/uploads/2019/01/test-image-6-300x211.jpg'
	 * + 'http://wptests.pmcdev.local/wp-content/uploads/2019/01/test-image-6.jpg'
	 */
	public function test_get_best_image() {
		// Test featured image
		$post     = $this->factory->post->create_and_get();
		$filename = __DIR__ . '/images/test-image.jpg';
		$contents = file_get_contents( $filename );
		$upload   = wp_upload_bits( basename( $filename ), null, $contents );
		$this->_make_attachment( $upload, $post->ID, true ); // true sets it as featured image
		$image = $this->_instance->get_best_image( $post->ID );
		$this->assertTrue( ! empty( get_the_post_thumbnail( $post->ID ) ) );
		$this->assertSame( $image, $upload['url'] );

		// Test pmc-gallery
		$post       = $this->factory->post->create_and_get();
		$filename   = __DIR__ . '/images/test-image.jpg';
		$contents   = file_get_contents( $filename );
		$upload     = wp_upload_bits( basename( $filename ), null, $contents );
		$attachment = $this->_make_attachment( $upload, 0, false ); // Set it up for pmc-gallery
		$gallery    = array( $attachment->ID );
		add_post_meta( $post->ID, 'pmc-gallery', $gallery );
		$image = $this->_instance->get_best_image( $post->ID );
		$this->assertSame( $image, $upload['url'] );

		// Test normal attachment
		$post     = $this->factory->post->create_and_get();
		$filename = __DIR__ . '/images/test-image.jpg';
		$contents = file_get_contents( $filename );
		$upload   = wp_upload_bits( basename( $filename ), null, $contents );
		$this->_make_attachment( $upload, $post->ID, false ); // false sets it as just an attachment
		$image = $this->_instance->get_best_image( $post->ID );
		$this->assertSame( $image, $upload['url'] );
	}

	/**
	 * Helper function to set an image attachment.
	 *
	 * @param      $upload
	 * @param int  $parent_id
	 * @param bool $featured
	 *
	 * @return mixed
	 */
	function _make_attachment( $upload, $parent_id = 0, $featured = false ) {
		$type = '';
		if ( ! empty( $upload['type'] ) ) {
			$type = $upload['type'];
		} else {
			$mime = wp_check_filetype( $upload['file'] );
			if ( $mime ) {
				$type = $mime['type'];
			}
		}
		$attachment = $this->factory->post->create_and_get(
			array(
				'post_title'     => basename( $upload['file'] ),
				'post_content'   => '',
				'post_type'      => 'attachment',
				'post_parent'    => $parent_id,
				'post_mime_type' => $type,
				'guid'           => $upload['url'],
			)
		);

		// Save the data
		$id = wp_insert_attachment( $attachment, $upload['file'], $parent_id );
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );

		// Set as featured image?
		if ( $featured ) {
			set_post_thumbnail( $parent_id, $attachment->ID );
		}

		return $attachment;
	}

	/**
	 * @covers ::add_meta_tags
	 */
	public function test_add_meta_tags() {

		$this->_mocker->mock_global_wp_post( $this );

		$this->_instance->settings = $this->_instance->get_settings();

		$meta_tags_output = Utility::buffer_and_return( array( $this->_instance, 'add_meta_tags' ) );

		$this->assertNotEmpty( $meta_tags_output );

		unset( $meta_tags_output );

		add_filter( 'pmc_swiftype_plugin_add_meta_tags_short_circuit', '__return_true' );

		$meta_tags        = $this->_instance->add_meta_tags();
		$meta_tags_output = Utility::buffer_and_return( array( $this->_instance, 'add_meta_tags' ) );

		$this->assertEmpty( $meta_tags );
		$this->assertEmpty( $meta_tags_output );

		unset( $meta_tags_output, $meta_tags );

		remove_filter( 'pmc_swiftype_plugin_add_meta_tags_short_circuit', '__return_true' );

		$this->_mocker->restore_global_wp_post();
		$this->_mocker->maybe_mock_empty_global_wp_query();

		global $coauthors_plus;

		$user1 = $this->factory->user->create_and_get(
			[
				'role' => 'administrator',
			]
		);

		$user2 = $this->factory->user->create_and_get(
			[
				'role' => 'administrator',
			]
		);

		$post = $this->factory->post->create_and_get(
			[
				'post_type'   => 'post',
				'post_author' => $user1->ID,
			]
		);

		$coauthors_plus->add_coauthors(
			$post->ID,
			[
				$user1->data->user_nicename,
				$user2->data->user_nicename,
			]
		);

		$this->_mocker->mock_global_wp_post( $this, [], $post );

		$output = Utility::buffer_and_return( 'do_action', [ 'wp_head' ] );

		$this->assertContains(
			'<meta class="swiftype" name="author" data-type="string" content="' . $user1->data->user_login . '" />',
			$output
		);
		$this->assertContains(
			'<meta class="swiftype" name="author" data-type="string" content="' . $user2->data->user_login . '" />',
			$output
		);
		$this->assertNotContains(
			'<meta class="swiftype" name="author" data-type="string" content="' . $user1->data->user_login . ', ' . $user2->data->user_login . '" />',
			$output
		);

		$this->_mocker->restore_global_wp_post();
		$this->_mocker->maybe_mock_empty_global_wp_query();

	}

	/**
	 * @covers ::filter_pmc_global_cheezcap_options
	 */
	public function test_filter_pmc_global_cheezcap_options() {
		$this->_instance = $this->test_get_instance();

		$options = array();
		$options = $this->_instance->filter_pmc_global_cheezcap_options( $options );

		// pmc_swiftype
		$this->assertInstanceOf( 'CheezCapDropdownOption', $options[0] );
		$this->assertEquals( 'pmc_swiftype', $options[0]->_key );
		$this->assertArraySubset( array( 'no', 'yes' ), $options[0]->options );
		$this->assertArraySubset( array( 'No', 'Yes' ), $options[0]->options_labels );

		// pmc_swiftype_date_options_specific_dates
		$this->assertInstanceOf( 'CheezCapDropdownOption', $options[1] );
		$this->assertEquals( 'pmc_swiftype_date_options_specific_dates', $options[1]->_key );
		$this->assertArraySubset( array( 'no', 'yes' ), $options[1]->options );
		$this->assertArraySubset( array( 'No', 'Yes' ), $options[1]->options_labels );

		// pmc_swiftype_sort_field
		$this->assertInstanceOf( 'CheezCapDropdownOption', $options[2] );
		$this->assertEquals( 'pmc_swiftype_sort_field', $options[2]->_key );
		$this->assertArraySubset( array( 'published_at-desc', '_score-desc', 'published_at-asc', 'comment_count-desc' ), $options[2]->options );
		$this->assertArraySubset( array( 'Published Date (newest first)', 'Relevance', 'Published Date (oldest first)', 'Most Commented' ), $options[2]->options_labels );

	}

	/**
	 * @covers ::get_default_sort_field
	 */
	public function test_get_default_sort_field() {
		$this->_instance = $this->test_get_instance();
		$sort_type       = $this->_instance->get_default_sort_field();

		// Check default value
		$this->assertEquals( 'published_at-desc', $sort_type, 'PMC\Swiftype\Plugin::get_default_sort_field(), Invalid default value' );
	}

	/**
	* test_set_crawl_delay | tests/test-plugin.php
	*
	* @since 2018-06-27 - Tests if the crawl delay text is output via the method
	* @author brandoncamenisch
	* @version 2018-06-27 - feature/WI-714:
	* - Tests if robots.txt has crawl limit output
	*
	* @covers ::set_crawl_delay
	*/
	public function test_set_crawl_delay() {
		// Call method simulating that we're public
		$robots_txt = $this->_instance->set_crawl_delay( '', true );
		$this->assertContains( 'Swiftbot', $robots_txt, 'robots.txt does not contain Swiftbot' );
		$this->assertContains( 'Crawl-delay', $robots_txt, 'robots.txt does not contain a Crawl-delay' );
	}

	public function test_main() {
		global $wp_query;

		$captured_url = false;

		add_filter(
			'wp_redirect', function( $redirect_url ) use ( &$captured_url ) {
				$captured_url = $redirect_url;
				return false;
			}
		);

		add_filter(
			'pmc_swiftype_configs', function( $configs ) {
				$configs['engine_key'] = 'unit-test';
				return $configs;
			}
		);

		$bots = apply_filters( 'pmc_seo_tweaks_robot_names', false );
		$this->assertTrue( in_array( 'st:robots', (array) $bots, true ) );

		$wp_query->is_404  = true;
		$wp_query->is_page = true;
		set_query_var( 'tag', 'test-test' );
		$term                     = wp_insert_term( 'test test', 'post_tag', [ 'slug' => 'test' ] );
		$wp_query->queried_object = (object) [
			'post_name' => 'results',
		];

		$bufs      = $this->_instance->tag_not_found_redirect();
		$term_link = get_term_link( $term['term_id'], 'post_tag' );
		$this->assertEquals( $term_link, $captured_url );

		$this->assertTrue( $this->_instance->is_enabled() );

		$bufs = \PMC\Unit_Test\Utility::simulate_wp_script_render();
		$this->assertContains( 'assets/css/datepicker.css', $bufs );
		$this->assertContains( 'https://s.swiftypecdn.com/cc/unit-test.js', $bufs );
		$this->assertContains( 'assets/js/SwiftypeComponents', $bufs );
		$this->assertContains( 'SwiftypeConfigs', $bufs );
		$this->assertContains( 'assets/js/configuration', $bufs );

	}

}
// EOF
