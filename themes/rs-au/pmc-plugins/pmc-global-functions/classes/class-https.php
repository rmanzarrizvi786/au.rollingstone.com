<?php
/**
 * Enable specific requirements for HTTPS
 */

namespace PMC\Global_Functions;

use PMC\Global_Functions\Traits\Singleton;

class HTTPS {
	use Singleton;

	/**
	 * Load the filter
	 */
	protected function __construct() {
		// Setup CSP headers only on SSL pages
		if ( \PMC::is_https() ) {
			add_filter( 'wp_headers', [ $this, 'add_csp_headers' ] );

			if ( function_exists( 'wpcom_vip_enable_https_canonical' ) ) {
				wpcom_vip_enable_https_canonical(); // Used to alert WP that we've moved to HTTPS.
			}
		}
	}

	/**
	 * CSP headers (https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP)
	 *
	 * Content-Security-Policy header is setup to upgrade https requests to https if valid
	 * Content-Security-Policy-Report-Only header is setup to send mixed content warning to report-uri
	 *
	 * This class will enable report-uri (https://report-uri.com/) for all sites when the page is loaded over HTTPS
	 * Any "mixed content warning" will be reported in an effort to clear them out and make our sites secure
	 *
	 *
	 * @param array $headers Array of headers.
	 * @return array $headers Array of headers.
	 */
	public function add_csp_headers( $headers = [] ) {
		$headers['Content-Security-Policy']             = 'upgrade-insecure-requests';
		$headers['Content-Security-Policy-Report-Only'] = "default-src data: 'unsafe-inline' 'unsafe-eval' https: blob: http://*.files.wordpress.com wss://" . wp_parse_url( get_home_url(), PHP_URL_HOST ) . '; report-uri https://pmcuri.report-uri.com/r/d/csp/reportOnly';
		return $headers;
	}
}

