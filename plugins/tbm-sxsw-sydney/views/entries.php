<?php 
    global $wpdb;

    $table = $wpdb->prefix . 'sxsw_sydney_entries_2023';

    $total_unique_email = $wpdb->get_var( "SELECT COUNT(DISTINCT email) FROM {$table}");
    $total_entries = $wpdb->get_var("SELECT COUNT(id) FROM {$table}");

    $entries = $wpdb->get_results("SELECT * FROM {$table}");
?>
<div class="wrap">
    <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
        <div style="margin-right: 1rem; margin-bottom: 1rem;">
            <strong>Total unique emails: <?php echo $total_unique_email; ?></strong>
            <br>
            <strong>Total entries: <?php echo $total_entries; ?></strong>
        </div>
        <!-- <form method="post" action="<?php echo admin_url('admin.php'); ?>">
            <input type="hidden" name="action" value="tbm_export_sxsw_sydney_entries_2023">
            <?php submit_button('Export'); ?>
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
