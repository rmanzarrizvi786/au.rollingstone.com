<?php

/**
 * Plugin Name: The Brag Observer
 * Plugin URI: https://thebrag.media/
 * Description:
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI:
 */

class BragObserver
{

  protected $plugin_name;
  protected $plugin_slug;

  protected $rest_api_key;
  protected $api_url;

  public function __construct()
  {

    $this->plugin_name = 'brag_observer';
    $this->plugin_slug = 'brag-observer';

    $this->is_sandbox = false; // in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);

    $this->rest_api_key = '1fc08f46-3537-43f6-b5c1-c68704acf3fa';
    if ($this->is_sandbox) {
      $this->api_url = 'https://the-brag.com/wp-json/brag_observer/v1/';
    } else {
      $this->api_url = 'https://thebrag.com/wp-json/brag_observer/v1/';
    }

    // Shortcodes
    add_shortcode('observer_tastemaker_form', [$this, 'shortcode_observer_tastemaker_form']);
    add_shortcode('observer_lead_generator_form', [$this, 'shortcode_observer_lead_generator_form']);

    // AJAX
    add_action('wp_ajax_save_tastemaker_review', [$this, 'save_tastemaker_review']);
    add_action('wp_ajax_nopriv_save_tastemaker_review', [$this, 'save_tastemaker_review']);

    add_action('wp_ajax_save_lead_generator_response', [$this, 'save_lead_generator_response']);
    add_action('wp_ajax_nopriv_save_lead_generator_response', [$this, 'save_lead_generator_response']);

    // REST API Endpoints
    add_action('rest_api_init', [$this, '_rest_api_init']);

    // AJAX
    add_action('wp_ajax_subscribe_observer', array($this, 'ajax_subscribe_observer'));
    add_action('wp_ajax_nopriv_subscribe_observer', array($this, 'ajax_subscribe_observer'));
  }

  /*
  * Shortcode: Tastemaker
  */
  public function shortcode_observer_tastemaker_form($atts)
  {

    $tastemaker_atts = shortcode_atts(array(
      'id' => NULL,
      'background' => '#e9ecef',
      'border' => '#fff',
      'width' => NULL
    ), $atts);

    if (is_null($tastemaker_atts['id']))
      return;

    $id = absint($tastemaker_atts['id']);

    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . '/js/scripts.js', array('jquery'), time(), true);
    $args = array(
      'url'   => admin_url('admin-ajax.php'),
      // 'ajax_nonce' => wp_create_nonce( $this->plugin_slug . '-nonce' ),
    );
    wp_localize_script($this->plugin_name, $this->plugin_name, $args);

    // $api_url = $this->api_url . 'get_tastemaker_form?key=' . $this->rest_api_key . '&id=' . $id;
    $api_url = $this->api_url . 'get_tastemaker_form?key=' . $this->rest_api_key . '&' . http_build_query($tastemaker_atts);

    $response = wp_remote_get($api_url, ['sslverify' => !$this->is_sandbox]);

    $responseBody = wp_remote_retrieve_body($response);
    if ($responseBody) {
      $resonseJson = json_decode($responseBody);
      $form_html = isset($resonseJson->success) && $resonseJson->success ? $resonseJson->data : '';
    } else {
      $form_html = '';
    }

    $form_html .= '
    <style>
    .tastemaker-form .tastemaker-form-wrap {
      padding: 1rem !important;
      margin-bottom: 1rem!important;
      margin-top: 1rem!important;
      border-radius: .25rem!important;
    }
    .tastemaker-form .tastemaker-form-wrap .row {
      display: flex;
      flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
    }
    .tastemaker-form .tastemaker-form-wrap .row .col-12 {
      flex: 0 0 100%;
      max-width: 100%;
      position: relative;
      width: 100%;
      padding-right: 15px;
      padding-left: 15px;
    }
    .tastemaker-form .tastemaker-form-wrap label {
      display: block;
      font-weight: bold;
      line-height: 1.7;
      margin-bottom: .5rem;
    }
    .tastemaker-form .tastemaker-form-wrap textarea.form-control {
      height: auto;
    }
    .tastemaker-form .tastemaker-form-wrap .form-control {
      display: block;
      width: 100%;
      height: calc(2.25rem + 2px);
      padding: .375rem .75rem;
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.5;
      color: #495057;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid #ced4da;
      border-radius: .25rem;
      transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .tastemaker-form .tastemaker-form-wrap .mt-2, .my-2 {
      margin-top: .5rem!important;
    }
    .tastemaker-form .tastemaker-form-wrap button, input, optgroup, select, textarea {
      margin: 0;
      font-family: inherit;
      font-size: inherit;
      line-height: inherit;
    }
    .tastemaker-form .tastemaker-form-wrap .btn:not(:disabled):not(.disabled) {
      cursor: pointer;
    }
    .tastemaker-form .tastemaker-form-wrap .btn.btn-danger {
      color: #fff;
      background-color: #dc3545;
      border-color: #dc3545;
    }
    .tastemaker-form .tastemaker-form-wrap .btn {
      display: inline-block;
      font-weight: 400;
      color: #212529;
      text-align: center;
      vertical-align: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      background-color: transparent;
      border: 1px solid transparent;
      padding: .375rem .75rem;
      font-size: 1rem;
      line-height: 1.5;
      border-radius: .25rem;
      transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .tastemaker-form .tastemaker-form-wrap .alert-danger {
      color: #721c24;
      background-color: #f8d7da;
      border-color: #f5c6cb;
    }
    .tastemaker-form .tastemaker-form-wrap .alert {
      position: relative;
      padding: .75rem 1.25rem;
      margin-bottom: 1rem;
      border: 1px solid transparent;
      border-radius: .25rem;
    }
    .tastemaker-form .tastemaker-form-wrap .d-none {
      display: none;
    }
    </style>
    ';

    return $form_html;
  } // shortcode_observer_tastemaker_form()

  /*
  * Shortcode: Lead Generator
  */
  public function shortcode_observer_lead_generator_form($atts)
  {
    $lead_generator_atts = shortcode_atts(array(
      'id' => NULL,
      'background' => '#e9ecef',
      'border' => '#fff',
      'width' => NULL,
      'lc' => isset($_GET['lc']) ? sanitize_text_field($_GET['lc']) : NULL,
      'cta' => 'Sign me up'
    ), $atts);

    if (is_null($lead_generator_atts['id']))
      return;

    $lead_generator_atts['id'] = (int)filter_var($lead_generator_atts['id'], FILTER_SANITIZE_NUMBER_INT);

    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . '/js/scripts.js', array('jquery'), time(), true);
    $args = array(
      'url'   => admin_url('admin-ajax.php'),
      // 'ajax_nonce' => wp_create_nonce( $this->plugin_slug . '-nonce' ),
    );
    wp_localize_script($this->plugin_name, $this->plugin_name, $args);

    $api_url = $this->api_url . 'get_lead_generator_form?key=' . $this->rest_api_key . '&' . http_build_query($lead_generator_atts);

    $response = wp_remote_get($api_url, ['sslverify' => !$this->is_sandbox]);

    $responseBody = wp_remote_retrieve_body($response);
    if ($responseBody) {
      $resonseJson = json_decode($responseBody);
      $form_html = isset($resonseJson->success) && $resonseJson->success ? $resonseJson->data : '';
    } else {
      $form_html = '';
    }

    return $form_html;
  } // shortcode_observer_lead_generator_form()

  /*
  * Save Lead Generator Response - Frontend
  */
  public function save_lead_generator_response()
  {

    if (defined('DOING_AJAX') && DOING_AJAX) :

      parse_str($_POST['formData'], $formData);

      $errors = [];

      $tastemaker_id = isset($formData['id']) ? $formData['id'] : null;
      if (is_null($tastemaker_id)) {
        $errors[] = 'Invalid submission.';
      }

      $formData['email'] = trim($formData['email']);
      if (!isset($formData['email']) || !is_email($formData['email'])) {
        $errors[] = 'Please enter valid email address.';
      }

      if (count($errors) > 0) {
        wp_send_json_error($errors);
      }

      $formData['key'] = $this->rest_api_key;

      $api_url = $this->api_url . 'create_lead_generator_response';
      $response = wp_remote_post(
        $api_url,
        [
          'method' => 'POST',
          'timeout' => 45,
          'body' => $formData,
          'sslverify' => !$this->is_sandbox
        ]
      );
      $responseBody = wp_remote_retrieve_body($response);
      $resonseJson = json_decode($responseBody);
      if ($resonseJson->success) {
        wp_send_json_success($resonseJson->data);
      } else {
        wp_send_json_error($resonseJson->data);
      }
    endif;
  } // save_lead_generator_response()

  /*
  * Save Review - Frontend
  */
  public function save_tastemaker_review()
  {

    if (defined('DOING_AJAX') && DOING_AJAX) :

      parse_str($_POST['formData'], $formData);

      // wp_send_json_error( $formData );

      $errors = [];

      $tastemaker_id = isset($formData['id']) ? $formData['id'] : null;
      if (is_null($tastemaker_id)) {
        $errors[] = 'Invalid submission.';
      }

      $formData['rating'] = isset($formData['rating']) ? absint($formData['rating']) : 0;
      if (!isset($formData['rating']) || !in_array($formData['rating'], [1, 2, 3, 4, 5])) {
        $errors[] = 'Please select valid star rating.';
      }

      $formData['email'] = trim($formData['email']);
      if (!isset($formData['email']) || !is_email($formData['email'])) {
        $errors[] = 'Please enter valid email address.';
      }

      if (count($errors) > 0) {
        wp_send_json_error($errors);
      }

      $formData['key'] = $this->rest_api_key;

      $api_url = $this->api_url . 'create_tastemaker_review';
      $response = wp_remote_post(
        $api_url,
        [
          'method' => 'POST',
          'timeout' => 45,
          'body' => $formData,
          'sslverify' => !$this->is_sandbox
        ]
      );
      $responseBody = wp_remote_retrieve_body($response);
      $resonseJson = json_decode($responseBody);
      if ($resonseJson->success) {
        wp_send_json_success($resonseJson->data);
      } else {
        wp_send_json_error($resonseJson->data);
      }
    // return $resonseJson->data;
    endif;
  }

  /*
  * Subscribe to Obsever List
  */
  public function ajax_subscribe_observer($formData = [], $return_json = true)
  {
    if (!empty($formData) || (defined('DOING_AJAX') && DOING_AJAX)) :

      if (empty($formData)) {
        if (isset($_POST['formData'])) {
          parse_str($_POST['formData'], $formData);
        } else {
          $formData = $_POST;
        }
      }

      if (!isset($formData['email'])) {
        $current_user = wp_get_current_user();
        $formData['email'] = $current_user->user_email;
      }

      if (!is_numeric($formData['list'])) {
        error_log('Observer List _' . $formData['list'] . '_is not numeric');
        wp_mail('sachin.patel@thebrag.media', 'Observer Error', 'Observer List _' . $formData['list'] . '_is not numeric');
        wp_send_json_error(['error' => ['message' => 'Something went wrong']]);
        wp_die();
      }

      $brag_api_url_base = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) ? 'https://the-brag.com/' : 'https://thebrag.com/';

      $brag_api_url = $brag_api_url_base . 'wp-json/brag_observer/v1/sub_unsub/';

      $response = wp_remote_post(
        $brag_api_url,
        [
          'method'      => 'POST',
          'body'        => array(
            'email' => $formData['email'],
            'list' => $formData['list'],
            'source' => $formData['source'],
            'status' => isset($formData['status']) ? $formData['status'] : 'subscribed',
          ),
          'sslverify' => !in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']),
        ]
      );
      $responseBody = wp_remote_retrieve_body($response);
      $resonseJson = json_decode($responseBody);

      if (isset($resonseJson->success) && $resonseJson->success == 1) {
        if ($return_json) {
          wp_send_json_success($resonseJson->data);
          wp_die();
        } else {
          return true;
        }
      }
      if ($return_json) {
        wp_send_json_error(['error' => ['message' => $resonseJson->data->error->message]]);
        wp_die();
      } else {
        return false;
      }
    endif;
  } // ajax_subscribe_observer() }}

  /*
  * REST: Endpoints
  */
  public function _rest_api_init()
  {
    register_rest_route('api/v1', '/observer/articles', array(
      'methods' => 'GET',
      'callback' => [$this, 'get_articles_for_topic'],
    ));

    register_rest_route('api/v2', '/articles', array(
      'methods' => 'GET',
      'callback' => [$this, 'articles_json_func'],
    ));

    register_rest_route('api/v2', '/articles/nz', array(
      'methods' => 'GET',
      'callback' => [$this, 'articles_nz_json_func'],
    ));
  }

  public function articles_nz_json_func($data)
  {
    $return = array();

    $posts_per_page = isset($_GET['size']) ? (int) $_GET['size'] : 10;
    $paged = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;

    $timezone = new DateTimeZone('Australia/Sydney');

    $args = [
      'post_status' => 'publish',
      'has_password'   => FALSE,
      'post_type' => ['pmc-nz', 'post'],
      'paged' => $paged,
      'meta_query' => [
        [
          'key'   => 'add_to_nz_content',
          'value' => '1',
        ]
      ]
    ];

    if ($offset > 0) {
      $args['offset'] = $offset;
    }

    $posts = new WP_Query($args);

    global $post;
    if ($posts->have_posts()) {
      while ($posts->have_posts()) {
        $posts->the_post();
        $url = get_the_permalink();
        $author = get_field('Author') ? get_field('Author') : get_the_author();

        $category_names = $tag_names = array();

        $post_categories = wp_get_post_categories(get_the_ID());
        if (count($post_categories) > 0) :
          foreach ($post_categories as $c) :
            $cat = get_category($c);
            array_push($category_names, $cat->name);
          endforeach;
        endif;

        $post_tags = wp_get_post_tags(get_the_ID());
        if (count($post_tags) > 0) :
          foreach ($post_tags as $t) :
            $tag = get_tag($t);
            array_push($tag_names, $tag->name);
          endforeach;
        endif;

        $content = apply_filters('the_content', get_the_content());

        $src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

        $return[] = array(
          'ID' => get_the_ID(),
          'title' => get_the_title(),
          'link' => $url,
          'guid' => get_the_guid(),
          'publish_date' => mysql2date('c', get_post_time('c', true), false),
          'description' => get_the_excerpt(),
          'image' => $src[0],
          'author' => $author,
          'categories' => $category_names,
          'tags' => $tag_names,
          // 'content' => $content,
          'site' => get_bloginfo('name'),
        );
      }
    }
    return $return;
  }

  public function articles_json_func($data)
  {
    $return = array();

    $posts_per_page = isset($_GET['size']) ? (int) $_GET['size'] : 10;
    $paged = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;

    $timezone = new DateTimeZone('Australia/Sydney');

    $args = [
      //        'post_type' => array( 'post', 'photo_gallery' ),
      'post_status' => 'publish',
      'posts_per_page' => $posts_per_page,
      'paged' => $paged,
    ];

    if ($offset > 0) {
      $args['offset'] = $offset;
    }

    $posts = new WP_Query($args);

    global $post;
    if ($posts->have_posts()) {
      while ($posts->have_posts()) {
        $posts->the_post();
        $url = get_the_permalink();
        $author = get_field('Author') ? get_field('Author') : get_the_author();

        $category_names = $tag_names = array();

        $post_categories = wp_get_post_categories(get_the_ID());
        if (count($post_categories) > 0) :
          foreach ($post_categories as $c) :
            $cat = get_category($c);
            array_push($category_names, $cat->name);
          endforeach;
        endif;

        $post_tags = wp_get_post_tags(get_the_ID());
        if (count($post_tags) > 0) :
          foreach ($post_tags as $t) :
            $tag = get_tag($t);
            array_push($tag_names, $tag->name);
          endforeach;
        endif;

        $content = apply_filters('the_content', get_the_content());

        $src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

        $return[] = array(
          'ID' => get_the_ID(),
          'title' => get_the_title(),
          'link' => $url,
          'guid' => get_the_guid(),
          'publish_date' => mysql2date('c', get_post_time('c', true), false),
          'description' => get_the_excerpt(),
          'image' => $src[0],
          'author' => $author,
          'categories' => $category_names,
          'tags' => $tag_names,
          // 'content' => $content,
          'site' => get_bloginfo('name'),
        );
      }
    }
    return $return;
  }


  /*
  * REST: get articles
  */
  function get_articles_for_topic($data)
  {
    $topic = isset($_GET['topic']) ? $_GET['topic'] : null;

    if (is_null($topic))
      return;

    $topics = array_map('trim', explode(',', $topic));
    $keywords = implode('+', $topics);

    $posts_per_page = isset($_GET['size']) ? (int) $_GET['size'] : 10;

    $timezone = new DateTimeZone('Australia/Sydney');

    if (isset($_GET['after'])) :
      $after_dt = new DateTime(date_i18n('Y-m-d H:i:s', strtotime(trim($_GET['after']))));
      $after_dt->setTimezone($timezone);
      $after = $after_dt->format('Y-m-d H:i:s');
    else :
      $after = NULL;
    endif;

    if (isset($_GET['before'])) :
      $before_dt = new DateTime(date_i18n('Y-m-d H:i:s', strtotime(trim($_GET['before']))));
      $before_dt->setTimezone($timezone);
      $before = $before_dt->format('Y-m-d H:i:s');
    else :
      $before = NULL;
    endif;

    if (is_null($after) || is_null($before))
      return;

    $return = array();

    $args = [
      'date_query' => array(
        'after' => $after,
        'before' => $before,
      ),
      'fields' => 'ids',
      'post_type' => array('post', 'country'),
      'post_status' => 'publish',
      'posts_per_page' => $posts_per_page,
    ];

    $posts_keyword = new WP_Query(
      $args +
        [
          's' => $keywords,
          'exact' => isset($_GET['exact']), // true,
          'sentence' => isset($_GET['sentence']), // true,
        ]
    );

    // Genre + Artist + Category
    $posts_genre = new WP_Query(
      $args +
        [
          'tax_query' => [
            'relation' => 'OR',
            [
              'taxonomy' => 'genre',
              'field' => 'slug',
              'terms' => $topics,
            ],
            [
              'taxonomy' => 'artist',
              'field' => 'slug',
              'terms' => $topics,
            ],
            [
              'taxonomy' => 'category',
              'field' => 'slug',
              'terms' => $topics,
            ],
          ],
        ]
    );

    // Tags
    $posts_tags = new WP_Query(
      $args +
        [
          'tag' => $keywords
        ]
    );

    $combined_ids = array_merge(
      $posts_keyword->posts,
      $posts_genre->posts,
      $posts_tags->posts
    );
    $combined_ids = array_unique($combined_ids);

    if (count($combined_ids) < 1)
      return;

    $posts = new WP_Query(['post__in' => $combined_ids]);

    global $post;
    if ($posts->have_posts()) {
      while ($posts->have_posts()) {
        $posts->the_post();
        $url = get_the_permalink();
        $author = get_field('Author') ? get_field('Author') : get_the_author();

        $src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium_large');

        $return[] = array(
          'title' => get_the_title(),
          'link' => $url,
          'publish_date' => mysql2date('c', get_post_time('c', true), false),
          'description' => get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true) ?: get_the_excerpt(),
          'image' => $src[0],
        );
      }
    }
    return $return;
  }

  /*
  * Call Remote API
  */
  private static function callAPI($method, $url, $data = '', $content_type = '')
  {
    $curl = curl_init();
    switch ($method) {
      case "POST":
        curl_setopt($curl, CURLOPT_POST, 1);
        if ($data)
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        break;
      case "PUT":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        if ($data)
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        break;
      default:
        if ($data)
          $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    if ($content_type !== false) {
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
      ));
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    if (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    // EXECUTE:
    $result = curl_exec($curl);

    // error_log( $url );
    // if ( 'POST' == $method ) {
    // echo '<pre>'; var_dump( curl_error( $curl ) ); echo '</pre>';
    // }
    if (!$result)
      return;
    curl_close($curl);
    return $result;
  }
}

new BragObserver();
