<?php

class Test_PMC_Geo_Uniques extends WP_UnitTestCase {

	function setUp() {
		// to speeed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;
		parent::setUp();
	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	public function test_implementation() {
		pmc_geo_add_location('AA');
		pmc_geo_add_location('gb');
		$this->assertTrue(pmc_geo_is_valid_location('us'));
		$this->assertTrue(pmc_geo_is_valid_location('gb'));
		$this->assertTrue(pmc_geo_is_valid_location('AA'));
		if ( defined('PMC_IS_VIP_GO_SITE') && PMC_IS_VIP_GO_SITE ) {
			$this->assertTrue(pmc_geo_is_valid_location('GB'));
			$this->assertTrue(pmc_geo_is_valid_location('US'));
			$this->assertTrue(pmc_geo_is_valid_location('AA'));
		}
	}
}
