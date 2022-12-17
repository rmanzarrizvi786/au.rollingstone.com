<?php

/**
 * Handle Braze requests
 */
class Braze
{
    protected $is_sandbox;
    protected $sdk_api_key;
    protected $sdk_endpoint;
    protected $api_key;
    protected $url;

    public function __construct()
    {
        $this->is_sandbox = isset($_ENV) && isset($_ENV['ENVIRONMENT']) && 'sandbox' == $_ENV['ENVIRONMENT'];

        if ($this->is_sandbox) {
            $this->sdk_api_key = '08d1f29b-c48e-4bb3-aef3-e133789a0c89';
            $this->sdk_endpoint = 'sdk.iad-05.braze.com';

            $this->api_key = '4bdcad2b-f354-48b5-a305-7a9d77eb356e';

            $this->url = 'https://rest.iad-05.braze.com';
        } else {
            $this->sdk_api_key = '5fd1c924-ded7-46e7-b75d-1dc4831ecd92';
            $this->sdk_endpoint = 'sdk.iad-05.braze.com';
            $this->api_key = '3570732f-b2bd-4687-9b19-e2cb32f226ae';
            $this->url = 'https://rest.iad-05.braze.com';
        }
    }

    public function setMethod($method = 'GET')
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        if (isset($this->url)) {
            return $this->url;
        }
        return FALSE;
    }

    public function setPayload($payload = NULL)
    {
        $this->payload = $payload;
    }

    public function getPayload()
    {
        if (isset($this->payload)) {
            return $this->payload;
        }
        return FALSE;
    }

    public function setParams($params = array())
    {
        $this->params = $params;
    }

    public function getParams()
    {
        if (isset($this->params)) {
            return $this->params;
        }
        return false;
    }

    public function buildQuery($params)
    {
        if (!empty($params)) {
            return \http_build_query($params);
        }
        return FALSE;
    }

    public function request($urlSuffix = '')
    {
        $url = $this->getUrl();
        $url .= $urlSuffix;
        $payload = $this->getPayload();
        $method = $this->getMethod();
        $params = $this->getParams();
        $query = $this->buildQuery($params);
        if (!empty($query)) {
            $url = $url . '?' . $query;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout after 10 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (strtoupper($method) == 'POST') {
            $data = json_encode($payload);
            $headers  = [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data),
                'Authorization: Bearer ' . $this->api_key
            ];
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        curl_close($ch);

        return array(
            'response' => $result,
            'code' => $status_code,
        );
    }

    public function getUser($user_id)
    {
        global $wpdb;
        $user = get_user_by('id', $user_id);
        if (!$user)
            return false;

        $user_info = get_userdata($user_id);

        $user_attributes = [];
        $user_attributes['email'] = $user->user_email;

        $this->setMethod('POST');
        $this->setPayload([
            'email_address' => $user_info->user_email
        ]);
        $res_braze_users = $this->request('/users/export/ids');

        $has_braze_user = false;

        if (201 == $res_braze_users['code']) {
            $braze_users = json_decode($res_braze_users['response']);

            if (isset($braze_users->users[0])) {
                $braze_user = $braze_users->users[0];

                $has_braze_user = true;

                // $user_attributes['custom_attributes'] = $braze_user->custom_attributes;

                if (isset($braze_user->external_id)) {
                    $user_attributes['external_id'] = $braze_user->external_id;
                } else {
                    $user_attributes['user_alias'] = $braze_user->user_aliases[0];
                    $user_attributes['_update_existing_only'] = false;
                }

                if (isset($braze_user->first_name)) {
                    $user_attributes['first_name'] = $braze_user->first_name;
                }
                if (isset($braze_user->last_name)) {
                    $user_attributes['last_name'] = $braze_user->last_name;
                }
            }
        }

        if (!$has_braze_user) {
            if (get_user_meta($user_id, $wpdb->prefix . 'auth0_id')) {
                $user_attributes['external_id'] = get_user_meta($user_id, $wpdb->prefix . 'auth0_id', true);
            } else if (get_user_meta($user_id, 'wp_auth0_id')) {
                // If user's Auth0 ID is not set using wpdb prefix, check if set using wp_ prefix
                $user_attributes['external_id'] = get_user_meta($user_id, 'wp_auth0_id', true);
            } else {
                // User's Auth0 ID not set, set alias for user
                $user_attributes['user_alias'] = [
                    'alias_name' => $user_info->user_email,
                    'alias_label' => 'email',
                ];
                $user_attributes['_update_existing_only'] = false;
            }

            $attributes[] = array_merge($user_attributes, ['email' => $user_info->user_email]);
            $braze_payload['attributes'] = $attributes;
            $this->setPayload($braze_payload);
            $this->request('/users/track', true);

            $braze_user = $this->getUser($user_id);
        }

        return [
            'user' => isset($braze_user) ? $braze_user : null,
            'user_attributes' => $user_attributes,
        ];
    } // getUser($user_id)

    public function getUserByEmail($email)
    {
        $user = get_user_by('email', $email);
        if ($user) {
            return $this->getUser($user->ID);
        }

        $user_attributes = [];
        $user_attributes['email'] = $email;

        $this->setMethod('POST');
        $this->setPayload([
            'email_address' => $email
        ]);
        $res_braze_users = $this->request('/users/export/ids');

        $has_braze_user = false;

        if (201 == $res_braze_users['code']) {
            $braze_users = json_decode($res_braze_users['response']);

            if (isset($braze_users->users[0])) {
                $braze_user = $braze_users->users[0];

                $has_braze_user = true;

                if (isset($braze_user->external_id)) {
                    $user_attributes['external_id'] = $braze_user->external_id;
                } else {
                    $user_attributes['user_alias'] = $braze_user->user_aliases[0];
                    $user_attributes['_update_existing_only'] = false;
                }

                if (isset($braze_user->first_name)) {
                    $user_attributes['first_name'] = $braze_user->first_name;
                }
                if (isset($braze_user->last_name)) {
                    $user_attributes['last_name'] = $braze_user->last_name;
                }
            }
        }

        if (!$has_braze_user) {
            $user_attributes['user_alias'] = [
                'alias_name' => $email,
                'alias_label' => 'email',
            ];
            $user_attributes['_update_existing_only'] = false;

            $attributes[] = array_merge($user_attributes, ['email' => $email]);
            $braze_payload['attributes'] = $attributes;
            $this->setPayload($braze_payload);
            $this->request('/users/track', true);

            $braze_user = $this->getUserByEmail($email);
        }

        return [
            'user' => isset($braze_user) ? $braze_user : null,
            'user_attributes' => $user_attributes,
        ];
    } // getUserByEmail($email)

    public function triggerEventByEmail($email, $event_name, $properties = [], $attributes = [])
    {
        $braze_user = $this->getUserByEmail($email);

        $user_attributes = $braze_user['user_attributes'];

        $braze_payload = [];

        if (!empty($attributes)) {
            $braze_payload['attributes'] = [array_merge($braze_user['user_attributes'], $attributes)];
        }

        $event_payload = [
            'name' => $event_name,
            'time' => current_time('c')
        ];
        if (is_array($properties) && !empty($properties)) {
            $event_payload = array_merge($event_payload, ['properties' => $properties]);
        }
        $braze_payload['events'] = [array_merge($user_attributes, $event_payload)];

        if (!empty($braze_payload)) {
            $this->setPayload($braze_payload);
            $res_track = $this->request('/users/track', true);
            if (201 !== $res_track['code']) {
                error_log("Error pushing event to Braze in " . __METHOD__ . " on line " . __LINE__ . ". " .  print_r($res_track, true)) . print_r($braze_payload, true);
            }
            return $res_track;
        }
        return false;
    } // triggerEventByEmail($email, $event_name, $properties = [], $attributes = [])

    public function setAttributes($user_id, $attributes = [], $overwrite = true)
    {
        if (!is_array($attributes) || empty($attributes))
            return;
        $braze_user = $this->getUser($user_id);

        $attribute_keys = array_keys($attributes);

        if (!$overwrite) {
            foreach ($attribute_keys as $attribute_key) {
                $custom_attribute_name = "{$attribute_key}";
                if (isset($braze_user['user']) && !is_null($braze_user['user'])) {
                    if (isset($braze_user['user']->custom_attributes)) {
                        if (isset($braze_user['user']->custom_attributes->$custom_attribute_name)) {
                            // If array, merge with existing ones
                            if (is_array($attributes[$attribute_key])) {
                                $attributes[$attribute_key] = array_merge($braze_user['user']->custom_attributes->$custom_attribute_name, $attributes[$attribute_key]);
                            } else {
                                unset($attributes[$attribute_key]);
                            }
                        }
                    }
                }
            }
        }

        if (isset($braze_user['user_attributes'])) {
            $user_attributes = $braze_user['user_attributes'];

            $braze_payload = [];

            if (is_array($attributes) && !empty($attributes)) {
                $braze_payload['attributes'] = [array_merge($user_attributes, $attributes)];
            }

            if (!empty($braze_payload)) {
                $this->setPayload($braze_payload);
                $res_track = $this->request('/users/track', true);
                if (201 !== $res_track['code']) {
                    error_log("Error pushing attribute to Braze in " . __METHOD__ . " on line " . __LINE__ . ". " .  print_r($res_track, true)) . print_r($braze_payload, true);
                }
            }
        }
    } // setAttributes($user_id, $attributes = [], $overwrite = true)
}
