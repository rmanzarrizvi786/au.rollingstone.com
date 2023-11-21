<?php
/**
 * CLI Commands for Lists post type.
 *
 * @author  Dhaval Parekh <dhaval.parekh@rtcamp.com>
 *
 * @package pmc-rollingstone-2018
 */

namespace Rolling_Stone\Inc\WP_CLI;

class Lists extends \PMC_WP_CLI_Base
{

	/**
	 * To get closest possible URL for List post type by csv.
	 *
	 * ## OPTIONS
	 *
	 * [--csv=<file>]
	 * : Path to csv file
	 *
	 * [--output-csv=<file>]
	 * : Path to output csv file
	 *
	 * [--log-file=<file>]
	 * : Path to log file
	 *
	 * [--email]
	 * : Email to send notification after script complete.
	 *
	 * [--email-when-done]
	 * : Whether to send notification or not.
	 *
	 * [--email-logfile]
	 * : Whether to send log file or not.
	 *
	 *
	 * ## EXAMPLES
	 *
	 *      wp rs-lists find_list_item_urls_from_csv --csv=input.csv --output-csv=output.csv
	 *
	 * @param array $args       Store all the positional arguments.
	 * @param array $assoc_args Store all the associative arguments.
	 */
	public function find_list_item_urls_from_csv($args = array(), $assoc_args = array())
	{

		$this->_extract_common_args($assoc_args);

		if (empty($assoc_args['csv'])) {
			$this->_write_log('Please pass .csv file in command.', -1);
		} elseif (!file_exists($assoc_args['csv'])) {
			$this->_write_log('Given .csv file does not exists.', -1);
		}

		if (empty($assoc_args['output-csv'])) {
			$this->_write_log('Please pass output csv path in command.', -1);
		}

		if ($this->dry_run) {
			$this->_write_log('Dry Run -- ' . PHP_EOL);
		} else {
			$this->_write_log('Actual Run -- ' . PHP_EOL);
		}

		$csv_file = $assoc_args['csv'];
		$output_csv = $assoc_args['output-csv'];

		$this->write_to_csv($output_csv, [], [['from', 'to']]);

		$csv_data = $this->_csv_to_array($csv_file);
		$output_csv_data = [];

		$count = 0;

		foreach ($csv_data as $row) {
			$from = (!empty($row['from'])) ? untrailingslashit($row['from']) : false;

			if (empty($from)) {
				continue;
			}

			$count++;

			$destination_url = 'Not Found';
			$post = $this->_get_list_item_by_legacy_url($from);

			if (empty($post) || !is_a($post, 'WP_Post')) {
				$post = $this->_get_list_item_by_url($from);
			}

			if (!empty($post) && is_a($post, 'WP_Post')) {
				$destination_url = get_permalink($post->ID);
				$destination_url = wp_parse_url($destination_url, PHP_URL_PATH);
				$destination_url = isset($destination_url) ? $destination_url : '';
				$destination_url = trailingslashit(rtrim($destination_url, '/'));
			}

			$from_url = trailingslashit($from);

			$output_csv_data[] = [
				'from' => $from_url,
				'to' => $destination_url,
			];

			$this->_write_log(sprintf('%d : %s => %s', $count, $from_url, $destination_url));

			if (0 === ($count % 5)) {

				$this->write_to_csv($output_csv, [], $output_csv_data, null, 'a');
				$output_csv_data = [];

				// Pause.
				sleep(2);

				// Free up Memory.
				$this->stop_the_insanity();

			}
		}

		$this->write_to_csv($output_csv, [], $output_csv_data, null, 'a');

	}

	/**
	 * To get List item post by it's legacy url.
	 *
	 * @param string $legacy_url Legacy URL
	 *
	 * @return bool|\WP_Post False if there is no post, otherwise WP_Post object.
	 */
	protected function _get_list_item_by_legacy_url($legacy_url)
	{

		if (empty($legacy_url)) {
			return false;
		}

		$posts_per_page = 1;

		$args = array(
			'post_type' => 'pmc_list_item',
			'posts_per_page' => $posts_per_page,
			'suppress_filters' => true,
			'post_status' => 'publish',
			'meta_query' => [ // WPCS: slow query ok.
				'legacy_slug_clause' => [
					'key' => 'legacy_slug',
					'value' => $legacy_url,
				],
			],
		);

		$query = new \WP_Query($args);

		$post = (!empty($query->posts) && is_array($query->posts)) ? $query->posts[0] : false;

		if (empty($post) || !is_a($post, 'WP_Post')) {
			return false;
		}

		return $post;
	}

	/**
	 * Get List item by URL.
	 *
	 * @param string $url URL
	 *
	 * @return bool|\WP_Post False if there is no post, otherwise WP_Post object.
	 */
	protected function _get_list_item_by_url($url)
	{

		if (empty($url)) {
			return false;
		}

		// Make sure, URL is without domain and trilling with `/`.
		// For regex to work.
		$url = wp_parse_url($url, PHP_URL_PATH);
		$url = sprintf('/%s/', trim($url, '/'));

		/**
		 * For Url like `/music/pictures/angelina-uncovered-19990727/jolie7-55484702/`
		 *
		 * https://regex101.com/r/LXO3Zo/1/
		 */
		$regex = '/(?P<category>(?:[\w-]+)\/(?:[\w-]+))\/(?P<slug>(?:[\w-]+))(-[0-9]{0,})?\/(?P<child>(?:[\w-\/]*))(-[0-9]{0,})?\//imU';

		$matches = [];

		preg_match($regex, $url, $matches);

		if (empty($matches)) {
			/**
			 * For Url like `/random-notes-2012/a-present-for-nickelback-0125064/`
			 *
			 * https://regex101.com/r/LXO3Zo/2/
			 */
			$regex = '/^\/(?P<slug>(?:random[\w-]+))\/(?P<child>(?:[\w-\/]*))(-[0-9]{0,})?\//imU';
			preg_match($regex, $url, $matches);
		}

		$list_slug = (!empty($matches['slug'])) ? trim($matches['slug'], '/') : '';
		$list_item_slug = (!empty($matches['child'])) ? trim($matches['child'], '/') : '';

		if (empty($list_slug)) {
			return;
		}

		$args = [
			'post_type' => 'pmc_list',
			'posts_per_page' => 1,
			'suppress_filters' => true,
			'post_status' => 'publish',
			'name' => $list_slug,
		];

		$query = new \WP_Query($args);
		$posts = $query->posts;

		$post = (!empty($posts[0]) && is_a($posts[0], 'WP_Post')) ? $posts[0] : false;

		unset($query);

		if (empty($post)) {
			return false;
		}

		if (empty($list_item_slug)) {
			return $post;
		}

		// Go for child item.

		$args = [
			'post_type' => 'pmc_list_item',
			'posts_per_page' => 1,
			'suppress_filters' => true,
			'post_status' => 'publish',
			'name' => $list_item_slug,
		];

		$query = new \WP_Query($args);
		$list_items = $query->posts;

		$list_item = (!empty($list_items[0]) && is_a($list_items[0], 'WP_Post')) ? $list_items[0] : false;

		unset($query);

		$relation_terms = get_the_terms($list_item, 'pmc_list_relation');

		if (empty($relation_terms) || is_wp_error($relation_terms) || !is_array($relation_terms)) {
			return $post;
		}

		$relation_terms = wp_list_pluck((array) $relation_terms, 'slug');
		$relation_terms = array_map('absint', (array) $relation_terms);

		if (!empty($list_item) && in_array(absint($post->ID), (array) $relation_terms, true)) {
			return $list_item;
		}

		return $post;
	}

	/**
	 * Convert csv to array
	 *
	 * @param  string $filename  CSV file.
	 * @param  string $delimiter CSV file delimiter.
	 *
	 * @link   http://gist.github.com/385876
	 *
	 * @return array $data
	 */
	private function _csv_to_array($filename = '', $delimiter = ',')
	{

		if (!file_exists($filename) || !is_readable($filename)) {
			return false;
		}

		$header = '';
		$data = array();

		$handle = fopen($filename, 'r'); // @codingStandardsIgnoreLine

		if (false === $handle) {
			WP_CLI::error('Please pass a valid .csv file in command.');
		}

		$index = 0;

		while (($row = fgetcsv($handle, 10000, $delimiter)) !== false) { // @codingStandardsIgnoreLine

			$index++;

			if (empty($header)) {
				$header = array_map('sanitize_title', (array) $row);
			} else {
				$data[] = array_combine($header, $row);
			}
		}

		fclose($handle); // @codingStandardsIgnoreLine

		return $data;
	}

}
