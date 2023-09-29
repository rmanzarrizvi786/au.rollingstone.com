<?php
/**
 * Outbrain.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-08
 */

$outbrain_device_id = function_exists( 'jetpack_is_mobile' ) && jetpack_is_mobile() ? 'MB' : 'AR';
?>
<div class="OUTBRAIN"
	data-src="<?php the_permalink(); ?>"
	data-widget-id="<?php echo esc_attr( $outbrain_device_id ); ?>_1"
	data-ob-template="RollingStone_1" ></div>
<div class="OUTBRAIN"
	data-src="<?php the_permalink(); ?>"
	data-widget-id="<?php echo esc_attr( $outbrain_device_id ); ?>_2"
	data-ob-template="RollingStone_1" ></div>
<script type="text/javascript" async="async" src="https://widgets.outbrain.com/outbrain.js"></script>
