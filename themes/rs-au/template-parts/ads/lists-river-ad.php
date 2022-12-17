<?php

/**
 * Lists-river-ad location
 *
 * @package pmc-rollingstone-2018
 * @since 2018-08-13
 */

// $ad_location = ( 1 === intval( $current_index + 1 ) ) ? 'lists-top-river-ad' : 'lists-river-ad';
?>
<?php // if ( 0 !== ( intval( $current_index + 1 ) % 2 ) && true !== PMC::is_mobile() ) { 
?>
<article class="c-list__item c-ad c-ad--admz">
	<?php
	if (0 == $current_index) {
		ThemeSetup::render_ads('inbody1', '', '');
	} else {
		ThemeSetup::render_ads('inbodyX', '', '', intval(($current_index + 1) / 2));
	}
	/* if ( 0 !== ( intval( $current_index + 1 ) % 2 ) ) :
			ThemeSetup::render_ads( 'mrec', '', '', intval( ( $current_index + 1 ) / 2 ) );
		else :
			ThemeSetup::render_ads( 'content_1', '', '', intval( ( $current_index + 1 ) / 2 ) );
		endif; */
	?>
</article>
<?php // } 
?>

<?php // if ( true === PMC::is_mobile() ) { 
?>

<?php // } 
?>