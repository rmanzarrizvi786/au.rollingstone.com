<?php

/**
 * Template for the hero image and text overlay at the top of a featured article.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-5-14
 */

use Rolling_Stone\Inc\Featured_Article;

?>
<div class="c-featured-hero">
	<svg class="c-featured-hero__mask" viewBox="0 0 1400 82" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
			<g transform="translate(0.000000, -847.000000)" fill="#fff">
				<g transform="translate(-1.000000, 218.000000)">
					<path d="M1.0749947,629.265625 C1.02505547,629.642576 1,630.020187 1,630.398438 C1,672.702155 314.400675,706.996094 701,706.996094 C1087.59932,706.996094 1401,672.702155 1401,630.398438 C1401,630.020187 1400.97494,629.642576 1400.92501,629.265625 L1401,629.265625 L1401,710.132812 L1,710.132812 L1,629.265625 L1.0749947,629.265625 Z"></path>
				</g>
			</g>
		</g>
	</svg>

	<div class="c-featured-hero__text-layer">
		<?php if (class_exists('\PMC\Styled_Heading\Styled_Heading')) : ?>
			<!-- Y -->
			<?php
			// echo \PMC\Styled_Heading\Styled_Heading::get_styled_heading( Featured_Article::STYLED_HEADING_ID ); // WPCS: XSS ok.
			echo \PMC\Styled_Heading\Styled_Heading::get_styled_heading('rollingstone_featured_article_styled_heading');
			?>
		<?php endif; ?>
	</div><!-- /.c-featured-hero__text-layer -->

	<div class="c-featured-hero__crop c-crop c-crop--ratio-2x1">
		<?php
		rollingstone_the_post_thumbnail(
			'ratio-2x1',
			[
				'class'  => 'c-crop__img critical',
				'srcset' => [480, 960, 1400],
				'sizes'  => '100vw',
			]
		);
		?>
	</div><!-- .c-crop -->
</div><!-- /.c-featured-hero -->