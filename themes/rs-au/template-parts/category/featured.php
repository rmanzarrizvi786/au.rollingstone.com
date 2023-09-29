<?php
/**
 * Category Featured.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-25
 */


$term = get_queried_object();

if ( ! empty( $term ) && is_a( $term, '\WP_Term' ) ) {
	$term_title = $term->slug;
} else {
	$term_title = single_term_title( '', false );
}

// Get Data.
$articles = \Rolling_Stone\Inc\Carousels::get_carousel_posts( sanitize_title( $term_title ), 3, 'category' );
$articles = ( ! empty( $articles ) && is_array( $articles ) ) ? $articles : [];

if ( count( $articles ) < 3 ) {
	// Fill up the rest of the spots with recent articles.
	$recent_articles = new WP_Query(
		array(
			'category_name'  => $term_title,
			'posts_per_page' => 3 - count( $articles ),
			'order'          => 'DESC',
			'orderby'        => 'ID',
		)
	);

	if ( ! empty( $recent_articles->posts ) ) {
		$articles = array_merge( $articles, $recent_articles->posts );
	}
}

if ( empty( $articles ) ) {
	return;
}

?>

<section class="l-3-pack">

	<div class="l-3-pack__item l-3-pack__item--primary">
		<?php
		\PMC::render_template(
			CHILD_THEME_PATH . '/template-parts/article/card-overlay.php', [
				'article' => array_shift( $articles ),
			], true
		);
		?>
	</div><!-- .l-3-pack__item--primary -->

	<?php if ( ! empty( $articles ) ) : ?>
	<div class="l-3-pack__item l-3-pack__item--secondary">
		<?php
		\PMC::render_template(
			CHILD_THEME_PATH . '/template-parts/article/card-featured.php', [
				'article' => array_shift( $articles ),
			], true
		);
		?>
	</div><!-- .l-3-pack__item--secondary -->
	<?php endif; ?>

	<?php if ( ! empty( $articles ) ) : ?>
	<div class="l-3-pack__item l-3-pack__item--tertiary">
		<?php
		\PMC::render_template(
			CHILD_THEME_PATH . '/template-parts/article/card-featured.php', [
				'article' => array_shift( $articles ),
			], true
		);
		?>
	</div><!-- .l-3-pack__item--tertiary -->
	<?php endif; ?>

</section><!-- .l-3-pack -->
