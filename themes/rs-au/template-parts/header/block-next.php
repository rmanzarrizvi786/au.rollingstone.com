<?php
/**
 * Header Block Next template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-11
 */

// Find a random post to display.
// $random_post = \PMC\Core\Inc\Theme::get_instance()->get_random_recent_post();
$random_post = ThemeSetup::get_random_recent_post();

if ( empty( $random_post ) ) {
	return;
}

?>

<a href="<?php echo esc_url( get_permalink( $random_post->ID ) ); ?>" class="l-header__block l-header__block--read-next">
	<span class="l-header__read-next-label"><?php esc_html_e( 'Read Next', 'pmc-rollingstone' ); ?></span>
	<span class="l-header__read-next-title t-semibold">
		<?php echo esc_html( $random_post->post_title ); ?>
	</span>
</a><!-- .l-header__block--read-next -->
