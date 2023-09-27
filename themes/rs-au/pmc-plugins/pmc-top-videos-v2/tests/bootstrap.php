<?php

// need to check if bootstrap need to be ignore
if ( ! defined( 'WP_TEST_IGNORE_BOOTSTRAP' ) ) {
	$_tests_dir = getenv( 'WP_TESTS_DIR' );
	if ( ! $_tests_dir ) {
		$_tests_dir = dirname( __DIR__ ) . '/tests/phpunit';
		if ( ! file_exists( $_tests_dir ) ) {
			$_tests_dir = '/var/www/html/wp-tests/tests/phpunit/';
		}
	}
	require_once $_tests_dir . '/includes/functions.php';
}

// need to use enclosure function here to avoid function name conflict when unit test are reference from root
tests_add_filter(
	'after_setup_theme', function() {
		// suppress warning and only reports errors
		error_reporting( E_CORE_ERROR | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_RECOVERABLE_ERROR ); // phpcs:ignore

		// IMPORTANT: We need to make sure the unit test is compatible with vip go environment testing
		if ( ! defined( 'IS_VIP_GO' ) || false === IS_VIP_GO ) {
			require_once( WP_CONTENT_DIR . '/themes/vip/plugins/vip-init.php' );
		}

		// Load required plugins here
		wpcom_vip_load_plugin( 'pmc-global-functions', 'pmc-plugins' );

		// Load any dependencies plugin via pmc_load_plugin here
		pmc_load_plugin( 'pmc-unit-test', 'pmc-plugins' );
		pmc_load_plugin( 'fieldmanager', false, '1.1' );
		pmc_load_plugin( 'fm-widgets', 'pmc-plugins' );
	}
);

if ( ! defined( 'WP_TEST_IGNORE_BOOTSTRAP' ) ) {
	require $_tests_dir . '/includes/bootstrap.php';
}

// EOF
