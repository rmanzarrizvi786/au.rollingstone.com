<?php
/**
 * Picture Video Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-20
 */

?>

<div class="c-picture__frame">

	<div class="c-crop c-crop--video c-crop--ratio-video" data-video-crop2>
		<div hidden>
			<?php rollingstone_get_video_source(); ?>
		</div>

		<?php get_template_part( 'template-parts/badges/video-picture' ); ?>

		<?php
		rollingstone_the_post_thumbnail(
			'ratio-video', [
				'class'  => 'c-crop__img',
				'srcset' => [ 480, 970, 1260 ],
				'sizes'  => '100%',
			]
		);
			?>
	</div><!-- .c-crop -->

</div>
