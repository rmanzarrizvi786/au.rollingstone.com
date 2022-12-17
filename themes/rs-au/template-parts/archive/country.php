<?php
/**
 * Country Archive Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-28
 */

?>

<div class="c-branding-banner">
	<h1 class="screen-reader-text">
		<?php esc_html_e( 'Country', 'pmc-rollingstone' ); ?>
	</h1>
</div><!-- .l-archive-top -->

<div class="l-blog">
	<main class="l-blog__primary">

		<div class="l-blog__item">

			<?php get_template_part( 'template-parts/category/featured' ); ?>

		</div><!-- .l-blog__item -->

		<div class="l-blog__item l-blog__item--spacer-xl">

			<div class="c-section-header c-section-header--country">
				<h3 class="c-section-header__heading t-country">
					<?php esc_html_e( 'Latest News', 'pmc-rollingstone' ); ?>
				</h3>
			</div><!-- .c-section-header -->

		</div><!-- .l-blog__item -->

		<div class="l-blog__item l-blog__item--spacer-s">
			<?php get_template_part( 'template-parts/archive/river' ); ?>
		</div><!-- .l-blog__item -->

		<div class="l-blog__item l-blog__item--spacer-l">
			<?php get_template_part( 'template-parts/archive/pagination' ); ?>
		</div><!-- .l-blog__item -->

	</main><!-- .l-blog__primary -->

	<?php if ( is_active_sidebar( 'archive_right_1' ) ) : ?>
	<aside class="l-blog__secondary">

		<?php dynamic_sidebar( 'archive_right_1' ); ?>

	</aside><!-- .l-blog__secondary -->
	<?php endif; ?>

</div><!-- .l-blog -->
