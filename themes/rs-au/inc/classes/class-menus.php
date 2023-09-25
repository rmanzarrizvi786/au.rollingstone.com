<?php
/**
 * Menus
 *
 * The child theme menus.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-04
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Class Menus
 *
 * @since 2018.04.04
 * @see \PMC\Global_Functions\Traits\Singleton
 */
class Menus {

	use Singleton;

	/**
	 * Class constructor.
	 *
	 * Removes the parent theme menus and initializes the child theme menus.
	 */
	protected function __construct() {

		add_action( 'init', [ $this, 'register_nav_menus' ] );

		// Removing duplicate link.
		remove_filter( 'wp_nav_menu_items', [ \PMC\Core\Inc\Menus\Menus::get_instance(), 'vertical_editorial_mobile_menu_item' ] );

		// Dynamic menu item for mobiles.
		add_filter( 'wp_nav_menu_items', [ $this, 'mobile_menu_item' ], 10, 2 );

		remove_filter( 'wp_nav_menu_items', [ \PMC\Core\Inc\Menus\Menus::get_instance(), 'nav_menu_css_class' ] );
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ), 10, 4 );

		remove_filter( 'wp_nav_menu_items', [ \PMC\Core\Inc\Menus\Menus::get_instance(), 'nav_menu_link_attributes' ] );
		add_filter( 'nav_menu_link_attributes', array( $this, 'nav_menu_link_attributes' ), 10, 4 );

		// Remove social menu concatenation.
		remove_filter( 'wp_nav_menu_items', [ \PMC\Core\Inc\Menus\Menus::get_instance(), 'wp_nav_menu_items' ] );
	}

	/**
	 * Register nav menus for child theme.
	 *
	 * @since 2018-04-13
	 */
	public function register_nav_menus() {
		$menus = [
			'rollingstone_videos'                  => __( 'Videos', 'pmc-rollingstone' ),
			'rollingstone_footer_legal'            => __( 'Footer - Legal', 'pmc-rollingstone' ),
			'rollingstone_footer_get_the_magazine' => __( 'Footer - Get The Magazine', 'pmc-rollingstone' ),
		];

		// Create menu's for each primary category.
		$categories = get_terms(
			array(
				'taxonomy' => 'category',
			)
		);

		// Only parent categories.
		foreach ( $categories as $category ) {
			if ( 0 === $category->parent ) {
				$menus[ $category->slug . '-category-menu' ] = $category->name . ' ' . __( 'Category Menu', 'pmc-rollingstone' );
			}
		}

		register_nav_menus( $menus );
	}

	/**
	 * Adds dynamic menu item for mobile devices.
	 *
	 * @param array $items Menu items.
	 * @param array $args  Menu arguments.
	 *
	 * @return string
	 */
	public function mobile_menu_item( $items, $args ) {

		if ( empty( $args->menu_id ) ) {
			return $items;
		}

		$all = esc_html__( 'All', 'pmc-rollingstone' );

		if ( is_archive() && 'category-header-menu' === $args->menu_id ) {

			$object = get_queried_object();
			$active = ( 0 === $object->parent ) ? 'is-active' : '';

			$show_all_link = sprintf(
				'<li class="c-page-nav__item %s" data-ripple><a href="%s" class="c-page-nav__link">%s</a></li>',
				$active,
				esc_url( get_term_link( $object->term_id ) ),
				$all
			);

			// add the 'All'  link to the start of the menu.
			$items = $show_all_link . $items;
		}

		if ( 'video-header-menu' === $args->menu_id ) {

			$active = is_page( 'video' ) ? 'is-active' : '';

			$esc_all = sprintf(
				'<li class="c-page-nav__item %s" data-ripple><a href="%s" class="c-page-nav__link">%s</a></li>',
				$active,
				esc_url( home_url( '/video/' ) ),
				$all
			);

			// add the 'All'  link to the start of the menu.
			$items = $esc_all . $items;
		}

		return $items;
	}

	/**
	 * Filters the CSS class(es) applied to a menu item's list item element.
	 *
	 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
	 * @param WP_Post  $item    The current menu item.
	 * @param stdClass $args    An object of wp_nav_menu() arguments.
	 * @param int      $depth   Depth of menu item. Used for padding.
	 *
	 * @return array   $classes The modified CSS classes that are applied to the menu item's `<li>` element.
	 */
	public function nav_menu_css_class( $classes, $item, $args, $depth ) {

		if ( 'pmc_core_mega_bottom' === $args->theme_location ) {
			$classes[] = 'l-mega__menu-item';
		}

		return $classes;
	}

	/**
	 * Filters the HTML attributes applied to a menu item's anchor element.
	 *
	 * @param array $atts {
	 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
	 *
	 *     @type string $title  Title attribute.
	 *     @type string $target Target attribute.
	 *     @type string $rel    The rel attribute.
	 *     @type string $href   The href attribute.
	 * }
	 * @param WP_Post  $item  The current menu item.
	 * @param stdClass $args  An object of wp_nav_menu() arguments.
	 * @param int      $depth Depth of menu item. Used for padding.
	 *
	 * @return array $atts The HTML attributes for the anchor element.
	 */
	public function nav_menu_link_attributes( $atts, $items, $args, $depth ) {
		$new_atts = array();

		if ( 'pmc_core_mega_bottom' === $args->theme_location ) {
			$new_atts['class'] = 'l-mega__menu-link';
		}

		return array_unique( array_merge( $atts, $new_atts ) );
	}
}
