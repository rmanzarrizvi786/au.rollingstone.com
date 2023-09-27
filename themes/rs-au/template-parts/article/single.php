<?php

/**
 * Single Post Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

$count_articles = isset($_POST['count_articles']) ? (int) $_POST['count_articles'] : 1;
if (!post_password_required($post)) :
?>
	<article class="single_story" data-id="<?php echo get_the_ID(); ?>" data-premium="<?php echo get_field('premium') ? 'true' : 'false'; ?>">
		<?php
		$categories = get_the_category(get_the_ID());
		$CategoryCD = '';
		if ($categories) :
			foreach ($categories as $category) :
				$CategoryCD .= $category->slug . ' ';
			endforeach; // For Each Category
		endif; // If there are categories for the post

		$tags = get_the_tags(get_the_ID());
		$TagsCD = '';
		if ($tags) :
			foreach ($tags as $tag) :
				$TagsCD .= $tag->slug . ' ';
			endforeach; // For Each Tag
		endif; // If there are tags for the post
		?>
		<div hidden class="cats" data-category="<?php echo $CategoryCD; ?>" data-tags="<?php echo $TagsCD; ?>"></div>

		<?php get_template_part('template-parts/article/header'); ?>

		<?php get_template_part('template-parts/article/featured-media'); ?>

		<div class="l-article-content">


			<?php
			// <!-- DailyMotion {{ -->
			// if (1 == $count_articles && shortcode_exists('dailymotion_playlist')) :
			// 	echo do_shortcode('[dailymotion_playlist]');
			// endif;
			// <!-- }} DailyMotion -->
			?>

			<div class="fc-article-copy">
				<?php get_template_part('template-parts/article/content'); ?>
			</div>

			<?php get_template_part('template-parts/article/footer'); ?>

		</div><!-- .l-article-content -->
	</article>

	<p>&nbsp;</p>
<?php elseif ($count_articles == 1) : ?>
	<style>
		.load-more {
			display: none;
		}

		input {
			border: 1px solid #ccc;
			padding: .5rem;
		}
	</style>
	<div style="margin: 1rem auto;">
		<?php echo get_the_password_form(); ?>
	</div>
<?php
endif;
