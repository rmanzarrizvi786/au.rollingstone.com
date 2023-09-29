<?php
/**
 * Footer - Nav.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-11
 */

?>

<div class="c-page-nav c-page-nav--footer c-page-nav--2-columns" data-dropdown>
	<ul class="c-page-nav__list">
		<li class="c-page-nav__item c-page-nav__item--heading is-active" data-ripple="inverted">
			<span class="c-page-nav__link t-bold"><?php esc_html_e( 'Rolling Stone', 'pmc-rollingstone' ); ?></span>
		</li><!-- .c-page-nav__item -->

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'pmc_core_footer',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'walker'         => new \Rolling_Stone\Inc\Menus\Footer_Nav_Walker(),
			)
		);
		?>

	</ul><!-- .c-page-nav__list -->
</div><!-- .c-page-nav--footer -->
