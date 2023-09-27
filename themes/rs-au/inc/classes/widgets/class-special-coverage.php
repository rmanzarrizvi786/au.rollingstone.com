<?php
/**
 * Rolling Stone Special Coverage Section.
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2018-04-18
 */

namespace Rolling_Stone\Inc\Widgets;

use Rolling_Stone\Inc\Carousels;
use \PMC\Core\Inc\Widgets\Traits\Templatize;

/**
 * Special Coverage widget
 */
class Special_Coverage extends \FM_Widget {

	use Templatize;

	/**
	 * Features constructor.
	 */
	public function __construct() {
		parent::__construct(
			'rs_special_coverage', __( 'RS Special Coverage', 'pmc-rollingstone' ), [
				'classname'   => 'special-coverage-widget',
				'description' => __( 'A list of stories that are listed as special coverage.', 'pmc-rollingstone' ),
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
