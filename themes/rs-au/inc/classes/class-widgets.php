<?php
/**
 * Class Widgets
 *
 * Implements widgets.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-04
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Class Widgets
 */
class Widgets {

	// use Singleton;

	/**
	 * Class constructor.
	 *
	 * @since 2017.1.0
	 */
	public function __construct() {
		add_filter( 'widgets_init', array( $this, 'load_widgets' ), 11 );
		add_filter( 'widgets_init', array( $this, 'register_sidebars' ) );
	}

	/**
	 * Load Widgets
	 *
	 * Load our custom widgets.
	 *
	 * @since 2017.1.0
	 */
	public function load_widgets() {
		// Unregister core widgets.
		unregister_widget( '\PMC\Core\Inc\Widgets\Trending_Now' );

		// Register custom widgets.
		register_widget( '\Rolling_Stone\Inc\Widgets\Trending' );
		register_widget( '\Rolling_Stone\Inc\Widgets\Editors_Picks' );
		register_widget( '\Rolling_Stone\Inc\Widgets\Section' );
		register_widget( '\Rolling_Stone\Inc\Widgets\Features' );
		// register_widget( '\Rolling_Stone\Inc\Widgets\Special_Coverage' );
		register_widget( '\Rolling_Stone\Inc\Widgets\Video_Gallery' );
		register_widget( '\Rolling_Stone\Inc\Widgets\Video_Top_Featured' );
		register_widget( '\Rolling_Stone\Inc\Widgets\Video_Featured' );
		// register_widget( '\Rolling_Stone\Inc\Widgets\Photo_Gallery' );
		register_widget( '\Rolling_Stone\Inc\Widgets\Video_Playlist' );
		// register_widget( '\Rolling_Stone\Inc\Widgets\Ticketing' );

		register_widget( '\Rolling_Stone\Inc\Widgets\Video_Record_Of_Week' );
	}

	/**
	 * Register Sidebars
	 *
	 * Register sidebars for the site.
	 *
	 * @since 2018.1.0
	 */
	public function register_sidebars() {

		// Unregister sidebars in PMC Core.
		unregister_sidebar( 'article_right_sidebar' );

		register_sidebar(
			array(
				'name'          => __( 'Article Right Sidebar', 'pmc-rollingstone' ),
				'id'            => 'article_right_sidebar',
				'before_widget' => '<div class="l-blog__item">',
				'after_widget'  => '</div>',
				'before_title'  => false,
				'after_title'   => false,
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Category Right Sidebar', 'pmc-rollingstone' ),
				'id'            => 'category_right_1',
				'before_widget' => false,
				'after_widget'  => false,
				'before_title'  => false,
				'after_title'   => false,
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Homepage Bottom', 'pmc-rollingstone' ),
				'id'            => 'homepage-bottom',
				'before_widget' => false,
				'after_widget'  => false,
				'before_title'  => false,
				'after_title'   => false,
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Featured Article Bottom', 'pmc-rollingstone' ),
				'id'            => 'featured-article-bottom',
				'before_widget' => false,
				'after_widget'  => false,
				'before_title'  => false,
				'after_title'   => false,
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Video Landing Page', 'pmc-rollingstone' ),
				'id'            => 'video_landing',
				'before_widget' => false,
				'after_widget'  => false,
				'before_title'  => false,
				'after_title'   => false,
			)
		);

	}
}

new Widgets();
