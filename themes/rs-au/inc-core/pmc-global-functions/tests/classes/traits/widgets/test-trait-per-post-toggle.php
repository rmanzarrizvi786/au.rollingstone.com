<?php
/**
 * Unit tests for \PMC\Global_Functions\Traits\Widgets\Per_Post_Toggle
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-08-16
 *
 * @package pmc-global-functions
 */

namespace PMC\Global_Functions\Tests\Traits\Widgets;

use \PMC\Global_Functions\Tests\Mocks\Per_Post_Toggle;
use \PMC\Global_Functions\Tests\Base;
use \PMC\Unit_Test\Utility;

/**
 * Class Test_Per_Post_Toggle
 *
 * @group pmc-global-functions
 * @group pmc-global-functions-traits-widget-per-post-toggle
 *
 * @coversDefaultClass \PMC\Global_Functions\Traits\Widgets\Per_Post_Toggle
 */
class Test_Per_Post_Toggle extends Base {

	/**
	 * @var \PMC\Global_Functions\Traits\Widgets\Per_Post_Toggle
	 */
	protected $_instance;

	public function setUp() {

		parent::setUp();

		$this->_instance = new Per_Post_Toggle();

		$this->_default_vars = [
			'_post_options_api',
			'_post_option',
		];

		$this->_take_snapshot( $this->_instance );

	}

	public function tearDown() {

		$this->_unset_data();
		$this->_restore_snapshot( $this->_instance );

		parent::tearDown();

	}

	/**
	 * Utility method to set up class vars
	 *
	 * @return void
	 */
	protected function _setup_data() : void {

		Utility::set_and_get_hidden_property(
			$this->_instance,
			'_post_option',
			[
				'slug'  => 'abc',
				'label' => 'ABC Term',
			]
		);

		Utility::invoke_hidden_method(
			$this->_instance,
			'_init_post_options'
		);

		$this->_instance->register_post_option();

	}

	/**
	 * Utility method to unset data
	 *
	 * @return void
	 */
	protected function _unset_data() : void {

		wp_delete_term( 'abc', '_post-options' );

	}

	/**
	 * Data provider to supply empty post option input
	 *
	 * @return array
	 */
	public function data_get_empty_post_option_input() : array {

		return [

			[
				'',
				'ABC',
			],

			[
				'pqr',
				'',
			],

		];

	}

	/**
	 * @covers ::_init_post_options
	 */
	public function test__init_post_options() : void {

		/*
		 * Set up
		 */
		Utility::invoke_hidden_method(
			$this->_instance,
			'_init_post_options'
		);

		$object_to_test = Utility::get_hidden_property( $this->_instance, '_post_options_api' );

		/*
		 * Assertions
		 */
		$this->assertInstanceOf(
			'\PMC\Post_Options\API',
			$object_to_test
		);

		$this->assertEquals(
			10,
			has_action(
				'init',
				[ $this->_instance, 'register_post_option' ]
			)
		);

		/*
		 * Clean up
		 */
		$this->_restore_snapshot( $this->_instance );

	}

	/**
	 * @dataProvider data_get_empty_post_option_input
	 *
	 * @covers ::_set_post_option_values
	 */
	public function test__set_post_option_values_when_input_is_empty( string $slug, string $label ) : void {

		/*
		 * Assertions
		 */
		Utility::assert_exception_on_hidden_method(
			\ErrorException::class,
			$this->_instance,
			'_set_post_option_values',
			[
				$slug,
				$label
			]
		);

	}

	/**
	 * @covers ::_set_post_option_values
	 */
	public function test__set_post_option_values_for_success() : void {

		/*
		 * Set up
		 */
		$slug  = 'test-slug';
		$label = 'Test Label';
		$desc  = 'Test Desc';

		Utility::invoke_hidden_method(
			$this->_instance,
			'_set_post_option_values',
			[
				$slug,
				$label,
				$desc,
			]
		);

		$data_to_test = Utility::get_hidden_property( $this->_instance, '_post_option' );

		/*
		 * Assertions
		 */
		$this->assertTrue( is_array( $data_to_test ) );
		$this->assertNotEmpty( $data_to_test );
		$this->assertArrayHasKey( 'slug', $data_to_test );
		$this->assertArrayHasKey( 'label', $data_to_test );
		$this->assertArrayHasKey( 'description', $data_to_test );
		$this->assertEquals( $slug, $data_to_test['slug'] );
		$this->assertEquals( $label, $data_to_test['label'] );
		$this->assertEquals( $desc, $data_to_test['description'] );

		/*
		 * Clean up
		 */
		$this->_restore_snapshot( $this->_instance );

	}

	/**
	 * @dataProvider data_get_empty_post_option_input
	 *
	 * @covers ::_is_post_option_defined
	 */
	public function test__is_post_option_defined_for_failure( string $slug, string $label ) : void {

		/*
		 * Set up
		 */
		Utility::set_and_get_hidden_property(
			$this->_instance,
			'_post_option',
			[
				'slug'  => $slug,
				'label' => $label,
			]
		);

		$output_to_test = Utility::invoke_hidden_method(
			$this->_instance,
			'_is_post_option_defined'
		);

		/*
		 * Assertions
		 */
		$this->assertFalse( $output_to_test );

		/*
		 * Clean up
		 */
		$this->_restore_snapshot( $this->_instance );

	}

	/**
	 * @covers ::_is_post_option_defined
	 */
	public function test__is_post_option_defined_for_success() : void {

		/*
		 * Set up
		 */
		Utility::set_and_get_hidden_property(
			$this->_instance,
			'_post_option',
			[
				'slug'  => 'abc',
				'label' => 'ABC Term',
			]
		);

		$output_to_test = Utility::invoke_hidden_method(
			$this->_instance,
			'_is_post_option_defined'
		);

		/*
		 * Assertions
		 */
		$this->assertTrue( $output_to_test );

		/*
		 * Clean up
		 */
		$this->_restore_snapshot( $this->_instance );

	}

	/**
	 * @covers ::current_post_has_option
	 */
	public function test_current_post_has_option_when_vars_are_not_set() : void {

		/*
		 * Set up
		 */
		$output_to_test = $this->_instance->current_post_has_option();

		/*
		 * Assertions
		 */
		$this->assertFalse( $output_to_test );

		/*
		 * Clean up
		 */
		$this->_restore_snapshot( $this->_instance );

	}

	/**
	 * @covers ::current_post_has_option
	 */
	public function test_current_post_has_option_when_post_does_not_exist() : void {

		/*
		 * Set up
		 */
		$this->_setup_data();

		$output_to_test = $this->_instance->current_post_has_option();

		/*
		 * Assertions
		 */
		$this->assertFalse( $output_to_test );

		/*
		 * Clean up
		 */
		$this->_unset_data();
		$this->_restore_snapshot( $this->_instance );

	}

	/**
	 * @covers ::current_post_has_option
	 */
	public function test_current_post_has_option_when_post_does_not_have_option() : void {

		/*
		 * Set up
		 */
		$this->_setup_data();
		$post_to_test_with = $this->_mocker->mock_global_wp_post( $this );
		$output_to_test    = $this->_instance->current_post_has_option( $post_to_test_with->ID );

		/*
		 * Assertions
		 */
		$this->assertFalse( $output_to_test );

		/*
		 * Clean up
		 */
		$this->_mocker->restore_global_wp_post();
		$this->_unset_data();
		$this->_restore_snapshot( $this->_instance );

	}

	/**
	 * @covers ::current_post_has_option
	 */
	public function test_current_post_has_option_for_success() : void {

		/*
		 * Set up
		 */
		$this->_setup_data();
		$post_to_test_with = $this->_mocker->mock_global_wp_post( $this );
		$post_option       = get_term_by( 'slug', 'abc', '_post-options' );

		wp_set_post_terms( $post_to_test_with->ID, $post_option->term_id, '_post-options' );

		$output_to_test    = $this->_instance->current_post_has_option( $post_to_test_with->ID );

		/*
		 * Assertions
		 */
		$this->assertTrue( $output_to_test );

		/*
		 * Clean up
		 */
		$this->_mocker->restore_global_wp_post();
		$this->_unset_data();
		$this->_restore_snapshot( $this->_instance );

	}

	/**
	 * @covers ::register_post_option
	 */
	public function test_register_post_option_when_options_are_not_defined() : void {

		/*
		 * Set up
		 */
		$this->_instance->register_post_option();

		$output_to_test = term_exists( 'abc', '_post-options' );

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output_to_test );

		/*
		 * Clean up
		 */
		$this->_restore_snapshot( $this->_instance );

	}

	/**
	 * @covers ::register_post_option
	 */
	public function test_register_post_option_for_success() : void {

		/*
		 * Set up
		 */
		$this->_setup_data();
		$this->_instance->register_post_option();

		$output_to_test = term_exists( 'abc', '_post-options' );

		/*
		 * Assertions
		 */
		$this->assertNotEmpty( $output_to_test );
		$this->assertTrue( is_array( $output_to_test ) );
		$this->assertArrayHasKey( 'term_id', $output_to_test );
		$this->assertArrayHasKey( 'term_taxonomy_id', $output_to_test );

		/*
		 * Clean up
		 */
		$this->_unset_data();
		$this->_restore_snapshot( $this->_instance );

	}

}    //end class

//EOF
