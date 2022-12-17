<?php

/**
 * Picture video template with no caption.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-03
 */

if (isset($video)) :

	// Set the credit and the caption.
	$image_credit  = pmc_get_photo_credit(get_post_thumbnail_id());
	$image_caption = get_post_field('post_excerpt', get_post_thumbnail_id());

	if (!has_post_thumbnail(get_the_ID())) {
		if (get_post_meta(get_the_ID(), 'thumbnail_ext_url', TRUE)) {
			$featured_image_override = get_post_meta(get_the_ID(), 'thumbnail_ext_url', TRUE);
			$image_credit  = get_post_meta(get_the_ID(), 'thumbnail_ext_image_credit', TRUE);
			$image_caption = get_post_meta(get_the_ID(), 'thumbnail_ext_image_caption', TRUE);
		}
	}
?>

	<figure class="c-picture c-picture--video c-picture--no-caption" style="text-align: initial;">
		<div class="c-picture__frame">

			<div class="c-crop c-crop--video c-crop--ratio-video" data-video-crop>
				<div>
					<?php // this code is escaped from calling function, @TODO: refactor code [PMCP-683] 
					?>
					<?php echo $video; // WPCS: xss okay. 
					?>
				</div>
			</div><!-- .c-crop -->

		</div>

		<?php if (!empty($image_caption) || !empty($image_credit)) : ?>
			<div class="c-picture__caption">

				<?php if (!empty($image_caption)) : ?>
					<p class="c-picture__title t-semibold">
						<?php echo esc_html($image_caption); ?>
					</p>
				<?php endif; ?>

				<?php if (!empty($image_credit)) : ?>
					<p class="c-picture__source t-semibold">
						<?php echo esc_html($image_credit); ?>
					</p>
				<?php endif; ?>

			</div><!-- .c-picture__caption -->
		<?php endif; ?>

	</figure><!-- .c-picture -->
<?php
endif;
