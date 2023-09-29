<?php
/**
 * Footer - Legal.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-11-04
 */

?>

<div class="c-page-nav c-page-nav--footer c-page-nav--1-column" data-dropdown>
	<ul class="c-page-nav__list">
		<li class="c-page-nav__item c-page-nav__item--heading is-active" data-ripple="inverted">
			<span class="c-page-nav__link t-bold"><?php esc_html_e( 'Legal', 'pmc-rollingstone' ); ?></span>
		</li><!-- .c-page-nav__item -->

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'rollingstone_footer_legal',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'walker'         => new \Rolling_Stone\Inc\Menus\Footer_Nav_Walker(),
			)
		);
		?>
		<li class="c-page-nav__item" data-ripple="inverted">
			<a href="#" class="c-page-nav__link t-semibold privacy-consent" target="_blank">
				<?php esc_html_e( 'Privacy Preferences', 'pmc-rollingstone' ); ?>
			</a>
		</li>
	</ul><!-- .c-page-nav__list -->
</div><!-- .c-page-nav--footer -->
