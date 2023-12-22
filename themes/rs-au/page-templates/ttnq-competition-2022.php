<?php
/**
 * Template Name: TTNQ Competition 2022
 */

$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$formdata = $_POST;
	$errors = [];

	if (empty($formdata['firstname'])) {
		array_push($errors, 'Please enter a First Name.');
	}

	if (empty($formdata['lastname'])) {
		array_push($errors, 'Please enter a Last Name.');
	}

	if (empty($formdata['email'])) {
		array_push($errors, 'Please enter an Email.');
	}

	if (empty($formdata['phone'])) {
		array_push($errors, 'Please enter a Phone Number.');
	}

	if (empty($formdata['address1'])) {
		array_push($errors, 'Please enter an Address.');
	}

	if (empty($formdata['city'])) {
		array_push($errors, 'Please enter a City.');
	}

	if (empty($formdata['state']) || $formdata['state'] == '-') {
		array_push($errors, 'Please select a State.');
	}

	if (empty($formdata['postcode'])) {
		array_push($errors, 'Please enter a Post Code.');
	}

	if (empty($formdata['agree'])) {
		array_push($errors, 'Please accept Terms and Conditions of entry.');
	}

	if (count($errors) === 0) {
		$firstname = sanitize_text_field($formdata['firstname']);
		$lastname = sanitize_text_field($formdata['lastname']);
		$email = sanitize_text_field($formdata['email']);
		$phone = sanitize_text_field($formdata['phone']);
		$address1 = sanitize_text_field($formdata['address1']);
		$address2 = sanitize_text_field($formdata['address2']);
		$city = sanitize_text_field($formdata['city']);
		$state = sanitize_text_field($formdata['state']);
		$postcode = sanitize_text_field($formdata['postcode']);
		$signup = sanitize_text_field($formdata['signup']);
		$agree = sanitize_text_field($formdata['agree']);

		# add to DB

		global $wpdb;

		$wpdb->insert(
			$wpdb->prefix . 'ttnq_competition_2022',
			[
				'firstname' => $firstname,
				'lastname' => $lastname,
				'email' => $email,
				'phone' => $phone,
				'address_1' => $address1,
				'address_2' => $address2,
				'city' => $city,
				'state' => $state,
				'postcode' => $postcode,
				'signup' => !empty($signup) ? $signup : 'no',
				'agree' => !empty($agree) ? $agree : 'no',
			],
			['%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s']
		);

		$success = true;
	}
}

function tbm_hide_admin_bar()
{
	if ($post->ID == 44288) {
		return false;
	}
}
add_filter('show_admin_bar', 'tbm_hide_admin_bar');

$submissions_active = time() < strtotime('2023-03-01');
// $submissions_active = false;

wp_enqueue_script('ttnq-competition-2022', get_template_directory_uri() . '/page-templates/ttnq-competition-2022/js/scripts.js', ['jquery'], time(), true);

get_header('ttnq-competition-2022');

if (have_posts()):
	while (have_posts()):
		the_post();
		if (!post_password_required($post)):
			?>
			<?php if (isset($errors) && count($errors) > 0) { ?>
				<div class="alert alert-danger text-center" id="errors">
					<?php foreach ($errors as $error) { ?>
						<div>
							<?php echo $error; ?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
			<div class="l-page__content mx-auto pb-5" style="max-width: 1000px; background: #fff;">
				<div class="d-flex justify-content-center align-items-center py-5 px-5">
					<div class="competition-logo"></div>
				</div>
				<div class="competition-banner px-sm-3">
					<div class="competition-banner_inner d-flex justify-content-center align-items-center py-sm-5 px-5">
						<div class="competition-banner_logo my-sm-5"></div>
					</div>
				</div>
				<div class="text-uppercase text-center pt-2 pb-5 px-3" style="font-weight: 500;">
					Thanks to Rolling Stone Australia, Cairns & Great Barrier Reef and My Queensland
				</div>

				<div class="competition-prize-sm">
					<div class="row mx-3 align-items-start competition-prize-bg">
						<div class="col-sm-6 px-0">
							<img src="https://cdn.thebrag.com/pages/ttnq-competition-2022/win.png" class="mx-3 mt-3"
								style="width: 158px; height: 140px;" />
						</div>
						<div class="col-sm-6 px-0" style="background: #fff;">
							<div style="width: 100%;" class="px-3 py-3">
								<div class="py-4 px-5 text-center competition-info-side">
									<h1 class="competition-heading">
										Enter the draw to win the Rolling Stone Australia TNQ travel competition
									</h1>

									<p class="my-3" style="font-size: 0.8125rem; font-weight: 600;">
										All entrants will be eligible to receive exclusive My Queensland holiday vouchers, valued up
										to $1000* off for the first 10 bookings*.
									</p>
									<p class="my-2" style="font-size: 0.8125rem; font-weight: 600;">
										Visit <a
											href="https://www.myqldholiday.com.au/tropical-northern-queensland-hot-deals/?utm_source=ttnq&utm_medium=affiliate&utm_campaign=mhc_qld_ttnq_activation&utm_content=hot_deals"
											target="_blank">MyQueensland.com.au</a> to learn more.
									</p>
								</div>
								<div class="py-4 px-5 text-center text-uppercase border shadow"
									style="font-size: 1.125rem; font-weight: 400;">
									<h2 class="competition-sub-heading">
										Prize Details
									</h2>

									<p class="my-3 text-uppercase">
										Flights to Cairns to the value of $1,980
									</p>
									<h2 class="competition-sub-heading my-3">
										PLUS
									</h2>
									<p class="my-3 text-uppercase">
										3 nights accommodation at Crystalbrook Flynn with complimentary breakfast and cocktails at
										Whiskey & Wine
									</p>
									<p class="my-3 text-uppercase">
										Reef Magic tour for 2
									</p>
									<p class="my-3 text-uppercase">
										Dinner at Ochre restaurant
									</p>
									<p class="my-3 text-uppercase">
										2 nights stay at Cassowary Falls with complimentary breakfast and Waterfall Tour
									</p>
									<p class="my-3 text-uppercase">
										Cairns adventure group tours voucher to the value of $300
									</p>
									<p class="my-3 text-uppercase">
										A Fitzroy island day tour
									</p>
									<p class="my-3 text-uppercase" style="font-size: 1.5rem;">
										<strong>
											<span style="color: #E60019;">Total Value:</span> A$5,500
										</strong>
									</p>
								</div>

								<div class="py-4 px-5 text-center">
									<p class="my-2" style="font-size: 0.8125rem; font-weight: 600;">
										Competition runs January 27th 2023 12am AEDT until February 28th 11:59pm AEDT. Only
										Australian residents are eligible.<br />
										The winner will be drawn on Wednesday 1st March 2023.
									</p>
									<p class="my-2" style="font-size: 0.8125rem; font-weight: 600;">
										<a href="https://thebrag.com/media/terms-and-conditions/" target="_blank"
											style="color: #000;">
											Terms and conditions can be found here.
										</a>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="competition-prize">
					<div class="mx-3">
						<div style="width: 100%;" class="px-1 py-1">
							<div class="py-5 px-1 text-center competition-info-side">
								<h1 class="competition-heading">
									Enter the draw to win the Rolling Stone Australia TNQ travel competition
								</h1>

								<p class="my-3" style="font-size: 0.8125rem; font-weight: 600;">
									All entrants will be eligible to receive exclusive My Queensland holiday vouchers, valued up to
									$1000* off for the first 10 bookings*.
								</p>
								<p class="my-2" style="font-size: 0.8125rem; font-weight: 600;">
									Visit <a
										href="https://www.myqldholiday.com.au/tropical-northern-queensland-hot-deals/?utm_source=ttnq&utm_medium=affiliate&utm_campaign=mhc_qld_ttnq_activation&utm_content=hot_deals"
										target="_blank">MyQueensland.com.au</a> to learn more.
								</p>
							</div>
							<div class="competition-prize-bg">
								<img src="https://cdn.thebrag.com/pages/ttnq-competition-2022/win.png" class="mx-3 mt-3"
									style="width: 158px; height: 140px;" />
							</div>
							<div class="px-3 py-0" style="margin-top: -3rem;">
								<div class="py-4 px-5 text-center text-uppercase border shadow"
									style="font-size: 1rem; font-weight: 400; background: #fff;">
									<h2 class="competition-sub-heading">
										Prize Details
									</h2>

									<p class="my-3 text-uppercase">
										Flights to Cairns to the value of $1,980
									</p>
									<h2 class="competition-sub-heading my-3">
										PLUS
									</h2>
									<p class="my-3 text-uppercase">
										3 nights accommodation at Crystalbrook Flynn with complimentary breakfast and cocktails at
										Whiskey & Wine
									</p>
									<p class="my-3 text-uppercase">
										Reef Magic tour for 2
									</p>
									<p class="my-3 text-uppercase">
										Dinner at Ochre restaurant
									</p>
									<p class="my-3 text-uppercase">
										2 nights stay at Cassowary Falls with complimentary breakfast and Waterfall Tour
									</p>
									<p class="my-3 text-uppercase">
										Cairns adventure group tours voucher to the value of $300
									</p>
									<p class="my-3 text-uppercase">
										A Fitzroy island day tour
									</p>
									<p class="my-3 text-uppercase" style="font-size: 1.5rem;">
										<strong>
											<span style="color: #E60019;">Total Value:</span> A$5,500
										</strong>
									</p>
								</div>

								<div class="py-3 px-0 text-center">
									<p class="my-2" style="font-size: 0.8125rem; font-weight: 600;">
										Competition runs January 27th 2023 12am AEDT until February 28th 11:59pm AEDT. Only
										Australian residents are eligible.<br />
										The winner will be drawn on Wednesday 1st March 2023.
									</p>
									<p class="my-2" style="font-size: 0.8125rem; font-weight: 600;">
										<a href="https://thebrag.com/media/terms-and-conditions/" target="_blank"
											style="color: #000;">
											Terms and conditions can be found here.
										</a>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php if (!$submissions_active): ?>
					<div class="mt-5 mb-4 text-center">
						<p style="text-align: center; margin: 2rem auto; color: red;">
							Competition has ended.
						</p>
					</div>
				<?php else: ?>
					<div class="mt-5 mb-4 text-center">
						<img src="https://cdn.thebrag.com/pages/ttnq-competition-2022/mqld2.png" style="max-width: 280px;" />
						<h1 class="competition-heading">
							<u>Explore Exclusive My Queensland Holidays</u>
						</h1>
					</div>
					<div class="mb-4 mx-3 text-center">
						<div class="container">
							<div class="row">
								<a href="https://www.myqldholiday.com.au/package/crystalbrook-flynn-5-nights-urban-room-flights-today/?utm_source=ttnq&utm_medium=affiliate&utm_campaign=mhc_qld&utm_content=crystalbrook_flynn"
									target="_blank" class="col m-3 d-flex align-items-center justify-content-center px-0"
									style="box-shadow: 0 0 10px rgb(0 0 0 / 25%); height: 18rem; background: url(https://www.myqldholiday.com.au/wp-content/uploads/sites/21/2022/04/CrystalBrook-Flynn-Pool-Area-800x600.jpg) no-repeat center center; background-size: cover; text-decoration: none;">
									<div class="p-3 d-flex align-items-center justify-content-center"
										style="background: rgba(0, 0, 0, 0.6); width: 70%; height: 70%;">
										<span class="text-uppercase"
											style="font-size: 1.375rem; line-height: 2rem; font-weight: bold; color: #fff;">Crystalbrook
											Flynn, A Cairns Luxury Hotel</span>
										<span style="color: #fff; font-size: 0.75rem; line-height: 1rem;" class="px-3">Flights, 5
											Nights, Reef Cruises, $250 Crystalbrook Credit, $2200 Bonus Value &amp; More</span>
										<span style="color: #fff; font-size: 2rem; font-weight: bold;">$1299pp*</span>
									</div>
								</a>
								<a href="https://www.myqldholiday.com.au/package/alamanda-palm-cove-by-lancemore-5-nights-one-bedroom-pool-view-room-land-only/?utm_source=ttnq&utm_medium=affiliate&utm_campaign=mhc_qld&utm_content=alamanda_palm_cove"
									target="_blank" class="col m-3 d-flex align-items-center justify-content-center px-0"
									style="box-shadow: 0 0 10px rgb(0 0 0 / 25%); height: 18rem; background: url(https://www.myqldholiday.com.au/wp-content/uploads/sites/21/2020/03/71RhiannonTaylor_LM_PalmCove.jpg) no-repeat center center; background-size: cover; text-decoration: none;">
									<div class="p-3 d-flex align-items-center justify-content-center"
										style="background: rgba(0, 0, 0, 0.6); width: 70%; height: 70%;">
										<span class="text-uppercase"
											style="font-size: 1.375rem; line-height: 2rem; font-weight: bold; color: #fff;">Alamanda
											Palm Cove by Lancemore</span>
										<span style="color: #fff; font-size: 0.75rem; line-height: 1rem;" class="px-3">5 Nights
											Accomodation, Full Day Reef Adventure, Beach Yoga, Cocktails, $1400 Bonus Value &amp;
											More</span>
										<span style="color: #fff; font-size: 2rem; font-weight: bold;">$1299pp*</span>
									</div>
								</a>
							</div>
							<div class="row">
								<a href="https://www.myqldholiday.com.au/package/rydges-esplanade-resort-cairns-7-night-mountain-view-queen-room/?utm_source=ttnq&utm_medium=affiliate&utm_campaign=mhc_qld&utm_content=rydes_esplanade"
									target="_blank" class="col m-3 d-flex align-items-center justify-content-center px-0"
									style="box-shadow: 0 0 10px rgb(0 0 0 / 25%); height: 18rem; background: url(https://www.myqldholiday.com.au/wp-content/uploads/sites/21/2017/10/rydges-cairns-8.jpg) no-repeat center center; background-size: cover; text-decoration: none;">
									<div class="p-3 d-flex align-items-center justify-content-center"
										style="background: rgba(0, 0, 0, 0.6); width: 70%; height: 70%;">
										<span class="text-uppercase"
											style="font-size: 1.375rem; line-height: 2rem; font-weight: bold; color: #fff;">Rydges
											Esplanade Resort Cairns</span>
										<span style="color: #fff; font-size: 0.75rem; line-height: 1rem;" class="px-3">7 Nights
											Accomodation, Reef Cruises, Cairns Aquarium, $2150 Bonus Value &amp; More</span>
										<span style="color: #fff; font-size: 2rem; font-weight: bold;">$899pp*</span>
									</div>
								</a>
								<a href="https://www.myqldholiday.com.au/package/paradise-links-resort-port-douglas-7-nights-links-room/?utm_source=ttnq&utm_medium=affiliate&utm_campaign=mhc_qld&utm_content=paradise_links_resort"
									target="_blank" class="col m-3 d-flex align-items-center justify-content-center px-0"
									style="box-shadow: 0 0 10px rgb(0 0 0 / 25%); height: 18rem; background: url(https://www.myqldholiday.com.au/wp-content/uploads/sites/21/2019/03/resort-pool-paradise-links-resort-port-douglas.jpg) no-repeat center center; background-size: cover; text-decoration: none;">
									<div class="p-3 d-flex align-items-center justify-content-center"
										style="background: rgba(0, 0, 0, 0.6); width: 70%; height: 70%;">
										<span class="text-uppercase"
											style="font-size: 1.375rem; line-height: 2rem; font-weight: bold; color: #fff;">Paradise
											Links Resort Port Douglas</span>
										<span style="color: #fff; font-size: 0.75rem; line-height: 1rem;" class="px-3">7 Nights
											Accomodation, Breakfast Hamper, Reef Cruise, Low Isles Cruise, & $2500 Bonus Value &amp;
											More</span>
										<span style="color: #fff; font-size: 2rem; font-weight: bold;">$699pp*</span>
									</div>
								</a>
							</div>
						</div>
						<div class="row">
							<a href="https://www.myqldholiday.com.au/tropical-northern-queensland-hot-deals/?utm_source=ttnq&utm_medium=affiliate&utm_campaign=mhc_qld_ttnq_activation&utm_content=hot_deals"
								target="_blank" class="mt-1 competition-submit"
								style="text-decoration: none; max-width: 280px; background: #00ACBE;">
								<span id="button-text" style="height: auto;">Explore More Deals</span>
							</a>
						</div>
					</div>
					<div class="mt-5 mb-4 text-center">
						<h1 class="competition-heading">
							<u>Enter Below</u>
						</h1>
					</div>
					<div class="mx-3 my-0 border competition-form px-sm-5">
						<div class="row d-flex pt-5 pb-2 px-3 text-center">
							<h2 class="competition-sub-heading">WIN A TROPICAL NORTH QUEENSLAND TRAVEL PACKAGE WORTH $5,500</h2>
							<p class="my-2 text-uppercase" style="font-size: 0.8125rem; font-weight: 500;">
								Thanks to Rolling Stone Australia,Cairns & Great Barrier Reef and My Queensland
							</p>
						</div>

						<?php //get_template_part('page-templates/ttnq-competition-2022/form'); ?>

						<form id="ttnq-competition-2022-form" method="POST" novalidate>
							<div class="row d-flex px-3">
								<div class="mt-1 pr-1 col-sm-6">
									<label class="text-uppercase">
										First name<br />
										<input type="text" name="firstname" placeholder="Enter your First Name" class="border"
											value="<?php echo $formdata['firstname']; ?>" required>
									</label>
								</div>
								<div class="mt-1 pl-1 col-sm-6">
									<label class="text-uppercase">
										Last name
										<input type="text" name="lastname" placeholder="Enter your Last Name" class="border"
											value="<?php echo $formdata['lastname']; ?>" required>
									</label>
								</div>
							</div>
							<div class="row d-flex py-2 px-3">
								<div class="mt-1 pr-1 col-sm-6">
									<label class="text-uppercase">
										Email
										<input type="email" name="email" placeholder="Enter your Email" class="border"
											value="<?php echo $formdata['email']; ?>" required>
									</label>
								</div>
								<div class="mt-1 pl-1 col-sm-6">
									<label class="text-uppercase">
										Phone Number
										<input type="text" name="phone" placeholder="Enter your Phone Number" class="border"
											value="<?php echo $formdata['phone']; ?>" required>
									</label>
								</div>
							</div>
							<div class="row py-2 px-3">
								<div class="mt-1 pr-1 col-12">
									<label class="text-uppercase">
										Address
										<div class="my-2">
											<input type="text" name="address1" placeholder="Address 1" class="border"
												value="<?php echo $formdata['address1']; ?>" required>
										</div>
										<div class="my-2">
											<input type="text" name="address2" placeholder="Address 2" class="border"
												value="<?php echo $formdata['address2']; ?>">
										</div>
									</label>
								</div>
							</div>
							<div class="row py-2 px-3">
								<div class="col-sm-6">
									<label class="text-uppercase">
										City
										<input type="text" name="city" placeholder="Enter your City" class="border"
											value="<?php echo $formdata['city']; ?>" required>
									</label>
								</div>
								<div class="col-sm-3">
									<?php
									$states = [
										'NSW',
										'Vic',
										'QLD',
										'SA',
										'WA',
										'NT',
										'ACT',
										'Tas'
									];
									?>
									<label class="text-uppercase">
										State
										<select name="state" required>
											<option value="">-</option>
											<?php foreach ($states as $$state): ?>
												<option value="<?php echo $$state; ?>" <?php echo $formdata['state'] == $$state ? 'selected' : ''; ?>>
													<?php echo $$state; ?>
												</option>
											<?php endforeach; ?>
										</select>
									</label>
								</div>
								<div class="col-sm-3">
									<label class="text-uppercase">
										Post Code
										<input type="text" name="postcode" placeholder="Enter your Post Code" class="border"
											value="<?php echo $formdata['postcode']; ?>" required>
									</label>
								</div>
							</div>
							<div class="col-12 mt-4 py-2 px-3">
								<div class="d-inline-flex mb-2">
									<div style="margin-right: 1rem;" class="d-flex align-items-center">
										<input type="checkbox" name="signup" style="float: left;" value="yes" <?php echo $formdata['signup'] ? 'checked' : ''; ?> />
									</div>
									<span>
										I agree to receive exclusive offers and marketing emails from My Queensland, The Brag and
										Tourism Tropical North Queensland.
										<span>
								</div>
								<div class="d-inline-flex mb-2">
									<div style="margin-right: 1rem;" class="d-flex align-items-center">
										<input type="checkbox" name="agree" style="float: left;" value="yes" <?php echo $formdata['agree'] ? 'checked' : ''; ?> required />
									</div>
									<span>
										I accept the Terms and Conditions of entry.
										<span>
								</div>
							</div>
							<div class="py-5 px-3">
								<button id="ttnq-competition-2022-submit" type="submit" class="mt-1 competition-submit">
									<div id="spinner" class="hidden"></div>
									<span id="button-text" style="height: auto;">SUBMIT</span>
								</button>
							</div>
						</form>
						<div class="my-3 text-center" style="font-size: 0.8125rem; font-weight: 600;">
							<h2 class="competition-sub-heading text-center">Competition Information</h2>
						</div>
						<p class="my-3 text-center" style="font-size: 0.8125rem; font-weight: 600;">
							<a href="https://thebrag.com/media/terms-and-conditions/" target="_blank" style="color: #000;">
								Terms and conditions can be found here.
							</a>
						</p>
						<table style="font-size: 0.8125rem;">
							<tbody>
								<tr>
									<td>
										<b>Competition:</b>
									</td>
									<td>
										Win a tropical North Queensland travel package.
									</td>
								</tr>
								<tr>
									<td>
										<b>Entrants:</b>
									</td>
									<td>
										Only Australian residents are eligible.
									</td>
								</tr>
								<tr>
									<td>
										<b>Entry Period:</b>
									</td>
									<td>
										Competition runs January 27th 2023 12am AEDT until February 28th 11:59pm AEDT.
									</td>
								</tr>
								<tr>
									<td>
										<b>Entry:</b>
									</td>
									<td>
										Fill in all the required data fields on the entry form.
									</td>
								</tr>
								<tr>
									<td>
										<b>Limit:</b>
									</td>
									<td>
										One (1) entry per person
									</td>
								</tr>
								<tr>
									<td>
										<b>Prize Determination:</b>
									</td>
									<td>
										The winners will be chosen at random.
									</td>
								</tr>
								<tr>
									<td>
										<b>Notification:</b>
									</td>
									<td>
										By email within one week of the winners being chosen.
									</td>
								</tr>
								<tr>
									<td>
										<b>Claim Period:</b>
									</td>
									<td>
										Within 4 weeks from the date of Notification. [Note: 3 months in South Australia. Shortest claim
										period is 4 minutes in NSW]
									</td>
								</tr>
								<tr>
									<td>
										<b>Unclaimed Prize Determination:</b>
									</td>
									<td>
										Should the winner not claim the prize within 4 weeks from the date of Notification, a new winner
										will be chosen. The new winner will be notified via email within a week of the Prize
										Determination.
									</td>
								</tr>
								<tr>
									<td>
										<b>Prize:</b>
									</td>
									<td>
										Flights to Cairns to the value of $1,980, 3 nights accommodation at Crystalbrook Flynn with
										complimentary breakfast and cocktails at Whiskey & Winem Reef Magic tour for 2, dinner at Ochre
										restaurant, 2 nights stay at Cassowary Falls with complimentary breakfast and waterfall tour,
										Cairns adventure group tours voucher to the value of $300, a Fitzroy island day tour. total
										prize pool value: $5,500.
									</td>
								</tr>
								<tr>
									<td>
										<b>Prize Conditions:</b>
									</td>
									<td>
										<p>
											Entrants must fill out form and opt in to these terms and conditions.
										</p>

										<p>
											* Prices are per person twin share unless otherwise stated. For full terms and conditions
											visit <a
												href="https://www.myqldholiday.com.au/tropical-northern-queensland-hot-deals/?utm_source=ttnq&utm_medium=affiliate&utm_campaign=mhc_qld_ttnq_activation&utm_content=hot_deals"
												target="_blank" style="color: #000;">MyQueensland.com.au</a>
										</p>
									</td>
								</tr>
							</tbody>
						</table>

						<div class="my-3 text-center" style="font-size: 0.8125rem; font-weight: 600;">
							<h2 class="competition-sub-heading text-center">Prize Operators Terms & Conditions</h2>
						</div>
						<table style="font-size: 0.8125rem;">
							<tbody>
								<tr>
									<td>
										<b>Ochre restaurant Voucher T&Cs:</b>
									</td>
									<td>
										No cash redemption. Voucher must be presented on arrival. Bookings Required.
									</td>
								</tr>
								<tr>
									<td>
										<b>Cassowary Falls Gateway Prize Certificate T&Cs:</b>
									</td>
									<td>
										No cash redemption. Valid for 2 years Book pending availability / not available on public
										holidays. Voucher only valid during your stay.
									</td>
								</tr>
								<tr>
									<td>
										<b>Crystalbrook Flynn Voucher T&Cs:</b>
									</td>
									<td>
										Subject to availability. Blackout dates apply: 20 December - 10 January. Advanced booking is
										essential. Not valid on public holidays or for special events. Please present voucher at time of
										check-in to redeem your stay. Credit card is required at time of check in. Voucher is not
										redeemable for cash.
									</td>
								</tr>
								<tr>
									<td>
										<b>Fitzroy Island Resort Voucher T&Cs:</b>
									</td>
									<td>
										Valid for 3 years from the date of purchase. Not redeemable for cash. Resort reservations MUST
										be contacted at least 72 Hours before this voucher can be used Please quote the voucher when
										making your reservation and present upon arrival at reception. Voucher must be used in its
										entirety — residual credit will be forfeited. Bookings are subject to availability.
									</td>
								</tr>
								<tr>
									<td>
										<b>Flight Centre T&Cs:</b>
									</td>
									<td>
										Not redeemable for cash. Return economy airfares to Cairns for 2 people up to the value of $1800
										from Australian airports. Can only be used at Flight Centre Business Travel Cairns. Only 1
										voucher can be used per person per booking. Non-transferable. Blackout periods apply – please
										refer to your experience voucher for further details. Does not apply to travelers cheque
										transactions. Cannot be used in conjunction with any other offer.
									</td>
								</tr>
								<tr>
									<td>
										<b>Reef Magic Voucher T&Cs:</b>
									</td>
									<td>
										• Vouchers are non-refundable, not redeemable for cash, but are transferable. The purchaser must
										advise Reef Magic Cruises it order to transfer a gift voucher. Failure to show or your scheduled
										date and time will rerder your voucher invalid. Remember to present your voucher or arrival at
										check-in as without it, you are unable to participate.<br />
										• Treat your voucher like cash and take care not to lose it. Lost or stolen vouchers are
										nor-refundable and no replacements are available.<br />
										• Any add-ors, optional activities or extras will be payable in additior to this voucher. The
										voucher holder will also be required to Day any price differences between the voucher value and
										current prices on the day of travel. All bookings are subject to availability and operator
										discretion. <br />
										• Standard Reef Magic Cruises booking terms and conditions apply and can be viewed at
										https://www.reefmagic.com
									</td>
								</tr>
								<tr>
									<td>
										<b>Cairns Adventure Group Prize T&Cs:</b>
									</td>
									<td>
										Please use before prize voucher expires.
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<?php if ($success): ?>
						<div class="modal focus" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"
							style="display: block; z-index: 999999;">
							<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
								<div class="modal-content competition-success px-3 py-5 rounded-0" style="max-width: 542px;">
									<a href="https://www.myqldholiday.com.au/" target="_blank">
										<img src="https://cdn.thebrag.com/pages/ttnq-competition-2022/success-banner.jpg"
											style="width: 100%;" />
									</a>

									<div class="competition-success-message text-center pt-2">
										Good luck!<br />
										You are entered in the competition
									</div>

									<div class="competition-success-message text-center pt-2">
										<a href="https://www.myqldholiday.com.au/" target="_blank">
											<img src="https://cdn.thebrag.com/pages/ttnq-competition-2022/success-cta.jpg"
												style="max-width: 425px; width: 100%;" />
										</a>
									</div>

									<div class="py-2 px-0 text-center">
										<p class="my-2" style="font-size: 0.8125rem; font-weight: 600;">
											Competition runs January 27th 2023 12am AEDT until February 28th 11:59pm AEDT. Only Australian
											residents are eligible.
											The winner will be drawn on Wednesday 1st March 2023.
										</p>
										<p class="my-2" style="font-size: 0.8125rem; font-weight: 600;">
											<a href="https://thebrag.com/media/terms-and-conditions/" target="_blank" style="color: #000;">
												Terms and conditions can be found here.
											</a>
										</p>
									</div>
								</div>
							</div>
						</div>
						<?php
					endif; // If $success
							endif; // If $submissions_active 
							?>
			</div><!-- .l-page__content -->
		<?php else: ?>
			<style type="text/css">
				body {
					background: none;
				}

				input {
					border: 1px solid #ccc;
					padding: 5px 6px;
				}
			</style>
			<div class="l-page__content d-flex align-items-center justify-content-center mx-auto text-center"
				style="max-width: 1000px; min-height: 1000px; background: #fff;">
				<?php echo get_the_password_form(); ?>
			</div>
			<?php
		endif;
	endwhile; // Password protected
	wp_reset_query();
endif;

get_template_part('template-parts/footer/footer');
?>
<script>
	if (window.history.replaceState) {
		window.history.replaceState(null, null, window.location.href);
	}
</script>
<?php //get_footer();
