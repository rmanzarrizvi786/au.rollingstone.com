<?php
/**
 * All must-have plugins (both VIP & PMC Plugins) which have
 * to be loaded on all sites.
 *
 * @author Amit Gupta <agupta@pmc.com>
 * @since 2015-07-06
 */


class PMC_Must_Have_Plugins {

	/**
	 * This function loads all the must have VIP plugins
	 *
	 * @return void
	 */
	public static function load_vip_plugins() {
		pmc_load_plugin( 'cheezcap' );
		pmc_load_plugin( 'pmc-geo-uniques', 'pmc-plugins' );
	}

	/**
	 * This function loads all the must have PMC plugins
	 *
	 * @return void
	 */
	public static function load_pmc_plugins() {

		// Load the pmc-wp-cli plugin before the must have plugins in case they need it.
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			// We can't cover this code here because of WP_CLI constant
			pmc_load_plugin( 'pmc-wp-cli', 'pmc-plugins' ); // @codeCoverageIgnore
		}

		pmc_load_plugin( 'pmc-tracking', 'pmc-plugins' );
		pmc_load_plugin( 'pmc-js-libraries', 'pmc-plugins' );
		pmc_load_plugin( 'pmc-krux-tag', 'pmc-plugins' );
		pmc_load_plugin( 'pmc-page-meta', 'pmc-plugins' );
		pmc_load_plugin( 'pmc-google-tagmanager', 'pmc-plugins' );
		pmc_load_plugin( 'pmc-disable-live-chat', 'pmc-plugins' );
		pmc_load_plugin( 'pmc-linkcount', 'pmc-plugins' );
		pmc_load_plugin( 'pmc-taxonomy-export', 'pmc-plugins' );
		pmc_load_plugin( 'pmc-omni', 'pmc-plugins' );
		pmc_load_plugin( 'pmc-options', 'pmc-plugins' );
		pmc_load_plugin( 'pmc-post-options', 'pmc-plugins' );
		pmc_load_plugin( 'pmc-post-reviewer', 'pmc-plugins' );

	}

}	//end of class



//EOF
