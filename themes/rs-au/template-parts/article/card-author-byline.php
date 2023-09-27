<?php

/**
 * Card - Author Byline.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

// $authors_byline = \PMC\Core\Inc\Meta\Byline::get_instance();
// $authors        = $byline->get_authors( get_the_ID() );
if (get_field('author')) :
	$authors = get_field('author');
else :
	$authors_byline = new \PMC\Core\Inc\Meta\Byline();
	$authors = $authors_byline->tbm_get_authors(get_the_ID());
endif;
?>

<div class="c-byline">
	<div class="c-byline__authors">
		<em class="c-byline__by"><?php esc_html_e('By', 'pmc-rollingstone'); ?></em>

		<?php
		$author_count = 0;
		if (!empty($authors) && is_array($authors)) {
			$author_count = count($authors);
			foreach ($authors as $author) {
		?>
				<div class="c-byline__author">
					<?php if ($author_count > 1) { ?>
						<a href="<?php echo esc_url(get_author_posts_url($author->ID, $author->user_nicename)); ?>" class="c-byline__link t-bold t-bold--upper" rel="author noopener noreferrer external">
							<?php echo esc_html($author->display_name); ?>
						</a>
					<?php } else {
						$author_name = $author->display_name;
					?>
						<a href="<?php echo esc_url(get_author_posts_url($author->ID, $author->user_nicename)); ?>" class="c-byline__link t-bold t-bold--upper author" rel="author" data-author="<?php echo $author->display_name; ?>">
							<?php echo esc_html($author->display_name); ?>
							<svg class="c-byline__icon" width="14" height="14">
								<use href="#svg-icon-more"></use>
							</svg>
						</a>
						<div class="c-byline__detail">
							<?php
							\PMC::render_template(
								CHILD_THEME_PATH . '/template-parts/article/card-author-detail.php',
								[
									'author' => $author,
								],
								true
							);
							?>
						</div><!-- .c-byline__detail -->
					<?php } // If number of authors == 1 
					?>
				</div><!-- .c-byline__author -->
				<?php
				if (empty($count)) {
					$count = 1;
				} else {
					$count++;
				}

				if ($count < $author_count) {
				?>
					<span class="c-byline__amp t-bold">&amp;</span>
		<?php
				}
			}    // end foreach loop

		} else {
			echo '<span class="c-byline__amp t-bold author" data-author="' . $authors . '">' . $authors . '</span>';
		}
		?>

		<?php if ($author_count == 1) { ?>
			<div style="display: inline-block; float: right;">
				<?php
				if (shortcode_exists('shout_writer_beer')) :
					echo do_shortcode('[shout_writer_beer author="' . $author_name . '"]');
				elseif (shortcode_exists('shout_writer_coffee')) :
					echo do_shortcode('[shout_writer_coffee author="' . $author_name . '"]');
				endif; // If shout writer shortcode exists
				?>
			</div>
		<?php } // If number of authors == 1 
		?>

	</div><!-- .c-byline__authors -->
</div><!-- .c-byline -->