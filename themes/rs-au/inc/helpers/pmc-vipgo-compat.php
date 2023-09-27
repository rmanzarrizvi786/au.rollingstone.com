<?php
/**
 * Performance and compatibility functions for VIP Go
 *
 * @since 2016-05-28 Corey Gilmore
 *
 * @package indiewire
 *
 */

/**
 * Performance fix: is_multi_author() makes a fairly expensive query for a value that's meaningless on any media site
 * If we remove the hook to clear the cached transient, this should generally only run once.
 *
 * @since 2016-05-28 Corey Gilmore
 *
 * @see is_multi_author()
 */
remove_action( 'transition_post_status', '__clear_multi_author_cache' );


/**
 * WPCOM Compat: Support plugins_url() inside the theme directory - adapted from wpcom_vip_plugins_url()
 *
 * @since 2016-05-28 Corey Gilmore
 */
function pmc_vipgo_compat_theme_plugins_url( $url = '', $path = '', $plugin = '' ) {
	static $content_dir, $pmc_plugins_dir, $pmc_plugins_url;

	if ( ! isset( $content_dir ) ) {
		// Be gentle on Windows, borrowed from core, see plugin_basename
		$content_dir     = str_replace( '\\', '/', WP_CONTENT_DIR ); // sanitize for Win32 installs
		$content_dir     = preg_replace( '|/+|', '/', $content_dir ); // remove any duplicate slash
		$pmc_plugins_dir = untrailingslashit( $content_dir ) . '/plugins/pmc-plugins';
		$pmc_plugins_url = content_url( '/plugins/pmc-plugins' );
	}

	if ( 0 === strpos( $plugin, $pmc_plugins_dir ) ) {
		$url_override = str_replace( $pmc_plugins_dir, $pmc_plugins_url, dirname( $plugin ) );
	} elseif ( 0 === strpos( $plugin, get_stylesheet_directory() ) ) {
		$url_override = str_replace( get_stylesheet_directory(), get_stylesheet_directory_uri(), dirname( $plugin ) );
	}

	if ( isset( $url_override ) ) {
		$url = trailingslashit( $url_override ) . $path;
	}

	return $url;
}
add_filter( 'plugins_url', 'pmc_vipgo_compat_theme_plugins_url', 10, 3 );


/**
 * Send headers to disable caching on VIP Go
 *
 * @param bool $allow_private Set to false to send Cache-Control header without "private"
 *
 * @since 2016-06-15 Corey Gilmore
 *
 */
function pmc_vipgo_send_no_cache_headers( $allow_private = true ) {
	if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
		if ( $allow_private ) {
			header( 'Cache-Control: private, no-cache, no-store, must-revalidate, max-age=0' ); // HTTP 1.1
		} else {
			header( 'Cache-Control: no-cache, no-store, must-revalidate, max-age=0' ); // HTTP 1.1
		}
		header( 'Pragma: no-cache' ); // HTTP 1.0
		header( 'Expires: 0' ); // proxies
	}
}
