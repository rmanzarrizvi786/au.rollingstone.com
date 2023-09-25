<?php

/**
 * Template Name: GMOAT 25 of 2021 - Vote
 */

header('Location:https://au.rollingstone.com/25-greatest-movies-of-2021/results/');
exit;

get_template_part('page-templates/gmoat-top-25-2021/header');

date_default_timezone_set('Australia/Sydney');
$voting_active = time() < strtotime('2022-01-26');
$img_url = RS_THEME_URL . '/images/vote-25-movies-2021/';
?>

<style>
  ol {
    counter-reset: item
  }

  ol li {
    display: block;
    font-weight: 700;
    margin-top: 1rem;
  }

  ol li span {
    font-weight: normal;
  }

  ol li:before {
    content: counters(item, ".") ". ";
    counter-increment: item
  }

  ol li ol li,
  ol li ul li {
    font-weight: normal;
    margin-top: .5rem;
  }

  ol li ol li:before {
    content: counters(item, ".") " ";
    margin-left: -1.5rem;
    counter-increment: item
  }

  ol li ol li ol li:before {
    margin-left: -2.2rem;
  }
</style>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" defer></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?php
wp_enqueue_script('vote-gmoat', get_template_directory_uri() . '/page-templates/gmoat-top-25-2021/js/scripts.js', ['jquery'], time(), true);
?>

<link rel="stylesheet" id="jq-ui-css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" type="text/css" media="all" />
<?php
add_action('wp_footer', function () {
?>
  <script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    jQuery(document).ready(function($) {});
  </script>

  <noscript>
    <style>
      .js-slideshow {
        display: flex !important;
      }

      #gmoat-form .tab {
        display: block !important;
      }
    </style>
  </noscript>

  <?php
}); // wp_footer

if (have_posts()) :
  while (have_posts()) :
    the_post();

    if (!post_password_required($post)) :
  ?>
      <div class="l-page__content">
        <div class="logo-wrap">
          <div class="l-section l-section--no-separator">
            <figure class="c-picture2" style="margin: auto;">
              <div class="c-picture__frame2">

                <div class="c-crop2 c-crop--ratio-3x2-2 logo">
                  <img src="<?php echo $img_url . 'AHEDATitle.png'; ?>" style="width: 460px; max-width: 100%;">
                  <div style="font-family: Graphik,sans-serif; margin-top: 2rem; font-size: 175%;">
                    VOTE FOR YOUR FAVOURITE MOVIES OF 2021 NOW!
                  </div>
                </div><!-- .c-crop -->
              </div>

            </figure><!-- .c-picture -->
          </div>
        </div>

        <div class="slideshow-wrap" id="slideshow-wrap1">
          <div class="slideshow js-slideshow" id="js-slideshow1" style="display: none;">
            <?php for ($i = 0; $i < 4; $i++) : ?>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/Shang-Chi.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Raya_And_The_Last_Dragon.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Eternals.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Jungle_Cruise.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Cruella.jpg" /></div>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/AQuietPlacePart2.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/PawPatrol_TheMovie.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Infinite.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/SnakeEyes_GIJoeOrigins.jpg" /></div>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/TheDry.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Promising_Young_Woman.jpg" /></div>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/DUNE.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/slick.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/The_father.jpg" /></div>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/Black_Widow.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Free_Guy.jpg" /></div>
            <?php endfor; ?>
          </div>
        </div>

        <div class="l-section l-section--no-separator" style="margin: 1rem auto 2rem auto; max-width: 50rem;">
          <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">

            <div class="gmoat-wrap">

              <h1 style="line-height: 2.5rem;"><?php the_title(); ?></h1>

              <p><em>Rolling Stone Australia</em> is searching for Australia's Favourite Movies of 2021 and we need your help! Whether it’s a critically-acclaimed gem or an overlooked modern classic, anything released last year is eligible for nomination.</p>
              <p>Powered by <a href="https://www.aheda.com.au/" target="_blank">AHEDA</a>, voting runs until Jan 25, with readers able to vote for their top three movies of the year. Those who vote can also opt-in for the chance to win a brand new 55” Sony Bravia XR OLED TV valued at $2,795</p>
              <p>The countdown of the results begins on Jan 27, so cast your mind back to those seminal movie moments you experienced last year, pick out some of your favourite 2021 films and vote below.</p>
              <?php if ($voting_active) : ?>
                <p><strong>To enter for the chance to win a 55” Sony Bravia XR OLED TV valued at $2,795 fill out your votes below, opt-in, and tell us in 25 words or less ‘What was your favourite film of 2021 and why?’</strong></p>

                <div class="tab">
                  <form action="#" method="post" id="gmoat-vote-form-2021">
                    <div style="text-align: center;">
                      <h2 style="margin-top: 1rem;">Vote Here</h2>
                    </div>
                    <?php
                    $movies = $wpdb->get_results("SELECT `id`, `title` FROM {$wpdb->prefix}gmoat_movies_2021 ORDER BY `title` ASC ");
                    for ($i = 1; $i <= 3; $i++) : ?>
                      <div class="mt-1"><label>Movie <?php echo $i; ?>
                          <div class="select-movie-wrap">
                            <input type="text" class="select-movies" id="select-movies<?php echo $i; ?>" name="select-movies[]">
                            <div class="edit-select-movie hidden" style="position:absolute; left:0; right:0; height: 100%; bottom:0;"></div>
                            <div class="edit-select-movie hidden"><i class="fas fa-pencil-alt"></i></div>
                            <input type="hidden" class="select-movies-id" id="select-movies-id<?php echo $i; ?>" name="select-movies-id[]">
                          </div>
                          <!-- <div class="select-movie-wrap">
                            <select name="select-movies-id[]" id="select-movies<?php echo $i; ?>" class="select-movies">
                              <option value=""> - </option>
                              <?php
                              // foreach ($movies as $movie) {
                              ?>
                                <option value="<?php // echo $movie->id; 
                                                ?>"><?php // echo $movie->title; 
                                                    ?></option>
                              <?php
                              // }
                              ?>
                            </select>
                          </div> -->
                        </label></div>
                    <?php endfor; ?>

                    <div class="d-flex flex-sm-column">
                      <div class="mt-1 pr-1 flex-fill">
                        <label>
                          Your name
                          <input type="text" name="user_fullname" required2>
                        </label>
                      </div>

                      <div class="mt-1 pl-1 flex-fill">
                        <label>
                          Your email
                          <input type="email" name="user_email" required2>
                        </label>
                      </div>
                    </div>

                    <div class="mt-1">
                      <div class="text-danger js-errors" style="display: none;"></div>
                    </div>

                    <div style="margin-top: 1rem; text-align :right;">
                      <button type="submit" class="btn-submit" id="btn-submit-vote" style="background: #000;">
                        <div class="spinner hidden"></div>
                        <span class="button-text d-flex" style="align-items: center;"><span style="margin-right: 0.5rem;">Vote</span> <i class="fa fa-chevron-right"></i></span>
                      </button>
                    </div>
                  </form>
                </div>

                <div class="tab" id="gmoat-comp-form-wrap" style="display: none;">
                  <!-- <div class="text-success js-success" style="display: none; text-align: center; font-size: 125%;"></div> -->
                  <form action="#" method="post" id="gmoat-comp-form">
                    <div style="text-align: center;" id="enter-comp-heading">
                      <h2 style="margin-top: 1rem;">Enter Competition Here</h2>
                    </div>
                    <div class="text-success" id="js-success" style="display: none; text-align: center; font-size: 125%;"></div>
                    <div class="fields-wrap">
                      <input type="hidden" name="entry_id" id="entry_id" value="">
                      <div class="mt-1">
                        <label>
                          Tell us in 25 words or less ‘What was your favourite film of 2021 and why?’
                          <textarea name="reason" id="reason" rows="2"></textarea>
                          <div style="display: none;"># of words <span id="reason_wordcount">0</span></div>
                        </label>
                      </div>

                      <div>
                        <div class="mt-1">
                          <label>
                            <input type="checkbox" name="consent_observer" value="yes" checked>
                            I agree to be signed up to The <a href="https://thebrag.com/observer/film-tv/" target="_blank">Film &amp; TV Observer</a> as part of the requirements for entry
                          </label>
                        </div>
                        <div class="mt-1">
                          <label>
                            <input type="checkbox" name="consent_aheda" value="yes" checked>
                            I agree to be signed up to AHEDA's mailing list as part of the requirements for entry
                          </label>
                        </div>
                      </div>

                      <div class="mt-1">
                        <div class="text-danger js-errors" style="display: none;"></div>
                      </div>

                      <div style="margin-top: 1rem; text-align :right;">
                        <button type="submit" class="btn-submit" id="btn-submit-comp">
                          <div class="spinner hidden"></div>
                          <span class="button-text">Submit</span>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>

                <div id="voted_movies_wrap" class="hidden">
                  <div class="my-votes-container">
                    <table id="voted_movies"></table>
                  </div>
                  <div class="social-icons" style="margin-top: .5rem;">
                    <div style="margin-right: 1rem;">
                      <a class="btn-share btn-social-icon facebook-button" id="share_facebook" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode(get_the_permalink() . '&utm_source=share_facebook'); ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    </div>
                    <div>
                      <a class="btn-share btn-social-icon twitter-button" id="share_twitter" href="https://twitter.com/intent/tweet?text=&url=<?php echo urlencode(get_the_permalink() . '&utm_source=share_twitter'); ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                    </div>
                  </div>
                </div>

              <?php else : ?>
                <div style="width: 550px; max-width: 100%; text-align: center; margin: 1rem auto; font-size: 125%; background: #f3f3f3; border-radius: 20px; padding: 1rem .5rem; box-shadow: 0 0 10px #aaa;">
                  <p>Voting has now closed for Australia's 25 Favourite Movies of 2021 readers poll.</p>
                  <p>We'll start counting down the results on this page starting January 27, stay tuned!</p>
                </div>

              <?php endif; // If $voting_active 
              ?>
              <div>
                <a href="https://www.aheda.com.au/" target="_blank" style="text-align: center; margin: 2rem auto; font-weight: 600; box-shadow: 0 0 18px #aaa; border-radius: 1rem; padding: .5rem 1rem; background: #000; color: #fff; display: block; font-size: 1.25rem; line-height: 2rem; font-family: Graphik,sans-serif;">
                  <p>Australia's Favourite Movies of 2021 is powered by the Australian Home Entertainment Distributors Association. Learn more <span style="color: #fff; text-decoration: underline;">here</span></p>
                  <p>Rent or Buy Your Favourite Movies from 2021 on Digital, 4K Ultra HD, Blu-ray or DVD today!</p>
                </a>
              </div>

            </div><!-- .gmoat-wrap -->
          </div><!-- /.c-content t-copy -->
        </div><!-- /.l-section -->

        <div class="slideshow-wrap" id="slideshow-wrap2" style="margin-top: 2rem;">
          <div class="slideshow js-slideshow" id="js-slideshow2" style="display: none;">
            <?php for ($i = 0; $i < 4; $i++) : ?>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/PETERRABBIT2THERUNAWAY.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Venom_LetThereBeCarnage.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/SpiderMan_NoWayHome.jpg" /></div>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/F9.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Nobody.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/NoTimeToDIe.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Last_Night_In_Soho.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Sing_2.jpg" /></div>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/CONJURING.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Godzilla_vs_Kong.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/The_Suicide_Squad.jpg" /></div>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/WRATHOFMAN.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/MortalKombat.jpg" /></div>
            <?php endfor; ?>
          </div>
        </div>

        <?php if ($voting_active) : ?>
          <div class="l-page__content" style="margin-top: 1rem;">
            <div class="l-section l-section--no-separator" style="margin: 1rem auto 2rem auto; max-width: 50rem;">
              <div class="c-content c-content--no-sidebar t-copy">
                <div class="t-c-wrap">
                  <h4>Terms &amp; Conditions</h4>

                  <table>
                    <tbody>
                      <tr>
                        <td>Competition</td>
                        <td>AHEDA x The Brag competition</td>
                      </tr>
                      <tr>
                        <td>Entry Period</td>
                        <td>Starts at 00:01 hours AEST / AEDT on 17th January 2022 and ends at [23:59 hours AEST / AEDT] on 25th January 2022</td>
                      </tr>
                      <tr>
                        <td>Entry</td>
                        <td>
                          (a) Fill in all the required data fields on the entry form for the Competition; and
                          <br>
                          (b) Vote for your top 3 movies of 2021 and answer the following question in 25 words or less: "What was your favourite film of 2021 and why?"
                          <br>
                          (c) You need to agree to be signed up to The Film & TV Observer as part of the requirements for entry
                          <br>
                          (d) You need to agree to be signed up to AHEDA's mailing list as part of the requirements for entry
                        </td>
                      </tr>
                      <tr>
                        <td>Limit</td>
                        <td>Limited to one entry per person</td>
                      </tr>
                      <tr>
                        <td>Judging Criteria</td>
                        <td>Originality and creativity</td>
                      </tr>
                      <tr>
                        <td>Prize Determination</td>
                        <td>10:00 hours AEST / AEDT on 1st February 2022 at The Brag Offices.</td>
                      </tr>
                      <tr>
                        <td>Notification</td>
                        <td>By email by 8th February 2022</td>
                      </tr>
                      <tr>
                        <td>Claim Period</td>
                        <td>Within 4 weeks from the date of Notification</td>
                      </tr>
                      <tr>
                        <td>Unclaimed Prize Determination</td>
                        <td>10:00 hours AEST / AEDT on 8th March at The Brag Offices
                          <br>
                          The winner will be notified by telephone and in writing via email or SMS (depending on the method of entry) within 5 working days of the Prize Determination.
                        </td>
                      </tr>
                      <tr>
                        <td>Prize</td>
                        <td>
                          (a) 55” Sony Bravia XR OLED TV valued at $2,795
                        </td>
                      </tr>
                      <tr>
                        <td>Total Prize Value</td>
                        <td>$2,795</td>
                      </tr>
                      <tr>
                        <td>Prize Conditions</td>
                        <td>N/A</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        <?php endif; // If $voting_active 
        ?>
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
