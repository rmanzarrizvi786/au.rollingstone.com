<?php
/**
 * Header Ad.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>

<div class="c-ad c-ad--desktop-header c-ad--970x250">
	<?php
		// pmc_adm_render_ads( 'leaderboard' );
		ThemeSetup::render_ads( 'leaderboard', 'desktop', 970 );
	?>
</div><!-- .c-ad -->

<div class="rs-leaderboard-ad"></div> <!-- marker for pmc-sticky-ad -->
