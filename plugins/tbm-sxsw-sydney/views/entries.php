<?php 
    global $wpdb;

    $table = $wpdb->prefix . 'sxsw_sydney_entries_2023';

    $total_unique_email = $wpdb->get_var( "SELECT COUNT(DISTINCT email) FROM {$table}");
    $total_entries = $wpdb->get_var("SELECT COUNT(id) FROM {$table}");
    $total_day_1 = $wpdb->get_var("SELECT COUNT(id) FROM {$table} WHERE day1 = 1");
    $total_day_2 = $wpdb->get_var("SELECT COUNT(id) FROM {$table} WHERE day2 = 1");
    $total_day_3 = $wpdb->get_var("SELECT COUNT(id) FROM {$table} WHERE day3 = 1");
    $total_day_4 = $wpdb->get_var("SELECT COUNT(id) FROM {$table} WHERE day4 = 1");

    $entries = $wpdb->get_results("SELECT * FROM {$table}");
?>
<div class="wrap">
    <div style="display: flex; flex-direction: row; justify-content: space-between;">
        <div>
            <div style="margin-right: 1rem; margin-bottom: 1rem;">
                <strong>Total entries:</strong> <?php echo $total_entries; ?>
                <strong>Total unique entries:</strong> <?php echo $total_unique_email; ?><br>
            </div>
            <div style="margin-right: 1rem; margin-bottom: 1rem;">
                <strong>Day 1 count:</strong> <?php echo $total_day_1; ?><br>
                <strong>Day 2 count:</strong> <?php echo $total_day_2; ?><br>
                <strong>Day 3 count:</strong> <?php echo $total_day_3; ?><br>
                <strong>Day 4 count:</strong> <?php echo $total_day_4; ?>
            </div>
        </div>
        <!-- <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
            <input type="hidden" name="action" value="tbm_sxsw_sydney_export_entries_2023">
            <input type="submit" value="Export Data" class="button button-primary">
        </form> -->
    </div>
    <table class="widefat">
        <tr>
            <td>
                <h1>Entries</h1>
                <?php
                    if ($entries) :
                        $entries = stripslashes_deep($entries);
                ?>
                <table class="widefat">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name / Email</th>
                            <th style="text-align:center;">Post Code</th>
                            <th style="text-align:center;">Day 1</th>
                            <th style="text-align:center;">Day 2</th>
                            <th style="text-align:center;">Day 3</th>
                            <th style="text-align:center;">Day 4</th>
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
                                <?php echo $entry->name; ?><br>
                                <a href="mailto:<?php echo $entry->email; ?>"><?php echo $entry->email; ?></a>
                            </td>
                            <td style="text-align: center;">
                                <?php echo $entry->postcode; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo $entry->day1; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo $entry->day2; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo $entry->day3; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo $entry->day4; ?>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    ?>
                    </tbody>
                </table>
                <?php
                    endif;
                ?>
            </td>
        </tr>
    </table>
</div>
