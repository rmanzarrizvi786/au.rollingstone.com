<?php
/**
 * Rolling Stone Editors' Picks Widget.
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2018-03-05
 */

namespace Rolling_Stone\Inc\Widgets;

use Rolling_Stone\Inc\Carousels;
use \PMC\Core\Inc\Widgets\Traits\Templatize;

/**
 * Trending Now widget
 */
class Editors_Picks extends \FM_Widget {

	use Templatize;

	/**
	 * Editors_Picks_Widget constructor.
	 */
	public function __construct() {
		parent::__construct(
			'rs_editors_picks', __( 'RS Editors Picks', 'pmc-core' ), [
				'classname'   => 'editors-picks-widget',
				'description' => __( 'A list of featured posts.', 'pmc-core' ),
			]
		);
	}

	/**
	 * Define the fields that should appear in the widget.
	 *
	 * @return array Fieldmanager fields.
	 */
	protected function fieldmanager_children() {

		$image_sizes = \PMC\Image\get_intermediate_image_sizes();

		return [
			'title'    => new \Fieldmanager_TextField( __( 'Title', 'pmc-core' ) ),
			'carousel' => new \Fieldmanager_Select(
				[
					'label'   => __( 'Carousel', 'pmc-core' ),
					'options' => Carousels::get_carousels(),
				]
			),
			'count'    => new \Fieldmanager_Select(
				[
					'label'   => __( 'Count', 'pmc-core' ),
					'options' => array_combine( range( 1, 10 ), range( 1, 10 ) ),
				]
			),
		];
	}

}
