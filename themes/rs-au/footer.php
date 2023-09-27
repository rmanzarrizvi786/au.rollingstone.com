<?php

/**
 * The template for displaying the footer.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-23
 */

?>
<div class="l-page__mega">
	<?php get_template_part('template-parts/footer/mega'); ?>
</div><!-- .l-page__mega -->
</div><!-- .l-page -->

<?php wp_footer(); ?>
<?php do_action('pmc-tags-footer'); // phpcs:ignore 
?>

<?php do_action('pmc-tags-bottom'); // phpcs:ignore 
?>

<?php // if ( is_home() || is_front_page() ) : 
?>
<script src="https://www.youtube.com/iframe_api" defer></script>
<script>
	jQuery(document).ready(function($) {
		$('body').on('click', '.yt-lazy-load', function() {
			var video_id = $(this).data('id');
			var player_id = $(this).prop('id');
			var player_height = $(this).height();

			var player;
			player = new YT.Player(player_id, {
				height: player_height,
				videoId: video_id,
				events: {
					'onReady': onPlayerReady,
				}
			});

			function onPlayerReady(event) {
				event.target.playVideo();
			}
		});
	});
</script>
<?php // endif; // If Home or Front page 
?>

<script>
	jQuery(document).ready(function($) {
		$('.l_toggle_menu_network').on('click', function(e) {
			e.preventDefault();
			$('#menu-network').toggle();
			$('#site_wrap').toggleClass('freeze');
			$('body').toggleClass('network-open');
			// $('#brands_wrap').height($(window).height() - jQuery('#adm_leaderboard-desktop').height() - 24);

			$('.is-header-sticky .l-header__search').toggle();
			$(this).toggleClass('expanded');
		});
	});
</script>

<?php if (!is_page_template('page-templates/page-subscribe-thankyou.php')) : ?>
	<!--modals-->
	<div id="modalMagSub" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<h2 style="text-align: center;">Support long-form journalism. Subscribe to Rolling Stone Magazine</h2>
			<p>&nbsp;</p>
			<a href="<?php echo home_url('subscribe-magazine'); ?>"><?php rollingstone_the_issue_cover(350); ?></a>
			<a href="<?php echo home_url('subscribe-magazine'); ?>" style="background: #000; color: #fff; padding: .5rem 1rem; display: block; text-align: center;">Subscribe NOW</a>
		</div>
	</div>
	<script>
		/*
jQuery(document).ready(function($){
	var data = {
		action: 'tbm_ajax_get_cookie',
		cookie: 'seen_popup'
	};
	$.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(res) {
		if (res.success) {

		} else {
			var data = {
				action: 'tbm_ajax_set_cookie',
				cookies: { seen_popup: { value: 'yes', period: 432000 } }
			};
			$.post('<?php echo admin_url('admin-ajax.php'); ?>', data);

			$('#modalMagSub').addClass('shown');

		}
	}).fail(function(xhr, textStatus, e) {});

	$('.modal-content .close').on('click', function() {
		$(this).closest('.shown').removeClass('shown');
	});
	$('.modal.shown').on('click', function(e){
		if ( e.target === $(this)[0] ) {
			$(this).removeClass('shown');
		}
	});
});
*/
	</script>
<?php endif; ?>

<!-- 22071836792/outofpage/outofpage -->
<div data-fuse="22779876859"></div>

</body>

</html>