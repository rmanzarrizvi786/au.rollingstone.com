<?php

/**
 * Template Name: Bonds (2022)
 */
get_header('rsbonds-custom-menu');
?>

<?php
if (have_posts()) :
	while (have_posts()) :
		the_post();
		if (!post_password_required($post)) :
?>
			<div class="bar"></div>
			<div style="position: relative;">
				<div class="scroller">
					<img src="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/sd_ts3.png">
				</div>
				<div class="top_outer"></div>
			</div>
			<div class="scroller_outer">
				<div class="scroller_inner">
					<div class="scroller_inner_item">FIRST 50 RECEIVE FREE BONDS TOTAL PACKAGE™ UNDERWEAR</div>
					<div class="scroller_inner_item">LEARN MORE</div>
				</div>
				<div class="scroller_inner">
					<div class="scroller_inner_item">FIRST 50 RECEIVE FREE BONDS TOTAL PACKAGE™ UNDERWEAR</div>
					<div class="scroller_inner_item">LEARN MORE</div>
				</div>
				<div class="scroller_inner">
					<div class="scroller_inner_item">FIRST 50 RECEIVE FREE BONDS TOTAL PACKAGE™ UNDERWEAR</div>
					<div class="scroller_inner_item">LEARN MORE</div>
				</div>
				<div class="scroller_inner">
					<div class="scroller_inner_item">FIRST 50 RECEIVE FREE BONDS TOTAL PACKAGE™ UNDERWEAR</div>
					<div class="scroller_inner_item">LEARN MORE</div>
				</div>
				<div class="scroller_inner scroller_inner_2">
					<div class="scroller_inner_item">FIRST 50 RECEIVE FREE BONDS TOTAL PACKAGE™ UNDERWEAR</div>
					<div class="scroller_inner_item">LEARN MORE</div>
				</div>
			</div>
			<div class="info_outer">
				<div class="info_inner">
					<div class="info_inner_left"></div>
					<div class="info_inner_arrow">
						<svg xmlns="http://www.w3.org/2000/svg" width="31.279" height="14.552" viewBox="0 0 31.279 14.552">
							<path id="Path_738" data-name="Path 738" d="M3695.4,656.5l-12.481,15.065L3695.4,686.5" transform="translate(-655.862 3696.165) rotate(-90)" fill="none" stroke="#707070" stroke-width="2" />
						</svg>
					</div>
					<div class="info_inner_right">
						<p class="p1">STAND BY YOUR (OLD) MAN THIS FATHER&CloseCurlyQuote;S DAY WITH THE BONDS X ROLLING STONE&CloseCurlyQuote;S CHARITY CALENDAR</p>
						<p class="p2">To celebrate Father&CloseCurlyQuote;s Day, Bonds and Rolling Stone have created a limited edition calendar — starring your favourite Aussie musos and their greatest supports, proudly sporting their Bonds Total Package™ underwear.</p>
						<p class="p2">All proceeds go to our friends at Support Act, the charity doing the most for musicians.</p>
						<p class="p2">
							<a href="#shop" id="submit-bonds-2022" class="mt-1">
								<span id="button-text">Shop Now</span>
							</a>
						</p>
					</div>
				</div>
			</div>
			<div class="slider_outer">
				<div class="slider_inner">
					<h2 class="h2">Sneek Peek</h2>
					<div style="height: 550px; position: relative; overflow: hidden;">
						<div class="slider_inner_carousel main-gallery js-flickity" data-flickity-options='{ "wrapAround": "true", "pageDots": false, "lazyLoad": true, "lazyLoad": 1 }'>
							<div class="slider_block gallery-cell">
								<img data-flickity-lazyload="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/gallery/01.jpg" width="517px" alt="">
							</div>
							<div class="slider_block gallery-cell">
								<img data-flickity-lazyload="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/gallery/03.jpg" width="517px" alt="">
							</div>
							<div class="slider_block gallery-cell">
								<img data-flickity-lazyload="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/gallery/04.jpg" width="517px" alt="">
							</div>
							<div class="slider_block gallery-cell">
								<img data-flickity-lazyload="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/gallery/05.jpg" width="517px" alt="">
							</div>
							<div class="slider_block gallery-cell">
								<img data-flickity-lazyload="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/gallery/06.jpg" width="517px" alt="">
							</div>
							<div class="slider_block gallery-cell">
								<img data-flickity-lazyload="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/gallery/07.jpg" width="517px" alt="">
							</div>
							<div class="slider_block gallery-cell">
								<img data-flickity-lazyload="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/gallery/08.jpg" width="517px" alt="">
							</div>
							<div class="slider_block gallery-cell">
								<img data-flickity-lazyload="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/gallery/09.jpg" width="517px" alt="">
							</div>
							<div class="slider_block gallery-cell">
								<img data-flickity-lazyload="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/gallery/10.jpg" width="517px" alt="">
							</div>
							<div class="slider_block gallery-cell">
								<img data-flickity-lazyload="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/gallery/11.jpg" width="517px" alt="">
							</div>
							<div class="slider_block gallery-cell">
								<img data-flickity-lazyload="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/gallery/12.jpg" width="517px" alt="">
							</div>
							<div class="slider_block gallery-cell">
								<img data-flickity-lazyload="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/gallery/13.jpg" width="517px" alt="">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="billing_outer" id="shop">
				<?php
				if (!TBM\Bonds\Bonds2022::$taking_orders) :
				?>
					<div class="sold-out">
						<span>SOLD OUT</span>
					</div>
				<?php
				endif;
				?>
				<div class="billing_inner">
					<?php if (TBM\Bonds\Bonds2022::$free_underwear) : ?>
						<div class="billing_inner_top">
							<h2 class="h2">Shop</h2>
							<p class="p1" style="text-align: center;">
								<img src="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/RS_Bonds_CalendarMock.png" width="380px" alt="">
							</p>
							<p class="p1" style="text-align: center;">
								Congratulations! You’re eligible to receive a pair of Bonds Total Package™ Undies with your calendar.
							</p>
							<p class="p2" style="margin-bottom: 0; text-align: center;">
								Terms and Conditions for free gift below.
							</p>
						</div>
					<?php endif; ?>

					<div class="billing_inner_left" style="padding-right: 30px;">
						<?php
						get_template_part('page-templates/bonds-charity-2023/form');
						?>
					</div>

					<?php if (TBM\Bonds\Bonds2022::$free_underwear) : ?>
						<div style="border-left: 1px solid #707070;"></div>
						<div class="billing_inner_right">
							<h2 class="h2">Shop</h2>
							<p class="p1" style="text-align: center;">
								<img src="<?php echo RS_THEME_URL; ?>/page-templates/bonds-charity-2023/imgs/RS_Bonds_CalendarMock_2.jpg" width="380px" alt="">
							</p>
							<p class="p1" style="text-align: center;">
								FIRST 50 ORDERS RECEIVE FREE BONDS TOTAL PACKAGE™ UNDERWEAR
							</p>
							<p class="p2" style="margin-bottom: 0; text-align: center;">
								Terms and Conditions for free gift below.
							</p>
						</div>
					<?php endif; ?>
				</div>

				<div style="background-color: #fff; border: none; padding: 20px 20px 0; max-width: 1000px; width: 100%; margin: 0 auto;">
					<?php if (TBM\Bonds\Bonds2022::$free_underwear) : ?>
						<p class="pb-2">
							<strong>Terms and Conditions for free gift here.</strong>
						</p>
						<ul class="list-styled mb-2">
							<li class="ml-4">The free gift cannot be substituted for any other item, cash or credit.</li>
							<li class="ml-4">The free gift is sent with your order.</li>
							<li class="ml-4">The free gift is limited to the first 50 purchases of The Total Support Calendar.</li>
							<li class="ml-4">The free gift may or may not have your required size, in this case you may choose another size which is available or choose to not receive the free gift.</li>
							<li class="ml-4">The free gift offer may not be used in conjunction with any other offer or promotion that we run on our website, unless otherwise stated.</li>
							<li class="ml-4">The free gift will be dispatched with the relevant items from your qualifying order.</li>
							<li class="ml-4">If due to change of mind a customer wishes to return an order or item which qualified for a gift with purchase, the transaction can only be refunded if the gift with purchase item is also returned.</li>
							<li class="ml-4">In the event of any dispute, the decision of Rolling Stone Australia is final.</li>
							<li class="ml-4">We reserve the right to amend these terms and conditions at any time. If we do this we will publish the amended terms and conditions on this page.</li>
						</ul>
					<?php endif; ?>

					<p>When purchasing goods from this Website, these Terms and Conditions form a contract between the customer (you) and Rolling Stone Australia subsidiary of Seventh Street Media Pty Ltd and apply to the ordering, purchase, fulfilment and delivery of goods from the Website. Please read these Terms and Conditions carefully before placing your Order as these Terms and Conditions contain important information about the ordering, processing, fulfilment and delivery of goods, including limitations of liability.</p>
				</div>
			</div>
		<?php
		else :
		?>
			<div class=" l-page__content">
				<div class="l-section l-section--no-separator">
					<div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">
						<div style="margin: 1rem auto;">
							<?php echo get_the_password_form(); ?>
						</div>
					</div>
				</div>
			</div>
<?php
		endif; // Password protected
	endwhile;
	wp_reset_query();
endif;

?>
<?php get_template_part('template-parts/footer/footer'); ?>
</div><!-- .l-page__content -->
</div><!-- .l-page -->

<?php wp_footer(); ?>

<script>
	jQuery(document).ready(function($) {
		$('.l_toggle_menu_network').on('click', function(e) {
			e.preventDefault();
			$('#menu-network').toggle();
			$('#site_wrap').toggleClass('freeze');
			$('body').toggleClass('network-open');
			// $('#brands_wrap').height($(window).height() - jQuery('#adm_leaderboard-desktop').height() - 24);

			$('.is-header-sticky .l-header__search').toggle();
			$(this).toggleClass('expanded');
		});
	});
</script>

</body>

</html>