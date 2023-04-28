<?php

/**
 * Front section 300x250 Ad.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-03-16
 */
?>
<div class="c-ad c-ad--300x250">
	<?php
	if ('Music' == $section_title) {
		ThemeSetup::render_ads('vrec_2', '', 300);
	} else if ('Culture' == $section_title) {
		// ThemeSetup::render_ads( 'content_1', '', 300 );
		ThemeSetup::render_ads('vrec_3', '', 300, 2);
	} else if ('Movies' == $section_title) {
		ThemeSetup::render_ads('vrec_4', '', 300, 2);
	} else if ('TV' == $section_title) {
		ThemeSetup::render_ads('vrec_5', '', 300, 2);
	}
	?>
</div>
<div class="c-ad c-ad--300x250" style="margin-top: 1rem;">
	<?php
	// pmc_adm_render_ads( 'front-section-sticky-ad' );
	/*
		if ( 'Music' == $section_title ) {
			ThemeSetup::render_ads( 'content_3', '', 300 );
		} else if ( 'Politics' == $section_title ) {
			ThemeSetup::render_ads( 'content_4', '', 300 );
		} else if ( 'Sports' == $section_title ) {
			ThemeSetup::render_ads( 'content_5', '', 300 );
		}
		*/
	// ThemeSetup::render_ads( 'side_1', '', 300 );
	?>
</div>