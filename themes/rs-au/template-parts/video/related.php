<?php
/**
 * Related Videos Section Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-20
 */

$videos_query = new \WP_Query(
	array(
		'post_type'      => 'pmc_top_video',
		'posts_per_page' => 8,
		'post__not_in' => array( get_the_ID() ),
	)
);

$videos = $videos_query->posts;

if ( empty( $videos ) ) {
	return;
}

?>

<div class="l-section l-section--no-separator l-section--with-bottom-margin">

	<div class="l-section__header">
		<div class="c-section-header">
			<h3 class="c-section-header__heading t-bold t-bold--upper t-bold--condensed">
				<?php esc_html_e( 'Related Videos', 'pmc-rollingstone' ); ?>
			</h3>
		</div><!-- .c-section-header -->
	</div><!-- /.l-section__header -->

	<ul class="c-newswire c-newswire--tablet-scroller">
		<?php
		foreach ( $videos as $video ) :
			setup_postdata( $GLOBALS['post'] =& $video ); // phpcs:ignore
			?>

			<li class="c-newswire__item">
				<?php get_template_part( 'template-parts/video/grid' ); ?>
			</li><!-- /.c-newswire__item -->

		<?php endforeach; ?>
	</ul><!-- .c-newswire -->

</div><!-- /.l-section -->

<?php
wp_reset_postdata();
