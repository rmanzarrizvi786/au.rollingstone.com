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

	<?php
	// Include CSS
	include(get_template_directory() . '/page-templates/jd-live-at-last-2/style.php');
	?>

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