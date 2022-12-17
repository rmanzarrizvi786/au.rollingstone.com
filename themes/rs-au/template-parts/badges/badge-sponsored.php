<?php
/**
 * Template for Sponsored Content badge
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2019-07-22
 */

if ( empty( $current_post ) || ! is_a( $current_post, \WP_Post::class ) ) {
	return;
}

?>

<div class="c-badge c-badge--sponsored">
	<?php
	do_action( 'pmc_frontend_components_badges_sponsored_content', (int) $current_post->ID );
	?>
</div><!-- .c-badge--sponsored -->
