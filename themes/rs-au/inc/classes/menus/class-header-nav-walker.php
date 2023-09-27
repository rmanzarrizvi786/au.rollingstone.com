<?php
/**
 * Header Nav Walker
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-06
 */

namespace Rolling_Stone\Inc\Menus;

/**
 * Class Main_Menu
 *
 * @since 2018-04-06
 */
class Header_Nav_Walker extends \Walker {

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

		// RS PRO icon.
		$icon = '';
		if ( ! empty( $item->classes ) && in_array( 'c-icon--rs-pro', (array) $item->classes, true ) ) {
			$icon = '<span class="c-icon c-icon--rs-pro"><svg><use xlink:href="#svg-rs-badge"></use></svg></span>';
		}

		// Prefixed home_url to relative URL's of categories.
		// if ( ! empty( $item->object ) && ! empty( $item->url ) && 'category' === $item->object ) {
		// 	$item->url = \Rolling_Stone\Inc\Reviews::get_instance()->maybe_get_full_url( $item->url );
		// }

		// Target.
		$target = ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';

		$output .= sprintf(
			"\n<li class=\"l-header__menu-item\"><a href='%s' class=\"l-header__menu-link\"%s>%s</a></li>\n",
			esc_url( $item->url ),
			$target,
			$icon . wp_kses_post( $item->title )
		);
	}
}
