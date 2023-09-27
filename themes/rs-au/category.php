<?php

/**
 * Category Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

get_header();

$category = get_query_var('cat');
$category = get_category($category);
if (!empty($category->parent)) {
?>
	<div class="l-page__content" data-flyout="is-sidebar-open" data-flyout-trigger="close">

		<?php
		if (rollingstone_is_country()) {
			get_template_part('template-parts/archive/country');
		} else {
			get_template_part('template-parts/archive/archive');
		}
		?>

		<?php get_template_part('template-parts/footer/footer'); ?>

	</div><!-- .l-page__content -->
<?php
} else {
	get_template_part('template-parts/category/header');
?>
	<div class="l-page__content">

		<div class="l-blog">
			<main class="l-blog__primary">

				<div class="l-blog__item">
					<?php get_template_part('template-parts/category/featured'); ?>
				</div><!-- .l-blog__item -->

				<div class="l-blog__item l-blog__item--spacer-xl">
					<?php get_template_part('template-parts/category/section-header'); ?>
				</div><!-- .l-blog__item -->

				<div class="l-blog__item l-blog__item--spacer-s">
					<?php get_template_part('template-parts/archive/river'); ?>
				</div><!-- .l-blog__item -->

				<div class="l-blog__item l-blog__item--spacer-l">
					<?php get_template_part('template-parts/archive/pagination'); ?>
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

		</div><!-- .l-blog -->

		<?php get_template_part('template-parts/footer/footer'); ?>
	</div><!-- .l-page__content -->
<?php } ?>

<?php
get_footer();
