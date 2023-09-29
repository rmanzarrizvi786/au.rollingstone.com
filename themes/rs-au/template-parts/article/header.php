<?php

/**
 * Article Header.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>

<header class="l-article-header">

	<div class="l-article-header__block l-article-header__block--breadcrumbs t-semibold t-semibold--upper">
		<?php get_template_part('template-parts/article/card-breadcrumbs'); ?>
	</div><!-- .l-article-header__block--breadcrumbs -->

	<time class="l-article-header__block l-article-header__block--time t-semibold t-semibold--upper" datetime="<?php echo esc_attr(get_post_time('c', true, get_the_ID())); ?>" itemprop="datePublished" data-pubdate="<?php echo get_the_time('M d, Y'); ?>">
		<?php echo esc_attr(get_the_time('F j, Y g:iA', get_the_ID())); ?>
	</time><!-- .l-article-header__block--time -->

	<div class="l-article-header__siteserved-ad"> <?php // pmc_adm_render_ads( 'article-header-logo' ); 
													?> </div>

	<?php
	\PMC::render_template(
		CHILD_THEME_PATH . '/template-parts/badges/badge-sponsored.php',
		[
			'current_post' => get_post(),
		],
		true
	);
	?>

	<?php
	$title = get_post_meta(get_the_ID(), '_yoast_wpseo_title', true) ? get_post_meta(get_the_ID(), '_yoast_wpseo_title', true) : get_the_title();
	if (strpos($title, '%%title%%') !== FALSE) {
		$title = get_the_title();
	}

	$count_articles = isset($_POST['count_articles']) ? (int) $_POST['count_articles'] : 1;
	?>
	<h1 class="l-article-header__row l-article-header__row--title t-bold t-bold--condensed" data-href="<?php the_permalink(); ?>" data-title="<?php echo htmlentities($title); ?>" data-share-title="<?php echo urlencode($title); ?>" data-share-url="<?php echo urlencode(get_permalink()); ?>" data-article-number="<?php echo $count_articles; ?>">
		<?php the_title(); ?>
	</h1><!-- .l-article-header__row--title -->

	<?php if (has_excerpt()) : ?>
		<h2 class="l-article-header__row l-article-header__row--lead t-semibold t-semibold--condensed">
			<?php the_excerpt(); ?>
		</h2><!-- .l-article-header__row--lead -->
	<?php endif; ?>

	<?php rollingstone_review_stars(); ?>

	<div class="l-article-header__block l-article-header__block--byline">
		<?php get_template_part('template-parts/article/card-author-byline'); ?>
	</div><!-- .l-article-header__block--byline -->

	<div class="l-article-header__block l-article-header__block--share">
		<?php // get_template_part( 'template-parts/article/card-social-share', 'wide' ); 
		?>
	</div><!-- .l-article-header__block--share -->

</header><!-- .l-article-header -->