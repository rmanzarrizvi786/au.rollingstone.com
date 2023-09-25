<?php
/**
 * @group pmc-adm
 */
class Test_Class_PMC_Ads_Interruptus extends WP_UnitTestCase {
	function setUp() {
		// to speeed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;
		parent::setUp();
	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * @covers PMC_Ads_Interruptus::_init
	 */
	public function test_init(){
		$instance = PMC_Ads_Interruptus::get_instance();

		$this->assertInstanceOf( "PMC_Ads_Interruptus", $instance );

		$actions = array(
			'init'    =>    'action_init',
		);

		foreach ( $actions as $key => $value ) {
			$this->assertNotEquals(
				false,
				has_filter( $key, array( $instance, $value ) ) ,
				sprintf('PMC_Ads_Interruptus::_init or action_init failed registering action "%1$s" to PMC_Ads_Interruptus::%2$s', $key, $value )
			);
		}

	}

	/**
	 *  @covers PMC_Ads_Interruptus::action_init
	 */
	public function test_action_init(){
		$instance = PMC_Ads_Interruptus::get_instance();

		$instance->action_init();

		$actions = array(
			'admin_head'      =>    'enqueue_admin_stuff',
			'pmc-tags-top'    =>    'action_interruptus',
		);

		// Expecting V3 to be used
		foreach ( $actions as $key => $value ) {
			$this->assertNotEmpty(
				has_filter( $key, array( $instance, $value ) ) ,
				sprintf('PMC_Ads_Interruptus::admin_init failed registering action "%1$s" to PMC_Ads_Interruptus::%2$s', $key, $value )
			);
		}
	}

	/**
	 *  @covers PMC_Ads_Interruptus::is_ad_interruptus
	 *  @covers PMC_Ads_Interruptus::_get_endpoints
	 *  @covers PMC_Ads_Interruptus::__call
	 *  @covers PMC_Ads_Interruptus::get_array_values
	 */
	public function test_is_ad_interruptus(){
		$instance = PMC_Ads_Interruptus::get_instance();
		$this->go_to( "/");
		set_query_var( 'prestitial', true);

		$this->assertTrue( $instance->is_prestitial() );
		$this->assertFalse( $instance->is_interstitial() );


		$post_id = $this->factory->post->create();
		$this->go_to( "/?p=" . $post_id );
		set_query_var( 'interstitial', true);

		$this->assertTrue( $instance->is_interstitial() );
		$this->assertFalse( $instance->is_prestitial() );

	}

	/**
	 *  @covers PMC_Ads_Interruptus::can_interrupt
	 */
	public function test_can_interrupt(){
		$instance = PMC_Ads_Interruptus::get_instance();

		$this->go_to( "/");
		$this->assertTrue( $instance->can_interrupt() );

		$post_id = $this->factory->post->create();
		$this->go_to( "/?p=" . $post_id );
		$this->assertTrue( $instance->can_interrupt() );
	}

	/**
	 *  @covers PMC_Ads_Interruptus::get_current_endpoint
	 */
	public function test_get_current_endpoint(){
		$instance = PMC_Ads_Interruptus::get_instance();
		$this->go_to( "/");
		set_query_var( 'prestitial', true);
		$this->assertTrue('prestitial' == $instance->get_current_endpoint() );
	}

}// EOF