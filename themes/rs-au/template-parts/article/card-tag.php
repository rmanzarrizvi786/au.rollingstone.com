<?php

/**
 * Card - Tag.
 *
 * @package pmc-rollingstone-2018
 * @since 2018.1.0
 */

$category = rollingstone_get_the_subcategory();
$classes  = 'c-card__tag t-semibold t-semibold--upper t-semibold--loose';
$show_tag = isset($show_tags) ? true : false;
$bold     = true;

if (!empty($bold)) {
	$classes = 'c-card__tag t-bold t-bold--upper';
}

// Remove tag on country (river) templates.
if (rollingstone_is_country() && !$show_tag) {
	return;
}

if (is_wp_error($category) || empty($category->name)) {
	return;
}

?>

<div class="<?php echo esc_attr($classes); ?>">
	<span class="screen-reader-text"><?php esc_html_e('Posted in:', 'pmc-rollingstone'); ?></span>
	<?php if (!empty($bold)) { ?>
		<span class="c-card__featured-tag"> <?php echo esc_html($category->name); ?></span>
	<?php } ?>
	<?php if (get_post_meta(get_the_ID(), 'premium', true)) : ?>
		<span class="c-card__featured-tag premium">premium</span>
	<?php endif; ?>
</div><!-- c-card__tag -->