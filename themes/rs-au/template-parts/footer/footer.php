<?php
/**
 * Footer.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-11-04
 */

if ( ! is_404() ) {
	if ( ! is_page_template( 'page-templates/page-gmoat-vote.php') ) {
		get_template_part( 'template-parts/ads/below-article' );
		get_template_part( 'template-parts/footer/newswire' );
	}
}

get_template_part( 'template-parts/footer/colophon' );
