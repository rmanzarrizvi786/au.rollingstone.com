<?php
/**
 * PMC - Footer.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-29
 */

$brands = [
	'Variety'          => 'https://variety.com/',
	'Deadline'         => 'https://deadline.com/',
	'Rolling Stone'    => 'https://www.rollingstone.com/',
	'WWD'              => 'https://wwd.com/',
	'HollywoodLife'    => 'https://hollywoodlife.com/',
	'Gold Derby'       => 'https://www.goldderby.com/',
	'Spy'              => 'https://spy.com/',
	'Robb Report'      => 'https://robbreport.com/',
	'Footwear News'    => 'https://footwearnews.com/',
	'BGR'              => 'https://bgr.com/',
	'IndieWire'        => 'https://www.indiewire.com/',
	'Sourcing Journal' => 'https://sourcingjournal.com/',
	'TVLine'           => 'https://tvline.com/',
	'Fairchild Media'  => 'https://fairchildlive.com/',
	'She Knows'        => 'https://www.sheknows.com/',
];

?>
<div class="c-pmc-footer">
	<div class="c-pmc-footer__wrap">

		<div class="c-pmc-footer__logo">
			<a href="https://www.pmc.com">
				<svg><use xlink:href="#svg-pmc-logo-black"></use></svg>
			</a>
		</div>

		<div class="c-pmc-footer__legal">
			&copy; Copyright 2018 Rolling Stone, LLC, a subsidiary of Penske Business Media, LLC. <br/>Powered by WordPress.com VIP
		</div>

		<div class="c-pmc-footer__dropdown c-pmc-footer--desktop" tabindex="0">
			<h4 class="c-pmc-footer__dropdown-trigger t-bold--upper">Our Brands</h4>
			<ul class="c-pmc-footer__dropdown-contents" tabindex="0">
			<?php
			// Output desktop footer links
			foreach ( $brands as $brand_name => $brand_url ) {
				?>
				<li><a class="c-pmc-footer__brand t-bold--upper" href="<?php echo esc_url( $brand_url ); ?>"><?php echo esc_html( $brand_name ); ?></a></li>
				<?php
			}
			?>

			</ul>
		</div>

	</div>

	<div class="l-footer__wrap c-pmc-footer--mobile">
		<div class="l-footer__nav">
			<nav class="l-footer__menu l-footer__menu--wide">
				<div class="c-page-nav c-page-nav--footer c-page-nav--pmc-footer c-page-nav--cta c-page-nav--1-column" data-dropdown="" data-collapsed-height="46px" data-expanded-height="148px">
					<ul class="c-page-nav__list">
						<li class="c-page-nav__item c-page-nav__item--heading is-active" data-ripple="">
							<span class="c-page-nav__link t-bold">Our Brands</span>
						</li><!-- .c-page-nav__item -->
						<?php
						// Output mobile footer links
						foreach ( $brands as $brand_name => $brand_url ) {
							?>
							<li class="c-page-nav__item"><a class="c-page-nav__link" data-ripple="inverted" href="<?php echo esc_url( $brand_url ); ?>"><?php echo esc_html( $brand_name ); ?></a></li>
							<?php
						}
						?>
					</ul><!-- .c-page-nav__list -->
				</div><!-- .c-page-nav--footer -->
			</nav>
		</div>	
	</div>

</div>

