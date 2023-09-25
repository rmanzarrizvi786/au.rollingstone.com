<?php

/**
 * Template name: homepage
 *
 * @package pmc-rollingstone-2018
 * @since 2019-11-23
 */

get_header();
?>

<div class="l-page__content">

	<div class="l-section__content">
		<div class="posts_grid l-section__grid">
			<div class="c-cards-grid">
				<?php
				$args = array(
					'category_name' => 'culture',
					"posts_per_page" => 6,
					"orderby"        => "date",
					"order"          => "DESC"
				); ?>
				<?php $my_posts = get_posts($args);

				foreach ($my_posts as $post) {
					$imagePath = str_replace('http://localhost/rollingstone/wp-content', S3_UPLOADS_BUCKET_URL, get_the_post_thumbnail_url($post->ID, 'full'));
				?>
					<div class="c-cards-grid__item" style="display:;">

						<article class="c-card c-card--grid c-card--grid--primary ">
							<?php
							//$featured_img_url = get_the_post_thumbnail_url($post->ID,'full'); 

							//str_replace();
							?>

							<a href="<?php the_permalink(); ?>" class="c-card__wrap">

								<figure class="c-card__image">


									<div class="c-crop c-crop--ratio-3x2">

										<img width="900" height="600" src="<?php echo $imagePath; ?>" data-src="<?php echo $imagePath; ?>" class="c-crop__img wp-post-image visible">
									</div><!-- .c-crop -->

								</figure><!-- .c-card__image -->

								<header class="c-card__header">



									<div class="c-badge c-badge--sponsored">
									</div><!-- .c-badge--sponsored -->

									<h3 class="c-card__heading t-bold">
										<?php the_title(); ?>

									</h3><!-- .c-card__heading -->

									<div class="c-card__tag t-bold t-bold--upper">
										<span class="screen-reader-text">Posted in:</span>
										<span class="c-card__featured-tag"> Music Features</span>
									</div><!-- c-card__tag -->
									<div class="c-card__byline t-copy">
										By
										Matt Slocum</div><!-- c-card__byline -->

									<p class="c-card__lead">
										When talking about the hottest rappers in Australia right now, Melbourne's Miko Mal (f.k.a Babyface Mal) is definitely at the top.</p><!-- c-card__lead -->


								</header><!-- .c-card__header -->
							</a><!-- .c-card__wrap -->
						</article><!-- .c-card--grid -->

					</div>
				<?php

					// ... Use regular 'the_title()', 'the_permalink()', etc. loop functions here.

				}
				?>


			</div>
		</div>
	</div>
	<?php if (is_active_sidebar('homepage-bottom')) : ?>
		<?php dynamic_sidebar('homepage-bottom'); ?>
	<?php endif; ?>


	<?php get_template_part('template-parts/footer/footer'); ?>
</div><!-- .l-page__content ends Test -->
<?php
get_footer();
