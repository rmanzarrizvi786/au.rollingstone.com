<?php
/*
   Plugin Name: TBM Google Analytics
   Plugin URI:
   description:
   Version: 1.0
   Author: Sachin Patel
   Author URI:
*/

function tbm_ga_articles_count($data)
{
    //    var_dump( $_GET );
    $return = array();
    $from = isset($_GET['from']) ? date_i18n('Y-m-d', strtotime('-1 day', strtotime(trim($_GET['from'])))) : NULL;
    $to = isset($_GET['to']) ? date_i18n('Y-m-d', strtotime('+1 day', strtotime(trim($_GET['to'])))) : NULL;
    $query_author = isset($_GET['author']) ? trim($_GET['author']) : NULL;
    if (is_null($from) || is_null($to))
        return $return;
    $posts = new WP_Query(array(
        'date_query' => array(
            'after' => $from,
            'before' => $to,
        ),
        'post_type' => array('post'),
        'post_status' => 'publish',
        'posts_per_page' => -1
    ));

    global $post;
    if ($posts->have_posts()) {
        return $posts->post_count;
    }
    return $return; //$posts->post_count;
}

function tbm_ga_articles($data)
{
    //    var_dump( $_GET );
    $return = array();

    // $from = isset($_GET['from']) ? date_i18n('Y-m-d', strtotime('-1 day', strtotime(trim($_GET['from'])))) : NULL;
    // $to = isset($_GET['to']) ? date_i18n('Y-m-d', strtotime('+1 day', strtotime(trim($_GET['to'])))) : NULL;

    $from = isset($_GET['from']) ? date_i18n('c', strtotime(trim($_GET['from']))) : NULL;
    $to = isset($_GET['to']) ? date_i18n('c', strtotime(trim($_GET['to']))) : NULL;

    $query_author = isset($_GET['author']) ? trim($_GET['author']) : NULL;
    if (is_null($from) || is_null($to))
        return $return;

    $args = array(
        'date_query' => array(
            'after' => date('c', strtotime($from)),
            'before' => date('c', strtotime($to)),
        ),
        'post_type' => $post_types,
        'post_status' => 'publish',
        'posts_per_page' => -1
    );

    $posts = new WP_Query($args);

    global $post;
    if ($posts->have_posts()) {
        while ($posts->have_posts()) {
            $posts->the_post();
            $url = get_the_permalink();
            $url_parsed = parse_url($url);
            $author = get_field('Author') ? get_field('Author') : (get_field('author') ? get_field('author') : get_the_author());
            $post_categories = wp_get_post_categories(get_the_ID());
            $category_names = array();
            if (count($post_categories) > 0) :
                foreach ($post_categories as $c) :
                    $cat = get_category($c);
                    array_push($category_names, $cat->name);
                endforeach;
            endif;

            if (!is_null($query_author)) {
                if (strpos(strtolower($author), strtolower($query_author)) === false) {
                    continue;
                }
            }

            $return[] = array(
                'ID' => get_the_ID(),
                'url' => $url,
                'path' => $url_parsed['path'],
                'publish_date' => get_the_date(),
                'publish_datetime' => get_the_time('Y-m-d H:i:s'),
                'author' => $author,
                'categories' => $category_names
            );
        }
    }
    return $return; //$posts->post_count;
}

add_action('rest_api_init', function () {
    register_rest_route('tbm_ga/v1', '/articles_count', array(
        'methods' => 'GET',
        'callback' => 'tbm_ga_articles_count',
        'permission_callback' => '__return_true',
    ));

    register_rest_route('ssm_ga/v1', '/articles', array(
        'methods' => 'GET',
        'callback' => 'tbm_ga_articles',
        'permission_callback' => '__return_true',
    ));
});

/*
 * Activation
 */
register_activation_hook(__FILE__, 'activate_tbm_ga');
function activate_tbm_ga()
{
    if (!wp_next_scheduled('cron_tbm_ga_update_pageviews', array(NULL, NULL))) {
        wp_schedule_event(time(), 'hourly', 'cron_tbm_ga_update_pageviews', array(NULL, NULL));
    }
}

/*
 * DeActivation
 */
register_deactivation_hook(__FILE__, 'deactivate_tbm_ga');
function deactivate_tbm_ga()
{
    $crons = _get_cron_array();
    if (empty($crons)) {
        return;
    }
    $hook = 'cron_tbm_ga_update_pageviews';
    foreach ($crons as $timestamp => $cron) {
        if (!empty($cron[$hook])) {
            unset($crons[$timestamp][$hook]);
        }
        if (empty($crons[$timestamp])) {
            unset($crons[$timestamp]);
        }
    }
    _set_cron_array($crons);
}

add_action('cron_tbm_ga_update_pageviews', 'tbm_ga_update_pageviews');

/*
 * Get pageviews and add the article with highest pageviews to the options
 */
add_action('admin_menu', 'admin_menu_tbm_ga_update_pageviews');
function admin_menu_tbm_ga_update_pageviews()
{
    add_management_page('Update Pageviews from GA', 'Update Pageviews from GA', 'administrator', 'tbm_ga_update_pageviews', 'tbm_ga_update_pageviews');
}
function tbm_ga_update_pageviews()
{

    date_default_timezone_set('Australia/NSW');
    $next_run_timestamp = wp_next_scheduled('cron_tbm_ga_update_pageviews', array(NULL, NULL));
    echo '<br>Scheduled automatic run is at ' . date('d-M-Y h:i:sa', $next_run_timestamp);
    echo '<br>Current Date/Time: ' . date('d-M-Y h:i:sa');

    // Load the Google API PHP Client Library.
    require_once __DIR__ . '/vendor/autoload.php';

    update_option('cron_run_most_viewed_yesterday', date('Y-m-d h:i:s'));

    $analytics = initializeAnalytics();

    $response = getReport($analytics);
    updateDB($response);

    exit;
}


/**
 * Initializes an Analytics Reporting API V4 service object.
 *
 * @return An authorized Analytics Reporting API V4 service object.
 */
function initializeAnalytics()
{
    $KEY_FILE_LOCATION = __DIR__ . '/service-account-credentials.json';

    // Create and configure a new client object.
    $client = new Google_Client();
    $client->setApplicationName("Page with most views in last 24 hours");
    $client->setAuthConfig($KEY_FILE_LOCATION);
    $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
    $analytics = new Google_Service_AnalyticsReporting($client);

    return $analytics;
}


/**
 * Queries the Analytics Reporting API V4.
 *
 * @param service An authorized Analytics Reporting API V4 service object.
 * @return The Analytics Reporting API V4 response.
 */
function getReport($analytics, $page_filter = NULL)
{
    date_default_timezone_set('Australia/Sydney');

    // Replace with your view ID, for example XXXX.
    $VIEW_ID = "210059008";

    // Create the ReportRequest object.
    $request = new Google_Service_AnalyticsReporting_ReportRequest();

    $request->setViewId($VIEW_ID);

    $current_hour = date('H');

    // Create the DateRange object.
    $dateRange = new Google_Service_AnalyticsReporting_DateRange();
    if ($current_hour >= 8) :
        $dateRange->setStartDate('today');
    else :
        $dateRange->setStartDate('yesterday');
    endif;
    $dateRange->setEndDate('today');

    $request->setDateRanges($dateRange);

    // Create the Metrics object.
    $pageviews = new Google_Service_AnalyticsReporting_Metric();
    $pageviews->setExpression("ga:pageViews");
    $pageviews->setAlias("pageViews");

    $request->setMetrics(array($pageviews));

    // Sorting (Ordering)
    $ordering = new Google_Service_AnalyticsReporting_OrderBy();
    $ordering->setFieldName("ga:pageviews");
    $ordering->setOrderType("VALUE");
    $ordering->setSortOrder("DESCENDING");

    $request->setOrderBys($ordering);

    // Set Country Filter
    $dimensionFilterCountry = new Google_Service_AnalyticsReporting_SegmentDimensionFilter();
    $dimensionFilterCountry->setDimensionName("ga:country");
    $dimensionFilterCountry->setOperator("EXACT");
    $dimensionFilterCountry->setExpressions(array('Australia'));
    // Create the DimensionFilterClauses
    $ga_dimensionFilterClause_country = new Google_Service_AnalyticsReporting_DimensionFilterClause();
    $ga_dimensionFilterClause_country->setFilters(array($dimensionFilterCountry));

    $dimensionFilterClauses[] = $ga_dimensionFilterClause_country;

    if (!is_null($page_filter)) :
        // Create the DimensionFilter.
        $dimensionFilter = new Google_Service_AnalyticsReporting_DimensionFilter();
        $dimensionFilter->setDimensionName('ga:pagePath');
        $dimensionFilter->setOperator('REGEXP');
        $dimensionFilter->setExpressions('/' . $page_filter . '/');
        //    $dimensionFilter->setNot(TRUE);
        // Create the DimensionFilterClauses
        $dimensionFilterClause = new Google_Service_AnalyticsReporting_DimensionFilterClause();
        $dimensionFilterClause->setFilters(array($dimensionFilter));

        $dimensionFilterClauses[] = $dimensionFilterClause;
    endif;

    $request->setDimensionFilterClauses($dimensionFilterClauses);

    //    $request->setDateRanges($dateRange);

    $request->setPageSize(40);

    // Creating multiple segments with function createSegment()
    $segments = [];

    if ($current_hour >= 8) :
        $segments[] = tbm_ga_createSegment((string) ($current_hour - 7), (string) $current_hour, 'After Midnight');
    else :
        $segments[] = tbm_ga_createSegment((string)(17 + $current_hour), '23', 'Before Midnight');
        $segments[] = tbm_ga_createSegment('00', (string) ($current_hour), 'After Midnight');
    endif;

    //    $segments[] = tbm_ga_createSegment('15', '23', 'Before Midnight');
    //    $segments[] = tbm_ga_createSegment('00', '10', 'After Midnight');

    //Create the hour dimension.
    $hour = new Google_Service_AnalyticsReporting_Dimension();
    $hour->setName("ga:hour");

    // Create the segment dimension.
    $segmentDimensions = new Google_Service_AnalyticsReporting_Dimension();
    $segmentDimensions->setName("ga:segment");

    //Create the Dimensions object.
    $pageDimension = new Google_Service_AnalyticsReporting_Dimension();
    $pageDimension->setName("ga:pagePath");

    //    $request->setDimensions(array($pageDimension));

    // Set the Segment to the ReportRequest object.
    $request->setDimensions(array($pageDimension, $hour, $segmentDimensions));
    $request->setSegments($segments);

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests(array($request));
    return $analytics->reports->batchGet($body);
}

/*
 * Create Segment function for GA
 */
function tbm_ga_createSegment($min, $max, $name)
{

    // Set a segment for hourly time range. For daily time range there is no neen id a segment.
    $dimensionFilter = new Google_Service_AnalyticsReporting_SegmentDimensionFilter();
    $dimensionFilter->setDimensionName('ga:hour');
    $dimensionFilter->setOperator('NUMERIC_BETWEEN');

    // $dimensionFilter->setExpressions($alert->get_expressions());
    $dimensionFilter->setMinComparisonValue($min);
    $dimensionFilter->setMaxComparisonValue($max);

    // Create Segment Filter Clause.
    $segmentFilterClause = new Google_Service_AnalyticsReporting_SegmentFilterClause();
    $segmentFilterClause->setDimensionFilter($dimensionFilter);

    // Create the Or Filters for Segment.
    $orFiltersForSegment = new Google_Service_AnalyticsReporting_OrFiltersForSegment();
    $orFiltersForSegment->setSegmentFilterClauses(array($segmentFilterClause));

    // Create the Simple Segment.
    $simpleSegment = new Google_Service_AnalyticsReporting_SimpleSegment();
    $simpleSegment->setOrFiltersForSegment(array($orFiltersForSegment));

    // Create the Segment Filters.
    $segmentFilter = new Google_Service_AnalyticsReporting_SegmentFilter();
    $segmentFilter->setSimpleSegment($simpleSegment);

    // Create the Segment Definition.
    $segmentDefinition = new Google_Service_AnalyticsReporting_SegmentDefinition();
    $segmentDefinition->setSegmentFilters(array($segmentFilter));

    // Create the Dynamic Segment.
    $dynamicSegment = new Google_Service_AnalyticsReporting_DynamicSegment();
    $dynamicSegment->setSessionSegment($segmentDefinition);
    $dynamicSegment->setName($name);

    // Create the Segments object.
    $segment = new Google_Service_AnalyticsReporting_Segment();
    $segment->setDynamicSegment($dynamicSegment);

    return $segment;
}

function updateDB($reports, $slug_filter = NULL, $post_type = 'post')
{
    global $wpdb;
    for ($reportIndex = 0; $reportIndex < count($reports); $reportIndex++) {
        $report = $reports[$reportIndex];
        $header = $report->getColumnHeader();
        $rows = $report->getData()->getRows();

        $pagePaths_pageViews = array();

        for ($rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
            $row = $rows[$rowIndex];
            $dimensions = $row->getDimensions();
            $metrics = $row->getMetrics();
            $pagePath = ltrim(rtrim($dimensions[0], '/'), '/');
            $pageViews = $metrics[0]->values[0];

            $pagePath_e = explode('/', $pagePath);


            if (
                !isset($pagePath_e[0]) ||
                in_array($pagePath_e[0], array('', 'bluesfestvip')) ||
                !isset($pagePath_e[2])
            ) :
                continue;
            endif;

            // echo '<pre>'; print_r( $pagePath_e ); echo '</pre>';

            if (is_null($slug_filter)) :
                $pagePath_e2 = explode('-', $pagePath_e[2]);
                $post_id = end($pagePath_e2);
                if (is_numeric($post_id)) :
                    $pagePaths_pageViews[$post_id] = $pageViews;
                else :
                    $pagePaths_pageViews[$post_id] += $pageViews;
                endif;
            else :
            // if ( isset( $pagePath_e[1] ) ) :
            //     if ( ! isset( $pagePaths_pageViews[ $pagePath_e[1] ] ) ) :
            //         $pagePaths_pageViews[ $pagePath_e[1] ] = $pageViews;
            //     else :
            //         $pagePaths_pageViews[ $pagePath_e[1] ] += $pageViews;
            //     endif;
            // endif;
            endif;
            // print_r( $dimensions ); echo '<pre>'; echo( $metrics[0]->values[0] ); echo '</pre><br>';
        }

        arsort($pagePaths_pageViews);

        foreach ($pagePaths_pageViews as $pagePath => $pageViews) :
            if (is_null($pagePath) || '' == $pagePath) :
                unset($pagePaths_pageViews[$pagePath]);
                continue;
            endif;
            $post = get_post($pagePath); // get_page_by_path( $pagePath, OBJECT, $post_type );
            if (!is_null($post) && $post_type == $post->post_type && 'publish' == $post->post_status) :
                $wpdb->insert(
                    $wpdb->prefix . 'tbm_trending',
                    array(
                        'post_id' => $post->ID,
                        'post_type' => $post->post_type,
                        'pageviews' => $pageViews,
                    ),
                    array(
                        '%d', '%s', '%d'
                    )
                );
            endif;
        endforeach;

        $array_keys_pagePaths_pageViews = array_keys($pagePaths_pageViews);

        foreach ($pagePaths_pageViews as $article_id => $pageViews) :
            $top_article = get_post($article_id);
            if (!is_null($top_article) && $top_article->post_status == 'publish') :
                if (is_null($slug_filter) && !get_option('force_most_viewed')) :
                    update_option('most_viewed_yesterday', $top_article->ID);
                else :
                // update_option( 'most_viewed_yesterday_' . $slug_filter, $top_article->ID );
                endif;
                break;
            endif;
        endforeach;



        echo '<h1>Result:</h1><pre>';
        print_r($pagePaths_pageViews);
        echo '</pre>';
    }
    //    exit;
}
