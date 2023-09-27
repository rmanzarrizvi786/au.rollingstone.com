<?php
/**
 * Tests for Lists
 */

namespace PMC\Lists\Tests;

use PMC\Lists\Lists;
use PMC\Unit_Test\Utility;

/**
 * @group pmc-lists
 * Test class for List_Post.
 *
 * @coversDefaultClass \PMC\Lists\List_Post
 * @group pmc-phpunit-ignore-failed
 */
class Test_List_Post extends \WP_UnitTestCase {

	public $post;
	public $term;
	public $items;

	/**
	 * Set up.
	 */
	public function setUp() {
		parent::setUp();

		$instance        = Utility::get_hidden_static_property( 'PMC\Lists\List_Post', '_instance' );
		$this->_instance = $instance['PMC\Lists\List_Post'];

	}

	/**
	 * Tear down.
	 */
	public function tearDown() {

		parent::tearDown();

	}

	/**
	 * Sets up posts to use for testing.
	 */
	public function set_up_posts( $item_number = 10 ) {
		if ( empty( $this->list ) ) {
			$this->list = $this->factory->post->create_and_get( [
				'post_type'    => 'pmc_list',
				'post_status'  => 'publish',
				'post_title'   => 'A List',
				'post_content' => 'Some list content',
				'post_excerpt' => 'Some list excerpt',
			] );
		}

		if ( empty( $this->term ) ) {
			if ( wpcom_vip_term_exists( strval( $this->list->ID ), 'pmc_list_relation' ) ) {
				$this->term = get_term_by( 'slug', strval( $this->list->ID ), 'pmc_list_relation' );
			} else {
				$this->term = $this->factory->term->create_and_get( [
					'name'     => strval( $this->list->ID ),
					'taxonomy' => 'pmc_list_relation',
				] );
			}
		}

		if ( empty( $this->items ) ) {

			$this->items = $this->factory->post->create_many( $item_number, [
				'post_type'    => 'pmc_list_item',
				'post_status'  => 'publish',
				'post_title'   => 'A List Item',
				'post_content' => 'Some content',
				'post_excerpt' => 'Some excerpt',
			] );

			foreach ( $this->items as $index => $item ) {
				wp_update_post( [
					'ID'         => $item,
					'menu_order' => $index + 1,
				] );

				wp_set_object_terms( $item, $this->term->name, 'pmc_list_relation' );
			}
		}
	}

	/**
	 * Test set_up.
	 *
	 * @covers ::set_up
	 */
	public function test_set_up() {

		$this->_instance->set_up();

		$this->assertEquals( 0, $this->_instance->get_list_items_count() );

		$item = $this->factory->post->create_and_get( [
			'post_type' => 'pmc_list_item',
			'title'     => 'Item',
		] );

		$this->go_to( get_the_permalink( $item->ID ) );

		$this->_instance->set_up();

		$this->assertEmpty( $this->_instance->get_list() );

		$this->set_up_posts();

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up();

		$this->assertNotEmpty( $this->_instance->get_List() );

	}

	/**
	 * Test set_up_list.
	 *
	 * @covers ::set_up_list
	 */
	public function test_set_up_list() {

		$this->set_up_posts( 10 );

		$list_item = $this->factory->post->create_and_get( [
			'post_type'  => 'pmc_list',
			'post_title' => 'My post title',
		] );

		$this->go_to( get_the_permalink( $list_item->ID ) );

		$this->_instance->set_up_list();

		$this->assertEquals( 0, $this->_instance->get_list_items_count() );

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up_list();

		$this->assertInstanceOf( \WP_Term::class, $this->_instance->get_term() );
		$this->assertEquals( 10, $this->_instance->get_list_items_count() );
		$this->assertInstanceOf( \WP_Post::class, $this->_instance->get_list() );

		$this->go_to( get_the_permalink( reset( $this->items ) ) );
		$this->_instance->set_up_list();

		$this->assertEquals( $this->list->ID, $this->_instance->get_list()->ID );

	}

	/**
	 * Test set_up_order
	 *
	 * @covers ::set_up_order
	 */
	public function test_set_up_order() {

		$this->set_up_posts();

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up_list();
		$this->_instance->set_up_order();

		$this->assertEquals( 'asc', $this->_instance->get_order() );
		$this->assertEquals( 'asc', $this->_instance->get_numbering() );

		update_post_meta( $this->list->ID, Lists::NUMBERING_OPT_KEY, 'desc' );

		$this->_instance->set_up_list();
		$this->_instance->set_up_order();

		$this->assertEquals( 'desc', $this->_instance->get_order() );
		$this->assertEquals( 'desc', $this->_instance->get_numbering() );

		update_post_meta( $this->list->ID, 'pmc_list_numbering', 'none' );

		$this->_instance->set_up_list();
		$this->_instance->set_up_order();

		$this->assertEquals( 'asc', $this->_instance->get_order() );
		$this->assertEquals( 'none', $this->_instance->get_numbering() );

	}

	/**
	 * Test set_up_list_items
	 *
	 * @covers ::set_up_list_items
	 */
	public function test_set_up_list_items() {

		$page = $this->factory->post->create_and_get( [ 'title' => 'My Page' ] );
		$this->go_to( get_the_permalink( $page->ID ) );

		$this->_instance->set_up_list();
		$this->_instance->set_up_order();
		$this->_instance->set_up_pagination();
		$this->_instance->set_up_list_items();

		$this->assertEquals( [], $this->_instance->get_list_items() );
		$this->assertEquals( 0, $this->_instance->get_list_items_count() );

		$this->set_up_posts( 53 );

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up_list();
		$this->_instance->set_up_order();
		$this->_instance->set_up_pagination();
		$this->_instance->set_up_list_items();

		$this->assertEquals( 50, count( $this->_instance->get_list_items() ) );
		$this->assertEquals( 53, $this->_instance->get_list_items_count() );

	}

	/**
	 * Test set_up_list_items_uncached
	 *
	 * @covers ::set_up_list_items_uncached
	 */
	public function test_set_up_list_items_uncached() {

		$this->assertEquals( [], $this->_instance->set_up_list_items_uncached() );
		$this->assertEquals( [], $this->_instance->set_up_list_items_uncached( [ 'post_type' => 'fake_post_type' ] ) );

		$this->set_up_posts( 53 );

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up_list();
		$this->_instance->set_up_order();
		$this->_instance->set_up_pagination();

		$items_query_args = [
			'post_status'    => 'publish',
			'post_type'      => Lists::LIST_ITEM_POST_TYPE,
			'posts_per_page' => $this->_instance->get_posts_per_page(),
			'paged'          => $this->_instance->get_current_page(),
			'orderby'        => 'menu_order title',
			'order'          => $this->_instance->get_order(),
			'tax_query'      => [ // WPCS: slow query ok.
				[
					'taxonomy' => Lists::LIST_RELATION_TAXONOMY,
					'field'    => 'slug',
					'terms'    => $this->_instance->get_list()->ID,
				],
			],
		];

		$results = $this->_instance->set_up_list_items_uncached( $items_query_args );

		$this->assertEquals( 53, $results['list_items_count'] );
		$this->assertEquals( 50, count( $results['list_items'] ) );

	}


	/**
	 * Test set_up_pagination
	 *
	 * @covers ::set_up_pagination
	 */
	public function test_set_up_pagination() {

		$this->set_up_posts( 55 );

		$this->go_to( get_the_permalink( $this->list->ID ) );

		global $wp_query;
		$wp_query->query_vars['list_page'] = 44;

		$this->_instance->set_up();

		$this->assertEquals( 44, $this->_instance->get_current_page() );

		$item = $this->items[53];
		wp_update_post( [
			'ID'         => $item,
			'menu_order' => 54,
		] );

		$this->go_to( get_the_permalink( $item ) );

		$this->_instance->set_up();

		$this->assertEquals( 2, $this->_instance->get_current_page() );
		$this->assertEquals( 3, $this->_instance->get_queried_item_index() );

		$item = $this->items[1];
		wp_update_post( [
			'ID'         => $item,
			'menu_order' => 1,
		] );

		$this->go_to( get_the_permalink( $item ) );

		$this->_instance->set_up();
		$this->assertEquals( 1, $this->_instance->get_current_page() );
		$this->assertEquals( -1, $this->_instance->get_queried_item_index() );

		// Test to ensure the queried index is adjusted for the last item on a page.
		wp_update_post( [
			'ID'         => $item,
			'menu_order' => 100,
		] );

		$this->go_to( get_the_permalink( $item ) );
		$this->_instance->set_up();

		$this->assertEquals( 2, $this->_instance->get_current_page() );
		$this->assertEquals( 0, ( $item->menu_order % $this->_instance->get_posts_per_page() ) );

		// menu_order starts at 1, but the index starts at 0, so the last item should be offset by 1.
		// The queried item has a menu order of 100, but it is the last item on a page of 50,
		// so the expected index is 49, accounting for the offset.
		$this->assertEquals( 49, $this->_instance->get_queried_item_index() );
	}

	/**
	 * Test set_up_posts_per_page.
	 *
	 * @covers ::set_up_posts_per_page
	 */
	public function test_set_up_posts_per_page() {

		$this->set_up_posts();

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up();

		$this->assertEquals( 50, $this->_instance->set_up_posts_per_page( 8 ) );

		$this->assertEquals( 50, $this->_instance->set_up_posts_per_page() );

		$this->assertEquals( 50, $this->_instance->set_up_posts_per_page( 1001 ) );
		$this->assertEquals( 50, $this->_instance->set_up_posts_per_page( 500 ) );

		add_filter( 'pmc_lists_per_page', function() {
			return 9999;
		} );
		$this->assertEquals( 100, $this->_instance->set_up_posts_per_page() );

		add_filter( 'pmc_lists_per_page', function() {
			return -999;
		} );
		$this->assertEquals( 1, $this->_instance->set_up_posts_per_page() );

		add_filter( 'pmc_lists_per_page', function() {
			return 10.5;
		} );
		$this->assertEquals( 10, $this->_instance->set_up_posts_per_page() );
	}

	/**
	 * Test set_up_list_by_linked_list_item.
	 *
	 * @covers ::set_up_list_by_linked_list_item
	 */
	function test_set_up_list_by_linked_list_item() {

		$this->go_to( '/' );

		$this->_instance->set_up_list_by_linked_list_item();
		$this->assertNull( $this->_instance->get_list() );

		$this->set_up_posts();

		$this->go_to( get_the_permalink( $this->items[2] ) );

		$this->_instance->set_up_list_by_linked_list_item();

		$this->assertInstanceOf( \WP_Term::class, $this->_instance->get_term() );
		$this->assertInstanceOf( \WP_Post::class, $this->_instance->get_list() );

	}

	/**
	 * Test get_post_uncached.
	 *
	 * @covers ::get_post_uncached
	 */
	function test_get_post_uncached() {

		$this->assertEquals( [], $this->_instance->get_post_uncached() );
		$this->assertEquals( [], $this->_instance->get_post_uncached( [ 'post_type' => 'fake_post_type' ] ) );

		$this->set_up_posts();

		$list_query_args = [
			'post_type' => Lists::LIST_POST_TYPE,
		];

		$this->assertInstanceOf( \WP_Post::class, $this->_instance->get_post_uncached( $list_query_args ) );
	}

	/**
	 * Test get_list.
	 *
	 * @covers ::get_list
	 */
	public function test_get_list() {

		$this->set_up_posts();

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up();

		$this->assertInstanceOf( \WP_Post::class, $this->_instance->get_list() );
	}

	/**
	 * Test get_list_items.
	 *
	 * @covers ::get_list_items
	 */
	public function test_get_list_items() {

		$this->set_up_posts();

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up();

		$this->assertNotEmpty( $this->_instance->get_list_items() );
		$this->assertContainsOnlyInstancesOf( \WP_Post::class, $this->_instance->get_list_items() );
	}

	/**
	 * Test get_term.
	 *
	 * @covers ::get_term
	 */
	public function test_get_term() {

		$this->set_up_posts();

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up();

		$this->assertInstanceOf( \WP_Term::class, $this->_instance->get_term() );
	}

	/**
	 * Test get_list_items_count.
	 *
	 * @covers ::get_list_items_count
	 */
	public function test_get_list_items_count() {

		$this->set_up_posts();

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up();

		$this->assertEquals( 10, $this->_instance->get_list_items_count() );
	}

	/**
	 * Test get_order.
	 *
	 * @covers ::get_order
	 */
	public function test_get_order() {

		$this->set_up_posts();

		$this->_instance->set_up();

		$this->assertEquals( 'asc', $this->_instance->get_order() );
	}

	/**
	 * Test get_posts_per_page.
	 *
	 * @covers ::get_posts_per_page
	 */
	public function test_get_posts_per_page() {

		$this->set_up_posts();

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up();

		$this->assertEquals( 50, $this->_instance->get_posts_per_page() );
	}

	/**
	 * Test get_queried_item_index.
	 *
	 * @covers ::get_queried_item_index
	 */
	public function test_get_queried_item_index() {

		$this->set_up_posts();

		$this->_instance->set_up();

		$this->assertEquals( -1, $this->_instance->get_queried_item_index() );
	}

	/**
	 * Test get_current_page.
	 *
	 * @covers ::get_current_page
	 */
	public function test_get_current_page() {

		$this->set_up_posts();

		$this->_instance->set_up();

		$this->assertEquals( 1, $this->_instance->get_current_page() );
	}

	/**
	 * Test has_next_page.
	 *
	 * @covers ::has_next_page
	 */
	public function test_has_next_page() {

		$this->set_up_posts( 53 );

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up();

		$this->assertEquals( true, $this->_instance->has_next_page() );
	}

	/**
	 * Test get_next_page_number.
	 *
	 * @covers ::get_next_page_number
	 */
	public function test_get_next_page_number() {

		$this->set_up_posts();

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->assertEquals( 2, $this->_instance->get_next_page_number() );
	}

	/**
	 * Test get_list_url.
	 *
	 * @covers ::get_list_url
	 */
	public function test_get_list_url() {

		$this->set_up_posts();

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up();

		$this->assertContains( '/?pmc_list=a-list', $this->_instance->get_list_url() );

	}

	/**
	 * Test get_numbering.
	 *
	 * @covers ::get_numbering
	 */
	public function test_get_numbering() {

		$this->set_up_posts();

		$this->_instance->set_up();

		$this->assertEquals( 'asc', $this->_instance->get_numbering() );
	}

	/**
	 * Test get_previous_item
	 *
	 * @covers ::get_previous_item
	 */
	public function test_get_previous_item() {

		$this->set_up_posts( 55 );

		$this->go_to( get_the_permalink( reset( $this->items ) ) );
		$this->_instance->set_up();

		$this->assertFalse( $this->_instance->get_previous_item( 999999 ) );

		$this->assertFalse( $this->_instance->get_previous_item( $this->items[0] ) );

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$this->_instance->set_up();

		$this->assertEquals( get_post( $this->items[4] ), $this->_instance->get_previous_item( $this->items[5] ) );

		$this->go_to( get_the_permalink( $this->items[51] ) );
		$this->_instance->set_up();

		$this->assertEquals( get_post( $this->items[49] ), $this->_instance->get_previous_item( $this->items[50] ) );
	}

	/**
	 * Test get_next_item
	 *
	 * @covers ::get_next_item
	 */
	public function test_get_next_item() {

		$this->set_up_posts( 55 );

		$this->go_to( get_the_permalink( $this->list->ID ) );
		$this->_instance->set_up();

		$this->assertFalse( $this->_instance->get_next_item( 999999 ) );

		$this->assertFalse( $this->_instance->get_next_item( $this->_instance->get_list_items()[54]->ID ) );

		$this->assertEquals( $this->_instance->get_list_items()[6], $this->_instance->get_next_item( $this->_instance->get_list_items()[5]->ID ) );

		$this->assertEquals( get_post( $this->items[50] ), $this->_instance->get_next_item( $this->items[49] ) );

		$this->go_to( get_the_permalink( $this->items[51] ) );
		$this->_instance->set_up();

		$this->assertFalse( $this->_instance->get_next_item( $this->items[54] ) );
	}

	/**
	 * Test add_head_links
	 *
	 * @covers ::add_head_links
	 */
	public function test_add_head_links() {
		global $post;

		$this->set_up_posts();
		$this->_instance->set_up();

		// List post
		$this->go_to( get_the_permalink( $this->list->ID ) );
		$post = $this->list;
		setup_postdata( $post );

		ob_start();
		$this->_instance->set_up();
		$this->_instance->add_head_links();
		$html = ob_get_clean();
		$this->assertNotFalse( strpos( $html, '<link rel="next"' ) );

		// First list item.
		ob_start();
		$this->_instance->add_head_links( get_post( $this->items[0] ) );
		$html = ob_get_clean();
		$this->assertNotFalse( strpos( $html, '<link rel="next"' ) );
		$this->assertFalse( strpos( $html, '<link rel="prev"' ) );

		// Internal list item.
		ob_start();
		$this->_instance->add_head_links( get_post( $this->items[3] ) );
		$html = ob_get_clean();

		$this->assertNotFalse( strpos( $html, '<link rel="next"' ) );
		$this->assertNotFalse( strpos( $html, '<link rel="prev"' ) );

	}

	/**
	 * @covers ::add_adm_custom_keywords
	 */
	public function test_add_adm_custom_keywords() {

		$this->set_up_posts( 5 );
		$this->go_to( get_the_permalink( $this->items[2] ) );
		$this->_instance->set_up();

		$this->assertNotEmpty( $this->list->ID );

		$keywords = apply_filters( 'pmc_adm_custom_keywords', [] );
		$this->assertEmpty( $keywords );

		$args = [
			'taxonomy' => 'category',
			'name'     => 'categoryone',
		];

		$cat_id = $this->factory->term->create( $args );

		$this->factory->term->add_post_terms( $this->list->ID, $cat_id, 'category' );
		$this->factory->term->add_post_terms( $this->list->ID, 'tagone', 'post_tag' );

		$keywords = apply_filters( 'pmc_adm_custom_keywords', [] );

		$this->assertNotEmpty( $keywords );
		$this->assertEquals( 2, count( $keywords ) );

	}

	/**
	 * @covers ::maybe_exclude_googlebot_news_tag
	 */
	public function test_maybe_exclude_googlebot_news_tag() {

		$gn_exclude = apply_filters( 'pmc_seo_tweaks_googlebot_news_override', false );

		$this->assertFalse( $gn_exclude );

		$this->set_up_posts();

		$this->go_to( get_the_permalink( $this->list->ID ) );

		$gn_exclude = apply_filters( 'pmc_seo_tweaks_googlebot_news_override', false );

		$this->assertTrue( $gn_exclude );
	}

	/**
	 * @covers ::filter_pmc_adm_google_publisher_slot
	 */
	public function test_filter_pmc_adm_google_publisher_slot() {

		$this->set_up_posts( 5 );

		$args = [
			'taxonomy' => 'category',
			'name'     => 'parent',
		];

		$parent_cat_id = $this->factory->term->create( $args );

		$args = [
			'taxonomy' => 'category',
			'name'     => 'child',
			'parent'   => $parent_cat_id,
		];

		$child_cat_id = $this->factory->term->create( $args );

		$this->factory->term->add_post_terms( $this->list->ID, $child_cat_id, 'category' );

		$this->go_to( get_the_permalink( $this->items[2] ) );

		$this->_instance->set_up();

		$slot = apply_filters( 'pmc_adm_google_publisher_slot', '12345/site/list/adlocation' );

		$this->assertContains( 'parent', $slot );

	}

	/**
	 * @covers ::filter_adm_topic_keywords
	 */
	public function test_filter_adm_topic_keywords() {

		$this->set_up_posts( 5 );

		register_taxonomy(
			'editorial',
			[
				'post',
				'pmc_list'
			],
			[
			'public'  => true,
			'show_ui' => true,
		] );

		$term = $this->factory()->term->create_and_get( [ 'taxonomy' => 'editorial' ] );
		$expected = [ $term->slug ];

		wp_set_post_terms( $this->list->ID, array( $term->term_id ), 'editorial', false );

		$this->go_to( get_the_permalink( $this->items[2] ) );

		$this->_instance->set_up();

		$keywords = apply_filters( 'pmc_adm_topic_keywords', [], [ 'editorial' ], [] );

		$this->assertEqualSets( $expected, $keywords );

	}

}
