<?php

/**
 * Plugin Name: TBM SCU Scholarship 2020/21/23
 * Plugin URI: https://thebrag.media/
 * Description:
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI:
*/

class TBMScuScholarship2020 {
    protected $plugin_name;
    protected $plugin_slug;
    protected $post_name;

    public function __construct() {
        $this->plugin_name = 'scu_scholarship';
        $this->plugin_slug = 'scu-scholarship';

        // Save Vote
        // add_action('wp_ajax_save_scu_2020', [$this, 'save_scu_2020']);
        // add_action('wp_ajax_nopriv_save_scu_2020', [$this, 'save_scu_2020']);

        // add_action('wp_ajax_save_scu_2021', [$this, 'save_scu_2021']);
        // add_action('wp_ajax_nopriv_save_scu_2021', [$this, 'save_scu_2021']);

        add_action('wp_ajax_save_scu_2023', [$this, 'save_scu_2023']);
        add_action('wp_ajax_nopriv_save_scu_2023', [$this, 'save_scu_2023']);

        add_action('admin_menu', function () {
            add_menu_page('SCU Scholarship', 'SCU Scholarship', 'edit_pages', $this->plugin_slug, [$this, 'index'], 'dashicons-welcome-learn-more', 17);
            // add_submenu_page($this->plugin_slug, 'SCU Scholarship 2021', '2021 Submissions', 'edit_pages', $this->plugin_slug, [$this, 'index']);
            // add_submenu_page($this->plugin_slug, 'SCU Scholarship 2020', '2020 Submissions', 'edit_pages', $this->plugin_slug . '-2020', [$this, 'index_2020']);
            add_submenu_page($this->plugin_slug, 'SCU Scholarship 2023', '2023 Submissions', 'edit_pages', $this->plugin_slug . '-2023', [$this, 'index_2023']);
        });

        // Export
        // add_action('admin_action_tbm_export_scu_scholarship_2020', [$this, 'export_2020']);
        // add_action('admin_action_tbm_export_scu_scholarship_2021', [$this, 'export_2021']);
        add_action('admin_action_tbm_export_scu_scholarship_2023', [$this, 'export_2023']);
    }

    public function index() {
        $year = 2023;
        
        if (isset($_GET['action']) && 'export' == trim($_GET['action'])) {
            include_once plugin_dir_path(__FILE__) . 'views/export.php';
        } else {
            include_once plugin_dir_path(__FILE__) . 'views/index.php';
        }
    }

    public function index_2020() {
        $year = 2020;
        
        if (isset($_GET['action']) && 'export' == trim($_GET['action'])) {
            include_once plugin_dir_path(__FILE__) . 'views/export.php';
        } else {
            include_once plugin_dir_path(__FILE__) . 'views/index.php';
        }
    }

    public function index_2023() {
        $year = 2023;
        
        if (isset($_GET['action']) && 'export' == trim($_GET['action'])) {
            include_once plugin_dir_path(__FILE__) . 'views/export.php';
        } else {
            include_once plugin_dir_path(__FILE__) . 'views/index.php';
        }
    }

    public function save_scu_2023() {
        $this->save_scu(2023);
    }

    public function save_scu_2021() {
        $this->save_scu(2021);
    }

    public function save_scu_2020() {
        $this->save_scu(2020);
    }

    private function save_scu($year = 2021) {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            $post = isset($_POST) ? $_POST : [];
            // wp_send_json_success( [ print_r( $post, true ) ] ); die();

            $required_fields = [];

            $required_fields = array_merge($required_fields, [
                'firstname' => 'Please enter your first name',
                'lastname' => 'Please enter your last name',
                'email|email' => 'Please enter a valid email address',
                'phone|phone' => 'Please enter valid phone number',
                'postcode|postcode' => 'Please enter valid postcode',
                'current_status' => 'Please tell us what describes you best',
                'reason' => 'In 50 words or less tell us why music matters to you.'
            ]);

            foreach ($required_fields as $required_field => $message) {
                $rf = explode('|', $required_field);

                if (isset($rf[0])) {
                    if (!isset($post[$rf[0]]) || '' == trim($post[$rf[0]])) {
                        wp_send_json_error(['f' => $rf[0], 'message' => $message]);
                        die();
                    }

                    if (isset($rf[1])) {
                        if ('email' == $rf[1] && !is_email($post[$rf[0]])) {
                            wp_send_json_error(['f' => $rf[0], 'message' => $message]);
                            die();
                        } else if ('phone' == $rf[1]) {
                            if (self::validatePhone($post[$rf[0]]) === false) {
                                wp_send_json_error(['f' => $rf[0], 'message' => $message]);
                                die();
                            }
                        } else if ('postcode' == $rf[1]) {
                            if (self::validatePostcode($post[$rf[0]]) === false) {
                                wp_send_json_error(['f' => $rf[0], 'message' => $message]);
                                die();
                            }
                        }
                    }
                }
            }

            global $wpdb;

            $entry = [
                'firstname' => sanitize_text_field($post['firstname']),
                'lastname' => sanitize_text_field($post['lastname']),
                'email' => sanitize_email($post['email']),
                'phone' => sanitize_text_field($post['phone']),
                'postcode' => sanitize_text_field($post['postcode']),
                'current_status' => sanitize_text_field($post['current_status']),
                'reason' => sanitize_textarea_field($post['reason']),
            ];

            $entry_format = ['%s', '%s', '%s', '%s', '%d', '%s', '%s',];

            // Insert entry
            $wpdb->insert(
                $wpdb->prefix . "scu_scholarship_{$year}",
                $entry,
                $entry_format
            );

            wp_send_json_success('Thank you!');
            die();
        } // If requesting using AJAX
    } // save_scu_2020()

    private function export($year = 2021) {
        include_once plugin_dir_path(__FILE__) . 'views/export.php';
    }

    public function export_2023() {
        $this->export(2023);
    }

    public function export_2021() {
        $this->export(2021);
    }

    public function export_2020() {
        return $this->export(2020);
    }

    private static function validatePhone($number, $options = array(
        'strict'        => false,
        'national'      => true,
        'indial'        => true,
        'international' => true,
        'other'         => true
    )) {
        $preg = array();
        
        if (empty($options['strict'])) {
            $number = str_replace(array('(', ')', '-', ' ', '.',), '', $number);
        }

        if (!empty($options['national'])) {
            $preg[] = "(0[3478][0-9]{8})";
            $preg[] = "(02[3-9][0-9]{7})";
        }

        if (!empty($options['indial'])) {
            $preg[] = '(13[0-9]{4})';
            $preg[] = "(1[3|8|9]00[0-9]{6})";
        }

        if (!empty($options['international'])) {
            $preg[] = "(\+61[23478][0-9]{8})";
        }

        //Other numbers, like premium SMS
        if (!empty($options['other'])) {

            //Premium SMS
            $preg[] = "(19[0-9]{4,6})";

            //Universial Personal Phones
            $preg[] = "(0550[0-9]{6})"; //VOIP range (proposed)
            $preg[] = "(059[0-9]{7})";  //Enum testing numbers
            $preg[] = "(0500[0-9]{6})"; //"Find me anywhere"

            //Data access providers
            $preg[] = "(0198[0-3][0-9]{5})";
        }

        if (!empty($preg)) {
            foreach ($preg as $pattern) {
                if (preg_match("/^" . $pattern . "$/", $number)) {
                    return true;
                }
            }
        }

        return false;
    }

    private static function validatePostcode($postcode) {
        $pattern = "(?:(?:[2-8]\d|9[0-7]|0?[28]|0?9(?=09))(?:\d{2}))";

        if (preg_match("/^" . $pattern . "$/", $postcode)) {
            return true;
        }

        return false;
    }
}

new TBMScuScholarship2020();