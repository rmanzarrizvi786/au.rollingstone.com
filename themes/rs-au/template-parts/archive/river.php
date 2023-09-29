<?php

/**
 * River.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>

<ul class="l-river">

	<?php
	$pinned_post = NULL;
	if (is_tag('sustainability')) {
		// $pinned_post = 40578; // 27050; // Pinned for 'Sustainability' tag
		if (!is_null($pinned_post)) {
			$pinned_args = [
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'p' => $pinned_post
			];

			$pinned_query = new WP_Query($pinned_args);
			if ($pinned_query->have_posts()) :
				while ($pinned_query->have_posts()) :
					$pinned_query->the_post();

					if (is_author()) {
						if (get_field('author') || get_field('Author')) {
							continue;
						}
					}

					get_template_part('template-parts/archive/post');
				endwhile;
				wp_reset_query();
			endif;
		}
	}
	?>

	<?php
	if (have_posts()) :
		while (have_posts()) :
			the_post();

			if ($pinned_post == get_the_ID()) {
				continue;
			}

			if (is_author()) {
				if (get_field('author') || get_field('Author')) {
					continue;
				}
			}


			get_template_part('template-parts/archive/post');
		endwhile;
	endif;
	?>

</ul><!-- .l-river -->