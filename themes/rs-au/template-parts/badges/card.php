<?php
/**
 * Gallery Badge template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-19
 */

if ( 'pmc-gallery' === get_post_type() ) :

	if ( empty( $count ) ) {
		$images = \PMC\Gallery\View::get_instance()->fetch_gallery( get_the_ID() );
		$count  = ( ! empty( $images ) && is_array( $images ) ) ? count( $images ) : 0;
	}

	$classes = '';

	if ( ! empty( $mobile ) ) {
		$classes = ' c-badge--mobile-full';
	}

?>

<div class="c-card__badge">

	<?php
	\PMC::render_template(
		CHILD_THEME_PATH . '/template-parts/badges/badge-gallery.php', [
			'count'   => $count,
			'classes' => $classes,
		], true
	);
?>

</div><!-- /.c-card__badge -->

<?php
endif;
