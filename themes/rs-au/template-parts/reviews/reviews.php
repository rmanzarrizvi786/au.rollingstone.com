<?php
/**
 * Reviews section.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-03-16
 */

$reviews_title = ( isset( $reviews_title ) ) ? $reviews_title : '';
$reviews       = \Rolling_Stone\Inc\Carousels::get_carousel_posts( $reviews_carousel, 4, false, true );

?>

<div class="c-reviews">
	<h3 class="c-reviews__label">
		<span class="c-reviews__label-head t-bold"><?php echo esc_html( $reviews_title ); ?></span>
		<span class="c-reviews__label-tail t-bold"><?php esc_html_e( 'Reviews', 'pmc-rollingstone' ); ?></span>
	</h3><!-- /.c-reviews__label -->

	<ul class="c-reviews__list">
		<?php foreach ( $reviews as $review ) : ?>
			<li class="c-reviews__item">
				<?php
				\PMC::render_template(
					CHILD_THEME_PATH . '/template-parts/reviews/review-card.php', [
						'review' => $review,
					], true
				);
				?>
			</li><!-- /.c-reviews__item -->
		<?php endforeach; ?>
	</ul><!-- /.c-reviews__list -->
</div><!-- .c-reviews -->
