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

	?>
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="@rollingstoneaus">
		<meta name="twitter:title" content="<?php the_title(); ?>">
		<meta name="twitter:image" content="<?php
											if (has_post_thumbnail()) {
												$src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
												$type = exif_imagetype($src[0]);

												if ($type != false && ($type == (IMAGETYPE_PNG || IMAGETYPE_JPEG))) {
													echo ("https://app.thebrag.com/img-socl/?url={$src[0]}");
												} else {
													echo $src[0];
												}
											}
											?>">
	<?php } // If single 
	?>

	<?php wp_head(); ?>
	<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
	<style>
		@-webkit-keyframes ticker {
			0% {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
				visibility: visible;
			}

			100% {
				-webkit-transform: translate3d(-100%, 0, 0);
				transform: translate3d(-100%, 0, 0);
			}
		}

		@keyframes ticker {
			0% {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
				visibility: visible;
			}

			100% {
				-webkit-transform: translate3d(-100%, 0, 0);
				transform: translate3d(-100%, 0, 0);
			}
		}

		@-webkit-keyframes scroller {
			0% {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
				visibility: visible;
			}

			100% {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 50px, 0);
			}
		}

		@keyframes scroller {
			0% {
				-webkit-transform: translate3d(0, 0, 0);
				transform: translate3d(0, 0, 0);
				visibility: visible;
				opacity: 0;
			}

			100% {
				-webkit-transform: translate3d(0, 100px, 0);
				transform: translate3d(0, 100px, 0);
				opacity: 1;
			}
		}

		#wpadminbar {
			display: none;
		}

		html {
			margin-top: 0 !important;
		}

		body {
			font-family: 'Graphik', sans-serif;
			font-weight: 100;
			color: #3B3836;
		}

		.info_inner_arrow svg path {
			fill: none !important;
		}

		img {
			max-width: 100%;
		}

		.scroller_outer,
		.info_outer,
		.slider_outer,
		.billing_outer {
			width: 100%;
			height: 100%;
		}

		.top_outer {
			max-width: 1000px;
			margin-left: auto;
			margin-right: auto;
			height: 298px;
			background-color: transparent;
			background-image: url('<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/RollingStone_Bonds_website-2000pxl_2.jpg');
			background-repeat: no-repeat;
			background-position: bottom center;
			display: flex;
			flex-direction: column;
			justify-content: center;
			background-size: contain;
			z-index: 1;
			position: relative;
		}

		.scroller {
			-moz-animation-duration: 5s;
			-webkit-animation-duration: 5s;
			animation-duration: 5s;
			-moz-animation-timing-function: linear;
			-webkit-animation-timing-function: linear;
			animation-timing-function: linear;
			-moz-animation-iteration-count: infinite;
			-webkit-animation-iteration-count: infinite;
			animation-iteration-count: infinite;
			-moz-animation-name: scroller;
			-webkit-animation-name: scroller;
			animation-name: scroller;
			max-width: 1200px;
			margin: 0 auto;
			display: none;
		}

		.scroller_outer {
			background-color: #000;
			width: 100%;
			overflow: hidden;
			display: flex;
			justify-content: flex-start;
			height: 43px;
			line-height: 43px;
			font-size: 20px;
		}

		.scroller_inner {
			display: inline-flex;
			color: #fff;
			white-space: nowrap;
			justify-content: flex-start;
			-moz-animation-duration: 20s;
			-webkit-animation-duration: 20s;
			animation-duration: 20s;
			-moz-animation-timing-function: linear;
			-webkit-animation-timing-function: linear;
			animation-timing-function: linear;
			-moz-animation-iteration-count: infinite;
			-webkit-animation-iteration-count: infinite;
			animation-iteration-count: infinite;
			-moz-animation-name: ticker;
			-webkit-animation-name: ticker;
			animation-name: ticker;
		}

		.scroller_inner_item {
			display: inline-flex;
			align-items: center;
			padding: 0 1em;
			font-weight: 400;
		}

		.slider_block {
			width: 550px;
			overflow: hidden;
			padding: 0;
			margin: 0;
			height: 550px;
			/* background-color: rgba(0, 0, 0, 0.5); */
		}

		.info_outer {
			background-color: #fff;
			padding-bottom: 60px;
		}

		.info_inner {
			margin: 0 auto;
		}

		.info_inner_arrow {
			text-align: center;
			margin-bottom: 30px;
		}

		.info_inner_left {
			display: flex;
			flex-direction: column;
			justify-content: center;
			margin: 0 auto;
			width: 100%;
			height: 206px;
			margin: 50px 0;
			background-image: url('<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/info_inner_image.png');
			background-position: calc(50% - 36px) 50%;
			background-repeat: no-repeat;
			background-size: auto 206px;
		}

		.info_inner_right {
			display: flex;
			flex-direction: column;
			justify-content: center;
			padding: 0 30px 30px;
		}

		.p1 {
			font-size: 22px;
			line-height: 30px;
			margin-bottom: 30px;
			font-weight: 400;
			color: #000;
		}

		.p2 {
			font-size: 20px;
			line-height: 30px;
			margin-bottom: 30px;
		}

		.slider_outer {
			height: 682px;
			background-color: #EFEFEF;
			border: 1px solid #707070;
		}

		.slider_inner {
			padding: 0 30px;
			overflow: hidden;
		}

		.slide_arrow {
			width: 60px;
			height: 550px;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			position: absolute;
			top: 0;
		}

		.slide_left {
			left: 0;
		}

		.slide_right {
			right: 0;
		}

		.billing_outer {
			padding: 90px 0;
			background-color: #fff;
			margin: 0 auto;
			position: relative;
		}

		.sold-out {
			display: flex;
			justify-content: center;
			align-items: center;
			font-size: 300%;
			line-height: 125%;
			text-align: center;

			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: rgba(0, 0, 0, 0.75);
			color: #fff;
		}

		.sold-out span {
			transform: rotate(-15deg);
		}

		.billing_inner {
			padding: 0 30px;
			background-color: #EFEFEF;
			border: 1px solid #707070;
			margin: 0 auto;
		}

		.billing_inner_left {
			width: 100%;
			padding: 30px 0 30px;
		}

		.billing_inner_top,
		.billing_inner_right {
			display: none;
			width: 100%;
			padding: 30px 0;
		}

		.billing_inner_top {
			display: block;
			text-align: center;
			padding: 30px 0 0;
		}

		.billing_tick {
			padding-right: 0.5rem !important;
		}

		.h2 {
			font-size: 26px;
			line-height: 30px;
			font-weight: 100;
			letter-spacing: 1px;
			text-transform: uppercase;
			padding: 30px 0;
			text-align: center;
		}

		.flickity-button {
			background: rgba(0, 0, 0, 0.1);
			width: 60px;
			height: 60px;
			color: #fff !important;
		}

		.flickity-button:hover {
			background: rgba(0, 0, 0, 0.4);
		}

		input {
			border: 1px solid #707070 !important;
			height: 45px !important;
			line-height: 45px !important;
			font-family: 'Graphik', sans-serif;
			outline: none;
			font-size: 12px;
			font-weight: 100;
			letter-spacing: 1px;
			padding: 0 10px;
			width: 100%;
			box-sizing: border-box;
		}

		.nav-network-wrap {
			border-bottom: none;
		}

		select {
			border: 1px solid #707070 !important;
			height: 45px !important;
			font-family: 'Graphik', sans-serif;
			outline: none;
			font-size: 12px;
			font-weight: 100;
			letter-spacing: 1px;
			padding: 0 10px;
		}

		input[type="checkbox"] {
			width: auto;
			line-height: 0 !important;
			height: auto !important;
		}

		.bar {
			height: 70px;
			width: 100%;
			position: absolute;
			top: 24px;
			background-color: #D32531;
			z-index: 0;
		}

		@media (min-width: 576px) {
			.top_outer {
				height: 396px;
			}

			.info_inner {
				display: flex;
				justify-content: space-between;
				padding: 30px;
			}

			.info_inner_left {
				background-position: center;
				background-size: contain;
				height: 306px;
				margin: 0;
			}

			.info_inner_right {
				padding-bottom: 0;
			}

			.info_inner_arrow {
				display: none;
			}
		}

		@media (min-width: 768px) {
			.top_outer {
				height: 496px;
				margin-top: 30px;
			}

			.billing_outer {
				width: 80%;
			}
		}

		@media (min-width: 992px) {
			.bar {
				height: 140px;
			}

			.top_inner,
			.scroller_inner,
			.info_inner {
				width: 1000px;
				height: 100%;
			}

			.top_outer {
				height: 796px;
			}

			.info_inner {
				padding: 0;
			}

			.scroller {
				display: block;
				left: 0;
				top: 50%;
				position: absolute;
				display: block;
				right: 0;
			}

			.info_inner_right_float {
				height: 300px;
				padding: 20px 0;
			}

			.billing_inner {
				padding: 0;
				display: flex;
				justify-content: space-around;
			}

			.billing_inner_left {
				width: auto;
			}

			.billing_inner_right {
				width: 400px;
				display: block;
			}

			.billing_inner_top {
				display: none;
			}
		}

		@media (min-width: 1200px) {
			.top_outer {
				height: 780px;
				margin-top: 0px;
			}

			.scroller_outer {
				height: 56px;
				line-height: 56px;
				font-size: 22px;
			}

			.info_outer {
				height: 682px;
			}

			.info_inner_left {
				width: 422px;
				height: 682px;
			}

			.info_inner_right {
				width: 536px;
				height: 682px;
			}
		}

		/* 
			576px for portrait phones
			768px for tablets
			992px for laptops
			1200px for large devices 
		*/
	</style>
	<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js" defer></script>

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
			fbq.disablePushState = true;
		</script>

		<noscript>
			<img height="1" width="1" src="https://www.facebook.com/tr?id=243859349395737&ev=PageView&noscript=1" />
		</noscript>

		<!-- End Facebook Pixel Code -->

		<div id="site_wrap" style="background: #fff;">
			<div>
				<div class="l-page__header">
					<?php get_template_part('template-parts/header/nav-network'); ?>
				</div>