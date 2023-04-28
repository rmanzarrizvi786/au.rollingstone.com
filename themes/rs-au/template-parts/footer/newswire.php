<?php
/**
 * Newswire.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-11-04
 */

$feeds = apply_filters( 'pmc_footer_list_of_feeds', array() );

// Bail if there are no feed items.
if ( empty( $feeds ) || ! is_array( $feeds ) ) {
	return;
}

?>

<div class="l-section l-section--no-separator">

	<div class="l-section__header">
		<div class="c-section-header">
			<h3 class="c-section-header__heading t-bold t-bold--condensed">
				<?php esc_html_e( 'Newswire', 'pmc-rollingstone' ); ?>
			</h3>
			<div class="c-section-header__powerby">
				<div class="c-section-header__powerby--text">
				<?php esc_html_e( 'Powered by', 'pmc-rollingstone' ); ?>
				</div>
				<svg class="c-section-header__powerby--logo"><use xlink:href="#svg-pmc-logo-black"></use></svg>
			</div>
		</div><!-- .c-section-header -->
	</div><!-- /.l-section__header -->

	<ul class="c-newswire">
		<?php foreach ( $feeds as $feed ) : ?>
			<?php if ( is_array( $feed ) && ! empty( $feed['feed_source_url'] ) ) : ?>
				<?php pmc_get_footer_feed( $feed['feed_source_url'], $feed['feed_title'] ); // WPCS: XSS ok. ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul><!-- .c-newswire -->

</div><!-- /.l-section -->
