<?php
/**
 * TBM Video + Record Of Week Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-01
 */

$title_video      = ! empty( $data['title_video'] ) ? $data['title_video'] : 'Video of the week';
$title_record      = ! empty( $data['title_record'] ) ? $data['title_record'] : 'Record of the week';

?>

<div class="l-section">

	<div class="l-section__header">
		<div class="c-section-header"></div><!-- .c-section-header -->
	</div><!-- /.l-section__header -->

	<div class="l-section__content">
		<div class="c-video-grid video-of-week-wrap">
			<h3 class="c-section-header__heading t-bold t-bold--condensed">
				<?php echo esc_html( $title_video ); ?>
			</h3>
			<div class="c-video-grid__main">

				<?php
				\PMC::render_template(
					CHILD_THEME_PATH . '/template-parts/video/section-video-of-week.php', [
						'css' => true,
					], true
				);
				?>

			</div><!-- /.c-video-grid__item -->
		</div><!-- /.c-video-grid -->

		<div class="c-video-grid record-of-week-wrap" style="width: 300px;">
			<h3 class="c-section-header__heading t-bold t-bold--condensed">
				<?php echo esc_html( $title_record ); ?>
			</h3>
			<div class="c-video-grid__main">

				<?php
				\PMC::render_template(
					CHILD_THEME_PATH . '/template-parts/video/section-record-of-week.php', [
						'css' => true,
					], true
				);
				?>

			</div><!-- /.c-video-grid__item -->
		</div><!-- /.c-video-grid -->
	</div><!-- /.l-section__content -->

</div>

<?php
wp_reset_postdata();
