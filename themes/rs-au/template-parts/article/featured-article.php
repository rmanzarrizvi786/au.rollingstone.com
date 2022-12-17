<?php

/**
 * Template for featured article.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-5-10
 */

use Rolling_Stone\Inc\Featured_Article;
use Rolling_Stone\Inc\Media;

global $post;
setup_postdata($post);

if (!empty($post->custom_thumbnail_id)) {
	$post_image_credit = pmc_get_photo_credit($post->custom_thumbnail_id);
} else {
	$post_image_credit = pmc_get_photo_credit(get_post_thumbnail_id());
}

?>
<div class="l-featured-article">

	<?php get_template_part('template-parts/article/featured-article-hero'); ?>

	<article class="c-featured-article c-content" data-id="<?php echo get_the_ID(); ?>" data-premium="<?php echo get_field('premium') ? 'true' : 'false'; ?>">
		<header class="c-featured-article__header">
			<?php if (!empty($post_image_credit)) { ?>
				<div class="c-featured-article__image-credit">
					<p class="c-featured-article__image-credit-source">
						<?php echo esc_html($post_image_credit); ?>
					</p>
				</div>
			<?php } ?>
			<div class="c-featured-article__breadcrumbs t-semibold t-semibold--upper">
				<?php get_template_part('template-parts/article/card-breadcrumbs'); ?>
			</div>
			<div class="c-featured-article__title">
				<?php if (class_exists('\PMC\Styled_Heading\Styled_Heading')) : ?>
					<?php
					// echo \PMC\Styled_Heading\Styled_Heading::get_styled_heading( Featured_Article::STYLED_HEADING_ID ); // WPCS: XSS ok.
					echo \PMC\Styled_Heading\Styled_Heading::get_styled_heading('rollingstone_featured_article_styled_heading');
					?>
				<?php endif; ?>
			</div><!-- /.c-featured-article__title -->
			<h2 class="c-featured-article__subtitle t-bold">
				<?php rollingstone_the_excerpt(); // Get the Excerpt here for Featured, instead of repurposing rollingstone_the_title() 
				?>
			</h2><!-- /.c-featured-article__title -->
			<div class="c-featured-article__meta">
				<?php
				if (get_field('author')) :
					$authors = get_field('author');
				else :
					$authors_byline = new \PMC\Core\Inc\Meta\Byline();
					$authors = $authors_byline->tbm_get_authors(get_the_ID());
				endif;
				// foreach ( get_coauthors( get_the_ID() ) as $author ) :
				if (!empty($authors) && is_array($authors)) {
					foreach ($authors_byline->tbm_get_authors(get_the_ID()) as $author) :
				?>
						<div class="c-featured-article__avatar">
							<?php
							echo get_avatar($author, 100, 'blank', '');
							// echo wp_kses(
							// 	coauthors_get_avatar( $author, 100 ),
							// 	wp_kses_allowed_html( Media::ATTACHMENT_KSES_CONTEXT )
							// );
							?>
						</div><!-- /.c-featured-article__avatar -->
						<div class="c-featured-article__byline">
							<?php
							\PMC::render_template(
								CHILD_THEME_PATH . '/template-parts/article/card-byline-small.php',
								[
									'author' => $author,
								],
								true
							);
							?>
						</div><!-- /.c-featured-article__byline -->
				<?php endforeach;
				} else {
					echo '<div>By ' . $authors . '</div>';
				} ?>
				<time class="c-featured-article__time t-semibold t-semibold--upper" datetime="<?php echo esc_attr(get_post_time('c', true, get_the_ID())); ?>">
					<?php echo wp_kses_post(get_the_time('F j, Y', get_the_ID())); ?>
				</time><!-- .l-article-header__block--time -->
				<?php // get_template_part( 'template-parts/article/card-social-share', 'vertical' ); 
				?>
			</div><!-- /.c-featured-article__meta -->
		</header><!-- /.c-featured-article__header -->

		<?php if (post_password_required($post)) : ?>
			<style>
				.load-more {
					display: none;
				}

				input {
					border: 1px solid #ccc;
					padding: .5rem;
				}
			</style>
		<?php endif; ?>

		<div class="fc-article-copy" style="max-width: 39.6875rem; margin-left: auto; margin-right: auto;"><?php the_content(); ?></div>

		<div class="c-featured-article__tags">
			<?php get_template_part('template-parts/article/card-tags'); ?>
		</div><!-- /.c-featured-article__tags -->

		<div class="c-featured-article__post-actions">
			<?php get_template_part('template-parts/article/card-post-actions'); ?>
		</div><!-- /.c-featured-article__post-actions -->

	</article><!-- /.c-featured-article -->
</div>
<?php get_template_part('template-parts/footer/footer'); ?>
<?php if (is_active_sidebar('featured-article-bottom')) : ?>
	<?php dynamic_sidebar('featured-article-bottom'); ?>
<?php
endif;
