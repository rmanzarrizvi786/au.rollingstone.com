<?php

/**
 * Plugin Name: Magazine Subscriptions (Stripe)
 * Plugin URI: https://thebrag.media
 * Description:
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI:
 */

class TBMMagSub {
    protected $plugin_name;
    protected $plugin_slug;

    protected $is_sandbox;
    protected $salesforce;
    protected $stripe;

    protected $required_fields;

    protected $base_price;
    protected $base_price_no_bundle;

    protected $shipping_cost;
    protected $number_of_issues;

    protected $is_promotion_running;
    protected $promotion_amount_off;

    protected $rest_api_keys; // Keys for API to RS AU Endpoints

    protected $api_brag_user; // Array to hold API info for Brag's API (key, url)

    protected $promo_tshirt;
    protected $promo_headphones;
    protected $promo_response_prefix;

    protected static $api_braze;

    protected $api_users;

    public function __construct() {
        $this->rest_api_keys = [
                'brag_users' => '39d733e9-5277-4389-811a-a14c9f1e9294',
        ];

        $this->is_sandbox = false; // isset($_ENV) && isset($_ENV['ENVIRONMENT']) && 'sandbox' == $_ENV['ENVIRONMENT'];
        // in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']); //false;

        $this->plugin_name = 'tbm_mag_sub';
        $this->plugin_slug = 'tbm-mag-sub';

        $this->base_price = 69.95; // change
        $this->base_price_no_bundle = 49.95;

        $this->shipping_cost = 0.00;
        $this->number_of_issues = 4;

        $this->is_promotion_running = true;
        $this->promotion_amount_off = 1499; // Cents

        $this->api_users = [
                'fewcents.co' => 'xumof-S_+E-R7p$FitaQ'
        ];

        $this->api_brag_user['rest_api_key'] = '1fc08f46-3537-43f6-b5c1-c68704acf3fa';
        if ($this->is_sandbox) {
                // $this->api_brag_user['api_url'] = 'https://staging.thebrag.com/wp-json/tbm_users/v1/';
                // $this->api_brag_user['api_url'] = 'http://the-brag.com/wp-json/tbm_users/v1/';
                $this->api_brag_user['api_url'] = 'http://172.19.0.1:8083/wp-json/tbm_users/v1/';

                // Braze
                self::$api_braze['sdk_api_key'] = '08d1f29b-c48e-4bb3-aef3-e133789a0c89';
                self::$api_braze['sdk_endpoint'] = 'sdk.iad-05.braze.com';
                self::$api_braze['api_key'] = '427ad36b-a698-44cb-982e-9dd08c3cbddf';
        } else {
                $this->api_brag_user['api_url'] = 'https://thebrag.com/wp-json/tbm_users/v1/';

                // Braze
                self::$api_braze['sdk_api_key'] = '08d1f29b-c48e-4bb3-aef3-e133789a0c89';
                self::$api_braze['sdk_endpoint'] = 'sdk.iad-05.braze.com';
                self::$api_braze['api_key'] = '427ad36b-a698-44cb-982e-9dd08c3cbddf';
        }

        /* 
        if (isset($_GET['debug'])) {
                $data_api['email'] = '1@thebrag.media';
                $data_api['full_name'] = 'Sachin Patel';
                $data_api['key'] = $this->api_brag_user['rest_api_key'];
                $data_api['source'] = 'rs-mag';

                $user_json = $this->callAPI(
                        'POST',
                        $this->api_brag_user['api_url'] . 'create',
                        $data_api,
                        false
                );

                $user = json_decode($user_json);

                echo '<pre>';
                        print_r($user->data->user_id);
                echo '</pre>';
                exit;
                error_log($user_json);
        } 
        */

        $this->promo_tshirt = false;
        $this->promo_headphones = false;
        $this->promo_response_prefix = 'Fender Stratocaster signed by Keith Urban';

        $this->required_fields = [
                'buyer_full_name',
                'sub_email',
                'sub_full_name',
                'shipping_address_1',
                'shipping_city',
                'shipping_state',
                'shipping_postcode',
                'shipping_country',
        ];

        if ($this->promo_tshirt) {
                $this->required_fields[] = 'tshirt_size';
        }

        add_action('init', array($this, 'start_session'), 1);
        add_action('admin_menu', array($this, 'admin_menu'));

        /* Paypal - Actions */
        // add_action('wp_ajax_subscribe_success', array($this, 'subscribe_success'));
        // add_action('wp_ajax_nopriv_subscribe_success', array($this, 'subscribe_success'));

        /* Stripe - Actions */
        add_action('wp_ajax_get_stripe_public_key', array($this, 'get_stripe_public_key'));
        add_action('wp_ajax_nopriv_get_stripe_public_key', array($this, 'get_stripe_public_key'));

        add_action('wp_ajax_get_stripe_payment_intent', array($this, 'get_stripe_payment_intent'));
        add_action('wp_ajax_nopriv_get_stripe_payment_intent', array($this, 'get_stripe_payment_intent'));

        add_action('wp_ajax_process_subscription', array($this, 'process_subscription'));
        add_action('wp_ajax_nopriv_process_subscription', array($this, 'process_subscription'));

        /* Salesforce - Order complete */
        add_action('wp_ajax_order_complete', array($this, 'order_complete'));
        add_action('wp_ajax_nopriv_order_complete', array($this, 'order_complete'));

        /* Coupons */
        add_action('admin_post_tbm_create_coupon', array($this, 'create_coupon'));
        add_action('admin_post_tbm_update_coupon', array($this, 'update_coupon'));

        /* Check Coupon */
        add_action('wp_ajax_check_coupon', array($this, 'ajax_check_coupon'));
        add_action('wp_ajax_nopriv_check_coupon', array($this, 'ajax_check_coupon'));

        /* Observer */
        // add_action('wp_ajax_subscribe_observer', array($this, 'ajax_subscribe_observer'));
        // add_action('wp_ajax_nopriv_subscribe_observer', array($this, 'ajax_subscribe_observer'));

        /* Create user */
        add_action('wp_ajax_get_or_create_user', [$this, 'ajax_get_or_create_user']);
        add_action('wp_ajax_nopriv_get_or_create_user', [$this, 'ajax_get_or_create_user']);

        // REST API
        add_action('rest_api_init', [$this, '_rest_api_init']);

        // Activation
        register_activation_hook(__FILE__, [$this, 'activate']);

        // Deactivation
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        // Cron
        add_action('cron_hook_tbm_mag_sub_renewals', [$this, 'tbm_mag_sub_renewals']);
        add_action('cron_hook_tbm_mag_send_overdue_invoices', [$this, 'tbm_mag_send_overdue_invoices']);
        add_action('cron_hook_tbm_mag_send_comps_renewals', [$this, 'tbm_mag_send_comps_renewals']);

        // Renwals
        add_action('wp_ajax_process_renewals', [$this, 'ajax_process_renewals']);
        add_action('wp_ajax_send_comps_renewals', [$this, 'ajax_send_comps_renewals']);
        add_action('wp_ajax_send_overdue_invoices', [$this, 'ajax_send_overdue_invoices']);

        // Cron schedules
        add_filter('cron_schedules', [$this, '_cron_schedules']);
    }

    public function get_stripe_public_key() {
            $config = include __DIR__ . '/classes/config.php';
            wp_send_json_success($config['stripe']['publishable_key']);
            wp_die();
    }

    public function get_stripe_payment_intent() {
        require_once __DIR__ . '/classes/payment.class.php';

        $payment = new Payment();

        $payment_intent = $payment->createIntent(5995, 'aud');

        wp_send_json_success(array(
            'client_secret' => $payment_intent->client_secret
        ));

        wp_die();
    }

        /*
        * Pay button - clicked
        */
        public function process_subscription() {
                if (!check_ajax_referer($this->plugin_name . '_nonce', 'nonce')) {
                        wp_send_json_error(['error' => ['message' => 'Whoops, like something unexpected happened on our side of things. Feel free to refresh your browser and give it another shot!']]);
                        wp_die();
                }

                require_once __DIR__ . '/classes/payment.class.php';
                require_once __DIR__ . '/classes/coupon.class.php';
                require_once __DIR__ . '/classes/subscription.class.php';

                $post = $_POST;

                foreach ($post as $key => $value) {
                        if ('v' == $key) {
                                continue;
                        }
           
                        $_SESSION[$key] = $value;
                }

                // Set Shipping address if it' not a gift
                if (!isset($post['purchase_as_gift'])) {
                        $required_fields = [
                                'buyer_full_name',
                                'sub_email',
                                'sub_full_name',
                                'sub_address_1',
                                'sub_city',
                                'sub_state',
                                'sub_postcode',
                                'sub_country',
                        ];

                        $post['shipping_address_1'] = $post['sub_address_1'];
                        $post['shipping_address_2'] = $post['sub_address_2'];
                        $post['shipping_city'] = $post['sub_city'];
                        $post['shipping_state'] = $post['sub_state'];
                        $post['shipping_postcode'] = $post['sub_postcode'];
                        $post['shipping_country'] = $post['sub_country'];

                        $post['sub_full_name'] = $post['buyer_full_name'];

                        $post['is_gift'] = 'no';
                
                } else {
                        $required_fields = [
                                'buyer_full_name',
                                'sub_email',
                                'sub_address_1',
                                'sub_city',
                                'sub_state',
                                'sub_postcode',
                                'sub_country',
                                'sub_full_name',
                                'shipping_address_1',
                                'shipping_city',
                                'shipping_state',
                                'shipping_postcode',
                                'shipping_country',
                        ];

                        $post['is_gift'] = 'yes';
                }

                /*
                * Fields validation
                */

                if ($this->promo_tshirt) {
                        $required_fields[] = 'tshirt_size';
                }

                foreach ($required_fields as $required_field) {
                        if (!isset($post[$required_field]) || '' == trim($post[$required_field])) {
                                wp_send_json_error(['error' => ['message' => 'Whoops, looks like you have forgotten to fill out all the necessary fields. Make sure you go back and give us all the info we need!', 'elem' => $required_field]]);
                                wp_die();
                        }
                }

                if (!is_email($post['sub_email'])) {
                        wp_send_json_error(['error' => ['message' => 'Whoops, looks like you have entered invalid email address!']]);
                        wp_die();
                }

                if (!array_search($post['shipping_country'], Payment::getCountries()) || '0' == array_search($post['shipping_country'], Payment::getCountries())) {
                        wp_send_json_error(['error' => ['message' => 'Looks like you forgot to select your country. Make sure you press the drop down list and pick the correct one!']]);
                        wp_die();
                }

                // wp_send_json_error( [ 'error' => [ 'message' => print_r( $post, true ) ] ] ); wp_die();

                global $wpdb;

                /*
                * Coupon code + Validation
                */

                $discount = 0;

                $coupon = '';
                $post['coupon_id'] = null;
                $coupon_code = isset($post['coupon_code']) && '' != trim($post['coupon_code']) ? trim($post['coupon_code']) : '';

                if (isset($post['coupon_code']) && '' != trim($post['coupon_code'])) {
                    $coupon_code = trim($post['coupon_code']);
                    $coupon_obj = new Coupon();
                    $coupon = $coupon_obj->validateCoupon($coupon_code, $post['sub_email']);
                }

                if (isset($coupon['error'])) {
                    wp_send_json_error(['error' => ['message' => $coupon['error']]]);
                    // 'Looks like that coupon code is invalid. If you are sure you are entering it correctly, send us an email at subscribe@thebrag.media to sort out the issue.']]);
                    wp_die();
                } elseif (is_null($coupon)) {
                    wp_send_json_error(['error' => ['Looks like that coupon code is invalid. If you are sure you are entering it correctly, send us an email at subscribe@thebrag.media to sort out the issue.']]);
                    wp_die();
                } elseif (isset($coupon['success'])) {
                    $discount = $coupon['amount_off'];
                    $post['coupon_id'] = $coupon['id'];
                    $post['coupon_code'] = ''; // $coupon->stripe_coupon_code;
                } else {
                    $post['coupon_code'] = '';
                }

                $post['promo_response_prefix'] = $this->promo_response_prefix;

                /*
                * Create Subscription
                */
                
                $subscription = new Subscription();
                $subscription->create($post);

                /*
                * Create Customer
                */
                
                $payment = new Payment();

                $buyer = [
                        'full_name' => $post['buyer_full_name'],
                        'address_1' => $post['sub_address_1'],
                        'address_2' => $post['sub_address_2'],
                        'city' => $post['sub_city'],
                        'country' => array_search($post['sub_country'], self::getCountries()),
                        'postcode' => $post['sub_postcode'],
                        'state' => $post['sub_state'],
                ];

                $shipping = [
                        'address_1' => $post['shipping_address_1'],
                        'address_2' => $post['shipping_address_2'],
                        'city' => $post['shipping_city'],
                        'country' => array_search($post['shipping_country'], self::getCountries()),
                        'postcode' => $post['shipping_postcode'],
                        'state' => $post['shipping_state'],
                ];

                $price = $this->base_price;
                
                if (isset($post['buy_option']) && 'no-bundle' == $post['buy_option']) {
                        $price = $this->base_price_no_bundle;
                }

                $invoice = $payment->createInvoice(
                        (int) ($price * 100),
                        (int) ($this->shipping_cost * 100),
                        $this->number_of_issues,
                        $coupon_code,
                        isset($coupon) ? (int) ($coupon['amount_off'] * 100) * -1 : 0,
                        $post['payment_method'],
                        $post['sub_email'],
                        $post['sub_full_name'],
                        $buyer,
                        $shipping
                );

                // $customer = $payment->createCustomer( $post['payment_method'], $post['sub_email'], $post['sub_full_name'], $buyer, $shipping );

                if (isset($invoice['error'])) {
                        $subscription->update([
                                'payment_error' => $invoice['error'],
                        ]);
                        
                        wp_send_json_error([
                                'error' => [
                                        'message' => $invoice['error']
                                ]
                        ]);

                        wp_die();
                }

                /*
                * Update Subscription with Customer & Invoice id
                */
                if ($invoice) {
                        $subscription->update([
                                'stripe_invoice_id' => $invoice['invoice']->id,
                                'stripe_customer_id' => $invoice['customer']->id,
                                'amount_paid' => $invoice['invoice']->amount_paid,
                        ]);

                        // Generate coupon if a promotional coupon is active
                        if ($this->is_promotion_running) {
                                $coupon = new Coupon();
                                $coupon_code = $coupon->createRandomCoupon($subscription, $this->promotion_amount_off, 'unlimited');
                        } // If $this->stripe['current_coupon_code'] is NOT null

                        wp_send_json_success(array('subscription' => $subscription, 'invoice' => $invoice['invoice']));
                        wp_die();
                } // If $customer
        }

        public function start_session() {
                if (!session_id()) {
                        session_start();
                }
        }

        public function admin_menu() {
                $main_menu = add_menu_page(
                        'Magazine Subscriptions',
                        'Magazine Subscriptions',
                        'edit_posts',
                        $this->plugin_slug,
                        array($this, 'index'),
                        'dashicons-book',
                        10
                );

                /* {{ Admins only */
                add_submenu_page(
                        $this->plugin_slug,
                        'Process renewals',
                        'Process renewals',
                        'administrator',
                        $this->plugin_slug . '-process-renewals',
                        array($this, 'show_process_renewals')
                );

                /* 
                add_submenu_page(
                        $this->plugin_slug,
                        'Facebook Custom Audience',
                        'Facebook Custom Audience',
                        'administrator',
                        $this->plugin_slug . '-facebook-custom-audience',
                        array($this, 'facebook_custom_audience')
                );
                */

                /*
                $submenu_coupons = add_submenu_page(
                        $this->plugin_slug,
                        'Coupons',
                        'Coupons',
                        'edit_posts',
                        $this->plugin_slug .'-coupons',
                        array( $this, 'manage_coupons' )
                );
                */

                /* }} Admins only */
        }

        public function facebook_custom_audience() {
                require_once __DIR__ . '/classes/facebook.class.php';
                
                $facebook = new Facebook();
                $facebook->index();
        }

        public function index() {
                // var_dump( $this->generate_random_coupon() ); exit;
                global $wpdb;

                $status = isset($_GET['status']) ? $_GET['status'] : '0';
                $used_coupon = isset($_GET['used_coupon']) && $_GET['used_coupon'] == '1' ? true : false;
                $statuses = $wpdb->get_results("SELECT status, COUNT(*) total FROM {$wpdb->prefix}mag_subscriptions GROUP BY status");
                $count_coupons_used = $wpdb->get_var("SELECT COUNT(*) total FROM {$wpdb->prefix}mag_subscriptions WHERE coupon_id IS NOT NULL");
        ?>
        <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1>Magazine Subscriptions</h1>
                <div>
                        Filter:
                        <a href="<?php echo add_query_arg('status', '0', remove_query_arg('used_coupon')); ?>" style="margin: 0 .25rem;<?php echo '0' == $status ? ' font-weight: bold;' : ''; ?>">All</a>
                        <?php foreach ($statuses as $s) : ?>
                        |
                        <a href="<?php echo add_query_arg('status', $s->status); ?>" style="margin: 0 .25rem;<?php echo $s->status == $status ? ' font-weight: bold;' : ''; ?>"><?php echo ucfirst($s->status); ?> (<?php echo $s->total; ?>)</a>
                        <?php endforeach; ?>
                        |
                        <a href="<?php echo add_query_arg('used_coupon', $used_coupon ? 0 : 1); ?>" style="margin: 0 .25rem;<?php echo true == $used_coupon ? ' font-weight: bold;' : ''; ?>">Used Coupon (<?php echo $count_coupons_used; ?>)</a>
                </div>
        </div>

        <input type="text" name="search-mag-sub" id="search-mag-sub" placeholder="Search..." class="form-control">
        <table class="widefat" cellspacing="0" id="list-mag-subs">
                <thead>
                        <tr>
                                <th>&nbsp;</th>
                                <th>Subscriber</th>
                                <th>Is Gift?</th>
                                <th>Buyer</th>
                                <th>TBM Coupon</th>
                                <th>Stripe Coupon</th>
                                <th>Status</th>
                                <!-- <th>PayPal Details</th> -->
                                <th>Created Date/Time</th>
                                <th>Salesforce Record</th>
                                <th>Stripe Record</th>
                                <th>Unique ID</th>
                        </tr>
                </thead>
                <?php
                        $query = "SELECT
                                s.id s_id,
                                s.sub_full_name,
                                s.shipping_address_1, s.shipping_address_2, s.shipping_city,
                                s.shipping_state, s.shipping_postcode, s.shipping_country,
                                s.address_1, s.address_2, s.city, s.state, s.postcode, s.country,
                                s.full_name, s.email, s.is_gift, s.status,
                                s.created_at, s.salesforce_id, s.uniqid,
                                s.stripe_customer_id,
                                c.coupon_code, c.stripe_coupon_code
                        FROM {$wpdb->prefix}mag_subscriptions s
                        LEFT JOIN {$wpdb->prefix}mag_coupons_stripe c ON s.coupon_id = c.id
                        WHERE 1=1 ";

                        if ('0' != $status) {
                                $query .= " AND s.status = '{$status}' ";
                        }
            
                        if ($used_coupon) {
                                $query .= " AND s.coupon_id IS NOT NULL";
                        }
                        
                        $query .= " ORDER BY s.id DESC";
                        $subs = $wpdb->get_results($query);
                        
                        if ($subs) {
                                $count = 0;
                ?>
                        <tbody id="the-list">
                                <?php foreach ($subs as $sub) {
                                        if (is_null($sub->uniqid)) {
                                                $uniqid = uniqid();
                                                $wpdb->update(
                                                        $wpdb->prefix . 'mag_subscriptions',
                                                        ['uniqid' => $uniqid,],
                                                        ['id' => $sub->s_id]
                                                );
                                        }

                                        $count++;
                                ?>
                                <tr>
                                        <td><?php echo $count; ?>.</td>
                                        <td>
                                                <strong><?php echo $sub->sub_full_name; ?></strong><br>
                                                <?php
                                                        echo $sub->shipping_address_1;
                                                        echo $sub->shipping_address_2 ? '<br>' . $sub->shipping_address_2 : '';
                                                        echo '<br>' . $sub->shipping_city . ' ' . $sub->shipping_state . ' ' . $sub->shipping_postcode . ' ' . $sub->shipping_country;
                                                ?>
                                        </td>
                                        <td>
                                                <?php echo $sub->is_gift; ?>
                                        </td>
                                        <td>
                                                <strong><?php echo $sub->full_name; ?></strong><br>
                                                <a href="mailto:<?php echo $sub->email; ?>"><?php echo $sub->email; ?></a><br>
                                                <?php
                                                        echo $sub->address_1;
                                                        echo $sub->address_2 ? '<br>' . $sub->address_2 : '';
                                                        echo '<br>' . $sub->city . ' ' . $sub->state . ' ' . $sub->postcode . ' ' . $sub->country;
                                                ?>
                                        </td>
                                        <td>
                                                <?php echo $sub->coupon_code; ?>
                                        </td>
                                        <td>
                                                <?php echo $sub->stripe_coupon_code; ?>
                                        </td>
                                        <td>
                                                <?php echo $sub->status; ?>
                                        </td>
                                        <!-- 
                                        <td>
                                                <?php if (isset($sub->orderID)) { ?>
                                                <strong>Order ID</strong><br><?php echo $sub->orderID; ?><br>
                                                <?php } ?>
                                                <?php if (isset($sub->subscriptionID)) { ?>
                                                <strong>Sub ID</strong><br><?php echo $sub->subscriptionID; ?>
                                                <?php } ?>
                                        </td>
                                        -->
                                        <td>
                                                <?php echo $sub->created_at; ?>
                                        </td>
                                        <td>
                                                <a href="https://thebragmedia.lightning.force.com/lightning/r/Magazine_Subscription__c/<?php echo $sub->salesforce_id; ?>/view" target="_blank">
                                                        <?php echo $sub->salesforce_id; ?>
                                                </a>
                                        </td>
                                        <td>
                                                <a href="https://dashboard.stripe.com/customers/<?php echo $sub->stripe_customer_id; ?>" target="_blank"><?php echo $sub->stripe_customer_id; ?></a>
                                        </td>
                                        <td>
                                                <?php echo $sub->uniqid; ?>
                                        </td>
                                </tr>
                                <?php } ?>
                        </tbody>
                <?php } ?>
        </table>

        <script>
                jQuery(document).ready(function($) {
                        $("#search-mag-sub").on("keyup", function() {
                                var value = $(this).val().toLowerCase();
                                
                                $("#list-mag-subs tr").filter(function() {
                                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                });
                        });
                });
        </script>
        <?php 
        }

        /*
        * List coupons
        */
        
        function manage_coupons() {
                include_once(plugin_dir_path(__FILE__) . 'partials/coupons/index.php');
        }

        /*
        * Get Stripe Plan Details
        */

        public static function getPlanDetails() {
                $self = new self();

                // Include Stripe SDK
                require_once(plugin_dir_path(__FILE__) . 'vendor/autoload.php');

                \Stripe\Stripe::setApiKey($self->stripe['secret_key']);

                return \Stripe\Plan::retrieve($self->stripe['plan_id']);
        }

        /*
        * Stripe order successful: Deactivate Coupon + Create Salesforce record
        */

        public function order_complete() {
                if (!check_ajax_referer($this->plugin_name . '_nonce', 'nonce')) {
                        error_log('--Subscription Error | Invalid Nonce');
                        wp_mail('dev@thebrag.media', 'RS Mag Error: Invalid Nonce', 'Invalid Nonce');
                        wp_send_json_error(['error' => ['message' => 'Whoops, like something unexpected happened on our side of things. Feel free to refresh your browser and give it another shot!']]);
                        wp_die();
                }

                // Nonce is valid at this stage, so proceeding

                require_once __DIR__ . '/classes/subscription.class.php';
                require_once __DIR__ . '/classes/coupon.class.php';
                require_once __DIR__ . '/classes/crm.class.php';

                $post = $_POST;

                // Get Subscription
                $subscription = new Subscription();
                $subscriber = $subscription->get($post['subscription_id']);
                $subscriber->update(
                        ['status' => 'active']
                );

                // if ( 'active' == $post['subscription_status'] ) :

                global $wpdb;

                /*
                * If coupon was used, Deactivate if required
                */

                $TBM_Coupon_code__c = '';
                if (!is_null($subscriber->coupon_id) && $subscriber->coupon_id > 0) {
                        $coupon_obj = new Coupon();
                        $tbm_coupon = $coupon_obj->get($subscriber->coupon_id);
                        
                        if ($tbm_coupon) {
                                $TBM_Coupon_code__c = $tbm_coupon->coupon_code;

                                if ('limited' == $tbm_coupon->type) {
                                        $tbm_coupon->update([
                                                'status' => 'inactive',
                                                'used_at' => current_time('mysql')
                                        ]);
                                } // If coupon is limited one
                        } // If coupon is found
                } // If subscriber has used coupon

                /*
                * Create record in CRM
                */
                
                $crm = new CRM();
                $crm_record = $crm->createSubscription($subscriber, $TBM_Coupon_code__c);
                
                if (isset($crm_record->error)) {
                        $subscriber->update([
                                'crm_error' => $crm_record->error,
                        ]);
                        
                        wp_send_json_error(['error' => ['message' => $crm_record['error']]]);
                        wp_die();
                } else {
                        $subscriber->update([
                                'salesforce_id' => $crm_record->id,
                        ]);
                }

                /*
                * Add to MailChimp
                */
                
                require_once(get_template_directory() . '/MailChimp.php');
                
                $mc_api_key = '727643e6b14470301125c15a490425a8-us1';
                $MailChimp = new \DrewM\MailChimp\MailChimp($mc_api_key);
                $mc_list_id = '543223853a'; // List ID for Rolling Stone Mag Subscribers
                $data_mc = array(
                        'email_address' => $subscriber->email,
                        'status' => 'subscribed',
                );
                $MailChimp->post("lists/$mc_list_id/members", $data_mc);

                $merge_fields = [
                        'FULLNAME' => $subscriber->full_name,
                ];

                $subscriber_hash = $MailChimp->subscriberHash($subscriber->email);
                $MailChimp->patch("lists/{$mc_list_id}/members/{$subscriber_hash}", [
                        'merge_fields' => $merge_fields
                ]);

                /*
                * Send an email to the subscriber
                */
                
                $headers[] = 'Content-Type: text/html; charset=UTF-8';
                // $headers[] = 'Bcc: dev@thebrag.media, daniel.boukata@thebrag.media';
                $to = $subscriber->email;
                $subject = 'Thank you for subscribing to the Rolling Stone Australia Magazine';

                ob_start();
                include(get_template_directory() . '/email-templates/header.php');
        ?>
        <p><strong>Dear <?php echo $subscriber->full_name; ?>,</strong></p>
        <p>You've just signed up to one of the most iconic, trusted, and recognisable publications in the world. By subscribing, you’re helping to ensure the continuation of our world-class journalism that exists to bring you the best in music, culture, sports, TV, movies, and politics.</p>
        <?php if ($this->promo_headphones) { ?>
        <p>Good news! You will also be receiving a pair of <strong>Audio Technica ATH-SR5 Headphones</strong> valued at <strong>$269</strong> with your subscription.</p>
        <?php } ?>
        <p>As part of the continuous service you requested your subscription will be automatically renewed at the end of your subscription term. For more information, please see the Automatic Renewal details below.</p>
        <div>
                <table align="center" border="0" cellpadding="5" cellspacing="0">
                        <tr>
                                <?php if (get_option('tbm_next_issue_cover') && '' != trim(get_option('tbm_next_issue_cover'))) : ?>
                                <td valign="middle" style="background: #eeeeee; border-top: 1px solid #000000; border-bottom: 1px solid #000000; padding-top: 15px; padding-bottom: 15px; padding-left: 15px; padding-right: 15px; width: 200px;">
                                        <img src="<?php echo trim(get_option('tbm_next_issue_cover')); ?>" width="180" style="width: 180px; height: auto;">
                                </td>
                                <?php endif; ?>
                                <td valign="middle" style="background: #eeeeee; border-top: 1px solid #000000; border-bottom: 1px solid #000000; padding-top: 15px; padding-bottom: 15px; padding-left: 15px; padding-right: 15px; width: 100%;">
                                        <h2>SUBSCRIPTION SUMMARY</h2>
                                        <table border="0" cellpadding="2" cellspacing="0">
                                                <tr>
                                                        <td><strong>Payment date:</strong></td>
                                                        <td><?php echo date('d M, Y', strtotime($subscriber->created_at)); ?></td>
                                                </tr>
                                                <tr>
                                                        <td><strong>Issues:</strong></td>
                                                        <td>4</td>
                                                </tr>
                                                <tr>
                                                        <td><strong>Amount Paid:</strong></td>
                                                        <td>$<?php echo number_format($subscriber->amount_paid / 100, 2); ?></td>
                                                </tr>
                                        </table>
                                </td>
                        </tr>
                </table>
        </div>

        <?php
                if ($this->is_promotion_running) {
                        $coupon_obj = new Coupon();
                        $coupon = $coupon_obj->getCouponForSubscription($subscriber);
                        
                        if ($coupon) {
        ?>
                <p>As a thanks for being a legend and subscribing to Rolling Stone, we are providing you with a discount code you can send to your family and friends. Just send them the following code and they can sign up for a yearly subscription and receive their first issue free!</p>
                <pre style="padding: 10px; border: 1px dashed #ccc; width: 120px; max-width: 100%; text-align: center; font-size: 18px; margin: auto; font: monospace"><?php echo $coupon->coupon_code; ?></pre>
        <?php
                } // If coupon found
        } // If Promotion is on
        ?>

        <p>&nbsp;</p>
        <p style="font-size: 11px;">
                <strong>Automatic Renewal and Delivery Terms:</strong> After your first term, your subscription will automatically renew until you tell us to stop. You will be notified if there is any rate increase for the next term and if you do nothing, your credit/debit card will be charged that rate, or if we are unable to charge you, we will send you an invoice. If you used a coupon in a term, it will not carry over to the following term. We reserve the right to change the number of issues in a subscription term, make substitutions and/or modify the manner in which the subscription is distributed. Once you order an annual subscription you may change your address at any time through your account at <a href="https://thebrag.com/observer/magazine-subscriptions/" target="_blank">The Brag Observer</a>, however annual subscriptions may not be cancelled until the current subscription is complete. Your subscription is subject to our <a href="https://thebrag.com/media/terms-and-conditions" target="_blank">Terms of Use</a> and <a href="https://thebrag.com/media/privacy-policy/" target="_blank">Privacy Policy</a>.
        </p>
        <p>&nbsp;</p>
        <h6 align="center" style="text-align: center; font-family:Helvetica, Arial, sans-serif;font-size:20px;line-height:40px;font-weight:bold;color:#555555;">CONNECT WITH US</h6>
        <table border="0" cellspacing="0" cellpadding="0" align="center" role="presentation">
                <tbody>
                        <tr>
                                <td valign="top" align="center" style="border: 1px solid #555555;">
                                        <a href="https://www.facebook.com/rollingstoneaustralia/" target="_blank" style="text-decoration:none;"><img src="https://cdn-r2-2.thebrag.com/pages/edm/images/footer_social_facebook.png" width="40" height="40" alt="facebook" style="font-family:Arial, sans-serif; font-size:12px; line-height:15px; color:#000000; display:block;" border="0"></a>
                                </td>
                                <td width="25" style="width:25px;">&nbsp;</td>
                                <td valign="top" align="center" style="border: 1px solid #555555;">
                                        <a href="https://twitter.com/rollingstoneaus" target="_blank" style="text-decoration:none;"><img src="https://cdn-r2-2.thebrag.com/pages/edm/images/footer_social_twitter.png" width="40" height="40" alt="twitter" style="font-family:Arial, sans-serif; font-size:12px; line-height:15px; color:#000000; display:block;" border="0"></a>
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
                include(get_template_directory() . '/email-templates/footer.php');

                $body = ob_get_contents();
                ob_end_clean();

                // wp_mail($to, $subject, $body, $headers);

                unset($_SESSION);

                /*
                * Create user on thebrag.com
                */

                /* $data_api['email'] = $subscriber->email;
                $data_api['full_name'] = $subscriber->full_name;
                $data_api['key'] = $this->api_brag_user['rest_api_key'];
                $data_api['source'] = 'rs-mag';
                $user_json = $this->callAPI(
                'POST',
                $this->api_brag_user['api_url'] . 'create',
                $data_api,
                false
                );

                $user = json_decode($user_json); */

                /* if (isset($user->success) && !$user->success) {
                if ($user->data[0] == 'eu') {
                        wp_send_json_error(['error' => ['message' => $user->data]]);
                        wp_die();
                } elseif ('nu|' == substr($user->data[0], 0, 3)) {
                        wp_send_json_error(['error' => ['message' => 'nu', 't' => substr($user->data[0], 3)]]);
                        wp_die();
                }
                } */

                // $user_id = $this->get_or_create_user(['email' => $subscriber->email], false);
                $auth0_user = $this->get_or_create_user(['email' => $subscriber->email], false);
                $user_id = isset($auth0_user['user_id']) ? $auth0_user['user_id'] : false;

                // Track event in Braze
                if ($user_id !== false) {
                        require __DIR__ . '/classes/braze.class.php';
                        $braze = new Braze();
                        $braze->setMethod('POST');

                        $attributes = [
                                'external_id' => $user_id,
                                'email' => $subscriber->email,
                                'rs_mag_subscriber' => true,
                                'rs_mag_active' => true,
                                // 'rs_mag_issues_remaining' => $this->number_of_issues,
                        ];

                        if ($subscriber->is_gift == 'yes') {
                                $attributes['rs_mag_gift_subscriber'] = true;
                        }

                        $braze_purchase_properties = [
                                // 'amount_paid' => $subscriber->amount_paid / 100,
                                'is_gift' => $subscriber->is_gift == 'yes',
                                'buyer_name' => isset($subscriber) && !is_null($subscriber) ? $subscriber->full_name : 'Subscriber',
                        ];

                        if (isset($auth0_user['new_user']) && $auth0_user['new_user']) {
                                $braze_purchase_properties['email'] = $auth0_user['email'];
                                $braze_purchase_properties['password'] = $auth0_user['password'];
                        }

                        $braze->setPayload([
                                'attributes' => [$attributes],
                                'purchases' => [
                                        [
                                                'external_id' => $user_id,
                                                'name' => 'rs_mag_subscribed',
                                                'time' => date('c'),
                                                'product_id' => 'rs_au_magazine',
                                                'currency' => 'AUD',
                                                'price' => $subscriber->amount_paid / 100,
                                                'quantity' => 1,
                                                'properties' => $braze_purchase_properties
                                        ]
                                ]
                                // 'events' => [
                                //     [
                                //         'external_id' => $user_id,
                                //         'name' => 'rs_mag_subscribed',
                                //         'time' => date('c'),
                                //         'properties' => [
                                //             'amount_paid' => $subscriber->amount_paid / 100
                                //         ]
                                //     ]
                                // ]
                        ]);
                        
                        $braze->request('/users/track');
                }

                // FB Track Event Conversion
                require_once __DIR__ . '/vendor/autoload.php';

                $fb_access_token = 'EAAJOvrJXAH4BAIawH2P6lZCWt07ZBDhr2WNiplolpjVElDSZAgHZArh7u4ddPtiZC2FYMgRTOQllQt7Y0YAXnmO9ZCEW37nNjXvKV1XFu6bRYLJ6EZBZApIlmpWlFgNV8ecM5qRXZA4gN4syurBZBG0gNyFi3A1KmQpTlUt3FUfpgbuLIMK0kiuHQbLS9FTKHOsVoZD';
                $fb_pixel_id = '243859349395737';
                $fb_api = \FacebookAds\Api::init(null, null, $fb_access_token);

                $fb_user_data = (new \FacebookAds\Object\ServerSide\UserData())
                        ->setEmails([$subscriber->email])
                        ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
                        ->setClientUserAgent($_SERVER['HTTP_USER_AGENT']);

                $fb_content = (new \FacebookAds\Object\ServerSide\Content())
                        ->setProductId('RS_AU_Mag')
                        ->setQuantity(1)
                        ->setDeliveryCategory(\FacebookAds\Object\ServerSide\DeliveryCategory::HOME_DELIVERY);

                $fb_custom_data = (new \FacebookAds\Object\ServerSide\CustomData())
                        ->setContents(array($fb_content))
                        ->setCurrency('AUD')
                        ->setValue($subscriber->amount_paid / 100);

                $fb_event = (new \FacebookAds\Object\ServerSide\Event())
                        ->setEventName('Purchase')
                        ->setEventTime(time())
                        ->setEventSourceUrl('https://au.rollingstone.com/subscribe-magazine/')
                        ->setUserData($fb_user_data)
                        ->setCustomData($fb_custom_data)
                        ->setActionSource(\FacebookAds\Object\ServerSide\ActionSource::WEBSITE);

                $fb_events = array();
                array_push($fb_events, $fb_event);

                $fb_request = (new \FacebookAds\Object\ServerSide\EventRequest($fb_pixel_id))
                        ->setEvents($fb_events);
                $fb_response = $fb_request->execute();

                // wp_send_json_success($user->data->user_id);
                wp_send_json_success($user_id);
                wp_die();
        }

        public function create_coupon() {
                if (isset($_POST['tbm_coupon_nonce']) && wp_verify_nonce($_POST['tbm_coupon_nonce'], 'tbm_coupon_form_nonce')) {
                        global $wpdb;
                        
                        $errors = array();
                
                        $post_vars['coupon_code'] = isset($_POST['coupon_code']) ? sanitize_text_field($_POST['coupon_code']) : NULL;
                        $post_vars['expiry_date'] = isset($_POST['expiry_date']) ? DateTime::createFromFormat('Y-m-d', $_POST['expiry_date'])->format('Y-m-d') : NULL;
                        $post_vars['discount'] = isset($_POST['discount']) ? (int) sanitize_text_field($_POST['discount']) : 0;
                        $post_vars['type'] = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : 'limited';
                        $post_vars['status'] = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : 'active';

                        $_SESSION['tbm']['coupons']['post_vars'] = $post_vars;

                        if (is_null($post_vars['coupon_code']) || is_null($post_vars['expiry_date']) || !$post_vars['expiry_date'] || $post_vars['discount'] <= 0 || strtotime($post_vars['expiry_date']) < time()) {
                                $errors[] = 'Please check the fields.';
                        }

                        if ($wpdb->get_var("SELECT id FROM {$wpdb->prefix}coupons WHERE code = '{$post_vars['coupon_code']}' LIMIT 1")) {
                                $errors[] = 'The code has already been used.';
                        }

                        if (count($errors) > 0) {
                                $_SESSION['tbm']['coupons']['errors'] = $errors;
                                wp_redirect(admin_url('admin.php?page=' . $this->plugin_slug . '-coupons&action=create'));
                                exit;
                        } else {
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
                                
                                if ($coupon) {
                                        wp_redirect(admin_url('admin.php?page=' . $this->plugin_slug . '-coupons&id=' . $wpdb->insert_id));
                                        exit;
                                } else {
                                        $_SESSION['tbm']['coupons']['errors'][] = 'Something went wrong with creating coupon';
                                        wp_redirect(admin_url('admin.php?page=' . $this->plugin_slug . '-coupons&action=create'));
                                        exit;
                                }
                        }
                }
        }

    public function update_coupon()
    {
        if (isset($_POST['tbm_coupon_nonce']) && wp_verify_nonce($_POST['tbm_coupon_nonce'], 'tbm_coupon_form_nonce')) {
            global $wpdb;
            $errors = array();

            $id = (int) $_POST['id'];

            $post_vars['id'] = $id;
            $post_vars['coupon_code'] = isset($_POST['coupon_code']) ? sanitize_text_field($_POST['coupon_code']) : NULL;
            $post_vars['expiry_date'] = isset($_POST['expiry_date']) ? DateTime::createFromFormat('Y-m-d', $_POST['expiry_date'])->format('Y-m-d') : NULL;
            $post_vars['discount'] = isset($_POST['discount']) ? (int) sanitize_text_field($_POST['discount']) : 1;
            $post_vars['type'] = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : 'limited';
            $post_vars['status'] = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : 'active';

            $_SESSION['tbm']['coupons']['post_vars'] = $post_vars;

            if (is_null($post_vars['coupon_code']) || is_null($post_vars['expiry_date']) || !$post_vars['expiry_date'] || $post_vars['discount'] <= 0 || strtotime($post_vars['expiry_date']) < time()) :
                $errors[] = 'Please check the fields.';
            endif;

            if (count($errors) > 0) :
                $_SESSION['tbm']['coupons']['errors'] = $errors;
                wp_redirect(admin_url('admin.php?page=' . $this->plugin_slug . '-coupons&action=edit&id=' . $id));
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
                if ($coupon !== false) {
                    wp_redirect(admin_url('admin.php?page=' . $this->plugin_slug . '-coupons&id=' . $id));
                    exit;
                } else {
                    $_SESSION['tbm']['coupons']['errors'][] = 'Something went wrong with creating coupon';
                    wp_redirect(admin_url('admin.php?page=' . $this->plugin_slug . '-coupons&action=edit&id=' . $id));
                    exit;
                }
            endif;
        }
    }

    public function ajax_check_coupon()
    {

        if (!check_ajax_referer($this->plugin_name . '_nonce', 'nonce')) :
            wp_send_json_error(['error' => ['message' => 'Whoops, like something unexpected happened on our side of things. Feel free to refresh your browser and give it another shot!']]);
            wp_die();
        endif;

        global $wpdb;

        $coupon_code = $_POST['coupon_code'];

        $email = null;
        if (isset($_POST['sub_email'])) {
            $email = trim($_POST['sub_email']);
        }

        require_once __DIR__ . '/classes/coupon.class.php';

        $coupon_obj = new Coupon();
        $coupon = $coupon_obj->validateCoupon($coupon_code, $email);

        $price = $this->base_price;
        if (isset($_POST['buy_option']) && 'no-bundle' == $_POST['buy_option']) {
            $price = $this->base_price_no_bundle;
        }

        // if ( is_null( $coupon ) ) {
        //   wp_send_json_error( [ 'error' => [ 'message' => 'Looks like that coupon code is invalid. If you are sure you are entering it correctly, send us an email at <a href="mailto:subscribe@thebrag.media">subscribe@thebrag.media</a> to sort out the issue.' ] ] );
        // } else
        if (isset($coupon['error'])) {
            wp_send_json_error([
                'amount_off' => 0,
                'amount_to_pay' => $price + ($this->shipping_cost * $this->number_of_issues),
                'error' => $coupon['error'], // 'Looks like that coupon code is invalid. If you are sure you are entering it correctly, send us an email at <a href="mailto:subscribe@thebrag.media">subscribe@thebrag.media</a> to sort out the issue.'
            ]);
        } else if (isset($coupon['success'])) {
            wp_send_json_success([
                'amount_off' => number_format($coupon['amount_off'], 2),
                'amount_to_pay' => round($price + ($this->shipping_cost * $this->number_of_issues) - $coupon['amount_off'], 2),
                'message' => 'Congratulations, your coupon was successfully entered! You\'re all set to get a discount of $' . number_format($coupon['amount_off'], 2) . ' on your first year\'s subscription.'
            ]);
        } else {
            wp_send_json_success([
                'amount_off' => 0,
                'amount_to_pay' => $price + ($this->shipping_cost * $this->number_of_issues),
                'message' => ''
            ]);
            // wp_send_json_error( [ 'error' => [ 'message' => 'Looks like that coupon code is invalid. If you are sure you are entering it correctly, send us an email at <a href="mailto:subscribe@thebrag.media">subscribe@thebrag.media</a> to sort out the issue.' ] ] );
        }
        wp_die();
    }

    public function _rest_api_init()
    {
        register_rest_route($this->plugin_name . '/v1', '/get', array(
            'methods' => 'GET',
            'callback' => [$this, 'rest_get_subscriptions'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/update_shipping', array(
            'methods' => 'POST',
            'callback' => [$this, 'rest_update_shipping'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/update_billing', array(
            'methods' => 'POST',
            'callback' => [$this, 'rest_update_billing'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/cancel_autorenew', array(
            'methods' => 'POST',
            'callback' => [$this, 'rest_cancel_autorenew'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/cancel_subscription', array(
            'methods' => 'POST',
            'callback' => [$this, 'rest_cancel_subscription'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/enable_autorenew', array(
            'methods' => 'POST',
            'callback' => [$this, 'rest_enable_autorenew'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/update_customer_id', array(
            'methods' => 'POST',
            'callback' => [$this, 'rest_update_customer_id'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/updated_payment_details', array(
            'methods' => 'POST',
            'callback' => [$this, 'rest_updated_payment_details'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/st_h', array(
            'methods' => 'POST',
            'callback' => [$this, 'rest_stripe_hook'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/current_issue_img', array(
            'methods' => 'GET',
            'callback' => function () {
                return get_option('tbm_current_issue_cover');
            },
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/next_issue_img', array(
            'methods' => 'GET',
            'callback' => function () {
                return get_option('tbm_next_issue_cover');
            },
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/next_issue_img_thumb', array(
            'methods' => 'GET',
            'callback' => function () {
                return get_option('tbm_next_issue_cover_thumb');
            },
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/create_new', array(
            'methods' => 'POST',
            'callback' => [$this, 'rest_create_new'],
            'permission_callback' => '__return_true',
        ));

        register_rest_route($this->plugin_name . '/v1', '/get_subscriber_emails', array(
            'methods' => 'GET',
            'callback' => [$this, 'rest_get_subscriber_emails'],
            'permission_callback' => '__return_true',
        ));
    }

    public function rest_get_subscriber_emails($request_data)
    {
        $data = $request_data->get_params();

        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            wp_send_json_error('Unauthorised');
            die();
        }

        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];

        if (isset($this->api_users[$username]) && $password === $this->api_users[$username]) {

            require_once __DIR__ . '/classes/crm.class.php';

            $size = isset($_GET['size']) ? absint($_GET['size']) : 2000;
            if ($size > 2000) {
                $size = 2000;
            }

            $offset = isset($_GET['offset']) ? absint($_GET['offset']) : 0;
            if ($offset > 2000) {
                $offset = 2000;
            }

            // Initiate CRM class object
            $crm = new CRM();

            // Get subscribers up for renewal
            $crm_subs = $crm->getSubscriberEmails($size, $offset);

            wp_send_json_success($crm_subs);
            die();
        }
        wp_send_json_error('Unauthorised');
        die();
    }

    public function rest_create_new($request_data)
    {
        $data = $request_data->get_params();

        if ((!isset($data['key']) || !$this->isRequestValid($data['key']))) {
            return;
        }

        /*
        * Create Subscription
        */
        require_once __DIR__ . '/classes/subscription.class.php';
        $subscription = new Subscription();
        $subscription->create($data);

        $subscriber = $subscription->get($subscription->id);
        $subscriber->update(
            ['status' => 'active']
        );

        require_once __DIR__ . '/classes/crm.class.php';
        /*
        * Create record in CRM
        */
        $crm = new CRM();
        $crm_record = $crm->createSubscription($subscriber, $data['coupon_code']);
        if (isset($crm_record->error)) {
            $subscriber->update(
                [
                    'crm_error' => $crm_record->error,
                ]
            );
            wp_send_json_error($crm_record['error']);
            die();
        } else {
            $subscriber->update(
                [
                    'salesforce_id' => $crm_record->id,
                ]
            );
        }

        wp_send_json_success($subscription);
        die();
    }

    public function get_subscriptions()
    {
        if (!is_user_logged_in())
            return;

        require_once __DIR__ . '/classes/subscription.class.php';
        require_once __DIR__ . '/classes/crm.class.php';
        $subscription = new Subscription();
        $crm = new CRM();

        $current_user = wp_get_current_user();

        $filters[] = [
            'field' => 'email',
            'value' => trim($current_user->user_email),
            'op' => '=',
        ];

        $subscriptions = $subscription->get_by($filters);

        if ($subscriptions && count($subscriptions) > 0) {
            return $subscriptions;
        }

        return;
    }

    /*
    * REST: get mag subscriptions
    */
    public function rest_get_subscriptions()
    {
        if ((!isset($_GET['key']) || !$this->isRequestValid($_GET['key']))) {
            return;
        }

        require_once __DIR__ . '/classes/subscription.class.php';
        require_once __DIR__ . '/classes/crm.class.php';
        $subscription = new Subscription();
        $crm = new CRM();

        $get = $_GET;
        foreach ($get as $k => $v) {
            $get[$k] = urldecode($v);
        }

        $filters = [];

        if (isset($_GET['email']) && is_email(trim($_GET['email']))) {
            $filters[] = [
                'field' => 'email',
                'value' => trim($_GET['email']),
                'op' => '=',
            ];
        } elseif (isset($_GET['uniqid'])) {
            $filters[] = [
                'field' => 'uniqid',
                'value' => trim($_GET['uniqid']),
                'op' => '=',
            ];
        }

        $subscriptions = $subscription->get_by($filters);

        if ($subscriptions && count($subscriptions) > 0) {
            foreach ($subscriptions as $subscription) {
                $subscription->crm_record = $crm->getSubscription($subscription->salesforce_id);
            }
            return $subscriptions;
        }

        return;
    } // rest_get_subscriptions

    public function rest_update_shipping($request_data)
    {
        $data = $request_data->get_params();

        if (!isset($data['key']) || !$this->isRequestValid($data['key'])) {
            wp_send_json_error(['error' => ['message' => 'Invalid request!']]);
            wp_die();
        }

        foreach ($this->required_fields as $required_field) :
            if (!in_array($required_field, ['sub_full_name', 'buyer_full_name', 'sub_email', 'tshirt_size'])) :
                if (!isset($data[$required_field]) || '' == trim($data[$required_field])) :
                    wp_send_json_error(['error' => ['message' => 'Whoops, looks like you have forgotten to fill out all the necessary fields. Make sure you give us all the info we need!']]);
                    wp_die();
                endif;
            endif;
        endforeach;

        require_once __DIR__ . '/classes/payment.class.php';
        if (!array_search($data['shipping_country'], Payment::getCountries()) || '0' == array_search($data['shipping_country'], Payment::getCountries())) :
            wp_send_json_error(['error' => ['message' => 'Looks like you forgot to select your country. Make sure you press the drop down list and pick the correct one!']]);
            wp_die();
        endif;

        global $wpdb;
        $update_values = [
            'shipping_address_1' => $data['shipping_address_1'],
            'shipping_address_2' => $data['shipping_address_2'],
            'shipping_city' => $data['shipping_city'],
            'shipping_state' => $data['shipping_state'],
            'shipping_postcode' => $data['shipping_postcode'],
            'shipping_country' => $data['shipping_country'],
            'updated_at' => current_time('mysql'),
        ];

        if (isset($data['sub_full_name'])) {
            $update_values['sub_full_name'] = $data['sub_full_name'];
        }

        $wpdb->update(
            $wpdb->prefix . 'mag_subscriptions',
            $update_values,
            ['uniqid' => $data['uniqid']]
        );

        require_once __DIR__ . '/classes/subscription.class.php';
        $subscription = new Subscription();
        $subscriber = $subscription->get_by([
            [
                'field' => 'uniqid',
                'value' => trim($data['uniqid']),
                'op' => '=',
            ]
        ]);
        if ($subscriber && $subscriber[0]) {
            $subscriber = $subscriber[0];
        }

        require_once __DIR__ . '/classes/crm.class.php';
        $crm = new CRM();
        return $crm->updateSubscription($subscriber);
    } // rest_update_shipping

    public function rest_update_billing($request_data)
    {

        $data = $request_data->get_params();

        if (!isset($data['key']) || !$this->isRequestValid($data['key'])) {
            wp_send_json_error(['error' => ['message' => 'Invalid request!']]);
            wp_die();
        }

        $required_fields = [
            'full_name',
            'sub_address_1',
            'sub_city',
            'sub_state',
            'sub_postcode',
            'sub_country',
        ];

        foreach ($required_fields as $required_field) :
            if (!isset($data[$required_field]) || '' == trim($data[$required_field])) :
                wp_send_json_error(['error' => ['message' => 'Whoops, looks like you have forgotten to fill out all the necessary fields. Make sure you give us all the info we need!']]);
                wp_die();
            endif;
        endforeach;

        require_once __DIR__ . '/classes/payment.class.php';
        if (!array_search($data['sub_country'], Payment::getCountries()) || '0' == array_search($data['sub_country'], Payment::getCountries())) :
            wp_send_json_error(['error' => ['message' => 'Looks like you forgot to select your country. Make sure you press the drop down list and pick the correct one!']]);
            wp_die();
        endif;

        global $wpdb;
        $update_values = [
            'address_1' => $data['sub_address_1'],
            'address_2' => $data['sub_address_2'],
            'city' => $data['sub_city'],
            'state' => $data['sub_state'],
            'postcode' => $data['sub_postcode'],
            'country' => $data['sub_country'],
            'updated_at' => current_time('mysql'),
        ];

        if (isset($data['full_name'])) {
            $update_values['full_name'] = $data['full_name'];
        }

        $wpdb->update(
            $wpdb->prefix . 'mag_subscriptions',
            $update_values,
            ['uniqid' => $data['uniqid']]
        );

        require_once __DIR__ . '/classes/subscription.class.php';
        $subscription = new Subscription();
        $subscriber = $subscription->get_by([
            [
                'field' => 'uniqid',
                'value' => trim($data['uniqid']),
                'op' => '=',
            ]
        ]);
        if ($subscriber && $subscriber[0]) {
            $subscriber = $subscriber[0];

            if (!is_null($subscriber->stripe_customer_id)) {
                require_once __DIR__ . '/classes/payment.class.php';
                $payment = new Payment();
                if ($payment->updateCustomer($subscriber)) {
                    require_once __DIR__ . '/classes/crm.class.php';
                    $crm = new CRM();
                    return $crm->updateSubscription($subscriber);
                } else {
                    wp_send_json_error(['error' => ['message' => 'Whoops, like something unexpected happened on our side of things. Feel free to refresh your browser and give it another shot!']]);
                    wp_die();
                }
            } else {
                require_once __DIR__ . '/classes/crm.class.php';
                $crm = new CRM();
                return $crm->updateSubscription($subscriber);
            }
        }
    } // rest_update_billing

    public function rest_cancel_autorenew($request_data)
    {
        $data = $request_data->get_params();

        if (!isset($data['key']) || !$this->isRequestValid($data['key'])) {
            wp_send_json_error(['error' => ['message' => 'Invalid request!']]);
            wp_die();
        }

        global $wpdb;
        $update_values = [
            'status' => 'cancelled',
            'updated_at' => current_time('mysql'),
        ];

        $wpdb->update(
            $wpdb->prefix . 'mag_subscriptions',
            $update_values,
            ['uniqid' => $data['uniqid']]
        );

        require_once __DIR__ . '/classes/subscription.class.php';
        $subscription = new Subscription();
        $subscriber = $subscription->get_by([
            [
                'field' => 'uniqid',
                'value' => trim($data['uniqid']),
                'op' => '=',
            ]
        ]);

        if ($subscriber && $subscriber[0]) {
            $subscriber = $subscriber[0];
            require_once __DIR__ . '/classes/crm.class.php';
            $crm = new CRM();
            return $crm->cancelAutorenew($subscriber);
        } else {
            wp_send_json_error(['error' => ['message' => 'Whoops, like something unexpected happened on our side of things. Feel free to refresh your browser and give it another shot!']]);
            wp_die();
        }
    } // rest_cancel_autorenew()

    public function rest_cancel_subscription($request_data)
    {
        $data = $request_data->get_params();

        if (!isset($data['key']) || !$this->isRequestValid($data['key'])) {
            wp_send_json_error(['error' => ['message' => 'Invalid request!']]);
            wp_die();
        }

        global $wpdb;
        $update_values = [
            'status' => 'cancelled',
            'updated_at' => current_time('mysql'),
        ];

        $wpdb->update(
            $wpdb->prefix . 'mag_subscriptions',
            $update_values,
            ['uniqid' => $data['uniqid']]
        );

        require_once __DIR__ . '/classes/subscription.class.php';
        $subscription = new Subscription();
        $subscriber = $subscription->get_by([
            [
                'field' => 'uniqid',
                'value' => trim($data['uniqid']),
                'op' => '=',
            ]
        ]);

        if ($subscriber && $subscriber[0]) {
            $subscriber = $subscriber[0];
            require_once __DIR__ . '/classes/crm.class.php';
            $crm = new CRM();
            return $crm->cancelSubscription($subscriber);
        } else {
            wp_send_json_error(['error' => ['message' => 'Whoops, like something unexpected happened on our side of things. Feel free to refresh your browser and give it another shot!']]);
            wp_die();
        }
    } // rest_cancel_subscription()

    public function rest_enable_autorenew($request_data)
    {
        $data = $request_data->get_params();

        if (!isset($data['key']) || !$this->isRequestValid($data['key'])) {
            wp_send_json_error(['error' => ['message' => 'Invalid request!']]);
            wp_die();
        }

        global $wpdb;
        $update_values = [
            'status' => 'active',
            'updated_at' => current_time('mysql'),
        ];

        $wpdb->update(
            $wpdb->prefix . 'mag_subscriptions',
            $update_values,
            ['uniqid' => $data['uniqid']]
        );

        require_once __DIR__ . '/classes/subscription.class.php';
        $subscription = new Subscription();
        $subscriber = $subscription->get_by([
            [
                'field' => 'uniqid',
                'value' => trim($data['uniqid']),
                'op' => '=',
            ]
        ]);

        if ($subscriber && $subscriber[0]) {
            $subscriber = $subscriber[0];
            require_once __DIR__ . '/classes/crm.class.php';
            $crm = new CRM();
            return $crm->enableAutorenew($subscriber);
        } else {
            wp_send_json_error(['error' => ['message' => 'Whoops, like something unexpected happened on our side of things. Feel free to refresh your browser and give it another shot!']]);
            wp_die();
        }
    } // rest_enable_autorenew

    public function rest_updated_payment_details($request_data)
    {
        $data = $request_data->get_params();

        if (!isset($data['key']) || !$this->isRequestValid($data['key'])) {
            wp_send_json_error(['error' => ['message' => 'Invalid request!']]);
            wp_die();
        }

        require_once __DIR__ . '/classes/subscription.class.php';
        $subscription = new Subscription();
        $subscriber = $subscription->get_by([
            [
                'field' => 'uniqid',
                'value' => trim($data['uniqid']),
                'op' => '=',
            ]
        ]);

        if ($subscriber && $subscriber[0]) {
            $subscriber = $subscriber[0];
            require_once __DIR__ . '/classes/crm.class.php';
            $crm = new CRM();
            return $crm->updatePaymentDetailsDate($subscriber);
        } else {
            wp_send_json_error(['error' => ['message' => 'Whoops, like something unexpected happened on our side of things. Feel free to refresh your browser and give it another shot!']]);
            wp_die();
        }
    } // rest_updated_payment_details()

    public function rest_update_customer_id($request_data)
    {
        $data = $request_data->get_params();

        if (!isset($data['key']) || !$this->isRequestValid($data['key'])) {
            wp_send_json_error(['error' => ['message' => 'Invalid request!']]);
            wp_die();
        }

        if (!isset($data['stripe_customer_id']) || '' == trim($data['stripe_customer_id'])) :
            wp_send_json_error(['error' => ['message' => 'Whoops, looks like you have forgotten to fill out all the necessary fields. Make sure you give us all the info we need!']]);
            wp_die();
        endif;

        global $wpdb;
        $update_values = [
            'stripe_customer_id' => $data['stripe_customer_id'],
            'updated_at' => current_time('mysql'),
        ];

        $wpdb->update(
            $wpdb->prefix . 'mag_subscriptions',
            $update_values,
            ['uniqid' => $data['uniqid']]
        );
    } // rest_update_customer_id

    private function isRequestValid($key)
    {
        return isset($key) && !is_null($key) && in_array($key, $this->rest_api_keys);
    }

    private function callAPI($method, $url, $data = '', $content_type = '')
    {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($content_type !== false) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            ));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // if (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
        if ($this->is_sandbox) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

            curl_setopt($curl, CURLOPT_USERPWD, "tbmstaging:tbmStaging");
        }
        // EXECUTE:
        $result = curl_exec($curl);

        if (!$result)
            return;
        curl_close($curl);
        return $result;
    }

    public function ajax_subscribe_observer()
    {

        if (defined('DOING_AJAX') && DOING_AJAX) :

            parse_str($_POST['formData'], $formData);

            if (!is_user_logged_in())
                return;

            $current_user = wp_get_current_user();

            $brag_api_url_base = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) ? 'https://the-brag.com/' : 'https://thebrag.com/';

            $brag_api_url = $brag_api_url_base . 'wp-json/brag_observer/v1/sub_unsub/';

            $response = wp_remote_post(
                $brag_api_url,
                [
                    'method'      => 'POST',
                    'body'        => array(
                        'email' => $current_user->user_email,
                        'list' => $formData['list'],
                        'status' => $formData['status'],
                    ),
                    'sslverify' => !in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']),
                ]
            );
            $responseBody = wp_remote_retrieve_body($response);
            $resonseJson = json_decode($responseBody);
            if ($resonseJson->data->success) {
                return wp_send_json_success();
                wp_die();
            }
            wp_send_json_error(['error' => ['message' => $resonseJson->data->error->message]]);
            wp_die();
        endif;
    } // ajax_subscribe_observer() }}

    /**
   * Activate plugin
   * @return void
   */
    public function activate()
    {
        if (!wp_next_scheduled('cron_hook_tbm_mag_sub_renewals')) {
            wp_schedule_event(time(), 'hourly', 'cron_hook_tbm_mag_sub_renewals');
        }
        if (!wp_next_scheduled('cron_hook_tbm_mag_send_overdue_invoices')) {
            wp_schedule_event(strtotime('11:10:00'), 'hourly', 'cron_hook_tbm_mag_send_overdue_invoices');
        }
        if (!wp_next_scheduled('cron_hook_tbm_mag_send_comps_renewals')) {
            wp_schedule_event(strtotime('00:15:00'), 'weekly', 'cron_hook_tbm_mag_send_comps_renewals');
        }
    } // activate()

    /**
   * Deactivate plugin
   */
    public function deactivate()
    {
        $crons = _get_cron_array();
        if (empty($crons)) {
            return;
        }
        $hooks = ['cron_hook_tbm_mag_sub_renewals', 'cron_hook_tbm_mag_send_overdue_invoices', 'cron_hook_tbm_mag_send_comps_renewals'];
        foreach ($hooks as $hook) {
            foreach ($crons as $timestamp => $cron) {
                if (!empty($cron[$hook])) {
                    unset($crons[$timestamp][$hook]);
                }
                if (empty($crons[$timestamp])) {
                    unset($crons[$timestamp]);
                }
            }
        }
        _set_cron_array($crons);
    } // deactivate()

    /**
   * Hook cron_schedules
   *
   * @return void
   * @param void
   */
    public function _cron_schedules($schedules)
    {
        $schedules['every30minutes'] = array(
            'interval' => 30 * 60,
            'display'  => esc_html__('Every 30 Minutes'),
        );
        return $schedules;
    }

    /**
   * Run cron - sub_renewals
   */
    public function tbm_mag_sub_renewals()
    {
        update_option('TBMMagSubRenewals_CronRun', current_time('mysql'), false);

        require_once __DIR__ . '/classes/renewals.class.php';
        $renewals = new Renewals();
        $renewals->process();
    } // tbm_mag_sub_renewals()

    /**
   * Run cron - send_overdue_invoices
   */
    public function tbm_mag_send_overdue_invoices()
    {
        update_option('TBMMagSendOverdueInvoices_CronRun', current_time('mysql'), false);

        require_once __DIR__ . '/classes/renewals.class.php';
        $renewals = new Renewals();
        $renewals->send_overdue_invoices();
    } // tbm_mag_send_overdue_invoices()

    /**
   * Run cron - send_comps_renewals
   */
    public function tbm_mag_send_comps_renewals()
    {
        update_option('TBMMagSendCompRenewals_CronRun', current_time('mysql'), false);

        require_once __DIR__ . '/classes/renewals.class.php';
        $renewals = new Renewals();
        $renewals->send_comps_renewals();
    } // tbm_mag_send_comps_renewals()

    /*
    * Process Renewals
    */
    public function show_process_renewals()
    {
        date_default_timezone_set('Australia/NSW');
        ?>
        <table class="table-sm">
            <tr>
                <th>Current Date/Time</th>
                <td><?php echo date('d-M-Y h:i:sa'); ?></td>
            </tr>
            <tr>
                <th>Scheduled automatic run for mag_sub_renewals</th>
                <td><?php $next_run_timestamp = wp_next_scheduled('cron_hook_tbm_mag_sub_renewals');
                        echo date('d-M-Y h:i:sa', $next_run_timestamp); ?></td>
            </tr>
            <tr>
                <th>Scheduled automatic run for mag_send_overdue_invoices</th>
                <td><?php $next_run_timestamp = wp_next_scheduled('cron_hook_tbm_mag_send_overdue_invoices');
                        echo date('d-M-Y h:i:sa', $next_run_timestamp); ?></td>
            </tr>
            <tr>
                <th>Scheduled automatic run for send_comps_renewals</th>
                <td><?php $next_run_timestamp = wp_next_scheduled('cron_hook_send_comps_renewals');
                        echo date('d-M-Y h:i:sa', $next_run_timestamp); ?></td>
            </tr>
        </table>
<?php
        if (isset($_GET['manual'])) {
            require_once __DIR__ . '/classes/renewals.class.php';
            $renewals = new Renewals();
            $renewals->index();
        }
    }

    public function ajax_process_renewals()
    {
        require_once __DIR__ . '/classes/renewals.class.php';
        $renewals = new Renewals();
        return $renewals->ajax_process_renewals();
    }

    public function ajax_send_comps_renewals()
    {
        require_once __DIR__ . '/classes/renewals.class.php';
        $renewals = new Renewals();
        return $renewals->send_comps_renewals();
    }

    public function ajax_send_overdue_invoices()
    {
        require_once __DIR__ . '/classes/renewals.class.php';
        $renewals = new Renewals();
        return $renewals->send_overdue_invoices();
    }

    public function rest_stripe_hook()
    {
        require_once __DIR__ . '/classes/payment.class.php';
        $payment = new Payment();
        $invoice = $payment->webhook();
        if ($invoice) {
            global $wpdb;
            $stripe_invoice_id = $invoice->id;

            $pending_invoice = $wpdb->get_row(
                "SELECT * FROM {$wpdb->prefix}mag_renewals WHERE stripe_invoice_id = '{$stripe_invoice_id}' LIMIT 1"
            );

            if (!$pending_invoice) {
                return;
            }

            $update_values = [
                'updated_at' => current_time('mysql'),
                'payment_status' => 'paid',
                'payment_error' => NULL,
            ];

            // Update renewal record
            $wpdb->update(
                $wpdb->prefix . 'mag_renewals',
                $update_values,
                [
                    'stripe_invoice_id' => $stripe_invoice_id
                ]
            );

            $subscription = $wpdb->get_row(
                "SELECT * FROM {$wpdb->prefix}mag_subscriptions WHERE stripe_customer_id = '{$invoice->customer}' LIMIT 1"
            );
            if ($subscription) {
                require_once __DIR__ . '/classes/crm.class.php';
                $crm = new CRM();
                $crm_sub = (array)$crm->getSubscription($subscription->salesforce_id);

                if ($crm_sub) {
                    $crm_response = $crm->updatePaymentAttempts($crm_sub);
                    if (isset($crm_response['error'])) {
                        $wpdb->update(
                            $wpdb->prefix . 'mag_renewals',
                            ['crm_error'  => $crm_response['error'], 'updated_at' => current_time('mysql'),],
                            ['stripe_invoice_id' => $stripe_invoice_id]
                        );

                        wp_mail('dev@thebrag.media', 'Stripe Webhook: CRM updatePaymentAttempts', $crm_sub['Email__c'] . ' => ' . $crm_response['error']);
                        wp_die();
                    }

                    $crm_response = $crm->resetRemainingIssues($crm_sub);
                    if (isset($crm_response['error'])) {

                        $wpdb->update(
                            $wpdb->prefix . 'mag_renewals',
                            ['crm_error'  =>  $crm_response['error'], 'updated_at' => current_time('mysql'),],
                            ['stripe_invoice_id' => $stripe_invoice_id]
                        );

                        wp_mail('dev@thebrag.media', 'Stripe Webhook: CRM resetRemainingIssues', $crm_sub['Email__c'] . ' => ' . $crm_response['error']);
                        wp_die();
                    }
                } else {
                    wp_mail('dev@thebrag.media', 'Stripe Webhook: CRM Record not found', print_r($invoice, true));
                }
            }
        }
    }

    public function cron_process_renewals()
    {
        date_default_timezone_set('Australia/NSW');
        $next_run_timestamp = wp_next_scheduled('cron_hook_tbm_mag_sub_renewals', array(NULL, NULL));
        echo '<br>Scheduled automatic run is at ' . date('d-M-Y h:i:sa', $next_run_timestamp);
        echo '<br>Current Date/Time: ' . date('d-M-Y h:i:sa');

        $this->tbm_mag_sub_renewals();
    }

    /*
    * Get Countries
    */
    public static function getCountries()
    {
        require_once __DIR__ . '/classes/helper.class.php';
        return Helper::getCountries();
    }

    public static function getBrazeAPICredentials()
    {
        return self::$api_braze;
    }

    public function ajax_get_or_create_user()
    {
        return $this->get_or_create_user($_POST);
    }
    public function get_or_create_user($data = null, $ajax = true)
    {
        $data = !is_null($data) ? stripslashes_deep($data) : [];

        if (isset($data['email']) && is_email($data['email'])) {
            require __DIR__ . '/vendor/autoload.php';

            $dotenv = Dotenv\Dotenv::createImmutable(ABSPATH);
            $dotenv->load();

            try {
                $auth0 = new Auth0\SDK\Auth0([
                    'domain' => $_ENV['AUTH0_DOMAIN'],
                    'clientId' => $_ENV['AUTH0_CLIENT_ID'],
                    'clientSecret' => $_ENV['AUTH0_CLIENT_SECRET'],
                ]);

                $management = $auth0->management();

                $response = $management->UsersByEmail()->get($data['email']);

                if ($response->getStatusCode() === 200) {
                    // Decode the JSON response into a PHP array:
                    $response = json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);

                    if (!empty($response)) {
                        // User found
                        if ($ajax) {
                            wp_send_json_success(
                                $response[0]['user_id']
                            );
                            die();
                        } else {
                            return [
                                'new_user' => false,
                                'user_id' => $response[0]['user_id']
                            ];
                        }
                    } else {
                        // Create user
                        try {
                            $user_pass = wp_generate_password(12, true, true);
                            $response = $management->users()->create(
                                'brag-observer',
                                [
                                    'email' => trim($data['email']),
                                    'password' => $user_pass,
                                    'email_verified' => true,
                                ]
                            );
                            if ($response->getStatusCode() === 201) {
                                $auth0_user = json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);
                                if ($ajax) {
                                    wp_send_json_success(
                                        $auth0_user['user_id']
                                    );
                                    die();
                                } else {
                                    return [
                                        'new_user' => true,
                                        'user_id' => $auth0_user['user_id'],
                                        'email' => trim($data['email']),
                                        'password' => $user_pass,
                                    ];
                                }
                            } else {
                                if ($ajax) {
                                    wp_send_json_error($response->getStatusCode());
                                    die();
                                } else {
                                    return false;
                                }
                            }
                        } catch (\Exception $e) {
                            error_log('Line: ' . __LINE__ . ' ' .  $e->getMessage());
                        }
                    }
                } else {
                    if ($ajax) {
                        wp_send_json_error($response->getStatusCode());
                        die();
                    } else {
                        return false;
                    }
                }
            } catch (\Exception $e) {
                error_log('Line: ' . __LINE__ . ' ' .  $e->getMessage());
            }
        }

        wp_send_json_error('Error');
        die();
    }
}

new TBMMagSub();
