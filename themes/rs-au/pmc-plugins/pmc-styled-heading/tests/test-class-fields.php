<?php
/**
 * Test Class for Fields class
 * @since 2018-5-23
 *
 */

use PMC\Styled_Heading\Fields;
use PMC\Styled_Heading\Styled_Heading;

/**
 * @group pmc-styled-heading
 * @coversDefaultClass \PMC\Styled_Heading\Fields
 */
class Test_Class_Fields extends WP_UnitTestCase {

	/**
	 * Setup Method
	 */
	public function setUp() {

		$this->_instance = new Fields( 'My Name', 'my_id' );

		// To speed up unit test, we bypass files scanning on upload folder.
		self::$ignore_files = true;
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * To prevent all upload files from deletion, since set $ignore_files = true
	 * we override the function and do nothing here
	 */
	public function remove_added_uploads() {}

	/**
	 * @covers ::__construct
	 */
	public function test_construct() {

		$this->assertEquals( 'My Name', $this->_instance->name );
		$this->assertEquals( 'my_id', $this->_instance->id );

	}

	/**
	 * @covers ::init
	 */
	public function test_init() {

		$this->_instance->init();

		$this->assertEquals( 'pmc_styled_heading_my_id', $this->_instance->field_group->name );

	}

	/**
	 * @covers ::get_group_fields
	 */
	public function test_get_group_fields() {

		$fields = $this->_instance->get_group_fields();

		$this->assertArrayHasKey( 'container_fields', $fields );

	}

	/**
	 * @covers ::get_line_fields
	 */
	public function test_get_line_fields() {

		$fields = $this->_instance->get_line_fields();

		$this->assertArrayHasKey( 'text', $fields );

	}

	/**
	 * @covers ::get_fields
	 */
	public function test_get_fields() {
		$post = $this->factory->post->create_and_get();

		update_post_meta( $post->ID, Styled_Heading::FILTER_PREFIX . 'my_meta_key', 'my_meta_value' );

		$meta_value = $this->_instance::get_fields( 'my_meta_key', $post->ID );

		$this->assertEquals( 'my_meta_value', $meta_value );

	}

}
