<?php
/**
 * Video Grid article Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-20
 */

?>

<article class="c-card c-card--video-grid">
	<a href="<?php the_permalink(); ?>" class="c-card__wrap">
		<figure class="c-card__image">

			<div class="c-crop c-crop--ratio-7x4">
				<?php
				rollingstone_the_post_thumbnail(
					'ratio-7x4', [
						'class'  => 'c-crop__img',
						'srcset' => [ 300, 250 ],
						'sizes'  => '(max-width: 767px) 250px, 300px',
					]
				);
				?>
			</div><!-- .c-crop -->

		</figure><!-- .c-card__image -->

		<header class="c-card__header">
			<?php get_template_part( 'template-parts/article/card-heading' ); ?>
		</header><!-- .c-card__header -->
	</a><!-- .c-card__wrap -->
</article><!-- .c-card--video-grid -->
