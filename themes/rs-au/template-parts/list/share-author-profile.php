<?php
/**
 * Author profile social bar item.
 *
 * @package pmc-rollingstone-2018
 */

if ( empty( $type ) || empty( $author ) ) {
	return;
}

$meta_key = "_pmc_user_{$type}";

$meta_value = isset( $author->{$meta_key} ) ? $author->{$meta_key} : '';

if ( empty( $meta_value ) ) {
	return;
}

if ( 'twitter' === $type ) {
	$meta_value = trim( $meta_value, '@' );
	$meta_value = sprintf( 'https://twitter.com/%1$s', $meta_value );
}

$title_tag   = ucwords( "{$type} Profile" );
$svg_icon_id = "#svg-icon-{$type}";
?>
<li class="c-social-bar__item">
	<a href="<?php echo esc_url( $meta_value ); ?>" class="c-social-bar__link t-semibold" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr( $title_tag ); ?> " itemprop="sameAs">
		<span class="c-icon">
			<svg><use xlink:href="<?php echo esc_url( $svg_icon_id ); ?>"></use></svg>
		</span>
		<?php echo esc_html( $type ); ?>
		<span class="screen-reader-text">
			<?php echo esc_html( $title_tag ); ?>
		</span>
	</a>
</li>
