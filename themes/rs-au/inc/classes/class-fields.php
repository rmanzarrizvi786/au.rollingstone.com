<?php
/**
 * Rolling Stone Fields
 *
 * Class for adding fields.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-10
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Class Fields.
 */
class Fields {

	use Singleton;

	/**
	 * Fields constructor.
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {

		$fields = \PMC\Core\Inc\Fieldmanager\Fields::get_instance();

		// Replace the category and tag selector with the custom Relationships implementation.
		add_action( 'fm_post_pmc_list', [ $fields, 'fields_relationships' ] );
		add_action( 'fm_post_pmc_top_video', [ $fields, 'fields_relationships' ] );

		add_filter( 'pmc_core_relationship_taxonomies', [ $this, 'filter_relationship_taxonomies_list' ] );

		add_action( 'fm_post_guest-author', [ $this, 'guest_author_seo_options' ] );

	}

	/**
	 * Filter the relationship tabs taxonomies list.
	 *
	 * @param array $taxonomies Specify taxonomies list for relationship tabs.
	 *
	 * @return array Updated taxonomies list for relationship tabs.
	 */
	public function filter_relationship_taxonomies_list( $taxonomies ) {

		if ( is_array( $taxonomies ) ) {
			$taxonomies = array_diff( $taxonomies, [ 'post_tag' ] );
		}

		return $taxonomies;
	}

	/**
	 * Guest Author (CAP) Fieldmanager fields.
	 */
	public function guest_author_seo_options() {
		$fm = new \Fieldmanager_Group(
			[
				'name'           => 'authors_seo_options',
				'serialize_data' => false,
				'add_to_prefix'  => false,
				'children'       => [
					'_pmc_disallow_seo_indexing' => new \Fieldmanager_Checkbox(
						[
							'label'       => __( "Don't Allow Indexing", 'pmc-rollingstone' ),
							'description' => __( 'Search engines will not be able to index the author page if this option is checked.', 'pmc-rollingstone' ),
						]
					),
				],
			]
		);

		return $fm->add_meta_box( __( 'SEO', 'pmc-rollingstone' ), [ 'guest-author' ], 'side' );

	}

}
