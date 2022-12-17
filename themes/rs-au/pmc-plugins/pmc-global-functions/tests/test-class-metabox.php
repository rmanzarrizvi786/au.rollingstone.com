<?php
/**
 * Unit test for class PMC\Global_Functions\Metabox
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2018-10-13
 */

namespace PMC\Global_Functions\Tests;

use \PMC\Unit_Test\Utility;
use \PMC\Global_Functions\Metabox;

/**
 *
 * @group pmc-global-functions
 * @group pmc-global-functions-test-metabox
 *
 * @requires PHP 7.2
 * @coversDefaultClass \PMC\Global_Functions\Metabox
 */
class Test_Metabox extends Base {

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
	 * @covers ::create
	 */
	public function test_create_with_invalid_type_input() {

		$method_name = 'create';
		$error_msg   = sprintf(
			'%s::%s() failed to throw %s exception on input of %%s type',
			Metabox::class,
			$method_name,
			\TypeError::class
		);

		// Test with array input
		Utility::assert_error(
			\TypeError::class,
			[ Metabox::class, $method_name ],
			[ [ 'abc' ] ],
			sprintf(
				$error_msg,
				'array'
			)
		);

		$test_obj = new \stdClass();

		// Test with object input
		Utility::assert_error(
			\TypeError::class,
			[ Metabox::class, $method_name ],
			[ $test_obj ],
			sprintf(
				$error_msg,
				'object'
			)
		);

	}

	/**
	 * @covers ::create
	 */
	public function test_create_with_empty_input() {

		Utility::assert_exception(
			\ErrorException::class,
			[ Metabox::class, 'create' ],
			[ '' ],
			sprintf(
				'%s::%s() failed to throw %s exception on empty input',
				Metabox::class,
				'create',
				\ErrorException::class
			)
		);

	}

	/**
	 * @covers ::create
	 */
	public function test_create_for_success() {

		$class_object = Metabox::create( 'abc' );

		$this->assertInstanceOf( Metabox::class, $class_object );

	}

	/**
	 * @covers ::_is_pre_flight_ok
	 */
	public function test_is_pre_flight_ok_when_id_is_invalid() {

		$class_object = Metabox::create( 'abc' );

		/*
		 * Test when metabox ID is empty
		 */

		$metabox_prop = [
			'id' => '',
		];

		// Set $_metabox property
		Utility::set_and_get_hidden_property( $class_object, '_metabox', $metabox_prop );

		$this->assertFalse( Utility::invoke_hidden_method( $class_object, '_is_pre_flight_ok' ) );

		/*
		 * Test when metabox ID is array
		 */

		$metabox_prop = [
			'id' => [ 'abc' ],
		];

		// Set $_metabox property
		Utility::set_and_get_hidden_property( $class_object, '_metabox', $metabox_prop );

		$this->assertFalse( Utility::invoke_hidden_method( $class_object, '_is_pre_flight_ok' ) );

	}

	/**
	 * @covers ::_is_pre_flight_ok
	 */
	public function test_is_pre_flight_ok_when_callback_is_invalid() {

		$class_object = Metabox::create( 'abc' );

		$metabox_prop = [
			'id'       => 'abc',
			'callback' => '',
		];

		/*
		 * Test when callback is empty
		 */

		// Set $_metabox property
		Utility::set_and_get_hidden_property( $class_object, '_metabox', $metabox_prop );

		$this->assertFalse( Utility::invoke_hidden_method( $class_object, '_is_pre_flight_ok' ) );

		/*
		 * Test when callback is non-existent
		 */

		$metabox_prop['callback'] = 'pqr';

		// Set $_metabox property
		Utility::set_and_get_hidden_property( $class_object, '_metabox', $metabox_prop );

		$this->assertFalse( Utility::invoke_hidden_method( $class_object, '_is_pre_flight_ok' ) );

	}

	/**
	 * @covers ::_is_pre_flight_ok
	 */
	public function test_is_pre_flight_ok_when_screen_is_empty() {

		$class_object = Metabox::create( 'abc' );

		// Set $_screen property
		Utility::set_and_get_hidden_property( $class_object, '_screen', '' );

		$this->assertFalse( Utility::invoke_hidden_method( $class_object, '_is_pre_flight_ok' ) );

	}

	/**
	 * @covers ::_is_pre_flight_ok
	 */
	public function test_is_pre_flight_ok_for_success() {

		$class_object = Metabox::create( 'abc' );

		$metabox_prop = [
			'id'       => 'abc',
			'callback' => 'intval',
		];

		// Set $_metabox property
		Utility::set_and_get_hidden_property( $class_object, '_metabox', $metabox_prop );

		// Set $_screen property
		Utility::set_and_get_hidden_property( $class_object, '_screen', 'scr-1' );

		$this->assertTrue( Utility::invoke_hidden_method( $class_object, '_is_pre_flight_ok' ) );

	}

	/**
	 * @covers ::having_title
	 */
	public function test_having_title_with_invalid_type_input() {

		$class_object = Metabox::create( 'abc' );
		$method_name  = 'having_title';
		$error_msg    = sprintf(
			'%s::%s() failed to throw %s exception on input of %%s type',
			Metabox::class,
			$method_name,
			\TypeError::class
		);

		// Test with array input
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ [ 'abc' ] ],
			sprintf(
				$error_msg,
				'array'
			)
		);

		$test_obj = new \stdClass();

		// Test with object input
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ $test_obj ],
			sprintf(
				$error_msg,
				'object'
			)
		);

	}

	/**
	 * @covers ::having_title
	 */
	public function test_having_title_with_empty_input() {

		$class_object = Metabox::create( 'abc' );
		$method_name  = 'having_title';

		Utility::assert_exception(
			\ErrorException::class,
			[ $class_object, $method_name ],
			[ '' ],
			sprintf(
				'%s::%s() failed to throw %s exception on empty input',
				Metabox::class,
				$method_name,
				\ErrorException::class
			)
		);

	}

	/**
	 * @covers ::having_title
	 */
	public function test_having_title_for_success() {

		$class_object  = Metabox::create( 'abc' );
		$input_to_test = 'some title';

		$output = $class_object->having_title( $input_to_test );

		$metabox_prop = Utility::get_hidden_property( $class_object, '_metabox' );

		$this->assertEquals( $input_to_test, $metabox_prop['title'] );

		$this->assertInstanceOf( Metabox::class, $output );

	}

	/**
	 * @covers ::on_screen
	 */
	public function test_on_screen_with_invalid_type_input() {

		$class_object = Metabox::create( 'abc' );
		$method_name  = 'on_screen';
		$error_msg    = sprintf(
			'%s::%s() failed to throw %s exception on input of %%s type',
			Metabox::class,
			$method_name,
			\TypeError::class
		);

		// Test with array input
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ [ 'abc' ] ],
			sprintf(
				$error_msg,
				'array'
			)
		);

		$test_obj = new \stdClass();

		// Test with object input
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ $test_obj ],
			sprintf(
				$error_msg,
				'object'
			)
		);

	}

	/**
	 * @covers ::on_screen
	 */
	public function test_on_screen_with_empty_input() {

		$class_object = Metabox::create( 'abc' );
		$method_name  = 'on_screen';

		Utility::assert_exception(
			\ErrorException::class,
			[ $class_object, $method_name ],
			[ '' ],
			sprintf(
				'%s::%s() failed to throw %s exception on empty input',
				Metabox::class,
				$method_name,
				\ErrorException::class
			)
		);

	}

	/**
	 * @covers ::on_screen
	 */
	public function test_on_screen_for_success() {

		$class_object  = Metabox::create( 'abc' );
		$input_to_test = 'some-screen-id-1';

		$output = $class_object->on_screen( $input_to_test );

		$screen_prop = Utility::get_hidden_property( $class_object, '_screen' );

		$this->assertEquals( $input_to_test, $screen_prop );

		$this->assertInstanceOf( Metabox::class, $output );

	}

	/**
	 * @covers ::in_context
	 */
	public function test_in_context_with_invalid_type_input() {

		$class_object = Metabox::create( 'abc' );
		$method_name  = 'in_context';
		$error_msg    = sprintf(
			'%s::%s() failed to throw %s exception on input of %%s type',
			Metabox::class,
			$method_name,
			\TypeError::class
		);

		// Test with array input
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ [ 'abc' ] ],
			sprintf(
				$error_msg,
				'array'
			)
		);

		$test_obj = new \stdClass();

		// Test with object input
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ $test_obj ],
			sprintf(
				$error_msg,
				'object'
			)
		);

	}

	/**
	 * @covers ::in_context
	 */
	public function test_in_context_with_empty_input() {

		$class_object = Metabox::create( 'abc' );
		$method_name  = 'in_context';

		Utility::assert_exception(
			\ErrorException::class,
			[ $class_object, $method_name ],
			[ '' ],
			sprintf(
				'%s::%s() failed to throw %s exception on empty input',
				Metabox::class,
				$method_name,
				\ErrorException::class
			)
		);

	}

	/**
	 * @covers ::in_context
	 */
	public function test_in_context_for_success() {

		$class_object  = Metabox::create( 'abc' );
		$input_to_test = 'some-context';

		$output = $class_object->in_context( $input_to_test );

		$metabox_prop = Utility::get_hidden_property( $class_object, '_metabox' );

		$this->assertEquals( $input_to_test, $metabox_prop['context'] );

		$this->assertInstanceOf( Metabox::class, $output );

	}

	/**
	 * @covers ::of_priority
	 */
	public function test_of_priority_with_invalid_type_input() {

		$class_object = Metabox::create( 'abc' );
		$method_name  = 'of_priority';
		$error_msg    = sprintf(
			'%s::%s() failed to throw %s exception on input of %%s type',
			Metabox::class,
			$method_name,
			\TypeError::class
		);

		// Test with array input
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ [ 'abc' ] ],
			sprintf(
				$error_msg,
				'array'
			)
		);

		$test_obj = new \stdClass();

		// Test with object input
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ $test_obj ],
			sprintf(
				$error_msg,
				'object'
			)
		);

	}

	/**
	 * @covers ::of_priority
	 */
	public function test_of_priority_with_empty_input() {

		$class_object = Metabox::create( 'abc' );
		$method_name  = 'of_priority';

		Utility::assert_exception(
			\ErrorException::class,
			[ $class_object, $method_name ],
			[ '' ],
			sprintf(
				'%s::%s() failed to throw %s exception on empty input',
				Metabox::class,
				$method_name,
				\ErrorException::class
			)
		);

	}

	/**
	 * @covers ::of_priority
	 */
	public function test_of_priority_for_success() {

		$class_object  = Metabox::create( 'abc' );
		$input_to_test = 'some-priority';

		$output = $class_object->of_priority( $input_to_test );

		$metabox_prop = Utility::get_hidden_property( $class_object, '_metabox' );

		$this->assertEquals( $input_to_test, $metabox_prop['priority'] );

		$this->assertInstanceOf( Metabox::class, $output );

	}

	/**
	 * @covers ::with_css_class
	 */
	public function test_with_css_class_with_invalid_type_input() {

		$class_object = Metabox::create( 'abc' );
		$method_name  = 'with_css_class';
		$error_msg    = sprintf(
			'%s::%s() failed to throw %s exception on input of %%s type',
			Metabox::class,
			$method_name,
			\TypeError::class
		);

		// Test with array input
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ [ 'abc' ] ],
			sprintf(
				$error_msg,
				'array'
			)
		);

		$test_obj = new \stdClass();

		// Test with object input
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ $test_obj ],
			sprintf(
				$error_msg,
				'object'
			)
		);

	}

	/**
	 * @covers ::with_css_class
	 */
	public function test_with_css_class_for_success() {

		$class_object  = Metabox::create( 'abc' );
		$input_to_test = 'c-top-align';

		$output = $class_object->with_css_class( $input_to_test );

		$metabox_prop = Utility::get_hidden_property( $class_object, '_metabox' );

		$this->assertEquals( $input_to_test, $metabox_prop['class'] );

		$this->assertInstanceOf( Metabox::class, $output );

	}

	/**
	 * @covers ::render_via
	 */
	public function test_render_via_with_invalid_type_input() {

		$class_object = Metabox::create( 'abc' );
		$method_name  = 'render_via';
		$error_msg    = sprintf(
			'%s::%s() failed to throw %s exception on %%s callback input and args input of %%s type',
			Metabox::class,
			$method_name,
			\TypeError::class
		);

		// Test with
		//    invalid callback
		//    string args
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ 'pqr', 'test' ],
			sprintf(
				$error_msg,
				'invalid',
				'string'
			)
		);

		// Test with
		//    invalid callback
		//    array args
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ 'pqr', [ 'test' ] ],
			sprintf(
				$error_msg,
				'invalid',
				'array'
			)
		);

		// Test with
		//    valid callback
		//    string args
		Utility::assert_error(
			\TypeError::class,
			[ $class_object, $method_name ],
			[ 'intval', 'test' ],
			sprintf(
				$error_msg,
				'valid',
				'string'
			)
		);

	}

	/**
	 * @covers ::render_via
	 */
	public function test_render_via_for_success() {

		$class_object  = Metabox::create( 'abc' );

		$callback_to_test      = 'is_string';
		$callback_args_to_test = [ 'car' ];

		$output = $class_object->render_via( $callback_to_test, $callback_args_to_test );

		$metabox_prop = Utility::get_hidden_property( $class_object, '_metabox' );

		$this->assertEquals( $callback_to_test, $metabox_prop['callback'] );
		$this->assertEquals( $callback_args_to_test, $metabox_prop['callback_args'] );

		$this->assertInstanceOf( Metabox::class, $output );

	}

	/**
	 * @covers ::add
	 */
	public function test_add_for_failure() {

		/*
		 * Test with invalid screen ID because every other value
		 * is tested for in its own test case
		 */

		$class_object         = Metabox::create( 'abc' );
		$screen_to_test_with  = 'non-existent-screen';
		$metabox_to_test_with = [
			'id'            => 'abc',
			'title'         => 'Custom Metabox',
			'context'       => 'normal',
			'priority'      => 'default',
			'class'         => '',
			'callback'      => 'is_string',
			'callback_args' => [ 'car' ],
		];

		// Set $_screen property
		Utility::set_and_get_hidden_property( $class_object, '_screen', $screen_to_test_with );

		// Set $_metabox property
		Utility::set_and_get_hidden_property( $class_object, '_screen', $metabox_to_test_with );

		$class_object->add();

		$this->assertFalse( isset(
			$GLOBALS['wp_meta_boxes'][ $screen_to_test_with ][ $metabox_to_test_with['context'] ][ $metabox_to_test_with['priority'] ][ $metabox_to_test_with['id'] ]
		) );

	}

	/**
	 * @covers ::add
	 */
	public function test_add_for_success() {

		/*
		 * Test with valid screen ID because every other value
		 * is tested for in its own test case
		 */

		$class_object         = Metabox::create( 'abc' );
		$screen_to_test_with  = 'post';
		$metabox_to_test_with = [
			'id'            => 'abc-success',
			'title'         => 'Custom Metabox',
			'context'       => 'normal',
			'priority'      => 'default',
			'class'         => '',
			'callback'      => 'is_string',
			'callback_args' => [ 'car' ],
		];

		// Set $_screen property
		Utility::set_and_get_hidden_property( $class_object, '_screen', $screen_to_test_with );

		// Set $_metabox property
		Utility::set_and_get_hidden_property( $class_object, '_metabox', $metabox_to_test_with );

		$class_object->add();

		$this->assertTrue( isset(
			$GLOBALS['wp_meta_boxes'][ $screen_to_test_with ][ $metabox_to_test_with['context'] ][ $metabox_to_test_with['priority'] ][ $metabox_to_test_with['id'] ]
		) );

	}

	/**
	 * @covers ::render
	 */
	public function test_render_for_failure() {

		$class_object = Metabox::create( 'abc' );

		$output = Utility::buffer_and_return( [ $class_object, 'render' ] );

		$this->assertEmpty( $output );

	}

	/**
	 * @covers ::render
	 */
	public function test_render_for_success() {

		$class_object         = Metabox::create( 'abc' );
		$screen_to_test_with  = 'post';
		$metabox_to_test_with = [
			'id'            => 'abc-render-success',
			'title'         => 'Custom Metabox',
			'context'       => 'normal',
			'priority'      => 'default',
			'class'         => 'c-mb-top',
			'callback'      => 'is_string',
			'callback_args' => [ 'car' ],
		];

		$string_expected_in_output = sprintf(
			'<div id="mb-%s" class="%s">',
			esc_attr( $metabox_to_test_with['id'] ),
			esc_attr( $metabox_to_test_with['class'] )
		);

		// Set $_screen property
		Utility::set_and_get_hidden_property( $class_object, '_screen', $screen_to_test_with );

		// Set $_metabox property
		Utility::set_and_get_hidden_property( $class_object, '_metabox', $metabox_to_test_with );

		$output = Utility::buffer_and_return( [ $class_object, 'render' ] );

		$this->assertNotEmpty( $output );
		$this->assertContains( $string_expected_in_output, $output );

	}

}	//end class


//EOF
