<?php
/**
 * IMPORTANT: Do not add any code before this require_once statement below
 */
require_once getenv('PMC_PHPUNIT_BOOTSTRAP');

// need to use enclosure function here to avoid function name conflict when unit test are reference from root
tests_add_filter( 'after_setup_theme', function() {

	define( 'PMC_GLOBAL_FUNCTIONS_TESTS_ROOT', rtrim( __DIR__, '/' ) );

	// suppress warning and only reports errors
	error_reporting( E_CORE_ERROR | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_RECOVERABLE_ERROR ); // phpcs:ignore

	pmc_load_plugin( 'co-authors-plus', 'plugins' );
	pmc_load_plugin( 'pmc-guest-authors', 'pmc-plugins' );
	pmc_load_plugin( 'cheezcap' );
	pmc_load_plugin( 'pmc-post-options', 'pmc-plugins' );

	require_once PMC_GLOBAL_FUNCTIONS_TESTS_ROOT . '/mocks/helpers.php';

	// Init all CheezCap options.
	\PMC_Cheezcap::get_instance()->register();

	if( !class_exists( 'PMC_Gallery_Common' ) ) {
		pmc_load_plugin( 'pmc-gallery', 'pmc-plugins' );
	}

} );


\PMC\Unit_Test\Bootstrap::get_instance()->start();

// EOF
