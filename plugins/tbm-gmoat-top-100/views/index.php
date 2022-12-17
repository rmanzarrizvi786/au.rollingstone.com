<?php global $wpdb; ?>
<div class="wrap">
  <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
    <div style="margin-right: 1rem;">
      <?php $total_unique_email = $wpdb->get_var("SELECT COUNT(DISTINCT user_email) FROM {$wpdb->prefix}gmoat_entries"); ?>
      <strong>Total unique emails: <?php echo $total_unique_email; ?></strong>
      <br>
      <?php $total_entries = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}gmoat_movie_entries"); ?>
      <strong>Total entries: <?php echo $total_entries; ?></strong>
    </div>
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
      <input type="hidden" name="action" value="tbm_export_gmoat2020">
      <?php submit_button('Export'); ?>
    </form>
  </div>
  <table class="widefat">
    <tr>
      <td>
        <h1>GMOAT (top 100)</h1>
        <?php
        $top_movies_query1 = $wpdb->prepare("
          SELECT
            m.id, m.title, COUNT( me.movie_id ) count_entries
          FROM
            {$wpdb->prefix}gmoat_movies m
              JOIN {$wpdb->prefix}gmoat_movie_entries me
                ON m.id = me.movie_id
          WHERE
            me.movie_id IS NOT NULL
          GROUP BY
            m.id
          ORDER BY
            count_entries DESC
          LIMIT 100
        ");
        $top_movies1 = $wpdb->get_results($top_movies_query1);
        $movies1 = wp_list_pluck($top_movies1, 'count_entries', 'title');

        $top_movies_query2 = $wpdb->prepare("
          SELECT
            me.movie_title title, COUNT( me.movie_title ) count_entries
          FROM
            {$wpdb->prefix}gmoat_movie_entries me
          WHERE
            me.movie_title IS NOT NULL
          GROUP BY
            me.movie_title
          ORDER BY
            count_entries DESC
          LIMIT 100
        ");
        $top_movies2 = $wpdb->get_results($top_movies_query2);
        $movies2 = wp_list_pluck($top_movies2, 'count_entries', 'title');

        $movies = $movies1 + $movies2;

        if ($movies) :
          arsort($movies);
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
              foreach ($movies as $title => $count_entries) :
                if ($counter >= 100)
                  break;
                $counter++;
              ?>
                <tr>
                  <th><?php echo $counter; ?></th>
                  <th><?php echo $title; ?></th>
                  <td><?php echo $count_entries; ?></td>
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
        <h1>Wildcard entries (top 100)</h1>
        <?php
        $user_movies_query1 = $wpdb->prepare("
          SELECT
            m.id, m.title, COUNT( e.wildcard_movie_id ) count_entries
          FROM
            {$wpdb->prefix}gmoat_entries e
              JOIN {$wpdb->prefix}gmoat_movies m
                ON m.id = e.wildcard_movie_id
          WHERE
            e.wildcard_movie_id IS NOT NULL
          GROUP BY
            m.title
          ORDER BY
            count_entries DESC
        ");
        $top_user_movies1 = $wpdb->get_results($user_movies_query1);
        $user_movies1 = wp_list_pluck($top_user_movies1, 'count_entries', 'title');

        $user_movies_query2 = $wpdb->prepare("
          SELECT
            e.wildcard_movie_title title, COUNT( e.wildcard_movie_title ) count_entries
          FROM
            {$wpdb->prefix}gmoat_entries e
          WHERE
            e.wildcard_movie_title IS NOT NULL
          GROUP BY
            e.wildcard_movie_title
          ORDER BY
            count_entries DESC
        ");
        $top_user_movies2 = $wpdb->get_results($user_movies_query2);
        $user_movies2 = wp_list_pluck($top_user_movies2, 'count_entries', 'title');

        $user_movies = $user_movies1 + $user_movies2;

        if ($user_movies) :
          arsort($user_movies);
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
              foreach ($user_movies as $title => $count_entries) :
                if ($counter >= 100)
                  break;
                $counter++;
              ?>
                <tr>
                  <th><?php echo $counter; ?></th>
                  <th><?php echo $title; ?></th>
                  <td><?php echo $count_entries; ?></td>
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