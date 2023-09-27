<?php
namespace PMC\Global_Functions\Tests;

use \PMC_WPCOM_Legacy_Redirector_Extras;

use \PMC\Unit_Test\Utility;

/**
 * @group pmc-global-functions
 *
 * Unit test for class PMC_WPCOM_Legacy_Redirector_Extras
 *
 * Author: Amit Gupta <agupta@pmc.com>
 *
 * @requires PHP 5.6
 * @coversDefaultClass \PMC_WPCOM_Legacy_Redirector_Extras
 */
class Test_PMC_WPCOM_Legacy_Redirector_Extras extends Base {

	/**
	 * @var \PMC_WPCOM_Legacy_Redirector_Extras
	 */
	protected $_redirector;

	function setUp() {

		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;

		$this->_redirector = PMC_WPCOM_Legacy_Redirector_Extras::get_instance();

		parent::setUp();

	}

	function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * @covers ::_setup_hooks
	 */
	public function test_hooks_setup() {

		$hooks = array(
			'wpcom_legacy_redirector_request_path' => 'match_wildcard_rules',
		);

		foreach ( $hooks as $hook => $listener ) {

			$this->assertGreaterThanOrEqual(
				1,
				has_filter( $hook, array( $this->_redirector, $listener ) ),
				sprintf( 'PMC_WPCOM_Legacy_Redirector_Extras::_setup_hooks() failed to register hook "%1$s" to PMC_WPCOM_Legacy_Redirector_Extras::%2$s()', $hook, $listener )
			);

		}

	}

	/**
	 * @covers ::register_wildcard_rules
	 */
	public function test_if_register_wildcard_rules_throws_exception_on_bad_input() {

		// Pass non-array as parameter
		Utility::assert_error(
			'\TypeError',
			array( $this->_redirector, 'register_wildcard_rules' ),
			array( 'abc' ),
			'PMC_WPCOM_Legacy_Redirector_Extras::register_wildcard_rules() accepted a non-array as parameter'
		);

		// Pass empty array as parameter
		Utility::assert_exception(
			'\ErrorException',
			array( $this->_redirector, 'register_wildcard_rules' ),
			array(
				array()
			),
			'PMC_WPCOM_Legacy_Redirector_Extras::register_wildcard_rules() accepted an empty array as parameter'
		);

	}

	/**
	 * @covers ::register_wildcard_rules
	 */
	public function test_if_register_wildcard_rules_works_with_good_input() {

		$rules = array(
			'/cat/some-slug-here-*',
		);

		$output_from_callback = $this->_redirector->register_wildcard_rules( $rules );

		$this->assertTrue( $output_from_callback, 'PMC_WPCOM_Legacy_Redirector_Extras::register_wildcard_rules() failed to return TRUE on good input' );

		$wildcard_rules_in_redirector = Utility::get_hidden_property( $this->_redirector, '_wildcard_rules' );

		$this->assertEquals( $rules, $wildcard_rules_in_redirector, 'PMC_WPCOM_Legacy_Redirector_Extras::register_wildcard_rules() failed to set wildcard rules on good input' );

	}

	/**
	 * @covers ::match_wildcard_rules
	 */
	public function test_match_wildcard_rules() {

		$rule = '/cat/*/some-slug-here-*/';

		$url_failure = 'http://hollywoodlife.com/tag/some-slug-here/';
		$url_success = 'http://hollywoodlife.com/cat/some-stub/some-slug-here-as-well/';

		//Empty out any wildcard rules registered
		Utility::set_and_get_hidden_property( $this->_redirector, '_wildcard_rules', array() );

		/*
		 * Test for failure due to lack of any wildcard rules
		 */
		$this->assertEquals(
			$url_failure,
			$this->_redirector->match_wildcard_rules( $url_failure ),
			'PMC_WPCOM_Legacy_Redirector_Extras::match_wildcard_rules() failed to return input as is when no wildcard rules have been defined'
		);

		//Set wildcard rule
		Utility::set_and_get_hidden_property( $this->_redirector, '_wildcard_rules', array( $rule ) );

		/*
		 * Test for failure due to lack of match with wildcard rule
		 */
		$this->assertEquals(
			$url_failure,
			$this->_redirector->match_wildcard_rules( $url_failure ),
			'PMC_WPCOM_Legacy_Redirector_Extras::match_wildcard_rules() failed to return input as is when it does not match defined wildcard rule'
		);

		/*
		 * Test for success on account of a match with wildcard rule
		 */
		$this->assertEquals(
			$rule,
			$this->_redirector->match_wildcard_rules( $url_success ),
			'PMC_WPCOM_Legacy_Redirector_Extras::match_wildcard_rules() failed to return wildcard rule when URL matches with defined wildcard rule'
		);

	}

	/**
	 * @covers ::trailingslashit_in_url
	 */
	public function test_trailingslashit_in_url() {

		$this->assertEquals( '/subscribenow/', $this->_redirector->trailingslashit_in_url( '/subscribenow' ) );
		$this->assertEquals( '/subscribenow/?query=string', $this->_redirector->trailingslashit_in_url( '/subscribenow?query=string' ) );

		$this->assertEquals( '/subscribenow/', $this->_redirector->trailingslashit_in_url( '/subscribenow/' ) );
		$this->assertEquals( '/subscribenow/?query=string', $this->_redirector->trailingslashit_in_url( '/subscribenow/?query=string' ) );

	}

}	//end class


//EOF
