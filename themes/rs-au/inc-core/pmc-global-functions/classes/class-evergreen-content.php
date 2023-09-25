<?php
/**
 * To add evergreen content config in page meta for event tracking.
 *
 * @author Vishal Dodiya <vishal.dodiya@rtcamp.com>
 *
 * @since 2018-05-25 READS-1155
 */

namespace PMC\Global_Functions;

use PMC\Global_Functions\Traits\Singleton;
use PMC_Cache;
use WP_Post;

class Evergreen_Content {

	use Singleton;

	const SLUG        = 'evergreen-content';
	const CACHE_GROUP = 'evergreen_content';
	const CACHE_LIFE  = HOUR_IN_SECONDS * 12;

	/**
	 * Class Constructor.
	 *
	 * @codeCoverageIgnore
	 */
	protected function __construct() {
		$this->_setup_hooks();
	}

	/**
	 * Setup filters and actions.
	 *
	 * @return void
	 */
	protected function _setup_hooks() : void {

		/**
		 * Actions.
		 */

		add_action( 'init', [ $this, 'add_default_term' ] );
		add_action( 'init', [ $this, 'rewrite_evergreen_post_link' ] );
		add_action( 'template_redirect', [ $this, 'redirect_old_evergreen_urls' ] );
		add_action( 'save_post_post', [ $this, 'action_post_save_clear_cache' ], 10, 3 );

		/**
		 * Filters.
		 */
		add_filter( 'pmc_post_options_custom_dims', [ $this, 'maybe_add_custom_dims' ] );
		add_filter( 'pre_post_link', [ $this, 'filter_evergreen_post_link' ], 11, 3 );
		add_filter( 'post_type_link', [ $this, 'filter_evergreen_post_link' ], 11, 3 );
		add_filter( 'pre_pmc_vertical_permalink_tag', [ $this, 'filter_pre_pmc_vertical_permalink_tag' ], 10, 5 );

	}

	/**
	 * Redirect old urls with date to new urls without date.
	 */
	public function redirect_old_evergreen_urls() {

		global $wp;
		$post = get_post();

		// Bail out if its not single post, or removing dates from permalink is disabled, or post type doesn't support Post Options.
		if (
			empty( $post )
			|| ! is_singular( 'post' )
			|| is_preview()
			|| '/' === wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) // @codingStandardsIgnoreLine
			|| ! $this->_should_update_permalink_structure()
			|| preg_match( '#^feature/#', $wp->request )
			|| 'post' !== $post->post_type
			|| ! $this->_is_post_evergreen( $post )
		) {
			return;
		}

		$requested_permalink = trailingslashit( home_url( $wp->request ) );
		$new_permalink       = trailingslashit( get_permalink( $post ) );

		if ( \PMC::is_amp() ) {
			// Ignore this for now, there is no current proper mocking for amp entpoint for testing at the moment
			$new_permalink .= 'amp/'; // @codeCoverageIgnore
		} else {
			$page = get_query_var( 'page' );
			if ( ! empty( $page ) ) {
				$new_permalink .= $page . '/';
			}
		}

		if ( $requested_permalink !== $new_permalink ) {
			wp_safe_redirect( $new_permalink, '301' );
			exit(); // @codeCoverageIgnore
		}

	}

	/**
	 * Add evergreen-content to custom dimension.
	 *
	 * @param array $slug_array
	 *
	 * @return array
	 */
	public function maybe_add_custom_dims( array $slug_array ) : array {
		$slug_array[] = self::SLUG;

		return $slug_array;
	}

	/**
	 * Method to add post option for Evergreen Content.
	 *
	 * @return void
	 */
	public function add_default_term() {

		// Checking Taxonomy class because it is loaded by post options plugin when plugin is loaded.
		// Autoloading is false to prevent PHP from loading class, make sure plugin does that job.
		if ( class_exists( '\PMC\Post_Options\Taxonomy', false ) ) {
			\PMC\Post_Options\API::get_instance()->register_global_options(
				[
					self::SLUG => [
						'label'       => 'Evergreen Content',
						'description' => 'Posts with this term will be set as Evergreen Content.',
					],
				]
			);

		}
	}

	/**
	 * Alter the permalink for the evergreen posts. Removes '%year%', '%monthnum%' from URL. SADE-300
	 * Use '/feature/%postname%/' for evergreen posts.
	 *
	 * @param string   $permalink The post's permalink structure.
	 * @param \WP_Post $post      The post in question.
	 *
	 * @return string Permalink structures for post.
	 */
	public function filter_evergreen_post_link( $permalink, $post, $leavename ) : string {

		/**
		 * Filter to allow removing dates from permalink for Evergreen Content.
		 */
		if ( ! $this->_should_update_permalink_structure() ) {
			return $permalink;
		}

		if ( is_a( $post, 'WP_Post' ) && 'post' === $post->post_type && true === $this->_is_post_evergreen( $post ) ) {
			$permalink = $this->_get_permalink_without_date( $post, $leavename );
		}

		return $permalink;
	}

	/**
	 * Fires on save_post_post to purge permalink cache.
	 *
	 * @param int      $post_id Post ID of post being saved.
	 * @param \WP_Post $post    Post Object.
	 * @param bool     $update  Whether this is an existing post being updated or not.
	 */
	public function action_post_save_clear_cache( $post_id, $post, $update ) {
		$this->_is_post_evergreen( $post, true );
	}

	/**
	 * Determine whether the post is Evergreen or not.
	 *
	 * @param \WP_Post $post          The post in question.
	 * @param bool     $refresh_cache Whether to refresh the cache or not.
	 *
	 * @return bool
	 */
	private function _is_post_evergreen( $post, $refresh_cache = false ) : bool {

		$key   = $post->ID . '_evergreen-content';
		$cache = new PMC_Cache( $key, self::CACHE_GROUP );

		$cache
			->expires_in( self::CACHE_LIFE )
			->updates_with(
				[ $this, 'is_post_evergreen_uncached' ],
				[ $post ]
			);

		if ( $refresh_cache ) {
			$cache->invalidate();
		}

		return $cache->get();
	}

	/**
	 * Determine whether $post is Evergreen Post.
	 *
	 * @param \WP_Post $post The Post object.
	 *
	 * @return bool
	 */
	public function is_post_evergreen_uncached( $post ) : bool {
		return \PMC\Post_Options\API::get_instance()->post( $post->ID )->has_option( 'evergreen-content' );
	}

	/**
	 * Get permalink for Evergreen Post without date in it.
	 *
	 * @param \WP_Post $post The Post object.
	 *
	 * @return string Returns permalink without date in it.
	 */
	private function _get_permalink_without_date( WP_Post $post, $leavename = true ) : string {
		return sprintf( '/feature/%1$s-%2$s/', $leavename ? '%postname%' : $post->post_name, $post->ID );
	}

	/**
	 * Rewrite for evergreen content - SADE-300
	 */
	public function rewrite_evergreen_post_link() {
		add_rewrite_rule( 'feature/([^/]+)-(\d+)/amp(/(.*))?/?$', 'index.php?post_type=post&name=$matches[1]&p=$matches[2]&amp=$matches[3]', 'top' );
		add_rewrite_rule( 'feature/([^/]+)-(\d+)(?:/(\d+)/?)?', 'index.php?post_type=post&name=$matches[1]&p=$matches[2]&page=$matches[3]', 'top' );
	}

	/**
	 * Prevent pmc-vertical plugin from overriding the permalink structure.
	 *
	 * @param bool     $override   Current value of whether to disable permalink overriding or not.
	 * @param string   $permalink  Permalink being overridden.
	 * @param \WP_Post $post       Post object for which the permalink is being overridden.
	 * @param bool     $leavename  Whether to keep the post name.
	 * @param bool     $canonical Whether to modify permalink for canonical urls.
	 *
	 * @return mixed
	 */
	public function filter_pre_pmc_vertical_permalink_tag( string $override, string $permalink, $post, $leavename, $canonical ) : string {

		/**
		 * If the $post is Evergreen and updating permalink structure is enabled then return $permalink as it is,
		 * because $this->filter_evergreen_post_link() function would have already taken care of the permalink structure for Evergreen on pre_post_link filter.
		 */
		if ( $this->_should_update_permalink_structure() && $this->_is_post_evergreen( $post ) ) {
			return $permalink;
		}

		return $override;
	}

	/**
	 * Helper function to determine whether to change the permalink structure for Evergreen Articles.
	 *
	 * @return bool
	 */
	private function _should_update_permalink_structure() : bool {

		/**
		 * Filter to enable / disable updating permalink structure for Evergreen Content.
		 */
		return (bool) apply_filters( 'pmc_evergreen_content_remove_permalink_dates', false );
	}

}
