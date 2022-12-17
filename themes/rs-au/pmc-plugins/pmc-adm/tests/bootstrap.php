<?php

// need to check if bootstrap need to be ignore
if ( ! defined( 'WP_TEST_IGNORE_BOOTSTRAP' ) ) {
	$_tests_dir = getenv( 'WP_TESTS_DIR' );
	if ( ! $_tests_dir ) {
		$_tests_dir = dirname( __DIR__ ) . '/tests/phpunit';
	}
	require_once $_tests_dir . '/includes/functions.php';
}
// Need to set $_SERVER[ "GEOIP_COUNTRY_CODE" ] for testing is_country ad condition.
$_SERVER[ "GEOIP_COUNTRY_CODE" ] = 'US';
// need to use enclosure function here to avoid function name conflict when unit test are reference from root
tests_add_filter( 'after_setup_theme', function() {

	if ( ! defined( 'PMC_SITE_NAME' ) ) {
		define( 'PMC_SITE_NAME', 'example.org' );
	}

	// suppress warning and only reports errors
	error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
	$plugins_dir = dirname( dirname( dirname( __DIR__ ) ) );

	require_once( $plugins_dir . '/plugins/vip-init.php' );

	// Load required plugins here
	if ( defined('WP_CLI') && WP_CLI ) {
		require_once $plugins_dir . '/plugins/vip-do-not-include-on-wpcom/vip-wp-cli.php';
	}
	wpcom_vip_load_plugin( 'pmc-global-functions', 'pmc-plugins' );
	pmc_load_plugin( 'cheezcap' );
	pmc_load_plugin( 'jwplayer' );
	pmc_load_plugin( 'pmc-adm', 'pmc-plugins' );
	pmc_load_plugin( 'pmc-vertical', 'pmc-plugins' );
	pmc_load_plugin( 'pmc-primary-taxonomy', 'pmc-plugins' );
	pmc_load_plugin( 'pmc-gallery-v3', 'pmc-plugins' );
	pmc_load_plugin( 'pmc-floating-video', 'pmc-plugins' );

	$adsense_provider          = new Adsense_Provider( '1234' );
	$site_served_provider      = new Site_Served_Provider( '1234' );
	$double_click_provider     = new DoubleClick_Provider( '1234' );
	$google_publisher_provider = new Google_Publisher_Provider( '1234' );
	$boomerang_provider        = new Boomerang_Provider( '1234', [
		'script_url' => 'https://some.url/script.js',
	] );

	pmc_adm_add_provider( $adsense_provider );
	pmc_adm_add_provider( $site_served_provider );
	pmc_adm_add_provider( $double_click_provider );
	pmc_adm_add_provider( $google_publisher_provider );
	pmc_adm_add_provider( $boomerang_provider );

} );

if ( ! defined( 'WP_TEST_IGNORE_BOOTSTRAP' ) ) {
	require $_tests_dir . '/includes/bootstrap.php';
	// Disable the deprecated warnings (problem with WP3.7.1 and php 5.5)
	if ( class_exists( 'PHPUnit_Framework_Error_Deprecated' ) ) {
		PHPUnit_Framework_Error_Deprecated::$enabled = false;
	}
}
//EOF