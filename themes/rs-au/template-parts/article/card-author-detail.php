<?php
/**
 * Card - Author Detail.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

?>

<div class="c-author">
	<?php if ( has_post_thumbnail( $author->ID ) ) : ?>
	<div class="c-author__thumb">
		<?php rollingstone_the_author_avatar( $author->ID, [ 'class' => 'c-author__headshot' ], 94 ); // WPCS: XSS okay. ?>
	</div>
	<?php endif; ?>

	<div class="c-author__meta">
		<h4 class="c-author__heading t-bold">
			<a href="<?php echo esc_url( get_author_posts_url( $author->ID, $author->user_nicename ) ); ?>" rel="author" class="c-author__meta-link">
				<?php echo esc_html( $author->display_name ); ?>
			</a>
		</h4>

		<?php if ( ! empty( $author->_pmc_title ) ) : ?>
		<p class="c-author__role"><?php echo esc_html( $author->_pmc_title ); ?></p>
		<?php endif; ?>

		<?php
		if ( ! empty( $author->_pmc_user_twitter ) ) :
			$twitter_handle = trim( $author->_pmc_user_twitter, '@' );
			$twitter_link   = sprintf( 'https://twitter.com/%1$s', $twitter_handle );
		?>
		<a class="c-author__twitter t-bold" href="<?php echo esc_url( $twitter_link ); ?>" target="_blank" rel="noopener noreferrer">
			<span class="c-icon c-icon--twitter">
				<svg><use href="#svg-icon-twitter"></use></svg>
			</span>
			@<?php echo esc_html( $twitter_handle ); ?>
		</a>
		<a href="<?php echo esc_url( $twitter_link ); ?>" class="c-btn c-btn--block c-btn--twitter t-bold t-bold--upper" target="_blank" rel="noopener noreferrer">
			<?php esc_html_e( 'Follow', 'pmc-rollingstone' ); ?>
		</a>
		<?php endif; ?>
	</div>

	<h4 class="c-author__heading t-bold"><?php echo esc_html( $author->display_name ); ?>'s <?php esc_html_e( 'Most Recent Stories', 'pmc-rollingstone' ); ?></h4>

	<?php
	// $author_obj = \Rolling_Stone\Inc\Author::get_instance();
	$author_obj = new \Rolling_Stone\Inc\Author();
	$articles   = $author_obj->get_author_posts( $author->user_nicename );
	if ( ! empty( $articles ) && is_array( $articles ) ) :
	?>
	<ul class="c-author__posts t-semibold">
		<?php foreach ( $articles as $article_id ) : ?>
		<li class="c-author__post">
			<a href="<?php echo esc_url( get_permalink( $article_id ) ); ?>" class="c-author__meta-link c-author__meta-link--post">
				<?php echo esc_html( get_the_title( $article_id ) ); ?>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<div class="c-author__view-all">
		<a href="<?php echo esc_url( get_author_posts_url( $author->ID, $author->user_nicename ) ); ?>" class="c-author__meta-link c-author__meta-link--all t-semibold t-semibold--upper t-semibold--loose"><?php esc_html_e( 'View All', 'pmc-rollingstone' ); ?></a>
	</div>
	<?php endif; ?>
</div><!-- .c-author -->
