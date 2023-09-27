<?php

// phpcs:disable

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
	error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

	$plugins_dir = dirname( dirname( dirname( __DIR__ ) ) );

	require_once( $plugins_dir . '/plugins/vip-init.php' );

	// Load required plugins here
	wpcom_vip_load_plugin( 'pmc-global-functions', 'pmc-plugins' );
	pmc_load_plugin( 'custom-metadata' );
	pmc_load_plugin( 'co-authors-plus', false, '3.2' );
	pmc_load_plugin( 'pmc-unit-test', 'pmc-plugins' );
	pmc_load_plugin( 'pmc-structured-data', 'pmc-plugins' );
	pmc_load_plugin( 'pmc-lists', 'pmc-plugins' );
} );

if ( ! defined( 'WP_TEST_IGNORE_BOOTSTRAP' ) ) {
	require $_tests_dir . '/includes/bootstrap.php';
}
