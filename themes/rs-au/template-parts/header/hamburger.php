<?php
/**
 * 'Menu' button with hamburger icon.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>

<button class="c-hamburger" data-flyout="is-mega-open" data-flyout-scroll-freeze>
	<svg class="c-hamburger__icon"><use xlink:href="#svg-icon-hamburger"></use></svg>
	<span class="c-hamburger__label t-semibold"><?php esc_html_e( 'Menu', 'pmc-rollingstone' ); ?></span>
</button><!-- .c-hamburger -->
