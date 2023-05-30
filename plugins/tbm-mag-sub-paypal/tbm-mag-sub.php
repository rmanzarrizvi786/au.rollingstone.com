<?php
/**
 * Plugin Name: Magazine Subscriptions (PayPal)
 * Plugin URI: https://thebrag.media
 * Description:
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI:
 */

class TBMMagSubPaypal {

  protected $plugin_name;
  protected $plugin_slug;

  protected $is_sandbox;

  protected $salesforce_client_id;
  protected $salesforce_client_secret;
  protected $salesforce_login_uri;
  protected $salesforce_username;
  protected $salesforce_password;

  protected $paypal_client_id;
  protected $paypal_secret;
  protected $paypal_baseurl;
  protected $paypal_access_token;

  protected $plan_ids;

  public function __construct() {

    $this->is_sandbox = false;

    $this->plugin_name = 'tbm_mag_sub';
    $this->plugin_slug = 'tbm-mag-sub';


    if ( $this->is_sandbox ) {
      $this->paypal_client_id = 'AZNnEZBiLx2V7P-9jy0v27w2k8ZctDLmXePgLqU2jLR9-QkcTNUerBFYWFjrgZjXZMo4Akb_kt_rkd3B';
      $this->paypal_secret = 'EOsF4PngqTe1q9MZPwEkksYpZnJmspuFO_5sP3MLHdIm63q78J62axYxtlPlCODcafc2a1qykBIHV52C';
      $this->paypal_baseurl = 'https://api.sandbox.paypal.com/v1/';

      $this->salesforce_client_id = '3MVG9iLRabl2Tf4i06H1GR8eODcSbvsQvreIXGO6fDTjEpvD3bW8NXWj1ZbQiDq_yf9oK77pEftt123JLL5V8';
      $this->salesforce_client_secret = '7A03E86633D50C10FFCD35B64ED6B77409375B5BB0DD6ABF4931AE295B9CEF57';
      $this->salesforce_login_uri = 'https://thebragmedia--tbmsandbox.my.salesforce.com/';
      $this->salesforce_username = 'sachin.patel@thebrag.media.tbmsandbox';
      $this->salesforce_password = 'sAchin456';
    } else {
      $this->paypal_client_id = 'AR0BSoU6J_xIDZbp1P1xYp9HD2bMqZyhgSjGq0lkTVX64Eq6F9fWarmjUFSTUga-sAcOFIuUJofKz7um';
      $this->paypal_secret = 'EAQ0_s8YzKW_Hq7Ykz-F7KAxm5gipq5OV8waRLoUf3ARCsQwT4FDUM7IV3IJZNBU9W9_16cI-X_CkMxk';
      $this->paypal_baseurl = 'https://api.paypal.com/v1/';

      $this->salesforce_client_id = '3MVG9G9pzCUSkzZskqIN3a4qW0R3A8M8wOR_tcBYKxXgBiCJXOVESBA399bvfHSMktJHlaHipQQhH.jwYJVt5';
      $this->salesforce_client_secret = '2D07B74620BC31B2A7CA3D14C7DD840F22189B3A9DF75B6BB9EFC59E8FD2F568';
      $this->salesforce_login_uri = 'https://thebragmedia.my.salesforce.com/';
      $this->salesforce_username = 'sachin.patel@thebrag.media';
      $this->salesforce_password = 'sAchin456';
    }

    add_action('init', array($this, 'start_session'), 1);

    add_action('admin_menu', array( $this, 'admin_menu' ) );

    add_action( 'wp_ajax_click_button', array( $this, 'click_button' ) );
    add_action( 'wp_ajax_nopriv_click_button', array( $this, 'click_button' ) );

    add_action( 'wp_ajax_subscribe_success', array( $this, 'subscribe_success' ) );
    add_action( 'wp_ajax_nopriv_subscribe_success', array( $this, 'subscribe_success' ) );

    add_action( 'admin_post_tbm_create_coupon', array( $this, 'create_coupon' ) );
    add_action( 'admin_post_tbm_update_coupon', array( $this, 'update_coupon' ) );

    if ( $this->is_sandbox ) { // Sandbox
      $this->plan_ids = [
        'regular' => [
          0 => 'P-8TS20227PW810561XLZZO4NQ',
          10 => 'P-9RS04009GX025201CLZZN7MA',
          50 => 'P-73S63653EX434464VLZZN7VA',
          100 => 'P-0KK62113B27720049LZZOATQ',
        ],
        'gift' => [
          0 => 'P-7W1084690W962035WLZZOBVA',
          10 => 'P-36N81933EW4884842LZZOE4A',
          20 => 'P-8W233834F4256564JLZZOLBA',
          50 => 'P-9DN555876W289960CLZZOGSA',
        ]
      ];
    } else { // Production
      // Old
      /*
      $this->plan_ids = [
        'regular' => [
          0 => 'P-9HG05484NW525442WLZYDDBA',
          10 => 'P-2NN90439RE7072624LZZBE3I',
          20 => 'P-12K42464AH4766317LZZPTEI',
          30 => 'P-8LK94909AB3270635LZZPUFQ',
          40 => 'P-9PJ3683737792201YLZZPUTA',
          50 => 'P-46D40960AD646181TLZZPVBY',
          60 => 'P-6P140459U64023939LZZPVZQ',
          70 => 'P-73F92478CM1223633LZZPWMI',
          80 => 'P-35S81740L23181357LZZPW3A',
          90 => 'P-2HN49184FN365233NLZZPXJY',
          100 => 'P-25B413946T1419220LZZL4XY',
        ],
        'gift' => [
          0 => 'P-71K33282C45857244LZZPYHQ',
          10 => 'P-05F74653DS177883NLZZPYYI',
          20 => 'P-3K124826JH5470612LZZPZEQ',
          30 => 'P-00M405202R8874424LZZPZOY',
          40 => 'P-3K654439RK6053520LZZPZ7Y',
          50 => 'P-5E3436802P5652136LZZP2NI',
          60 => 'P-6NN33546YY109324VLZZP22Q',
          70 => 'P-5FL65963RW0644536LZZP3EI',
          80 => 'P-0B6506262T8499838LZZP3NA',
          90 => 'P-8G1160078J904994GLZZP32I',
          100 => 'P-3L186620MS932394TLZZP5TI',
        ]
      ];
      */

      $this->plan_ids = [
        'regular' => [
          0 => 'P-5X650735YN566674DLZ3OAKA',
          100 => 'P-89B3032974950315TLZ3K6FA',
        ],
        'gift' => [
          0 => 'P-5V947410M6754683WLZ3OFEI',
        ]
      ];
    }
  }

  public function start_session() {
  	if(!session_id()) {
  		session_start();
  	}
  }

  public function admin_menu() {
    $main_menu = add_menu_page(
      'Magazine Subscriptions',
      'Magazine Subscriptions',
      'edit_posts',
      $this->plugin_slug,
      array( $this, 'index' ),
      'dashicons-book',
      10
    );

    $submenu_coupons = add_submenu_page(
      $this->plugin_slug,
      'Coupons',
      'Coupons',
      'edit_posts',
      $this->plugin_slug .'-coupons',
      array( $this, 'manage_coupons' )
    );
  }

  public function index() {
    global $wpdb;

    $status = isset( $_GET['status'] ) ? $_GET['status'] : '0';
?>
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <h1>Magazine Subscriptions</h1>
      <div>
        Filter:
        <a href="<?php echo add_query_arg( 'status', '0'  ); ?>" style="margin: 0 .25rem;<?php echo '0' == $status ? ' font-weight: bold;' : ''; ?>">All</a>
        |
        <a href="<?php echo add_query_arg( 'status', 'init'  ); ?>" style="margin: 0 .25rem;<?php echo 'init' == $status ? ' font-weight: bold;' : ''; ?>">Init</a>
        |
        <a href="<?php echo add_query_arg( 'status', 'subscribed'  ); ?>" style="margin: 0 .25rem;<?php echo 'subscribed' == $status ? ' font-weight: bold;' : ''; ?>">Subscribed</a>

      </div>
    </div>

    <table class="widefat fixed" cellspacing="0">
      <thead>
        <tr>
          <th>Subscriber</th>
          <!-- <th>Shipping Address</th> -->
          <th>Is Gift?</th>
          <th>Buyer</th>
          <!-- <th>Email</th>
          <th>Buyer Address</th> -->
          <th>Coupon</th>
          <th>Status</th>
          <th>PayPal Details</th>
          <th>Created Date/Time</th>
          <th>Salesforce Record</th>
        </tr>
      </thead>
<?php
    $query = "SELECT * FROM {$wpdb->prefix}mag_subscriptions WHERE 1=1 ";

    if ( '0' != $status && in_array( $status, array( 'init', 'subscribed' ) ) ) {
      $query .= " AND status = '{$status}' ";
    }
    $query .= " ORDER BY id DESC";

    $subs = $wpdb->get_results( $query );
    if ( $subs ) :
?>
      <tbody id="the-list">
<?php foreach( $subs as $sub ) : ?>
        <tr>
          <td><strong><?php echo $sub->sub_full_name; ?></strong><br>
            <?php
            echo $sub->shipping_address_1;
            echo $sub->shipping_address_2 ? '<br>' . $sub->shipping_address_2 : '';
            echo '<br>' . $sub->shipping_city . ' ' . $sub->shipping_state . ' ' . $sub->shipping_postcode;
          ?></td>
          <td><?php echo $sub->is_gift; ?></td>
          <td><strong><?php echo $sub->full_name; ?></strong><br>
            <a href="mailto:<?php echo $sub->email; ?>"><?php echo $sub->email; ?></a><br>
            <?php
            echo $sub->address_1;
            echo $sub->address_2 ? '<br>' . $sub->address_2 : '';
            echo '<br>' . $sub->city . ' ' . $sub->state . ' ' . $sub->postcode;
          ?></td>
          <td><?php echo $sub->coupon_id ? $wpdb->get_var( "SELECT coupon_code FROM {$wpdb->prefix}mag_coupons WHERE id = {$sub->coupon_id} LIMIT 1") : ''; ?></td>

          <td><?php echo $sub->status; ?></td>

          <td>
            <?php if ( $sub->orderID ) : ?>
            <strong>Order ID</strong><br><?php echo $sub->orderID; ?><br>
            <?php endif; ?>
            <?php if ( $sub->subscriptionID ) : ?>
            <strong>Sub ID</strong><br><?php echo $sub->subscriptionID; ?>

            <?php

            // $subscription = $this->paypal_request( 'billing/subscriptions/' . $sub->subscriptionID );
            // echo '<pre>'; print_r( $subscription ); echo '</pre>';
            ?>

            <?php endif; ?>
          </td>

          <td><?php echo $sub->created_at; ?></td>
          <td><a href="https://thebragmedia.lightning.force.com/lightning/r/Magazine_Subscription__c/<?php echo $sub->salesforce_id; ?>/view" target="_blank"><?php echo $sub->salesforce_id; ?></a></td>
        </tr>
<?php endforeach; ?>
      </tbody>
<?php endif; ?>
    </table>
<?php
  }

  protected function generatePaypalAccessToken() {
    // $this->paypal_access_token = $this->paypal_request( 'oauth2/token', 'grant_type=client_credentials' )->access_token;
  }

  /*
   * List coupons
   */
  function manage_coupons() {
    include_once( plugin_dir_path( __FILE__ ) . 'partials/coupons/index.php' );
  }

  public function click_button() {
    if( check_ajax_referer( $this->plugin_name . '_nonce', 'nonce' ) ) :
      $post = $_POST;

      // if ( $post['v'] != $_SESSION['tbm-mag-sub'] ) {
        // wp_send_json_error( array( 'result' => 'Invalid request - Session Error. ' . $post['v'] . ' ||| ' . $_SESSION['tbm-mag-sub'] ) );
      // }
      foreach( $post as $key => $value ) {
        if ( 'v' == $key )
          continue;
        $_SESSION[$key] = $value;
      }

      global $wpdb;

      $discount = 0;

      $post['coupon_id'] = null;
      if ( isset( $post['coupon_code'] ) && '' != trim( $post['coupon_code'] ) ) :
        $coupon_code = trim( $post['coupon_code'] );

        $coupon = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}mag_coupons WHERE coupon_code = '{$coupon_code}' AND status = 'active' AND expiry_date >= '" . date('Y-m-d') . "' LIMIT 1 " );

        if( $coupon ) :
          $post['coupon_id'] = $coupon->id;
          $discount = $coupon->discount;
        else :
          wp_send_json_error( array( 'result' => 'Invalid Coupon code' ) );
        endif;
      else :
        $post['coupon_code'] = '';
      endif;

      // Set Shipping address if it' not a gift
      if ( ! isset( $post['purchase_as_gift'] ) ) :
        $post['shipping_address_1'] = $post['sub_address_1'];
        $post['shipping_address_2'] = $post['sub_address_2'];
        $post['shipping_city'] = $post['sub_city'];
        $post['shipping_state'] = $post['sub_state'];
        $post['shipping_postcode'] = $post['sub_postcode'];

        $post['sub_full_name'] = $post['buyer_full_name'];

        $post['is_gift'] = 'no';
        $plan_id = isset( $this->plan_ids[ 'regular' ][ $discount ] ) ? $this->plan_ids[ 'regular' ][ $discount ] : $this->plan_ids[ 'regular' ][0];
      else :
        $post['is_gift'] = 'yes';
        $plan_id = isset( $this->plan_ids[ 'gift' ][ $discount ] ) ? $this->plan_ids[ 'gift' ][ $discount ] : $this->plan_ids[ 'gift' ][0];
      endif;

      // wp_send_json_success( array( 'plan_id' => $plan_id ) ); wp_die();

      // wp_send_json_error( array( 'result' => $plan_id ) );
      // exit;

      $wpdb->insert(
        $wpdb->prefix . 'mag_subscriptions',
        array(
          'full_name' => $post['buyer_full_name'],
          'email' => $post['sub_email'],

          'address_1' => $post['sub_address_1'],
          'address_2' => $post['sub_address_2'],
          'city' => $post['sub_city'],
          'state' => $post['sub_state'],
          'postcode' => $post['sub_postcode'],

          'coupon_id' => $post['coupon_id'],
          'is_gift' => $post['is_gift'],

          'sub_full_name' => $post['sub_full_name'],
          'shipping_address_1' => $post['shipping_address_1'],
          'shipping_address_2' => $post['shipping_address_2'],
          'shipping_city' => $post['shipping_city'],
          'shipping_state' => $post['shipping_state'],
          'shipping_postcode' => $post['shipping_postcode'],

          'status' => 'init',
          'ip_address' => $_SERVER['REMOTE_ADDR'],
        )
      );
      $_SESSION['sub_record'] = $wpdb->insert_id;

      wp_send_json_success( array( 'plan_id' => $plan_id ) ); wp_die();
    else :
      wp_send_json_error( array( 'result' => 'Invalid request' ) );
    endif;
  }

  public function subscribe_success() {
    if( check_ajax_referer( $this->plugin_name . '_nonce', 'nonce' ) ) :
      $post = $_POST;
      if ( isset( $_SESSION['sub_record'] ) ) :
        $sub_record = (int) $_SESSION['sub_record'];
        global $wpdb;
        $wpdb->update(
          $wpdb->prefix . 'mag_subscriptions',
          array(
            'status' => 'subscribed',
            'subscriptionID' => $post['subscriptionID'],
            'orderID' => $post['orderID'],
            'facilitatorAccessToken' => $post['facilitatorAccessToken'],
          ),
          array(
            'id' => $sub_record
          )
        );

        /*
        * Deactivate Coupon + Create Salesforce Record
        */
        $subscriber = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}mag_subscriptions WHERE id = {$sub_record} LIMIT 1");

        /*
         * Send an email to the subscriber
         */
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $to = $subscriber->email;
        $subject = 'Thank you for subscribing to Rolling Stone Australia';

        ob_start();
        include( get_template_directory() . '/email-templates/header.php' );
        ?>
        <p><strong>Dear <?php echo $subscriber->full_name; ?>,</strong></p>
        <p>You've just signed up to one of the most iconic, trusted, and recognisable publications in the world. By subscribing, you’re helping to ensure the continuation of our world-class journalism that exists to bring you the best in music, culture, sports, TV, movies, and politics.</p>
        <p>You'll soon start receiving our daily newsletter which collects the day's biggest headlines for your convenience, so be sure to check your inbox every day to keep up to date with what's going on across Australia and the world.</p>
        <p>As part of the continuous service you requested your subscription will be automatically renewed at the end of your subscription term. For more information, please see the Automatic Renewal details below.</p>
        <div>
          <table border="0" cellpadding="5" cellspacing="0">
            <tr>
              <td valign="middle" style="background: #eeeeee; border-top: 1px solid #000000; border-bottom: 1px solid #000000; padding-top: 15px; padding-bottom: 15px; padding-left: 15px; padding-right: 15px; width: 200px;">
                <img src="https://au.rollingstone.com/subscribe-magazine/assets/Cover.jpg" width="180" style="width: 180px; height: auto;">
              </td>
              <td valign="middle" style="background: #eeeeee; border-top: 1px solid #000000; border-bottom: 1px solid #000000; padding-top: 15px; padding-bottom: 15px; padding-left: 15px; padding-right: 15px; width: 100%;">
                <h2>SUBSCRIPTION SUMMARY</h2>
                <table border="0" cellpadding="2" cellspacing="0">
                  <tr>
                    <td><strong>Subscription ID:</strong></td>
                    <td><?php echo $subscriber->subscriptionID; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Issues:</strong></td>
                    <td>4</td>
                  </tr>
                  <tr>
                    <td><strong>Amount Paid:</strong></td>
                    <td>$65.95</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
        <p>&nbsp;</p>
        <p style="font-size: 11px;"><strong>Automatic Renewal and Delivery Terms:</strong> After your first term, your subscription will automatically renew until you tell us to stop. Each year we'll notify you of the rate for the next term and if you do nothing, your credit/debit card will be charged that rate, or if we are unable to charge you, we will send you an invoice. We reserve the right to change the number of issues in a subscription term, including discontinuing any format, make substitutions and/or modify the manner in which the subscription is distributed. Once you order an annual subscription you may change your address at any time by contacting our <a href="mailto:subscribe@thebrag.media" target="_blank">Customer Service</a>,however annual subscriptions may not be cancelled until the current subscription is complete. Your subscription is subject to our <a href="https://thebrag.com/media/terms-and-conditions" target="_blank">Terms of Use</a> and <a href="https://thebrag.com/media/privacy-policy/" target="_blank">Privacy Policy</a>.</p>
        <p>&nbsp;</p>
        <h6 align="center" style="text-align: center; font-family:Helvetica, Arial, sans-serif;font-size:20px;line-height:40px;font-weight:bold;color:#555555;">CONNECT WITH US</h6>
        <table border="0" cellspacing="0" cellpadding="0" align="center" role="presentation">
          <tbody>
            <tr>
              <td valign="top" align="center" style="border: 1px solid #555555;">
                <a href="https://www.facebook.com/rollingstoneaustralia/" target="_blank" style="text-decoration:none;"><img src="https://cdn-r2-2.thebrag.com/pages/edm/images/footer_social_facebook.png" width="40" height="40" alt="facebook" style="font-family:Arial, sans-serif; font-size:12px; line-height:15px; color:#000000; display:block;" border="0" ></a>
              </td>
              <td width="25" style="width:25px;">&nbsp;</td>
              <td valign="top" align="center" style="border: 1px solid #555555;">
                <a href="https://twitter.com/rollingstoneaus" target="_blank" style="text-decoration:none;"><img src="https://cdn-r2-2.thebrag.com/pages/edm/images/footer_social_twitter.png" width="40" height="40" alt="twitter" style="font-family:Arial, sans-serif; font-size:12px; line-height:15px; color:#000000; display:block;" border="0" ></a>
              </td>
              <td width="25" style="width:25px;">&nbsp;</td>
              <td valign="top" align="center" style="border: 1px solid #555555;">
                <a href="https://instagram.com/rollingstoneaus" target="_blank" style="text-decoration:none;"><img src="https://cdn-r2-2.thebrag.com/pages/edm/images/footer_social_instagram.png" width="40" height="40" alt="instagram" style="font-family:Arial, sans-serif; font-size:12px; line-height:15px; color:#000000; display:block;" border="0"></a>
              </td>
              <td width="25" style="width:25px;">&nbsp;</td>
              <td valign="top" align="center" style="border: 1px solid #555555;">
                <a href="https://www.youtube.com/channel/UC5ogXwEsy_q8_2DQHp1RU8A" target="_blank" style="text-decoration:none;"><img src="https://cdn-r2-2.thebrag.com/pages/edm/images/footer_social_youtube.png" width="40" height="40" alt="youtube" style="font-family:Arial, sans-serif; font-size:12px; line-height:15px; color:#000000; display:block;" border="0"></a>
              </td>
            </tr>
          </tbody>
        </table>
        <p>&nbsp;</p>
        <p style="color: #999;">Regards,<br><strong><?php bloginfo(); ?></strong></p>
        <?php
        include( get_template_directory() . '/email-templates/footer.php' );

        $body = ob_get_contents();
        ob_end_clean();

        wp_mail( $to, $subject, $body, $headers );

        if ( ! is_null( $subscriber->coupon_id ) ) :
          $coupon = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}mag_coupons WHERE id = {$subscriber->coupon_id} LIMIT 1" );
        endif;

        $coupon_code = '';
        if ( isset( $coupon ) && $coupon ) :
          $coupon_code = $coupon->coupon_code;
            if ( 'limited' == $coupon->type ) :
            $wpdb->update(
              $wpdb->prefix . 'mag_coupons',
              array(
                'status' => 'inactive',
                'used_at' => date('Y-m-d h:i:s')
              ),
              array(
                'id' => $coupon->id
              )
            );
          endif; // If coupon is limited one
        endif;

        $access_token = $this->salesforce_login( $this->salesforce_username, $this->salesforce_password );
        $url = $this->salesforce_login_uri . 'services/data/v47.0/sobjects/Magazine_Subscription__c/';
        $content = json_encode(
          array(
            'Name' => $subscriber->sub_full_name,
            'Buyer__c' => $subscriber->full_name,
            'Email__c' => $subscriber->email,

            'Address_1__c' => $subscriber->address_1,
            'Address_2__c' => $subscriber->address_2,
            'City__c' => $subscriber->city,
            'Postcode__c' => $subscriber->postcode,
            'State__c' => $subscriber->state,

            'Shipping_Address_1__c' => $subscriber->shipping_address_1,
            'Shipping_Address_2__c' => $subscriber->shipping_address_2,
            'Shipping_City__c' => $subscriber->shipping_city,
            'Shipping_Postcode__c' => $subscriber->shipping_postcode,
            'Shipping_State__c' => $subscriber->shipping_state,

            'Is_Gift__c' => $subscriber->is_gift == 'yes' ? true : false,
            'Coupon_code__c' => $coupon_code,

            'Created_At__c' => date('Y-m-d\Th:i:s'),

            'subscriptionID__c' => $subscriber->subscriptionID,
            'orderID__c' => $subscriber->orderID,
            'facilitatorAccessToken__c' => $subscriber->facilitatorAccessToken,

            'IP_Address__c' => $subscriber->ip_address,
          )
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array("Authorization: Bearer {$access_token}","Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($status != 201) {
          error_log( '--Salesforce Error: ' . $json_response );
          wp_send_json_error( array( 'result' => 'Error processing, please contact <a href="mailto:subscribe@thebrag.media" target="_blank">subscribe@thebrag.media</a> with the details you submitted.' ) );
        } else {
          $response = json_decode($json_response );
          $wpdb->update(
            $wpdb->prefix . 'mag_subscriptions',
            array(
              'salesforce_id' => $response->id,
            ),
            array(
              'id' => $sub_record
            )
          );
        }

        unset( $_SESSION );

        wp_send_json_success(); wp_die();
      else :
        wp_send_json_error( array( 'result' => 'Whoops, it looks like something went wrong. Please contact <a href="mailto:subscribe@thebrag.media" target="_blank">subscribe@thebrag.media</a> with the details you submitted.' ) );
      endif;
    else :
      wp_send_json_error( array( 'result' => 'Invalid request' ) );
    endif;
  }

  protected function salesforce_login($username, $password) {
    $params = 'grant_type=password' .
      '&client_id=' . $this->salesforce_client_id .
    	'&client_secret=' . $this->salesforce_client_secret .
    	'&username=' . urlencode($username) .
    	'&password=' . urlencode($password);

      $token_url = $this->salesforce_login_uri . "/services/oauth2/token";

      $curl = curl_init($token_url);
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
      $json_response = curl_exec($curl);
      $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

      curl_close($curl);

      $response = json_decode($json_response );

      return $response->access_token;
  }

  public function create_coupon() {
    if( isset( $_POST['tbm_coupon_nonce'] ) && wp_verify_nonce( $_POST['tbm_coupon_nonce'], 'tbm_coupon_form_nonce') ) {
      global $wpdb;
      $errors = array();
      $post_vars['coupon_code'] = isset( $_POST['coupon_code'] ) ? sanitize_text_field( $_POST['coupon_code'] ) : NULL;
      $post_vars['expiry_date'] = isset( $_POST['expiry_date'] ) ? DateTime::createFromFormat( 'Y-m-d', $_POST['expiry_date'] )->format('Y-m-d') : NULL;
      $post_vars['discount'] = isset( $_POST['discount'] ) ? (int) sanitize_text_field( $_POST['discount'] ) : 0;
      $post_vars['type'] = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : 'limited';
      $post_vars['status'] = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : 'active';

      $_SESSION['tbm']['coupons']['post_vars'] = $post_vars;

      if ( is_null( $post_vars['coupon_code'] ) || is_null( $post_vars['expiry_date'] ) || ! $post_vars['expiry_date'] || $post_vars['discount'] <= 0 || strtotime( $post_vars['expiry_date'] ) < time() ) :
        $errors[] = 'Please check the fields.';
      endif;

      if( $wpdb->get_var( "SELECT id FROM {$wpdb->prefix}coupons WHERE code = '{$post_vars['coupon_code']}' LIMIT 1" ) ) :
        $errors[] = 'The code has already been used.';
      endif;

      if ( count( $errors ) > 0 ) :
        $_SESSION['tbm']['coupons']['errors'] = $errors;
        wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_slug . '-coupons&action=create' ) );
        exit;
      else :
        $coupon = $wpdb->insert(
          $wpdb->prefix . 'mag_coupons',
          array(
            'coupon_code' => $post_vars['coupon_code'],
            'discount' => $post_vars['discount'],
            'expiry_date' => $post_vars['expiry_date'],
            'type' => $post_vars['type'],
            'status' => $post_vars['status'],
          )
        );
        if ( $coupon ) {
          wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_slug . '-coupons&id=' . $wpdb->insert_id ) );
          exit;
        } else {
          $_SESSION['tbm']['coupons']['errors'][] = 'Something went wrong with creating coupon';
          wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_slug . '-coupons&action=create' ) );
          exit;
        }
      endif;
    }
  }

  public function update_coupon() {
    if( isset( $_POST['tbm_coupon_nonce'] ) && wp_verify_nonce( $_POST['tbm_coupon_nonce'], 'tbm_coupon_form_nonce') ) {
      global $wpdb;
      $errors = array();

      $id = (int) $_POST['id'];

      $post_vars['id'] = $id;
      $post_vars['coupon_code'] = isset( $_POST['coupon_code'] ) ? sanitize_text_field( $_POST['coupon_code'] ) : NULL;
      $post_vars['expiry_date'] = isset( $_POST['expiry_date'] ) ? DateTime::createFromFormat( 'Y-m-d', $_POST['expiry_date'] )->format('Y-m-d') : NULL;
      $post_vars['discount'] = isset( $_POST['discount'] ) ? (int) sanitize_text_field( $_POST['discount'] ) : 1;
      $post_vars['type'] = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : 'limited';
      $post_vars['status'] = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : 'active';

      $_SESSION['tbm']['coupons']['post_vars'] = $post_vars;

      if ( is_null( $post_vars['coupon_code'] ) || is_null( $post_vars['expiry_date'] ) || ! $post_vars['expiry_date'] || $post_vars['discount'] <= 0 || strtotime( $post_vars['expiry_date'] ) < time() ) :
        $errors[] = 'Please check the fields.';
      endif;

      if ( count( $errors ) > 0 ) :
        $_SESSION['tbm']['coupons']['errors'] = $errors;
        wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_slug . '-coupons&action=edit&id=' . $id ) );
        exit;
      else :
        $coupon = $wpdb->update(
          $wpdb->prefix . 'mag_coupons',
          array(
            'coupon_code' => $post_vars['coupon_code'],
            'discount' => $post_vars['discount'],
            'expiry_date' => $post_vars['expiry_date'],
            'type' => $post_vars['type'],
            'status' => $post_vars['status'],
          ),
          array(
            'id' => $id,
          )
        );
        if ( $coupon !== false ) {
          wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_slug . '-coupons&id=' . $id ) );
          exit;
        } else {
          $_SESSION['tbm']['coupons']['errors'][] = 'Something went wrong with creating coupon';
          wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_slug . '-coupons&action=edit&id=' . $id ) );
          exit;
        }
      endif;
    }
  }

  protected function paypal_request( $request, $fields = '') {
    if ( ! $request )
      return;
    $url = $this->paypal_baseurl . $request;

    // return $url;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if ( $fields ) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    }
    if ( 'oauth2/token' != $request ) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, array( "Authorization: Bearer {$this->paypal_access_token}", "Content-Type: application/json") );
    } else {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_USERPWD, $this->paypal_client_id . ':' . $this->paypal_secret);
    }

    $result = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // return $result;

    if( empty( $result ) ) {
      // return '<pre>'; print_r( $url ); echo '</pre>';
    } else {
      $response = json_decode($result);
    }

    return $response;
  }
}

 new TBMMagSubPaypal();
