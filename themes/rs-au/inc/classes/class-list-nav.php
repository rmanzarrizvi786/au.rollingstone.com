<?php
/**
 * List Nav
 *
 * Renders a navigation component for a list.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-04
 */

namespace Rolling_Stone\Inc;

use PMC\Lists\List_Post;

/**
 * List_Nav Class
 */
class List_Nav {

	/**
	 * An instance of the List_Post plugin class.
	 *
	 * @var \PMC\Lists\List_Post
	 */
	public $list_post_instance;

	/**
	 * The number of items in the list.
	 *
	 * @var int
	 */
	public $list_items_count;

	/**
	 * The order in which to display the list.
	 *
	 * @var string
	 */
	public $order;

	/**
	 * The number of posts to display per page.
	 *
	 * @var int
	 */
	public $per_page;

	/**
	 * The range of numbers for each navigation list item.
	 *
	 * @var int
	 */
	public $nav_range;

	/**
	 * The index used while rendering the nav.
	 *
	 * @var int
	 */
	public $index;

	/**
	 * The page number of the items in the current nav link.
	 *
	 * @var int
	 */
	public $page;

	/**
	 * The starting number of the range currently being rendered.
	 *
	 * @var int
	 */
	public $range_start;

	/**
	 * The ending number of the range currently being rendered.
	 *
	 * @var [type]
	 */
	public $range_end;

	/**
	 * Class constructor.
	 */
	public function __construct( $list_id ) {

		$this->list_post_instance = List_Post::get_instance();

		// Set up unchanging variables.
		$this->list_items_count = $this->list_post_instance->get_list_items_count();
		$this->order            = $this->list_post_instance->get_order();
		$this->per_page         = $this->list_post_instance->get_posts_per_page();
		$this->set_up_nav_range();

	}

	/**
	 * Sets the nav range.
	 */
	protected function set_up_nav_range() {

		$this->nav_range = intval( $this->list_items_count / 10 );

		if ( 100 < $this->list_items_count ) {
			$this->nav_range = 50;
		}

		if ( 1000 < $this->list_items_count ) {
			$this->nav_range = 100;
		}

	}

	/**
	 * Renders the nav.
	 */
	public function render() {

		if ( ! is_numeric( $this->list_items_count ) ) {
			return;
		}

		if ( 'asc' === $this->order ) {
			$this->render_ascending();
		} elseif ( 'desc' === $this->order ) {
			$this->render_descending();
		}

	}

	/**
	 * Renders an ascending nav item.
	 */
	public function render_ascending_item() {
		if ( $this->index > $this->page * $this->per_page ) {
			$this->page++;
		}

		$url = List_Post::get_instance()->get_list_url() . '?list_page=' . $this->page . '#list-item-' . $this->index;

		$range_end = $this->index + $this->nav_range - 1;
		if ( $range_end > $this->list_items_count ) {
			$range_end = $this->list_items_count;
		}

		\PMC::render_template(
			CHILD_THEME_PATH . '/template-parts/list/list-nav-item.php',
			[
				'url'         => $url,
				'range_start' => strval( $this->index ),
				'range_end'   => strval( $range_end ),
			],
			true
		);

		if ( 1 === $this->index % $this->nav_range ) {
			$this->range_end += $this->nav_range;

			if ( $this->range_end > $this->list_items_count ) {
				$this->range_end = $this->list_items_count;
			}
		}
	}

	/**
	 * Renders an ascending nav.
	 */
	public function render_ascending() {
		$this->index = 1;
		$this->page  = 1;

		for ( $this->index; $this->index < $this->list_items_count + 1; $this->index += $this->nav_range ) {
			$this->render_ascending_item();
		}

	}

	/**
	 * Renders an item in a descending nav list.
	 */
	public function render_descending_item() {
		if ( $this->index < $this->list_items_count - $this->page * $this->per_page + 1 ) {
			$this->page++;
		}

		$url = List_Post::get_instance()->get_list_url() . '?list_page=' . $this->page . '#list-item-' . $this->index;

		$range_end = $this->index - $this->nav_range + 1;
		if ( 1 > $range_end ) {
			$range_end = 1;
		}

		\PMC::render_template(
			CHILD_THEME_PATH . '/template-parts/list/list-nav-item.php',
			[
				'url'         => $url,
				'range_start' => strval( $this->index ),
				'range_end'   => strval( $range_end ),
			],
			true
		);

		if ( 1 === $this->index % $this->nav_range ) {
			$this->range_end -= $this->nav_range;

			if ( $this->range_end < 1 ) {
				$this->range_end = 1;
			}
		}
	}

	/**
	 * Renders a descending nav.
	 */
	public function render_descending() {
		$this->index = $this->list_items_count;
		$this->page  = 1;

		for ( $this->index; $this->index > 0; $this->index -= $this->nav_range ) {
			$this->render_descending_item();
		}
	}
}
