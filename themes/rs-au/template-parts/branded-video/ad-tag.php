<?php
/**
 * Template to render JS Ad tag on a Branded Video Landing Page
 *
 * @author Amit Gupta <agupta@pmc.com>
 *
 * @since 2019-10-03
 *
 * @package pmc-rollingstone-2018
 */

if ( empty( $post_id ) || empty( $branded_page ) || empty( $branded_page_settings ) ) {
	return;
}

if ( empty( $branded_page_settings['js_tag_url'] ) ) {
	return;
}
?>
<div class="l-section l-section--no-separator">

	<?php if ( ! empty( $branded_page_settings['js_tag_title'] ) ) { ?>
	<div class="l-section__header">
		<div class="c-section-header">
			<h3 class="c-section-header__heading t-bold t-bold--upper t-bold--condensed">
				<?php echo esc_html( $branded_page_settings['js_tag_title'] ); ?>
			</h3>
		</div><!-- .c-section-header -->
	</div><!-- /.l-section__header -->
	<?php } ?>

	<div class="l-section__content">
		<div class="c-video-grid branded-videos">
			<div class="c-video-grid__subgrid--branded-videos">
				<script type="text/javascript" src="<?php echo esc_url( $branded_page_settings['js_tag_url'] ); ?>"></script>
			</div><!-- /.c-video-grid__item -->
		</div><!-- /.c-video-grid -->
	</div><!-- /.l-section__content -->

</div>

