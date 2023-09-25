<?php

/**
 * Template Name: NZ
 */

get_header();

$paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
?>
<div class="l-page__content">
	<?php if (1 === $paged) : ?>
		<div class="l-home-top">
			<?php get_template_part('template-parts/nz/top-stories'); ?>
			<aside class="l-home-top__sidebar">
				<?php if (is_active_sidebar('home_right_1')) : ?>
					<?php dynamic_sidebar('home_right_1'); ?>
				<?php endif; ?>
			</aside><!-- /.l-home-top__sidebar -->
		</div><!-- .l-home-top -->
	<?php endif; ?>

	<div class="l-blog">
		<main class="l-blog__primary">
			<div class="l-blog__item l-blog__item--spacer-s">
				<?php get_template_part('template-parts/nz/river', null, ['paged' => $paged]); ?>
			</div><!-- .l-blog__item -->
		</main><!-- .l-blog__primary -->

		<aside class="l-blog__secondary">
			<div style="margin-bottom: 2rem;"><?php ThemeSetup::render_ads('mrec', '', 300); ?></div>
			<?php if (is_active_sidebar('category_right_1')) : ?>
				<?php dynamic_sidebar('category_right_1'); ?>
			<?php endif; ?>
			<div class="sticky-side-ads-wrap" style="position: sticky; top: 95px; margin-top: 1rem;">
				<?php ThemeSetup::render_ads('vrec', '', 300); ?>
			</div>
		</aside><!-- .l-blog__secondary -->
	</div>

	<?php // if (is_active_sidebar('homepage-bottom')) : 
	?>
	<?php // dynamic_sidebar('homepage-bottom'); 
	?>
	<?php // endif; 
	?>


	<?php get_template_part('template-parts/footer/footer'); ?>
</div><!-- .l-page__content -->
<?php
get_footer();
