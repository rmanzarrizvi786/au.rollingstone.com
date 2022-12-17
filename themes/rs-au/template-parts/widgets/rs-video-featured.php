<?php
/**
 * Rolling Stone Video Featured Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-01
 */

$tax_name = ! empty( $data['category'] ) ? $data['category'] : '';

if ( empty( $tax_name ) ) {
	return;
}

$taxonomy   = 'vcategory';
$category   = get_term_by( 'slug', $tax_name, $taxonomy );

$term_link = get_term_link( $category->term_id );

if ( is_wp_error( $term_link ) ) {
	return;
}

$title      = ! empty( $data['title'] ) ? $data['title'] : $category->name;
$video_args = array(
	'posts_per_page' => 5,
	'post_type'      => 'pmc_top_video',
	'tax_query'      => array( // WPCS: slow query ok.
		array(
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => $tax_name,
		),
	),
);

$video_query = new WP_Query( $video_args );
$posts       = $video_query->posts;

if ( empty( $posts ) ) {
	return;
}

?>

<div class="l-section l-section--no-separator">

	<div class="l-section__header">
		<div class="c-section-header">
			<h3 class="c-section-header__heading t-bold t-bold--upper t-bold--condensed">
				<?php echo esc_html( $title ); ?>
			</h3>

			<a href="<?php echo esc_url( $term_link ); ?>" class="c-section-header__cta t-semibold t-semibold--upper t-semibold--loose">
				<?php esc_html_e( 'View All', 'pmc-rollingstone' ); ?>
			</a>
		</div><!-- .c-section-header -->
	</div><!-- /.l-section__header -->

	<div class="l-section__content">
		<div class="c-video-grid">
			<?php
			if ( ! empty( $posts[0] ) ) :
				setup_postdata( $GLOBALS['post'] =& $posts[0] ); // phpcs:ignore
			?>
			<div class="c-video-grid__main">

				<?php
				\PMC::render_template(
					CHILD_THEME_PATH . '/template-parts/video/section-video.php', [
						'css' => true,
					], true
				);
				?>

			</div><!-- /.c-video-grid__item -->
			<?php
			endif;
			unset( $posts[0] );
			?>

			<div class="c-video-grid__subgrid">
				<?php
				if ( ! empty( $posts ) ) :
					foreach ( $posts as $video ) :
						setup_postdata( $GLOBALS['post'] =& $video ); // phpcs:ignore
					?>

					<div class="c-video-grid__item">

						<?php
						\PMC::render_template(
							CHILD_THEME_PATH . '/template-parts/video/section-video.php', [
								'css' => false,
							], true
						);
						?>

					</div><!-- /.c-video-grid__item -->

				<?php
					endforeach;
				endif;
				?>
			</div><!-- /.c-video-grid__item -->
		</div><!-- /.c-video-grid -->
	</div><!-- /.l-section__content -->

</div>

<?php
wp_reset_postdata();
