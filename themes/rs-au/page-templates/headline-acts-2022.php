<?php

/**
 * Template Name: HeadlineActs (2022)
 */

if (isset($_POST['age_year'])) {
	define('AGE_ALLOWED', 18);
	$currentYear = date('Y');
	$birthYear =
		isset($_POST['dob_year_ns']) ? absint($_POST['dob_year_ns']) : (isset($_POST['age_year']) ? absint($_POST['age_year']) : 0);

	$isAmbiguousValue = $currentYear - $birthYear === AGE_ALLOWED;

	if ($isAmbiguousValue) {
		$birthMonth =
			isset($_POST['dob_month_ns']) ? absint($_POST['dob_month_ns']) : (isset($_POST['dob_month']) ? absint($_POST['dob_month']) : 0);

		$birthDay =
			isset($_POST['dob_day_ns']) ? absint($_POST['dob_day_ns']) : (isset($_POST['dob_day']) ? absint($_POST['dob_day']) : 0);
		if ($birthYear > 0 && $birthMonth > 0 && $birthDay > 0) {
			$birthDate = new DateTime($birthYear . '-' . $birthMonth . '-' . $birthDay);
			$now = new DateTime();
			$interval = $now->diff($birthDate);
			if ($interval->y >= 18) {
				proceed(isset($_POST['rememberme']));
			}
		}
	}

	$hasLegalAgeValue = $currentYear - $birthYear >= AGE_ALLOWED;
	if ($hasLegalAgeValue) {
		proceed(isset($_POST['rememberme']));
	}
}

function proceed($rememberme = false, $url = '')
{
	if ('' == $url) {
		$url = get_the_permalink();
	}
	if ($rememberme) {
		setcookie(
			'tbm-headlineact-over-18',
			true,
			time() + 31556926 // For a year
		);
	} else {
		if (!session_id()) {
			session_start();
		}
		$_SESSION['tbm-headlineact-over-18'] = true;
	}
	wp_redirect($url);
	exit;
}

$is_over_18 = isset($_COOKIE['tbm-headlineact-over-18']) || isset($_SESSION['tbm-headlineact-over-18']);

get_header('headline-acts-2022');
?>

<?php
if (have_posts()) :
	while (have_posts()) :
		the_post();

		if (!post_password_required($post)) :
?>

			<?php if ($is_over_18) : ?>
				<div class="video-wrap d-flex text-center" id="video-1" style="display: none;">
					<div style="width: 560px; max-width: 100%">
						<button class="btn-close-video" data-target="video-1">
							<img src="<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/btn-close.svg">
						</button>
					</div>
				</div>
				<div id="page-overlay" style="display: none"></div>
			<?php endif; ?>

			<div class="l-page" id="site_wrap" style="width: 100%; max-width: 100%; overflow-x: hidden">
				<?php if (!$is_over_18) : ?>
					<div class="l-page__header">
						<header>
							<?php get_template_part('template-parts/header/nav-network'); ?>
							<div class="l-header__wrap tbm">
								<div class="content d-flex flex-column">
									<div class="l-header__content content-wrap">
										<div class="font-primary" style="font-size: 1.25rem; letter-spacing: 2px;"><strong>HEADLINE ACTS &amp; ROLLING STONE AUSTRALIA</strong></div>
										<div class="text-gold" style="margin-top: .25rem; font-family: Graphik-Medium, sans-serif; letter-spacing: 1px;">PRESENT</div>
										<h1 class="l-header__branding">
											<img src="<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/logo.png" class="feature-image" style="width: 300px; max-width: 100%;">
										</h1>
										<div class="content-wrap">
											<h1 class="font-secondary mb-3" style="line-height: 100%;">Welcome to Headline Acts</h1>
											<div style="width: 650px; margin: auto; max-width: 100%; font-family: Graphik-Medium, sans-serif;">
												<p>You need to be of legal drinking age to enter this site. By entering the site you confirm that you are over the age of 18.</p>
											</div>
										</div>
										<div class="age-gate content-wrap" style="font-family: Graphik-Medium, sans-serif;">
											<div id="agegate">
												<form name="agegate" method="post">
													<div class="mt-3 d-flex">
														<noscript>
															<div class="d-flex align-items-stretch">
																<input type="number" name="dob_day_ns" placeholder="DD" min="1" max="31" class="age-gate__input" required />
																<input type="number" name="dob_month_ns" placeholder="MM" min="1" max="12" class="age-gate__input" required />
																<input type="number" name="dob_year_ns" placeholder="YYYY" min="1900" class="age-gate__input" required />
																<button type="submit" class="js-step2-submit">OK</button>
															</div>
														</noscript>

														<fieldset class="agegate_digit-wrapper js-agegate_digit-wrapper js-agegate_step1 clear-after">
															<input id="age-gate-year" type="tel" name="age_year" placeholder="YYYY" maxlength="4" onclick="this.select();" class="age-gate__input">
														</fieldset>

														<fieldset class="agegate_step2 js-agegate_step2 clear-after">
															<div class="d-flex align-items-stretch">
																<input type="tel" data-value="01" name="dob_day" placeholder="DD" min="1" max="31" maxlength="2" class="age-gate__input">
																<input type="tel" data-value="01" name="dob_month" placeholder="MM" min="1" max="12" maxlength="2" class="age-gate__input">
																<input type="tel" data-value="" name="dob_year" placeholder="YYYY" min="1900" maxlength="4" class="age-gate__input">
																<button type="submit" class="js-step2-submit" name="confirm-over-18"><span>OK</span></button>
															</div>
														</fieldset>
													</div>

													<p class=" agegate_validation" data-underage="You must be 18+ to enter." data-invalid="You must be 18+ to enter. Please enter your year of birth" data-invalid-birth-date="You must be 18+ to enter. Please enter your date of birth" style="display: none;"></p>

													<div class="mt-3">
														<label for="age-gate-rememberme" class="age-gate-rememberme-wrapper"><input type="checkbox" name="rememberme" id="age-gate-rememberme" checked="checked"><label for="age-gate-rememberme"></label>
															<p>Remember me</p>
														</label>
													</div>

												</form>
											</div>
										</div>
										<div class="mt-4 pt-4"><img src="<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/drink-smart.png" style="width: 120px;"></div>
										<!-- .l-header__branding -->
									</div><!-- .l-header__content -->
								</div>
							</div>
							<!--. l-header__wrap -->

						</header><!-- .l-header -->
					</div>
				<?php else : ?>
					<div class="l-page__header">
						<header>
							<?php get_template_part('template-parts/header/nav-network'); ?>
						</header>
					</div>
					<div id="content-wrap2">
						<section class="">
							<div class="intro d-flex flex-column justify-content-between align-items-stretch">
								<div class="d-flex justify-content-between align-items-stretch w-100">
									<div class="header-logo">
										<img src="<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/logo.png">
									</div>
									<div class="header-menu">
										<ul>
											<li><a href="#about">About</a></li>
											<li><a href="#news">News</a></li>
											<li><a href="#features">Features</a></li>
										</ul>
									</div>
								</div>
								<div>
									<div class="content-wrap header-rs-logo-wrap">
										<div class="content d-flex flex-column" style="width: 100%; font-family: Graphik-Medium, sans-serif;">
											<div class="my-2" style="font-size: .75rem;"><strong>BROUGHT TO YOU BY</strong></div>
											<a href="/" target="_blank"><img class="l-header__logo" src="<?php echo RS_THEME_URL . '/assets/src/images/_dev/RS-AU_LOGO-RED.png'; ?>"></a>
										</div>
									</div>
									<div class="header-drink-smart-wrap">
										<div class="px-2"><img src="<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/drink-smart.png" class="header-drink-smart" style=" width: 120px;"></div>
									</div>
								</div>
							</div>
						</section><!-- .intro -->

						<section id="about" class="about d-flex flex-column py-3 py-md-4 px-3 my-4">
							<h2 class="text-center mb-3 heading">About</h2>
							<div class="content-wrap d-flex flex-column">
								<p style="font-family: 'Recoleta',sans-serif;">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eros metus, placerat in pulvinar at, sodales ac leo. Aliquam lobortis lacus sapien, sed varius lectus tristique eu. Cras iaculis tincidunt consectetur.
								</p>
							</div>
						</section>

						<section id="news" class="latest-news d-flex flex-column py-3 py-md-4 px-3 mt-4">
							<h2 class="text-center mb-3 heading">News</h2>
							<div class="content-wrap">
								<div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch my-3">
									<div class="col-12 col-md-6 d-flex align-items-stretch">
										<div class="mb-2 mr-0 mr-md-2 d-flex align-items-stretch">
											<a href="https://au.rollingstone.com/music/music-features/the-headline-worthy-acts-we-found-on-the-muso-40789/" target="_blank" class="news">
												<img src="https://app.thebrag.com/img-socl/?url=https://cdn-r2-1.thebrag.com/rs/uploads/2022/06/muso-header.jpg&nologo=1">
												<h4>The Headline Worthy Acts We Found On Muso</h4>
												<p>We scrolled through pages of artists on Muso and found some of Australia's best up and coming independent artists.</p>
											</a>
										</div>
									</div>
									<div class="col-12 col-md-6 d-flex align-items-stretch">
										<div class="mb-2 ml-0 ml-md-1 d-flex align-items-stretch">
											<a href="https://au.rollingstone.com/culture/culture-news/headline-acts-40672/" target="_blank" class="news">
												<img src="https://app.thebrag.com/img-socl/?url=https://cdn-r2-1.thebrag.com/rs/uploads/2022/05/headline-acts-hero.jpg&nologo=1">
												<h4>Meet the Music Infused Wine That's Backing Local Gigs</h4>
												<p>Headline Acts, a new wine brand with music in its vines, is set to become an integral part of Australia's live music landscape.</p>
											</a>
										</div>
									</div>
								</div>
							</div>
						</section>

						<section id="features" class="features latest-news d-flex flex-column py-3 py-md-4 px-3 mt-4">
							<h2 class="text-center mb-3 heading">Features</h2>
							<div class="content-wrap">
								<div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch my-3">
									<div class="col-12 col-md-4">
										<div class="mb-2 mr-0 mr-md-2">
											<a href="#" target="_blank" class="news">
												<img src="https://placehold.co/1200x630/2A273D/2A273D/png">
												<h4>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ultrices enim vitae lorem tempor egestas.</h4>
											</a>
										</div>
									</div>
									<div class="col-12 col-md-4">
										<div class="mb-2 mx-0 mx-md-1">
											<a href="#" target="_blank" class="news">
												<img src="https://placehold.co/1200x630/2A273D/2A273D/png">
												<h4>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ultrices enim vitae lorem tempor egestas.</h4>
											</a>
										</div>
									</div>

									<div class="col-12 col-md-4">
										<div class="mb-2 ml-0 ml-md-2">
											<a href="#" target="_blank" class="news">
												<img src="https://placehold.co/1200x630/2A273D/2A273D/png">
												<h4>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ultrices enim vitae lorem tempor egestas.</h4>
											</a>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div><!-- #content-wrap -->
				<?php
				endif; // Over 18 check
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

					if ($('#age-gate-year').length)
						$('#age-gate-year').focus();

					$('.btn-watch-now').on('click', function() {
						var target = $(this).data('target');
						if (!target) return;
						var targetElem = $('#' + target);
						if (!targetElem.length) return;
						$('#page-overlay').show();
						targetElem.show();
					})

					// $('#btn-watch-now-1').trigger('click');

					$('.btn-close-video').on('click', function() {
						var target = $(this).data('target');
						if (!target) return;
						var targetElem = $('#' + target);
						if (!targetElem.length) return;
						targetElem.find('iframe').each(function(i, e2) {
							this.src = this.src;
						});
						$('#page-overlay').hide();
						targetElem.hide();
					})
					$('#page-overlay').on('click', function() {
						$(this).hide();
						$('.video-wrap').hide();
						$('.video-wrap').each(function(i, e) {
							$(e).find('iframe').each(function(i, e2) {
								this.src = this.src;
							});
						});
					})
				})
			</script>
			<?php
			get_footer();
