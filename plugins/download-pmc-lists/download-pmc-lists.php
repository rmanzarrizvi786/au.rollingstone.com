<?php

/**
 * Plugin Name: Download PMC Lists
 * Plugin URI: https://thebrag.media
 * Description:
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI:
 */

class TBMDownloadPMCLists
{

  protected $plugin_name;
  protected $plugin_slug;
  protected $rs_feed;

  public function __construct()
  {
    $this->plugin_name = 'tbm_download_pmc_lists';
    $this->plugin_slug = 'tbm-download-pmc-lists';

    $this->rs_feed = 'https://www.rollingstone.com/custom-feed/australia/?v=' . time();

    add_action('admin_menu', array($this, 'admin_menu'));

    add_action('wp_ajax_start_download', array($this, 'start_download'));
    add_action('wp_ajax_nopriv_start_download', array($this, 'start_download'));

    add_action('wp_ajax_continue_download', array($this, 'continue_download'));
    add_action('wp_ajax_nopriv_continue_download', array($this, 'continue_download'));

    add_action('wp_ajax_start_download_article', array($this, 'start_download_article'));
    // add_action('wp_ajax_nopriv_start_download_article', array($this, 'start_download_article'));

    add_action('wp_ajax_start_download_article_feed', array($this, 'start_download_article_feed'));

    add_action('apple_news_skip_push', array($this, 'apple_news_skip_push'), 10, 2);
  }

  public function admin_menu()
  {
    $main_menu = add_menu_page(
      'Download PMC',
      'Download PMC',
      'edit_posts',
      $this->plugin_slug,
      [$this, 'index'],
      'dashicons-download',
      10
    );

    $submenu_list = add_submenu_page(
      $this->plugin_slug,
      'Download PMC List',
      'List',
      'edit_posts',
      $this->plugin_slug . '-list',
      [$this, 'index_list']
    );

    /* $submenu_article = add_submenu_page(
      $this->plugin_slug,
      'Download PMC Article',
      'Article',
      'edit_posts',
      $this->plugin_slug . '-article',
      [$this, 'index_article']
    ); */

    $submenu_article = add_submenu_page(
      $this->plugin_slug,
      'Download PMC Article',
      'Article',
      'edit_posts',
      $this->plugin_slug . '-article',
      [$this, 'index_article']
    );

    add_action('load-' . $submenu_list, array($this, 'load_admin_js'));
    add_action('load-' . $submenu_article, array($this, 'load_admin_js'));
  }

  function load_admin_js()
  {
    add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
  }

  public function enqueue_admin_scripts($hook)
  {
    wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('js/admin.js', __FILE__), array('jquery'), time(), true);
    wp_localize_script(
      $this->plugin_slug . '-admin-script',
      $this->plugin_name,
      array(
        'url'   => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce($this->plugin_name . '_nonce'),
      )
    );
  }

  public function index()
  {
    echo '<h1>Choose submenu from the left.</h1>';
  }

  /*
  * List
  */
  public function index_list()
  {
?>
    <h1>Download PMC List</h1>
    <input type="url" name="list_url" id="list_url" style="width: 90%; display: block; margin: 1rem 0;" value="" placeholder="https://">

    <button id="start-download" class="button button-primary">Download</button>

    <div id="migration-results" style="padding: 10px;"></div>
  <?php
  }

  public function start_download()
  {
    if (check_ajax_referer($this->plugin_name . '_nonce', 'nonce')) :

      ini_set('max_execution_time', 600); // 600 seconds = 10 minutes

      global $wpdb;

      $list_url = isset($_POST['list_url']) ? $_POST['list_url'] : NULL;

      if (!is_null($list_url)) :

        $html = file_get_contents($list_url);

        $html = preg_replace('/<!--(.|\s)*?-->/', '', $html);

        // wp_send_json_error( array( 'result' => '<pre>' . print_r( str_replace( [ '<', '>', ], [ '&lt;', '&gt;', ], $html ), true ) . '</pre>' ) );

        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $doc->preserveWhiteSpace = false;

        $meta_og_description = '';
        foreach ($doc->getElementsByTagName('meta') as $meta) {
          if ($meta->getAttribute('property') == 'og:description') {
            $meta_og_description = $meta->getAttribute('content');
          }
        }

        $dom_xpath = new DOMXpath($doc);

        $next_page_elements = $dom_xpath->query("//*[@rel='next']");
        foreach ($next_page_elements as $element) {
          $hrec = $element->getAttribute('href');
          // if ( strpos( $hrec, '?' ) !== FALSE ) {
          if ($hrec) {
            $next_page = $hrec;
            break;
          }
        }

        $title_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'l-article-header__row')]");
        foreach ($title_elements as $element) {
          $list_title = $this->get_inner_html($element);
          break;
        }

        // $lead_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'c-list__lead')]");
        $lead_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'c-content t-copy')]");
        foreach ($lead_elements as $element) {
          $list_content = $this->get_inner_html($element);
          break;
        }

        $list_content .= '<p><em>From <a href="' . $list_url . '" target="_blank">Rolling Stone US</a></em></p>';

        $featured_img_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'wp-post-image')]");
        foreach ($featured_img_elements as $element) {
          $featured_img = $element->getAttribute('data-src');
          $featured_img = strtok($featured_img, '?') . '?w=10000';
          break;
        }

        // Categories - get from breadcrumbs
        $cat_names = $cat_IDs = array();
        $breadcrumbs_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'c-breadcrumbs')]");
        foreach ($breadcrumbs_elements as $element) {
          $dom = new DOMDocument();
          @$dom->loadHTML($this->get_inner_html($element));

          $a_elements = $dom->getElementsByTagName('a'); //->item(1);
          if ($a_elements->length > 0) {
            // for( $i = 0; $i < $a_elements->length; $i++) {
            foreach ($a_elements as $element) {
              if (!in_array(strtolower($element->nodeValue), array('home'))) {
                $cat_names[] = $element->nodeValue;
              }
            }
          }
        }
        if (!empty($cat_names)) {
          foreach ($cat_names as $cat_name) {
            $cat_IDs[] = get_cat_ID($cat_name);
          }
        }

        $new_list_args = array(
          'post_content' => $list_content,
          'post_title' => $list_title,
          'post_excerpt' => $meta_og_description,
          'post_status' => 'draft', // 'Published' == $article['Status'] ? 'publish' : 'draft',
          'post_type' => 'pmc_list',
          'post_category' => $cat_IDs,
          'meta_input' => array(
            '_yoast_wpseo_canonical' => $list_url,
          ),
        );

        $new_list_id = wp_insert_post($new_list_args);

        if (!isset($new_list_id) || is_wp_error($new_list_id)) {
          wp_send_json_error(array('result' => 'Error creating main list'));
        }

        // $list_item_elements = $dom_xpath->query("//*[@class='c-list__item c-list__item--artist']");
        // $list_item_elements = $dom_xpath->query('//div[contains(@class,"c-list__item")]');

        if (isset($featured_img) && '' != $featured_img) {
          // required libraries for media_sideload_image
          require_once(ABSPATH . 'wp-admin/includes/file.php');
          require_once(ABSPATH . 'wp-admin/includes/media.php');
          require_once(ABSPATH . 'wp-admin/includes/image.php');

          // load the image
          $result = media_sideload_image($featured_img, $new_list_id);

          // then find the last image added to the post attachments
          $attachments = get_posts(array('numberposts' => '1', 'post_parent' => $new_list_id, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC'));

          if (sizeof($attachments) > 0) {
            // set image as the post thumbnail
            set_post_thumbnail($new_list_id, $attachments[0]->ID);
          }
        }

        $pmc_list_relation = wp_insert_term(
          $new_list_id,
          'pmc_list_relation'
        );

        $term_taxonomy_id = !is_wp_error($pmc_list_relation) ? $pmc_list_relation['term_taxonomy_id'] : array();

        $list_items = $this->download_list_items($dom_xpath, $term_taxonomy_id);
        $total_list_items = $list_items['total_list_items'];

        if (isset($list_items['next_page'])) { // If there is a next page, notify to continue download
          $next_page = $list_items['next_page'];
          wp_send_json_success(
            array(
              'result' => 'Downloading more from next page <a href="' . $next_page . '" target="_blank">' . $next_page . '</a>, please wait...<br><a href="' . home_url('/?p=' . $new_list_id) . '" target="_blank">View List so far</a>',
              'list_url' => $next_page,
              'list_id' => $new_list_id,
              'total_list_items' => $total_list_items,
              'term_taxonomy_id' => $term_taxonomy_id,
              'has_next_page' => true,
            )
          );
          wp_die();
        } else { // If there is NO next page, download has been finished and notify with the newly created list
          wp_send_json_success(array('result' => '<span style="color: green; font-weight: bold;">Download FINISHED.</span> <a href="' . home_url('/?p=' . $new_list_id) . '" target="_blank">View List</a>'));
          wp_die();
        }
      endif; // If $list_url is NOT NULL
    endif; // If nonce validated
  }

  public function continue_download()
  {
    if (check_ajax_referer($this->plugin_name . '_nonce', 'nonce')) :
      global $wpdb;

      $list_url = isset($_POST['list_url']) ? $_POST['list_url'] : NULL;

      if (!is_null($list_url)) :
        $html = file_get_contents($list_url);

        $html = preg_replace('/<!--(.|\s)*?-->/', '', $html);

        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $doc->preserveWhiteSpace = false;

        $dom_xpath = new DOMXpath($doc);

        $next_page_elements = $dom_xpath->query("//*[@rel='next']");
        foreach ($next_page_elements as $element) {
          $hrec = $element->getAttribute('href');
          if (strpos($hrec, '?') !== FALSE) {
            $next_page = $hrec;
            break;
          }
        }

        $term_taxonomy_id = isset($_POST['term_taxonomy_id']) ? absint($_POST['term_taxonomy_id']) : 0;
        $total_list_items = isset($_POST['total_list_items']) ? absint($_POST['total_list_items']) : 0;
        $new_list_id = isset($_POST['list_id']) ? absint($_POST['list_id']) : 0;

        // $total_list_items = $this->download_list_items( $dom_xpath, $term_taxonomy_id, $total_list_items );
        $list_items = $this->download_list_items($dom_xpath, $term_taxonomy_id, $total_list_items);
        $total_list_items = $list_items['total_list_items'];

        if ($list_items['next_page']) { // If there is a next page, notify to continue download
          $next_page = $list_items['next_page'];
          wp_send_json_success(
            array(
              'result' => 'Downloading more from next page <a href="' . $next_page . '" target="_blank">' . $next_page . '</a>, please wait...<br><a href="' . home_url('/?p=' . $new_list_id) . '" target="_blank">View List so far</a>',
              'list_url' => $next_page,
              'list_id' => $new_list_id,
              'total_list_items' => $total_list_items,
              'term_taxonomy_id' => $term_taxonomy_id,
              'has_next_page' => true,
            )
          );
          wp_die();
        } else { // If there is NO next page, download has been finished and notify with the newly created list
          wp_send_json_success(array('result' => '<span style="color: green; font-weight: bold;">Download FINISHED.</span> <a href="' . home_url('/?p=' . $new_list_id) . '" target="_blank">View List</a>'));
          wp_die();
        }
      endif; // If $list_url is NOT NULL
    else :
      wp_send_json_error(array('result' => 'Error with nonce'));
    endif; // If nonce validated
  }

  function get_inner_html($node, $strip_tags = false)
  {
    $innerHTML = '';
    $children = $node->childNodes;
    if ($children->length > 0) {
      foreach ($children as $child) {
        $innerHTML .= $child->ownerDocument->saveXML($child);
      }
    }
    if ($strip_tags) {
      return trim(strip_tags($innerHTML));
    }
    return $innerHTML;
  }

  function download_list_items($dom_xpath, $term_taxonomy_id, $count_elements = 0)
  {

    $js_list_items = $dom_xpath->query("//*[contains(concat(' ', @id, ' '),'pmc-lists-front-js-extra')]");
    foreach ($js_list_items as $i => $element) {
      $nodes = $element->childNodes;
      $list_item = array();
      $meta_input = array();

      foreach ($nodes as $node) {
        $text = str_replace('var pmcGalleryExports = ', '', $node->wholeText);
        $text = str_replace(['\n', '\r', '\t',], ['', '', '',], $text);
        $text = str_replace(',"closeButtonLink":"\/"};', '}', $text);

        $first_occ_extra_var = strpos($text, 'var pmcgalleryamapi');
        if ($first_occ_extra_var !== FALSE) {
          $text = substr($text, 0, $first_occ_extra_var);
        }

        // wp_send_json_error( array( 'result' => '<div style="width: 100%; max-width: 100%:">' . $first_occ_extra_var . '</div>' ) );

        $items = json_decode($text, true);
        switch (json_last_error()) {
          case JSON_ERROR_NONE:
            break;
          case JSON_ERROR_DEPTH:
            error_log(' - Maximum stack depth exceeded');
            break;
          case JSON_ERROR_STATE_MISMATCH:
            error_log(' - Underflow or the modes mismatch');
            break;
          case JSON_ERROR_CTRL_CHAR:
            error_log(' - Unexpected control character found');
            break;
          case JSON_ERROR_SYNTAX:
            error_log(' - Syntax error, malformed JSON');
            break;
          case JSON_ERROR_UTF8:
            error_log(' - Malformed UTF-8 characters, possibly incorrectly encoded');
            break;
          default:
            break;
        }
        // wp_send_json_error( array( 'result' => '<div style="width: 100%; max-width: 100%:"><code>' . str_replace( ['<', '>',], [ '&lt;', '&gt;.', ], $text ) . '</code></div>' ) );

        if ($items && $items['gallery']) {
          foreach ($items['gallery'] as $item) {

            $list_item = array();
            $meta_input = array();

            $count_elements++;
            $list_item['counter'] = $count_elements;

            $list_item['title'] = trim($item['title']);

            $list_item['img'] = strtok($item['image'], '?') . '?w=1000';
            $meta_input['thumbnail_ext_url'] = $list_item['img'];

            if (isset($item['video'])) {

              $iframe_embed_root = simplexml_load_string($item['video']);
              $iframe_embed_src = (string) $iframe_embed_root['data-src'];
              $iframe_embed_src = strtok($iframe_embed_src, '?');
              $list_item['iframe'] = str_replace('/embed/', '/watch?v=', $iframe_embed_src);
              $meta_input['pmc_top_video_source'] = $list_item['iframe'];
            }

            if (isset($item['image_credit'])) {
              $list_item['_image_credit'] = trim($item['image_credit']);
              $meta_input['thumbnail_ext_image_credit'] = trim($item['image_credit']);
            }

            $list_item['content'] = wpautop(strip_tags($item['description']));

            if (!empty($list_item)) {
              $new_list_item_args = array(
                'post_content' => $list_item['content'],
                'post_title' => $list_item['title'],
                'post_status' => 'publish',
                'post_type' => 'pmc_list_item',
                // 'post_category' => $cat_IDs,
                'tax_input' => array(
                  'pmc_list_relation' => array(
                    'pmc_list_relation' => $term_taxonomy_id
                  ),
                ),
                'menu_order' => $list_item['counter'],
                'meta_input' => $meta_input,
              );

              $new_list_item_id = wp_insert_post($new_list_item_args);

              // wp_send_json_success( array( 'result' => '<pre>' . print_r( $list_item, true ) . '</pre>' ) ); wp_die();
              // break;
            } // If $list_item is NOT empty
          }

          $return = [
            'total_list_items' => $count_elements,
            'next_page' => false,
          ];

          if ($items['nextPageLink'] && '' != $items['nextPageLink']) {
            $return['next_page'] = $items['nextPageLink']; // true;
          }

          return $return;
        }
      }
    }


    /*
    $list_item_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'c-list__item')]");
    if ( $list_item_elements->length < 1 ) {
      $list_item_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'c-gallery-vertical__slide-wrapper')]");
      if ( $list_item_elements->length < 1 ) {
        wp_send_json_error( array( 'result' => 'Error: fetched list is empty' ) );
      }
    }


    foreach( $list_item_elements as $i => $element ) {

      $nodes = $element->childNodes;
      $list_item = array();
      $meta_input = array();

      foreach ($nodes as $node) {
        if ( in_array( $node->tagName, array( 'header' ) ) ) {
          $count_elements++;
          $list_item['counter'] = $count_elements;
          $list_item_title = $this->get_inner_html( $node, true );
          $arr_list_item_title = explode( "\n", $list_item_title );
          foreach ( $arr_list_item_title as $value ) {
            if ( is_numeric( trim( $value ) ) || trim( $value ) == '' ) {
              continue;
            } else {
              $list_item['title'] = trim( $value );
              break;
            }
          }
        }

        $dom = new DOMDocument();
        @$dom->loadHTML( $this->get_inner_html( $node ) );
        if ( 'figure' == $node->tagName ) {
          $arr_img = $arr_iframe = array();

          $imgs = $dom->getElementsByTagName('img');
          if ( $imgs->item(0) ) {
            $img = $imgs->item(0);
            foreach ($img->attributes as $attr) {
              $name = $attr->nodeName;
              $value = $attr->nodeValue;
              $arr_img[$name] = $value;
            }
            $list_item['img'] = strtok( $arr_img['data-src'], '?' ) . '?w=1000';
          } // If img tag is present

          $iframes = $dom->getElementsByTagName('iframe');
          if ( $iframes->item(0) ) {
            $iframe = $iframes->item(0);
            foreach ($iframe->attributes as $attr) {
              $name = $attr->nodeName;
              $value = $attr->nodeValue;
              $arr_iframe[$name] = $value;
            }
            $iframe_embed_src = strtok( $arr_iframe['data-src'], '?' );
            $list_item['iframe'] = str_replace( '/embed/', '/watch?v=', $iframe_embed_src );
          } // If iframe tag is present
        } // If tagName is 'figure'

        if ( in_array( $node->tagName, array( 'div' ) ) ) {
          $picture_credit = trim( strip_tags( $node->nodeValue ) );

          if ( '' != $picture_credit ) {
            $list_item['_image_credit'] = trim( strip_tags( $node->nodeValue ) );
            $meta_input['thumbnail_ext_image_credit'] = trim( strip_tags( $node->nodeValue ) );
          }

          // wp_send_json_success( array( 'result' => '<pre>' . print_r( $meta_input, true ) . '</pre>' ) );
        } // If 'div' = picture credit is present

        if ( in_array( $node->tagName, array( 'main' ) ) ) {
          $list_item['content'] = $this->get_inner_html( $node );
        }
      } // For Each $nodes = childNodes

      if ( isset( $list_item['iframe'] ) ) {
        $meta_input['pmc_top_video_source'] = $list_item['iframe'];
      }

      if ( isset( $list_item['img'] ) && '' != $list_item['img'] ) {
        $meta_input['thumbnail_ext_url'] = $list_item['img'];
      }

      if ( ! empty( $list_item ) ) {
        $new_list_item_args = array(
            'post_content' => $list_item['content'],
            'post_title' => $list_item['title'],
            'post_status' => 'publish',
            'post_type' => 'pmc_list_item',
            'post_category' => $cat_IDs,
            'tax_input' => array(
              'pmc_list_relation' => array(
                'pmc_list_relation' => $term_taxonomy_id
              ),
            ),
            'menu_order' => $list_item['counter'],
            'meta_input' => $meta_input,
        );

        $new_list_item_id = wp_insert_post( $new_list_item_args );

        // wp_send_json_success( array( 'result' => '<pre>' . print_r( $list_item, true ) . '</pre>' ) ); wp_die();
        // break;
      } // If $list_item is NOT empty

    } // For Each $list_item_elements
    */

    // return $count_elements;
  }

  /*
  * Article
  */
  public function index_article()
  {
  ?>
    <h1>Download PMC Article</h1>
    <input type="url" name="article_url" id="article_url" style="width: 90%; display: block; margin: 1rem 0;" value="" placeholder="https://">

    <button id="start-download-article" class="button button-primary">Download</button>

    <div id="migration-results" style="padding: 10px;"></div>
  <?php
  }

  public function start_download_article()
  {
    if (check_ajax_referer($this->plugin_name . '_nonce', 'nonce')) :

      ini_set('max_execution_time', 600); // 600 seconds = 10 minutes

      global $wpdb;

      $article_url = isset($_POST['article_url']) ? $_POST['article_url'] : NULL;

      return $this->downloadArticle($article_url);
    endif; // If nonce validated
  }

  private function downloadArticle($article_url)
  {

    global $wpdb;
    if (!is_null($article_url)) :

      $html = file_get_contents($article_url);

      $html = preg_replace('/<!--(.|\s)*?-->/', '', $html);

      $doc = new DOMDocument();
      @$doc->loadHTML($html);
      $doc->preserveWhiteSpace = false;

      // Meta tags
      $meta_description = $meta_og_description = $author = '';
      $authors = [];
      foreach ($doc->getElementsByTagName('meta') as $meta) {
        if ($meta->getAttribute('name') == 'description') {
          $meta_description = $meta->getAttribute('content');
        }
        if ($meta->getAttribute('property') == 'og:description') {
          $meta_og_description = $meta->getAttribute('content');
        }
        if ($meta->getAttribute('property') == 'og:title') {
          $article_title = $meta->getAttribute('content');
        }
        if ($meta->getAttribute('property') == 'og:image') {
          $featured_img = $meta->getAttribute('content');
        }
        if ($meta->getAttribute('name') == 'author') {
          $meta_authors = explode(',', $meta->getAttribute('content'));
          foreach ($meta_authors as $meta_author) {
            if (!in_array($meta_author, $authors)) {
              $authors[] = $meta_author;
            }
          }
        }
      }
      $author = implode(' & ', $authors);

      $dom_xpath = new DOMXpath($doc);

      // $title_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'l-article-header__row')]");
      // foreach( $title_elements as $element ) {
      //   $list_title = $this->get_inner_html( $element );
      //   break;
      // }

      // Featured Image Alt
      $img_alt = $this->getFeaturedImgAlt($dom_xpath);

      // Check if video is used instead of featured image
      $featured_vid_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'c-picture__frame')]");
      $featured_video_src = '';
      foreach ($featured_vid_elements as $element) {
        $children = $element->childNodes;
        if ($children->length > 0) {
          foreach ($children as $child) {
            if (isset($child->tagName) && 'div' == $child->tagName) {
              $children2 = $child->childNodes;
              if ($children2->length > 0) {
                foreach ($children2 as $child2) {
                  if (isset($child2->tagName) && 'div' == $child2->tagName) {
                    $children3 = $child2->childNodes;
                    if ($children3->length > 0) {
                      foreach ($children3 as $child3) {
                        if (isset($child3->tagName) && 'iframe' == $child3->tagName) {
                          foreach ($child3->attributes as $key => $value) {
                            if ($key == 'src' || $key == 'data-src') {
                              $featured_video_src = $child3->getAttribute($key);
                              $featured_video_src = strtok($featured_video_src, '?');
                              $featured_video_src = str_replace('/embed/', '/watch?v=', $featured_video_src);
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }

      $featured_vid_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'featured-video')]");


      foreach ($featured_vid_elements as $element) {
        if (isset($element->tagName) && 'div' == $element->tagName) {
          $children = $element->childNodes;
          if ($children) {
            foreach ($children as $child) {
              foreach ($child->attributes as $key => $value) {
                if ($key == 'class') {
                  if (strpos($child->getAttribute($key), 'o-video-card__link') !== FALSE) {
                    $featured_vid_element = $child;
                  }
                }
                if ($key == 'data-video-showcase-type') {
                  if ($child->getAttribute($key) == 'youtube') {
                    $featured_vid_type = 'youtube';
                  }
                }
              }
            }
          }
        }
      }

      if (isset($featured_vid_element) && isset($featured_vid_type)) {
        foreach ($featured_vid_element->attributes as $key => $value) {
          if ($featured_vid_type == 'youtube') {
            if ($key == 'data-video-showcase-trigger') {
              $featured_video_src = 'https://www.youtube.com/watch?v=' . $featured_vid_element->getAttribute($key);
              $a[$key] = $featured_video_src;
            }
          }
        }
      }



      // Featured Image elements; caption and credit
      // $featured_img_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'c-picture__caption')]");
      $featured_img_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'c-figcaption')]");
      $img_caption = $img_credit = '';
      foreach ($featured_img_elements as $element) {
        $children = $element->childNodes;
        if ($children->length > 0) {
          foreach ($children as $child) {
            if (isset($child->tagName) && 'cite' == $child->tagName && trim($child->nodeValue) != '') {
              $img_caption = $this->get_inner_html($child);
            } elseif (isset($child->tagName) && 'p' == $child->tagName && trim($child->nodeValue) != '') {
              foreach ($child->attributes as $key => $value) {
                if ($key == 'class') {
                  if (strpos($child->getAttribute($key), 'c-picture__title') !== FALSE) {
                    $img_caption = $this->get_inner_html($child);
                  } elseif (strpos($child->getAttribute($key), 'c-picture__source') !== FALSE) {
                    $img_credit = $this->get_inner_html($child);
                  }
                }
              }
            }
          }
        }
      }



      // Tags
      // $tags_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'c-tags')]");
      $tags_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'article-tags')]");
      $tags = [];
      foreach ($tags_elements as $tag_element) {
        $tags_children = $tag_element->childNodes;
        if ($tags_children->length > 0) {
          foreach ($tags_children as $tag_child) {

            // if (isset($tag_child->tagName) && 'p' == $tag_child->tagName) {
            if (isset($tag_child->tagName) && 'nav' == $tag_child->tagName) {

              $tags_children2 = $tag_child->childNodes;
              if ($tags_children2->length > 0) {
                foreach ($tags_children2 as $tag_child2) {

                  $tags_children3 = $tag_child2->childNodes;
                  if ($tags_children3->length > 0) {
                    foreach ($tags_children3 as $tag_child3) {



                      $tags_children4 = $tag_child3->childNodes;
                      if ($tags_children4->length > 0) {
                        foreach ($tags_children4 as $tag_child4) {

                          // $tags[] = $tag_child4;

                          if (isset($tag_child4->tagName) && 'a' == $tag_child4->tagName) {
                            $tag_text = trim($this->get_inner_html($tag_child4));
                            $tag_text = str_replace(',', '', $tag_text);
                            $tags[] = $tag_text;
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }

      // wp_send_json_error(array('result' => '<pre>' . print_r($tags, true)));
      // die();

      $content = '';
      $content_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'a-content')]");

      // If content is wrapped with pmc-paywall div tag, change elements
      foreach ($content_elements as $element) {
        $children = $element->childNodes;
        if ($children->length > 0) {
          foreach ($children as $child) {
            if (isset($child->tagName) && 'div' == $child->tagName) {
              foreach ($child->attributes as $key => $value) {
                if ($key == 'class' && 'pmc-paywall' == $child->getAttribute($key)) {
                  $content_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'pmc-paywall')]");
                  break;
                }
              }
            }
          }
        }
      }

      foreach ($content_elements as $element) {
        $children = $element->childNodes;
        if ($children->length > 0) {
          foreach ($children as $child) {
            if (isset($child->tagName)) {
              if ('p' == $child->tagName && trim($child->nodeValue) != '') {
                $content .= '<p>' . $this->get_inner_html($child) . '</p>';
              }
              if ('blockquote' == $child->tagName && trim($child->nodeValue) != '') {
                $content .= '<blockquote>' . $this->get_inner_html($child) . '</blockquote>';
              }
              if ('iframe' == $child->tagName) {
                $iframe_attr = '';
                foreach ($child->attributes as $key => $value) {
                  $iframe_attr .= ' ' . $key . '="' . $child->getAttribute($key) . '"';
                }
                $content .= '<iframe' . $iframe_attr . '>' . $child->nodeValue . '</iframe>';
              }
              if ('script' == $child->tagName) {
                $script_attr = '';
                foreach ($child->attributes as $key => $value) {
                  $script_attr .= ' ' . $key . '="' . $child->getAttribute($key) . '"';
                }
                $content .= '<script' . $script_attr . '>' . $child->nodeValue . '</script>';
              }
              if (in_array($child->tagName, ['div', 'section'])) {
                $elem_attr = '';
                foreach ($child->attributes as $key => $value) {
                  if (
                    $key == 'class' &&
                    (in_array($child->getAttribute($key), array('admz', 'c-featured-article__tags', 'c-featured-article__post-actions', 'c-related-links-wrapper'))
                      ||
                      strpos($child->getAttribute($key), 'l-article-content__pull') !== FALSE
                      ||
                      strpos($child->getAttribute($key), 'injected-related-content') !== FALSE
                      ||
                      strpos($child->getAttribute($key), 'brands-most-popular') !== FALSE
                    )
                  ) {
                    continue (2);
                  }
                  $elem_attr .= ' ' . $key . '="' . $child->getAttribute($key)  . '"';
                }
                $content .= '<' . $child->tagName . $elem_attr . '>' . $this->get_inner_html($child) . '</' . $child->tagName . '>';
              }
            }
          }
        }
      }

      $content .= '<p><em>From <a href="' . $article_url . '" target="_blank">Rolling Stone US</a></em></p>';


      // wp_send_json_success( array( 'result' => '<pre>' . print_r( $featured_img, true ) . '</pre>' ) );

      // Categories - get from breadcrumbs
      $cat_names = $cat_IDs = array();
      $breadcrumbs_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'c-breadcrumbs')]");
      foreach ($breadcrumbs_elements as $element) {
        $dom = new DOMDocument();
        @$dom->loadHTML($this->get_inner_html($element));

        $a_elements = $dom->getElementsByTagName('a');
        if ($a_elements->length > 0) {
          foreach ($a_elements as $element) {
            if (!in_array(strtolower($element->nodeValue), array('home'))) {
              $cat_names[] = $element->nodeValue;
            }
          }
        }
      }
      if (!empty($cat_names)) {
        foreach ($cat_names as $cat_name) {
          $cat_IDs[] = get_cat_ID($cat_name);
        }
      }



      $meta = [
        '_yoast_wpseo_canonical' => $article_url,
        '_yoast_wpseo_metadesc' => $meta_og_description,
        '_yoast_wpseo_title' => $article_title,

        'apple_news_is_preview' => '1',
        'apple_news_is_hidden' => '1',

      ];

      if (isset($author)) {
        $meta['author'] = $author;
      }

      if (isset($featured_video_src) && '' != $featured_video_src) {
        $meta['pmc_top_video_source'] = $featured_video_src;
      }

      // Post name
      $parsed_url = parse_url($article_url);
      $post_name_e = explode('/', $parsed_url['path']);
      $post_name_e = array_map('trim', $post_name_e);
      $post_name_e = array_filter($post_name_e);
      $post_name = preg_replace('|((.*))-(\d+)$|', '$1', end($post_name_e));

      $new_article_args = array(
        'post_name' => $post_name,
        'post_content' => $content,
        'post_title' => $article_title,
        'post_excerpt' => $meta_description,
        'post_status' => 'draft', // 'Published' == $article['Status'] ? 'publish' : 'draft',
        'post_type' => 'post',
        'post_category' => $cat_IDs,
        'tags_input' => isset($tags) ? $tags : [],
        'meta_input' => $meta,
      );

      $new_article_id = wp_insert_post($new_article_args);

      if (!isset($new_article_id) || is_wp_error($new_article_id)) {
        wp_send_json_error(array('result' => 'Error creating the article'));
        die();
      }

      $wpdb->insert(
        $wpdb->prefix . 'pmc_imports',
        array(
          'post_id' => $new_article_id,
          'article_url' => $article_url,
          'article_type' => 'post'
        )
      );

      if (isset($featured_img) && '' != $featured_img) {
        // required libraries for media_sideload_image
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // load the image
        media_sideload_image($featured_img, $new_article_id);

        // then find the last image added to the post attachments
        $attachments = get_posts(array('numberposts' => '1', 'post_parent' => $new_article_id, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC'));

        if (sizeof($attachments) > 0) {
          // set image as the post thumbnail
          $attachment_id = $attachments[0]->ID;
          set_post_thumbnail($new_article_id, $attachment_id);

          if ('' != $img_caption) {
            wp_update_post(['ID' => $attachment_id, 'post_excerpt' => trim($img_caption)]);
          }
          if ('' != $img_credit) {
            update_post_meta($attachment_id, '_image_credit', trim($img_credit));
          }
          if ('' != $img_alt) {
            update_post_meta($attachment_id, '_wp_attachment_image_alt', trim($img_alt));
          }
        }
      }

      wp_send_json_success(array('result' => '<p style="color: green; font-weight: bold;">Download FINISHED.</p> <a href="' . home_url('/?p=' . $new_article_id) . '" target="_blank" class="button">View Article</a> <a href="' . get_edit_post_link($new_article_id) . '" target="_blank" class="button">Edit Article</a>'));
      die();
    endif; // If $article_url is NOT NULL
  }

  public function apple_news_skip_push($bool, $post_id)
  {
    global $wpdb;
    $check_post = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}pmc_imports WHERE post_id = {$post_id} LIMIT 1");
    if ($check_post) {
      return true;
    }
  }

  /*
  * Article (Feed)
  */
  public function index_article_feed()
  {
  ?>
    <h1>Download PMC Article</h1>

    <input type="url" name="article_url" id="article_url" style="width: 90%; display: block; margin: 1rem 0;" value="" placeholder="https://">
    <button id="start-download-article-feed" class="button button-primary">Download</button>

    <div id="migration-results" style="padding: 10px;"></div>
<?php
  }

  private function curl_get_file_contents($URL)
  {
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    curl_close($c);

    if ($contents) return $contents;
    else return FALSE;
  }

  public function start_download_article_feed()
  {
    global $wpdb;
    if (check_ajax_referer($this->plugin_name . '_nonce', 'nonce')) :

      $article_url = isset($_POST['article_url']) ? $_POST['article_url'] : NULL;

      if (is_null($article_url)) :
        wp_send_json_error(['result' => 'Article URL is missing']);
        die();
      endif;

      ini_set('max_execution_time', 600); // 600 seconds = 10 minutes

      include_once(ABSPATH . WPINC . '/feed.php');
      $rss = fetch_feed($this->rs_feed);

      if (is_wp_error($rss)) {
        wp_send_json_error(array('result' => '<div style="width: 100%; max-width: 100%:">Error fetching feed!</div>'));
        die();
      }

      $rss_items = $rss->get_items(0);

      if ($rss_items) {
        $articles = [];
        foreach ($rss_items as $item) {
          $item_categories = $item->get_categories();
          $categories = $tags = [];
          if ($item_categories) {
            foreach ($item_categories as $item_category) {
              if (category_exists($item_category->term)) {
                $categories[] = $item_category->term;
              } else {
                $tags[] = $item_category->term;
              }
            }
          }

          $content = $item->get_content();
          $doc = new DOMDocument();
          @$doc->loadHTML($content);
          $imgs = $doc->getElementsbyTagName('img');

          // wp_send_json_error(array('result' => '<pre>' . print_r($imgs, true)));
          // die();

          if ($imgs && isset($imgs->item)) {
            $image = $imgs->item(0)->getAttribute('src');
          } else {
            $image = '';
          }

          $content = preg_replace("/<img[^>]+\>/i", "", $content, 1);


          $articles[esc_url($item->get_permalink())] = [
            'title' => esc_html($item->get_title()),
            'categories' => $categories,
            'tags' => $tags,
            'author' => $item->get_author()->name,
            'image' => $image,
            'content' => $content,
            'url' => esc_url($item->get_permalink()),
          ];
        }

        if (in_array($article_url, array_keys($articles))) {
          $the_article = $articles[$article_url];
          return $this->createArticle($the_article);
        }

        return $this->downloadArticle($article_url);
      }

      wp_send_json_error(array('result' => 'Empty feed!'));
      die();
    endif; // If nonce validated
  }

  /*
  function getImgSrc( $node ) {
    $children = $node->childNodes;
    if ( $children->length > 0 ) {
      foreach ($children as $child) {
        $children2 = $child->childNodes;
        if ( $children2->length > 0 ) {
          foreach ($children2 as $child2) {
            if ( 'img' == $child2->tagName ) {
              return $child2->getAttribute( 'data-src' );
            }
          }
        }
      }
    }
  }
  */

  private function createArticle($article = [])
  {
    global $wpdb;

    if (empty($article) || !isset($article['title']) || '' == trim($article['title']) || !isset($article['url']) || '' == trim($article['url'])) {
      wp_send_json_error(['result' => 'Missing details.']);
      die();
    }

    if (!empty($article['categories'])) {
      foreach ($article['categories'] as $cat_name) {
        $cat_IDs[] = get_cat_ID($cat_name);
      }
    }

    $content = isset($article['content']) ?  $article['content'] . '<p><em>From <a href="' . $article['url'] . '" target="_blank">Rolling Stone US</a></em></p>' : '';

    $parsed_url = parse_url($article['url']);
    $post_name_e = explode('/', $parsed_url['path']);
    $post_name_e = array_map('trim', $post_name_e);
    $post_name_e = array_filter($post_name_e);
    $post_name = preg_replace('|((.*))-(\d+)$|', '$1', end($post_name_e));

    $html = file_get_contents($article['url']);
    $html = preg_replace('/<!--(.|\s)*?-->/', '', $html);

    $doc = new DOMDocument();
    @$doc->loadHTML($html);
    $doc->preserveWhiteSpace = false;

    $meta_og_description = '';
    foreach ($doc->getElementsByTagName('meta') as $meta) {
      if ($meta->getAttribute('property') == 'og:description') {
        $meta_og_description = $meta->getAttribute('content');
      }
      if (!isset($article['image']) || '' == $article['image']) {
        if ($meta->getAttribute('property') == 'og:image') {
          $article['image'] = $meta->getAttribute('content');
        }
      }
    }


    $metas = [
      '_yoast_wpseo_canonical' => $article['url'],
      '_yoast_wpseo_title' => $article['title'],
      '_yoast_wpseo_metadesc' => $meta_og_description,
      'apple_news_is_preview' => '1',
      'apple_news_is_hidden' => '1',
    ];

    if (isset($article['author']) && '' != trim($article['author'])) {
      $metas['author']  = $article['author'];
    }

    /* if (isset($article['image']) && '' != $article['image']) {
      $metas['thumbnail_ext_url'] = $article['image'];
    } */

    $new_article_args = [
      'post_name' => $post_name,
      'post_content' => $content,
      'post_title' => $article['title'],
      'post_excerpt' => $meta_og_description,
      'post_status' => 'draft',
      'post_type' => 'post',
      'post_category' => $cat_IDs,
      'tags_input' => isset($article['tags']) ? $article['tags'] : [],
      'meta_input' => $metas,
    ];

    $new_article_id = wp_insert_post($new_article_args);

    if (!isset($new_article_id) || is_wp_error($new_article_id)) {
      wp_send_json_error(['result' => 'Error creating the article']);
      die();
    }

    $wpdb->insert(
      $wpdb->prefix . 'pmc_imports',
      array(
        'post_id' => $new_article_id,
        'article_url' => $article['url'],
        'article_type' => 'post',
      )
    );

    if (isset($article['image']) && '' != $article['image']) {
      // required libraries for media_sideload_image
      require_once(ABSPATH . 'wp-admin/includes/file.php');
      require_once(ABSPATH . 'wp-admin/includes/media.php');
      require_once(ABSPATH . 'wp-admin/includes/image.php');

      // load the image
      $result = media_sideload_image($article['image'], $new_article_id);

      // then find the last image added to the post attachments
      $attachments = get_posts(array('numberposts' => '1', 'post_parent' => $new_article_id, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC'));

      if (sizeof($attachments) > 0) {
        // set image as the post thumbnail
        set_post_thumbnail($new_article_id, $attachments[0]->ID);
      }
    }

    wp_send_json_success(array('result' => '<p style="color: green; font-weight: bold;">Download FINISHED.</p> <a href="' . home_url('/?p=' . $new_article_id) . '" target="_blank" class="button">View Article</a> <a href="' . get_edit_post_link($new_article_id) . '" target="_blank" class="button">Edit Article</a>'));
    die();
  }

  private function getFeaturedImgAlt($dom_xpath, $element = null)
  {
    if (!is_null($element)) {
      if ('img' == $element->tagName) {
        foreach ($element->attributes as $attr) {
          if ('alt' == $attr->name)
            return $attr->value;
        }
        return $element;
      }
      if ('' != $element->tagName) {
        foreach ($element->childNodes as $e) {
          if (isset($e->tagName) && '' != $e->tagName) {
            return $this->getFeaturedImgAlt($dom_xpath, $e);
          }
        }
      }
    } else {
      $featured_img_elements = $dom_xpath->query("//*[contains(concat(' ', @class, ' '),'c-picture')]");
      foreach ($featured_img_elements as $elem) {
        if ('img' != $elem->tagName) {
          foreach ($elem->childNodes as $e) {
            return $this->getFeaturedImgAlt($dom_xpath, $e);
          }
        }
      }
    }
    return '';
  }
}
new TBMDownloadPMCLists();
