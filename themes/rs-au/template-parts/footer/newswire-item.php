<?php
/**
 * Newswire Item.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-11-04
 */

if (
	! empty( $item['url'] ) &&
	! empty( $item['image'] ) &&
	! empty( $item['title'] ) &&
	! empty( $item['date'] ) &&
	! empty( $item['source']['name'] )
) :

?>

<li class="c-newswire__item">

	<article class="c-card c-card--brand">
		<a href="<?php echo esc_url( $item['url'] ); ?>" class="c-card__wrap">
			<figure class="c-card__image">

				<div class="c-crop c-crop--ratio-7x4">

					<?php
					$param_connector = strpos( $item['image'], '?' ) ? '&' : '?';
					$image_base      = add_query_arg( 'quality', '98', $item['image'] );
					$image_base      = remove_query_arg( [ 'resize' ], $image_base );
					$image_base      = set_url_scheme( $image_base, 'https' );
					$image_src       = add_query_arg( 'w', '300', $image_base );
					$image_src_2x    = add_query_arg( 'w', '600', $image_base );

					?>
					<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
						data-src="<?php echo esc_url( $image_src ); ?>"
						data-srcset="<?php echo esc_url( $image_src ); ?> 300w, <?php echo esc_url( $image_src_2x ); ?> 600w"
						alt="<?php echo esc_attr( $item['title'] ); ?>"
						sizes="(max-width: 959px) 46%, (max-width: 1259px) 22%, 300px"
						class="c-crop__img" width="300" height="171"
					>

				</div><!-- .c-crop -->

			</figure><!-- .c-card__image -->

			<header class="c-card__header">

				<h3 class="c-card__heading t-bold">
					<?php echo esc_html( $item['title'] ); ?>
				</h3><!-- .c-card__heading -->

				<div class="c-card__brand t-bold">
					<span class="screen-reader-text"><?php esc_html_e( 'Posted on:', 'pmc-rollingstone' ); ?></span> <?php echo esc_html( $item['source']['name'] ); ?>
				</div><!-- c-card__tag -->

				<div class="c-card__timestamp t-copy">
					<span class="screen-reader-text"><?php esc_html_e( 'Posted', 'pmc-rollingstone' ); ?></span>
					<?php echo esc_html( $item['date'] ); ?> <?php esc_html_e( 'ago', 'pmc-rollingstone' ); ?>
				</div><!-- c-card__timestamp -->

			</header><!-- .c-card__header -->
		</a><!-- .c-card__wrap -->
	</article><!-- .c-card--brand -->

</li><!-- /.c-newswire__item -->

<?php
endif;
