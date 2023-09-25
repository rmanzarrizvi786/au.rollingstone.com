<?php
/**
 * Page template to auto inject related jwplayer videos into page.
 */

if ( empty( $player_id ) || empty( $playlist_id ) ) {
	return;
}
?>

<div class="pmc-contextual-player">
	<?php if ( ! empty( $player_title ) ) { ?>
		<h3>
			<?php echo esc_html( $player_title ); ?>
		</h3>
	<?php } ?>

	<amp-jwplayer
			data-player-id="<?php echo esc_attr( $player_id ); ?>"
			data-playlist-id="<?php echo esc_attr( $playlist_id ); ?>"
			layout="responsive"
			width="16" height="9">
	</amp-jwplayer>

</div>
