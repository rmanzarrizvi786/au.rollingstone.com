<?php

/**
 * Template Name: Magnum After Dark (2021)
 */
// get_header();

get_template_part('page-templates/magnum-2021/header');

wp_enqueue_script('magnum-timer-2021', RS_THEME_URL . '/page-templates/magnum-2021/js/scripts.js', ['jquery'], time(), true);

add_action('wp_footer', function () {
?>
  <style>

  </style>
  <script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    jQuery(document).ready(function($) {});
  </script>
<?php
}); // wp_footer

add_action('wp_footer', function () {
?>
  <style>

  </style>
  <?php
});

if (have_posts()) :
  while (have_posts()) :
    the_post();

    if (!post_password_required($post)) :
  ?>
      <div class="l-page__content c-content bg-dark">

        <div class="intro l-section2 l-section--no-separator">
          <div class="container-intro">
            <div class="container-intro-inner">
              <?php if (0) : ?>
                <figure class="c-picture2" style="margin: auto;">
                  <div class="c-picture__frame2">

                    <div class="c-crop2 c-crop--ratio-3x2-2 logo">

                    </div><!-- .c-crop -->
                  </div>

                </figure><!-- .c-picture -->
              <?php endif; ?>

              <div id="fb-root"></div>
              <script async defer src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script>
              <div class="videos-wrap">
                <h1 style="text-align: center; color: rgb(150, 130, 70); padding: 2rem 1rem; font-size: 2rem; margin: 0 auto !important;" id="story-title"><?php the_title(); ?></h1>
                <div class="videos" style="height: 100%;">
                  <?php
                  $video_ids = [
                    '189089739801041',
                    '203812618262632',
                    '487610935687148',
                    '398507848265886',
                    '903994113491342',
                    '249439256658160',
                  ];
                  foreach ($video_ids as $i => $video_id) : ?>
                    <div class="video-wrap" id="video-wrap-<?php echo $i; ?>">
                      <div class="fb-video" id="vid-<?php echo $i; ?>" data-href="https://www.facebook.com/FacebookDevelopers/posts/<?php echo $video_id; ?>" data-allowfullscreen="true"></div>
                    </div>
                  <?php endforeach; ?>
                  <!-- <div class="video-wrap">
                    <iframe id="vid-1" src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Frollingstoneaustralia%2Fvideos%2F189089739801041%2F&show_text=false&width=560&t=0" width="560" height="314" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>
                  </div>
                  <div class="video-wrap">
                    <iframe id="vid-2" src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Frollingstoneaustralia%2Fvideos%2F203812618262632%2F&show_text=false&width=560&t=0" width="560" height="314" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>
                  </div>
                  <div class="video-wrap">
                    <iframe id="vid-3" src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Frollingstoneaustralia%2Fvideos%2F487610935687148%2F&show_text=false&width=560&t=0" width="560" height="314" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>
                  </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
    else :
    ?>
      <div class="l-page__content">
        <div class="l-section l-section--no-separator">
          <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">
            <div style="margin: 3rem auto; width: 80%; text-align: center;">
              <?php echo get_the_password_form(); ?>
            </div>
          </div>
        </div>
      </div>
<?php
    endif; // Pasword protected
  endwhile;
endif;
?>

<?php // get_template_part('template-parts/footer/footer'); 
?>
</div><!-- .l-page__content -->

<script>
  jQuery(document).ready(function($) {
    // setVideoWrapHeight();

    $(window).on('resize', function() {
      // setVideoWrapHeight();
    })

    function setVideoWrapHeight() {
      var newHeight = $(window).height() - $('#story-title').outerHeight(); // - $('.l-page__header').outerHeight()
      if (newHeight < 500)
        newHeight = 500;
      $(".videos-wrap .videos").css('height', newHeight * 2);
    }

    $('.video-wrap iframe').on('click', function() {
      $('.video-wrap').removeClass('active');
      $(this).closest('video-wrap').addClass('active');
    });
  });

  window.fbAsyncInit = function() {
    FB.init({
      // appId: '',
      xfbml: true,
      version: 'v3.2'
    });
    video_players = [];
    play_count = 0;
    video_ids = [];
    var videoWrapHeight = 0;
    FB.Event.subscribe('xfbml.ready', function(msg) {
      if (msg.type === 'video') {
        if (video_ids.indexOf(msg.id) < 0) {
          video_players.push(msg.instance);
          video_ids.push(msg.id);

          videoWrapHeight += jQuery('#' + msg.id).height();

          // console.log(jQuery('#' + msg.id).height());

          jQuery(".videos-wrap .videos").css('height', videoWrapHeight);

          // jQuery('.video-wrap').each(function(i, e) {
          //   console.log(jQuery(e).height());
          // });
        }
        if (screen.width > 768) {
          msg.instance.subscribe('startedPlaying', function(e) {
            play_count++;
            for (i = 0; i < video_players.length; i++) {
              var video_id = msg.id;
              if (msg.instance != video_players[i]) {
                video_players[i].pause();
              } else {
                jQuery('#' + video_id).parent().css('z-index', play_count);
              }
            }
          });
        }
      }
    });
  };
</script>

<?php
get_footer();
