<?php
/**
 * Plugin Name: PMC Google Tag Manager
 * Description: Adds script and  settings field for GTM
 * Author: Adaeze Esiobu|PMC
 * Version: 1.0.0.0
 * License: PMC Proprietary.  All rights reserved.
 */
// wpcom_vip_load_plugin( 'pmc-global-functions', 'pmc-plugins' );

class PMC_Google_Tagmanager { // extends PMC_Singleton{

	// protected function _init() {
	public function __construct() {

		add_action( 'pmc-tags-top',array( $this, 'render_gtm_script' ) );

		add_action( 'admin_init', array( $this, 'add_tagmanager_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_datalayer_js_object' ), 1 ); // do NOT change this priority
		add_action( 'pmc_enqueue_scripts_using_pmc_page_meta', array( $this, 'add_datalayer_js_values' ) ); // do NOT change the hook

	}

	/**
	 * register settings and add settings field
	 */
	public function add_tagmanager_settings(){
		register_setting( 'general', 'pmc_google_tag_manager_account', array($this, 'validate_account_id') );

		add_settings_field(
			'pmc_google_tag_manager_account',
			'Google Tag Manager account',
			array($this, 'gtm_settings_field'),
			'general',
			'default'
		);
	}

	/**
	 * Add the dataLayer object to the top of the <head> tag (or as close as this hook allows)
	 *
	 * This must run before the `pmc_enqueue_scripts_using_pmc_page_meta` hook when we add information from PMC_Page_Meta
	 *
	 * @since 2015-07-01 Corey Gilmore
	 *
	 * @see PMC_Page_Meta
	 * @uses action::wp_enqueue_scripts:1
	 *
	 * @version 2015-07-01 Corey Gilmore PPT-5136
	 *
	 */
	public function add_datalayer_js_object() {
	?>
	<script type="text/javascript">
		// dataLayer = window.dataLayer || [];
		window.dataLayer = window.dataLayer || [];
		<?php
		if ( is_single() ) :
			if ( get_field( 'author' ) ) {
				$author = get_field( 'author' );
			} else if ( get_field( 'Author' ) ) {
				$author =get_field( 'Author' );
			} else {
				if ( '' != get_the_author_meta( 'first_name', $post->post_author ) && '' != get_the_author_meta( 'last_name', $post->post_author ) ) {
					$author = get_the_author_meta( 'first_name', $post->post_author ) . ' ' . get_the_author_meta( 'last_name', $post->post_author );
				} else {
					$author = get_the_author_meta( 'display_name', $post->post_author );
				}
			}

			$categories = get_the_category(get_the_ID());
			$CategoryCD = '';
			if ( $categories ) :
				foreach( $categories as $category ) :
					$CategoryCD .= $category->slug . ' ';
				endforeach; // For Each Category
			endif; // If there are categories for the post

			$tags = get_the_tags(get_the_ID());
			$TagsCD = '';
			if ( $tags ) :
					foreach( $tags as $tag ) :
							$TagsCD .= $tag->slug . ' ';
					endforeach; // For Each Tag
			endif; // If there are tags for the post
		?>
		window.dataLayer.push({
				'AuthorCD': '<?php echo $author; ?>',
				'CategoryCD': '<?php echo $CategoryCD; ?>',
				'TagsCD': '<?php echo $TagsCD; ?>',
				'PubdateCD': '<?php echo get_the_time('M d, Y', get_the_ID() ); ?>'
		});
		<?php endif; ?>
	</script>
	<?php
	}

	/**
	 * Push meta information from the `pmc_meta` JS object into into the GTM `dataLayer` object.
	 *
	 * This must fire *after* we add pmc_meta to the HEAD, which is why we use the `pmc_enqueue_scripts_using_pmc_page_meta` action
	 *
	 * @since 2015-07-01 Corey Gilmore
	 *
	 * @see PMC_Page_Meta
	 * @uses action::pmc_enqueue_scripts_using_pmc_page_meta
	 *
	 * @version 2015-07-01 Corey Gilmore PPT-5136
	 *
	 */
	public function add_datalayer_js_values() {
		?>
		<script type="text/javascript">
		if( window.hasOwnProperty( 'pmc_meta' ) ) {
			if( !window.hasOwnProperty( 'dataLayer' ) || !window.dataLayer.hasOwnProperty('push') ) {
				window.dataLayer = [];
			}
			window.dataLayer.push( pmc_meta );
		}
		</script>

		<?php
	}

	/**
	 * render settings field
	 */
	public function gtm_settings_field(){
		echo '<input id="pmc_google_tag_manager_account" name="pmc_google_tag_manager_account" type="text" value="' . esc_attr( get_option('pmc_google_tag_manager_account') ) . '" />';

	}

	/**
	 * @param $account_id
	 * @return mixed|null|string|void
	 * Validation for google tag account ID. ID must be in format GTM-XXXX
	 */
	public function validate_account_id( $account_id ){

		if ( empty( $account_id ) ) {
			return null;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			add_settings_error( 'pmc_google_tag_manager_account', 'pmc_google_tag_manager_account', __( 'Only administrators can change the Google Tag Manager account.' ), 'error' );

			return get_option('pmc_google_tag_manager_account');
		}

		if ( ! preg_match( '/^GTM-[a-z\d]{4,9}$/i', $account_id ) ) {
			add_settings_error( 'pmc_google_tag_manager_account', 'pmc_google_tag_manager_account', __( 'Google Tag Manager account must be in the format: GTM-XXXXX' ), 'error' );

			return get_option('pmc_google_tag_manager_account');
		}


		$account_id = strtoupper( $account_id );

		return $account_id;

	}


	/**
	 * check if GTM is activated. if it is, then add GTM script.
	 */
	public function render_gtm_script(){

		if( $this->is_gtm_active() ){

 			$this->output_universal_analytics_code();
		}

	}

	/**
	 * @return bool
	 * checks the  option to see if GTM is activated. and empty option means
	 * GTM is disabled
	 */
	public function is_gtm_active(){

		$gtm_id = get_option('pmc_google_tag_manager_account');

		$active = ! empty( $gtm_id );

		return $active;
	}

	/**
	 * render GTM script
	 */
	public function output_universal_analytics_code(){
		$gtm_id = get_option('pmc_google_tag_manager_account');

		?>
	<!-- Google Tag Manager -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_js( $gtm_id ); ?>"
					  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','<?php echo esc_js( $gtm_id ); ?>');</script>
	<!-- End Google Tag Manager -->

	<?php

	}
}

// PMC_Google_Tagmanager::get_instance();
new PMC_Google_Tagmanager();
