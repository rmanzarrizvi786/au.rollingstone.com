<?php

/**
 * Template Name: JD Live at Last (2022)
 */
get_header('jd-live-at-last');
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
									<h1 class="l-header__branding">
										<img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/JDliveatlast.svg" class="feature-image">
									</h1>
									<div class="text">Giving back to music and reigniting the live music scene that we have so dearly missed. The 2022 instalment of the Live At Last tour was bigger and better than ever with an all-star Aussie lineup heading to some of Australia’s most beloved East Coast venues</div>
									<!-- .l-header__branding -->
								</div><!-- .l-header__content -->
							</div>
						</div>
						<!--. l-header__wrap -->

					</header><!-- .l-header -->
				</div>
				<div id="content-wrap2">
					<section class="intro">
						<div class="content d-flex flex-column">
							<div class="text">
								All proceeds of ticket sales will be donated to Support Act. Support Act is Australia’s only charity delivering crisis relief services to artists, artist managers, crew and music workers as a result of ill health, injury, a mental health problem, or some other crisis that impacts on their ability to work in music.
							</div>
							<div class="mt-3">
								<a href="https://www.secretsounds.com/tours/jack-daniels-live-last2022/" target="_blank" class="btn">FIND OUT MORE</a>
							</div>
						</div>
					</section><!-- .intro -->

					<section class="shows">
						<div class="content d-flex flex-column">
							<h2 class="text-center mt-3 mb-2">Live At Last Tour 2022</h2>
							<div class="d-flex flex-wrap flex-column flex-md-row">
								<!-- 
								<div class="col-12 col-md-6">
									<div class="my-3">
										<div class="mx-0 mx-md-2"><img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/Psychedelic-Porn-Crumpets.jpg"></div>
										<div>
											<h3>PSYCHEDELIC PORN CRUMPETS</h3>
											<p>
												Thursday, April 21st
												<br>
												Factory Theatre, Sydney
											</p>
											<div class="mt-2">
												<a href="https://aucentury.sales.ticketsearch.com/sales/salesevent/52056" target="_blank" class="btn btn-tickets">TICKETS</a>
											</div>
										</div>
									</div>
								</div>
 
								<div class="col-12 col-md-6">
									<div class="my-3">
										<div class="mx-0 mx-md-2"><img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/san-cisco.jpg"></div>
										<div>
											<h3>SAN CISCO</h3>
											<p>
												Sunday, April 24th
												<br>
												Fortitude Music Hall, Brisbane
											</p>
											<div class="mt-2">
												<a href="https://www.ticketmaster.com.au/event/13005C68C6BFB9ED" target="_blank" class="btn btn-tickets">TICKETS</a>
											</div>
										</div>
									</div>
								</div>

								<div class="col-12 col-md-6">
									<div class="my-3">
										<div class="mx-0 mx-md-2"><img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/ruby-fields.jpg"></div>
										<div>
											<h3>RUBY FIELDS</h3>
											<p>
												Thursday, April 28th
												<br>
												The Espy, Melbourne
											</p>
											<div class="mt-2">
												<a href="https://moshtix.com.au/v2/event/live-at-last-feat-ruby-fields-with-special-guests/137188?skin=theespymelb" target="_blank" class="btn btn-tickets">TICKETS</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							-->

								<!-- <h2 class="text-center mt-3 mb-2">Previous Events</h2> -->
								<div class="d-flex flex-wrap flex-column flex-md-row">
									<div class="col-12 col-md-6">
										<div class="my-3 mx-3 previous-events">
											<div class="mx-0 mx-md-2 img-wrap"><img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/Psychedelic-Porn-Crumpets.jpg"></div>
											<div>
												<h3>PSYCHEDELIC PORN CRUMPETS</h3>
												<p>
													Thursday, April 21st
													<br>
													Factory Theatre, Sydney
												</p>
												<div class="mt-2">
													<a href="https://au.rollingstone.com/music/music-pictures/gallery-jack-daniels-live-at-last-with-psychedelic-porn-crumpets-39985/" target="_blank" class="btn btn-tickets">VIEW GALLERY</a>
												</div>
											</div>
										</div>
									</div>

									<!-- <div class="col-12 col-md-6">
									<div class="my-3 mx-3 previous-events">
										<div class="mx-0 mx-md-2 img-wrap"><img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/pond.jpg"></div>
										<div>
											<h3>POND</h3>
											<p>
												Tuesday, April 12th
												<br>
												SolBar, Sunshine Coast
											</p>
											<div class="mt-2">
												<a href="https://au.rollingstone.com/music/music-pictures/gallery-jack-daniels-live-at-last-with-pond-39717/" target="_blank" class="btn btn-tickets">VIEW GALLERY</a>
											</div>
										</div>
									</div>
								</div> -->

									<div class="col-12 col-md-6">
										<div class="my-3 mx-3 previous-events">
											<div class="mx-0 mx-md-2 img-wrap"><img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/san-cisco.jpg"></div>
											<div>
												<h3>SAN CISCO</h3>
												<p>
													Sunday, April 24th
													<br>
													Fortitude Music Hall, Brisbane
												</p>
												<div class="mt-2">
													<a href="https://au.rollingstone.com/music/music-pictures/gallery-jack-daniels-live-at-last-with-san-cisco-39981/" target="_blank" class="btn btn-tickets">VIEW GALLERY</a>
												</div>
											</div>
										</div>
									</div>

									<div class="col-12 col-md-6">
										<div class="my-3 mx-3 previous-events">
											<div class="mx-0 mx-md-2 img-wrap"><img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/ruby-fields.jpg"></div>
											<div>
												<h3>RUBY FIELDS</h3>
												<p>
													Thursday, April 28th
													<br>
													The Espy, Melbourne
												</p>
												<div class="mt-2">
													<a href="https://au.rollingstone.com/music/music-pictures/gallery-jack-daniels-live-at-last-with-ruby-fields-40394/" target="_blank" class="btn btn-tickets">VIEW GALLERY</a>
												</div>
											</div>
										</div>
									</div>

									<div class="col-12 col-md-6">
										<div class="my-3 mx-3 previous-events">
											<div class="mx-0 mx-md-2 img-wrap"><img src="<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/pond.jpg"></div>
											<div>
												<h3>POND</h3>
												<p>
													Tuesday, April 12th
													<br>
													SolBar, Sunshine Coast
												</p>
												<div class="mt-2">
													<a href="https://au.rollingstone.com/music/music-pictures/gallery-jack-daniels-live-at-last-with-pond-39717/" target="_blank" class="btn btn-tickets">VIEW GALLERY</a>
												</div>
											</div>
										</div>
									</div>


								</div>
							</div>
					</section><!-- .shows -->

					<section class="latest-news px-2 px-md-4 py-2 py-md-3">
						<div class="content-wrap">
							<div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch">
								<div class="col-12 col-md-8">
									<div class="mb-4 mb-md-2 mr-0 mr-md-1">
										<h2>Latest News</h2>
										<a href="https://au.rollingstone.com/music/music-live-reviews/san-cisco-live-at-last-2022-review-40369/" target="_blank" class="news">
											<img src="https://au.rollingstone.com/wp-content/uploads/2022/04/Jack-Daniels-Live-At-Last-Pond-by-Kieran-Tunbridge-174.jpg?resize=900,600&w=1200">
											<h4>Jack Daniel’s brings San Cisco, Wafia, and merci, mercy to Live at Last in Brisbane</h4>
											<p>San Cisco ensured that the Brisbane leg of the Jack Daniel’s Live At Last series was one that fans would remember forever.</p>
											<!-- <p>Read the full story on Rolling Stone Australia</p> -->
										</a>
									</div>
								</div>
								<div class="col-12 col-md-4 d-flex align-items-stretch">
									<div class="d-flex flex-column justify-content-between">
										<a href="https://au.rollingstone.com/music/music-features/psychedelic-porn-crumpets-39712/" target="_blank" class="news mb-2 mb-md-2 ml-0 ml-md-2">
											<img src="https://au.rollingstone.com/wp-content/uploads/2021/11/ppc.jpg?resize=900,600&w=1200">
											<h4>&quot;We’re just such a better band in terms of musicianship&quot;: Psychedelic Porn Crumpets Are Finally Coming Back to the East Coast</h4>
											<!-- <p>Read the full story on Rolling Stone Australia</p> -->
										</a>
										<a href="https://au.rollingstone.com/music/music-features/san-cisco-are-growing-up-and-taking-pop-music-with-them-39699/" target="_blank" class="news mb-2 mb-md-2 ml-0 ml-md-2">
											<img src="https://au.rollingstone.com/wp-content/uploads/2021/04/san-cisco.jpeg?resize=1400,700&w=1400">
											<h4>San Cisco Are Growing Up and Taking Pop Music with Them</h4>
										</a>
									</div>
								</div>
							</div>

							<div class="d-flex flex-wrap flex-column flex-md-row align-items-start justify-content-start">
								<a href="https://au.rollingstone.com/music/music-live-reviews/psychedelic-porn-crumpets-live-at-last-2022-review-2-40373/" target="_blank" class="col-12 col-md-4 mt-2 mb-md-2 mr-0">
									<div class="mr-0 mr-md-2 news">
										<img src="https://au.rollingstone.com/wp-content/uploads/2022/05/ppc.jpg?resize=900,600&w=1200">
										<h4>Psychedelic Porn Crumpets Mark Their Return to Live Shows with One Hell of A Good Time</h4>
									</div>
								</a>

								<a href="https://au.rollingstone.com/music/music-features/pond-evolve-as-songwriters-without-losing-their-core-effervescence-39349/" target="_blank" class="col-12 col-md-4 mt-2 mb-md-2 mx-0">
									<div class="mx-0 mx-md-1 news">
										<img src="https://au.rollingstone.com/wp-content/uploads/2022/03/pond.jpg?resize=1400,700&w=1400">
										<h4>Pond Evolve as Songwriters Without Losing Their Core Effervescence</h4>
									</div>
								</a>

								<a href="https://au.rollingstone.com/music/music-live-reviews/ruby-fields-live-at-last-2022-review-40441/" target="_blank" class="col-12 col-md-4 mt-2 mb-md-2 ml-0">
									<div class="ml-0 ml-md-2 news">
										<img src="https://au.rollingstone.com/wp-content/uploads/2022/05/Jack-Daniels-Live-At-Last-Ruby-Fields-by-Nathan-Goldsworthy-38.jpg?resize=900,600&w=1200">
										<h4>Ruby Fields Capped Off a Stellar Jack Daniel’s Live at Last Tour in Melbourne</h4>
									</div>
								</a>
							</div>

					</section>

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
			<?php
			get_footer();
