<?php
/**
 * Card - Heading.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

$classes = 'c-card__heading t-bold';

if ( ! empty( $super ) ) {
	$classes = 'c-card__heading t-super';
}

if ( ! empty( $extra_classes ) ) {
	$classes .= ' ' . $extra_classes;
}

if ( ! empty( $copy ) ) {
	$classes = 'c-card__heading t-copy';
}
?>

<?php
\PMC::render_template(
	CHILD_THEME_PATH . '/template-parts/badges/badge-sponsored.php',
	[
		'current_post' => get_post(),
	],
	true
);
?>

<h3 class="<?php echo esc_attr( $classes ); ?>">
	<?php if ( ! empty( $paid ) ) : ?>
	<svg class="c-card__paywall-icon"><use xlink:href="#svg-icon-key"></use></svg>
	<?php endif; ?>

	<?php rollingstone_the_title(); ?>
</h3><!-- .c-card__heading -->
