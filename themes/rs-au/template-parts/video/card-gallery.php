<?php
/**
 * Rolling Stone Video Gallery Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-24
 */

$carousel    = isset( $carousel ) ? $carousel : 'video-gallery';
$videos      = \Rolling_Stone\Inc\Carousels::get_carousel_posts( $carousel, 5 );
$is_carousel = ( ! empty( $is_carousel ) ) ? $is_carousel : false;

if ( empty( $videos ) ) {
	$video_query = new \WP_Query(
		array(
			'posts_per_page' => 5,
			'post_type'      => 'pmc_top_video',
		)
	);

	$videos = $video_query->posts;
}

$slider_class     = '';
$card_video_thumb = '';
$card_class       = '';
if ( ! is_home() && ! is_front_page() ) {
	$card_video_thumb = 'c-card--video-thumb--light';
	$card_class       = 'c-card--video--image-first c-card--video--light';
	$slider_class     = 'c-slider--light';
}

if ( isset( $videos[0] ) ) :
	$featured                        = $videos[0];
	setup_postdata( $GLOBALS['post'] =& $featured ); // phpcs:ignore
	?>

	<div class="c-video-gallery__main">
		<?php
		\PMC::render_template(
			CHILD_THEME_PATH . '/template-parts/video/card-video.php', [
				'class'         => $card_class,
				'is_video_home' => ( is_home() || is_front_page() ),
				'is_carousel'   => $is_carousel,
			], true
		);
		?>
	</div><!-- /.c-video-gallery__main -->

<?php
endif;

// if ( ! is_archive() && ! empty( $videos ) && is_array( $videos ) ) :
if ( ! is_tax ( 'vcategory' ) && ! is_post_type_archive( 'pmc_top_video' ) && ! empty( $videos ) && is_array( $videos ) ) :
?>

<div class="c-video-gallery__slider c-slider <?php echo esc_attr( $slider_class ); ?>" data-slider data-slider--centered>

	<?php if ( ! is_home() && ! is_front_page() ) : ?>
	<h3 class="c-video-gallery__slider-heading t-bold">
		<?php esc_html_e( 'The Latest Videos', 'pmc-rollingstone' ); ?>
	</h3>
	<?php endif; ?>

	<a href="" class="c-slider__nav c-slider__nav--left" data-slider-nav="prev">
		<svg class="c-slider__icon"><use xlink:href="#svg-icon-chevron"></use></svg>
	</a>
	<a href="" class="c-slider__nav c-slider__nav--right" data-slider-nav="next">
		<svg class="c-slider__icon"><use xlink:href="#svg-icon-chevron"></use></svg>
	</a>

	<div class="c-slider__track" data-slider-track>
		<?php
		// Reorder posts to show featured video on the third.
		if ( count( $videos ) > 3 ) {
			// $videos[0] = $videos[2];
			// $videos[2] = $featured;
		}

		foreach ( $videos as $video ) :
			// Exclude the featured video from the list
			if ( $video->ID == $featured->ID ) :
				// continue;
			endif;
			setup_postdata( $GLOBALS['post'] =& $video ); // phpcs:ignore
		?>

		<div class="c-video-gallery__item c-slider__item" data-slider-item>
			<?php
			\PMC::render_template(
				CHILD_THEME_PATH . '/template-parts/video/card-video-thumb.php', [
					'class'    => $card_video_thumb,
					'featured' => ( $featured->ID === $video->ID ),
				], true
			);
			?>
		</div><!-- /.c-video-gallery__item c-slider__item -->

		<?php endforeach; ?>
	</div><!-- /.c-slider__track -->
</div><!-- /.c-video-gallery__slider c-slider -->

<?php
endif;

wp_reset_postdata();
