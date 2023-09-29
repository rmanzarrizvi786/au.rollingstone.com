<?php /* Template Name: Login */

$redirect_to = isset( $_GET['redirectTo'] ) ? urldecode( $_GET['redirectTo'] ) : home_url( '/' );

// Redirect to intended URL if user is already logged in to WP
if ( is_user_logged_in() ) :
  wp_redirect( $redirect_to );
  exit;
endif;

// Load SSO
require_once(  ABSPATH . 'sso-sp/simplesaml/lib/_autoload.php');
$auth = new SimpleSAML_Auth_Simple('default-sp');

if( $auth->isAuthenticated () ) {
  $sso_user = $auth->getAttributes();
  // echo '<pre>'; print_r( $sso_user ); exit;

  $user = get_user_by( 'email', $sso_user['mail'][0] );
  if ( ! $user ) :
    // Create user if not found using SSO email
    $user_id = wp_insert_user(array(
        'user_login' => $sso_user['mail'][0],
        'user_pass' => NULL,
        'user_email' => $sso_user['mail'][0],
        'first_name' => $sso_user['first_name'][0],
        'last_name' => $sso_user['last_name'][0],
        'user_registered' => date('Y-m-d H:i:s'),
        'role' => $sso_user['role'][0]
      )
    );
  else :
    // User found, get user_id
    $user_id = $user->ID;
  endif;

  if ( isset( $sso_user['activationcode'] ) ) {
    update_user_meta( $user_id, 'activationcode', $sso_user['activationcode'][0] );
  }

  if ( isset( $sso_user['sso_id'] ) ) {
    update_user_meta( $user_id, 'sso_id', $sso_user['sso_id'][0] );
  }

  if ( isset( $sso_user['is_activated'] ) ) {
    update_user_meta( $user_id, 'is_activated', $sso_user['is_activated'][0] );
  }

  // Log the user in
  $current_user = wp_set_current_user( $user_id );
  wp_set_current_user( $user_id, $current_user->user_login );
  wp_set_auth_cookie( $user_id );

  // Redirect to intended URL
  wp_redirect( $redirect_to ); exit;
} // If authenticated via SSO

// Not authenticated via SSO, redirect to authenticate
require_once(  ABSPATH . 'sso-sp/simplesaml/lib/_autoload.php');
$auth = new SimpleSAML_Auth_Simple('default-sp');

$auth->requireAuth([
  'ReturnTo' => home_url( '/login/?redirectTo=') . $redirect_to, // home_url( '/' ),
  'KeepPost' => FALSE,
]);
\SimpleSAML\Session::getSessionFromRequest()->cleanup();
exit;
