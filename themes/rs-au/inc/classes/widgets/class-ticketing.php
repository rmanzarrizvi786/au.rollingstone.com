<?php
/**
 * Rolling Stone ticketing.
 *
 * @package pmc-rollingstone-2018
 * @since 2019-03-25
 */

namespace Rolling_Stone\Inc\Widgets;

use \PMC;
use \PMC\Global_Functions\Traits\Widgets\Per_Post_Toggle;

/**
 * Ticketing widget
 */
class Ticketing extends \WP_Widget {

	use Per_Post_Toggle;

	const ID = 'rs_ticketing';

	/**
	 * Class constructor
	 *
	 * @throws \ErrorException
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {

		parent::__construct(
			self::ID,
			__( 'RS Ticketing', 'pmc-rollingstone' ),
			[
				'classname'   => 'ticketings-section-widget',
				'description' => __( 'Displays concert listings based on user region.', 'pmc-rollingstone' ),
			]
		);

		$this->_setup_post_option();

	}

	/**
	 * Method to set up post option for the widget
	 *
	 * @return void
	 *
	 * @throws \ErrorException
	 *
	 * Ignoring code coverage here as the methods called in this method
	 * have their own unit tests and unit tests for this method are not needed.
	 *
	 * @codeCoverageIgnore
	 */
	protected function _setup_post_option() : void {

		$this->_set_post_option_values(
			'rs-disable-ticketing-widget',
			__( 'Disable Vivid Seats Widget', 'pmc-rollingstone' ),
			__( 'Posts with this term will not display RS Ticketing widget on post page.', 'pmc-rollingstone' )
		);

		$this->_init_post_options();

	}

	/**
	 * Method to determine if current instance of the widget should be rendered
	 * on current device or not.
	 *
	 * @param array $instance
	 *
	 * @return bool
	 */
	protected function _should_render_on_current_device( array $instance ) : bool {

		/*
		 * If widget is disabled for current post then do not render it
		 */
		if ( $this->current_post_has_option() ) {
			return false;
		}

		$instance['render_on_mobile'] = ( ! empty( $instance['render_on_mobile'] ) ) ? $instance['render_on_mobile'] : 'no';

		/*
		 * If current device is mobile and current instance setting is for mobile
		 * then it should be rendered.
		 */
		if ( PMC::is_mobile() && 'yes' === $instance['render_on_mobile'] ) {
			return true;
		}

		/*
		 * If current device is not mobile and current instance setting is not for mobile
		 * then it should be rendered.
		 */
		if ( ! PMC::is_mobile() && 'yes' !== $instance['render_on_mobile'] ) {
			return true;
		}

		return false;

	}

	/**
	 * Method to render widget settings UI in wp-admin
	 *
	 * @param array $instance
	 *
	 * @return void
	 *
	 * @throws \Exception
	 */
	public function form( $instance ) {

		$instance['render_on_mobile'] = ( ! empty( $instance['render_on_mobile'] ) ) ? $instance['render_on_mobile'] : 'no';

		PMC::render_template(
			CHILD_THEME_PATH . '/template-parts/widgets/ticketing/settings-form.php',
			[
				'instance' => $instance,
				'widget'   => $this,
			],
			true
		);

	}

	/**
	 * Method to return values to be saved as widget settings
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = [];

		$allowed_values = [
			'yes',
			'no',
		];

		$new_instance['render_on_mobile'] = ( ! empty( $new_instance['render_on_mobile'] ) ) ? sanitize_title( $new_instance['render_on_mobile'] ) : 'no';
		$instance['render_on_mobile']     = ( in_array( $new_instance['render_on_mobile'], (array) $allowed_values, true ) ) ? strtolower( $new_instance['render_on_mobile'] ) : 'no';

		return $instance;

	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 * @throws \Exception
	 */
	public function widget( $args, $instance ) {

		// Check if current instance can be rendered on current device or not.
		if ( ! $this->_should_render_on_current_device( $instance ) ) {
			return;
		}

		PMC::render_template(
			CHILD_THEME_PATH . '/template-parts/widgets/ticketing/front-end.php',
			[
				'args' => $args,
			],
			true
		);

	}

}

// EOF
