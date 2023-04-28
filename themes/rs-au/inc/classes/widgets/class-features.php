<?php
/**
 * Rolling Stone Features Section.
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2018-04-13
 */

namespace Rolling_Stone\Inc\Widgets;

use Rolling_Stone\Inc\Carousels;
use \PMC\Core\Inc\Widgets\Traits\Templatize;

/**
 * Features widget
 */
class Features extends \FM_Widget {

	use Templatize;

	/**
	 * Features constructor.
	 */
	public function __construct() {
		parent::__construct(
			'rs_features', __( 'RS Features', 'pmc-rollingstone' ), [
				'classname'   => 'features-widget',
				'description' => __( 'A list of stories that are features.', 'pmc-rollingstone' ),
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
