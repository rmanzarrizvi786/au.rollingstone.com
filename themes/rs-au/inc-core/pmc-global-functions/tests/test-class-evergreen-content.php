<?php
namespace PMC\Global_Functions\Tests;

use ParagonIE\Sodium\Core\Util;
use PMC\Unit_Test\Utility;
use PMC\Global_Functions\Evergreen_Content;

/**
 * @group pmc-global-functions
 * Unit test cases for \PMC\Global_Functions\Evergreen_Content class
 *
 * @author Vishal Dodiya <vishal.dodiya@rtcamp.com>
 *
 * @since 2018-05-25 READS-1155
 * @coversDefaultClass PMC\Global_Functions\Evergreen_Content
 */
class Test_Evergreen_Content extends Base {

	/**
	 * @var \PMC\Global_Functions\Evergreen_Content
	 */
	protected $_instance;

	public function setUp() {
		// to speed up unit test, we bypass files scanning on upload folder.
		self::$ignore_files = true;
		parent::setUp();

		$this->_instance = \PMC\Global_Functions\Evergreen_Content::get_instance();
	}

	/**
	 * @covers ::_setup_hooks()
	 */
	public function test__setup_hooks() {
		Utility::invoke_hidden_method( $this->_instance, '_setup_hooks' );

		$hooks = [

			[
				'priority' => 10,
				'hook'     => 'init',
				'callback' => [ $this->_instance, 'add_default_term' ],
			],

			[
				'priority' => 10,
				'hook'     => 'pmc_post_options_custom_dims',
				'callback' => [ $this->_instance, 'maybe_add_custom_dims' ],
			],

		];

		foreach ( $hooks as $hook ) {

			if ( is_array( $hook['callback'] ) && is_string( $hook['callback'][1] ) ) {
				$callback = $hook['callback'][1];
			} else {
				$callback = $hook['callback'];
			}

			$error_string = sprintf(
				'Callback "%1$s" not setup for hook %2$s at priority %3$s',
				$callback,
				$hook['hook'],
				$hook['priority']
			);

			$this->assertEquals( $hook['priority'], has_filter( $hook['hook'], $hook['callback'] ), $error_string );

		}
	}

	/**
	 * @covers ::maybe_add_custom_dims()
	 */
	public function test_maybe_add_custom_dims() {
		$slugs = apply_filters( 'pmc_post_options_custom_dims', [] );
		$this->assertContains( 'evergreen-content', $slugs );
	}

	/**
	 * @covers ::add_default_term()
	 */
	public function test_add_default_term() {
		register_taxonomy( '_post-options', [ 'post' ] );
		$this->_instance->add_default_term();
		$this->go_to( '/' );

		$this->assertGreaterThan( 0, wpcom_vip_term_exists( 'evergreen-content', '_post-options', wpcom_vip_term_exists( 'global-options', '_post-options' ) ) );
	}

	/**
	 * @covers ::action_post_save_clear_cache
	 * @covers ::_is_post_evergreen
	 * @covers ::is_post_evergreen_uncached
	 */
	public function test_action_post_save_clear_cache() {

		$p = $this->factory->post->create_and_get( [ 'post_title' => 'One' ] );

		$this->_mocker->mock_global_wp_query(
			[
				'is_single'         => true,
				'queried_object_id' => $p->ID,
				'queried_object'    => get_post( $p ),
			]
		);

		register_taxonomy( '_post-options', [ 'post' ] );
		wp_set_post_terms( $p->ID, [ 'example', 'evergreen-content' ], '_post-options' );

		wp_cache_flush();

		$GLOBALS['post'] = get_post( $p );

		// Set the cache.
		$expected_output = Utility::invoke_hidden_method( $this->_instance, '_is_post_evergreen', [ $p ] );

		$cache_key = md5( $p->ID . '_evergreen-content' );

		$cached_output = wp_cache_get( $cache_key, $this->_instance::CACHE_GROUP );

		$this->assertEquals( $expected_output, $cached_output );

		wp_cache_flush();

		wp_update_post(
			[
				'ID'         => $p->ID,
				'post_title' => 'Updated',
			]
		);

		$this->assertTrue( wp_cache_get( $cache_key, $this->_instance::CACHE_GROUP ) );

	}

	/**
	 * @covers ::redirect_old_evergreen_urls
	 */
	public function test_redirect_old_evergreen_urls() {

		// When its not a single post request.
		$this->assertEmpty( $this->_instance->redirect_old_evergreen_urls() );

		$p = $this->factory->post->create_and_get( [
			'post_title' => 'One',
			'post_content' => "This is first page content\n<!--nextpage-->\nThis is second page content\n<!--nextpage-->\nThis is 3rd page content\n",
		] );

		$this->_mocker->mock_global_wp_query(
			[
				'is_single'         => true,
				'queried_object_id' => $p->ID,
				'queried_object'    => get_post( $p ),
			]
		);

		$GLOBALS['post'] = get_post( $p );

		global $wp, $wp_rewrite;

		$wp_rewrite->set_permalink_structure( '/%year%/%category%/%postname%-%post_id%/' );
		$wp_rewrite->flush_rules();

		$wp->request = '2019/uncategorized/one-' . $p->ID;

		$this->assertEmpty( $this->_instance->redirect_old_evergreen_urls() );

		register_taxonomy( '_post-options', [ 'post' ] );
		wp_set_post_terms( $p->ID, [ 'example', 'evergreen-content' ], '_post-options' );
		$terms = wp_get_post_terms( $p->ID, '_post-options' );
		$this->assertContains( 'evergreen', print_r( $terms, true ) );

		wp_cache_flush();

		add_filter( 'pmc_evergreen_content_remove_permalink_dates', '__return_true' );

		$this->assert_not_redirect(
			function() use ( $p ) {
				$this->go_to( home_url( '/2019/uncategorized/one-' . $p->ID ) );
				$this->_instance->redirect_old_evergreen_urls();
			}
		);

		$this->assert_not_redirect(
			function() use ( $p ) {
				$this->go_to( home_url( '/2019/uncategorized/one-' . $p->ID . '/2/' ) );
				$this->_instance->redirect_old_evergreen_urls();
			}
		);

		register_taxonomy( '_post-options', [ 'post' ] );
		wp_set_post_terms( $p->ID, [ 'example', 'evergreen-content' ], '_post-options' );
		$key   = $post->ID . '_evergreen-content';
		$cache = new \PMC_Cache( $key, Evergreen_Content::CACHE_GROUP );
		$cache->invalidate();

		$this->assert_redirect_to(
			home_url( 'feature/one-' . $p->ID ) . '/',
			function() use ( $p ) {
				$this->go_to( home_url( '/2019/?p=' . $p->ID ) );
				$this->_instance->redirect_old_evergreen_urls();
			},
			301
		);

		$this->assert_redirect_to(
			home_url( 'feature/one-' . $p->ID ) . '/2/',
			function() use ( $p ) {
				$this->go_to( home_url( '/2019/?page=2&p=' . $p->ID ) );
				$this->_instance->redirect_old_evergreen_urls();
			},
			301
		);

	}

	/**
	 * @covers ::filter_evergreen_post_link
	 * @covers ::_get_permalink_without_date
	 * @covers ::_should_update_permalink_structure
	 */
	public function test_filter_evergreen_post_link() {

		Utility::unset_singleton( \PMC\Global_Functions\Evergreen_Content::class );

		remove_all_actions( 'init' );
		remove_all_filters( 'pmc_post_options_custom_dims' );
		remove_all_filters( 'post_link' );
		remove_all_filters( 'post_type_link' );

		$GLOBALS['wp_rewrite']->set_permalink_structure( '/%year%/%monthnum%/%postname%-%post_id%/' );
		flush_rewrite_rules();

		$post_ID = $this->factory->post->create(
			[
				'post_title'   => 'lorem_ipsum',
				'post_content' => 'Lorem ipsum',
			]
		);

		$default_link = get_permalink( $post_ID );

		$this->assertContains( '/lorem_ipsum-' . $post_ID, $default_link );

		$instance = \PMC\Global_Functions\Evergreen_Content::get_instance();

		register_taxonomy( '_post-options', [ 'post' ] );
		wp_set_post_terms( $post_ID, [ 'example', 'evergreen-content' ], '_post-options' );
		$terms = wp_get_post_terms( $post_ID, '_post-options' );
		$this->assertContains( 'evergreen', print_r( $terms, true ) );

		wp_cache_flush();

		add_filter( 'pmc_evergreen_content_remove_permalink_dates', '__return_true' );

		$new_link = get_permalink( $post_ID );

		$this->assertNotEquals( $default_link, $new_link );
		$this->assertContains( '/feature/lorem_ipsum', $new_link );

		// Let remove the evergreen content post option and verify the post no longer have it
		wp_remove_object_terms( $post_ID, [ 'example', 'evergreen-content' ], '_post-options' );
		$terms = wp_get_post_terms( $post_ID, '_post-options' );
		$this->assertNotContains( 'evergreen', print_r( $terms, true ) );

		// This link should be coming from cache
		$link = get_permalink( $post_ID );
		$this->assertEquals( $new_link, $link );
		$this->assertNotEquals( $default_link, $link );

		// Let clear cache, we should get back the original default link
		$cache = new \PMC_Cache( $post_ID . '_evergreen-content', \PMC\Global_Functions\Evergreen_Content::CACHE_GROUP );
		$cache->invalidate();
		$link = get_permalink( $post_ID );
		$this->assertEquals( $default_link, $link );
		$this->assertNotEquals( $new_link, $link );

	}

	/**
	 * @covers ::rewrite_evergreen_post_link()
	 */
	public function test_rewrite_evergreen_post_link() {

		global $wp_rewrite;

		Utility::unset_singleton( \PMC\Global_Functions\Evergreen_Content::class );
		remove_all_actions( 'init' );
		$instance = \PMC\Global_Functions\Evergreen_Content::get_instance();
		$this->assertTrue( 0 < has_action( 'init', [ $instance, 'rewrite_evergreen_post_link' ] ) );

		do_action( 'init' );

		$this->assertArraySubSet( [ 'feature/([^/]+)-(\d+)(?:/(\d+)/?)?' => 'index.php?post_type=post&name=$matches[1]&p=$matches[2]&page=$matches[3]' ], $wp_rewrite->extra_rules_top );
		$this->assertArraySubSet( [ 'feature/([^/]+)-(\d+)/amp(/(.*))?/?$' => 'index.php?post_type=post&name=$matches[1]&p=$matches[2]&amp=$matches[3]' ], $wp_rewrite->extra_rules_top );

	}

	/**
	 * @covers ::filter_pre_pmc_vertical_permalink_tag
	 */
	public function test_filter_pre_pmc_vertical_permalink_tag() {

		$p         = $this->factory->post->create_and_get();
		$permalink = get_permalink( $p );
		$leavename = false;
		$canonical = false;

		// When updating permalink is disabled.
		$this->assertEmpty( apply_filters( 'pre_pmc_vertical_permalink_tag', '', $permalink, $p, $leavename, $canonical ) );

		/**
		 * When updating permalink is enabled but post is not Evergreen.
		 */

		// Enable updating permalink structure.
		add_filter( 'pmc_evergreen_content_remove_permalink_dates', '__return_true' );

		$this->assertEmpty( apply_filters( 'pre_pmc_vertical_permalink_tag', '', $permalink, $p, $leavename, $canonical ) );

		/**
		 * When updating permalinking structure is enabled and post is evergreen.
		 */

		// Make the post Evergreen.
		register_taxonomy( '_post-options', [ 'post' ] );
		wp_set_post_terms( $p->ID, [ 'example', 'evergreen-content' ], '_post-options' );

		wp_cache_flush();

		$this->assertEquals( $permalink, apply_filters( 'pre_pmc_vertical_permalink_tag', '', $permalink, $p, $leavename, $canonical ) );

	}
}
