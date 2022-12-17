<?php
/**
 * Class to add Pro Publica pixel post option
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-07-01
 */

namespace Rolling_Stone\Inc\Options;

use \PMC\Global_Functions\Traits\Singleton;
use \PMC\Post_Options\API;

class Pro_Publica_Pixel {

	use Singleton;

	const ID   = 'pro-publica-pixel';
	const SLUG = 'enable-propublica-pixel';
	const NAME = 'Enable ProPublica Pixel';

	/**
	 * @var \PMC\Post_Options\API
	 */
	protected $_post_options;

	/**
	 * Class constructor
	 *
	 * @codeCoverageIgnore
	 */
	protected function __construct() {

		$this->_post_options = API::get_instance();

		$this->_setup_hooks();

	}

	/**
	 * Method to set up listeners to hooks
	 *
	 * @return void
	 *
	 * @codeCoverageIgnore
	 */
	protected function _setup_hooks() : void {

		/*
		 * Actions
		 */
		add_action( 'init', [ $this, 'register' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_stuff' ] );

	}

	/**
	 * Method to register option under global options
	 *
	 * @return void
	 */
	public function register() : void {

		$this->_post_options->register_global_options(
			[
				self::SLUG => [
					'label'       => self::NAME,
					'description' => 'Posts with this term will load ProPublica tracking pixel.',
				],
			]
		);

	}

	/**
	 * Method to enqueue assets to frontend
	 *
	 * @return void
	 */
	public function enqueue_stuff() : void {

		if ( ! is_singular() ) {
			return;
		}

		$current_post = get_post();

		if ( empty( $current_post ) || ! is_a( $current_post, \WP_Post::class ) ) {
			return;
		}

		if ( ! $this->_post_options->post( $current_post )->has_option( self::SLUG ) ) {
			return;
		}

		wp_enqueue_script(
			sprintf( 'pmc-async-%s-js', self::ID ),
			'https://pixel.propublica.org/pixel.js',
			[],
			false,
			true
		);

	}

}    //end class

//EOF
