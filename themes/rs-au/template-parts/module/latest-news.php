<?php
/**
 * Latest News module.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-13
 */

use \Rolling_Stone\Inc\RS_Query;

$rs_query    = new RS_Query(); // RS_Query::get_instance();
$latest_news = \Rolling_Stone\Inc\Carousels::get_carousel_posts( 'latest-news', 4 );
$latest_news = ( ! empty( $latest_news ) && is_array( $latest_news ) ) ? $latest_news : [];

if ( count( $latest_news ) < 4 ) {
	$args['posts_per_page'] = 4 - count( $latest_news );

	if ( ! empty( $latest_news ) ) {
		$args['post__not_in'] = wp_list_pluck( $latest_news, 'ID' );
	}

	$backfill = $rs_query->get_posts( $args );

	if ( ! empty( $backfill ) ) {
		$latest_news = array_merge( $latest_news, $backfill );
	}
}

?>

<div class="c-latest-list">

	<div class="c-latest-list__header">
		<h4 class="c-latest-list__heading">
			<span class="t-bold"><?php esc_html_e( 'The Latest', 'pmc-rollingstone' ); ?></span>
		</h4>
	</div><!-- .c-latest-list__header -->

	<?php
	foreach ( $latest_news as $index => $post ) {
		if ( 0 !== $index && ! \PMC::is_mobile() ) {
			?>
			<hr class="c-latest-list__line">
			<?php
		}
		?>
		<div class="c-latest-list__item">
			<?php
			\PMC::render_template( CHILD_THEME_PATH . '/template-parts/article/card-excerpt.php', [ 'article' => $post ], true );
			?>
		</div><!-- /.c-latest-list__item -->
		<?php
	}
	?>
	<a href="<?php echo esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ); ?>" class="c-latest-list__cta">
		<span class="t-semibold"><?php esc_html_e( 'More News', 'pmc-rollingstone' ); ?></span>
	</a>
</div><!-- .clatest-list -->
<?php
wp_reset_postdata();
?>
