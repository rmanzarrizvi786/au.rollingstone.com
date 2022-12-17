<?php
/**
 * Rolling Stone Photo Galleries Section.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-20
 */

namespace Rolling_Stone\Inc\Widgets;

use Rolling_Stone\Inc\Carousels;
use \PMC\Core\Inc\Widgets\Traits\Templatize;

/**
 * Photo Galleries widget
 */
class Photo_Gallery extends \FM_Widget {

	use Templatize;

	/**
	 * Photo Galleries constructor.
	 */
	public function __construct() {
		parent::__construct(
			'rs_photo_gallery', __( 'RS Photo Galleries', 'pmc-rollingstone' ), [
				'classname'   => 'photo-gallery-widget',
				'description' => __( 'A list of photo galleries.', 'pmc-rollingstone' ),
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
			'count'    => new \Fieldmanager_Select(
				[
					'label'   => __( 'Count', 'pmc-rollingstone' ),
					'options' => array_combine( range( 1, 10 ), range( 1, 10 ) ),
				]
			),
		];
	}
}
