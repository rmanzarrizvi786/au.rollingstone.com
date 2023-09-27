<?php
/**
 * Rolling Stone Video Card Thumb Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-21
 */

$class    = isset( $class ) ? $class : '';
$bold     = isset( $bold ) ? $bold : '';
$on_tax_c = ( isset( $on_tax ) && true === $on_tax ) ? 'c-card c-card--video-grid' : "c-card c-card--video-thumb {$class} t-semibold";

// $term     = \PMC\Core\Inc\Theme::get_instance()->get_the_primary_term( 'category' );

$PMC_Primary_Taxonomy = new PMC_Primary_Taxonomy();
$term          = $PMC_Primary_Taxonomy->get_primary_taxonomy( null, 'category' );

$cat_name = ( ! $term || is_wp_error( $term ) ) ? __( 'Uncategorized', 'pmc-rollingstone' ) : $term->name;
$cat_link = ( ! $term || is_wp_error( $term ) ) ? '' : get_term_link( $term );

if ( is_wp_error( $cat_link ) ) {
	return;
}

$title      = rollingstone_get_the_title();
$lead       = rollingstone_get_the_excerpt();
$card_class = 'c-card__wrap'; // ( isset( $featured ) && true === $featured ) ? 'c-card__wrap is-active' : 'c-card__wrap';

?>

<article class="<?php echo esc_attr( $on_tax_c ); ?>">
	<a href="<?php the_permalink(); ?>" class="<?php echo esc_attr( $card_class ); ?>">
		 <!-- data-video-gallery-thumb
		data-tag="<?php // echo esc_attr( $cat_name ); ?>"
		data-heading="<?php // echo esc_attr( $title ); ?>"
		data-lead="<?php // echo esc_attr( wp_strip_all_tags( $lead ) ); ?>"
		data-permalink="<?php // the_permalink(); ?>"
		data-tag-permalink="<?php // echo esc_url( $cat_link ); ?>"> -->

		<figure class="c-card__image2">
			 <!-- data-active-text="<?php // echo esc_attr( 'Now Playing', 'pmc-rollingstone' ); ?>"> -->
			<div hidden>
				<?php
					rollingstone_render_carousal_jwplayer();
				?>
			</div>

			<?php
			if ( ! is_archive() ) {
				get_template_part( 'template-parts/badges/video' );
			}
			?>

			<div class="c-crop c-crop--ratio-7x4">
				<?php
				rollingstone_the_post_thumbnail(
					'ratio-7x4', array(
						'class'  => 'c-crop__img',
						'sizes'  => '(max-width: 480px) 210px, (max-width: 767px) 350px,(max-width: 959px) 450px, 300px',
						'srcset' => [ 300, 450, 350, 210 ],
					)
				);
					?>
			</div><!-- .c-crop -->

		</figure><!-- .c-card__image -->

		<header class="c-card__header">

			<h3 class="c-card__heading <?php echo esc_attr( $bold ); ?>">
				<?php echo esc_html( $title ); ?>
			</h3><!-- .c-card__heading -->

		</header><!-- .c-card__header -->
	</a><!-- .c-card__wrap -->
</article><!-- .c-card -->
