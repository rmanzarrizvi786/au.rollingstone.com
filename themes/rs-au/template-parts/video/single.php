<?php
/**
 * Video Single Post Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.04.20
 */

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
?>

<div class="l-category-header">

	<div class="c-category-header">
		<h3 class="c-category-header__heading t-bold t-bold--upper t-bold--condensed" style="margin: 0; padding: 0;">
			<?php esc_html_e( 'Videos', 'pmc-rollingstone' ); ?>
		</h3>

		<a href="<?php echo esc_url( home_url( '/video/' ) ); ?>" class="c-category-header__cta t-semibold">
			<?php esc_html_e( 'View All', 'pmc-rollingstone' ); ?>
		</a>
	</div><!-- .c-category-header -->

	<div class="l-category-header__video">
		<?php get_template_part( 'template-parts/video/picture', 'wide' ); ?>
	</div><!-- /.l-category-header__video -->

</div><!-- /.l-blog__header -->

<div class="l-blog l-blog--with-gap">
	<main class="l-blog__primary">
		<?php get_template_part( 'template-parts/video/content' ); ?>
	</main><!-- .l-blog__primary -->

	<aside class="l-page__secondary l-blog__secondary">
		<?php get_template_part( 'template-parts/ads/article-video' ); ?>
	</aside><!-- .l-blog__secondary -->
</div><!-- .l-blog -->

<?php
	endwhile;
endif;
