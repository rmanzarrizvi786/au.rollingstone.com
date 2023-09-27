<?php

/**
 * Plugin Name: TBM Ads Manager
 * Plugin URI: https://thebrag.media/
 * Description:
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI:
 */

class TBMAds
{

  protected $plugin_title;
  protected $plugin_name;
  protected $plugin_slug;

  protected static $_instance;

  public function __construct()
  {

    $this->plugin_title = 'TBM Ads';
    $this->plugin_name = 'tbm_ads';
    $this->plugin_slug = 'tbm-ads';

    add_action('wp_enqueue_scripts', [$this, 'action_wp_enqueue_scripts']);
    add_action('wp_head', [$this, 'action_wp_head']);
  }

  /*
  * Enqueue JS
  */
  public function action_wp_enqueue_scripts()
  {
    wp_enqueue_script('adm-fuse', 'https://cdn.fuseplatform.net/publift/tags/2/2375/fuse.js', [], '1');
  }

  /*
  * WP Head
  */
  public function action_wp_head()
  {
    // if (!is_home() && !is_front_page()) 
    {
?>
      <script type="text/javascript">
        const fusetag = window.fusetag || (window.fusetag = {
          que: []
        });

        fusetag.que.push(function() {
          googletag.pubads().enableSingleRequest();
          googletag.enableServices();
        });
      </script>
<?php
    }
  }

  /*
  * Singleton
  */
  public static function get_instance()
  {
    if (!isset(static::$_instance)) {
      static::$_instance = new TBMAds();
    }
    return static::$_instance;
  }

  /*
  * Get Ad Tag
  */
  public function get_ad($ad_location = '', $slot_no = 0, $post_id = null, $device = '', $ad_width = '')
  {
    if ('' == $ad_location)
      return;

    $html = '';
    $fuse_tags = self::fuse_tags();

    if (isset($_GET['screenshot'])) {
      $pagepath = 'screenshot';
    } else if (isset($_GET['dfp_key'])) {
      $pagepath = $_GET['dfp_key'];
    } else if (is_home() || is_front_page()) {
      $pagepath = 'homepage';
    } else {
      $pagepath_uri = substr(str_replace(['/', 'beta'], '', $_SERVER['REQUEST_URI']), 0, 40);
      $pagepath_e = explode('?', $pagepath_uri);
      $pagepath = $pagepath_e[0];
    }

    if (function_exists('amp_is_request') && amp_is_request()) {
      if (isset($fuse_tags['amp'][$ad_location]['sticky']) && $fuse_tags['amp'][$ad_location]['sticky']) {
        $html .= '<amp-sticky-ad layout="nodisplay">';
      }
      $html .= '<amp-ad
        width=' . $fuse_tags['amp'][$ad_location]['width']
        . ' height=' . $fuse_tags['amp'][$ad_location]['height']
        . ' type="doubleclick"'
        . ' data-slot="' . $fuse_tags['amp']['network_id'] . $fuse_tags['amp'][$ad_location]['slot'] . '"'
        . '></amp-ad>';
      if (isset($fuse_tags['amp'][$ad_location]['sticky']) && $fuse_tags['amp'][$ad_location]['sticky']) {
        $html .= '</amp-sticky-ad>';
      }
      return $html;
    } else {

      if (in_array($ad_location, ['mrec1', 'mrec_1'])) {
        $ad_location = 'rail1';
      } elseif (in_array($ad_location, ['mrec2', 'mrec_2'])) {
        $ad_location = 'rail2';
      }

      $fuse_id = null;

      $post_type = get_post_type(get_the_ID());

      $section = 'homepage';
      if (is_home() || is_front_page()) {
        $section = 'homepage';
      } elseif (is_category()) {
        $term = get_queried_object();
        if ($term) {
          $category_parent_id = $term->category_parent;
          if ($category_parent_id != 0) {
            $category_parent = get_term($category_parent_id, 'category');
            $category = $category_parent->slug;
          } else {
            $category = $term->slug;
          }
        }
        $section = 'category';
      } elseif (is_archive()) {
        $section = 'category';
      } elseif (in_array($post_type, ['post', 'snaps', 'photo_gallery'])) {
        $section = 'article';
        if ($slot_no == 2) {
          $section = 'second_article';
        }

        $categories = get_the_category($post_id);
        if ($categories) {
          foreach ($categories as $category_obj) :
            $category = $category_obj->slug;
            break;
          endforeach;
        }
      }


      if (isset($section)) {
        $fuse_id = $fuse_tags[$section][$ad_location];
      } else {
        $fuse_id = $fuse_tags[$ad_location];
      }
      $html .= '<!--' . $post_id . ' | '  . $section . ' | ' . $ad_location . ' | ' . $slot_no . '-->';
      $html .= '<div data-fuse="' . $fuse_id . '"></div>';

      if ($slot_no > 1) {
        $html .= '<script>
      fusetag.que.push(function(){
        fusetag.loadSlotById("' . $fuse_id . '");
       });
       </script>';
      } else {
        $html .= '<script type="text/javascript">
        fusetag.setTargeting("fuse_category", ["' . $category . '"]);
        fusetag.setTargeting("pagepath", ["' . $pagepath . '"]);
        </script>';
      }

      if (isset($category)) {
        /* $html .= '<script type="text/javascript">
      fusetag.que.push(function() {
         fusetag.setTargeting("fuse_category", ["' . $category . '"]);
      });
      </script>'; */
      }

      return $html;
    }
  }

  private static function fuse_tags()
  {
    return [
      'amp' => [
        'network_id' => '/22071836792/SSM_rollingstone/',
        'header' => [
          'width' => 320,
          'height' => 50,
          'slot' => 'AMP_Header',
        ],
        'mrec_1' => [
          'width' => 300,
          'height' => 250,
          'slot' => 'AMP_mrec_1',
        ],
        'mrec_2' => [
          'width' => 300,
          'height' => 250,
          'slot' => 'AMP_mrec_2',
        ],
        'sticky_footer' => [
          'width' => 320,
          'height' => 50,
          'slot' => 'AMP_sticky_footer',
          'sticky' => true
        ]
      ],
      'article' => [
        'skin' =>   '22378668622',
        'leaderboard' =>   '22378668229',

        'mrec' =>   '22378668643',
        'rail1' =>   '22378668643',

        'vrec' =>   '22378668619',
        'rail2' =>   '22378668619',

        'incontent_1' =>   '22378668628',
        'inbody1' =>   '22378668628',

        'incontent_2' =>   '22378668637',
        'desktop_sticky' =>   '22378668640',
        'mob_sticky' =>   '22378668232',
      ],
      'second_article' => [
        'skin' =>   '22378668646',
        'leaderboard' =>   '22378668652',

        'mrec' =>   '22378668655',
        'rail1' =>   '22378668655',

        'vrec' =>   '22378668238',
        'rail2' =>   '22378668238',

        'incontent_1' =>   '22378668649',
        'inbody1' =>   '22378668649',

        'incontent_2' =>   '22378668241',
      ],
      'category' => [
        'skin' =>   '22378668631',

        'leaderboard' =>   '22378668610',

        'mrec' =>   '22378668616',
        'rail1' =>   '22378668616',

        'vrec' =>   '22377804353',
        'rail2' =>   '22377804353',

        'desktop_sticky' =>   '22378668235',
        'mob_sticky' =>   '22378668634',
      ],
      'homepage' => [
        'desktop_sticky' =>   '22378668589',

        'vrec_1' =>   '22378668214',
        'rail1' =>   '22378668214',

        'vrec_2' =>   '22378668586',
        'rail2' =>   '22378668586',

        'vrec_3' =>   '22378668583',
        'vrec_4' =>   '22378668592',
        'vrec_5' =>   '22378668595',
        'vrec_6' =>   '22377804323',
        'vrec_7' =>   '22378668217',

        'header' =>   '22378668580',
        'leaderboard' =>   '22378668580',

        'skin' =>   '22378668601',

        'incontent_1' =>   '22378668598',
        'inbody1' =>   '22378668598',

        'incontent_2' =>   '22378668223',
        'inbody2' =>   '22378668223',

        'incontent_3' =>   '22378668613',

        'incontent_4' =>   '22378668604',
        'incontent_5' =>   '22378668220',

        'incontent_6' =>   '22378553511',

        'mob_sticky' =>   '22378668226',
      ]
    ];
  }
}

TBMAds::get_instance();