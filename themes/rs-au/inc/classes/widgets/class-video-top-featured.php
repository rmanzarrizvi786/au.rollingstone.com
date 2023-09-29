<?php
/**
 * Rolling Stone Video top Featured Gallery.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-03
 */

namespace Rolling_Stone\Inc\Widgets;

use Rolling_Stone\Inc\Carousels;
use \PMC\Core\Inc\Widgets\Traits\Templatize;

/**
 * Video Top Featured widget
 */
class Video_Top_Featured extends \FM_Widget {

	use Templatize;

	/**
	 * Video Top Featured constructor.
	 */
	public function __construct() {
		parent::__construct(
			'rs_video_top_featured', __( 'RS Video Top Featured', 'pmc-rollingstone' ), [
				'classname'   => 'video-top-featured-section-widget',
				'description' => __( 'A list of featured videos.', 'pmc-rollingstone' ),
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
			'carousel' => new \Fieldmanager_Select(
				[
					'label'   => __( 'Carousel', 'pmc-rollingstone' ),
					'options' => Carousels::get_carousels(),
				]
			),
		];
	}
}
