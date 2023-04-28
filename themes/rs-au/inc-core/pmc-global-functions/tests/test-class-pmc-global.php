<?php
namespace PMC\Global_Functions\Tests;

use PMC_Global;
use PMC_Feature;
use PMC_Ajax;
use PMC;
use PMC_CheezCapAjaxButton;
use PMC_Shortcode;

/**
 * @group pmc-global-functions
 *
 * PHPUnit tests for class PMC_Global
 *
 * @since 2015-07-14 PPT-5077 Archana Mandhare
 *
 * @requires PHP 5.3
 * @coversDefaultClass PMC_Global
 */
class Tests_Class_PMC_Global extends Base {

	function setUp() {
		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;

		parent::setUp();

		remove_all_actions( 'deprecated_function_run' );
		remove_all_actions( 'deprecated_argument_run' );
		remove_all_actions( 'deprecated_hook_run' );

	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * @covers ::_init()
	 */
	public function test_init() {
		$pmc_global = PMC_Global::get_instance();

		$this->assertNotEmpty( $pmc_global, 'Error retrieving instance of PMC_Global' );

		$filters = array(
			'body_class'                  => 'filter_body_class',
		);

		foreach ( $filters as $key => $value ) {
			$this->assertGreaterThanOrEqual(
				10,
				has_filter( $key, array( $pmc_global, $value ) ),
				sprintf( 'PMC_Global::_init failed registering filter/action "%1$s" to PMC_Global::%2$s', $key, $value )
			);
		}

	}

	/**
	 * @covers PMC_Feature::filter_allow_custom_post_types_in_rest_api()
	 */
	public function test_filter_allow_custom_post_types_in_rest_api() {
		$keys = array(
			'post',
			'page',
		);

		$allowed_post_types = PMC_Feature::filter_allow_custom_post_types_in_rest_api( $keys );

		foreach ( $allowed_post_types as $post_types ) {
			$this->assertTrue( post_type_exists( $post_types ) );
		}

	}

	/**
	 * @covers \pmc_global_functions_url
	 * @group pmc-phpunit-ignore-failed

	 * @NOTE: test fails as a result of the path needing to be updated
	 * + [path] => /wp-content/plugins/var/www/html/wp-content/themes/vip/pmc-plugins/pmc-global-functions
	 *
	 */
	public function test_pmc_global_functions_url() {

		$this->go_to( '/' );

		// we need to unset to prevent is_admin returning true that may set by other test case
		unset($GLOBALS['current_screen']);

		// valid the pmc_global_functions_url function
		$urls = parse_url( pmc_global_functions_url() );
		$this->assertArraySubset( [
			'host' => WP_TESTS_DOMAIN,
			'path' => '/wp-content/themes/vip/pmc-plugins/pmc-global-functions/',
		], $urls );

		$urls = parse_url( pmc_global_functions_url( '/unitest') );
		$this->assertArraySubset( [
			'host' => WP_TESTS_DOMAIN,
			'path' => '/wp-content/themes/vip/pmc-plugins/pmc-global-functions/unitest',
		], $urls );

		// validating related events call the pmc_global_functions_url function
		add_filter( 'pmc_remove_standard_wp_image_sizes', '__return_true' );
		PMC_Ajax::get_instance();
		PMC_Shortcode::pmc_create_boombox_embed_script( [] );
		new PMC_CheezCapAjaxButton( 'name', 'desc', 'id' );
		PMC::enqueue_ab_test_js();
		PMC::enqueue_socialite_js();
		PMC::enqueue_chosen();
		PMC::enqueue_sticky_rightrail();
		wp_enqueue_script( 'pmc-hooks' );

		// simulate wp script render
		ob_start();
		do_action( 'wp_enqueue_scripts' );
		do_action( 'admin_enqueue_scripts' );
		get_header();
		get_footer();
		$bufs = ob_get_clean();

		// verify these assets reference is correctly output
		$contains = [
				'/css/pmc-boombox.css',
				'/css/pmc-cheezcap-ajax-button.css',
				'/css/pmc-global-overrides.css',
				'/chosen/chosen.css',
				'/chosen/chosen.jquery.js',
				'/js/pmc-ab-test.js',
				'/js/pmc-ajax.js',
				'/js/pmc-cheezcap-ajax-button.js',
				'/js/pmc-images.js',
				'/js/pmc-hooks.js',
				'/js/pmc-socialite-plugin.js',
				'/js/pmc-sticky-rightrail.js',
				'/js/socialite.js',
			];

		foreach( $contains as $path ) {
			$url = pmc_global_functions_url( $path );
			$this->assertContains( $url, $bufs );
		}

	}

}


//EOF
