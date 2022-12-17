<?php
/**
 * Head
 *
 * Functionality related to content in the HTML document head.
 *
 * @package pmc-rollingstone-2018
 * @since   2018-05-31
 */

namespace Rolling_Stone\Inc;

use PMC\Global_Functions\Traits\Singleton;

/**
 * Class Head.
 */
class Head {

	use Singleton;

	/**
	 * Class constructor.
	 * @codeCoverageIgnore
	 * Sets up actions and filters.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * setup actions and filters
	 */
	public function setup_hooks() {
		add_filter( 'jetpack_open_graph_tags', array( $this, 'override_open_graph_title_tag' ) );
		add_action( 'pmc_tags_head', array( $this, 'action_pmc_tags_head' ) );
	}

	/**
	 * Checks for a title override. If exist, set it as the open graph tags.
	 *
	 * @param array $tags An associative array of open graph tags.
	 *
	 * @return array The filtered array of open graph tags.
	 */
	public function override_open_graph_title_tag( $tags ) {

		if ( ! is_single() || ! function_exists( 'pmc_get_title' ) ) {
			return $tags;
		}

		// pmc_get_title falls back to the current post title if the override meta value isn't found.
		$tags['og:title'] = pmc_get_title();

		return $tags;

	}

	/**
	 * Add site specific tags in the head of every page.
	 */
	public function action_pmc_tags_head() {

		\PMC::render_template( CHILD_THEME_PATH . '/template-parts/header/tags-head.php', [], true );

	}

}
