<?php
/**
 * Lists
 *
 * Lists related functionality.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-03
 */

namespace Rolling_Stone\Inc;

use PMC\Global_Functions\Traits\Singleton;
use PMC\Lists\Lists as ListsPlugin;
use PMC\Lists\List_Post;

/**
 * Lists Class
 *
 * @see \PMC\Global_Functions\Traits\Singleton
 */
class Lists {

	use Singleton;

	public $index;
	public $list;
	public $list_items;
	public $has_next_page;
	public $item_template;
	public $first_visible_index;
	public $order;
	public $hiding_items = false;

	/**
	 * Class constructor.
	 */
	protected function __construct() {

		add_filter( 'pmc_list_templates', [ $this, 'add_custom_templates' ] );
		add_filter( 'template_include', [ $this, 'filter_single_list_item_template' ] );

	}

	/**
	 * Add custom templates.
	 *
	 * @param array $templates List templates.
	 * @return array
	 */
	public function add_custom_templates( $templates ) {

		return [
			'item-album'          => __( 'Album', 'pmc-list' ),
			'item-featured-image' => __( 'Featured Image', 'pmc-list' ),
		];
	}

	/**
	 * Loads the list template for a single list item.
	 *
	 * @param string $template The template to load.
	 * @return string The filtered template.
	 */
	public function filter_single_list_item_template( $template ) {

		if ( 'pmc_list_item' === get_query_var( 'post_type' ) ) {
			
			return locate_template( 'single-pmc_list.php' );
		}

		return $template;
	}

	/**
	 * Gets the item template string from the list's post meta.
	 *
	 * @return string The template string.
	 */
	public function get_item_template() {

		$item_template_meta = get_post_meta( $this->list->ID, 'pmc_list_template', true );

		if ( 'item-featured-image' === $item_template_meta ) {
			return 'featured-image';
		} else {
			return 'album';
		}

	}

	/**
	 * Returns whether there are any list items to display.
	 *
	 * @return boolean
	 */
	public function have_posts() {

		return is_array( $this->list_items ) && ! empty( $this->list_items );

	}

	/**
	 * Sets up the next list item.
	 */
	public function the_post() {

		global $post;

		if ( ! is_array( $this->list_items ) ) {
			return;
		}

		$this->index += 1;

		$post = array_shift( $this->list_items );

		setup_postdata( $post );

	}

	/**
	 * Prepares the list and list items for templating.
	 */
	public function set_up_list() {

		$instance = List_Post::get_instance();

		$this->list = $instance->get_list();

		if ( ! is_a( $this->list, '\WP_Post' ) ) {
			return;
		}

		$this->list_items = $instance->get_list_items();
		if ( ! is_array( $this->list_items ) ) {
			return;
		}

		$this->index         = -1;
		$this->item_template = $this->get_item_template();

		$this->first_visible_index = $instance->get_queried_item_index();
		$this->order               = $instance->get_order();
		$this->has_next_page       = $instance->has_next_page();

	}

	/**
	 * Echoes a list item.
	 */
	public function the_item() {

		\PMC::render_template(
			sprintf(
				'%stemplate-parts/list/item-%s.php',
				trailingslashit( CHILD_THEME_PATH ),
				$this->item_template
			),
			[
				'index'           => $this->get_index_text(),
				'index_attribute' => $this->get_index_text( true ),
				'current_index'   => $this->index,
				'template_path'   => CHILD_THEME_PATH,
			],
			true
		);

	}

	/**
	 * Gets the number to display on a list item.
	 */
	public function get_index_text( $provide_attribute = false ) {

		$instance  = List_Post::get_instance();
		$numbering = $instance->get_numbering();

		if ( 'none' === $numbering && true === $provide_attribute ) {
			$numbering = 'asc';
		}

		if ( 'asc' === $numbering ) {
			return strval(
				( $instance->get_current_page() - 1 ) * $instance->get_posts_per_page()
				+ $this->index + 1
			);
		}

		if ( 'desc' === $numbering ) {
			return strval(
				$instance->get_list_items_count()
				- ( ( $instance->get_current_page() - 1 ) * $instance->get_posts_per_page() )
				- $this->index
			);
		}

		return '';

	}

	/**
	 * Provides the CSS class to add to the list's container element.
	 *
	 * @return string CSS class.
	 */
	public function list_css_class() {

		$list_css_class = 'albums';
		if ( 'album' !== $this->item_template ) {
			$list_css_class = 'artists';
		}

		return $list_css_class;
	}

	/**
	 * Whether the opening elements for hidden list items should be rendered.
	 *
	 * @return boolean
	 */
	public function should_start_hidden_items() {

		if ( 0 === $this->index && 0 < $this->first_visible_index ) {
			$this->hiding_items = true;
			return true;
		}

		return false;
	}

	/**
	 * Whether it's time to close the hidden elements' container.
	 *
	 * @return boolean
	 */
	public function should_close_hidden_items() {
		return $this->hiding_items && $this->index === $this->first_visible_index;
	}

	/**
	 * Returns the list's permalink.
	 *
	 * @return string A URL.
	 */
	public function get_url() {
		return List_Post::get_instance()->get_list_url();
	}

	/**
	 * Returns the permalink for the list's next page.
	 *
	 * @return string A URL.
	 */
	public function get_next_page_url() {
		return List_Post::get_instance()->get_list_url() . '?list_page=' . strval( List_Post::get_instance()->get_next_page_number() );
	}

	/**
	 * Returns whether the list has a next page.
	 *
	 * @return boolean
	 */
	public function has_next_page() {
		return $this->has_next_page;
	}

}

Lists::get_instance();
