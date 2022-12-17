<?php
global $wpdb;

$movie_id = absint($_GET['m_id']);
$movie_query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}gmoat_movies_2021 m WHERE m.id = %d LIMIT 1", $movie_id);
$movie = $wpdb->get_row($movie_query);

?>
<div class="wrap">
  <table class="widefat">
    <tr>
      <td>
        <h1><?php echo $movie->title; ?></h1>
        <?php
        $entries_query = $wpdb->prepare("
          SELECT
            e.*
          FROM
            {$wpdb->prefix}gmoat_entries_2021 e
              JOIN {$wpdb->prefix}gmoat_movie_entries_2021 me
                ON e.id = me.entry_id
              JOIN {$wpdb->prefix}gmoat_movies_2021 m
                ON m.id = me.movie_id
          WHERE
            m.id = %d
        ", $movie_id);
        $entries = $wpdb->get_results($entries_query);
        if ($entries) :
        ?>
          <table class="widefat">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Submitted at</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($entries as $entry) : ?>
                <tr>
                  <th><?php echo $entry->user_fullname; ?></th>
                  <th><?php echo $entry->user_email; ?></th>
                  <td><?php echo $entry->created_at; ?></td>
                </tr>
              <?php endforeach; // For Each $movie in $top_movies 
              ?>
            </tbody>
          </table>
        <?php
        endif; // If $top_movies
        ?>
      </td>
    </tr>
  </table>
</div>