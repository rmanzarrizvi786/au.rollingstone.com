<?php
/**
 * Template to render settings form for the Ticketing widget
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since  2019-08-14
 */

if ( empty( $instance ) || empty( $widget ) ) {
	return;
}
?>
<p>
	<input
		id="<?php echo esc_attr( $widget->get_field_id( 'render_on_mobile' ) ); ?>"
		name="<?php echo esc_attr( $widget->get_field_name( 'render_on_mobile' ) ); ?>"
		type="checkbox"
		value="yes"
		<?php checked( 'yes', $instance['render_on_mobile'] ); ?>
	/>
	<label for="<?php echo esc_attr( $widget->get_field_id( 'render_on_mobile' ) ); ?>"><?php esc_html_e( 'Mobile Version?', 'pmc-rollingstone' ); ?></label>
</p>

