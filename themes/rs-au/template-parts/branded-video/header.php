<?php
/**
 * Video Header.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-23
 */

if ( empty( $post_id ) || empty( $branded_page ) || empty( $branded_page_settings ) ) {
	return;
}
?>

<div class="l-section-top-branded-videos">
	<?php $branded_page->render_banner( $post_id ); ?>
</div><!-- .l-section-top -->
