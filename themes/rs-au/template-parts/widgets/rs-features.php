<?php
/**
 * Rolling Stone Features Template.
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2018-04-13
 */

use \Rolling_Stone\Inc\RS_Query;

$features_title = ! empty( $data['title'] ) ? $data['title'] : __( 'Featured Stories', 'pmc-rollingstone' );
$carousel       = ! empty( $data['carousel'] ) ? $data['carousel'] : 'none';
$features       = \Rolling_Stone\Inc\Carousels::get_carousel_posts( $carousel, 5 );
$rs_query       = new RS_Query(); // RS_Query::get_instance();
$post_count     = 4;

if ( empty( $features ) ) {
	$query_args = array(
		'posts_per_page' => $post_count,
	);

	$features = $rs_query->get_posts( $query_args );
}

if ( empty( $features ) ) {
	return;
}

?>
	<div class="l-section l-section--no-separator l-section--with-bottom-margin">

		<div class="c-features">

			<?php
			if ( isset( $features[0] ) ) :
				setup_postdata( $GLOBALS['post'] =& $features[0] ); // phpcs:ignore
				?>
				<article class="c-features__main">
					<a href="<?php echo esc_url( get_permalink() ); ?>" class="c-features__main-wrap">

						<figure class="c-features__main-image">

							<div class="c-crop c-crop--size-features-main">
								<?php
								rollingstone_the_post_thumbnail(
									'ratio-3x2', array(
										'class'  => 'c-crop__img',
										'sizes'  => '(max-width: 480px) 480px, (max-width: 820px) 780px, 510px',
										'srcset' => [ 510, 780, 480 ],
									)
								);
								?>
							</div><!-- .c-crop -->

						</figure><!-- .c-features__main-image -->

						<span class="c-features__tag t-bold"><?php echo esc_html( $features_title ); ?></span>

						<header class="c-features__main-header">
							<h3 class="c-features__main-headline t-bold">
								<?php rollingstone_the_title(); ?>
							</h3><!-- /.c-features__main-headline -->
							<p class="c-features__main-lead t-copy">
								<?php rollingstone_the_excerpt(); ?>
							</p><!-- /.c-features__main-lead -->
						</header><!-- /.c-features__main-header -->

					</a><!-- /.c-features__main-wrap -->
				</article><!-- /.c-features__main -->
			<?php
			endif;
			unset( $features[0] );
			?>
			<div class="c-features__slider c-slider" data-slider>

				<a href="" class="c-slider__nav c-slider__nav--left" data-slider-nav="prev">
					<svg class="c-slider__icon">
						<use xlink:href="#svg-icon-chevron"></use>
					</svg>
				</a>
				<a href="" class="c-slider__nav c-slider__nav--right" data-slider-nav="next">
					<svg class="c-slider__icon">
						<use xlink:href="#svg-icon-chevron"></use>
					</svg>
				</a>

				<div class="c-slider__track" data-slider-track>
					<?php
					if ( ! empty( $features ) ) :
						foreach ( $features as $feature ) :
							setup_postdata( $GLOBALS['post'] =& $feature ); // phpcs:ignore
						?>
						<article class="c-features__item c-slider__item" data-slider-item>
							<a href="<?php echo esc_url( get_permalink() ); ?>" class="c-features__item-wrap">

								<figure class="c-features__item-image">

									<div class="c-crop c-crop--ratio-11x14">
										<?php
										rollingstone_the_post_thumbnail(
											'ratio-11x14', array(
												'class'  => 'c-crop__img',
												'sizes'  => '440px',
												'srcset' => [ 440, 660 ],
											)
										);
										?>
									</div><!-- .c-crop -->

								</figure><!-- .c-features__item-image -->

								<header class="c-features__item-header">
									<h3 class="c-features__item-headline t-semibold">
										<?php rollingstone_the_title(); ?>
									</h3><!-- /.c-features__item-headline -->
								</header><!-- /.c-features__item-header -->

							</a><!-- /.c-features__item-wrap -->
						</article><!-- /.c-features__item c-slider__item -->
					<?php
						endforeach;
					endif;
					?>
				</div><!-- /.c-slider__track -->
			</div><!-- /.c-features__slider -->
		</div><!-- /.c-features -->


	</div><!-- /.l-section -->
<?php
wp_reset_postdata();
