<?php
/**
 * @group pmc-adm
 * @group pmc-adm-ads-dfp-prestitial
 */
class Test_Class_PMC_Ads_Dfp_Prestitial extends WP_UnitTestCase {

	function setUp() {
		// to speeed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;
		parent::setUp();
		// do not report on warning to avoid unit test reporting on: Cannot modify header information - headers already sent by'
		error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

		// Ensure that the PMC ADs class caches are not placed in persistent storage.
		// We're effectively telling the class not to cache anything during testing.
		// This way, we can create new ad units in tests and expect to always receive
		// those same ad units (not the cached ads from the first test).
		wp_cache_add_non_persistent_groups( PMC_Ads::cache_group );
	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * @covers PMC_Ads_Dfp_Prestitial::_init
	 * @covers PMC_Ads_Dfp_Prestitial::action_init
	 */
	public function test_init(){
		$instance = PMC_Ads_Dfp_Prestitial::get_instance();

		$filters = array(
			'init' => 'action_init',
            'pmc-tags-top' => 'action_add_dfp_prestitial_markup',
		);

		foreach ( $filters as $key => $value ) {
			$this->assertNotEquals(
				false,
				has_filter( $key, array( $instance, $value ) ) ,
				sprintf('PMC_Ads_Dfp_Prestitial::_init or action_init failed registering filter/action "%1$s" to PMC_Ads_Dfp_Prestitial::%2$s', $key, $value )
			);
		}

	}

	/**
	 * @depends test_init
	 */
    function test_no_ad_for_prestitial() {
        PMC_Cheezcap::get_instance()->register();

        $instance = PMC_Ads::get_instance();
        $instance->add_provider(new Google_Publisher_Provider('unittest') );
        $instance->add_locations( array(
            'dfp-prestitial'       => 'DFP Prestitial',
        ));

        $html = $instance->render_ads('dfp-prestitial','',false);
        $this->assertNotContains('<div id="unittest-uid0" class=""></div>',$html,'There should not be prestitial oop ad slot div');

        ob_start();
        do_action('wp_footer');

        $html = ob_get_clean();

        ob_start();
        PMC_Ads_Dfp_Prestitial::get_instance()->action_add_dfp_prestitial_markup();
        $html = ob_get_clean();
        $this->assertNotContains('<div id="prestitial-ad-section"',$html,'There should not be prestitial oop ad container div');

    }

	/**
	 * @depends test_init
	 * @depends test_no_ad_for_prestitial
	 */
	function test_structure() {
		PMC_Cheezcap::get_instance()->register();

		$instance = PMC_Ads::get_instance();
		$instance->add_provider(new Google_Publisher_Provider('unittest') );
		$instance->add_locations( array(
            'dfp-prestitial'       => 'DFP Prestitial',
		));

		$ad = array(
			'provider'     => 'google-publisher',
			'device'       => array('Desktop','Mobile','Tablet'),
			'slot-type'    => 'oop',
			'zone'         => 'unittest',
			'sitename'     => 'unittest',
			'ad-width'     => '[[1,1]]',
			'dynamic_slot' => 'unittest',
			'width'        => 1,
			'height'       => 1,
			'location'     => 'dfp-prestitial',
			'div-id'       => 'unittest',
		);

		$p = $this->factory->post->create(array(
			'post_type'    => 'pmc-ad',
			'post_content' => json_encode( $ad ),
		) );

		update_post_meta( $p, '_ad_location', 'dfp-prestitial' );
		$ads = $instance->get_ads_to_render( 'dfp-prestitial' );
		$this->assertArraySubset(
			array(
				'1x1-oop' => array(
					'zone'      => 'unittest',
					'slot-type' => 'oop',
					'location'  => 'dfp-prestitial',
				),
			),
			$ads,
			'Invalid out of page ad type'
		);

		$html = $instance->render_ads('dfp-prestitial','',false);
		$this->assertContains('<div id="unittest-uid0" class=" adw-1 adh-1"></div>',$html,'Cannot find prestitial oop ad slot div');


		ob_start();
		do_action('wp_footer');

		$html = ob_get_clean();

		$this->assertContains('"id": "unittest-uid0"',$html,'Cannot find prestitial ad oop ad slot div');
		$this->assertContains('"oop": true',$html,'Cannot find prestitial ad oop ad slot div');

		ob_start();
        PMC_Ads_Dfp_Prestitial::get_instance()->action_add_dfp_prestitial_markup();
		$html = ob_get_clean();
		$this->assertContains('<div id="prestitial-ad-section"',$html,'Cannot find prestitial oop ad container div');

	}


}