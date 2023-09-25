<?php

/**
 * The template for displaying the footer.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-23
 */

?>
<footer class="l-footer">
	<div class="l-footer__wrap">
		<div class="l-footer__nav">
			<nav class="l-footer__menu">
				<div class="c-page-nav c-page-nav--footer c-page-nav--cta c-page-nav--1-column" data-dropdown="">
					<ul class="c-page-nav__list">
						<li class="c-page-nav__item c-page-nav__item--heading is-active" data-ripple="inverted">
							<span class="c-page-nav__link t-bold">Get The Magazine</span>
						</li>
						<!-- .c-page-nav__item -->
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="<?php echo get_site_url(); ?>/subscribe-magazine/" class="c-page-nav__link t-semibold " title="SUBSCRIBE NOW" target="_blank">SUBSCRIBE NOW</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="<?php echo get_site_url(); ?>/subscribe-magazine/?gift=1" class="c-page-nav__link t-semibold " title="GIVE A GIFT" target="_blank">GIVE A GIFT</a>
						</li>
					</ul>
					<!-- .c-page-nav__list -->
				</div>
				<!-- .c-page-nav--footer -->
				<div class="c-page-nav c-page-nav--footer c-page-nav--1-column" data-dropdown="">
					<ul class="c-page-nav__list">
						<li class="c-page-nav__item c-page-nav__item--heading is-active" data-ripple="inverted">
							<span class="c-page-nav__link t-bold">About</span>
						</li>
						<!-- .c-page-nav__item -->
						<?php
						$socialNav = wp_get_nav_menu_items(5);
						foreach ($socialNav as $navItem) {

						?>
							<li class="c-page-nav__item" data-ripple="inverted">
								<a href="<?php echo $navItem->url; ?>" class="c-page-nav__link t-semibold " title="<?php echo $navItem->title; ?>"><?php echo $navItem->title; ?></a>
							</li>
						<?php
						}
						?>


						<!-- <li class="c-page-nav__item" data-ripple="inverted"><a href="#" class="c-page-nav__link t-semibold privacy-consent" target="_blank"></a></li> -->
					</ul>
					<!-- .c-page-nav__list -->
				</div>
				<!-- .c-page-nav--footer -->
			</nav>

			<nav class="l-footer__menu l-footer__menu--wide">
				<div class="c-page-nav c-page-nav--footer c-page-nav--2-columns" data-dropdown="">
					<ul class="c-page-nav__list">
						<li class="c-page-nav__item c-page-nav__item--heading is-active" data-ripple="inverted">
							<span class="c-page-nav__link t-bold">Rolling Stone</span>
						</li>
						<!-- .c-page-nav__item -->
						<?php
						$socialNav = wp_get_nav_menu_items(7);
						foreach ($socialNav as $navItem) {
						?>
							<li class="c-page-nav__item" data-ripple="inverted">
								<a href="<?php echo $navItem->url; ?>" class="c-page-nav__link t-semibold " title="<?php echo $navItem->title; ?>"><?php echo $navItem->title; ?></a>
							</li>
						<?php
						}
						?>
					</ul>
					<!-- .c-page-nav__list -->
				</div>
				<!-- .c-page-nav--footer -->
				<br>
				<div class="c-page-nav c-page-nav--footer c-page-nav--1-column" data-dropdown="">
					<ul class="c-page-nav__list">
						<li class="c-page-nav__item c-page-nav__item--heading is-active" data-ripple="inverted">
							<span class="c-page-nav__link t-bold"><?php esc_html_e('Connect With Us', 'pmc-rollingstone'); ?></span>
						</li>
						<?php
						$socialNav = wp_get_nav_menu_items(6);
						foreach ($socialNav as $navItem) {
							if (str_contains($navItem->title, 'ubscribe')) {
								$iconName = 'email';
							} else {
								$iconName = strtolower($navItem->title);
							}
						?>
							<li class="c-page-nav__item" data-ripple="inverted">
								<a href="<?php echo $navItem->url; ?>" class="c-page-nav__link t-semibold " title="<?php echo $navItem->title; ?>" target="_blank">
									<span class="c-page-nav__icon c-icon c-icon--inline c-icon--inverted c-icon--round">
										<!-- <i class="fa-brands fa-facebook-f"></i> -->

										<img src="<?php echo TBM_CDN; ?>/assets/images/icon-<?php echo $iconName; ?>.svg" class="img-responsive" style="width:10px; height:12px;" />
									</span>
									<?php echo $navItem->title; ?>
								</a>
							</li>
						<?php
						}
						?>

						<!-- .c-page-nav__item -->

					</ul>
					<!-- .c-page-nav__list -->
				</div>
				<!-- .c-page-nav--footer -->
			</nav>
			<!-- .l-footer__menu -->
		</div>
		<!-- .l-footer__nav -->
		<div class="l-footer__newsletter" style="border-bottom: 0;"></div>
		<!-- .l-footer__newsletter -->
		<div class="l-footer__cover">
			<a href="https://au.rollingstone.com/subscribe-magazine/" target="_blank">
				<img src="https://cdn-r2-1.thebrag.com/rs/uploads/2023/08/014_RSAUNZ_COVER-TroyeSivan_v.jpg?w=250" data-src="https://cdn-r2-1.thebrag.com/rs/uploads/2023/08/014_RSAUNZ_COVER-TroyeSivan_v.jpg?w=250" alt="" class="l-footer__cover-image" style="width: 250px">
			</a>
		</div>
		<!-- .l-footer__cover -->
	</div>
	<!-- .l-footer__wrap -->
	<div style="padding: 0 1rem;">
		<div class="l-footer__tip">
			<p class="l-footer__tip-heading t-bold">Have you got Tip/Op-Ed/Video?</p>
			<div class="l-footer__tip-body"></div>
			<a class="l-footer__tip-link t-bold" href="https://thebrag.media/submit-a-tip/" target="_blank">
				<span>Send Us a Tip</span>
			</a>
			<a class="l-footer__tip-link t-bold" href="https://thebrag.media/how-to-submit-an-op-ed-essay/" target="_blank">
				<span>Send Us an op-ed</span>
			</a>
			<a class="l-footer__tip-link t-bold" href="https://thebrag.media/submit/" target="_blank">
				<span>Send Us a video</span>
			</a>
			<img class="l-footer__logo" src="https://cdn-r2-2.thebrag.com/assets/images/RSAUwhitewhite_WEB.png">
		</div>
		<!-- .l-footer__tip -->
	</div>
</footer>
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