<?php
/**
 * Review Card.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-03-16
 */

if ( empty( $review ) ) {
	return;
}

global $post;

$post = $review;

setup_postdata( $post );

$thumbnail_ratio = has_category( 'music-album-reviews' ) ? 'ratio-1x1' : 'ratio-2x3';

?>

<article class="c-reviews-card">
	<a href="<?php the_permalink(); ?>" class="c-reviews-card__wrap">
		<figure class="c-reviews-card__image">

			<div class="c-reviews-card__crop c-crop c-crop--<?php echo esc_attr( $thumbnail_ratio ); ?>">
				<?php rollingstone_review_image(); ?>
			</div><!-- .c-crop -->

		</figure><!-- .c-reviews-card__image -->

		<header class="c-reviews-card__header">

			<div class="c-reviews-card__rating">
				<?php rollingstone_review_stars(); ?>
			</div>

			<h4 class="c-reviews-card__headline">
				<span class="t-copy">
					<?php if ( has_category( 'music-album-reviews' ) ) : ?>
						<?php rollingstone_review_artist(); ?>
					<?php else : ?>
						<?php rollingstone_review_title(); ?>
					<?php endif; ?>
				</span>
			</h4>

			<?php if ( has_category( 'music-album-reviews' ) ) : ?>
			<h5 class="c-reviews-card__subheadline">
				<span class="t-semibold t-semibold--upper">
					<?php rollingstone_review_title(); ?>
				</span>
			</h5>
			<?php endif; ?>

			<p class="c-reviews-card__lead">
				<span class="t-copy"><?php rollingstone_the_excerpt(); ?></span>
			</p>

			<div class="c-reviews-card__cta">
				<span class="t-semibold t-semibold--upper"><?php esc_html_e( 'Read More', 'pmc-rollingstone' ); ?></span>
			</div>

		</header><!-- .c-reviews-card__header -->
	</a><!-- .c-reviews-card__wrap -->
</article><!-- .c-reviews-card -->

<?php
wp_reset_postdata();
