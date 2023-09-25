<?php
/**
 * Galleries Template
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-19
 */

if ( empty( $galleries ) || empty( $settings ) ) {
	return;
}

?>

<section class="l-carousel l-carousel--has-header" data-photo-carousel>
	<header class="l-carousel__header">
		<h3 class="c-heading c-heading--section"><?php echo esc_html( $settings['title'] ); ?></h3>
	</header>

	<footer class="l-carousel__footer">
		<a href="<?php echo esc_url( $settings['more_link'] ); ?>" class="c-more"><?php echo esc_html( $settings['more_text'] ); ?></a>
	</footer>

	<nav class="l-carousel__arrows"></nav>

	<div class="l-carousel__slider" data-trigger="photo-slider">
		<?php
		rollingstone_iterate_template_part(
			$galleries,
			'template-parts/gallery/galleries-item.php',
			[
				'settings' => $settings,
			]
		);
		?>
	</div><!-- .l-carousel__slider -->
</section><!-- .l-more-from.l-more-from--galleries -->
