<?php
/**
 * @group pmc-adm
 * @group pmc-adm-ads-dfp-skin
 */
class Test_Class_PMC_Ads_Dfp_Skin extends WP_UnitTestCase {

	function setUp() {
		// to speeed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;
		parent::setUp();
		// do not report on warning to avoid unit test reporting on: Cannot modify header information - headers already sent by'
		error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * @covers PMC_Ads_Dfp_Skin::_init
	 * @covers PMC_Ads_Dfp_Skin::action_init
	 */
	public function test_init(){
		$instance = PMC_Ads_Dfp_Skin::get_instance();

		$filters = array(
			'init' => 'action_init',
			'pmc-tags-top' => 'action_add_dfp_skin_markup',
		);

		foreach ( $filters as $key => $value ) {
			$this->assertNotEquals(
				false,
				has_filter( $key, array( $instance, $value ) ) ,
				sprintf('PMC_Ads_Dfp_Skin::_init or action_init failed registering filter/action "%1$s" to PMC_Ads_Dfp_Skin::%2$s', $key, $value )
			);
		}

	}

	function test_structure() {
		PMC_Cheezcap::get_instance()->register();

		$instance = PMC_Ads::get_instance();
		$instance->add_provider(new Google_Publisher_Provider('unittest') );
		$instance->add_locations( array(
			'responsive-skin-ad'   => 'Responsive Skin Ad',
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
			'location'     => 'responsive-skin-ad',
			'div-id'       => 'unittest',
		);

		$p = $this->factory->post->create(array(
			'post_type'    => 'pmc-ad',
			'post_content' => json_encode( $ad ),
		) );

		update_post_meta( $p, '_ad_location', 'responsive-skin-ad' );
		$ads = $instance->get_ads_to_render( 'responsive-skin-ad' );
		$this->assertArraySubset(
			array(
				'1x1-oop' => array(
					'zone'      => 'unittest',
					'slot-type' => 'oop',
					'location'  => 'responsive-skin-ad',
				),
			),
			$ads,
			'Invalid out of page ad type'
		);

		$html = $instance->render_ads('responsive-skin-ad','',false);
		$this->assertContains('<div id="unittest-uid0" class=" adw-1 adh-1"></div>',$html,'Cannot find responsive oop ad slot div');


		ob_start();
		do_action('wp_footer');

		$html = ob_get_clean();

		$this->assertContains('"id": "unittest-uid0"',$html,'Cannot find responsive skin oop ad slot div');
		$this->assertContains('"oop": true',$html,'Cannot find responsive skin oop ad slot div');

		ob_start();
		PMC_Ads_Dfp_Skin::get_instance()->action_add_dfp_skin_markup();
		$html = ob_get_clean();
		$this->assertContains('<div id="skin-ad-section"',$html,'Cannot find responsive oop ad container div');

	}
}