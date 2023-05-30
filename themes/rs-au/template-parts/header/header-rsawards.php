<?php

/**
 * Header template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-05
 */

use PMC\Lists\List_Post;

global $post;

$is_a_list_page   = rollingstone_is_list();
$list_page_active = ($is_a_list_page) ? 'l-header l-header--list' : 'l-header';
$total_items      = List_Post::get_instance()->get_list_items_count();

$show = isset($_GET['show']) ? trim(strtolower($_GET['show'])) : 'news';
?>

<div class="l-page__header">

	<header class="<?php echo esc_attr($list_page_active); ?>">
		<?php get_template_part('template-parts/header/nav-network'); ?>
		<div class="l-header__wrap tbm">
			<div class="l-header__content">

				<div class="l-header__search t-semibold is-search-expandable" data-header-search-trigger>
					<!-- <div data-st-search-form="small_search_form"></div> -->
					<div data-st-search-form="small_search_form">
						<div class="search-input-with-autocomplete">
							<div class="search-form">
								<form role="search" method="get" class="" action="<?php echo esc_url(home_url('/')); ?>">
									<input type="text" autocomplete="off" id="small_search_form" name="s" value="" placeholder="Search">
									<input type="submit" value="Search">
								</form>
							</div>
						</div>
					</div>
				</div><!-- .l-header__search -->

				<?php if (is_front_page()) : ?>
					<h1 class="l-header__branding">
						<a href="<?php echo esc_url(home_url('/')); ?>">
							<img class="l-header__logo" src="<?php echo TBM_CDN . '/assets/src/images/RS-AU_LOGO-RED.png'; ?>">
							<!-- <svg class="l-header__logo"><use xlink:href="#svg-rs-logo"></use></svg>-->
							<span class="screen-reader-text"><?php esc_html_e('Rolling Stone Australia', 'pmc-rollingstone'); ?></span>
						</a>
					</h1>
				<?php else : ?>
					<div class="l-header__branding">
						<a href="<?php echo esc_url(home_url('/')); ?>">
							<!-- <svg class="l-header__logo"><use xlink:href="#svg-rs-logo"></use></svg> -->
							<img class="l-header__logo" src="<?php echo TBM_CDN . '/assets/src/images/RS-AU_LOGO-RED.png'; ?>">
							<span class="screen-reader-text"><?php esc_html_e('Rolling Stone Australia', 'pmc-rollingstone'); ?></span>
						</a>
					</div>
				<?php endif; ?>
				<!-- .l-header__branding -->

				<div class="l-header__block l-header__block--right">
					<?php // get_template_part( 'template-parts/module/social-bar-header' ); 
					?>
					<?php \PMC::render_template(locate_template('/template-parts/module/cover.php'), [], true); // WPCS: XSS ok. 
					?>
				</div><!-- .l-header__block--right -->
			</div><!-- .l-header__content -->
			<div class="rsa-header-top">
				<div class="rsa-header-top-red">
					<?php
					get_template_part('template-parts/header/nav', 'rsawards', ['show' => $show]);
					?>
				</div>
				<div class="rsa-header-top-white"></div>
			</div>
		</div>
		<!--. l-header__wrap -->

	</header><!-- .l-header -->
	<!-- <div class="d-md-none"> -->
	<?php
	// if (PMC::is_mobile()) {
	// get_template_part('template-parts/ads/mobile-header');
	// }
	?>
	<!-- </div> -->

	<div class="rsa-header rsa-header-<?php echo $show; ?>" style="position: relative;">
		<?php if ('news' == $show) : ?>
			<div class="content-wrap d-flex">
				<div class="rsa-header-left">
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/SJRSA-header-left.png" width="200">
				</div>
				<div style="flex: 1; text-align: center; padding: 1rem;">
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/SJRS22_logoLock_100kRed100m100y.png">
				</div>
				<div class="rsa-header-right">
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/SJRSA-header-right.png" width="100">
				</div>
			</div>
			<div class="content-wrap d-flex flex-column" style="padding: 2rem;">
				<div class="d-flex flex-column flex-md-row align-items-end">
					<div class="text-center text-md-right" style="flex: 0 0 35%; width: 100%;">
						<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/Hula22giphy.gif" width="150">
					</div>
					<div class="d-flex flex-column align-items-start" style="padding: 0 2rem; flex: 0 0 65%">
						<!-- <div class="d-flex flex-column">
							<div class="header-sub-text">
								SUBSCRIBE TO<br>
								ROLLING STONE AUSTRALIA<br>
								MAGAZINE TO BE ELIGIBLE
							</div>
							<div>
								<div class="text-center mt-2">
									<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/DownArrow.svg" width="30">
								</div>
								<a href="https://au.rollingstone.com/vote-for-rolling-stone-readers-award-2022/" target="_blank" class="btn-vote-header" style="text-transform: uppercase;">
									Vote
								</a>
							</div>
						</div> -->
						<div class="d-flex d-none d-md-flex" style="margin-top: 1rem;">
							<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/SponsoredByLOGOS.png" style="width: 100%;">
						</div>
					</div>
				</div>
				<div class="d-flex d-md-none d-flex" style="margin: 1rem 1rem 0;">
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/SponsoredByLOGOS.png" width="450">
				</div>
			</div>

		<?php else : ?>
			<div class="content-wrap d-flex">
				<div class="text-center text-md-right">
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/Hula22giphy.gif" width="100">
				</div>
				<div style="text-align: center; padding: 1rem;">
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/SJRS22_logoLock_100kRed100m100y.png" width="400">
				</div>
			</div>

		<?php endif; ?>
	</div>

</div>