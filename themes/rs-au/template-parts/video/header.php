<?php
/**
 * Video Header.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-23
 */

$title = ( is_archive() ) ? __( 'Videos', 'pmc-rollingstone' ) : get_the_title();

?>

<div class="l-section-top l-section-top--tight-bg">
	<h1 class="l-section-top__heading">
		<span class="t-super"><?php echo esc_html( $title ); ?></span>
	</h1><!-- .l-section-top__heading -->

	<nav class="l-section-top__menu">
		<div class="c-page-nav c-page-nav--header" data-dropdown data-line-menu>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'rollingstone_videos',
					'container'      => false,
					'items_wrap'     => '<ul id="%1$s" class="c-page-nav__list %2$s">%3$s</ul>',
					'menu_class'     => '',
					'depth'          => 0,
					'walker'         => new \Rolling_Stone\Inc\Menus\Video_Nav_Walker(),
					'menu_id'        => 'video-header-menu',
				)
			);
			?>
		</div><!-- .c-page-nav--header -->
	</nav><!-- .l-section-top__menu -->
</div><!-- .l-section-top -->
