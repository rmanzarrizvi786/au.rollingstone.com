<?php

/**
 * Category Header.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>
<div class="l-section-top">
	<h1 class="l-section-top__heading">
		<span class="t-super">
			<?php echo single_term_title('', false); ?>
		</span>
	</h1><!-- .l-section-top__heading -->

	<nav class="l-section-top__menu">
		<div class="c-page-nav c-page-nav--header" data-dropdown="" data-line-menu="">
			<ul id="category-header-menu" class="c-page-nav__list ">
				<?php
				$menu_obj = wp_get_nav_menu_object('Category Menu - ' . single_term_title('', false));
				$menu_id = $menu_obj ? $menu_obj->term_id : 0;

				$socialNav = wp_get_nav_menu_items($menu_id);
				foreach ((array) $socialNav as $navItem) {
					if (isset($navItem->url)) {
						?>
						<li class="c-page-nav__item is-active" data-ripple="">
							<a href="<?php echo $navItem->url; ?>" class="c-page-nav__link" data-line-menu-left="">
								<?php echo $navItem->title; ?>
							</a>
						</li>

						<?php
					}
				}
				?>




			</ul>
			<div class="line-menu-indicator" style="transform: translateX(23px);"></div>
		</div><!-- .c-page-nav--header -->
	</nav><!-- .l-section-top__menu -->
</div>