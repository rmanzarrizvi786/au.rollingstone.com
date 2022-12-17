<?php
/**
 * To register all WP CLI command
 *
 * @author  Dhaval Parekh <dhaval.parekh@rtcamp.com>
 *
 * @since   2018-11-28
 *
 * @package pmc-rollingstone-2018
 */

namespace Rolling_Stone\Inc;

use PMC\Global_Functions\Traits\Singleton;

/**
 * Class WP_CLI
 */
class WP_CLI {

	use Singleton;

	/**
	 * WP_CLI constructor.
	 */
	protected function __construct() {
		$this->_register_cli_command();
	}

	/**
	 * To register all cli command.
	 */
	protected function _register_cli_command() {
		\WP_CLI::add_command( 'rs-lists', '\Rolling_Stone\Inc\WP_CLI\Lists' );
		\WP_CLI::add_command( 'rs-utilities', '\Rolling_Stone\Inc\WP_CLI\CLI_Utilities' );
	}

}
