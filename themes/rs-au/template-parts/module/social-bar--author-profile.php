<?php
/**
 * Template part for social share bar for author profile
 *
 * @package pmc-rollingstone-2018
 */

if ( empty( $author ) ) {
	return;
}

$profile_types = [ 'twitter', 'facebook', 'instagram', 'youtube' ];

?>
<ul class="c-social-bar c-social-bar--author-profile">

	<?php
	foreach ( $profile_types as $type ) {
		PMC::render_template(
			sprintf( '%s/template-parts/list/share-author-profile.php', untrailingslashit( CHILD_THEME_PATH ) ),
			[
				'author' => $author,
				'type'   => $type,
			],
			true
		);
	}
	?>

</ul>
