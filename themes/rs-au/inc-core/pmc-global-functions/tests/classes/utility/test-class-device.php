<?php
/**
 * Unit tests for \PMC\Global_Functions\Utility\Device
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-09-27
 *
 * @package pmc-global-functions
 */

namespace PMC\Global_Functions\Tests\Utility;

use \PMC\Global_Functions\Tests\Base;
use \PMC\Global_Functions\Utility\Device;
use \PMC\Unit_Test\Utility;


/**
 * Class Test_Device
 *
 * @group pmc-global-functions
 * @group pmc-global-functions-utility-device
 *
 * @coversDefaultClass \PMC\Global_Functions\Utility\Device
 */
class Test_Device extends Base {

	/**
	 * @var \PMC\Global_Functions\Utility\Device
	 */
	protected $_instance;

	public function setUp() {

		parent::setUp();

		$this->_instance = Device::get_instance();

		$this->_default_vars = [
			'_is_mobile',
			'_is_smart',
			'_is_dumb',
			'_is_tablet',
			'_is_ipad',
			'_is_desktop',
			'_is_bot',
			'_is_bot_type',
		];

		$this->_take_snapshot( $this->_instance );

	}

	public function tearDown() {

		$this->_restore_snapshot( $this->_instance );

		parent::tearDown();

	}

	/**
	 * Data provider to get the mobile types
	 *
	 * @return array
	 */
	public function data_get_mobile_types() : array {

		return [
			[ 'any' ],
			[ 'smart' ],
			[ 'dumb' ],
		];

	}

	/**
	 * Data provider to get the bot types
	 *
	 * @return array
	 */
	public function data_get_bot_types() : array {

		return [

			[
				'any',
				'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Safari/537.36',
			],

			[
				'googlebot',
				'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Safari/537.36',
			],

			[
				'msnbot',
				'msnbot/2.0b (+http://search.msn.com/msnbot.htm)',
			],

		];

	}

	/**
	 * @covers ::is_desktop
	 */
	public function test_is_desktop_cached() : void {

		/*
		 * Set up
		 */
		Utility::set_and_get_hidden_property( $this->_instance, '_is_desktop', false );

		$output = $this->_instance->is_desktop();

		/*
		 * Assertions
		 */
		$this->assertFalse( $output );

	}

	/**
	 * @covers ::is_desktop
	 */
	public function test_is_desktop_uncached() : void {

		/*
		 * Set up
		 */
		$output = $this->_instance->is_desktop();

		/*
		 * Assertions
		 */
		$this->assertTrue( $output );

	}

	/**
	 * @covers ::is_mobile
	 *
	 * @dataProvider data_get_mobile_types
	 */
	public function test_is_mobile_cached( string $type ) : void {

		/*
		 * Set up
		 */
		Utility::set_and_get_hidden_property( $this->_instance, '_is_smart', false );
		Utility::set_and_get_hidden_property( $this->_instance, '_is_dumb', false );
		Utility::set_and_get_hidden_property( $this->_instance, '_is_mobile', false );

		$output = $this->_instance->is_mobile( $type );

		/*
		 * Assertions
		 */
		$this->assertFalse( $output );

	}

	/**
	 * @covers ::is_mobile
	 */
	public function test_is_mobile_on_vip_go_production() : void {

		/*
		 * Set up
		 */
		add_filter( 'pmc_is_production_mock_env', '__return_true' );

		$_SERVER['HTTP_X_MOBILE_CLASS'] = 'smart';
		$output                         = $this->_instance->is_mobile();

		/*
		 * Assertions
		 */
		$this->assertTrue( $output );

		/*
		 * Clean up
		 */
		remove_filter( 'pmc_is_production_mock_env', '__return_true' );
		unset( $_SERVER['HTTP_X_MOBILE_CLASS'] );

	}

	/**
	 * @covers ::is_mobile
	 */
	public function test_is_mobile_uncached() : void {

		/*
		 * Set up
		 */
		add_filter( 'pre_jetpack_is_mobile', '__return_true' );

		$output = $this->_instance->is_mobile();

		/*
		 * Assertions
		 */
		$this->assertTrue( $output );

		/*
		 * Clean up
		 */
		remove_filter( 'pre_jetpack_is_mobile', '__return_true' );

	}

	/**
	 * @covers ::is_tablet
	 */
	public function test_is_tablet_cached() : void {

		/*
		 * Set up
		 */
		Utility::set_and_get_hidden_property( $this->_instance, '_is_tablet', false );

		$output = $this->_instance->is_tablet();

		/*
		 * Assertions
		 */
		$this->assertFalse( $output );

	}

	/**
	 * @covers ::is_tablet
	 */
	public function test_is_tablet_on_vip_go_production() : void {

		/*
		 * Set up
		 */
		add_filter( 'pmc_is_production_mock_env', '__return_true' );

		$_SERVER['HTTP_X_MOBILE_CLASS'] = 'tablet';
		$output                         = $this->_instance->is_tablet();

		/*
		 * Assertions
		 */
		$this->assertTrue( $output );

		/*
		 * Clean up
		 */
		remove_filter( 'pmc_is_production_mock_env', '__return_true' );
		unset( $_SERVER['HTTP_X_MOBILE_CLASS'] );

	}

	/**
	 * @covers ::is_tablet
	 */
	public function test_is_tablet_uncached() : void {

		/*
		 * Set up
		 */
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Linux; Android 6.0; GT-P2150) AppleWebKit/537.30 (KHTML, like Gecko) Chrome/53.0.2785 Safari/537.30';
		$output                     = $this->_instance->is_tablet();

		/*
		 * Assertions
		 */
		$this->assertTrue( $output );

		/*
		 * Clean up
		 */
		unset( $_SERVER['HTTP_USER_AGENT'] );

	}

	/**
	 * @covers ::is_ipad
	 */
	public function test_is_ipad_cached() : void {

		/*
		 * Set up
		 */
		Utility::set_and_get_hidden_property( $this->_instance, '_is_ipad', false );

		$output = $this->_instance->is_ipad();

		/*
		 * Assertions
		 */
		$this->assertFalse( $output );

	}

	/**
	 * @covers ::is_ipad
	 */
	public function test_is_ipad_uncached() : void {

		/*
		 * Set up
		 */
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; CPU OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.1 Mobile/15E148 Safari/604.1';
		$output                     = $this->_instance->is_ipad();

		/*
		 * Assertions
		 */
		$this->assertTrue( $output );

		/*
		 * Clean up
		 */
		unset( $_SERVER['HTTP_USER_AGENT'] );

	}

	/**
	 * @covers ::is_bot
	 *
	 * @dataProvider data_get_bot_types
	 */
	public function test_is_bot_cached( string $type ) : void {

		/*
		 * Set up
		 */
		Utility::set_and_get_hidden_property( $this->_instance, '_is_bot', true );
		Utility::set_and_get_hidden_property(
			$this->_instance,
			'_is_bot_type',
			[
				'alexa'            => true,
				'baiduspider'      => true,
				'bingbot'          => true,
				'googlebot'        => true,
				'googlebot-mobile' => true,
				'googlebot-image'  => true,
				'googlebot-news'   => true,
				'msnbot'           => true,
				'pingdom.com_bot'  => true,
				'twitterbot'       => true,
			]
		);

		$output = $this->_instance->is_bot( $type );

		/*
		 * Assertions
		 */
		$this->assertTrue( $output );

	}

	/**
	 * @covers ::is_bot
	 *
	 * @dataProvider data_get_bot_types
	 */
	public function test_is_bot_uncached( string $type, string $user_agent ) : void {

		/*
		 * Set up
		 */
		$_SERVER['HTTP_USER_AGENT'] = $user_agent;
		$output                     = $this->_instance->is_bot( $type );

		/*
		 * Assertions
		 */
		$this->assertTrue( $output );

		/*
		 * Clean up
		 */
		unset( $_SERVER['HTTP_USER_AGENT'] );

	}

}    //end class

//EOF
