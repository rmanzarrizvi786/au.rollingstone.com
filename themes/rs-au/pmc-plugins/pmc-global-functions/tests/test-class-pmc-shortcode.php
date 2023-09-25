<?php
namespace PMC\Global_Functions\Tests;
use PMC_Shortcode;

/**
 * @group pmc-global-functions
 * PHPUnit tests for class PMC_Shortcode
 *
 * @requires PHP 5.3
 */
class Tests_PMC_Shortcode extends Base {

	function setUp() {
		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;

		parent::setUp();
	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	public function test_facebook_like_box() {
		PMC_Shortcode::load();
		$bufs = PMC_Shortcode::facebook_like_box( [
				'href'         => 'http://www.facebook.com/unitest',
				'width'        => 'width',
				'height'       => 'height',
				'show_faces'   => 'show_faces',
				'border_color' => 'border_color',
				'stream'       => 'stream',
				'header'       => 'header',
				'eventracking' => 'eventracking',
				'appid'		   => 'appid',
			] );

		$this->assertContains( "<div class='fb-like-box' data-href='http://www.facebook.com/unitest' data-width='width' data-height='height' data-show-faces='show_faces' data-border-color='border_color' data-stream='stream' data-header='header'>", $bufs );
	}
}
