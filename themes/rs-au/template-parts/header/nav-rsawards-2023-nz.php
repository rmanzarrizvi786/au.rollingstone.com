<?php
extract($args);

/**
 * Header Nav Template
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-06
 */

?>

<nav class="l-header__nav" style="order: 2;">
	<!-- <div class="l-header__toggle l-header__toggle--hamburger"> -->
	<?php // get_template_part('template-parts/header/hamburger'); 
	?>
	<!-- </div> -->
	<!-- .l-header__toggle--hamburger -->

	<!-- <div class="l-header__toggle l-header__toggle--close"> -->
	<?php // get_template_part('template-parts/header/close-button'); 
	?>
	<!-- </div> -->
	<!-- .l-header__toggle--close -->

	<?php
	$menu_items = [
		// 'news' => [
		// 	'title' => 'News',
		// 	'link' => home_url('rolling-stone-awards-2023/nz?show=news')
		// ],
		'info' => [
			'title' => 'Info',
			'link' => home_url('rolling-stone-awards-2023/nz/?show=info')
		],
		'categories' => [
			'title' => 'Award categories',
			'link' => home_url('rolling-stone-awards-2023/nz/?show=categories')
		],
		/* 
		'venue-details' => [
			'title' => 'Venue Details',
			'link' => home_url('rolling-stone-awards-2022?show=venue-details')
		],
		'faq' => [
			'title' => 'FAQ',
			'link' => home_url('rolling-stone-awards-2022?show=faq')
		],
		'judges' => [
			'title' => 'Judges',
			'link' => home_url('rolling-stone-awards-2022?show=judges')
		],
		'sponsors' => [
			'title' => 'Sponsors',
			'link' => home_url('rolling-stone-awards-2022?show=sponsors')
		], */

		// 'nominate' =>
		// [
		// 	'title' => 'Nominate',
		// 	'link' => 'https://au.rollingstone.com/nominate-for-rolling-stone-australia-readers-award-2022/',
		// 	'target' => '_blank',
		// ],

		// 'vote' =>
		// [
		// 	'title' => 'Vote',
		// 	'link' => 'https://au.rollingstone.com/vote-for-rolling-stone-readers-award-2022/',
		// 	'target' => '_blank',
		// ],
	];
	?>

	<ul class="l-header__menu t-semibold">
		<?php foreach ($menu_items as $menu_key => $menu_item) : ?>
			<li class="l-header__menu-item <?php echo isset($show) && $show == $menu_key ? 'active' : ''; ?>">
				<a href='<?php echo $menu_item['link']; ?>' class="l-header__menu-link" <?php echo isset($menu_item['target']) ? "target={$menu_item['target']}" : ''; ?>>
					<?php echo $menu_item['title']; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php
	/* wp_nav_menu(
		array(
			'theme_location' => 'pmc_core_header',
			'container'      => false,
			'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
			'menu_class'     => 'l-header__menu t-semibold',
			'depth'          => 0,
			'walker'         => new \Rolling_Stone\Inc\Menus\Header_Nav_Walker(),
		)
	); */
	?>
</nav><!-- .l-header__nav -->