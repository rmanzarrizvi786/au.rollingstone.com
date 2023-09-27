<?php

/**
 * NZ Pagination.
 */

extract($args);
?>

<div class="c-pagination">
	<?php previous_posts_link(__('Previous', 'pmc-rollingstone'), $query->max_num_pages); ?>
	<?php next_posts_link(__('Next', 'pmc-rollingstone'), $query->max_num_pages); ?>
</div><!-- .c-pagination -->