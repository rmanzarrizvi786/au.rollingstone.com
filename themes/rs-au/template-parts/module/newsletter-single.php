<?php
/**
 * Newsletter Signup - Single.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>

<div class="c-post-actions__newsletter">
	<svg class="c-post-actions__badge">
		<use href="#svg-rs-badge"></use>
	</svg>
	<div class="c-post-actions__text">
		<p class="t-semibold">
			<?php esc_html_e( 'Want more Rolling Stone?', 'pmc-rollingstone' ); ?> <a href="<?php echo home_url( 'subscribe'); ?>" class="c-post-actions__link"><?php esc_html_e( 'Sign up for our newsletter.', 'pmc-rollingstone' ); ?></a>
		</p>
	</div>
</div>
