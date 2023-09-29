<?php

/**
 * Article Footer.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>

<footer>

	<?php
	get_template_part('template-parts/article/card-tags');
	get_template_part('template-parts/article/card-post-actions');

	if (!get_field('disable_ads') && !get_field('disable_ads_in_content')) :
		get_template_part('template-parts/ads/article-below-content');
	endif;
	?>

</footer>