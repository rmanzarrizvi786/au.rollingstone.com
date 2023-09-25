<?php
/**
 * Rolling Stone Section Video Card Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-25
 */

if ( empty( $item ) ) {
	return;
}

$data = get_post_meta( $item->curation_id, '_pmc_master_article_id', true );
$post = json_decode( $data );

if ( empty( $post->id ) ) {
	return;
}

$url = get_permalink( $post->id );

if ( is_wp_error( $url ) ) {
	return;
}

setup_postdata( $GLOBALS['post'] =& $item ); // phpcs:ignore
?>

<article class="c-card c-card--video-grid">
	<a href="<?php echo esc_url( $url ); ?>" class="c-card__wrap">
		<figure class="c-card__image">

			<div class="c-crop c-crop--ratio-7x4">

				<?php
				rollingstone_the_post_thumbnail(
					'ratio-7x4',
					[
						'class'  => 'c-crop__img',
						'sizes'  => '(max-width: 767px) 250px, 300px',
						'srcset' => [ 300, 250 ],
					]
				);
				?>

			</div><!-- .c-crop -->

		</figure><!-- .c-card__image -->
		<header class="c-card__header">

			<?php get_template_part( '/template-parts/article/card-heading' ); ?>

		</header><!-- .c-card__header -->
	</a><!-- .c-card__wrap -->
</article><!-- .c-card--video-grid -->
<?php
wp_reset_postdata();

//EOF
