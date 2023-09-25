<?php
/**
 * Gallery Item template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-19
 */

$images = \PMC\Gallery\View::get_instance()->fetch_gallery( $item->ID );
$count  = ( ! empty( $images ) && is_array( $images ) ) ? count( $images ) : '0';

?>

<article class="c-gallery-card">
	<a href="<?php the_permalink(); ?>" class="c-gallery-card__wrap">
		<figure class="c-gallery-card__image">

			<div class="c-crop c-crop--ratio-3x2">
				<?php
				rollingstone_the_post_thumbnail(
					'ratio-3x2', array(
						'class'  => 'c-crop__img',
						'sizes'  => '(max-width: 1259px) 60%, 760px',
						'srcset' => [ 450, 760, 920, 1440 ],
					)
				);
				?>
			</div><!-- .c-crop -->

			<?php
			\PMC::render_template(
				CHILD_THEME_PATH . '/template-parts/badges/gallery.php', [
					'count'  => $count,
					'mobile' => true,
				], true
			);
?>

		</figure><!-- .c-gallery-card__image -->
		<header class="c-gallery-card__header">

			<h4 class="c-gallery-card__headline t-bold"><?php rollingstone_the_title(); ?></h4>
			<p class="c-gallery-card__lead t-copy">
				<?php rollingstone_the_excerpt(); ?>
			</p>

			<div class="c-gallery-card__thumbs">
				<?php
				if ( intval( $count ) > 0 ) {

					$index = 0;

					foreach ( $images as $image ) {

						$index++;

						if ( $index >= 4 ) {
							break;
						}

						$image_src    = $image['image'] . '?crop=3:2,smart&w=90';
						$image_src_2x = $image['image'] . '?crop=3:2,smart&w=180';
					?>

						<figure class="c-gallery-card__thumb">
							<div class="c-crop c-crop--ratio-3x2">
								<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
									data-src="<?php echo esc_url( $image_src ); ?>"
									data-srcset="<?php echo esc_url( $image_src ) . ' 90w, ' . esc_url( $image_src_2x ) . ' 180w'; ?>"
									sizes="90px"
									alt="<?php echo esc_attr( $image['caption'] ); ?>"
									class="c-crop__img" width="90" height="60" />
							</div><!-- .c-crop -->
						</figure><!-- .c-gallery-card__thumb -->

						<?php
					}    // end foreach loop

				}    // end if block
				?>
			</div><!-- .c-gallery-card__thumbs -->

		</header><!-- .c-gallery-card__header -->
	</a><!-- .c-gallery-card__wrap -->
</article><!-- .c-gallery-card -->

