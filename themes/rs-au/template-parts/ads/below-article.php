<?php

/**
 * Below Article Ad.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-03-16
 */
?>
<?php
if (!in_array(get_post_type(get_the_ID()), ['post']))
	return;
?>
<div class="c-ad c-ad--admz" style="padding-top: 1.25rem; padding-bottom: 1.25rem; margin-bottom: 0; background-color: #fff;">
	<?php
	// pmc_adm_render_ads( 'below-article' );
	ThemeSetup::render_ads('incontent_2', '', 300, get_the_ID() . '999');
	?>
</div>