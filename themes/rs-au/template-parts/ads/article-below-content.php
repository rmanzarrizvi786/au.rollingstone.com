<?php
/**
 * Article below content ad location
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2019-09-19
 */

// pmc_adm_render_ads( 'article-vivid-ad-unit' );
?>
<div class="c-ad c-ad--admz" style="padding-top: 1.25rem; padding-bottom: 1.25rem; margin-bottom: 0; background-color: #fff;">
<?php
// ThemeSetup::render_ads( 'content_6', '', 300 );
ThemeSetup::render_ads( 'incontent_2', '', 300, get_the_ID() . '999' );
?>
</div>
