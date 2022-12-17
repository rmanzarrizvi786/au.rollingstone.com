<?php

/**
 * List Item Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-01
 */

global $post;

$video         = rollingstone_get_video_source(false, $post->ID);
$image_credit  = pmc_get_photo_credit(get_post_thumbnail_id());
$image_caption = get_post_field('post_excerpt', get_post_thumbnail_id());
$title         = rollingstone_get_the_title();

$list_item_authors = \PMC::get_post_authors_list(get_the_ID(), 'all', 'user_login', 'user_nicename');
$list_item_authors = (!empty($list_item_authors)) ? str_replace(',', '^', $list_item_authors) : '';

/*
 * Legacy RS list items should show a placeholder image if a video exists to hide
 * duplicated images that were previously set.
 *
 * @todo: we should change this to an option which allows the editor to show/hide
 * featured image for a list item and retrospectively update old RS list items.
 */
if (!empty($video)) {
	// $legacy_id               = get_post_meta( get_the_ID(), 'legacy_id', true );
	$featured_image_override = '';

	// if ( ! empty( $legacy_id ) && ! has_post_thumbnail( get_the_ID() ) ) {
	if (!has_post_thumbnail(get_the_ID())) {
		$featured_image_override = get_stylesheet_directory_uri() . '/assets/build/images/_dev/list-placeholder.jpg';

		if (get_post_meta(get_the_ID(), 'thumbnail_ext_url', TRUE)) {
			$featured_image_override = get_post_meta(get_the_ID(), 'thumbnail_ext_url', TRUE);
			$image_credit  = get_post_meta(get_the_ID(), 'thumbnail_ext_image_credit', TRUE);
			$image_caption = get_post_meta(get_the_ID(), 'thumbnail_ext_image_caption', TRUE);
		} else {
			$video_source = get_post_meta(get_the_ID(), 'pmc_top_video_source', true);
			if (rollingstone_is_youtube_url($video_source)) {
				parse_str(parse_url($video_source, PHP_URL_QUERY), $video_vars);
				$youtube_vid_id = $video_vars['v'];
				$featured_image_override = "https://i.ytimg.com/vi/$youtube_vid_id/maxresdefault.jpg";
			}
		}
	}
}
?>

<article class="c-list__item c-list__item--artist" id="list-item-<?php echo esc_attr($index_attribute); ?>" data-list-item="<?php echo esc_attr($index_attribute); ?>" data-list-title="<?php echo esc_attr($title); ?>" data-list-permalink="<?php echo esc_url(get_permalink()); ?>" data-list-item-id="<?php the_ID(); ?>" data-list-item-authors="<?php echo esc_attr($list_item_authors); ?>">

	<?php if (empty($video)) : ?>
		<figure class="c-list__picture">
			<!-- <div class="c-list__share" data-collapsible="collapsed" data-collapsible-close-on-click> -->
			<!-- <div class="c-list__social-bar" data-collapsible-panel> -->
			<?php // get_template_part( 'template-parts/list/share-bar' ); 
			?>
			<!-- </div> -->
			<!-- /.c-list__social-bar -->
			<!-- <svg class="c-list__icon" data-collapsible-toggle="always-show"><use xlink:href="#svg-icon-share"></use></svg> -->
			<!-- </div> -->
			<!-- /.c-list__share -->

			<div class="c-crop c-crop--size-16x9">
				<?php
				if (has_post_thumbnail(get_the_ID())) {
					rollingstone_the_post_thumbnail(
						'ratio-16x9-list-item',
						[
							'class' => 'c-crop__img',
							'sizes' => '(max-width: 480px) 440px, (max-width: 959px) 919px, 940px',
							'srcet' => [440, 919, 940],
						]
					);
				} else {
					$featured_image_override = get_post_meta(get_the_ID(), 'thumbnail_ext_url', TRUE);
				?>
					<img width="900" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo $featured_image_override; ?>" class="c-crop__img wp-post-image" alt="" />
				<?php
				}
				?>
			</div><!-- .c-crop -->
		</figure><!-- /.c-list__picture -->
	<?php else : ?>
		<figure class="c-list__picture c-picture--video c-picture--no-caption">
			<!-- <div class="c-list__share" data-collapsible="collapsed" data-collapsible-close-on-click> -->
			<!-- <div class="c-list__social-bar" data-collapsible-panel> -->
			<?php // get_template_part( 'template-parts/list/share-bar' ); 
			?>
			<!-- </div><!-- /.c-list__social-bar -->
			<!-- <svg class="c-list__icon" data-collapsible-toggle="always-show"><use xlink:href="#svg-icon-share"></use></svg> -->
			<!-- </div><!-- /.c-list__share -->
			<div class="c-picture__frame">

				<div class="c-crop c-crop--video c-crop--ratio-video" data-video-crop>
					<div hidden>
						<?php echo $video; // WPCS: xss okay. 
						?>
					</div>

					<?php get_template_part('template-parts/badges/video-picture'); ?>

					<?php
					if (!empty($featured_image_override)) {
					?>
						<img src="<?php echo esc_url($featured_image_override); ?>" class="c-crop__img wp-post-image visible" alt="<?php esc_attr_e('Play video', 'pmc-rollingstone'); ?>" title="<?php esc_attr_e('Play video', 'pmc-rollingstone'); ?>" style="max-width: 100%; height: auto;">
					<?php
					} else {
						rollingstone_the_post_thumbnail(
							'ratio-video',
							[
								'class'  => 'c-crop__img',
								'srcset' => [480, 970, 1260],
								'sizes'  => '100%',
							]
						);
					}
					?>
				</div><!-- .c-crop -->

			</div>

		</figure>
	<?php endif; ?>

	<?php if (!empty($image_caption) || !empty($image_credit)) : ?>
		<div class="c-picture__caption">

			<p class="c-picture__title t-semibold">
				<?php echo esc_html($image_caption); ?>
			</p>
			<p class="c-picture__source t-semibold">
				<?php echo esc_html($image_credit); ?>
			</p>

		</div><!-- .c-picture__caption -->
	<?php endif; ?>

	<header class="c-list__header">
		<?php if (!empty($index)) : ?>
			<span class="c-list__number t-bold">
				<?php echo esc_html($index); ?>
			</span>
		<?php endif; ?>

		<h3 class="c-list__title t-bold">
			<?php echo esc_html($title); ?>
		</h3><!-- /.c-list__title -->
	</header><!-- /.c-list__header -->

	<main class="c-list__main">
		<div class="c-list__lead c-content">
			<?php the_content(); ?>
		</div><!-- /.c-list__lead -->
	</main><!-- /.c-list__main -->
</article><!-- .c-list__item -->

<?php
\PMC::render_template(
	sprintf(
		'%s/template-parts/ads/lists-river-ad.php',
		$template_path
	),
	[
		'current_index' => $current_index,
	],
	true
);
?>