<?php
namespace PMC\Adm\Providers;

/**
 * PMC Default provider to fall back to prevent non-existing provider error
 * See PMC_Ads::get_provider
 */

class PMC_Default extends \PMC_Ad_Provider {

	/**
	 * Include any 3rd-party scripts.
	 */
	public function include_assets() {

	}

	/**
	 * To Render or return an ad markup.
	 *
	 * @param array $data Ads Data.
	 * @param bool $echo should echo or not.
	 *
	 * @return void
	 */
	public function render_ad( array $data, $echo = false ) {

	}

	/**
	 * Return the Admin UI templates from /pmc-adm/templates/provider-admin/*.php
	 */
	public function get_admin_templates() {

	}

}

//EOF
