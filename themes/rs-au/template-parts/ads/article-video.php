<?php
/**
 * Article Video Ad.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.04.20
 */

?>

<div class="c-ad c-ad--300x250">
	<?php
	//pmc_adm_render_ads( 'right-rail-1' );
	ThemeSetup::render_ads( 'mrec', '', 300 );
	?>
</div>
<div class="c-ad c-ad--300x600">
	<?php ThemeSetup::render_ads( 'half_page', '', 300 ); ?>
</div><!-- .c-ad -->
