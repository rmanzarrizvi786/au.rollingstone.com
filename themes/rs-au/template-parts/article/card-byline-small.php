<?php
/**
 * Template for a simple byline.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-5-14
 */

if ( isset( $author ) ) : ?>

<div class="c-byline c-byline--small">
	<div class="c-byline__authors">
		<em class="c-byline__by"><?php esc_html_e( 'By', 'pmc-rollingstone' ); ?></em>
		<div class="c-byline__author">
			<a href="<?php echo esc_url( get_author_posts_url( $author->ID, $author->user_nicename ) ); ?>" class="c-byline__link t-bold t-bold--upper" rel="author">
				<?php echo wp_kses_post( $author->display_name ); ?>
			</a>
		</div><!-- .c-byline__author -->
	</div><!-- .c-byline__authors -->
</div><!-- .c-byline -->

<?php
endif;
