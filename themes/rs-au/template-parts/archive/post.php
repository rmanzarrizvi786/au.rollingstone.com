<?php
/**
 * River Post.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>

<li class="l-river__item">
	<?php get_template_part( 'template-parts/article/card-river' ); ?>
</li><!-- .l-river__item -->

<?php
global $wp_query;

/*
 * Insert a newsletter subscription in the 4th position.
 */
if ( ! empty( $wp_query->posts[2] ) && get_the_ID() === $wp_query->posts[2]->ID ) :
?>
<li class="l-river__item">
	<?php get_template_part( 'template-parts/module/subscribe-newsletter-river' ); ?>
</li><!-- .l-river__item -->
<?php
endif;
