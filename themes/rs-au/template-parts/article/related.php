<?php
/**
 * Related Content.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-01
 */

global $post;

if ( empty( $items['posts'] ) ) {
	return;
}

?>

<div class="l-article-content__pull l-article-content__pull--left">
	<div class="<?php echo esc_attr( apply_filters( 'rollingstone_related_box_class', 'c-related' ) ); ?>">

		<h4 class="c-related__heading t-bold t-bold--upper">
			<?php echo esc_html( apply_filters( 'rollingstone_related_box_title', __( 'Related', 'pmc-rollingstone' ) ) ); ?>
		</h4><!-- .c-trending__heading -->

		<?php
		foreach ( $items['posts'] as $index => $post ) {
			setup_postdata( $post );
		?>

		<a href="<?php the_permalink(); ?>" class="c-related__link">

			<?php if ( 0 === $index ) : ?>
			<div class="c-related__img">
				<div class="c-crop c-crop--size-related">
					<?php
					$allowed_tags                    = wp_kses_allowed_html( 'post' );
					$allowed_tags['img']['data-src'] = true;
					echo wp_kses(
						get_the_post_thumbnail(
							$item->ID, 'ratio-3x2', [
								'class' => 'c-crop__img',
							]
						), $allowed_tags
					);
					?>
				</div>
			</div>
			<?php endif; ?>

			<h5 class="c-related__caption t-bold">
				<?php the_title(); ?>
			</h5>
		</a>

		<?php } ?>

	</div><!-- .c-related -->
</div><!-- .l-article-content__pull--left -->

<?php
wp_reset_postdata();
