<?php
/**
 * 404.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-28
 */

/*
 * Render cached HTML for 404 page
 */
 $Page_404 = new \Rolling_Stone\Inc\Pages\Page_404();
 $Page_404->render_cached_html();
// \Rolling_Stone\Inc\Pages\Page_404::get_instance()->render_cached_html();

//EOF
