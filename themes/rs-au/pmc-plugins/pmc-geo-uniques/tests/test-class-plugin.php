<?php

/**
 * @group pmc-geo-uniques
 *
 * Unit Test cases for Plugin.
 *
 * @author Vinod Tella <vtella@pmc.com>
 *
 * @since 2019-01-06 READS-1691
 *
 * @coversDefaultClass \PMC\Geo_Uniques\Plugin
 */

class Test_Class_Plugin extends WP_UnitTestCase {

	function setUp() {
		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;
		parent::setUp();
	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * @covers ::pmc_geo_add_eu_locations
	 */
	public function test_pmc_geo_add_eu_locations() {
		$this->assertTrue( pmc_geo_is_valid_location( 'nl' ) );
		$this->assertFalse( pmc_geo_is_valid_location( 'in' ) );
	}

	/**
	 * @covers ::pmc_geo_get_region_code
	 */
	public function test_pmc_geo_get_region_code() {
		$this->assertEquals( 'other', \PMC\Geo_Uniques\Plugin::get_instance()->pmc_geo_get_region_code() );
	}

	/**
	 * @covers ::pmc_geo_add_default_locations
	 */
	public function test_pmc_geo_add_default_locations() {
		$this->assertTrue( pmc_geo_is_valid_location( 'us' ) );
	}
}
