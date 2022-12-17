<?php
/**
 * Template for 'buy-now' shortcode
 *
 * @package pmc-rollingstone-2018
 */

?>

<div class="rs__buy-now-wrapper">
	<div class="rs__btn rs__btn-skew spy">
		<a class="rs__link" href="<?php echo esc_url( $product->url ); ?>" target="_blank" data-category-name="<?php echo esc_attr( $category_name ); ?>">
			<span class="buy-now-cta"><?php esc_html_e( 'Buy:', 'pmc-rollingstone' ); ?></span>
			<span class="buy-now-title"><?php echo esc_html( $title ); ?></span>
			<span class="buy-now-price"><?php echo esc_html( $price ); ?></span>
		</a>
	</div>
	<div class="rs__btn rs__btn-skew buy">
		<a href="<?php echo esc_url( $product->url ); ?>" class="rs__link" target="_blank" data-category-name="<?php echo esc_attr( $category_name ); ?>">
			<span><?php esc_html_e( 'Buy it', 'pmc-rollingstone' ); ?></span>
		</a>
	</div>
</div>
