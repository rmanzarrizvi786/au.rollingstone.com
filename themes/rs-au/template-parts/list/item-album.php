<?php
/**
 * List Item Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-01
 */

$item_class  = 'c-list__item';
$image_class = 'c-crop c-crop--ratio-1x1';

$title = rollingstone_get_the_title();

$list_item_authors = \PMC::get_post_authors_list( get_the_ID(), 'all', 'user_login', 'user_nicename' );
$list_item_authors = ( ! empty( $list_item_authors ) ) ? str_replace( ',', '^', $list_item_authors ) : '';
?>

<article class="<?php echo esc_attr( $item_class ); ?>"
		id="list-item-<?php echo esc_attr( $index_attribute ); ?>"
		data-list-item="<?php echo esc_attr( $index_attribute ); ?>"
		data-list-title="<?php echo esc_attr( $title ); ?>"
		data-list-permalink="<?php echo esc_url( get_permalink() ); ?>"
		data-list-item-id="<?php the_ID(); ?>"
		data-list-item-authors="<?php echo esc_attr( $list_item_authors ); ?>">

	<figure class="c-list__picture">
		<!-- <div class="c-list__share" data-collapsible="collapsed" data-collapsible-close-on-click> -->
			<!-- <div class="c-list__social-bar" data-collapsible-panel> -->
				<?php // get_template_part( 'template-parts/list/share-bar' ); ?>
			<!-- </div> --><!-- /.c-list__social-bar
			<!-- <svg class="c-list__icon" data-collapsible-toggle="always-show"><use xlink:href="#svg-icon-share"></use></svg> -->
		<!-- </div> --><!-- /.c-list__share -->

		<div class="<?php echo esc_attr( $image_class ); ?>">
			<?php
			if ( has_post_thumbnail( get_the_ID() ) ) {
				rollingstone_the_post_thumbnail(
					'ratio-1x1', array(
						'class'  => 'c-crop__img',
						'sizes'  => '(max-width: 959px) 300px, 385px',
						'srcset' => [ 300, 385 ],
					)
				);
			} else {
				$featured_image_override = get_post_meta( get_the_ID(), 'thumbnail_ext_url', TRUE );
				?>
				<img width="900" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo $featured_image_override; ?>" class="c-crop__img wp-post-image" alt="" />
				<?php
			}
			?>
		</div><!-- .c-crop -->
	</figure><!-- /.c-list__picture -->

	<header class="c-list__header">
		<?php if ( ! empty( $index ) ) : ?>
		<span class="c-list__number t-bold">
			<?php echo esc_html( $index ); ?>
		</span>
		<?php endif; ?>

		<h3 class="c-list__title t-bold">
			<?php echo esc_html( $title ); ?>
		</h3><!-- /.c-list__title -->
	</header><!-- /.c-list__header -->

	<main class="c-list__main">
		<div class="c-list__lead c-content">
			<?php the_content(); ?>
		</div><!-- /.c-list__lead -->
	</main><!-- /.c-list__main -->
</article><!-- .c-list__item -->

<?php
	\PMC::render_template(
		sprintf(
			'%s/template-parts/ads/lists-river-ad.php',
			$template_path
		),
		[
			'current_index' => $current_index,
		],
		true
	);
	?>
