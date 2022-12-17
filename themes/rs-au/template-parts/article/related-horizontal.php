<?php
/**
 * Horizontal Related Articles.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-03
 */

global $post;

$items = apply_filters( 'pmc_core_filter_related_articles', \Related::get_instance()->get_related_items() );

if ( empty( $items['posts'] ) ) {
	return;
}

?>

<div class="c-related-horizontal">
	<h4 class="c-related-horizontal__title t-bold t-bold--upper">
		<?php esc_html_e( 'Related Articles', 'pmc-rollingstone' ); ?>
	</h4>

	<?php
	foreach ( $items['posts'] as $post ) {
		setup_postdata( $post );
		?>
		<div class="c-related-horizontal__item">
			<a href="<?php the_permalink(); ?>" class="c-related-horizontal__link t-semibold t-semibold--upper">
				<?php the_title(); ?>
			</a>
		</div><!-- /.c-related-horizontal__item -->
	<?php } ?>
</div><!-- .c-related-horizontal -->

<?php
wp_reset_postdata();
