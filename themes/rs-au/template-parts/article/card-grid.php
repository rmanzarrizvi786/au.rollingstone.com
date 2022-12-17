<?php
/**
 * Card - Grid.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-13
 */

global $post;

setup_postdata( $GLOBALS['post'] =& $article ); // phpcs:ignore

$c_cards_grid_loop_modifier = ( 0 === $index ) ? 'c-card--grid--primary' : 'c-card--grid--secondary';

$sizes = ( 0 === $index )
? '(max-width: 767px) 730px, (max-width: 380px) 345px, 285px'
: '(max-width: 480px) 210px, (max-width: 767px) 345px, 285px';

$srcset = ( 0 === $index ) ? array( 160, 285, 335, 730 ) : array( 160, 285, 335 );

?>

<article class="c-card c-card--grid <?php echo esc_attr( $c_cards_grid_loop_modifier ); ?> <?php echo ( isset( $modifier ) ) ? 'c-card--grid-' . esc_attr( $modifier ) : ''; ?>">
	<a href="<?php the_permalink(); ?>" class="c-card__wrap">
		<figure class="c-card__image">

			<?php get_template_part( 'template-parts/article/card-badge-video' ); ?>

			<div class="c-crop c-crop--ratio-3x2">

				<?php
				rollingstone_the_post_thumbnail(
					'ratio-3x2',
					array(
						'class'  => 'c-crop__img',
						'sizes'  => $sizes,
						'srcset' => $srcset,
					)
				);
				?>

			</div><!-- .c-crop -->

		</figure><!-- .c-card__image -->

		<header class="c-card__header">

			<?php
			get_template_part( 'template-parts/article/card-heading' );
			get_template_part( 'template-parts/article/card-tag' );
			get_template_part( 'template-parts/article/card-byline' );
			get_template_part( 'template-parts/article/card-dek' );
			?>

		</header><!-- .c-card__header -->
	</a><!-- .c-card__wrap -->
</article><!-- .c-card--grid -->

<?php
wp_reset_postdata();
