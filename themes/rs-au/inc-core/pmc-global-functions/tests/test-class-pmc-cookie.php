<?php

namespace PMC\Global_Functions\Tests;

use PMC\Global_Functions\Classes\PMC_Cookie;

/**
 * @group pmc-global-functions
 *
 * Unit test for class PMC_Cookie
 *
 * Author: Miguel Angel Liriano <miguel.liriano@x-team.com>
 *
 * @requires PHP 5.3
 * @coversDefaultClass \PMC\Global_Functions\Classes\PMC_Cookie
 */
class Test_Class_PMC_Cookie extends Base {

	private $_class_cookie;

	function setUp() {
		unset( $_COOKIE[ 'cookieName' ] );

		$this->_class_cookie = $this->getMockBuilder( PMC_cookie::class )
			->disableOriginalConstructor()
			->setMethods( [
				'set_cookie',
				'filter_input',
			] )
			->getMock();

		parent::setUp();
	}

	/**
	 * Should create cookie with hash value
	 *
	 * @covers ::set_signed_cookie()
	 */
	public function test_assert_set_signed_cookie() {

		$name      = 'cookieName';
		$value     = 'value test';
		$expire    = 360;
		$path      = '/';
		$domain    = 'testing';
		$secure    = true;
		$http_only = true;

		$this->_class_cookie->expects( $this->exactly( 1 ) )
			->method( 'set_cookie' )
			->with( $name, 'value test.b70ca126b542c9748ee87bd4903bd6e7', $expire, $path, $domain, $secure, $http_only )
			->willReturn( true );

		$this->assertTrue( $this->_class_cookie->set_signed_cookie( $name, $value, $expire, $path, $domain, $secure, $http_only ) );
	}

	/**
	 * Should return null if cookie is undefined
	 *
	 * @covers ::get_cookie_value()
	 */
	public function test_get_cookie_value_with_undefined_cookie() {

		$this->assertNull( $this->_class_cookie->get_cookie_value( 'cookieName' ) );
	}

	/**
	 * Should return null if cookie is not trustworthy
	 *
	 * @covers ::get_cookie_value()
	 */
	public function test_get_cookie_value_with_not_trustworthy_value() {
		$name = 'cookieName';

		$cookieClass = $this->getMockBuilder( PMC_cookie::class )
			->disableOriginalConstructor()
			->setMethods( [
				'delete_cookie',
				'filter_input',
			] )
			->getMock();

		$cookieClass->expects( $this->once() )
			->method( 'delete_cookie' )
			->with( $name )
			->willReturn( true );

		$cookieClass->expects( $this->exactly( 2 ) )
			->method( 'filter_input' )
			->with( INPUT_COOKIE, $name )
			->willReturn( 'testing' );

		$_COOKIE[ $name ] = 'testing';

		$this->assertNull( $cookieClass->get_cookie_value( 'cookieName' ) );
	}

	/**
	 * Should return the value if cookie is trustworthy
	 *
	 * @covers ::get_cookie_value()
	 */
	public function test_get_cookie_value_with_trustworthy_value() {
		$this->_class_cookie->expects( $this->exactly( 2 ) )
			->method( 'filter_input' )
			->with( INPUT_COOKIE, 'cookieName' )
			->willReturn( 'test.682d0e57ac1287d7936d65c5cfadf597' );

		$this->assertEquals( 'test', $this->_class_cookie->get_cookie_value( 'cookieName' ) );
	}

	/**
	 * Should delete cookie
	 *
	 * @covers ::delete_cookie()
	 */
	public function test_delete_cookie() {
		$name = 'cookieName';

		$_COOKIE[ $name ] = 'test.682d0e57ac1287d7936d65c5cfadf597';

		$cookie_class = $this->getMockBuilder( PMC_cookie::class )
			->disableOriginalConstructor()
			->setMethods( [
				'set_signed_cookie',
				'filter_input',
			] )
			->getMock();

		$cookie_class->expects( $this->once() )
			->method( 'filter_input' )
			->with( INPUT_COOKIE, 'cookieName' )
			->willReturn( 'test.682d0e57ac1287d7936d65c5cfadf597' );


		$cookie_class->expects( $this->once() )
			->method( 'set_signed_cookie' )
			->with( $name )
			->willReturn( true );

		$cookie_class->delete_cookie( $name );
		$this->assertEmpty( $_COOKIE );
	}
}
