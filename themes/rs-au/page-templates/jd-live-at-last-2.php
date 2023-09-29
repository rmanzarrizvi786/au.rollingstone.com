<?php

/**
 * Template Name: JD Live at Last 2 (2022)
 */
get_header('jd-live-at-last-2');
?>

<?php
if (have_posts()) :
	while (have_posts()) :
		the_post();

		if (!post_password_required($post)) :
?>
			<div class="l-page" id="site_wrap" style="width: 100%; max-width: 100%; overflow-x: hidden">
				<div class="l-page__header">
					<header>
						<?php get_template_part('template-parts/header/nav-network'); ?>
						<div class="l-header__wrap tbm">
							<div class="content d-flex flex-column">
								<div class="l-header__content">
									<h1 class="l-header__branding d-flex flex-column">
										<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/logos.png" class="">
										<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/MICtourLockup.png" class="feature-image desktop">
										<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/MICtour_stackedshadow.png" class="mobile">
									</h1>
									<div class="text">
										<div class="text-inner">
											Off the back of its sell-out success, our mates at Jack Daniel's continue to celebrate Aussie live music and bring your fav Aussie acts to your hometown!
										</div>
									</div>
									<!-- .l-header__branding -->
								</div><!-- .l-header__content -->
							</div>
						</div>
						<!--. l-header__wrap -->

						<div class="header-bottom">
							JACK DANIEL'S IS MAKING IT COUNT BY SUPPORTING THE MUSIC INDUSTRY.
						</div>
					</header><!-- .l-header -->
				</div>
				<div id="content-wrap2">
					<section class="intro-wrap">
						<div class="intro">
							<div class="content d-flex flex-column">
								<div class="text">
									<div class="text-inner">
										<h3 style="font-weight: bold;">All proceeds of ticket sales will be donated to Support Act.</h3>
										<p style="font-family: Graphik, sans-serif;">Support Act is Australia’s only charity delivering crisis relief services to artists, artist managers, crew and music workers as a result of ill health, injury, a mental health problem, or some other crisis that impacts on their ability to work in music.</p>
									</div>
								</div>
								<div class="intro-btn-wrap">
									<a href="#" target="_blank" class="btn">LEARN MORE</a>
								</div>
							</div>
						</div>
					</section><!-- .intro -->

					<section class="gigs d-flex">
						<div class="gigs-wrap d-flex flex-column flex-md-row align-items-stretch">
							<div class="gigs-menu d-none d-md-block mx-2">
								<ul>
									<li><a href="#">Events</a></li>
									<li><a href="#">Map</a></li>
									<li><a href="#">News</a></li>
									<li><a href="#">Previous Galleries</a></li>
								</ul>
							</div>
							<div class="gigs-heading px-2 px-md-0">
								<div class="thick-line"></div>
								<h3 class="mb-2 mt-2 d-flex justify-content-between flex-nowrap">
									<span>UPCOMING GIGS</span>
									<span class="ml-2"><img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/arrow-right.png"></span>
								</h3>
								<div class="box d-none d-md-block">
									<p>These amazing Australian acts will each play an intimate show, where you can show your support with tickets only at $10. Keep an eye out for more tour dates to be announced soon with more big names from the Australian music scene coming to your local town.</p>
									<p class="mt-3"><strong>Don't miss your chance to experience bringing back Aussie live music bigger than ever and Make It Count with Jack Daniel's.</strong></p>
								</div>
							</div>
							<div class="gig-items d-flex align-items-stretch justify-content-start flex-column flex-md-row">
								<div class="gig-item d-flex flex-column justify-content-between">
									<div>
										<div class="img-wrap">
											<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/4-CH0A0269-1.jpg">
										</div>
										<div class="gig-info">
											<h3>DUNE RATS</h3>
											<p>with special guests<br>Beddy Rays &amp; VOIID</p>
										</div>
										<div class="time-location">
											Friday 11 November, 4:00 pm<br>
											Night Quarter,<br />
											Sunshine Coast, QLD
										</div>
									</div>
									<a href="https://www.moshtix.com.au/v2/event/jack-daniel%E2%80%99s-make-it-count-feat-dune-rats/144769" target="_blank" class="btn mt-3">GET TICKETS</a>
								</div>

								<div class="gig-item d-flex flex-column justify-content-between">
									<div>
										<div class="img-wrap">
											<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/StandAtlantic.jpg">
										</div>
										<div class="gig-info">
											<h3>Stand Atlantic</h3>
											<p>with special guests<br>Redhook</p>
										</div>
										<div class="time-location">
											Wednesday 7 December, 7:30 pm<br>
											Crowbar,<br />
											Sydney (Leichhardt, NSW)
										</div>
									</div>
									<!-- <a href="#" target="_blank" class="btn soldout mt-3">SOLD OUT</a> -->
									<a href="https://crowbarsyd.oztix.com.au/outlet/event/c15e654c-195e-4213-9a72-8254fe6b0e73

" target="_blank" class="btn mt-3">GET TICKETS</a>
								</div>

								<div class="gig-item d-flex flex-column justify-content-between">
									<div>
										<div class="img-wrap">
											<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/jd-stacked-dark.png" style="width: 80%;">
										</div>
										<div class="gig-info">
											<h3>Artist TBC</h3>
											<p>with special guests<br>TBC</p>
										</div>
										<div class="time-location">
											EARLY FEB<br>
											Magnet House,<br />
											Perth, WA
										</div>
									</div>
									<a href="#" target="_blank" class="btn mt-3">FIND OUT MORE</a>
								</div>

								<div class="gig-item d-flex flex-column justify-content-between">
									<div>
										<div class="img-wrap">
											<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/jd-stacked-dark.png" style="width: 80%;">
										</div>
										<div class="gig-info">
											<h3>Artist TBC</h3>
											<p>with special guests<br>TBC</p>
										</div>
										<div class="time-location">
											LATE FEB<br>
											The Cambridge Hotel,<br />
											Newcastle West, NSW
										</div>
									</div>
									<a href="#" target="_blank" class="btn mt-3">FIND OUT MORE</a>
								</div>

								<div class="gig-item d-flex flex-column justify-content-between">
									<div>
										<div class="img-wrap">
											<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/jd-stacked-dark.png" style="width: 80%;">
										</div>
										<div class="gig-info">
											<h3>Artist TBC</h3>
											<p>with special guests<br>TBC</p>
										</div>
										<div class="time-location">
											LATE MARCH/EARLY APRIL<br>
											170 Russell,<br />
											Melbourne, VIC
										</div>
									</div>
									<a href="#" target="_blank" class="btn mt-3">FIND OUT MORE</a>
								</div>
							</div>
						</div>
						<div class="gigs-bottom">KEEP YOUR EYES PEELED FOR FURTHER JACK DANIEL'S MAKE IT COUNT TOUR ANNOUNCEMENTS</div>
					</section><!-- .gigs -->

					<section class="map">
						<iframe src="https://snazzymaps.com/embed/430993" width="100%" height="600px" style="border:none;"></iframe>
					</section>

<!-- 					<section class="latest-news px-2 px-md-4 py-2 py-md-3">
						<div class="content-wrap">
							<h2>Latest News</h2>
							<div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch">
								<div class="col-12 col-md-8">
									<div class="mb-4 mb-md-2 mr-0 mr-md-1">
										<a href="#" target="_blank" class="news">
											<img src="https://via.placeholder.com/1200x800">
											<h4>Beddy Rays join Dune Rats for the return of Jack Daniel’s Make It Count</h4>
											<p>Read the full story on Rolling Stone Australia</p>
										</a>
									</div>
								</div>
								<div class="col-12 col-md-4 d-flex align-items-stretch">
									<div class="d-flex flex-column justify-content-between">
										<a href="https://au.rollingstone.com/music/music-features/psychedelic-porn-crumpets-39712/" target="_blank" class="news mb-2 mb-md-2 ml-0 ml-md-2">
											<img src="https://via.placeholder.com/600x860">
											<h4>Dune Rats are frothing to Make It Count for Jack Daniel’s Tour and Support Act</h4>
										</a>
									</div>
								</div>
							</div>
							<div class="latest-news-bottom text-center mt-3" style="color: #8B8B8B">
								PLAY IT LOUD. ENJOY RESPONSIBLY. PLEASE DO NOT FORWARD OR SHARE WITH ANYONE UNDER 18.
							</div>

					</section> -->
					<!-- .latest-news -->



				</div><!-- #content-wrap -->
			<?php
		else :
			?>
				<div class="l-page__content">
					<div class="l-section l-section--no-separator">
						<div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">
							<div style="margin: 1rem auto;">
								<?php echo get_the_password_form(); ?>
							</div>
						</div>
					</div>
				</div>
	<?php
		endif; // Password protected
	endwhile;
	wp_reset_query();
endif;

	?>
	<?php get_template_part('template-parts/footer/footer'); ?>
			</div><!-- .l-page__content -->
			<script>
				jQuery(document).ready(function($) {
					$('.map').click(function () {
						$('.map iframe').css("pointer-events", "auto");
					});

					$( ".map" ).mouseleave(function() {
						$('.map iframe').css("pointer-events", "none"); 
					});
				});
			</script>
			<?php
			get_footer();
