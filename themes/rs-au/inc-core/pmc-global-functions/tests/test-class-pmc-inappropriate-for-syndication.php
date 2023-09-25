<?php
namespace PMC\Global_Functions\Tests;
use PMC\Unit_Test\Utility;
use PMC\Global_Functions\Crypto;

class Test_PMC_Inappropriate_For_Syndication extends Base {

	protected function _load_plugin() {
	}

	public function test_plugin() {
		global $wp_query;

		if ( ! defined( 'PMC_INAPPROPRIATE_FOR_SYNDICATION_FLAG' ) ) {
			define( 'PMC_INAPPROPRIATE_FOR_SYNDICATION_FLAG', true );
		}

		$this->mock_maybe_mock_empty_global_wp_query();
		$wp_query->is_feed = true;
		\PMC_Inappropriate_For_Syndication::get_instance()->exclude_inappropriate_for_syndication_posts( $wp_query );
		$this->assertTrue( isset( $GLOBALS['pmc_inappropriate_for_syndication'] ) );
		$this->assertTrue( $GLOBALS['pmc_inappropriate_for_syndication'] );

	}

}