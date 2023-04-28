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
class Video_Record_Of_Week extends \FM_Widget {

	use Templatize;

	/**
	 * Video Gallery constructor.
	 */
	public function __construct() {
		parent::__construct(
			'rs_video_record_of_week', __( 'TBM Video + Record of week', 'pmc-rollingstone' ), [
				'classname'   => 'video-record-of-week',
				'description' => __( 'Video + Record of the week.', 'pmc-rollingstone' ),
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
			'title_video'    => new \Fieldmanager_TextField( __( 'Title Video', 'pmc-rollingstone' ) ),
			'title_record'    => new \Fieldmanager_TextField( __( 'Title Record', 'pmc-rollingstone' ) ),
		];
	}
}
