<?php

/**
 * Footer - Social.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-11-04
 */

?>

<div class="c-page-nav c-page-nav--footer c-page-nav--1-column" data-dropdown>
	<ul class="c-page-nav__list">
		<?php
		
		$primaryNav = wp_get_nav_menu_items(6);
		foreach ($primaryNav as $navItem) {

			echo '<li><a href="' . $navItem->url . '" title="' . $navItem->title . '">' . $navItem->title . '</a></li>';
		}
		?>
		<li class="c-page-nav__item c-page-nav__item--heading is-active" data-ripple="inverted">
			<span class="c-page-nav__link t-bold"><?php esc_html_e('Connect With Us aaa', 'pmc-rollingstone'); ?></span>
		</li><!-- .c-page-nav__item -->

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'pmc_core_social',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'walker'         => new \Rolling_Stone\Inc\Menus\Social_Nav_Walker(),
			)
		);
		?>

	</ul><!-- .c-page-nav__list -->
</div><!-- .c-page-nav--footer -->