<?php
/**
 * Rolling Stone Video Gallery.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-21
 */

namespace Rolling_Stone\Inc\Widgets;

use Rolling_Stone\Inc\Carousels;
use \PMC\Core\Inc\Widgets\Traits\Templatize;

/**
 * Video Gallery widget
 */
class Video_Gallery extends \FM_Widget {

	use Templatize;

	/**
	 * Video Gallery constructor.
	 */
	public function __construct() {
		parent::__construct(
			'rs_video_gallery', __( 'RS Video Gallery', 'pmc-rollingstone' ), [
				'classname'   => 'video-gallery-section-widget',
				'description' => __( 'A list of videos.', 'pmc-rollingstone' ),
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
			'carousel' => new \Fieldmanager_Select(
				[
					'label'   => __( 'Carousel', 'pmc-rollingstone' ),
					'options' => Carousels::get_carousels(),
				]
			),
		];
	}
}
