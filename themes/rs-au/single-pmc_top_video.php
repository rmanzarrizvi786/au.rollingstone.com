<?php

if (post_password_required($post)) {
	if (is_user_logged_in()) {
		do_action( 'wp_footer','wp_admin_bar_render' );
	}

	get_template_part('template-parts/protected/header');
		echo get_the_password_form();
	get_template_part('template-parts/protected/footer');

	exit;
}

/**
 * Single Video Post Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.04.20
 */

get_header();

?>
<div class="l-page__content" data-flyout="is-sidebar-open" data-flyout-trigger="close">

	<?php get_template_part( 'template-parts/video/single' ); ?>
	<?php get_template_part( 'template-parts/video/related' ); ?>
	<?php get_template_part( 'template-parts/footer/footer' ); ?>

</div><!-- /.l-page__content -->

<?php if ( is_active_sidebar( 'article_right_sidebar' ) ) : ?>
<div class="l-page__sidebar">

</div><!-- /.l-page__sidebar -->
<?php endif; ?>

<?php
get_footer();
