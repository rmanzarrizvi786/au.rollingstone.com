<?php
/**
 * Default template for the RS Live Media page
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since 2018-07-10
 */


$confirmation_url = [
	'success' => add_query_arg( 'status', 'success', get_permalink() ),
	'error'   => add_query_arg( 'status', 'error', get_permalink() ),
];

?>

<div class="c-content">

	<p class="t-bold">
		<?php
		echo esc_html__(
			'Whether it\'s the Super Bowl, Lollapalooza or New York Fashion Week, Rolling Stone Live Media takes you there. Our dynamic events ranging from  intimate speaker sessions to performances and celebrations connect thousands of people across the country, include powerhouse brands, music\'s biggest artists, and one-of-a-kind experiences you\'ll want to be a part of.',
			'pmc-rollingstone'
		);
		?>
	</p>

	<p><?php echo esc_html__( 'For your chance to get involved and witness the power of Rolling Stone Live Media, sign up now!', 'pmc-rollingstone' ); ?></p>

	<form method="post" action="https://pages.email.rollingstone.com/rs-live-media-api/" class="l-livemedia__form">

		<div class="l-livemedia__form-row l-livemedia__form-row--2col">
			<div class="l-livemedia__form-col" id="form_fname">
				<label for="form_fname_input"><?php echo esc_html__( 'First Name', 'pmc-rollingstone' ); ?></label>
				<input type="text" id="form_fname_input" name="FirstName" maxlength="250" placeholder="<?php echo esc_attr( __( 'Enter your First Name', 'pmc-rollingstone' ) ); ?>" required="required">
			</div>
			<div class="l-livemedia__form-col" id="form_lname">
				<label for="form_lname_input"><?php echo esc_html__( 'Last Name', 'pmc-rollingstone' ); ?></label>
				<input type="text" id="form_lname_input" name="LastName" maxlength="250" placeholder="<?php echo esc_attr( __( 'Enter your Last Name', 'pmc-rollingstone' ) ); ?>" required="required">
			</div>
		</div>

		<div class="l-livemedia__form-row" id="form_title">
			<label for="form_title_input"><?php echo esc_html__( 'Title', 'pmc-rollingstone' ); ?></label>
			<input type="text" id="form_title_input" name="NameTitle">
		</div>

		<div class="l-livemedia__form-row" id="form_email">
			<label for="form_email_input"><?php echo esc_html__( 'Email', 'pmc-rollingstone' ); ?></label>
			<input type="email" id="form_email_input" name="EmailAddress" maxlength="250" placeholder="<?php echo esc_attr( __( 'Enter your Email address', 'pmc-rollingstone' ) ); ?>" required="required">
		</div>

		<div class="l-livemedia__form-row" id="form_company">
			<label for="form_company_input"><?php echo esc_html__( 'Company', 'pmc-rollingstone' ); ?></label>
			<input type="text" id="form_company_input" name="Company" maxlength="250" placeholder="<?php echo esc_attr( __( 'Enter your Company name (if any)', 'pmc-rollingstone' ) ); ?>">
		</div>

		<div class="l-livemedia__form-row" id="form_submit">
			<input type="submit" value="<?php echo esc_attr( __( 'Submit', 'pmc-rollingstone' ) ); ?>" id="form_submit_input" class="c-btn c-btn--block c-btn--large c-btn--red t-bold">
		</div>

		<input name="__contextName" value="FormPost" type="hidden">
		<input name="__executionContext" value="Post" type="hidden">
		<input name="__successPage" class="js-newsletter-successpage" value="<?php echo esc_attr( $confirmation_url['success'] ); ?>" type="hidden">
		<input name="__errorPage" class="js-newsletter-errorpage" value="<?php echo esc_attr( $confirmation_url['error'] ); ?>" type="hidden">
	</form>

</div> <!-- .c-content -->
