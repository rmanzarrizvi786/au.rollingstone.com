<?php
/**
 * Template part for Author bio dek
 *
 * @package pmc-rollingstone-2018
 */

if ( empty( $data ) ) {
	return;
}
?>
<div class="c-author-bio__dek-wrap">
	<p class="c-author-bio__dek" itemprop="description">
		<?php
			echo wp_kses_post( $data );
		?>
	</p>
</div>
