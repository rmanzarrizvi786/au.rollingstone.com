<?php
/**
 * Handle mapping/filtering of image meta with attachment fields.
 *
 * @ticket CDWE-196
 *
 * @version 2017-02-15 Chandra Patel <chandrakumar.patel@rtcamp.com>
 */

use \PMC\Global_Functions\Traits\Singleton;

/**
 * This class map image meta data with attachment fields and filter image caption.
 */
class PMC_Image_Metadata {

	use Singleton;

	const OPTION_NAME = 'pmc_map_image_iptc_metadata';

	/**
	 * Register hooks
	 */
	protected function __construct() {

		add_filter( 'pmc_global_cheezcap_options', array( $this, 'filter_pmc_global_cheezcap_options' ), 20 );
		add_filter( 'wp_generate_attachment_metadata', array( $this, 'map_image_meta' ), 10, 2 );
		add_filter( 'wp_read_image_metadata', array( $this, 'filter_image_meta' ) );

	}

	/**
	 * Add CheezCap options to enable/disable `Map REX/Shutterstock IPTC Metadata` feature.
	 *
	 * @param array $cheezcap_options CheezCap options.
	 *
	 * @return array $cheezcap_options CheezCap options.
	 */
	public function filter_pmc_global_cheezcap_options( $cheezcap_options = array() ) {

		if ( empty( $cheezcap_options ) || ! is_array( $cheezcap_options ) ) {
			$cheezcap_options = array();
		}

		$cheezcap_options[] = new CheezCapDropdownOption(
			esc_html__( 'Map REX/Shutterstock IPTC Metadata', 'pmc-plugins' ),
			esc_html__( 'By enabling this option Title, Caption, Alt Text and Photo Credit are populated by the image\'s embedded IPTC Metadata on upload of the image per REX/Shutterstock conventions.', 'pmc-plugins' ),
			self::OPTION_NAME,
			array( 'disabled', 'enabled' ),
			0, // Default option => Disabled.
			array( esc_attr__( 'Disabled', 'pmc-plugins' ), esc_attr__( 'Enabled', 'pmc-plugins' ) )
		);

		return $cheezcap_options;
	}

	/**
	 * Add image credit, alt text to attachments on upload.
	 *
	 * @version 2017-08-31 CDWE-605 Get image credit from XMP meta for PNG image format
	 *
	 * @param array $metadata       An array of attachment meta data.
	 * @param int   $attachment_id  Current attachment ID.
	 *
	 * @return array $metadata Untouched metadata.
	 */
	public function map_image_meta( $metadata, $attachment_id ) {

		if ( 'enabled' !== PMC_Cheezcap::get_instance()->get_option( self::OPTION_NAME ) ) {
			return $metadata;
		}


		if ( empty( get_post_meta( $attachment_id, '_image_credit', true ) ) ) {

			if ( ! empty( $metadata['image_meta']['credit'] ) ) {

				$image_credit = $metadata['image_meta']['credit'];

			} elseif ( get_post_mime_type( $attachment_id ) === 'image/png' ) {

				$image_meta = $this->_get_xmp_metadata( wp_get_attachment_url( $attachment_id ) );

				if ( ! empty( $image_meta ) && is_array( $image_meta ) ) {

					if ( ! empty( $image_meta['creator'] ) && is_array( $image_meta['creator'] ) ) {

						$image_credit = $image_meta['creator'][0];

					} elseif ( ! empty( $image_meta['creator'] ) && is_string( $image_meta['creator'] ) ) {

						$image_credit = $image_meta['creator'];

					} elseif ( ! empty( $image_meta['credit'] ) ) {

						$image_credit = $image_meta['credit'];

					}

				}

			}

			// Save image credit.
			if ( ! empty( $image_credit ) ) {
				update_post_meta( $attachment_id, '_image_credit', $image_credit );
			}

		}

		// Save image alt text.
		if ( ! empty( $metadata['image_meta']['caption'] ) && empty( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ) {
			update_post_meta( $attachment_id, '_wp_attachment_image_alt', $metadata['image_meta']['caption'] );
		}

		return $metadata;

	}

	/**
	 * Remove the first line from image caption if string start with `Mandatory Credit:`
	 * and image id found at end of the string.
	 *
	 * Example: `Mandatory Credit: Photo by Rob Latour/REX/Shutterstock (8137126bh)`
	 * where (8137126bh) is the image id.
	 *
	 * @param array $meta Image meta data.
	 *
	 * @return array $meta Image meta data.
	 */
	public function filter_image_meta( $meta ) {

		if ( 'enabled' !== PMC_Cheezcap::get_instance()->get_option( self::OPTION_NAME ) || empty( $meta['caption'] ) ) {
			return $meta;
		}

		$lines = preg_split( '/\r\n|\r|\n/', $meta['caption'] );

		if ( ! empty( $lines[0] ) ) {
			$pattern = '/^Mandatory Credit:.+\(\d+[a-z]{1,2}\)$/';

			preg_match( $pattern, $lines[0], $matches );

			if ( ! empty( $matches ) ) {
				unset( $lines[0] );
				$meta['caption'] = implode( "\n", $lines );
			}
		}

		return $meta;

	}

	/**
	 * Read XMP metadata from image.
	 *
	 * @link https://surniaulula.com/2013/04/09/read-adobe-xmp-xml-in-php/
	 *
	 * @version 2017-08-31 CDWE-605
	 *
	 * @param string $image_url Image URL.
	 *
	 * @return bool|array
	 */
	protected function _get_xmp_metadata( $image_url ) {

		if ( empty( $image_url ) ) {
			return false;
		}

		if ( false === filter_var( $image_url, FILTER_VALIDATE_URL ) ) {
			return false;
		}

		$response = vip_safe_wp_remote_get( $image_url, '', 3, 3 );

		if ( empty( $response ) || is_wp_error( $response ) || 200 !== intval( wp_remote_retrieve_response_code( $response ) ) ) {
			return false;
		}

		$content = wp_remote_retrieve_body( $response );

		$xmp_data_start = strpos( $content, '<x:xmpmeta' );

		$xmp_data_end   = strpos( $content, '</x:xmpmeta>' );

		if ( false === $xmp_data_start || false === $xmp_data_end ) {
			return false;
		}

		$xmp_length = $xmp_data_end - $xmp_data_start;

		$xmp_data   = substr( $content, $xmp_data_start, $xmp_length + 12 );

		if ( empty( $xmp_data ) ) {
			return false;
		}

		$image_meta = array();

		// Only 'credit' and 'creator' meta needed. We can get other meta if we need.
		$xmp_fields = array(
			'credit'   => '<rdf:Description[^>]+?photoshop:Credit="([^"]*)"',
			'creator'  => '<dc:creator>\s*<rdf:Seq>\s*(.*?)\s*<\/rdf:Seq>\s*<\/dc:creator>',
		);

		foreach ( $xmp_fields as $key => $regex ) {

			// get a single text string.
			$image_meta[ $key ] = preg_match( "/$regex/is", $xmp_data, $match ) ? $match[1] : '';

			// if string contains a list, then re-assign the variable as an array with the list elements.
			$image_meta[ $key ] = ( preg_match_all( '/<rdf:li[^>]*>([^>]*)<\/rdf:li>/is', $image_meta[ $key ], $match ) ) ? $match[1] : $image_meta[ $key ];

		}

		return $image_meta;

	}

}

PMC_Image_Metadata::get_instance();
