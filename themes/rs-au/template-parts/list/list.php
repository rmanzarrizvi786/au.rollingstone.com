<?php
/**
 * Lists Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-28
 */

use Rolling_Stone\Inc\Lists;

$list = Lists::get_instance();
$list->set_up_list();

?>

<div class="c-list c-list--<?php echo esc_attr( $list->list_css_class() ); ?>">
	<?php while ( $list->have_posts() ) : ?>
		<?php $list->the_post(); ?>

		<?php if ( $list->should_start_hidden_items() ) : ?>
			<div data-list-hidden hidden>
		<?php elseif ( $list->should_close_hidden_items() ) : ?>

			</div><!-- data-list-hidden -->

			<div data-list-autofocus>
				<input type="text" autofocus tabindex="-1" />
			</div>

			<div class="c-list__separator" data-list-separator>
				<button class="c-list__separator-btn c-btn c-btn--outline t-semibold t-semibold--upper" data-list-load-previous>
					<?php esc_html_e( 'Load Previous', 'pmc-rollingstone' ); ?>
				</button>
			</div>
		<?php endif; ?>

		<?php $list->the_item(); ?>

		<?php if ( $list->should_close_hidden_items() ) : ?>

			<div class="c-list__separator" data-list-separator>
				<a href="<?php echo esc_url( $list->get_url() ); ?>" class="c-list__separator-btn c-btn c-btn--outline t-semibold t-semibold--upper">
					<?php esc_html_e( 'View Complete List', 'pmc-rollingstone' ); ?>
				</a>
			</div>

		<?php endif; ?>

	<?php endwhile; ?>

	<?php if ( $list->has_next_page() ) : ?>
		<div class="c-list__separator c-list__separator--with-border">
			<a rel="next" href="<?php echo esc_url( $list->get_next_page_url() ); ?>" class="c-list__separator-btn c-btn c-btn--outline t-semibold t-semibold--upper">
				<?php esc_html_e( 'Load More', 'pmc-rollingstone' ); ?>
			</a>
		</div>
	<?php endif; ?>
</div><!-- .c-list -->

<?php
wp_reset_postdata();
