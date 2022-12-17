<?php
/**
 * River Card.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>

<article class="c-card c-card--domino">
	<a href="<?php the_permalink(); ?>" class="c-card__wrap">
		<figure class="c-card__image">

			<?php get_template_part( 'template-parts/article/card-badge-video' ); ?>
			<?php get_template_part( 'template-parts/article/card-badge-gallery' ); ?>

			<div class="c-crop c-crop--size-domino">
				<?php
				the_post_thumbnail(
					'ratio-4x3', array(
						'class' => 'c-crop__img',
					)
				);
				?>
			</div><!-- .c-crop -->

		</figure><!-- .c-card__image -->

		<header class="c-card__header">

			<?php get_template_part( 'template-parts/article/card-heading' ); ?>
			<?php get_template_part( 'template-parts/article/card-tag' ); ?>

			<?php
			\PMC::render_template(
				CHILD_THEME_PATH . '/template-parts/article/card-byline.php', [
					'semibold' => true,
				], true
			);
			?>

			<?php get_template_part( 'template-parts/article/card-dek' ); ?>

		</header><!-- .c-card__header -->
	</a><!-- .c-card__wrap -->
</article><!-- .c-card--domino -->
