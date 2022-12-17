<?php
/**
 * Picture video template with no caption.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-03
 */

if ( isset( $video ) ) :

?>

<figure class="c-picture c-picture--video c-picture--no-caption" style="text-align: initial;">
	<div class="c-picture__frame">

	<div class="c-crop c-crop--video c-crop--ratio-video" data-video-crop>
		<div>
			<?php // this code is escaped from calling function, @TODO: refactor code [PMCP-683] ?>
			<?php echo $video; // WPCS: xss okay. ?>
		</div>
	</div><!-- .c-crop -->

	</div>

</figure><!-- .c-picture -->
<?php
endif;
