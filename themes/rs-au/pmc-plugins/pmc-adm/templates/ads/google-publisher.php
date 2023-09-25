<?php
/*
NOTE: ad div placements must be all defined before we call googletag.display
In order for DFP to sync ads, a single request must be made to GTP
Therefore, inline ad rendering should NOT be implemented
*/

$out_of_page = ( empty( $ad['out-of-page'] ) || strtolower( $ad['out-of-page'] ) == 'no' ) ? false : true;

$rotatable_classes = array();

if ( ! empty( $ad['is-ad-rotatable'] ) && strtolower( $ad['is-ad-rotatable'] ) == 'yes' ) {
	$rotatable_classes[] = 'ad-rotatable';
}

if ( ! empty( $ad ) && ! empty( $ad['ad-group'] ) && 'time-gap-ads' === $ad['ad-group'] ) {
	$rotatable_classes[] = 'time-gap-ads';
}
?>

<div class="pmc-adm-goog-pub-div <?php echo esc_attr( $ad['css-class'] ); ?>">
	<div id="<?php echo esc_attr( $ad['div-id'] ); ?>" class="<?php echo esc_attr( implode(' ', $rotatable_classes ) ); ?><?php echo esc_attr( ' adw-' . $ad['width'] . ' adh-' . $ad['height'] ); ?>"></div>
	<?php  if ( $out_of_page ) { //out of page ad ?>
		<div id="<?php echo esc_attr( $ad['div-id'] ); ?>-oop" class="<?php echo esc_attr( implode(' ', $rotatable_classes ) ); ?>"></div>
	<?php } ?>
</div>
