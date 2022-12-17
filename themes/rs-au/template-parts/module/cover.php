<?php
/**
 * Cover/subscribe.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

$class = ( isset( $mega ) ) ? 'c-cover--mega' : '';
$width = ( isset( $width ) ) ? $width : 80;
?>
<div class="c-cover t-bold <?php echo esc_attr( $class ); ?>">

	<?php if ( isset( $mega ) ) : ?>

		<a href="<?php echo esc_url( trailingslashit( home_url( 'subscribe-magazine' ) ) ); ?>" class="c-cover__cta">
			<?php esc_html_e( 'Subscribe', 'pmc-rollingstone' ); ?>
		</a><!-- .c-cover__cta" -->

	<?php else : ?>

		<a href="<?php echo esc_url( trailingslashit( home_url( 'subscribe-magazine' ) ) ); ?>" class="c-cover__cta c-cover__cta--header" target="_blank">
			<?php esc_html_e( 'Subscribe', 'pmc-rollingstone' ); ?>
		</a>

	<?php endif; ?>

	<a href="<?php echo esc_url( trailingslashit( home_url( 'subscribe-magazine' ) ) ); ?>" target="_blank">
		<?php
		// rollingstone_the_issue_cover( $width, [ 'class' => 'c-cover__image' ] );
		rollingstone_next_issue_cover( $width, [ 'class' => 'c-cover__image' ] );
		?>
	</a>

</div><!-- .c-cover -->
