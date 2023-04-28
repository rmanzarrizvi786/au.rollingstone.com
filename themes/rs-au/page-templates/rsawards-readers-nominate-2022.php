<?php

/**
 * Template Name: RS Awards Readers Nomimate (2022)
 */

define('ICONS_URL', get_template_directory_uri() . '/images/');

$award_categories = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rsawards_categories_2022 WHERE status = '1'");

$action = isset($_GET['a']) && in_array(trim($_GET['a']), ['add', 'edit', 'ty']) ? trim($_GET['a']) : NULL;
$success = isset($_GET['success']) ? trim($_GET['success']) : NULL;

date_default_timezone_set('Australia/NSW');
$noms_open_at = '2021-10-25 12:00:00';
$noms_open = true; // isset($_GET['no']) && '7Odori' == $_GET['no'] ? true : time() >= strtotime($noms_open_at);
$noms_closed = true; // false;

$errors = [];

$formdata = [];

if (is_user_logged_in()) :
	$current_user = wp_get_current_user();
	require_once get_template_directory() . '/page-templates/rs-awards/2022/submit.php';

	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-ui-datepicker-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/dark-hive/jquery-ui.min.css');

	wp_enqueue_script('rs-awards', get_template_directory_uri() . '/page-templates/rs-awards/js/scripts-2022.js', array('jquery'), time(), true);
endif; // If logged in

get_header('rsawards');
?>

<style>
	.card {
		position: relative;
		display: -ms-flexbox;
		display: flex;
		-ms-flex-direction: column;
		flex-direction: column;
		min-width: 0;
		word-wrap: break-word;
		background-color: #fff;
		background-clip: border-box;
		border: none;
		border-radius: .25rem;
		font-size: 0.9rem;
	}

	.bg-dark {
		background-color: #343a40 !important;
	}

	.card-body {
		-ms-flex: 1 1 auto;
		flex: 1 1 auto;
		padding: 0 1.25rem 1.25rem;
	}

	.text-white {
		color: #fff !important;
		font-family: Roboto, sans-serif;
	}

	.mb-3,
	.my-3 {
		margin-bottom: 1rem !important;
	}

	.row {
		display: -ms-flexbox;
		display: flex;
		-ms-flex-wrap: wrap;
		flex-wrap: wrap;
		margin-right: -15px;
		margin-left: -15px;
		font-family: Roboto, sans-serif;
	}

	.form-control {
		display: block;
		width: 100%;
		height: calc(2.25rem + 2px);
		padding: .375rem .75rem;
		font-size: 1rem;
		font-weight: 400;
		font-family: Roboto, sans-serif;
		line-height: 1.5;
		color: #495057;
		background-color: #fff;
		background-clip: padding-box;
		border: 1px solid #ced4da;
		border-radius: .25rem;
		transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
	}

	textarea.form-control {
		height: auto;
		/* font-size: 16px; */
		font-family: Roboto, sans-serif;
	}

	.awardnoms button {
		background: #d32531;
		padding: .35rem .75rem;
		text-align: center;
		color: #fff;
		border-radius: 5px;
		min-width: 20%;
		display: block;
		margin: 0 auto;
		font-family: Roboto, sans-serif;
	}

	@media (min-width: 768px) {
		/* .col-md-4 {
			-ms-flex: 0 0 50%;
			flex: 0 0 50%;
			max-width: 50%;
		} */
	}

	#thank-you-popup {
		position: fixed;
		right: 0;
		bottom: 0;
		left: 0;
		/* background: rgba(255,255,255,.5); */
		z-index: 30000;
		display: none;
		transition: .25 all linear;
		color: #333;
		box-shadow: 0 0 10px #000;
		text-align: center;
		background: #fff;
	}

	#thank-you-popup.success {
		background: #28a745 !important;
		color: #fff !important;
		padding: 2rem 1rem;
	}

	#thank-you-popup.danger {
		background: #dc3545 !important;
		color: #fff !important;
		padding: 2rem 1rem;
	}

	.t-c-wrap {
		font-size: 12px
	}

	.t-c-wrap table {
		border: none;
	}

	.t-c-wrap table tr,
	.t-c-wrap table tr th,
	.t-c-wrap table tr td {
		/* border: none; */
		vertical-align: top;
	}

	.t-c-wrap table tr th,
	.t-c-wrap table tr td {
		padding-left: 5px;
		padding-right: 5px;
	}

	p {
		margin-bottom: 1rem;
	}
</style>

<?php
if (have_posts()) :
	while (have_posts()) :
		the_post();

		if (!post_password_required($post)) :
?>
			<div id="content-wrap">

				<div class="container rsa-header" style="background-color: #fff;">
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/header-red.jpg">
				</div>

				<section class="bg-white p-3 d-flex" style="background-color: #fff; padding: 1.5rem;">
					<div class="col-12">

						<?php
						if (0) :
						?>
							<div style="margin: 1rem auto;">
								<!-- <div class="col-md-4" style="text-align: center;"><?php rollingstone_the_issue_cover(250); ?></div> -->
								<div>
									<h3 style="text-align: center;">Thanks for all your responses! Nominations have now closed.</h3>
								</div>
							</div>
							<?php
						else :
							if (is_user_logged_in()) :
								$current_user = wp_get_current_user();
							?>

								<?php if (isset($errors) && count($errors) > 0) : ?>
									<div class="alert alert-danger" id="errors">
										<?php foreach ($errors as $error) : ?>
											<div><?php echo $error; ?></div>
										<?php endforeach; // For Each $error 
										?>
									</div>
								<?php endif; // If there are errors set 
								?>

								<div id="thank-you-popup">
									<div></div>
								</div>

								<?php
								$magSub = new TBMMagSub();
								$subscriptions = $magSub->get_subscriptions();
								if (current_user_can('administrator')  || $subscriptions) : // If subscriptions
								?>
									<div style="font-family: 'Roboto', sans-serif;">
										<h2 style="text-align: center; margin: 1rem auto 2rem auto; color: #000; font-family: Poppins, sans-serif; font-size: 32px;">Nominate Your Favourite Artist</h2>

										<p style="font-style: italic; text-align: center; color: #000;">Nominate now and go in the running to win exclusive tickets to the Rolling Stone Awards for you and 3 friends!</p>

										<p>As a Rolling Stone Australia subscriber, you are part of an elite club that pays for music journalism. This means music really matters to you, and it is for that reason you are a vital part of the 2022 Sailor Jerry Rolling Stone Australia Awards.</p>
										<p>Because of your contribution to journalism, and as clear purveyors of music in all its forms, you have been entrusted with a responsibility to determine an award winner.</p>
										<p>From today until December 17, Rolling Stone Australia subscribers can cast their nomination for their favourite Australian artist.</p>
										<p>From there, the most nominated artists will progress to a final round where subscribers will vote for the final winner. This second round will take place in January 2022.</p>
										<p>This award is judged solely by our readers, the subscribers who are the life blood of music journalism. As such if you would like to nominate in the first round or vote in the second, make sure that you're subscribed to Rolling Stone Australia Magazine.</p>
										<p>Any Australian artist who has released a song, album or EP within the eligibility period of 1st November 2020 - 15th October 2021 is eligible, and can be nominated below.</p>
										<p>You will decide the winner of the prestigious and most coveted Rolling Stone Australia Readers Award.</p>
									</div>
									<div class="flex-wrap">
										<?php
										$rs_nominated = get_user_meta($current_user->ID, 'rsawards_nominated_2022', true);

										if (!$noms_closed) :
										?>
											<div id="awardnoms-head" class="card-header p-0" id="awardnoms-heading1" style="border-radius: 0 !important;">
												<div class="bg-dark d-flex align-items-center" style="padding: 1.25em 1.25em 0;">
													<h4 style="color: #fff; font-family: Roboto, sans-serif; font-weight: bold; margin-bottom: 1rem;">Cast Your Nomination</h4>
												</div>
											</div>
											<div id="awardnoms1" class="awardnoms card" aria-labelledby="awardnoms-heading1" data-id="1" style="">
												<div class="card-body bg-dark text-white">
													<p>
														For your nomination to count the artist <strong>must be an Australian resident or citizen</strong> and must have released an Album, EP or single between <strong>1st November 2020 - 15th October 2021</strong>.
													</p>

													<form id="voting-form">
														<div class="row">
															<div style="width: 100%; padding: 0 1.25em 0;">
																<input type="text" name="artist_name" class="form-control mt-2" placeholder="Artist name">
															</div>

															<div style="width: 100%; padding: 0 1.25em 1.25em;">
																<textarea name="entry" class="form-control mt-2" placeholder="In 25 words or less explain why you are voting for this artist."></textarea>
															</div>

															<div style="width: 100%; padding: 0 1.25em 1.25em;">
																<p>
																	<em>* Please note: only one nomination will be counted per subscriber.</em>
																</p>
															</div>
															<button>Nominate</button>
														</div>
													</form>
												</div>
											</div>
										<?php else :  ?>
											<div id="awardnoms1" class="awardnoms card" aria-labelledby="awardnoms-heading1" data-id="1" style="">
												<div class="card-body bg-dark text-white" style="text-align: center; padding: 0; font-weight: bold; background: #28a745 !important;">
													<p style="margin-top: 1rem;">Thanks for all your responses! Nominations have now closed. See you at the awards!</p>
												</div>
											</div>
										<?php endif; ?>

										<div id="awardnoms2" class="awardnoms card" aria-labelledby="awardnoms-heading1" data-id="1" style="display: none;">
											<div class="card-body bg-dark text-white" style="text-align: center; padding: 0; font-weight: bold; background: #28a745 !important;">
												<p style="margin-top: 1rem;">Thanks for your nomination!</p>
											</div>
										</div>
									</div>

									<h4 style="margin-top: 24px; margin-bottom: 12px; color: #000; font-family: Poppins, sans-serif; font-size: 24px; font-weight: normal;">Rolling Stone Awards Competition Terms &amp; Conditions</h4>

									<div class="t-c-wrap">
										<table style="font-family: 'Roboto', sans-serif;">
											<tbody>
												<tr>
													<td>Competition</td>
													<td>Rolling Stone Awards 2021 Readers’ Choice</td>
												</tr>
												<tr>
													<td>Entry Period</td>
													<td>Starts at 14:00 hours AEDT on 2 December 2021 and ends at 23:59 hours AEDT on 17 December 2021</span></td>
												</tr>
												<tr>
													<td>Entry</td>
													<td>
														(a) Go to https://au.rollingstone.com/nominate-for-rolling-stone-australia-readers-award-2022/ (“Website”); <br />
														(b) Fill in all the required data fields on the entry form for the Competition; and<br />
														(c) Nomination for your choice
													</td>
												</tr>
												<tr>
													<td>Limit</td>
													<td>1 entry per subscriber</td>
												</tr>
												<tr>
													<td>Judging Criteria</td>
													<td>Originality and creativity in 25 words or less answer</td>
												</tr>
												<tr>
													<td>Prize Determination</td>
													<td>14:00 hours AEDT on 8 February 2022 at The Brag Media.</td>
												</tr>
												<tr>
													<td>Notification</td>
													<td>By email by 10 February 2022</td>
												</tr>
												<tr>
													<td>Claim Period</td>
													<td>Within 2 weeks from the date of Notification </td>
												</tr>
												<tr>
													<td>Unclaimed Prize Determination</td>
													<td>14:00 hours AEST on 25 February 2022 at The Brag Media Offices<br />
														The winner will be notified in writing via email within 7 days of the Prize Determination.
													</td>
												</tr>
												<tr>
													<td>Prize</td>
													<td>
														4 tickets to attend Rolling Stone Awards event in Sydney on 31st march 2022
													</td>
												</tr>
												<tr>
													<td>Total Prize Value</td>
													<td>$2000</td>
												</tr>
												<tr>
													<td>Prize Conditions</td>
													<td>
														- Winner must arrange their own transport to and from the Rolling Stone Awards event.<br />
														- WInner must be at least 18 years of age.
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								<?php else : // If no subscriptions
								?>
									<div class="d-md-flex align-items-center">
										<div class="col-md-4" style="text-align: left;"><?php rollingstone_next_issue_cover(350); ?></div>
										<div>
											<h4 style="font-size: 150%; line-height: 1.7; text-align: center; margin: auto 1rem;">
												To be eligible to nominate for the Rolling Stone Readers Award, you need to have an active subscription.
												<br>
												If you currently don't have a <a href="https://au.rollingstone.com/" target="_blank" style="color: #d32531;">Rolling Stone Australia</a> Magazine subscription, you can subscribe <a href="https://au.rollingstone.com/subscribe-magazine/" target="_blank" style="color: #d32531;">here</a> and go in the running to win exclusive tickets to the awards for you and 3 of your mates.
											</h4>
											<p style="text-align: center; margin-top: 1rem;">If you think this is an error, please <a href="mailto:subscribe@thebrag.media" target="_blank" style="color: #0BA4FF;">contact us</a></p>
										</div>
									</div>
								<?php
								endif; // If subscriptions
								?>

							<?php else : // Not logged in 
							?>

								<div class="d-flex flex-wrap" style="font-size: 125%; line-height: 150%; text-align: center;">
									<p>If you are a magazine subscriber, please log in to nominate. If you are not a magazine subscriber, please <a href="https://au.rollingstone.com/subscribe-magazine/" target="_blank" style="color: #d32531;">subscribe here</a> so that you can join an elite club of supporters and readers of long form music journalism.</p>

									<p>
										Voters also go in the running to win <strong>exclusive tickets to the awards for you and 3 of your mates!</strong>
									</p>
								</div>

								<div style="display: flex; justify-content: center; margin: 1rem auto;">
									<a href="<?php echo esc_url(wp_login_url(home_url($wp->request))); ?>" class="c-subscribe__button c-subscribe__button--subscribe t-bold t-bold--upper" style="font-size: 100%;">Click here to login &amp; nominate</a>
								</div>

						<?php endif; // If logged in 
						endif;
						?>

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

<?php add_action('wp_footer', function () {
	global $noms_open, $noms_open_at;
?>
	<script>
		const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		jQuery(document).ready(function($) {
			jQuery(document).ready(function($) {
				$('#voting-form').on('submit', function(eve) {
					eve.preventDefault();

					// $('#thank-you-popup').text('').slideUp().removeClass();

					let formData = new FormData(this);
					formData.append('action', 'nominate_readers_award_2022');

					if (formData.getAll('artist_name')[0] == "") {
						$('#thank-you-popup').text('Please enter the artist name!').slideDown().removeClass().addClass('danger');
						setTimeout(function() {
							$('#thank-you-popup').slideUp();
						}, 3000);
						return false;
					}

					if (formData.getAll('entry')[0] == "") {
						$('#thank-you-popup').text('Please enter your 25 words or less!').slideDown().removeClass().addClass('danger');
						setTimeout(function() {
							$('#thank-you-popup').slideUp();
						}, 3000);
						return false;
					}

					$(this).find('button').attr("disabled", true).css('color', '#fff').css('backgroundColor', '#aaa').text('Submiting your nomination.')

					$.ajax({
						type: "POST",
						url: ajaxurl,
						data: formData,
						processData: false,
						contentType: false,
						dataType: "json",
						success: function(res, textStatus, jqXHR) {
							if (res.success) {
								$('#thank-you-popup').text(res.data).slideDown().removeClass().addClass('success');

								$('#awardnoms1').hide();
								$('#awardnoms-head').hide();
								$('#awardnoms2').show();
								setTimeout(function() {
									$('#thank-you-popup').slideUp();
								}, 3000);


							} else {
								$('#thank-you-popup').text(res.data).slideDown().removeClass().addClass('danger');
							}
						}
					});
				});

				$('#thank-you-popup').on('click', function() {
					$(this).slideUp();
				})
			});
		});
	</script>
<?php
}); // wp_footer

?>
<?php get_template_part('template-parts/footer/footer'); ?>
</div><!-- .l-page__content -->
<?php
get_footer();
