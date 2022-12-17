<?php

/**
 * Template Name: SCU Scholarship (2023)
 */

get_template_part('page-templates/scu-scholarship-2020/header');

$submissions_active = time() < strtotime('2023-01-01');
// $submissions_active = false;

wp_enqueue_script('scu-scholarship-2020', get_template_directory_uri() . '/page-templates/scu-scholarship-2020/js/scripts-2023.js', ['jquery'], time(), true);
?>
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

      #scu-scholarship-form .tab {
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
        <div class="slideshow-wrap" id="slideshow-wrap1">
          <div class="slideshow js-slideshow" id="js-slideshow1" style="display: none;">
            <?php for ($i = 0; $i < 5; $i++) : ?>
              <div class="slide">
                <img src="<?php echo RS_THEME_URL ?>/images/scu-scholarship-2021/scroll-1.jpg" style="width: auto !important;
      height: 100% !important;
      max-width: none !important;">
              </div>
            <?php endfor; ?>
          </div>
        </div>

        <div class="logo-wrap">
          <div class="l-section l-section--no-separator">
            <figure class="c-picture2" style="margin: auto;">
              <div class="c-picture__frame2">

                <div class="c-crop2 c-crop--ratio-3x2-2 logo">
                  <div style="font-family: Graphik,sans-serif; margin-bottom: 2rem; font-size: 175%; line-height: 1.3;">
                    Do you dream of a career in Music? We are here to help
                  </div>
                  <img src="<?php echo RS_THEME_URL . '/images/scu-scholarship-2021/RSS_SCU_H.png'; ?>" style="width: 40%; max-width: 100%;">
                </div><!-- .c-crop -->
              </div>

            </figure><!-- .c-picture -->
          </div>
        </div>

        <div class="l-section l-section--no-separator" style="margin: 2rem auto; max-width: 50rem;">
          <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">

            <div class="scu-scholarship-wrap">

              <h1 style="line-height: 2.5rem;"><?php the_title(); ?></h1>

              <p>In partnership with Southern Cross University, Rolling Stone Australia is proud to announce the second Rolling Stone Music Scholarship, which will be awarded to hard-working, passionate individual looking to study a <a href="https://www.scu.edu.au/study-at-scu/music-and-creative-arts/contemporary-music/?utm_source=rolling_stone&utm_medium=display&utm_campaign=music_scholarship_2022&utm_content=micro_site&utm_term=null" target="_blank">Bachelor of Contemporary Music</a>.</p>
              <div style="float: left; margin-right: 1rem; margin-bottom: .5rem; width: 50%; padding: .65rem 0;">
                <div style="display: inline-block; border: 2px solid #ccc; line-height: 1; padding: .25rem;">
                  <img src="<?php echo RS_THEME_URL; ?>/images/scu-scholarship-2021/image-1.jpg">
                </div>
              </div>
              <p>The Scholarship covers a total value of $15,000 over 3 years for Southern Cross University's Bachelor of Contemporary Music degree at either their Lismore or Coomera campuses.
                Students of the Bachelor of Contemporary Music can look forward to creating a full portfolio for their career in music, where you can "tailor your degree towards your area of interest, whether it’s production, performance, songwriting, or other professional areas of the music industry."</p>

              <div style="float: right; margin-left: 1rem; margin-bottom: .5rem; width: 50%; padding: .65rem 0;">
                <div style="display: inline-block; border: 2px solid #ccc; line-height: 1; padding: .25rem;">
                  <img src="<?php echo RS_THEME_URL; ?>/images/scu-scholarship-2021/image-2.jpg">
                </div>
              </div>

              <p>By learning in state-of-the-art facilities with on-campus recording studios, keyboard labs, music production labs, rehearsal studios, and even professional-standard performance spaces, you'll be working with the best of the best in equipment that will, in-turn, equip you for your perfect career in music.</p>

              <p>Applications open on November 28, and close on December 31, 2022. From there, top responses will be asked to audition. To apply, submit your application via the form below.</p>

              <div style="width: 100%; clear: both;"></div>

              <?php if (!$submissions_active) : ?>
                <h2 style="text-align: center; margin: 2rem auto;">
                  Applications are now closed.
                </h2>
              <?php else : ?>
                <div class="tab">
                  <form action="#" method="post" id="scu-scholarship-form" novalidate>
                    <div style="text-align: center;">
                      <h2 style="margin-top: 1rem;">Apply for the Rolling Stone Music Scholarship Below</h2>
                    </div>
                    <div class="d-flex flex-sm-column">
                      <div class="mt-1 pr-1 col-6">
                        <label>
                          First name
                          <input type="text" name="firstname" required>
                        </label>
                      </div>

                      <div class="mt-1 pl-1 col-6">
                        <label>
                          Last name
                          <input type="text" name="lastname" required>
                        </label>
                      </div>
                    </div>

                    <div class="d-flex flex-sm-column">
                      <div class="mt-1 pr-1 col-6">
                        <label>
                          Email
                          <input type="email" name="email" required>
                        </label>
                      </div>

                      <div class="mt-1 pl-1 col-6">
                        <label>
                          Phone
                          <input type="text" name="phone" required>
                        </label>
                      </div>
                    </div>

                    <div class="d-flex flex-sm-column">
                      <div class="mt-1 pr-1 col-6">
                        <label>
                          Postcode
                          <input type="text" name="postcode" required>
                        </label>
                      </div>

                      <div class="mt-1 pl-1 col-6">
                        <?php
                        $what_describes = [
                          'Current year 12 student',
                          'Current TAFE student',
                          'International Student',
                          'On a gap year',
                          'Returning to study after a break',
                          'Looking to advance or change my career',
                        ];
                        ?>
                        <label>
                          What describes you best?
                          <select name="current_status" required>
                            <option value="">-</option>
                            <?php foreach ($what_describes as $what_describe) : ?>
                              <option value="<?php echo sanitize_title_with_dashes($what_describe); ?>"><?php echo $what_describe; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </label>
                      </div>
                    </div>

                    <div class="d-flex flex-sm-column">
                      <div class="mt-1 flex-fill">
                        In 50 words or less tell us why music matters to you?
                        <textarea name="reason" id="reason" rows="2" required></textarea>
                        <div style="display: none;"># of words <span id="reason_wordcount">0</span></div>
                      </div>
                    </div>

                    <div class="mt-1">
                      <div class="text-danger js-errors" style="display: none;"></div>
                    </div>

                    <div style="margin-top: 1rem; text-align :right;">
                      <button type="submit" class="btn-submit" id="btn-submit" style="background: #000;">
                        <div class="spinner hidden"></div>
                        <span class="button-text d-flex" style="align-items: center;">Submit</span>
                      </button>
                    </div>

                    <h3 style="margin-top: 2rem; text-align: center; font-size: .9rem;">
                      <a href="<?php echo RS_THEME_URL ?>/images/scu-scholarship-2021/Terms-and-Conditions-V2.pdf" target="_blank" style="color: #777;">Terms &amp; Conditions</a>
                      <span style="color: #bbb;">
                        &nbsp;
                        |
                        &nbsp;
                      </span>
                      <a href="<?php echo RS_THEME_URL ?>/images/scu-scholarship-2021/Privacy-Notice.pdf" target="_blank" style="color: #777;">Privacy Notice</a>
                    </h3>
                  </form>
                </div>

                <div id="thank-you-popup">
                  <div></div>
                </div>

                <div id="submissions_wrap" class="hidden2">
                  <div style="padding: 1rem 0;">
                    <div class="my-submissions-container">
                      <h3 style="margin: .5rem auto 1rem; text-align: center;">Share the Rolling Stone Scholarship opportunity</h3>
                      <div class="social-icons" style="margin-top: .5rem;">
                        <div>
                          <a class="btn-share btn-social-icon facebook-button" id="share_facebook" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode(get_the_permalink() . '?utm_source=share_facebook'); ?>"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                        </div>
                        <div style="margin: auto 1rem;">
                          <a class="btn-share btn-social-icon twitter-button" id="share_twitter" href="https://twitter.com/intent/tweet?text=&url=<?php echo urlencode(get_the_permalink() . '?utm_source=share_twitter'); ?>"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                        </div>
                        <div>
                          <a class="btn-social-icon email-button" id="share_email" href="mailto:?subject=&body=<?php echo urlencode(get_the_permalink() . '?utm_source=share_email'); ?>"><i class="fas fa-envelope" aria-hidden="true"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              <?php endif; // If $submissions_active 
              ?>

              <div style="margin-top: 2rem; text-align: center;">
                <h3>Hear from last year's scholarship recipient</h3>
                <div style="display: inline-block; border: 4px solid #555; line-height: 1; padding: .5rem; background-color: #ccc;">
                  <iframe width="560" height="315" src="https://www.youtube.com/embed/yZmSbloY144" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
              </div>


              <!-- <div>
                <a href="#" target="_blank" style="text-align: center; margin: 2rem auto; font-weight: 600; box-shadow: 0 0 18px #aaa; border-radius: 1rem; padding: .5rem 1rem; background: #000; color: #fff; display: block;">
                </a>
              </div> -->

            </div><!-- .scu-scholarship-wrap -->
          </div><!-- /.c-content t-copy -->
        </div><!-- /.l-section -->

        <div class="slideshow-wrap" id="slideshow-wrap2" style="margin-top: 2rem;">
          <div class="slideshow js-slideshow" id="js-slideshow2" style="display: none;">
            <?php for ($i = 0; $i < 5; $i++) : ?>
              <div class="slide"><img src="<?php echo RS_THEME_URL ?>/images/scu-scholarship-2021/scroll-2.jpg" /></div>
            <?php endfor; ?>
          </div>
        </div>

        <?php if (0) : ?>
          <div class="l-page__content" style="margin-top: 2.3rem;">
            <div class="l-section l-section--no-separator">
              <div class="c-content c-content--no-sidebar t-copy" style="margin: auto;">
                <div class="t-c-wrap">
                  <h3>

                  </h3>
                </div>
              </div>
            </div>
          </div>
        <?php endif; // If $submissions_active 
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
