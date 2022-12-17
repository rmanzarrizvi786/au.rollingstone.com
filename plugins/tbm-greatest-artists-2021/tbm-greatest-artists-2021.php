<?php

/**
 * Plugin Name: TBM 50 Greatest Artists 2021
 * Plugin URI: https://thebrag.media/
 * Description:
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI:
 */

class TBMGreatestArtists2021
{

  protected $plugin_name;
  protected $plugin_slug;
  protected $post_name;

  public function __construct()
  {

    $this->plugin_name = 'tbm_greatest_artists_2021';
    $this->plugin_slug = 'tbm-greatest-artists-2021';
    $this->post_name = '50-greatest-artists';

    /* AJAX */
    add_action('wp_ajax_save_artist', [$this, 'save_artist']);

    add_action('admin_menu', function () {
      // add_menu_page('50 Greatest Artists', '50 Greatest Artists', 'edit_pages', $this->plugin_slug, [$this, 'index'], 'dashicons-playlist-audio', 17);
    });

    // Export
    // add_action('admin_action_tbm_export_gmoat2020', [$this, 'export']);

    add_action('init', [$this, 'add_rewrite_tags']);
    // add_filter('rewrite_rules_array', [$this, 'rewrite_rules']);
    add_filter('page_link', [$this, 'filter_permalinks'], 10, 3);
  }

  public function add_rewrite_tags()
  {
    add_rewrite_tag('%artist%', '([^&]+)');
    add_rewrite_rule('(' . $this->post_name . ')/page/([0-9]{1,})/([^/]+)?/?$', 'index.php?post_type=page&name=' . $this->post_name . '&paged=$matches[2]&artist=$matches[3]', 'top');
  }

  public function rewrite_rules($rules)
  {
    $new_rules['(' . $this->post_name . ')/page/([0-9]{1,})/([^/]+)?/?$'] = 'index.php?post_type=page&name=' . $this->post_name . '&paged=$matches[2]&artist=$matches[3]';

    return $new_rules + $rules;
  }

  public function filter_permalinks($permalink, $post_id, $leavename = false)
  {
    $post = get_post($post_id);

    if (empty($post)) {
      return $permalink;
    }

    if ($post->post_name != $this->post_name) {
      return $permalink;
    }

    if (get_query_var('artist')) {
      // global $wp_query; echo '<pre>'; print_r( $wp_query ); echo '</pre>'; exit;
      $paged = get_query_var('paged');
      $artist = get_query_var('artist');
      return $permalink .= 'page/' . $paged . '/'; // . $artist . '/';
    }

    return $permalink;
  }

  public function index()
  {
    include_once plugin_dir_path(__FILE__) . 'views/index.php';
  }

  public function save_artist()
  {
    $post = isset($_POST) ? $_POST : [];
    if (empty($post)) {
      wp_send_json_error('Invalid request');
      wp_die();
    }

    if (!isset($post['formData'])) {
      wp_send_json_error('Invalid request, missing data');
      wp_die();
    }

    parse_str($post['formData'], $formData);

    global $wpdb;

    $check = $wpdb->get_row($wpdb->prepare("SELECT id FROM {$wpdb->prefix}greatest_artists_2021 WHERE position = %d", $formData['position']));

    $values = [
      'title' => $formData['title'],
      'slug' => sanitize_title($formData['title']),
      'description' => $formData['description'],
      'image_url' => $formData['image_url'],
      'image_credit' => $formData['image_credit'],
      'author' => $formData['author'],
      'author_image_url' => $formData['author_image_url'],
      'position' => $formData['position'],
      'author_bio' => $formData['author_bio'],
    ];

    if ($check) { // Update
      $wpdb->update(
        $wpdb->prefix . 'greatest_artists_2021',
        $values,
        ['id' => $check->id]
      );
    } else { // Insert
      $wpdb->insert(
        $wpdb->prefix . 'greatest_artists_2021',
        $values
      );
    }

    wp_send_json_success();

    wp_send_json_error('Error');
    wp_die();
  }
}

new TBMGreatestArtists2021();
