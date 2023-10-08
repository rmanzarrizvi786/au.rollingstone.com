<?php

/**
 * Template Name: RS Awards Vote (2021)
 */
get_header();

add_action('wp_footer', function () {
?>
  <script src="https://kit.fontawesome.com/2d9b4b58e5.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap-grid.min.css">
  <style>
    #list-artists input[type=radio] {
      position: absolute;
      visibility: hidden;
    }

    #list-artists .artist-wrap label {
      display: block;
      position: relative;
      margin: 10px auto;
      z-index: 9;
      cursor: pointer;
      transition: all 0.25s linear;
      height: 100%;
    }

    #list-artists .artist-wrap .check,
    #list-artists .artist-wrap .checked {
      display: block;
      position: absolute;
      height: 25px;
      width: 25px;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 5;
      transition: .25s all linear;
    }

    #list-artists .artist-wrap .check {
      border: 5px solid #AAAAAA;
      border-radius: 100%;
      opacity: 1;
    }

    #list-artists .artist-wrap .checked {
      opacity: 0;
      transition: .25s all linear;
      font-size: 125%;
    }

    #list-artists .artist-wrap:hover .check,
    input[type=radio]:checked~label .check {
      opacity: 0 !important;
      border-color: #d32531;
    }

    #list-artists .artist-wrap:hover .checked,
    input[type=radio]:checked~label .checked {
      color: #d32531;

      opacity: 1 !important;
    }

    /* 
    #list-artists .artist-wrap .check::before {
      display: block;
      position: absolute;
      content: '';
      border-radius: 100%;
      height: 25px;
      width: 25px;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      margin: auto;
      transition: background 0.25s linear;
    }

    input[type=radio]:checked~label .check {
      border: 5px solid #d32531;
    }

    input[type=radio]:checked~label .check::before {
      background: #d32531;
    } */


    input[type=radio]:checked~label {
      color: #d32531;
    }

    #thank-you-popup {
      position: fixed;
      right: 0;
      bottom: 0;
      left: 0;
      /* background: rgba(255,255,255,.5); */
      z-index: 30000;
      display: none;
      transition: .25 all linear;
      color: #333;
      box-shadow: 0 0 10px #000;
      text-align: center;
      background: #fff;
    }

    #thank-you-popup.success {
      background: #28a745 !important;
      color: #fff !important;
      padding: 2rem 1rem;
    }

    #thank-you-popup.danger {
      background: #dc3545 !important;
      color: #fff !important;
      padding: 2rem 1rem;
    }


    /* position: absolute; top: 45%; bottom: 45%; left: 40%; right: 40%; z-index: 20001;  */
  </style>

  <script>
    const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    jQuery(document).ready(function($) {
      $('input[name="artist"]').on('change', function() {

        let artist_id = $(this).val();

        let formData = new FormData();
        formData.append('action', 'vote_readers_award_2020');
        formData.append('artist_id', artist_id);

        $.ajax({
          type: "POST",
          url: ajaxurl,
          data: formData,
          processData: false,
          contentType: false,
          dataType: "json",
          success: function(res, textStatus, jqXHR) {
            if (res.success) {
              $('#thank-you-popup').text(res.data).slideDown().removeClass().addClass('success');
              setTimeout(function() {
                $('#thank-you-popup').slideUp();
              }, 3000);
            } else {
              $('#thank-you-popup').text(res.data).slideDown().removeClass().addClass('danger');
            }
          }
        });


      });
      $('#thank-you-popup').on('click', function() {
        $(this).slideUp();
      })
    });
  </script>
<?php
}); // wp_footer action
?>
<?php
if (have_posts()) :
  while (have_posts()) :
    the_post();

    if (!post_password_required($post)) :
?>
      <div class="l-page__content">

        <div class="l-section l-section--no-separator" style="margin: 2rem auto;">
          <div class="c-content c-content--no-sidebar t-copy">
            <h1><?php the_title(); ?></h1>

            <?php if (0) : // Voting closed 
            ?>
              <div class="d-md-flex align-items-center">
                <div class="col-md-4" style="text-align: center;"><?php rollingstone_the_issue_cover(250); ?></div>
                <div>
                  <h3>Thanks for all your responses! Voting has now closed. See you at the awards!</h3>
                </div>
              </div>
              <?php
            else :
              if (is_user_logged_in()) :
                $current_user = wp_get_current_user();
                $my_vote_artist_id = $wpdb->get_var($wpdb->prepare("SELECT artist_id FROM {$wpdb->prefix}rsawards_votes_final WHERE user_id = %d", $current_user->ID));
              ?>

                <div id="thank-you-popup">
                  <div></div>
                </div>

                <?php
                $magSub = new TBMMagSub();
                $subscriptions = $magSub->get_subscriptions();
                if (1 || $subscriptions) : // If subscriptions
                ?>
                  <img src="https://images-r2.thebrag.com/rs/uploads/2020/11/SJRSAwardsNominateHero_1000pxwide.jpg" width="100%" />
                  <div class="d-flex flex-wrap">
                    <p>As a Rolling Stone subscriber, you are part of an elite club that pays for music journalism. This means music really matters to you, and it is for that reason you are a vital part of the 2021 Sailor Jerry Rolling Stone Australia Awards.</p>
                    <p>Because of your contribution to journalism, and as clear purveyors of music in all its forms, you have been entrusted with a responsibility to determine an award winner.</p>
                    <p>From today until November 13, Rolling Stone Australia subscribers can cast their vote for their favourite Australian artist by voting for the Rolling Stone Reader’s Award.</p>
                    <p>This award is judged solely by our readers, the subscribers who are the life blood of music journalism.</p>
                    <p>The list of nominees below has been laboured over by some of the most respected names in music, and now it’s your turn. You will decide the winner of the prestigious “Rolling Stone Readers Award”, and will be one of the most treasured trophies for artists on the night.
                  </div>
                  <div class="row" id="list-artists">
                    <?php
                    $artists = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rsawards_artists ORDER BY artist_name ASC");
                    if ($artists) :
                      foreach ($artists as $artist) :
                        $artist_img_url = !is_null($artist->image_url) ? $artist->image_url : 'https://placehold.it/800x800/333/fff/?text=' . urlencode($artist->artist_name);
                    ?>
                        <div class="col-md-3 col-6 artist-wrap" style="text-align: center; cursor: pointer; margin: 1rem auto 2rem;">
                          <input type="radio" id="artist_<?php echo $artist->id; ?>" name="artist" value="<?php echo $artist->id; ?>" <?php echo isset($my_vote_artist_id) && $my_vote_artist_id == $artist->id ? 'checked' : ''; ?>>
                          <label for="artist_<?php echo $artist->id; ?>">
                            <img src="<?php echo $artist_img_url; ?>" alt="">
                            <h3><?php echo $artist->artist_name; ?></h3>
                            <div class="check"></div>
                            <div class="checked"><i class="fas fa-medal"></i></div>
                          </label>
                        </div>
                    <?php endforeach;
                    endif; // If $artists 
                    ?>
                  </div>
                <?php else : // If no subscriptions
                ?>
                  <div class="d-md-flex align-items-center">
                    <div class="col-md-4" style="text-align: center;"><?php rollingstone_the_issue_cover(250); ?></div>
                    <div>
                      <h4 style="font-size: 150%; line-height: 1.7; text-align: center; margin: auto 1rem;">
                        To be eligible to vote for the Rolling Stone Readers Award, you need to have an active subscription
                        <br>
                        You currently don't have any <a href="https://au.rollingstone.com/" target="_blank" style="color: #d32531;">Rolling Stone Australia</a> Magazine Subscriptions, you can subscribe <a href="https://au.rollingstone.com/subscribe-magazine/" target="_blank" style="color: #d32531;">here</a>.
                      </h4>
                      <p style="text-align: center;">If you think this is an error, please <a href="mailto:subscribe@thebrag.media" target="_blank" style="color: #0BA4FF;">contact us</a></p>
                    </div>
                  </div>
                <?php
                endif; // If subscriptions
                ?>

              <?php else : // Not logged in 
              ?>

                <div class="d-flex flex-wrap">
                  <p>If you are a subscriber, please log in to cast your vote. If you are not a subscriber, please <a href="https://au.rollingstone.com/subscribe-magazine/" target="_blank" style="color: #d32531;">subscribe here</a> so that you can join an elite club of supporters and readers of long form music journalism.</p>
                </div>

                <div style="display: flex; justify-content: center; margin: 1rem auto 0;">
                  <a href="<?php echo home_url('/login/?redirectTo=') . home_url($wp->request); ?>" class="c-subscribe__button c-subscribe__button--subscribe t-bold t-bold--upper" style="font-size: 100%;">Click here to login &amp; vote</a>
                </div>

            <?php endif; // If logged in 
            endif; // Voting closed
            ?>

          </div><!-- /.c-content t-copy -->
        </div><!-- /.l-section -->

      <?php
    else :
      ?>
        <div class="l-page__content">
          <div class="l-section l-section--no-separator">
            <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">
              <div style="margin: 1rem auto;">
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

  <?php get_template_part('template-parts/footer/footer'); ?>
      </div><!-- .l-page__content -->
      <?php
      get_footer();
