<?php
/**
 * Card - rating.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-5-12
 */

if ( isset( $rating ) && isset( $out_of ) ) :
?>

<div class="l-article-header__row">
	<div class="c-rating c-rating--<?php echo esc_attr( $out_of ); ?>-stars">

		<?php for ( $i = 0; $i < $out_of; $i++ ) : ?>
			<?php if ( $rating === $i + .5 ) : ?>
				<svg class="c-rating__star c-rating__star--active">
					<use xlink:href="#svg-icon-star"></use>
					<use xlink:href="#svg-icon-star--half"></use>
				</svg>
			<?php elseif ( $i < $rating ) : ?>
				<svg class="c-rating__star c-rating__star--active">
					<use xlink:href="#svg-icon-star"></use>
				</svg>
			<?php else : ?>
				<svg class="c-rating__star">
					<use xlink:href="#svg-icon-star"></use>
				</svg>
			<?php endif; ?>
		<?php endfor; ?>

	</div><!-- /.c-star-rating -->
</div>

<?php
endif;
