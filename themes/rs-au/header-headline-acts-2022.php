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
		html {
			scroll-behavior: smooth;
		}

		@font-face {
			font-family: 'Olive Village';
			src: url('<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/OliveVillage-Regular.eot');
			src: url('<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/OliveVillage-Regular.eot?#iefix') format('embedded-opentype'),
				url('<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/OliveVillage-Regular.woff2') format('woff2'),
				url('<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/OliveVillage-Regular.woff') format('woff'),
				url('<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/OliveVillage-Regular.ttf') format('truetype'),
				url('<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/OliveVillage-Regular.svg#OliveVillage-Regular') format('svg');
			font-weight: normal;
			font-style: normal;
			font-display: swap;
		}

		@font-face {
			font-family: 'Recoleta';
			src: url('<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/Recoleta-Medium.eot');
			src: url('<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/Recoleta-Medium.eot?#iefix') format('embedded-opentype'),
				url('<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/Recoleta-Medium.woff2') format('woff2'),
				url('<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/Recoleta-Medium.woff') format('woff'),
				url('<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/Recoleta-Medium.ttf') format('truetype'),
				url('<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/Recoleta-Medium.svg#Recoleta-Medium') format('svg');
			font-weight: 500;
			font-style: normal;
			font-display: swap;
		}

		.font-primary {
			font-family: "Olive Village", sans-serif;
		}

		.font-secondary {
			font-family: "Recoleta", sans-serif;
		}


		.l-page {
			color: #3B3836;
		}

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

		.justify-content-between {
			justify-content: space-between;
		}

		.align-items-start {
			align-items: flex-start !important;
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

		<?php endforeach; ?>.w-100 {
			width: 100%;
		}

		.text-uppercase {
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

		.content-wrap {
			max-width: 75rem;
		}

		.l-header__content {
			min-width: 0;
		}

		.btn {
			font-family: Graphik-Medium, sans-serif;
			outline: none;
			padding: .25rem .5rem;
			border: none;
			cursor: pointer;
			text-align: center;
			font-weight: bold;
			transition: .25s all linear;
			border: 2px solid rgb(233, 69, 5);
			color: rgb(233, 69, 5);
			border-radius: 0 !important;
			display: inline-block;
			font-size: 150%;
			letter-spacing: 1px;
			background-image: linear-gradient(to top, rgb(233, 69, 5) 50%, transparent 50%);
			background-size: 100% 200%;
			background-position: top;
			transition: all 0.25s ease-in-out;
		}

		.btn:hover {
			background-position: bottom;
			color: #fff;
		}

		.btn.btn-tickets {
			padding-left: 3rem;
			padding-right: 3rem;
			padding-top: .75rem;
			padding-bottom: .75rem;
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


			.text-md-left {
				text-align: left !important;
			}

			.text-md-right {
				text-align: right !important;
			}

			.btn {
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

			<?php foreach (range(0, 4) as $i) : ?>.m-md-<?php echo $i; ?> {
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
		?>@media(min-width: 48rem) {
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
			width: 100%;
			margin-left: auto;
			margin-right: auto;
		}

		.content-wrap {
			width: 100%;
		}

		.l-footer__wrap {
			max-width: 1000px;
		}

		body {
			background-color: #fff;
		}

		.bg-semi-dark {
			background-color: rgba(0, 0, 0, .5);
			color: #fff;
		}

		.l-header__wrap,
		.intro,
		.shows {
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;

			position: relative;
		}

		.l-header__wrap .content,
		.intro .content {
			z-index: 2;
		}

		.l-header__wrap:after {
			z-index: -1;
		}

		.l-header__wrap {
			background-color: #fff;
			transition: .25s all linear;
			position: relative;
			text-align: center;
			padding: 1rem;
		}

		.l-header__wrap .l-header__content {
			display: flex;
			flex-direction: column;
			align-items: center;
			padding: 2rem 0;
		}

		.intro {
			/* border: 1rem solid #fff; */
			background-image: url(<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/bg-header.jpg);
			position: relative;
			background-position: center bottom;
			width: 100%;
			/* padding-top: 7rem;
			padding-bottom: 7rem; */
			/* height: 100vh; */
			color: #fff;
		}

		.header-drink-smart-wrap {
			/* position: absolute;
			bottom: 1rem;
			left: 1rem; */
		}

		.header-drink-smart {
			filter: brightness(0) invert(1);
		}

		.img-header {
			width: 80%;
			max-width: 100%;
		}

		.about .content-wrap,
		.news .content-wrap {
			font-size: 170%;
			line-height: 150%;
			font-family: Graphik-Medium, sans-serif;
		}

		.latest-news {
			background-color: #2A273D;
			color: #fff;
		}

		.features {
			background-color: #fff;
			color: #000;
		}

		.about .content-wrap {
			width: 900px;
			max-width: 100%;
			margin-left: auto;
			margin-right: auto;
			text-align: center;
		}

		.shows {
			color: #fff;
			padding: 4rem 2rem;
			text-align: center;
			justify-content: flex-end;
			font-family: Graphik, sans-serif;
			background-position: top;
			background-size: contain;
			background-repeat: repeat;
			background-image: url(<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/free-black-paper-texture.jpg);
		}

		.shows img {
			filter: grayscale(1);
		}

		.shows h3 {
			font-weight: bold;
			font-size: 150%;
			margin: 1rem auto .5rem;
		}

		.latest-news .news {
			display: block;
			background-color: #fff;
			padding: 1rem;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
			color: #000;
		}

		.latest-news .news h4 {
			font-size: 150%;
			line-height: 125%;
			font-family: Graphik, sans-serif;
			font-weight: bold;
			margin: .5rem auto;
		}

		.latest-news .news h4.side {
			font-size: 100%;
			line-height: 130%;
		}

		.latest-news .news p {
			font-family: Graphik-Medium, sans-serif;
		}

		.previous-events {
			border-top: .5rem solid #baab8f;
			padding: 1.5rem;
			background-color: #000;
		}

		.previous-events h3.heading {
			font-family: Georgia, Times, Times New Roman, serif;
			text-align: center;
			color: #fff;
			font-size: 200%;
			margin: 0 auto 1.5rem auto;
		}

		.previous-events .img-wrap {
			position: relative;
		}

		.previous-events .img-wrap::after {
			content: "";
			display: block;
			position: absolute;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			background-color: rgba(0, 0, 0, .7);
		}

		.l-header__wrap .text,
		.intro .text {
			max-width: 100%;
			text-align: center;
			font-weight: bold;
			font-size: 120%;
			line-height: 150%;
		}

		.l-header__wrap.scrolled {
			background: linear-gradient(to bottom, #000, transparent);
			position: relative;
			height: 80px;
		}

		.l-header__wrap .l-header__branding {
			padding: 1rem .5rem;
		}

		.l-header__wrap .l-header__branding a {
			color: #fff;
			font-size: .9rem;
		}

		.l-header__wrap .l-header__branding a span {
			font-size: .85rem;
		}

		.l-header__wrap .l-header__branding img {
			transition: .25s all linear;
		}

		.l-header__wrap.scrolled .l-header__branding {
			padding: .25rem;
		}

		.l-header__wrap.scrolled .l-header__branding img {
			width: 9rem;
			top: 0;
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

		.news-links .col-md-6 {
			padding: .5rem;
		}

		.news-links a {
			display: flex;
		}

		.news-links a .img-wrap {
			width: 120px;
			overflow: hidden;
			flex: 1 0 auto;
			margin-right: 1rem;
			position: relative;
			height: 100px !important;
		}

		.news-links a img {
			height: 100% !important;
			width: auto;
			max-width: none;
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
		}

		.l-header__nav {
			width: auto !important;
			height: auto !important;
		}

		#content-wrap {
			max-width: none;
		}

		#content-wrap section {
			max-width: 1000px;
			margin: auto;
		}

		.subheading {
			background-color: #fff;
			text-align: center;
			padding: .25rem .5rem;
			font-family: Graphik, sans-serif;
			text-transform: uppercase;
			font-weight: bold;
			letter-spacing: 1px;
			padding-top: 1rem;
			position: relative;
		}

		.subheading:before {
			content: "";
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			background-color: #000;
			height: .5rem;
			border-top: 1px solid #fff;
		}

		.gigs {
			margin-top: 2rem;
		}

		.gig-item {
			padding: 1rem;

			font-family: Graphik, sans-serif;
			text-align: center;
			font-weight: bold;

			background-color: #fff;
		}


		.gig-item .gig-info {
			padding: 1rem;
			margin: .5rem auto;
			border: 1px solid rgba(0, 0, 0, .5);
			text-transform: uppercase;
		}

		.gig-item .gig-info .city {
			color: #df3535;
			font-size: 2rem;
			line-height: 2.5rem;
		}

		@media (max-width: 40rem) {
			#content-wrap {
				background-position: center;
				background-size: contain;
			}
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

		.feature-image {
			width: 75%;
		}

		@media (min-width: 60rem) {
			.intro {
				background-position: center;
			}

			.l-header__wrap .text,
			.intro .text {
				width: 55%;
			}

			.l-header__wrap .l-header__branding a {
				font-size: 1.5rem;
			}

			.l-header__wrap .l-header__branding a span {
				font-size: 1rem;
			}

			.l-header,
			.l-header__content,
			.l-header__wrap {
				height: auto;
			}
		}

		.header-sub-text {
			color: #fff;
			text-align: center;
			font-family: Graphik, sans-serif;
			letter-spacing: .05rem;
		}

		.rsa-header {
			background-color: #000;
		}


		@media (max-width: 59.9375rem) {
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

		.l-header,
		.l-header__wrap {
			height: auto;
		}

		header {
			top: 0;
			left: 0;
			right: 0;
			z-index: 2;
		}

		.rsa-header-top .rsa-header-top-red {
			background-color: #CD232A;
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

		.para-1,
		.para-2,
		.para-3 {
			font-family: Graphik, sans-serif;

			color: #fff;
			text-transform: uppercase;

			text-align: center;
			font-weight: bold;
			letter-spacing: 1px;
		}

		.news-pieces .news-piece {
			margin: 1rem 0;
		}

		.news-pieces a {
			display: block;
			background-color: rgba(255, 255, 255, 0.15);
			height: 100%;
			margin: 1rem;
		}

		.news-pieces a .img-wrap {
			width: 100%;
			height: 0;
			padding-bottom: 52%;
			overflow-y: hidden;
			position: relative;
		}

		.news-pieces a .img-wrap img {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
		}

		.news-pieces a .link-text {
			margin: 1rem 1rem 0;
			color: #fff !important
		}

		.artist-img-wrap {
			line-height: 0;
		}


		@media (min-width: 48rem) {

			.para-1,
			.para-2,
			.para-3 {
				width: 75%;
				margin: auto;
			}

			.para-2 {
				float: right;
			}

			.para-3 {
				float: left;
				margin-left: 7rem;
			}
		}

		.text-white {
			color: #fff;
		}

		.text-gold {
			color: #AD721D;
		}

		.btn-city {
			border: 3px solid rgb(227, 175, 62);
			padding: .5rem 2rem;
			background-color: #fff;
			color: rgb(233, 69, 5);
			margin: .25rem auto;
			display: block;
			max-width: 100%;
			width: 200px;
			font-size: 1.3rem;
		}

		.upcoming-events,
		.welcome-sessions,
		.spotify-embed,
		.bg-gray {
			background-color: rgb(235, 235, 235);
		}

		.event-wrap {
			background-color: #fff;
			line-height: 0;
			padding: 1rem;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
		}

		.event {
			position: relative;
		}

		.event .city {
			position: absolute;
		}

		.event .city {
			left: 2rem;
			bottom: 3rem;
			font-size: 300%;
			text-shadow: 1px 1px 10px #fff;
		}

		.event.event-brisbane .city,
		.event.event-melbourne .city {
			/* left: 1rem;
			top: 2rem;
			font-size: 250%; */
		}

		.event-wrap .info {
			margin-top: .5rem;
			text-align: center;
			padding-bottom: 1rem;
			line-height: 125%;
			font-family: Graphik-Medium;
		}

		.btn-signup {
			background-color: #AD721D;
			font-family: Graphik-Medium, sans-serif;
			display: inline-block;
			padding: .75rem;
			color: #fff;
			text-align: center;
		}

		.win-tickets-wrap {
			font-family: Graphik-Medium, sans-serif;
		}

		h4.win-tickets {
			font-size: 200%;
			line-height: 150%;
			font-weight: bold;
			margin: .5rem auto 1rem;
			display: inline-block;
			border-bottom: 3px solid rgb(189, 0, 33);
		}

		.text-red {
			color: rgb(189, 0, 33);
		}

		.age-gate #age-gate-rememberme {
			display: none;
		}

		.age-gate #age-gate-rememberme+label {
			display: inline-block;
			position: relative;
			padding-left: 30px;
			line-height: 30px;
			cursor: pointer;
			top: 0;
			height: 0;
			transform: translateY(-20px);
			margin: 0;
		}

		.age-gate #age-gate-rememberme:checked+label:before {
			background-color: rgb(233, 69, 5);
		}

		.age-gate #age-gate-rememberme+label:before {
			position: absolute;
			left: 0;
			top: 2px;
			content: "";
			border-radius: 2px;
			display: inline-block;
			width: 25px;
			height: 25px;
			margin-right: 5px;
			vertical-align: bottom;
			transition: all .15s ease-in-out;
			background-color: rgb(233, 69, 5);
		}

		.age-gate #age-gate-rememberme:checked+label:after {
			transform: rotate(45deg) scale(1);
		}

		.age-gate #age-gate-rememberme+label:after {
			position: absolute;
			top: 7px;
			left: 9px;
			content: "";
			display: block;
			width: 7px;
			height: 12px;
			border: solid #fff8e1;
			border-width: 0 3px 3px 0;
			transform: rotate(45deg) scale(0);
			transition: all 80ms ease-in-out;
		}

		.age-gate .age-gate-rememberme-wrapper p {
			display: inline;
		}

		.text-all-together,
		.text-for-the,
		.text-music {
			line-height: 100%;
		}

		.text-all-together {
			font-size: 500%;
		}

		.text-for-the {
			font-size: 400%;
			text-decoration: underline
		}

		.text-music {
			font-size: 700%;
			/* margin: 0; */
			margin: auto .75rem;
		}

		.img-header-wrap,
		.text-header {
			text-align: center;
		}

		.age-gate__input {
			display: block;
			flex-grow: 1;
			width: 100%;
			max-width: 100px;
			-webkit-appearance: none;
			outline: none;
			border-radius: 0;
			padding: 5px;
			font-size: 24px;
			color: #222 !important;
			text-transform: uppercase;
			text-align: center;
			border: 0;
			border-bottom: 2px solid #efefef;
			margin: 5px;
			font-weight: 300;
			font-family: 'Recoleta', sans-serif;
		}

		.age-gate__input::placeholder {
			color: #bbb;
		}

		#agegate .agegate_validation {
			color: #cc0033;
			display: none;
			font-size: 12px;
			height: 14px;
			margin-top: 20px;
		}

		#agegate .agegate_step2 {
			display: none;
		}

		.js-step2-submit {
			display: block;
			background: rgb(233, 69, 5);
			border: 1px solid rgb(233, 69, 5);
			color: #fff;
			padding: .75rem 1rem;
			font-family: Graphik-Medium;
		}

		@media (min-width: 48rem) {
			.age-gate__input {
				padding: 10px;
			}
		}

		@media (min-width: 60rem) {
			.text-all-together {
				font-size: 550%;
				line-height: 70%;
			}

			.text-for-the {
				font-size: 500%;

			}

			.text-music {
				font-size: 900%;
			}

			.img-header-wrap {
				text-align: right;
			}

			.text-header {
				text-align: left;
			}
		}

		.no-js #age-gate-year {
			display: none;
		}

		.video-placehoder-wrap {
			/* border: 1px solid #3B3836; */
			font-family: Graphik-Medium, sans-serif;
		}

		.video-placehoder-wrap h4 {
			font-size: 2rem;
		}

		.ls-1 {
			letter-spacing: 1px;
		}

		.btn-watch-now {
			background-color: #3B3836;
			color: #fff;
			border-radius: 6.25rem;
			padding: .75rem 3rem;
			transition: .25s all linear;
			margin-bottom: -1.5rem;
		}

		.btn-watch-now:hover {
			background-color: rgb(213, 30, 41);
		}

		.video-wrap {
			position: fixed;
			top: 50%;
			left: 50%;
			z-index: 30000;
			transform: translate(-50%, -50%);
			width: calc(100% - 2rem);
			background-color: #fff;
			border-radius: .25rem;
			font-family: Graphik-Medium, sans-serif;
			padding: 2rem 1rem;
		}

		.video-wrap .btn-close-video {
			position: absolute;
			top: -.5rem;
			right: -.5rem;
			padding: .5rem;
			background-color: #ccc;
			border-radius: 50%;
			line-height: 0;
		}

		.video-wrap .btn-close-video img {
			width: 1rem;
			height: 1rem;
		}

		@media(min-width: 48rem) {
			.video-wrap {
				max-width: 40rem;
			}
		}

		#page-overlay {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: rgba(0, 0, 0, .75);
			z-index: 3000;
		}

		.header-logo {
			/* position: absolute;
			top: 1rem;
			left: 1rem; */
			width: 30%;
			padding: .5rem;
		}

		.header-logo img {
			filter: brightness(0) invert(1);
		}

		.header-menu {
			display: flex;
			justify-content: flex-end;
			/* position: absolute;
			top: .5rem;
			right: .5rem; */
			margin: .5rem;
		}

		.header-menu ul,
		.header-menu ul li {
			list-style: none;
		}

		.header-menu ul {
			display: flex;
		}

		.header-menu ul li a {
			display: block;
			padding: .25rem .5rem;
			color: #fff;
			font-family: 'Recoleta', sans-serif;
			font-size: 1rem;
		}

		.header-rs-logo-wrap {
			padding: 1rem;
		}

		.about .heading,
		.latest-news .heading,
		.features .heading {
			font-family: "Olive Village", sans-serif;
			font-size: 2rem;
			line-height: 125%;
		}

		.l-footer {
			background: top center no-repeat #382240;
			background-image: url(<?php echo get_template_directory_uri(); ?>/images/headline-acts-2022/footer-bg.svg);
			background-size: cover;
		}

		.l-footer__wrap {
			padding-left: 0 !important;
		}

		.l-footer__cover {
			display: none !important;
		}

		@media (min-width: 48rem) {
			.intro {
				/* min-height: 90vh; */
				justify-content: space-between;
			}

			.header-logo {
				padding: 3rem 0 0 3rem;
				width: 45%;
			}

			.header-menu {
				margin: 1rem;
			}

			.header-menu ul li a {
				padding: .5rem 1rem;
				font-size: 1.3rem;
			}

			.header-rs-logo-wrap {
				padding: 3rem;
			}

			.about .heading,
			.latest-news .heading,
			.features .heading {
				font-family: "Olive Village", sans-serif;
				font-size: 4rem;

			}
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

	<script>
		jQuery(document).ready(function($) {
			var $dob_day;
			var $dob_month;
			var AGE_ALLOWED = 18;

			var $errorMessage = jQuery(".agegate_validation");
			var $form = jQuery("form[name=agegate]");

			var $dob_day = jQuery("[name=dob_day]");
			var $dob_month = jQuery("[name=dob_month]");
			var $dob_year = jQuery("[name=dob_year]");
			var $submit = jQuery(".js-step2-submit");

			$step1 = jQuery(".js-agegate_step1");
			$step2 = jQuery(".js-agegate_step2");

			$('#age-gate-year').on('keyup', function() {
				var agegateYear = $(this).val();
				if (agegateYear.toLocaleString().length == 4) {
					var currentYear = new Date().getFullYear();
					var hasLegalAgeValue = hasLegalAge(agegateYear, currentYear);
					var isValidYearValue = isValidYear(agegateYear, currentYear);
					var isAmbiguousValue = isAmbiguous(agegateYear, currentYear);

					if (!isValidYearValue) {
						setInvalidYearErrorMessage();
					} else if (!hasLegalAgeValue) {
						setUnderageErrorMessage();
						$dob_year.val(agegateYear);
						// delaySubmit({
						// 	timeout: 300,
						// });
					}

					if (!hasLegalAgeValue || !isValidYearValue) {
						showError();
					} else if (isAmbiguousValue) {
						$dob_year.val(currentYear - 18);
						$('#age-gate-year').blur();
						setTimeout(function() {
							showFullForm();
						}, 50);
					} else {
						$dob_year.val(agegateYear);
						hideError();
						delaySubmit({
							timeout: 300,
						});
					}
				}
			});

			$form.on("submit", function(e) {
				// e.preventDefault();

				var hasError = false;
				if ($step2.is(":visible")) {
					if (
						$dob_day.val() === "" ||
						$dob_month.val() === "" ||
						$dob_year.val() === "" ||
						!validateDobDay($dob_day.val()) ||
						!validateDobMonth($dob_month.val())
					) {
						setInvalidBirthDateErrorMessage();
						showError();
						e.preventDefault();
						hasError = true;
					} else if (
						!validateDob(
							$dob_day.val(),
							$dob_month.val(),
							$dob_year.val()
						)
					) {
						setUnderageErrorMessage();
						showError();
						hasError = true;
						e.preventDefault();
					} else {
						hideError();
					}
				}
				// if (!hasError && typeof beforeSubmit === "function") {
				// 	beforeSubmit(e);
				// }
			});

			var hasLegalAge = function hasLegalAge(birthYear, currentYear) {
				return currentYear - birthYear >= AGE_ALLOWED;
			};

			var isValidYear = function isValidYear(birthYear, currentYear) {
				var isValid = currentYear - birthYear <= 122;
				isValid = isValid && currentYear - birthYear >= 0;
				return isValid;
			};

			var isAmbiguous = function isAmbiguous(birthYear, currentYear) {
				return currentYear - birthYear === AGE_ALLOWED;
			};

			var setInvalidYearErrorMessage =
				function setInvalidYearErrorMessage() {
					setErrorMessage($errorMessage.data("invalid"));
				};
			var setUnderageErrorMessage = function setUnderageErrorMessage() {
				setErrorMessage($errorMessage.data("underage"));
			};
			var setErrorMessage = function setErrorMessage(message) {
				$errorMessage.text(message);
			};

			var delaySubmit = function delaySubmit(param) {
				setTimeout(function() {
					// $form.find('[type="submit"]').eq(0).trigger("click");
					$form.trigger('submit');
				}, param.timeout);
			};

			var showError = function showError() {
				$errorMessage.show();
			};
			var hideError = function hideError() {
				$errorMessage.text("").hide();
			};

			var showFullForm = function showFullForm() {
				$step1.hide();
				$step2.show();
				$errorMessage.addClass("agegate_v2");
				$dob_day.focus();
			};

			var setInvalidBirthDateErrorMessage =
				function setInvalidBirthDateErrorMessage() {
					setErrorMessage($errorMessage.data("invalid-birth-date"));
				};
			var validateDobDay = function validateDobDay(day) {
				return day >= 1 && day <= 31;
			};
			var validateDobMonth = function validateDobMonth(month) {
				return month >= 1 && month <= 12;
			};
			var validateDob = function validateDob(day, month, year) {
				var d = new Date();
				d.setUTCDate(day);
				d.setUTCMonth(month - 1);
				d.setUTCFullYear(year);
				var comparisionDate = new Date();
				comparisionDate.setFullYear(
					comparisionDate.getFullYear() - AGE_ALLOWED
				);
				return d.getTime() <= comparisionDate.getTime();
			};
		});
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
			! function(f, b, e, v, n, t, s) {
				if (f.fbq) return;
				n = f.fbq = function() {
					n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments)
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
			}(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '243859349395737');
			fbq('track', 'PageView');
		</script>

		<noscript>
			<img height="1" width="1" src="https://www.facebook.com/tr?id=243859349395737&ev=PageView&noscript=1" />
		</noscript>


		<!-- End Facebook Pixel Code -->