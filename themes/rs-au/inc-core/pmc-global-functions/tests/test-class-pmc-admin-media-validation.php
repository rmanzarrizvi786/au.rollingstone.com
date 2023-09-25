<?php
/**
 * Unit tests for \PMC\Global_Functions\PMC_Admin_Media_Validation
 *
 * @author Vishal Dodiya <vishal.dodiya@rtcamp.com>
 *
 * @since 2018-08-17 READS-1409
 *
 * @package pmc-global-functions
 */

namespace PMC\Global_Functions\Tests;

/**
 * @coversDefaultClass \PMC\Global_Functions\PMC_Admin_Media_Validation
 */
class Test_PMC_Admin_Media_Validation extends Base {

	function setUp() {

		// to speed up unit test, we bypass files scanning on upload folder.
		self::$ignore_files = true;

		parent::setUp();

	}

	/**
	 * To prevent all upload files from deletion, since set $ignore_files = true.
	 * we override the function and do nothing here.
	 */
	function remove_added_uploads() {}

	/**
	 * @covers ::enqueue_scripts
	 */
	public function test_enqueue_scripts() {

		set_current_screen( 'admin_enqueue_scripts' );

		wp_enqueue_media(); // Mock Enqueue media to assert validation script enqueue.

		do_action( 'admin_enqueue_scripts', '' );

		$this->assertFalse( wp_script_is( 'pmc_admin_media_validation_js', 'enqueued' ) );
		$this->assertFalse( wp_style_is( 'pmc_admin_media_validation_css', 'enqueued' ) );

		do_action( 'admin_enqueue_scripts', 'post.php' );

		$this->assertTrue( wp_script_is( 'pmc_admin_media_validation_js', 'enqueued' ) );
		$this->assertTrue( wp_style_is( 'pmc_admin_media_validation_css', 'enqueued' ) );

		// Dequeue scripts to assert enqueue on post-new.php page.
		wp_dequeue_script( 'pmc_admin_media_validation_js' );
		wp_dequeue_style( 'pmc_admin_media_validation_css' );

		$this->assertFalse( wp_script_is( 'pmc_admin_media_validation_js', 'enqueued' ) );
		$this->assertFalse( wp_style_is( 'pmc_admin_media_validation_css', 'enqueued' ) );

		do_action( 'admin_enqueue_scripts', 'post-new.php' );

		$this->assertTrue( wp_script_is( 'pmc_admin_media_validation_js', 'enqueued' ) );
		$this->assertTrue( wp_style_is( 'pmc_admin_media_validation_css', 'enqueued' ) );

	}

}
