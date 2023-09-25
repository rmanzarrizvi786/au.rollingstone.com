<?php
/**
 * @group pmc-adm
 * @group pmc-adm-oop
 */

class Test_Class_PMC_Ad_OOP extends WP_UnitTestCase {
	function setUp() {
		// to speeed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;
		parent::setUp();
	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	function test_structure() {
		PMC_Cheezcap::get_instance()->register();

		$instance = PMC_Ads::get_instance();
		$instance->add_provider(new Google_Publisher_Provider('unittest') );
		$instance->add_locations( array(
			'unittest'   => 'unittest',
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
				'location'     => 'unittest',
				'div-id'       => 'unittest',
			);
		$p = $this->factory->post->create(array(
				'post_type'    => 'pmc-ad',
				'post_content' => json_encode( $ad ),
			) );

		update_post_meta( $p, '_ad_location', 'unittest' );
		$ads = $instance->get_ads_to_render( 'unittest' );
		$this->assertArraySubset(
				array(
					'1x1-oop' => array(
						'zone'      => 'unittest',
						'slot-type' => 'oop',
						'location'  => 'unittest',
							),
					),
				$ads,
				'Invalid out of page ad type'
			);
		$html = $instance->render_ads('unittest','',false);
		$this->assertContains('<div id="unittest-uid0" class=" adw-1 adh-1"></div>',$html,'Cannot find oop ad slot div');
		ob_start();
		do_action('wp_footer');
		$html = ob_get_clean();
		$this->assertContains('"id": "unittest-uid0"',$html,'Cannot find oop ad slot div');
		$this->assertContains('"oop": true',$html,'Cannot find oop ad slot div');

	}
}
