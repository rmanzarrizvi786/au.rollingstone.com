<?php

/**
 * Overlay Article.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

setup_postdata($GLOBALS['post'] = &$article); // phpcs:ignore

$img_size = (is_front_page() || is_page_template('page-templates/page-nz.php')) ? 'ratio-1x1' : 'ratio-3x2';
$srcset   = (is_front_page() || is_page_template('page-templates/page-nz.php')) ? [190, 210, 240, 270, 350] : [306, 768];
$sizes    = (is_front_page() || is_page_template('page-templates/page-nz.php'))
	? '(max-width: 480px) 210px, (max-width: 767px) 350px, (max-width: 959px) 240px, (max-width: 1100px) 270px, 190px'
	: '(max-width: 479px) 100vw, (max-width: 767px) 50vw, (max-width: 959px) 34vw, 306px';

if (!isset($show_dek) && !is_category()) {
	$show_dek = true;
} else {
	$show_dek = false;
}

?>

<article class="c-card c-card--featured<?php echo (is_front_page() || is_page_template('page-templates/page-nz.php')) ? '-home' : ''; ?>" <?php echo is_front_page() ? 'id="home-c-card-' . get_the_ID() . '"' : ''; ?>>
	<a href="<?php the_permalink(); ?>" class="c-card__wrap">
		<figure class="c-card__image">

			<div class="c-crop c-crop--size-featured<?php echo (is_front_page() || is_page_template('page-templates/page-nz.php')) ? '-home' : ''; ?>">
				<?php
				rollingstone_the_post_thumbnail(
					$img_size,
					array(
						'class'  => 'c-crop__img',
						'srcset' => $srcset,
						'sizes'  => $sizes,
					)
				);
				?>
			</div><!-- .c-crop -->

		</figure><!-- .c-card__image -->

		<header class="c-card__header">

			<?php
			get_template_part('template-parts/article/card-heading');
			get_template_part('template-parts/article/card-tag');

			if ($show_dek) {
				get_template_part('template-parts/article/card-dek');
			}
			?>

		</header><!-- .c-card__header -->
	</a><!-- .c-card__wrap -->
</article><!-- .c-card--featured -->

<?php
wp_reset_postdata();
