<?php
/**
 * Rolling Stone Video Card Template.
 *
 * @package pmc-rollingstone-2018
 * @since   2018-04-21
 */

//\PMC\Core\Inc\Theme::get_instance()->get_the_primary_term( 'category' );

$PMC_Primary_Taxonomy = new PMC_Primary_Taxonomy();
$term          = $PMC_Primary_Taxonomy->get_primary_taxonomy( null, 'category' );

$cat_name      = ( ! $term || is_wp_error( $term ) ) ? __( 'Uncategorized', 'pmc-rollingstone' ) : $term->name;
$cat_link      = ( ! $term || is_wp_error( $term ) ) ? '' : get_term_link( $term );
$class         = isset( $class ) ? $class : '';
$is_video_home = isset( $is_video_home ) ? $is_video_home : false;
$is_carousel   = ( ! empty( $is_carousel ) ) ? $is_carousel : false;
?>

<article class="c-card c-card--video <?php echo esc_attr( $class ); ?>">
	<div class="c-card__wrap">
		<figure class="c-card__image">

			<div style="max-width: 678px;" class="c-crop c-crop--video c-crop--ratio-video">
				<a href="<?php the_permalink(); ?>">

					<?php // get_template_part( 'template-parts/badges/video-large' ); ?>

					<?php
					rollingstone_the_post_thumbnail(
						'ratio-video', [
							'class'  => 'c-crop__img',
							'srcset' => [ 350, 600, 800 ],
							'sizes'  => '(max-width: 959px) 92%, (max-width: 1300px) 55%, 775px',
						]
					);
					?>

				</a>
				<div data-video-crop>

					<div hidden>
						<?php

						rollingstone_get_video_source();

						// if ( $is_carousel ) {
						// 	rollingstone_render_carousal_jwplayer();
						// } else {
						// 	rollingstone_get_video_source();
						// }

						?>
					</div>


				</div>
			</div><!-- .c-crop -->

		</figure><!-- .c-card__image -->

		<header class="c-card__header">

			<h3 class="c-card__heading t-bold">

				<a href="<?php the_permalink(); ?>" data-video-gallery-card-heading>
					<?php rollingstone_the_title(); ?>
				</a>
			</h3><!-- .c-card__heading -->

			<?php if ( $is_video_home ) : ?>

				<span class="c-card__tag t-semibold t-semibold--upper t-semibold--loose" data-video-gallery-card-tag>

					<?php echo esc_html( $cat_name ); ?>

				</span>

			<?php else : ?>

				<span class="c-card__tag t-semibold t-semibold--upper t-semibold--loose">

					<a href="<?php echo esc_url( $cat_link ); ?>" class="u-color--black" data-video-gallery-card-tag>
						<?php echo esc_html( $cat_name ); ?>
					</a>
				</span><!-- c-card__tag -->

			<?php endif; ?>

			<?php if ( ! is_tax ( 'vcategory' ) && ! is_home() && ! is_front_page() ) : ?>
			<p class="c-card__lead t-copy" data-video-gallery-card-lead>
				<?php rollingstone_the_excerpt(); ?>
			</p><!-- /.c-card__lead t-copy -->
			<?php endif; ?>

			<?php if ( false === $is_video_home ) : ?>

				<div class="c-card__social-bar">
					<?php // get_template_part( 'template-parts/article/card-social-share', 'wide' ); ?>
				</div><!-- /.c-card__social-bar -->

			<?php endif; ?>

		</header><!-- .c-card__header -->
	</div><!-- .c-card__wrap -->
</article><!-- .c-card -->
