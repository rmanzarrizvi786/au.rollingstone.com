<?php
/**
 * Rolling Stone Columnist Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-26
 */

global $post;

use Rolling_Stone\Inc\Media;
use Rolling_Stone\Inc\Carousels;

$columnist_title = ( isset( $columnist_title ) ) ? $columnist_title : '';
$columnist_posts = Carousels::get_carousel_posts( $columnist_carousel, 3, false, false );

if ( empty( $columnist_posts ) ) {
	return;
}

?>

<div class="c-columnists">
	<h3 class="c-columnists__label">
		<span class="c-columnists__badge">
				<svg class="c-columnists__icon"><use xlink:href="#svg-rs-badge"></use></svg>
		</span>
		<span class="t-bold">
			<?php echo esc_html( $columnist_title ); ?>
		</span>
	</h3>
	<ul class="c-columnists__list">
		<?php
		foreach ( $columnist_posts as $post ) :
			setup_postdata( $post );
			$authors = get_coauthors( get_the_ID() );
			$authors = ( ! empty( $authors ) && is_array( $authors ) ) ? $authors : [];
			if ( 0 === count( $authors ) ) :
				continue;
			endif;
			$author = $authors[0];

			?>
			<li class="c-columnists__item">
				<article class="c-testimonial">
					<header class="c-testimonial__header">
						<div class="c-testimonial__avatar-wrap">
							<?php
								echo wp_kses(
									coauthors_get_avatar( $author, 160 ),
									wp_kses_allowed_html( Media::ATTACHMENT_KSES_CONTEXT )
								);
							?>

						</div><!-- /.c-testimonial__avatar-wrap -->

						<div class="c-testimonial__author">
							<p class="t-copy t-bold">
								<?php echo wp_kses_post( $author->display_name ); ?>
							</p>

							<p class="c-testimonial__tagline">
								<span>
									<?php echo wp_kses_post( $author->_pmc_excerpt ); ?>
								</span>
							</p>

						</div><!-- /.c-testimonial__author -->
					</header><!-- /.c-testimonial__header -->

					<a href="<?php the_permalink(); ?>" class="c-testimonial__main">
						<h4 class="c-testimonial__title">
							<span class="t-semibold">
								<?php rollingstone_the_title(); ?>
							</span>
						</h4>

						<p class="c-testimonial__body">
							<span class="t-copy">
								<?php rollingstone_the_excerpt(); ?>
							</span>
						</p><!-- /.c-testimonial__body -->
					</a><!-- /.c-testimonial__main -->
				</article><!-- /.c-testimonial__item -->
			</li><!-- /.c-columnists__item -->

		<?php endforeach; ?>
	</ul><!-- /.c-columnists__list -->
</div><!-- .c-columnists -->
<?php
wp_reset_postdata();
