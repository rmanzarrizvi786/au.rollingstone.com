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

		.justify-content-start {
			justify-content: flex-start;
		}

		.align-items-start {
			align-items: flex-start;
		}

		.align-items-end {
			align-items: flex-end;
		}

		.align-items-stretch {
			align-items: stretch;
		}

		.flex-wrap {
			flex-wrap: wrap;
		}

		.flex-nowrap {
			flex-wrap: nowrap;
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
			.text-md-right {
				text-align: right !important;
			}

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
			/* border-radius: .5rem;
    color: #fff;
    background: linear-gradient(-45deg, #d32531 50%, rgba(211, 37, 49, 0.5) 50%); */
		}

		.how-to-nominate {
			/* background: url(<?php echo get_template_directory_uri(); ?>/images/rsa2022/bg-htn.jpg) repeat; */
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

		#content-wrap,
		.content-wrap {
			position: relative;
			/* margin-top: .75rem; */
			width: 100%;
			max-width: 1000px;
			margin-left: auto;
			margin-right: auto;
		}

		@font-face {
			font-family: GraphikXCondensed-BoldItalic;
			src: url("<?php echo tbm_cdn; ?>/assets/fonts/Graphik/GraphikXCondensed-BoldItalic.otf") format("opentype");
			font-display: block;
		}

		.content-wrap {
			width: 100%;
		}

		.l-footer__wrap {
			max-width: 1000px;
		}

		/* #content-wrap:before,
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

		#content-wrap:before {
			left: -340px;
			background-image: url(<?php echo get_template_directory_uri(); ?>/images/rsa2022/left-red.jpg);
		}

		#content-wrap:after {
			right: -340px;
			background-image: url(<?php echo get_template_directory_uri(); ?>/images/rsa2022/right-red.jpg);
		} */


		body {
			/* font-family: "Poppins", sans-serif !important; */
			background-color: #fff;
			font-family: Graphik, sans-serif;
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

/*		.news-links .col-md-6 {
			padding: .5rem;
		}

		.news-links a {
			display: flex;
		}

		.news-links a .img-wrap {
			width: 120px;
			overflow: hidden;
			flex: 1 0 auto;
			border-radius: .25rem;
			margin-right: 1rem;
			position: relative;
			height: 100px !important;
		}

		.news-links a img {
			height: 100% !important;
			width: auto;
			max-width: none;
			border-radius: .25rem;
			margin-right: 1rem;

			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
		}

		.news-links a,
		.news-links a:visited {
			color: #df3535;
			text-decoration: none;
			transition: .25s all linear;
		}

		.news-links a:hover {
			color: #000;
		}*/

		.l-header__nav {
			width: auto !important;
			height: auto !important;
		}

		@media (max-width: 59.9375rem) {
			.l-header__menu {
				display: flex;
				flex-direction: column;
				width: 100%;
				border: 1px solid #777;
			}

			.l-header__menu:after {
				content: ">";
				transform: rotate(90deg);
				position: absolute;
				top: .55rem;
				right: 1rem;
				color: #d32531;
				font-size: 150%;
				z-index: -1;
			}

			.l-header__menu li {
				display: none;
				width: 100%;
				padding: .5rem 1rem;
				text-align: center;
			}

			.l-header__menu li a {
				display: block;
				width: 100%;
				text-align: center;
				color: #333 !important;
			}

			.l-header__menu li.active,
			.l-header__menu li.show {
				display: inherit;
			}

			.l-header__menu li.active a {
				color: #d32531 !important;
			}

			#content-wrap {
				/* margin-top: 3.5rem; */
			}

			.l-header__wrap {
				height: 6.5rem;
			}

			.l-header__wrap.active {
				height: 12rem;
			}

			.l-header__nav {
				position: relative;
			}

			.l-header__block--right {
				top: 1rem !important;
			}
		}

		.l-header__content--sticky {
			overflow-x: hidden;
		}

		@media (min-width: 60rem) {

			.l-header,
			.l-header__content,
			.l-header__wrap {
				height: 5rem;
			}
		}

		.rsa-header-news {
			background-color: #CD232A;
			background-size: cover;
			background-position: center bottom;
			background-repeat: repeat-x;
			position: relative;
			background-image: url(<?php echo get_template_directory_uri(); ?>/images/rsa2022/TopTornPaper.png);
		}

		.rsa-header-news:before {
			content: "";
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 100px;
			background-color: #fdfcfc;
		}

		.header-sub-text {
			color: #fff;
			text-align: center;
			font-family: Graphik, sans-serif;
			letter-spacing: .05rem;
		}

		.btn-vote-header {
			background-color: #000;
			color: #fff;
			font-family: Graphik, sans-serif;
			padding: .75rem 2rem;
			margin: .5rem auto 1rem auto;
			display: block;
			position: relative;
			font-size: 1.5rem;
		}

		.btn-vote-header:after {
			content: "";
			position: absolute;
			bottom: -.25rem;
			background-color: #000;
			height: 2px;
			left: 0;
			right: 0;
		}


		@media (min-width: 60rem) {
			.rsa-header-news {
				min-height: 45rem;
			}

			.rsa-header {
				margin-top: 72px;
			}
		}

		@media (max-width: 59.9375rem) {

			.rsa-header-news {
				min-height: 35rem;
			}

			.rsa-header {
				margin-top: 130px;
				background-position: center -10vh;
			}

			.l-header__wrap {
				height: auto;
			}

			.rsa-header-left,
			.rsa-header-right {
				display: none;
			}

			.l-header__menu {
				flex-direction: row !important
			}

			.l-header__menu li {
				width: auto !important;
			}
		}

		@media (max-width: 23.4375rem) {
			.rsa-header {
				/* min-height: 25rem; */
			}
		}

		.l-header,
		.l-header__wrap {
			height: auto;
		}

		/*header {
			position: fixed !important;
			top: 0;
			left: 0;
			right: 0;
			z-index: 2;
		}*/

		.rsa-header-top .rsa-header-top-red {
			background-color: #CD232A;
			/* height: 2rem; */
		}

		.rsa-header-top .rsa-header-top-white {
			background-color: #fff;
			height: .25rem;
			border-bottom: 2px solid #CD232A;
		}

		.l-header__menu li {
			display: inline-block !important;
		}

		.l-header__menu li a,
		.l-header__menu-link {
			color: #fff !important;
		}

		.l-header__menu li.active a,
		.l-header__menu-link:hover {
			color: #000 !important;
		}

		.l-header__menu li.active a {
			font-weight: bold;
			letter-spacing: 1px;
		}

		#wpadminbar {
			display: none;
		}

		.news-links .col-md-6 {
			padding: .5rem;
		}

		.news-links a {
			color: #df3535;
			background-color: #fff;
			padding: 1rem;
			box-shadow: 0 0 10px rgb(0 0 0 / 25%);
			color: #000;
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

	<script>
		jQuery(document).ready(function($) {
			$('.l-header__menu li.active a').on('click', function(e) {
				e.preventDefault();
				$(this).closest('.l-header__menu').find('li').toggleClass('show');
				$('.l-header__wrap').toggleClass('active');
			})
		})
	</script>

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
			<?php get_template_part('template-parts/header/header', 'rsawards-2023'); ?>