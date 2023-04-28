<?php
/**
 * @group pmc-adm
 */
use PMC\Unit_Test;

class Test_Class_PMC_Ads_Txt extends WP_UnitTestCase {
	function setUp() {
		error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
		\PMC_Cheezcap::get_instance()->register();
		self::$ignore_files = true;
		parent::setUp();
	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * @covers PMC_Ads_Txt::pmc_ads_txt_init
	 */
	public function ctest_pmc_ads_txt_init(){
		$instance = PMC_Ads_Txt::get_instance();

		$hooks = array(
			'init' => 'pmc_ads_txt_init',
			'parse_query' => 'pmc_ads_txt_load',
		);

		foreach ( $hooks as $key => $value ) {
			$this->assertNotEmpty(
				has_filter( $key, array( $instance, $value ) ) ,
				sprintf('PMC_Ads_Txt::admin_init failed registering action "%1$s" to PMC_Ads_Txt::%2$s', $key, $value )
			);
		}

		$rewrite_rules = get_option( 'rewrite_rules' );
		$this->assertArrayHasKey( 'ads\.txt$', $rewrite_rules );
	}

	/**
	 * @covers PMC_Ads_Txt::pmc_ads_txt_load
	 */
	public function test_is_ads_txt() {
		$this->go_to('/');
		$instance = PMC_Ads_Txt::get_instance();
		$this->assertFalse( $instance->is_ads_txt() );

		$this->go_to('/test');
		$this->assertFalse( $instance->is_ads_txt() );

		set_query_var( 'ads_txt', '1' );
		$this->assertTrue( $instance->is_ads_txt() );
	}

}
// EOF