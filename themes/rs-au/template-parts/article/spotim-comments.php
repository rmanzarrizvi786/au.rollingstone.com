<?php
/**
 * Spot.IM comments template.
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2019-05-14
 */

global $post;

if ( class_exists( '\SpotIM_Frontend' ) ) {

	\SpotIM_Frontend::display_comments();

}
