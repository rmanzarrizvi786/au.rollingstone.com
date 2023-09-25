<?php

/**
 * Card - Featured Image.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-21
 */

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

<figure class="c-picture">
	<div class="c-picture__frame">

		<div class="c-crop c-crop--ratio-3x2">
			<?php
			if (isset($featured_image_override)) {
			?>
				<img width="900" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo $featured_image_override; ?>" class="c-crop__img wp-post-image" alt="" />
			<?php
			} else {
				$img_sizes  = '(max-width: 450px) 500px, (max-width: 600px) 650px, (max-width: 900px) 950px, (max-width: 1200px) 1250px';
				$img_srcset = [450, 600, 900, 1200];

				if (\PMC::is_mobile()) {
					$img_sizes  = '(max-width: 450px) 500px, (max-width: 600px) 650px';
					$img_srcset = [450, 600];
				}

				rollingstone_the_post_thumbnail(
					'ratio-3x2',
					[
						'class'  => 'c-crop__img',
						'sizes'  => $img_sizes,
						'srcset' => $img_srcset,
					]
				);
			}
			?>
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