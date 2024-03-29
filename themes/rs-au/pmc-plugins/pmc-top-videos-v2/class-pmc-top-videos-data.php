<?php

/**
 * Define PMC_Top_Videos_Data class while extending Singleton
 *
 */

namespace PMC\Top_Videos_V2;

use \PMC\Global_Functions\Traits\Singleton;

class PMC_Top_Videos_Data {

	// use Singleton;

	/**
	 * Class constructor.
	 *
	 * Initialize couple of methods, fetch and save youtube data at the
	 * time of fetch top videos load.
	 */
	public function __construct() {

		add_action( 'pmc_fetch_top_videos', array( $this, 'fetch_and_save_youtube_data' ) );

		add_action( 'pmc_update_playcounts', array( $this, 'update_playcounts' ) );

		if ( ! wp_next_scheduled( 'pmc_fetch_top_videos' ) ) {
			//RUN 1 am PST/PDT which makes it 8:00 GMT
			wp_schedule_event( strtotime( 'today 8:00' ), 'twicedaily', 'pmc_fetch_top_videos' );
		}

		if ( ! wp_next_scheduled( 'pmc_update_playcounts' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'pmc_update_playcounts' );
		}

	}

	/**
	 * Fetch and save youtube data, checks for main class PMC_Top_Videos
	 * existence and then fetch the data.
	 */
	public function fetch_and_save_youtube_data() {

		//Make sure that PMC_Top_Videos exists else no point fetching data.
		if ( ! class_exists( 'PMC_Top_Videos' ) ) {
			return;
		}

		$count = 25;

		/**
		 * Filters the endpoint to fetch YouTube data from.
		 *
		 * The fetched data is expected to be in JSON format with an array of 25 entries at $data->feed->entry.
		 *
		 * Example: http://gdata.youtube.com/feeds/api/users/variety/uploads?v=2&alt=json&orderby=published&max-results=25
		 *
		 * @param string A URL or an empty string.
		 */
		$data_endpoint = apply_filters( 'pmc_top_videos_youtube_data_endpoint', '' );

		if ( empty( $data_endpoint ) ) {
			return;
		}

		$data = json_decode( wpcom_vip_file_get_contents( $data_endpoint ) );

		if ( ! empty( $data ) ) {

			$entries = $data->feed->entry;

			if ( ! is_array( $entries ) ) {
				return;
			}

			$count = min( $count, count( $entries ) ) - 1;

			for ( $i = $count; $i > -1; $i-- ) {
				if ( isset( $entries[ $i ] ) ) {
					$entry = $entries[ $i ];
					PMC_Top_Videos::get_instance()->insert_post( $entry );
				}
			}
		}

	}
	/**
	 * Updates playcounts for all videos less than 30 day old
	 *
	 * @codeCoverageIgnore
	 *
	 * @return void.
	 */
	public function update_playcounts() {

		$v_top_videos   = PMC_Top_Videos::get_instance();
		$all_videos     = array();
		$all_playcounts = array();

		// Usage of meta_key is required, we are only using meta_key here as it is indexed.
		$query_args = array(
			'posts_per_page' => 50,
			'post_type'      => PMC_Top_Videos::POST_TYPE_NAME,
			'meta_key'       => '_pmc_top_video_id', // WPCS: Slow query okay.
			'paged'          => 1, // API only allows for up to 50 requests at a time.
		);

		add_filter( 'posts_where', array( $v_top_videos, 'where_last_30_days' ) );

		$query = new \WP_Query( $query_args );

		if ( $query->have_posts() ) {
			while ( $query_args['paged'] <= $query->max_num_pages ) {
				// We've already queried for page one, but above that we need to requery for the next page.
				if ( 1 !== $query_args['paged'] ) {
					$query = new \WP_Query( $query_args );
				}

				$videos     = array();
				$playcounts = array();
				while ( $query->have_posts() ) {
					$query->the_post();
					$videos[ get_the_id() ] = get_post_meta( get_the_id(), '_pmc_top_video_id', true );
				}
				$playcounts = $this->get_updated_playcounts( $videos );

				$all_videos     = $all_videos + $videos;
				$all_playcounts = $all_playcounts + $playcounts;

				$query_args['paged'] += $query_args['paged'];
			}

			remove_filter( 'posts_where', array( $v_top_videos, 'where_last_30_days' ) );

			foreach ( $all_videos as $post_id => $video_id ) {
				if ( isset( $all_playcounts[ $video_id ]['playcount'] ) ) {

					$post_arr = array(
						'menu_order' => $all_playcounts[ $video_id ]['playcount'],
						'ID'         => $post_id,
					);

					wp_update_post( $post_arr );
				}
			}
		}

		wp_reset_postdata();

	}
	/**
	 * Gets an array of current playcounts from YouTube based on video ID
	 *
	 * This uses the youtube batch request system to get multiple updated playcounts in one request. You
	 * can request up to 50 videos at at time. The downside of this batch API is that there is no way to
	 * get the data back as JSON. Also, for some reason SimpleXMLElement chokes on the ATOM feed. However,
	 * loading the data into SimplePie works great, which is what we do. We filter the fetch feed function
	 * to allow loading local data rather than actually trying to fetch a feed. We can't directly query
	 * with SimplePie because the YouTube API requires you to POST XML data to the API. It comes back as
	 * a valid ATOM feed.
	 *
	 * @param array $videos The ids of the videos we want to get updated playcounts for.
	 * @return array Each video is an element containing an associative array with 'link' and 'playcount'
	 */
	private function get_updated_playcounts( $videos ) {

		if ( ! is_array( $videos ) ) {
			return false;
		}

		$video_data = '';

		foreach ( $videos as $video ) {
			$video_data .= "<entry>
		<id>http://gdata.youtube.com/feeds/api/videos/$video</id>
		</entry>";
		}

		if ( ! empty( $video_data ) ) {

			$post_request = '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/"
			xmlns:batch="http://schemas.google.com/gdata/batch" xmlns:yt="http://gdata.youtube.com/schemas/2007">
			<batch:operation type="query" />' . $video_data . '</feed>';

			$response = wp_remote_post(
				'http://gdata.youtube.com/feeds/api/videos/batch', array(
					'headers' => array( 'content-type' => 'application/xml' ),
					'body'    => $post_request,
				)
			);

			if ( 200 === wp_remote_retrieve_response_code( $response ) ) {

				// Get a SimpleXML element based on the response.
				add_action( 'wp_feed_options', array( $this, 'use_local_data' ), 10, 2 );
				$response_data = fetch_feed( wp_remote_retrieve_body( $response ) );
				remove_action( 'wp_feed_options', array( $this, 'use_local_data' ), 10, 2 );

				// Grab the data out of the feed.
				$playcounts = array();

				// VIP 14 Nov 2013: stop fatals when RSS feed returns an error
				if ( is_wp_error( $response_data ) ) {
					return $playcounts;
				}

				foreach ( $response_data->get_items() as $entry ) {
					$stats = $entry->get_item_tags( 'http://gdata.youtube.com/schemas/2007', 'statistics' );
					$link  = str_replace( '&amp;feature=youtube_gdata', '', $entry->get_link() );
					$id    = str_replace( 'http://www.youtube.com/watch?v=', '', $link );

					if ( isset( $stats[0]['attribs']['']['viewCount'] ) && ! empty( $link ) ) {
						$playcounts[ $id ] = array(
							'link'      => $link,
							'playcount' => $stats[0]['attribs']['']['viewCount'],
						);
					}
				}

				return $playcounts;
			}
		}
	}

	/**
	 * Allows for the use of local data when using the fetch feed function
	 *
	 * @codeCoverageIgnore
	 *
	 * @param obj $feed The current SimpleXML object
	 * @param string $data Our local XML data to parse
	 */
	public function use_local_data( $feed, $data ) {
		$feed = new \SimplePie();
		$feed->set_raw_data( $data );
	}
}

//EOF
