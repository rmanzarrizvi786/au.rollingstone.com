<?php
/**
 * Class to add Ecomm Disclaimer post option
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-08-26
 */

namespace Rolling_Stone\Inc\Options;

use \PMC\Global_Functions\Traits\Singleton;
use \PMC\Post_Options\API;
use \PMC;

class Ecomm_Disclaimer {

	use Singleton;

	const SLUG = 'rs-add-ecomm-disclaimer';

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

		/*
		 * Filters
		 */

		// delay this, so that it shows up before everything else in content
		add_filter( 'the_content', [ $this, 'maybe_prepend_html' ], 11 );

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
					'label'       => __( 'Add Ecomm Disclaimer', 'pmc-rollingstone' ),
					'description' => __( 'Posts with this term will show Ecomm Disclaimer above content on single post page.', 'pmc-rollingstone' ),
				],
			]
		);

	}

	/**
	 * Method to determine whether to show disclaimer or not
	 *
	 * @return bool
	 */
	protected function _should_show_disclaimer() : bool {

		/*
		 * If its not a single post page, or its a feed
		 * then we don't want to show
		 */
		if ( ! is_singular() || is_feed() ) {
			return false;
		}

		$current_post = get_post();

		// If global post object not available, we don't want to show
		if ( empty( $current_post ) || ! is_a( $current_post, \WP_Post::class ) ) {
			return false;
		}

		// If current post has option, then we want to show
		if ( $this->_post_options->post( $current_post )->has_option( self::SLUG ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Method to prepend disclaimer HTML
	 *
	 * @param string $content
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function maybe_prepend_html( string $content ) : string {

		if ( ! $this->_should_show_disclaimer() ) {
			return $content;
		}

		$disclaimer = PMC::render_template(
			sprintf( '%s/template-parts/options/ecomm-disclaimer.php', untrailingslashit( CHILD_THEME_PATH ) )
		);

		$content = $disclaimer . $content;

		return $content;

	}

}    //end class

//EOF
