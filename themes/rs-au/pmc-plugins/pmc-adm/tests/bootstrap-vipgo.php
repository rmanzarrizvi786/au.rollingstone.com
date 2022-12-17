<?php
define( 'VIP_GO_ENV', true );

// need to check if bootstrap need to be ignore
if ( ! defined( 'WP_TEST_IGNORE_BOOTSTRAP' ) ) {
	$_tests_dir = getenv( 'WP_TESTS_DIR' );
	if ( ! $_tests_dir ) {
		$_tests_dir = dirname( __DIR__ ) . '/tests/phpunit';
	}
	require_once $_tests_dir . '/includes/functions.php';
}

// need to use enclosure function here to avoid function name conflict when unit test are reference from root
tests_add_filter( 'after_setup_theme', function() {

	// suppress warning and only reports errors
	error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
	$plugins_dir = dirname( dirname( dirname( __DIR__ ) ) );

	wpcom_vip_load_plugin( 'pmc-global-functions', 'pmc-plugins' );
	pmc_load_plugin( 'cheezcap' );
	pmc_load_plugin( 'pmc-adm', 'pmc-plugins' );
	pmc_load_plugin( 'pmc-vertical', 'pmc-plugins' );
} );

if ( ! defined( 'WP_TEST_IGNORE_BOOTSTRAP' ) ) {
	require $_tests_dir . '/includes/bootstrap.php';
	// Disable the deprecated warnings (problem with WP3.7.1 and php 5.5)
	if ( class_exists( 'PHPUnit_Framework_Error_Deprecated' ) ) {
	PHPUnit_Framework_Error_Deprecated::$enabled = false;
	}
}
//EOF