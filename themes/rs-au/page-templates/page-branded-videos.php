<?php
/**
 * Template Name: Branded Videos Landing Page
 *
 * @package pmc-rollingstone-2018
 *
 * @since 2019-09-30
 */

$post_id               = get_the_ID();
$branded_page          = \PMC\Top_Videos_V2\Landing_Pages\Branded_Page::get_instance();
$branded_page_settings = $branded_page->get_settings( $post_id );

if ( ! $branded_page->is_enabled_for_post( $post_id ) ) {
	wp_safe_redirect( home_url(), 302 );
	exit;
}

get_header();

?>

	<div class="l-page__content l-page__branded-videos">
		<?php
		\PMC::render_template(
			sprintf( '%s/template-parts/branded-video/header.php', CHILD_THEME_PATH ),
			[
				'post_id'               => $post_id,
				'branded_page'          => $branded_page,
				'branded_page_settings' => $branded_page_settings,
			],
			true
		);
		?>

		<?php
		\PMC::render_template(
			sprintf( '%s/template-parts/branded-video/top-carousel.php', CHILD_THEME_PATH ),
			[
				'post_id'               => $post_id,
				'branded_page'          => $branded_page,
				'branded_page_settings' => $branded_page_settings,
			],
			true
		);
		?>

		<?php
		\PMC::render_template(
			sprintf( '%s/template-parts/branded-video/middle-carousel.php', CHILD_THEME_PATH ),
			[
				'post_id'               => $post_id,
				'branded_page'          => $branded_page,
				'branded_page_settings' => $branded_page_settings,
				'slot_name'             => 'second_carousel',
				'slug'                  => ( ! empty( $branded_page_settings['second_carousel'] ) ) ? $branded_page_settings['second_carousel'] : '',
				'title'                 => ( ! empty( $branded_page_settings['second_carousel_title'] ) ) ? $branded_page_settings['second_carousel_title'] : '',
			],
			true
		);
		?>

		<?php
		\PMC::render_template(
			sprintf( '%s/template-parts/branded-video/bottom-carousel.php', CHILD_THEME_PATH ),
			[
				'post_id'               => $post_id,
				'branded_page'          => $branded_page,
				'branded_page_settings' => $branded_page_settings,
			],
			true
		);
		?>

		<?php
		\PMC::render_template(
			sprintf( '%s/template-parts/branded-video/ad-tag.php', CHILD_THEME_PATH ),
			[
				'post_id'               => $post_id,
				'branded_page'          => $branded_page,
				'branded_page_settings' => $branded_page_settings,
			],
			true
		);
		?>

		<?php get_template_part( 'template-parts/footer/footer' ); ?>
	</div><!-- .l-page__content -->

<?php
get_footer();
