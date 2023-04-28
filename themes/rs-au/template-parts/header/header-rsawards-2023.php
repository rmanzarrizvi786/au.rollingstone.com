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
$show = is_page('vote-for-rolling-stone-readers-award-2023') ? 'vote' : $show;
?>

<div class="l-page__header">

	<header class="<?php echo esc_attr($list_page_active); ?>">
		<?php get_template_part('template-parts/header/nav-network'); ?>
		<div class="l-header__wrap tbm">
			<div class="rsa-header-top">
				<div class="rsa-header-top-red">
					<?php
					get_template_part('template-parts/header/nav', 'rsawards-2023', ['show' => $show]);
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
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2023/date.png" width="100">
				</div>
				<div style="flex: 1; text-align: center; padding: 1rem;">
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2023/RSAUAwards_Filigree.png">
				</div>
				<div class="rsa-header-right">
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2023/awards3.png" width="120">
				</div>
			</div>
			<div class="content-wrap d-flex flex-column" style="padding: 2rem;">
				<div class="d-flex flex-column flex-md-row align-items-end">
					<div class="d-flex flex-column align-items-center" style="flex: 0 0 65%">
						<div class="d-flex flex-column">
							<div class="header-sub-text">
								SUBSCRIBE TO<br>
								ROLLING STONE AUSTRALIA<br>
								MAGAZINE TO BE ELIGIBLE
							</div>
							<div>
								<div class="text-center mt-2">
									<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2023/DownArrow.svg" width="30">
								</div>
								<a href="https://au.rollingstone.com/nominate-for-rolling-stone-australia-readers-award-2023/" target="_blank" class="btn-vote-header" style="text-transform: uppercase;">
									Vote
								</a>
							</div>
						</div>
						<div class="d-flex d-none d-md-flex" style="margin-top: 1rem;">
							<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2023/sponsors.png" style="width: 100%;">
						</div>
					</div>
				</div>
				<div class="d-flex d-md-none d-flex" style="margin: 1rem 1rem 0;">
					<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2023/SponsoredByLOGOS.png" width="450">
				</div>
			</div>

		<?php else : ?>
			<div class="content-wrap d-flex">
				<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2023/RSAUAwards_Filigree.png" width="500">
			</div>
			<div class="d-flex d-none d-md-flex" style="margin-top: 1rem;">
				<img src="<?php echo get_template_directory_uri(); ?>/images/rsa2023/sponsors.png" style="width: 500px; filter: invert(100%);">
			</div>
		<?php endif; ?>
	</div>

</div>