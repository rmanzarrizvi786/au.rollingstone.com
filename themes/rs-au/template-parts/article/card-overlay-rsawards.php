<?php

/**
 * Overlay Article.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

global $post;
setup_postdata($GLOBALS['post'] = &$article); // phpcs:ignore

$img_size = (is_front_page()) ? 'ratio-5x6' : 'ratio-1x1';

$sizes = (is_front_page()) ? '(max-width: 480px) 440px, (max-width: 767px) 725px, (max-width: 959px) 660px, 560px' : '(max-width: 767px) 100vw, (max-width: 959px) 66vw, 620px';

$srcset = (is_front_page()) ? [440, 560, 660, 725] : [400, 768, 1000, 1240];
?>

<article class="c-card c-card--overlay <?php echo (is_front_page()) ? 'c-card--overlay--home' : ''; ?>">
	<a href="https://au.rollingstone.com/music/music-news/bachelor-of-contemporary-music-southern-cross-university-21893/" class="c-card__wrap" target="_blank">
		<figure class="c-card__image">

			<div class="c-crop c-crop--<?php echo (is_front_page()) ? 'ratio-5x6' : 'ratio-1x1'; ?>">
				<img width="431" height="615" src="https://images-r2.thebrag.com/rs/uploads/2021/01/SJRSAwardsNominateHero-Vert.png"class="c-crop__img wp-post-image visible" alt="">
			</div><!-- .c-crop -->

		</figure><!-- .c-card__image -->

		<header class="c-card__header">

			<h3 class="c-card__heading t-bold">
				A Deeper Look Into the Bachelor of Contemporary Music Through Southern Cross University
			</h3><!-- .c-card__heading -->


			<?php
			// \PMC::render_template(
			// 	CHILD_THEME_PATH . '/template-parts/article/card-heading.php',
			// 	[
			// 		'super' => false,
			// 	],
			// 	true
			// );
			?>

			<?php
			// \PMC::render_template(
			// 	CHILD_THEME_PATH . '/template-parts/article/card-tag.php', [
			// 		'bold' => true,
			// 	], true
			// );
			?>

			<?php // get_template_part('template-parts/article/card-dek'); 
			?>

		</header><!-- .c-card__header -->
	</a><!-- .c-card__wrap -->
</article><!-- .c-card--overlay -->

<?php
wp_reset_postdata();
