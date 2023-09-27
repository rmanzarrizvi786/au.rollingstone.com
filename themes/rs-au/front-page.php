<?php
/**
 * Template name: homepage
 *
 * @package pmc-rollingstone-2018
 * @since 2019-11-23
 */

get_header();
?>
	<div class="l-page__content">
		<div class="l-home-top">
			<?php get_template_part( 'template-parts/module/top-stories' ); ?>
			<!-- <div class="l-home-top__list"> -->
				<?php //get_template_part( 'template-parts/module/latest-news' ); ?>
			<!-- </div><!-- /.l-home-top__list -->
			<aside class="l-home-top__sidebar">
				<?php //if ( is_active_sidebar( 'home_right_1' ) ) : ?>
					<?php //dynamic_sidebar( 'home_right_1' ); ?>
				<?php //endif; ?>
				<?php the_widget( '\Rolling_Stone\Inc\Widgets\Trending', array(
					'style' => 'hero',
					'count' => 4
				) ); ?> 
				<div id="sticky-rail-ad" style="z-index: 1000;">
					<div class="admz pmc-adm-goog-pub-div c-ad c-ad--300x250 c-ad--boxed" id="adm-right-rail-1">
						<div id='adm_rail1' style='width: 300px; margin: auto;'>
							<div data-fuse="22378668214"></div>
						</div>
					</div>
				</div>
			</aside><!-- /.l-home-top__sidebar -->
		</div><!-- .l-home-top -->

		<?php if ( is_active_sidebar( 'homepage-bottom' ) ) : ?>
			<?php dynamic_sidebar( 'homepage-bottom' ); ?>
		<?php endif; ?>


		<?php get_template_part( 'template-parts/footer/footer' ); ?>
	</div><!-- .l-page__content -->
<?php
get_footer();
