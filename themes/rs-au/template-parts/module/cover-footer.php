<?php
/**
 * Cover Image - Footer.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-11-04
 */

?>

<div class="l-footer__cover">
	<a href="<?php echo esc_url( trailingslashit( home_url( 'subscribe-magazine' ) ) ); ?>" target="_blank">
		<?php
		// rollingstone_the_issue_cover( 250, [ 'class' => 'l-footer__cover-image' ] );
		rollingstone_next_issue_cover( 250, [ 'class' => 'l-footer__cover-image' ] );
		?>
	</a>
</div><!-- .l-footer__cover -->
