<?php global $wpdb; ?>
<div class="wrap">
  <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
    <div style="margin-right: 1rem;">
      <?php $total_unique_email = $wpdb->get_var("SELECT COUNT(DISTINCT email) FROM {$wpdb->prefix}scu_scholarship_$year"); ?>
      <strong>Total unique emails: <?php echo $total_unique_email; ?></strong>
      <br>
      <?php $total_entries = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}scu_scholarship_$year"); ?>
      <strong>Total entries: <?php echo $total_entries; ?></strong>
    </div>
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
      <input type="hidden" name="action" value="tbm_export_scu_scholarship_<?php echo $year; ?>">
      <?php submit_button('Export');
      ?>
    </form>
  </div>
  <table class="widefat">
    <tr>
      <td>
        <h1>SCU Rolling Stone Scholarship <?php echo $year; ?> submissions</h1>
        <?php
        $query = ("
          SELECT
            *
          FROM
            {$wpdb->prefix}scu_scholarship_{$year}
        ");
        $entries = $wpdb->get_results($query);

        if ($entries) :
        ?>
          <table class="widefat">
            <thead>
              <tr>
                <th>#</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Postcode</th>
                <th>Current Status</th>
                <th>Reason</th>
                <th>Submitted at</th>
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
                  <td><?php echo $entry->firstname; ?></td>
                  <td><?php echo $entry->lastname; ?></td>
                  <td><?php echo $entry->email; ?></td>
                  <td><?php echo $entry->phone; ?></td>
                  <td><?php echo $entry->postcode; ?></td>
                  <td><?php echo $entry->current_status; ?></td>
                  <td><?php echo wpautop(stripslashes($entry->reason)); ?></td>
                  <td><?php echo $entry->created_at; ?></td>
                </tr>
              <?php endforeach; // For Each $entry in $entries
              ?>
            </tbody>
          </table>
        <?php
        endif; // If $entries
        ?>
      </td>
    </tr>
  </table>
</div>