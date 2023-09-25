<?php

/**
 * @group pmc-options
 * @coversDefaultClass PMC_Options
 */
class Tests_Class_PMC_Options extends WP_UnitTestCase {

	private $_int_value = 1;
	private $_array_value = array( "key" => 'value' );
	private $_string_value = "this is string";

	private $_add_option_group = 'add_option_group';
	private $_update_option_group = 'update_option_group';
	private $_update_option_group_2 = 'update_option_group_2';
	private $_delete_option_group = 'delete_option_group';
	private $_get_option_group = 'get_option_group';
	private $_get_options_group = 'get_options_group';

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
	 * @covers ::init
	 */
	public function test_init() {
		$pmc_options = PMC_Options::get_instance();

		$this->assertEquals( 10, has_action( 'init', array(
			$pmc_options,
			'register_post_type'
		) ), 'PMC_Options::register_post_type is not attaching PMC_Options::register_post_type to init' );
	}

	/**
	 * @covers ::get_instance
	 */
	public function test_get_instance() {

		$this->assertInstanceOf( "PMC_Options", PMC_Options::get_instance() );

	}

	/**
	 * @covers ::register_post_type
	 */
	public function test_register_post_type() {

		$this->assertTrue( post_type_exists( PMC_Options::post_type_name ) );

	}

	/**
	 * @covers ::insert_post
	 */
	public function test_insert_post() {

		$this->assertNotNull( PMC_Options::get_instance()->insert_post() );

	}

	/**
	 * @covers ::add_option
	 */
	public function test_add_option() {

		PMC_Options::get_instance( $this->_add_option_group )->add_option( 'int_key', $this->_int_value );
		PMC_Options::get_instance( $this->_add_option_group )->add_option( 'array_key', $this->_array_value );
		PMC_Options::get_instance( $this->_add_option_group )->add_option( 'string_key', $this->_string_value );

		$this->assertEquals( $this->_int_value, PMC_Options::get_instance( $this->_add_option_group )->get_option( 'int_key' ), "PMC_Options::add_option failed for integer value" );

		$this->assertEquals( $this->_array_value, PMC_Options::get_instance( $this->_add_option_group )->get_option( 'array_key' ), "PMC_Options::add_option failed for array value" );

		$this->assertEquals( $this->_string_value, PMC_Options::get_instance( $this->_add_option_group )->get_option( 'string_key' ), "PMC_Options::add_option failed for string value" );


	}

	/**
	 * @covers ::update_option
	 */
	public function test_update_option() {

		PMC_Options::get_instance( $this->_update_option_group )->update_option( 'int_key', $this->_int_value );
		PMC_Options::get_instance( $this->_update_option_group )->update_option( 'array_key', $this->_array_value );
		PMC_Options::get_instance( $this->_update_option_group )->update_option( 'string_key', $this->_string_value );

		$this->assertEquals( $this->_int_value, PMC_Options::get_instance( $this->_update_option_group )->get_option( 'int_key' ), "PMC_Options::update_option failed for integer value" );

		$this->assertEquals( $this->_array_value, PMC_Options::get_instance( $this->_update_option_group )->get_option( 'array_key' ), "PMC_Options::update_option failed for array value" );

		$this->assertEquals( $this->_string_value, PMC_Options::get_instance( $this->_update_option_group )->get_option( 'string_key' ), "PMC_Options::update_option failed for string value" );

		$int_value    = 1000;
		$array_value  = array( "key_2" => 'value_2' );
		$string_value = "this is string 2";

		PMC_Options::get_instance( $this->_update_option_group_2 )->update_option( 'int_key', $int_value );
		PMC_Options::get_instance( $this->_update_option_group_2 )->update_option( 'array_key', $array_value );
		PMC_Options::get_instance( $this->_update_option_group_2 )->update_option( 'string_key', $string_value );


		$this->assertEquals( $int_value, PMC_Options::get_instance( $this->_update_option_group_2 )->get_option( 'int_key' ), "PMC_Options::update_option failed for integer value" );

		$this->assertEquals( $array_value, PMC_Options::get_instance( $this->_update_option_group_2 )->get_option( 'array_key' ), "PMC_Options::update_option failed for array value" );

		$this->assertEquals( $string_value, PMC_Options::get_instance( $this->_update_option_group_2 )->get_option( 'string_key' ), "PMC_Options::update_option failed for string value" );


	}

	/**
	 * @covers ::delete_option
	 */
	public function test_delete_option() {

		PMC_Options::get_instance( $this->_delete_option_group )->update_option( 'key', 'value' );

		$this->assertTrue( PMC_Options::get_instance( $this->_delete_option_group )->delete_option( 'key' ), "PMC_Options::delete_option failed" );

		//Delete empty key again to test edge case
		$this->assertFalse( PMC_Options::get_instance( $this->_delete_option_group )->delete_option( 'key' ), "PMC_Options::delete_option failed for empty key" );

	}


	/**
	 * @covers ::get_option
	 */
	public function test_get_option() {

		PMC_Options::get_instance( $this->_get_option_group )->update_option( 'int_key', $this->_int_value );
		PMC_Options::get_instance( $this->_get_option_group )->update_option( 'array_key', $this->_array_value );
		PMC_Options::get_instance( $this->_get_option_group )->update_option( 'string_key', $this->_string_value );

		$this->assertEquals( $this->_int_value, PMC_Options::get_instance( $this->_get_option_group )->get_option( 'int_key' ), "PMC_Options::get_option failed for integer value" );

		$this->assertEquals( $this->_array_value, PMC_Options::get_instance( $this->_get_option_group )->get_option( 'array_key' ), "PMC_Options::get_option failed for array value" );

		$this->assertEquals( $this->_string_value, PMC_Options::get_instance( $this->_get_option_group )->get_option( 'string_key' ), "PMC_Options::get_option failed for string value" );

		$this->assertEmpty( PMC_Options::get_instance( $this->_get_option_group )->get_option( 'key' ), "PMC_Options::get_option failed for unknown key" );

	}

	/**
	 * @covers ::get_options
	 */
	public function test_get_options() {
		PMC_Options::get_instance( $this->_get_options_group )->update_option( 'int_key', $this->_int_value );
		PMC_Options::get_instance( $this->_get_options_group )->update_option( 'array_key', $this->_array_value );
		PMC_Options::get_instance( $this->_get_options_group )->update_option( 'string_key', $this->_string_value );

		$options = PMC_Options::get_instance( $this->_get_options_group )->get_options();

		$this->assertTrue( is_array( $options ) && count( $options ) === 3, "PMC_Options::get_options failed retrieving values" );

		$this->assertArrayHasKey( 'int_key', $options, "PMC_Options::get_options failed to have key int_key" );
		$this->assertArrayHasKey( 'array_key', $options, "PMC_Options::get_options failed to have key array_key" );
		$this->assertArrayHasKey( 'string_key', $options, "PMC_Options::get_options failed to have key string_key" );

		$this->assertArraySubset(array(
			'int_key' => array( $this->_int_value ),
			'array_key' => array( maybe_serialize( $this->_array_value ) ),
			'string_key' => array( $this->_string_value )
		), $options, false, "PMC_Options::get_options failed to have correct array structure");
	}


}
//EOF