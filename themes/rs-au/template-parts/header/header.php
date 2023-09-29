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
?>

<div class="l-page__header">

	<div class="ad-bb-header sticky-bottom-mobile">
		<?php
		// if (!PMC::is_mobile()) {
		get_template_part('template-parts/ads/header');
		// }
		?>
	</div>

	<?php get_template_part('template-parts/header/nav-network'); ?>

	<header class="<?php echo esc_attr($list_page_active); ?>" data-header data-header-sticky-class="is-header-sticky" data-header-ready-class="is-header-ready" data-header-search-class="is-search-expanded">
		<div class="l-header__wrap tbm">
			<div class="l-header__content">

				<div class="l-header__search t-semibold is-search-expandable" data-header-search-trigger style="">
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

				<?php if (
					is_page_template('page-templates/page-nz.php') ||
					is_page_template('page-templates/rsawards-readers-nominate-nz-2022.php') ||
					(is_singular('pmc-nz'))
				) : ?>
					<h1 class="l-header__branding">
						<a href="<?php echo esc_url(home_url('/nz/')); ?>">
							<img class="l-header__logo" src="<?php echo TBM_CDN . '/assets/images/RSNZ_primary.png'; ?>">
							<span class="screen-reader-text"><?php esc_html_e('Rolling Stone New Zealand', 'pmc-rollingstone'); ?></span>
						</a>
					</h1>
				<?php else : ?>
					<div class="l-header__branding">
						<a href="<?php echo esc_url(home_url('/')); ?>">
							<img class="l-header__logo" src="<?php echo TBM_CDN . '/assets/images/RSAU_Primary.png'; ?>">
							<span class="screen-reader-text"><?php esc_html_e('Rolling Stone Australia', 'pmc-rollingstone'); ?></span>
						</a>
					</div>
				<?php endif; ?>
				<!-- .l-header__branding -->

				<div class="l-header__block l-header__block--left t-bold">
					Send us
					<a href="https://thebrag.media/submit-a-tip/" target="_blank" class="l-header__link"><?php esc_html_e('tip', 'pmc-rollingstone'); ?></a>
					/
					<a href="https://thebrag.media/how-to-submit-an-op-ed-essay/" target="_blank" class="l-header__link"><?php esc_html_e('op-ed', 'pmc-rollingstone'); ?></a>
					/
					<a href="https://thebrag.media/submit/" target="_blank" class="l-header__link"><?php esc_html_e('video', 'pmc-rollingstone'); ?></a>
				</div><!-- .l-header__block--left -->

				<div class="l-header__block l-header__block--right">
					<?php // get_template_part( 'template-parts/module/social-bar-header' ); 
					?>
					<?php \PMC::render_template(locate_template('/template-parts/module/cover.php'), [], true); // WPCS: XSS ok. 
					?>
				</div><!-- .l-header__block--right -->

				<?php get_template_part('template-parts/header/nav-main'); ?>

			</div><!-- .l-header__content -->

			<div class="l-header__content l-header__content--sticky" style="flex-direction: column;
    padding-right: 0;
    padding-left: 0;">

				<div style="width: 100%;" class="nav-network-wrap-2"><?php get_template_part('template-parts/header/nav-network'); ?></div>

				<div style="width: 100%;
    display: flex;
    flex-direction: row;
    padding-left: 1.25rem;
    padding-right: 4.0625rem;
		padding-right: 0;">

					<?php if (
						is_page_template('page-templates/page-nz.php') ||
						is_page_template('page-templates/rsawards-readers-nominate-nz-2022.php') ||
						(is_singular('pmc-nz'))
					) : ?>
						<a href="<?php echo esc_url(home_url('/nz/')); ?>" class="l-header__branding l-header__branding--sticky">
							<img class="l-header__logo" src="<?php echo TBM_CDN . '/assets/images/RSNZ_Primary.png'; ?>">
						</a><!-- .l-header__branding--sticky -->
					<?php else : ?>
						<a href="<?php echo esc_url(home_url('/')); ?>" class="l-header__branding l-header__branding--sticky">
							<img class="l-header__logo" src="<?php echo TBM_CDN . '/assets/images/RSAU_Primary.png'; ?>">
						</a><!-- .l-header__branding--sticky -->
					<?php endif; ?>

					<div class="l-header__toggle l-header__toggle--sticky l-header__toggle--hamburger">
						<?php get_template_part('template-parts/header/hamburger'); ?>
					</div><!-- .l-header__toggle--sticky--hamburger -->

					<div class="l-header__toggle l-header__toggle--sticky l-header__toggle--close">
						<?php get_template_part('template-parts/header/close-button'); ?>
					</div><!-- .l-header__toggle--sticky--close -->

					<?php
					if ($is_a_list_page && 20 <= $total_items) {
						get_template_part('template-parts/list/list-nav');
					} else {
						get_template_part('template-parts/header/block-next');
					}
					?>

					<div style="margin-top: .25rem; margin-right: .25rem;">
						<?php get_template_part('template-parts/module/social-bar-header'); ?>
					</div><!-- .l-mega__block--social -->

					<a href="<?php echo esc_url(trailingslashit(home_url('subscribe-magazine'))); ?>" class="l-header__block l-header__block--sticky-link  t-bold">
						<?php esc_html_e('Subscribe', 'pmc-rollingstone'); ?>
					</a>

				</div>

			</div><!-- .l-header__content--sticky -->
		</div>
		<!--. l-header__wrap -->


		<div class="l-header__wrap l-header__wrap--layer l-header__wrap--subscribe">

			<div class="l-header__subscribe" style="/*width: 50%; right: 0; left: auto; top: -2rem;*/ padding-left: 2.75rem; padding-right: 2.75rem;">
				<?php get_template_part('template-parts/module/subscribe-flyout'); ?>
			</div><!-- .l-header__subscribe -->

		</div><!-- .l-header__wrap--layer--subscribe -->
	</header><!-- .l-header -->
	<!-- <div class="d-md-none"> -->
	<?php
	// if (PMC::is_mobile()) {
	// get_template_part('template-parts/ads/mobile-header');
	// }
	?>
	<!-- </div> -->

</div>