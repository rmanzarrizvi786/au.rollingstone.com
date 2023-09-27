<?php
if ( empty( $css_class ) ) {
	$css_class = '';
}

if ( empty( $placeholder_text ) ) {
	$placeholder_text = __( 'Your email', 'pmc-rollingstone' );
}
?>
<input type="hidden" name="group[95][1]" value="1">
<input type="hidden" name="group[95][2]" value="1">
<input type="hidden" name="group[95][4]" value="1">
<input type="email" name="EMAIL" class="js-newsletter-email <?php echo esc_attr( $css_class ); ?>" placeholder="<?php echo esc_attr( $placeholder_text ); ?>" required style="/*background: #555; color: #fff;*/">

<script>
jQuery( document ).ready(function($) {

	// Validate email will return true if valid and false if invalid.
	function validate_email( email ) {
		const expr = /^([\w.+-]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		return expr.test( email );
	}

	$email_field = jQuery('.js-newsletter-email')
	$success_page_field = jQuery('.js-newsletter-successpage');

	if ( 0 === $email_field.length || 0 === $success_page_field.length ) {
		return;
	}

	success_page_base_url = $success_page_field.data( 'base-url' );

	$email_field.on( 'keyup blur', ( e ) => {

		// validate email and check for empty value.
		if ( '' === $( e.target ).val() || validate_email( $( e.target ).val() ) ) {
			$( e.target ).removeClass( 'invalid' );
		}

		var email_address = encodeURIComponent( $( e.target ).val() );
		$success_page_field.val( success_page_base_url + '&email=' + email_address );

	} );

});
</script>
