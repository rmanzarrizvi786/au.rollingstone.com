<?php

/**
 * Top Stories - Homepage.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-13
 */

use \Rolling_Stone\Inc\RS_Query;

$rs_query    = new RS_Query(); // RS_Query::get_instance();
$top_stories = \Rolling_Stone\Inc\Carousels::get_carousel_posts('top-stories', 3);

$most_viewed = null;

if (get_option('force_most_viewed')) {
	$most_viewed = get_option('force_most_viewed');
} else if (get_option('most_viewed_yesterday')) {
	$most_viewed = get_option('most_viewed_yesterday');
}

if (!is_null($most_viewed)) :
	$trending_story_args = array(
		'post_status' => 'publish',
		// 'post__not_in' => $exclude_posts,
		'posts_per_page' => 1,
		'has_password'   => FALSE,
		'post_type' => ['post', 'page', 'list'],
		'p' => $most_viewed, //get_option('most_viewed_yesterday'),
	);
	$trending_story = new WP_Query($trending_story_args);
	$top_stories = $trending_story->posts;
endif;

if (get_option('tbm_home_middle_1_ID') && get_option('tbm_home_middle_1_ID') != get_option('most_viewed_yesterday')) {
	$home_middle_1_story_args = array(
		'post_status' => 'publish',
		'post__not_in' => $exclude_posts,
		'posts_per_page' => 1,
		'has_password'   => FALSE,
		'p' => get_option('tbm_home_middle_1_ID'),
	);
	$home_middle_1_story = new WP_Query($home_middle_1_story_args);
	$top_stories = (!empty($top_stories) && is_array($top_stories)) ? array_merge($top_stories, $home_middle_1_story->posts) : $home_middle_1_story->posts;
}

if (get_option('tbm_home_middle_2_ID') && get_option('tbm_home_middle_2_ID') != get_option('most_viewed_yesterday')) {
	$home_middle_2_story_args = array(
		'post_status' => 'publish',
		'post__not_in' => $exclude_posts,
		'posts_per_page' => 1,
		'has_password'   => FALSE,
		'p' => get_option('tbm_home_middle_2_ID'),
	);
	$home_middle_2_story = new WP_Query($home_middle_2_story_args);
	$top_stories = (!empty($top_stories) && is_array($top_stories)) ? array_merge($top_stories, $home_middle_2_story->posts) : $home_middle_2_story->posts;
}

$top_stories = (!empty($top_stories) && is_array($top_stories)) ? $top_stories : [];

// If the carousel is empty, let's snag three latest posts.
if (count($top_stories) < 3) {
	$args['posts_per_page'] = 3 - count($top_stories);

	if (!empty($top_stories)) {
		$args['post__not_in'] = wp_list_pluck($top_stories, 'ID');
	}

	$backfill = $rs_query->get_posts($args);

	if (!empty($backfill)) {
		$top_stories = array_merge($top_stories, $backfill);
	}
}

?>
<div class="l-home-top__3-pack">
	<section class="l-3-pack l-3-pack--reversed">
		<?php
		foreach ($top_stories as $index => $post) :
			if (0 === $index) {
		?>
				<div class="l-3-pack__item l-3-pack__item--primary">
					<?php
					// if (time() < strtotime('2020-12-08')) :
					// \PMC::render_template(CHILD_THEME_PATH . '/template-parts/article/card-overlay-rsawards.php', ['article' => $post], true);
					// else :
					\PMC::render_template(CHILD_THEME_PATH . '/template-parts/article/card-overlay.php', ['article' => $post], true);
					// \PMC::render_template(CHILD_THEME_PATH . '/template-parts/article/card-overlay-forced.php', ['article' => $post], true);
					// endif;
					?>
				</div><!-- .l-3-pack__item--primary -->
			<?php
				continue;
			}
			?>
			<div class="l-3-pack__item l-3-pack__item--<?php echo (1 === $index) ? 'secondary' : 'tertiary'; ?>">
				<?php
				\PMC::render_template(
					CHILD_THEME_PATH . '/template-parts/article/card-featured.php',
					[
						'article'  => $post,
						'show_dek' => false,
					],
					true
				);
				?>
			</div><!-- .l-3-pack__item--[secondary|tertiary] -->
		<?php endforeach; ?>
	</section><!-- .l-3-pack -->
</div><!-- /.l-home-top__3-pack -->