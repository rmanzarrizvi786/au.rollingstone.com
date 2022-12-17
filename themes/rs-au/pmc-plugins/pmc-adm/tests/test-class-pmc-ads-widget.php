<?php
/**
 * @group pmc-adm
 */
class Test_Class_PMC_Ads_Widget extends WP_UnitTestCase {

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
	 * @covers PMC_Ads_Widget
	 */
	function test_register_widget() {
		global $wp_widget_factory;
		unregister_widget( 'PMC_Ads_Widget' );
		register_widget( 'PMC_Ads_Widget' );
		$this->assertTrue( isset( $wp_widget_factory->widgets['PMC_Ads_Widget'] ) );
	}

	/**
	 * @covers PMC_Ads_Widget
	 */
	function test_unregister_widget() {
		global $wp_widget_factory;
		unregister_widget( 'PMC_Ads_Widget' );
		$this->assertFalse( isset( $wp_widget_factory->widgets['PMC_Ads_Widget'] ) );
	}


	/**
	 * @covers PMC_Ads_Widget::get_ads
	 */
	public function test_get_ads(){
		$instance = PMC_Ads::get_instance();

		register_post_type('not-pmc-ad',array());

		$this->factory->post->create_many( 10, array( 'post_type' => 'not-pmc-ad' ) );
		$this->factory->post->create_many( 10, array( 'post_type' => 'pmc-ad' ) );

		$posts_width_ads = $instance->get_ads( false );
		foreach($posts_width_ads as $key => $post ){

			// Verify only ads of the correct post type are being retrieved
			$post_type = get_post_type ( $post );
			$this->assertTrue(
				$post_type == 'pmc-ad',
				'PMC::get_ads is returning posts that are not of post_type pmc-ad'
			);
		}

	}

}