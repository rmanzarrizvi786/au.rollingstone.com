<?php

/**
 * Article Content.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

$count_articles = isset($_POST['count_articles']) ? (int) $_POST['count_articles'] : 1;
?>

<div class="c-content t-copy">
	<?php
	// the_content();
	$content = apply_filters('the_content', $post->post_content);
	echo $content;

	if (get_field('track_visitors')) : ?>
		<script>
			jQuery(document).ready(function($) {
				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					type: 'post',
					dataType: 'json',
					cache: 'false',
					data: {
						'action': 'tbm_set_cookie',
						'key': 'tbm_v',
						'value': '<?php echo get_field('track_visitors'); ?>',
						'duration': '<?php echo 60 * 60 * 24 * 365; ?>'
					}
				});
			});
			window.dataLayer = window.dataLayer || [];
			window.dataLayer.push({
				'event': 'track_visitor',
				'trackVisitor': '<?php echo get_field('track_visitors'); ?>'
			});
		</script>
	<?php endif; // If set track visitors 
	?>

</div><!-- .c-content -->