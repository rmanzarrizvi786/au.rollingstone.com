<?php
/**
 * Template for share icons on a list item.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-07
 */

use Rolling_Stone\Inc\Sharing;

if ( ! isset( $post ) ) {
	$post = $GLOBALS['post'];
}

if ( empty( $post ) ) {
	return;
}

$permalink     = get_permalink( $post->ID );
$title         = get_the_title( $post->ID );
$via           = get_bloginfo( 'name' );
$sharing_icons = Sharing::get_instance()->get_icons();

if ( Sharing::has_icons( $sharing_icons ) ) : ?>

<ul class="c-social-bar c-social-bar--small">

	<?php foreach ( $sharing_icons['primary'] as $id => $share_icon ) : ?>
		<?php if ( 'facebook' === $id ) : ?>

			<li class="c-social-bar__item">
				<a href="<?php echo esc_url( "https://www.facebook.com/sharer.php?u={$permalink}&title={$title}&sdk=joey&display=popup&ref=plugin&src=share_button" ); ?>" class="c-social-bar__link" title="Facebook" rel="noopener noreferrer" target="_blank">
					<span class="c-icon c-icon--white">
						<svg>
							<use xlink:href="#svg-icon-facebook"></use>
						</svg>
					</span>
					<span class="screen-reader-text">
						Share on Facebook
					</span>
				</a>
			</li>

		<?php elseif ( 'twitter' === $id ) : ?>

			<li class="c-social-bar__item">
				<a href="<?php echo esc_url( "https://twitter.com/intent/tweet?url={$permalink}&text={$title}&via={$via}" ); ?>" class="c-social-bar__link" title="Twitter" rel="noopener noreferrer" target="_blank">
					<span class="c-icon c-icon--white">
						<svg>
							<use xlink:href="#svg-icon-twitter"></use>
						</svg>
					</span>
					<span class="screen-reader-text">Share on Twitter</span>
				</a>
			</li>

		<?php endif; ?>

	<?php endforeach; ?>

</ul>

<?php
endif;

