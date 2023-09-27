<?php

/**
 * Template Name: RS Awards Readers Vote (2023)
 */

define('ICONS_URL', get_template_directory_uri() . '/images/');

$action = isset($_GET['a']) && in_array(trim($_GET['a']), ['add', 'edit', 'ty']) ? trim($_GET['a']) : NULL;
$success = isset($_GET['success']) ? trim($_GET['success']) : NULL;

date_default_timezone_set('Australia/NSW');
$noms_open_at = '2023-03-02 12:00:00';
$noms_open = false; // isset($_GET['no']) && '7Odori' == $_GET['no'] ? true : time() >= strtotime($noms_open_at);
$noms_closed = true;

get_header('rsawards-custom-menu-2023');
?>

<style>
  #list-artists input[type=radio] {
    position: absolute;
    visibility: hidden;
  }

  .artist-wrap {
    text-align: center;
    cursor: pointer;
    margin: 1rem auto 2rem;
    padding-left: 1rem;
    padding-right: 1rem;
  }

  .artist-wrap.fade {
    opacity: .65;
  }

  .artist-wrap:hover {
    opacity: 1 !important;
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
    bottom: -2rem;
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

  #list-artists .artist-wrap:hover img.artist-img {
    border: 0.15rem solid #d32531;
    transform: scale(.95);
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

  input[type=radio]~label img.artist-img {
    border-radius: .5rem;
    border: .15rem solid transparent;
    transition: .25s all linear;
  }

  input[type=radio]:checked~label img.artist-img {
    border: 0.15rem solid #d32531;
    transform: scale(.95);
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

  p {
    margin-bottom: 1rem;
  }
</style>

<?php
if (have_posts()) :
  while (have_posts()) :
    the_post();

    if (!post_password_required($post)) :
?>
      <div id="content-wrap">

        <section class="bg-white p-3 d-flex" style="background-color: #fff; padding: 1.5rem;">
          <div class="col-12">

            <?php if ($noms_closed === true && !isset($_GET['letmeaccess'])) : // Voting closed 
            ?>
              <div class="d-md-flex align-items-center justify-content-center py-4" style="align-items: center; height: 400px;">
                  <h2 style="line-height: 2;">Thanks for all your responses! Voting has now closed. See you at the awards!</h2>
                </div>
              </div>
              <?php
            else :
              if (is_user_logged_in()) :
                $current_user = wp_get_current_user();
                $my_vote_artist_id = $wpdb->get_var($wpdb->prepare("SELECT artist_id FROM {$wpdb->prefix}rsawards_votes_final_2023 WHERE user_id = %d", $current_user->ID));
              ?>

                <div id="thank-you-popup">
                  <div></div>
                </div>

                <?php
                $magSub = new TBMMagSub();
                $subscriptions = $magSub->get_subscriptions();
                if (current_user_can('administrator')  || $subscriptions || $current_user->ID == 1338 || $current_user->ID == 10) : // If subscriptions
                ?>
                  <div>
                  <p style="font-style: italic; text-align: center; color: #000;">Nominate now and go in the running to win exclusive tickets to the Rolling Stone Awards for you and 3 friends!</p>
                    <p>As a Rolling Stone subscriber, you are part of an elite club that pays for music journalism. This means music really matters to you, and it is for that reason you are a vital part of the 2023 Shure Rolling Stone Australia Awards.</p>
                    <p>Because of your contribution to journalism, and as clear purveyors of music in all its forms, you have been entrusted with a responsibility to determine an award winner.</p>
                    <p>From today until March 12, Rolling Stone Australia subscribers can cast their vote for their favourite Australian artist by voting for the Rolling Stone Reader’s Award.</p>
                    <p>This award is judged solely by our readers, the subscribers who are the life blood of music journalism.</p>
                    <p>The list of nominees below has been laboured over by some of the most respected names in music, and now it’s your turn. You will decide the winner of the prestigious “Rolling Stone Readers Award”, and will be one of the most treasured trophies for artists on the night.
                  </div>
                  <div class="row flex-wrap" id="list-artists">
                    <?php
                    $artists = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rsawards_artists_2023 ORDER BY artist_name ASC");
                    if ($artists) :
                      foreach ($artists as $artist) :
                        $artist_img_url = !is_null($artist->image_url) ? $artist->image_url : 'https://placehold.it/800x800/333/fff/?text=' . urlencode($artist->artist_name);
                    ?>
                        <div class="col-md-3 col-6 artist-wrap <?php echo isset($my_vote_artist_id) && $my_vote_artist_id != $artist->id ? 'fade' : ''; ?>" id="artist-wrap-<?php echo $artist->id; ?>">
                          <input type="radio" id="artist_<?php echo $artist->id; ?>" name="artist" value="<?php echo $artist->id; ?>" <?php echo isset($my_vote_artist_id) && $my_vote_artist_id == $artist->id ? 'checked' : ''; ?>>
                          <label for="artist_<?php echo $artist->id; ?>">
                            <img src="<?php echo $artist_img_url; ?>" alt="<?php echo $artist->artist_name; ?>" class="artist-img">
                            <h3 style="margin-bottom: 1rem;"><?php echo $artist->artist_name; ?></h3>
                            <div class="check"></div>
                            <div class="checked">
                              <img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/medal.svg">
                            </div>
                          </label>
                        </div>
                    <?php endforeach;
                    endif; // If $artists 
                    ?>
                  </div>
                  <hr class="my-4" />
									<div class="t-c-wrap">
                    <h3 class="text-center py-4">Rolling Stone Awards Competition Terms &amp; Conditions</h3>
										<table style="font-family: 'Roboto', sans-serif; border: 1px solid: #000;">
											<tbody>
												<tr>
													<td>Competition</td>
													<td>Rolling Stone Awards 2023 Readers’ Choice</td>
												</tr>
												<tr>
													<td>Entry Period</td>
													<td>Starts at 12:00 hours AEDT on 6 February 2023 and ends at 23:59 hours AEDT on 12 March 2023</span></td>
												</tr>
												<tr>
													<td>Entry</td>
													<td>
														(a) Go to https://au.rollingstone.com/vote-for-rolling-stone-readers-award-2023/ (“Website”); <br />
														(b) Fill in all the required data fields on the entry form for the Competition; and<br />
														(c) Nomination for your choice
													</td>
												</tr>
												<tr>
													<td>Limit</td>
													<td>1 entry per subscriber</td>
												</tr>
												<tr>
													<td>Judging Criteria</td>
													<td>Winner picked at random.</td>
												</tr>
												<tr>
													<td>Prize Determination</td>
													<td>12:00 hours AEDT on 13th March 2023 at The Brag Media.</td>
												</tr>
												<tr>
													<td>Notification</td>
													<td>By email by 13th March 2023</td>
												</tr>
												<tr>
													<td>Claim Period</td>
													<td>Within 1 weeks from the date of Notification </td>
												</tr>
												<tr>
													<td>Unclaimed Prize Determination</td>
													<td>14:00 hours AEST on 20th March 2023 at The Brag Media Offices<br />
														The winner will be notified in writing via email within 7 days of the Prize Determination.
													</td>
												</tr>
												<tr>
													<td>Prize</td>
													<td>
														4 tickets to attend Rolling Stone Awards event in Sydney on 4th April 2023
													</td>
												</tr>
												<tr>
													<td>Total Prize Value</td>
													<td>$2000</td>
												</tr>
												<tr>
													<td>Prize Conditions</td>
													<td>
														- Winner must arrange their own transport to and from the Rolling Stone Awards event.<br />
														- WInner must be at least 18 years of age.
													</td>
												</tr>
											</tbody>
										</table>
									</div>
                <?php else : // If no subscriptions
                ?>
                  <div class="d-md-flex align-items-center py-4">
                    <div class="col-md-4" style="text-align: center;"><?php rollingstone_the_issue_cover(250); ?></div>
                    <div>
                      <h4 style="font-size: 130%; line-height: 1.7; text-align: center; margin: auto 1rem;">
                        To be eligible to vote for the Rolling Stone Readers' Choice Award, you need to have an active magazine subscription.
                        <br><br>
                        You currently don't have any active magazine subscriptions, but you can subscribe <a href="https://au.rollingstone.com/subscribe-magazine/" target="_blank" style="color: #d32531;">here</a>.<br /><br />
                        If you think this is an error, please <a href="mailto:subscribe@thebrag.media" target="_blank" style="color: #0BA4FF;">contact us</a>
                      </h4>
                    </div>
                  </div>
                <?php
                endif; // If subscriptions
                ?>

              <?php else : // Not logged in 
              ?>

                <div class="d-md-flex align-items-center">
                  <div class="col-md-4" style="text-align: center;"><?php rollingstone_the_issue_cover(250); ?></div>
                  <div>
                    <div class="d-flex flex-wrap">
                      <p>If you are a subscriber, please log in to cast your vote. If you are not a subscriber, please <a href="https://au.rollingstone.com/subscribe-magazine/" target="_blank" style="color: #d32531;">subscribe here</a> so that you can join an elite club of supporters and readers of long form music journalism.</p>
                    </div>

                    <div style="display: flex; justify-content: center; margin: 1rem auto 2rem;">
                      <a href="<?php echo home_url('/wp-login.php?redirect_to=') . home_url($wp->request); ?>" class="c-subscribe__button c-subscribe__button--subscribe t-bold t-bold--upper" style="font-size: 100%; border-radius: .25rem;">Click here to login &amp; vote</a>
                    </div>
                  </div>
                </div>

            <?php endif; // If logged in 
            endif; // Voting closed
            ?>

          </div>
        </section>
      </div><!-- #content-wrap -->
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
    endif; // Password protected
  endwhile;
  wp_reset_query();
endif;
?>

<?php add_action('wp_footer', function () {
  global $noms_open, $noms_open_at;
?>
  <script>
    const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    jQuery(document).ready(function($) {

      $('input[name="artist"]').on('change', function() {

        let artist_id = $(this).val();

        $('.artist-wrap').addClass('fade');
        $('.artist-wrap#artist-wrap-' + artist_id).removeClass('fade');

        $('#thank-you-popup').text('Saving, please wait...').slideDown().removeClass().addClass('success');

        let formData = new FormData();
        formData.append('action', 'vote_readers_award_2023');
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
              $('#thank-you-popup').text('Something went wrong, please try again.').slideDown().removeClass().addClass('danger');
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
}); // wp_footer

if (count($errors) > 0) :
  add_action('wp_footer', function () {
  ?>
    <script>
      jQuery(document).ready(function($) {
        // setTimeout(function() {
        jQuery([document.documentElement, document.body]).animate({
          scrollTop: $('#errors').offset().top - 160
        }, 1000);
        // }, 1000);
      });
    </script>
  <?php
  }, 100);
endif; // If there are $errors

if ('ty' == $action && 1 == $success) :
  add_action('wp_footer', function () {
  ?>
    <div id="msg-ty" class="bg-success text-white text-center py-3 px-2" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 3; font-size: 150%; background-color: #28a745 !important;">
      Your nominations have been submitted.
    </div>
    <script>
      jQuery(document).ready(function($) {
        // setTimeout(function() {
        jQuery([document.documentElement, document.body]).animate({
          scrollTop: $('#my-noms').offset().top - 160
        }, 1000);
        // }, 1500);
        setTimeout(function() {
          jQuery('#msg-ty').fadeOut();
        }, 7000);
      });
    </script>
  <?php
  });
endif; // If 'ty' == $action

if ('add' == $action) :
  add_action('wp_footer', function () {
  ?>
    <script>
      jQuery(document).ready(function($) {
        // setTimeout(function() {
        jQuery([document.documentElement, document.body]).animate({
          scrollTop: jQuery('#nomination-entries').offset().top - 160
        }, 1000);
        // }, 1500);
      });
    </script>
  <?php
  });
endif; // If 'add' == $action

if ('edit' == $action) :
  add_action('wp_footer', function () {
  ?>
    <script>
      jQuery(document).ready(function($) {
        // setTimeout(function() {
        jQuery([document.documentElement, document.body]).animate({
          scrollTop: jQuery('#nomination-entries-edit').offset().top - 160
        }, 1000);
        // }, 1500);
      });
    </script>
<?php
  });
endif; // If 'edit' == $action
?>
<?php get_template_part('template-parts/footer/footer'); ?>
</div><!-- .l-page__content -->
<?php
get_footer();
