<?php
/**
 * Card - Breadcrumbs.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

// $theme = \PMC\Core\Inc\Theme::get_instance();
$items = ThemeSetup::get_breadcrumb();

?>

<span class="c-breadcrumbs">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="c-breadcrumbs__link"><?php esc_html_e( 'Home', 'pmc-rollingstone' ); ?></a>
	<?php
	if ( ! empty( $items ) ) :
		foreach ( $items as $item ) :
			$link = get_term_link( $item->term_id );

			if ( empty( $item->name ) || empty( $link ) ) {
				continue;
			}
	?>
	<a href="<?php echo esc_url( $link ); ?>" class="c-breadcrumbs__link"><?php echo esc_html( $item->name ); ?></a>
	<?php
		endforeach;
	endif;
	?>
</span><!-- .c-breadcrumbs -->
