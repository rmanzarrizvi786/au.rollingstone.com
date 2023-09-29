<?php
/**
 * Editorial related functionality.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-24
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Taxonomy for Editorial Types.
 */
class Editorial {

	use Singleton;

	/**
	 * Name of the taxonomy.
	 *
	 * @var string
	 */
	public $name = 'editorial';

	/**
	 * Object types for this taxonomy
	 *
	 * @var array
	 */
	public $object_types = [
		'post',
		'pmc-gallery',
		'pmc_top_video',
		'pmc_list',
		'tout',
	];

	public function __construct() {
		add_action( 'init', [ $this, 'create_taxonomy' ] );
	}

	/**
	 * Creates the taxonomy.
	 */
	public function create_taxonomy() {
		register_taxonomy(
			$this->name,
			apply_filters( 'pmc_core_editorial_tax_object_types', $this->object_types ),
			array(
				'label'             => __( 'Editorial', 'pmc-rollingstone' ),
				'labels'            => array(
					'name'               => _x( 'Editorials', 'taxonomy general name', 'pmc-rollingstone' ),
					'singular_name'      => _x( 'Editorial', 'taxonomy singular name', 'pmc-rollingstone' ),
					'add_new_item'       => __( 'Add New Editorial', 'pmc-rollingstone' ),
					'edit_item'          => __( 'Edit Editorial', 'pmc-rollingstone' ),
					'new_item'           => __( 'New Editorial', 'pmc-rollingstone' ),
					'view_item'          => __( 'View Editorial', 'pmc-rollingstone' ),
					'search_items'       => __( 'Search Editorials', 'pmc-rollingstone' ),
					'not_found'          => __( 'No Editorials found.', 'pmc-rollingstone' ),
					'not_found_in_trash' => __( 'No Editorials found in Trash.', 'pmc-rollingstone' ),
					'all_items'          => __( 'Editorials', 'pmc-rollingstone' ),
				),
				'query_var'         => true,
				'show_ui'           => true,
				'hierarchical'      => true,
				'rewrite'           => array(
					'slug'       => 'e',
					'with_front' => false,
				),
				'capabilities'      => array(
					'manage_terms' => 'manage_options',
					'edit_terms'   => 'manage_options',
					'delete_terms' => 'manage_options',
					'assign_terms' => 'edit_posts',
				),
				'show_in_menu'      => true,
				'show_in_nav_menus' => true,
				'show_admin_column' => false,
			)
		);
	}
}
