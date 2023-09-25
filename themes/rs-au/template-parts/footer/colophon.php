<?php

/**
 * Colophon.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-23-05
 */

?>
<footer class="l-footer">
	<div class="l-footer__wrap">
		<div class="l-footer__nav">
			<nav class="l-footer__menu">
				<?php get_template_part('template-parts/module/get-the-magazine-footer'); ?>
				<?php get_template_part('template-parts/footer/about'); ?>

			</nav><!-- .l-footer__menu--wide -->
			<!-- <nav class="l-footer__menu"> -->
			<?php // get_template_part( 'template-parts/footer/about' ); 
			?>
			<!-- </nav><!-- .l-footer__menu -->
			<!-- <nav class="l-footer__menu">
				<?php // get_template_part( 'template-parts/footer/social' ); 
				?>
			</nav><!-- .l-footer__menu -->
			<nav class="l-footer__menu l-footer__menu--wide">
				<?php get_template_part('template-parts/footer/nav'); ?>
				<br>
				<?php get_template_part('template-parts/footer/social'); ?>
			</nav><!-- .l-footer__menu -->
		</div><!-- .l-footer__nav -->

		<?php get_template_part('template-parts/module/newsletter-footer'); ?>
		<?php // get_template_part( 'template-parts/module/tip-footer' ); 
		?>

		<?php get_template_part('template-parts/module/cover-footer'); ?>

	</div><!-- .l-footer__wrap -->

	<div style="padding: 0 1rem;">
		<?php get_template_part('template-parts/module/tip-footer'); ?>
	</div>

	<?php // get_template_part( 'template-parts/module/pmc-footer' ); 
	?>

</footer><!-- .l-footer -->