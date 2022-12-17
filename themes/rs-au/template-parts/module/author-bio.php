<?php
/**
 * Template part for Author bio for author archive page
 *
 * @package pmc-rollingstone-2018
 */

if ( empty( $author ) ) {
	return;
}

$dek = $author->description;

$has_thumbnail = has_post_thumbnail( $author->ID );
$class         = empty( $has_thumbnail ) ? 'c-author-bio--without-thumb' : '';
?>
<section class="c-author-bio <?php echo esc_attr( $class ); ?>" itemscope itemtype="https://schema.org/Person">

	<?php if ( $has_thumbnail ) : ?>
		<figure class="c-author-bio__thumb">
			<?php rollingstone_the_author_avatar( $author->ID, [ 'class' => 'c-author-bio__headshot' ], 92 ); ?>
		</figure>
	<?php endif; ?>

	<div class="c-author-bio__meta">

		<h1 class="c-author-bio__heading t-bold" itemprop="name">
			<?php echo esc_html( $author->display_name ); ?>
		</h1>

		<?php
		PMC::render_template( sprintf( '%s/template-parts/module/social-bar--author-profile.php', untrailingslashit( CHILD_THEME_PATH ) ), [ 'author' => $author ], true );

		if ( PMC::is_desktop() ) {
			PMC::render_template( sprintf( '%s/template-parts/module/author-bio-dek.php', untrailingslashit( CHILD_THEME_PATH ) ), [ 'data' => $dek ], true );
		}
		?>

	</div>

	<?php
	if ( ! PMC::is_desktop() ) {
		PMC::render_template( sprintf( '%s/template-parts/module/author-bio-dek.php', untrailingslashit( CHILD_THEME_PATH ) ), [ 'data' => $dek ], true );
	}
	?>

</section><!-- /.c-author-bio -->
