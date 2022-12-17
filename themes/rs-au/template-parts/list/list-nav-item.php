<?php
/**
 * Lists Nav Item  Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-04
 */

if ( isset( $url ) && isset( $range_start ) && isset( $range_end ) ) :

?>

<li class="l-header__menu-item">
	<a href="<?php echo esc_url( $url ); ?>"
		class="l-header__menu-link"
		data-list-nav-item
		data-list-range-start="<?php echo esc_attr( $range_start ); ?>"
		data-list-range-end="<?php echo esc_attr( $range_end ); ?>">
		<?php echo esc_html( $range_start . ( $range_end === $range_start ? '' : '-' . $range_end ) ); ?>
	</a>
</li><!-- .l-header__menu-item -->

<?php
endif;
