<?php
/**
 * Class to deal with 404 pages
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-09-17
 *
 * @package pmc-rollingstone-2018
 */

namespace Rolling_Stone\Inc\Pages;

use \PMC\Global_Functions\Traits\Singleton;
use \PMC;
use \PMC_Cache;


class Page_404 {

	// use Singleton;

	const CACHE_LIFE  = 30; // start small
	const CACHE_KEY   = 'page-404';
	const CACHE_GROUP = 'rollingstone-pages-v1';

	/**
	 * Method to get the page HTML.
	 * This is an uncached output, do not use it directly on frontend.
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function get_uncached_html() : string {

		return PMC::render_template(
			sprintf(
				'%s/template-parts/pages/404.php',
				untrailingslashit( CHILD_THEME_PATH )
			)
		);

	}

	/**
	 * Function to output the 404 page HTML
	 * This is the cached output and this should be used on the frontend.
	 *
	 * @return void
	 */
	public function render_cached_html() : void {

		$cache = new PMC_Cache( self::CACHE_KEY, self::CACHE_GROUP );

		$cache->expires_in( self::CACHE_LIFE )
				->updates_with(
					[ $this, 'get_uncached_html' ]
				);

		/*
		 * Ignore this part in PHPCS since this just outputs the page
		 * template. All escaping, sanitization, etc. is done in the
		 * template itself. It has to be done this way to run the
		 * 404 page template via PMC_Cache.
		 */
		echo $cache->get();    // phpcs:ignore

	}

}    // end class

//EOF
