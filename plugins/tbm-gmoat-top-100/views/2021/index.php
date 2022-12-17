<?php global $wpdb; ?>
<div class="wrap">
  <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
    <div style="margin-right: 1rem;">
      <?php $total_unique_email = $wpdb->get_var("SELECT COUNT(DISTINCT user_email) FROM {$wpdb->prefix}gmoat_entries_2021"); ?>
      <strong>Total unique emails: <?php echo $total_unique_email; ?></strong>
      <br>
      <?php $total_entries = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}gmoat_movie_entries_2021"); ?>
      <strong>Total entries: <?php echo $total_entries; ?></strong>
    </div>
    <!-- <form method="post" action="<?php echo admin_url('admin.php'); ?>">
      <input type="hidden" name="action" value="tbm_export_gmoat2021">
      <?php submit_button('Export'); ?>
    </form> -->
  </div>
  <table class="widefat">
    <tr>
      <td>
        <h1>GMOAT</h1>
        <?php
        $top_movies_query1 = "
          SELECT
            m.id, m.title, COUNT( me.movie_id ) count_entries
          FROM
            {$wpdb->prefix}gmoat_movies_2021 m
              JOIN {$wpdb->prefix}gmoat_movie_entries_2021 me
                ON m.id = me.movie_id
          WHERE
            me.movie_id IS NOT NULL
          GROUP BY
            m.id
          ORDER BY
            count_entries DESC
        ";
        $top_movies1 = $wpdb->get_results($top_movies_query1);
        $movies1 = wp_list_pluck($top_movies1, 'count_entries', 'title');

        $top_movies_query2 = "
          SELECT
            me.movie_title title, COUNT( me.movie_title ) count_entries
          FROM
            {$wpdb->prefix}gmoat_movie_entries_2021 me
          WHERE
            me.movie_title IS NOT NULL
          GROUP BY
            me.movie_title
          ORDER BY
            count_entries DESC
          LIMIT 25
        ";
        $top_movies2 = $wpdb->get_results($top_movies_query2);
        $movies2 = wp_list_pluck($top_movies2, 'count_entries', 'title');

        $movies = [];
        foreach ($movies1 as $title => $count_entries) {
          // $title = strtolower(stripslashes($title));

          $title_key = sanitize_title($title);
          if (!isset($movies[$title_key]['title'])) {
            $movies[$title_key]['title'] = $title;
          }
          if (isset($movies[$title_key]['entries'])) {
            $movies[$title_key]['entries'] += $count_entries;
          } else {
            $movies[$title_key]['entries'] = $count_entries;
          }
        }
        foreach ($movies2 as $title => $count_entries) {
          // $title = strtolower(stripslashes($title));
          $title_key = sanitize_title($title);
          if (!isset($movies[$title_key]['title'])) {
            $movies[$title_key]['title'] = $title;
          }
          if (isset($movies[$title_key]['entries'])) {
            $movies[$title_key]['entries'] += $count_entries;
          } else {
            $movies[$title_key]['entries'] = $count_entries;
          }
        }
        // $movies = $movies1 + $movies2;


        // echo '<pre>' . print_r($movies, true) . '</pre>';
        // exit;

        if ($movies) :
          uasort($movies, function ($a, $b) {
            return $b['entries'] <=> $a['entries'];
          });
          // arsort($movies);
        ?>
          <table class="widefat">
            <thead>
              <tr>
                <th>#</th>
                <th>Movie Title</th>
                <th># of votes</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $counter = 0;
              foreach ($movies as $movie) :
                // if ($counter >= 25)
                //   break;
                $counter++;
              ?>
                <tr>
                  <th><?php echo $counter; ?></th>
                  <th><?php echo stripslashes($movie['title']); ?></th>
                  <td><?php echo $movie['entries']; ?></td>
                </tr>
              <?php endforeach; // For Each $movie in $top_movies 
              ?>
            </tbody>
          </table>
        <?php
        endif; // If $top_movies
        ?>
      </td>
      <td>
        <h1>Comp entries</h1>
        <?php
        $entries = $wpdb->get_results("
          SELECT
            *
          FROM
            {$wpdb->prefix}gmoat_entries_2021 e
          WHERE
            e.reason IS NOT NULL AND
            e.reason != ''
        ");

        if ($entries) :
          $entries = stripslashes_deep($entries);
        ?>
          <table class="widefat">
            <thead>
              <tr>
                <th>#</th>
                <th>Name / Email</th>
                <th>Reason</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $counter = 0;
              foreach ($entries as $entry) :
                $counter++;
              ?>
                <tr>
                  <td><?php echo $counter; ?></td>
                  <td>
                    <?php echo $entry->user_fullname; ?>
                    <br><a href="mailto:<?php echo $entry->user_email; ?>"><?php echo $entry->user_email; ?></a>
                  </td>
                  <td><?php echo wpautop($entry->reason); ?></td>
                </tr>
              <?php endforeach; // For Each $movie in $top_user_movies 
              ?>
            </tbody>
          </table>
        <?php
        endif; // If $top_user_movies
        ?>
      </td>
    </tr>
  </table>
</div>