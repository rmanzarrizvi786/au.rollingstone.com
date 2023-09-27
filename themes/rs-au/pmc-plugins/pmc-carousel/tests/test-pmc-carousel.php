<?php

/**
 * @group pmc-carousel
 * @group pmc-carousel-class-pmc-carousel
 *
 * @coversDefaultClass PMC_Carousel
 */
class Tests_Class_PMC_Carousel extends WP_UnitTestCase {

	private $test_option_name = 'pmc_carousel_taxonomies';
	private $test_option_defaults = array(
		'available_taxes' => array()
	);

	CONST TEST_MODULES_TAXONOMY_NAME = 'pmc_carousel_modules';
	CONST TEST_CACHE_GROUP = '_pmc-carousel-modules';
	CONST TEST_DEFAULT_TAXONOMY = 'pmc_carousel_modules';
	CONST TEST_DEFAULT_TERM = 'featured-carousel';


	/**
	 * Method to return an array with dummy values to test 'pmc_carousel_render_short_circuit' hook
	 *
	 * @return array
	 */
	public function get_render_short_circuit() : array {

		return [
			[
				'ID' => 123,
			],
		];

	}

	/**
	 * @covers ::_init
	 */
	public function test_init() {
		$pmc_carousel = PMC_Carousel::get_instance();

		$this->assertNotEmpty( $pmc_carousel, 'Error retrieving instance of PMC_Carousel' );

		$filters = array(
			'save_post'                           => 'action_save_post',
			'restrict_manage_posts'               => 'action_restrict_manage_posts',
			'admin_print_footer_scripts'          => 'action_print_footer_scripts',
			'add_meta_boxes'                      => 'action_add_meta_boxes',
			'admin_enqueue_scripts'               => 'action_admin_enqueue_scripts',
			'wp_ajax_carousel_cats'               => 'action_ajax',
			'pre_get_posts'                       => 'action_pre_get_posts',
			'admin_menu'                          => 'action_admin_menu',
			'admin_init'                          => 'action_admin_init',
			'init'                                => 'action_init',
			'simple_page_ordering_ordered_posts'  => 'action_simple_page_ordering_ordered_posts',
			'pmc-linkcontent-before-insert-field' => 'action_pmc_linkcontent_before_insert_field',

		);

		foreach ( $filters as $key => $value ) {
			$this->assertGreaterThanOrEqual(
				10,
				has_filter( $key, array( $pmc_carousel, $value ) ),
				sprintf( 'PMC_Carousel::_init failed registering filter/action "%1$s" to PMC_Carousel::%2$s', $key, $value )
			);
		}

		$this->assertClassHasAttribute( 'default_taxonomy', 'PMC_Carousel' );
		$this->assertClassHasAttribute( 'default_term', 'PMC_Carousel' );

	}

	/**
	 * @covers ::get_instance
	 */
	public function test_get_instance() {

		$this->assertInstanceOf( "PMC_Carousel", PMC_Carousel::get_instance() );

	}

	/**
	 * @covers ::render
	 */
	public function test_render_when_it_is_short_circuit() : void {

		/*
		 * Set up
		 */
		add_filter( 'pmc_carousel_render_short_circuit', [ $this, 'get_render_short_circuit' ] );

		$instance = PMC_Carousel::get_instance();
		$output   = $instance->render( 'category', 'uncategorized' );

		/*
		 * Assertions
		 */
		$this->assertNotEmpty( $output );
		$this->assertTrue( is_array( $output ) );

		/*
		 * Clean up
		 */
		remove_filter( 'pmc_carousel_render_short_circuit', [ $this, 'get_render_short_circuit' ] );

	}

}
//EOF
