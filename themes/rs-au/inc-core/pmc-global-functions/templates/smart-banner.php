<?php
/**
 * Renders the meta tags for iOS and android smart banner.
 */

if ( empty( $smart_banner_options ) || ! $smart_banner_options['is_smart_banner_enabled'] ) {
	return;
}
?>

<?php if ( ! empty( $smart_banner_options['android_app_id'] ) || ! empty( $smart_banner_options['ios_app_id'] ) ) : ?>

	<link rel="manifest" href="/manifest.json">

<?php endif; ?>

<?php if ( ! empty( $smart_banner_options['ios_app_id'] ) && is_numeric( $smart_banner_options['ios_app_id'] ) ) : ?>

	<!-- Smart Banner start -->
	<meta name="apple-itunes-app" content="app-id=<?php echo esc_attr( $smart_banner_options['ios_app_id'] ); ?>">
	<!-- Smart Banner end -->

<?php endif; ?>
