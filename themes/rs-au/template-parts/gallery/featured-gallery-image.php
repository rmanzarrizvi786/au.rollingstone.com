<?php
/**
 * Featured gallery image template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-03
 */

use Rolling_Stone\Inc\Media;

if ( ! empty( $image ) ) :
?>
<div class="c-picture__thumb-image c-crop c-crop--ratio-3x2">
	<?php rollingstone_attachment_image( $image, 'ratio-3x2', false, [ 'class' => 'c-crop__img' ] ); ?>
</div><!-- .c-crop -->
<?php
endif;
