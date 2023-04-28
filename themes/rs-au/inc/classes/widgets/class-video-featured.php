<?php
/**
 * Rolling Stone Featured Video Category Gallery.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-01
 */

namespace Rolling_Stone\Inc\Widgets;

use Rolling_Stone\Inc\Carousels;
use \PMC\Core\Inc\Widgets\Traits\Templatize;

/**
 * Video Featured widget
 */
class Video_Featured extends \FM_Widget {

	use Templatize;

	/**
	 * Video Gallery constructor.
	 */
	public function __construct() {
		parent::__construct(
			'rs_video_featured', __( 'RS Video Featured', 'pmc-rollingstone' ), [
				'classname'   => 'video-gallery-featured-widget',
				'description' => __( 'A list of videos from a playlist.', 'pmc-rollingstone' ),
			]
		);
	}

	/**
	 * Define the fields that should appear in the widget.
	 *
	 * @return array Fieldmanager fields.
	 */
	protected function fieldmanager_children() {

		return [
			'title'    => new \Fieldmanager_TextField( __( 'Title', 'pmc-rollingstone' ) ),
			'category' => new \Fieldmanager_Select(
				[
					'label'   => __( 'Playlist', 'pmc-rollingstone' ),
					'options' => $this->get_categories(),
				]
			),
		];
	}

	/**
	 * Fetch a list of categories to select.
	 *
	 * @return array
	 */
	protected function get_categories() {
		$categories = array();
		$terms      = get_terms(
			array(
				'taxonomy'   => 'vcategory',
				'hide_empty' => true,
			)
		);

		// Only fetch parent terms.
		foreach ( $terms as $term ) {
			if ( 0 === $term->parent ) {
				$categories[ $term->slug ] = $term->name;
			}
		}

		return $categories;
	}
}
