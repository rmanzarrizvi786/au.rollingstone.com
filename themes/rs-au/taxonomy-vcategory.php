<?php
/**
 * The Archive for the Video Category (vcategory) term.
 *
 * This is also known as "Playlist."
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-25
 */

get_header();

?>

<div class="l-page__content">
	<?php get_template_part( 'template-parts/video/header' ); ?>

	<div class="l-section l-section--no-separator">
		<div class="c-video-gallery c-video-gallery--article">

			<?php get_template_part( 'template-parts/video/card-gallery' ); ?>

		</div>
	</div><!-- /.l-section -->

	<div class="l-section l-section--no-separator l-section--with-bottom-margin">

		<div class="l-section__header l-section__header--spaced">
			<div class="c-section-header">
				<h3 class="c-section-header__heading t-bold t-bold--upper t-bold--condensed">
					<?php single_cat_title(); ?>
				</h3>
			</div><!-- .c-section-header -->
		</div><!-- /.l-section__header -->

		<ul class="c-newswire">

			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					?>
					<li class="c-newswire__item">
						<?php
						\PMC::render_template(
							CHILD_THEME_PATH . '/template-parts/video/card-video-thumb.php', [
								'bold'   => 't-bold',
								'on_tax' => true,
							], true
						);
						?>
					</li>
					<?php
				endwhile;
			endif;
			?>

		</ul><!-- .c-newswire -->

	</div><!-- /.l-section -->

	<?php get_template_part( 'template-parts/video/pagination' ); ?>
	<?php get_template_part( 'template-parts/footer/footer' ); ?>
</div><!-- .l-page__content -->

<?php
get_footer();
