<?php
// Define own common test base class

namespace PMC\Global_Functions\Tests;

use PMC\Unit_Test\Utility;

// Define as abstract class to prevent test suite from scanning for test method
abstract class Base extends \PMC\Unit_Test\Base {

	// We need to define this function to load and activate our plugin
	protected function _load_plugin() {

	}

	// Add additionial protected helper method or common codes to shared among unit test cases

}    // end of class

//EOF
