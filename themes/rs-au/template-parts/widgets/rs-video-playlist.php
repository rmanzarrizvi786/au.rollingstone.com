<?php
/**
 * Rolling Stone Video Playlist Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-03
 */

$title     = ! empty( $data['title'] ) ? $data['title'] : __( 'More Playlists', 'pmc-rollingstone' );
$carousel  = ! empty( $data['carousel'] ) ? $data['carousel'] : 'none';
$more_list = \Rolling_Stone\Inc\Carousels::get_carousel_posts( $carousel, 3 );

if ( empty( $more_list ) ) {
	return;
}

?>

<div class="l-section l-section--no-separator">

	<div class="l-section__header">
		<div class="c-section-header">
			<h3 class="c-section-header__heading t-bold t-bold--upper t-bold--condensed">
				<?php echo esc_html( $title ); ?>
			</h3>
		</div><!-- .c-section-header -->
	</div><!-- /.l-section__header -->

	<div class="l-section__content">
		<div class="c-video-grid">
			<div class="c-video-grid__rail">

				<?php
				foreach ( $more_list as $playlist ) :
					setup_postdata( $GLOBALS['post'] =& $playlist ); // phpcs:ignore

					// Get url.
					$data = get_post_meta( $playlist->curation_id, '_pmc_master_article_id', true );
					$term = json_decode( $data );

					if ( empty( $term->id ) ) {
						continue;
					}

					$url = get_permalink( $term->id );

					if ( is_wp_error( $url ) ) {
						continue;
					}
				?>

					<a href="<?php echo esc_url( $url ); ?>" class="c-video-grid__item c-video-grid__item--playlist">
						<div class="c-crop c-crop--ratio-7x4">

							<?php
							rollingstone_the_post_thumbnail(
								'ratio-7x4', [
									'class'  => 'c-crop__img',
									'srcset' => [ 250, 406 ],
									'sizes'  => '(max-width: 767px) 250px, 406px',
								]
							);
								?>

						</div><!-- .c-crop -->
					</a><!-- /.c-video-grid__item c-video-grid__item--playlist -->

				<?php endforeach; ?>

			</div><!-- /.c-video-grid__rail -->
		</div><!-- /.c-video-grid -->
	</div><!-- /.l-section__content -->
</div><!-- /.l-section -->

<?php
wp_reset_postdata();
