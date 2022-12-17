<?php
/**
 * Class to render Ticketing widget in article footer outside of a sidebar.
 *
 * This class is dependent on the Ticketing widget and is not actually a WP Widget itself.
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-09-10
 */

namespace Rolling_Stone\Inc\Widgets;

use \PMC;
use \PMC\Global_Functions\Traits\Singleton;

class Ticketing_Footer {

	use Singleton;

	/**
	 * Method to check if widget should be rendered or not
	 *
	 * @return bool
	 */
	protected function _should_render() : bool {

		$widget_sidebar = is_active_widget( false, false, Ticketing::ID, true );

		if ( ! empty( $widget_sidebar ) && is_single() && PMC::is_mobile() ) {
			return true;
		}

		return false;

	}

	/**
	 * Method to render widget
	 *
	 * @return void
	 */
	public function render() : void {

		if ( ! $this->_should_render() ) {
			return;
		}

		/*
		 * Which device is current device and
		 * whether widget is rendered or not is not our concern here.
		 * We render the widget by hot wiring it.
		 */
		$render_on_mobile = ( PMC::is_mobile() ) ? 'yes' : 'no';

		the_widget(
			'\Rolling_Stone\Inc\Widgets\Ticketing',
			[
				'render_on_mobile' => $render_on_mobile,
			]
		);

	}

}    // end class

//EOF
