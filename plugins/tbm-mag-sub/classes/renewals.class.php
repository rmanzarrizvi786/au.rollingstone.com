<?php
class Renewals
{
    public function __construct()
    {
    }

    public function index()
    {
        include_once(plugin_dir_path(__FILE__) . '/../partials/renewals/index.php');
    }

    public function ajax_process_renewals()
    {
        return $this->process();
    }

    public function process()
    {
        require_once __DIR__ . '/crm.class.php';
        require_once __DIR__ . '/helper.class.php';
        require_once __DIR__ . '/payment.class.php';
        require_once __DIR__ . '/email.class.php';

        $email = new Email();

        // Get config
        $config = include __DIR__ . '/config.php';

        // Initiate CRM class object
        $crm = new CRM();

        // Get subscribers up for renewal
        $crm_subs = $crm->getSubscriptionsForRenewal(10);

        // Set base price
        $base_price = $config['magazine']['base_price'];

        // If there are any subscriptions up for renewal
        if ($crm_subs && is_array($crm_subs) && !empty($crm_subs)) {

            global $wpdb;

            foreach ($crm_subs as $crm_sub) {

                $message = '';

                $query_sub = "SELECT
                    *
                    FROM {$wpdb->prefix}mag_subscriptions
                    WHERE `salesforce_id` = '{$crm_sub['Id']}' LIMIT 1";
                $sub = $wpdb->get_row($query_sub);

                if (!$sub) {
                    wp_send_json_error([$crm_sub['Email__c'] . ' => Record not found for Salesforce ID; ' . $crm_sub['Id'] . '.']);
                    wp_mail('toby.smith@thebrag.media', 'RS Mag Renewal Error', 'Record not found for Salesforce ID; ' . $crm_sub['Id']);
                    wp_die();
                }

                // Get Observer User
                $data_api_observer['email'] = $crm_sub['Email__c'];
                $data_api_observer['key'] = $config['api_brag_user']['rest_api_key'];
                $data_api_observer['source'] = 'rs-mag';
                $sub->observer_user_json = Helper::callAPI(
                    'GET',
                    $config['api_brag_user']['api_url'] . 'get',
                    $data_api_observer,
                    false
                );
                if ($sub->observer_user_json) {
                    $sub->observer_user_decoded = json_decode($sub->observer_user_json);
                    $sub->observer_user = $sub->observer_user_decoded->data;
                }

                $payment = new Payment();

                if($sub->id < 2383) {
                    $base_price = $config['magazine']['base_price_legacy'];
                }

                // Cancel subscription in Stripe if stripe_subscription_id is NOT NULL
                if (!is_null($sub->stripe_subscription_id)) {
                    $stripe_sub = $payment->cancelSubscription($sub->stripe_subscription_id);
                    if ($stripe_sub['error']) {
                        wp_send_json_error([$sub->email . ' => ' . $stripe_sub['stripe_error']]);
                        wp_mail('toby.smith@thebrag.media', 'RS Mag Renewal Stripe Error', $sub->email . ' => ' . $stripe_sub['stripe_error']);
                        wp_die();
                    } else {
                        $wpdb->update(
                            $wpdb->prefix . 'mag_subscriptions',
                            [
                                'stripe_subscription_id' => NULL,
                            ],
                            [
                                'id' => $sub->id,
                            ]
                        );
                    }
                }

                // Check if there is any unpaid invoice
                $unpaid_invoice = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}mag_renewals WHERE `subscription_id` = '{$sub->id}' AND payment_status = 'unpaid' ORDER BY id DESC LIMIT 1");

                $error = '';
                $success = false;

                if ($unpaid_invoice) {
                    $invoice_id = $unpaid_invoice->id;
                    $update_values = [
                        // 'no_of_attempts' => ++$unpaid_invoice->no_of_attempts,
                        'updated_at' => current_time('mysql'),
                        // 'last_payment_attempt' => current_time('mysql'),
                    ];

                    // Try paying the invoice
                    $invoice = $payment->payInvoice($unpaid_invoice->stripe_invoice_id);

                    if ($invoice['error']) { // If invoice payment returns error
                        $update_values['payment_status'] = 'unpaid';
                        $update_values['payment_error'] = $invoice['stripe_error'];
                        $error = $sub->email . ' => ' . $invoice['stripe_error'];
                        $success = false;
                    } else { // Payment was successful i.e. there was no error
                        $update_values['payment_status'] = 'paid';
                        $update_values['payment_error'] = NULL;
                        $message = $sub->email . ' => Payment successful';
                        $success = true;
                    }

                    // Update renewal record
                    $wpdb->update(
                        $wpdb->prefix . 'mag_renewals',
                        $update_values,
                        [
                            'id' => $unpaid_invoice->id
                        ]
                    );
                } else { // No unpaid invoice, create new one and finalise payment
                    // Create Stripe Invoice and Process payment
                    $invoice = $payment->createInvoice(
                        (int) ($base_price * 100),
                        (int) ($config['magazine']['shipping_cost'] * 100),
                        $config['magazine']['number_of_issues'],
                        '',
                        0,
                        NULL,
                        $sub->email,
                        $sub->sub_full_name,
                        [],
                        [],
                        $sub->stripe_customer_id
                    );

                    // Insert in to Renewals database
                    $insert_values = [
                        'subscription_id' => $sub->id,
                        'amount' => (int) ($base_price * 100) +  (int) ($config['magazine']['shipping_cost'] * 100),
                        'stripe_invoice_id' => $invoice['invoice']->id,
                        'payment_status' => 'unpaid',
                        'payment_error' => isset($invoice['stripe_error']) ? $invoice['stripe_error'] : NULL,
                        'last_payment_attempt' => current_time('mysql'),
                    ];
                    if (isset($invoice['error'])) {
                        $insert_values['amount'] = (int) ($base_price * 100) +  (int) ($config['magazine']['shipping_cost'] * 100);
                        $insert_values['payment_status'] = 'unpaid';
                        $error = $sub->email . ' => ' . $invoice['stripe_error'];
                        $success = false;
                    } else {
                        $insert_values['amount'] = $invoice['invoice']->amount_paid;
                        $insert_values['payment_status'] = 'paid';
                        $message = $sub->email . ' => Payment successful';
                        $success = true;
                    }
                    $wpdb->insert(
                        $wpdb->prefix . 'mag_renewals',
                        $insert_values
                    );
                    $invoice_id = $wpdb->insert_id;
                }

                // Update CRM - Update Payment Attempts
                require_once __DIR__ . '/crm.class.php';
                $crm = new CRM();
                $crm_response = $crm->updatePaymentAttempts($crm_sub);

                if (isset($crm_response['error'])) {
                    $wpdb->update(
                        $wpdb->prefix . 'mag_renewals',
                        ['crm_error'  => $crm_response['error'], 'updated_at' => current_time('mysql'),],
                        ['id' => $invoice_id]
                    );

                    wp_send_json_error([$crm_sub['Email__c'] . ' => ' . $crm_response['error'] . ' | ' . $invoice_id]);
                    wp_mail('toby.smith@thebrag.media', 'RS Mag Renewal CRM Error', $crm_sub['Email__c'] . ' => ' . $crm_response['error'] . ' | ' . $invoice_id);
                    wp_die();
                } else {
                    $message .= ' + ' . $crm_response;
                }

                /**
                 * Set up for Braze Event
                 */
                $braze_event_properties = [
                    'buyer_name' => isset($sub) && !is_null($sub) && isset($crm_sub['Buyer__c']) ? $crm_sub['Buyer__c'] : 'Subscriber',
                ];
                require __DIR__ . '/braze.class.php';
                $braze = new Braze();
                $braze->setMethod('POST');

                if (!$success) {
                    // $email->send('renewal-failed', $crm_sub, $sub);

                    /**
                     * Trigger Braze Event
                     */
                    $braze_event_properties['result'] = 'failed';

                    $braze->triggerEventByEmail(
                        $sub->email,
                        'rs_mag_renewed',
                        $braze_event_properties
                    );

                    wp_send_json_error([$error]);
                    wp_mail('toby.smith@thebrag.media', 'RS Mag Renewal Failed', $error);
                    wp_die();
                } else {
                    // $email->send('renewal-success', $crm_sub, $sub);

                    /**
                     * Trigger Braze Event
                     */
                    $braze_event_properties['result'] = 'success';

                    require_once __DIR__ . '/coupon.class.php';
                    $coupon_obj = new Coupon();
                    $coupon = $coupon_obj->getCouponForSubscription($sub);

                    if ($coupon && $coupon->coupon_code) {
                        $braze_event_properties['coupon_code'] = $coupon->coupon_code;
                    }

                    $braze->triggerEventByEmail(
                        $sub->email,
                        'rs_mag_renewed',
                        $braze_event_properties
                    );

                    // Update CRM - Reset Remaining Issues and Number of Payment Attempts
                    $crm_response = $crm->resetRemainingIssues($crm_sub);

                    if (isset($crm_response['error'])) {

                        $wpdb->update(
                            $wpdb->prefix . 'mag_renewals',
                            ['crm_error'  =>  $crm_response['error'], 'updated_at' => current_time('mysql'),],
                            ['id' => $invoice_id]
                        );

                        wp_send_json_error([$crm_sub['Email__c'] . ' => ' . $crm_response['error']]);
                        wp_mail('toby.smith@thebrag.media', 'RS Mag CRM Update Error', $crm_sub['Email__c'] . ' => ' . $crm_response['error']);
                        wp_die();
                    } else {
                        $message .= ' + ' . $crm_response;
                    }
                }
            }

            wp_send_json_success([$message]);
            wp_die();
        } // If $crm_subs
        else {
            wp_send_json_success(['Done']);
            wp_die();
        }
    } // process()

    public function send_comps_renewals()
    {
        require_once __DIR__ . '/crm.class.php';
        require_once __DIR__ . '/email.class.php';

        $email = new Email();

        // Initiate CRM class object
        $crm = new CRM();

        // Get Comp subscribers up for renewal
        $crm_comp_subs = $crm->getSubscriptionsForRenewalComps(100);

        // If there are any subscriptions up for renewal
        if ($crm_comp_subs && is_array($crm_comp_subs) && !empty($crm_comp_subs)) {
            if ($email->sendComps($crm_comp_subs)) {
                wp_send_json_success('Sent email');
            } else {
                wp_send_json_error('Something went wrong with sending email, please try again or contact the admin.');
            }
            wp_die();
        } else {
            wp_send_json_success('The list is empty.');
        }
    } // send_comps_renewals()

    public function send_overdue_invoices()
    {
        require_once __DIR__ . '/crm.class.php';
        require_once __DIR__ . '/helper.class.php';
        require_once __DIR__ . '/payment.class.php';
        require_once __DIR__ . '/email.class.php';

        $email = new Email();

        // Get config
        $config = include __DIR__ . '/config.php';

        // Initiate CRM class object
        $crm = new CRM();

        // Get subscribers up for renewal
        $crm_subs = $crm->getSubscriptionsForRenewalOverdue(1);

        // If there are any subscriptions up for renewal
        if ($crm_subs && is_array($crm_subs) && !empty($crm_subs)) {

            global $wpdb;

            foreach ($crm_subs as $crm_sub) {

                $message = '';

                $query_sub = "SELECT
                    *
                    FROM {$wpdb->prefix}mag_subscriptions
                    WHERE `salesforce_id` = '{$crm_sub['Id']}' LIMIT 1";
                $sub = $wpdb->get_row($query_sub);

                if (!$sub) {
                    wp_send_json_error([$crm_sub['Email__c'] . ' => Record not found for Salesforce ID; ' . $crm_sub['Id'] . '.']);
                    wp_die();
                }

                $unpaid_invoice = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}mag_renewals WHERE `subscription_id` = '{$sub->id}' AND payment_status = 'unpaid' LIMIT 1");
                if ($unpaid_invoice) {
                    // Update renewal record
                    $wpdb->update(
                        $wpdb->prefix . 'mag_renewals',
                        [
                            'payment_status' => 'failed',
                            'updated_at' => current_time('mysql'),
                        ],
                        [
                            'id' => $unpaid_invoice->id
                        ]
                    );
                }

                $payment = new Payment();
                $invoice = $payment->createInvoice(
                    (int) ($base_price * 100),
                    (int) ($config['magazine']['shipping_cost'] * 100),
                    $config['magazine']['number_of_issues'],
                    '',
                    0,
                    NULL,
                    $sub->email,
                    $sub->sub_full_name,
                    [],
                    [],
                    $sub->stripe_customer_id,
                    'send_invoice',
                    'We tried to process your auto-renewal but your payment method failed. To renew your magazine subscription, please pay this invoice.'
                );
                if (isset($invoice['error'])) {
                    wp_send_json_error([$sub->email . ' => ' . $invoice['stripe_error']]);
                    wp_die();
                }

                $message = $sub->email . ' => Final Invoice has been sent';

                // Insert in to Renewals database
                $insert_values = [
                    'subscription_id' => $sub->id,
                    'amount' => (int) ($base_price * 100) +  (int) ($config['magazine']['shipping_cost'] * 100),
                    'stripe_invoice_id' => $invoice['invoice']->id,
                    'payment_status' => 'unpaid',
                    'payment_error' => isset($invoice['stripe_error']) ? $invoice['stripe_error'] : NULL,
                    'last_payment_attempt' => NULL,
                ];
                $wpdb->insert(
                    $wpdb->prefix . 'mag_renewals',
                    $insert_values
                );

                // Update Salesforce
                require_once __DIR__ . '/crm.class.php';
                $crm = new CRM();
                $crm_response = $crm->updatePaymentAttempts($crm_sub);
                $message .= ' + ' . $crm_response;

                wp_send_json_success([$message]);
                wp_die();
            }
        }
    } // send_overdue_invoices()
}
