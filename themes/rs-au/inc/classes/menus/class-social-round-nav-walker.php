<?php
/**
 * Social Round Nav Walker
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-13
 */

namespace Rolling_Stone\Inc\Menus;

/**
 * Class Social_Round_Nav_Walker
 *
 * @since 2018-04-13
 */
class Social_Round_Nav_Walker extends \Rolling_Stone\Inc\Menus\Social_Nav_Walker {

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

		// Figure out the social icon name.
		$icon   = '';
		$domain = wp_parse_url( $item->url );

		if ( ! empty( $domain['host'] ) ) {
			$icon = str_replace( 'www.', '', $domain['host'] );

			$icon = preg_split( '/(?=\.[^.]+$)/', $icon );

			// Cater for domains with a double barrel TLD.
			$icon = preg_split( '/(?=\.[^.]+$)/', $icon[0] );

			// Remove special characters.
			$icon = sanitize_title( $icon[0] );

			if( 'https://thebrag.com/observer/' == $item->url ) {
				$icon = 'email';
			}
		}

		// Target.
		$target = ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';

		$output .= sprintf(
			'<li class="c-social-bar__item"><a href="%s" class="c-social-bar__link" title="%s" rel="noopener noreferrer"%s><span class="c-icon c-icon--white c-icon--large"><svg><use xlink:href="#svg-icon-%s"></use></svg></span><span class="screen-reader-text">%s</span></a>',
			esc_url( $item->url ),
			esc_attr( $item->title ),
			$target,
			esc_attr( $icon ),
			__( 'Share on', 'pmc-rollingstone' ) . esc_html( $item->title )
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
