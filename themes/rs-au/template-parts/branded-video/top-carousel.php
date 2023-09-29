<?php
/**
 * Template to render top carousel on a Branded Video Landing Page
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since 2019-10-03
 *
 * @package pmc-rollingstone-2018
 */

if ( empty( $post_id ) || empty( $branded_page ) || empty( $branded_page_settings ) ) {
	return;
}

if ( empty( $branded_page_settings['first_carousel'] ) ) {
	return;
}

the_widget(
	'\Rolling_Stone\Inc\Widgets\Video_Top_Featured',
	[
		'carousel' => $branded_page_settings['first_carousel'],
	]
);


//EOF
