<?php
class CRM {
    protected $is_sandbox;
    protected $listview_active;
    protected $listview_renewals;
    protected $listview_renewals_comps;
    protected $listview_renewals_overdue;
    protected $listview_upcoming_renewals;

    protected $listview_active_emails_only;

    protected $salesforce;

    public function __construct() {
        $config = include __DIR__ . '/config.php';

        $this->is_sandbox = $config['is_sandbox'];
        $this->listview_active = $this->is_sandbox ? '00B2u000001DnYLEA0' : '00B2u000001DnYLEA0';
        $this->listview_renewals = $this->is_sandbox ? '00B9D00000222dpUAA' : '00B2u000001EE8UEAW';
        $this->listview_renewals_comps = $this->is_sandbox ? '00B9D00000228IXUAY' : '00B2u000001EEFLEA4';
        $this->listview_renewals_overdue = $this->is_sandbox ? '00B9D0000022H2KUAU' : '00B2u000001EEiDEAW';
        $this->listview_upcoming_renewals = $this->is_sandbox ? '00B9t000000dngnEAA' : '00B9o000000WMGHEA4';

        $this->listview_active_emails_only = $this->is_sandbox ? '00B9t000000dngnEAA' : '00B9o000000WMGHEA4';

        $this->salesforce = $config['salesforce'];
    }

        /**
   * Create Subscription
   *
   * @param $subscriber
   * @param $TBM_Coupon_code__c
   * @return void
   */
    public function createSubscription($subscriber, $TBM_Coupon_code__c) {
        $access_token = $this->salesforce_login();
        $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/';
        $content = [
            'Name' => $subscriber->sub_full_name,
            'Buyer__c' => $subscriber->full_name,
            'Email__c' => $subscriber->email,
            'Email_Reciever__c' => $subscriber->email_reciever,

            'Address_1__c' => $subscriber->address_1,
            'Address_2__c' => $subscriber->address_2,
            'City__c' => $subscriber->city,
            'Postcode__c' => $subscriber->postcode,
            'State__c' => $subscriber->state,
            'Country__c' => $subscriber->country,
            'Is_Providoor__c' => $subscriber->is_providoor == 'yes' ? true : false,

            'Shipping_Address_1__c' => $subscriber->shipping_address_1,
            'Shipping_Address_2__c' => $subscriber->shipping_address_2,
            'Shipping_City__c' => $subscriber->shipping_city,
            'Shipping_Postcode__c' => $subscriber->shipping_postcode,
            'Shipping_State__c' => $subscriber->shipping_state,
            'Shipping_Country__c' => $subscriber->shipping_country,

            'T_shirt_size__c' =>  $subscriber->tshirt_size,

            'Is_Gift__c' => $subscriber->is_gift == 'yes' ? true : false,
            'Coupon_code__c' => $subscriber->coupon_code,
            'TBM_Coupon_code__c' => $TBM_Coupon_code__c,

            'Created_At__c' => date('Y-m-d\Th:i:s'),

            'subscriptionID__c' => $subscriber->stripe_subscription_id,
            // 'orderID__c' => $subscriber->orderID,
            // 'facilitatorAccessToken__c' => $subscriber->facilitatorAccessToken,

            'IP_Address__c' => $subscriber->ip_address,

            'Amount_Paid__c' => round($subscriber->amount_paid / 100, 2),

            'Promotion_Response__c' => $subscriber->promotion_response,

            'Buy_Option__c' => $subscriber->buy_option
        ];

        $content['Subscription_Package__c'] = $subscriber->buy_option == 'printonly' ? 'Print' : ($subscriber->buy_option == 'digitalonly' ? 'Digital' : 'Combo');

        if ($subscriber->buy_option == 'printonly') {
            $content['Digital_Issues_Remaining__c'] = 0;
            $content['Remaining_Issues__c'] = 4;
        } else if ($subscriber->buy_option == 'digitalonly') {
            $content['Digital_Issues_Remaining__c'] = 3; // 4 on sub but assume first issue has been auto sent on purchase
            $content['Remaining_Issues__c'] = 0;
        } else if ($subscriber->buy_option == 'printdigital') {
            $content['Digital_Issues_Remaining__c'] = 3; // 4 on sub but assume first issue has been auto sent on purchase
            $content['Remaining_Issues__c'] = 4;
        } else {
            $content['Digital_Issues_Remaining__c'] = 0;
            $content['Remaining_Issues__c'] = 0;
        }

        $content = json_encode($content);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($status != 201) {
            error_log('--Salesforce Error: ' . $json_response);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Create Sub', $json_response);
            return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        } else {
            $response = json_decode($json_response);
            return $response;
        }
    }

        /**
   * Get Subscription
   *
   * @param $id
   * @return void
   */
        public function getSubscription($id) {
                $access_token = $this->salesforce_login();
                $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/' . $id;
                // $content = json_encode(
                //   [
                //     'fields' => 'Remaining_Issues__c',
                //   ]
                // );
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                // curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

                $json_response = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                // return ( $status ); exit;

                curl_close($curl);

                if ($status != 200) {
                        error_log('--Salesforce Error: ' . $json_response);
                        wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Get Sub', "Method: " . __METHOD__ . "\n Line: " . __LINE__ . "\n Id: {$id}\n" . $json_response);
                        return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
                        wp_die();
                } else {
                        $response = json_decode($json_response);
                        return $response;
                }
        } // getSubscription

        /**
   * Update Subscription
   *
   * @param $subscriber
   * @return void
   */
        public function updateSubscription($subscriber) {
            $access_token = $this->salesforce_login();
            $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/' . $subscriber->salesforce_id;

            $content = [
                    'Name' => $subscriber->sub_full_name,
                    'Buyer__c' => $subscriber->full_name,
                    'Email_Reciever__c' => $subscriber->email_reciever,
                    // 'Email__c' => $subscriber->email,

                    'Address_1__c' => $subscriber->address_1,
                    'Address_2__c' => $subscriber->address_2,
                    'City__c' => $subscriber->city,
                    'Postcode__c' => $subscriber->postcode,
                    'State__c' => $subscriber->state,
                    'Country__c' => $subscriber->country,

                    'Shipping_Address_1__c' => $subscriber->shipping_address_1,
                    'Shipping_Address_2__c' => $subscriber->shipping_address_2,
                    'Shipping_City__c' => $subscriber->shipping_city,
                    'Shipping_Postcode__c' => $subscriber->shipping_postcode,
                    'Shipping_State__c' => $subscriber->shipping_state,
                    'Shipping_Country__c' => $subscriber->shipping_country,

                    'Buy_Option__c' => $subscriber->buy_option,
            ];

            $content['Subscription_Package__c'] = $subscriber->buy_option == 'printonly' ? 'Print' : ($subscriber->buy_option == 'digitalonly' ? 'Digital' : 'Combo');

            $content = json_encode($content);

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

            $json_response = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            if ($status != 204) {
                    error_log('--Salesforce Error: ' . $json_response . ' | Status: ' . $status);
                    wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Update Sub', $json_response);
                    wp_send_json_error(['error' => ['message' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.']]);
                    wp_die();
            }

            wp_send_json_success();
            wp_die();
        } //updateSubscription

        /**
        * Reset Remaining Issues and Number of Payment Attempts in CRM
        */
        public function resetRemainingIssues($crm_sub) {
            $config = include __DIR__ . '/config.php';

            $access_token = $this->salesforce_login();
            $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/' . $crm_sub['Id'];

            $content = json_encode(
                    [
                        'Remaining_Issues__c' => $config['magazine']['number_of_issues'],
                        'Number_of_payment_attempts__c' => 0,
                    ]
            );

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

            $json_response = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            if ($status != 204) {
                    error_log('--Salesforce resetRemainingIssues Error: ' . $json_response . ' | Status: ' . $status);
                    wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce resetRemainingIssues', $json_response);
                    return ['error' => 'Salesforce resetRemainingIssues Error: ' . $json_response . ' | Status: ' . $status];
            }
            return 'Reset remaining issues in Salesforce';
        }

        /**
        * Reset Remaining Issues and Number of Payment Attempts in CRM
        */
        public function resetRemainingDigital($crm_sub) {
            $config = include __DIR__ . '/config.php';

            $access_token = $this->salesforce_login();
            $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/' . $crm_sub['Id'];

            $content = json_encode(
                    [
                            'Digital_Issues_Remaining__c' => $config['magazine']['number_of_issues'],
                            'Number_of_payment_attempts__c' => 0,
                    ]
            );

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

            $json_response = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            if ($status != 204) {
                    error_log('--Salesforce resetRemainingIssues Error: ' . $json_response . ' | Status: ' . $status);
                    wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce resetRemainingIssues', $json_response);
                    return ['error' => 'Salesforce resetRemainingIssues Error: ' . $json_response . ' | Status: ' . $status];
            }
            return 'Reset remaining issues in Salesforce';
        }

        /**
   * Update Payment Attempts in CRM
   */
        public function updatePaymentAttempts($crm_sub)
        {
                $config = include __DIR__ . '/config.php';

                $access_token = $this->salesforce_login();
                $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/' . $crm_sub['Id'];

                $Last_payment_attempt__c = date('Y-m-d\Th:i:s');

                if ($crm_sub['Number_of_payment_attempts__c'] == '') {
                        $Number_of_payment_attempts__c = 0;
                } else {
                        $Number_of_payment_attempts__c = $crm_sub['Number_of_payment_attempts__c'];
                }

                $content = json_encode(
                        [
                                'Last_payment_attempt__c' => $Last_payment_attempt__c,
                                'Number_of_payment_attempts__c' => ++$Number_of_payment_attempts__c,
                        ]
                );

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

                $json_response = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                curl_close($curl);

                if ($status != 204) {
                        error_log('--Salesforce updatePaymentAttempts Error: ' . $json_response . ' | Status: ' . $status);
                        wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce updatePaymentAttempts', $json_response);
                        return ['error' => 'Salesforce updatePaymentAttempts Error: ' . $json_response . ' | Status: ' . $status];
                }
                return 'Updated Payment info in Salesforce';
        }

        /**
   * Payment Details updated in CRM
   */
        public function updatePaymentDetailsDate($subscriber)
        {
                $access_token = $this->salesforce_login();
                $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/' . $subscriber->salesforce_id;

                $content = json_encode(
                        [
                                'Payment_details_updated__c' => date('Y-m-d\Th:i:s'),
                        ]
                );

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

                $json_response = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                curl_close($curl);

                if ($status != 204) {
                        error_log('--Salesforce Error: ' . $json_response . ' | Status: ' . $status);
                        wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Cancel Sub', $json_response);
                        wp_send_json_error(['error' => ['message' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.']]);
                        wp_die();
                }

                wp_send_json_success();
                wp_die();
        }

        /**
   * Cancel Autorenew
   *
   * @param $subscriber
   * @return void
   */
        public function cancelAutorenew($subscriber)
        {
                $access_token = $this->salesforce_login();
                $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/' . $subscriber->salesforce_id;

                $content = json_encode(
                        [
                                'Active__c' => false,
                        ]
                );

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

                $json_response = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                curl_close($curl);

                if ($status != 204) {
                        error_log('--Salesforce Error: ' . $json_response . ' | Status: ' . $status);
                        wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Cancel Sub', $json_response);
                        wp_send_json_error(['error' => ['message' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.']]);
                        wp_die();
                }

                // Trigger Braze Event
                $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/' . $subscriber->salesforce_id;
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                $json_response = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);
                if ($status != 200) {
                        wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Get Sub', "Method: " . __METHOD__ . "\n Line: " . __LINE__ . "\n Id: {$subscriber->salesforce_id}\n" . $json_response);
                } else {
                        $response = json_decode($json_response);
                        require __DIR__ . '/braze.class.php';
                        $braze = new Braze();
                        $braze->setMethod('POST');
                        $braze->triggerEventByEmail(
                                $response->Email__c,
                                'rs_mag_autorenewal_updated',
                                [
                                        'subscriber' => $response->Name,
                                        'status' => 'de-activated',
                                ]
                        );
                }

                wp_send_json_success();
                wp_die();
        } // cancelAutorenew

        /**
   * Cancel Subscription
   *
   * @param $subscriber
   * @return void
   */
        public function cancelSubscription($subscriber)
        {
                $access_token = $this->salesforce_login();
                $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/' . $subscriber->salesforce_id;

                $content = json_encode(
                        [
                                'Active__c' => false,
                                'Remaining_Issues__c' => 0,
                                'Digital_Issues_Remaining__c' => 0,
                        ]
                );

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

                $json_response = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                curl_close($curl);

                if ($status != 204) {
                        error_log('--Salesforce Error: ' . $json_response . ' | Status: ' . $status);
                        wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Cancel Sub', $json_response);
                        wp_send_json_error(['error' => ['message' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.']]);
                        wp_die();
                }
                wp_send_json_success();
                wp_die();
        } // cancelSubscription

        /**
   * Enable Autorenew
   *
   * @param $subscriber
   * @return void
   */
        public function enableAutorenew($subscriber)
        {
                $access_token = $this->salesforce_login();
                $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/' . $subscriber->salesforce_id;

                $content = json_encode(
                        [
                                'Active__c' => true,
                        ]
                );

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

                $json_response = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                curl_close($curl);

                if ($status != 204) {
                        error_log('--Salesforce Error: ' . $json_response . ' | Status: ' . $status);
                        wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Enable Sub', $json_response);
                        wp_send_json_error(['error' => ['message' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.']]);
                        wp_die();
                }

                // Trigger Braze Event
                $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/' . $subscriber->salesforce_id;
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                $json_response = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);
                if ($status != 200) {
                        wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Get Sub', "Method: " . __METHOD__ . "\n Line: " . __LINE__ . "\n Id: {$subscriber->salesforce_id}\n" . $json_response);
                } else {
                        $response = json_decode($json_response);
                        require __DIR__ . '/braze.class.php';
                        $braze = new Braze();
                        $braze->setMethod('POST');
                        $braze->triggerEventByEmail(
                                $response->Email__c,
                                'rs_mag_autorenewal_updated',
                                [
                                        'subscriber' => $response->Name,
                                        'status' => 'activated',
                                ]
                        );
                }

                wp_send_json_success();
                wp_die();
        } // enableAutorenew

        /**
   * Show Export
   *
   * @return void
   */
        public function showExport()
        {
                $access_token = $this->salesforce_login();
                // $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c/';
                // $url = $this->salesforce['login_uri'] . '/services/data/v40.0/limits/recordCount?sObjects=Magazine_Subscription__c';
                $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c//listview_actives/' . $this->listview_active . '/results?limit=2000';
                // $url = $this->salesforce['login_uri'] . '/services/data/v47.0/sobjects/Magazine_Subscription__c//listview_actives/recent';

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                // curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

                $json_response = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                curl_close($curl);

                $response = json_decode($json_response);
                $records = $response->records;

                if ($records) :
?>
                        <form action="<?php echo esc_url(admin_url('admin.php')); ?>" method="post" target="_blank">
                                <input type="hidden" name="action" value="export_crm_subs" />
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <h1>Export - Magazine Subscriptions</h1>
                                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Export"></p>
                                </div>
                                <table class="widefat">
                                        <?php
                                        if ($records[0] && $records[0]->columns) : ?>
                                                <thead>
                                                        <tr>
                                                                <td id="cb" class="manage-column column-cb check-column">
                                                                        <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                                                                        <input id="cb-select-all-1" type="checkbox">
                                                                </td>
                                                                <?php foreach ($records[0]->columns as $column) : ?>
                                                                        <th><?php echo $column->fieldNameOrPath; ?></th>
                                                                <?php endforeach; ?>
                                                        </tr>
                                                </thead>
                                                <?php
                                        endif;
                                        foreach ($records as $record) :
                                                $columns = $record->columns;
                                                if ($columns) :
                                                        $item = wp_list_pluck($columns, 'value', 'fieldNameOrPath');
                                                ?>
                                                        <tr>
                                                                <th scope="row" class="check-column">
                                                                        <label class="screen-reader-text" for="cb-select-<?php echo $item['Id']; ?>">
                                                                                Select <?php echo $item['Name']; ?>
                                                                        </label>
                                                                        <input id="cb-select-<?php echo $item['Id']; ?>" type="checkbox" name="salesforce_records[]" value="<?php echo $item['Id']; ?>">
                                                                </th>
                                                                <?php foreach ($item as $key => $val) : ?>

                                                                        <?php if (!in_array($key, ['Buyer__c', 'Full_Address__c', 'Is_Gift__c', 'Coupon_code__c', 'TBM_Coupon_code__c', 'Amount_Paid__c', 'CreatedDate', 'LastModifiedDate', 'SystemModstamp'])) : ?>
                                                                                <input type="hidden" name="subscriptions[<?php echo $item['Id']; ?>][<?php echo $key; ?>]" value="<?php echo $val; ?>">
                                                                        <?php endif; ?>
                                                                        <td><?php echo 'Name' == $key ? '<strong>' : ''; ?><?php echo $val; ?><?php echo 'Name' == $key ? '</strong>' : ''; ?></td>
                                                                <?php endforeach; // For Each Column 
                                                                ?>
                                                        </tr>
                                        <?php
                                                endif; // If $columns
                                        endforeach; // For Each $records 
                                        ?>
                                </table>
                        </form>
<?php
                endif; // If $records

                // echo '<pre>'; print_r( ( $records ) ); exit;
        }

        /**
   * Do Export
   *
   * @return void
   */
        public function doExport()
        {
                $salesforce_records = isset($_POST['salesforce_records']) && !empty($_POST['salesforce_records']) ? $_POST['salesforce_records'] : null;
                if (!is_null($salesforce_records)) :
                        $subscriptions = isset($_POST['subscriptions']) && !empty($_POST['subscriptions']) ? $_POST['subscriptions'] : null;

                        $first = reset($subscriptions);
                        if ($first) :
                                $csv_content = implode(array_keys($first), ",") . "\n";
                        endif;

                        foreach ($subscriptions as $subscription) :
                                if (!isset($subscription['Id']) || !in_array($subscription['Id'], $salesforce_records)) :
                                        continue;
                                endif;

                                // $csv_content .= implode( ",", $subscription ) . "\n";
                                $csv_content .= "\"" . implode("\",\"", $subscription) . "\"" . "\n";
                        endforeach;

                        header('Content-Encoding: UTF-8');
                        header('Content-type: text/csv; charset=UTF-8');
                        header('Content-Disposition: attachment; filename="export.csv";');
                        header("Pragma: no-cache");
                        header("Expires: 0");

                        echo $csv_content;
                        exit();
                else :
                        echo 'Nothing to process';
                endif;
        }

        /**
   * Get Subscriptions up for renewal
   *
   * @param $limit
   * @return $sub
   */
        public function getSubscriptionsForRenewal($limit = 10) {
            $access_token = $this->salesforce_login();
            $url = $this->salesforce['login_uri'] . '/services/data/v51.0/sobjects/Magazine_Subscription__c/listviews/' . $this->listview_renewals . '/results?limit=' . $limit;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

            $json_response = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($status != 200) {
                error_log('--Salesforce Error Get Renewals list: ' . $json_response);
                wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Get Renewals list', $json_response);
                return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
                wp_die();
            } else {
                $field_email = 'Email__c';

                $response = json_decode($json_response);

                $subs = [];
                $sub_emails = [];
                if ($response && isset($response->records)) {
                foreach ($response->records as $record) {
                        $sub = [];
                        foreach ($record->columns as $column) {
                                $sub[$column->fieldNameOrPath] = $column->value;
                                if ($column->fieldNameOrPath == $field_email) {
                                        $sub_emails[] = $column->value;
                                }
                        }
                        array_push($subs, $sub);
                }
                }

                return $subs;
            }
        } // getSubscriptionsForRenewal()

        /**
   * Get Upcoming renewals (week out from renewal date)
   *
   * @param $limit
   * @return $sub
   */
    public function getSubscriptionsUpcomingRenewal($limit = 10) {
        $access_token = $this->salesforce_login();
        $url = $this->salesforce['login_uri'] . '/services/data/v51.0/sobjects/Magazine_Subscription__c/listviews/' . $this->listview_upcoming_renewals . '/results?limit=' . $limit;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($status != 200) {
            error_log('--Salesforce Error Get Renewals list: ' . $json_response . ' - ' . $status);
        //     wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Get Renewals list', $json_response);
            return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        } else {
            $field_email = 'Email__c';

            $response = json_decode($json_response);

            $subs = [];
            $sub_emails = [];
            if ($response && isset($response->records)) {
                foreach ($response->records as $record) {
                    $sub = [];
                    foreach ($record->columns as $column) {
                        $sub[$column->fieldNameOrPath] = $column->value;
                        if ($column->fieldNameOrPath == $field_email) {
                            $sub_emails[] = $column->value;
                        }
                    }
                    array_push($subs, $sub);
                }
            }

            return $subs;
        }
    } // getSubscriptionsForRenewal()

        /**
   * Get Subscriber Emails
   */
        public function getSubscriberEmails($limit = 10, $offset = 1)
        {
                $access_token = $this->salesforce_login();
                $url = $this->salesforce['login_uri'] . "/services/data/v47.0/sobjects/Magazine_Subscription__c//listviews/{$this->listview_active_emails_only}/results?limit={$limit}&offset={$offset}";
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                $json_response = curl_exec($curl);
                curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);

                $response = json_decode($json_response);
                $records = isset($response->records) ? $response->records : NULL;
                $emails = [];
                $has_more = false;
                if ($records) {
                        foreach ($records as $record) {
                                $columns = $record->columns;
                                if ($columns) {
                                        foreach ($columns as $column) {
                                                if ($column->fieldNameOrPath == 'Email__c') {
                                                        // if (!is_null($column->value) && !in_array($column->value, $emails)) 
                                                        {
                                                                $emails[] = $column->value;
                                                        }
                                                }
                                        }
                                }
                        }


                        $offset += $limit;
                        $url = $this->salesforce['login_uri'] . "/services/data/v47.0/sobjects/Magazine_Subscription__c//listviews/{$this->listview_active_emails_only}/results?limit=1&offset={$offset}";
                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_HEADER, false);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                        $json_response = curl_exec($curl);
                        curl_getinfo($curl, CURLINFO_HTTP_CODE);
                        curl_close($curl);
                        $response = json_decode($json_response);
                        if ($response->size > 0) {
                                $has_more = true;
                        }
                }
                return ['size' => count($emails), 'emails' => $emails, 'has_more' => $has_more];
        } // getSubscriberEmails()

        /**
   * Get Subscriptions up for Comps renewal
   *
   * @param $limit
   * @return $sub
   */
        public function getSubscriptionsForRenewalComps($limit = 10)
        {
                $access_token = $this->salesforce_login();
                $url = $this->salesforce['login_uri'] . '/services/data/v51.0/sobjects/Magazine_Subscription__c/listviews/' . $this->listview_renewals_comps . '/results?limit=' . $limit;

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

                $json_response = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);

                if ($status != 200) {
                        error_log('--Salesforce Error Get Renewals list: ' . $json_response);
                        wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Get Renewals list', $json_response);
                        return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
                        wp_die();
                } else {
                        $field_email = 'Email__c';

                        $response = json_decode($json_response);

                        $subs = [];
                        $sub_emails = [];
                        if ($response && isset($response->records)) {
                                foreach ($response->records as $record) {
                                        $sub = [];
                                        foreach ($record->columns as $column) {
                                                $sub[$column->fieldNameOrPath] = $column->value;
                                                if ($column->fieldNameOrPath == $field_email) {
                                                        $sub_emails[] = $column->value;
                                                }
                                        }
                                        array_push($subs, $sub);
                                }
                        }

                        return $subs;
                }
        } // getSubscriptionsForRenewalComps()

        /**
   * Get Subscriptions up for renewal - Overdue
   *
   * @param $limit
   * @return $sub
   */
        public function getSubscriptionsForRenewalOverdue($limit = 10)
        {
                $access_token = $this->salesforce_login();
                $url = $this->salesforce['login_uri'] . '/services/data/v51.0/sobjects/Magazine_Subscription__c/listviews/' . $this->listview_renewals_overdue . '/results?limit=' . $limit;

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}", "Content-type: application/json"));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

                $json_response = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);

                if ($status != 200) {
                        error_log('--Salesforce Error Get Renewals list: ' . $json_response);
                        wp_mail('dev@thebrag.media', 'RS Mag Error: Salesforce Get Renewals list', $json_response);
                        return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
                        wp_die();
                } else {
                        $field_email = 'Email__c';

                        $response = json_decode($json_response);

                        $subs = [];
                        $sub_emails = [];
                        if ($response && isset($response->records)) {
                                foreach ($response->records as $record) {
                                        $sub = [];
                                        foreach ($record->columns as $column) {
                                                $sub[$column->fieldNameOrPath] = $column->value;
                                                if ($column->fieldNameOrPath == $field_email) {
                                                        $sub_emails[] = $column->value;
                                                }
                                        }
                                        array_push($subs, $sub);
                                }
                        }

                        return $subs;
                }
        } // getSubscriptionsForRenewalOverdue()

        /**
   * Salesforce Login
   *
   * @return void
   */
    protected function salesforce_login() {
        $params = 'grant_type=password' .
            '&client_id=' . $this->salesforce['client_id'] .
            '&client_secret=' . $this->salesforce['client_secret'] .
            '&username=' . urlencode($this->salesforce['username']) .
            '&password=' . urlencode($this->salesforce['password']);

        $token_url = $this->salesforce['login_uri'] . "/services/oauth2/token";

        $curl = curl_init($token_url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($status != 200) {
            error_log('--Salesforce Error Login: ' . $json_response);
        }

        curl_close($curl);

        $response = json_decode($json_response);

        return $response->access_token;
    }
}
