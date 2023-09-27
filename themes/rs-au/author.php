<?php
/**
 * Author Archive Template.
 *
 * @package pmc-rollingstone-2018
 */

get_header();

?>

<div class="l-page__content" data-flyout="is-sidebar-open" data-flyout-trigger="close">

	<?php
	PMC::render_template( sprintf( '%s/template-parts/archive/author.php', untrailingslashit( CHILD_THEME_PATH ) ), [], true );
	PMC::render_template( sprintf( '%s/template-parts/footer/footer.php', untrailingslashit( CHILD_THEME_PATH ) ), [], true );
	?>

</div><!-- .l-page__content -->

<?php
get_footer();
