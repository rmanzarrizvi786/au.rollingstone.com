<?php
/**
 * Class Issues
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-30
 */

namespace Rolling_Stone\Inc;

use PMC\Global_Functions\Traits\Singleton;

/**
 * Class Issues
 */
class Issues {

	use Singleton;

	const OPTION_NAME = 'current_issue';

	/**
	 * Class constructor.
	 */
	protected function __construct() {

		if ( function_exists( 'fm_register_submenu_page' ) ) {
			fm_register_submenu_page(
				self::OPTION_NAME,
				'edit.php?post_type=pmc-pub-issue',
				__( 'Current Issue', 'pmc-rollingstone' ),
				__( 'Current Issue', 'pmc-rollingstone' ),
				'edit_posts'
			);
			add_action( 'fm_submenu_' . self::OPTION_NAME, array( $this, 'add_current_issue_field' ) );
		}

	}

	/**
	 * Adds the field manager for the current issue submenu page.
	 */
	public function add_current_issue_field() {
		$this->fm = new \Fieldmanager_Group(
			[
				'name'     => self::OPTION_NAME,
				'label'    => __( 'Select Current Issue', 'pmc-rollingstone' ),
				'children' => [
					'current_issue_post' => new \Fieldmanager_Autocomplete(
						[
							'label'       => __( 'Search Issues', 'pmc-rollingstone' ),
							'description' => __( 'Begin typing the issue name to search.', 'pmc-rollingstone' ),
							'datasource'  => new \Fieldmanager_Datasource_Post(
								[
									'query_args' => [
										'post_type' => 'pmc-pub-issue',
									],
								]
							),
						]
					),
				],
			]
		);

		$this->fm->activate_submenu_page();
	}

	/**
	 * Provides the ID for the current issue post.
	 */
	public function get_current_issue_id() {

		$issue = get_option( self::OPTION_NAME );

		if ( is_array( $issue ) && isset( $issue['current_issue_post'] ) ) {
			return $issue['current_issue_post'];
		}

	}

}
