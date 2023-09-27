<?php
require_once getenv('PMC_PHPUNIT_BOOTSTRAP');

// need to use enclosure function here to avoid function name conflict when unit test are reference from root
tests_add_filter(
	'after_setup_theme', function() {
		// suppress warning and only reports errors
		error_reporting( E_CORE_ERROR | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_RECOVERABLE_ERROR ); // phpcs:ignore

		// Load any dependencies here

		// doesn't autoload.
		require_once 'pmc-linkcontent.php';

	}
);

PMC\Unit_Test\Bootstrap::get_instance()->start();

// EOF
