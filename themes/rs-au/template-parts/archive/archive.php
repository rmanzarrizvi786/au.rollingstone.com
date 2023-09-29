<?php

/**
 * Archive Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-28
 */

?>

<div class="l-archive-top">
	<h1 class="l-archive-top__heading">
		<span class="t-super t-super--upper">
			<?php
			if (is_tag('sustainability')) {
				echo 'Sustainability';
			} else {
				the_archive_title();
			}
			?>
		</span>
	</h1><!-- .l-archive-top__heading -->
</div><!-- .l-archive-top -->

<div class="l-blog">
	<main class="l-blog__primary">

		<div class="l-blog__item l-blog__item--spacer-xl">
			<div class="c-section-header">
				&nbsp;
			</div><!-- .c-section-header -->
		</div><!-- .l-blog__item -->

		<div class="l-blog__item l-blog__item--spacer-s">
			<?php get_template_part('template-parts/archive/river'); ?>
		</div><!-- .l-blog__item -->

		<div class="l-blog__item l-blog__item--spacer-l">
			<?php get_template_part('template-parts/archive/pagination'); ?>
		</div><!-- .l-blog__item -->

	</main><!-- .l-blog__primary -->

	<?php if (is_active_sidebar('archive_right_1')) : ?>
		<aside class="l-blog__secondary">

			<?php dynamic_sidebar('archive_right_1'); ?>

		</aside><!-- .l-blog__secondary -->
	<?php endif; ?>

</div><!-- .l-blog -->