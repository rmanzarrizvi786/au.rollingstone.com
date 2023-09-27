<?php
/**
 * Rolling Stone Carousels
 *
 * Class for dealing with Carousel data.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-03-05
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Class Carousels.
 *
 * @since 2017.1.0
 */
class Carousels {

	// use Singleton;

	/**
	 * Carousels constructor.
	 */
	public function __construct() {
		add_filter( 'pmclinkcontent_post_types', array( $this, 'add_content_types' ) );
	}

	/**
	 * Add post types to the curation metabox.
	 *
	 * @param array $post_types The existing supported post types.
	 * @return array
	 */
	public function add_content_types( $post_types ) {
		$post_types[] = 'pmc_list';
		$post_types[] = 'pmc_top_video';
		return $post_types;
	}

	/**
	 * Get Carousels
	 *
	 * Returns a list of carousels.
	 *
	 * @since 2017.1.0
	 * @return array The list of carousels
	 */
	public static function get_carousels() {
		$carousels = array(
			'none' => __( 'No Carousel (hide)', 'pmc-rollingstone' ),
		);

		foreach ( get_terms( \PMC_Carousel::modules_taxonomy_name, array( 'hide_empty' => false ) ) as $term ) {
			$carousels[ $term->slug ] = $term->name;
		}

		return $carousels;
	}

	/**
	 * Get Carousel Posts
	 *
	 * Returns a list of posts selected in the carousel.
	 *
	 * @since 2017.1.0
	 *
	 * @param string  $carousel The carousel id.
	 * @param integer $count The number of articles to fetch.
	 * @param bool    $taxonomy Use the carousel taxonomy? True/False.
	 *
	 * @return array The list of carousel posts
	 */
	public static function get_carousel_posts( $carousel, $count, $taxonomy = false, $add_filler = false ) {
		$carousel_posts = array();

		if ( class_exists( 'PMC_Carousel' ) ) {
			if ( ! $taxonomy ) {
				$taxonomy = \PMC_Carousel::modules_taxonomy_name;
			}

			$posts = pmc_render_carousel(
				$taxonomy, $carousel, $count, '', array(
					'add_filler'           => $add_filler,
					'add_filler_all_posts' => false,
				)
			);

			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					if ( ( ! empty( $post['ID'] ) ) && ( 0 !== $post['ID'] ) ) {
						// Since the Carousel doesn't provide us a \WP_Post object, it's easier to create one.
						$carousel_item = get_post( $post['ID'] );

						if ( ! empty( $carousel_item ) ) {
							if ( ! empty( trim( $post['parent_ID'] ) ) ) {
								$carousel_item->curation_id = $post['parent_ID'];
							}

							if ( ! empty( trim( $post['title'] ) ) ) {
								$carousel_item->custom_title = $post['title'];
							}

							if ( ! empty( trim( $post['excerpt'] ) ) ) {
								$carousel_item->custom_excerpt = $post['excerpt'];
							}

							if ( has_post_thumbnail( $post['parent_ID'] ) ) {
								$carousel_item->custom_thumbnail_id = $post['parent_ID'];
							}

							$carousel_posts[] = $carousel_item;
						}
					} else {

						$post_object = get_post( $post['parent_ID'] );

						if ( ! empty( $post['url'] ) ) {
							$post_object->url = $post['url'];
						}

						$carousel_posts[] = $post_object;
					}
				}
			}
		}

		return $carousel_posts;
	}
}
