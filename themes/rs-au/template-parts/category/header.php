<?php
/**
 * Category Header.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>

<div class="l-section-top">
	<h1 class="l-section-top__heading">
		<span class="t-super"><?php single_term_title( '' ); ?></span>
	</h1><!-- .l-section-top__heading -->

	<nav class="l-section-top__menu">
		<div class="c-page-nav c-page-nav--header" data-dropdown data-line-menu>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => sanitize_title( single_term_title( '', false ) ) . '-category-menu',
					'container'      => false,
					'items_wrap'     => '<ul id="%1$s" class="c-page-nav__list %2$s">%3$s</ul>',
					'menu_class'     => '',
					'depth'          => 0,
					'walker'         => new \Rolling_Stone\Inc\Menus\Category_Nav_Walker(),
					'menu_id'        => 'category-header-menu',
				)
			);
			?>
		</div><!-- .c-page-nav--header -->
	</nav><!-- .l-section-top__menu -->
</div><!-- .l-section-top -->
