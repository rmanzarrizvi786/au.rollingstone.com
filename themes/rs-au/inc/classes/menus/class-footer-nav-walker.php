<?php
/**
 * Footer Nav Walker
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-13
 */

namespace Rolling_Stone\Inc\Menus;

/**
 * Class Footer_Nav_Walker
 *
 * @since 2018-04-13
 */
class Footer_Nav_Walker extends \Walker {

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

		$output .= sprintf(
			'<li class="c-page-nav__item" data-ripple="inverted"><a href="%s" class="c-page-nav__link t-semibold %s" title="%s"%s>%s</a>',
			esc_url( $item->url ),
			( get_the_ID() === $item->object_id ) ? 'current' : '',
			esc_attr( $item->title ),
			$target,
			esc_html( $item->title )
		);

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

		$output .= '</li>';

	}
}
