<?php

/**
 * Mega menu.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>

<div class="l-mega" data-mega-menu>
	<div class="l-mega__close">

		<?php get_template_part('template-parts/header/close-button'); ?>

	</div><!-- .l-mega__close -->
	<div class="l-mega__wrap" data-mega-menu-wrap>

		<div class="l-mega__row">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="l-mega__branding">
				<img class="l-header__logo" src="<?php echo TBM_CDN . '/assets/images/RSAUwhitewhite_WEB.png'; ?>">
				<span class="screen-reader-text"><?php esc_html_e('Rolling Stone', 'pmc-rollingstone'); ?></span>
			</a><!-- .l-mega__branding -->

			<div class="l-mega__search">
				<!-- <div data-st-search-form="search_form"></div> -->
				<div data-st-search-form="search_form">
					<div class="search-input-with-autocomplete">
						<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
							<input type="text" autocomplete="off" id="st-search-form-input" name="s" value="" placeholder="Search">
							<input type="submit" name="searchButton" value="Search">
						</form>
					</div>
				</div>
			</div><!-- .l-mega__search -->
		</div><!-- .l-mega__row -->

		<div class="l-mega__nav">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'pmc_core_mega',
					'menu_class'     => 'c-mega-nav',
					'container'      => false,
					'items_wrap'     => '<ul class="%2$s" data-collapsible-group>%3$s</ul>',
					'depth'          => 2,
					'walker'         => new \Rolling_Stone\Inc\Menus\Mega_Nav_Walker(),
				)
			);
			?>

			<div class="l-mega__cover">
				<?php \PMC::render_template(locate_template('template-parts/module/cover.php'), ['mega' => true], true); // WPCS: XSS ok. 
				?>
			</div><!-- .l-mega__cover -->
		</div><!-- .l-mega__nav -->

		<div class="l-mega__row">
			<div class="l-mega__block l-mega__block--social">
				<p class="l-mega__heading t-bold"><?php esc_html_e('Follow Us', 'pmc-rollingstone'); ?></p>
				<?php get_template_part('template-parts/module/social-bar-round'); ?>
			</div><!-- .l-mega__block--social -->

			<div class="l-mega__block l-mega__block--newsletter">
				<p class="l-mega__heading t-bold"><?php esc_html_e('Alerts &amp; Newsletters', 'pmc-rollingstone'); ?></p>
				<?php get_template_part('template-parts/module/newsletter-signup'); ?>
			</div><!-- .l-mega__block--newsletter -->
		</div><!-- .l-mega__row -->

		<div class="l-mega__row">
			<?php
			wp_nav_menu(
				array(
					'menu_class'      => 'l-mega__menu',
					'theme_location'  => 'pmc_core_mega_bottom',
					'container_class' => 'l-mega__block l-mega__block--footer',
					'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
					'walker'         => new \Rolling_Stone\Inc\Menus\Footer_Nav_Walker(),
				)
			);
			?>

			<!-- .l-mega__block--footer -->

			<div class="l-mega__block l-mega__block--legal">
				<!-- <svg class="l-mega__pmc-logo"><use xlink:href="#svg-pmc-logo-white"></use></svg> -->
				<span class="screen-reader-text"><?php // esc_html_e( 'PMC', 'pmc-rollingstone' ); 
													?></span>
				<p class="l-mega__copyright">
					<?php
					// Translators: Mega Footer Copyright.
					// printf( esc_html__( '&copy; %1$s Penske Media Corporation', 'pmc-rollingstone' ), esc_html( date( 'Y' ) ) );
					?>
				</p>
			</div><!-- .l-mega__block--legal -->

			<?php if (is_user_logged_in()) : ?>
				<div style="text-align: center; margin: 0 auto 1rem;">
					<a href="<?php echo wp_logout_url(); ?>" style="color: #777">Logout</a>
				</div>
			<?php endif; ?>
		</div><!-- .l-mega__row -->

	</div><!-- .l-mega__wrap -->
</div><!-- .l-mega -->