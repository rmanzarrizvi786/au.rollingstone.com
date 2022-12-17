<?php

/*
 * This class is to simplify the caching of data returned by a method. If the
 * data is already in cache then that cached data is returned else it fetches
 * the data from the method, stores it in cache and returns it. So instead of
 * making multiple calls to check for cache and then generating data and
 * caching it, a chain of methods can be called instead and this
 * class does all the cache validity and data generation etc.
 *
 * This class uses wp_cache for the caching purpose.
 *
 * @since 2013-04-19 Amit Gupta
 */

class PMC_Cache {

	const error_code = 'pmc_cache';

	private static $cache_group = 'pmc_cache_v1';

	protected $_key;
	protected $_expiry = 900;	//15 minutes, default expiry
	protected $_callback;
	protected $_params = array();

	/**
	 * PMC_Cache constructor.
	 *
	 * @param string $cache_key
	 * @param string $cache_group
	 */
	public function __construct( $cache_key = '', $cache_group = '' ) {
		if( empty( $cache_key ) || ! is_string( $cache_key ) ) {
			return new WP_Error( self::error_code, __("Cache key is required to create PMC_Cache object") );
		}

		$this->_key = md5( $cache_key );

		if( ! empty( $cache_group ) && is_string( $cache_group ) ) {
			self::$cache_group = $cache_group;
		}

		//call init
		$this->_init();
	}

	protected function _init() {
		
		// NOTE!!!
		// Be very careful filtering this cache_group value!!
		// All uses of PMC_Cache use this group!!
		// Check the passed $_key value to ensure you're only
		// filtering the group for your cache instance or similar.
		$cache_group = apply_filters( 'pmc_cache_group_override', self::$cache_group, $this->_key );

		if( ! empty( $cache_group ) && is_string( $cache_group ) ) {
			self::$cache_group = $cache_group;
		}

		unset( $cache_group );
	}

	/**
	 * This function is for deleting the cache
	 */
	public function invalidate() {
		wp_cache_delete( $this->_key, self::$cache_group );

		return $this;
	}

	/**
	 * This function accepts the cache expiry
	 */
	public function expires_in( $expiry ) {
		$expiry = intval( $expiry );

		if( $expiry > 0 ) {
			$this->_expiry = $expiry;
		}

		unset( $expiry );

		return $this;
	}

	/**
	 * This function accepts the callback from which data is to be received
	 */
	public function updates_with( $callback, $params = array() ) {
		if( empty( $callback ) || ! is_callable( $callback ) ) {
			return new WP_Error( self::error_code, __("Callback passed is not callable") );
		}

		if( ! is_array( $params ) ) {
			return new WP_Error( self::error_code, __("All parameters for the callback must be in an array") );
		}

		$this->_callback = $callback;
		$this->_params = $params;

		return $this;
	}

	/**
	 * This function returns the data from cache if it exists or returns the
	 * data it gets back from the callback and caches it as well
	 */
	public function get() {

		$data = wp_cache_get( $this->_key, self::$cache_group );

		if ( ! empty( $data ) ) {

			if ( 'empty' === $data ) {
				return false;
			}

			return $data;
		}



		//If we don't have a callback to get data from or if its not a valid
		//callback then return error. This will happen in the case when
		//updates_with() is not called before get()
		if( empty( $this->_callback ) || ! is_callable( $this->_callback ) ) {
			return new WP_Error( self::error_code, __("No valid callback set") );
		}

		try {
			$data = call_user_func_array( $this->_callback, $this->_params );
			if ( empty( $data ) ) {
				$data = 'empty';
			}
			wp_cache_set( $this->_key, $data, self::$cache_group, $this->_expiry );

			if ( 'empty' === $data ) {
				return false;
			}
		}
		catch ( Exception $e ) {
			$data = false;
		}

		return $data;
	}

//end of class
}

//EOF
