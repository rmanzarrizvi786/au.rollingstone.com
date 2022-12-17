<?php
/**
 * Tests for Lists
 */

namespace PMC\Lists\Tests;

use PMC\Unit_Test\Utility;
use PMC\Lists\Lists;

/**
 * @group pmc-lists
 * Test class for Lists.
 *
 * @coversDefaultClass \PMC\Lists\Lists
 */
class Test_Lists extends \WP_UnitTestCase {

	/**
	 * Set up.
	 */
	public function setUp() {
		parent::setUp();

		// Admin list item page.
		wp_set_current_user( 1 );
		set_current_screen( 'edit-pmc_list_item' );

		$instance        = Utility::get_hidden_static_property( 'PMC\Lists\Lists', '_instance' );
		$this->_instance = $instance['PMC\Lists\Lists'];
	}

	/**
	 * Tear down.
	 */
	public function tearDown() {
		parent::tearDown();

		unset( $GLOBALS['current_screen'] );
	}

	/**
	 * Test constructor.
	 *
	 * @covers ::__construct
	 */
	public function test___construct() {

		$this->assertInstanceOf( '\PMC\Lists\Lists', $this->_instance );
		$this->assertEquals( 10, has_action( 'init', array( $this->_instance, 'init' ) ) );
		$this->assertEquals( 10, has_action( 'wp_ajax_pmc_get_lists', array( $this->_instance, 'get_lists' ) ) );
		$this->assertEquals( 10, has_action( 'admin_enqueue_scripts', array( $this->_instance, 'admin_enqueue_scripts' ) ) );
		$this->assertEquals( 20, has_filter( 'simple_page_ordering_is_sortable', array( $this->_instance, 'enable_sorting' ), 20, 2 ) );
		$this->assertEquals( 10, has_filter( 'restrict_manage_posts', array( $this->_instance, 'filter_list_items' ) ) );
		$this->assertEquals( 10, has_filter( 'views_edit-' . $this->_instance::LIST_ITEM_POST_TYPE, array( $this->_instance, 'show_current_list' ) ) );
		$this->assertEquals( 10, has_filter( 'custom_menu_order', array( $this->_instance, 'reorder_menu' ) ) );
		$this->assertEquals( 10, has_action( 'save_post', array( $this->_instance, 'save_post' ) ) );

	}

	/**
	 * Test init.
	 *
	 * @covers ::init
	 */
	public function test_init() {
		global $wp_rewrite;

		$this->_instance->init();

		$this->assertTrue( post_type_exists( 'pmc_list' ) );
		$this->assertTrue( post_type_exists( 'pmc_list_item' ) );
		$this->assertTrue( taxonomy_exists( 'pmc_list_relation' ) );
		$this->assertTrue( in_array( '%list_page%', (array) $wp_rewrite->rewritecode, true ) );
	}

	/**
	 * Test reordering menu.
	 *
	 * @covers ::reorder_menu
	 */
	public function test_reorder_menu() {
		global $submenu;

		$original_submenu = $submenu;
		$submenu          = array(
			'edit.php?post_type=pmc_list' => array(
				1   => array(
					0 => 'Menu Item 1',
					2 => 'post.php',
				),
				2   => array(
					0 => 'Menu Item 2',
					2 => 'edit.php?post_type=pmc_list_item',
				),
				20  => array(
					0 => 'Menu Item 20',
					2 => 'edit.php',
				),
				100 => array(
					0 => 'Menu Item 100',
					2 => 'settings.php',
				),
			),
		);

		$this->_instance->reorder_menu( true );

		$this->assertNotEmpty( $submenu['edit.php?post_type=pmc_list'][11] );
		$this->assertEquals( 'edit.php?post_type=pmc_list_item', $submenu['edit.php?post_type=pmc_list'][11][2] );

		// Tear down.
		$submenu = $original_submenu;
	}

	/**
	 * Test admin enqueue scripts.
	 *
	 * @covers ::admin_enqueue_scripts
	 */
	public function test_admin_enqueue_scripts() {
		global $post;

		// Create a list item so that WP and assign it to $post so WP thinks we're on a list item page.
		$post = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc_list_item',
			'post_status'  => 'publish',
			'post_title'   => 'A List Item',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		$this->_instance->admin_enqueue_scripts();

		$this->assertTrue( wp_script_is( 'pmc-lists', 'enqueued' ) );

		// Tear down.
		wp_reset_postdata();
	}

	/**
	 * Test enable sorting.
	 *
	 * @covers ::enable_sorting
	 */
	public function test_enable_sorting() {
		$this->assertTrue( $this->_instance->enable_sorting( true, 'pmc_list_item' ) );
		$this->assertFalse( $this->_instance->enable_sorting( false, 'some_other_post_type' ) );
	}

	/**
	 * Test list meta boxes.
	 *
	 * @covers ::list_meta_boxes
	 */
	public function test_list_meta_boxes() {
		global $current_screen, $wp_meta_boxes;

		$current_screen->post_type = 'pmc_list';

		$list = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc_list',
			'post_status'  => 'publish',
			'post_title'   => 'A List Item',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		$this->_instance->list_meta_boxes( $list );
		$this->assertNotEmpty( $wp_meta_boxes['pmc_list']['side']['high']['pmc-list-options'] );
	}

	/**
	 * Test list item meta boxes.
	 *
	 * @covers ::list_item_meta_boxes
	 */
	public function test_list_item_meta_boxes() {
		global $current_screen, $wp_meta_boxes;

		$current_screen->post_type = 'pmc_list_item';

		$list_item = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc_list_item',
			'post_status'  => 'publish',
			'post_title'   => 'A List Item',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		$this->_instance->list_item_meta_boxes( $list_item );
		$this->assertNotEmpty( $wp_meta_boxes['pmc_list_item']['side']['high']['pmc-list-select'] );
	}

	/**
	 * Test list options meta box.
	 *
	 * @covers ::list_options_meta_box
	 */
	public function test_list_options_meta_box() {
		ob_start();
		$this->_instance->list_options_meta_box( $this->factory->post->create_and_get() );
		$html = ob_get_clean();

		$this->assertContains( '<select name="pmc_list_template">', $html );
	}

	/**
	 * Test list select meta box.
	 *
	 * @covers ::list_select_meta_box
	 */
	public function test_list_select_meta_box() {
		ob_start();
		$post = $this->factory->post->create_and_get();
		$term = $this->factory->term->create_and_get( [
			'name'     => 'my-term',
			'taxonomy' => Lists::LIST_RELATION_TAXONOMY,
		] );

		wp_set_post_terms( $post->ID, $term->term_id, Lists::LIST_RELATION_TAXONOMY );
		$this->_instance->list_select_meta_box( $post );
		$html = ob_get_clean();

		$this->assertContains( '<p class="pmc_list_selected" style="font-weight: 700;">', $html );
	}

	/**
	 * Test save post.
	 *  @group failing
	 * @covers ::save_post
	 */
	public function test_save_post() {
		global $post;

		$list = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc_list',
			'post_status'  => 'publish',
			'post_title'   => 'A List Item',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		// Fake that we're viewing a list.
		$post = $list;

		// Set request data.
		$_POST['pmc_list_template']  = 'default.php';
		$_POST['pmc_list_numbering'] = 'asc';

		$this->_instance->save_post( $list->ID, $list );

		$this->assertEquals( 'default.php', get_post_meta( $list->ID, 'pmc_list_template', true ) );
		$this->assertEquals( 'asc', get_post_meta( $list->ID, 'pmc_list_numbering', true ) );

		// Tear down.
		wp_reset_postdata();

		wp_set_current_user( 1 );

		$parent_post = $this->factory->post->create_and_get( [
			'post_title' => 'My Post',
			'post_name'  => 'my-post',
		] );

		$revision = $this->factory->post->create_and_get( [
			'post_type'   => 'revision',
			'post_title'  => 'My Post',
			'post_parent' => $parent_post->ID,
			'post_name'   => "{$parent_post->ID}-revision-1",
		] );

		$this->assertFalse( $this->_instance->save_post( $revision->ID, $revision ) );

		$autosave = $this->factory->post->create_and_get( [
			'post_type'   => 'revision',
			'post_title'  => 'My Post',
			'post_parent' => $parent_post->ID,
			'post_name'   => "{$parent_post->ID}-autosave",
		] );

		$this->assertFalse( $this->_instance->save_post( $autosave->ID, $autosave ) );

		$list_item = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc_list_item',
			'post_status'  => 'publish',
			'post_title'   => 'A List Item',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		// Fake that we're viewing a list.
		$post = $list_item;

		$list_item_save_post = $this->_instance->save_post( $list_item->ID, $list_item );

		$this->assertEquals( false, $list_item_save_post );
		$this->assertEquals( 0, get_post( $list_item->ID )->menu_order );
		$this->assertFalse( has_term( strval( $list->ID ), Lists::LIST_RELATION_TAXONOMY, $list_item ) );

		// Set request data.
		$_POST['pmc_list_id'] = $list->ID;

		$this->_instance->save_post( $list_item->ID, $list_item );
		$this->assertTrue( has_term( strval( $list->ID ), Lists::LIST_RELATION_TAXONOMY, $list_item ) );

		wp_update_post( [
			'ID'         => $list_item->ID,
			'menu_order' => 6,
		] );

		$this->_instance->save_post( $list_item->ID, get_post( $list_item->ID ) );

		$this->assertNotEquals( 0, get_post( $list_item->ID )->menu_order );

		// Tear down.
		wp_reset_postdata();
	}

	/**
	 * Test update_menu_orders
	 *
	 * @covers ::update_menu_orders
	 */
	public function test_update_menu_orders() {

		$post = $this->factory->post->create_and_get();
		$term = $this->factory->term->create_and_get( [
			'name'     => 'my-term',
			'taxonomy' => Lists::LIST_RELATION_TAXONOMY,
		] );

		wp_set_post_terms( $post->ID, $term->term_id, Lists::LIST_RELATION_TAXONOMY );

		$this->assertNull( $this->_instance->update_menu_orders( '' ) );

		$GLOBALS['current_screen'] = \WP_Screen::get( 'pmc_list_item' );

		$items = $this->factory->post->create_many( 5, [
			'post_type'    => 'pmc_list_item',
			'post_status'  => 'publish',
			'post_title'   => 'A List Item',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		foreach ( $items as $item ) {
			wp_set_post_terms( $item, $term->slug, Lists::LIST_RELATION_TAXONOMY );
		}

		$this->_instance->update_menu_orders( 'my-term' );

		$this->assertEquals( 5, get_post( $items[4] )->menu_order );

		$this->assertEquals( 0, get_post( $new_item->ID )->menu_order );
	}

	/**
	 * Test modify_reorder_query.
	 *
	 * @covers ::modify_reorder_query
	 */
	public function test_modify_reorder_query() {

		$term = $this->factory->term->create_and_get( [
			'name'     => 'my-term',
			'taxonomy' => Lists::LIST_RELATION_TAXONOMY,
		] );

		$post = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc_list_item',
			'post_status'  => 'publish',
			'post_title'   => 'A List Item',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		wp_set_post_terms( $post, $term->slug, Lists::LIST_RELATION_TAXONOMY );

		$_POST['id'] = strval( $post->ID );

		$test_query = new \WP_Query( [ 'post_type' => Lists::LIST_ITEM_POST_TYPE ] );
		$instance   = $this->_instance;

		remove_all_actions( 'wp_ajax_simple_page_ordering' );
		add_action( 'wp_ajax_simple_page_ordering', function() use ( $instance, $test_query ) {
			$instance->modify_reorder_query( $test_query );
		} );

		do_action( 'wp_ajax_simple_page_ordering' );

		$this->assertEquals( 'publish', $test_query->query_vars['status'] );

	}

	/**
	 * Test show current list.
	 *
	 * @covers ::show_current_list
	 */
	public function test_show_current_list() {
		global $post;

		$post = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc_list_item',
			'post_status'  => 'publish',
			'post_title'   => 'A List Item',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		$list = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc_list',
			'post_status'  => 'publish',
			'post_title'   => 'A List Item',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		set_query_var( 'pmc_list_relation', $list->ID );

		ob_start();
		$this->_instance->show_current_list();
		$html = ob_get_clean();

		$this->assertContains( 'Currently viewing list:', $html );

		// Tear down.
		wp_reset_postdata();

		set_query_var( 'pmc_list_relation', '' );

		ob_start();
		$this->_instance->show_current_list();
		$html = ob_get_clean();

		$this->assertNotContains( 'Currently viewing list:', $html );

		// Tear down.
		wp_reset_postdata();

		$this->go_to( '/wp-admin' );
		$this->assertFalse( $this->_instance->show_current_list() );

	}

	/**
	 * Test filter list items.
	 *
	 * @covers ::filter_list_items
	 */
	public function test_filter_list_items() {
		global $post;

		$post = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc_list_item',
			'post_status'  => 'publish',
			'post_title'   => 'A List Item',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		ob_start();
		$this->_instance->filter_list_items();
		$html = ob_get_clean();

		$this->assertContains( '<input type="text" id="pmc_filter_list" placeholder="Filter By List">', $html );

		// Tear down.
		wp_reset_postdata();

		$this->go_to( '/wp-admin' );
		set_query_var( 'post_type', '' );
		$this->assertFalse( $this->_instance->filter_list_items() );

	}

	/**
	 * Test fix_sort_by_order_link
	 *
	 * @covers ::fix_sort_by_order_link
	 */
	function test_fix_sort_by_order_link() {
		$views = [];

		$this->assertEquals( [], $this->_instance->fix_sort_by_order_link( $views ) );

		$views['all'] = 'some-html';

		$this->assertEquals( $views, $this->_instance->fix_sort_by_order_link( $views ) );

		$views['all'] = 'orderby=menu_order+title&order=asc';

		$this->assertEquals( $views, $this->_instance->fix_sort_by_order_link( $views ) );

		$views['all'] = 'orderby=menu_order+title';

		$this->assertEquals( [ 'all' => 'orderby=menu_order+title&order=asc' ], $this->_instance->fix_sort_by_order_link( $views ) );
	}

	/**
	 * Test get relation term for item.
	 *
	 * @covers ::get_relation_term_for_item
	 */
	public function test_get_relation_term_for_item() {
		$term = $this->factory->term->create_and_get( [
			'name'     => 'my-new-term',
			'taxonomy' => Lists::LIST_RELATION_TAXONOMY,
		] );

		$post = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc_list_item',
			'post_status'  => 'publish',
			'post_title'   => 'A List Item',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		wp_set_post_terms( $post->ID, 'my-new-term', Lists::LIST_RELATION_TAXONOMY );

		$term = $this->_instance->get_relation_term_for_item( $post->ID );

		$this->assertInstanceOf( \WP_Term::class, $term );
	}

	/**
	 * Test get list for item.
	 *
	 * @covers ::get_list_for_item
	 */
	public function test_get_list_for_item() {
		$list = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc_list',
			'post_status'  => 'publish',
			'post_title'   => 'A List',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		$list_item = $this->factory->post->create_and_get( [
			'post_type'    => 'pmc_list_item',
			'post_status'  => 'publish',
			'post_title'   => 'A List Item',
			'post_content' => 'Some content',
			'post_excerpt' => 'Some excerpt',
		] );

		wp_set_post_terms( $list_item->ID, $list->ID, 'pmc_list_relation' );

		$this->assertNull( $this->_instance->get_list_for_item( 88888888 ) );

		$new_list = $this->_instance->get_list_for_item( $list_item->ID );

		$this->assertEquals( $list->ID, $new_list->ID );
		$this->assertEquals( 'A List', $new_list->post_title );

		wp_delete_post( $list->ID );
		$this->assertNull( $this->_instance->get_list_for_item( $list_item->ID ) );

	}

	/**
	 * @covers ::whitelist_post_type_for_sitemaps
	 */
	public function test_whitelist_post_type_for_sitemaps() {

		$input = [
			'post',
			'page',
		];

		$output = apply_filters( 'pmc_sitemaps_post_type_whitelist', $input );

		$this->assertContains( 'pmc_list', $output );
	}

	/**
	 * @covers ::list_item_add_custom_column
	 */
	public function test_list_item_add_custom_column() {

		$output = apply_filters( 'manage_pmc_list_item_posts_columns', [] );

		$this->assertEquals(
			[
				'list' => 'List',
			],
			$output
		);

		$output = apply_filters( 'manage_pmc_list_item_posts_columns', [ 'abc' => 'ABC' ] );

		$this->assertEquals(
			[
				'abc'  => 'ABC',
				'list' => 'List',
			],
			$output
		);
	}

	/**
	 * @covers ::list_add_custom_column
	 */
	public function test_list_add_custom_column() {

		$output = apply_filters( 'manage_pmc_list_posts_columns', [] );

		$this->assertEquals(
			[
				'list-items' => 'List Items',
			],
			$output
		);

		$output = apply_filters( 'manage_pmc_list_posts_columns', [ 'abc' => 'ABC' ] );

		$this->assertEquals(
			[
				'abc'        => 'ABC',
				'list-items' => 'List Items',
			],
			$output
		);
	}

	/**
	 * @covers ::list_item_manage_custom_column
	 */
	public function test_list_item_manage_custom_column() {

		$list = $this->factory->post->create_and_get(
			[
				'post_type' => Lists::LIST_POST_TYPE,
			]
		);

		$list_item = $this->factory->post->create_and_get(
			[
				'post_type' => Lists::LIST_ITEM_POST_TYPE,
			]
		);

		wp_set_post_terms( $list_item->ID, $list->ID, Lists::LIST_RELATION_TAXONOMY );

		// Passed empty values.
		$output = apply_filters( 'manage_pmc_list_item_posts_custom_column', '', '' );
		$this->assertEmpty( $output );

		// Passed different column name.
		$output = apply_filters( 'manage_pmc_list_item_posts_custom_column', 'abc', '' );
		$this->assertEmpty( $output );

		$request_url = $_SERVER['REQUEST_URI']; // @codingStandardsIgnoreLine Save default request URL.

		$_SERVER['REQUEST_URI'] = admin_url( '/edit.php?post_type=' . Lists::LIST_ITEM_POST_TYPE );

		// Passed proper column name and list item id.
		$output = Utility::buffer_and_return( 'apply_filters', [
			'manage_pmc_list_item_posts_custom_column',
			'list',
			$list_item->ID,
		] );

		$this->assertEquals(
			sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( Lists::LIST_RELATION_TAXONOMY, $list->ID ) ), $list->post_title ),
			$output
		);

		$_SERVER['REQUEST_URI'] = $request_url; // Reset request URL.
	}

	/**
	 * @covers ::list_manage_custom_column
	 */
	public function test_list_manage_custom_column() {

		$list = $this->factory->post->create_and_get(
			[
				'post_type' => Lists::LIST_POST_TYPE,
			]
		);

		$list_item1 = $this->factory->post->create_and_get(
			[
				'post_type' => Lists::LIST_ITEM_POST_TYPE,
			]
		);

		$list_item2 = $this->factory->post->create_and_get(
			[
				'post_type' => Lists::LIST_ITEM_POST_TYPE,
			]
		);

		wp_set_post_terms( $list_item1->ID, $list->ID, Lists::LIST_RELATION_TAXONOMY );
		wp_set_post_terms( $list_item2->ID, $list->ID, Lists::LIST_RELATION_TAXONOMY );

		// Passed empty values.
		$output = apply_filters( 'manage_pmc_list_posts_custom_column', '', '' );
		$this->assertEmpty( $output );

		// Passed different column name.
		$output = apply_filters( 'manage_pmc_list_posts_custom_column', 'abc', '' );
		$this->assertEmpty( $output );

		// Passed proper column name and list item id.
		$output = Utility::buffer_and_return( 'apply_filters', [
			'manage_pmc_list_posts_custom_column',
			'list-items',
			$list->ID,
		] );

		$this->assertEquals(
			sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( Lists::LIST_RELATION_TAXONOMY, $list->ID, admin_url( '/edit.php?post_type=' . Lists::LIST_ITEM_POST_TYPE ) ) ), '2' ),
			$output
		);
	}
}
