<?php
/**
 * Template to display carousel module on Branded Video Landing Page
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since 2019-11-04
 *
 * @package pmc-rollingstone-2018
 */

if ( empty( $post_id ) || empty( $branded_page ) || empty( $branded_page_settings ) ) {
	return;
}

if ( empty( $slot_name ) || empty( $title ) ) {
	return;
}

$posts = call_user_func_array(
	[ $branded_page, sprintf( 'get_%s', $slot_name ) ],
	[
		$post_id,
		8,
	]
);

$posts = ( ! empty( $posts['posts'] ) ) ? $posts['posts'] : [];

if ( empty( $posts ) ) {
	return;
}

?>

	<div class="l-section l-section--no-separator">

		<div class="l-section__header">
			<div class="c-section-header">
				<h3 class="c-section-header__heading t-bold t-bold--upper t-bold--condensed">
					<?php echo esc_html( $title ); ?>
				</h3>
			</div><!-- .c-section-header -->
		</div><!-- /.l-section__header -->

		<div class="l-section__content">
			<div class="c-video-grid branded-videos">
				<div class="c-video-grid__subgrid--branded-videos">
					<?php
					if ( ! empty( $posts ) ) :
						foreach ( $posts as $video ) :
							?>

							<div class="c-video-grid__item--branded-videos">

								<?php
								\PMC::render_template(
									CHILD_THEME_PATH . '/template-parts/branded-video/carousel-item.php',
									[
										'item' => $video,
									],
									true
								);
								?>

							</div><!-- /.c-video-grid__item -->

							<?php
						endforeach;
					endif;
					?>
				</div><!-- /.c-video-grid__item -->
			</div><!-- /.c-video-grid -->
		</div><!-- /.l-section__content -->

	</div>
