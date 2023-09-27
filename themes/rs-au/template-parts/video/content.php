<?php
/**
 * Video Content Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-20
 */

$category = rollingstone_get_the_subcategory();

$term_link = get_term_link( $category );

if ( is_wp_error( $term_link ) ) {
	return;
}

?>

<article class="c-category-article c-category-article--category">

	<?php if ( ! is_wp_error( $category ) && ! empty( $category->name ) ) : ?>
	<div class="c-category-article__tagline">
		<a href="<?php echo esc_url( $term_link ); ?>" class="c-category-article__tag t-semibold t-semibold--upper t-semibold--loose">
			<?php echo esc_html( $category->name ); ?>
		</a>
		<time class="c-category-article__time t-semibold" datetime="<?php echo esc_attr( get_post_time( 'c', true, get_the_ID() ) ); ?>">
			<?php echo esc_html( get_the_time( 'F j, Y g:iA', get_the_ID() ) ); ?> ET
		</time>
	</div><!-- /.c-category-article__tagline -->
	<?php endif; ?>

	<h1 class="c-category-article__heading t-bold">
		<?php the_title(); ?>
	</h1><!-- .c-category-article__heading -->

	<p class="c-category-article__lead t-copy">
		<?php the_content(); // echo esc_html( wp_filter_nohtml_kses( get_the_content() ) ); ?>
	</p><!-- /.c-category-article__lead t-copy -->

	<footer class="c-category-article__footer">
		<div class="c-category-article__social">
			<?php // get_template_part( 'template-parts/article/card-social-share', 'wide' ); ?>
		</div><!-- /.c-category-article__social -->
	</footer><!-- /.c-category-article__footer -->

</article><!-- .c-category-article -->
