<?php
/**
 * Photo Gallery Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-20
 */

use \Rolling_Stone\Inc\RS_Query;

$section_header = ! empty( $data['title'] ) ? $data['title'] : __( 'Photo Galleries', 'pmc-rollingstone' );
$carousel       = ! empty( $data['carousel'] ) ? $data['carousel'] : 'photo-gallery';
$count          = ! empty( $data['count'] ) ? $data['count'] : 4;
$galleries      = \Rolling_Stone\Inc\Carousels::get_carousel_posts( $carousel, $count );
$rs_query       = RS_Query::get_instance();

// If the carousel is empty, let's snag some galleries.
if ( empty( $galleries ) || ! is_array( $galleries ) || count( $galleries ) < $count ) {

	$query_args = array(
		'posts_per_page' => $count - count( $galleries ),
		'post_type'      => 'pmc-gallery',
	);

	if ( ! empty( $galleries ) ) {
		$query_args['post__not_in'] = wp_list_pluck( $galleries, 'ID' );
	}

	$backfill = $rs_query->get_posts( $query_args );

	if ( ! empty( $backfill ) ) {
		$galleries = array_merge( $galleries, $backfill );
	} else {
		$galleries = $backfill->posts;
	}
}

if ( empty( $galleries ) ) {
	return;
}

?>

<div class="l-section" data-section="<?php echo esc_attr( $section_header ); ?>">

	<script>
		PMC_RS_setHomeAppearance( <?php echo wp_json_encode( $section_header ); ?> );
	</script>

	<div class="l-section__header">
		<?php
		\PMC::render_template(
			CHILD_THEME_PATH . '/template-parts/module/section-header-hide.php', [
				'title' => $section_header,
				'url'   => home_url( '/pictures/' ),
			], true
		);
		?>
	</div><!-- /.l-section__header -->

	<div class="l-section__content">
		<div class="c-galleries">
			<div class="c-galleries__slider c-slider" data-slider>
				<a href="" class="c-slider__nav c-slider__nav--left" data-slider-nav="prev">
					<svg class="c-slider__icon"><use xlink:href="#svg-icon-chevron"></use></svg>
				</a>
				<a href="" class="c-slider__nav c-slider__nav--right" data-slider-nav="next">
					<svg class="c-slider__icon"><use xlink:href="#svg-icon-chevron"></use></svg>
				</a>

				<div class="c-slider__track" data-slider-track>

					<?php
					foreach ( $galleries as $gallery ) :
						setup_postdata( $GLOBALS['post'] =& $gallery ); // phpcs:ignore
						?>

						<div class="c-galleries__item c-slider__item" data-slider-item>
							<?php
							\PMC::render_template(
								CHILD_THEME_PATH . '/template-parts/gallery/galleries-item.php', [
									'item' => $gallery,
								], true
							);
							?>
						</div><!-- /.c-galleries__item c-slider__item -->

					<?php endforeach; ?>

				</div><!-- /.c-slider__track -->
			</div><!-- /.c-slider -->
		</div><!-- /.c-galleries -->
	</div><!-- /.l-section__content -->
</div><!-- /.l-section -->

<?php
wp_reset_postdata();
