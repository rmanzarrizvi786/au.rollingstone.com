<?php
/**
 * Rolling Stone Special Coverage Template.
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2018-04-18
 */

$article_title = ! empty( $data['title'] ) ? $data['title'] : __( 'Special Coverage', 'pmc-rollingstone' );
$carousel      = ! empty( $data['carousel'] ) ? $data['carousel'] : 'none';
$articles      = \Rolling_Stone\Inc\Carousels::get_carousel_posts( $carousel, 4 );

if ( ! empty( $articles ) ) :
?>

<div class="l-section l-section--no-separator l-section--with-bottom-margin">

	<div class="c-coverage">
		<h3 class="c-coverage__title">
			<span class="c-coverage__badge">
				<svg class="c-coverage__icon"><use xlink:href="#svg-rs-badge"></use></svg>
			</span><!-- /.c-coverage__badge -->
			<span class="t-bold t-bold--upper"><?php echo esc_html( $article_title ); ?></span>
		</h3><!-- /.c-coverage__title -->

		<ul class="c-newswire c-newswire--dark">
		<?php
		foreach ( $articles as $article ) :
			setup_postdata( $GLOBALS['post'] =& $article ); // phpcs:ignore
				?>
				<li class="c-newswire__item">

					<article class="c-card c-card--coverage">
						<a href="<?php the_permalink(); ?>" class="c-card__wrap">
							<figure class="c-card__image">

								<?php
								\PMC::render_template(
									CHILD_THEME_PATH . '/template-parts/badges/gallery.php', [
										'mobile' => true,
									], true
								);
								?>

								<div class="c-crop c-crop--ratio-3x2">

									<?php
									rollingstone_the_post_thumbnail(
										'ratio-3x2', array(
											'class'  => 'c-crop__img',
											'sizes'  => '(max-width: 480px) 210px, (max-width: 767px) 345px, 285px',
											'srcset' => [ 160, 285, 335 ],
										)
									);
									?>

								</div><!-- .c-crop -->

							</figure><!-- .c-card__image -->

							<header class="c-card__header">

								<?php get_template_part( 'template-parts/article/card-heading' ); ?>

							</header><!-- .c-card__header -->
						</a><!-- .c-card__wrap -->
					</article><!-- .c-card--brand -->

				</li><!-- /.c-newswire__item -->
			<?php
		endforeach;
		wp_reset_postdata();
		?>
		</ul><!-- .c-newswire -->
	</div><!-- .c-coverage -->

</div><!-- .l-section -->

<?php
endif;
