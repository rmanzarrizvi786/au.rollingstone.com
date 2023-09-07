<?php

/**
 * Plugin Name: Theme Options
 * Plugin URI: https://thebrag.media/
 * Description: Theme Options
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI: https://thebrag.media/
 */

add_action('admin_menu', 'tbm_theme_options_plugin_menu');
function tbm_theme_options_plugin_menu()
{
    add_menu_page('Theme Options', 'Theme Options', 'edit_pages', 'tbm_theme_options', 'tbm_theme_options');
}

add_action('rest_api_init', 'tbm_theme_options_rest_api_init');

function tbm_theme_options_rest_api_init()
{
    register_rest_route('tbm', '/latest', array(
        'methods' => 'GET',
        'callback' => 'rest_get_latest',
        'permission_callback' => '__return_true',
    ));

    register_rest_route('tbm', '/most-read', array(
        'methods' => 'GET',
        'callback' => 'rest_get_most_read',
        'permission_callback' => '__return_true',
    ));
}

function string_limit_words($string, $word_limit)
{
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit) {
        array_pop($words);
        return implode(' ', $words) . '...';
    }
    return implode(' ', $words);
}

function rest_get_most_read()
{
    $articles_arr = array();
    $trending_story_args = [
        'post_status' => 'publish',
        'posts_per_page' => 1,
    ];
    if (get_option('most_viewed_yesterday')) {
        $trending_story_args['p'] = get_option('most_viewed_yesterday');
    }
    $trending_story_query = new WP_Query($trending_story_args);
    if ($trending_story_query->have_posts()) :
        while ($trending_story_query->have_posts()) :
            $trending_story_query->the_post();
            $trending_story_ID = get_the_ID();
            $exclude_posts[] = $trending_story_ID;
            $args['exclude_posts'][] = $trending_story_ID;
        endwhile;
        wp_reset_query();
    endif;

    if (!is_null($trending_story_ID) && $trending_story_ID != '') :
        $trending_story = get_post($trending_story_ID);
        if ($trending_story) :
            $categories = get_the_category($trending_story);

            $trending_story_image_id = get_post_thumbnail_id($trending_story->ID);
            $trending_story_src = wp_get_attachment_image_src($trending_story_image_id, 'large');
            $trending_story_alt_text = get_post_meta(get_post_thumbnail_id($trending_story->ID), '_wp_attachment_image_alt', true);
            if ($trending_story_alt_text == '') {
                $trending_story_alt_text = trim(strip_tags(get_the_title()));
            }

            $excerpt = trim($trending_story->trending_story_alt_text) != '' ? $trending_story->trending_story_alt_text : string_limit_words(get_the_excerpt(), 25);

            $articles_arr[] = [
                'image' => $trending_story_src[0],
                'title' => $trending_story->post_title,
                'category' => $categories[0]->name,
                'brand_logo' => 'https://images.thebrag.com/common/brands/au.rollingstone.png',
                'brand_link' => 'https://au.rollingstone.com',
                'excerpt' =>  $excerpt,
                'link' => get_the_permalink(),
            ];            
        endif; // If Trending Story
    endif;

    return $articles_arr;
}

function rest_get_latest()
{
    global $post;

    $trending_story_args = [
        'post_status' => 'publish',
        'posts_per_page' => 1,
    ];
    if (get_option('most_viewed_yesterday')) {
        $trending_story_args['p'] = get_option('most_viewed_yesterday');
    }
    $trending_story_query = new WP_Query($trending_story_args);
    if ($trending_story_query->have_posts()) :
        while ($trending_story_query->have_posts()) :
            $trending_story_query->the_post();
            $trending_story_ID = get_the_ID();
            $exclude_posts[] = $trending_story_ID;
            $args['exclude_posts'][] = $trending_story_ID;
        endwhile;
        wp_reset_query();
    endif;

    $posts_per_page = 6;
    $news_args = array(
        'post_status' => 'publish',
        'post_type' => array('post', 'snaps', 'dad'),
        'ignore_sticky_posts' => 1,
        'post__not_in' => $exclude_posts,
        'posts_per_page' => $posts_per_page,
    );
    $news_query = new WP_Query($news_args);
    $no_of_columns = 2;
    if ($news_query->have_posts()) :
        $count = 1;
        $articles_arr = [
            'read_more' => [
                'title' => 'Read More',
                'link' => 'https://au.rollingstone.com',
            ],
            'articles' => []
        ];

        while ($news_query->have_posts()) :
            $news_query->the_post();
            $post_id = get_the_ID();

            $category = '';

            if ('snaps' == $post->post_type) :
                $category = 'GALLERY';
            elseif ('dad' == $post->post_type) :
                $categories = get_the_terms(get_the_ID(), 'dad-category');
                if ($categories) :
                    if ($categories[0] && 'Uncategorised' != $categories[0]->name) :
                        $category = $categories[0]->name;
                    elseif (isset($categories[1])) :
                        $category = $categories[1]->name;
                    else :
                    endif; // If Uncategorised 
                endif; // If there are Dad categories 
            else :
                $categories = get_the_category();
                if ($categories) :
                    if (isset($categories[0]) && 'Evergreen' != $categories[0]->cat_name) :
                        if (0 == $categories[0]->parent) :
                            $category = $categories[0]->cat_name;
                        else : $parent_category = get_category($categories[0]->parent);
                            $category = $parent_category->cat_name;
                        endif;
                    elseif (isset($categories[1])) :
                        if (0 == $categories[1]->parent) :
                            $category = $categories[1]->cat_name;
                        else : $parent_category = get_category($categories[1]->parent);
                            $category = $parent_category->cat_name;
                        endif;
                    endif; // If Evergreen 
                endif; // If there are Dad categories 
            endif; // If Photo Gallery 
            
            // Image
            // Title
            // Brand logo
            // Brand Link
            // Excerpt
            // Link

            $image = '' !== get_the_post_thumbnail() ? get_the_post_thumbnail_url() : '';
            $metadesc = get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true);
            $excerpt = trim($metadesc) != '' ? $metadesc : get_the_excerpt();

            $articles_arr['articles'][] = [
                'image' => $image,
                'title' => get_the_title(),
                'category' => $category,
                'brand_logo' => 'https://images.thebrag.com/common/brands/Rolling-Stone-Australia-light.png',
                'brand_link' => 'https://au.rollingstone.com',
                'excerpt' => $excerpt,
                'link' => get_the_permalink(),
            ];
            
            $count++;
        endwhile;
    endif;

    return $articles_arr;
}

function tbm_theme_options()
{
    // wp_enqueue_script( 'bs', get_template_directory_uri() . '/bs/js/bootstrap.bundle.min.js', array( 'jquery' ), '20190424', true );
    wp_enqueue_style('bs', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css');
    // wp_enqueue_style( 'edm-mailchimp', plugin_dir_url( __FILE__ ) . '/css/style.css' );

    // wp_enqueue_script( 'td-jquery-autocomplete', get_template_directory_uri() . '/js/jquery.auto-complete.min.js', array( 'jquery' ), NULL, true );
    // wp_enqueue_script( 'td-options-ajax-search', get_template_directory_uri() . '/js/scripts-admin.js', array( 'jquery' ), NULL, true );

    wp_enqueue_script('tbm-theme-options', plugin_dir_url(__FILE__) . '/js/scripts.js', array('jquery'), '20190429', true);

    /*
     * Save options
     */

    if (isset($_POST['force_most_viewed'])) :
        $force_most_viewed = absint($_POST['force_most_viewed']);
        if ($force_most_viewed > 0) :
            update_option('force_most_viewed', absint($_POST['force_most_viewed']));
            update_option('most_viewed_yesterday', absint($_POST['force_most_viewed']));
        else :
            update_option('force_most_viewed', '');
        endif;
    endif; // force_most_viewed

    if (isset($_POST) && count($_POST) > 0) :
        foreach ($_POST as $key => $value) :
            if (strpos($key, 'tbm_') !== false) :
                update_option($key, sanitize_text_field($value));
            endif;
        endforeach;
        echo '<div class="alert alert-success">Options have been saved!</div>';
    endif;
?>
    <style>
        label.reset {
            background: #ccc;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            color: #fff;
            text-align: center;
        }
    </style>

    <div class="container-fluid">
        <h1>Theme Options</h1>
        <form method="post" class="form">
            <div class="row">
                <!-- <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <h3>Video of the week</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Link URL</label>
                                <label class="reset">x</label>
                                <input name="tbm_featured_video_link" id="tbm_featured_video_link" type="text" value="<?php echo stripslashes(get_option('tbm_featured_video_link')); ?>" placeholder="https://" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>YouTube URL</label>
                                <label class="reset">x</label>
                                <input name="tbm_featured_video" id="tbm_featured_video" type="text" value="<?php echo stripslashes(get_option('tbm_featured_video')); ?>" placeholder="https://" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Featured Video Artist Title</label>
                                <label class="reset">x</label>
                                <input name="tbm_featured_video_artist" id="tbm_featured_video_artist" type="text" value="<?php echo stripslashes(get_option('tbm_featured_video_artist')); ?>" placeholder="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Featured Video Song Title</label>
                                <label class="reset">x</label>
                                <input name="tbm_featured_video_song" id="tbm_featured_video_song" type="text" value="<?php echo stripslashes(get_option('tbm_featured_video_song')); ?>" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- Video of the week -->

                <!-- <div class="col-md-6">
                    <div class="row">
                        <div class="col-12">
                            <h3>Record of the week</h3>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Artist</label>
                                <label class="reset">x</label>
                                <input name="tbm_featured_album_artist" id="tbm_featured_album_title" type="text" value="<?php // echo stripslashes(get_option('tbm_featured_album_artist')); 
                                                                                                                            ?>" placeholder="" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Title</label>
                                <label class="reset">x</label>
                                <input name="tbm_featured_album_title" id="tbm_featured_album_title" type="text" value="<?php // echo stripslashes(get_option('tbm_featured_album_title')); 
                                                                                                                        ?>" placeholder="" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Image URL</label>
                                <label class="reset">x</label>
                                <input type="text" name="tbm_featured_album_image_url" id="tbm_featured_album_image_url" class="form-control" value="<?php // echo get_option('tbm_featured_album_image_url') != '' ? get_option('tbm_featured_album_image_url')  : ''; 
                                                                                                                                                        ?>">
                                <?php
                                /* if (function_exists('wp_enqueue_media')) {
                                    wp_enqueue_media();
                                } else {
                                    wp_enqueue_style('thickbox');
                                    wp_enqueue_script('media-upload');
                                    wp_enqueue_script('thickbox');
                                } */
                                ?>
                                <?php // if (get_option('tbm_featured_album_image_url') != '') : 
                                ?>
                                    <img src="<?php // echo get_option('tbm_featured_album_image_url'); 
                                                ?>" width="100" id="tbm_featured_album_image" class="img-fluid d-block">
                                <?php // endif; 
                                ?>
                                <button id="btn-featured-album-image" type="button" class="button">Upload / Select from Library</button>
                            </div>

                            <div class="form-group">
                                <label>Link</label>
                                <label class="reset">x</label>
                                <input name="tbm_featured_album_link" id="tbm_featured_album_link" type="text" value="<?php // echo stripslashes(get_option('tbm_featured_album_link')); 
                                                                                                                        ?>" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- Record of the week -->
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-12">
                            <h3>Last Issue Cover</h3>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Heading</label>
                                <label class="reset">x</label>
                                <input name="tbm_last_issue_heading" id="tbm_last_issue_heading" type="text" value="<?php echo stripslashes(get_option('tbm_last_issue_heading')); ?>" placeholder="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Image URL</label>
                                <label class="reset">x</label>
                                <input name="tbm_last_issue_cover" id="tbm_last_issue_cover" type="text" value="<?php echo stripslashes(get_option('tbm_last_issue_cover')); ?>" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-12">
                            <h3>Current Issue Cover</h3>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Heading</label>
                                <label class="reset">x</label>
                                <input name="tbm_current_issue_heading" id="tbm_current_issue_heading" type="text" value="<?php echo stripslashes(get_option('tbm_current_issue_heading')); ?>" placeholder="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Image URL</label>
                                <label class="reset">x</label>
                                <input name="tbm_current_issue_cover" id="tbm_current_issue_cover" type="text" value="<?php echo stripslashes(get_option('tbm_current_issue_cover')); ?>" placeholder="" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Social Share Image URL</label>
                                <label class="reset">x</label>
                                <input name="tbm_current_issue_cover_social" id="tbm_current_issue_cover_social" type="text" value="<?php echo stripslashes(get_option('tbm_current_issue_cover_social')); ?>" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row">
                        <div class="col-12">
                            <h3>Next Issue Cover</h3>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Heading</label>
                                <label class="reset">x</label>
                                <input name="tbm_next_issue_heading" id="tbm_next_issue_heading" type="text" value="<?php echo stripslashes(get_option('tbm_next_issue_heading')); ?>" placeholder="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Image URL</label>
                                <label class="reset">x</label>
                                <input name="tbm_next_issue_cover" id="tbm_next_issue_cover" type="text" value="<?php echo stripslashes(get_option('tbm_next_issue_cover')); ?>" placeholder="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Thumb Image URL</label>
                                <label class="reset">x</label>
                                <input name="tbm_next_issue_cover_thumb" id="tbm_next_issue_cover_thumb" type="text" value="<?php echo stripslashes(get_option('tbm_next_issue_cover_thumb')); ?>" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12 col-md-6">
                    <h3>Featured Article for Infinite Scroll</h3>

                    <div class="form-group">
                        <label>Post ID</label>
                        <label class="reset">x</label>
                        <input name="tbm_featured_infinite_ID" id="tbm_featured_infinite_ID" type="text" value="<?php echo stripslashes(get_option('tbm_featured_infinite_ID')); ?>" placeholder="" class="form-control">
                    </div>
                </div>
                <!-- Featured Article for Infinite Scroll ID -->

                <div class="col-12 col-md-6">
                    <h3>Force Trending on Home page</h3>

                    <div class="form-group">
                        <label>Post ID</label>
                        <label class="reset">x</label>
                        <input name="force_most_viewed" id="force_most_viewed" type="number" value="<?php echo stripslashes(get_option('force_most_viewed')); ?>" placeholder="" class="form-control">
                    </div>
                </div>
                <!-- Force Trending on Home page -->
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <h4>Home Page Featured Article at position 2 ID<br><br></h4>

                    <div class="form-group">
                        <label>Post ID</label>
                        <label class="reset">x</label>
                        <input name="tbm_home_middle_1_ID" id="tbm_home_middle_1_ID" type="text" value="<?php echo stripslashes(get_option('tbm_home_middle_1_ID')); ?>" placeholder="" class="form-control">
                    </div>
                </div>
                <!-- Featured Article for Infinite Scroll ID -->

                <div class="col-12 col-md-6">
                    <h4>Home Page Featured Article at position 3 ID<br><small>If #2 is missing, this will be placed at #2</small></h4>

                    <div class="form-group">
                        <label>Post ID</label>
                        <label class="reset">x</label>
                        <input name="tbm_home_middle_2_ID" id="tbm_home_middle_2_ID" type="number" value="<?php echo stripslashes(get_option('tbm_home_middle_2_ID')); ?>" placeholder="" class="form-control">
                    </div>
                </div>
                <!-- Force Trending on Home page -->
            </div>

            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-12">
                        <h3>DailyMotion Player</h3>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Player ID</label>
                            <label class="reset">x</label>
                            <input name="tbm_floating_dm_player_id" id="tbm_floating_dm_player_id" type="text" value="<?php echo get_option('tbm_floating_dm_player_id'); ?>" placeholder="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Playlist ID</label>
                            <label class="reset">x</label>
                            <input name="tbm_floating_dm_playlist_id" id="tbm_floating_dm_playlist_id" type="text" value="<?php echo get_option('tbm_floating_dm_playlist_id'); ?>" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-12">
                        <h3>GAM Ad Unit</h3>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>ID</label>
                                <label class="reset">x</label>
                                <input name="tbm_gam_ad_unit_id" id="tbm_gam_ad_unit_id" type="text" value="<?php echo get_option('tbm_gam_ad_unit_id'); ?>" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <input type="submit" name="submit" id="submit-campaign" class="button button-primary" value="Save">
                </div>
            </div>
        </form>
    </div>
<?php
}

/*
 * API to update theme options remotely from thebrag.com WP
 */
function tbm_update_theme_options_remote_func($data)
{
    if ('cacU1r_3wUpusw9cadltIratL8+glt*s' == $_POST['auth_key']) :
        $data = $_POST['data'];
        if (is_array($data) && count($data) > 0) :
            foreach ($data as $key => $value) :
                if (strpos($key, 'tbm_') !== false) :
                    update_option($key, sanitize_text_field($value));
                endif;
            endforeach;
        endif;
    endif;
}
add_action('rest_api_init', function () {
    register_rest_route('api/v1', '/update_theme_options', array(
        'methods' => 'POST',
        'callback' => 'tbm_update_theme_options_remote_func',
        'permission_callback' => '__return_true',
    ));
});

add_action('edit_form_after_title', function ($post) {
    $screen = get_current_screen();
    if ($screen->id != 'post') {
        return;
    }
    if ($post && ('post' != $post->post_type || 'publish' == $post->post_status)) {
        return;
    }
?>
    <div style="background-color: lightyellow; padding: 0.25rem 0.5rem">
        <h3>Checklist:</h3>
        <ol>
            <li>Does this article adhere EXACTLY to the Crib Notes for this publication?</li>
            <li>If it’s a news piece: Was it first published by another publication within the last hour?</li>
            <li>If it was first published over an hour ago, do you have an original & exclusive angle?!</li>
            <li>Are there any opps to add in a link to a relevant Observer newsletter?</li>
        </ol>
    </div>
<?php
});

// Coil - Monetize content
/* add_action('wp_head', function () {
    echo '<meta name="monetization" content="$ilp.uphold.com/68Q7DryfNX4d">';
}); */

class TBM_WP_HTML_Compression
{
    // Settings
    protected $compress_css = true;
    protected $compress_js = false;
    protected $info_comment = false;
    protected $remove_comments = true;

    // Variables
    protected $html;
    public function __construct($html)
    {
        if (!empty($html)) {
            $this->parseHTML($html);
        }
    }
    public function __toString()
    {
        return $this->html;
    }
    protected function bottomComment($raw, $compressed)
    {
        $raw = strlen($raw);
        $compressed = strlen($compressed);

        $savings = ($raw - $compressed) / $raw * 100;

        $savings = round($savings, 2);

        return '<!--HTML compressed, size saved ' . $savings . '%. From ' . $raw . ' bytes, now ' . $compressed . ' bytes-->';
    }
    protected function minifyHTML($html)
    {
        $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
        $overriding = false;
        $raw_tag = false;
        // Variable reused for output
        $html = '';
        foreach ($matches as $token) {
            $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

            $content = $token[0];

            if (is_null($tag)) {
                if (!empty($token['script'])) {
                    $strip = $this->compress_js;
                } else if (!empty($token['style'])) {
                    $strip = $this->compress_css;
                } else if ($content == '<!--wp-html-compression no compression-->') {
                    $overriding = !$overriding;

                    // Don't print the comment
                    continue;
                } else if ($this->remove_comments) {
                    if (!$overriding && $raw_tag != 'textarea') {
                        // Remove any HTML comments, except MSIE conditional comments
                        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                    }
                }
            } else {
                if ($tag == 'pre' || $tag == 'textarea') {
                    $raw_tag = $tag;
                } else if ($tag == '/pre' || $tag == '/textarea') {
                    $raw_tag = false;
                } else {
                    if ($raw_tag || $overriding) {
                        $strip = false;
                    } else {
                        $strip = true;

                        // Remove any empty attributes, except:
                        // action, alt, content, src
                        $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);

                        // Remove any space before the end of self-closing XHTML tags
                        // JavaScript excluded
                        $content = str_replace(' />', '/>', $content);
                    }
                }
            }

            if ($strip) {
                $content = $this->removeWhiteSpace($content);
            }

            $html .= $content;
        }

        return $html;
    }

    public function parseHTML($html)
    {
        $this->html = $this->minifyHTML($html);

        if ($this->info_comment) {
            $this->html .= "\n" . $this->bottomComment($html, $this->html);
        }
    }

    protected function removeWhiteSpace($str)
    {
        $str = str_replace("\t", ' ', $str);
        $str = str_replace("\n",  '', $str);
        $str = str_replace("\r",  '', $str);

        while (stristr($str, '  ')) {
            $str = str_replace('  ', ' ', $str);
        }

        return $str;
    }
}

function tbm_wp_html_compression_finish($html)
{
    return new TBM_WP_HTML_Compression($html);
}

function tbm_wp_html_compression_start()
{
    ob_start('tbm_wp_html_compression_finish');
}
// add_action('get_header', 'tbm_wp_html_compression_start');

// JS to make BB sticky
add_action('wp_footer', function () {
    if (
        is_page_template("page-templates/rs-awards-nominate-2022.php")
        ||
        is_page_template("page-templates/rs-awards-2022.php")
        ||
        is_page_template("page-templates/page-gmoat-vote-2022.php")
        ||
        is_page_template('page-templates/jim-beam-2022.php')
        ||
        (isset($_SERVER['REQUEST_URI']) && in_array($_SERVER['REQUEST_URI'], ['/subscribe-magazine/', '/subscribe-magazine-staging/']))
    ) {
        return;
    }
?>
    <script>
        fusetag.onSlotRenderEnded((e) => {
            if (e.slotId === 'fuse-slot-22378668580-1' || e.slotId === 'fuse-slot-22378668229-1') {
                googletag.pubads().addEventListener('slotRenderEnded', function(event) {
                    const slot = event.slot
                    if (slot.getSlotElementId() === 'fuse-slot-22378668580-1' || slot.getSlotElementId() === 'fuse-slot-22378668229-1') {
                        if (event.creativeId === 138373276463) {
                            const skin = document.getElementById('skin')
                            skin.style.setProperty('display', 'none', 'important')

                            const ad_billboard = parent.document.querySelector('.c-ad--desktop-header')

                            ad_billboard.style.position = 'fixed'
                            ad_billboard.style.zIndex = 999
                            ad_billboard.style.bottom = '15px'
                            ad_billboard.style.transform = 'translateX(-50%)';
                            ad_billboard.style.left = '50%';

                            setTimeout(function() {
                                ad_billboard.style.bottom = '0'
                                ad_billboard.style.position = 'relative'
                            }, 6000)
                        }
                    }
                })
            }
        })
    </script>
<?php
});

// Brand lift study
add_action('wp_footer', function () {
    return;
    if (
        is_page_template("page-templates/rs-awards-nominate-2022.php")
        ||
        is_page_template("page-templates/rs-awards-2022.php")
        ||
        is_page_template('page-templates/stream.php')
        ||
        (isset($_SERVER['REQUEST_URI']) && in_array($_SERVER['REQUEST_URI'], ['/subscribe-magazine/', '/subscribe-magazine-staging/']))
    ) {
        return;
    }
?>
    <script>
        jQuery(document).ready(function($) {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: {
                    'action': 'tbm_set_cookie',
                    'key': 'tbm_sm_seen',
                    'value': 'true',
                    'duration': '<?php echo 60 * 60 * 24 * 30; ?>'
                }
            });
        }); {
            var tbm_e = document.cookie,
                tbm_t = tbm_e.indexOf("; tbm_sm_seen=");
            if (-1 == tbm_t) {
                {
                    const e = (e, t, n, s) => {
                            var c, o, r;
                            e.SMCX = e.SMCX || [], t.getElementById(s) || (o = (c = t.getElementsByTagName(n))[c.length - 1], (r = t.createElement(n)).type = "text/javascript", r.async = !0, r.id = s, r.src = "https://widget.surveymonkey.com/collect/website/js/tRaiETqnLgj758hTBazgd36CitCEEwoE44pTCPBWttcrfN2mODXNCsr6H61j_2BkMD.js", o.parentNode.insertBefore(r, o))
                        },
                        t = (e, t, n, s) => {
                            var c, o, r;
                            e.SMCX = e.SMCX || [], t.getElementById(s) || (o = (c = t.getElementsByTagName(n))[c.length - 1], (r = t.createElement(n)).type = "text/javascript", r.async = !0, r.id = s, r.src = "https://widget.surveymonkey.com/collect/website/js/tRaiETqnLgj758hTBazgd36CitCEEwoE44pTCPBWtteffxwhXTTNQIUFZGZf1MZH.js", o.parentNode.insertBefore(r, o))
                        };
                    null !== (() => {
                        var e = document.cookie,
                            t = e.indexOf("; tbm_v=");
                        if (-1 == t) {
                            if (0 != (t = e.indexOf("tbm_v="))) return null
                        } else {
                            t += 2;
                            var n = document.cookie.indexOf(";", t); - 1 == n && (n = e.length)
                        }
                        return decodeURI(e.substring(t + "tbm_v=".length, n))
                    })() ? (console.log("cookie"), e(window, document, "script", "smcx-sdk")) : (console.log("no cookie"))
                }
            }
        }
    </script>
    <style>
        .smcx-modal:before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin-top: -100%;
            margin-bottom: -100%;
            background: rgba(0, 0, 0, .75);
            margin-left: -100%;
            margin-right: -100%;
        }

        .smcx-modal-header,
        .smcx-modal>.smcx-modal-content,
        .smcx-widget-footer {
            background-color: #2E073E;
        }

        .smcx-modal-header {
            border-radius: 5px 5px 0 0;
        }

        .smcx-modal>.smcx-modal-content {
            border-radius: 0;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 10px !important;
        }

        @media (max-width: 600px) {
            .smcx-widget.smcx-modal>.smcx-modal-content {
                width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding: 0 2% !important;
            }
        }

        .smcx-widget-footer {
            border-radius: 0 0 5px 5px;
        }
    </style>
<?php
});

// Live Stream
add_action('wp_footer', function () {
    if (
        is_page_template("page-templates/rs-awards-nominate-2022.php")
        ||
        is_page_template("page-templates/rs-awards-2022.php")
        ||
        is_page_template('page-templates/stream.php')
        ||
        (isset($_SERVER['REQUEST_URI']) && in_array($_SERVER['REQUEST_URI'], ['/subscribe-magazine/', '/subscribe-magazine-staging/']))
    ) {
        return;
    }

    date_default_timezone_set('Australia/Sydney');
    if (time() < strtotime('2021-12-05 11:00:00') || time() > strtotime('2021-12-05 18:00:00')) {
        return;
    }
?>
    <div class="videoWrapper">
        <div class="togglePlayer">
            <h3>Streaming Now</h3>
            <button class="btn-close"><img src="https://cdn.thebrag.com/icons/chevron-thin-left.svg" width="10" height="9" alt="▼"></button>
            <button class="btn-open"><img src="https://cdn.thebrag.com/icons/chevron-thin-left.svg" width="10" height="9" alt="▼"></button>
        </div>
        <div id="player"></div>
    </div>
    <style>
        .videoWrapper {
            z-index: 3000;
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            min-width: 350px;
            width: 35%;
            max-width: 100%;
            background-color: #000;
        }

        .videoWrapper .togglePlayer {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .videoWrapper h3 {
            color: #fff;
            margin: .5rem;
        }

        .videoWrapper button.btn-close,
        .videoWrapper button.btn-open {
            color: #fff;
            text-align: right;
            padding: .5rem;
            font-family: Graphik, sans-serif;
            display: none;
        }

        .videoWrapper button.btn-close img,
        .videoWrapper button.btn-open img {
            width: 16px;
        }

        .videoWrapper button.btn-close {
            transform: rotate(-90deg);
        }

        .videoWrapper button.btn-open {
            transform: rotate(90deg);
        }

        @media (max-width: 36rem) {
            .videoWrapper {
                right: 0;
                bottom: 4rem;
                padding-bottom: .25rem;
                width: 100%;
            }
        }
    </style>
    <script src="https://cdn.jwplayer.com/libraries/GSyPNv61.js"></script>
    <script type="text/JavaScript">
        jQuery(document).ready(function($){
            var playerActive = localStorage.getItem('streamOpen');

            jwplayer("player").setup({
                "playlist": [
                    {
                        "sources": [
                            {
                                "type": "hls",
                                "file": "https://cdn3.wowza.com/1/d2laNFBUSTdXOEo5/VGtNdlRG/hls/live/playlist.m3u8",
                            }
                        ],
                        "image": 'https://cdn.thebrag.com/videos/RSAU200SONOSholder_v1.jpg'
                    }
                ],
                "aboutlink": "https://au.rollingstone.com",
                "primary": "html5",
                "hlshtml": true,
                "autostart": false,
                "loop": true,
                "cast": {},
                "autoPause": {
                    "viewability": false,
                    "pauseAds": false
                }
            });

            jwplayer("player").on('ready', function() {
                if(playerActive == "1" || playerActive == null) {
                    $('#player').show();
                    $('.videoWrapper .togglePlayer').find('.btn-open').hide();
                    $('.videoWrapper button.btn-close').show();
                } else {
                    $('#player').hide();
                    $('.videoWrapper .togglePlayer').find('.btn-close').hide();
                    $('.videoWrapper button.btn-open').show();
                }
             });
            
            $('.videoWrapper .togglePlayer').on('click', function() {
                if(playerActive == "1" || playerActive == null) {
                    $('#player').hide();
                    $(this).find('.btn-close').hide();
                    $('.videoWrapper button.btn-open').show();
                    jwplayer().pause();
                } else {
                    $('#player').show();
                    $(this).find('.btn-open').hide();
                    $('.videoWrapper button.btn-close').show();
                    jwplayer().play();
                }

                playerActive = playerActive == "1" ? "0" : "1";
                localStorage.setItem('streamOpen', playerActive);
            });
        });
        
    </script>
<?php
});

add_action('admin_post_thumbnail_html', function ($content, $post_id, $thumbnail_id) {
    $html = '<div style="background-color: lightyellow; padding: 0.25rem">
    <em>Recommended size: 1200 x 630 (px)</em>
    </div>';
    return  $content . $html;
}, 10, 3);
