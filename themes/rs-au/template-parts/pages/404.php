<?php
/**
 * 404 Page template
 *
 * Moved here from the theme root so that it can be run through PMC_Cache
 * and 404 pages can be cached instead of being generated by WP on every request
 * which is taxing on the servers.
 *
 * @ticket BR-412
 *
 * @since 2019-09-17
 */

get_header();
?>
	<div class="l-page__content">

		<div class="c-404" style="background-image: url(<?php echo esc_url(TBM_CDN . '/assets/images/404-background.jpg' ); ?>);">
			<h1 class="c-404__title t-super"><?php esc_html_e( '404', 'pmc-rollingstone' ); ?></h1>
			<h2 class="c-404__subtitle t-semibold"><?php esc_html_e( 'Page Not Found', 'pmc-rollingstone' ); ?></h2>
			<p class="c-404__description t-country"><?php esc_html_e( 'OOPS! We can\'t find what you\'re looking for.', 'pmc-rollingstone' ); ?></p>
			<a href="/" class="c-404__btn t-semibold"><?php esc_html_e( 'Return to Homepage', 'pmc-rollingstone' ); ?></a>
		</div><!-- /.c-404 -->

		<?php get_template_part( 'template-parts/footer/footer' ); ?>
	</div><!-- .l-page__content -->

<?php
get_footer();

//EOF
