<?php
/**
 * @group pmc-adm
 */
use PMC\Unit_Test;

class Test_Class_PMC_Ads_Time_Gap_Trigger extends WP_UnitTestCase {
	function setUp() {
		$this->_mocker = new Unit_Test\Mock\Mocker;
		\PMC_Cheezcap::get_instance()->register();
		self::$ignore_files = true;
		parent::setUp();
	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * @covers PMC_Ads_Time_Gap_Trigger::filter_pmc_adm_ad_groups
	 */
	public function test_filter_pmc_adm_ad_groups(){
		$instance = PMC_Ads_Time_Gap_Trigger::get_instance();

		$hooks = array(
			'pmc-adm-ad-groups'            => 'filter_pmc_adm_ad_groups',
			'pmc-tags-top'                 => 'action_pmc_ads_time_gap_tags',
			'pmc_global_cheezcap_options'  => 'filter_pmc_global_cheezcap_options',
		);

		foreach ( $hooks as $key => $value ) {
			$this->assertNotEmpty(
				has_filter( $key, array( $instance, $value ) ) ,
				sprintf('PMC_Ads_Time_Gap_Trigger::admin_init failed registering action "%1$s" to PMC_Ads_Time_Gap_Trigger::%2$s', $key, $value )
			);
		}

		$groups = apply_filters( 'pmc-adm-ad-groups', array() );
		$this->assertNotEmpty( $groups );
		$this->assertArrayHasKey( 'time_gap_ads', $groups );

	}

	/**
	 * @covers PMC_Ads_Time_Gap_Trigger::action_pmc_ads_time_gap_tags
	 */
	public function test_action_pmc_ads_time_gap_tags(){
		$instance = PMC_Ads_Time_Gap_Trigger::get_instance();
		// Mocking Ad with location amp-mid-article.
		$ad = array(
			'title'         => 'HP Test Ad',
			'provider'      => 'google-publisher',
			'device'        => array( 'Desktop', 'Mobile', 'Tablet' ),
			'ad-width'      => '[[300,250]]',
			'width'         => 300,
			'height'        => 250,
			'location'      => 'native-river-ad',
			'div-id'        => 'test-homepage-native-river-div',
			'ad_conditions' => '',
		);

		$post_id = $this->factory->post->create( array(
			'post_type'    => 'pmc-ad',
			'post_content' => json_encode( $ad ),
		) );
		update_post_meta( $post_id, '_ad_location', 'native-river-ad' );
		update_post_meta( $post_id, '_ad_group', 'time_gap_ads' );
		$GLOBALS['cap']->pmc_ads_time_gap_trigger_ads_value = '2';
		$instance = PMC_Ads::get_instance();
		$instance->add_provider(new Google_Publisher_Provider('unittest') );

		apply_filters( 'pmc_global_cheezcap_options', array() );
		ob_start();
		do_action( 'pmc-tags-top' );
		$html = ob_get_clean();
		$this->assertNotEmpty( $html );
		$this->assertContains( 'pmc_adm_has_time_gap_ads', $html );
	}

	/**
	 * @covers PMC_Ads_Time_Gap_Trigger::filter_pmc_global_cheezcap_options
	 */
	public function test_filter_pmc_global_cheezcap_options() {
		$instance = PMC_Ads_Time_Gap_Trigger::get_instance();

		$this->assertEquals( 10, has_filter( 'pmc_global_cheezcap_options', array( $instance, 'filter_pmc_global_cheezcap_options' ) ) );

		$options = $instance->filter_pmc_global_cheezcap_options( array() );
		$this->assertNotEmpty( $options );
		$this->assertEquals( 1, count( $options ) );
	}

}// EOF