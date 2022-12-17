<?php
/**
 * Rolling Stone Section.
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2018-04-13
 */

namespace Rolling_Stone\Inc\Widgets;

use Rolling_Stone\Inc\Carousels;
use \PMC\Core\Inc\Widgets\Traits\Templatize;

/**
 * Section widget
 */
class Section extends \FM_Widget {

	use Templatize;

	/**
	 * Section constructor.
	 */
	public function __construct() {
		parent::__construct(
			'rs_section', __( 'RS Section', 'pmc-rollingstone' ), [
				'classname'   => 'section-widget',
				'description' => __( 'A list of posts from a specified category.', 'pmc-rollingstone' ),
			]
		);
	}

	/**
	 * Define the fields that should appear in the widget.
	 *
	 * @return array Fieldmanager fields.
	 */
	protected function fieldmanager_children() {

		$carousels = Carousels::get_carousels();

		return [
			'title'              => new \Fieldmanager_TextField( __( 'Title', 'pmc-rollingstone' ) ),
			'category'           => new \Fieldmanager_Select(
				[
					'label'   => __( 'Category', 'pmc-rollingstone' ),
					'options' => $this->get_categories(),
				]
			),
			'the_list_carousel'  => new \Fieldmanager_Select(
				[
					'label'   => __( 'The List (optional)', 'pmc-rollingstone' ),
					'options' => $carousels,
				]
			),
			'reviews_title'      => new \Fieldmanager_TextField( __( 'Reviews Title (optional)', 'pmc-rollingstone' ) ),
			'reviews_carousel'   => new \Fieldmanager_Select(
				[
					'label'   => __( 'Reviews Carousel (optional)', 'pmc-rollingstone' ),
					'options' => $carousels,
				]
			),
			'columnist_title'    => new \Fieldmanager_TextField( __( 'Columnists Title (optional)', 'pmc-rollingstone' ) ),
			'columnist_carousel' => new \Fieldmanager_Select(
				[
					'label'   => __( 'Columnists Carousel (optional)', 'pmc-rollingstone' ),
					'options' => $carousels,
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
				'taxonomy' => 'category',
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
