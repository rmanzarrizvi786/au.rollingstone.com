<?php
/**
 * Category Nav Walker
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-13
 */

namespace Rolling_Stone\Inc\Menus;

/**
 * Class Category_Nav_Walker
 *
 * @since 2018-04-13
 */
class Category_Nav_Walker extends \Walker {

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
		// 	$Reviews = new \Rolling_Stone\Inc\Reviews();
		// 	$item->url = $Reviews->maybe_get_full_url( $item->url );
		// 	// $item->url = \Rolling_Stone\Inc\Reviews::get_instance()->maybe_get_full_url( $item->url );
		// }

		// Target.
		$esc_target = ( ! empty( $item->target ) ) ? sprintf( ' target="%s"', esc_attr( $item->target ) ) : '';

		$output .= sprintf(
			"\n<li class=\"c-page-nav__item%s\" data-ripple><a href='%s' class=\"c-page-nav__link\"%s>%s</a></li>\n",
			// ( get_the_ID() === $item->object_id ) ? ' is-active' : '',
			( get_queried_object_id() === (int) $item->object_id ) ? ' is-active' : '',
			esc_url( $item->url ),
			$esc_target,
			wp_kses_post( $item->title )
		);
	}
}
