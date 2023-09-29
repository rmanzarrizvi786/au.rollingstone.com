<?php
/**
 * Excerpt article card template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-13
 */

?>

<article class="c-card c-card--excerpt">
	<a href="<?php echo esc_url( get_permalink() ); ?>" class="c-card__wrap">
		<header class="c-card__header">

			<?php \PMC::render_template( CHILD_THEME_PATH . '/template-parts/article/card-heading.php', [ 'copy' => true ], true ); ?>

			<?php get_template_part( 'template-parts/article/card-tag' ); ?>

		</header><!-- .c-card__header -->
	</a><!-- .c-card__wrap -->
</article><!-- .c-card--grid -->
