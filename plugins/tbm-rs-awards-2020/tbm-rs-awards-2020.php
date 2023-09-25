<?php

/**
 * Plugin Name: Rolling Stone Awards (2020)
 * Plugin URI: https://thebrag.com/media/
 * Description:
 * Version: 1.0.0
 * Author: Sachin Patel
 * Author URI: http://www.patelsachin.com
 */

class TBM_RollingStoneAwards2020
{

	protected $plugin_name;
	protected $plugin_slug;

	public function __construct()
	{
		$this->plugin_name = 'tbm_rolling_stone_awards';
		$this->plugin_slug = 'tbm-rolling-stone-awards';

		add_action('admin_menu', [$this, '_admin_menu']);

		add_action('wp_ajax_nominate_readers_award_2020', [$this, 'nominate_readers_award_2020']);
		add_action('wp_ajax_nopriv_nominate_readers_award_2020', [$this, 'nominate_readers_award_2020']);

		add_action('wp_ajax_vote_readers_award_2020', [$this, 'vote_readers_award_2020']);
		add_action('wp_ajax_nopriv_vote_readers_award_2020', [$this, 'vote_readers_award_2020']);
	}

	public function _admin_menu()
	{
		add_menu_page('Rolling Stone Awards (2021)', 'Rolling Stone Awards (2021)', 'edit_posts', $this->plugin_slug, [$this, 'index'], 'dashicons-awards', 20);
		add_submenu_page($this->plugin_slug, 'Votes (2021)', 'Votes (2021)', 'edit_posts', $this->plugin_slug . '-votes', [$this, 'index_votes']);
	}

	public function nominate_readers_award_2020()
	{
		if (!is_user_logged_in())
			wp_send_json_error('Please login and try again');

		if (empty($_POST['artist_name']))
			wp_send_json_error('Please enter an artist name');

		if (empty($_POST['entry']))
			wp_send_json_error("Please answer in 25 words on less why you're voting for this artist.");

		$current_user = wp_get_current_user();
		$field_1 = sanitize_text_field($_POST['artist_name']);
		$entry = sanitize_text_field($_POST['entry']);

		global $wpdb;

		$wpdb->insert(
			$wpdb->prefix . 'rsawards_votes',
			[
				'user_id' => $current_user->ID,
				'artist_name' => $field_1,
				'entry' => $entry,
			],
			['%d', '%s', '%s']
		);

		$rs_nominated = get_user_meta($current_user->ID, 'rsawards_voted', $single);

		if (!isset($rs_nominated) || $rs_nominated != true) {
			$email_body = '';
			ob_start();
			include(get_template_directory() . '/page-templates/rs-awards/email-body.php');
			$email_body = ob_get_contents();
			ob_end_clean();

			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			$headers[] = 'From: Rolling Stone Australia <noreply@thebrag.media>';

			wp_mail(
				$current_user->user_email,
				'Thank you, your vote is official',
				$email_body,
				$headers
			);

			add_user_meta($current_user->ID, 'rsawards_voted', true, true);
		}

		wp_send_json_success('Thank you, your vote has been recorded');
	}

	public function vote_readers_award_2020()
	{
		if (!is_user_logged_in())
			wp_send_json_error('Please login and try again');

		$current_user = wp_get_current_user();

		global $wpdb;

		$artist_id = absint($_POST['artist_id']);

		$wpdb->delete(
			$wpdb->prefix . 'rsawards_votes_final',
			[
				'user_id' => $current_user->ID,
			]
		);

		$wpdb->insert(
			$wpdb->prefix . 'rsawards_votes_final',
			[
				'user_id' => $current_user->ID,
				'artist_id' => $artist_id,
			],
			['%d', '%d',]
		);
		wp_send_json_success('Thank you, your vote has been recorded');
	}

	public function index()
	{
		global $wpdb;
		// wp_enqueue_script('bs', get_template_directory_uri() . '/bs/js/bootstrap.bundle.min.js', array('jquery'), NULL, true);
		wp_enqueue_style('bs', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css');
?>
		<style>
			.dark a.active,
			.dark a.active:hover,
			.dark a.active:focus {
				background-color: #000 !important;
				color: #fff !important;
			}
		</style>
		<div class="container-fluid">
			<h1 class="my-3">Rolling Stone Australia Readers Award 2021 Nominations</h1>
			<div class="row">

				<?php

				$artists = $wpdb->get_results("SELECT artist_name, COUNT(DISTINCT(user_id)) count_entries FROM `{$wpdb->prefix}rsawards_votes` GROUP BY artist_name ORDER BY count_entries DESC, id ASC");
				if ($artists) :
				?>

					<div class="col-md-6">
						<h2>Top artists</h2>
						<table class="table table-sm">
							<tr>
								<th>#</th>
								<th>Artist</th>
								<th># of entries</th>
							</tr>
							<?php
							$counter = 1;
							foreach ($artists as $artist) :
							?>
								<tr>
									<td><?php echo $counter; ?></td>
									<td><?php echo $artist->artist_name; ?></td>
									<td><?php echo $artist->count_entries; ?></td>
								</tr>
							<?php
								$counter++;
							endforeach;
							?>
						</table>
					</div>
				<?php endif; // If $artists
				?>


				<?php

				$noms = $wpdb->get_results("SELECT user_id, artist_name, entry FROM `{$wpdb->prefix}rsawards_votes`  WHERE 1 ORDER BY id DESC");

				if ($noms) :
				?>

					<div class="col-md-6">
						<h2>Individual entries</h2>
						<?php
						// $noms = array_reverse($noms);
						?>
						<table class="table table-sm">
							<tr>
								<th>#</th>
								<th>Artist Name</th>
								<th>Entry</th>
								<th>Submitted by</th>
							</tr>
							<?php
							$counter = 1;
							foreach ($noms as $nom) :
							?>
								<tr>
									<th><?php echo $counter; ?></th>
									<td><?php echo $nom->artist_name; ?></td>
									<td><?php echo stripslashes($nom->entry); ?></td>
									<td>
										<?php
										$user_info = get_userdata($nom->user_id);
										if ($user_info) {
											echo $user_info->first_name . ' ' . $user_info->last_name;
										?>
											(<?php echo $user_info->user_email; ?>)
										<?php } // If $user_info 
										?>
									</td>
								</tr>
							<?php $counter++;
							endforeach; // For Each $nom 
							?>
						</table>
					</div>
				<?php endif; // If $noms 
				?>

			</div>
		</div>
	<?php
	} // Index

	public function index_votes()
	{
		global $wpdb;
		wp_enqueue_style('bs', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css');
	?>
		<div class="container-fluid">
			<h1 class="my-3">Rolling Stone Australia Readers Award 2021 Votes</h1>
			<div class="row">
				<?php
				$artists = $wpdb->get_results("
				SELECT
					a.artist_name,
					COUNT(DISTINCT(v.user_id)) count_entries
				FROM `{$wpdb->prefix}rsawards_votes_final` v
					JOIN `{$wpdb->prefix}rsawards_artists` a
						ON a.id = v.artist_id
				GROUP BY v.artist_id
				ORDER BY count_entries DESC");
				if ($artists) :
				?>

					<div class="col-md-6">
						<h2>Top artists</h2>
						<table class="table table-sm">
							<tr>
								<th>#</th>
								<th>Artist</th>
								<th># of votes</th>
							</tr>
							<?php
							$counter = 1;
							foreach ($artists as $artist) :
							?>
								<tr>
									<td><?php echo $counter; ?></td>
									<td><?php echo $artist->artist_name; ?></td>
									<td><?php echo $artist->count_entries; ?></td>
								</tr>
							<?php
								$counter++;
							endforeach;
							?>
						</table>
					</div>
				<?php endif; // If $artists
				?>
			</div>
		</div>
<?php
	}
}

new TBM_RollingStoneAwards2020();
