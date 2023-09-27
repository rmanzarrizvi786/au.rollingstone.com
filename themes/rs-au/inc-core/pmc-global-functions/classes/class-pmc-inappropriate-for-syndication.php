<?php
/**
 * This class is added to show a checkbox, on add/edit post page in wpadmin, in the metabox
 * containing Update/Publish controls, to allow a post to be excluded from partner feeds
 */

class PMC_Inappropriate_For_Syndication extends PMC_Singleton {

	/**
	 * @var String Key name for the toggle flag
	 */
	const FLAG = '_pmc_inappropriate_for_syndication';

	/**
	 * @var String Key value for the toggle flag
	 */
	const FLAG_VALUE = 'yes';

	/**
	 * @var String Unique plugin ID, ideal for use as cache key as well
	 */
	const PLUGIN_ID = 'pmc-inappropriate-for-syndication';

	/**
	 * @var integer Maximum number of posts to exclude
	 */
	const EXCLUSION_LIMIT = 100;

	/**
	 * @var integer Cache life in seconds
	 */
	const CACHE_LIFE = 1800; //30 minutes

	/**
	 * @var Array Array containing nonce action and name
	 */

	protected $_nonce = array (
		'action' => 'pmc_inappropriate_for_syndication',
		'name' => 'pmc_ifs_nonce'
	);

	/*
	 * @var Array Array containing feed slugs that need to bypass the flag inappropriate for syndication
	 * This is used for sailthru feeds since we want all posts to show in sailthru feed
	 * ir-respective of the flag present or not
	 * @since 2014-11-26 Archana Mandhare
	 * @ticket PPT-3683
	 *
	 */
	protected $_bypass_feeds = array ( 'sailthru' );
	protected $_exclude_post_types = array ( 'sailthru_fast', 'sailthru_recurring', 'pmc-long-options' );

	/**
	 * Class initialization routine
	 *
	 * @return void
	 */
	protected function _init () {
		$this->_setup_hooks();
	}

	/**
	 * Setup hooks and listeners
	 *
	 * @return void
	 */
	protected function _setup_hooks () {
		define( 'PMC_INAPPROPRIATE_FOR_SYNDICATION_FLAG', true );

		/*
		 * Actions
		 */
		add_action( 'post_submitbox_misc_actions', array ( $this, 'show_inappropriate_for_syndication_flag' ) );
		add_action( 'save_post', array ( $this, 'save_inappropriate_for_syndication_flag' ) );

		/*
		 * Filters
		 */
		add_filter( 'pre_get_posts', array ( $this, 'exclude_inappropriate_for_syndication_posts' ) );
		add_filter( 'pmc_custom_feed_post_start', array ( $this, 'exclude_flagged_post_from_feed' ), 1, 2 );
	}

	/**
	 * This function shows a checkbox, on add/edit post page in wpadmin, in the metabox
	 * containing Update/Publish controls, to allow a post to be excluded from partner feeds
	 *
	 * @since 2012-11-15 Amit Gupta
	 * @version 2012-11-16 Amit Gupta
	 * @version 2014-11-21 Archana Mandhare
	 */
	public function show_inappropriate_for_syndication_flag () {

		$excluded_post_types = apply_filters( 'pmc_inappropriate_for_syndication_exclude_types', $this->_exclude_post_types );

		if ( empty( $GLOBALS[ 'post' ] ) || !is_object( $GLOBALS[ 'post' ] ) || !isset( $GLOBALS[ 'post' ]->ID ) || in_array( $GLOBALS[ 'post' ]->post_type, $excluded_post_types ) ) {
			return;
		}

		$flag = get_post_meta( $GLOBALS[ 'post' ]->ID, self::FLAG, true );
		$flag = ( self::FLAG_VALUE !== $flag ) ? false : true;
		//allow LOBs to default the value of the inappropiate for syndication flag on new posts.
		if( isset( $GLOBALS[ 'post' ]->post_status ) && $GLOBALS[ 'post' ]->post_status == "auto-draft" ){
			$set_default_true = apply_filters( 'pmc_inappropiate_for_syndication_true', false );
			if( $set_default_true ){
				$flag = ( self::FLAG_VALUE !== $flag ) ? true : false;
			}
		}

		?>
		<div class="misc-pub-section">
			<?php wp_nonce_field( $this->_nonce[ 'action' ], $this->_nonce[ 'name' ] ); ?>
			<label>
				<input type="checkbox" value="<?php echo esc_attr( self::FLAG_VALUE ); ?>" name="<?php echo esc_attr( $this->_nonce[ 'action' ] ) ?>" <?php checked( $flag, true ); ?> /> Inappropriate for Syndication
			</label>
		</div>
		<?php
	}

	/**
	 * This function saves the flag as meta value if flag is checked on a post
	 * else deletes the flag from post meta
	 *
	 * @since 2012-11-15 Amit Gupta
	 * @version 2012-11-16 Amit Gupta
	 * @version 2013-06-04 Amit Gupta
	 * @version 2014-11-21 Archana Mandhare
	 * @version 2017-08-31 Archana Mandhare
	 */
	public function save_inappropriate_for_syndication_flag ( $post_id ) {

		if ( empty( $post_id ) ) {
			return;
		}

		$excluded_post_types = apply_filters( 'pmc_inappropriate_for_syndication_exclude_types', $this->_exclude_post_types );

		if ( in_array( get_post_type( $post_id ), $excluded_post_types ) ) {
			return;
		}

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) || ( defined( 'WPCOM_DOING_REBLOG' ) && true === WPCOM_DOING_REBLOG ) ) {
			return;
		}

		if ( empty( $_POST[ $this->_nonce[ 'name' ] ] ) || !wp_verify_nonce( $_POST[ $this->_nonce[ 'name' ] ], $this->_nonce[ 'action' ] ) ) {
			return;
		}

		$excluded_posts = $this->get_excluded_posts();
		$rebuild_cache = false; //assume we don't want to flush excluded post IDs cache

		if ( isset( $_POST[ $this->_nonce[ 'action' ] ] ) && $_POST[ $this->_nonce[ 'action' ] ] == self::FLAG_VALUE ) {
			update_post_meta( $post_id, self::FLAG, self::FLAG_VALUE );

			if ( !in_array( $post_id, $excluded_posts ) ) {
				$rebuild_cache = true;
			}
		} else {
			delete_post_meta( $post_id, self::FLAG );
			if ( ! empty( $excluded_posts ) && is_array( $excluded_posts ) && in_array( $post_id, $excluded_posts ) ) {
				//flag removed but post ID is in excluded posts cache, so we'll rebuild it
				$rebuild_cache = true;
			}
		}

		if ( $rebuild_cache === true ) {

			$pmc_cache = new PMC_Cache( self::PLUGIN_ID );

			$pmc_cache->invalidate()
					->expires_in( self::CACHE_LIFE )
					->updates_with( array ( $this, 'get_excluded_posts_from_db' ) )
					->get();
		}
		unset( $rebuild_cache, $excluded_posts );
	}

	/**
	 * This function excludes posts flagged as inappropriate for syndication
	 * This is used for custom feeds & posts fetched in mmc-jrc plugin, both of
	 * them set the global var $pmc_inappropriate_for_syndication as TRUE
	 * before they query the posts
	 *
	 * @since 2012-11-15 Amit Gupta
	 * @version 2012-11-17 Amit Gupta
	 * @version 2014-11-21 Archana Mandhare
	 */
	public function exclude_inappropriate_for_syndication_posts ( $query ) {

		$should_bypass = false;

		if ( defined( 'PMC_INAPPROPRIATE_FOR_SYNDICATION_FLAG' ) && PMC_INAPPROPRIATE_FOR_SYNDICATION_FLAG ) {
			if ( isset( $GLOBALS['wp_query'] ) && is_object( $GLOBALS['wp_query'] ) && is_feed() ) {
				$GLOBALS['pmc_inappropriate_for_syndication'] = true;
			}
		}

		if ( !is_a( $query, 'WP_Query' ) ) {
			return;
		}

		if ( !isset( $GLOBALS[ 'pmc_inappropriate_for_syndication' ] ) || $GLOBALS[ 'pmc_inappropriate_for_syndication' ] !== true ) {
			unset( $GLOBALS[ 'pmc_inappropriate_for_syndication' ] );
			return;
		}

		if ( is_feed() ) {

			$should_bypass = true;

			$feed_slug = get_query_var( 'feed' );
			$feeds_to_bypass = apply_filters( 'pmc_inappropriate_for_syndication_bypass', $this->_bypass_feeds );

			if ( !empty( $feeds_to_bypass ) && in_array( $feed_slug, $feeds_to_bypass ) ) {
				unset( $should_bypass );
				return;
			}
		}

		if ( $should_bypass !== true ) {
			unset( $should_bypass );
			return;
		}

		$post_not_in = $query->get( 'post__not_in' );
		$posts_to_exclude = $this->get_excluded_posts();

		if ( empty( $posts_to_exclude ) || !is_array( $posts_to_exclude ) ) {
			unset( $should_bypass );
			return;
		}

		if ( is_array( $post_not_in ) ) {
			$post_not_in = array_filter( array_unique( array_map( 'intval', array_merge( $post_not_in, $posts_to_exclude ) ) ) );
		} else {
			$post_not_in = $posts_to_exclude;
		}

		$query->set( 'post__not_in', $post_not_in );

		unset( $should_bypass ); //unset the flag as we don't need it anymore in current scope
		unset( $GLOBALS[ 'pmc_inappropriate_for_syndication' ] );
	}

	/**
	 * This function returns the IDs of posts marked from exclusion. It returns
	 * cached data and is safe to use.
	 *
	 * @return array Returns an array containing IDs of posts marked inappropriate for syndication
	 */
	public function get_excluded_posts () {
		$pmc_cache = new PMC_Cache( self::PLUGIN_ID );

		return $pmc_cache->expires_in( self::CACHE_LIFE )
						->updates_with( array ( $this, 'get_excluded_posts_from_db' ) )
						->get();
	}

	/**
	 * This function returns the IDs of posts marked inappropriate for syndication. It returns
	 * non cached data, be careful with this function.
	 *
	 * @return array Returns an array containing IDs of posts marked for exlusion from Home river
	 */
	public function get_excluded_posts_from_db () {
		$sql = "SELECT DISTINCT post_id FROM " . $GLOBALS[ 'wpdb' ]->postmeta . " WHERE meta_key=%s AND meta_value=%s ORDER BY meta_id DESC LIMIT 0, %d";

		$post_ids = $GLOBALS[ 'wpdb' ]->get_col( $GLOBALS[ 'wpdb' ]->prepare( $sql, self::FLAG, self::FLAG_VALUE, self::EXCLUSION_LIMIT ) );

		if ( !empty( $post_ids ) ) {
			$post_ids = array_filter( array_unique( array_map( 'intval', ( array ) $post_ids ) ) );
		} else {
			$post_ids = array ();
		}

		return $post_ids;
	}

	/**
	 * @param mixed $post The post object or ID to check for exlusion
	 * @return bool True if post is flagged for exclusion
	 */
	public function is_exclude( $post ) {
		$post = get_post( $post );
		if ( !empty( $post ) ) {
			return self::FLAG_VALUE == get_post_meta( $post->ID, self::FLAG, true );
		}
		return false;
	}

	/**
	 * This function is called on 'pmc_custom_feed_post_start' filter and
	 * returns boolean FALSE if the post object passed to it is flagged as
	 * inappropriate for syndication else it returns the post object as is.
	 *
	 * @ticket PPT-4186
	 * @since 2015-02-11 Amit Gupta
	 *
	 * @param WP_Post $post Object of current post being rendered in a feed generated by custom feeds plugin
	 * @param array $feed_options
	 * @return boolean|WP_Post
	 */
	public function exclude_flagged_post_from_feed( $post, $feed_options = array() ) {
		if ( empty( $post ) || ! is_a( $post, 'WP_Post' ) ) {
			//not a post object, bail out by returning FALSE
			//to skip any further op on this non-object
			return false;
		}

		if ( $this->is_exclude( $post ) ) {
			//this post is marked as inappropriate for syndication,
			//so skip any further op on this post
			return false;
		}

		return $post;
	}

}	//end of class


PMC_Inappropriate_For_Syndication::get_instance();

// EOF
