<?php

/**
 * Single Post Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-05
 */

// Temp fix, use global var to track the current article post
$GLOBALS['_current_post'] = get_post();

use Rolling_Stone\Inc\Featured_Article;

$is_country = rollingstone_is_country();
$l_blog_css = ($is_country) ? 'l-blog l-blog--with-flag' : 'l-blog';

get_header();

?>

<div class="l-page__content" data-flyout="is-sidebar-open" data-flyout-trigger="close">
	<?php if (has_term('featured-article', 'global-options')) : ?>
		<?php get_template_part('template-parts/article/featured-article'); ?>
	<?php else : ?>
		<div class="<?php echo esc_attr($l_blog_css); ?>">
			<main class="l-blog__primary">
				<?php if ($is_country) : ?>
					<img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/build/images/_dev/flag-country.png" alt="Country Flag" class="l-blog__flag" />
				<?php endif; ?>

				<div id="single-wrap">
					<?php
					if (have_posts()) :
						while (have_posts()) :
							the_post();
							get_template_part('template-parts/article/single');
						// include( get_template_directory() . '/template-parts/article/single.php' );
						endwhile;
					endif;
					?>
				</div>
			</main><!-- .l-blog__primary -->

			<aside class="l-blog__secondary">
				<div style="margin-bottom: 2rem;"><?php ThemeSetup::render_ads('mrec', '', 300); ?></div>
				<?php //dynamic_sidebar('article_right_sidebar'); ?>
				<?php 
					the_widget( '\Rolling_Stone\Inc\Widgets\Trending', array(
						'count' => 10
					) ); 
				?> 
				<div class="sticky-side-ads-wrap" style="position: sticky; top: 95px; margin-top: 1rem;">
					<?php ThemeSetup::render_ads('vrec', '', 300); ?>
				</div>
			</aside><!-- .l-blog__secondary -->
		</div>

		<?php if (is_active_sidebar('article_right_sidebar')) : ?>
			<div class="l-page__sidebar">

			</div><!-- /.l-page__sidebar -->
		<?php endif; ?>
	<?php endif; ?>

	<?php // get_template_part( 'template-parts/footer/footer' ); 
	?>
</div><!-- /.l-page__content -->

<?php
get_footer();
