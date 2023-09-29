<?php
/**
 * A thumbnail with a text overlay.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-03
 */

if ( isset( $image ) && isset( $image_count ) ) :
?>
<div class="c-picture__thumb c-picture__thumb--with-caption">
	<div class="c-picture__thumb-caption">
		<span class="c-picture__thumb-subtitle t-semibold t-semibold--upper t-semibold--loose"><?php esc_html_e( 'View Gallery', 'pmc-rollingstone' ); ?></span>
		<span class="t-bold t-bold--upper"><?php echo intval( $image_count ); ?> <?php esc_html_e( 'Photos', 'pmc-rollingstone' ); ?></span>
	</div>
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
<?php
endif;
