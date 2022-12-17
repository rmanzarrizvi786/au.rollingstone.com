<?php

class PMC_Plugin_Loader {

	public static function load_global_plugins() {
		if ( ! function_exists( 'load_pmc_plugins' ) ) {
			return;
		}

		load_pmc_plugins( array( 'pmc-plugins' => array( 'pmc-helpdesk', 'pmc-cloudflare' ) ) );

		self::_load_settings_for_plugin();
	}


	private static function _load_settings_for_plugin() {

		self::_load_pmc_helpdesk_settings();
	}

	private static function _load_pmc_helpdesk_settings() {

		add_filter(
			'pmc-helpdesk-form-headers', function ( $headers ) {
				// Default "From" should is the end user's e-mail address, which makes replies and filtering easy.
				// We're going to add a "Sender" header to the e-mail so that sites can use a valid sender to alleviate spam.
				$headers['Sender'] = 'do-not-reply@pmc.com';

				return $headers;
			}
		);

		add_filter(
			'pmc-helpdesk-form-to', function ( $default_email_address ) {
				$recipient_list = array(
					'helpdesk@pmc.com',
				);


				if ( defined( 'PMC_SITE_NAME' ) ) {
					$recipient_list[] = 'websupport+' . PMC_SITE_NAME . '@pmc.com';
				} else {
					$recipient_list[] = 'websupport@pmc.com';
				}

				$to_list = array();
				foreach ( $recipient_list as $email_address ) {
					$user = get_user_by( 'email', $email_address );
					if ( $user ) {
						$to_list[] = $user->display_name . ' <' . $email_address . '>';
					} else {
						$to_list[] = $email_address;
					}
				}

				$to = implode( ',', $to_list );

				return $to;
			}
		);
	}

}
//EOF