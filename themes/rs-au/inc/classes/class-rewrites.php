<?php

/**
 * Rewrites
 *
 * Setup rewrites for the theme.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-05-02
 */

namespace Rolling_Stone\Inc;

use PMC\Global_Functions\Traits\Singleton;
use PMC\Lists\Lists;

/**
 * Class Rewrites
 *
 * @see   \PMC\Global_Functions\Traits\Singleton
 */
class Rewrites
{

	use Singleton;

	const POST_TYPES = array('pmc_top_video', 'pmc_list', 'pmc_list_item', 'pmc-gallery', 'pmc-nz');

	/**
	 * Class constructor.
	 *
	 * @since 2018-05-02
	 */
	protected function __construct()
	{
		add_action('init', array($this, 'set_permalink_defaults'));
		add_filter('rewrite_rules_array', array($this, 'rewrite_rules'));
		add_filter('category_rewrite_rules', array($this, 'remove_category_rules'));
		// add_filter( 'category_link', array( $this, 'remove_category_base' ) );
		add_filter('option_permalink_structure', array($this, 'set_permalink_structure'));
		add_filter('option_tag_base', array($this, 'set_tag_base'));
		add_filter('post_link', array($this, 'filter_permalinks'), 10, 3);
		add_filter('register_post_type_args', array($this, 'set_post_type_permalink'), 9999, 2);
		add_filter('register_taxonomy_args', array($this, 'set_taxonomy_permalink'), 9999, 2);
		add_filter('post_type_link', array($this, 'filter_post_type_permalinks'), 10, 2);
		add_action('init', array($this, 'add_rewrite_tags'));
		add_filter('template_include', array($this, 'set_static_pages'));
	}

	/**
	 * Sets the default permastruct and bases for tags and categories.
	 */
	public function set_permalink_defaults()
	{
		if (!defined('WPCOM_VIP_CUSTOM_PERMALINKS')) {
			if (function_exists('wpcom_vip_load_permastruct')) {
				wpcom_vip_load_permastruct($this->get_permalink_structure());
			}

			if (function_exists('wpcom_vip_load_category_base')) {
				wpcom_vip_load_category_base('');
			}

			if (function_exists('wpcom_vip_load_tag_base')) {
				wpcom_vip_load_tag_base('t');
			}
		}
	}

	/**
	 * This is our main rewrite method. It handles switching out the default WordPress
	 * rules for custom rules.
	 *
	 * We base all our permalinks on category and subcategory. Most of our URL's for
	 * posts and post types have the category and subcategory in them.
	 *
	 * We generate additional endpoints for embedding and amp.
	 *
	 * @param array $rules The existing rules.
	 * @return array
	 */
	public function rewrite_rules($rules)
	{
		$new_rules  = array();
		$categories = get_categories(
			[
				'hide_empty' => false,
			]
		);

		$new_rules['(nz/music/music-news)/?$']            = 'index.php?post_type=pmc-nz&category_name=$matches[1]&paged=$matches[3]';

		if (!empty($categories) && is_array($categories)) {
			$slugs = array();

			foreach ($categories as $category) {
				if (is_object($category) && !is_wp_error($category)) {
					if (0 === $category->category_parent) {
						$slugs[] = $category->slug;
						$slugs[] = 'nz/' . $category->slug;
					} else {
						$slugs[] = trim(get_category_parents($category->term_id, false, '/', true), '/');
						$slugs[] = 'nz/' . trim(get_category_parents($category->term_id, false, '/', true), '/');
					}
				}
			}

			if (!empty($slugs)) {
				foreach ($slugs as $slug) {
					if (false !== strpos($slug, 'lists')) {
						// List rules.
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/feed/(feed|rdf|rss|rss2|atom)?/?$'] = 'index.php?pmc_list=$matches[2]&feed=$matches[4]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/(feed|rdf|rss|rss2|atom)/?$']       = 'index.php?pmc_list=$matches[2]&feed=$matches[4]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/embed/?$']                          = 'index.php?pmc_list=$matches[2]&embed=true';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/trackback/?$']                      = 'index.php?pmc_list=$matches[2]&tb=true';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/fbid(/(.*))?/?$']                   = 'index.php?pmc_list=$matches[2]&fbid=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/amp(/(.*))?/?$']                    = 'index.php?pmc_list=$matches[2]&amp=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)(/page/([0-9]{1,}))?/?$']            = 'index.php?pmc_list=$matches[2]&paged=$matches[5]';

						// List item rules.
						$new_rules['(' . $slug . ')/([^/]+)/([^/]+)-([0-9]+)/feed/(feed|rdf|rss|rss2|atom)?/?$'] = 'index.php?pmc_list_item=$matches[3]&feed=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)/([^/]+)-([0-9]+)/(feed|rdf|rss|rss2|atom)/?$']       = 'index.php?pmc_list_item=$matches[3]&feed=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)/([^/]+)-([0-9]+)/embed/?$']                          = 'index.php?pmc_list_item=$matches[3]&embed=true';
						$new_rules['(' . $slug . ')/([^/]+)/([^/]+)-([0-9]+)/trackback/?$']                      = 'index.php?pmc_list_item=$matches[3]&tb=true';
						$new_rules['(' . $slug . ')/([^/]+)/([^/]+)-([0-9]+)/fbid(/(.*))?/?$']                   = 'index.php?pmc_list_item=$matches[3]&fbid=$matches[6]';
						$new_rules['(' . $slug . ')/([^/]+)/([^/]+)-([0-9]+)/amp(/(.*))?/?$']                    = 'index.php?pmc_list_item=$matches[3]&amp=$matches[6]';
						$new_rules['(' . $slug . ')/([^/]+)/([^/]+)-([0-9]+)(/page/([0-9]{1,}))?/?$']            = 'index.php?pmc_list_item=$matches[3]&paged=$matches[6]';
					} elseif (false !== strpos($slug, 'videos')) {
						// Video rules.
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/feed/(feed|rdf|rss|rss2|atom)?/?$'] = 'index.php?pmc_top_video=$matches[2]&feed=$matches[4]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/(feed|rdf|rss|rss2|atom)/?$']       = 'index.php?pmc_top_video=$matches[2]&feed=$matches[4]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/embed/?$']                          = 'index.php?pmc_top_video=$matches[2]&embed=true';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/trackback/?$']                      = 'index.php?pmc_top_video=$matches[2]&tb=true';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/fbid(/(.*))?/?$']                   = 'index.php?pmc_top_video=$matches[2]&fbid=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/amp(/(.*))?/?$']                    = 'index.php?pmc_top_video=$matches[2]&amp=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)(/page/([0-9]{1,}))?/?$']            = 'index.php?pmc_top_video=$matches[2]&paged=$matches[5]';
					} elseif (false !== strpos($slug, 'pictures')) {
						// Gallery rules.
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/feed/(feed|rdf|rss|rss2|atom)?/?$'] = 'index.php?pmc-gallery=$matches[2]&feed=$matches[4]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/(feed|rdf|rss|rss2|atom)/?$']       = 'index.php?pmc-gallery=$matches[2]&feed=$matches[4]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/embed/?$']                          = 'index.php?pmc-gallery=$matches[2]&embed=true';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/trackback/?$']                      = 'index.php?pmc-gallery=$matches[2]&tb=true';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/fbid(/(.*))?/?$']                   = 'index.php?pmc-gallery=$matches[2]&fbid=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/amp(/(.*))?/?$']                    = 'index.php?pmc-gallery=$matches[2]&amp=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)(/page/([0-9]{1,}))?/?$']            = 'index.php?pmc-gallery=$matches[2]&paged=$matches[5]';

						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/([^/]+)/feed/(feed|rdf|rss|rss2|atom)?/?$'] = 'index.php?pmc-gallery=$matches[2]&feed=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/([^/]+)/(feed|rdf|rss|rss2|atom)/?$']       = 'index.php?pmc-gallery=$matches[2]&feed=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/([^/]+)/embed/?$']                          = 'index.php?pmc-gallery=$matches[2]&embed=true';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/([^/]+)/trackback/?$']                      = 'index.php?pmc-gallery=$matches[2]&tb=true';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/([^/]+)/fbid(/(.*))?/?$']                   = 'index.php?pmc-gallery=$matches[2]&fbid=$matches[6]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/([^/]+)/amp(/(.*))?/?$']                    = 'index.php?pmc-gallery=$matches[2]&amp=$matches[6]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/([^/]+)(/page/([0-9]{1,}))?/?$']            = 'index.php?pmc-gallery=$matches[2]&paged=$matches[6]';
					} elseif (false !== strpos($slug, 'nz/')) {
						// Post rules.
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/feed/(feed|rdf|rss|rss2|atom)?/?$'] = 'index.php?pmc-nz=$matches[2]&feed=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/(feed|rdf|rss|rss2|atom)/?$']       = 'index.php?pmc-nz=$matches[2]&feed=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/embed/?$']                          = 'index.php?pmc-nz=$matches[2]&embed=true';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/trackback/?$']                      = 'index.php?pmc-nz=$matches[2]&tb=true';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/fbid(/(.*))?/?$']                   = 'index.php?pmc-nz=$matches[2]&fbid=$matches[6]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/amp(/(.*))?/?$']                    = 'index.php?pmc-nz=$matches[2]&amp=$matches[6]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)(/page/([0-9]{1,}))?/?$']            = 'index.php?pmc-nz=$matches[2]&paged=$matches[6]';
					} else {
						// Post rules.
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/l(/(.*))?/?$']                    = 'index.php?name=$matches[2]';

						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/feed/(feed|rdf|rss|rss2|atom)?/?$'] = 'index.php?name=$matches[2]&feed=$matches[4]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/(feed|rdf|rss|rss2|atom)/?$']       = 'index.php?name=$matches[2]&feed=$matches[4]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/embed/?$']                          = 'index.php?name=$matches[2]&embed=true';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/trackback/?$']                      = 'index.php?name=$matches[2]&tb=true';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/fbid(/(.*))?/?$']                   = 'index.php?name=$matches[2]&fbid=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)/amp(/(.*))?/?$']                    = 'index.php?name=$matches[2]&amp=$matches[5]';
						$new_rules['(' . $slug . ')/([^/]+)-([0-9]+)(/page/([0-9]{1,}))?/?$']            = 'index.php?name=$matches[2]&paged=$matches[5]';
					}

					// Category rules.
					if (false !== strpos($slug, 'nz/')) {
						// $new_rules['(' . $slug . ')(/page/([0-9]{1,}))?/?$']            = 'index.php?post_type=pmc-nz&category_name=$matches[1]&paged=$matches[3]';
					}

					$new_rules['(' . $slug . ')/feed/(feed|rdf|rss|rss2|atom)?/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
					$new_rules['(' . $slug . ')/(feed|rdf|rss|rss2|atom)/?$']       = 'index.php?category_name=$matches[1]&feed=$matches[2]';
					$new_rules['(' . $slug . ')(/page/([0-9]{1,}))?/?$']            = 'index.php?category_name=$matches[1]&paged=$matches[3]';
				}
			}
		}

		// echo '<pre>';
		// print_r($new_rules);
		// exit;

		// Unset post type permalinks that we override.
		foreach ($rules as $rule => $pattern) {
			if (false !== strpos($rule, '%subcategory%')) {
				unset($rules[$rule]);
			}

			// Also remove category rules that get added by the post type args filter. Pages will break if this is not here.
			if (false !== strpos($pattern, 'category_name')) {
				unset($rules[$rule]);
			}
		}

		// Static rules.
		$new_rules['interactive/([^/]+)/?$'] = 'index.php?interactive=$matches[1]&static-page=1';

		// $new_rules['([^/]+)/([^/]+)/([^/]+)/music/post/([^/]+)'] = 'index.php?pagename=redirect-old-content&old-slug=$matches[4]';

		return $new_rules + $rules;
	}

	/**
	 * Removes the existing rewrite rules as we generate our own custom category
	 * rewrite rules in the method above.
	 *
	 * @param array $rules The existing category rules.
	 * @return array
	 */
	public function remove_category_rules($rules)
	{
		return array();
	}

	/**
	 * Filter post and post type permalinks to include category and subcategory
	 * in them.
	 *
	 * We also take care of rewriting the lists permalinks so that the list data
	 * is contained in each list item permalink.
	 *
	 * Importantly, if a category or subcategory is not assigned to a post or
	 * post type, we fallback to a default.
	 *
	 * @param string  $permalink The unfiltered permalnk.
	 * @param integer $post_id The ID of the post.
	 * @param bool    $leavename Whether to keep the post name.
	 *
	 * @return mixed
	 */
	public function filter_permalinks($permalink, $post_id, $leavename = false)
	{
		$post = get_post($post_id);

		if (empty($post)) {
			return $permalink;
		}

		// We don't want to do anything to the permalink if it's a auto-draft or it has no tags in it to be replaced.
		if (
			'auto-draft' === get_post_status($post) ||
			false === strpos($permalink, '%')
		) {
			return $permalink;
		}

		// We do not want to make any change if permalink have the form: http://domain/?p=x.
		if (preg_match('/\\/\\?p=\\d+\$/', $permalink)) {
			return $permalink;
		}

		if ('pmc_list_item' === $post->post_type) {

			// If we're dealing with a list item, category and subcategory should be from the parent list.
			$list = Lists::get_instance()->get_list_for_item($post->ID);

			if (!empty($list)) {
				$category    = rollingstone_get_the_category($list->ID);
				$subcategory = rollingstone_get_the_subcategory($list->ID);
			} else {
				// return false as the list does not exist to which the list item belongs to.
				return false; // @codeCoverageIgnore
			}
		} else {
			$category    = rollingstone_get_the_category($post->ID);
			$subcategory = rollingstone_get_the_subcategory($post->ID);
		}

		if (empty($category)) {
			$category = 'music';
		} else {
			$category = $category->slug;
		}

		if (empty($subcategory)) {
			$default_sub_category = 'news';

			if ('pmc_list' === $post->post_type || 'pmc_list_item' === $post->post_type) {
				$default_sub_category = 'lists';
			} elseif ('pmc_top_video' === $post->post_type) {
				$default_sub_category = 'videos';
			} elseif ('pmc-gallery' === $post->post_type) {
				$default_sub_category = 'pictures';
			}

			$subcategory = $category . '-' . $default_sub_category;

			// Movies is the only sub cat that we need to de-pluralize the default subcategory.
			$subcategory = str_replace('movies-news', 'movie-news', $subcategory);
		} else {
			$subcategory = $subcategory->slug;
		}

		$permalink = str_replace('%post_id%', $post->ID, $permalink);
		$permalink = str_replace('%category%', $category, $permalink);
		$permalink = str_replace('%subcategory%', $subcategory, $permalink);

		if (true !== $leavename) {
			$permalink = str_replace('%postname%', $post->post_name, $permalink);
		}

		// Only for list items.
		if (!empty($list)) {
			$permalink = str_replace('%list%', $list->post_name, $permalink);
			$permalink = str_replace('%list_id%', $list->ID, $permalink);
		}

		return $permalink;
	}

	/**
	 * Remove the category base.
	 *
	 * @param string $url The existing category permalink.
	 * @return mixed
	 */
	public function remove_category_base($url)
	{
		return str_replace('/' . get_option('category_base') . '/', '/', $url);
	}

	/**
	 * Set the default permalink structure.
	 *
	 * @param string $structure The existing structure.
	 * @return string
	 */
	public function set_permalink_structure($structure)
	{
		return $this->get_permalink_structure();
	}

	/**
	 * Reusable default permalink structure.
	 *
	 * @return string
	 */
	public function get_permalink_structure()
	{
		return '/%category%/%subcategory%/%postname%-%post_id%/';
	}

	/**
	 * Set the tag base.
	 *
	 * @param string $base The existing tag base.
	 * @return string
	 */
	public function set_tag_base($base)
	{
		return 't';
	}

	/**
	 * Adds the category and subcategory to the post type permalinks that we've
	 * defined.
	 *
	 * Also adds the list name and id if we're dealing with a list item.
	 *
	 * @param array  $args The post type args.
	 * @param string $name The name of the post type.
	 * @return mixed
	 */
	public function set_post_type_permalink($args, $name)
	{
		if (in_array($name, (array) self::POST_TYPES, true)) {
			$args['rewrite'] = array(
				// 'slug' => '%category%/%subcategory%',
				'slug' => 'pmc-nz' == $name ? 'nz/%category%/%subcategory%' : '%category%/%subcategory%',
			);

			if ('pmc_list_item' === $name) {
				$args['rewrite']['slug'] = $args['rewrite']['slug'] . '/%list%-%list_id%';
			}
		}

		return $args;
	}

	/**
	 * Change the vcategory taxonony rewrite to playlists.
	 *
	 * @param array  $args The taxonomy args.
	 * @param string $name The name of the taxonomy.
	 * @return mixed
	 */
	public function set_taxonomy_permalink($args, $name)
	{
		if ('vcategory' === $name) {
			$args['rewrite'] = array(
				'slug' => 'playlist',
			);
		}

		return $args;
	}

	/**
	 * Adds the ID of the post type to the end of the permalink.
	 *
	 * @param string  $permalink The post type permalink.
	 * @param integer $post_id The ID of the post.
	 * @return mixed
	 */
	public function filter_post_type_permalinks($permalink, $post_id)
	{
		$post = get_post($post_id);

		if ('auto-draft' === get_post_status($post)) {
			return $permalink;
		}

		if (!empty($post->post_type) && in_array($post->post_type, (array) self::POST_TYPES, true)) {
			$permalink = untrailingslashit($permalink);
			$permalink = $permalink . '-%post_id%/';
		}

		return $this->filter_permalinks($permalink, $post_id);
	}

	/**
	 * Rewrite tags used for determining static pages.
	 */
	public function add_rewrite_tags()
	{
		add_rewrite_tag('%interactive%', '([^/]+)');
		add_rewrite_tag('%static-page%', '([^/]+)');

		add_rewrite_tag('%old-slug%', '([^/]+)');
	}

	/**
	 * Overwrite the current template if we're viewing a static page.
	 *
	 * @param string $template Current them template.
	 * @return string
	 */
	public function set_static_pages($template)
	{
		$interactive = get_query_var('interactive');

		if (!empty($interactive) && !empty(get_query_var('static-page'))) {
			$template_file = 'static-pages/' . sanitize_title($interactive) . '.php';
			$template_file = locate_template($template_file);

			if ($template_file) {
				return $template_file;
			} else {
				global $wp_query;
				$wp_query->set_404();
			}
		}

		return $template;
	}
}

Rewrites::get_instance();
