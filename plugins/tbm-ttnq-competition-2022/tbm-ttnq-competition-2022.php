<?php

/**
 * Plugin Name: TBM TTNQ Competition
 * Plugin URI: https://thebrag.com/media/
 * Description:
 * Version: 1.0.0
 * Author: Toby Smith
 * Author URI: http://tobypsmith.com
 */

class TBM_TTNQCompetition {

	protected $plugin_name;
	protected $plugin_slug;

	public function __construct() {
		$this->plugin_name = 'tbm_ttnq_competition_2022';
		$this->plugin_slug = 'tbm-ttnq-competition-2022';

		// add_action('admin_menu', [$this, '_admin_menu']);

		add_action('wp_ajax_ttnq_enter_competition_2022', [$this, 'ttnq_enter_competition_2022']);
		add_action('wp_ajax_nopriv_ttnq_enter_competition_2022', [$this, 'ttnq_enter_competition_2022']);
	}

	// public function _admin_menu() {
	// 	add_menu_page('TTNQ Competition (2022)', 'TTNQ Competition (2022)', 'edit_posts', $this->plugin_slug, [$this, 'index'], 'dashicons-palmtree', 20);
	// }

	public function ttnq_enter_competition_2022() {
      	// $post = isset($_POST) ? $_POST : [];

		# do required checks

		// if (empty($_POST['artist_name']))
		// 	wp_send_json_error('Please enter an artist name');

		// if (empty($_POST['entry']))
		// 	wp_send_json_error("Please answer in 25 words on less why you're voting for this artist.");

		# sanitise fields

		// $field_1 = sanitize_text_field($_POST['artist_name']);
		// $entry = sanitize_text_field($_POST['entry']);

		# add to DB

		// global $wpdb;

		// $wpdb->insert(
		// 	$wpdb->prefix . 'ttnq_competition_2022',
		// 	[
		// 		'user_id' => $current_user->ID,
		// 		'artist_name' => $field_1,
		// 		'entry' => $entry,
		// 	],
		// 	['%d', '%s', '%s']
		// );

		wp_send_json_success('yes');
	} // ttnq_enter_competition()
}

new TBM_TTNQCompetition();