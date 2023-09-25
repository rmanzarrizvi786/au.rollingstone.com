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
		@font-face {
			font-family: 'GraphikXXCondensed';
			src: url('<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/GraphikXXCondensed-Bold.otf') format('truetype');
			font-weight: normal;
			font-style: normal;
			font-display: swap;
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

		.content-wrap {
			max-width: 75rem;
		}

		.btn {
			font-family: Graphik, sans-serif;
			outline: none;
			padding: .25rem .5rem;
			border: none;
			cursor: pointer;
			text-align: center;
			font-weight: bold;
			transition: .25s all linear;
			border: 2px solid #baab8f;
			color: #baab8f;
			border-radius: 0 !important;
			display: inline-block;
			font-family: 'GraphikXXCondensed';
			font-size: 150%;
			letter-spacing: 2px;
			/* background-color: #000; */
			background-image: linear-gradient(to top, #baab8f 50%, transparent 50%);
			background-size: 100% 200%;
			background-position: top;
			transition: all 0.25s ease-in-out;
		}

		.btn:hover {
			background-position: bottom;
			/* background-color: #baab8f; */
			color: #000;
		}

		.btn.btn-tickets {
			padding-left: 3rem;
			padding-right: 3rem;
			padding-top: .5rem;
			padding-bottom: .5rem;
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
		?>@media(min-width: 48rem) {
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
			/* max-width: 1000px; */
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
			/* font-family: "Poppins", sans-serif !important; */
			background-color: #000;
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

		.l-header__wrap:after,
		.intro:after {
			content: "";
			position: absolute;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			/* background-color: rgba(0, 0, 0, .5); */
			background: linear-gradient(to top, rgba(0, 0, 0, .85) 0%, rgba(0, 0, 0, .25) 100%);
			z-index: 1;
		}

		.l-header__wrap:after {
			z-index: -1;
		}

		.l-header__wrap {
			background-color: transparent;
			transition: .25s all linear;
			position: relative;
			background-image: url(<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/feature-image.jpg);
			color: #fff;
			text-align: center;
			padding: 1rem 1rem 3rem 1rem;
		}

		.l-header__wrap .l-header__content,
		.intro {
			display: flex;
			flex-direction: column;
			align-items: center;
			padding: 2rem;
		}

		.intro {
			justify-content: flex-end;
			border: 1rem solid #fff;
			min-height: 100vh;
			background-image: url(<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/ruby-fields-live-ep.jpg);
		}

		@media (min-height: 1200px) {
			.intro {
				min-height: 600px !important;
			}

			/* .shows {
				min-height: 0 !important;
			} */
		}

		.shows {
			color: #fff;
			padding: 4rem 2rem;
			text-align: center;
			justify-content: flex-end;
			/* min-height: 100vh; */
			font-family: Graphik, sans-serif;
			background-position: top;
			background-size: contain;
			background-repeat: repeat;
			background-image: url(<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/free-black-paper-texture.jpg);
		}

		.shows img {
			filter: grayscale(1);
		}

		.shows h2,
		.latest-news h2 {
			font-family: Georgia, Times, Times New Roman, serif;
			font-weight: bold;
			font-size: 300%;
			line-height: 175%;
		}

		.shows h3 {
			font-weight: bold;
			font-size: 150%;
			margin: 1rem auto .5rem;
		}

		.latest-news {
			background-color: #fff;
			min-height: 100vh;
			/* padding: 4rem 2rem; */
		}

		.latest-news h2 {
			border-top: 3px solid #baab8f;
			/* padding-top: 1.5rem; */
		}

		.latest-news .news {
			display: block;
			background-color: #fff;
			padding: 1rem;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
			color: #000;
		}

		.latest-news .news p {
			font-style: italic;
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
			color: #fff;
			max-width: 100%;
			text-align: center;
			font-weight: bold;
			font-size: 120%;
			line-height: 150%;
		}

		.l-header__wrap.scrolled {
			/* background-color: rgba(0, 0, 0, 0.5); */
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

		.l-header__branding img {
			filter: brightness(0) invert(1);
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
			/* margin-top: 3.5rem; */
			max-width: none;
			/* background-size: cover;
			background-position: center top;
			background-repeat: repeat-y;
			background-image: url(<?php echo get_template_directory_uri(); ?>/images/sj-road-to-rs/RoadToAwards_road-2.jpg); */
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
			/* min-height: 2.75rem; */
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
			/* background-color: #fff; */
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

		.feature-image {
			width: 55%;
		}

		@media (min-width: 60rem) {

			.intro {
				border-width: 3rem;
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

			.l-header__wrap.scrolled .l-header__branding img {
				width: 12rem;
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


		@media (min-width: 60rem) {}

		@media (max-width: 59.9375rem) {


			.rsa-header {
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

		@media (max-width: 23.4375rem) {}

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

			<img height="1" width="1" src="https://www.facebook.com/tr?id=243859349395737&ev=PageView&noscript=1" />

		</noscript>

		<!-- End Facebook Pixel Code -->