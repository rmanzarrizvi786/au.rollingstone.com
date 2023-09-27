<?php
/**
 * Country
 *
 * Country related functionality.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-25
 */

namespace Rolling_Stone\Inc;

use \PMC\Global_Functions\Traits\Singleton;

/**
 * Country Class
 *
 * @see \PMC\Global_Functions\Traits\Singleton
 */
class Country {

	use Singleton;

	/**
	 * Class constructor.
	 */
	protected function __construct() {
		add_filter( 'body_class', [ $this, 'body_class' ] );
		add_filter( 'top_posts_get_most_commented_args', [ $this, 'is_country' ] );
		add_filter( 'top_posts_get_random_posts_args', [ $this, 'is_country' ] );
		add_filter( 'related_get_related_items_category', [ $this, 'country_slug' ] );
	}

	/**
	 * Adds custom country class.
	 *
	 * @param array $classes Classes.
	 *
	 * @return array
	 */
	public function body_class( $classes ) {

		if ( ! rollingstone_is_country() ) {
			return $classes;
		}

		$country = 'has-branding has-branding--country';

		if ( is_single() ) {
			$country = 'is-single ' . $country;
		}

		return array_merge( $classes, [ $country ] );
	}

	/**
	 * Get Country posts when on a country post.
	 *
	 * @param  array $args Arguments.
	 *
	 * @return array
	 */
	public function is_country( $args ) {
		if ( rollingstone_is_country() ) {
			$args['category_name'] = 'music-country';
		}

		return $args;
	}

	/**
	 * Return the Country slug on Country posts and categories.
	 *
	 * @param  string $category_slug Category slug.
	 *
	 * @return string
	 */
	public function country_slug( $category_slug ) {
		if ( rollingstone_is_country() ) {
			return 'music-country';
		}

		return $category_slug;
	}
}
