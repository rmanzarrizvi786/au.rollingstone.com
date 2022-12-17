<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<head>
	<meta charset="<?php echo esc_attr(get_bloginfo('charset')); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<link rel="manifest" href="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/manifest.json'); ?>">

	<!-- Responsiveness -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Browser shell -->
	<meta name="theme-color" content="#df3535">

	<!-- Add to home screen for iOS -->
	<meta name="apple-mobile-web-app-title" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<link rel="apple-touch-icon" href="https://www.rollingstone.com/wp-content/uploads/2022/08/cropped-Rolling-Stone-Favicon.png?w=180" />

	<!-- Tile icons for Windows -->
	<meta name="msapplication-config" content="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/browserconfig.xml'); ?>">
	<meta name="msapplication-TileImage" content="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/icons/mstile-144x144.png'); ?>">
	<meta name="msapplication-TileColor" content="#eff4ff">

	<!-- Favicons -->
	<link rel="icon" href="https://www.rollingstone.com/wp-content/uploads/2022/08/cropped-Rolling-Stone-Favicon.png?w=32" sizes="32x32" />
	<link rel="icon" href="https://www.rollingstone.com/wp-content/uploads/2022/08/cropped-Rolling-Stone-Favicon.png?w=192" sizes="192x192" />

	<!-- Safari pin icon -->
	<link rel="mask-icon" href="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/icons/safari-pinned-tab.svg'); ?>" color="#000000">

	<!-- Titles -->
	<meta name="apple-mobile-web-app-title" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
	<meta name="application-name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
	<meta name="description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
	<!-- Titles:end -->

	<meta property="fb:pages" content="203538151294" />

	<?php if (is_single()) { ?>
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="@rollingstoneaus">
		<meta name="twitter:title" content="<?php the_title(); ?>">
		<meta name="twitter:image" content="<?php
			if (has_post_thumbnail()) {
				$src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
				$type = exif_imagetype($src[0]);

				if ($type != false && ($type == (IMAGETYPE_PNG || IMAGETYPE_JPEG))) {
					echo ("https://app.thebrag.com/img-socl/?url={$src[0]}&nologo=1");
				} else {
					echo $src[0];
				}
			}
		?>">
	<?php } // If single ?>

	<?php do_action('pmc_tags_head'); ?>

	<?php wp_head(); ?>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

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
		body {
			font-family: 'Graphik', sans-serif;
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

		html { margin-top: 0 !important; }
		@media screen and ( max-width: 782px ) {
			html { margin-top: 0 !important; }
		}

		.competition-banner {
			width: 100%; 
			max-height: 538px;
		}

		.competition-banner_inner {
			width: 100%; 
/*			height: 538px; */
			max-height: 538px;
			background-image: url('https://cdn.thebrag.com/pages/ttnq-competition-2022/banner-bg.jpg');
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
		}

		.competition-banner_logo {
			width: 443px;
			height: 376px; 
			background-image: url('https://cdn.thebrag.com/pages/ttnq-competition-2022/banner-logo2.png');
			background-position: center;
			background-size: contain;
			background-repeat: no-repeat;
		}

		.competition-logo {
			background-image: url('https://cdn.thebrag.com/pages/ttnq-competition-2022/logo-min.png');
			background-position: center;
			background-repeat: no-repeat;
			background-size: contain;
			width: 100%; 
			height: 114px;
		}

		[data-fuse] {
  			display: none;
		}

		.competition-heading {
			font-family: 'Publico', sans-serif;
			font-size: 1.875rem !important;
			line-height: 2rem !important;
		}

		.competition-sub-heading {
			font-weight: 600;
			font-size: 1.375rem !important;
			line-height: 1.5rem !important;
		}

		.competition-form {
			background-color: #EFEFEF;
		}

		.competition-form input[type="text"],
		.competition-form input[type="email"],
		.competition-form input[type="phone"],
		.competition-form select,
		.competition-form label {
			width: 100%;
		}

		.competition-form label {
			line-height: 2em;
			font-size: 0.8125rem;
		}

		.competition-form input,
		.competition-form select {
			font-size: 1em;
			padding: 0.625rem;
		}

		.competition-form select {
			height: 3rem;
		}

		.competition-info-side,
		.competition-info-top,
		.competition-success {
			background-image: url('https://cdn.thebrag.com/pages/ttnq-competition-2022/line.gif');
			background-position: top;
			background-repeat: repeat-x;
		}

		.competition-info-side a,
		.competition-info-top a {
			text-decoration: none;
			color: #2EB0A2;
		}

		.competition-prize-sm {
			display: none;
		}

		.competition-prize-bg { 
			background-image: url('https://cdn.thebrag.com/pages/ttnq-competition-2022/prize-bg.jpg');
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
			height: 510px;
		}

		.competition-submit {
			width: 100%;
			padding: 0.75rem 1rem;
			border: none;
			background-color: #d32531;
			color: white;
			font-size: 1rem;
			font-weight: bold;
			letter-spacing: 0.4px;
			border-radius: 0.5rem;
		}


		.l-mega__block--newsletter {
			display: none;
		}

		.competition-success-message {
			font-family: 'Publico', sans-serif;
			font-size: 1.625rem !important;
			line-height: 1.75rem !important;
		}

		/* Small devices (landscape phones, 576px and up) */
		@media (min-width: 576px) {
			.competition-logo {
				height: 252px;
			}

			.competition-prize-bg {
				background-size: auto 100%; 
    			height: 100%;
			}

			.competition-prize-sm {
				display: block;
			}

			.competition-prize {
				display: none;
			}
		}

		/* Medium devices (tablets, 768px and up) */
		@media (min-width: 768px) {			
			.modal-lg, .modal-xl {
			    --bs-modal-width: 542px;
			}
		}

		@media (min-width: 889px) {
			.competition-prize-bg {
				background-position: top left;
			}
		}

		/* Large devices (desktops, 992px and up) */
		@media (min-width: 992px) {

		}

		/* Extra large devices (large desktops, 1200px and up) */
		@media (min-width: 1200px) {
			body {
				background-image: url('https://cdn.thebrag.com/pages/ttnq-competition-2022/reskin2.jpg');
				background-position: center top;
				background-repeat: no-repeat;
				background-attachment: fixed;
			}
		}
	</style>
</head>
	<body <?php body_class(); ?>>
		<?php do_action('pmc-tags-top'); // phpcs:ignore ?>

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
			fbq.disablePushState = true;
		</script>

		<noscript>
			<img height="1" width="1" src="https://www.facebook.com/tr?id=243859349395737&ev=PageView&noscript=1" />
		</noscript>
		<!-- End Facebook Pixel Code -->

<div class="l-page" id="site_wrap" style="width: 100%; max-width: 100%; overflow-x: hidden">
	<div class="l-page__header">
		<?php get_template_part('template-parts/header/nav-network'); ?>
	</div> <!-- .l-page__header -->