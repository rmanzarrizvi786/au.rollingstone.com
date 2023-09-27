<?php
/**
 * Template Name: Charts Single
 *
 * @package pmc-rollingstone-2018
 * @since 2019-04-22
 */

get_header();
echo do_shortcode( apply_filters( 'single_chart_template_shortcode', 'No Data Available' ) );
get_footer();
