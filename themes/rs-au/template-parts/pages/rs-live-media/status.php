<?php
/**
 * Success/Error page template for RS Live Media page
 *
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since 2018-07-16
 */

if ( empty( $status ) ) {
	//bail out, no status set
	return;
}

$heading  = '';
$text     = '';
$back_url = '';

switch ( $status ) {

	case 'success':
		$heading  = __( 'Thank You', 'pmc-rollingstone' );
		$text     = __( 'You have successfully been signed up to receive updates.', 'pmc-rollingstone' );
		$back_url = home_url();
		break;

	case 'error':
		$heading  = __( 'Oops!', 'pmc-rollingstone' );
		$text     = __( 'It looks like something went wrong. <br>Please double check the form and ensure that all of your information was input correctly.', 'pmc-rollingstone' );
		$back_url = get_permalink();
		break;

}

?>
<div class="c-content">
	<h2><?php echo wp_kses_post( $heading ); ?></h2>
	<p><?php echo wp_kses_post( $text ); ?></p>
</div>

<div class="u-spacer--v-small">
	<a href="<?php echo esc_url( $back_url ); ?>" class="c-btn c-btn--large c-btn--red t-bold"><?php echo esc_html__( 'Back To RollingStone', 'pmc-rollingstone' ); ?></a>
</div>
