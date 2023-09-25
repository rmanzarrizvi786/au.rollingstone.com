<?php

/**
 * Top Stories - NZ.
 */

use \Rolling_Stone\Inc\RS_Query;

$rs_query    = new RS_Query(); // RS_Query::get_instance();

$most_viewed = null;

$trending_story_args = [
	'post_status' => 'publish',
	'posts_per_page' => 3,
	'has_password'   => FALSE,
	'post_type' => ['pmc-nz', 'post'],
	'meta_query' => [
		[
			'key'   => 'add_to_nz_content',
			'value' => '1',
		]
	]
];
$trending_story = new WP_Query($trending_story_args);
$top_stories = $trending_story->posts;

$top_stories = (!empty($top_stories) && is_array($top_stories)) ? $top_stories : [];

if (empty($top_stories))
	return;

// If the carousel is empty, let's snag three latest posts.
if (count($top_stories) < 3) {
	$trending_story_args['posts_per_page'] = 3 - count($top_stories);

	if (!empty($top_stories)) {
		$trending_story_args['post__not_in'] = wp_list_pluck($top_stories, 'ID');
	}

	$backfill = $rs_query->get_posts($trending_story_args);

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
					\PMC::render_template(CHILD_THEME_PATH . '/template-parts/article/card-overlay.php', ['article' => $post], true);
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