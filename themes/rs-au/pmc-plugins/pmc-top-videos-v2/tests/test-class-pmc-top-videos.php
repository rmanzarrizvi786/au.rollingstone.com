<?php
/**
 * Unit test cases for PMC Top Videos
 *
 * @author  Dhaval Parekh <dhaval.parekh@rtcamp.com>
 *
 * @package pmc-top-videos
 */

namespace PMC\Top_Videos_V2\Tests;

use PMC\Top_Videos_V2\PMC_Top_Videos;

require_once( __DIR__ . '/class-base.php' );

/**
 * @coversDefaultClass \PMC\Top_Videos_V2\PMC_Top_Videos
 */
class Test_PMC_Top_Videos extends Base {

	/**
	 * @var \PMC_Top_Videos
	 */
	protected $_instance;

	public function setUp() {

		parent::setUp();

		$this->_instance = PMC_Top_Videos::get_instance();
	}

	/**
	 * @covers ::whitelist_post_type_for_sitemaps
	 */
	public function test_whitelist_post_type_for_sitemaps() {

		$input = [
			'post',
			'page',
		];

		$output = apply_filters( 'pmc_sitemaps_post_type_whitelist', $input );

		$this->assertContains( 'pmc_top_video', $output );
	}

	/**
	 * @covers ::action_widgets_init
	 */
	public function test_action_widgets_init() {

		$this->_instance->action_widgets_init();
		// Make sure the class exists.
		$this->assertTrue( class_exists( \PMC\Top_Videos_V2\Video_Featured::class ) );
	}

	/**
	 * @covers ::load_frontend_assets
	 */
	public function test_load_frontend_assets() {

		$this->_instance->load_frontend_assets();
		$this->assertTrue( wp_style_is( 'pmc-top-videos-frontend-css' ) );
	}

}
