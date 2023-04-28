<?php
/**
 * Unit test for class PMC\Global_Functions\Service\CDN_Cache
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2018-10-10
 */

namespace PMC\Global_Functions\Tests;

use \PMC\Unit_Test\Utility;
use \PMC\Global_Functions\Service\CDN_Cache;

/**
 *
 * @group pmc-global-functions
 * @group pmc-global-functions-test-service-cdn-cache
 *
 * @requires PHP 7.2
 * @coversDefaultClass \PMC\Global_Functions\Service\CDN_Cache
 */
class Test_CDN_Cache extends Base {

	/**
	 * Default vars in class being tested
	 *
	 * @var array
	 */
	protected $_default_vars = [
		'_callback',
		'_callback_parameters',
		'_map',
		'_cache_buckets',
		'_temporary_cache_buckets',
		'_data_to_return',
	];

	/**
	 * Default values of class vars in class being tested
	 *
	 * @var array
	 */
	protected $_default_values;

	/**
	 * @var \PMC\Global_Functions\Service\CDN_Cache
	 */
	protected $_instance;

	function setUp() {

		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;

		// simulate cdn cache bypass header to activate the plugin
		$_SERVER['HTTP_X_WP_CB'] = 1;

		$this->_instance = CDN_Cache::get_instance();

		// Make a copy of default class property values
		// to allow for clean slate while testing
		$this->_copy_defaults();

		parent::setUp();

	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * Method to get the default values of class and store in a var for the purpose
	 * of resetting them back to mimic fresh instantiation.
	 *
	 * @return void
	 */
	protected function _copy_defaults() : void {

		for ( $i = 0; $i < count( $this->_default_vars ); $i++ ) {

			$var = $this->_default_vars[ $i ];

			$this->_default_values[ $var ] = Utility::get_hidden_property( $this->_instance, $var );

		}

	}

	/**
	 * Method to set the default values of class for the purpose
	 * of resetting them back to mimic fresh instantiation.
	 *
	 * @return void
	 */
	protected function _reset_defaults() : void {

		$_GET  = [];
		$_POST = [];

		foreach ( $this->_default_values as $var_name => $var_value ) {
			Utility::set_and_get_hidden_property( $this->_instance, $var_name, $var_value );
		}

	}

	/**
	 * @covers ::_setup_hooks
	 */
	public function test_hooks_setup() {

		$hooks = [

			[
				'priority' => 20,
				'hook'     => 'send_headers',
				'callback' => [ $this->_instance, 'maybe_set_cookie' ],
			],

		];

		foreach ( $hooks as $hook ) {

			if ( is_array( $hook['callback'] ) && is_string( $hook['callback'][1] ) ) {
				$callback = $hook['callback'][1];
			} else {
				$callback = $hook['callback'];
			}

			$error_string = sprintf(
				'Callback "%1$s()" not found on hook "%2$s" at priority %3$s',
				$callback,
				$hook['hook'],
				$hook['priority']
			);

			$this->assertEquals( $hook['priority'], has_filter( $hook['hook'], $hook['callback'] ), $error_string );

		}

	}

	/**
	 * @covers ::_is_pre_flight_ok
	 */
	public function test_is_pre_flight_ok_when_map_not_available() {

		$this->assertFalse( Utility::invoke_hidden_method( $this->_instance, '_is_pre_flight_ok' ) );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_is_pre_flight_ok
	 */
	public function test_is_pre_flight_ok_when_callback_is_not_available() {

		// Set $_map property
		Utility::set_and_get_hidden_property( $this->_instance, '_map', [ 'orange' ] );

		$this->assertFalse( Utility::invoke_hidden_method( $this->_instance, '_is_pre_flight_ok' ) );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_is_pre_flight_ok
	 */
	public function test_is_pre_flight_ok_for_success() {

		// Set $_map property
		Utility::set_and_get_hidden_property( $this->_instance, '_map', [ 'orange' ] );

		// Set $_callback property
		Utility::set_and_get_hidden_property( $this->_instance, '_callback', 'intval' );

		$this->assertTrue( Utility::invoke_hidden_method( $this->_instance, '_is_pre_flight_ok' ) );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_sanitize_bucket_names
	 */
	public function test_sanitize_bucket_names_when_invalid_parameter_type_is_passed() {

		$method_name = '_sanitize_bucket_names';
		$error_msg   = sprintf(
			'%s::%s() failed to throw %s exception on input of %%s type',
			CDN_Cache::class,
			'_sanitize_bucket_names',
			\TypeError::class
		);

		// Test with array input
		Utility::assert_error_on_hidden_method(
			\TypeError::class,
			$this->_instance,
			$method_name,
			[ [ 'abc' ] ],
			sprintf(
				$error_msg,
				'array'
			)
		);

		$test_obj = new \stdClass();

		// Test with object input
		Utility::assert_error_on_hidden_method(
			\TypeError::class,
			$this->_instance,
			$method_name,
			[ $test_obj ],
			sprintf(
				$error_msg,
				'object'
			)
		);

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_sanitize_bucket_names
	 */
	public function test_sanitize_bucket_names_for_success() {

		$inputs = [
			'car'         => 'car',
			'bike:ducati' => 'bike ducati',
		];

		foreach ( $inputs as $input => $expected_output ) {

			$this->assertEquals(
				$expected_output,
				Utility::invoke_hidden_method( $this->_instance, '_sanitize_bucket_names', [ $input ] )
			);

		}

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_get_callback_output
	 */
	public function test_get_callback_output_when_callback_is_not_set() {

		Utility::assert_exception_on_hidden_method(
			\ErrorException::class,
			$this->_instance,
			'_get_callback_output',
			[],
			sprintf(
				'%s::%s() failed to throw %s exception when a callback has not been set',
				CDN_Cache::class,
				'_get_callback_output',
				\ErrorException::class
			)
		);

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_get_callback_output
	 */
	public function test_get_callback_output_when_callback_is_set() {

		// set a dummy callback
		Utility::set_and_get_hidden_property( $this->_instance, '_callback', 'intval' );

		// set dummy callback parameter
		Utility::set_and_get_hidden_property( $this->_instance, '_callback_parameters', [ '5' ] );

		$method_output = Utility::invoke_hidden_method( $this->_instance, '_get_callback_output' );

		$this->assertNotEmpty(
			$method_output,
			sprintf(
				'%s::%s() failed to return a value',
				CDN_Cache::class,
				'_get_callback_output'
			)
		);

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_get_callback_output
	 */
	public function test_get_callback_output_when_callback_is_set_to_return_boolean() {

		$expected_value = 'true';

		// set a dummy callback
		Utility::set_and_get_hidden_property( $this->_instance, '_callback', 'is_numeric' );

		// set dummy callback parameter
		Utility::set_and_get_hidden_property( $this->_instance, '_callback_parameters', [ 5 ] );

		$method_output = Utility::invoke_hidden_method( $this->_instance, '_get_callback_output' );

		$this->assertEquals(
			$expected_value,
			$method_output,
			sprintf(
				'%1$s::%2$s() failed to return "%3$s" value for callback which returns %3$s as boolean',
				CDN_Cache::class,
				'_get_callback_output',
				$expected_value
			)
		);

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_execute_plan
	 */
	public function test_execute_plan_when_pre_flight_check_fails() {

		Utility::assert_exception_on_hidden_method(
			\ErrorException::class,
			$this->_instance,
			'_execute_plan',
			[ ],
			sprintf(
				'%s::%s() failed to throw %s exception when pre flight checks fail',
				CDN_Cache::class,
				'_execute_plan',
				\ErrorException::class
			)
		);

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_execute_plan
	 */
	public function test_execute_plan_for_success_when_output_is_expected() {

		$map_to_test_with = [
			'true'    => [ 'c-bucket-1' ],
			'default' => [ 'c-bucket-2' ],
		];

		// set map
		Utility::set_and_get_hidden_property( $this->_instance, '_map', $map_to_test_with );

		// set callback
		Utility::set_and_get_hidden_property( $this->_instance, '_callback', 'is_numeric' );

		// set callback parameters
		Utility::set_and_get_hidden_property( $this->_instance, '_callback_parameters', [ '5' ] );

		$method_output = Utility::invoke_hidden_method( $this->_instance, '_execute_plan' );

		$data_to_return = Utility::get_hidden_property( $this->_instance, '_data_to_return' );

		$this->assertTrue( $method_output );
		$this->assertTrue( is_array( $data_to_return ) );
		$this->assertNotEmpty( $data_to_return );

		$this->assertArrayHasKey( 'callback_output', $data_to_return );
		$this->assertArrayHasKey( 'cache_buckets', $data_to_return );

		$this->assertEquals( 'true', $data_to_return['callback_output'] );
		$this->assertEquals( $map_to_test_with['true'], $data_to_return['cache_buckets'] );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_execute_plan
	 */
	public function test_execute_plan_for_success_when_provided_default_is_used() {

		$map_to_test_with = [
			'false'   => [ 'c-bucket-1' ],
			'default' => [ 'c-bucket-2' ],
		];

		// set map
		Utility::set_and_get_hidden_property( $this->_instance, '_map', $map_to_test_with );

		// set callback
		Utility::set_and_get_hidden_property( $this->_instance, '_callback', 'is_numeric' );

		// set callback parameters
		Utility::set_and_get_hidden_property( $this->_instance, '_callback_parameters', [ '5' ] );

		$method_output = Utility::invoke_hidden_method( $this->_instance, '_execute_plan' );

		$data_to_return = Utility::get_hidden_property( $this->_instance, '_data_to_return' );

		$this->assertTrue( $method_output );
		$this->assertTrue( is_array( $data_to_return ) );
		$this->assertNotEmpty( $data_to_return );

		$this->assertArrayHasKey( 'callback_output', $data_to_return );
		$this->assertArrayHasKey( 'cache_buckets', $data_to_return );

		$this->assertEquals( 'true', $data_to_return['callback_output'] );
		$this->assertEquals( $map_to_test_with['default'], $data_to_return['cache_buckets'] );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_execute_plan
	 */
	public function test_execute_plan_for_success_when_global_default_is_used() {

		$map_to_test_with = [
			'false' => [ 'c-bucket-1' ],
		];

		$class_default_to_test = [
			CDN_Cache::CACHE_BUCKET_DEFAULT,
		];

		// set map
		Utility::set_and_get_hidden_property( $this->_instance, '_map', $map_to_test_with );

		// set callback
		Utility::set_and_get_hidden_property( $this->_instance, '_callback', 'is_numeric' );

		// set callback parameters
		Utility::set_and_get_hidden_property( $this->_instance, '_callback_parameters', [ '5' ] );

		$method_output = Utility::invoke_hidden_method( $this->_instance, '_execute_plan' );

		$data_to_return = Utility::get_hidden_property( $this->_instance, '_data_to_return' );

		$this->assertTrue( $method_output );
		$this->assertTrue( is_array( $data_to_return ) );
		$this->assertNotEmpty( $data_to_return );

		$this->assertArrayHasKey( 'callback_output', $data_to_return );
		$this->assertArrayHasKey( 'cache_buckets', $data_to_return );

		$this->assertEquals( 'true', $data_to_return['callback_output'] );
		$this->assertEquals( $class_default_to_test, $data_to_return['cache_buckets'] );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_earmark_bucket_names
	 */
	public function test_earmark_bucket_names_when_there_are_no_temp_buckets() {

		Utility::invoke_hidden_method( $this->_instance, '_earmark_bucket_names' );

		$this->assertEmpty( Utility::get_hidden_property( $this->_instance, '_cache_buckets' ) );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_earmark_bucket_names
	 */
	public function test_earmark_bucket_names_when_there_are_temp_buckets_but_no_cache_buckets() {

		$default_temp_cache_buckets = [ 'xyz' ];

		// set temporary cache buckets
		Utility::set_and_get_hidden_property( $this->_instance, '_temporary_cache_buckets', $default_temp_cache_buckets );

		Utility::invoke_hidden_method( $this->_instance, '_earmark_bucket_names' );

		$this->assertEquals(
			$default_temp_cache_buckets,
			Utility::get_hidden_property( $this->_instance, '_cache_buckets' )
		);

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_earmark_bucket_names
	 */
	public function test_earmark_bucket_names_when_there_are_temp_buckets_and_cache_buckets() {

		$default_cache_buckets      = [ 'abc', 'pqr' ];
		$default_temp_cache_buckets = [ 'xyz' ];
		$expected_buckets           = array_merge( $default_cache_buckets, $default_temp_cache_buckets );

		// set cache buckets
		Utility::set_and_get_hidden_property( $this->_instance, '_cache_buckets', $default_cache_buckets );

		// set temporary cache buckets
		Utility::set_and_get_hidden_property( $this->_instance, '_temporary_cache_buckets', $default_temp_cache_buckets );

		Utility::invoke_hidden_method( $this->_instance, '_earmark_bucket_names' );

		$this->assertEquals(
			$expected_buckets,
			Utility::get_hidden_property( $this->_instance, '_cache_buckets' )
		);

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_cleanup_temporary_data
	 */
	public function test_cleanup_temporary_data() {

		// set temporary cache buckets
		Utility::set_and_get_hidden_property( $this->_instance, '_temporary_cache_buckets', [ 'abc', 'pqr' ] );

		// set $_map
		Utility::set_and_get_hidden_property( $this->_instance, '_map', [ 'abc', 'pqr' ] );

		// set $_data_to_return
		Utility::set_and_get_hidden_property( $this->_instance, '_data_to_return', [ 'abc', 'pqr' ] );

		// set callback
		Utility::set_and_get_hidden_property( $this->_instance, '_callback', 'is_numeric' );

		// set callback parameters
		Utility::set_and_get_hidden_property( $this->_instance, '_callback_parameters', [ 5 ] );


		// run method
		Utility::invoke_hidden_method( $this->_instance, '_cleanup_temporary_data' );

		$this->assertEmpty(
			Utility::get_hidden_property( $this->_instance, '_temporary_cache_buckets' )
		);

		$this->assertEmpty(
			Utility::get_hidden_property( $this->_instance, '_map' )
		);

		$this->assertEmpty(
			Utility::get_hidden_property( $this->_instance, '_data_to_return' )
		);

		$this->assertEmpty(
			Utility::get_hidden_property( $this->_instance, '_callback' )
		);

		$this->assertEmpty(
			Utility::get_hidden_property( $this->_instance, '_callback_parameters' )
		);


		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_set_buckets
	 */
	public function test_set_buckets_when_invalid_parameter_type_is_passed() {

		$method_name = '_set_buckets';
		$error_msg   = sprintf(
			'%s::%s() failed to throw %s exception on input of %%s type',
			CDN_Cache::class,
			'_set_buckets',
			\TypeError::class
		);

		// Test with string input
		Utility::assert_error_on_hidden_method(
			\TypeError::class,
			$this->_instance,
			$method_name,
			[ 'abc' ],
			sprintf(
				$error_msg,
				'string'
			)
		);

		$test_obj = new \stdClass();

		// Test with object input
		Utility::assert_error_on_hidden_method(
			\TypeError::class,
			$this->_instance,
			$method_name,
			[ $test_obj ],
			sprintf(
				$error_msg,
				'object'
			)
		);

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_set_buckets
	 */
	public function test_set_buckets_for_success() {

		$bucket_names_to_test = [ 'abc', 'pqr' ];

		Utility::invoke_hidden_method( $this->_instance, '_set_buckets', [ $bucket_names_to_test ] );

		$this->assertEquals(
			$bucket_names_to_test,
			Utility::get_hidden_property( $this->_instance, '_temporary_cache_buckets' )
		);

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::map_buckets
	 */
	public function test_map_buckets_when_invalid_parameter_type_is_passed() {

		$method_name = 'map_buckets';
		$error_msg   = sprintf(
			'%s::%s() failed to throw %s exception on input of %%s type',
			CDN_Cache::class,
			$method_name,
			\TypeError::class
		);

		// Test with string input
		Utility::assert_error(
			\TypeError::class,
			[ $this->_instance, $method_name ],
			[ 'abc' ],
			sprintf(
				$error_msg,
				'string'
			)
		);

		$test_obj = new \stdClass();

		// Test with object input
		Utility::assert_error(
			\TypeError::class,
			[ $this->_instance, $method_name ],
			[ $test_obj ],
			sprintf(
				$error_msg,
				'object'
			)
		);

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::map_buckets
	 */
	public function test_map_buckets_for_success() {

		$bucket_names_to_test = [ 'abc', 'pqr' ];

		$class_object = $this->_instance->map_buckets( $bucket_names_to_test );

		$this->assertEquals(
			$bucket_names_to_test,
			Utility::get_hidden_property( $this->_instance, '_map' )
		);

		$this->assertInstanceOf( CDN_Cache::class, $class_object );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::for_callback
	 */
	public function test_for_callback_when_invalid_parameter_type_is_passed() {

		$method_name = 'for_callback';
		$error_msg   = sprintf(
			'%s::%s() failed to throw %s exception on %%s callback input and callback parameters input of %%s type',
			CDN_Cache::class,
			$method_name,
			\TypeError::class
		);

		// Test with
		//    invalid callback
		//    string args
		Utility::assert_error(
			\TypeError::class,
			[ $this->_instance, $method_name ],
			[ 'abc', 'pqr' ],
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
			[ $this->_instance, $method_name ],
			[ 'abc', [ 'pqr' ] ],
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
			[ $this->_instance, $method_name ],
			[ 'intval', 'pqr' ],
			sprintf(
				$error_msg,
				'valid',
				'string'
			)
		);

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::for_callback
	 */
	public function test_for_callback_for_success() {

		$callback_to_test        = 'is_string';
		$callback_params_to_test = [ 'car' ];

		$output = $this->_instance->for_callback( $callback_to_test, $callback_params_to_test );

		$callback_in_class        = Utility::get_hidden_property( $this->_instance, '_callback' );
		$callback_params_in_class = Utility::get_hidden_property( $this->_instance, '_callback_parameters' );

		$this->assertInstanceOf( CDN_Cache::class, $output );

		$this->assertEquals( $callback_to_test, $callback_in_class );

		$this->assertEquals( $callback_params_to_test, $callback_params_in_class );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::per_evaluation
	 */
	public function test_per_evaluation_for_failure() {

		/*
		 * Testing one case only here since the failure check is completely
		 * dependent on _is_pre_flight_ok() which has its own comprehensive tests
		 */

		$temporary_map = [ 'car' ];

		Utility::set_and_get_hidden_property( $this->_instance, '_map', $temporary_map );

		Utility::assert_exception(
			\ErrorException::class,
			[ $this->_instance, 'per_evaluation' ],
			[ ],
			sprintf(
				'%s::%s() failed to throw %s exception when callback is not specified',
				CDN_Cache::class,
				'per_evaluation',
				\ErrorException::class
			)
		);

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::per_evaluation
	 */
	public function test_per_evaluation_for_success() {

		/*
		 * Testing one case only here since the test for default value
		 * is depended on _execute_plan() which has its own comprehensive tests
		 */

		$map_to_test_with = [
			5 => [ 'car' ],
		];

		// Set $_map property
		Utility::set_and_get_hidden_property( $this->_instance, '_map', $map_to_test_with );

		// Set $_callback property
		Utility::set_and_get_hidden_property( $this->_instance, '_callback', 'intval' );

		// Set $__callback_parameters property
		Utility::set_and_get_hidden_property( $this->_instance, '_callback_parameters', [ '5' ] );


		$output = $this->_instance->per_evaluation();

		$cache_buckets_in_class = Utility::get_hidden_property( $this->_instance, '_cache_buckets' );

		$this->assertTrue( is_array( $output ) );
		$this->assertNotEmpty( $output );

		$this->assertArrayHasKey( 'callback_output', $output );
		$this->assertArrayHasKey( 'cache_buckets', $output );

		$this->assertEquals( 5, $output['callback_output'] );

		$this->assertNotEmpty( $cache_buckets_in_class );

		$this->assertEquals( $map_to_test_with[ 5 ], $cache_buckets_in_class );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::maybe_set_cookie
	 */
	public function test_maybe_set_cookie_when_cache_buckets_are_not_set() {

		$cookie_name_to_test       = 'cache_bucket';
		$method_to_test            = 'maybe_set_cookie';

		Utility::buffer_and_return( [ $this->_instance, $method_to_test ] );

		$this->assertArrayNotHasKey( $cookie_name_to_test, $_COOKIE, sprintf(
			'%s::%s() set cache bucket cookie even when cache buckets were not defined',
			CDN_Cache::class,
			$method_to_test
		) );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::maybe_set_cookie
	 */
	public function test_maybe_set_cookie_for_success() {

		$cookie_name_to_test       = 'cache_bucket';
		$cache_bucket_to_test_with = 'supercar';
		$method_to_test            = 'maybe_set_cookie';

		// Set $_cache_buckets property
		Utility::set_and_get_hidden_property( $this->_instance, '_cache_buckets', [ $cache_bucket_to_test_with ] );

		Utility::buffer_and_return( [ $this->_instance, $method_to_test ] );

		$this->assertArrayHasKey( $cookie_name_to_test, $_COOKIE, sprintf(
			'%s::%s() did not set cache bucket cookie even when cache buckets were defined',
			CDN_Cache::class,
			$method_to_test
		) );

		$this->assertEquals( $cache_bucket_to_test_with, $_COOKIE[ $cookie_name_to_test ], sprintf(
			'%s::%s() did not set expected value in cache bucket cookie',
			CDN_Cache::class,
			$method_to_test
		) );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::remove_cookie
	 */
	public function test_remove_cookie_for_success() {

		$cookie_name_to_test       = 'cache_bucket';
		$cache_bucket_to_test_with = 'supercar';
		$method_to_test            = 'remove_cookie';

		// Set $_COOKIE var for test
		$_COOKIE[ $cookie_name_to_test ] = $cache_bucket_to_test_with;

		Utility::buffer_and_return( [ $this->_instance, $method_to_test ] );

		$this->assertArrayHasKey( $cookie_name_to_test, $_COOKIE );

		$this->assertEmpty( $_COOKIE[ $cookie_name_to_test ], sprintf(
			'%s::%s() did not remove cache bucket cookie',
			CDN_Cache::class,
			$method_to_test
		) );

		// Reset class properties back to default state
		$this->_reset_defaults();

	}

	/**
	 * @covers ::_load_fastly
	 */
	public function test_fastly_filters() {

		// @TODO: We will need this test, once Fastly is moved to pmc-plugins
		// $this->assertTrue( class_exists( 'Purgely' ) );

		// Run the method before testing the hooks
		Utility::invoke_hidden_method( $this->_instance, '_load_fastly' );

		$filters = [
				'purgely_template_keys',
				'purgely_surrogate_key_collection',
				'purgely_always_purged_types',
				'purgely_related_surrogate_keys',
			];

		foreach ( $filters as $filter ) {
			$this->assertTrue(
				has_filter( $filter ),
				sprintf( '%s is not present', $filter )
			);
		}

		$this->assertTrue( is_array( $this->_instance->maybe_add_fastly_template_keys( null ) ) );
		$this->assertTrue( is_array( $this->_instance->maybe_add_fastly_surrogate_keys( null ) ) );
		$this->assertTrue( is_array( $this->_instance->maybe_add_fastly_related_surrogate_keys( null, $post ) ) );

		$this->assertArraySubset( [ 'tm-feed', 'tm-archive' ], $this->_instance->maybe_add_fastly_always_purged_types( null ) );

		$this->assertArraySubset( [ 'test' ], $this->_instance->maybe_add_fastly_template_keys( [ 'test' ] ) );

		global $post;

		$post_id = $this->factory->post->create( array(
			'post_type'   => 'post',
			'post_status' => 'published',
		) );

		$this->go_to( get_permalink( $post_id ) );
		$post = get_post( $post_id );
		$post->post_type = 'pmc-custom-feed';
		setup_postdata( $post );

		// For some reason, global $wp_query doesn't work inside unit test
		$GLOBALS['wp_query']->is_single = true;

		$this->assertArraySubset( [ 'tm-feed' ], $this->_instance->maybe_add_fastly_template_keys( [ 'tm-single' ] ) );

		$GLOBALS['wp_query']->is_single               = false;
		$GLOBALS['wp_query']->is_archive              = true;
		$GLOBALS['wp_query']->is_post_type_archive    = true;
		$GLOBALS['wp_query']->query['post_type']      = 'pmc-gallery';
		$GLOBALS['wp_query']->query_vars['post_type'] = 'pmc-gallery';

		$this->assertArraySubset( [ 'tm-gallery' ], $this->_instance->maybe_add_fastly_template_keys( [ 'tm-archive' ] ) );

		$GLOBALS['wp_query']->is_year                 = true;
		$GLOBALS['wp_query']->query['year']           = '2018';
		$GLOBALS['wp_query']->query_vars['year']      = '2018';

		$this->assertArraySubset( [ 'tm-archive', 'y-2018' ], $this->_instance->maybe_add_fastly_surrogate_keys( [ 'tm-archive' ] ) );

		$this->assertArraySubset( [ 'tm-home', 'tm-feed', 'y-' . get_the_date( 'Y', $post ) ], $this->_instance->maybe_add_fastly_related_surrogate_keys( [ 'tm-home', 'tm-feed' ], $post ) );

		wp_reset_postdata();

	}

}	//end class


//EOF
