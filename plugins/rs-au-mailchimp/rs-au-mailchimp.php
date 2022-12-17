<?php
/**
 * Plugin Name: Rolling Stone AU MailChimp Integration
 * Plugin URI: https://thebrag.media
 * Description: Rolling Stone AU MailChimp Integration
 * Version: 1.0.0
 * Author: Sachin Patel
 */
use \DrewM\MailChimp\MailChimp;

class RSAU_MailChimp {
  protected $plugin_name;
  protected $plugin_slug;

  protected $mailchimp_listid;
  protected $defaults;

  public function __construct() {
      $this->plugin_name = 'rsau_mailchimp';
      $this->plugin_slug = 'rsau-mailchimp';

      $this->mailchimp_listid = '435b42b91d';
      $this->defaults['subject'] = 'Rolling Stone Australia Newsletter';
      $this->defaults['title'] = 'RS AU Newsletter #';
      $this->defaults['replyto'] = 'noreply@thebrag.media';
      $this->defaults['from_name'] = 'Rolling Stone Australia';


      add_action('admin_menu', array( $this, 'admin_menu' ) );

      add_action( 'wp_ajax_save_newsletter', array( $this, 'save_newsletter' ) );
      add_action( 'wp_ajax_nopriv_save_newsletter', array( $this, 'save_newsletter' ) );

      add_action( 'wp_ajax_save_edm_settings', array( $this, 'save_edm_settings' ) );
      add_action( 'wp_ajax_nopriv_save_edm_settings', array( $this, 'save_edm_settings' ) );

      add_action( 'wp_ajax_get_remote_data', array( $this, 'get_remote_data' ) );
      add_action( 'wp_ajax_nopriv_get_remote_data', array( $this, 'get_remote_data' ) );

      add_action('wp_ajax_search_posts', array( $this, 'search_posts' ) );
      add_action('wp_ajax_nopriv_search_posts', array( $this, 'search_posts' ) );

      add_action( 'rest_api_init', function () {
        /* register_rest_route( 'tbm_mailchimp/v1', '/promoter', array(
          'methods' => 'GET',
          'callback' => array( $this,  'tbm_edm_promoter_func' ),
        ) ); */
        register_rest_route( 'tbm_mailchimp/v1', '/adlinks', array(
          'methods' => 'GET',
          'callback' => array( $this,  'tbm_edm_adlinks_func' ),
        ) );
      } );

  }

  public function admin_menu() {
      add_menu_page('RS AU MailChimp', 'RS AU MailChimp', 'edit_posts', $this->plugin_slug, [ $this, 'index' ], 'dashicons-email', 10);
      add_submenu_page( $this->plugin_slug, 'New Newsletter', 'Create', 'edit_posts', $this->plugin_slug . '-create', [ $this, 'create' ] );
      add_submenu_page( $this->plugin_slug, 'EDM Settings', 'EDM Settings', 'edit_posts', $this->plugin_slug.'-edm_settings', [ $this, 'edm_settings' ] );
  }

  public function edm_settings() {
  ?>
  <div class="wrap">
  <?php
      include ( plugin_dir_path( __FILE__ ) . 'edm-settings.php');
  ?>
  </div>
  <?php
  }

  public function create() {
  ?>
    <div class="wrap">
      <?php include ( plugin_dir_path( __FILE__ ) . 'form-newsletter.php'); ?>
    </div>
  <?php
  }

  public function index() {
  ?>
  <div class="wrap">
    <?php
      global $wpdb;
      $id = isset( $_GET['id'] ) ? $_GET['id'] : null;
      $table = $wpdb->prefix . "edms";

      if ( isset( $_GET['preview'] ) ):
        if ( ! is_null( $id ) ):
          include ( plugin_dir_path( __FILE__ ) . 'preview-newsletter.php');
        else:
          echo 'Newsletter not found.';
        endif;
      elseif ( isset( $_GET['copy'] ) ) :
        if ( !is_null( $id ) ):
          $clone = $wpdb->get_row( "SELECT * FROM $table WHERE id = {$id}" );
          $today =  date( 'j F, Y', strtotime( current_time( 'mysql' ) ) );
          $clone->details = json_decode( $clone->details );
          unset( $clone->details->id );
          $clone->details->date_for = $today;
          $clone->details->subject = 'Rolling Stone AU Newsletter (' . $today . ')';

          if ( isset( $clone->details->title ) ) :
            $title_parts = explode( '#', $clone->details->title );
            $clone->details->title = $title_parts[0] . '#' . ( $title_parts[1] + 1);
          else:
            $clone->details->title = 'Tucker Bag #';
          endif;

          $clone->details = json_encode( $clone->details );
          $wpdb->insert(
            $table,
            array(
              'date_for' => date( 'Y-m-d', strtotime( current_time( 'mysql' ) ) ),
              'details' => $clone->details,
              'created_at' => current_time( 'mysql' ),
              'updated_at' => current_time( 'mysql' ),
            ),
            array(
              '%s', '%s', '%s', '%s',
            )
          );
          ?>
          <script>
            window.location = '?page=' . $this->plugin_slug . '/' . $this->plugin_slug . '.php&edit=1&id=<?php echo $wpdb->insert_id; ?>';
          </script>
          <?php
        endif;
      elseif ( isset( $_GET['edit'] ) ) :
        if ( !is_null( $id ) ):
          $newsletter = $wpdb->get_row( "SELECT * FROM $table WHERE id = {$id}" );
          $newsletter->details = json_decode( $newsletter->details );
        endif;
        include ( plugin_dir_path( __FILE__ ) . 'form-newsletter.php');
      elseif ( isset( $_GET['delete'] ) ):
        $wpdb->delete( $table, array( 'id' => $id) );
        ?>
        <script>
          window.location = '?page=<?php echo $this->plugin_slug; ?>'
        </script>
      <?php
      elseif ( isset( $_GET['create-on-mc'] ) ):
        if ( !is_null( $id ) ):
          ob_start();
          $action = 'create-on-mc';
          include(plugin_dir_path( __FILE__ ) . 'preview-newsletter.php');
          $html = ob_get_contents();
          ob_end_clean();

          // echo $html; exit;

          require_once( plugin_dir_path( __FILE__ ) . 'mailchimp-api/MailChimp.php');
          $api_key = '727643e6b14470301125c15a490425a8-us1';
          $MailChimp = new MailChimp( $api_key );

          $newsletter = $wpdb->get_row( "SELECT * FROM $table WHERE id = {$id}" );
          $newsletter->details = json_decode( $newsletter->details );

          if ( $newsletter->details->subject == '' ) {
            $newsletter->details->subject = $this->default_subject;
          }

          $data = array(
            "type" => "regular",
            "recipients" => array(
              "list_id" => $this->mailchimp_listid,
            ),
            "settings" => array(
              "subject_line" => $newsletter->details->subject,
              "preview_text" => $newsletter->details->preview_text,
              "title" => $newsletter->details->title,
              "reply_to" => $newsletter->details->reply_to,
              "from_name" => $newsletter->details->from_name
            ),
          );

          $campaign = $MailChimp->post( 'campaigns', $data );
          $campaign_id = $campaign['id'];

          $content = array(
            'html' => $html,
          );
          $MailChimp->put( 'campaigns/' . $campaign_id . '/content', $content );

          $wpdb->update( $table, array( 'status' => '1' ), array ( 'id' => $newsletter->id ) );
          ?>
          <script>
            window.location = '?page=<?php echo $this->plugin_slug; ?>'
          </script>
          <?php
        endif;
      else:
        include ( plugin_dir_path( __FILE__ ) . 'list.php');
      endif;
    ?>
  </div>
  <?php
  } // Index

  public function save_newsletter() {
    $errors = array();

    if ( strlen( $_POST['data'] ) > 0 ):
      parse_str($_POST['data'], $data);
      if ( strtotime( $data['date_for'] ) < strtotime( date('Y-m-d') ) ):
        $errors[] = 'Date must be today or in future.';
      endif;

      if ( $data['cover_story_image'] ) :
        $data['cover_story_image'] = $this->resize_image( $data['cover_story_image'], 660, 370 );
      endif;

      if ( $data['featured_story_image_1'] ) :
        $data['featured_story_image_1'] = $this->resize_image( $data['featured_story_image_1'], 660, 370 );
      endif;

      if ( $data['featured_story_image_2'] ) :
        $data['featured_story_image_2'] = $this->resize_image( $data['featured_story_image_2'], 660, 370 );
      endif;

      if ( isset( $data['post_images'] ) ) :
        foreach ( $data['post_images'] as $key => $image_url ) :
          if ( $image_url ) :
            $data['post_images'][$key] = $this->resize_image( $image_url, 660, 370 );
          endif;
        endforeach;
      endif;

      if ( count( $errors ) == 0 ):
        if ( isset( $data['posts'] ) && count( $data['posts'] ) > 0 )
          asort( $data['posts'] );

        $data = stripslashes_deep( $data );

        global $wpdb;
        $table = $wpdb->prefix . "edms";

        if ( isset( $data['id'] ) ):
          $wpdb->update(
            $table,
            array(
                'date_for' => date( 'Y-m-d', strtotime( $data['date_for'] ) ),
                'details' => json_encode( $data ),
                'status' => '0',
                'updated_at' => current_time( 'mysql' ),
            ),
            array ( 'id' => $data['id'] ) );
        else:
          $wpdb->insert(
            $table,
            array(
                'date_for' => date( 'Y-m-d', strtotime( $data['date_for'] ) ),
                'details' => json_encode( $data ),
                'created_at' => current_time( 'mysql' ),
                'updated_at' => current_time( 'mysql' ),
            ),
            array(
                '%s', '%s', '%s', '%s',
            )
          );
        endif;

        echo json_encode( array( 'success' => true ) );
      endif; // If no errors
    else:
      $errors[] = 'Incomplete data.';
    endif; // If there is post data
    if ( count( $errors ) > 0 )
      echo json_encode( array( 'errors' => $errors ) );
    die();
  } // save_newsletter

  public function save_edm_settings() {
    $errors = array();
    if ( count( $_POST['data'] ) > 0 ):
      parse_str($_POST['data'], $data);
      $data = stripslashes_deep( $data );

      foreach( $data as $k => $v ) :
        if ( is_array( $v ) ) :
          $data[$k] = json_encode( $v );
        endif;
      endforeach;
    else:
      $errors[] = 'Incomplete data.';
    endif; // If there is post data
    if ( count( $errors ) > 0 ) {
      echo json_encode( array( 'errors' => $errors ) ); wp_die();
    } else {
      foreach ( $data as $key => $value ) :
        update_option( $key, $value );
      endforeach;

      echo json_encode( array( 'success' => true ) ); wp_die();
    }
  }

  function resize_image( $url, $thumb_width, $thumb_height, $import_dir_part = NULL, $filename = NULL ) {
      $dir = wp_upload_dir();

      if( is_null( $import_dir_part ) ) {
          $import_dir_part = '/edm/' . date('Y-m/d/');
      }
      $import_dir =  $dir['basedir'] . $import_dir_part;
      if ( ! is_dir( $import_dir ) )
          wp_mkdir_p( $import_dir );
      $img = $import_dir . basename( $url );
      file_put_contents( $img, file_get_contents( $url ) );

      $explode = explode( ".", basename( $url ) );
      $filetype = end( $explode );

      if ($filetype == 'jpg') {
          $image = imagecreatefromjpeg("$img");
      } else
      if ($filetype == 'jpeg') {
          $image = imagecreatefromjpeg("$img");
      } else
      if ($filetype == 'png') {
          $image = imagecreatefrompng("$img");
      } else
      if ($filetype == 'gif') {
          $image = imagecreatefromgif("$img");
      }

      if ( is_null( $filename ) ) {
        $filename = str_replace( '.' . $filetype, '.jpg', basename( $url ) );
      }
      $filepath = $import_dir . $filename;

      $width = imagesx($image);
      $height = imagesy($image);
      $original_aspect = $width / $height;
      $thumb_aspect = $thumb_width / $thumb_height;
      if ( $original_aspect >= $thumb_aspect ) {
         // If image is wider than thumbnail (in aspect ratio sense)
         $new_height = $thumb_height;
         $new_width = $width / ($height / $thumb_height);
      } else {
         // If the thumbnail is wider than the image
         $new_width = $thumb_width;
         $new_height = $height / ($width / $thumb_width);
      }
      $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
      // Resize and crop
      imagecopyresampled($thumb,
                         $image,
                         0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                         0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                         0, 0,
                         $new_width, $new_height,
                         $width, $height);
      imagejpeg($thumb, $filepath, 80);
      $upload    = wp_upload_dir();
      $base_url = $upload['baseurl'] . $import_dir_part;
  //    @unlink( $img );
      return $base_url . $filename;
  } // resize_image

  public function get_remote_data() {
    if ( strlen( $_POST['data'] ) > 0 ):
      parse_str($_POST['data'], $data);
      $sites_html = file_get_contents( $data['url'] );
      $html = new DOMDocument();
      @$html->loadHTML($sites_html);
      $meta_og_title = $meta_og_img = $meta_og_description = null;
      foreach( $html->getElementsByTagName('meta') as $meta ) {
        if( $meta->getAttribute( 'property' ) == 'og:image' ){
          if ( ! isset( $meta_og_img ) ) {
            $meta_og_img = $meta->getAttribute('content');
          }
        }
        if( $meta->getAttribute( 'property' )=='og:title' ){
          if ( ! isset( $meta_og_title ) ) {
            $meta_og_title = $meta->getAttribute('content');
          }
        }
        if( $meta->getAttribute( 'property' )=='og:description' ) {
          if ( ! isset( $meta_og_description ) ) {
            $meta_og_description = $meta->getAttribute('content');
          }
        }
      }
      echo json_encode( array( 'success' => true, 'title' => trim( $meta_og_title ), 'description' => trim( $meta_og_description ), 'image' => trim( $meta_og_img ) ) );
      die();
    endif;
    echo json_encode( array( 'success' => false, 'url' => $data['url'] ) );
    die();
  } // get_remote_data

  public function search_posts(){
    $post_type = isset( $_POST['type'] ) ? $_POST['type'] : 'any';
    $args = array(
      'post_type' => $post_type,
      'post_status' => 'publish',
      's' => $_POST['term'],
      'posts_per_page' => 10,
    );

    if ( isset( $_POST['after'] ) ) {
      $args['date_query'] = array(
        'after' => date_i18n( 'Y-m-d', strtotime( '- 30 days' ) )
      );
    }

    $query = new WP_Query( $args );

    $return = array();

    if($query->have_posts()){
      while ($query->have_posts()) {
        $query->the_post();
        $excerpt = get_the_excerpt(); // string_limit_words(get_the_excerpt(), 30);
        if ( get_field( 'thumbnail_ext_url' ) ) {
          $thumbnail = get_field( 'thumbnail_ext_url' );
        } else {
          $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
          $thumbnail = $thumbnail[0];
        }

        $metadesc = get_post_meta( get_the_ID(), '_yoast_wpseo_metadesc', true );
        $return[] = [get_the_ID(), get_the_title(), get_the_permalink(), get_the_date('d M, Y'), $excerpt, $thumbnail, $metadesc];
      }
    }
    echo json_encode( $return );
    wp_reset_postdata();
    die();
  } // search_posts

  public function tbm_edm_promoter_func($data) {
    $return = array();
    $date = isset( $_GET['date'] ) ? $_GET['date'] : NULL;

    if( is_null( $date ) ) :
      return $return;
    endif;

    global $wpdb;

    $newsletter = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}edms WHERE date_for = '{$date}' AND status = '1' ORDER BY id DESC LIMIT 1 ");

    if ( ! $newsletter || ! $newsletter->promoter_articles ) :
      return $return;
    endif;

    $return = json_decode( $newsletter->promoter_articles );

    return $return;
  }

  public function tbm_edm_adlinks_func($data) {
    $return = array();
    $date = isset( $_GET['date'] ) ? $_GET['date'] : NULL;

    if( is_null( $date ) ) :
      return $return;
    endif;

    global $wpdb;

    $newsletter = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}edms WHERE date_for = '{$date}' AND status = '1' ORDER BY id DESC LIMIT 1 ");

    if ( ! $newsletter || ! $newsletter->ad_links ) :
      return $return;
    endif;

    $ad_links = json_decode( $newsletter->ad_links );

    if( $ad_links && is_array( $ad_links ) ) :

      ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');

      foreach( $ad_links as $ad_link ) :
        $sites_html = file_get_contents( $ad_link );
        $html = new DOMDocument();
        @$html->loadHTML($sites_html);
        $meta_og_title = $meta_og_img = $meta_og_description = null;
        foreach( $html->getElementsByTagName('meta') as $meta ) {
          if( $meta->getAttribute( 'property' )=='og:title' ){
            if ( ! isset( $meta_og_title ) ) {
              array_push( $return, array(
                $ad_link,
                $meta->getAttribute('content')
              ) );
            }
          }
        }
      endforeach;
    endif;

    // $return = json_decode( $return );

    return $return;
  }
} // END of Class RSAU_MailChimp


/*
add_filter( 'the_posts', function( $posts, \WP_Query $query ) {
    if( $pick = $query->get( '_shuffle_and_pick' ) ) {
        shuffle( $posts );
        $posts = array_slice( $posts, 0, (int) $pick );
    }
    return $posts;
}, 10, 2 );
*/

new RSAU_MailChimp();
