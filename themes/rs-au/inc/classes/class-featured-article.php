<?php
/**
 * Featured Article.
 *
 * Functionality related to featured articles.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-09
 */

namespace Rolling_Stone\Inc;

use PMC\Global_Functions\Traits\Singleton;
use PMC\Post_Options\Api as Post_Options;
use PMC\Publication_Issue_V2\Publication_Issue;

class Featured_Article {

	use Singleton;

	/**
	 * The name of the featured article option.
	 */
	const OPTION_NAME = 'rollingstone_featured_article';

	/**
	 * A unique ID for the article's styled heading metabox.
	 *
	 * @var string
	 */
	const STYLED_HEADING_ID = 'rollingstone_featured_article_styled_heading';

	/**
	 * Whether or not the current post is a featured article.
	 *
	 * @var null|boolean
	 */
	private $_is_featured_article;

	/**
	 * Class constructor.
	 */
	protected function __construct() {

		add_action( 'init', array( $this, 'register_option' ) );
		add_action( 'init', array( $this, 'register_styled_heading' ) );
		add_action( 'template_redirect', array( $this, 'setup_post_hooks' ) );

		if ( class_exists( '\PMC\Styled_Heading\Styled_Heading' ) ) {
			add_filter( \PMC\Styled_Heading\Styled_Heading::FILTER_PREFIX . 'show_meta_box', array( $this, 'filter_do_styled_heading' ) );
		}

		add_filter( 'pmc_core_injection_args', array( $this, 'remove_injected_related_card' ) );
		add_filter( 'pmc_core_injection_args', array( $this, 'inject_promo_card' ) );

	}

	/**
	 * Removes the automatically injected related component.
	 *
	 * @param array $injected_elements An array of injected components.
	 * @return array The filtered array.
	 */
	public function remove_injected_related_card( $injected_elements ) {

		if ( ! $this->is_featured_article() ) {
			return $injected_elements;
		}

		if ( isset( $injected_elements['related'] ) ) {
			unset( $injected_elements['related'] );
		}

		return $injected_elements;

	}

	/**
	 * Adds the promo card to the array of injected components.
	 *
	 * @param array $injected_elements An array of injected components.
	 * @return array The filtered components.
	 */
	public function inject_promo_card( $injected_elements ) {

		if ( ! $this->is_featured_article() ) {
			return $injected_elements;
		}

		$injected_elements['promo'] = array(
			'pos'      => 100,
			'min'      => 300,
			'inserted' => false,
			'callback' => array( $this, 'promo_card' ),
		);

		return $injected_elements;

	}

	/**
	 * Returns the HTML for the promo component.
	 *
	 * @return string HTML or an empty string on failure.
	 */
	public function promo_card() {
		global $post;

		$issue = Publication_Issue::get_instance()->post_to_issue( $post );

		return \PMC::render_template(
			CHILD_THEME_PATH . '/template-parts/article/card-gift-card.php',
			[
				'issue_id' => $issue->ID,
			]
		);
	}

	/**
	 * Registers a styled heading for featured articles.
	 */
	public function register_styled_heading() {
		if ( class_exists( '\PMC\Styled_Heading\Styled_Heading' ) ) {
			\PMC\Styled_Heading\Styled_Heading::register_styled_heading( __( 'Featured Article Hero Heading', 'pmc-rollingstone' ), self::STYLED_HEADING_ID );
		}

	}

	/**
	 * Filters whether the styled heading metabox should show.
	 *
	 * @param bool True if it should show; false otherwise.
	 * @return bool
	 */
	public function filter_do_styled_heading( $bool ) {

		return $this->is_featured_article();

	}

	/**
	 * Adds hooks that should only run when the current post is a featured article.
	 *
	 * @return void
	 */
	public function setup_post_hooks() {
		if ( ! $this->is_featured_article() ) {
			return;
		}

		add_filter( Sharing::SOCIAL_ICON_MODIFIER_CLASS_FILTER, array( $this, 'social_icon_css_class' ) );
		add_filter( 'video_embed_html', array( $this, 'filter_video_embed' ) );

	}

	/**
	 * Registers the Featured Article option.
	 */
	public function register_option() {
		$option = array(
			self::OPTION_NAME => array(
				'label'       => __( 'Featured Article', 'pmc-rollingstone' ),
				'description' => __( 'Use the Featured Article template for this post.', 'pmc-rollingstone' ),
			),
		);

		Post_Options::get_instance()->register_global_options( $option );

	}

	/**
	 * Supplies a alternative CSS class to apply to social sharing icons.
	 *
	 * @return string The CSS class.
	 */
	public function social_icon_css_class( $class ) {
		return 'c-social-bar__icon';
	}

	/**
	 * Returns whether the current post is a featured article.
	 *
	 * @return boolean True if the current post is a featured article.
	 */
	public function is_featured_article( $post = null ) {
		if ( ! is_admin() && ! is_single() ) {
			return false;
		}

		if ( ! is_bool( $this->_is_featured_article ) ) {

			Post_Options::get_instance()->post( $GLOBALS['post'] );
			$this->_is_featured_article = Post_Options::get_instance()->has_option( self::OPTION_NAME );
		}

		return $this->_is_featured_article;
	}

	/**
	 * Wraps video embed HTML in a div for styling.
	 *
	 * @param string The embed HTML.
	 * @return string The filtered HTML.
	 */
	public function filter_video_embed( $embed ) {
		return "<div class=\"video-embed\">$embed</div>";
	}

}
