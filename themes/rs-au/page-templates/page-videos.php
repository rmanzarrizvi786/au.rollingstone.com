<?php
/**
 * Template Name: Videos
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-23
 */

get_header();

?>

<div class="l-page__content">
	<?php get_template_part( 'template-parts/video/header' ); ?>

	<?php dynamic_sidebar( 'video_landing' ); ?>

	<?php get_template_part( 'template-parts/footer/footer' ); ?>
</div><!-- .l-page__content -->

<?php
get_footer();
