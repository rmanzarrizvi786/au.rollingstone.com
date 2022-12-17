<?php
/**
 * Created by PhpStorm.
 * @author: Archana Mandhare
 * @since: 2015/03/17
 * @ticket: PPT-4248 - Unit test pmc-carousel plugin
 */

use \PMC\Unit_Test\Mock\Mocker;
/**
 * @group pmc-carousel
 * @coversDefaultClass PMC_Master_Featured_Articles
 */
class Tests_Class_PMC_Master_Featured_Articles extends WP_UnitTestCase {

	CONST FEATURED_POST_TYPE = 'pmc_featured';

	protected $_mocker = false;

	/**
	 * Setup Method.
	 */
	public function setUp() {
		// To speed up unit test, we bypass files scanning on upload folder.
		self::$ignore_files = true;
		parent::setUp();

		$this->_mocker = new Mocker();
	}

	/**
	 * To prevent all upload files from deletion, since set $ignore_files = true
	 * We override the function and do nothing here.
	 */
	public function remove_added_uploads() {}

	/*
	 * @covers ::get_instance
	 */
	public function test__init() {
		$pmc_master_featured_articles = PMC_Master_Featured_Articles::get_instance();

		$this->assertNotEmpty( $pmc_master_featured_articles, 'Error retrieving instance of PMC_Master_Featured_Articles' );

		$filters = array(
			'after_setup_theme'                       => 'action_after_theme_setup',
			'init'                                    => 'init',
			'save_post'                               => 'save_post',
			'transition_post_status'                  => 'transition_post_status',
			'manage_pmc_featured_posts_columns'       => 'manage_posts_columns',
			'manage_pmc_featured_posts_custom_column' => 'manage_posts_custom_column',
			'request'                                 => 'request',
			'wp_ajax_pmc_master_publish_carousel'     => 'ajax_publish_carousel',
		);

		foreach ( $filters as $key => $value ) {
			$this->assertGreaterThanOrEqual(
				10,
				has_filter( $key, array( $pmc_master_featured_articles, $value ) ),
				sprintf( 'PMC_Master_Featured_Articles::_init failed registering filter/action "%1$s" to PMC_Master_Featured_Articles::%2$s', $key, $value )
			);
		}

		$this->assertEquals(
			100,
			has_action( 'admin_head-edit.php', array( $pmc_master_featured_articles, 'admin_head' ) ),
			sprintf( 'PMC_Master_Featured_Articles::_init failed registering filter/action "%1$s" to PMC_Master_Featured_Articles::%2$s', 'admin_head-edit.php', 'admin_head' )
		);

		$this->assertEquals(
			10,
			has_action( 'pre_get_posts', array( $pmc_master_featured_articles, 'pre_get_posts' ) ),
			sprintf( 'PMC_Master_Featured_Articles::_init failed registering filter/action "%1$s" to PMC_Master_Featured_Articles::%2$s', 'pre_get_posts', 'pre_get_posts' )
		);
	}

	/*
	 * @covers ::get_instance
	 */
	public function test_init() {

		PMC_Master_Featured_Articles::get_instance()->init();
		$this->assertTrue( post_type_exists( self::FEATURED_POST_TYPE ) );

	}

	/*
	 * @covers ::get_instance
	 */
	public function test_action_after_theme_setup() {

		global $_wp_additional_image_sizes;
		PMC_Master_Featured_Articles::get_instance()->action_after_theme_setup();
		$this->assertTrue( array_key_exists( 'carousel-small-thumb', $_wp_additional_image_sizes ) );

	}

	/**
	 * @covers ::get_instance
	 */
	public function test_get_instance() {

		$this->assertInstanceOf( "PMC_Master_Featured_Articles", PMC_Master_Featured_Articles::get_instance() );

	}

	/**
	 * @covers ::save_post
	 */
	public function test_save_post() {

		$test_data = $this->factory->post->create();

		$test_post = get_post( $test_data );

		$post_id = $test_post->ID;

		$this->go_to( "/?p=$post_id" );

		$this->assertEquals( $post_id, get_queried_object_id() );

		$new_meta_value = array(
			'url'      => esc_url_raw( 'http://google.com' ),
			'id'       => intval( 123 ),
			'title'    => esc_html( 'This is test title' ),
			'type'     => 'test_type',
			'taxonomy' => 'pmc_carousel_modules',
		);

		$expected_meta_value = json_encode( $new_meta_value );

		update_post_meta( $post_id, '_pmc_master_article_id', $expected_meta_value );

		$current_meta_value = json_decode( get_post_meta( $post_id, '_pmc_master_article_id', true ) );

		$this->assertEquals( $expected_meta_value, json_encode( $current_meta_value ) );
	}

	/**
	 * @covers ::pre_get_posts
	 */
	public function test_pre_get_posts() {

		$test_query = new WP_Query();
		PMC_Master_Featured_Articles::get_instance()->pre_get_posts( $test_query );

		$this->assertEquals(
			10,
			has_action( 'pre_get_posts', array( PMC_Master_Featured_Articles::get_instance(), 'pre_get_posts' ) ),
			sprintf( 'PMC_Master_Featured_Articles::pre_get_posts failed registering action "%1$s" to PMC_Master_Featured_Articles::%2$s', 'pre_get_posts', 'pre_get_posts' )
		);

		global $wp_query, $wp_the_query;

		// backup main WP_Query.
		$old_wp_the_query = $wp_the_query;

		// Mock screen for `is_admin()`.
		set_current_screen( 'dashboard' );

		// Mock postype in query.
		$this->_mocker->mock_global_wp_query( array(
			'query' => array(
				'post_type' => 'pmc_featured',
			),
		) );

		// Mock global main query, for $query->is_main_query().
		$wp_the_query = $wp_query;

		PMC_Master_Featured_Articles::get_instance()->pre_get_posts( $wp_query );

		$this->assertFalse(
			has_action( 'pre_get_posts', array( PMC_Master_Featured_Articles::get_instance(), 'pre_get_posts' ) ),
			sprintf( 'PMC_Master_Featured_Articles::pre_get_posts failed deregister action "%1$s" to PMC_Master_Featured_Articles::%2$s', 'pre_get_posts', 'pre_get_posts' )
		);

		$this->assertEquals( 'menu_order title', $wp_query->get( 'orderby' ) );
		$this->assertEquals( 'ASC', $wp_query->get( 'order' ) );

		// Restore global main query.
		$wp_the_query = $old_wp_the_query;

		$this->_mocker->restore_global_wp_query();
	}

	/**
	 * @covers ::manage_posts_columns
	 */
	public function test_manage_posts_columns() {

		$expected_cols = array(
			'cb'     => '<input type="checkbox" />',
			'image'  => 'Image',
			'title'  => 'Title',
			'author' => 'Author',
			'date'   => 'Date',
		);

		$actual_cols = PMC_Master_Featured_Articles::get_instance()->manage_posts_columns( $expected_cols );

		$this->assertEquals( $expected_cols, $actual_cols );

	}

	/**
	 * @covers ::manage_posts_custom_column
	 */
	public function test_manage_posts_custom_column() {

		$test_data = $this->factory->post->create();

		$test_post = get_post( $test_data );

		$post_id = $test_post->ID;

		$this->go_to( "/?p=$post_id" );

		$this->assertEquals( $post_id, get_queried_object_id() );

		$expected_value = get_the_post_thumbnail( $post_id, 'carousel-small-thumb' );

		$actual_value = PMC_Master_Featured_Articles::get_instance()->manage_posts_custom_column( 'image', $post_id );

		$this->assertEquals( $expected_value, $actual_value );


	}

	/**
	 * @covers ::generate_carousel
	 */
	public function test_generate_carousel() {

		$actual_data = PMC_Master_Featured_Articles::get_instance()->generate_carousel();

		$this->assertNotNull( $actual_data );
	}

	/**
	 * @covers ::featured_meta_box_cb
	 */
	public function test_featured_meta_box_cb() {
		global $wp_meta_boxes;

		$screen = convert_to_screen( self::FEATURED_POST_TYPE );
		$page = $screen->id;
		$context = 'normal';
		$priority = 'default';
		$id = 'pmc-master-link-meta-box';

		PMC_Master_Featured_Articles::get_instance()->featured_meta_box_cb();

		$this->assertTrue( isset( $wp_meta_boxes[ $page ][ $context ][ $priority ][ $id ] ) );
	}


}
