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


				<h1 class="l-header__branding">
					<a href="<?php echo esc_url(home_url('/nz/')); ?>">
						<img class="l-header__logo" src="<?php echo TBM_CDN . '/assets/src/images/RollingStoneNZ.png'; ?>">
						<span class="screen-reader-text"><?php esc_html_e('Rolling Stone New Zealand', 'pmc-rollingstone'); ?></span>
					</a>
				</h1>
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
					get_template_part('template-parts/header/nav', 'rsawards-nz', ['show' => $show]);
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
			<div class="content-wrap d-flex flex-column">
				<div class="white-stripes"></div>
				<div class="d-flex" style="flex: 1; text-align: center; padding: 1rem;">
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/nz/logo-header.png" class="logo-header">
				</div>
				<div class="white-stripes"></div>
				<div class="mt-2 px-2">
					<div><img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/nz/LogoStrip-white2.png"></div>
				</div>
			</div>
			<?php if (0) : ?>
				<div class="content-wrap d-flex flex-column" style="padding: 2rem;">
					<div class="d-flex flex-column flex-md-row align-items-end" style="width: 100%;">
						<div class="d-flex flex-column" style="padding: 0 2rem; flex: 0 0 65%">
							<div class="d-flex flex-column">
								<div class="header-sub-text">
									SUBSCRIBE TO<br>
									ROLLING STONE AUSTRALIA/NEW ZEALAND<br>
									MAGAZINE TO BE ELIGIBLE
								</div>
								<div>
									<div class="text-center mt-2">
										<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/DownArrow.svg" width="30">
									</div>
									<a href="<?php echo home_url('/nominate-for-rolling-stone-new-zealand-readers-award-2022/'); ?>" target="_blank" class="btn-vote-header" style="text-transform: uppercase;">
										Nominate
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

		<?php else : ?>
			<div class="content-wrap d-flex flex-column">
				<div class="white-stripes"></div>
				<div class="d-flex" style="flex: 1; text-align: center; padding: 1rem;">
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/nz/logo-header.png" class="logo-header">
				</div>
				<div class="white-stripes"></div>
				<div class="mt-2 px-2">
					<div><img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/nz/LogoStrip-white.png"></div>
				</div>
			</div>

		<?php endif; ?>
	</div>

</div>