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
				<h1 class="l-header__branding">
					<a href="<?php echo esc_url(home_url('/')); ?>">
						SAILOR JERRY AND ROLLING STONE AUSTRALIA
						<br>
						<span>PRESENT</span>
					</a>
				</h1>
				<!-- .l-header__branding -->
			</div><!-- .l-header__content -->

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
		<div class="content-wrap d-flex">
			<div style="flex: 1; text-align: center; padding: 1rem;">
				<img src="<?php echo get_template_directory_uri(); ?>/images/sj-road-to-rs/logo.png" style="width: 35rem;">
			</div>
		</div>
	</div>

</div>