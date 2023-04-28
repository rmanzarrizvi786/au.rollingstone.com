<?php
/**
 * Card - Byline.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

 if ( ! in_category( 'music-videos' ) ) :

$classes = 't-copy';

if ( ! empty( $semibold ) ) {
	$classes = 't-semibold t-semibold--upper t-semibold--loose';
}

?>

<div class="c-card__byline <?php echo esc_attr( $classes ); ?>">
	<?php esc_html_e( 'By', 'pmc-rollingstone' ); ?>

	<?php
	if ( get_field( 'author' ) ) :
		echo ucwords( strtolower( get_field( 'author' ) ) );
	else :
		// Remove the links from the byline.
		$by_line = new \PMC\Core\Inc\Meta\Byline;
		echo wp_kses( $by_line->get_the_mini_byline( get_the_ID() ), [] );
		// echo wp_kses( \PMC\Core\Inc\Meta\Byline::get_instance()->get_the_mini_byline( get_the_ID() ), [] );
	endif;
	?>
</div><!-- c-card__byline -->
<?php endif; // If NOT in category music-videos
