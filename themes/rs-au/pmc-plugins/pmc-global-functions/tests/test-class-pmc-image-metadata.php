<?php
namespace PMC\Global_Functions\Tests;

use PMC\Unit_Test\Utility;
use PMC_Image_Metadata;
use PMC_Cheezcap;

/**
 * @group pmc-global-functions
 *
 * Unit tests for class PMC_Image_Metadata
 *
 * Author: Chandra Patel <chandrakumar.patel@rtcamp.com>
 *
 * @requires PHP 5.6
 * @coversDefaultClass PMC_Image_Metadata
 * Class Test_PMC_Image_Metadata
 */
class Test_PMC_Image_Metadata extends Base {

	protected $_pmc_image_metadata;

	function setUp() {

		// to speed up unit test, we bypass files scanning on upload folder.
		self::$ignore_files = true;

		parent::setUp();

		$this->_pmc_image_metadata = PMC_Image_Metadata::get_instance();

	}

	/**
	 * To prevent all upload files from deletion, since set $ignore_files = true
	 * we override the function and do nothing here.
	 */
	function remove_added_uploads() {}

	/**
	 * Tests whether hooks registered or not.
	 *
	 * @covers ::__construct()
	 */
	public function test_actions_and_filters() {

		$this->assertEquals( 20, has_filter( 'pmc_global_cheezcap_options', array( $this->_pmc_image_metadata, 'filter_pmc_global_cheezcap_options' ) ) );
		$this->assertEquals( 10, has_filter( 'wp_generate_attachment_metadata', array( $this->_pmc_image_metadata, 'map_image_meta' ) ) );
		$this->assertEquals( 10, has_filter( 'wp_read_image_metadata', array( $this->_pmc_image_metadata, 'filter_image_meta' ) ) );

	}

	/**
	 * @covers ::map_image_meta()
	 */
	public function test_map_image_meta() {

		$metadata = array();

		$attachment_image = __DIR__ . '/assets/dummy-attachment-with-iptc-metadata.jpg';

		$option_name = PMC_Image_Metadata::OPTION_NAME;

		PMC_Cheezcap::get_instance()->register();

		$attachment_id = $this->factory->attachment->create_object(
			array(
				'file' => $attachment_image,
				'post_mime_type' => 'image/jpeg',
				'post_title'     => 'test image iptc metadata',
			)
		);

		// Fetch additional metadata from EXIF/IPTC.
		$metadata['image_meta'] = wp_read_image_metadata( $attachment_image );

		// Test after disable Map REX/Shutterstock IPTC Metadata option.
		$GLOBALS['cap']->$option_name = 'disabled';

		// Add image credit, alt text to attachment.
		$metadata = $this->_pmc_image_metadata->map_image_meta( $metadata, $attachment_id );

		$this->assertEmpty( get_post_meta( $attachment_id, '_image_credit', true ) );

		$this->assertEmpty( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) );

		// Now, test after enable Map REX/Shutterstock IPTC Metadata option.
		$GLOBALS['cap']->$option_name = 'enabled';

		// Add image credit, alt text to attachment.
		$metadata = $this->_pmc_image_metadata->map_image_meta( $metadata, $attachment_id );

		$this->assertEquals(
			$metadata['image_meta']['credit'],
			get_post_meta( $attachment_id, '_image_credit', true ),
			'Image credit meta not mapped correctly with attachment field.'
		);

		$this->assertEquals(
			$metadata['image_meta']['caption'],
			get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
			'Image caption meta not mapped correctly with attachment field.'
		);

	}

	/**
	 * @covers ::filter_image_meta()
	 */
	public function test_filter_image_meta() {

		$metadata = array();

		PMC_Cheezcap::get_instance()->register();

		$option_name = PMC_Image_Metadata::OPTION_NAME;

		// Test after enable Map REX/Shutterstock IPTC Metadata option.
		$GLOBALS['cap']->$option_name = 'enabled';

		// Fetch additional metadata from EXIF/IPTC.
		$metadata['caption'] = "Mandatory Credit: Photo by Chelsea Lauren/Variety/REX/Shutterstock (8137137dr)\nEmma Stone and Brie Larson\nThe 23rd Annual Screen Actors Guild Awards, Backstage, Los Angeles, USA - 29 Jan 2017";

		$filtered_metadata = $this->_pmc_image_metadata->filter_image_meta( $metadata );

		$this->assertNotContains(
			'Mandatory Credit: Photo by Chelsea Lauren/Variety/REX/Shutterstock (8137137dr)',
			$filtered_metadata['caption']
		);

		// Test after disable Map REX/Shutterstock IPTC Metadata option.
		$GLOBALS['cap']->$option_name = 'disabled';

		$filtered_metadata = $this->_pmc_image_metadata->filter_image_meta( $metadata );

		$this->assertContains(
			'Mandatory Credit: Photo by Chelsea Lauren/Variety/REX/Shutterstock (8137137dr)',
			$filtered_metadata['caption']
		);

	}

	/**
	 * @covers ::_get_xmp_metadata()
	 * @group pmc-phpunit-ignore-failed
	 */
	public function test_get_xmp_metadata() {

		// Test with image which contain XMP meta.
		$attachment_image = __DIR__ . '/assets/dummy-image-with-xmp-metadata.png';

		$attachment_id = $this->factory->attachment->create_upload_object( $attachment_image );

		$image_meta = Utility::invoke_hidden_method(
			$this->_pmc_image_metadata,
			'_get_xmp_metadata',
			array( wp_get_attachment_url( $attachment_id ) )
		);

		$this->assertTrue( is_array( $image_meta ), 'An array should return with image metadata.' );

		$this->assertEquals( 'Photo Credit', $image_meta['credit'], 'Credit meta info does not fetch from image.' );

		$this->assertEquals( 'Photo Creator', $image_meta['creator'][0], 'Creator meta info does not fetch from image.' );

		wp_delete_attachment( $attachment_id );

		// Test with image which doesn't contain XMP meta.
		$attachment_image = __DIR__ . '/assets/dummy-image-without-xmp-metadata.png';

		$attachment_id = $this->factory->attachment->create_upload_object( $attachment_image );

		$image_meta = Utility::invoke_hidden_method(
			$this->_pmc_image_metadata,
			'_get_xmp_metadata',
			array( wp_get_attachment_url( $attachment_id ) )
		);

		$this->assertEmpty( $image_meta );

		wp_delete_attachment( $attachment_id );

	}

	/**
	 * @covers ::map_image_meta()
	 * @group pmc-phpunit-ignore-failed
	 */
	public function test_map_image_meta_for_png_image() {

		$option_name = PMC_Image_Metadata::OPTION_NAME;

		PMC_Cheezcap::get_instance()->register();

		// Test after disable Map REX/Shutterstock IPTC Metadata option.
		$GLOBALS['cap']->$option_name = 'enabled';

		// Test with image which contain XMP meta.
		$attachment_image = __DIR__ . '/assets/dummy-image-with-xmp-metadata.png';

		$attachment_id = $this->factory->attachment->create_upload_object( $attachment_image );

		$this->assertEquals(
			'Photo Creator',
			get_post_meta( $attachment_id, '_image_credit', true ),
			'Image credit meta not mapped correctly with attachment field.'
		);

		wp_delete_attachment( $attachment_id );

		// Test with image which doesn't contain XMP meta.
		$attachment_image = __DIR__ . '/assets/dummy-image-without-xmp-metadata.png';

		$attachment_id = $this->factory->attachment->create_upload_object( $attachment_image );

		$this->assertEmpty( get_post_meta( $attachment_id, '_image_credit', true ) );

		wp_delete_attachment( $attachment_id );

	}

}
