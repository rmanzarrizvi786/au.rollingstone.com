# PMC Lists

This plugin provides functionality for displaying lists of items. It provides a "List" post type, a "List Item" post type, a taxonomy for associating items with lists, and a WordPress admin interface for creating lists.

While this plugin provides the post types, admin interface, and data, templating of lists is expected to be handled in themes or other plugins. This plugin's `List_Post` class provides the main interface for doing so.

## List_Post

The List_Post class will be instantiated on any frontend page that is a singular List or a singular List Item. If the page is a singular List Item, the item's associated list and all its other items are retrieved. The following public methods are available.

### ::get_list : \WP_Post

Provides the WP_Post object for the current list.

### ::get_list_items : array

Provides list items (an array of WP_Post objects) for the current page. E.g., when on page two of a list showing 50 items per page, items 51-100 are returned.

### ::get_term : \WP_Term

Provides the term associating list items with their list on the current page.

### ::get_list_items_count : int

Gets the total number of list items associated with the current list.

### ::get_order : string

Gets the order in which the list should be displayed ('asc' or 'desc').

### ::get_posts_per_page : int

Returns the number if items to show per page.

### ::get_queried_item_index : int

If a single item was queried, returns its index within the current page.

### ::get_current_page : int

Returns the list page number currently being displayed.

### ::has_next_page : bool

Returns whether the current list has a next page.

### ::get_next_page_number : int

Returns the next page number.

### ::get_list_url : string

Gets the permalink for the current list.

### ::get_previous_item( int $item_id ) : \WP_Post|false

Returns the list item previous to the past-in item.

### ::get_next_item( int $item_id ) : \WP_Post|false

Returns the list item following the past-in item.

## Filters

### pmc_lists_per_page

Filters the number of list items to display per page. Default: 100 if there number of items is greater than 1000; 50 if the number of items is greater than 50; otherwise, the number of items (no pagination).