<?php
/**
 * Author Archive Template.
 *
 * @package pmc-rollingstone-2018
 */

$author = get_queried_object();
?>

<div class="l-blog">
	<main class="l-blog__primary l-blog__primary--author-archive">

		<?php
		PMC::render_template( sprintf( '%s/template-parts/module/author-bio.php', untrailingslashit( CHILD_THEME_PATH ) ), [ 'author' => $author ], true );
		?>

		<div class="l-blog__item l-blog__item--spacer-xl">
			<div class="c-section-header">
				&nbsp;
			</div><!-- .c-section-header -->
		</div><!-- .l-blog__item -->

		<div class="l-blog__item l-blog__item--spacer-s">
			<?php
			PMC::render_template( sprintf( '%s/template-parts/archive/river.php', untrailingslashit( CHILD_THEME_PATH ) ), [], true );
			?>
		</div><!-- .l-blog__item -->

		<div class="l-blog__item l-blog__item--spacer-l">
			<?php
			PMC::render_template( sprintf( '%s/template-parts/archive/pagination.php', untrailingslashit( CHILD_THEME_PATH ) ), [], true );
			?>
		</div><!-- .l-blog__item -->

	</main><!-- .l-blog__primary -->

	<?php if ( is_active_sidebar( 'archive_right_1' ) ) : ?>
		<aside class="l-blog__secondary">

			<?php dynamic_sidebar( 'archive_right_1' ); ?>

		</aside><!-- .l-blog__secondary -->
	<?php endif; ?>

</div><!-- .l-blog -->
