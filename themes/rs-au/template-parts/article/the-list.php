<?php
/**
 * Section "The List".
 *
 * @package pmc-rollingstone-2018
 * @since 2018-03-16
 */

if ( empty( $the_list_carousel ) ) {
	return;
}

$the_list = \Rolling_Stone\Inc\Carousels::get_carousel_posts( $the_list_carousel, 1 );

if ( empty( $the_list[0] ) ) {
	return;
}

setup_postdata( $GLOBALS['post'] =& $the_list[0] ); // phpcs:ignore
?>

<a href="<?php the_permalink(); ?>" class="c-the-list">
	<h4 class="c-the-list__headline">
		<span class="t-bold">
			<?php rollingstone_the_title(); ?>
		</span>
	</h4><!-- /.c-the-list__headline -->
</a><!-- .c-the-list -->

<?php
wp_reset_postdata();
