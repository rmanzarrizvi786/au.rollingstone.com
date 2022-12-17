<?php


namespace Rolling_Stone\Inc\Widgets;

use \PMC\Core\Inc\Widgets\Traits\Templatize;

/**
 * Trending Now widget
 */
class Trending extends \FM_Widget {

	use Templatize;

	/**
	 * Trending_Now_Widget constructor.
	 */
	public function __construct() {
		parent::__construct(
			'rs_trending_now', __( 'RS Trending Now', 'pmc-rollingstone' ), [
				'classname'   => 'trending-now-widget',
				'description' => __( 'A list of trending posts.', 'pmc-rollingstone' ),
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
			'title'      => new \Fieldmanager_TextField( __( 'Title', 'pmc-rollingstone' ) ),
			'type'       => new \Fieldmanager_Select(
				[
					'label'   => __( 'Type', 'pmc-rollingstone' ),
					'options' => [
						'most_commented' => __( 'Most Commented', 'pmc-rollingstone' ),
						'most_viewed'    => __( 'Most Viewed', 'pmc-rollingstone' ),
					],
				]
			),
			'count'      => new \Fieldmanager_Select(
				[
					'label'   => __( 'Count', 'pmc-rollingstone' ),
					'options' => array_combine( range( 1, 10 ), range( 1, 10 ) ),
				]
			),
			'period'     => new \Fieldmanager_Select(
				[
					'label'   => __( 'Period', 'pmc-rollingstone' ),
					'options' => [
						'1'   => __( 'Last 1 day', 'pmc-rollingstone' ),
						'2'   => __( 'Last 2 days', 'pmc-rollingstone' ),
						'7'   => __( 'Last 7 days', 'pmc-rollingstone' ),
						'30'  => __( 'Last 30 days', 'pmc-rollingstone' ),
						'60'  => __( 'Last 60 days', 'pmc-rollingstone' ),
						'90'  => __( 'Last 90 days', 'pmc-rollingstone' ),
						'180' => __( 'Last 6 months', 'pmc-rollingstone' ),
						'365' => __( 'Last 1 year', 'pmc-rollingstone' ),
					],
				]
			),
			'image_size' => new \Fieldmanager_Select(
				[
					'label'   => __( 'Image Size', 'pmc-rollingstone' ),
					'options' => array_combine( $image_sizes, $image_sizes ),
				]
			),
			'style'      => new \Fieldmanager_Select(
				[
					'label'   => __( 'Style', 'pmc-rollingstone' ),
					'options' => [
						'sidebar' => __( 'Sidebar', 'pmc-rollingstone' ),
						'hero'    => __( 'Hero', 'pmc-rollingstone' ),
					],
				]
			),

		];
	}

}
