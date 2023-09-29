<?php
namespace PMC\Global_Functions\Tests;

use \PMC\Unit_Test\Utility;

/**
 * @group pmc-global-functions
 * PHPUnit tests for functions located within pmc-global-functions.php
 *
 * @requires PHP 5.3
 */
class Tests_PMC_Global_Functions extends Base {

	/*
	 * Array of random plugins to test plugin loading with
	 *
	 * NOTE: This array and the next need to contain different
	 *       plugins for the tests to work properly.
	 */
	public $plugins = array(
		'pmc-plugins' => array(
			'pmc-disable-comments',
			'pmc-adm',
		),
		'plugins'     => array(
			'cache-nav-menu',
			'byline',
			'edit-flow',
		),
	);

	/*
	 * Array of random plugins to test plugin loading with
	 *
	 * NOTE: For now we're unable to test versioned plugins
	 * in pmc-plugins due to how we've structured our plugins.
	 * All our plugin manifest files are named the same as their
	 * containing directory, e.g. 'pmc-gallery-v2/pmc-gallery-v2.php'
	 *
	 * For versioned plugins to work we would need to rename
	 * things like so: 'pmc-gallery-v2/pmc-gallery.php'
	 */
	public $plugins_with_versions = array(
		'plugins' => array(
			'facebook-instant-articles' => '3.2',
			'wpcom-legacy-redirector'   => '1.2.0',
			'fieldmanager'              => '1.1',
		),
	);

	/**
	 * Array of plugins we'll use to test loading inclusion
	 */
	public $plugins_for_inclusion = array(
		'pmc-plugins' => array(
			'pmc-fubar',
		),
		'plugins'     => array(
			'blah-foo',
		),
	);

	/**
	 * Array of plugins we'll use to test loading exclusion
	 */
	public $plugins_for_exclusion = array(
		'pmc-plugins' => array(
			'pmc-adm',
		),
		'plugins'     => array(
			'byline',
		),
	);

	public function setUp() {
		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;
		parent::setUp();

	}

	public function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * Test that pmc_load_plugin() does indeed load a plugin
	 *
	 * @covers \pmc_load_plugin
	 */
	public function test_that_pmc_load_plugin_loads_a_plugin() {

		// Test a pmc plugin, this plugin should already loaded via bootstrap
		$this->assertFalse( pmc_load_plugin( 'pmc-global-functions', 'pmc-plugins' ) );

		// Test a WPCOM shared plugin
		$this->assertTrue( pmc_load_plugin( 'fieldmanager', false, '1.1' ) );

		// And do an additional check to ensure the plugins were loaded
		$this->_assert_if_plugins_are_loaded(
			[
				'pmc-plugins' => [ 'pmc-global-functions' ],
				'plugins'     => [ 'fieldmanager-1.1' ],
			]
		);
	}

	/**
	 * Test that we can exclude a plugin from loading with pmc_load_plugin()
	 *
	 * @covers \pmc_load_plugin
	 */
	public function test_that_a_plugin_can_be_excluded_from_loading() {

		// Exclude a plugin from loading
		add_filter( 'pmc_do_not_load_plugin', array( $this, '_exclude_plugin_from_loading' ), 10, 4 );

		// pmc_load_plugin() will return false when requested plugin
		// has previously been filtered for exclusion.
		$this->assertFalse( pmc_load_plugin( 'pmc-global-functions', 'pmc-plugins' ) );

		// Remove the exclusion filter so we don't mess up other unit tests
		remove_filter( 'pmc_do_not_load_plugin', array( $this, '_exclude_plugin_from_loading' ), 10 );
	}

	/**
	 * Test that a plugin may only be loaded once with pmc_load_plugin()
	 *
	 * @covers \pmc_load_plugin
	 */
	public function test_that_a_plugin_cant_be_loaded_twice() {

		// Note, due to the way pmc_load_plugin() is written,
		// this test will only pass (receive false) for non-pmc plugins.
		pmc_load_plugin( 'fieldmanager', false, '1.1' );
		$this->assertFalse( pmc_load_plugin( 'fieldmanager', false, '1.1' ) );
	}

	/**
	 * Test that an array of plugins can be included with load_pmc_plugins()
	 *
	 * @covers \pmc_load_plugin
	 */
	public function test_that_multiple_plugins_can_be_loaded() {
		load_pmc_plugins( $this->plugins );
		$this->_assert_if_plugins_are_loaded( $this->plugins );
	}

	/**
	 * Test that an array of plugins with version numbers can be included
	 * with load_pmc_plugins()
	 *
	 * @covers \load_pmc_plugins
	 */
	public function test_that_multiple_plugins_w_versions_can_be_loaded() {
		load_pmc_plugins( $this->plugins_with_versions );
		$this->_assert_if_plugins_are_loaded( $this->plugins_with_versions );
	}

	/**
	 * Test that plugins may be included and excluded
	 * with load_pmc_plugins_filter()
	 *
	 * @covers \load_pmc_plugins_filter
	 */
	public function test_plugin_filter_with_load_pmc_plugins_filter() {
		$plugins = load_pmc_plugins_filter(
			$this->plugins,
			$this->plugins_for_inclusion,
			$this->plugins_for_exclusion
		);

		// Verify that additional plugins were added via inclusion
		$this->assertTrue(
			in_array(
				$this->plugins_for_inclusion['pmc-plugins'][0],
				$plugins['pmc-plugins']
			)
		);

		$this->assertTrue(
			in_array(
				$this->plugins_for_inclusion['plugins'][0],
				$plugins['plugins']
			)
		);

		// Verify that some plugins were removed via exclusion
		$this->assertFalse(
			in_array(
				$this->plugins_for_exclusion['pmc-plugins'][0],
				$plugins['pmc-plugins']
			)
		);

		$this->assertFalse(
			in_array(
				$this->plugins_for_exclusion['plugins'][0],
				$plugins['plugins']
			)
		);
	}

	/**
	 * Filter the plugins to be excluded
	 *
	 * @param bool   $do_exclusion False by default.
	 * @param string $plugin       The name of the plugin
	 * @param bool|string $folder  The folder which contains the plugin
	 * @param bool|string $version The version of the plugin being loaded. False when not set.
	 *
	 * @return bool Return true to prevent loading of the named plugin.
	 */
	public function _exclude_plugin_from_loading( $do_exclusion = false, $plugin = '', $folder = '', $version = false ) {
		if ( 'pmc-global-functions' === $plugin ) {
			$do_exclusion = true;
		}
		return $do_exclusion;
	}

	/**
	 * After an array of plugins has been loaded, test that
	 * they are indeed loaded.
	 *
	 * @param array $maybe_loaded_plugins An array of plugins which have been loaded
	 *
	 * @return null
	 */
	public function _assert_if_plugins_are_loaded( $maybe_loaded_plugins = array() ) {
		global $vip_loaded_plugins;

		if ( ! empty( $maybe_loaded_plugins ) && is_array( $maybe_loaded_plugins ) ) {
			foreach ( $maybe_loaded_plugins as $folder => $plugins ) {

				if ( ! empty( $plugins ) && is_array( $plugins ) ) {
					foreach ( $plugins as $key => $value ) {

						$version = false;

						if ( is_numeric( $key ) ) {
							$plugin = $value;
						} else {
							$plugin  = $key;
							$version = $value;
						}

						if ( false !== $version ) {
							$plugin .= '-' . $version;
						}

						$this->assertTrue( in_array( "{$folder}/{$plugin}", $vip_loaded_plugins ) );
					}
				}
			}
		}
	}

}


//EOF
