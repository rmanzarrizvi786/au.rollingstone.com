<?php
/**
 * WordPress Admin hooks and filters
 *
 * @package WordPress
 * @subpackage pmc-plugins
 *
 * @since 2014-12-09 Corey Gilmore
 *
 */


/**
 * Add information about the current screen to the admin body class
 *
 * @see PPT-3825
 * @see http://codex.wordpress.org/Function_Reference/get_current_screen
 *
 * @since 2014-12-09 Corey Gilmore
 *
 */
function pmc_admin_screen_body_class( $classes ) {
	$screen = get_current_screen();

	$attrs = array(
		'action',
		'base',
		'id',
		'parent_base',
		'parent_file',
		'post_type',
		'taxonomy',
	);

	// Loop through all of the named attributes
	foreach( $attrs as $attr ) {
		if( isset( $screen->$attr ) && ( is_string( $screen->$attr ) || is_numeric( $screen->$attr ) ) && $screen->$attr != '' ) {
			$val = $screen->$attr;

			// Normalize the screen value a bit
			// First remove any invalid CSS characters to avoid inadvertantly adding an unwanted class name
			$val = '--' . preg_replace( '/[^_a-zA-Z0-9-]+/is', '-', $val );

			$val = preg_replace( '/--+/', '-', $val ); // remove multiple hyphens
			$val = trim( $val, '-' ); // trim any leading or tailing hyphens
			if( !empty( $val ) && $val != '-' ) {
				$classes .= ' ' . esc_attr( 'pmc-wpadmin-screen-' . $attr . '-' . $val );
			}
		}
	}

	return $classes;
}
add_filter( 'admin_body_class', 'pmc_admin_screen_body_class' );


// EOF