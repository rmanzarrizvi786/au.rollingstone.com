<?php
/**
 * Utilitiy CLI Commands.
 *
 * @author  Kelin Chauhan <kelin.chauhan@rtcamp.com>
 *
 * @package pmc-rollingstone-2018
 */

namespace Rolling_Stone\Inc\WP_CLI;

/**
 * Contains utility commands for RS.
 *
 * @codeCoverageIgnore
 */
class CLI_Utilities extends \PMC_WP_CLI_Base {

	/**
	 *
	 * WP-CLI command to add meta to posts
	 *
	 * ## OPTIONS
	 *
	 * [--meta-key]
	 * : Meta key to add
	 *
	 * [--meta-value]
	 * : Meta value to add
	 *
	 * [--csv-file]
	 * : CSV file containing post ids
	 *
	 * [--dry-run]
	 * : Whether or not to do dry run
	 * ---
	 * default: false
	 * options:
	 *   - true
	 *   - false
	 *
	 * [--log-file=<file>]
	 * : Path to the log file.
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
	 * ## EXAMPLES
	 *
	 *      $ wp rs-utilities add_meta_to_guest_authors_from_csv --meta-key="test" --meta-value="test-value" --csv-file="authors.csv"
	 *
	 * @subcommand add_meta_to_guest_authors_from_csv
	 *
	 * @param array $args       Store all the positional arguments.
	 * @param array $assoc_args Store all the associative arguments.
	 */
	public function add_meta_to_guest_authors_from_csv( $args = array(), $assoc_args = array() ) {

		$this->_extract_common_args( $assoc_args );

		if ( $this->dry_run ) {
			$this->_warning( 'You have called the command wp rs-utilities add_meta_to_guest_authors_from_csv in dry run mode.' . PHP_EOL );
			$this->_write_log( '------------------------------------------------------------------------------', 0 );
		}

		if ( empty( $assoc_args['meta-key'] ) ) {
			$this->_error( 'Please pass meta key.' );
			return;
		}

		if ( ! isset( $assoc_args['meta-value'] ) ) {
			$this->_error( 'Please pass meta value.' );
			return;
		}

		if ( empty( $assoc_args['csv-file'] ) ) {
			$this->_error( 'Please pass csv file.' );
			return;
		}

		if ( ! file_exists( $assoc_args['csv-file'] ) ) {
			$this->_error( 'Given .csv file does not exists.' );
			return;
		}

		$this->_notify_start( 'WP-CLI command wp pmc-meta add_meta_to_post_from_csv: Started' );
		$this->_write_log( '------------------------------------------------------------------------------', 0 );

		$meta_key   = sanitize_key( $assoc_args['meta-key'] );
		$meta_value = $assoc_args['meta-value'];
		$csv_file   = $assoc_args['csv-file'];

		$handle = fopen( $csv_file, 'r' ); // @codingStandardsIgnoreLine

		if ( false === $handle ) {
			$this->_error( 'Please pass a valid .csv file in command.' );

			return;
		}

		$count        = 0;
		$post_updated = 0;
		$failed_count = 0;

		global $coauthors_plus;

		if ( ! isset( $coauthors_plus ) || empty( $coauthors_plus ) || ! isset( $coauthors_plus->guest_authors ) ) {
			$this->_error( 'Something went wrong!' );
			return;
		}

		while ( ( $row = fgetcsv( $handle, 1000, ',' ) ) !== false ) {  // @codingStandardsIgnoreLine

			$count ++;

			if ( 0 === ( $count % 100 ) ) {
				$this->stop_the_insanity();
				sleep( 2 );
			}

			$post_name = ( isset( $row[0] ) ) ? $row[0] : '';
			$post_url  = ( isset( $row[1] ) ) ? $row[1] : '';

			// Skip the iteration if post url is missing.
			if ( empty( $post_url ) ) {
				$this->_warning( sprintf( 'Skipped author (%s) at line %d; Invalid data', $post_name, $count ) );
				$failed_count++;
				continue;
			}

			// Retrieve post using the title.
			$post = $coauthors_plus->guest_authors->get_guest_author_by( 'post_name', basename( untrailingslashit( $post_url ) ) );

			// skip iteration if guest author id not found.
			if ( empty( $post ) ) {
				$this->_warning( sprintf( "Skipped author (%s); couldn't find author", $post_name ) );
				$failed_count++;
				continue;
			}

			if ( ! $this->dry_run ) {
				$added = update_post_meta( $post->ID, $meta_key, $meta_value );
				if ( false !== $added ) {
					$this->_success( sprintf( 'Meta (%s) is added to author %d (%s)', $meta_key . ' => ' . $meta_value, $post->ID, $post_name ) );
					$post_updated ++;
				} else {
					$failed_count++;
					$this->_warning( sprintf( 'Failed adding meta to author %d (%s)', $post->ID, $post_name ) );
					continue;
				}

			} else {
				$this->_success( sprintf( 'Meta (%s) will be added to author %d (%s)', $meta_key . ' => ' . $meta_value, $post->ID, $post_name ) );
			}

		} // while.

		if ( ! $this->dry_run ) {
			$this->_write_log( '------------------------------------------------------------------------------', 0 );
			$this->_success( sprintf( 'Total authors processed %d', $count ) );
			$this->_success( sprintf( 'Total authors skipped   %d', $failed_count ) );
			$this->_success( sprintf( 'Total authors updated   %d', $post_updated ) );
		} else {
			$this->_write_log( '------------------------------------------------------------------------------', 0 );
			$this->_success( sprintf( 'Total authors processed       %d', $count ) );
			$this->_success( sprintf( 'Total authors will be skipped %d', $failed_count ) );
			$this->_success( sprintf( 'Total authors will be updated %d', $post_updated ) );
		}

		$this->_write_log( '------------------------------------------------------------------------------', 0 );
		$this->_notify_done( 'WP-CLI command wp rs-utilities add_meta_to_guest_authors_from_csv Completed' );

	}

	/**
	 *
	 * WP-CLI command to move posts from rs-recommends tag to product-recommendations category.
	 *
	 * ## OPTIONS
	 *
	 * [--csv-file]
	 * : CSV file containing post ids
	 *
	 * [--dry-run]
	 * : Whether or not to do dry run
	 * ---
	 * default: false
	 * options:
	 *   - true
	 *   - false
	 *
	 * [--log-file=<file>]
	 * : Path to the log file.
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
	 * ## EXAMPLES
	 *
	 *      $ wp rs-utilities move_posts_to_product_recommendations_category --csv-file="posts.csv"
	 *
	 * @subcommand move_posts_to_product_recommendations_category
	 *
	 * @param array $args       Store all the positional arguments.
	 * @param array $assoc_args Store all the associative arguments.
	 */
	public function move_posts_to_product_recommendations_category( $args = array(), $assoc_args = array() ) {

		$this->_extract_common_args( $assoc_args );

		if ( $this->dry_run ) {
			$this->_warning( 'You have called the command wp rs-utilities move_posts_to_product_recommendations_category in dry run mode.' . PHP_EOL );
			$this->_write_log( '------------------------------------------------------------------------------', 0 );
		}

		if ( empty( $assoc_args['csv-file'] ) ) {
			$this->_error( 'Please pass csv file.' );
			return;
		}

		if ( ! file_exists( $assoc_args['csv-file'] ) ) {
			$this->_error( 'Given .csv file does not exists.' );
			return;
		}

		$this->_notify_start( 'WP-CLI command wp rs-utilities move_posts_to_product_recommendations_category: Started' );
		$this->_write_log( '------------------------------------------------------------------------------', 0 );

		if ( ! class_exists( '\WPCOM_Legacy_Redirector' ) ) {
			$this->_error( '\WPCOM_Legacy_Redirector not found' );
			return;
		}

		$csv_file = $assoc_args['csv-file'];
		$handle   = fopen( $csv_file, 'r' ); // @codingStandardsIgnoreLine

		if ( false === $handle ) {
			$this->_error( 'Please pass a valid .csv file in command.' );

			return;
		}

		$count        = 0;
		$posts_moved  = 0;
		$failed_count = 0;

		$headers = fgetcsv( $handle, 1000, ',' );

		while ( ( $row = fgetcsv( $handle, 1000, ',' ) ) !== false ) {  // @codingStandardsIgnoreLine

			$count ++;

			if ( 0 === ( $count % 100 ) ) {
				$this->stop_the_insanity();
				sleep( 2 );
			}

			$data = array_combine( $headers, $row );

			$post_title         = ( isset( $data['post_title'] ) ) ? $data['post_title'] : '';
			$current_categories = ( isset( $data['current_categories'] ) ) ? $data['current_categories'] : '';
			$post_url           = ( isset( $data['post_url'] ) ) ? $data['post_url'] : '';
			$new_categories     = ( isset( $data['new_categories'] ) ) ? $data['new_categories'] : '';
			$tags               = ( isset( $data['tags'] ) ) ? $data['tags'] : '';

			// Skip the iteration if any of post title / new category / current categories / tags is missing.
			if ( empty( $post_title ) || empty( $current_categories ) || empty( $post_url ) || empty( $new_categories ) || empty( $tags ) ) {
				$this->_warning( sprintf( 'Skipped post (%s) at line %d; Invalid data', $post_title, $count ) );
				$failed_count++;
				continue;
			}

			// Retrieve post using the url.
			$post_id = wpcom_vip_url_to_postid( $post_url );
			$post    = get_post( $post_id );

			if ( empty( $post ) ) {
				$this->_warning( sprintf( 'Skipped post (%s) at line (%d); post not found', $post_title, $count ) );
				$failed_count++;
				continue;
			}

			$new_categories     = explode( '/', trim( $new_categories, '/' ) );
			$current_categories = explode( ',', $current_categories );
			$tags               = explode( ',', $tags );

			// Verify that the post has all the tags from CSV.
			foreach ( $tags as $tag ) {
				$tag  = str_replace( "\xc2\xa0", '', $tag ); // Need to remove the non-breaking space.
				$term = wpcom_vip_term_exists( trim( $tag ), 'post_tag' );

				// Skip the iteration if the tag does not exist.
				if ( empty( $term ) ) {
					$this->_warning( sprintf( 'Skipped post (%s) at line (%d); Tag (%s) not found', $post_title, $count, $tag ) );
					$failed_count++;
					continue 2; // Break out of foreach and continue the outer while loop.
				}

				// Skip the current iteration for post if it does not have the required tags.
				if ( ! has_tag( $term['term_id'], $post ) ) {
					$this->_warning( sprintf( 'Skipped post (%s) at line (%d); Tag (%s) not assigned to post', $post_title, $count, $tag ) );
					$failed_count++;
					continue 2; // Break out of foreach and continue the outer while loop.
				}
			}

			// Verify that post has all the categories from CSV.
			foreach ( $current_categories as $current_category ) {
				$current_category = str_replace( "\xc2\xa0", '', $current_category );
				$term             = wpcom_vip_term_exists( trim( $current_category ), 'category' );

				// Skip the iteration if the category does not exist.
				if ( empty( $term ) ) {
					$this->_warning( sprintf( 'Skipped post (%s) at line (%d); Category (%s) not found', $post_title, $count, $current_category ) );
					$failed_count++;
					continue 2; // Break out of foreach and continue the outer while loop.
				}

				// Skip the current iteration for post if it does not have the required categories.
				if ( ! has_category( $term['term_id'], $post ) ) {
					$this->_warning( sprintf( 'Skipped post (%s) at line (%d); current category / sub category (%s) not assigned to post', $post_title, $count, $current_category ) );
					$failed_count++;
					continue 2; // Break out of foreach and continue the outer while loop.
				}
			}

			// Fetch and verify that the new categories exists in the db.
			$new_parent_category = $new_categories[0];
			$new_sub_category    = $new_categories[1];

			$new_parent_category = wpcom_vip_term_exists( $new_parent_category, 'category' );

			if ( empty( $new_parent_category ) || ! isset( $new_parent_category['term_id'] ) ) {
				$this->_warning( sprintf( 'Skipped post (%s) at line (%d); new parent category (%s) not found', $post_title, $count, $new_categories[0] ) );
				$failed_count++;
				continue;
			}

			$new_sub_category = wpcom_vip_term_exists( $new_sub_category, 'category', $new_parent_category['term_id'] );

			if ( empty( $new_sub_category ) || ! isset( $new_sub_category['term_id'] ) ) {
				$this->_warning( sprintf( 'Skipped post (%s) at line (%d); new sub category (%s) not found', $post_title, $count, $new_categories[1] ) );
				$failed_count++;
				continue;
			}

			if ( ! $this->dry_run ) {

				// Get the permalink of the post to add redirection.
				$original_url       = get_permalink( $post->ID );
				$redirect_from_path = wp_parse_url( $original_url, PHP_URL_PATH );

				// Add new categories.
				wp_set_object_terms(
					$post->ID,
					[
						intval( $new_parent_category['term_id'] ),
						intval( $new_sub_category['term_id'] ),
					],
					'category'
				);

				// Replace new categories data in postmeta.
				// @see https://bitbucket.org/penskemediacorp/pmc-core-v2/src/1b94f1da1b0573239ac187493e7ba03149987e2d/inc/classes/fieldmanager/class-fields.php#lines-252
				update_post_meta( $post->ID, 'categories', $new_parent_category['term_id'] );
				update_post_meta( $post->ID, 'subcategories', $new_sub_category['term_id'] );

				// Add post option to exclude the post from google news sitemap.
				wp_add_object_terms( $post->ID, 'exclude-from-google-news', '_post-options' );

				// Add redirection to the updated url based on new categories.
				$inserted = \WPCOM_Legacy_Redirector::insert_legacy_redirect( $redirect_from_path, $post->ID );

				if ( ! $inserted || is_wp_error( $inserted ) ) {
					$this->_warning( sprintf( 'Could not insert redirection for %s ', $original_url ) );
					$this->_warning( $inserted->get_error_message() );
					$failed_count++;
				}

				$this->_success( sprintf( '(%s) post moved to %s', $post_title, $data['new_categories'] ) );
				$posts_moved++;
			} else {

				$posts_moved++;
				$this->_success( sprintf( '(%s) post will be moved to %s', $post_title, $data['new_categories'] ) );
			}
		} // while.

		if ( ! $this->dry_run ) {
			$this->_write_log( '------------------------------------------------------------------------------', 0 );
			$this->_success( sprintf( 'Total posts processed %d', $count ) );
			$this->_success( sprintf( 'Total posts skipped   %d', $failed_count ) );
			$this->_success( sprintf( 'Total posts moved     %d', $posts_moved ) );
		} else {
			$this->_write_log( '------------------------------------------------------------------------------', 0 );
			$this->_success( sprintf( 'Total posts processed       %d', $count ) );
			$this->_success( sprintf( 'Total posts will be skipped %d', $failed_count ) );
			$this->_success( sprintf( 'Total posts will be moved   %d', $posts_moved ) );
		}

		$this->_write_log( '------------------------------------------------------------------------------', 0 );
		$this->_notify_done( 'WP-CLI command wp rs-utilities move_posts_to_product_recommendations_category Completed' );
	}

}
