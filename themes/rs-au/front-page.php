<?php

/**
 * Template name: homepage
 *
 * @package pmc-rollingstone-2018
 * @since 2019-11-23
 */

get_header();
?>

<div class="l-page__content">
	<div class="l-home-top">
		<div class="l-home-top__3-pack">
			<section class="l-3-pack l-3-pack--reversed">
				<?php
				$args = array(
					//'category_name' => 'culture',
					"posts_per_page" => 1,
					//'post_type' => 'pmc_top_video',
					"orderby"        => "date",
					//'meta_key' => 'pmc_top_video_source',
					//'meta_value' => '',
					//'meta_compare' => '!=',
					"order"          => "DESC"
				);
				$videoPosts = get_posts($args);
				foreach ($videoPosts as $post) {
				?>
					<div class="l-3-pack__item l-3-pack__item--primary">
						<article class="c-card c-card--overlay c-card--overlay--home">
							<a href="<?php the_permalink(); ?>" class="c-card__wrap">
								<figure class="c-card__image" id="img-article-50043">
									<div class="c-crop c-crop--ratio-5x6">
										<?php echo get_the_post_thumbnail(); ?>
									</div>
									<!-- .c-crop -->
								</figure>
								<!-- .c-card__image -->
								<header class="c-card__header">
									<div class="c-badge c-badge--sponsored"></div>
									<!-- .c-badge--sponsored -->
									<h3 class="c-card__heading t-bold">
										<?php the_title(); ?>
									</h3>
									<!-- .c-card__heading -->
									<div class="c-card__tag t-bold t-bold--upper">
										<span class="screen-reader-text">Posted in:</span>
										<span class="c-card__featured-tag"> Music News</span>
									</div>
									<!-- c-card__tag -->
									<p class="c-card__lead">
										<?php echo wp_trim_words($post->post_content, 20); ?>
									</p>
									<!-- c-card__lead -->
								</header>
								<!-- .c-card__header -->
							</a>
							<!-- .c-card__wrap -->
						</article>
						<!-- .c-card--overlay -->
					</div>
				<?php } ?>
				<!-- .l-3-pack__item--primary -->
				<?php
				$args = array(
					//'category_name' => 'culture',
					"posts_per_page" => 3,
					//'post_type' => 'pmc_top_video',
					"orderby"        => "date",

					"order"          => "DESC"
				);
				$videoPosts = get_posts($args);
				foreach ($videoPosts as $post) {
				?>
					<div class="l-3-pack__item l-3-pack__item--secondary">
						<article class="c-card c-card--featured-home" id="home-c-card-49872">
							<a href="<?php the_permalink(); ?>" class="c-card__wrap">
								<figure class="c-card__image">

									<?php echo get_the_post_thumbnail(); ?>

									<!-- .c-crop -->
								</figure>
								<!-- .c-card__image -->
								<header class="c-card__header">
									<div class="c-badge c-badge--sponsored"></div>
									<!-- .c-badge--sponsored -->
									<h3 class="c-card__heading t-bold">
										<?php the_title(); ?>
									</h3>
									<!-- .c-card__heading -->
									<div class="c-card__tag t-bold t-bold--upper">
										<span class="screen-reader-text">Posted in:</span>
										<span class="c-card__featured-tag"> Electronics</span>
									</div>
									<!-- c-card__tag -->
								</header>
								<!-- .c-card__header -->
							</a>
							<!-- .c-card__wrap -->
						</article>
						<!-- .c-card--featured -->
					</div>
				<?php } ?>
				<!-- .l-3-pack__item--[secondary|tertiary] -->
				<div class="l-3-pack__item l-3-pack__item--tertiary">
					<article class="c-card c-card--featured-home" id="home-c-card-50144">
						<a href="https://au.rollingstone.com/music/music-news/limp-bizkit-new-zealand-show-tickets-50144/" class="c-card__wrap">
							<figure class="c-card__image">

								<?php echo get_the_post_thumbnail(); ?>

								<!-- .c-crop -->
							</figure>
							<!-- .c-card__image -->
							<header class="c-card__header">
								<div class="c-badge c-badge--sponsored"></div>
								<!-- .c-badge--sponsored -->
								<h3 class="c-card__heading t-bold"> Limp Bizkit Are Rollin’ Rollin’ Rollin’ Into New Zealand</h3>
								<!-- .c-card__heading -->
								<div class="c-card__tag t-bold t-bold--upper">
									<span class="screen-reader-text">Posted in:</span>
									<span class="c-card__featured-tag"> Music News</span>
								</div>
								<!-- c-card__tag -->
							</header>
							<!-- .c-card__header -->
						</a>
						<!-- .c-card__wrap -->
					</article>
					<!-- .c-card--featured -->
				</div>
				<!-- .l-3-pack__item--[secondary|tertiary] -->
			</section>
			<!-- .l-3-pack -->
		</div>
		<!-- /.l-home-top__3-pack -->
		<!-- <div class="l-home-top__list"> -->
		<!-- </div>
					<!-- /.l-home-top__list -->
		<aside class="l-home-top__sidebar">
			<div class="l-home-top__sidebar-item">
				<div class="c-trending c-trending--hero">
					<h4 class="c-trending__heading t-bold t-bold--loose"> Trending </h4>
					<!-- .c-trending__heading -->
					<ol class="c-trending__list">
						<?php
						$args = array(
							//'category_name' => 'music',
							"posts_per_page" => 3,
							"orderby"        => "date",
							"order"          => "DESC"
						);
						$trendingPosts = get_posts($args);

						foreach ($trendingPosts as $post) {

						?>
							<li class="c-trending__item">
								<a href="https://au.rollingstone.com/music/music-features/tony-cohen-nick-cave-memoir-47732/" class="c-trending__link">
									<h5 class="c-trending__title">
										<div class="c-trending__number">
											<span class="c-trending__counter t-bold"></span>
										</div>
										<span class="c-trending__caption t-semibold"> <?php the_title(); ?> </span>
									</h5>
									<!-- .c-trending__title -->
								</a>
								<!-- .c-trending__link -->
							</li>
							<!-- .c-trending__item --> <?php } ?>
					</ol>
					<!-- .c-trending__list -->
				</div>
				<!-- .c-trending -->
			</div>
			<div id="sticky-rail-ad" style="z-index: 1000;">
				<div class="admz pmc-adm-goog-pub-div c-ad c-ad--300x250 c-ad--boxed" id="adm-right-rail-1">
					<div id="adm_rail1" style="width: 300px; margin: auto;">
						<div data-fuse="22378668214" data-fuse-code="fuse-slot-22378668214-1" data-fuse-zone-instance="zone-instance-22378668214-1" data-fuse-slot="fuse-slot-22378668214-1" data-fuse-processed-at="4975">
							<div id="fuse-slot-22378668214-1" class="fuse-slot" style="max-width: inherit; max-height: inherit;" data-google-query-id="CKvA_9-du4EDFTOZJwIdbBABBQ">
								<div id="google_ads_iframe_22071836792/SSM_rollingstone/homepage_vrec_1_0__container__" style="border: 0pt none; margin: auto; text-align: center; width: 300px; height: 600px;">
									<iframe frameborder="0" src="https://4b331e11edcc2408f79d97a40ba91dd0.safeframe.googlesyndication.com/safeframe/1-0-40/html/container.html" id="google_ads_iframe_22071836792/SSM_rollingstone/homepage_vrec_1_0" title="3rd party ad content" name="" scrolling="no" marginwidth="0" marginheight="0" width="300" height="600" data-is-safeframe="true" sandbox="allow-forms allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation-by-user-activation" allow="attribution-reporting" role="region" aria-label="Advertisement" tabindex="0" data-google-container-id="2" style="border: 0px; vertical-align: bottom;" data-gtm-yt-inspected-10="true" data-gtm-yt-inspected-16="true" data-load-complete="true"></iframe>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</aside>
		<!-- /.l-home-top__sidebar -->
	</div>
	<!-- .l-home-top -->
	<div class="l-section" data-section="Music">
		<script>
			PMC_RS_setHomeAppearance("Music");
		</script>
		<div class="l-section__header">
			<div class="c-section-header">
				<h3 class="c-section-header__heading t-bold t-bold--condensed">
					Music
				</h3>
				<a href="https://au.rollingstone.com/music/" class="c-section-header__cta t-semibold t-semibold--upper t-semibold--loose"> View All </a>
				<p class="c-section-header__msg"> You have set the display of this section to be hidden. <br> Click the button to the right to show it again. </p>
				<!-- /.c-section-header__msg -->
				<a href="#" class="c-section-header__btn" data-section-toggle="">
					<span class="c-section-header__hide t-semibold t-semibold--upper">Hide</span>
					<span class="c-section-header__show t-semibold t-semibold--upper">Show</span>
					<svg class="c-section-header__btn-arrow">
						<use xlink:href="#svg-icon-arrow-down"></use>
					</svg>
				</a>
			</div>
			<!-- .c-section-header -->
		</div>
		<!-- /.l-section__header -->
		<div class="l-section__content">
			<div class="l-section__grid">
				<div class="c-cards-grid">
					<?php
					$args = array(
						'category_name' => 'music',
						"posts_per_page" => 6,
						"orderby"        => "date",
						"order"          => "DESC"
					);
					$posts = get_posts($args);

					foreach ($posts as $post) {

					?>
						<div class="c-cards-grid__item">
							<article class="c-card c-card--grid c-card--grid--primary ">
								<a href="<?php the_permalink(); ?>" class="c-card__wrap">
									<figure class="c-card__image">

										<?php echo get_the_post_thumbnail(); ?>

										<!-- .c-crop -->
									</figure>
									<!-- .c-card__image -->
									<header class="c-card__header">
										<div class="c-badge c-badge--sponsored"></div>
										<!-- .c-badge--sponsored -->
										<h3 class="c-card__heading t-bold"> <?php the_title(); ?> </h3>
										<!-- .c-card__heading -->
										<div class="c-card__tag t-bold t-bold--upper">
											<span class="screen-reader-text">Posted in:</span>
											<span class="c-card__featured-tag"> Music News</span>
										</div>
										<!-- c-card__tag -->
										<div class="c-card__byline t-copy"> By: <?php echo get_the_author_meta('display_name', $post->post_author); ?> </div>
										<!-- c-card__byline -->
										<p class="c-card__lead">
											<?php
											echo wp_trim_words($post->post_content, 20);
											//$post->post_content;
											//echo $content = get_the_content('Read more');
											//echo apply_filters('the_content', $content);
											?> </p>
										<!-- c-card__lead -->
									</header>
									<!-- .c-card__header -->
								</a>
								<!-- .c-card__wrap -->
							</article>
							<!-- .c-card--grid -->
						</div>
						<!-- /.c-cards-grid__item --> <?php } ?>
				</div>
				<!-- .c-cards-grid -->
			</div>
			<!-- /.l-section__grid -->
			<div class="l-section__sidebar">
				<div class="l-section__sticky c-sticky c-sticky--size-grow" data-section-ad="Music">
					<script>
						PMC_RS_toggleHomeAd("Music");
					</script>
					<div class="c-sticky__item">
						<div class="c-ad c-ad--300x250">
							<!--5 | homepage | vrec_2 | 1-->
							<div data-fuse="22378668586" style="margin: auto;" data-fuse-code="fuse-slot-22378668586-1" data-fuse-zone-instance="zone-instance-22378668586-1" data-fuse-slot="fuse-slot-22378668586-1" data-fuse-processed-at="4975">
								<div id="fuse-slot-22378668586-1" class="fuse-slot" style="max-width: inherit; max-height: inherit;" data-google-query-id="CK3A_9-du4EDFTOZJwIdbBABBQ">
									<div id="google_ads_iframe_22071836792/SSM_rollingstone/homepage_vrec_2_0__container__" style="border: 0pt none; margin: auto; text-align: center; width: 300px; height: 600px;">
										<iframe frameborder="0" src="https://4b331e11edcc2408f79d97a40ba91dd0.safeframe.googlesyndication.com/safeframe/1-0-40/html/container.html" id="google_ads_iframe_22071836792/SSM_rollingstone/homepage_vrec_2_0" title="3rd party ad content" name="" scrolling="no" marginwidth="0" marginheight="0" width="300" height="600" data-is-safeframe="true" sandbox="allow-forms allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation-by-user-activation" allow="attribution-reporting" role="region" aria-label="Advertisement" tabindex="0" data-google-container-id="4" style="border: 0px; vertical-align: bottom;" data-gtm-yt-inspected-10="true" data-gtm-yt-inspected-16="true" data-load-complete="true"></iframe>
									</div>
								</div>
							</div>
							<script type="text/javascript">
								fusetag.setTargeting("pagepath", ["homepage"]);
								fusetag.setTargeting("site", "rollingstoneau");
							</script>
						</div>
						<div class="c-ad c-ad--300x250" style="margin-top: 1rem;"></div>
					</div>
					<!-- /.c-sticky__item -->
				</div>
				<!-- /.l-section__sticky c-sticky c-sticky--size-grow -->
				<div class="l-section__sidebar-footer"></div>
				<!-- /.l-section__sidebar-footer -->
			</div>
			<!-- /.l-section__sidebar -->
		</div>
		<!-- /.l-section__content -->
	</div>
	<!-- /.l-section -->
	<div class="l-section">
		<div class="l-section__header">
			<div class="c-section-header"></div>
			<!-- .c-section-header -->
		</div>
		<!-- /.l-section__header -->
		<div class="l-section__content">
			<div class="c-video-grid video-of-week-wrap">
				<h3 class="c-section-header__heading t-bold t-bold--condensed"> Video of the week </h3>
				<div class="c-video-grid__main">
					<a style="position: relative; width:100%; cursor:pointer; overflow:hidden; display: block;" class="mb-3 home-featured-content" href="https://www.youtube.com/watch?v=18Wz3NqQVCc" target="_blank" rel="noreferrer">
						<div id="featured-video-player" class="youtube-player" style="height: 140px">
							<img src="https://i.ytimg.com/vi/18Wz3NqQVCc/maxresdefault.jpg?sqp=-oaymwEmCIAKENAF8quKqQMa8AEB-AH-CYACkgWKAgwIABABGGUgVChMMA8=&amp;rs=AOn4CLAUERN-0H_9XhwLojy9BTYWs15H3g" style="position: absolute; width: 100%; z-index: 1;top:50%;left:50%;transform:translate(-50%, -50%)" class="video-thumb visible" alt="" title="">
							<img class="play-button-red visible" src="https://cdn-r2-2.thebrag.com/assets/images//play-button-60px.png" style="width: 40px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 2;transition: .25s all linear;" alt="Play" title="Play">
						</div>
						<div class="featured-video-meta d-flex justify-content-between align-items-center">
							<h3 style="font-size: 1rem;line-height: 1.25;"> Luca George - 'Suit Of Blue' </h3>
						</div>
					</a>
				</div>
				<!-- /.c-video-grid__item -->
			</div>
			<!-- /.c-video-grid -->
			<div class="c-video-grid record-of-week-wrap" style="width: 300px;">
				<h3 class="c-section-header__heading t-bold t-bold--condensed"> Record of the week </h3>
				<div class="c-video-grid__main">
					<div id="record-of-week" style="overflow: hidden">
						<a href="https://open.spotify.com/album/0LZgoU16nhBXiosjVM1kF2?si=5bjg5dHSSU6SVTz0Z7BrEQ" target="_blank" class="d-block home-featured-content" style="display: block; position: relative;">
							<img src="https://images.thebrag.com/tb/uploads/2023/09/Artwork.jpg" style="height: 100%; width: auto; max-width: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" class="visible">
							<h3 class="title"> MANE - <em>Caught In The Undertow EP</em>
							</h3>
						</a>
					</div>
				</div>
				<!-- /.c-video-grid__item -->
			</div>
			<!-- /.c-video-grid -->
		</div>
		<!-- /.l-section__content -->
	</div>
	<div class="l-section" data-section="Culture">
		<script>
			PMC_RS_setHomeAppearance("Culture");
		</script>
		<div class="l-section__header">
			<div class="c-section-header">
				<h3 class="c-section-header__heading t-bold t-bold--condensed"> Culture </h3>
				<a href="https://au.rollingstone.com/culture/" class="c-section-header__cta t-semibold t-semibold--upper t-semibold--loose"> View All </a>
				<p class="c-section-header__msg"> You have set the display of this section to be hidden. <br> Click the button to the right to show it again. </p>
				<!-- /.c-section-header__msg -->
				<a href="#" class="c-section-header__btn" data-section-toggle="">
					<span class="c-section-header__hide t-semibold t-semibold--upper">Hide</span>
					<span class="c-section-header__show t-semibold t-semibold--upper">Show</span>
					<svg class="c-section-header__btn-arrow">
						<use xlink:href="#svg-icon-arrow-down"></use>
					</svg>
				</a>
			</div>
			<!-- .c-section-header -->
		</div>
		<!-- /.l-section__header -->
		<div class="l-section__content">
			<div class="l-section__grid">
				<div class="c-cards-grid">
					<?php
					$args = array(
						'category_name' => 'culture',
						"posts_per_page" => 6,
						"orderby"        => "date",
						"order"          => "DESC"
					);
					$posts = get_posts($args);

					foreach ($posts as $post) {

					?>
						<div class="c-cards-grid__item">
							<article class="c-card c-card--grid c-card--grid--primary ">
								<a href="<?php the_permalink(); ?>" class="c-card__wrap">
									<figure class="c-card__image">

										<?php echo get_the_post_thumbnail(); ?>

										<!-- .c-crop -->
									</figure>
									<!-- .c-card__image -->
									<header class="c-card__header">
										<div class="c-badge c-badge--sponsored"></div>
										<!-- .c-badge--sponsored -->
										<h3 class="c-card__heading t-bold"> <?php the_title(); ?> </h3>
										<!-- .c-card__heading -->
										<div class="c-card__tag t-bold t-bold--upper">
											<span class="screen-reader-text">Posted in:</span>
											<span class="c-card__featured-tag"> Music News</span>
										</div>
										<!-- c-card__tag -->
										<div class="c-card__byline t-copy"> By: <?php echo get_the_author_meta('display_name', $post->post_author); ?> </div>
										<!-- c-card__byline -->
										<p class="c-card__lead">
											<?php
											echo wp_trim_words($post->post_content, 20);
											//$post->post_content;
											//echo $content = get_the_content('Read more');
											//echo apply_filters('the_content', $content);
											?> </p>
										<!-- c-card__lead -->
									</header>
									<!-- .c-card__header -->
								</a>
								<!-- .c-card__wrap -->
							</article>
							<!-- .c-card--grid -->
						</div>
						<!-- /.c-cards-grid__item --> <?php } ?>
				</div>
				<!-- .c-cards-grid -->
			</div>
			<!-- /.l-section__grid -->
			<div class="l-section__sidebar">
				<div class="l-section__sticky c-sticky c-sticky--size-grow" data-section-ad="Culture">
					<script>
						PMC_RS_toggleHomeAd("Culture");
					</script>
					<div class="c-sticky__item">
						<div class="c-ad c-ad--300x250">
							<!--5 | homepage | vrec_3 | 2-->
							<div data-fuse="22378668583" style="margin: auto;" data-fuse-code="fuse-slot-22378668583-1" data-fuse-zone-instance="zone-instance-22378668583-1" data-fuse-slot="fuse-slot-22378668583-1" data-fuse-processed-at="4949">
								<div id="fuse-slot-22378668583-1" class="fuse-slot" style="max-width: inherit; max-height: inherit;" data-google-query-id="CK7A_9-du4EDFTOZJwIdbBABBQ">
									<div id="google_ads_iframe_22071836792/SSM_rollingstone/homepage_vrec_3_0__container__" style="border: 0pt none; margin: auto; text-align: center; width: 300px; height: 600px;">
										<iframe frameborder="0" src="https://4b331e11edcc2408f79d97a40ba91dd0.safeframe.googlesyndication.com/safeframe/1-0-40/html/container.html" id="google_ads_iframe_22071836792/SSM_rollingstone/homepage_vrec_3_0" title="3rd party ad content" name="" scrolling="no" marginwidth="0" marginheight="0" width="300" height="600" data-is-safeframe="true" sandbox="allow-forms allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation-by-user-activation" allow="attribution-reporting" role="region" aria-label="Advertisement" tabindex="0" data-google-container-id="5" style="border: 0px; vertical-align: bottom;" data-gtm-yt-inspected-10="true" data-gtm-yt-inspected-16="true" data-load-complete="true"></iframe>
									</div>
								</div>
							</div>
							<script>
								fusetag.que.push(function() {
									fusetag.loadSlotById("22378668583");
								});
							</script>
						</div>
						<div class="c-ad c-ad--300x250" style="margin-top: 1rem;"></div>
					</div>
					<!-- /.c-sticky__item -->
				</div>
				<!-- /.l-section__sticky c-sticky c-sticky--size-grow -->
				<div class="l-section__sidebar-footer"></div>
				<!-- /.l-section__sidebar-footer -->
			</div>
			<!-- /.l-section__sidebar -->
		</div>
		<!-- /.l-section__content -->
	</div>
	<!-- /.l-section -->
	<div class="l-section l-section--dark">
		<div class="l-section__header">
			<div class="c-section-header c-section-header--dark">
				<h3 class="c-section-header__heading t-bold t-bold--upper t-bold--condensed"> Video </h3>
				<a href="https://au.rollingstone.com/video/" class="c-section-header__cta t-semibold t-semibold--upper t-semibold--loose"> View All </a>
			</div>
			<!-- .c-section-header -->
		</div>
		<!-- /.l-section__header -->
		<div class="l-section__content">
			<div class="c-video-gallery" data-video-gallery="">
				<div class="c-video-gallery__main">
					<?php
					$args = array(
						//'category_name' => 'culture',
						"posts_per_page" => 1,
						'post_type' => 'pmc_top_video',
						"orderby"        => "date",
						'meta_key' => 'pmc_top_video_source',
						'meta_value' => '',
						'meta_compare' => '!=',
						"order"          => "DESC"
					);
					$videoPosts = get_posts($args);
					foreach ($videoPosts as $post) {
						//echo '<pre>';
						//echo $post->ID;
						$videoUrl = get_post_meta($post->ID, 'pmc_top_video_source', true); //get_post_custom_values('video_url');
						if ($videoUrl != '') {

					?>
							<article class="c-card c-card--video ">
								<div class="c-card__wrap">
									<figure class="c-card__image">

										<a href="<?php the_permalink(); ?>">
											<?php echo get_the_post_thumbnail(); ?>
										</a>
										<div data-video-crop="">
											<div hidden="">
												<iframe type="text/html" width="670" height="407" data-src="<?php echo $videoUrl; ?>" allowfullscreen="true" style="border:0;" data-gtm-yt-inspected-10="true" data-gtm-yt-inspected-16="true"></iframe>
											</div>
										</div>
										<div class="jwplayer_container" hidden="">
											<div id="jwplayer_0_div"></div>
										</div>

										<!-- .c-crop -->
									</figure>
									<!-- .c-card__image -->
									<header class="c-card__header">
										<h3 class="c-card__heading t-bold">
											<a href="<?php the_permalink(); ?>" data-video-gallery-card-heading="">
												<?php the_title(); ?>
											</a>
										</h3>
										<!-- .c-card__heading -->
										<span class="c-card__tag t-semibold t-semibold--upper t-semibold--loose" data-video-gallery-card-tag=""> Music </span>
									</header>


									<!-- .c-card__header -->
								</div>
								<!-- .c-card__wrap -->
							</article>
							<!-- .c-card -->
					<?php }
					} ?>
				</div>
				<!-- /.c-video-gallery__main -->
				<div class="c-video-gallery__slider c-slider " data-slider="" data-slider--centered="">
					<a href="" class="c-slider__nav c-slider__nav--left" data-slider-nav="prev">
						<svg class="c-slider__icon">
							<use xlink:href="#svg-icon-chevron"></use>
						</svg>
					</a>
					<a href="" class="c-slider__nav c-slider__nav--right" data-slider-nav="next">
						<svg class="c-slider__icon">
							<use xlink:href="#svg-icon-chevron"></use>
						</svg>
					</a>
					<div class="c-slider__track" data-slider-track="" style="transform: translateX(-257.5px);">
						<!-- /.c-video-gallery__item c-slider__item -->
						<?php
						$args = array(
							//'category_name' => 'culture',
							"posts_per_page" => 10,
							'post_type' => 'pmc_top_video',
							"orderby"        => "date",
							'meta_key' => 'pmc_top_video_source',
							'meta_value' => '',
							'meta_compare' => '!=',
							"order"          => "DESC"
						);
						$videoPosts = get_posts($args);
						foreach ($videoPosts as $post) {
							//echo '<pre>';
							//echo $post->ID;
							$videoUrl = get_post_meta($post->ID, 'pmc_top_video_source', true); //get_post_custom_values('video_url');
							if ($videoUrl != '') {

						?>
								<div class="c-video-gallery__item c-slider__item" data-slider-item="">
									<article class="c-card c-card--video-thumb  t-semibold">
										<a href="<?php the_permalink(); ?>" class="c-card__wrap">
											<figure class="c-card__image2">
												<!-- data-active-text=""> -->
												<div hidden="">
													<iframe type="text/html" width="670" height="407" data-src="<?php $videoUrl ?>" allowfullscreen="true" allow="autoplay" style="border:0;" data-gtm-yt-inspected-10="true" data-gtm-yt-inspected-16="true"></iframe>
												</div>
												<div class="c-card__badge c-card__badge--play">
													<div class="c-badge c-badge--play">
														<svg class="c-play-btn c-play-btn--medium c-play-btn--thumb" viewBox="0 0 88 88">
															<g transform="translate(-.082 -.082)" fill="none" fill-rule="evenodd">
																<circle class="c-play-btn__fill" fill="#D32531" stroke="#D32531" stroke-width="4" cx="44" cy="44" r="44"></circle>
																<circle class="c-play-btn__border" fill="none" stroke="none" stroke-width="4" cx="44" cy="44" r="44" stroke-dasharray="276" stroke-dashoffset="276"></circle>
																<path class="c-play-btn__icon" d="M38.242 28.835c-.634-.467-2.323-.467-2.46 1.298v19.743a.99.99 0 0 0 1.577.796 3.88 3.88 0 0 0 1.577-3.123V33.105l16.008 10.969-18.458 12.61c-.44.3-.703.798-.703 1.331a1.564 1.564 0 0 0 2.46 1.298l20.383-13.941c.528-.317 1.252-1.615 0-2.596l-20.384-13.94z" fill="#FFF"></path>
															</g>
														</svg>
													</div>
													<!-- .c-badge--play -->
												</div>
												<!-- /.c-card__badge -->

												<?php echo get_the_post_thumbnail(); ?>


												<!-- .c-crop -->
											</figure>
											<!-- .c-card__image -->
											<header class="c-card__header">
												<h3 class="c-card__heading ">
													<?php the_title(); ?>
												</h3>
												<!-- .c-card__heading -->
											</header>
											<!-- .c-card__header -->
										</a>
										<!-- .c-card__wrap -->
									</article>
									<!-- .c-card -->
								</div>
						<?php
							}
						}
						?>

					</div>
					<!-- /.c-slider__track -->
				</div>
				<!-- /.c-video-gallery__slider c-slider -->
			</div>
			<!-- /.c-video-gallery -->
		</div>
		<!-- /.l-section__content -->
	</div>
	<!-- .c-coverage -->
	<div class="l-section" data-section="Movies">
		<script>
			PMC_RS_setHomeAppearance("Movies");
		</script>
		<div class="l-section__header">
			<div class="c-section-header">
				<h3 class="c-section-header__heading t-bold t-bold--condensed"> Movies </h3>
				<a href="https://au.rollingstone.com/movies/" class="c-section-header__cta t-semibold t-semibold--upper t-semibold--loose"> View All </a>
				<p class="c-section-header__msg"> You have set the display of this section to be hidden. <br> Click the button to the right to show it again. </p>
				<!-- /.c-section-header__msg -->
				<a href="#" class="c-section-header__btn" data-section-toggle="">
					<span class="c-section-header__hide t-semibold t-semibold--upper">Hide</span>
					<span class="c-section-header__show t-semibold t-semibold--upper">Show</span>
					<svg class="c-section-header__btn-arrow">
						<use xlink:href="#svg-icon-arrow-down"></use>
					</svg>
				</a>
			</div>
			<!-- .c-section-header -->
		</div>
		<!-- /.l-section__header -->
		<div class="l-section__content">
			<div class="l-section__grid">
				<div class="c-cards-grid">
					<?php
					$args = array(
						'category_name' => 'movies',
						"posts_per_page" => 6,
						"orderby"        => "date",
						"order"          => "DESC"
					);
					$posts = get_posts($args);

					foreach ($posts as $post) {

					?>
						<div class="c-cards-grid__item">
							<article class="c-card c-card--grid c-card--grid--primary ">
								<a href="<?php the_permalink(); ?>" class="c-card__wrap">
									<figure class="c-card__image">

										<?php echo get_the_post_thumbnail(); ?>

										<!-- .c-crop -->
									</figure>
									<!-- .c-card__image -->
									<header class="c-card__header">
										<div class="c-badge c-badge--sponsored"></div>
										<!-- .c-badge--sponsored -->
										<h3 class="c-card__heading t-bold"> <?php the_title(); ?> </h3>
										<!-- .c-card__heading -->
										<div class="c-card__tag t-bold t-bold--upper">
											<span class="screen-reader-text">Posted in:</span>
											<span class="c-card__featured-tag"> Music News</span>
										</div>
										<!-- c-card__tag -->
										<div class="c-card__byline t-copy"> By: <?php echo get_the_author_meta('display_name', $post->post_author); ?> </div>
										<!-- c-card__byline -->
										<p class="c-card__lead"> <?php
																	echo wp_trim_words($post->post_content, 20);
																	//$post->post_content;
																	//echo $content = get_the_content('Read more');
																	//echo apply_filters('the_content', $content);
																	?> </p>
										<!-- c-card__lead -->
									</header>
									<!-- .c-card__header -->
								</a>
								<!-- .c-card__wrap -->
							</article>
							<!-- .c-card--grid -->
						</div>
						<!-- /.c-cards-grid__item --> <?php } ?>
				</div>
				<!-- .c-cards-grid -->
			</div>
			<!-- /.l-section__grid -->
			<div class="l-section__sidebar">
				<div class="l-section__sticky c-sticky c-sticky--size-grow" data-section-ad="Movies">
					<script>
						PMC_RS_toggleHomeAd("Movies");
					</script>
					<div class="c-sticky__item">
						<div class="c-ad c-ad--300x250">
							<!--5 | homepage | vrec_4 | 2-->
							<div data-fuse="22378668592" style="margin: auto;" data-fuse-code="fuse-slot-22378668592-1" data-fuse-zone-instance="zone-instance-22378668592-1" data-fuse-slot="fuse-slot-22378668592-1" data-fuse-processed-at="4964">
								<div id="fuse-slot-22378668592-1" class="fuse-slot" style="max-width: inherit; max-height: inherit;" data-google-query-id="CK_A_9-du4EDFTOZJwIdbBABBQ">
									<div id="google_ads_iframe_22071836792/SSM_rollingstone/homepage_vrec_4_0__container__" style="border: 0pt none; margin: auto; text-align: center; width: 300px; height: 600px;">
										<iframe frameborder="0" src="https://4b331e11edcc2408f79d97a40ba91dd0.safeframe.googlesyndication.com/safeframe/1-0-40/html/container.html" id="google_ads_iframe_22071836792/SSM_rollingstone/homepage_vrec_4_0" title="3rd party ad content" name="" scrolling="no" marginwidth="0" marginheight="0" width="300" height="600" data-is-safeframe="true" sandbox="allow-forms allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation-by-user-activation" allow="attribution-reporting" role="region" aria-label="Advertisement" tabindex="0" data-google-container-id="6" style="border: 0px; vertical-align: bottom;" data-gtm-yt-inspected-10="true" data-gtm-yt-inspected-16="true" data-load-complete="true"></iframe>
									</div>
								</div>
							</div>
							<script>
								fusetag.que.push(function() {
									fusetag.loadSlotById("22378668592");
								});
							</script>
						</div>
						<div class="c-ad c-ad--300x250" style="margin-top: 1rem;"></div>
					</div>
					<!-- /.c-sticky__item -->
				</div>
				<!-- /.l-section__sticky c-sticky c-sticky--size-grow -->
				<div class="l-section__sidebar-footer"></div>
				<!-- /.l-section__sidebar-footer -->
			</div>
			<!-- /.l-section__sidebar -->
		</div>
		<!-- /.l-section__content -->
	</div>
	<!-- /.l-section -->
	<div class="l-section" data-section="TV">
		<script>
			PMC_RS_setHomeAppearance("TV");
		</script>
		<div class="l-section__header">
			<div class="c-section-header">
				<h3 class="c-section-header__heading t-bold t-bold--condensed"> TV </h3>
				<a href="https://au.rollingstone.com/tv/" class="c-section-header__cta t-semibold t-semibold--upper t-semibold--loose"> View All </a>
				<p class="c-section-header__msg"> You have set the display of this section to be hidden. <br> Click the button to the right to show it again. </p>
				<!-- /.c-section-header__msg -->
				<a href="#" class="c-section-header__btn" data-section-toggle="">
					<span class="c-section-header__hide t-semibold t-semibold--upper">Hide</span>
					<span class="c-section-header__show t-semibold t-semibold--upper">Show</span>
					<svg class="c-section-header__btn-arrow">
						<use xlink:href="#svg-icon-arrow-down"></use>
					</svg>
				</a>
			</div>
			<!-- .c-section-header -->
		</div>
		<!-- /.l-section__header -->
		<div class="l-section__content">
			<div class="l-section__grid">
				<div class="c-cards-grid">
					<?php
					$args = array(
						'category_name' => 'tv',
						"posts_per_page" => 6,
						"orderby"        => "date",
						"order"          => "DESC"
					);
					$posts = get_posts($args);

					foreach ($posts as $post) {

					?>
						<div class="c-cards-grid__item">
							<article class="c-card c-card--grid c-card--grid--primary ">
								<a href="<?php the_permalink(); ?>" class="c-card__wrap">
									<figure class="c-card__image">

										<?php echo get_the_post_thumbnail(); ?>

										<!-- .c-crop -->
									</figure>
									<!-- .c-card__image -->
									<header class="c-card__header">
										<div class="c-badge c-badge--sponsored"></div>
										<!-- .c-badge--sponsored -->
										<h3 class="c-card__heading t-bold"> <?php the_title(); ?> </h3>
										<!-- .c-card__heading -->
										<div class="c-card__tag t-bold t-bold--upper">
											<span class="screen-reader-text">Posted in:</span>
											<span class="c-card__featured-tag"> Music News</span>
										</div>
										<!-- c-card__tag -->
										<div class="c-card__byline t-copy"> By: <?php echo get_the_author_meta('display_name', $post->post_author); ?> </div>
										<!-- c-card__byline -->
										<p class="c-card__lead">
											<?php
											echo wp_trim_words($post->post_content, 20);

											?>
										</p>
										<!-- c-card__lead -->
									</header>
									<!-- .c-card__header -->
								</a>
								<!-- .c-card__wrap -->
							</article>
							<!-- .c-card--grid -->
						</div>
						<!-- /.c-cards-grid__item --> <?php } ?>
				</div>
				<!-- .c-cards-grid -->
			</div>
			<!-- /.l-section__grid -->
			<div class="l-section__sidebar">
				<div class="l-section__sticky c-sticky c-sticky--size-grow" data-section-ad="TV">
					<script>
						PMC_RS_toggleHomeAd("TV");
					</script>
					<div class="c-sticky__item">
						<div class="c-ad c-ad--300x250">
							<!--5 | homepage | vrec_5 | 2-->
							<div data-fuse="22378668595" style="margin: auto;" data-fuse-code="fuse-slot-22378668595-1" data-fuse-zone-instance="zone-instance-22378668595-1" data-fuse-slot="fuse-slot-22378668595-1" data-fuse-processed-at="4968">
								<div id="fuse-slot-22378668595-1" class="fuse-slot" style="max-width: inherit; max-height: inherit;" data-google-query-id="CLDA_9-du4EDFTOZJwIdbBABBQ">
									<div id="google_ads_iframe_22071836792/SSM_rollingstone/homepage_vrec_5_0__container__" style="border: 0pt none; margin: auto; text-align: center; width: 300px; height: 600px;">
										<iframe frameborder="0" src="https://4b331e11edcc2408f79d97a40ba91dd0.safeframe.googlesyndication.com/safeframe/1-0-40/html/container.html" id="google_ads_iframe_22071836792/SSM_rollingstone/homepage_vrec_5_0" title="3rd party ad content" name="" scrolling="no" marginwidth="0" marginheight="0" width="300" height="600" data-is-safeframe="true" sandbox="allow-forms allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts allow-top-navigation-by-user-activation" allow="attribution-reporting" role="region" aria-label="Advertisement" tabindex="0" data-google-container-id="7" style="border: 0px; vertical-align: bottom;" data-gtm-yt-inspected-10="true" data-gtm-yt-inspected-16="true" data-load-complete="true"></iframe>
									</div>
								</div>
							</div>
							<script>
								fusetag.que.push(function() {
									fusetag.loadSlotById("22378668595");
								});
							</script>
						</div>
						<div class="c-ad c-ad--300x250" style="margin-top: 1rem;"></div>
					</div>
					<!-- /.c-sticky__item -->
				</div>
				<!-- /.l-section__sticky c-sticky c-sticky--size-grow -->
				<div class="l-section__sidebar-footer"></div>
				<!-- /.l-section__sidebar-footer -->
			</div>
			<!-- /.l-section__sidebar -->
		</div>
		<!-- /.l-section__content -->
	</div>
	<!-- /.l-section -->

	<!-- .l-footer -->
</div>
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
							<a href="/subscribe-magazine/" class="c-page-nav__link t-semibold " title="SUBSCRIBE NOW" target="_blank">SUBSCRIBE NOW</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="/subscribe-magazine/?gift=1" class="c-page-nav__link t-semibold " title="GIVE A GIFT" target="_blank">GIVE A GIFT</a>
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
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://thebrag.media/" class="c-page-nav__link t-semibold " title="Advertise" target="_blank">Advertise</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://thebrag.media/privacy-policy/" class="c-page-nav__link t-semibold " title="Privacy Policy" target="_blank">Privacy Policy</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://thebrag.media/terms-and-conditions/" class="c-page-nav__link t-semibold " title="Competition Ts &amp; Cs" target="_blank">Competition Ts &amp; Cs</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://thebrag.com/media/terms-of-use/" class="c-page-nav__link t-semibold " title="Terms of use" target="_blank">Terms of use</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://thebrag.com/media/editorial-code/" class="c-page-nav__link t-semibold " title="Editorial Code">Editorial Code</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="mailto:hello@thebrag.media" class="c-page-nav__link t-semibold " title="Contact us">Contact us</a>
						</li>
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
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://au.rollingstone.com/music/" class="c-page-nav__link t-semibold " title="Music">Music</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://au.rollingstone.com/tv/" class="c-page-nav__link t-semibold " title="TV">TV</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://au.rollingstone.com/movies/" class="c-page-nav__link t-semibold " title="Movies">Movies</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://au.rollingstone.com/politics/" class="c-page-nav__link t-semibold " title="Politics">Politics</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://au.rollingstone.com/culture/" class="c-page-nav__link t-semibold " title="Culture">Culture</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://au.rollingstone.com/sports/" class="c-page-nav__link t-semibold " title="Sports">Sports</a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://au.rollingstone.com/rolling-stone-awards-2022/" class="c-page-nav__link t-semibold " title="RS Awards 22">RS Awards 22</a>
						</li>
					</ul>
					<!-- .c-page-nav__list -->
				</div>
				<!-- .c-page-nav--footer -->
				<br>
				<div class="c-page-nav c-page-nav--footer c-page-nav--1-column" data-dropdown="">
					<ul class="c-page-nav__list">
						<li class="c-page-nav__item c-page-nav__item--heading is-active" data-ripple="inverted">
							<span class="c-page-nav__link t-bold">Connect With Us</span>
						</li>
						<!-- .c-page-nav__item -->
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://www.facebook.com/rollingstoneaustralia/" class="c-page-nav__link t-semibold " title="Facebook" target="_blank">
								<span class="c-page-nav__icon c-icon c-icon--inline c-icon--inverted c-icon--round">
									<svg>
										<use xlink:href="#svg-icon-facebook"></use>
									</svg>
								</span>Facebook </a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://twitter.com/rollingstoneaus" class="c-page-nav__link t-semibold " title="Twitter" target="_blank">
								<span class="c-page-nav__icon c-icon c-icon--inline c-icon--inverted c-icon--round">
									<svg>
										<use xlink:href="#svg-icon-twitter"></use>
									</svg>
								</span>Twitter </a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://instagram.com/rollingstoneaus" class="c-page-nav__link t-semibold " title="Instagram" target="_blank">
								<span class="c-page-nav__icon c-icon c-icon--inline c-icon--inverted c-icon--round">
									<svg>
										<use xlink:href="#svg-icon-instagram"></use>
									</svg>
								</span>Instagram </a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://www.youtube.com/channel/UC5ogXwEsy_q8_2DQHp1RU8A" class="c-page-nav__link t-semibold " title="YouTube" target="_blank">
								<span class="c-page-nav__icon c-icon c-icon--inline c-icon--inverted c-icon--round">
									<svg>
										<use xlink:href="#svg-icon-youtube"></use>
									</svg>
								</span>YouTube </a>
						</li>
						<li class="c-page-nav__item" data-ripple="inverted">
							<a href="https://thebrag.com/observer/" class="c-page-nav__link t-semibold " title="Subscribe to our Newsletters" target="_blank">
								<span class="c-page-nav__icon c-icon c-icon--inline c-icon--inverted c-icon--round">
									<svg>
										<use xlink:href="#svg-icon-email"></use>
									</svg>
								</span>Subscribe to our Newsletters </a>
						</li>
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
<?php
get_footer();
