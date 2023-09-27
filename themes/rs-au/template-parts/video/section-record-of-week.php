<?php

/**
 * TBM Section Video of week Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-25
 */

$rotw_response = wp_remote_get('https://thebrag.com/wp-json/tbm/rotw?v=' . time());
if (is_array($rotw_response) && !is_wp_error($rotw_response)) {
    $rotw = json_decode($rotw_response['body']);
?>
    <div id="record-of-week" style="overflow: hidden">
        <a href="<?php echo $rotw->link; ?>" target="_blank" class="d-block home-featured-content" style="display: block; position: relative;">
            <img src="<?php echo $rotw->image; ?>" style="height: 100%; width: auto; max-width: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <h3 class="title">
                <?php echo esc_html(stripslashes($rotw->artist)); ?>
                -
                <em><?php echo esc_html(stripslashes($rotw->title)); ?></em>
            </h3>
        </a>
    </div>
<?php } ?>