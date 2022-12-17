<?php
global $wpdb;

$movie_title = urldecode( $_GET['m_title'] );
$movie_query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}gmoat_user_movies m WHERE m.movie_title = %s LIMIT 1", $movie_title );
$movie = $wpdb->get_row( $movie_query );

?>
<div class="wrap">
  <table class="widefat">
    <tr>
      <td>
        <h1><?php echo $movie->movie_title; ?></h1>
        <?php
        $entries_query = $wpdb->prepare( "
          SELECT
            e.*
          FROM
            {$wpdb->prefix}gmoat_entries e
              JOIN {$wpdb->prefix}gmoat_user_movies um
                ON e.id = um.entry_id
          WHERE
            um.movie_title = %s
        ", $movie_title );
        $entries = $wpdb->get_results( $entries_query );
        if ( $entries ) :
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
          <?php foreach ( $entries as $entry ) : ?>
            <tr>
              <th><?php echo $entry->user_fullname; ?></th>
              <th><?php echo $entry->user_email; ?></th>
              <td><?php echo $entry->created_at; ?></td>
            </tr>
          <?php endforeach; // For Each $movie in $top_movies ?>
          </tbody>
        </table>
        <?php
        endif; // If $top_movies
      ?>
      </td>
    </tr>
  </table>
</div>
