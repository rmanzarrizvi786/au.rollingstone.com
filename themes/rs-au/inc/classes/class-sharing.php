<?php
/**
 * Sharing
 *
 * Handles Social Sharing from the pmc-social-bar plugin.
 *
 * The parent Class is found in pmc-plugins/pmc-social-bar/classes/frontend.php.
 *
 * @see pmc-plugins/pmc-social-bar/classes/frontend.php
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

namespace Rolling_Stone\Inc;

use \PMC\Social_Share_Bar;

/**
 * Class Sharing
 *
 * Handler for Sharing icon display.
 *
 * @since 2018.1.0
 * @see Social_Share_Bar\Frontend
 */
class Sharing extends Social_Share_Bar\Frontend {
	/**
	 * The name of the filter for modifying the social icon modifier CSS class.
	 */
	const SOCIAL_ICON_MODIFIER_CLASS_FILTER = 'rollingstone_social_icon_modifier_class';

	/**
	 * Get Icons
	 *
	 * Fetches the social icons arrays from the settings.
	 *
	 * @since 2018.1.0
	 * @return array An array of social sharing icons.
	 */
	public function get_icons() {
		global $post;
		return array(
			'primary'   => $this->get_icons_from_cache( Social_Share_Bar\Admin::PRIMARY, $post->post_type ),
			'secondary' => $this->get_icons_from_cache( Social_Share_Bar\Admin::SECONDARY, $post->post_type ),
		);
	}

	/**
	 * Has Icons
	 *
	 * Checks that proper array keys are set as arrays, and that they are not empty.
	 *
	 * @since 2018.1.0
	 * @param array $icons An array of icons.
	 * @return bool If the proper array keys are valid.
	 */
	public static function has_icons( $icons = array() ) {
		return (
			! empty( $icons['primary'] ) &&
			! empty( $icons['secondary'] ) &&
			is_array( $icons['primary'] ) &&
			is_array( $icons['secondary'] )
		);
	}

	/**
	 * Returns a modifier CSS class to apply to a social icon wrapper element.
	 *
	 * @param string $class The CSS class.
	 * @return string The class with filters applied.
	 */
	public static function get_icon_modifier_class() {
		/**
		 * Filters the modifier CSS class applied to social icon wrapper elements.
		 *
		 * @param string The CSS class. Default 'c-icon--white'.
		 */
		return apply_filters(
			self::SOCIAL_ICON_MODIFIER_CLASS_FILTER,
			'c-icon--white'
		);
	}

	/**
	 * Build Link
	 *
	 * Creates an anchor tag using information from a Social Icon object.
	 *
	 * @since 2018.1.0
	 * @param object $icon     A Social Icon object.
	 * @param string $id       A social Icon ID string.
	 * @param string $location A social bar location.
	 * @param string $url     An optional link to override the default.
	 *
	 * @return string A social icon anchor element.
	 */
	public static function build_link( $icon, $id, $location = '', $url = null ) {
		if (
			empty( $icon ) ||
			! is_object( $icon ) ||
			! is_string( $id ) ||
			empty( $icon->url ) ||
			empty( $icon->class ) ||
			empty( $icon->name )
		) {
			return '';
		}

		$class      = ! empty( $icon->class ) ? $icon->class : '';
		$icon_class = '';
		if ( 'gallery' === $location ) {
			$class .= ' gallery__share gallery__share-' . $id;
		} else {
			$class      .= ' c-social-bar__link';
			$icon_class .= ' c-icon ' . self::get_icon_modifier_class();
		}

		if ( null === $url ) {
			if ( $icon->is_javascript() ) {
				$url = esc_attr( $icon->url );
			} else {
				$url = esc_url( $icon->url );
			}
		}

		$link = sprintf(
			'<a href="%1$s" class="%6$s"%2$s%3$s rel="noopener noreferrer" title="%4$s"><span class="%7$s"><svg><use xlink:href="#svg-icon-%5$s"></use></svg></span><span class="screen-reader-text">%4$s</span></a>',
			$url,
			$icon->is_popup() ? ' target="_blank"' : '',
			\PMC\Social_Share_Bar\Config::WA === $id ? ' data-action="share/whatsapp/share"' : '',
			esc_html( $icon->name ),
			sanitize_title( $icon->name ),
			esc_attr( $class ),
			esc_attr( $icon_class )
		);

		echo wp_kses(
			$link, array(
				'a'    => array(
					'href'        => array(),
					'class'       => array(),
					'target'      => array(),
					'data-action' => array(),
					'rel'         => array(),
					'title'       => array(),
				),
				'span' => array(
					'class' => array(),
				),
				'svg'  => array(),
				'use'  => array(
					'xlink:href' => array(),
				),
			)
		);
	}
}
