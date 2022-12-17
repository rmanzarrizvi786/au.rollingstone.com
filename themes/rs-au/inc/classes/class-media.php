<?php
/**
 * Media
 *
 * Media related functionality.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

namespace Rolling_Stone\Inc;

use PMC\Global_Functions\Traits\Singleton;

/**
 * Class Media
 *
 * @since 2018.1.0
 * @see \PMC\Global_Functions\Traits\Singleton
 */
class Media {

	// use Singleton;

	/**
	 * The wp_kses_allowed_html context for attachment image.
	 */
	const ATTACHMENT_KSES_CONTEXT = 'rollingstone_attachment_image';

	/**
	 * Class constructor.
	 *
	 * Initializes the theme assets.
	 *
	 * @since 2018.1.0
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {
		$this->_setup_hooks();
	}


	/**
	 * Method to set up hooks with listeners
	 *
	 * @return void
	 */
	protected function _setup_hooks() : void {

		/*
		 * Actions
		 */
		add_action( 'after_setup_theme', array( $this, 'add_image_sizes' ) );
		add_action( 'embed_oembed_html', array( $this, 'wrap_youtube' ), 10, 2 );

		/*
		 * Filters
		 */
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'calculate_image_sources' ), 1, 3 );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'filter_attachment_image_attributes' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'fix_image_url_query_string' ), 99, 1 );
		add_filter( 'wp_kses_allowed_html', array( $this, 'filter_attachment_image_kses' ), 10, 2 );
		add_filter( 'kses_allowed_protocols', array( $this, 'allow_data_protocol' ) );
		add_filter( 'the_content', array( $this, 'lazyload_images_in_content' ) );
		add_filter( 'pmc_feature_video_post_types', array( $this, 'enable_videos_for_list_and_items' ) );
		add_filter( 'pmc_gallery_link_post_types', array( $this, 'enable_feature_for_list' ) );
		add_filter( 'rollingstone_override_image_crop_parameters', [ $this, 'maybe_override_crop_parameters' ], 10, 3 );

	}

	/**
	 * This method kind of replicates what add_query_arg() does but with the
	 * caveat that it does not run querystring values through urlencode()
	 * because it is used to create image URL values for srcsets which get mangled
	 * when run through urlencode()
	 *
	 * @param string $key
	 * @param string $value
	 * @param string $url
	 *
	 * @return string
	 */
	protected function _add_query_string_var( string $key, string $value, string $url ) : string {

		if ( empty( $key ) || empty( $value ) || empty( $url ) ) {
			return $url;
		}

		if ( false === strpos( $url, '?' ) ) {
			$url .= '?';
		}

		$last_char = substr( $url, -1 );

		if ( '?' !== $last_char ) {
			$url .= '&';
		}

		$url .= sprintf( '%s=%s', $key, $value );

		return $url;

	}

	/**
	 * Add Image Sizes.
	 *
	 * @since 2018.1.0
	 * @action init
	 */
	public function add_image_sizes() {

		add_image_size( 'ratio-11x14', 275, 350, true );
		add_image_size( 'ratio-5x6', 725, 870, true );
		add_image_size( 'ratio-1x1', 1240, 1240, true );
		add_image_size( 'ratio-4x3', 940, 705, true );
		add_image_size( 'ratio-3x2', 900, 600, true );
		add_image_size( 'ratio-7x4', 1260, 720, true );
		add_image_size( 'ratio-16x9-list-item', 1260, 709, false );
		add_image_size( 'ratio-2x1', 1400, 700, true );
		add_image_size( 'ratio-4x5', 600, 746, true );
		add_image_size( 'ratio-video', 815, 458, true );

	}

	/**
	 * Wrap YouTube embeds with a div for display.
	 *
	 * @param string $html The embed html.
	 * @param string $url The embed url.
	 * @return string
	 */
	public function wrap_youtube( $html, $url ) {
		if ( false !== strpos( $url, 'youtu' ) ) {
			return '<div class="video-embed">' . $html . '</div>';
		}

		return $html;
	}

	/**
	 * Applies lazyload-related attributes to images.
	 *
	 * @param array $attr Image attributes.
	 * @return array The filtered image attributes.
	 */
	public function filter_attachment_image_attributes( $attr ) {

		if ( is_admin() || ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) ) {
			return $attr;
		}

		// Don't lazyload if the image has the `critical` CSS class.
		if ( isset( $attr['class'] ) && in_array( 'critical', (array) explode( ' ', $attr['class'] ), true ) ) {
			return $attr;
		}

		if ( is_singular( 'pmc-gallery' ) ) {
			return $attr;
		}

		$modified_attributes = [
			'src' => 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==',
		];

		foreach ( $attr as $key => $value ) {
			if ( in_array( $key, [ 'src', 'srcset' ], true ) ) {
				$modified_attributes[ "data-$key" ] = $value;
			} else {
				$modified_attributes[ $key ] = $value;
			}
		}

		return $modified_attributes;
	}

	/**
	 * Method to override crop parameters for an image if hard cropping is not enabled on image size.
	 * Hooked on 'rollingstone_override_image_crop_parameters'
	 *
	 * @param string $src
	 * @param string $size
	 * @param string $og_src
	 *
	 * @return string
	 */
	public function maybe_override_crop_parameters( string $src, string $size, string $og_src ) : string {

		global $_wp_additional_image_sizes;

		if ( empty( $size ) || empty( $_wp_additional_image_sizes[ $size ] ) || empty( $og_src ) ) {
			return $src;
		}

		$image_size = $_wp_additional_image_sizes[ $size ];

		if ( ! isset( $image_size['crop'] ) || false === $image_size['crop'] ) {
			return remove_query_arg( 'resize', $src );
		}

		return $src;

	}

	/**
	 * Calculate image src and srcset attributes.
	 *
	 * @param array $attr Image attributes.
	 * @return array The filtered image attributes.
	 */
	public function calculate_image_sources( $attr, $attachment, $size ) {
		global $_wp_additional_image_sizes;

		// Normalize `srcset` early so that no array to string conversion ever happens.
		if ( ! empty( $attr['srcset'] ) ) {
			$base_srcset = $attr['srcset'];
			unset( $attr['srcset'] );

			if ( ! empty( $attr['sizes'] ) ) {
				$base_sizes = $attr['sizes'];
				unset( $attr['sizes'] );
			}
		}

		// Get the original image details.
		$image = wp_get_attachment_image_src( $attachment->ID, 'full' );
		if ( ! $image ) {
			return $attr;
		}
		list( $src, $width, $height ) = $image;

		if ( ! is_string( $size ) || ! isset( $_wp_additional_image_sizes[ $size ] ) ) {
			return $attr;
		}

		// Get the thumbnail dimensions.
		$dimensions = $_wp_additional_image_sizes[ $size ];

		// Get the image metadata and crop parameters
		$metadata        = wp_get_attachment_metadata( $attachment->ID );
		$crop_parameters = '';
		if ( isset( $metadata['focal_point_offset_left'] ) && isset( $metadata['focal_point_offset_top'] ) ) {
			$offset_x        = round( $metadata['focal_point_offset_left'] / $width * 100 );
			$offset_y        = round( $metadata['focal_point_offset_top'] / $height * 100 );
			$crop_parameters = sprintf( ',offset-x%d,offset-y%d', $offset_x, $offset_y );
		} elseif ( isset( $metadata['enable_smart_crop'] ) && 1 === $metadata['enable_smart_crop'] ) {
			$crop_parameters = ',smart';
		}

		// Set the URL structure.
		$src    = str_replace( ' ', '%20', $src );
		$og_src = $src;
		$src    = $this->_add_query_string_var(
			'resize',
			sprintf( '%d,%d%s', $dimensions['width'], $dimensions['height'], $crop_parameters ),
			$src
		);
		$src    = apply_filters( 'rollingstone_override_image_crop_parameters', $src, $size, $og_src, $dimensions, $crop_parameters );

		$default_width = $dimensions['width'];

		// Construct srcset attribute.
		if ( ! empty( $base_srcset ) ) {
			if ( is_array( $base_srcset ) ) {
				$srcset        = [];
				$default_width = $base_srcset[0];
				foreach ( $base_srcset as $width ) {
					$srcset[] = $this->_add_query_string_var(
						'w',
						sprintf( '%1$d %1$dw', $width ),
						$src
					);
				}

				$srcset         = implode( ', ', $srcset );
				$attr['srcset'] = $srcset;
			} else {
				$attr['srcset'] = $base_srcset;
			}

			if ( ! empty( $base_sizes ) ) {
				$attr['sizes'] = $base_sizes;
			}
		}

		// Set the source attribute.
		$attr['src'] = $this->_add_query_string_var( 'w', $default_width, $src );

		return $attr;
	}

	public function fix_image_url_query_string( $html ) {
		return preg_replace_callback(
			'/(?<=src="|srcset=")[^"]*/mi', function( $matches ) {
				return str_replace( '&amp;', '&', $matches[0] );
			}, $html
		);
	}

	/**
	 * Filters kses for attachment images.
	 *
	 * @param array $attr Allowed tags and attributes.
	 * @param string|array $context The context for which to retrieve tags.
	 * @return array The filtered tags and attributes.
	 */
	public function filter_attachment_image_kses( $attr, $context ) {
		if ( self::ATTACHMENT_KSES_CONTEXT !== $context ) {
			return $attr;
		}

		return array(
			'img' => array(
				'aria-describedby' => 1,
				'alt'              => 1,
				'class'            => 1,
				'data-src'         => 1,
				'data-srcset'      => 1,
				'height'           => 1,
				'id'               => 1,
				'sizes'            => 1,
				'src'              => 1,
				'srcset'           => 1,
				'title'            => 1,
				'width'            => 1,
			),
		);
	}

	/**
	 * Adds 'data' to the list of allowed protocols.
	 *
	 * @param array $protocols Protocols allowed in wp_kses.
	 * @return array The modified protocols.
	 */
	public function allow_data_protocol( $protocols ) {
		$protocols[] = 'data';

		return $protocols;
	}

	/**
	 * Replaces images in post content with lazyload-ready versions.
	 * This is necessary because the filters applying lazyload attributes are not applied in the editor.
	 *
	 * @param string $content Post content.
	 * @return string Filtered content.
	 */
	public function lazyload_images_in_content( $content ) {

		global $post;

		if (
			is_admin() || empty( $post ) || empty( $content )
			|| false === strpos( $content, '<img' )
			|| ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) ) {
			return $content;
		}

		$attachments = get_attached_media( 'image', $post->ID );

		return preg_replace_callback(
			'/<img.*\/>/',
			function( $matches ) use ( $attachments ) {

				$match = $matches[0];

				foreach ( array_keys( (array) $attachments ) as $attachment_id ) {

					// Search for the attachment ID in the image tag.
					if ( false === strpos( $match, "wp-image-{$attachment_id}" ) ) {
						continue;
					}

					try {

						// Parse the image tag to make its attributes available.
						$xml_element = new \SimpleXMLElement( $match );

						// Try to find the image size from the WP-generated size-{$size} CSS class.
						preg_match( '/size-(?P<size>.+)("| )/', (string) $xml_element->attributes()->class, $size_match );

						// If the ID matches but a size wasn't found, give up and return the original image tag.
						if ( ! isset( $size_match['size'] ) ) {
							return $match;
						}

						// Gather other attributes set via the post editor.
						$attributes = array_reduce(
							[ 'width', 'height', 'style', 'id', 'aria-describedby', 'class' ],
							function( $_attributes, $attribute ) use ( $xml_element ) {

								if ( null !== $xml_element->attributes()->$attribute ) {
									$_attributes[ $attribute ] = (string) $xml_element->attributes()->$attribute;
								}

								return $_attributes;

							},
							[]
						);

						// Return the image with filters applied.
						return rollingstone_get_attachment_image( $attachment_id, $size_match['size'], false, $attributes );

					} catch ( \Exception $e ) {
						return $match;
					}

				}

				// Return the original tag unchanged.
				return $match;

			},
			$content
		);

	}

	/**
	 * Adds the pmc list post type to an array of post types.
	 *
	 * @param array $post_types
	 * @return array
	 */
	public function enable_feature_for_list( $post_types ) {

		if ( ! is_array( $post_types ) ) {
			$post_types = array();
		}

		if ( in_array( 'pmc_list', (array) $post_types, true ) ) {
			return $post_types;
		}

		$post_types[] = 'pmc_list';

		return $post_types;
	}

	/**
	 * Adds the pmc_list and pmc_list_item post types to an array of post types.
	 *
	 * @param array $post_types
	 * @return array
	 */
	public function enable_videos_for_list_and_items( $post_types ) {

		if ( ! is_array( $post_types ) ) {
			$post_types = array();
		}

		if ( ! in_array( 'pmc_list', (array) $post_types, true ) ) {
			$post_types[] = 'pmc_list';
		}

		if ( ! in_array( 'pmc_list_item', (array) $post_types, true ) ) {
			$post_types[] = 'pmc_list_item';
		}

		return $post_types;
	}

}    //end class

new Media();

//EOF
