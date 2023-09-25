<?php
namespace PMC\Global_Functions\Tests;

use \PMC\Unit_Test\Utility;
use PMC_Cheezcap;

/**
 * @group pmc-global-functions
 * @group cheezcap
 *
 * PHPUnit tests for class PMC_Cheezcap
 *
 * @since 2017-01-03 Hau Vong
 *
 */
class Tests_Class_PMC_Cheezcap extends Base {
	public function setUp() {
		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;

		parent::setUp();
	}

	public function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	public function test_get_option() {
		// Make sure we initialize the cheezcap object
		PMC_Cheezcap::get_instance()->register();

		// Expect false to be returned when an option is not set.
		// In this scenario, CheezCap throws an exception and PMC_Cheezcap
		// catches said exception and returns false.
		$non_existant_option = PMC_Cheezcap::get_instance()->get_option( 'non_existing_property' );
		$this->assertFalse( $non_existant_option );

		// Verify the assigned property is correctly retrieved
		$GLOBALS['cap']->cheezcap_test = 'cheezcap';
		$this->assertEquals( 'cheezcap', PMC_Cheezcap::get_instance()->get_option('cheezcap_test') );

		// Verify an existing valid cheezcap setting auto loaded from pmc global functions
		$this->assertNotEmpty( PMC_Cheezcap::get_instance()->get_option('pmc_global_terms') );

		// Destroy the cheezcap object and relaced with a custom standard object
		$GLOBALS['cap'] = (object)[ 'cheezcap_test' => 'object' ];

		// Trigger the action to simulate action init
		PMC_Cheezcap::get_instance()->action_init();

		// Verify the assigned property is correctly retrieved with the new value
		$this->assertEquals( 'object', PMC_Cheezcap::get_instance()->get_option('cheezcap_test') );

		// Verify existing property returning false due to variable is not an object
		$GLOBALS['cap'] = [ 'cheezcap_test' => 'array' ];
		PMC_Cheezcap::get_instance()->action_init();
		$this->assertFalse( PMC_Cheezcap::get_instance()->get_option('cheezcap_test') );

		$instance = PMC_Cheezcap::get_instance();

		update_option( 'cap_cheezcap-unittest', 'cheezcap-unittest' );
		$this->assertNotEquals( 'cheezcap-unittest', $instance->get_option( 'cheezcap-unittest') );

		unset( $GLOBALS['cap'] );
		Utility::set_and_get_hidden_property( $instance, '_cheezcap_instance', false );

		$this->assertEquals( 'cheezcap-unittest', $instance->get_option( 'cheezcap-unittest') );

	}
}

