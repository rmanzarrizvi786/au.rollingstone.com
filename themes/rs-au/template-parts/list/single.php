<?php
/**
 * List single content template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-01
 */

use Rolling_Stone\Inc\Lists;

?>

<div class="l-page__content">
	<div class="l-blog">
		<main class="l-blog__primary">
			<?php get_template_part('template-parts/list/article'); ?>
			<?php get_template_part('template-parts/list/list'); ?>
			<?php get_template_part('template-parts/article/footer'); ?>
		</main><!-- .l-blog__primary -->

		<aside class="l-blog__secondary">
			<?php dynamic_sidebar('article_right_sidebar'); ?>
		</aside><!-- .l-blog__secondary -->

	</div><!-- .l-blog -->

	<?php get_template_part('template-parts/footer/footer'); ?>

</div><!-- /.l-page__content -->