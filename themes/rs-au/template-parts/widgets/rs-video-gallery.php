<?php
/**
 * Rolling Stone Video Gallery Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-21
 */

$title    = ! empty( $data['title'] ) ? $data['title'] : __( 'Video', 'pmc-rollingstone' );
$carousel = ! empty( $data['carousel'] ) ? $data['carousel'] : 'video-gallery';

?>

<div class="l-section l-section--dark">

	<div class="l-section__header">
		<div class="c-section-header c-section-header--dark">
			<h3 class="c-section-header__heading t-bold t-bold--upper t-bold--condensed">
				<?php echo esc_html( $title ); ?>
			</h3>

			<a href="<?php echo esc_url( home_url( '/video/' ) ); ?>" class="c-section-header__cta t-semibold t-semibold--upper t-semibold--loose">
				<?php esc_html_e( 'View All', 'pmc-rollingstone' ); ?>
			</a>
		</div><!-- .c-section-header -->
	</div><!-- /.l-section__header -->

	<div class="l-section__content">
		<div class="c-video-gallery" data-video-gallery>

			<?php
			\PMC::render_template(
				CHILD_THEME_PATH . '/template-parts/video/card-gallery.php', [
					'carousel'    => $carousel,
					'is_carousel' => true,
				], true
			);
			?>

		</div><!-- /.c-video-gallery -->
	</div><!-- /.l-section__content -->

</div><!-- .c-coverage -->
