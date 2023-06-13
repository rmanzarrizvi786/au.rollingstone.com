<?php

if (post_password_required($post)) {
	if (is_user_logged_in()) {
		do_action( 'wp_footer','wp_admin_bar_render' );
	}

	get_template_part('template-parts/protected/header');
		echo get_the_password_form();
	get_template_part('template-parts/protected/footer');

	wp_die();
}

/**
 * Page template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-28
 */

get_header();
?>
	<div class="l-page__content">

		<div class="l-section l-section--standard-template l-section--no-separator">
			<div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						?>

						<h1><?php the_title(); ?></h1>
						<?php the_content(); ?>

						<?php
					endwhile;
				endif;
				?>
			</div><!-- /.c-content t-copy -->
		</div><!-- /.l-section -->

		<?php get_template_part( 'template-parts/footer/footer' ); ?>
	</div><!-- .l-page__content -->

<?php
get_footer();
