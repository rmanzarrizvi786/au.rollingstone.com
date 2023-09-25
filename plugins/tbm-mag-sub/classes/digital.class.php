<?php

require_once(plugin_dir_path(__FILE__) . '../vendor/autoload.php');

class Digital {
    protected $is_sandbox;

    protected $digital;

    public function __construct() {
        $this->is_sandbox = isset($_ENV) && isset($_ENV['ENVIRONMENT']) && 'development' == $_ENV['ENVIRONMENT'];

        $config = include __DIR__ . '/config.php';

        $this->digital = $config['digital'];
    }

    public function createSubscriptionLatest($subscriber) {
        $content = [
            'FullName' => $subscriber->full_name,
            'Address1' => $subscriber->address_1,
            'Address2' => $subscriber->address_2 || '',
            'City' => $subscriber->city,
            'State' => $subscriber->state,
            'ZipCode' => $subscriber->postcode,
            'Source' => 'Rolling Stone AU/NZ',
            'Channel' => 'Rolling Stone AU/NZ Website',
            'PartnerSubscriptionID' => $subscriber->uniqid,
            'PartnerConsumerID' => $subscriber->salesforce_id,
            'Email' => $subscriber->email_reciever,
            'Key' => $this->digital['api_key'],
            'ProgramKey' => $this->digital['digital'],
        ];
        
        $url = $this->digital['api_url'] . '/subscribe';

        $curl = new Curl\Curl();
        $curl->post($url, $content);

        if ($curl->error) {
            error_log('--eMagazines Error: ' . $curl->errorMessage);
            wp_mail('dev@thebrag.media', 'RS Mag Error: eMagazines Create Sub', "Method: " . __METHOD__ . "\n Line: " . __LINE__ . "\n Id: { $subscriber->uniqid }\n" . print_r($curl->diagnose(), true));
            return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        } else {
            $response = $curl->response;

            return [
                'latest_issue_link' => $response->latest_issue_link,
                'express_library_link' => $response->express_library_link,
                'library_link' => $response->library_link,
                'entitlement_link' => $response->express_library_link,
            ];
        }
    }

    public function createSubscriptionLibrary($subscriber) {
        $content = [
            'FullName' => $subscriber->full_name,
            'Address1' => $subscriber->address_1,
            'Address2' => $subscriber->address_2 || '',
            'City' => $subscriber->city,
            'State' => $subscriber->state,
            'ZipCode' => $subscriber->postcode,
            'Source' => 'Rolling Stone AU/NZ',
            'Channel' => 'Rolling Stone AU/NZ Website',
            'PartnerSubscriptionID' => $subscriber->uniqid,
            'PartnerConsumerID' => $subscriber->salesforce_id,
            'Email' => $subscriber->email_reciever,
            'Key' => $this->digital['api_key'],
            'ProgramKey' => $this->digital['catalogue'],
        ];
        
        $url = $this->digital['api_url'] . '/subscribe';

        $curl = new Curl\Curl();
        $curl->post($url, $content);

        if ($curl->error) {
            error_log('--eMagazines Error: ' . $curl->errorMessage);
            wp_mail('dev@thebrag.media', 'RS Mag Error: eMagazines Create Sub', "Method: " . __METHOD__ . "\n Line: " . __LINE__ . "\n Id: { $subscriber->uniqid }\n" . print_r($curl->diagnose(), true));
            return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        } else {
            $response = $curl->response;

            return [
                'latest_issue_link' => $response->latest_issue_link,
                'express_library_link' => $response->express_library_link,
                'library_link' => $response->library_link,
                'entitlement_link' => $response->express_library_link,
            ];
        }
    }

    public function cancelSubscription($subscriber) {
        $content = [
            'Key' => $this->digital['api_key'],
            'ProgramKey' => $this->digital['catalogue'],
            'Email' => $subscriber->email_reciever,
        ];

        $url = $this->digital['api_url'] . '/cancel';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($json_response = 'error') {
            error_log('--eMagazines Error: ' . $json_response);
            wp_mail('dev@thebrag.media', 'RS Mag Error: eMagazines Cancel Sub', "Method: " . __METHOD__ . "\n Line: " . __LINE__ . "\n Id: { $subscriber->uniqid }\n" . $json_response);
            return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        } else {
            $response = json_decode($json_response);
            return $response;
        }
    }

    public function issue() {
        $content = [
            'Key' => $this->digital['api_key'],
            'ProgramKey' => $this->digital['catalogue']
        ];

        $url = $this->digital['api_url'] . '/issue';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($json_response = 'error') {
            error_log('--eMagazines Error: ' . $json_response);
            wp_mail('dev@thebrag.media', 'RS Mag Error: eMagazines Cancel Sub', "Method: " . __METHOD__ . "\n Line: " . __LINE__ . "\n Id: { $subscriber->uniqid }\n" . $json_response);
            return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        } else {
            $response = json_decode($json_response);
            return $response;
        }
    }

    public function library($subscriber) {
        $content = [
            'Key' => $this->digital['api_key'],
            'Email' => $subscriber->email_reciever,
            'Format' => 'json'
        ];

        $url = $this->digital['api_url'] . '/library';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($json_response = 'error') {
            error_log('--eMagazines Error: ' . $json_response);
            wp_mail('dev@thebrag.media', 'RS Mag Error: eMagazines Library', "Method: " . __METHOD__ . "\n Line: " . __LINE__ . "\n Id: { $subscriber->uniqid }\n" . $json_response);
            return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        } else {
            $response = json_decode($json_response);
            return $response;
        }
    }

    public function reset($libraryToken, $entitlementLink) {
        $content = [
            'LibraryToken' => $libraryToken,
            'EntitlementLink' => $entitlementLink,
        ];

        $url = $this->digital['api_url'] . '/reset';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($json_response = 'error') {
            error_log('--eMagazines Error: ' . $json_response);
            wp_mail('dev@thebrag.media', 'RS Mag Error: eMagazines Cancel Sub', "Method: " . __METHOD__ . "\n Line: " . __LINE__ . "\n LibraryToken: { $libraryToken } EntitlementLink { $entitlementLink }\n" . $json_response);
            return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        } else {
            $response = json_decode($json_response);
            return $response;
        }
    }

    public function updateSubscription($subscriber) {
        $content = [
            'FullName' => $subscriber->full_name,
            'Address1' => $subscriber->address_1,
            'Address2' => $subscriber->address_2,
            'City' => $subscriber->city,
            'State' => $subscriber->state,
            'ZipCode' => $subscriber->postcode,
            'Source' => 'Rolling Stone AU/NZ',
            'Channel' => 'Rolling Stone AU/NZ Website',
            'PartnerSubscriptionID' => $subscriber->uniqid,
            'PartnerConsumerID' => $subscriber->salesforce_id,
            'Email' => $subscriber->email_reciever,
            'Key' => $this->digital['api_key'],
            'ProgramKey' => $this->digital['digital'],
        ];

        $url = $this->digital['api_url'] . '/customer';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($json_response = 'error') {
            error_log('--eMagazines Error: ' . $json_response);
            wp_mail('dev@thebrag.media', 'RS Mag Error: eMagazines Create Sub', "Method: " . __METHOD__ . "\n Line: " . __LINE__ . "\n Id: { $subscriber->uniqid }\n" . $json_response);
            return ['error' => 'Whoops, like something there was an unexpected error, please contact subscribe@thebrag.media with the details you submitted.'];
            wp_die();
        } else {
            $response = json_decode($json_response);
            return $response;
        }
    }
}