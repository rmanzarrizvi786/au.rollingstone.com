<?php
/**
 * Template Name: Charts Overview
 *
 * @package pmc-rollingstone-2018
 * @since 2019-04-22
 */

get_header();
?>
	<div class="l-page__content l-page__charts l-page__charts--overview">
		<div class="l-section">
		<div class="c-content c-content--charts">

			<?php get_template_part( 'plugins/charts/template-parts/header/overview' ); ?>
			<?php get_template_part( 'plugins/charts/template-parts/main/overview' ); ?>
			<?php get_template_part( 'plugins/charts/template-parts/ad/overview' ); ?>

			</div><!-- /.c-content t-copy -->
		</div><!-- /.l-section -->
		<?php get_template_part( 'template-parts/footer/footer' ); ?>
	</div><!-- .l-page__content -->

<?php
get_footer();
