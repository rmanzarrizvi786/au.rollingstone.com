<?php
/**
 * Overlay Article.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

global $post;
// setup_postdata( $GLOBALS['post'] =& $article ); // phpcs:ignore

$img_size = ( is_front_page() ) ? 'ratio-5x6' : 'ratio-1x1';

$sizes = ( is_front_page() ) ? '(max-width: 480px) 440px, (max-width: 767px) 725px, (max-width: 959px) 660px, 560px' : '(max-width: 767px) 100vw, (max-width: 959px) 66vw, 620px';

$srcset = ( is_front_page() ) ? [ 440, 560, 660, 725 ] : [ 400, 768, 1000, 1240 ];
?>

	<article class="c-card c-card--overlay <?php echo ( is_front_page() ) ? 'c-card--overlay--home' : ''; ?>">
		<a href="https://au.rollingstone.com/culture/culture-lists/50-minis-greatest-hits-pop-culture-music-art-22463/1-10-22464/" class="c-card__wrap">
			<figure class="c-card__image">

				<div class="c-crop c-crop--<?php echo ( is_front_page() ) ? 'ratio-5x6' : 'ratio-1x1'; ?>">
					<img src="https://au.rollingstone.com/wp-content/uploads/2021/01/mini-macca.jpg?resize=725,870&amp;w=440" data-src="https://au.rollingstone.com/wp-content/uploads/2021/01/mini-macca.jpg?resize=725,870&amp;w=440" class="c-crop__img wp-post-image visible" alt="" loading="lazy" data-srcset="https://au.rollingstone.com/wp-content/uploads/2021/01/mini-macca.jpg?resize=725,870&amp;w=440 440w, https://au.rollingstone.com/wp-content/uploads/2021/01/mini-macca.jpg?resize=725,870&amp;w=560 560w, https://au.rollingstone.com/wp-content/uploads/2021/01/mini-macca.jpg?resize=725,870&amp;w=660 660w, https://au.rollingstone.com/wp-content/uploads/2021/01/mini-macca.jpg?resize=725,870&amp;w=725 725w" sizes="(max-width: 480px) 440px, (max-width: 767px) 725px, (max-width: 959px) 660px, 560px" srcset="https://au.rollingstone.com/wp-content/uploads/2021/01/mini-macca.jpg?resize=725,870&amp;w=440 440w, https://au.rollingstone.com/wp-content/uploads/2021/01/mini-macca.jpg?resize=725,870&amp;w=560 560w, https://au.rollingstone.com/wp-content/uploads/2021/01/mini-macca.jpg?resize=725,870&amp;w=660 660w, https://au.rollingstone.com/wp-content/uploads/2021/01/mini-macca.jpg?resize=725,870&amp;w=725 725w" width="725" height="870">
				</div><!-- .c-crop -->

			</figure><!-- .c-card__image -->

			<header class="c-card__header">

			<h3 class="c-card__heading t-bold">
				50 of MINI’s Greatest Hits in The World of Pop Culture, Music and Art
			</h3><!-- .c-card__heading -->

			<p class="c-card__lead">
				The original Mini was voted the second most influential car of the 20th century.
			</p>


			</header><!-- .c-card__header -->
		</a><!-- .c-card__wrap -->
	</article><!-- .c-card--overlay -->

<?php
wp_reset_postdata();
