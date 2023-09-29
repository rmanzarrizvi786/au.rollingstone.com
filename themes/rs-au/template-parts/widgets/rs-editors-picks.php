<?php
/**
 * Editors Picks component.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-03-05
 */

global $post;

$editors_picks_data     = $data;
$editors_picks_title    = ! empty( $editors_picks_data['title'] ) ? $editors_picks_data['title'] : __( 'Our Picks', 'pmc-core' );
$editors_picks_carousel = ! empty( $editors_picks_data['carousel'] ) ? $editors_picks_data['carousel'] : '';
$editors_picks_count    = ! empty( $editors_picks_data['count'] ) ? $editors_picks_data['count'] : 5;
$editors_picks          = \Rolling_Stone\Inc\Carousels::get_carousel_posts( $editors_picks_carousel, $editors_picks_count );
$image_size             = 'ratio-4x3';

?>

<div class="l-blog__item">
	<div class="c-editors-picks">

		<h4 class="c-editors-picks__heading t-bold t-bold--upper t-bold--loose">
			<?php echo esc_html( $editors_picks_title ); ?>
		</h4><!-- .c-editors-picks__heading -->

		<ul class="c-editors-picks__list">

			<?php
			foreach ( $editors_picks as $pick ) {
				setup_postdata( $GLOBALS['post'] =& $pick ); // phpcs:ignore
				?>
				<li class="c-editors-picks__item">
					<article class="c-card c-card--picks">
						<a href="<?php echo esc_url( get_permalink( $pick->ID ) ); ?>" class="c-card__wrap">
							<figure class="c-card__image">

								<div class="c-crop c-crop--ratio-1x1">
									<?php
									rollingstone_the_post_thumbnail(
										$image_size, [
											'class'  => 'c-crop__img',
											'sizes'  => '100%',
											'srcset' => [ 120 ],
										]
									);
									?>
								</div><!-- .c-crop -->

							</figure><!-- .c-card__image -->
							<header class="c-card__header">
								<?php
								\PMC::render_template(
									CHILD_THEME_PATH . '/template-parts/article/card-heading.php', [
										'extra_classes' => 't-semibold',
									], true
								);

								\PMC::render_template(
									CHILD_THEME_PATH . '/template-parts/article/card-tag.php', [
										'show_tags' => true,
									], true
								);
								?>

							</header><!-- .c-card__header -->
						</a><!-- .c-card__wrap -->
					</article>
				</li><!-- .c-editors-picks__item -->
			<?php
			}    // end foreach loop

			// reset global post vars
			wp_reset_postdata();
			?>

		</ul><!-- .c-editors-picks__list -->
	</div><!-- .c-editors-picks -->
</div><!-- .l-blog__item -->
