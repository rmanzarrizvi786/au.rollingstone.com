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
			<div class="rsa-header-top">
				<div class="rsa-header-top-red">
					<?php
					get_template_part('template-parts/header/nav', 'rsawards-2023-nz', ['show' => $show]);
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
					<img src="https://cdn-r2-2.thebrag.com/pages/rolling-stone-awards-2023-nz/awards6.png" width="120">
				</div>
				<div style="flex: 1; text-align: center; padding: 1rem; padding-bottom: 0;">
					<img src="https://cdn-r2-1.thebrag.com/rs/uploads/2023/08/RSNZ23_PH_logoLock.png">
					<img src="https://cdn-r2-2.thebrag.com/pages/rolling-stone-awards-2023-nz/LogoStrip-black.png" style="width: 100%;">
				</div>
				<div class="rsa-header-right">
					<img src="https://cdn-r2-2.thebrag.com/pages/rolling-stone-awards-2023-nz/awards5.png" width="120">
				</div>
			</div>

		<?php else : ?>
			<div class="content-wrap d-flex">
				<img src="https://cdn-r2-1.thebrag.com/rs/uploads/2023/08/RSNZ23_PH_logoLock.png" width="500">
			</div>
			<!-- <div class="d-flex d-none d-md-flex" style="margin-top: 1rem;">
				<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2023/sponsors.png" style="width: 500px; filter: invert(100%);">
			</div> -->
		<?php endif; ?>
	</div>

</div>