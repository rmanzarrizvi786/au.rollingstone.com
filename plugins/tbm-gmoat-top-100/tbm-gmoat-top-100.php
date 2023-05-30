<?php

/**
 * Plugin Name: TBM GMOAT Top 100
 * Plugin URI: https://thebrag.media/
 * Description:
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI:
 */

class TBMGmoatTop100
{

  protected $plugin_name;
  protected $plugin_slug;
  protected $post_names;

  public function __construct()
  {

    $this->plugin_name = 'tbm_gmoat_top100';
    $this->plugin_slug = 'tbm-gmoat-top100';
    $this->plugin_slug_top_25 = 'tbm-gmoat-top25-2021';
    $this->post_names[] = '100-greatest-movies-of-all-time';
    $this->post_names[] = '25-greatest-movies-of-2021';

    /* AJAX */
    add_action('wp_ajax_search_movie', [$this, 'ajax_search']);
    add_action('wp_ajax_nopriv_search_movie', [$this, 'ajax_search']);

    add_action('wp_ajax_gmoat_search_movie_2021', [$this, 'ajax_search_2021']);
    add_action('wp_ajax_nopriv_gmoat_search_movie_2021', [$this, 'ajax_search_2021']);

    add_action('wp_ajax_save_final_movie', [$this, 'save_final_movie']);
    add_action('wp_ajax_save_final_movie_2021', [$this, 'save_final_movie_2021']);

    // add_action('wp_ajax_save_movie_entries', [$this, 'save_entries']);
    // add_action('wp_ajax_nopriv_save_movie_entries', [$this, 'save_entries']);

    // Save Vote
    add_action('wp_ajax_save_gmoat_vote', [$this, 'save_gmoat_vote']);
    add_action('wp_ajax_nopriv_save_gmoat_vote', [$this, 'save_gmoat_vote']);

    add_action('wp_ajax_save_gmoat_vote_2021', [$this, 'save_gmoat_vote_2021']);
    add_action('wp_ajax_nopriv_save_gmoat_vote_2021', [$this, 'save_gmoat_vote_2021']);

    // Save Comp
    add_action('wp_ajax_save_gmoat_comp', [$this, 'save_gmoat_comp']);
    add_action('wp_ajax_nopriv_save_gmoat_comp', [$this, 'save_gmoat_comp']);

    add_action('wp_ajax_save_gmoat_comp_2021', [$this, 'save_gmoat_comp_2021']);
    add_action('wp_ajax_nopriv_save_gmoat_comp_2021', [$this, 'save_gmoat_comp_2021']);

    add_action('admin_menu', function () {
      // add_menu_page('GMOAT results', 'GMOAT results', 'edit_pages', $this->plugin_slug, [$this, 'index'], 'dashicons-editor-video', 19);
      // add_submenu_page($this->plugin_slug, 'GMOAT Final 100', 'GMOAT Final 100', 'edit_posts', $this->plugin_slug . '-final-100', [$this, 'final_100']);

      // add_menu_page('GMOAT 25 results (2021)', 'GMOAT 25 results (2021)', 'edit_pages', $this->plugin_slug_top_25, [$this, 'index_2021'], 'dashicons-editor-video', 18);
      // add_submenu_page($this->plugin_slug_top_25, 'GMOAT Final 25 (2021)', 'GMOAT Final 25', 'edit_posts', $this->plugin_slug_top_25 . '-final', [$this, 'final_25_2021']);
    });

    add_filter('wpseo_opengraph_image', [$this, '_wpseo_opengraph_image']);
    add_filter('wpseo_opengraph_url', [$this, '_wpseo_opengraph_url']);

    // Export
    add_action('admin_action_tbm_export_gmoat2020', [$this, 'export']);
    add_action('admin_action_tbm_export_gmoat2021', [$this, 'export_2021']);

    /*
    add_action('wp_mail_failed', function($wp_error) {

      global $ts_mail_errors;
      global $phpmailer;

      if (!isset($ts_mail_errors)) $ts_mail_errors = array();
      if (isset($phpmailer)) {
        $ts_mail_errors[] = $phpmailer->ErrorInfo;
      }
      error_log(print_r($ts_mail_errors, true));

    }, 10, 1);
    */

    add_action('init', [$this, 'add_rewrite_tags']);
    // add_filter('rewrite_rules_array', [$this, 'rewrite_rules']);
    add_filter('page_link', [$this, 'filter_permalinks'], 10, 3);
  }

  public function add_rewrite_tags()
  {
    add_rewrite_tag('%movie%', '([^&]+)');
    foreach ($this->post_names as $post_name) {
      add_rewrite_rule('(' . $post_name . ')/page/([0-9]{1,})/([^/]+)?/?$', 'index.php?post_type=page&name=' . $post_name . '&paged=$matches[2]&movie=$matches[3]', 'top');
    }
  }

  public function rewrite_rules($rules)
  {
    foreach ($this->post_names as $post_name) {
      $new_rules['(' . $this->post_name . ')/page/([0-9]{1,})/([^/]+)?/?$'] = 'index.php?post_type=page&name=' . $post_name . '&paged=$matches[2]&movie=$matches[3]';
    }

    return $new_rules + $rules;
  }

  public function filter_permalinks($permalink, $post_id, $leavename = false)
  {
    $post = get_post($post_id);

    if (empty($post)) {
      return $permalink;
    }

    if (!in_array($post->post_nam, $this->post_names)) {
      return $permalink;
    }

    if (get_query_var('movie')) {
      // global $wp_query; echo '<pre>'; print_r( $wp_query ); echo '</pre>'; exit;
      $paged = get_query_var('paged');
      $movie = get_query_var('movie');
      return $permalink .= 'page/' . $paged . '/'; // . $movie . '/';
    }

    return $permalink;
  }

  public function _wpseo_opengraph_url($url)
  {
    if (is_page_template('page-templates/page-gmoat-vote.php') && isset($_GET['r'])) {
      return get_permalink() . '?r=' . $_GET['r'];
    }
    if (is_page_template('page-templates/page-gmoat-25-2021-vote.php') && isset($_GET['r'])) {
      return get_permalink() . '?r=' . $_GET['r'];
    }
    return $url;
  }

  public function _wpseo_opengraph_image($url)
  {
    if (
      is_page_template('page-templates/page-gmoat-vote.php') && isset($_GET['r'])
      ||
      is_page_template('page-templates/page-gmoat-25-2021-vote.php') && isset($_GET['r'])
    ) :
      global $wpdb;

      if (is_page_template('page-templates/page-gmoat-vote.php') && isset($_GET['r'])) {
        $entries_table = $wpdb->prefix . 'gmoat_entries';
        $movie_entries_table = $wpdb->prefix . 'gmoat_movie_entries';
        $movies_table = $wpdb->prefix . 'gmoat_movies';
        $img_dir = get_template_directory() . '/images/vote-100-movies/';
        $img_url = RS_THEME_URL . '/images/vote-100-movies/';

        $png_width = 850;
        $png_height = 250;
        $jpeg_width = 1200;
        $jpeg_height = 630;

        $font_size = 25;
      } else {
        $entries_table = $wpdb->prefix . 'gmoat_entries_2021';
        $movie_entries_table = $wpdb->prefix . 'gmoat_movie_entries_2021';
        $movies_table = $wpdb->prefix . 'gmoat_movies_2021';
        $img_dir = get_template_directory() . '/images/vote-25-movies-2021/';
        $img_url = RS_THEME_URL . '/images/vote-25-movies-2021/';

        $png_width = 850;
        $png_height = 850;
        $jpeg_width = 1280;
        $jpeg_height = 1280;

        $font_size = 50;
      }

      $r = '' != trim($_GET['r']) ? trim($_GET['r']) : null;
      if (is_null($r))
        return $url;

      $entry = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM {$entries_table} WHERE result_code = %s", $r)
      );

      if (!$entry)
        return $url;



      // $black = imagecolorallocate($im, 0, 0, 0);
      // $white = imagecolorallocate($im, 255, 255, 255);
      // $font = TBM_CDN . '/assets/fonts/RobotoCondensed-Light.ttf';
      $font = TBM_CDN . '/assets/fonts/Roboto-Bold.ttf';

      $text = "";

      // User entry
      /*
      $user_movie_title = '';
      if (!is_null($entry->wildcard_movie_id)) {
        $user_movie_title = $wpdb->get_var($wpdb->prepare("SELECT title FROM {$wpdb->prefix}gmoat_movies WHERE id = %d", $entry->wildcard_movie_id));
      } else if (!is_null($entry->wildcard_movie_title)) {
        $user_movie_title = $entry->wildcard_movie_title;
      }
      if ('' != $user_movie_title) {
        $text .= "√ " . $user_movie_title . "\n";
      }
      */

      require_once __DIR__ . '/vendor/autoload.php';

      $text_y = 50;

      $im = imagecreatetruecolor($png_width, $png_height);
      imagefill($im, 0, 0, 0x7fff0000);
      $box = new GDText\Box($im);
      $box->setFontFace($font);
      $box->setFontSize($font_size);
      $box->setLineHeight(1.25);
      $box->setTextAlign('left', 'top');

      // Movie entries
      $movies = $wpdb->get_results(
        $wpdb->prepare("SELECT me.movie_title AS title FROM {$movie_entries_table} me WHERE me.movie_title IS NOT NULL AND me.entry_id = %d", $entry->id)
      );
      if ($movies) {
        foreach ($movies as $movie) {
          $box->setBox(170, $text_y, $png_width - 170, $png_height - 20);
          $box->draw($movie->title);
          // $text .= "√ " . $movie->title . "\n\n";

          $text_y += 250;
        }
      }

      $movies = $wpdb->get_results(
        $wpdb->prepare("SELECT m.title FROM {$movies_table} m JOIN {$movie_entries_table} me ON m.id = me.movie_id WHERE me.movie_id IS NOT NULL AND me.entry_id = %d", $entry->id)
      );
      if ($movies) {
        foreach ($movies as $movie) {

          $box->setBox(170, $text_y, $png_width - 170, $png_height - 20);
          $box->draw($movie->title);
          // $text .= "√ " . $movie->title . "\n\n";

          $text_y += 250;
        }
      }

      // $box->setBox(20, 20, $png_width - 20, $png_height - 20);
      // $box->draw($text);


      // Write it
      // imagettftext( $im, $font_size, 0, 10, 40, $white, $font, $text);

      // $im = imagecreatetruecolor($png_width, $png_height);
      // imagefill($im, 0, 0, 0x7fff0000);

      $jpeg = imagecreatefromjpeg($img_dir . 'my-votes-social-v2.jpg');
      $out = imagecreatetruecolor($jpeg_width, $jpeg_height);
      imagecopyresampled($out, $jpeg, 0, 0, 0, 0, $jpeg_width, $jpeg_height, $jpeg_width, $jpeg_height);
      imagecopyresampled($out, $im, 175, 200, 0, 0, $png_width, $png_height, $png_width, $png_height);
      imagejpeg($out, $img_dir . 'results/' . $r . '.jpg');
      // die('<img src="' . $img_url . 'results/' . $entry->result_code . '.jpg' . '" width="500">');
      return $img_url . 'results/' . $entry->result_code . '.jpg';
    endif;
    return $url;
  }

  public function index()
  {
    if (isset($_GET['m_id']) && '' != trim($_GET['m_id'])) {
      include_once plugin_dir_path(__FILE__) . 'views/movie-entries.php';
    } else if (isset($_GET['m_title']) && '' != trim($_GET['m_title'])) {
      include_once plugin_dir_path(__FILE__) . 'views/user-entries.php';
    } else if (isset($_GET['action']) && 'export' == trim($_GET['action'])) {
      include_once plugin_dir_path(__FILE__) . 'views/export.php';
    } else {
      include_once plugin_dir_path(__FILE__) . 'views/index.php';
    }
  } // index()

  public function index_2021()
  {
    if (isset($_GET['m_id']) && '' != trim($_GET['m_id'])) {
      include_once plugin_dir_path(__FILE__) . 'views/2021/movie-entries.php';
    } else if (isset($_GET['m_title']) && '' != trim($_GET['m_title'])) {
      include_once plugin_dir_path(__FILE__) . 'views/2021/user-entries.php';
    } else if (isset($_GET['action']) && 'export' == trim($_GET['action'])) {
      include_once plugin_dir_path(__FILE__) . 'views/2021/export.php';
    } else {
      include_once plugin_dir_path(__FILE__) . 'views/2021/index.php';
    }
  } // index_2021()

  public function final_100()
  {
    include_once plugin_dir_path(__FILE__) . 'views/final-100.php';
  } // final_100()

  public function final_25_2021()
  {
    include_once plugin_dir_path(__FILE__) . 'views/2021/final-25.php';
  } // final_100_2021()

  public function ajax_search()
  {
    if (defined('DOING_AJAX') && DOING_AJAX) {

      $search = isset($_GET['search']) && '' != trim($_GET['search']) ? sanitize_text_field($_GET['search']) : NULL;

      if (is_null($search)) {
        wp_send_json_success([]);
      }

      global $wpdb;
      $query = "SELECT id, title FROM {$wpdb->prefix}gmoat_movies WHERE title LIKE '%{$search}%'";
      if (isset($_GET['excludeMovies']) && !empty($_GET['excludeMovies'])) {
        $excludeMovies = array_map('absint', $_GET['excludeMovies']);
        $query .= " AND id NOT IN ( " . implode(',', $excludeMovies) . " ) ";
      }
      $query .= "ORDER BY title LIMIT 10";

      // error_log( $query );

      $movies = $wpdb->get_results($query);

      if (!$movies)
        die();

      $return = [];

      foreach ($movies as $movie) {
        $return[] = ['id' => $movie->id,  'value' => stripslashes($movie->title)];
      }

      echo json_encode($return);
      die();

      // if ( $movies ) :
      //   wp_send_json_success( $movies );
      // else :
      //   wp_send_json_success( [ ] );
      // endif; // If $movies

    } // If requesting using AJAX
  } // ajax_search()

  public function ajax_search_2021()
  {
    if (defined('DOING_AJAX') && DOING_AJAX) {

      $search = isset($_GET['search']) && '' != trim($_GET['search']) ? sanitize_text_field($_GET['search']) : NULL;

      if (is_null($search)) {
        wp_send_json_success([]);
      }

      global $wpdb;
      $query = "SELECT id, title FROM {$wpdb->prefix}gmoat_movies_2021 WHERE title LIKE '%{$search}%'";
      if (isset($_GET['excludeMovies']) && !empty($_GET['excludeMovies'])) {
        $excludeMovies = array_map('absint', $_GET['excludeMovies']);
        $query .= " AND id NOT IN ( " . implode(',', $excludeMovies) . " ) ";
      }
      $query .= "ORDER BY title LIMIT 10";

      $movies = $wpdb->get_results($query);

      if (!$movies)
        die();

      $return = [];

      foreach ($movies as $movie) {
        $return[] = ['id' => $movie->id,  'value' => stripslashes($movie->title)];
      }

      echo json_encode($return);
      die();
    } // If requesting using AJAX
  } // ajax_search_2021()

  public function save_gmoat_vote()
  {
    if (defined('DOING_AJAX') && DOING_AJAX) {

      $post = isset($_POST) ? $_POST : [];
      // wp_send_json_error( [ print_r( $post, true ) ] );

      $count_movies = 0;
      $movie_ids = [];
      $movies = [];

      if (isset($post['select-movies-id']) && !empty($post['select-movies-id'])) {
        foreach ($post['select-movies-id'] as $select_movie_id) {
          if (is_numeric($select_movie_id) && '' != trim($select_movie_id)) {
            $count_movies++;
            $movie_ids[] = $select_movie_id;
          }
        }
      }

      if (isset($post['select-movies']) && !empty($post['select-movies'])) {
        foreach ($post['select-movies'] as $select_movie) {
          if ('' != trim($select_movie)) {
            $count_movies++;
            $movies[] = $select_movie;
          }
        }
      }

      if ($count_movies < 3) {
        wp_send_json_error(['Please complete all fields']);
      }

      $required_fields = [];

      $required_fields = array_merge($required_fields, [
        'user_fullname' => 'Please enter your name',
        'user_email|email' => 'Please enter a valid email address',
      ]);

      foreach ($required_fields as $required_field => $message) {
        $rf = explode('|', $required_field);
        if (isset($rf[0])) {
          if (!isset($post[$rf[0]]) || '' == trim($post[$rf[0]])) {
            wp_send_json_error($message);
          }
          if (isset($rf[1])) {
            if ('email' == $rf[1] && !is_email($post[$rf[0]])) {
              wp_send_json_error($message);
            }
          }
        }
      }

      global $wpdb;

      $entered_comp = false;

      // Check if user has already voted
      $check_query = $wpdb->prepare("SELECT id, result_code, entered_comp FROM {$wpdb->prefix}gmoat_entries WHERE user_email = %s LIMIT 1", sanitize_email($post['user_email']));
      $check = $wpdb->get_row($check_query);
      if ($check) { // If already voted
        // wp_send_json_success($check->result_code);
        $result_code = $check->result_code;
        if ('yes' == $check->entered_comp) {
          $entered_comp = true;
        }
      } else { // Not already voted
        do {
          $result_code = substr(md5(uniqid(rand(0, 10000), true)), 0, 8);
        } while (!$this->check_unique_result_code($result_code));

        $entry = [
          'user_fullname' => sanitize_text_field($post['user_fullname']),
          'user_email' => sanitize_email($post['user_email']),
          'result_code' => $result_code,
        ];
        $entry_format = ['%s', '%s', '%s'];

        // Insert entry
        $wpdb->insert(
          $wpdb->prefix . 'gmoat_entries',
          $entry,
          $entry_format
        );
        $entry_id = $wpdb->insert_id;

        // Insert movie entries
        if (count($movie_ids) > 0) {
          foreach ($movie_ids as $movie_id) {
            $wpdb->insert(
              $wpdb->prefix . 'gmoat_movie_entries',
              [
                'entry_id' => $entry_id,
                'movie_id' => absint($movie_id)
              ],
              ['%d', '%d']
            );
          }
        }
        if (count($movies) > 0) {
          foreach ($movies as $movie) {

            $check_existing_movie = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}gmoat_movies WHERE title = %s", $movie));
            if ($check_existing_movie && !in_array($check_existing_movie, $movie_ids)) {
              $wpdb->insert(
                $wpdb->prefix . 'gmoat_movie_entries',
                [
                  'entry_id' => $entry_id,
                  'movie_id' => $check_existing_movie
                ],
                ['%d', '%d']
              );
            } else {
              $wpdb->insert(
                $wpdb->prefix . 'gmoat_movie_entries',
                [
                  'entry_id' => $entry_id,
                  'movie_title' => $movie
                ],
                ['%d', '%s']
              );
            }
          }
        }
      } // If already voted

      $referrer = wp_get_referer() ?: home_url('/vote-for-100-top-movies/');
      $share_url = add_query_arg('r', $result_code, $referrer);

      // wp_send_json_success(urlencode($share_url));
      wp_send_json_success(['id' => $result_code, 'share_url' => $share_url, 'entered_comp' => $entered_comp]);
      die();
    } // If requesting using AJAX

  } // save_gmoat_vote()

  public function save_gmoat_vote_2021()
  {
    if (defined('DOING_AJAX') && DOING_AJAX) {

      $post = isset($_POST) ? $_POST : [];

      $count_movies = 0;
      $movie_ids = [];
      $movies = [];

      // wp_send_json_error('<pre>' . print_r($post, true) . '</pre>');

      if (isset($post['select-movies-id']) && !empty($post['select-movies-id'])) {
        foreach ($post['select-movies-id'] as $select_movie_id) {
          if (is_numeric($select_movie_id) && absint($select_movie_id) > 0) {
            $count_movies++;
            $movie_ids[] = $select_movie_id;
          }
        }
      }

      if (isset($post['select-movies']) && !empty($post['select-movies'])) {
        foreach ($post['select-movies'] as $select_movie) {
          if ('' != trim($select_movie)) {
            $count_movies++;
            $movies[] = $select_movie;
          }
        }
      }

      if ($count_movies < 3) {
        wp_send_json_error(['Please complete all fields']);
      }

      $required_fields = [];

      $required_fields = array_merge($required_fields, [
        'user_fullname' => 'Please enter your name',
        'user_email|email' => 'Please enter a valid email address',
      ]);

      foreach ($required_fields as $required_field => $message) {
        $rf = explode('|', $required_field);
        if (isset($rf[0])) {
          if (!isset($post[$rf[0]]) || '' == trim($post[$rf[0]])) {
            wp_send_json_error($message);
          }
          if (isset($rf[1])) {
            if ('email' == $rf[1] && !is_email($post[$rf[0]])) {
              wp_send_json_error($message);
            }
          }
        }
      }

      global $wpdb;

      $entered_comp = false;

      // Check if user has already voted
      $check_query = $wpdb->prepare("SELECT id, result_code, entered_comp FROM {$wpdb->prefix}gmoat_entries_2021 WHERE user_email = %s LIMIT 1", sanitize_email($post['user_email']));
      $check = $wpdb->get_row($check_query);
      if ($check) { // If already voted
        // wp_send_json_success($check->result_code);
        $result_code = $check->result_code;
        if ('yes' == $check->entered_comp) {
          $entered_comp = true;
        }
      } else { // Not already voted
        do {
          $result_code = substr(md5(uniqid(rand(0, 10000), true)), 0, 8);
        } while (!$this->check_unique_result_code($result_code));

        $entry = [
          'user_fullname' => sanitize_text_field($post['user_fullname']),
          'user_email' => sanitize_email($post['user_email']),
          'result_code' => $result_code,
        ];
        $entry_format = ['%s', '%s', '%s'];

        // Insert entry
        $wpdb->insert(
          $wpdb->prefix . 'gmoat_entries_2021',
          $entry,
          $entry_format
        );
        $entry_id = $wpdb->insert_id;

        // Insert movie entries
        if (count($movie_ids) > 0) {
          foreach ($movie_ids as $movie_id) {
            $wpdb->insert(
              $wpdb->prefix . 'gmoat_movie_entries_2021',
              [
                'entry_id' => $entry_id,
                'movie_id' => absint($movie_id)
              ],
              ['%d', '%d']
            );
          }
        }
        if (count($movies) > 0) {
          foreach ($movies as $movie) {

            $check_existing_movie = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}gmoat_movies WHERE title = %s", $movie));
            if ($check_existing_movie && !in_array($check_existing_movie, $movie_ids)) {
              $wpdb->insert(
                $wpdb->prefix . 'gmoat_movie_entries_2021',
                [
                  'entry_id' => $entry_id,
                  'movie_id' => $check_existing_movie
                ],
                ['%d', '%d']
              );
            } else {
              $wpdb->insert(
                $wpdb->prefix . 'gmoat_movie_entries_2021',
                [
                  'entry_id' => $entry_id,
                  'movie_title' => $movie
                ],
                ['%d', '%s']
              );
            }
          }
        }
      } // If already voted

      $referrer = wp_get_referer() ?: home_url('/vote-for-100-top-movies/');
      $share_url = add_query_arg('r', $result_code, $referrer);

      // wp_send_json_success(urlencode($share_url));
      wp_send_json_success(['id' => $result_code, 'share_url' => $share_url, 'entered_comp' => $entered_comp]);
      die();
    } // If requesting using AJAX

  } // save_gmoat_vote_2021()

  public function save_gmoat_comp()
  {
    if (defined('DOING_AJAX') && DOING_AJAX) {

      $post = isset($_POST) ? $_POST : [];
      // wp_send_json_error( [ print_r( $post, true ) ] );

      global $wpdb;

      // Check if user has already voted
      $result_code = trim($post['entry_id']);
      $check_entry = $wpdb->get_row($wpdb->prepare("SELECT id, user_fullname, user_email, entered_comp FROM {$wpdb->prefix}gmoat_entries WHERE result_code = %s LIMIT 1", $result_code));
      if (!$check_entry) {
        wp_send_json_error('Error!');
      }

      if (!is_null($check_entry->entered_comp)) {
        wp_send_json_error('You have already enetered competition, thank you!');
      }

      $entry_id = $check_entry->id;

      $required_fields = [];

      if (
        (!isset($post['user_entry_id']) && !isset($post['user_entry']))
        ||
        (isset($post['user_entry_id']) && '' != trim($post['user_entry_id']) && !is_numeric($post['user_entry_id']))
        ||
        (isset($post['user_entry']) && '' == trim($post['user_entry']))
      ) {
        wp_send_json_error(['Please provide wildcard entry']);
      }

      $required_fields = array_merge($required_fields, [
        'reason' => 'Please tell us why you chose your wildcard vote in 25 words or less',
      ]);

      foreach ($required_fields as $required_field => $message) {
        $rf = explode('|', $required_field);
        if (isset($rf[0])) {
          if (!isset($post[$rf[0]]) || '' == trim($post[$rf[0]])) {
            wp_send_json_error($message);
          }
          if (isset($rf[1])) {
            if ('email' == $rf[1] && !is_email($post[$rf[0]])) {
              wp_send_json_error($message);
            }
          }
        }
      }

      if (!isset($post['consent_observer']) || 'yes' != $post['consent_observer']) {
        wp_send_json_error(['You need to agree to be signed up to The Film & TV Observer.']);
      }

      if (!isset($post['consent_aheda']) || 'yes' != $post['consent_aheda']) {
        wp_send_json_error(['You need to agree to be added to AHEDA\'s mailing list.']);
      }

      $entry['entered_comp'] = 'yes';
      $entry_format[] = '%s';

      if (isset($post['user_entry']) && '' != trim($post['user_entry'])) {
        $entry['wildcard_movie_title'] = $post['user_entry'];
        $entry_format[] = '%s';
      } elseif (isset($post['user_entry_id']) && $post['user_entry_id'] > 0) {
        $entry['wildcard_movie_id'] = $post['user_entry_id'];
        $entry_format[] = '%d';
      }

      if (isset($post['consent_observer'])) {
        $entry['consent_observer'] = 'yes';
        $entry_format[] = '%s';
      }

      if (isset($post['consent_aheda'])) {
        $entry['consent_aheda'] = 'yes';
        $entry_format[] = '%s';
      }

      $wpdb->update(
        $wpdb->prefix . 'gmoat_entries',
        $entry,
        ['id' => $entry_id],
        $entry_format
      );

      // Send email {{
      $to = $check_entry->user_email;
      $subject = 'Thank you for Voting!';

      ob_start();

      $logo_url = get_template_directory_uri() . '/images/vote-100-movies/header-email.jpg';
      $logo_width = '560';

      include(get_template_directory() . '/email-templates/header.php');

      $bm_social_links = array(
        'facebook' => 'https://www.facebook.com/rollingstoneaustralia/',
        'twitter' => 'https://twitter.com/rollingstoneaus',
        'instagram' => 'https://instagram.com/rollingstoneaus',
        'link' => 'https://au.rollingstone.com/',
      );
?>
      <p style="font-size: 16px; line-height: 24px; margin-bottom: 10px;">Hi <?php echo $check_entry->user_fullname; ?>,
      <p style="font-size: 16px; line-height: 24px; margin-bottom: 10px;">Thanks for voting in <em>Rolling Stone's 100 Great Movies Of All Time!</em></p>
      <p style="font-size: 16px; line-height: 24px; margin-bottom: 10px;">We're super grateful to have your input. Your votes have been counted and you're in the running to win the prize.</p>
      <table align="center" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td align="center" valign="top">
              <!--[if mso]>
                <table align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                <![endif]-->
              <!--[if mso]>
                <td align="center" valign="top">
                <![endif]-->
              <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                <tbody>
                  <tr>
                    <td valign="top" style="padding-right:5px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                        <tbody>
                          <tr>
                            <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                              <table align="left" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                  <tr>
                                    <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                      <a href="<?php echo esc_url($bm_social_links['facebook']); ?>" target="_blank"><img alt="Facebook" src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-facebook-48.png" style="display:block;" height="24" width="24" class=""></a>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!--[if mso]>
              </td>
              <![endif]-->
              <!--[if mso]>
              <td align="center" valign="top">
              <![endif]-->
              <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                <tbody>
                  <tr>
                    <td valign="top" style="padding-right:5px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                        <tbody>
                          <tr>
                            <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                              <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                <tbody>
                                  <tr>
                                    <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                      <a href="<?php echo esc_url($bm_social_links['twitter']); ?>" target="_blank"><img alt="Twitter" src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-twitter-48.png" style="display:block;" height="24" width="24" class=""></a>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!--[if mso]>
            </td>
            <![endif]-->
              <!--[if mso]>
            <td align="center" valign="top">
            <![endif]-->
              <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                <tbody>
                  <tr>
                    <td valign="top" style="padding-right:5px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                        <tbody>
                          <tr>
                            <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                              <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                <tbody>
                                  <tr>
                                    <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                      <a href="<?php echo esc_url($bm_social_links['instagram']); ?>" target="_blank"><img alt="Instagram" src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-instagram-48.png" style="display:block;" height="24" width="24" class=""></a>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!--[if mso]>
          </td>
          <![endif]-->

              <!--[if mso]>
        </tr>
        </table>
        <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    <?php
      include(get_template_directory() . '/email-templates/footer.php');

      $email_body = ob_get_contents();
      ob_end_clean();

      $headers[] = 'Content-Type: text/html; charset=UTF-8';
      $headers[] = 'From: Rolling Stone Australia <noreply@thebrag.media>';

      wp_mail($to, $subject, $email_body, $headers);
      // }} Send email

      // Subscribe to Film & TV Observer on thebrag.com
      require_once WP_PLUGIN_DIR . '/brag-observer/brag-observer.php';
      $bo = new BragObserver();
      $bo->ajax_subscribe_observer(
        [
          'list' => 16, // 16 = List ID of Film & TV Observer
          'email' => sanitize_email($to),
          'source' => 'GMOAT Top 100',
        ],
        false
      );

      wp_send_json_success();
    } // If requesting using AJAX

  } // save_gmoat_comp()

  public function save_gmoat_comp_2021()
  {
    if (defined('DOING_AJAX') && DOING_AJAX) {

      $post = isset($_POST) ? $_POST : [];
      // wp_send_json_error([print_r($post, true)]);

      global $wpdb;

      // Check if user has already voted
      $result_code = trim($post['entry_id']);
      $check_entry = $wpdb->get_row($wpdb->prepare("SELECT id, user_fullname, user_email, entered_comp FROM {$wpdb->prefix}gmoat_entries_2021 WHERE result_code = %s LIMIT 1", $result_code));
      if (!$check_entry) {
        wp_send_json_error('Error!');
      }

      if (!is_null($check_entry->entered_comp)) {
        wp_send_json_error('You have already enetered competition, thank you!');
      }

      $entry_id = $check_entry->id;

      $required_fields = [];

      /* if (
        (!isset($post['user_entry_id']) && !isset($post['user_entry']))
        ||
        (isset($post['user_entry_id']) && '' != trim($post['user_entry_id']) && !is_numeric($post['user_entry_id']))
        ||
        (isset($post['user_entry']) && '' == trim($post['user_entry']))
      ) {
        wp_send_json_error(['Please provide wildcard entry']);
      } */

      $required_fields = array_merge($required_fields, [
        'reason' => 'Please tell us why you chose your wildcard vote in 25 words or less',
      ]);

      foreach ($required_fields as $required_field => $message) {
        $rf = explode('|', $required_field);
        if (isset($rf[0])) {
          if (!isset($post[$rf[0]]) || '' == trim($post[$rf[0]])) {
            wp_send_json_error($message);
          }
          if (isset($rf[1])) {
            if ('email' == $rf[1] && !is_email($post[$rf[0]])) {
              wp_send_json_error($message);
            }
          }
        }
      }

      if (!isset($post['consent_observer']) || 'yes' != $post['consent_observer']) {
        wp_send_json_error(['You need to agree to be signed up to The Film & TV Observer.']);
      }

      if (!isset($post['consent_aheda']) || 'yes' != $post['consent_aheda']) {
        wp_send_json_error(['You need to agree to be added to AHEDA\'s mailing list.']);
      }

      $entry = [
        'entered_comp' => 'yes',
        'reason' => sanitize_textarea_field($post['reason']),
      ];
      $entry_format[] = '%s';
      $entry_format[] = '%s';

      /* if (isset($post['user_entry']) && '' != trim($post['user_entry'])) {
        $entry['wildcard_movie_title'] = $post['user_entry'];
        $entry_format[] = '%s';
      } elseif (isset($post['user_entry_id']) && $post['user_entry_id'] > 0) {
        $entry['wildcard_movie_id'] = $post['user_entry_id'];
        $entry_format[] = '%d';
      } */

      if (isset($post['consent_observer'])) {
        $entry['consent_observer'] = 'yes';
        $entry_format[] = '%s';
      }

      if (isset($post['consent_aheda'])) {
        $entry['consent_aheda'] = 'yes';
        $entry_format[] = '%s';
      }

      $wpdb->update(
        $wpdb->prefix . 'gmoat_entries_2021',
        $entry,
        ['id' => $entry_id],
        $entry_format
      );

      // Send email {{
      $to = $check_entry->user_email;
      $subject = 'Thank you for Voting!';

      ob_start();

      $logo_url = get_template_directory_uri() . '/images/vote-25-movies-2021/AHEDATitle.png';
      $logo_width = '460';

      include(get_template_directory() . '/email-templates/header.php');

      $bm_social_links = array(
        'facebook' => 'https://www.facebook.com/rollingstoneaustralia/',
        'twitter' => 'https://twitter.com/rollingstoneaus',
        'instagram' => 'https://instagram.com/rollingstoneaus',
        'link' => 'https://au.rollingstone.com/',
      );
    ?>
      <p style="font-size: 16px; line-height: 24px; margin-bottom: 10px;">Hi <?php echo $check_entry->user_fullname; ?>,
      <p style="font-size: 16px; line-height: 24px; margin-bottom: 10px;">Thanks for voting in <em>25 Greatest Movies of 2021!</em></p>
      <p style="font-size: 16px; line-height: 24px; margin-bottom: 10px;">We're super grateful to have your input. Your votes have been counted and you're in the running to win the prize.</p>
      <table align="center" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td align="center" valign="top">
              <!--[if mso]>
                <table align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                <![endif]-->
              <!--[if mso]>
                <td align="center" valign="top">
                <![endif]-->
              <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                <tbody>
                  <tr>
                    <td valign="top" style="padding-right:5px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                        <tbody>
                          <tr>
                            <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                              <table align="left" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                  <tr>
                                    <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                      <a href="<?php echo esc_url($bm_social_links['facebook']); ?>" target="_blank"><img alt="Facebook" src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-facebook-48.png" style="display:block;" height="24" width="24" class=""></a>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!--[if mso]>
              </td>
              <![endif]-->
              <!--[if mso]>
              <td align="center" valign="top">
              <![endif]-->
              <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                <tbody>
                  <tr>
                    <td valign="top" style="padding-right:5px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                        <tbody>
                          <tr>
                            <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                              <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                <tbody>
                                  <tr>
                                    <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                      <a href="<?php echo esc_url($bm_social_links['twitter']); ?>" target="_blank"><img alt="Twitter" src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-twitter-48.png" style="display:block;" height="24" width="24" class=""></a>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!--[if mso]>
            </td>
            <![endif]-->
              <!--[if mso]>
            <td align="center" valign="top">
            <![endif]-->
              <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                <tbody>
                  <tr>
                    <td valign="top" style="padding-right:5px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                        <tbody>
                          <tr>
                            <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                              <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                <tbody>
                                  <tr>
                                    <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                      <a href="<?php echo esc_url($bm_social_links['instagram']); ?>" target="_blank"><img alt="Instagram" src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-instagram-48.png" style="display:block;" height="24" width="24" class=""></a>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!--[if mso]>
          </td>
          <![endif]-->

              <!--[if mso]>
        </tr>
        </table>
        <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    <?php
      include(get_template_directory() . '/email-templates/footer.php');

      $email_body = ob_get_contents();
      ob_end_clean();

      $headers[] = 'Content-Type: text/html; charset=UTF-8';
      $headers[] = 'From: Rolling Stone Australia <noreply@thebrag.media>';

      wp_mail($to, $subject, $email_body, $headers);
      // }} Send email

      // Subscribe to Film & TV Observer on thebrag.com
      require_once WP_PLUGIN_DIR . '/brag-observer/brag-observer.php';
      $bo = new BragObserver();
      $bo->ajax_subscribe_observer(
        [
          'list' => 16, // 16 = List ID of Film & TV Observer
          'email' => sanitize_email($to),
          'source' => 'GMOAT Top 25 (2021)',
        ],
        false
      );

      wp_send_json_success();
    } // If requesting using AJAX

  } // save_gmoat_comp_2021()

  /*
  * Save Entries - V0
  */
  public function save_entries()
  {

    if (defined('DOING_AJAX') && DOING_AJAX) {

      $post = isset($_POST) ? $_POST : [];

      // wp_send_json_error( [ print_r( $post, true ) ] );

      $count_movies = 0;
      $movie_ids = [];
      $movies = [];

      if (isset($post['select-movies-id']) && !empty($post['select-movies-id'])) {
        foreach ($post['select-movies-id'] as $select_movie_id) {
          if (is_numeric($select_movie_id) && '' != trim($select_movie_id)) {
            $count_movies++;
            $movie_ids[] = $select_movie_id;
          }
        }
      }

      if (isset($post['select-movies']) && !empty($post['select-movies'])) {
        foreach ($post['select-movies'] as $select_movie) {
          if ('' != trim($select_movie)) {
            $count_movies++;
            $movies[] = $select_movie;
          }
        }
      }

      if ($count_movies < 3) {
        wp_send_json_error(['Please complete all fields']);
      }


      // $movies = isset( $post['movies'] ) && ! in_array( strtolower( trim( $post['movies'] ) ), [ '', 'null', ] ) && ! is_null( $post['movies'] ) ? explode( ',', $post['movies'] ) : [];
      // if ( count( $movies ) !== 3  ) {
      //   wp_send_json_error( [ 'Please select 3 movies from the list' ] );
      // }

      $required_fields = [];

      if (
        (!isset($post['user_entry_id']) && !isset($post['user_entry']))
        ||
        (isset($post['user_entry_id']) && '' != trim($post['user_entry_id']) && !is_numeric($post['user_entry_id']))
        ||
        (isset($post['user_entry']) && '' == trim($post['user_entry']))
      ) {
        wp_send_json_error(['Please provide wildcard entry']);
      }

      $required_fields = array_merge($required_fields, [
        // 'reason' => 'Please tell us why you chose your wildcard vote in 25 words or less',
        'user_fullname' => 'Please enter your name',
        'user_email|email' => 'Please enter a valid email address',
      ]);

      foreach ($required_fields as $required_field => $message) {
        $rf = explode('|', $required_field);
        if (isset($rf[0])) {
          if (!isset($post[$rf[0]]) || '' == trim($post[$rf[0]])) {
            wp_send_json_error($message);
          }
          if (isset($rf[1])) {
            if ('email' == $rf[1] && !is_email($post[$rf[0]])) {
              wp_send_json_error($message);
            }
          }
        }
      }

      /*
      if (!isset($post['consent_observer']) || 'yes' != $post['consent_observer']) {
        wp_send_json_error(['You need to agree to be signed up to The Film & TV Observer.']);
      }

      if (!isset($post['consent_aheda']) || 'yes' != $post['consent_aheda']) {
        wp_send_json_error(['You need to agree to be added to AHEDA\'s mailing list.']);
      }
      */

      global $wpdb;

      // Check if user has already voted
      $check_query = $wpdb->prepare("SELECT id FROM {$wpdb->prefix}gmoat_entries WHERE user_email = %s LIMIT 1", sanitize_email($post['user_email']));
      $check = $wpdb->get_row($check_query);
      if ($check) {
        wp_send_json_error('You have already voted, thank you!');
      }

      $entry = [
        'user_fullname' => sanitize_text_field($post['user_fullname']),
        'user_email' => sanitize_email($post['user_email']),
        'reason' => sanitize_textarea_field($post['reason']),
      ];
      $entry_format = ['%s', '%s', '%s'];

      if (isset($post['user_entry']) && '' != trim($post['user_entry'])) {
        $entry['wildcard_movie_title'] = $post['user_entry'];
        $entry_format[] = '%s';
      } elseif (isset($post['user_entry_id']) && $post['user_entry_id'] > 0) {
        $entry['wildcard_movie_id'] = $post['user_entry_id'];
        $entry_format[] = '%d';
      }

      if (isset($post['consent_observer'])) {
        $entry['consent_observer'] = 'yes';
        $entry_format[] = '%s';
      }

      if (isset($post['consent_aheda'])) {
        $entry['consent_aheda'] = 'yes';
        $entry_format[] = '%s';
      }

      // Insert entry
      $wpdb->insert(
        $wpdb->prefix . 'gmoat_entries',
        $entry,
        $entry_format
      );
      $entry_id = $wpdb->insert_id;

      do {
        $result_code = substr(md5(uniqid($entry_id, true)), 0, 8);
      } while (!$this->check_unique_result_code($result_code));

      $wpdb->update(
        $wpdb->prefix . 'gmoat_entries',
        ['result_code' => $result_code,],
        ['id' => $entry_id]
      );

      // Insert movie entries
      if (count($movie_ids) > 0) {
        foreach ($movie_ids as $movie_id) {
          $wpdb->insert(
            $wpdb->prefix . 'gmoat_movie_entries',
            [
              'entry_id' => $entry_id,
              'movie_id' => absint($movie_id)
            ],
            ['%d', '%d']
          );
        }
      }
      if (count($movies) > 0) {
        foreach ($movies as $movie) {

          $check_existing_movie = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}gmoat_movies WHERE title = %s", $movie));
          if ($check_existing_movie && !in_array($check_existing_movie, $movie_ids)) {
            $wpdb->insert(
              $wpdb->prefix . 'gmoat_movie_entries',
              [
                'entry_id' => $entry_id,
                'movie_id' => $check_existing_movie
              ],
              ['%d', '%d']
            );
          } else {
            $wpdb->insert(
              $wpdb->prefix . 'gmoat_movie_entries',
              [
                'entry_id' => $entry_id,
                'movie_title' => $movie
              ],
              ['%d', '%s']
            );
          }
        }
      }

      // Insert wildcard entry
      /*
      if ( isset( $post['user_entry'] ) && '' != trim( $post['user_entry'] ) ) {
        $wpdb->insert(
          $wpdb->prefix . 'gmoat_user_movies',
          [
            'entry_id' => $entry_id,
            'movie_title' => sanitize_text_field( $post['user_entry'] ),
          ],
          [ '%d', '%s', ]
        );
      } elseif ( isset( $post['user_entry_movie'] ) && $post['user_entry'] > 0 ) {

      }
      */

      // Send email {{
      $to = $post['user_email'];
      $subject = 'Thank you for Voting!';

      ob_start();

      $logo_url = get_template_directory_uri() . '/images/vote-100-movies/header-email.jpg';
      $logo_width = '560';

      include(get_template_directory() . '/email-templates/header.php');

      $bm_social_links = array(
        'facebook' => 'https://www.facebook.com/rollingstoneaustralia/',
        'twitter' => 'https://twitter.com/rollingstoneaus',
        'instagram' => 'https://instagram.com/rollingstoneaus',
        'link' => 'https://au.rollingstone.com/',
      );
    ?>
      <p style="font-size: 16px; line-height: 24px; margin-bottom: 10px;">Hi <?php echo $post['user_fullname']; ?>,
      <p style="font-size: 16px; line-height: 24px; margin-bottom: 10px;">Thanks for voting in <em>Rolling Stone's 100 Great Movies Of All Time!</em></p>
      <p style="font-size: 16px; line-height: 24px; margin-bottom: 10px;">We're super grateful to have your input. Your votes have been counted and you're in the running to win the prize.</p>
      <table align="center" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td align="center" valign="top">
              <!--[if mso]>
              <table align="center" border="0" cellspacing="0" cellpadding="0">
              <tr>
              <![endif]-->
              <!--[if mso]>
              <td align="center" valign="top">
              <![endif]-->
              <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                <tbody>
                  <tr>
                    <td valign="top" style="padding-right:5px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                        <tbody>
                          <tr>
                            <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                              <table align="left" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                  <tr>
                                    <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                      <a href="<?php echo esc_url($bm_social_links['facebook']); ?>" target="_blank"><img alt="Facebook" src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-facebook-48.png" style="display:block;" height="24" width="24" class=""></a>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!--[if mso]>
            </td>
            <![endif]-->
              <!--[if mso]>
            <td align="center" valign="top">
            <![endif]-->
              <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                <tbody>
                  <tr>
                    <td valign="top" style="padding-right:5px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                        <tbody>
                          <tr>
                            <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                              <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                <tbody>
                                  <tr>
                                    <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                      <a href="<?php echo esc_url($bm_social_links['twitter']); ?>" target="_blank"><img alt="Twitter" src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-twitter-48.png" style="display:block;" height="24" width="24" class=""></a>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!--[if mso]>
          </td>
          <![endif]-->
              <!--[if mso]>
          <td align="center" valign="top">
          <![endif]-->
              <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                <tbody>
                  <tr>
                    <td valign="top" style="padding-right:5px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                        <tbody>
                          <tr>
                            <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                              <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                <tbody>
                                  <tr>
                                    <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                      <a href="<?php echo esc_url($bm_social_links['instagram']); ?>" target="_blank"><img alt="Instagram" src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-instagram-48.png" style="display:block;" height="24" width="24" class=""></a>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!--[if mso]>
        </td>
        <![endif]-->

              <!--[if mso]>
      </tr>
      </table>
      <![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
<?php
      include(get_template_directory() . '/email-templates/footer.php');

      $email_body = ob_get_contents();
      ob_end_clean();

      $headers[] = 'Content-Type: text/html; charset=UTF-8';
      $headers[] = 'From: Rolling Stone Australia <noreply@thebrag.media>';

      wp_mail($to, $subject, $email_body, $headers);
      // }} Send email

      // Subscribe to Film & TV Observer on thebrag.com
      require_once WP_PLUGIN_DIR . '/brag-observer/brag-observer.php';
      $bo = new BragObserver();
      $bo->ajax_subscribe_observer(
        [
          'list' => 16, // 16 = List ID of Film & TV Observer
          'email' => sanitize_email($post['user_email']),
          'source' => 'GMOAT Top 100',
        ],
        false
      );

      $referrer = wp_get_referer() ?: home_url('/100-greatest-movies-of-all-time/');
      $share_url = add_query_arg('r', $result_code, $referrer);

      wp_send_json_success(urlencode($share_url));
    } // If requesting using AJAX
  } // save_entries()

  public function save_final_movie()
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

    $errors = [];

    parse_str($post['formData'], $formData);

    $required_fields = [
      'image_url',
      'title',
      'description',
      'position',
    ];

    foreach ($required_fields as $required_field) {
      if (!isset($formData[$required_field]) || '' == trim($formData[$required_field])) {
        // $errors[] = ucfirst(str_replace('_', ' ', $required_field)) . ' is required.';
      }
    }

    if (isset($formData['link_jbhifi_bluray']) && '' != trim($formData['link_jbhifi_bluray'])) {
      if (filter_var($formData['link_jbhifi_bluray'], FILTER_VALIDATE_URL) === FALSE) {
        $errors[] = 'JB Hi-Fi URL (Blu ray) is invalid.';
      }
    }

    if (isset($formData['link_amazon_bluray']) && '' != trim($formData['link_amazon_bluray'])) {
      if (filter_var($formData['link_amazon_bluray'], FILTER_VALIDATE_URL) === FALSE) {
        $errors[] = 'Amazon URL (Blu ray) is invalid.';
      }
    }

    if (isset($formData['link_sanity_bluray']) && '' != trim($formData['link_sanity_bluray'])) {
      if (filter_var($formData['link_sanity_bluray'], FILTER_VALIDATE_URL) === FALSE) {
        $errors[] = 'Sanity URL (Blu ray) is invalid.';
      }
    }


    if (isset($formData['link_jbhifi_dvd']) && '' != trim($formData['link_jbhifi_dvd'])) {
      if (filter_var($formData['link_jbhifi_dvd'], FILTER_VALIDATE_URL) === FALSE) {
        $errors[] = 'JB Hi-Fi URL (DVD) is invalid.';
      }
    }

    if (isset($formData['link_amazon_dvd']) && '' != trim($formData['link_amazon_dvd'])) {
      if (filter_var($formData['link_amazon_dvd'], FILTER_VALIDATE_URL) === FALSE) {
        $errors[] = 'Amazon URL (DVD) is invalid.';
      }
    }

    if (isset($formData['link_sanity_dvd']) && '' != trim($formData['link_sanity_dvd'])) {
      if (filter_var($formData['link_sanity_dvd'], FILTER_VALIDATE_URL) === FALSE) {
        $errors[] = 'Sanity URL (DVD) is invalid.';
      }
    }


    if (isset($formData['link_jbhifi_4k']) && '' != trim($formData['link_jbhifi_4k'])) {
      if (filter_var($formData['link_jbhifi_4k'], FILTER_VALIDATE_URL) === FALSE) {
        $errors[] = 'JB Hi-Fi URL (4K) is invalid.';
      }
    }

    if (isset($formData['link_amazon_4k']) && '' != trim($formData['link_amazon_4k'])) {
      if (filter_var($formData['link_amazon_4k'], FILTER_VALIDATE_URL) === FALSE) {
        $errors[] = 'Amazon URL (4K) is invalid.';
      }
    }

    if (isset($formData['link_sanity_4k']) && '' != trim($formData['link_sanity_4k'])) {
      if (filter_var($formData['link_sanity_4k'], FILTER_VALIDATE_URL) === FALSE) {
        $errors[] = 'Sanity URL (4K) is invalid.';
      }
    }

    if (!empty($errors)) {
      wp_send_json_error(implode('<br>', $errors));
    }

    global $wpdb;

    $check = $wpdb->get_row($wpdb->prepare("SELECT id FROM {$wpdb->prefix}gmoat_final WHERE position = %d", $formData['position']));

    $values = [
      'title' => $formData['title'],
      'slug' => sanitize_title($formData['title']),
      'description' => $formData['description'],
      'image_url' => $formData['image_url'],

      'link_jbhifi_bluray' => $formData['link_jbhifi_bluray'],
      'link_amazon_bluray' => $formData['link_amazon_bluray'],
      'link_sanity_bluray' => $formData['link_sanity_bluray'],

      'link_jbhifi_dvd' => $formData['link_jbhifi_dvd'],
      'link_amazon_dvd' => $formData['link_amazon_dvd'],
      'link_sanity_dvd' => $formData['link_sanity_dvd'],

      'link_jbhifi_4k' => $formData['link_jbhifi_4k'],
      'link_amazon_4k' => $formData['link_amazon_4k'],
      'link_sanity_4k' => $formData['link_sanity_4k'],

      'position' => $formData['position'],
    ];

    if ($check) { // Update
      $wpdb->update(
        $wpdb->prefix . 'gmoat_final',
        $values,
        ['id' => $check->id]
      );
    } else { // Insert
      $wpdb->insert(
        $wpdb->prefix . 'gmoat_final',
        $values
      );
    }

    wp_send_json_success();

    wp_send_json_error('Error');
    wp_die();
  } // save_final_movie()

  public function save_final_movie_2021()
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

    $errors = [];

    parse_str($post['formData'], $formData);

    $required_fields = [
      'image_url',
      'title',
      'description',
      'position',
    ];

    foreach ($required_fields as $required_field) {
      if (!isset($formData[$required_field]) || '' == trim($formData[$required_field])) {
        // $errors[] = ucfirst(str_replace('_', ' ', $required_field)) . ' is required.';
      }
    }

    if (isset($formData['link_purchase']) && '' != trim($formData['link_purchase'])) {
      if (filter_var($formData['link_purchase'], FILTER_VALIDATE_URL) === FALSE) {
        $errors[] = 'Purchase link is invalid.';
      }
    }

    if (!empty($errors)) {
      wp_send_json_error(implode('<br>', $errors));
    }

    global $wpdb;

    $check = $wpdb->get_row($wpdb->prepare("SELECT id FROM {$wpdb->prefix}gmoat_final_2021 WHERE position = %d", $formData['position']));

    $values = [
      'title' => $formData['title'],
      'slug' => sanitize_title($formData['title']),
      'description' => $formData['description'],
      'image_url' => $formData['image_url'],

      'link_purchase' => $formData['link_purchase'],

      'position' => $formData['position'],
    ];

    if ($check) { // Update
      $wpdb->update(
        $wpdb->prefix . 'gmoat_final_2021',
        $values,
        ['id' => $check->id]
      );
    } else { // Insert
      $wpdb->insert(
        $wpdb->prefix . 'gmoat_final_2021',
        $values
      );
    }

    wp_send_json_success();

    wp_send_json_error('Error');
    wp_die();
  } // save_final_movie_2021()

  private function check_unique_result_code($unique)
  {
    global $wpdb;
    $result = $wpdb->get_var("SELECT result_code from {$wpdb->prefix}gmoat_entries where result_code = '{$unique}'");
    if (!$result)
      return true;
    return false;
  }

  public function export()
  {
    include_once plugin_dir_path(__FILE__) . 'views/export.php';
  } // export()

  public function export_2021()
  {
    include_once plugin_dir_path(__FILE__) . 'views/2021/export.php';
  } // export_2021()
}

new TBMGmoatTop100();
