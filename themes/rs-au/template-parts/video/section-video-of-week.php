<?php

/**
 * TBM Section Video of week Template.
 *
 * @package pmc-rollingstone-2018
 * @since 2018-04-25
 */

/*
 * Featured Video
 */
$votw_response = wp_remote_get('https://thebrag.com/wp-json/tbm/votw');
if (is_array($votw_response) && !is_wp_error($votw_response)) {
  $votw = json_decode($votw_response['body']);
?>

  <a style="position: relative; width:100%; cursor:pointer; overflow:hidden; display: block;" class="mb-3 home-featured-content" href="<?php echo $votw->link; ?>" target="_blank" rel="noreferrer">

    <div id="featured-video-player" class="youtube-player" style="height: 140px">
      <img src="<?php echo $votw->image; ?>" style="position: absolute; width: 100%; z-index: 1;top:50%;left:50%;transform:translate(-50%, -50%)" class="video-thumb" alt="<?php esc_html($votw->artist) . ' - ' . esc_html($votw->song); ?>" title="<?php esc_html($votw->artist) . ' - ' . esc_html($votw->song); ?>">
      <img class="play-button-red" src="<?php echo get_template_directory_uri(); ?>/assets/src/images/_dev/play-button-60px.png" style="width: 40px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 2;transition: .25s all linear;" alt="Play" title="Play">
    </div>
    <div class="featured-video-meta d-flex justify-content-between align-items-center">
      <h3 style="font-size: 1rem;line-height: 1.25;">
        <?php echo esc_html(stripslashes($votw->artist)); ?>
        - '<?php echo esc_html(stripslashes($votw->song)); ?>'
      </h3>
    </div>

  </a>
  <?php
} else {
  $featured_yt_vid_id = NULL;
  $featured_video = get_option('tbm_featured_video');
  $tbm_featured_video_link = get_option('tbm_featured_video_link');

  if (!is_null($featured_video) && $featured_video != '') :
    parse_str(parse_url($featured_video, PHP_URL_QUERY), $featured_video_vars);
    $featured_yt_vid_id = $featured_video_vars['v'];
    $featured_video_img = 'https://i.ytimg.com/vi/' . $featured_yt_vid_id . '/0.jpg';
    if ($tbm_featured_video_link) :
      $tbm_featured_video_link_html = file_get_contents($tbm_featured_video_link);
      $tbm_featured_video_link_html_dom = new DOMDocument();
      @$tbm_featured_video_link_html_dom->loadHTML($tbm_featured_video_link_html);
      // $meta_og_img_tbm_featured_video_link = null;
      foreach ($tbm_featured_video_link_html_dom->getElementsByTagName('meta') as $meta) {
        if ($meta->getAttribute('property') == 'og:image') {
          $featured_video_img = $meta->getAttribute('content');
          break;
        }
      }
  ?>
      <a style="position: relative; width:100%; cursor:pointer; overflow:hidden; display: block;" class="mb-3 home-featured-content" href="<?php echo $tbm_featured_video_link; ?>" target="_blank">
      <?php else : // $tbm_featured_video_link is not set, so display video 
      ?>
        <div style="position: relative; width:100%; cursor:pointer; overflow:hidden;" title="Click to play video" class="mb-3 yt-lazy-load home-featured-content" data-id="<?php echo $featured_yt_vid_id; ?>" id="<?php echo $featured_yt_vid_id; ?>">
        <?php endif; // If $tbm_featured_video_link 
        ?>
        <div id="featured-video-player" class="youtube-player" data-id="<?php echo $featured_yt_vid_id; ?>" style="height: 140px">
          <img src="<?php echo $featured_video_img; ?>" style="position: absolute; width: 100%; z-index: 1;top:50%;left:50%;transform:translate(-50%, -50%)" class="video-thumb" alt="<?php esc_html(get_option('tb_featured_video_artist_title')) . ' - ' . esc_html(get_option('tb_featured_video_song_title')); ?>" title="<?php esc_html(get_option('tb_featured_video_artist_title')) . ' - ' . esc_html(get_option('tb_featured_video_song_title')); ?>">
          <img class="play-button-red" src="<?php echo get_template_directory_uri(); ?>/assets/src/images/_dev/play-button-60px.png" style="width: 40px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 2;transition: .25s all linear;" alt="Play" title="Play">
        </div>
      <?php endif; // If Featured Video is set 
      ?>

      <div class="featured-video-meta d-flex justify-content-between align-items-center">
        <h3 style="font-size: 1rem;line-height: 1.25;">
          <?php
          //add artist title and songs title
          if (get_option('tbm_featured_video_artist')) {
            echo '' . esc_html(stripslashes(get_option('tbm_featured_video_artist')));
          }
          if (get_option('tbm_featured_video_song')) {
            echo ' - \'' . esc_html(stripslashes(get_option('tbm_featured_video_song'))) . '\'';
          }
          ?>
        </h3>
      </div>
      <?php if ($tbm_featured_video_link) : ?>
      </a>
    <?php else : // $tbm_featured_video_link is not set, so display video 
    ?>
      </div>
  <?php endif; // If $tbm_featured_video_link 
    } // If not fetched via API