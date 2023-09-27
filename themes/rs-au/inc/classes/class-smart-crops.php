<?php
/**
 * Smart Crops
 *
 * Fastly-based smart crops functionality.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Class Smart_Crops
 *
 * @since 2018.1.0
 * @see \PMC\Global_Functions\Traits\Singleton
 */
class Smart_Crops {

	use Singleton;

	const NONCE_ACTION = 'rs-smartcrop-nonce';

	const NONCE_FIELD = 'security';

	/**
	 * Class constructor.
	 *
	 * Initializes the theme assets.
	 *
	 * @since 2018.1.0
	 */
	protected function __construct() {

		// Admin-only hooks
		if ( is_admin() ) {

			// Load admin JS file.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10, 1 );

			// Add AJAX handlers.
			add_action( 'wp_ajax_toggle_smart_crop', array( $this, 'toggle_smart_crop' ) );
			add_action( 'wp_ajax_set_focal_point', array( $this, 'set_focal_point' ) );
			add_action( 'wp_ajax_reset_smart_crops', array( $this, 'reset_smart_crops' ) );

			// Add a new field to the edit attachment screen
			add_filter( 'attachment_fields_to_edit', array( $this, 'modify_attachment_fields' ), 51, 2 );
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * Enqueue custom admin scripts.
	 *
	 * @since 2018.1.0
	 *
	 * @param $hook string The current admin page
	 * @return void
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'upload.php' === $hook || 'post.php' === $hook || 'post-new.php' === $hook ) {
			wp_enqueue_script( 'smart-crops', RS_THEME_URL . '/inc/classes/js/smart-crops.js', array( 'jquery' ), RS_THEME_VERSION, true );
			wp_enqueue_style( 'smart-crops', RS_THEME_URL . '/inc/classes/css/smart-crops.css', array(), RS_THEME_VERSION, 'all' );
		}
	}

	/**
	 * Modify attachment fields.
	 *
	 * @since 2018.1.0
	 * @filter attachment_fields_to_edit
	 *
	 * @param $form_fields array Existing fields.
	 * @param $attachment object The attachment currently being edited.
	 * @return array Form fields, either unmodified on error or new field added on success.
	 */
	public function modify_attachment_fields( $form_fields, $attachment ) {
		if ( ! wp_attachment_is_image( $attachment->ID ) ) {
			return $form_fields;
		}

		$form_fields['pmc_smart_crops'] = array(
			'label' => __( 'Smart Crops', 'pmc-rollingstone' ),
			'input' => 'html',
			'html'  => $this->get_attachment_field_html( $attachment ),
		);

		return $form_fields;
	}


	/**
	 * Generate the HTML for the edit attachment field.
	 *
	 * @param $attachment object The attachment currently being edited.
	 * @return string The HTML for the form field.
	 */
	public function get_attachment_field_html( $attachment ) {
		global $_wp_additional_image_sizes;
		$sizes = $_wp_additional_image_sizes;
		$image = wp_get_attachment_image_src( $attachment->ID, 'full' );

		if ( empty( $image ) ) {
			return '<p>' . __( 'No attachment image was found.', 'pmc-rollingstone' ) . '</p>';
		}

		// List image properties.
		list( $src, $width, $height ) = $image;

		// Get the image metadata.
		$metadata = wp_get_attachment_metadata( $attachment->ID );

		$crop_parameters = '';
		if ( isset( $metadata['focal_point_offset_left'] ) && isset( $metadata['focal_point_offset_top'] ) ) {
			$offset_x        = round( $metadata['focal_point_offset_left'] / $width * 100 );
			$offset_y        = round( $metadata['focal_point_offset_top'] / $height * 100 );
			$crop_parameters = sprintf( ',offset-x%d,offset-y%d', $offset_x, $offset_y );
		} elseif ( isset( $metadata['enable_smart_crop'] ) && 1 === $metadata['enable_smart_crop'] ) {
			$crop_parameters = ',smart';
		}
		$smart_crops_state = ( '' !== $crop_parameters ) ? 'has-cropping-parameters' : '';

		// Set the URL structure.
		$src = str_replace( ' ', '%20', $src );

		// Generate the HTML output.
		$html  = '<p class="hide-if-js">' . __( 'You need to enable Javascript to use this functionality.', 'pmc-rollingstone' ) . '</p>';
		$html .= '<input type="button" class="hide-if-no-js button" data-show-thumbnails value="' . __( 'Open Cropping Tools', 'pmc-rollingstone' ) . '" />';
		$html .= '<input type="button" class="hide-if-no-js button hidden" data-use-smart-crop value="' . __( 'Use Smart Crop', 'pmc-rollingstone' ) . '" />';
		$html .= '<input type="button" class="hide-if-no-js button hidden" data-select-focal-point value="' . __( 'Select Focal Point', 'pmc-rollingstone' ) . '" />';
		$html .= '<input type="button" class="hide-if-no-js button hidden" data-reset-smart-crops value="' . __( 'Reset Cropping', 'pmc-rollingstone' ) . '" />';
		$html .= '<span class="spinner"></span>';
		$html .= sprintf(
			'<ul id="%s" class="smart-crop-thumbs-container hidden %s" data-attachment-id="%d" data-attachment-src="%s">',
			esc_attr( 'wpcom-thumbs-' . $attachment->ID ),
			esc_attr( $smart_crops_state ),
			esc_attr( $attachment->ID ),
			esc_url( $src )
		);

		// key wont really matter if its not using a dimension map
		foreach ( $sizes as $image_name => $size ) {
			$thumbnail_url = sprintf( '%1$s?width=%2$d&crop=%2$d:%3$d%4$s', $src, $size['width'], $size['height'], $crop_parameters );

			$html .= '<li>';
			$html .= '<strong>' . esc_html( $image_name ) . '</strong><br />';
			$html .= '<img src="' . esc_url( $thumbnail_url ) . '" alt="' . esc_attr( $image_name ) . '" />';
			$html .= '</li>';
		}

		$html .= '</ul>';
		$html .= '<input type="hidden" id="smart-crops-nonce" name="' . esc_attr( self::NONCE_FIELD ) . '" value="' . esc_attr( wp_create_nonce( self::NONCE_ACTION ) ) . '">';

		return $html;
	}

	/**
	 * Toggle smart crop feature.
	 *
	 * @since 2018.1.0
	 * @action wp_ajax_toggle_smart_crop
	 *
	 * @return void Echo return value.
	 */
	public function toggle_smart_crop() {
		$this->validate();

		// Get the attachment ID and validate the smart crop.
		$id    = $this->get_attachment_id();
		$state = $this->validate_ajax_smart_crop_state();

		// Update attachment metadata.
		$metadata                      = wp_get_attachment_metadata( $id );
		$metadata['enable_smart_crop'] = $state;

		unset( $metadata['focal_point_offset_left'] );
		unset( $metadata['focal_point_offset_top'] );

		$return_value = wp_update_attachment_metadata( $id, $metadata );

		// Send the result of action back to the view.
		wp_die( esc_html( strval( $return_value ) ) );
	}

	/**
	 * Set focal point.
	 *
	 * @since 2018.1.0
	 * @action wp_ajax_set_focal_point
	 *
	 * @return void Echo return value.
	 */
	public function set_focal_point() {
		$this->validate();

		// Get the attachment ID and validate the focal point offset.
		$id                      = $this->get_attachment_id();
		$focal_point_offset_left = $this->validate_ajax_focal_point_offset( $id, 'left' );
		$focal_point_offset_top  = $this->validate_ajax_focal_point_offset( $id, 'top' );

		// Update attachment metadata.
		$metadata                            = wp_get_attachment_metadata( $id );
		$metadata['focal_point_offset_left'] = $focal_point_offset_left;
		$metadata['focal_point_offset_top']  = $focal_point_offset_top;

		unset( $metadata['enable_smart_crop'] );

		$return_value = wp_update_attachment_metadata( $id, $metadata );

		// Send the result of action back to the view.
		wp_die( esc_html( strval( $return_value ) ) );
	}

	/**
	 * Reset smart crops.
	 *
	 * @since 2018.1.0
	 * @action wp_ajax_reset_smart_crops
	 *
	 * @return void Echo return value.
	 */
	public function reset_smart_crops() {
		$this->validate();

		// Get the attachment ID.
		$id = $this->get_attachment_id();

		// Update attachment metadata.
		$metadata = wp_get_attachment_metadata( $id );

		unset( $metadata['focal_point_offset_left'] );
		unset( $metadata['focal_point_offset_top'] );
		unset( $metadata['enable_smart_crop'] );

		$return_value = wp_update_attachment_metadata( $id, $metadata );

		// Send the result of action back to the view.
		wp_die( esc_html( strval( $return_value ) ) );
	}

	/**
	 * Validate the request.
	 */
	public function validate() {
		// Nonce check.
		if ( ! check_ajax_referer( self::NONCE_ACTION, self::NONCE_FIELD ) ) {
			wp_die( esc_html__( 'Invalid request.', 'pmc-rollingstone' ) );
		}

		$attachment_id = \PMC::filter_input( INPUT_POST, 'attachment_id', FILTER_SANITIZE_NUMBER_INT );

		// Data check.
		if ( empty( $attachment_id ) ) {
			wp_die( esc_html__( 'Invalid <code>attachment_id</code> parameter.', 'pmc-rollingstone' ) );
		}

		$attachment = get_post( $attachment_id );

		// Permissions check.
		if ( ! current_user_can( get_post_type_object( $attachment->post_type )->cap->edit_post, $attachment->ID ) ) {
			wp_die( esc_html__( 'You are not allowed to edit this attachment.', 'pmc-rollingstone' ) );
		}
	}

	/**
	 * Validate attachment ID AJAX parameter.
	 *
	 * Makes sure that the "attachment_id" (attachment ID) parameter is valid and dies if it's not.
	 * Returns the parameter value on success.
	 *
	 * @return null|int Dies on error, returns parameter value on success.
	 */
	public function get_attachment_id() {
		$attachment_id = \PMC::filter_input( INPUT_POST, 'attachment_id', FILTER_SANITIZE_NUMBER_INT );

		if ( ! empty( $attachment_id ) ) {
			$attachment = get_post( $attachment_id );
		}

		if ( empty( $attachment ) ) {
			wp_die( esc_html__( 'The attachment post was not found.', 'pmc-rollingstone' ) );
		}

		if ( 'attachment' !== $attachment->post_type || ! wp_attachment_is_image( $attachment->ID ) ) {
			wp_die( esc_html__( 'That is not a valid image attachment.', 'pmc-rollingstone' ) );
		}

		return $attachment->ID;
	}

	/**
	 * Validate smart crop state AJAX parameter.
	 *
	 * Makes sure "smart_crop_state" post parameter is valid and dies if it's not.
	 * Returns the parameter value on success.
	 *
	 * @return null|int Dies on error, returns parameter value on success.
	 */
	public function validate_ajax_smart_crop_state() {
		$state = \PMC::filter_input( INPUT_POST, 'smart_crop_state', FILTER_VALIDATE_BOOLEAN );

		if ( empty( $state ) ) {
			wp_die( esc_html__( 'Smart crop state parameter is missing.', 'pmc-rollingstone' ) );
		}

		return $state;
	}

	/**
	 * Validate focal point offset AJAX parameter.
	 *
	 * Makes sure "focal_point_offset_left" or "focal_point_offset_top" post parameters are valid
	 * and dies if they are not. Returns the parameter value on success.
	 *
	 * @param $attachment_id int Attachment ID.
	 * @param $direction string Offset direction being validated.
	 * @return null|int Dies on error, returns parameter value on success.
	 */
	public function validate_ajax_focal_point_offset( $attachment_id, $direction ) {
		$param_name        = 'focal_point_offset_' . $direction;
		$param_value       = \PMC::filter_input( INPUT_POST, $param_name, FILTER_SANITIZE_NUMBER_INT );
		$offset_percentage = ! empty( $param_value ) || 0 === $param_value ? $param_value : -1;

		if ( 0 > $offset_percentage || 100 < $offset_percentage ) {
			wp_die( esc_html(
				sprintf(
					// translators: The placeholder is replaced by the direction of the offset.
					__( 'Focal point offset %s parameter is incorrect.', 'pmc-rollingstone' ),
					$direction
				)
			) );
		}

		$metadata   = wp_get_attachment_metadata( $attachment_id );
		$max_offset = 'left' === $direction ? $metadata['width'] : $metadata['height'];
		$offset     = round( $offset_percentage * $max_offset / 100 );

		return $offset;
	}
}
