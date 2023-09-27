<?php
/**
 * List article template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-01
 */

?>

<article>

	<?php get_template_part( 'template-parts/article/header' ); ?>

	<?php get_template_part( 'template-parts/article/featured-media' ); ?>

	<div class="c-list__lead c-content">
		<?php the_content(); ?>
	</div>

</article>
