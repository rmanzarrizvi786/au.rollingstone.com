<?php
/**
 * Card - Gift
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-31
 */

if ( ! isset( $issue_id ) ) {
	return;
}

?>

<div class="c-gift-card">
	<div class="c-gift-card__cover">
		<a href="<?php echo esc_url( trailingslashit( home_url( 'subscribe-more' ) ) ); ?>">
			<?php rollingstone_the_issue_cover( 300, [ 'class' => 'c-gift-card__cover-image' ], $issue_id ); ?>
		</a>
	</div><!-- .c-gift-card__cover -->

	<ul class="c-gift-card__list">
		<li class="c-gift-card__item c-gift-card__item--heading is-active" data-ripple="inverted">
			<span class="t-bold"><?php esc_html_e( 'Get The Magazine', 'pmc-rollingstone' ); ?></span>
		</li><!-- .c-gift-card__item -->

		<li class="c-gift-card__item" data-ripple="inverted">
			<a href="<?php echo esc_url( trailingslashit( home_url( 'subscribe-more' ) ) ); ?>" class="c-gift-card__link t-semibold t-semibold--upper">
				<?php esc_html_e( 'Subscribe Now', 'pmc-rollingstone' ); ?>
			</a>
		</li><!-- .c-gift-card__item -->

		<li class="c-gift-card__item" data-ripple="inverted">
			<a href="<?php echo esc_url( trailingslashit( home_url( 'subscribe-more' ) ) ); ?>" class="c-gift-card__link t-semibold t-semibold--upper">
				<?php esc_html_e( 'Give a Gift', 'pmc-rollingstone' ); ?>
			</a>
		</li><!-- .c-gift-card__item -->

	</ul><!-- .c-gift-card__list -->

</div><!-- .c-gift-card -->
