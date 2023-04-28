<?php
/**
 * Rolling Stone Video Playlist.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-03
 */

namespace Rolling_Stone\Inc\Widgets;

use Rolling_Stone\Inc\Carousels;
use \PMC\Core\Inc\Widgets\Traits\Templatize;

/**
 * Video Playlist widget
 */
class Video_Playlist extends \FM_Widget {

	use Templatize;

	/**
	 * Video Playlist constructor.
	 */
	public function __construct() {
		parent::__construct(
			'rs_video_playlist', __( 'RS Video Playlist', 'pmc-rollingstone' ), [
				'classname'   => 'video-playlist-section-widget',
				'description' => __( 'A list of video playlist (categories).', 'pmc-rollingstone' ),
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
