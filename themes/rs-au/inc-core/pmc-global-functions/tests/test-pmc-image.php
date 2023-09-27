<?php
namespace PMC\Global_Functions\Tests;

/**
 * @group pmc-global-functions
 * PHPUnit tests for the PMC\Image namespace
 *
 * @requires PHP 5.3
 */
class Tests_PMC_Image extends Base {

	public $dummy_attachment_id = null;

	public $portrait_image_size_name   = 'portrait';
	public $portrait_image_size_width  = 600;
	public $portrait_image_size_height = 1024;
	public $portrait_image_size_crop   = 1;

	public $landscape_image_size_name   = 'landscape';
	public $landscape_image_size_width  = 1024;
	public $landscape_image_size_height = 600;
	public $landscape_image_size_crop   = 1;

	public $square_image_size_name   = 'square';
	public $square_image_size_width  = 600;
	public $square_image_size_height = 600;
	public $square_image_size_crop   = 1;

	public function setUp() {
		// to speed up unit test, we bypass files scanning on upload folder
		self::$ignore_files = true;

		parent::setUp();

		add_image_size(
			$this->portrait_image_size_name,
			$this->portrait_image_size_width,
			$this->portrait_image_size_height,
			$this->portrait_image_size_crop
		);

		add_image_size(
			$this->landscape_image_size_name,
			$this->landscape_image_size_width,
			$this->landscape_image_size_height,
			$this->landscape_image_size_crop
		);

		add_image_size(
			$this->square_image_size_name,
			$this->square_image_size_width,
			$this->square_image_size_height,
			$this->square_image_size_crop
		);

		$this->dummy_attachment_id = $this->factory->attachment->create_upload_object(
		    __DIR__ . '/assets/dummy-attachment-2048x1349.jpg', 0
		);

		remove_all_filters( 'intermediate_image_sizes' );
	}

	public function remove_added_uploads() {
		// To prevent all upload files from deletion, since set $ignore_files = true
		// we override the function and do nothing here
	}

	/**
	 * @covers \PMC\Image\image_size_names_choose
	 */
	public function test_image_size_names_choose() {
		$this->assertSame( 9999, has_filter( 'image_size_names_choose', '\PMC\Image\image_size_names_choose' ) );

		add_filter( 'image_size_names_choose', function( $images ) {
			return array();
		});

		$images = apply_filters( 'image_size_names_choose', array() );
		$this->assertSame( 4, count( $images ) ); // Should have 4 standard WP sizes despite filter above.

	}

	/**
	 * @covers \PMC\Image\attachment_fields_to_edit
	 */
	public function test_attachment_fields_to_edit() {
		add_filter( 'pmc_image_size_warning', function( $images = array() ) {
			$images[] = array(
				'title' => 'Featured Image',
				'width' => 3000,
				'height' => 3000,
			);

			return $images;
		});

		$attachment_id = $this->dummy_attachment_id;
		$attachment = get_post( $attachment_id );
		$form_fields = apply_filters( 'attachment_fields_to_edit', array(), $attachment );
		$this->assertArrayHasKey( 'pmc_featured-image_dimension_alert', $form_fields );
	}

	/**
	 * @covers \PMC\Image\get_intermediate_image_sizes
	 */
	public function test_getting_all_image_size_names() {
		$sizes = \PMC\Image\get_intermediate_image_sizes();
		$this->assertContains( $this->portrait_image_size_name, $sizes );
	}

	/**
	 * @covers \PMC\Image\get_image_sizes
	 */
	public function test_getting_data_about_all_image_sizes() {
		$sizes = \PMC\Image\get_image_sizes();

		$this->assertArrayHasKey( $this->portrait_image_size_name, $sizes );

		$this->assertArrayHasKey( 'width',  $sizes[ $this->portrait_image_size_name ] );
		$this->assertArrayHasKey( 'height', $sizes[ $this->portrait_image_size_name ] );
		$this->assertArrayHasKey( 'crop',   $sizes[ $this->portrait_image_size_name ] );

		$this->assertEquals(
			$this->portrait_image_size_width,
			$sizes[ $this->portrait_image_size_name ]['width']
		);

		$this->assertEquals(
			$this->portrait_image_size_height,
			$sizes[ $this->portrait_image_size_name ]['height']
		);

		$this->assertEquals(
			$this->portrait_image_size_crop,
			$sizes[ $this->portrait_image_size_name ]['crop']
		);
	}

	/**
	 * @covers \PMC\Image\get_image_size
	 */
	public function test_getting_data_about_a_specific_image_size() {
		$size = \PMC\Image\get_image_size( $this->portrait_image_size_name );

		$this->assertArrayHasKey( 'width',  $size );
		$this->assertArrayHasKey( 'height', $size );
		$this->assertArrayHasKey( 'crop',   $size );

		$this->assertEquals( $this->portrait_image_size_width,  $size['width'] );
		$this->assertEquals( $this->portrait_image_size_height, $size['height'] );
		$this->assertEquals( $this->portrait_image_size_crop,   $size['crop'] );
	}

	/**
	 * @covers \PMC\Image\get_image_size_max_possible_crop
	 */
	public function test_getting_failure_of_max_possible_crop_for_a_specific_image_size() {
		$max_crop = \PMC\Image\get_image_size_max_possible_crop( 0, 'blah' );
		$this->assertFalse( $max_crop );

		$max_crop = \PMC\Image\get_image_size_max_possible_crop( 1234, '' );
		$this->assertFalse( $max_crop );

		$max_crop = \PMC\Image\get_image_size_max_possible_crop( 0, '' );
		$this->assertFalse( $max_crop );
	}

	/**
	 * Test getting the max crop for a portrait image size
	 *
	 * @covers \PMC\Image\get_image_size_max_possible_crop
	 */
	public function test_getting_max_possible_crop_for_a_portrait_image() {

		// I've manually done the math to validate these tests using
		// the width/height of our uploaded image which is 2048x1349.

		// Portrait Max Crop
		// Image size is 600x1024. Because the size is a portrait
		// we scale it to 1349 tall which proportionally scales the width to 790.
		// Therefore, $portrait_max_crop should = [ 790, 1349 ]
		$portrait_max_crop = \PMC\Image\get_image_size_max_possible_crop(
			$this->dummy_attachment_id,
			$this->portrait_image_size_name
		);

		$this->assertCount( 2, $portrait_max_crop );
		$this->assertEquals( $portrait_max_crop[0], 790 );
		$this->assertEquals( $portrait_max_crop[1], 1349 );
	}

	/**
	 * Test getting the max crop for a landscape image size
	 *
	 * @covers \PMC\Image\get_image_size_max_possible_crop
	 */
	public function test_getting_max_possible_crop_for_a_landscape_image() {

		// I've manually done the math to validate these tests using
		// the width/height of our uploaded image which is 2048x1349.

		// Landscape Max Crop
		// Image size is 1024x600. Because the size is a landscape we scale
		// it to 2048px wide which proportionally scales the height to 1200.
		// Therefore, $landscape_max_crop should = [ 2048, 1200 ]
		$landscape_max_crop = \PMC\Image\get_image_size_max_possible_crop(
			$this->dummy_attachment_id,
			$this->landscape_image_size_name
		);

		$this->assertCount( 2, $landscape_max_crop );
		$this->assertEquals( $landscape_max_crop[0], 2048 );
		$this->assertEquals( $landscape_max_crop[1], 1200 );
	}

	/**
	 * Test getting the max crop for a square image size
	 *
	 * @covers \PMC\Image\get_image_size_max_possible_crop
	 */
	public function test_getting_max_possible_crop_for_a_square_image() {

		// I've manually done the math to validate these tests using
		// the width/height of our uploaded image which is 2048x1349.

		// Square Max Crop
		// Image size is 600x1024. Because the size is a square we scale it
		// to 1349 tall which proportionally scales the width to 1349 as well.
		// Therefore, $square_max_crop should = [ 1349, 1349 ]
		$square_max_crop = \PMC\Image\get_image_size_max_possible_crop(
			$this->dummy_attachment_id,
			$this->square_image_size_name
		);

		$this->assertCount( 2, $square_max_crop );
		$this->assertEquals( $square_max_crop[0], 1349 );
		$this->assertEquals( $square_max_crop[1], 1349 );
	}

	/**
	 * Portrait crop coordinates
	 *
	* @covers \PMC\Image\get_pixel_coordinates_for_named_crop
	* @depends test_getting_max_possible_crop_for_a_portrait_image
	*/
	public function test_getting_portrait_named_crop_in_pixels() {

		// I've manually done the math to validate these tests using
		// the width/height of our uploaded image which is 2048x1349
		// and the portrait crop size of 600x1024 which has a max crop
		// of 790x1349. E.g. for a center/top crop, to calculate the
		// center x coordinate we do 2048 - 790 = 1258 / 2 = 629, and
		// to calculate the top y coordinate we do 1349 - 1349 = 0.

		// There are 9 possible crop combinations
		$possible_crops = [
			[
				'x' => 'left',
				'y' => 'top',
				'expected' => [ 0, 0 ],
			],
			[
				'x' => 'left',
				'y' => 'center',
				'expected' => [ 0, 0 ],
			],
			[
				'x' => 'left',
				'y' => 'bottom',
				'expected' => [ 0, 0 ],
			],
			[
				'x' => 'center',
				'y' => 'top',
				'expected' => [ 629, 0 ],
			],
			[
				'x' => 'center',
				'y' => 'center',
				'expected' => [ 629, 0 ],
			],
			[
				'x' => 'center',
				'y' => 'bottom',
				'expected' => [ 629, 0 ],
			],
			[
				'x' => 'right',
				'y' => 'top',
				'expected' => [ 1258, 0 ],
			],
			[
				'x' => 'right',
				'y' => 'center',
				'expected' => [ 1258, 0 ],
			],
			[
				'x' => 'right',
				'y' => 'bottom',
				'expected' => [ 1258, 0 ],
			]
		];

		$portrait_max_crop = \PMC\Image\get_image_size_max_possible_crop(
			$this->dummy_attachment_id,
			$this->portrait_image_size_name
		);

		foreach ( $possible_crops as $crop_details ) {

			$crop_coordinates = \PMC\Image\get_pixel_coordinates_for_named_crop(
				$this->dummy_attachment_id,
				[ $crop_details['x'], $crop_details['y'] ],
				$portrait_max_crop
			);

			$this->assertCount( 2, $crop_coordinates );
			$this->assertEquals( $crop_coordinates[0], $crop_details['expected'][0] );
			$this->assertEquals( $crop_coordinates[1], $crop_details['expected'][1] );
		}
	}

	/**
	 * Landscape crop coordinates
	 *
	* @covers \PMC\Image\get_pixel_coordinates_for_named_crop
	* @depends test_getting_max_possible_crop_for_a_landscape_image
	*/
	public function test_getting_landscape_named_crop_in_pixels() {

		// I've manually done the math to validate these tests using
		// the width/height of our uploaded image which is 2048x1349
		// and the landscape crop size of 1024x600 which has a max crop
		// of 2048x1200. E.g. for a center/top crop, to calculate the
		// center x coordinate we do 2048 - 2048 = 0 / 2 = 0, and
		// to calculate the top y coordinate we do 1349 - 1200 = 149.

		// There are 9 possible crop combinations
		$possible_crops = [
			[
				'x' => 'left',
				'y' => 'top',
				'expected' => [ 0, 0 ],
			],
			[
				'x' => 'left',
				'y' => 'center',
				'expected' => [ 0, 74 ],
			],
			[
				'x' => 'left',
				'y' => 'bottom',
				'expected' => [ 0, 149 ],
			],
			[
				'x' => 'center',
				'y' => 'top',
				'expected' => [ 0, 0 ],
			],
			[
				'x' => 'center',
				'y' => 'center',
				'expected' => [ 0, 74 ],
			],
			[
				'x' => 'center',
				'y' => 'bottom',
				'expected' => [ 0, 149 ],
			],
			[
				'x' => 'right',
				'y' => 'top',
				'expected' => [ 0, 0 ],
			],
			[
				'x' => 'right',
				'y' => 'center',
				'expected' => [ 0, 74 ],
			],
			[
				'x' => 'right',
				'y' => 'bottom',
				'expected' => [ 0, 149 ],
			]
		];

		$landscape_max_crop = \PMC\Image\get_image_size_max_possible_crop(
			$this->dummy_attachment_id,
			$this->landscape_image_size_name
		);

		foreach ( $possible_crops as $crop_details ) {

			$crop_coordinates = \PMC\Image\get_pixel_coordinates_for_named_crop(
				$this->dummy_attachment_id,
				[ $crop_details['x'], $crop_details['y'] ],
				$landscape_max_crop
			);

			$this->assertCount( 2, $crop_coordinates );
			$this->assertEquals( $crop_coordinates[0], $crop_details['expected'][0] );
			$this->assertEquals( $crop_coordinates[1], $crop_details['expected'][1] );
		}
	}

	/**
	 * Ideally the following test would be duplicated to
	 * also cover landscape and portrait crops..
	 *
	* @covers \PMC\Image\get_pixel_coordinates_for_named_crop
	* @depends test_getting_max_possible_crop_for_a_square_image
	*/
	public function test_getting_square_named_crop_in_pixels() {

		// I've manually done the math to validate these tests using
		// the width/height of our uploaded image which is 2048x1349
		// and the square crop size of 600x600 which has a max crop
		// of 1349x1349. E.g. for a center/top crop, to calculate the
		// center x coordinate we do 2048 - 1349 = 699 / 2 = 349, and
		// to calculate the top y coordinate we do 1349 - 1349 = 0.

		// There are 9 possible crop combinations
		$possible_crops = [
			[
				'x' => 'left',
				'y' => 'top',
				'expected' => [ 0, 0 ],
			],
			[
				'x' => 'left',
				'y' => 'center',
				'expected' => [ 0, 0 ],
			],
			[
				'x' => 'left',
				'y' => 'bottom',
				'expected' => [ 0, 0 ],
			],
			[
				'x' => 'center',
				'y' => 'top',
				'expected' => [ 349, 0 ],
			],
			[
				'x' => 'center',
				'y' => 'center',
				'expected' => [ 349, 0 ],
			],
			[
				'x' => 'center',
				'y' => 'bottom',
				'expected' => [ 349, 0 ],
			],
			[
				'x' => 'right',
				'y' => 'top',
				'expected' => [ 699, 0 ],
			],
			[
				'x' => 'right',
				'y' => 'center',
				'expected' => [ 699, 0 ],
			],
			[
				'x' => 'right',
				'y' => 'bottom',
				'expected' => [ 699, 0 ],
			]
		];

		$square_max_crop = \PMC\Image\get_image_size_max_possible_crop(
			$this->dummy_attachment_id,
			$this->square_image_size_name
		);

		foreach ( $possible_crops as $crop_details ) {

			$crop_coordinates = \PMC\Image\get_pixel_coordinates_for_named_crop(
				$this->dummy_attachment_id,
				[ $crop_details['x'], $crop_details['y'] ],
				$square_max_crop
			);

			$this->assertCount( 2, $crop_coordinates );
			$this->assertEquals( $crop_coordinates[0], $crop_details['expected'][0] );
			$this->assertEquals( $crop_coordinates[1], $crop_details['expected'][1] );
		}
	}
}

//EOF