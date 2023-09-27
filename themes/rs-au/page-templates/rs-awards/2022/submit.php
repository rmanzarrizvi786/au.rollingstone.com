<?php
$date_from = date('Y-m-d', strtotime('1 Nov 2020'));
$date_to = date('Y-m-d', strtotime('15 Oct 2021'));
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $success = false;
  $formdata = $_POST; // recursive_sanitize_text_field($_POST);
  foreach ($award_categories as $award_category) {
    $field_1 = isset($formdata['fields'][$award_category->id]) ? $formdata['fields'][$award_category->id]['field_1'] : [];
    if (isset($field_1) && is_array($field_1) && !empty($field_1)) {
      foreach ($field_1 as $key => $value) {
        if (isset($field_1[$key]) && '' != trim($field_1[$key])) {
          // Validate field_2 to field_4 if field_1 is completed
          $i_to = 4; // $award_category->id <= 3 ? 5 : 4;
          for ($i = 2; $i <= $i_to; $i++) {
            $field_i = $formdata['fields'][$award_category->id]['field_' . $i][$key];
            if (!isset($field_i) || '' == trim($field_i)) {
              array_push($errors, 'Please complete all details for ' . $field_1[$key] . ' in ' . $award_category->title);
              break;
            } // Validation: If $field_i is not set or is blank
          } // For field_2 to field_5

          // Validate date = field_2
          $field_2 = $formdata['fields'][$award_category->id]['field_2'][$key];
          if (
            isset($field_2) && '' != trim($field_2) &&
            (strtotime($field_2) < strtotime($date_from) ||
              strtotime($field_2) > strtotime($date_to))
          ) {
            array_push($errors, 'Release date for ' . $field_1[$key] . ' must be in catchment period in ' . $award_category->title);
          } // Date validation

          // Validate URL = field_5
          if ($award_category->id <= 3) {
            $field_5 = $formdata['fields'][$award_category->id]['field_5'][$key];
            if (
              isset($field_5) && '' != trim($field_5) &&
              !filter_var($field_5, FILTER_VALIDATE_URL)
            ) {
              array_push($errors, 'Please enter valid Spotify Link for ' . $field_1[$key] . ' in ' . $award_category->title);
            }
          } // URL validation


        } // If $field_1 array element is set and not blank
      } // For Each $field_1 array elements
    } // If Field 1 is set, is array AND is not empty
  } // For each $award_category from DB

  if (count($errors) === 0) {

    if (isset($_POST['action']) && 'edit' == $_POST['action']) {
      $wpdb->delete(
        $wpdb->prefix . 'rsawards_nominations_2022',
        [
          'user_id' => $current_user->ID
        ]
      );
    } // If updating

    if (isset($formdata) && isset($formdata['fields'])) {
      foreach ($formdata['fields'] as $category_id => $fields) {
        $count = count($fields['field_1']);

        for ($counter = 0; $counter < $count; $counter++) {
          if (isset($fields['field_1'][$counter]) && '' != trim($fields['field_1'][$counter])) {
            $insert_values = [
              'user_id' => $current_user->ID,
              'category_id' => $category_id,
            ];
            $i_to = $category_id <= 3 ? 5 : 4;
            for ($i = 1; $i <= $i_to; $i++) {
              if ($i == 2) {
                $insert_values['field_' . $i] = date('Y-m-d', strtotime($fields['field_' . $i][$counter]));
              } else if ($i == 4) {
                $insert_values['field_' . $i] = sanitize_textarea_field($fields['field_' . $i][$counter]);
              } else {
                $insert_values['field_' . $i] = sanitize_text_field($fields['field_' . $i][$counter]);
              }
            }
            $wpdb->insert(
              $wpdb->prefix . 'rsawards_nominations_2022',
              $insert_values,
              ['%d', '%d', '%s', '%s', '%s', '%s', '%s',]
            );
            $success = true;
          }
        } // counters for number of submission in each category
      } // For Each $formdata['fields']


    } // If $formdata and $formdata['fields'] are set

    $rs_nominated = get_user_meta($current_user->ID, 'rsawards_nominated_2022', true);

    // if ('add' == $_POST['action'] && $success && (!isset($rs_nominated) || $rs_nominated != true)) 
    {

      /* 
      // Subscribe to TIO Observer on thebrag.com
      require_once WP_PLUGIN_DIR . '/brag-observer/brag-observer.php';
      $bo = new BragObserver();
      $bo->ajax_subscribe_observer(
        [
          'list' => 4, // 4 = List ID of TIO
          'email' => $current_user->user_email,
          'source' => 'RS Awards 2022',
        ],
        false
      );
      */

      $email_body = '';
      ob_start();
      include(get_template_directory() . '/page-templates/rs-awards/2022/email-body.php');
      $email_body = ob_get_contents();
      ob_end_clean();

      $headers[] = 'Content-Type: text/html; charset=UTF-8';

      wp_mail(
        $current_user->user_email,
        'Thank you, your nomination is official',
        $email_body,
        $headers
      );

      add_user_meta($current_user->ID, 'rsawards_nominated_2022', true);
    }

    // wp_redirect(home_url('/'));
    // wp_redirect(get_permalink() . '/?a=ty');

    $query_args = [
      'a' => 'ty',
      'success' => $success
    ];
    wp_redirect(add_query_arg($query_args, get_the_permalink()));
    die();
  } // If there are no errors
} // If form is posted
