<?php
/**
 * Card's timestamp template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-11
 */

?>

<div class="c-card__timestamp t-semibold">
	<span class="screen-reader-text"><?php esc_html_e( 'Posted', 'pmc-rollingstone' ); ?></span>
	<?php echo esc_html( human_time_diff( strtotime( get_the_time( 'F j, Y g:iA', get_the_ID() ) ) ) ); ?> <?php esc_html_e( 'ago', 'pmc-rollingstone' ); ?>
</div><!-- c-card__timestamp -->
