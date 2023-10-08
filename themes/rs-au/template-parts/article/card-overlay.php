<?php

/**
 * Overlay Article.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

global $post;
setup_postdata($GLOBALS['post'] = &$article); // phpcs:ignore

$img_size = (is_front_page() || is_page_template('page-templates/page-nz.php')) ? 'ratio-5x6' : 'ratio-1x1';

$sizes = (is_front_page() || is_page_template('page-templates/page-nz.php')) ? '(max-width: 480px) 440px, (max-width: 767px) 725px, (max-width: 959px) 660px, 560px' : '(max-width: 767px) 100vw, (max-width: 959px) 66vw, 620px';

$srcset = (is_front_page() || is_page_template('page-templates/page-nz.php')) ? [440, 560, 660, 725] : [400, 768, 1000, 1240];
?>

<article class="c-card c-card--overlay <?php echo (is_front_page() || is_page_template('page-templates/page-nz.php')) ? 'c-card--overlay--home' : ''; ?>">
	<a href="<?php the_permalink(); ?>" class="c-card__wrap">
		<figure class="c-card__image" id="img-article-<?php echo $post->ID; ?>">

			<div class="c-crop c-crop--<?php echo (is_front_page() || is_page_template('page-templates/page-nz.php')) ? 'ratio-5x6' : 'ratio-1x1'; ?>">
				<?php
				if (get_the_ID() ===  19276) : ?>
					<img width="431" height="615" src="https://images-r2.thebrag.com/rs/uploads/2020/11/SJRSAwardsNominateHero-Vert.jpg" class="c-crop__img wp-post-image visible" alt="">
				<?php elseif (get_the_ID() ===  21893) : ?>
					<img width="431" height="615" src="https://images-r2.thebrag.com/rs/uploads/2021/01/SJRSAwardsNominateHero-Vert.png" class="c-crop__img wp-post-image visible" alt="">
				<?php else :
					rollingstone_the_post_thumbnail(
						$img_size,
						array(
							'class'  => 'c-crop__img',
							'sizes'  => $sizes,
							'srcset' => $srcset,
						)
					);
				endif;
				?>
			</div><!-- .c-crop -->

		</figure><!-- .c-card__image -->

		<header class="c-card__header">

			<?php
			\PMC::render_template(
				CHILD_THEME_PATH . '/template-parts/article/card-heading.php',
				[
					'super' => false,
				],
				true
			);
			?>

			<?php
			\PMC::render_template(
				CHILD_THEME_PATH . '/template-parts/article/card-tag.php',
				[
					'bold' => true,
				],
				true
			);
			?>

			<?php get_template_part('template-parts/article/card-dek'); ?>

		</header><!-- .c-card__header -->
	</a><!-- .c-card__wrap -->
</article><!-- .c-card--overlay -->

<?php
wp_reset_postdata();
