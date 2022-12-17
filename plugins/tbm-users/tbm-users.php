<?php
/**
 * Plugin Name: TBM Users
 * Plugin URI: https://thebrag.media/
 * Description:
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI:
 */

class TBMUsers {

  protected $plugin_name;
  protected $plugin_slug;

  public function __construct() {

    $this->plugin_name = 'tbm_users';
    $this->plugin_slug = 'tbm-users';

    // Hide Admin Bar
    add_filter('show_admin_bar', [ $this, '_show_admin_bar' ]);
    remove_action('wp_head', '_admin_bar_bump_cb');

    // WP Logout
    add_action( 'wp_logout', [ $this, '_wp_logout' ] );

    // Lost Password
    // add_action( 'lost_password', [ $this, '_lost_password' ] );

    // add_action( 'login_form_lostpassword', [ $this, '_login_form_lostpassword' ] );

    // add_filter( 'retrieve_password_title', [ $this, '_retrieve_password_title' ], 10, 1 );
    // add_filter( 'retrieve_password_message', [ $this, '_retrieve_password_message' ], 10, 4 );

    // add_action( 'login_form_rp', [ $this, '_login_form_rp' ] );
    // add_action( 'login_form_resetpass', [ $this, '_login_form_rp'] );

    // add_action( 'wp_login_failed', [ $this, '_wp_login_failed' ] );

    add_action( 'admin_init', [ $this, '_admin_init' ] );

    // add_action( 'wp', [ $this, '_wp'] );

    add_filter( 'send_email_change_email', '__return_false' );
    add_filter( 'send_password_change_email', '__return_false' );
  }

  /*
  * Init - Navigate wp-admin (or wp-login.php) to /login page
  */
  public function _wp() {
    if ( is_page_template( 'page-templates/logout.php' ) )
      return;

    if ( is_user_logged_in() ) {

      if ( current_user_can( 'edit_posts' ) )
        return;

      $current_user = wp_get_current_user();
    }
  }

  /**
  * Redirect non-admin users to home page
  */
  public function _admin_init() {
    if ( ! current_user_can( 'edit_posts' ) && ('/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF']) ) {
      wp_redirect( home_url() );
      exit;
    }
  }

  /*
   * Navigate failed login to /login?status=failed page
   */
  public function _wp_login_failed( $username ) {
    $_SESSION['login_status'] = 'failed';
    wp_redirect( home_url( '/login/' ) );
    exit;
  }

  /*
   * Show admin bar only for admins and editors
   */
  public function _show_admin_bar() {
    return current_user_can('edit_posts');
  }

  /*
  * Logout of SSO when logged out of WP
  */
  public function _wp_logout() {

    // wp_redirect( 'https://the-brag.com/simplesaml/saml2/idp/SingleLogoutService.php?ReturnTo=' . home_url( '/' ) );

    require_once(  ABSPATH . 'sso-sp/simplesaml/lib/_autoload.php');
    $auth = new SimpleSAML_Auth_Simple('default-sp');

    // $auth->logout( wp_get_referer() ? : home_url() );
    // $auth->logout( 'https://the-brag.com/logout/' );

    $returnTo = home_url();

    if( in_array( $_SERVER['REMOTE_ADDR'], [ '127.0.0.1', '::1'] ) ) {
      $auth->logout( 'https://the-brag.com/logout/?returnTo=' . urlencode( $returnTo ) );
    } else {
      $auth->logout( 'https://thebrag.com/logout/?returnTo=' . urlencode( $returnTo ) );
    }
  }

  /**
   * Redirects the user to the custom "Forgot your password?" page instead of
   * wp-login.php?action=lostpassword.
   */
  public function _lost_password() {
    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
      wp_redirect( home_url( 'forgot-password' ) );
      exit;
    }
  }

  /**
   * Initiates password reset.
   */
  public function _login_form_lostpassword() {
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
      $errors = retrieve_password();
      if ( is_wp_error( $errors ) ) {
        // Errors found
        $redirect_url = home_url( 'forgot-password' );
        $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
      } else {
        // Email sent
        $redirect_url = home_url( 'forgot-password' );
        $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
      }
      wp_redirect( $redirect_url );
      exit;
    }
  }

  public function _retrieve_password_title($old_subject) {
    $subject = sprintf( __('[The BRAG] Password Reset'), $blogname );
    return $subject;
  }

  /**
   * Returns the message body for the password reset mail.
   * Called through the retrieve_password_message filter.
   *
   * @param string  $message    Default mail message.
   * @param string  $key        The activation key.
   * @param string  $user_login The username for the user.
   * @param WP_User $user_data  WP_User object.
   *
   * @return string   The mail message to send.
   */
  public function _retrieve_password_message( $message, $key, $user_login, $user_data ) {
    ob_start();
    include( get_template_directory() . '/email-templates/header.php' );
  ?>
    <div>
      <p><strong>Hello!</strong></p>
      <p>You asked us to reset your password for your account using the email address <?php echo $user_data->user_email; ?>.
      <p>If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.</p>
      <p>To reset your password, visit the following address:<br>
      <?php echo site_url( "/reset-password/?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ); ?>
      <p>&nbsp;</p>
      <p style="color: #999;">Regards,<br><strong>The Brag</strong></p>
    </div>
  <?php
      include( get_template_directory() . '/email-templates/footer.php' );

      $msg = ob_get_contents();
      ob_end_clean();
      return $msg;
  }

  /**
   * Redirects to the custom password reset page, or the login page
   * if there are errors.
   * Resets the user's password if the password reset form was submitted.
   */
  public function _login_form_rp() {
    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
      // Verify key / login combo
      $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
      if ( ! $user || is_wp_error( $user ) ) {
        if ( $user && $user->get_error_code() === 'expired_key' ) {
          wp_redirect( home_url( '/forgot-password/?errors=expiredkey' ) );
        } else {
          wp_redirect( home_url( '/forgot-password/?errors=invalidkey' ) );
        }
        exit;
      }

      $redirect_url = home_url( 'reset-password' );
      $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
      $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );
      wp_redirect( $redirect_url );
      exit;
    } else if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
      $rp_key = $_REQUEST['rp_key'];
      $rp_login = $_REQUEST['rp_login'];

      $user = check_password_reset_key( $rp_key, $rp_login );

      if ( ! $user || is_wp_error( $user ) ) {
        if ( $user && $user->get_error_code() === 'expired_key' ) {
          wp_redirect( home_url( '/forgot-password/?errors=expiredkey' ) );
        } else {
          wp_redirect( home_url( '/forgot-password/?errors=invalidkey' ) );
        }
        exit;
      }

      if ( isset( $_POST['pass1'] ) ) {
        if ( $_POST['pass1'] != $_POST['pass2'] ) {
          // Passwords don't match
          $redirect_url = home_url( 'reset-password' );
          $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
          $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
          $redirect_url = add_query_arg( 'errors', 'password_reset_mismatch', $redirect_url );
          wp_redirect( $redirect_url );
          exit;
        }

        if ( empty( $_POST['pass1'] ) ) {
          // Password is empty
          $redirect_url = home_url( 'reset-password' );
          $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
          $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
          $redirect_url = add_query_arg( 'errors', 'password_reset_empty', $redirect_url );
          wp_redirect( $redirect_url );
          exit;
        }

        // Parameter checks OK, reset password
        reset_password( $user, $_POST['pass1'] );
        wp_redirect( home_url( '/login/?password=changed' ) );
      } else {
        echo "Invalid request.";
      }
      exit;
    }
  }
}

new TBMUsers();
