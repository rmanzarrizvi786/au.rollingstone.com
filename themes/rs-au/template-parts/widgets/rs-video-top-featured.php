<?php
/**
 * Rolling Stone Video Top Featured Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-03
 */

$carousel = ! empty( $data['carousel'] ) ? $data['carousel'] : 'video-gallery';

?>

<div class="l-section l-section--no-separator">
	<div class="c-video-gallery c-video-gallery--article" data-video-gallery>

		<?php
		\PMC::render_template(
			CHILD_THEME_PATH . '/template-parts/video/card-gallery.php', [
				'carousel'    => $carousel,
				'is_carousel' => true,
			], true
		);
		?>

	</div>
</div><!-- /.l-section -->
