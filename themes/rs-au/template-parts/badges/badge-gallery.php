<?php
/**
 * Gallery Badge template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-19
 */

if ( empty( $count ) || empty( $classes ) ) {
	return;
}

?>

<div class="c-badge c-badge--gallery<?php echo esc_attr( $classes ); ?>">
	<svg class="c-badge__icon"><use xlink:href="#svg-icon-camera"></use></svg>
	<div class="c-badge__label t-semibold t-semibold--upper">
		<?php
		// Translators: number of photos.
		echo sprintf( _n( '%s Photo', '%s Photos', intval( $count ), 'pmc-rollingstone' ), intval( $count ) ); // WPCS: XSS ok.
		?>
	</div><!-- .c-badge__label -->
</div><!-- .c-badge--gallery -->
