<?php
/**
 * Mega Menu Walker
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-06
 */

namespace Rolling_Stone\Inc\Menus;

/**
 * Class Mega_Main_Menu
 *
 * @since 2018-04-06
 */
class Mega_Nav_Walker extends \Walker {

	/**
	 * DB fields.
	 *
	 * @var array
	 */
	public $db_fields = array(
		'parent' => 'menu_item_parent',
		'id'     => 'db_id',
	);

	/**
	 * At the start of each element, output a <li> and <a> tag structure.
	 *
	 * Note: Menu objects include url and title properties, so we will use those.
	 *
	 * @param string          $output Passed by reference. Used to append additional content.
	 * @param object          $item The data object.
	 * @param int             $depth Depth of the item.
	 * @param \stdClass|array $args An array of additional arguments.
	 * @param int             $id ID of the current item.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		// Prefixed home_url to relative URL's of categories.
		// if ( ! empty( $item->object ) && ! empty( $item->url ) && 'category' === $item->object ) {
		// 	$item->url = \Rolling_Stone\Inc\Reviews::get_instance()->maybe_get_full_url( $item->url );
		// }

		// Target.
		$target = ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';

		if ( 0 === $depth ) {
			$output .= '<li class="c-mega-nav__item" data-collapsible="collapsed">';
			$output .= '<a href="' . esc_url( $item->url ) . '" class="c-mega-nav__link t-bold" data-ripple' . $target . '>';
			$output .= wp_kses_post( $item->title );
			$output .= '</a>';

			$output .= '<a href="#" class="c-mega-nav__expander" data-collapsible-toggle="always-show" data-ripple>';
			$output .= '<span class="c-mega-nav__expander-icon"></span>';
			$output .= '<span class="screen-reader-text">' . __( 'Expand the sub menu', 'pmc-rollingstone' ) . '</span>';
			$output .= '</a>';
			$output .= '<ul class="c-mega-nav__submenu" data-collapsible-panel data-collapsible-breakpoint="mobile-only">';
		}

		if ( 1 === $depth ) {
			$output .= '<li class="c-mega-nav__item c-mega-nav__item--sub">';
			$output .= '<a href="' . esc_url( $item->url ) . '" class="c-mega-nav__link c-mega-nav__link--sub t-semibold" data-ripple>' . wp_kses_post( $item->title ) . '</a>';
		}
	}

	/**
	 * Ends the element output.
	 *
	 * @param string          $output Passed by reference. Used to append additional content.
	 * @param \WP_Post        $item Page data object. Not used.
	 * @param int             $depth Depth of page. Not Used.
	 * @param \stdClass|array $args An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( 0 === $depth ) {
			$output .= '</ul>';
			$output .= '</li>';
		}

		if ( 1 === $depth ) {
			$output .= '</li>';
		}
	}
}
