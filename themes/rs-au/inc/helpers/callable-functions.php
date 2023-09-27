<?php
/**
 * Callable Functions
 *
 * @package pmc-rollingstone-2018
 * @since 2017.1.0
 */

use Rolling_Stone\Inc\Category;

/**
 * Get the parent category for a post.
 *
 * @param int|null $post_id The ID of the post.
 * @return array|bool|null|WP_Error|WP_Term
 */
function rollingstone_get_the_category( $post_id = null ) {
	$categories = rollingstone_get_the_post_categories( $post_id );

	if ( ! empty( $categories['category'] ) ) {
		return get_term( $categories['category'] );
	}

	return false;
}

/**
 * Get the subcategory for a post.
 *
 * @param int|null $post_id The ID of the post.
 * @return array|bool|null|WP_Error|WP_Term
 */
function rollingstone_get_the_subcategory( $post_id = null ) {
	$categories = rollingstone_get_the_post_categories( $post_id );

	if ( ! empty( $categories['subcategory'] ) ) {
		return get_term( $categories['subcategory'] );
	}

	return false;
}

/**
 * Get the category and sub category for a post. If there are multiple categories
 * and subcategories, we fetch the first.
 *
 * @param int|null $post_id The ID of the post.
 * @return bool|mixed|string
 */
function rollingstone_get_the_post_categories( $post_id = null ) {

	$category = new Category();
	return $category->get_the_post_categories( $post_id );

}

/**
 * Iterate over an array of arbitrary items, passing the index and item to a
 * given template part.
 *
 * This function will load the given template using PMC::render_template() and add two variables to its
 * variables array: 'index' and 'item'. 'index' will hold the array key, and
 * 'item' will hold the array value.
 *
 * @param array  $iterate   The items to iterate over.
 * @param string $path      Template path.
 * @param array  $variables Variables for the template. Adds 'index' and
 *                         'item' as noted above.
 *
 * @return void
 */
function rollingstone_iterate_template_part( $iterate, $path, array $variables = array() ) {

	if ( empty( $iterate ) || ! is_array( $iterate ) ) {
		return;
	}

	foreach ( $iterate as $index => $item ) {

		$variables['item']  = $item;
		$variables['index'] = $index;

		\PMC::render_template(
			sprintf( '%s/%s', untrailingslashit( CHILD_THEME_PATH ), $path ),
			$variables,
			true
		);

	}

}

/**
 * Checks whether a string resembles a YouTube URL.
 *
 * @param string $url A presumed URL.
 * @return bool Whether it resembles a YouTube URL.
 */
function rollingstone_is_youtube_url( $url = '' ) {
	if ( false !== strpos( $url, 'youtu.be' ) ) {
		return true;
	}

	if ( false !== strpos( $url, 'youtube.com/watch' ) ) {
		return true;
	}

	return false;
}

/**
 * Checks whether a string resembles a DailyMotion URL.
 *
 * @param string $url A presumed URL.
 * @return bool Whether it resembles a DailyMotion URL.
 */
function rollingstone_is_dailymotion_url( $url = '' ) {
	if ( false !== strpos( $url, 'dailymotion.com/video' ) ) {
		return true;
	}

	return false;
}

/**
 * Helper function to return the legacy id & permalink
 * @param  WP_POST $post The post to retrieve the data from
 * @return array
 */
function rollingstone_get_legacy_data( $post = null ) {

	// Try to retrieve currently post via global or queried object
	if ( empty( $post ) ) {
		if ( ! empty( $GLOBALS['_current_post'] ) ) {
			$post = $GLOBALS['_current_post'];
		}

		if ( empty( $post ) ) {
			$post = get_queried_object();
			if ( ! empty( $post ) && ! empty( $post->ID ) ) {
				$post = get_post( $post->ID );
			}
		}

	}

	if ( empty( $post ) ) {
		return [
			'the_id'        => get_the_ID(),
			'the_permalink' => get_the_permalink(),
		];
	}

	$the_id = $post->ID;

	if ( get_the_time( 'U', $post ) < PMC_LEGACY_DATE ) {

		$legacy_slug = get_post_meta( $the_id, 'legacy_slug', true );

		if ( ! empty( $legacy_slug ) ) {

			// Legacy site use https: for canonical url
			$the_permalink = 'https://www.rollingstone.com' . $legacy_slug;

			// The disqus identifier reference full http: url
			$the_id = 'http://www.rollingstone.com' . $legacy_slug;

		}

	}

	// fallback if there is no legacy url
	if ( empty( $the_permalink ) ) {
		$the_permalink = get_the_permalink( $post );
	}

	return [
		'the_id'        => $the_id,
		'the_permalink' => $the_permalink,
	];

}

function rollingstone_get_image_proxy( $image ) {
	$old_domain = 'https://bcm.buzzanglemusic.com/';
	if ( substr( $image, 0, strlen( $old_domain ) ) === $old_domain ) {
		if ( \PMC::is_production() ) {
			return 'https://www.rollingstone.com/proxy-chart-image/' . substr( $image, strlen( $old_domain ) );
		} else {
			return 'https://rollingstone.pmcqa.com/proxy-chart-image/' . substr( $image, strlen( $old_domain ) );
		}
	} else {
		return $image;
	}

}
