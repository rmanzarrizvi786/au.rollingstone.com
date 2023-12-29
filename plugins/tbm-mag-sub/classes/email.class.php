<?php
    class Email
    {

        protected $template;
        protected $comps_email_destination;

        public function __construct()
        {
            $this->template = __DIR__ . '/../email-templates/template.php';

            $this->comps_email_destination = 'dev@thebrag.media';
        }

        public function getHeader()
        {
            return $this->email_header; // . $this->email_footer;
        }

        public function send($type = null, $crm_sub = null, $sub = null)
        {
            if (is_null($type) || is_null($crm_sub) || !isset($crm_sub->Email__c)) {
                return;
            }

            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            $sub_name = isset($sub) && !is_null($sub) && isset($crm_sub->Buyer__c) ? $crm_sub->Buyer__c : 'subscriber';

            switch ($type):
        case 'renewal-failed':
            $subject = 'Oops! There was an issue with the renewal of your Rolling Stone Australia magazine subscription.';
            // $preview_text = '';

            $payment_link = 'https://thebrag.com/observer/magazine-subscriptions/?a=update_payment&id=' . $sub->uniqid;
            if (isset($sub->observer_user) && isset($sub->observer_user->oc_token) && $sub->observer_user->oc_token != false) {
                    $returnTo = urlencode($payment_link . '&oc=' . $sub->observer_user->oc_token);
                    $payment_link = 'https://thebrag.com/login-redirect/?&returnTo=' . $returnTo;
            }

            ob_start();
        ?>
                <p>Dear<?php echo $sub_name; ?>,</p>
                <p>We hope you've been enjoying your Rolling Stone Australia magazine subscription. We've now sent you 4 quarterly issues as part of your annual subscription, so it's time for your renewal. We recently tried to process your renewal but ran into a payment problem.</p>
                <p>If you'd like to continue receiving the magazine (and we sincerely hope you do), please update your payment details <a href="<?php echo $payment_link; ?>" target="_blank">here</a> and we'll try to process the renewal again in 3 days time.</p>

                <table align="center" border="0" cellpadding="5" cellspacing="0" width="100%" style="width: 400px; max-width: 100%;">
                    <?php if (get_option('tbm_next_issue_cover') && '' != trim(get_option('tbm_next_issue_cover'))): ?>
                        <tr>
                            <td align="center" valign="middle" style="text-align: center;">
                                <img src="<?php echo trim(get_option('tbm_next_issue_cover')); ?>" width="200" style="width: 200px; max-width: 100%; height: auto;">
                            </td>
                        </tr>
                    <?php endif;?>
                    <tr>
                        <td valign="middle" style="background: #f3f3f3; padding-top: 15px; padding-bottom: 15px; padding-left: 15px; padding-right: 15px; width: 100%; border-radius: 10px;">
                            <table border="0" cellpadding="5" cellspacing="0" width="100%" style="width: 100%;">
                                <tr>
                                    <td valign="top"><strong>Rolling Stone Australia Subscription</strong><br><span style="color: #ccc;">(4 issues)</span></td>
                                    <td valign="top" align="right" style="text-align: right; white-space: nowrap;">$59.95</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Shipping</strong><br><span style="color: #ccc;">(4 issues)</span></td>
                                    <td valign="top" align="right" style="text-align: right; white-space: nowrap;">$0.00</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Amount due</strong></td>
                                    <td valign="top" align="right" style="text-align: right; white-space: nowrap;">$59.95</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td align="" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; width: 100%; text-align: center; background-color: rgb(211, 37, 49); border-radius: 6px; height: 44px;">
                                            <a style="border: 0px; margin: 0px; padding: 0px 60px; text-decoration: none; outline: 0px; display: block; text-align: center;" href="<?php echo $payment_link; ?>" target="_blank">
                                                <span style="text-decoration: none; font-weight: 500; font-size: 16px; line-height: 44px; color: rgb(255, 255, 255);">Update payment details</span>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>

                <p>Thanks for being a Rolling Stone magazine subscriber and supporting long-form journalism!</p>

                <p>Regards,<br>Rolling Stone Australia</p>
<?php

            $body = ob_get_contents();
            ob_end_clean();

            ob_start();
            include $this->template;
            $email_html = ob_get_clean();

            break; // renewal-failed
        case 'renewal-success':
            $subject = 'Success! Your Rolling Stone Australia magazine subscription has renewed for another 4 issues.';
            // $preview_text = '';

            require_once __DIR__ . '/coupon.class.php';
            $coupon_obj = new Coupon();
            $coupon = $coupon_obj->getCouponForSubscription($sub);

            $body = 'Dear ' . $sub_name . "!<br><br>
                    Success! You're annual subscription to the Rolling Stone Australia magazine has been renewed for another 4 issues.<br><br>
                    We hope you've been enjoying the issue and want to thank you for being a valued subscriber and supporter of longform print journalism and one of the most iconic, trusted, and recognisable publications in the world. We couldn’t do this without you!<br><br>
                    Your receipt should arrive separately in another email. In the meantime please make sure your shipping details are all up to date in your profile <a href=\"https://thebrag.com/observer/magazine-subscriptions/\" target=\"_blank\">here</a>.<br><br>
                    Regards,<br>Rolling Stone Australia</p>";

            require_once __DIR__ . '/coupon.class.php';
            $coupon_obj = new Coupon();
            $coupon = $coupon_obj->getCouponForSubscription($sub);

            if ($coupon && $coupon->coupon_code) {
                $body .= '<br><div style="padding: 20px; border-radius: 20px; background-color: #f3f3f3;">
                    As a thanks for being a legend and renewing your Rolling Stone magazine subscription, we are providing you with a discount code you can send to your family and friends. Just send them the following code and they can sign up for a yearly subscription and receive their first issue free!<br>
                    <pre style="padding: 10px; border: 1px dashed #ccc; width: 120px; max-width: 100%; text-align: center; font-size: 18px; margin: auto; font: monospace">' . $coupon->coupon_code . '</pre></div>';
            }

            ob_start();
            include $this->template;
            $email_html = ob_get_clean();

            break; // renewal-success
        default:
            break;
            endswitch;

            if (isset($email_html) && isset($subject)) {
                wp_mail($crm_sub->Email__c, $subject, $email_html, $headers);
            }
        }

        public function sendComps($crm_subs = [])
        {
            if (empty($crm_subs)) {
                return;
            }

            $headers[] = 'Content-Type: text/html; charset=UTF-8';

            $subject = 'Attention: These are Comp Subscriptions up for renewal';
            $body = 'Please see the list of Comp Subscriptions below up for renewal;';
            $body .= '<table cellpadding="5" border="1" width="100%" style="border-colod: #ccc;">
        <tr>
        <th>Subscriber</th>
        <th>Buyer</th>
        <th>Email</th>
        </tr>';
            foreach ($crm_subs as $crm_sub) {
                $body .= '<tr>';
                $body .= '<td><a href="https://sales.thebrag.media/app/v1-subscription#id=' . $crm_sub->Id . '/view" target="_blank">' . $crm_sub->Name . '</a></td>';
                $body .= '<td>' . $crm_sub->Buyer__c . '</td>';
                $body .= '<td><a href="mailto:' . $crm_sub->Email__c . '">' . $crm_sub->Email__c . '</a></td>';
                $body .= '</tr>';
            }
            $body .= '</table>';

            ob_start();
            include $this->template;
            $email_html = ob_get_clean();

            if (isset($email_html) && isset($subject)) {
                return wp_mail($this->comps_email_destination, $subject, $email_html, $headers);
            }
        }
}
