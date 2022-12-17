<?php
/**
 * Card - Dek.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

$dek = rollingstone_get_the_excerpt();

if ( ! empty( $dek ) ) :
?>

<p class="c-card__lead">
	<?php echo esc_html( wp_strip_all_tags( $dek ) ); ?>
</p><!-- c-card__lead -->

<?php
endif;
