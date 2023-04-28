<?php
/**
 * Card - Tags.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

$tags = get_the_terms( get_the_ID(), 'post_tag' );

if ( ! empty( $tags ) && is_array( $tags ) ) :

?>

<div class="c-tags">
	<p class="t-semibold">
		<?php esc_html_e( 'In This Article:', 'pmc-rollingstone' ); ?>

		<?php
		foreach ( $tags as $tag ) :
			$term_link = get_tag_link( $tag );

			if ( empty( $term_link ) || is_wp_error( $term_link ) ) {
				continue;
			}

			$delimiter               = '';
			empty( $count ) ? $count = 1 : $count++;

			if ( $count < count( $tags ) ) {
				$delimiter = ',';
			}
		?>
		<a href="<?php echo esc_url( $term_link ); ?>" title="<?php echo esc_attr( $tag->name ); ?>" class="c-tags__item"><?php echo esc_html( $tag->name ); ?></a><?php echo esc_html( $delimiter ); ?>
		<?php endforeach; ?>
	</p>
</div><!-- .c-tags -->

<?php endif; ?>
