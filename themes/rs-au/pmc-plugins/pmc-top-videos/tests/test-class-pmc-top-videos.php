<?php
/**
 * Unit test cases for PMC Top Videos
 *
 * @author  Dhaval Parekh <dhaval.parekh@rtcamp.com>
 *
 * @package pmc-top-videos
 */

namespace PMC_Top_Videos\Tests;

require_once( __DIR__ . '/class-base.php' );

/**
 * @coversDefaultClass PMC_Top_Videos
 */
class Test_PMC_Top_Videos extends Base {

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


}
