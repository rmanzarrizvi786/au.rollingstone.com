<?php
/**
 * Unit tests for \PMC\Global_Functions\Utility\Attachment
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-07-30
 *
 * @package pmc-global-functions
 */

namespace PMC\Global_Functions\Tests\Utility;

use \PMC\Global_Functions\Tests\Base;
use \PMC\Global_Functions\Utility\Attachment;
use \PMC\Unit_Test\Utility;


/**
 * Class Test_Attachment
 *
 * @group pmc-global-functions
 * @group pmc-global-functions-utility-attachment
 *
 * @coversDefaultClass \PMC\Global_Functions\Utility\Attachment
 */
class Test_Attachment extends Base {

	/**
	 * @var \PMC\Global_Functions\Utility\Attachment
	 */
	protected $_instance;

	public function setUp() {

		parent::setUp();

		$this->_instance = Attachment::get_instance();

	}

	/**
	 * Data provider to provide unclean URLs and corresponding clean URLs
	 *
	 * @return array
	 */
	public function data_unclean_urls() : array {

		return [
			[
				'http://example.com/some/test/image.jpg',
				'http://example.com/some/test/image.jpg',
			],
			[
				'http://example.com/some/test/image.jpg?w=500',
				'http://example.com/some/test/image.jpg',
			],
			[
				'http://example.com/some/test/image.jpg?w=500#some-frgmt',
				'http://example.com/some/test/image.jpg',
			],
			[
				'http://example.com/some/test/image.jpg#some-frgmt?w=500',
				'http://example.com/some/test/image.jpg',
			],
			[
				'http://example.com/some/test/image.jpg?w=500#some-frgmt?a=300&b=200',
				'http://example.com/some/test/image.jpg',
			],
			[
				'//example.com/some/test/image.jpg?h=200',
				'//example.com/some/test/image.jpg',
			],
		];

	}

	/**
	 * Data provider to provide URLs and corresponding file names
	 *
	 * @return array
	 */
	public function data_urls_with_expected_file_names() : array {

		return [
			[
				'http://example.com/some/test/image.jpg',
				'image',
			],
			[
				'http://example.com/some/test/image',
				'image',
			],
			[
				'/some/test/image.png',
				'image',
			],
			[
				'http://example.com/some/test/image.jpg?w=500',
				'image',
			],
			[
				'http://example.com/some/test/image.jpg?w=500#some-frgmt',
				'image',
			],
			[
				'http://example.com/some/test/.jpg',
				'',
			],
		];

	}

	/**
	 * Data provider to provide URLs and corresponding search slugs
	 *
	 * @return array
	 */
	public function data_urls_with_expected_search_slugs() : array {

		return [
			[
				'http://example.com/some/test/image.jpg',
				'image',
			],
			[
				'http://example.com/some/test/image',
				'image',
			],
			[
				'/some/test/image.png',
				'image',
			],
			[
				'http://example.com/some/test/image.jpg?w=500',
				'image',
			],
			[
				'http://example.com/some/test/image.jpg?w=500#some-frgmt',
				'image',
			],
			[
				'http://example.com/some/test/.jpg',
				'',
			],
			[
				'http://example.com/some/test/image43.jpg',
				'image',
			],
			[
				'http://example.com/some/test/image-6.jpg',
				'image',
			],
			[
				'http://example.com/some/test/image-43-6.jpg',
				'image-43',
			],
		];

	}

	/**
	 * Data provider to provide invalid URLs and corresponding post IDs
	 *
	 * @return array
	 */
	public function data_invalid_urls() : array {

		return [
			[
				'',
				-1
			],
			[
				' ',
				-1
			],
			[
				'#a',
				-1
			],
			[
				'?a=123',
				-1
			],
			[
				'?a=123#hgt',
				-1
			],
			[
				'#hgt?a=123',
				-1
			],
			[
				'/some/thing/some/image.jpg',
				-1
			],
		];

	}

	/**
	 * Data provider to provide URL protocols
	 *
	 * @return array
	 */
	public function data_get_url_protocols() : array {

		return [
			[ 'http' ],
			[ 'https' ],
		];

	}

	/**
	 * Data provider to provide random integers
	 *
	 * @return array
	 */
	public function data_get_integers() : array {

		return [
			[ 6785 ],
			[ 45679 ],
		];

	}

	/**
	 * @covers ::_get_clean_url
	 */
	public function test__get_clean_url_when_input_is_empty() : void {

		/*
		 * Set up
		 */
		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_get_clean_url',
			[
				'',
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output );

	}

	/**
	 * @covers ::_get_clean_url
	 *
	 * @dataProvider data_unclean_urls
	 */
	public function test__get_clean_url_for_success( string $url_to_test, string $url_expected ) : void {

		/*
		 * Set up
		 */
		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_get_clean_url',
			[
				$url_to_test,
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEquals( $url_expected, $output );

	}

	/**
	 * @covers ::_get_file_name
	 */
	public function test__get_file_name_when_input_is_empty() : void {

		/*
		 * Set up
		 */
		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_get_file_name',
			[
				'',
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output );

	}

	/**
	 * @covers ::_get_file_name
	 *
	 * @dataProvider data_urls_with_expected_file_names
	 */
	public function test__get_file_name_for_success( string $url_to_test, string $file_name_expected ) : void {

		/*
		 * Set up
		 */
		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_get_file_name',
			[
				$url_to_test,
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEquals( $file_name_expected, $output );

	}

	/**
	 * @covers ::_is_url_of_current_domain
	 */
	public function test__is_url_of_current_domain_when_input_is_empty() : void {

		/*
		 * Set up
		 */
		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_is_url_of_current_domain',
			[
				'',
			]
		);

		/*
		 * Assertions
		 */
		$this->assertFalse( $output );

	}

	/**
	 * @covers ::_is_url_of_current_domain
	 */
	public function test__is_url_of_current_domain_when_url_is_external() : void {

		/*
		 * Set up
		 */
		add_filter( 'pmc_is_production_mock_env', '__return_true' );

		$url_to_test = 'http://example.com/some/test/image.jpg';
		$output      = Utility::invoke_hidden_method(
			$this->_instance,
			'_is_url_of_current_domain',
			[
				$url_to_test,
			]
		);

		/*
		 * Assertions
		 */
		$this->assertFalse( $output );

		/*
		 * Clean up
		 */
		remove_filter( 'pmc_is_production_mock_env', '__return_true' );

	}

	/**
	 * @covers ::_is_url_of_current_domain
	 */
	public function test__is_url_of_current_domain_for_success() : void {

		/*
		 * Set up
		 */
		add_filter( 'pmc_is_production_mock_env', '__return_true' );

		$url_to_test = home_url( '/some/test/image.jpg' );
		$output      = Utility::invoke_hidden_method(
			$this->_instance,
			'_is_url_of_current_domain',
			[
				$url_to_test,
			]
		);

		/*
		 * Assertions
		 */
		$this->assertTrue( $output );

		/*
		 * Clean up
		 */
		remove_filter( 'pmc_is_production_mock_env', '__return_true' );

	}

	/**
	 * @covers ::_get_slug_to_search
	 */
	public function test__get_slug_to_search_when_input_is_empty() : void {

		/*
		 * Set up
		 */
		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_get_slug_to_search',
			[
				'',
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEmpty( $output );

	}

	/**
	 * @covers ::_get_slug_to_search
	 *
	 * @dataProvider data_urls_with_expected_search_slugs
	 */
	public function test__get_slug_to_search_for_success( string $url_to_test, string $search_slug_expected ) : void {

		/*
		 * Set up
		 */
		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_get_slug_to_search',
			[
				$url_to_test,
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEquals( $search_slug_expected, $output );

	}

	/**
	 * @covers ::_get_postid_from_url_via_es
	 *
	 * @dataProvider data_invalid_urls
	 */
	public function test__get_postid_from_url_via_es_when_url_is_invalid( string $url_to_test, int $output_expected ) : void {

		/*
		 * Set up
		 */
		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_get_postid_from_url_via_es',
			[
				$url_to_test,
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEquals( $output_expected, $output );

	}

	/**
	 * @covers ::_get_postid_from_url_via_es
	 */
	public function test__get_postid_from_url_via_es_when_site_id_is_invalid() : void {

		/*
		 * Set up
		 */
		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_get_postid_from_url_via_es',
			[
				home_url( 'wp-content/uploads/2019/08/some-image.jpg' ),
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEquals( -1, $output );

	}

	/**
	 * @covers ::_get_postid_from_url_via_es
	 */
	public function test__get_postid_from_url_via_es_when_response_is_empty() : void {

		/*
		 * Set up
		 */
		add_filter( 'pmc_is_production_mock_env', '__return_true' );

		$api_url = sprintf( 'https://public-api.wordpress.com/rest/v1/sites/%d/search', get_current_blog_id() );

		$this->mock_http_once( $api_url, '' );

		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_get_postid_from_url_via_es',
			[
				home_url( 'wp-content/uploads/2019/08/some-image.jpg' ),
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEquals( -1, $output );

		/*
		 * Clean up
		 */
		remove_filter( 'pmc_is_production_mock_env', '__return_true' );

	}

	/**
	 * @covers ::_get_postid_from_url_via_es
	 *
	 * @dataProvider data_get_integers
	 */
	public function test__get_postid_from_url_via_es_when_url_match_not_found( int $post_id ) : void {

		/*
		 * Set up
		 */
		add_filter( 'pmc_is_production_mock_env', '__return_true' );

		$json_url = home_url( 'wp-content/uploads/2019/08/some-image-new.jpg' );
		$json_url = set_url_scheme( $json_url, 'https' );
		$json_url = wp_json_encode( substr( $json_url, 8 ) );

		$api_url            = sprintf( 'https://public-api.wordpress.com/rest/v1/sites/%d/search', get_current_blog_id() );
		$mock_response_body = sprintf(
			'{"results":{"total":1,"max_score":1,"hits":[{"_score":1,"fields":{"post_id":%d,"url":%s}}]},"took":1}',
			$post_id,
			$json_url
		);

		$this->mock_http_once( $api_url, $mock_response_body );

		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_get_postid_from_url_via_es',
			[
				home_url( 'wp-content/uploads/2019/08/some-image.jpg' ),
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEquals( -1, $output );

		/*
		 * Clean up
		 */
		remove_filter( 'pmc_is_production_mock_env', '__return_true' );

	}

	/**
	 * @covers ::_get_postid_from_url_via_es
	 *
	 * @dataProvider data_get_integers
	 */
	public function test__get_postid_from_url_via_es_for_success( int $expected_post_id ) : void {

		/*
		 * Set up
		 */
		add_filter( 'pmc_is_production_mock_env', '__return_true' );

		$file_url = home_url( 'wp-content/uploads/2019/08/some-image.jpg' );
		$json_url = set_url_scheme( $file_url, 'https' );
		$json_url = wp_json_encode( substr( $json_url, 8 ) );

		$api_url            = sprintf( 'https://public-api.wordpress.com/rest/v1/sites/%d/search', get_current_blog_id() );
		$mock_response_body = sprintf(
			'{"results":{"total":1,"max_score":1,"hits":[{"_score":1,"fields":{"post_id":%d,"url":%s}}]},"took":1}',
			$expected_post_id,
			$json_url
		);

		$this->mock_http_once( $api_url, $mock_response_body );

		$output = Utility::invoke_hidden_method(
			$this->_instance,
			'_get_postid_from_url_via_es',
			[
				$file_url,
			]
		);

		/*
		 * Assertions
		 */
		$this->assertEquals( $expected_post_id, $output );

		/*
		 * Clean up
		 */
		remove_filter( 'pmc_is_production_mock_env', '__return_true' );

	}

	/**
	 * @covers ::get_postid_from_url_uncached
	 *
	 * @dataProvider data_invalid_urls
	 */
	public function test_get_postid_from_url_uncached_when_input_is_invalid( string $url_to_test, int $id_expected ) : void {

		/*
		 * Set up
		 */
		$output = $this->_instance->get_postid_from_url_uncached( $url_to_test );

		/*
		 * Assertions
		 */
		$this->assertEquals( $id_expected, $output );

	}

	/**
	 * @covers ::get_postid_from_url_uncached
	 */
	public function test_get_postid_from_url_uncached_when_url_is_external() : void {

		/*
		 * Set up
		 */
		$url_to_test = 'http://example.com/some/test/image.jpg';
		$output      = $this->_instance->get_postid_from_url_uncached( $url_to_test );

		/*
		 * Assertions
		 */
		$this->assertEquals( -1, $output );

	}

	/**
	 * @covers ::get_postid_from_url_uncached
	 */
	public function test_get_postid_from_url_uncached_when_filename_is_invalid() : void {

		/*
		 * Set up
		 */
		$url_to_test = home_url( '/some/test/.jpg' );
		$output      = $this->_instance->get_postid_from_url_uncached( $url_to_test );

		/*
		 * Assertions
		 */
		$this->assertEquals( -1, $output );

	}

	/**
	 * @covers ::get_postid_from_url_uncached
	 */
	public function test_get_postid_from_url_uncached_when_global_wpdb_is_not_available() : void {

		/*
		 * Set up
		 */
		$url_to_test     = home_url( '/some/test/image.jpg' );
		$wpdb_backup     = ( isset( $GLOBALS['wpdb'] ) ) ? $GLOBALS['wpdb'] : '';
		$GLOBALS['wpdb'] = '';
		$output          = $this->_instance->get_postid_from_url_uncached( $url_to_test );

		/*
		 * Assertions
		 */
		$this->assertEquals( -1, $output );

		/*
		 * Clean up
		 */
		$GLOBALS['wpdb'] = $wpdb_backup;

	}

	/**
	 * @covers ::get_postid_from_url_uncached
	 *
	 * @dataProvider data_get_url_protocols
	 */
	public function test_get_postid_from_url_uncached_for_success( string $protocol ) : void {

		/*
		 * Set up
		 */
		$image_to_test_with    = sprintf( '%s/assets/red.jpg', PMC_GLOBAL_FUNCTIONS_TESTS_ROOT );
		$attachment_id_to_test = $this->factory->attachment->create_upload_object( $image_to_test_with, 0 );
		$attachment_to_test    = get_post( $attachment_id_to_test );
		$url_to_test           = set_url_scheme( $attachment_to_test->guid, $protocol );

		$sql = sprintf(
			"UPDATE %s SET guid = %%s WHERE ID = %%d LIMIT 1",
			$GLOBALS['wpdb']->posts
		);

		$GLOBALS['wpdb']->query(
			$GLOBALS['wpdb']->prepare(
				$sql,
				$url_to_test,
				$attachment_to_test->ID
			)
		);

		$output = $this->_instance->get_postid_from_url_uncached( $url_to_test );

		/*
		 * Assertions
		 */
		$this->assertEquals( $attachment_id_to_test, $output );

		/*
		 * Clean up
		 */
		wp_delete_attachment( $attachment_id_to_test, true );

	}

	/**
	 * @covers ::get_postid_from_url
	 *
	 * @dataProvider data_invalid_urls
	 */
	public function test_get_postid_from_url_when_input_is_invalid( string $url_to_test, int $id_expected ) : void {

		/*
		 * Set up
		 */
		$output = $this->_instance->get_postid_from_url( $url_to_test );

		/*
		 * Assertions
		 */
		$this->assertEquals( $id_expected, $output );

	}

	/**
	 * @covers ::get_postid_from_url
	 */
	public function test_get_postid_from_url_for_success() : void {

		/*
		 * Set up
		 */
		$image_to_test_with    = sprintf( '%s/assets/red.jpg', PMC_GLOBAL_FUNCTIONS_TESTS_ROOT );
		$attachment_id_to_test = $this->factory->attachment->create_upload_object( $image_to_test_with, 0 );
		$attachment_to_test    = get_post( $attachment_id_to_test );
		$url_to_test           = $attachment_to_test->guid;

		$output = $this->_instance->get_postid_from_url( $url_to_test );

		/*
		 * Assertions
		 */
		$this->assertEquals( $attachment_id_to_test, $output );

		/*
		 * Clean up
		 */
		wp_delete_attachment( $attachment_id_to_test, true );

	}

}    //end class

//EOF
