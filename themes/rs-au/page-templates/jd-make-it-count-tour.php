<?php

/**
 * Template Name: JD Make It Count Tour (2022)
 */
get_header('jd-make-it-count-tour');
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
									<a href="https://supportact.org.au/about-support-act/" target="_blank" class="btn">LEARN MORE</a>
								</div>
							</div>
						</div>
					</section><!-- .intro -->

					<section class="gigs d-flex">
						<div class="gigs-wrap d-flex flex-column flex-md-row align-items-stretch">
							<div class="gigs-menu d-none d-md-block mx-2">
								<!-- 								<ul>
									<li><a href="#">Events</a></li>
									<li><a href="#">Map</a></li>
									<li><a href="#">News</a></li>
									<li><a href="#">Previous Galleries</a></li>
								</ul> -->
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

							<div class="gig-items">
								<div class=" d-flex align-items-stretch justify-content-start flex-column flex-md-row">
									<div class="gig-item d-flex flex-column justify-content-between">
										<div class="state">QLD</div>
										<div>
											<div class="img-wrap">
												<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/4-CH0A0269-1.jpg">
											</div>
											<div class="gig-info">
												<h3>DUNE RATS</h3>
												<p>with special guests<br>Beddy Rays &amp; VOIID</p>
											</div>
											<div class="time-location">
												Friday 11th November, 4:00 pm<br>
												Night Quarter,<br />
												Sunshine Coast, QLD
											</div>
										</div>
										<div class="btn mt-3" style="cursor: auto; opacity: 0.3;">GET TICKETS</div>
									</div>

									<div class="gig-item d-flex flex-column justify-content-between">
										<div class="state">NSW</div>
										<div>
											<div class="img-wrap">
												<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/StandAtlantic.jpg">
											</div>
											<div class="gig-info">
												<h3>Stand Atlantic</h3>
												<p>with special guests<br>Redhook</p>
											</div>
											<div class="time-location">
												Wednesday 7th December, 7:30 pm<br>
												Crowbar,<br />
												Sydney (Leichhardt, NSW)
											</div>
										</div>
										<!-- <a href="#" target="_blank" class="btn soldout mt-3">SOLD OUT</a> -->
										<div class="btn mt-3" style="cursor: auto; opacity: 0.3;">GET TICKETS</div>
									</div>

									<div class="gig-item d-flex flex-column justify-content-between">
										<div class="state">NSW</div>
										<div>
											<div class="img-wrap">
												<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/rolling2.jpg">
											</div>
											<div class="gig-info">
												<h3>ROLLING BLACKOUTS COASTAL FEVER</h3>
												<p>with special guests<br>Hatchie</p>
											</div>
											<div class="time-location">
												Thursday 16th March<br>
												The Cambridge Hotel,<br />
												Newcastle West, NSW
											</div>
										</div>
										<!-- <a href="https://tickets.oztix.com.au/outlet/event/6aca5f66-4b74-48f6-ab4c-55f02c7ad8a3" target="_blank" class="btn mt-3">GET TICKETS</a> -->
										<div class="btn mt-3" style="cursor: auto; opacity: 0.3;">GET TICKETS</div>
									</div>
								</div>

								<div class="thin-line mb-2"></div>
								<!-- 								<h3 class="subhead mb-2 mt-2 d-flex justify-content-start flex-nowrap">
									<span>COMING SOON</span>
									<span class="ml-2"><img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/arrow-right.png"></span>
								</h3> -->
								<div class=" d-flex align-items-stretch justify-content-start flex-column flex-md-row">
									<div class="gig-item d-flex flex-column justify-content-between">
										<div class="state">VIC</div>
										<div>
											<div class="img-wrap">
												<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/touch.jpg">
											</div>
											<div class="gig-info">
												<h3>TOUCH SENSITIVE</h3>
												<p>with special guests<br>Nyxen, Juno Mamba & Foura (DJ Set)</p>
											</div>
											<div class="time-location">
												Wednesday 29th March<br>
												170 Russell,<br />
												Melbourne, VIC
											</div>
										</div>
										<div class="btn mt-3" style="cursor: auto; opacity: 0.3;">GET TICKETS</div>
									</div>

									<div class="gig-item d-flex flex-column justify-content-between">
										<div class="state">WA</div>
										<div>
											<div class="img-wrap">
												<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/sly-withers.jpg">
											</div>
											<div class="gig-info">
												<h3>SLY WITHERS</h3>
												<p>with special guests<br>Mal De Mar</p>
											</div>
											<div class="time-location">
												Tuesday 24th April<br>
												Magnet House,<br />
												Perth, WA
											</div>
										</div>
										<div class="btn mt-3" style="cursor: auto; opacity: 0.3;">GET TICKETS</div>
									</div>
								</div>
							</div>
						</div>
						<div class="gigs-bottom">KEEP YOUR EYES PEELED FOR FURTHER JACK DANIEL'S MAKE IT COUNT TOUR ANNOUNCEMENTS</div>
					</section><!-- .gigs -->

					<!-- 					<section class="video d-flex p-2">
						<div class="overlay"></div>
						<div class="btn-play"></div>
						<video controlslist="nodownload" width="250" id="jd-video">
							<source src=" https://cdn.thebrag.com/videos/APPLE_WHISKEY_VIDEO_4x5.mp4" type="video/mp4">
						</video>
					</section><!-- .video --> -->

					<section class="map">
						<iframe src="https://snazzymaps.com/embed/430993" width="100%" height="600px" style="border:none;"></iframe>
					</section>

					<section class="intro-wrap">
						<div class="content-wrap" style="background: transparent;">
							<div class="latest-news" style="background: transparent;">
								<h2 style="color: #fff; text-align:center; border-top: none;">Make It Count Tour 2022</h2>
							</div>
						</div>
						<div class="d-flex flex-wrap flex-column flex-md-row">
							<div class="col-12 col-md-4">
								<div class="my-3 mx-3 previous-events">
									<div class="mx-0 mx-md-2 img-wrap"><img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2022/12/StandAtlantic_ChrisFrape-264.jpg&#038;nologo=1"></div>
									<div style="color: #fff; margin-top: 15px; text-align: center;">
										<h3 style="font-weight: bold;">STAND ATLANTIC with special guests Redhook</h3>
										<p>
											Friday, November 11th
											<br>
											Night Quarter, Sunshine Coast
										</p>
										<div class="mt-2">
											<a href="https://au.rollingstone.com/music/music-pictures/gallery-jack-daniels-make-it-count-with-stand-atlantic-44308/" target="_blank" class="btn btn-tickets">VIEW GALLERY</a>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12 col-md-4">
								<div class="my-3 mx-3 previous-events">
									<div class="mx-0 mx-md-2 img-wrap"><img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2022/11/ktunbridge-140.jpg&#038;nologo=1"></div>
									<div style="color: #fff; margin-top: 15px; text-align: center;">
										<h3 style="font-weight: bold;">DUNE RATS with special guests Beddy Rays & VOIID</h3>
										<p>
											Wednesday, 7th December
											<br>
											Crowbar, Sydney (Leichhardt, NSW)
										</p>
										<div class="mt-2">
											<a href="https://au.rollingstone.com/music/music-pictures/gallery-jack-daniels-make-it-count-with-dune-rats-43895/" target="_blank" class="btn btn-tickets">VIEW GALLERY</a>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12 col-md-4">
								<div class="my-3 mx-3 previous-events">
									<div class="mx-0 mx-md-2 img-wrap"><img src="<?php echo get_template_directory_uri(); ?>/page-templates/jd-make-it-count-tour/JACK-DANIELS-MAKE-IT-COUNT-TOUR_ROLLING-STONE-WEB-social.jpg"></div>
									<div style="color: #fff; margin-top: 15px; text-align: center;">
										<h3 style="font-weight: bold;">ROLLING BLACKOUTS COASTAL FEVER with special guests Hatchie</h3>
										<p>
											Thursday, 16th March
											<br>
											The Cambridge Hotel, Newcastle West, NSW
										</p>
										<div class="mt-2">
											<a href="https://au.rollingstone.com/music/music-pictures/gallery-jack-daniels-make-it-count-with-rolling-blackouts-coastal-fever-and-hatchie-46320/" target="_blank" class="btn btn-tickets">VIEW GALLERY</a>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12 col-md-4">
								<div class="my-3 mx-3 previous-events">
									<div class="mx-0 mx-md-2 img-wrap"><img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2023/04/rollingstoneXJackDaniel-146.jpg&nologo=1"></div>
									<div style="color: #fff; margin-top: 15px; text-align: center;">
										<h3 style="font-weight: bold;">TOUCH SENSITIVE with special guests Nyxen, Juno Mamba & Foura (DJ Set)</h3>
										<p>
											Wednesday 29th March
											<br>
											170 Russell, Melbourne, VIC
										</p>
										<div class="mt-2">
											<a href="https://au.rollingstone.com/music/music-pictures/gallery-jack-daniels-make-it-count-with-touch-sensitive-46551/" target="_blank" class="btn btn-tickets">VIEW GALLERY</a>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12 col-md-4">
								<div class="my-3 mx-3 previous-events">
									<div class="mx-0 mx-md-2 img-wrap"><img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2023/05/RSMakeItCount_PERTH-62.jpg&nologo=1"></div>
									<div style="color: #fff; margin-top: 15px; text-align: center;">
										<h3 style="font-weight: bold;">SLY WITHERS with special guests Mal De Mar</h3>
										<p>
											Tuesday 24th April
											<br>
											Magnet House, Perth, WA
										</p>
										<div class="mt-2">
											<a href="https://au.rollingstone.com/music/music-pictures/gallery-jack-daniels-make-it-count-with-sly-withers-47257/" target="_blank" class="btn btn-tickets">VIEW GALLERY</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section><!-- .intro -->


					<section class="latest-news px-2 px-md-4 py-2 py-md-3">
						<div class="content-wrap">
							<h2>Latest News</h2>
							<div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch">
								<div class="col-12 col-md-8 d-flex align-items-stretch">
									<div class="mb-2 mr-0 mr-md-1 d-flex align-items-stretch">
										<a href="https://au.rollingstone.com/music/music-news/jack-daniels-make-it-count-brisbane-43844/" target="_blank" class="news">
											<img src="https://images-r2.thebrag.com/rs/uploads/2020/11/dune.jpg?resize=900,600&w=1200">
											<h4>Dune Rats Bring The Chaos to Jack Daniel’s Make It Count Brisbane Show</h4>
											<p>Kicking off the seventh stop in the Make It Count tour, Dune Rats brought their trademark chaotic and energetic stage presence, which has made them one of the country’s most exciting and talked-about live acts of the past decade.</p>
										</a>
									</div>
								</div>
								<div class="d-flex flex-column col-12 col-md-4">
									<div class="d-flex align-items-stretch">
										<div class="d-flex flex-column justify-content-between">
											<a href="https://au.rollingstone.com/music/music-live-reviews/stand-atlantic-and-redhook-jack-daniels-make-it-count-tour-44394/" target="_blank" class="news mb-2 ml-0 ml-md-2">
												<img src="https://images-r2.thebrag.com/rs/uploads/2022/12/jack-daniels.jpg?resize=900,600&w=1200">
												<h4 class="side">Stand Atlantic and RedHook Tear It Up At Sydney’s Crowbar for Jack Daniel’s Make It Count Tour</h4>
											</a>
										</div>
									</div>
									<div class="d-flex align-items-stretch">
										<div class="d-flex flex-column justify-content-between">
											<a href="https://au.rollingstone.com/music/music-live-reviews/jack-daniels-make-it-count-rolling-blackouts-coastal-fevel-hatchie-46095/" target="_blank" class="news mb-2 ml-0 ml-md-2">
												<img src="https://images-r2.thebrag.com/rs/uploads/2023/03/jack-daniels.jpg?resize=1400,700&w=1400">
												<h4 class="side">Rolling Blackouts Coastal Fever and Hatchie ‘Make It Count’ at One of The Cambridge Hotel’s Final Shows</h4>
											</a>
										</div>
									</div>

								</div>
								<!-- 								<div class="d-flex flex-column col-12 col-md-4">
									<div class="d-flex align-items-stretch">
										<div class="d-flex flex-column justify-content-between">
											<a href="https://au.rollingstone.com/music/music-features/the-great-australian-songs-written-by-musicians-who-trusted-their-spirit-44188/" target="_blank" class="news mb-2 ml-0 ml-md-2">
												<img src="https://images-r2.thebrag.com/rs/uploads/2022/01/gotye-1.jpg?resize=900,600&w=1200">
												<h4 class="side">The Great Australian Songs Written by Musicians Who Trusted Their Spirit</h4>
											</a>
										</div>
									</div>
									<div class="d-flex align-items-stretch">
										<div class="d-flex flex-column justify-content-between">
											<a href="https://au.rollingstone.com/music/music-news/jack-daniels-make-it-count-brisbane-43844/" target="_blank" class="news mb-2 ml-0 ml-md-2">
												<img src="https://images-r2.thebrag.com/rs/uploads/2020/11/dune.jpg?resize=900,600&w=1200">
												<h4 class="side">Dune Rats Bring The Chaos to Jack Daniel’s Make It Count Brisbane Show</h4>
											</a>
										</div>
									</div>
								</div> -->
							</div>
							<div class="latest-news-bottom text-center mt-3" style="color: #8B8B8B">
								PLAY IT LOUD. ENJOY RESPONSIBLY. PLEASE DO NOT FORWARD OR SHARE WITH ANYONE UNDER 18.
							</div>
					</section>
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
					$('.map').click(function() {
						$('.map iframe').css("pointer-events", "auto");
					});

					$(".map").mouseleave(function() {
						$('.map iframe').css("pointer-events", "none");
					});

					$(".video .overlay, .video .btn-play").click(function() {
						var video = $("#jd-video").get(0);
						video.play();

						$(this).hide();
						return false;
					});

					$("#jd-video").bind("play", function() {
						$(".video .overlay, .video .btn-play").hide();
					});
					$("#jd-video").bind("pause ended", function() {
						$(".video .overlay, .video .btn-play").show();
					});
				});
			</script>
			<?php
			get_footer();
