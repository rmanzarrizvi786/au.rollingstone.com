<?php
/**
 * Unit tests for \PMC\Global_Functions\Utility\Number
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-11-05
 *
 * @package pmc-global-functions
 */

namespace PMC\Global_Functions\Tests\Utility;

use \PMC\Global_Functions\Tests\Base;
use \PMC\Global_Functions\Utility\Number;


/**
 * Class Test_Number
 *
 * @group pmc-global-functions
 * @group pmc-global-functions-utility-number
 *
 * @coversDefaultClass \PMC\Global_Functions\Utility\Number
 */
class Test_Number extends Base {

	/**
	 * @var \PMC\Global_Functions\Utility\Number
	 */
	protected $_instance;

	public function setUp() {

		parent::setUp();

		$this->_instance = Number::get_instance();

	}

	/**
	 * Data provider to provide out of bounds numbers
	 *
	 * @return array
	 */
	public function data_get_out_of_bounds_numbers() : array {

		return [
			[ -1 ],
			[ 0 ],
			[ 60 ],
		];

	}

	/**
	 * Data provider to provide numbers, seperators and their ordinal equivalents
	 *
	 * @return array
	 */
	public function data_get_numbers_with_ordinals() : array {

		return [

			[
				1,
				'-',
				'first',
			],

			[
				14,
				'-',
				'fourteenth',
			],

			[
				23,
				'-',
				'twenty-third',
			],

			[
				32,
				'/',
				'thirty/second',
			],

		];

	}

	/**
	 * @covers ::get_ordinal
	 *
	 * @dataProvider data_get_out_of_bounds_numbers
	 */
	public function test_get_ordinal_when_number_is_out_of_bounds( int $num ) : void {

		/*
		 * Set up
		 */
		$output = $this->_instance->get_ordinal( $num );

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output );

	}

	/**
	 * @covers ::get_ordinal
	 *
	 * @dataProvider data_get_numbers_with_ordinals
	 */
	public function test_get_ordinal_for_success( int $num, string $seperator, string $expected ) : void {

		/*
		 * Set up
		 */
		$output = $this->_instance->get_ordinal( $num, $seperator );

		/*
		 * Assertions
		 */
		$this->assertEquals( $expected, $output );

	}

	/**
	 * @covers ::get_ordinal_as_label
	 */
	public function test_get_ordinal_as_label() : void {

		/*
		 * Set up
		 */
		$output = $this->_instance->get_ordinal_as_label( 23 );

		/*
		 * Assertions
		 */
		$this->assertEquals( 'Twenty Third', $output );

	}

}    //end class

//EOF
