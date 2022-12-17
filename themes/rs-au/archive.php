<?php
/**
 * Archive Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-28
 */

get_header();

?>

<div class="l-page__content" data-flyout="is-sidebar-open" data-flyout-trigger="close">

	<?php
	if ( rollingstone_is_country() ) {
		get_template_part( 'template-parts/archive/country' );
	} else {
		get_template_part( 'template-parts/archive/archive' );
	}
	?>

	<?php get_template_part( 'template-parts/footer/footer' ); ?>

</div><!-- .l-page__content -->

<?php
get_footer();
