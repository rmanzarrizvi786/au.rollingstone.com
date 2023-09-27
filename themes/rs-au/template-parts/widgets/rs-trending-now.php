<?php
/**
 * Trending Now List Widget Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-25
 */

global $post;

$trending_widget_data  = $data;
$trending_posts_limit  = isset( $trending_widget_data['count'] ) ? absint( $trending_widget_data['count'] ) : 10;
$trending_widget_title = ! empty( $trending_widget_data['title'] ) ? $trending_widget_data['title'] : __( 'Trending', 'pmc-rollingstone' );
$period                = isset( $trending_widget_data['period'] ) ? absint( $trending_widget_data['period'] ) : 30;
$type                  = isset( $trending_widget_data['type'] ) ? $trending_widget_data['type'] : 'most_viewed';
$image_size            = isset( $trending_widget_data['image_size'] ) ? $trending_widget_data['image_size'] : 'thumbnail';
$style                 = ! empty( $trending_widget_data['style'] ) ? $trending_widget_data['style'] : 'sidebar';
$is_country            = rollingstone_is_country();
$font_class            = ( $is_country ) ? 't-country' : 't-bold';
$trending_class        = $style; // sidebar / hero
$trending_div          = ( is_front_page() || is_home() ) ? 'l-home-top__sidebar-item' : 'l-blog__item';

if ( $is_country ) {
	if ( is_single() ) {
		$trending_class = 'framed c-trending--framed--country';
	} else {
		$trending_class = 'hero c-trending--hero--country';
	}
}

$trending = \PMC\Core\Inc\Top_Posts::get_posts( $trending_posts_limit, 365, $period, $type );

if ( empty( $trending ) ) {
	return;
}

$num = count($trending);

if($num > 5) {
	array_splice($trending, count($trending) - 5, 5);
}

?>

<div class="<?php echo esc_attr( $trending_div ); ?>">
	<div class="c-trending c-trending--<?php echo esc_attr( $trending_class ); ?>">
		<?php if ( $is_country ) : ?>

			<h4 class="c-trending__heading t-country">
				<?php echo esc_html( $trending_widget_title ); ?>
			</h4><!-- .c-trending__heading -->

		<?php else : ?>

			<h4 class="c-trending__heading t-bold t-bold--loose">
				<?php echo esc_html( $trending_widget_title ); ?>
			</h4><!-- .c-trending__heading -->

		<?php endif; ?>

		<ol class="c-trending__list">
			<?php
			foreach ( $trending as $index => $_post ) :
				$post = get_post( $_post['post_id'] );
				setup_postdata( $post );

				if ( 0 === $index && 'sidebar' === $style && ! $is_country ) {
					?>
					<li class="c-trending__item c-trending__item--featured">
						<a href="<?php the_permalink(); ?>" class="c-trending__link">

							<div class="c-trending__image c-crop c-crop--ratio-3x2">
								<?php
								rollingstone_the_post_thumbnail(
									'ratio-3x2', array(
										'class'  => 'c-crop__img',
										'sizes'  => '300px',
										'srcset' => [ 300, 600 ],
									)
								);
								?>
							</div><!-- .c-crop -->

							<h5 class="c-trending__title">
								<div class="c-trending__number"><span class="c-trending__counter <?php echo esc_attr( $font_class ); ?>"></span></div>
								<span class="c-trending__caption t-semibold"><?php the_title(); ?></span>
							</h5><!-- .c-trending__title -->

						</a><!-- .c-trending__link -->
					</li><!-- .c-trending__item -->
					<?php
					continue;
				}

				?>
				<li class="c-trending__item">
					<a href="<?php the_permalink(); ?>" class="c-trending__link">
						<h5 class="c-trending__title">
							<div class="c-trending__number"><span class="c-trending__counter <?php echo esc_attr( $font_class ); ?>"></span></div>
							<span class="c-trending__caption t-semibold"><?php the_title(); ?></span>
						</h5><!-- .c-trending__title -->
					</a><!-- .c-trending__link -->
				</li><!-- .c-trending__item -->
			<?php
			endforeach;
			wp_reset_postdata();
			?>
		</ol><!-- .c-trending__list -->
	</div><!-- .c-trending -->
</div>
