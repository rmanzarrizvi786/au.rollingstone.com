<?php

/**
 * Card - Featured Media.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-21
 */

$post_id = get_the_ID();

$video = rollingstone_get_video_source(false);

if (!empty($video)) { // && ! \PMC\Gallery\View::is_vertical_gallery() ) {

	\PMC::render_template(
		// CHILD_THEME_PATH . '/template-parts/video/picture-no-caption.php',
		CHILD_THEME_PATH . '/template-parts/video/picture-with-caption.php',
		compact('video'),
		true
	);

	return;
}

/*
$linked_gallery = \PMC\Gallery\View::get_linked_gallery_data( $post_id );

if ( ! empty( $linked_gallery ) ) {
	$images                   = get_post_meta( $linked_gallery['id'], \PMC\Gallery\Defaults::NAME, true ) ?: [];
	$linked_gallery['images'] = array_values( $images );

	\PMC::render_template(
		CHILD_THEME_PATH . '/template-parts/gallery/featured-gallery.php',
		[
			'gallery' => $linked_gallery,
		],
		true
	);
	return;
}
*/

/* if (get_post_meta($post_id, 'thumbnail_ext_url', TRUE) && '' != get_post_meta($post_id, 'thumbnail_ext_url', TRUE)) {
	get_template_part('template-parts/article/featured-image-external');
} else if (has_post_thumbnail($post_id)) {
	get_template_part('template-parts/article/featured-image');
} */
get_template_part('template-parts/article/featured-image');
