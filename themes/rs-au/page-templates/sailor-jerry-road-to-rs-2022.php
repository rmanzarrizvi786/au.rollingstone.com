<?php

/**
 * Template Name: Sailor Jerry Road to Rolling Stone (2022)
 */

define('ICONS_URL', get_template_directory_uri() . '/images/');

$award_categories = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rsawards_categories_2022 WHERE status = '1'");

$action = isset($_GET['a']) && in_array(trim($_GET['a']), ['add', 'edit', 'ty']) ? trim($_GET['a']) : NULL;
$success = isset($_GET['success']) ? trim($_GET['success']) : NULL;

date_default_timezone_set('Australia/NSW');
$noms_open_at = '2021-10-25 12:00:00';
$noms_open = true; // isset($_GET['no']) && '7Odori' == $_GET['no'] ? true : time() >= strtotime($noms_open_at);
$noms_closed = false;

$errors = [];

$formdata = [];

if (is_user_logged_in()) :
	require_once get_template_directory() . '/page-templates/rs-awards/2022/submit.php';
	$current_user = wp_get_current_user();

	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-ui-datepicker-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/dark-hive/jquery-ui.min.css');

	wp_enqueue_script('rs-awards', get_template_directory_uri() . '/page-templates/rs-awards/js/scripts-2022.js', array('jquery'), time(), true);
endif; // If logged in

get_header('sj-road-to-rs');
?>

<?php
if (have_posts()) :
	while (have_posts()) :
		the_post();

		if (!post_password_required($post)) :
?>
			<div id="content-wrap">

				<!-- <div class="container rsa-header" style="background-color: #fff;">
          <img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/header-red.jpg">
        </div> -->

				<div class="subheading">
					FREE LIVE GIG SERIES
				</div>
				<section>
					<div class="para-1" style="margin: 4rem auto 2rem;">
						<p>A newly announced free live event series will spearhead the return of gigs across Australia in the lead up to the forthcoming Sailor Jerry Rolling Stone Australia Awards</p>
						<div style="margin-top: 3rem;">
							<p style="font-size: .9rem;">
								Choose your city<br>
								and attend for FREE
							</p>
						</div>
					</div>
					<div class="row gigs mx-2">

						<div class="col-12 col-md-4">
							<a href="https://www.facebook.com/events/362378492113777/?acontext=%7B%22event_action_history%22%3A[%7B%22surface%22%3A%22page%22%7D]%7D" target="_blank" style="display: block; color: inherit;">
								<div class="gig-item mb-2">
									<img src="<?php echo get_template_directory_uri(); ?>/images/sj-road-to-rs/RoadToAwardsLayers_TheGrogans.jpg">
									<div class="gig-info">
										<div class="city">Melbourne</div>
										<div>March 16</div>
									</div>
									<div class="artists">
										The Grogans
									</div>
								</div>
							</a>
						</div>

						<div class="col-12 col-md-4">
							<a href="https://www.facebook.com/events/369494454627939?acontext=%7B%22event_action_history%22%3A[%7B%22surface%22%3A%22page%22%7D%2C%7B%22mechanism%22%3A%22surface%22%2C%22surface%22%3A%22edit_dialog%22%7D]%2C%22ref_notif_type%22%3Anull%7D" target="_blank" style="display: block; color: inherit;">
								<div class="gig-item mb-2">
									<img src="<?php echo get_template_directory_uri(); ?>/images/sj-road-to-rs/RoadToAwardsLayers_SydneyTheTerrys.jpg">
									<div class="gig-info">
										<div class="city">Sydney</div>
										<div>March 17</div>
									</div>
									<div class="artists">
										The Terrys
									</div>
								</div>
							</a>
						</div>

						<div class="col-12 col-md-4">
							<a href="https://www.facebook.com/events/505863351156672/?acontext=%7B%22event_action_history%22%3A[%7B%22surface%22%3A%22page%22%7D]%7D" target="_blank" style="display: block; color: inherit;">
								<div class="gig-item mb-2">
									<img src="<?php echo get_template_directory_uri(); ?>/images/sj-road-to-rs/RoadToAwardsLayers_brisbane.jpg">
									<div class="gig-info">
										<div class="city">Brisbane</div>
										<div>March 24</div>
									</div>
									<div class="artists">
										Eliza and the Delusionals
									</div>
								</div>
							</a>
						</div>
					</div>
				</section>

				<div class="subheading">
					NEWS
				</div>
				<section class=" p-3 d-flex c-content">
					<div class="col-12">
						<div class="d-flex flex-column flex-md-row align-items-stretch">
							<!-- <h2 style="margin-top: 2rem; text-align: center; color: #fff;">NEWS</h2> -->
							<div class="d-flex flex-wrap align-items-stretch news-pieces">
								<?php
								$article_urls = [
									// 'https://au.rollingstone.com/music/music-news/vote-in-rolling-stone-australia-readers-award-2022-36149/',
									// 'https://au.rollingstone.com/music/music-features/sailor-jerry-road-to-rolling-stone-concerts-lineup-36874/',
									// 'https://theindustryobserver.thebrag.com/sailor-jerry-rolling-stone-australia-awards-labels-leaderboard/',
									// 'https://theindustryobserver.thebrag.com/rolling-stone-australia-awards-2022-nominees/',
									// 'https://tonedeaf.thebrag.com/rolling-stone-awards-nominees-2022/',
									// 'https://au.rollingstone.com/music/music-news/nominations-rolling-stone-readers-award-34267/',
									// 'https://theindustryobserver.thebrag.com/nominations-2022-rolling-stone-australia-awards/',
									'https://au.rollingstone.com/music/music-features/sailor-jerry-road-to-rolling-stone-concerts-lineup-36874/',
									'https://au.rollingstone.com/music/music-features/eliza-the-delusionals-37264/',
									'https://tonedeaf.thebrag.com/eliza-the-delusionals-free-gig-brisbane/',
									'https://au.rollingstone.com/music/music-features/the-grogans-sailor-jerry-road-to-rolling-stone-awards-38722/',
									'https://au.rollingstone.com/music/music-news/the-terrys-celebrate-the-countrys-return-to-live-music-in-epic-sailor-jerry-road-to-rolling-stone-awards-gig-38771/',
								];
								foreach ($article_urls as $url) {
									$sites_html = file_get_contents($url);
									$html = new DOMDocument();
									@$html->loadHTML($sites_html);
									$meta_og_title = $meta_og_img = $meta_og_description = null;
									foreach ($html->getElementsByTagName('meta') as $meta) {
										if ($meta->getAttribute('property') == 'og:image') {
											if (!$meta_og_img) {
												$meta_og_img = $meta->getAttribute('content');
												$meta_og_img = str_ireplace('/img-socl/?url=', '', substr($meta_og_img, strpos($meta_og_img, '/img-socl/?url=')));
											}
										} elseif ($meta->getAttribute('property') == 'og:title') {
											if (!$meta_og_title) {
												$meta_og_title = $meta->getAttribute('content');
											}
										}
									}
									if ($meta_og_img) {
								?>
										<div class="col-12 col-md-4 news-piece">
											<a href="<?php echo $url; ?>" target="_blank">
												<div class="img-wrap">
													<img src="<?php echo $meta_og_img; ?>">
												</div>
												<?php if ($meta_og_title) { ?>
													<h3 class="link-text"><?php echo $meta_og_title; ?></h3>
												<?php } ?>
											</a>
										</div>
								<?php
									}
								}
								?>
							</div>
						</div>
					</div>
				</section>

				<div class="subheading">
					Galleries
				</div>
				<section class=" p-3 d-flex c-content">
					<div class="col-12">
						<div class="d-flex flex-column flex-md-row align-items-stretch">
							<!-- <h2 style="margin-top: 2rem; text-align: center; color: #fff;">NEWS</h2> -->
							<div class="d-flex flex-wrap align-items-stretch news-pieces">
								<?php
								$article_urls = [
									'https://au.rollingstone.com/music/music-pictures/sailor-jerry-road-to-rolling-stone-awards-the-terrys-at-marys-underground-38516/',
									'https://au.rollingstone.com/music/music-pictures/gallery-sailor-jerry-road-to-rolling-stone-awards-the-grogans-38836/',
								];
								foreach ($article_urls as $url) {
									$sites_html = file_get_contents($url);
									$html = new DOMDocument();
									@$html->loadHTML($sites_html);
									$meta_og_title = $meta_og_img = $meta_og_description = null;
									foreach ($html->getElementsByTagName('meta') as $meta) {
										if ($meta->getAttribute('property') == 'og:image') {
											if (!$meta_og_img) {
												$meta_og_img = $meta->getAttribute('content');
												$meta_og_img = str_ireplace('/img-socl/?url=', '', substr($meta_og_img, strpos($meta_og_img, '/img-socl/?url=')));
											}
										} elseif ($meta->getAttribute('property') == 'og:title') {
											if (!$meta_og_title) {
												$meta_og_title = $meta->getAttribute('content');
											}
										}
									}
									if ($meta_og_img) {
								?>
										<div class="col-12 col-md-4 news-piece">
											<a href="<?php echo $url; ?>" target="_blank">
												<div class="img-wrap">
													<img src="<?php echo $meta_og_img; ?>">
												</div>
												<?php if ($meta_og_title) { ?>
													<h3 class="link-text"><?php echo $meta_og_title; ?></h3>
												<?php } ?>
											</a>
										</div>
								<?php
									}
								}
								?>
							</div>
						</div>
					</div>
				</section>

				<div class="subheading"></div>
				<?php if (0) : // hidden temporarily, 14 Feb 2022 
				?>
					<section class="bg-semi-dark">
						<div class="d-flex artist-images">
							<?php for ($i = 0; $i < 12; $i++) { ?>
								<div class="col-4 col-md-3 artist-img-wrap">
									<img src="https://picsum.photos/400/400?random=<?php echo $i; ?>">
								</div>
							<?php } ?>
						</div>
					</section>
				<?php endif; ?>

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
