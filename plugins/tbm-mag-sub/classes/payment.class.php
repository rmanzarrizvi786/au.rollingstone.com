<?php

class Payment {
    protected $is_sandbox;
    protected $stripe;
    protected $config;

    public function __construct() {
        $this->config = include __DIR__ . '/config.php';

        // Include Stripe SDK
        require_once(plugin_dir_path(__FILE__) . '../vendor/autoload.php');

        $this->stripe = new \Stripe\StripeClient($this->config['stripe']['secret_key']);
    }

    public function createIntent($amount, $currency) {
        $payment_intent = $this->stripe->paymentIntents->create([
            'setup_future_usage' => 'off_session',
            'amount' => $amount,
            'currency' => $currency,
            'automatic_payment_methods' => ['enabled' => true]
        ]);

        return $payment_intent;
    }

    public function createCustomer($payment_method, $sub_email, $sub_full_name, $buy_option, $buyer = [], $shipping = []) {
        try {
            $customer = $this->stripe->customers->create([
                'payment_method' => $payment_method,
                'email' => $sub_email,
                'name' => $buyer['full_name'],
                'address' => [
                    'line1' => $buyer['address_1'],
                    'line2' => $buyer['address_2'],
                    'city' => $buyer['city'],
                    'country' => $buyer['country'],
                    'postal_code' => $buyer['postcode'],
                    'state' => $buyer['state'],
                ],
                'shipping' => [
                    'address' => [
                        'line1' => $shipping['address_1'],
                        'line2' => $shipping['address_2'],
                        'city' => $shipping['city'],
                        'country' => $shipping['country'],
                        'postal_code' => $shipping['postcode'],
                        'state' => $shipping['state'],
                    ],
                    'name' => $sub_full_name
                ],
                'invoice_settings' => [
                    'default_payment_method' => $payment_method
                ],
                'metadata' => [
                    'buy_option' => $buy_option,
                    'email_reciever' => $shipping['sub_email_reciever']
                ]
            ]);

            return $customer;
        } catch (\Stripe\Exception\CardException $e) {
            error_log('--Stripe Error | Customer | CardException : ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Cus', $e->getError()->message);
            return ['error' => 'It looks like your card has been declined. Make sure all your details are correct, your card is valid, and give it another shot.'];
            wp_die();
        } catch (\Stripe\Exception\RateLimitException $e) {
            error_log('--Stripe Error | Customer | RateLimitException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Cus', $e->getError()->message);
            return ['error' => 'Whoops, like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.'];
            wp_die();
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            error_log('--Stripe Error | Customer | InvalidRequestException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Cus', $e->getError()->message);
            return ['error' => 'Whoops, like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.'];
            wp_die();
        } catch (\Stripe\Exception\AuthenticationException $e) {
            error_log('--Stripe Error | Customer | AuthenticationException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Cus', $e->getError()->message);
            return ['error' => 'Whoops, like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.'];
            wp_die();
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            error_log('--Stripe Error | Customer | ApiConnectionException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Cus', $e->getError()->message);
            return ['error' => 'Whoops, it seems we had a bit of trouble getting in contact with the payment service. Feel free to refresh your browser and give it another shot!'];
            wp_die();;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log('--Stripe Error | Customer | ApiErrorException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Cus', $e->getError()->message);
            return ['error' => 'Whoops, like something unexpected happened on our side of things. Please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        } catch (Exception $e) {
            error_log('--Stripe Error | Customer | Exception: ' . $e->getMessage());
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Cus', $e->getMessage());
            return ['error' => 'Whoops, like something unexpected happened on our side of things. Please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        }
    }

    public function payInvoice($invoice_id) {
        try {
            $invoice = $this->stripe->invoices->retrieve($invoice_id);
            if ($invoice->paid) {
                return $invoice;
            }
            $invoice = $this->stripe->invoices->pay($invoice_id);
            return $invoice;
        } catch (\Stripe\Exception\CardException $e) {
            error_log('--Stripe Error | Invoice | CardException : ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Pay Invoice: CardException', $e->getError()->message);
            return [
                'error' => 'It looks like your card has been declined. Make sure all your details are correct, your card is valid, and give it another shot.',
                'invoice' => $invoice,
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (\Stripe\Exception\RateLimitException $e) {
            error_log('--Stripe Error | Invoice | RateLimitException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Pay Invoice: RateLimitException', $e->getError()->message);
            return [
                'error' => 'Whoops, it looks like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.',
                'invoice' => $invoice,
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            error_log('--Stripe Error | Invoice | InvalidRequestException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Pay Invoice: InvalidRequestException', $e->getError()->message);
            return [
                'error' => 'Whoops, like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.',
                'invoice' => $invoice,
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (\Stripe\Exception\AuthenticationException $e) {
            error_log('--Stripe Error | Invoice | AuthenticationException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Pay Invoice: AuthenticationException', $e->getError()->message);
            return [
                'error' => 'Whoops, like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.',
                'invoice' => $invoice,
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            error_log('--Stripe Error | Invoice | ApiConnectionException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Pay Invoice: ApiConnectionException', $e->getError()->message);
            return [
                'error' => 'Whoops, it seems we had a bit of trouble getting in contact with the payment service. Feel free to refresh your browser and give it another shot!',
                'invoice' => $invoice,
                'stripe_error' => $e->getError()->message
            ];
            wp_die();;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log('--Stripe Error | Invoice | ApiErrorException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Pay Invoice: ApiErrorException', $e->getError()->message);
            return [
                'error' => 'Whoops, like something unexpected happened on our side of things. Please contact subscribe@thebrag.media with the details you submitted.',
                'invoice' => $invoice,
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (Exception $e) {
            error_log('--Stripe Error | Invoice | Exception: ' . $e->getMessage());
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Pay Invoice: Exception', $e->getMessage());
            return [
                'error' => 'Whoops, like something unexpected happened on our side of things. Please contact subscribe@thebrag.media with the details you submitted.',
                'invoice' => $invoice,
                'stripe_error' =>$e->getMessage()
            ];
            wp_die();
        }
    }

    public function cancelSubscription($sub_id) {
        try {
            $stripe_sub = $this->stripe->subscriptions->retrieve(
                $sub_id,
                []
            );

            if ($stripe_sub) {
                return $this->stripe->subscriptions->cancel(
                    $sub_id,
                    []
                );
            }
        } catch (\Stripe\Exception\CardException $e) {
            error_log('--Stripe Error | CancelSubscription | CardException : ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe CancelSubscription: CardException', $e->getError()->message);
            return [
                'error' => 'It looks like your card has been declined. Make sure all your details are correct, your card is valid, and give it another shot.',
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (\Stripe\Exception\RateLimitException $e) {
            error_log('--Stripe Error | CancelSubscription | RateLimitException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe CancelSubscription: RateLimitException', $e->getError()->message);
            return [
                'error' => 'Whoops, it looks like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.',
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            error_log('--Stripe Error | CancelSubscription | InvalidRequestException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe CancelSubscription: InvalidRequestException', $e->getError()->message);
            return [
                'error' => 'Whoops, like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.',
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (\Stripe\Exception\AuthenticationException $e) {
            error_log('--Stripe Error | CancelSubscription | AuthenticationException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe CancelSubscription: AuthenticationException', $e->getError()->message);
            return [
                'error' => 'Whoops, like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.',
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            error_log('--Stripe Error | CancelSubscription | ApiConnectionException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe CancelSubscription: ApiConnectionException', $e->getError()->message);
            return [
                'error' => 'Whoops, it seems we had a bit of trouble getting in contact with the payment service. Feel free to refresh your browser and give it another shot!',
                'stripe_error' => $e->getError()->message
            ];
            wp_die();;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log('--Stripe Error | CancelSubscription | ApiErrorException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe CancelSubscription: ApiErrorException', $e->getError()->message);
            return [
                'error' => 'Whoops, like something unexpected happened on our side of things. Please contact subscribe@thebrag.media with the details you submitted.',
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (Exception $e) {
            error_log('--Stripe Error | CancelSubscription | Exception: ' . $e->getMessage());
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe CancelSubscription: Exception', $e->getMessage());
            return [
                'error' => 'Whoops, like something unexpected happened on our side of things. Please contact subscribe@thebrag.media with the details you submitted.',
                'stripe_error' => $e->getMessage()
            ];
            wp_die();
        }
    }

    public function createInvoice($buy_option, $product_description, $subtotal, $shipping_cost, $number_of_issues, $coupon_code, $amount_off, $payment_method, $sub_email, $sub_full_name, $buyer = [], $shipping = [], $customer_id = NULL, $collection_method = 'charge_automatically', $description = '') {
        try {
            $customer = null;
            if (is_null($customer_id)) {
                $customer = $this->createCustomer($payment_method, $sub_email, $sub_full_name, $buy_option, $buyer, $shipping);

                if ($customer['error']) {
                    return ['error' => $customer['error']];
                    wp_die();
                }

                $customer_id = $customer->id;
            }

            $this->stripe->invoiceItems->create([
                'customer' => $customer_id,
                'amount' => $subtotal,
                'currency' => 'aud',
                'description' => $product_description || 'Rolling Stone Magazine Subscription',
            ]);

            $this->stripe->invoiceItems->create([
                'customer' => $customer_id,
                'unit_amount' => $shipping_cost,
                'currency' => 'aud',
                'quantity' => $number_of_issues,
                'description' => 'Shipping'
            ]);

            if ('' != $coupon_code && $amount_off < 0) :
                $this->stripe->invoiceItems->create([
                    'customer' => $customer_id,
                    'amount' => $amount_off,
                    'currency' => 'aud',
                    'description' => 'Coupon code: ' . $coupon_code
                ]);
            endif;

            $stripe_data = [
                'customer' => $customer_id,
                'default_tax_rates' => [
                    $this->config['stripe']['default_tax_rates']
                ],
                'collection_method' => $collection_method,
            ];

            if ('' != $description) {
                $stripe_data['description'] = $description;
            }

            if ('charge_automatically' != $collection_method) {
                $stripe_data['days_until_due'] = 30;
                // $stripe_data['footer'] = 'Please pay by due date in order to renew your magazine subscription.';
            }

            $invoice = $this->stripe->invoices->create($stripe_data);

            if ('charge_automatically' == $collection_method) {
                $this->stripe->invoices->pay($invoice->id);
            } else {
                $this->stripe->invoices->sendInvoice($invoice->id);
            }

            $invoice = $this->stripe->invoices->retrieve($invoice->id);

            return [
                'customer' => $customer,
                'invoice' => $invoice
            ];
        } catch (\Stripe\Exception\CardException $e) {
            error_log('--Stripe Error | Invoice | CardException : ' . $e->getError()->message);
            // wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Invoice: CardException', $e->getError()->message);
            return [
                'error' => 'It looks like your card has been declined. Make sure all your details are correct, your card is valid, and give it another shot.',
                'customer' => $customer,
                'invoice' => $invoice,
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (\Stripe\Exception\RateLimitException $e) {
            error_log('--Stripe Error | Invoice | RateLimitException: ' . $e->getError()->message);
            // wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Invoice: RateLimitException', $e->getError()->message);
            return [
                'error' => 'Whoops, it looks like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.',
                'customer' => $customer,
                'invoice' => $invoice,
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            error_log('--Stripe Error | Invoice | InvalidRequestException: ' . $e->getError()->message);
            // wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Invoice: InvalidRequestException', $e->getError()->message);
            return [
                'error' => 'Whoops, like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.',
                'customer' => $customer,
                'invoice' => $invoice,
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (\Stripe\Exception\AuthenticationException $e) {
            error_log('--Stripe Error | Invoice | AuthenticationException: ' . $e->getError()->message);
            // wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Invoice: AuthenticationException', $e->getError()->message);
            return [
                'error' => 'Whoops, like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.',
                'customer' => $customer,
                'invoice' => $invoice,
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            error_log('--Stripe Error | Invoice | ApiConnectionException: ' . $e->getError()->message);
            // wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Invoice: ApiConnectionException', $e->getError()->message);
            return [
                'error' => 'Whoops, it seems we had a bit of trouble getting in contact with the payment service. Feel free to refresh your browser and give it another shot!',
                'customer' => $customer,
                'invoice' => $invoice,
                'stripe_error' => $e->getError()->message
            ];
            wp_die();;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log('--Stripe Error | Invoice | ApiErrorException: ' . $e->getError()->message);
            // wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Invoice: ApiErrorException', $e->getError()->message);
            return [
                'error' => 'Whoops, like something unexpected happened on our side of things. Please contact subscribe@thebrag.media with the details you submitted.',
                'customer' => $customer,
                'invoice' => $invoice,
                'stripe_error' => $e->getError()->message
            ];
            wp_die();
        } catch (Exception $e) {
            error_log('--Stripe Error | Invoice | Exception: ' . $e->getMessage());
            // wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Create Invoice: Exception', $e->getError()->message);
            return [
                'error' => 'Whoops, like something unexpected happened on our side of things. Please contact subscribe@thebrag.media with the details you submitted.',
                'customer' => $customer,
                'invoice' => $invoice,
                'stripe_error' => $e->getMessage()  
            ];
            wp_die();
        }
    }

    public function updateCustomer($subscriber) {
        try {
            $customer = $this->stripe->customers->update(
                $subscriber->stripe_customer_id,
                [
                    'name' => $subscriber->full_name,
                    'address' => [
                        'line1' => $subscriber->address_1,
                        'line2' => $subscriber->address_2,
                        'city' => $subscriber->city,
                        'country' => $subscriber->country,
                        'postal_code' => $subscriber->postcode,
                        'state' => $subscriber->state,
                    ],
                    'shipping' => [
                        'address' => [
                            'line1' => $subscriber->shipping_address_1,
                            'line2' => $subscriber->shipping_address_2,
                            'city' => $subscriber->shipping_city,
                            'country' => $subscriber->shipping_country,
                            'postal_code' => $subscriber->shipping_postcode,
                            'state' => $subscriber->shipping_state,
                        ],
                        'name' => $subscriber->sub_full_name,
                    ],
                ]
            );

            return true;
        } catch (\Stripe\Exception\CardException $e) {
            error_log('--Stripe Error | Customer | CardException : ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Update Cus', $e->getError()->message);
            return ['error' => 'It looks like your card has been declined. Make sure all your details are correct, your card is valid, and give it another shot.'];
            wp_die();
        } catch (\Stripe\Exception\RateLimitException $e) {
            error_log('--Stripe Error | Customer | RateLimitException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Update Cus', $e->getError()->message);
            return ['error' => 'Whoops, like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.'];
            wp_die();
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            error_log('--Stripe Error | Customer | InvalidRequestException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Update Cus', $e->getError()->message);
            return ['error' => 'Whoops, like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.'];
            wp_die();
        } catch (\Stripe\Exception\AuthenticationException $e) {
            error_log('--Stripe Error | Customer | AuthenticationException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Update Cus', $e->getError()->message);
            return ['error' => 'Whoops, like something unexpected happened on our of things. Feel free to refresh your browser and give it another shot.'];
            wp_die();
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            error_log('--Stripe Error | Customer | ApiConnectionException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Update Cus', $e->getError()->message);
            return ['error' => 'Whoops, it seems we had a bit of trouble getting in contact with the payment service. Feel free to refresh your browser and give it another shot!'];
            wp_die();;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log('--Stripe Error | Customer | ApiErrorException: ' . $e->getError()->message);
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Update Cus', $e->getError()->message);
            return ['error' => 'Whoops, like something unexpected happened on our side of things. Please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        } catch (Exception $e) {
            error_log('--Stripe Error | Customer | Exception: ' . $e->getMessage());
            wp_mail('dev@thebrag.media', 'RS Mag Error: Stripe Update Cus', $e->getMessage());
            return ['error' => 'Whoops, like something unexpected happened on our side of things. Please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        }
    }

    public function webhook() {
        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                return $invoice;
                break;
            default:
                // error_log( '-- Stripe Webhook -- Received unknown event type ' . $event->type );
        }



        // http_response_code(200);
    }

    /*
    * Get Countries
    */
    public static function getCountries() {
        require_once __DIR__ . '/helper.class.php';
        return Helper::getCountries();
    }
}
