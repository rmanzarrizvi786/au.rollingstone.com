<?php

/**
 * River.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

extract($args);
?>

<ul class="l-river">

	<?php
	$args = [
		'post_status' => 'publish',
		'has_password'   => FALSE,
		'post_type' => ['pmc-nz', 'post'],
		'paged' => $paged,
		'meta_query' => [
			[
				'key'   => 'add_to_nz_content',
				'value' => '1',
			]
		]
	];
	$query = new WP_Query($args);

	if ($query->have_posts()) :
		while ($query->have_posts()) :
			$query->the_post();

			if (is_author()) {
				if (get_field('author') || get_field('Author')) {
					continue;
				}
			}

			get_template_part('template-parts/archive/post');
		endwhile;
	endif;
	?>

	<div class="l-blog__item l-blog__item--spacer-l">
		<?php get_template_part('template-parts/nz/pagination', null, ['query' => $query]); ?>
	</div><!-- .l-blog__item -->

</ul><!-- .l-river -->