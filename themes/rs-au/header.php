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
	<link rel="apple-touch-icon" href="https://www.rollingstone.com/wp-content/uploads/2022/08/cropped-Rolling-Stone-Favicon.png?w=180" />

	<!-- Tile icons for Windows -->
	<meta name="msapplication-config" content="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/browserconfig.xml'); // WPCS: XSS okay. 
												?>">
	<meta name="msapplication-TileImage" content="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/icons/mstile-144x144.png'); // WPCS: XSS okay. 
													?>">
	<meta name="msapplication-TileColor" content="#eff4ff">

	<!-- Favicons -->
	<link rel="icon" href="https://www.rollingstone.com/wp-content/uploads/2022/08/cropped-Rolling-Stone-Favicon.png?w=32" sizes="32x32" />
	<link rel="icon" href="https://www.rollingstone.com/wp-content/uploads/2022/08/cropped-Rolling-Stone-Favicon.png?w=192" sizes="192x192" />

	<!-- Safari pin icon -->
	<link rel="mask-icon" href="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/icons/safari-pinned-tab.svg'); // WPCS: XSS okay. 
								?>" color="#000000">

	<!-- Titles -->
	<meta name="apple-mobile-web-app-title" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
	<meta name="application-name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
	<meta name="description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
	<!-- Titles:end -->

    <meta name='impact-site-verification' value='1335992589'>

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
													echo ("https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/{$src[0]}");
												} else {
													echo $src[0];
												}
											}
											?>">
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
		<?php
		if (get_field('page_background_colour')) : ?>body {
			background-color: <?php echo get_field('page_background_colour'); ?> !important;
		}

		<?php endif; ?>div.admz,
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
			fbq.disablePushState = true;
		</script>

		<noscript>

			<img height="1" width="1" src="https://www.facebook.com/tr?id=243859349395737&ev=PageView

&noscript=1" />

		</noscript>

		<!-- End Facebook Pixel Code -->

		<?php if (!is_singular('pmc-gallery')) : ?>
			<div id="skin-ad-section">
				<div id="skin-ad-container">
					<!-- <a href="https://thebrag.media" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/_tmp-bg.jpg"></a> -->
					<?php ThemeSetup::render_ads('skin'); ?>
				</div>
			</div>


			<div class="l-page" id="site_wrap">
				<?php get_template_part('template-parts/header/header'); ?>
			<?php endif; ?>
