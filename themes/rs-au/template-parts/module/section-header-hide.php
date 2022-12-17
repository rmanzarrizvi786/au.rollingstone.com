<?php
/**
 * Home Page Section Header template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-19
 */

// Section title.
$title = isset( $title ) ? $title : __( 'Latest News', 'pmc-rollingstone' );
$url   = isset( $url ) ? $url : '#';

?>

<div class="c-section-header">
	<h3 class="c-section-header__heading t-bold t-bold--condensed">
		<?php echo esc_html( $title ); ?>
	</h3>

	<a href="<?php echo esc_url( $url ); ?>" class="c-section-header__cta t-semibold t-semibold--upper t-semibold--loose">
		<?php esc_html_e( 'View All', 'pmc-rollingstone' ); ?>
	</a>

	<p class="c-section-header__msg">
		<?php esc_html_e( 'You have set the display of this section to be hidden.', 'pmc-rollingstone' ); ?><br>
		<?php esc_html_e( 'Click the button to the right to show it again.', 'pmc-rollingstone' ); ?>
	</p><!-- /.c-section-header__msg -->

	<a href="#" class="c-section-header__btn" data-section-toggle>
		<span class="c-section-header__hide t-semibold t-semibold--upper"><?php esc_html_e( 'Hide', 'pmc-rollingstone' ); ?></span>
		<span class="c-section-header__show t-semibold t-semibold--upper"><?php esc_html_e( 'Show', 'pmc-rollingstone' ); ?></span>
		<svg class="c-section-header__btn-arrow"><use xlink:href="#svg-icon-arrow-down"></use></svg>
	</a>
</div><!-- .c-section-header -->
