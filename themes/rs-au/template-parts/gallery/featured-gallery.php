<?php
/**
 * Featured gallery template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-03
 */

use Rolling_Stone\Inc\Media;

if ( ! empty( $gallery ) && ! empty( $gallery['images'] ) && is_array( $gallery['images'] ) && count( $gallery['images'] ) >= 5 ) :
?>
<a href="<?php echo esc_url( $gallery['url'] ); ?>" class="c-picture c-picture--gallery c-picture--no-caption">
	<div class="c-picture__frame">
		<div class="c-crop c-crop--ratio-4x3">
			<?php rollingstone_attachment_image( $gallery['images'][0], 'ratio-4x3', false, [ 'class' => 'c-crop__img' ] ); ?>
		</div><!-- .c-crop -->
	</div>

	<div class="c-picture__thumbs">
		<?php foreach ( array_slice( $gallery['images'], 1, 3 ) as $image ) : ?>
			<div class="c-picture__thumb">
				<?php
					\PMC::render_template(
						CHILD_THEME_PATH . '/template-parts/gallery/featured-gallery-image.php',
						[
							'image' => $image,
						],
						true
					);
				?>
			</div><!-- /.c-picture__thumb -->
		<?php endforeach; ?>
		<?php
			\PMC::render_template(
				CHILD_THEME_PATH . '/template-parts/gallery/thumb-with-caption.php',
				[
					'image'       => $gallery['images'][4],
					'image_count' => count( $gallery['images'] ),
				],
				true
			);
		?>

	</div><!-- /.c-picture__thumbs -->

</a><!-- .c-picture -->
<?php
elseif ( has_post_thumbnail( get_the_ID() ) ) :
	// Fall back to featured image.
	get_template_part( 'template-parts/article/featured-image' );
endif;
