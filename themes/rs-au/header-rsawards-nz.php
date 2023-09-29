<?php

/**
 * The template for displaying the header.
 *
 * @package rs-au-2019
 * @since 2019-11-20
 */

use PMC\Lists\List_Post;

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<head>
	<meta charset="<?php echo esc_attr(get_bloginfo('charset')); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<link rel="manifest" href="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/manifest.json'); // WPCS: XSS okay. 
								?>">

	<!-- Responsiveness -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Browser shell -->
	<meta name="theme-color" content="#df3535">

	<!-- Add to home screen for iOS -->
	<meta name="apple-mobile-web-app-title" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<link rel="apple-touch-icon" href="https://www.rollingstone.com/wp-content/uploads/2018/07/cropped-rs-favicon.png?w=180">

	<!-- Tile icons for Windows -->
	<meta name="msapplication-config" content="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/browserconfig.xml'); // WPCS: XSS okay. 
												?>">
	<meta name="msapplication-TileImage" content="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/icons/mstile-144x144.png'); // WPCS: XSS okay. 
													?>">
	<meta name="msapplication-TileColor" content="#eff4ff">

	<!-- Favicons -->
	<link rel="icon" type="image/png" sizes="32x32" href="https://www.rollingstone.com/wp-content/uploads/2018/07/cropped-rs-favicon.png?w=32">
	<link rel="shortcut icon" href="https://www.rollingstone.com/wp-content/uploads/2018/07/cropped-rs-favicon.png?w=196">

	<!-- Safari pin icon -->
	<link rel="mask-icon" href="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/icons/safari-pinned-tab.svg'); // WPCS: XSS okay. 
								?>" color="#000000">

	<!-- Titles -->
	<meta name="apple-mobile-web-app-title" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
	<meta name="application-name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
	<meta name="description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
	<!-- Titles:end -->

	<meta property="fb:pages" content="203538151294" />

	<?php if (is_single()) {
		$src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
	?>
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="@rollingstoneaus">
		<meta name="twitter:title" content="<?php the_title(); ?>">
		<meta name="twitter:image" content="<?php if (has_post_thumbnail()) {
												echo $src[0];
											} ?>">
	<?php } // If single 
	?>

	<?php do_action('pmc_tags_head'); ?>

	<?php wp_head(); ?>

	<?php if (is_front_page()) : ?>
		<script>
			function PMC_RS_isSectionOpen(name) {
				var storage = localStorage.getItem('PMC_RS_homeState');
				var hasState = storage && 'undefined' !== typeof JSON.parse(storage)[name];
				return hasState ? JSON.parse(storage)[name] : true;
			}

			function PMC_RS_setHomeAppearance(name) {
				var isSectionOpen = PMC_RS_isSectionOpen(name);
				var section = document.querySelector('[data-section="' + name + '"]');

				if (null !== section) {
					if (isSectionOpen) {
						section.classList.remove('is-closing');
						section.classList.remove('is-closed');
					} else {
						section.classList.add('is-closing');
						section.classList.add('is-closed');
					}
				}
			}

			function PMC_RS_toggleHomeAd(name) {
				var isSectionOpen = PMC_RS_isSectionOpen(name);
				var ad = document.querySelector('[data-section-ad="' + name + '"]');

				if (!isSectionOpen && null !== ad) {
					ad.parentNode.removeChild(ad);
				}
			}
		</script>
	<?php endif; ?>

	<?php // get_template_part( 'template-parts/header/ads-direct-js' ); 
	?>

	<!-- Admiral -->
	<script type="text/javascript">
		!(function(o, n, t) {
			t = o.createElement(n), o = o.getElementsByTagName(n)[0], t.async = 1, t.src = "https://bravecalculator.com/v2/0/jmfAe_cRj0ZB4StQ-uahRpOs6jYGaVFa9WTlyW2bPfNf4vnVKw2BPI", o.parentNode.insertBefore(t, o)
		})(document, "script"), (function(o, n) {
			o[n] = o[n] || function() {
				(o[n].q = o[n].q || []).push(arguments)
			}
		})(window, "admiral");
		!(function(n, e, r, t) {
			function o() {
				if ((function o(t) {
						try {
							return (t = localStorage.getItem("v4ac1eiZr0")) && 0 < t.split(",")[4]
						} catch (n) {}
						return !1
					})()) {
					var t = n[e].pubads();
					typeof t.setTargeting === r && t.setTargeting("admiral-engaged", "true")
				}
			}(t = n[e] = n[e] || {}).cmd = t.cmd || [], typeof t.pubads === r ? o() : typeof t.cmd.unshift === r ? t.cmd.unshift(o) : t.cmd.push(o)
		})(window, "googletag", "function");
	</script>


	<!-- TikTok -->
	<script>
		(function() {
			var ta = document.createElement('script');
			ta.type = 'text/javascript';
			ta.async = true;
			ta.src = 'https://analytics.tiktok.com/i18n/pixel/sdk.js?sdkid=BRGQC53J857475I0KC5G';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ta, s);
		})();
	</script>

	<style>
		div.admz,
		div.admz-sp {
			margin-left: auto;
			margin-right: auto;
			text-align: center;
		}

		figure {
			max-width: 100%;
			text-align: center;
		}

		iframe {
			margin: auto;
			max-width: 100%;
		}

		.c-picture__title,
		.c-picture__source {
			text-align: left;
		}

		.d-none {
			display: none !important;
		}

		.d-flex,
		.row {
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.flex-fill {
			flex: 1;
		}

		.flex-column {
			flex-direction: column;
		}

		.flex-row {
			flex-direction: row;
		}

		.align-items-stretch {
			align-items: stretch;
		}

		.flex-wrap {
			flex-wrap: wrap;
		}

		.h-100 {
			height: 100%;
		}

		.fields-wrap {
			background-color: rgba(0, 0, 0, 0.025);
		}

		.fields-wrap:nth-child(even) {
			background-color: rgba(0, 0, 0, 0.075);
		}

		.fields-wrap:last-child {
			border-bottom-left-radius: 1rem;
			border-bottom-right-radius: 1rem;
		}

		<?php foreach (range(1, 4) as $i) : ?>.mt-<?php echo $i; ?>,
		.my-<?php echo $i; ?> {
			margin-top: <?php echo $i / 2; ?>rem !important;
		}

		.mb-<?php echo $i; ?>,
		.my-<?php echo $i; ?> {
			margin-bottom: <?php echo $i / 2; ?>rem !important;
		}

		.ml-<?php echo $i; ?>,
		.mx-<?php echo $i; ?> {
			margin-left: <?php echo $i / 2; ?>rem !important;
		}

		.mr-<?php echo $i; ?>,
		.mx-<?php echo $i; ?> {
			margin-right: <?php echo $i / 2; ?>rem !important;
		}

		.p-<?php echo $i; ?> {
			padding: <?php echo $i / 2; ?>rem;
		}

		.pt-<?php echo $i; ?>,
		.py-<?php echo $i; ?> {
			padding-top: <?php echo $i / 2; ?>rem !important;
		}

		.pb-<?php echo $i; ?>,
		.py-<?php echo $i; ?> {
			padding-bottom: <?php echo $i / 2; ?>rem !important;
		}

		.pl-<?php echo $i; ?>,
		.px-<?php echo $i; ?> {
			padding-left: <?php echo $i / 2; ?>rem !important;
		}

		.pr-<?php echo $i; ?>,
		.px-<?php echo $i; ?> {
			padding-right: <?php echo $i / 2; ?>rem !important;
		}

		<?php endforeach; ?>.text-uppercase {
			text-transform: uppercase;
		}

		.text-center {
			text-align: center;
		}

		.text-right {
			text-align: right;
		}

		.text-primary {
			color: #d32531;
		}

		.btn {
			font-family: Graphik, sans-serif;
			outline: none;
			padding: .25rem .5rem;
			border: none;
			border-radius: .25rem;
			cursor: pointer;
			text-align: center;
			font-weight: bold;
			transition: 1s all linear;
		}

		.btn.btn-add,
		.btn.btn-remove {
			color: #fff;
		}

		.btn.btn-add,
		.btn-outline-primary {
			color: #d32531;
			border: 1px solid #d32531;
			transition: .15s all linear;
		}

		.btn.btn-add:hover,
		.btn-outline-primary:hover {
			color: #fff;
			/* border: 1px solid #fff; */
			background-color: #d32531;
		}

		.btn.btn-remove {
			background-color: #999;
			padding: .05rem .5rem;
		}

		.btn.btn-success {
			background-color: #61ba00;
			color: #fff;
		}

		.btn-nominate {
			border: none;
			display: inline-block;
			background-color: #df3535;
			color: #fff;
			border-radius: 10rem;
			font-family: Graphik, sans-serif;
			font-size: 1.5rem;
			margin: 1rem auto;
			transition: .25s all linear;
			border: 1px solid #df3535;
		}

		.btn-nominate:hover {
			background-color: #fff !important;
			color: #df3535 !important;
		}

		.alert {
			position: relative;
			padding: 0.5rem 1rem;
			border: 1px solid transparent;
			border-radius: 0.25rem;
		}

		.alert-danger {
			color: #721c24;
			background-color: #f8d7da;
			border-color: #f5c6cb;
		}

		.alert-success {
			color: #155724;
			background-color: #d4edda;
			border-color: #c3e6cb;
		}

		.form-control {
			width: 100%;
			padding: .75rem;
			font-size: 1rem;
			background-color: #fff;
			background-clip: padding-box;
			border: 1px solid #ced4da;
			border-radius: .25rem;
			transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
			font-family: Graphik, sans-serif;
		}

		.form-control::placeholder {
			opacity: .5;
			transition: .25s all linear;
		}

		.form-control:focus::placeholder {
			opacity: .25;
		}

		.rotate180 {
			transform: rotate(180deg);
			transition: .25s all linear;
		}

		<?php for ($i = 1; $i <= 12; $i++) { ?>.col-<?php echo $i; ?> {
			flex: 0 0 <?php echo $i * 8.333333; ?>%;
		}

		<?php } ?>@media (min-width: 48rem) {
			.btn {
				border-radius: 10rem;
				padding: .5rem 1rem;
			}

			.d-md-none {
				display: none !important;
			}

			.d-md-block {
				display: block !important;
			}

			.d-md-flex {
				display: flex !important;
			}

			.flex-md-row {
				flex-direction: row !important;
			}

			.flex-md-column {
				flex-direction: column !important;
			}

			.col-md-6 {
				flex: 0 0 50%;
			}

			<?php foreach (range(1, 4) as $i) : ?>.m-md-<?php echo $i; ?> {
				margin: <?php echo $i / 2; ?>rem;
			}

			.ml-md-<?php echo $i; ?>,
			.mx-md-<?php echo $i; ?> {
				margin-left: <?php echo $i / 2; ?>rem !important;
			}

			.mr-md-<?php echo $i; ?>,
			.mx-md-<?php echo $i; ?> {
				margin-right: <?php echo $i / 2; ?>rem !important;
			}

			.mt-md-<?php echo $i; ?>,
			.my-md-<?php echo $i; ?> {
				margin-top: <?php echo $i / 2; ?>rem !important;
			}

			.mb-md-<?php echo $i; ?>,
			.my-md-<?php echo $i; ?> {
				margin-bottom: <?php echo $i / 2; ?>rem !important;
			}

			.p-md-<?php echo $i; ?> {
				padding: <?php echo $i / 2; ?>rem;
			}

			.pt-md-<?php echo $i; ?>,
			.py-md-<?php echo $i; ?> {
				padding-top: <?php echo $i / 2; ?>rem !important;
			}

			.pb-md-<?php echo $i; ?>,
			.py-md-<?php echo $i; ?> {
				padding-bottom: <?php echo $i / 2; ?>rem !important;
			}

			.pl-md-<?php echo $i; ?>,
			.px-md-<?php echo $i; ?> {
				padding-left: <?php echo $i / 2; ?>rem !important;
			}

			.pr-md-<?php echo $i; ?>,
			.px-md-<?php echo $i; ?> {
				padding-right: <?php echo $i / 2; ?>rem !important;
			}

			<?php endforeach; ?><?php for ($i = 1; $i <= 12; $i++) { ?>.col-md-<?php echo $i; ?> {
				flex: 0 0 <?php echo $i * 8.333333; ?>%;
			}

			<?php } ?>
		}

		@media (min-width: 48rem) {
			<?php for ($i = 1; $i <= 12; $i++) { ?>.col-lg-<?php echo $i; ?> {
				flex: 0 0 <?php echo $i * 8.333333; ?>%;
			}

			<?php } ?>
		}


		<?php
		if (is_user_logged_in()) :
		?>.ui-datepicker-today .ui-state-highlight {
			background: none !important;
			border-color: #444 !important;
			color: inherit !important;
		}

		.dark a.active,
		.dark a.active:hover,
		.dark a.active:focus {
			background-color: #000 !important;
			color: #fff !important;
		}

		.btn .fas {
			transition: .25s all linear;
		}

		.btn .fas.active {
			transform: rotate(180deg)
		}

		.textarea-partial {
			max-height: 150px;
			overflow-y: scroll;
			/* border-bottom: 1px solid rgba(255, 255, 255, .25); */
			position: relative;
		}

		.counter-wrap {
			counter-reset: section 1;
		}

		.separator {
			position: relative;
		}

		.separator:before {
			counter-increment: section;
			content: counters(section, '');
			position: absolute;
			left: 0;
			top: 0;
		}

		.separator:before,
		.counter {
			display: flex;
			justify-content: center;
			align-items: center;
			padding: .25rem .5rem;
			height: 100%;
			background: #d32531;
			color: #fff;
			border-radius: 4px;
		}

		.separator hr,
		.divider-h hr {
			visibility: hidden;
			/* background-color: #777;
			background: linear-gradient(90deg, rgba(0, 0, 0, 0) 0%, #d32531 50%, rgba(0, 0, 0, 0) 100%);
			height: 1px;
			border-width: 0; */
		}

		.collapse {
			display: none;
		}

		.collapse.show {
			display: block !important;
		}

		.accordion .card-header button,
		.accordion .card-body {
			border: 1px solid rgba(0, 0, 0, 0.25);
		}

		.accordion .card-header button {
			transition: .25s all linear;
		}

		.accordion .card-body {
			border-radius: 1rem;
		}

		.accordion .card-header button.rounded-top {
			margin-left: 2rem;
			border-radius: 1rem 1rem 0 0 !important;
			border-bottom: 1px solid #fff;
			margin-bottom: -1px;
			border-bottom-color: #fff;
		}

		<?php
		endif; // If logged in 
		?>#timer-awards-noms-open {
			/* font-family: "Poppins", sans-serif; */
		}

		#timer-awards-noms-open .sep {
			font-size: 270%;
			line-height: 1;
			margin-top: -2rem;
		}

		#timer-awards-noms-open .number {
			font-size: 270%;
			width: 4rem;
			text-align: center;
			line-height: 1.25;
		}

		@media(min-width: 48rem) {
			#timer-awards-noms-open .sep {
				font-size: 500%;
			}

			#timer-awards-noms-open .number {
				width: 7rem;
				font-size: 500%;
			}

			.how-to-nominate {
				max-width: 80%;
				margin: auto;
			}

			.intro-para {
				max-width: 90%;
			}
		}

		.mw-none {
			max-width: none !important;
		}

		#content-wrap {
			position: relative;
			/* margin-top: .75rem; */
			/* padding-top: .75rem; */

			width: 100%;
			max-width: 1000px;
			margin-left: auto;
			margin-right: auto;
			background-color: #fff;
		}

		#wpadminbar {
			display: none;
		}

		html {
			margin-top: 0 !important;
		}

		.l-page {
			background-color: #000;
		}

		.l-footer__wrap {
			max-width: 1000px;
		}

		#content-wrap:before,
		#content-wrap:after {
			content: "";
			width: 340px;
			position: absolute;
			top: 0;
			background-repeat: no-repeat;
			height: 100%;
			background-size: contain;
			background-color: #000;
			min-height: 1630px;
		}

		.is-header-sticky .l-header__wrap {
			position: relative;
		}

		@media (min-width: 60rem) {

			.l-header,
			.l-header__content,
			.l-header__wrap {
				height: 7rem;
			}
		}


		#content-wrap:before {
			width: 339px;
			left: -339px;
			background-image: url(<?php echo get_template_directory_uri(); ?>/images/rsa2022/nz/PHRSNZAwardsNomsLanding-left3.jpg);
			background-position: top right;
		}

		#content-wrap:after {
			right: -340px;
			background-image: url(<?php echo get_template_directory_uri(); ?>/images/rsa2022/nz/PHRSNZAwardsNomsLanding-right.jpg);
			background-position: top left;
		}


		body {
			/* font-family: "Poppins", sans-serif !important; */
			background-color: #fff;
		}

		.ad-bb-header {
			display: none;
		}

		input[type="password"] {
			border: 1px solid;
		}

		#skin-ad-section,
		#onesignal-bell-container,
		#onesignal-slidedown-dialog {
			display: none !important;
		}

		.news-links a,
		.news-links a:visited {
			color: #df3535;
			text-decoration: none;
			transition: .25s all linear;
		}

		.news-links a:hover {
			color: #000;
		}
	</style>

	<!-- Apester -->
	<script type="text/javascript" src="https://static.apester.com/js/sdk/latest/apester-sdk.js" async></script>

</head>

<?php if (rollingstone_is_list() && 'none' !== List_Post::get_instance()->get_order()) : ?>

	<body data-list-page data-list-total="<?php echo esc_attr(List_Post::get_instance()->get_list_items_count()); ?>" <?php body_class(); ?>>

	<?php else : ?>

		<body <?php body_class(); ?>>

		<?php endif; ?>

		<?php do_action('pmc-tags-top'); // phpcs:ignore 
		?>

		<!-- Facebook Pixel Code -->

		<script>
			! function(f, b, e, v, n, t, s)

			{
				if (f.fbq) return;
				n = f.fbq = function() {
					n.callMethod ?

						n.callMethod.apply(n, arguments) : n.queue.push(arguments)
				};

				if (!f._fbq) f._fbq = n;
				n.push = n;
				n.loaded = !0;
				n.version = '2.0';

				n.queue = [];
				t = b.createElement(e);
				t.async = !0;

				t.src = v;
				s = b.getElementsByTagName(e)[0];

				s.parentNode.insertBefore(t, s)
			}(window, document, 'script',

				'https://connect.facebook.net/en_US/fbevents.js');


			fbq('init', '243859349395737');

			fbq('track', 'PageView');
		</script>

		<noscript>

			<img height="1" width="1" src="https://www.facebook.com/tr?id=243859349395737&ev=PageView

&noscript=1" />

		</noscript>

		<!-- End Facebook Pixel Code -->

		<div class="l-page" id="site_wrap" style="width: 100%; max-width: 100%; overflow-x: hidden">
			<?php get_template_part('template-parts/header/header'); ?>