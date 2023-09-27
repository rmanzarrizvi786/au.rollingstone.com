<?php
/**
 * Category Section Widget.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-03-13
 */

$section_title = ( ! empty( $data['title'] ) ) ? $data['title'] : __( 'Featured', 'pmc-rollingstone' );

// Columnist data.
$columnist_title    = ( ! empty( $data['columnist_title'] ) ) ? $data['columnist_title'] : __( 'Columnists', 'pmc-rollingstone' );
$columnist_carousel = ( ! empty( $data['columnist_carousel'] ) ) ? $data['columnist_carousel'] : '';

// Review data.
$reviews_title    = ( ! empty( $data['reviews_title'] ) ) ? $data['reviews_title'] : __( 'Reviews', 'pmc-rollingstone' );
$reviews_carousel = ( ! empty( $data['reviews_carousel'] ) ) ? $data['reviews_carousel'] : '';

$section_category  = ( ! empty( $data['category'] ) ) ? $data['category'] : '';
$term_link         = get_term_link( $section_category, 'category' );
$the_list_carousel = ( ! empty( $data['the_list_carousel'] ) ) ? $data['the_list_carousel'] : '';

if ( empty( $section_category ) ) {
	return;
}

$query_args = array(
	'posts_per_page' => 6,
	'category_name'  => sanitize_text_field( $section_category ),
);

$section_query = new WP_Query( $query_args );
$section_posts = $section_query->posts;

?>
<div class="l-section" data-section="<?php echo esc_attr( $section_title ); ?>">

	<script>
		PMC_RS_setHomeAppearance( <?php echo wp_json_encode( $section_title ); ?> );
	</script>

	<div class="l-section__header">
		<div class="c-section-header">
			<h3 class="c-section-header__heading t-bold t-bold--condensed">
				<?php echo esc_html( $section_title ); ?>
			</h3>
			<?php if ( ! is_wp_error( $term_link ) ) : ?>
				<a href="<?php echo esc_url( $term_link ); ?>" class="c-section-header__cta t-semibold t-semibold--upper t-semibold--loose">
					<?php esc_html_e( 'View All', 'pmc-rollingstone' ); ?>
				</a>
			<?php endif; ?>
			<p class="c-section-header__msg">
				<?php esc_html_e( 'You have set the display of this section to be hidden.', 'pmc-rollingstone' ); ?><br>
				<?php esc_html_e( 'Click the button to the right to show it again.', 'pmc-rollingstone' ); ?>
			</p><!-- /.c-section-header__msg -->
			<a href="#" class="c-section-header__btn" data-section-toggle>
				<span class="c-section-header__hide t-semibold t-semibold--upper"><?php esc_html_e( 'Hide', 'pmc-rollingstone' ); ?></span>
				<span class="c-section-header__show t-semibold t-semibold--upper"><?php esc_html_e( 'Show', 'pmc-rollingstone' ); ?></span>
				<svg class="c-section-header__btn-arrow">
					<use xlink:href="#svg-icon-arrow-down"></use>
				</svg>
			</a>
		</div><!-- .c-section-header -->
	</div><!-- /.l-section__header -->

	<div class="l-section__content">
		<div class="l-section__grid">
			<div class="c-cards-grid">
				<?php
				if ( ! empty( $section_posts ) ) :
					foreach ( $section_posts as $index => $post ) :

						if ( 2 === $index ) {
							// Show sponsored post.
							//get_template_part( '/template-parts/ads/grid-sponsor' );
						}
					?>
					<div class="c-cards-grid__item">
						<?php
						\PMC::render_template(
							CHILD_THEME_PATH . '/template-parts/article/card-grid.php', [
								'article' => $post,
								'index'   => $index,
							], true
						);
						?>
					</div><!-- /.c-cards-grid__item -->
				<?php
					endforeach;
				endif;
				?>
			</div><!-- .c-cards-grid -->
		</div><!-- /.l-section__grid -->

		<div class="l-section__sidebar">
			<div class="l-section__sticky c-sticky c-sticky--size-grow" data-section-ad="<?php echo esc_attr( $section_title ); ?>">
				<script>
					PMC_RS_toggleHomeAd( <?php echo wp_json_encode( $section_title ); ?> );
				</script>
				<div class="c-sticky__item">
					<?php
					// get_template_part( 'template-parts/ads/section-ad-300x250' );
					include( locate_template( 'template-parts/ads/section-ad-300x250.php', false, false ) );
					?>
				</div><!-- /.c-sticky__item -->
			</div><!-- /.l-section__sticky c-sticky c-sticky--size-grow -->

			<div class="l-section__sidebar-footer">
				<?php
				\PMC::render_template(
					CHILD_THEME_PATH . '/template-parts/article/the-list.php', [
						'the_list_carousel' => $the_list_carousel,
					], true
				);
				?>
			</div><!-- /.l-section__sidebar-footer -->
		</div><!-- /.l-section__sidebar -->

		<?php if ( ! empty( $reviews_carousel ) && 'none' !== $reviews_carousel ) : ?>
			<div class="l-section__block">
				<?php
				\PMC::render_template(
					CHILD_THEME_PATH . '/template-parts/reviews/reviews.php', [
						'reviews_title'    => $reviews_title,
						'reviews_carousel' => $reviews_carousel,
					], true
				);
				?>
			</div><!-- /.l-section__block -->
		<?php endif; ?>

		<?php if ( ! empty( $columnist_carousel ) && 'none' !== $columnist_carousel ) : ?>
			<div class="l-section__block">
				<?php
				\PMC::render_template(
					CHILD_THEME_PATH . '/template-parts/columnist/columnists.php', [
						'columnist_title'    => $columnist_title,
						'columnist_carousel' => $columnist_carousel,
					], true
				);
				?>
			</div><!-- /.l-section__block -->
		<?php endif; ?>
	</div><!-- /.l-section__content -->
</div><!-- /.l-section -->
