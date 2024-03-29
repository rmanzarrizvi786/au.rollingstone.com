<?php

/**
 * Plugin Name: SXSW Sydney Entries
 * Plugin URI: https://thebrag.com/media/
 * Description:
 * Version: 1.0.0
 * Author: Toby Smith
 */

class TBM_SxSw_Sydney_Entries {
    protected $plugin_name;
	protected $plugin_slug;

	public function __construct() {
		$this->plugin_name = 'tbm_sxsw_sydney_entries';
		$this->plugin_slug = 'tbm-sxsw-sydney-entries';

        add_action( 'rest_api_init', function () {
            register_rest_route( $this->plugin_name . '/v1', '/entries', array(
                'methods' => 'POST',
                'callback' => [ $this, 'sxsw_sydney_entries_2023' ]
            ) );
        } );

		add_action( 'admin_menu', [ $this, '_admin_menu' ] );
        add_action( 'admin_action_sxsw_sydney_2023_export', [ $this, 'export' ] );
	}

	public function sxsw_sydney_entries_2023($req) {
        # close off entries

        return wp_send_json_error("We're no longer taking guest list entries.");

		# do required checks

		if ( empty( $req['name'] ) )
            return wp_send_json_error('Please enter your name.');

		if ( empty( $req['email'] ) )
            return wp_send_json_error('Please enter your email.');

        if ( empty( $req['postcode'] ) )
            return wp_send_json_error('Please enter a postcode.');

        if ( empty( $req['day1'] ) && empty( $req['day2'] ) && empty( $req['day3'] ) && empty( $req['day4'] ) )
            return wp_send_json_error('Please select a day.');

        if( !filter_var( $req['email'], FILTER_VALIDATE_EMAIL ) )
            return wp_send_json_error('Please enter a valid email address.');

        if( !filter_var( $req['postcode'], FILTER_VALIDATE_INT ) )
            return wp_send_json_error('Please enter a valid postcode.');

		$name = sanitize_text_field($req['name']);
		$email = sanitize_text_field($req['email']);
		$postcode = sanitize_text_field($req['postcode']);
		$day1 = sanitize_text_field($req['day1']);
		$day2 = sanitize_text_field($req['day2']);
		$day3 = sanitize_text_field($req['day3']);
		$day4 = sanitize_text_field($req['day4']);
        $day4 = sanitize_text_field($req['iagree']);

		# add to DB

		global $wpdb;

		$wpdb->insert(
			$wpdb->prefix . 'sxsw_sydney_entries_2023',
			[
				'name' =>$name,
				'email' => $email,
				'postcode' => $postcode,
				'day1' => $day1,
				'day2' => $day2,
				'day3' => $day3,
				'day4' => $day4,
			],
			[ '%s', '%s', '%s', '%s', '%s', '%s', '%s' ]
		);

		return wp_send_json_success('Your entry has been received.');
	}

    public function _admin_menu() {
        add_menu_page(
            'SXSW Sydney 2023',
            'SXSW Sydney 2023',
            'edit_posts',
            $this->plugin_slug,
            [ $this, 'index' ],
            'dashicons-calendar',
            11
        );
    }

    public function index() {
        include_once plugin_dir_path( __FILE__ ) . 'views/entries.php';
    }

    public function export() {
        include_once plugin_dir_path( __FILE__ ) . 'views/export.php';
    }
}

new TBM_SxSw_Sydney_Entries();
