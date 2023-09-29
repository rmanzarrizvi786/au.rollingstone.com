<?php
/**
 * Header Nav Template
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-06
 */

?>

<nav class="l-header__nav">
	<div class="l-header__toggle l-header__toggle--hamburger">
		<?php get_template_part( 'template-parts/header/hamburger' ); ?>
	</div><!-- .l-header__toggle--hamburger -->

	<div class="l-header__toggle l-header__toggle--close">
		<?php get_template_part( 'template-parts/header/close-button' ); ?>
	</div><!-- .l-header__toggle--close -->

	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'pmc_core_header',
			'container'      => false,
			'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
			'menu_class'     => 'l-header__menu t-semibold',
			'depth'          => 0,
			'walker'         => new \Rolling_Stone\Inc\Menus\Header_Nav_Walker(),
		)
	);
	?>
</nav><!-- .l-header__nav -->
