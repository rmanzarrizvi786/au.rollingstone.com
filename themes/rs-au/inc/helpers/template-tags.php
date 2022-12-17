<?php

use PMC\Review\Fields;
use Rolling_Stone\Inc\Issues;
use Rolling_Stone\Inc\Media;

/**
 * Callable function for rendering the footer feed.
 *
 * The caching functionality within PMC requires a callable function for
 * rendering, although here we are just wrapping our class->method call.
 *
 * @see PMC_Cache::updates_with()    Requires callable function.
 * @see pmc_master_get_footer_feed() Applies filter: 'pmc_master_footer_feed_callback'
 *
 * @throws Exception Error.
 * @since 2018-11-04
 * @param array $args Callback arguments.
 * @return string
 */
function rollingstone_render_footer_feed($args)
{

	return \PMC::render_template(
		CHILD_THEME_PATH . '/template-parts/footer/newswire-item.php',
		[
			'item' => \Rolling_Stone\Inc\Footer_Feed::build_footer_feed($args),
		]
	);
}

/**
 * The title (curated titles can be overwritten).
 */
function rollingstone_the_title()
{
	echo esc_html(rollingstone_get_the_title());
}

/**
 * Return the title (curated titles can be overwritten).
 *
 * @return string
 */
function rollingstone_get_the_title()
{

	global $post;

	if (!empty($post->custom_title)) {
		return $post->custom_title;
	}

	return get_the_title();
}

/**
 * The excerpt (curated excerpts can be overwritten).
 */
function rollingstone_the_excerpt()
{
	echo esc_html(wp_strip_all_tags(rollingstone_get_the_excerpt()));
}

/**
 * Return the excerpt (curated excerpts can be overwritten).
 *
 * @return string
 */
function rollingstone_get_the_excerpt()
{

	global $post;

	if (!empty($post->custom_excerpt)) {
		return $post->custom_excerpt;
	}

	/*
	 * Show Dek only in river and not on single post pages
	 *
	 * @since 2018-07-24 Amit Gupta PMCP-717
	 */
	if (!empty($post->ID) && !is_single()) {

		$dek = get_post_meta($post->ID, 'override_post_excerpt', true);

		if (!empty($dek) && is_string($dek)) {
			return $dek;
		}
	}

	/*
	 * If its single post page and the excerpt field is empty
	 * then we should bail out, we don't want excerpt created
	 * from post content.
	 *
	 * @since 2018-07-24 Amit Gupta PMCP-717
	 */
	if (is_single() && empty($post->post_excerpt)) {
		return '';
	}

	return get_the_excerpt();
}

/**
 * Display the thumbnail (curated thumbnails can be overwritten).
 *
 * @param string|array $size Image size to use. Default is 'thumbnail'.
 * @param string|array $attr Query string or array of attributes. Default empty.
 */
function rollingstone_the_post_thumbnail($size = 'thumbnail', $attr = '')
{

	$thumbnail = rollingstone_get_the_post_thumbnail(null, $size, $attr);
	// wp_kses(
	// 	rollingstone_get_the_post_thumbnail( null, $size, $attr ),
	// 	wp_kses_allowed_html( Media::ATTACHMENT_KSES_CONTEXT )
	// );

	// See https://core.trac.wordpress.org/ticket/11311.
	echo str_replace('&amp;', '&', $thumbnail); // WPCS: XSS okay.

}

/**
 * Return the thumbnail (curated thumbnails can be overwritten).
 *
 * @param int|WP_Post  $post Post ID or WP_Post object. Default is global $post.
 * @param string|array $size Image size to use. Default is 'thumbnail'.
 * @param string|array $attr Query string or array of attributes. Default empty.
 * @return string
 */
function rollingstone_get_the_post_thumbnail($post = null, $size = 'thumbnail', $attr = '')
{
	$post = get_post($post);
	if (!$post) {
		return '';
	}

	if (!empty($post->custom_thumbnail_id)) {
		return get_the_post_thumbnail($post->custom_thumbnail_id, $size, $attr);
	}

	return get_the_post_thumbnail($post->ID, $size, $attr);
}

/**
 * Echoes an attachment image specified by attachment ID.
 *
 * @param int $image The attachment ID.
 * @param string $size A registered image size.
 * @param boolean $icon Whether the image should be treated as an icon.
 * @param array $attr Attributes for the image markup.
 */
function rollingstone_get_attachment_image($image, $size = 'thumbnail', $icon = false, $attr = [])
{

	$image = wp_kses(
		wp_get_attachment_image($image, $size, $icon, $attr),
		wp_kses_allowed_html(Media::ATTACHMENT_KSES_CONTEXT)
	);

	// See https://core.trac.wordpress.org/ticket/11311.
	return str_replace('&amp;', '&', $image);
}


/**
 * Echoes an attachment image specified by attachment ID.
 *
 * @param int $image The attachment ID.
 * @param string $size A registered image size.
 * @param boolean $icon Whether the image should be treated as an icon.
 * @param array $attr Attributes for the image markup.
 */
function rollingstone_attachment_image($image, $size = 'thumbnail', $icon = false, $attr = [])
{

	// The image is sanitized in rollingstone_get_attachment_image.
	echo rollingstone_get_attachment_image($image, $size, $icon, $attr); // WPCS: XSS okay.

}

/**
 * Custom pagination for the video playlist taxonomy page.
 *
 * @return void
 */
function rollingstone_video_playlist_pagination()
{

	global $wp_query;

	$paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
	$max   = intval($wp_query->max_num_pages);

	// Add current page to the array.
	if ($paged >= 1) {
		$links[] = $paged;
	}

	// Add the pages around the current page to the array.
	if ($paged >= 3) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if (($paged + 2) <= $max) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	// Previous Post Link.
	printf(
		'<a href="%s" class="c-large-pagination__nav"><svg class="c-large-pagination__icon c-large-pagination__icon--left"><use xlink:href="#svg-icon-chevron"></use></svg></a>',
		esc_url(get_previous_posts_page_link())
	);

	echo '<div class="c-large-pagination__numbers">';

	sort($links);
	foreach ((array) $links as $link) {
		$class = ($paged === $link) ? 'c-large-pagination__number t-semibold is-active' : 'c-large-pagination__number t-semibold';
		printf('<a href="%s" class="%s">%s</a>', esc_url(get_pagenum_link($link)), $class, $link); // WPCS: XSS ok.
	}

	echo '</div>';

	// Next Post Link.
	if (get_next_posts_link()) {
		printf(
			'<a href="%s" class="c-large-pagination__nav"><svg class="c-large-pagination__icon c-large-pagination__icon--right"><use xlink:href="#svg-icon-chevron"></use></svg></a>',
			esc_url(get_next_posts_page_link())
		);
	}
}

/**
 * To filter Query args from youtube URL.
 *
 * @before https://www.youtube.com/watch?v=vJjw5kMr9X8&feature=youtu.be
 * @after  https://www.youtube.com/watch?v=vJjw5kMr9X8
 *
 * @param string $url url to filter query args.
 *
 * @return string
 */
function rollingstone_filter_youtube_url($url)
{

	if (!empty($url)) {
		$url_parts = wp_parse_url($url);
		$host      = !empty($url_parts['host']) ? $url_parts['host'] : '';

		if (
			'www.youtube.com' === $host
			|| 'youtube.com' === $host
			|| 'www.youtu.be' === $host
			|| 'youtu.be' === $host
		) {

			if (!empty($url_parts['query'])) {
				parse_str($url_parts['query'], $query_params);
			}

			$url = sprintf('%1$s://%2$s%3$s', $url_parts['scheme'], $host, $url_parts['path']);

			if (!empty($query_params['v']) && false === strpos($host, 'youtu.be')) {
				$url = add_query_arg(array('v' => $query_params['v']), $url);
			}
		}
	}

	return $url;
}

/**
 * Output the top video.
 *
 * @param $echo bool To outout the source or not.
 * @return string
 */
function rollingstone_get_video_source($echo = true, $post_id = NULL)
{
	if (is_null($post_id)) {
		global $post;
	} else {
		$post = get_post($post_id);
	}

	// return '';

	// Fetch the video source.
	// $video_source = get_post_meta( $post->ID, PMC_Featured_Video_Override::META_KEY, true );

	$video_source = get_post_meta($post->ID, 'pmc_top_video_source', true);

	// if ( empty( $video_source ) ) {
	// 	$video_source = get_post_meta( $post->ID, 'pmc_top_video_source', true );
	// }

	if (empty($video_source)) {
		return '';
	}

	$video_source = rollingstone_filter_youtube_url($video_source);

	// For YouTube, apply an iFrame. Caters for youtu.be links.
	if (rollingstone_is_youtube_url($video_source)) {
		$video_source = str_replace('www.', '', $video_source);

		if (strpos($video_source, 'youtu.be')) {
			$video_source = preg_replace('~^https?://youtu\.be/([a-z-\d_]+)$~i', 'https://www.youtube.com/embed/$1', $video_source);
		} elseif (strpos($video_source, 'youtube.com/watch')) {
			$video_source = preg_replace('~^https?://youtube\.com\/watch\?v=([a-z-\d_]+)$~i', 'https://www.youtube.com/embed/$1', $video_source);
		}

		$video_source .= '?version=3&#038;rel=1&#038;fs=1&#038;autohide=2&#038;showsearch=0&#038;showinfo=1&#038;iv_load_policy=1&#038;wmode=transparent';

		if (is_singular('post')) {
			$video_html = '<iframe type="text/html" width="670" height="407" src="%1$s" data-src="%1$s" allowfullscreen="true" style="border:0;"></iframe>';
		} else {
			$video_html = '<iframe type="text/html" width="670" height="407" data-src="%1$s" allowfullscreen="true" style="border:0;"></iframe>';
		}

		$video_source = sprintf($video_html, esc_url($video_source));
	} elseif (rollingstone_is_dailymotion_url($video_source)) {

		$category_details = get_the_category($post_id);

		$ads_params = '';
		if (isset($category_details) && isset($category_details[0])) {
			$ads_params = '?autoplay=true&ads_params=' . $category_details[0]->slug;
		}

		$video_source = str_replace('www.', '', $video_source);

		if (strpos($video_source, 'dailymotion.com/video')) {
			$video_source = preg_replace('~^https?://dailymotion\.com\/video\/([a-z-\d_]+)$~i', 'https://www.dailymotion.com/embed/video/$1' . $ads_params, $video_source);
		}

		// $video_source .= '?version=3&#038;rel=1&#038;fs=1&#038;autohide=2&#038;showsearch=0&#038;showinfo=1&#038;iv_load_policy=1&#038;wmode=transparent';

		if (is_singular('post')) {
			$video_html = '<iframe type="text/html" width="670" height="407" src="%1$s" data-src="%1$s" allowfullscreen="true" style="border:0;"></iframe>';
		} else {
			$video_html = '<iframe type="text/html" width="670" height="407" data-src="%1$s" allowfullscreen="true" allow="autoplay" style="border:0;"></iframe>';
		}

		$video_source = sprintf($video_html, esc_url($video_source));
	} elseif (!empty(wp_parse_url($video_source, PHP_URL_HOST))) {    // phpcs:ignore

		// Run it via oEmbed parser to parse any embeds in there
		// $video_source = wpcom_vip_wp_oembed_get( $video_source );

	} else {

		// Run source via shortcode parser to parse any shortcodes in there
		$video_source = do_shortcode($video_source);
	}

	if ($echo) {
		echo $video_source; // WPCS: XSS okay. Cleaned above already depending on the source.
	} else {
		return $video_source;
	}
}

/**
 * Render JWPlayer for video carousal gallery.
 *
 * @return void
 */
function rollingstone_render_carousal_jwplayer()
{
	global $post;

	// Fetch the video source.
	// $video_source = get_post_meta( $post->ID, PMC_Featured_Video_Override::META_KEY, true );

	$video_source = get_post_meta($post->ID, 'video_url', true);

	// if ( empty( $video_source ) ) {
	// 	$video_source = get_post_meta( $post->ID, 'pmc_top_video_source', true );
	// }

	// Check if it's JW Video.
	if (false !== strpos($video_source, 'jwplayer')) {
		global $jwplayer_shortcode_embedded_players;

		$regex = '/\[jwplayer (?P<media>[0-9a-z]{8})(?:[-_])?(?P<player>[0-9a-z]{8})?\]/i';
		preg_match($regex, $video_source, $matches, null, 0);

		$player = (!empty($matches['player'])) ? $matches['player'] : false;
		$media  = (!empty($matches['media'])) ? $matches['media'] : false;
		$player = (false === $player) ? get_option('jwplayer_player') : $player;

		$content_mask = jwplayer_get_content_mask();
		$protocol     = (is_ssl() && defined('JWPLAYER_CONTENT_MASK') && JWPLAYER_CONTENT_MASK === $content_mask) ? 'https' : 'http';

		$json_feed = "$protocol://$content_mask/feeds/$media.json";

		if (false !== $player && !in_array($player, (array) $jwplayer_shortcode_embedded_players, true)) {
			$js_lib = "$protocol://$content_mask/libraries/$player.js";

			$jwplayer_shortcode_embedded_players[] = $player;
			printf('<script type="text/javascript" src="%s"></script>', esc_url($js_lib));
		}

		printf("<div id='jwplayer_%s_div' data-videoid='%s' data-jsonfeed='%s'></div>", esc_attr($media), esc_attr($media), esc_url($json_feed));
	} else {
		rollingstone_get_video_source();
	}
}

/**
 * Echoes review rating stars for the current post or a post passed in by ID.
 *
 * @param int|null $post_id A WP_Post ID.
 */
function rollingstone_review_stars($post_id = null)
{

	$post_id = null !== $post_id ? $post_id : get_the_ID();
	/*
	$rating  = Fields::get_instance()->get( Fields::RATING, $post_id );
	$out_of  = Fields::get_instance()->get( Fields::RATING_OUT_OF, $post_id );
	*/
	$rating  = get_field('rating', $post_id);
	$out_of  = get_field('rating_out_of', $post_id);

	if (empty($rating) || empty($out_of)) {
		return;
	}

	$rating = floatval($rating);
	$out_of = intval($out_of);

	// Make sure $out_of is an acceptable value.
	if ($out_of <= 0 || $out_of > 5) {
		return;
	}

	// Make sure $rating is in the correct range.
	if ($rating < 0 || $rating > $out_of) {
		return;
	}

	\PMC::render_template(
		CHILD_THEME_PATH . '/template-parts/article/card-rating.php',
		compact('rating', 'out_of'),
		true
	);
}

/**
 * Check if it's 'Country' template type.
 *
 * @return bool
 */
function rollingstone_is_country()
{

	$country_categories = [
		'music-country',
		'music-country-lists',
		'music-country-pictures',
		'music-country-videos',
	];

	if (is_single()) {
		return has_category($country_categories);
	}

	return is_category($country_categories);
}

/**
 * Echoes the title of a reviewed work -- e.g. a film title, a music album name.
 *
 * @param int $post_id A WP_Post ID.
 */
function rollingstone_review_title($post_id = null, $is_attr = false)
{

	$post_id = null !== $post_id ? $post_id : get_the_ID();
	$title   = Fields::get_instance()->get(Fields::TITLE, $post_id);

	if (!empty($title)) {
		echo $is_attr ? esc_attr($title) : wp_kses_post($title);
	}
}

/**
 * Echoes the artist of a reviewed work.
 *
 * @param int $post_id A WP_Post ID.
 */
function rollingstone_review_artist($post_id = null)
{

	$post_id = null !== $post_id ? $post_id : get_the_ID();
	$artist  = Fields::get_instance()->get(Fields::ARTIST, $post_id);

	if (!empty($artist)) {
		echo wp_kses_post($artist);
	}
}

/**
 * Echoes the image URL set as post meta for a reviewed work.
 *
 * @param int $post_id A WP_Post ID.
 */
function rollingstone_review_image($post_id = null)
{

	$post_id = null !== $post_id ? $post_id : get_the_ID();
	$image   = Fields::get_instance()->get(Fields::IMAGE, $post_id);

	if (!empty($image)) {
		$thumbnail_ratio = has_category('music-album-reviews', $post_id) ? '1:1' : '2:3';
		$image_height    = has_category('music-album-reviews', $post_id) ? '200' : '300';
		$image_src       = $image . '?crop=' . $thumbnail_ratio . ',smart&w=225';
		$image_src_2x    = $image . '?crop=' . $thumbnail_ratio . ',smart&w=400';
		echo '<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="' .
			'data-src="' . esc_url($image_src) . '" ' .
			'data-srcset="' . esc_url($image_src) . ' 225w, ' . esc_url($image_src_2x) . ' 400w"' .
			'sizes="(max-width: 1259px) 200px, 225px"' .
			'class="c-crop__img" alt width="200" height="' . esc_attr($image_height) . '" />';
	}
}

/**
 * Echoes the current issue cover if one exists in the specified size.
 *
 * @param int   $width A width of the cover
 * @param array $attr Attributes to add to the IMG tag. Default is empty array.
 * @param int   $issue_id Specific Issue ID which cover should be displayed. Default is null.
 */
function rollingstone_the_issue_cover($width = 120, $attr = [], $issue_id = null)
{
	// if ( null === $issue_id ) {
	// 	$issue_id = ''; //Issues::get_instance()->get_current_issue_id();
	// }
	//
	// if ( empty( $issue_id ) ) {
	// 	return;
	// }

	// $url = get_the_post_thumbnail_url( $issue_id, 'full' );

	$url = get_option('tbm_current_issue_cover');

	// var_dump( $url );

	if (empty($url)) {
		return rollingstone_next_issue_cover($width, $attr, $issue_id);
		// return;
	}

	$src         = sprintf('%s?w=%d', $url, $width);
	$srcset      = sprintf('%1$s?w=%2$d 1x, %1$s?w=%3$d 2x', $url, $width, $width * 2);
	$placeholder = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
	$class       = !empty($attr['class']) ? $attr['class'] : '';
	$alt         = ''; // ! empty( $attr['alt'] ) ? $attr['alt'] : get_the_title( $issue_id );

	printf(
		'<img src="%s" data-src="%s" alt="%s" class="%s" style="width: %spx" />',
		esc_url($src),
		esc_url($src),
		// esc_attr( str_replace( '%20', ' ', esc_url( $srcset ) ) ),
		esc_attr($alt),
		esc_attr($class),
		esc_attr($width)
	);
}

function rollingstone_next_issue_cover($width = 120, $attr = [], $issue_id = null)
{

	$url = get_option('tbm_next_issue_cover');

	if (!$url) {
		return rollingstone_the_issue_cover($width, $attr, $issue_id);
	}

	if (empty($url)) {
		return;
	}

	$src         = sprintf('%s?w=%d', $url, $width);
	$srcset      = sprintf('%1$s?w=%2$d 1x, %1$s?w=%3$d 2x', $url, $width, $width * 2);
	$placeholder = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
	$class       = !empty($attr['class']) ? $attr['class'] : '';
	$alt         = ''; // ! empty( $attr['alt'] ) ? $attr['alt'] : get_the_title( $issue_id );

	printf(
		'<img src="%s" data-src="%s" alt="%s" class="%s" style="width: %spx" />',
		esc_url($src),
		esc_url($src),
		// esc_attr( str_replace( '%20', ' ', esc_url( $srcset ) ) ),
		esc_attr($alt),
		esc_attr($class),
		esc_attr($width)
	);
}

function rollingstone_last_issue_cover($width = 120, $attr = [], $issue_id = null)
{

	$url = get_option('tbm_last_issue_cover');

	if (!$url) {
		return rollingstone_the_issue_cover($width, $attr, $issue_id);
	}

	if (empty($url)) {
		return;
	}

	$src         = sprintf('%s?w=%d', $url, $width);
	$srcset      = sprintf('%1$s?w=%2$d 1x, %1$s?w=%3$d 2x', $url, $width, $width * 2);
	$placeholder = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
	$class       = !empty($attr['class']) ? $attr['class'] : '';
	$alt         = ''; // ! empty( $attr['alt'] ) ? $attr['alt'] : get_the_title( $issue_id );

	printf(
		'<img src="%s" data-src="%s" alt="%s" class="%s" style="width: %spx" />',
		esc_url($src),
		esc_url($src),
		// esc_attr( str_replace( '%20', ' ', esc_url( $srcset ) ) ),
		esc_attr($alt),
		esc_attr($class),
		esc_attr($width)
	);
}

/**
 * Display the author avatar.
 *
 * @param int   $author_id Author post ID.
 * @param array $attr Attributes to add to the IMG tag. Default is empty array.
 * @param int   $width A width of the avatar. Default is 92.
 */
function rollingstone_the_author_avatar($author_id, $attr = [], $width = 92)
{
	if (!$author_id) {
		return;
	}

	$url = get_the_post_thumbnail_url($author_id, 'full');

	if (empty($url)) {
		return;
	}

	$src         = sprintf('%s?w=%d&crop=1:1', $url, $width);
	$srcset      = sprintf('%1$s?w=%2$d&crop=1:1 1x, %1$s?w=%3$d&crop=1:1 2x', $url, $width, $width * 2);
	$placeholder = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
	$class       = !empty($attr['class']) ? $attr['class'] : '';
	$alt         = !empty($attr['alt']) ? $attr['alt'] : get_the_title($author_id);

	printf(
		'<img src="%s" data-src="%s" data-srcset="%s" alt="%s" class="%s" />',
		esc_url($placeholder),
		esc_url($src),
		esc_attr(str_replace('%20', ' ', esc_url($srcset))),
		esc_attr($alt),
		esc_attr($class)
	);
}

/**
 * Is it a list page?
 *
 * @return bool
 */
function rollingstone_is_list()
{
	return is_singular(['pmc_list', 'pmc_list_item']);
}
