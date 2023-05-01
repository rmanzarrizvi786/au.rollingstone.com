<?php

/**
 * Child theme initialization file.
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2018-03-05
 */

define('CHILD_THEME_PATH', get_stylesheet_directory());
define('RS_THEME_URL', get_stylesheet_directory_uri());
define('RS_THEME_VERSION', '1.63');

// July 2 2018, 6:00 AM
define('PMC_LEGACY_DATE', mktime(6, 0, 0, 7, 2, 2018));


/**
 * Use for social share twitter icon.
 * Ex. like @variety.
 */
if (!defined('PMC_TWITTER_SITE_USERNAME')) {
    define('PMC_TWITTER_SITE_USERNAME', 'RollingStone');
}

// Enable site map on demand generation if rebuild is required.
define('PMC_SITEMAP_REBUILD_ON_DEMAND', 1);


// add_action( 'after_setup_theme', 'rolling_stone_init', 1 );

/**
 * Temporary function to exclude wpcom-thumbnail-editor from RS
 * This plugin is loaded by core-v2 and has to be excluded from RS
 * Adding this here because RS class is set to run on after_setup_theme
 * hook which is too late for this.
 */
function rollingstone_exclude_plugins(array $plugins = [])
{

    if (empty($plugins['plugins'])) {

        $plugins['plugins'] = [];
    }

    $plugins['plugins'][]     = 'wpcom-thumbnail-editor';
    $plugins['pmc-plugins'][] = 'pmc-gallery-v3';

    return $plugins;
}

add_filter('load_pmc_plugins_exclude', 'rollingstone_exclude_plugins');

add_filter('send_password_change_email', '__return_false');

// add_action( 'wp_enqueue_scripts', [ $this, 'site_css' ] );



require_once CHILD_THEME_PATH . '/inc-core/pmc-global-functions/classes/traits/trait-singleton.php';

require_once CHILD_THEME_PATH . '/inc/classes/class-rewrites.php';

require_once CHILD_THEME_PATH . '/inc/classes/class-rs-query.php';

require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-lists/classes/class-lists.php';
require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-lists/classes/class-list-post.php';
require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-lists/pmc-lists.php';
require_once CHILD_THEME_PATH . '/inc/classes/class-lists.php';
require_once CHILD_THEME_PATH . '/inc/classes/class-list-nav.php';

require_once CHILD_THEME_PATH . '/class-pmc.php';
require_once CHILD_THEME_PATH . '/class-theme-setup.php';
require_once CHILD_THEME_PATH . '/inc/classes/menus/class-header-nav-walker.php';
require_once CHILD_THEME_PATH . '/inc/classes/menus/class-mega-nav-walker.php';
require_once CHILD_THEME_PATH . '/inc/classes/menus/class-footer-nav-walker.php';
require_once CHILD_THEME_PATH . '/inc/classes/menus/class-social-nav-walker.php';
require_once CHILD_THEME_PATH . '/inc/classes/menus/class-social-header-nav-walker.php';
require_once CHILD_THEME_PATH . '/inc/classes/menus/class-social-round-nav-walker.php';
require_once CHILD_THEME_PATH . '/inc/classes/menus/class-category-nav-walker.php';


require_once CHILD_THEME_PATH . '/inc/classes/menus/class-video-nav-walker.php';

require_once CHILD_THEME_PATH . '/inc-core/helpers/gallery-helpers.php';
require_once CHILD_THEME_PATH . '/inc-core/pmc-global-functions/classes/class-pmc-cache.php';
require_once CHILD_THEME_PATH . '/inc-core/classes/meta/class-byline.php';

require_once CHILD_THEME_PATH . '/inc-core/pmc-global-functions/php/pmc-image.php';

require_once CHILD_THEME_PATH . '/inc-core/classes/widgets/traits/trait-templatize.php';

require_once CHILD_THEME_PATH . '/inc-core/classes/class-top-posts.php';

require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-options/pmc-options.php';
require_once CHILD_THEME_PATH . '/pmc-plugins/fm-widgets/fm-widgets.php';
require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-carousel/pmc-carousel.php';
require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-linkcontent/pmc-linkcontent.php';
require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-carousel-widget/pmc-carousel-widget.php';
require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-primary-taxonomy/pmc-primary-taxonomy.php';
require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-styled-heading/pmc-styled-heading.php';
require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-top-videos-v2/pmc-top-videos-v2.php';

require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-google-tagmanager/pmc-google-tagmanager.php';

// require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-gallery-v4/pmc-gallery-v4.php';

// require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-google-amp/classes/single-post.php';
// require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-google-amp/pmc-google-amp.php';


// require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-review/pmc-review.php';



// require_once CHILD_THEME_PATH . '/inc-core/pmc-global-functions/classes/class-pmc-cheezcap.php';
// require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-swiftype/classes/plugin.php';
// require_once CHILD_THEME_PATH . '/pmc-plugins/pmc-swiftype/classes/widget.php';



require_once CHILD_THEME_PATH . '/inc/classes/class-widgets.php';
require_once CHILD_THEME_PATH . '/inc/classes/class-pagination.php';
require_once CHILD_THEME_PATH . '/inc/classes/widgets/class-trending.php';
require_once CHILD_THEME_PATH . '/inc/classes/widgets/class-features.php';
require_once CHILD_THEME_PATH . '/inc/classes/widgets/class-section.php';
require_once CHILD_THEME_PATH . '/inc/classes/widgets/class-editors-picks.php';
require_once CHILD_THEME_PATH . '/inc/classes/widgets/class-video-record-of-week.php';
require_once CHILD_THEME_PATH . '/inc/classes/widgets/class-video-top-featured.php';
require_once CHILD_THEME_PATH . '/inc/classes/widgets/class-video-featured.php';
require_once CHILD_THEME_PATH . '/inc/classes/widgets/class-video-gallery.php';
require_once CHILD_THEME_PATH . '/inc/classes/widgets/class-video-playlist.php';

require_once CHILD_THEME_PATH . '/inc/classes/class-breadcrumbs.php';

require_once CHILD_THEME_PATH . '/inc/classes/class-author.php';

// require_once CHILD_THEME_PATH . '/inc/classes/class-reviews.php';

require_once CHILD_THEME_PATH . '/inc/classes/class-media.php';
require_once CHILD_THEME_PATH . '/inc/classes/class-rs-query.php';
require_once CHILD_THEME_PATH . '/inc/classes/class-carousels.php';
require_once CHILD_THEME_PATH . '/inc/classes/class-category.php';

require_once CHILD_THEME_PATH . '/inc/classes/pages/class-page-404.php';

require_once CHILD_THEME_PATH . '/inc/helpers/template-tags.php';
require_once CHILD_THEME_PATH . '/inc/helpers/callable-functions.php';




//EOF


function brands()
{
    $pub_logos = [
        'the-brag' => [
            'title' => 'The Brag',
            'link' => 'https://thebrag.com/',
            'logo_name' => 'The-Brag_combo',
            'ext' => 'svg',
        ],
        'brag-jobs' => [
            'title' => 'The Brag Jobs',
            'link' => 'https://thebrag.com/jobs',
            'logo_name' => 'The-Brag-Jobs',
            'width' => 80,
            'ext' => 'png',
        ],
        /*'dbu' => [
            'title' => 'Don\'t Bore Us',
            'link' => 'https://dontboreus.thebrag.com/',
            'logo_name' => 'Dont-Bore-Us',
            'ext' => 'svg',
        ],
        'tio' => [
            'title' => 'The Industry Observer',
            'link' => 'https://theindustryobserver.thebrag.com/',
            'logo_name' => 'The-Industry-Observer',
            'ext' => 'svg',
        ], */
        'rolling-stone-australia' => [
            'title' => 'Rolling Stone Australia',
            'link' => 'https://au.rollingstone.com/',
            'logo_name' => 'Rolling-Stone-Australia',
            'ext' => 'png',
        ],
        'tone-deaf' => [
            'title' => 'Tone Deaf',
            'link' => 'https://tonedeaf.thebrag.com/',
            'logo_name' => 'Tone-Deaf',
            'ext' => 'svg',
            'width' => 80
        ],
        'tmn' => [
            'title' => 'The Music Network',
            'link' => 'https://themusicnetwork.com/',
            'logo_name' => 'TMN',
            'ext' => 'svg',
            'width' => 80
        ],
        'variety-au' => [
            'title' => 'Variety Australia',
            'link' => 'https://au.variety.com/',
            'logo_name' => 'Variety-Australia',
            'ext' => 'svg',
            'width' => 120
        ],
    ];
    return $pub_logos;
} // brands()

function brands_network()
{
    $pub_logos = [
        /**
         * EPIC
         */
        'lwa' => [
            'title' => 'Life Without Andy',
            'link' => 'https://lifewithoutandy.com/',
            'logo_name' => 'lwa',
            'ext' => 'png',
            'width' => 60
        ],
        'hypebeast' => [
            'title' => 'Hypebeast',
            'link' => 'https://hypebeast.com/',
            'logo_name' => 'Hypebeast',
            'ext' => 'png',
        ],
        'funimation' => [
            'title' => 'Funimation',
            'link' => 'https://www.funimation.com/',
            'logo_name' => 'Funimation',
            'ext' => 'png',
        ],
        'crunchyroll' => [
            'title' => 'Crunchyroll',
            'link' => 'https://www.crunchyroll.com/en-gb',
            'logo_name' => 'Crunchyroll',
            'ext' => 'png',
        ],
        'enthusiast' => [
            'title' => 'Enthusiast Gaming',
            'link' => 'https://www.enthusiastgaming.com/',
            'logo_name' => 'enthusiast',
            'ext' => 'png',
        ],
        'gamelancer' => [
            'title' => 'Gamelancer',
            'link' => 'https://gamelancer.com/',
            'logo_name' => 'Gamelancer',
            'ext' => 'png',
        ],
        'toongoggles' => [
            'title' => 'ToonGoggles',
            'link' => 'https://www.toongoggles.com/',
            'logo_name' => 'ToonGoggles',
            'ext' => 'png',
        ],
        'kidoodle' => [
            'title' => 'kidoodle',
            'link' => 'https://www.kidoodle.tv/',
            'logo_name' => 'kidoodle',
            'ext' => 'png',
        ],

        /**
         * PMC
         */
        'artnews' => [
            'title' => 'ARTnews',
            'link' => 'https://www.artnews.com/',
            'logo_name' => 'ARTnews',
        ],
        'bgr' => [
            'title' => 'BGR',
            'link' => 'https://bgr.com/',
            'logo_name' => 'bgr',
            'width' => 80
        ],
        'billboard' => [
            'title' => 'Billboard',
            'link' => 'https://billboard.com/',
            'logo_name' => 'billboard',
        ],
        'deadline' => [
            'title' => 'Deadline',
            'link' => 'https://deadline.com/',
            'logo_name' => 'DEADLINE',
        ],
        'dirt' => [
            'title' => 'Dirt',
            'link' => 'https://www.dirt.com/',
            'logo_name' => 'Dirt',
            'width' => 80
        ],
        'footwear' => [
            'title' => 'Footwear News',
            'link' => 'https://footwearnews.com/',
            'logo_name' => 'FootwearNews',
            'width' => 60
        ],
        'gold-derby' => [
            'title' => 'Gold Derby',
            'link' => 'https://www.goldderby.com/',
            'logo_name' => 'GoldDerby',
        ],
        'indiewire' => [
            'title' => 'IndieWire',
            'link' => 'https://www.indiewire.com/',
            'logo_name' => 'IndieWire',
        ],
        'sheknows' => [
            'title' => 'SheKnows',
            'link' => 'https://www.sheknows.com/',
            'logo_name' => 'SheKnows',
        ],
        'sourcing-journal' => [
            'title' => 'Sourcing Journal',
            'link' => 'https://sourcingjournal.com/',
            'logo_name' => 'SourcingJournal',
        ],
        'sportico' => [
            'title' => 'Sportico',
            'link' => 'https://www.sportico.com/',
            'logo_name' => 'Sportico',
        ],
        'spy' => [
            'title' => 'Spy',
            'link' => 'https://spy.com/',
            'logo_name' => 'Spy',
            'width' => 120,
        ],
        'stylecaster' => [
            'title' => 'Stylecaster',
            'link' => 'https://stylecaster.com/',
            'logo_name' => 'Stylecaster',
        ],
        'the-hollywood-reporter' => [
            'title' => 'The Hollywood Reporter',
            'link' => 'https://www.hollywoodreporter.com/',
            'logo_name' => 'The-Hollywood-Reporter',
        ],
        'tvline' => [
            'title' => 'TVLine',
            'link' => 'https://tvline.com/',
            'logo_name' => 'TVLine',
            'width' => 120,
        ],
        /* 'variety' => [
            'title' => 'Variety',
            'link' => 'https://variety.com/',
            'logo_name' => 'Variety',
            'width' => 120,
        ], */
        'vibe' => [
            'title' => 'VIBE',
            'link' => 'https://www.vibe.com/',
            'logo_name' => 'Vibe',
            'width' => 120,
        ],
    ];
    return $pub_logos;
} // brands_network()

// add_action('wp_footer', 'inject_roymorgan', 99, 2);
function inject_roymorgan()
{
?>
    <script type="text/javascript">
        jQuery(function() {
            var cachebuster = Date.now();
            var script = document.createElement('script');
            script.src = 'https://pixel.roymorgan.com/stats_v2/Tress.php?u=k7b7oit54p&ca=20005195&a=6id59hbq' + '&cb=' + cachebuster;
            script.async = true;
            document.body.appendChild(script);
        });
    </script>
<?php
}

/*
 * Restrict Image Upload Size
 */
add_filter('wp_handle_upload_prefilter', 'ssm_limit_image_size');
function ssm_limit_image_size($file)
{
    $errors = array();
    if (strpos($file['type'], 'image') !== false) {
        $filename = $file['name'];
        if (
            strpos(str_replace(array('-', '_', ' '), '', strtolower($filename)), 'screenshot') !== false
            ||
            strpos(str_replace(array('-', '_', ' '), '', strtolower($filename)), 'untitled') !== false
        ) {
            array_push($errors, '(+) Please rename the file before uploading.');
        }

        // Calculate the image size in KB
        $file_size = $file['size'] / 1024;

        $image = getimagesize($file['tmp_name']);
        $maximum = array(
            'width' => '2000',
            'height' => '2000'
        );
        $image_width = $image[0];
        $image_height = $image[1];

        if ($image_width > $maximum['width'] || $image_height > $maximum['height']) {
            array_push($errors, '(+) Image dimensions are too large. Maximum size is ' . $maximum['width'] . ' x ' . $maximum['height'] . ' pixels. Uploaded image is ' . $image_width . ' x ' . $image_height . ' pixels.');
        }

        // File size limit in KB
        $limit = 500;

        if (($file_size > $limit))
            array_push($errors, '(+) Uploaded file is too large. It has to be smaller than ' . $limit . 'KB');
    }
    if (!empty($errors)) {
        $file['error'] = implode(" ", $errors);
    }
    return $file;
}

/*
 * Top stories JSON
 */

function ssm_top_stories_json_func() {
    $return = array();

    $posts_per_page = isset($_GET['size']) ? (int) $_GET['size'] : 5;
    $paged = isset($_GET['page']) ? (int) $_GET['page'] : 1;

    $timezone = new DateTimeZone('Australia/Sydney');

    global $wpdb;

    $trending_posts = $wpdb->get_results( "SELECT post_id FROM {$wpdb->prefix}tbm_trending ORDER BY created_at DESC LIMIT 15" );

    if ( ! $trending_posts ) {
        return [];
    }


    $trending_post_ids = [];
    $trending_post_ids = array_merge( $trending_post_ids, wp_list_pluck( $trending_posts, 'post_id' ) );

    $posts = new WP_Query(
        [
            'post_type' => [ 'post', 'list', 'page' ],
            'post__in' => $trending_post_ids,
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged
        ]
    );

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
                'site' => get_bloginfo( 'name' ),
            );
        }
    }
    return $return;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'api/v2', '/articles/trending', array(
        'methods' => 'GET',
        'callback' => 'ssm_top_stories_json_func',
        'permission_callback' => '__return_true',
    ) );
} );

add_action('wp_footer', 'inject_ga4', 99, 2);
function inject_ga4()
{
?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-L8V4HEDPRH"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-L8V4HEDPRH');
    </script>
    <?php
}

// Exclude password protected posts from the main query

add_action( 'pre_get_posts', 'prefix_exclude_password_protected_posts' );
function prefix_exclude_password_protected_posts( $query ) {
    if ( ! $query->is_singular() && ! is_admin() ) {
        $query->set( 'has_password', false );
    }
}