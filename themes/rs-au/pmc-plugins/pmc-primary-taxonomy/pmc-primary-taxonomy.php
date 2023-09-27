<?php

// wpcom_vip_load_plugin( 'pmc-global-functions', 'pmc-plugins' );

class PMC_Primary_Taxonomy { // extends PMC_Singleton {

	private $_taxonomy_array = array(
			'category' => 'Category',
		);
	private $_post_array     = array( 'post' );
	const SLUG_PREFIX        = "primary_";
	const CACHE_GROUP        = 'pmc_primary_taxonomy';

	protected function _init() {
		add_action( 'custom_metadata_manager_init_metadata', array( $this, 'primary_metabox' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ) );
	}

	public function enqueue_scripts_styles() {

		global $pagenow;

		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {
			return;
		}

		wp_enqueue_script( "pmc-primary-taxonomy", rtrim( plugins_url( '', __FILE__ ), "/" ) . "/js/admin-script.js" );

	}

	function primary_metabox() {

		$args = array(
			'taxonomy'  => $this->_taxonomy_array,
			'post_type' => $this->_post_array
		);

		$args = apply_filters( 'pmc_primary_taxonomy_settings', $args );

		if ( empty( $args['taxonomy'] ) || empty( $args['post_type'] ) ) {
			return;
		}

		$this->_taxonomy_array = $args['taxonomy'];
		$this->_post_array     = array_unique( $args['post_type'] );

		$grp_slug = "";
		foreach ( $this->_taxonomy_array as $slug => $name ) {

			if ( is_numeric( $slug ) ) {
				$slug = strtolower( $name );
				$name = ucfirst( $name );
			}

			$grp_args = array(
				'label'   => 'Primary ' . $name,
				'context' => 'side',
			);

			$grp_slug        = self::SLUG_PREFIX . "{$slug}_div";
			$meta_field_slug = self::SLUG_PREFIX . $slug;

			x_add_metadata_group( $grp_slug, $this->_post_array, $grp_args );

			x_add_metadata_field(
				$meta_field_slug, $this->_post_array, array(
					'group'      => $grp_slug,
					'field_type' => 'select',
					'values'     => array(),
					'label'      => "Primary {$name}",
				)
			);

		}

		x_add_metadata_field(
			'_pmc_taxonomy_scripts', $this->_post_array, array(
				'group'            => $grp_slug,
				'display_callback' => array( $this, 'primary_tax_meta_box' ),
				// this function is defined below
				'label'            => '',
			)
		);

	}


	function primary_tax_meta_box() {

		global $post;

		$meta_box_id_array = array();
		$selected_array = array();

		foreach ( $this->_taxonomy_array as $slug => $name ) {

			$div_id = self::SLUG_PREFIX . $slug;
			if ( $selected_term_id = get_post_meta( $post->ID, $this->meta_key( $slug ), true ) ) {
				$selected_term_id = apply_filters( 'pmc_primary_taxonomy_meta_key', $selected_term_id, $post->ID, $slug );
			}
			$meta_box_id_array[] = array( "{$div_id}", "{$slug}", $selected_term_id );
			$selected_array[$slug] = $selected_term_id;

		}
		?>

		<script type="text/javascript">
			var pmc_primary_taxonomy_data = <?php echo json_encode($meta_box_id_array); ?>;
			var pmc_primary_selected_arr = <?php echo json_encode($selected_array); ?>;
		</script>

	<?php
	}

	public function meta_key ( $taxonomy ) {
		return self::SLUG_PREFIX . $taxonomy;
	}

	public function get_primary_taxonomy( $post, $taxonomy ) {

		if ( empty( $taxonomy ) || ! ( $post = get_post( $post ) ) ) {
			return false;
		}

		// we need the cache key base on post ID & modified
		$cache_key   = md5( self::SLUG_PREFIX . $taxonomy . $post->ID . $post->post_modified );
		$post_id = $post->ID;
		unset( $post );

		// no caching if we're in wp admin
		if ( !is_admin() ) {
			if ( $term = wp_cache_get( $cache_key, self::CACHE_GROUP ) ) {
				return $term;
			}
		}

		$term = false;

		if ( $term_id = get_post_meta( $post_id, $this->meta_key( $taxonomy ), true ) ) {
			$term_id = apply_filters( 'pmc_primary_taxonomy_meta_key', $term_id, $post_id, $taxonomy );
			$term = get_term_by( 'id', $term_id, $taxonomy );
			if ( !empty( $term) && ( is_wp_error( $term ) || !has_term( $term->term_id, $taxonomy, $post_id ) ) ) {
				$term = false;
			}
		}

		if ( empty( $term ) ) {
			$terms = get_the_terms( $post_id, $taxonomy );
			if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
				$term = reset( $terms );
			}
		}

		if ( !empty( $term ) ) {
			$term_link = get_term_link( $term, $taxonomy );
			if ( ! empty( $term_link ) && ! is_wp_error( $term_link ) ) {
				$term->link = $term_link;
			}
		}

		// we don't want to set any cache in wp admin
		if ( !is_admin() ) {
			wp_cache_set( $cache_key, $term, self::CACHE_GROUP, 60 );
		}

		return $term;
	}

	/**
	 * @ticket PPT-4753 - WP 4.2 Split Taxonomy Terms
	 * Check based on term_id if it has a new id generated after split and return the new value
	 *
	 * @param int $old_term_id ID of the formerly shared term.
	 * @param string $taxonomy Taxonomy for the split term.
	 */
	public function get_split_term_id( $old_term_id, $taxonomy ){

		$new_term_id = wp_get_split_term( $old_term_id, $taxonomy );
		if ( $new_term_id ) {
			return $new_term_id;
		}
		return $old_term_id;
	}

}

new PMC_Primary_Taxonomy();
//EOF
