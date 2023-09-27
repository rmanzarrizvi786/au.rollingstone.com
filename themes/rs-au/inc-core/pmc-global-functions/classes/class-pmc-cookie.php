<?php

namespace PMC\Global_Functions\Classes;

/**
 * Class to set/get secure cookies, this should be used instead of using `setcookie` directly
 *
 * Class PMC_Cookie
 * @package PMC\Global_Functions\Classes
 */
class PMC_Cookie {

	private static $_separator = '.';
	private static $_salt      = '1NMFIUPQmUC3oVdwvZ4TMA=/0e635da5';

	/**
	 * Set cookie using a hash for the value for security purposes.
	 *
	 * @param string $name    The cookie name, e.g. 'my_cookie'
	 * @param string $value   The cookie value, e.g. 'my_value'
	 * @param int $expire     The cookie expire seconds e.g. '3600'
	 * @param string $path    The cookie path e.g. '/fashion-new'
	 * @param string $domain  The cookie domain e.g. 'wwd.com'
	 * @param bool $secure    The cookie HTTPS secure e.g. 'true'
	 * @param bool $http_only The cookie http only request e.g. 'true'
	 *
	 * @return bool If output exists prior to calling this function, it will fail and return FALSE. If function successfully runs, it will return TRUE.
	 */
	public function set_signed_cookie( string $name, string $value, int $expire = 0, string $path = '', string $domain = '', bool $secure = false, bool $http_only = false ) : bool {
		$hash_value = $value . self::$_separator . md5( $value . self::$_salt );
		return $this->set_cookie( $name, $hash_value, $expire, $path, $domain, $secure, $http_only );
	}

	/**
	 * This method is a wrapper for PHP's setcookie() method with added functionality
	 * to allow unit testing setting up cookies by providing testable data on PHP Cli.
	 * Use `set_signed_cookie` method  for security purposes and mock this function
	 * for tests
	 *
	 * @param string $name      The name of the cookie.
	 * @param string $value     The value of the cookie.
	 * @param int    $expire    The time the cookie expires. This is a Unix timestamp so is in number of seconds since the epoch.
	 * @param string $path      The path on the server in which the cookie will be available on. If set to '/', the cookie will be available within the entire domain.
	 * @param string $domain    The (sub)domain that the cookie is available to.
	 * @param bool   $secure    Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client. When set to TRUE, the cookie will only be set if a secure connection exists.
	 * @param bool   $http_only When TRUE the cookie will be made accessible only through the HTTP protocol.
	 *
	 * @return bool If output exists prior to calling this function, it will fail and return FALSE. If function successfully runs, it will return TRUE.
	 *
	 * @note Ignoring this from code coverage since we do not have a reliable way to test this at present
	 * @codeCoverageIgnore
	 */
	public function set_cookie( string $name, string $value, int $expire = 0, string $path = '', string $domain = '', bool $secure = false, bool $http_only = false ) : bool {
		if ( php_sapi_name() !== 'cli' ) {
			/*
			 * Code is not running on PHP Cli and we are in clear.
			 * Use the PHP method and bail out.
			 *
			 * Ignore the below line in PHPCS because VIP ruleset flags setcookie() as it is not
			 * compatible with Batcache. This however is needed to work with CDN_Cache class
			 * which will work with Fastly and replace Batcache.
			 */
			return (bool) setcookie( $name, $value, $expire, $path, $domain, $secure, $http_only );  // phpcs:ignore WordPress.VIP.RestrictedFunctions.cookies_setcookie
		}

		/*
		 * Code is running on PHP Cli
		 * So lets add value to $_COOKIE array and bail out
		 *
		 * Ignore the below line in PHPCS because this code will run only in CLI mode to
		 * allow for unit tests to test cookie data
		 */
		$_COOKIE[ $name ] = $value; // phpcs:ignore WordPress.VIP.RestrictedVariables.cache_constraints___COOKIE

		return true;
	}

	/**
	 * This method is a wrapper for PHP's filter_input() method with added functionality
	 * to allow unit testing getting cookie value by providing testable data on PHP Cli
	 *
	 * @param int $type
	 * @param string $variable_name
	 * @param int $filter
	 * @param mixed $options
	 *
	 * @return mixed <b>FALSE</b> if the variable is not set and <b>NULL</b> if the filter fails.
	 *
	 * @link http://php.net/manual/en/function.filter-input.php
	 *
	 * @note Ignoring this from code coverage since we do not have a reliable way to test this at present
	 * @codeCoverageIgnore
	 */
	public function filter_input( int $type, string $variable_name, int $filter = FILTER_DEFAULT, $options = null ) {
		return filter_input( $type, $variable_name, $filter, $options );
	}

	/**
	 * Return the cookie value using the cookie name, if the value is malicious
	 * the function return `null` and delete the cookie.
	 *
	 * @param string $name The cookie name, e.g. 'my_cookie'
	 *
	 * @return string|null
	 */
	public function get_cookie_value( string $name ) {
		// Validate if the cookie exist
		if ( $this->filter_input( INPUT_COOKIE, $name ) ) {
			$cookie_name    = sanitize_text_field( wp_unslash( $this->filter_input( INPUT_COOKIE, $name ) ) );
			$value_and_hash = explode( self::$_separator, $cookie_name );

			// if the cookie is trustworthy we are going to return the cookie value
			// else we are going to delete it and return `null`
			if ( count( $value_and_hash ) === 2 &&
				md5( $value_and_hash[0] . self::$_salt ) === $value_and_hash[1]
			) {
				return $value_and_hash[0];
			} else {
				//Delete malicious cookie
				$this->delete_cookie( $name );
				return null;
			}
		}

		return null;
	}

	/**
	 * Delete the cookie from the browser and the `$_COOKIE` array using the cookie name
	 *
	 * @param string $name The cookie name, e.g. 'my_cookie'
	 */
	public function delete_cookie( string $name ) {
		if ( $this->filter_input( INPUT_COOKIE, $name ) ) {
			unset( $_COOKIE[ $name ] ); // phpcs:ignore WordPress.VIP.RestrictedVariables.cache_constraints___COOKIE, WordPress.VIP.SuperGlobalInputUsage.AccessDetected
		}

		$this->set_signed_cookie( $name, '', time() - 3600 );
	}
}
