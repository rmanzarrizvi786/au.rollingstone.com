<?php
/**
 * Social Bar Round.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-06
 */

?>

<ul class="c-social-bar c-social-bar--round">
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'pmc_core_social',
			'container'      => false,
			'items_wrap'     => '%3$s',
			'walker'         => new \Rolling_Stone\Inc\Menus\Social_Header_Nav_Walker(),
		)
	);
	?>
</ul><!-- .c-social-bar -->
