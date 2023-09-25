<?php
/**
 * Template Name: Custom Search Results Page
 *
 * @package pmc-rollingstone-2018
 * @since 2018.05.14
 */

get_header();
?>

<div class="l-page__content">
<div class="search-results">
	<div class="swiftype">
		<div class="search_form">
			<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex;">
				<input type="text" autocomplete="off" class="" placeholder="Search &hellip;" value="<?php echo get_search_query(); ?>" name="s">
				<input type="submit" class="" value="Search">
	    </form>
		</div>
	</div>
</div>

<div class="l-blog">
	<main class="l-blog__primary">

		<div class="l-blog__item l-blog__item--spacer-xl">
			<div class="c-section-header">
				&nbsp;
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

</div><!-- /.l-page__content -->
<?php
get_footer();
