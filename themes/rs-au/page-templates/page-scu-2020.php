<?php

/**
 * Template Name: SCU Scholarship (2020)
 */

get_template_part('page-templates/scu-scholarship-2020/header');

// $submissions_active = time() < strtotime('2021-02-01');
$submissions_active = false;

wp_enqueue_script('scu-scholarship-2020', get_template_directory_uri() . '/page-templates/scu-scholarship-2020/js/scripts.js', ['jquery'], time(), true);
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
              <div class="slide"><img src="<?php echo RS_THEME_URL ?>/images/scu-scholarship-2020/scroll-1.jpg" /></div>
              <div class="slide"><img src="<?php echo RS_THEME_URL ?>/images/scu-scholarship-2020/scroll-1.jpg" /></div>
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
                  <img src="<?php echo RS_THEME_URL . '/images/scu-scholarship-2020/RSS_SCU_H.png'; ?>" style="width: 40%; max-width: 100%;">
                </div><!-- .c-crop -->
              </div>

            </figure><!-- .c-picture -->
          </div>
        </div>

        <div class="l-section l-section--no-separator" style="margin: 2rem auto; max-width: 50rem;">
          <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">

            <div class="scu-scholarship-wrap">

              <h1 style="line-height: 2.5rem;"><?php the_title(); ?></h1>

              <p>In partnership with Southern Cross University, Rolling Stone Australia is bringing forth the Rolling Stone Music Scholarship which will be awarded to hard-working, passionate individuals looking to study a <a href="https://www.scu.edu.au/study-at-scu/music-and-creative-arts/contemporary-music/?utm_source=rolling_stone&utm_medium=display&utm_campaign=contemporary_music_program_2022&utm_content=micro_site&utm_term=null" target="_blank">Bachelor of Contemporary Music</a>.</p>
              <div style="float: left; margin-right: 1rem; margin-bottom: .5rem; width: 50%; padding: .65rem 0;"><img src="<?php echo RS_THEME_URL; ?>/images/scu-scholarship-2020/image-1.jpg"></div>
              <p>The Scholarship covers a total value of $15,000 over 3 years for Southern Cross University's Bachelor of Contemporary Music degree at either their Lismore or Coomera campuses.
                Students of the Bachelor of Contemporary Music can look forward to creating a full portfolio for their career in music, where you can "tailor your degree towards your area of interest, whether it’s production, performance, songwriting, or other professional areas of the music industry."</p>
              <div style="float: right; margin-left: 1rem; margin-bottom: .5rem; width: 50%; padding: .65rem 0;"><img src="<?php echo RS_THEME_URL; ?>/images/scu-scholarship-2020/image-2.jpg"></div>
              <p>By learning in state-of-the-art facilities with on-campus recording studios, keyboard labs, music production labs, rehearsal studios, and even professional-standard performance spaces, you'll be working with the best of the best in equipment that will, in-turn, equip you for your perfect career in music.</p>

              <p>Applications open on the 23rd of December, and close five weeks later on the 31st of January, 2021. The 10 best answers will go through to a second round where participants will be asked to submit a long-form answer. To apply, submit your application via the form below.</p>

              <p style="text-align: center;">
                <strong>Applications for the Rolling Stone Music Scholarship have now closed.</strong>
              </p>


              <?php if ($submissions_active) : ?>
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
                          'Current year 11 student',
                          'Current year 7-10 student',
                          'Current TAFE student',
                          'International Student',
                          'On a gap year',
                          'Returning to study after a break',
                          'Looking to advance or change my career',
                          'Parent/Guardian of student',
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

                    <h3 style="text-align: center; font-size: .9rem;">
                      <a href="<?php echo RS_THEME_URL ?>/images/scu-scholarship-2020/Terms-and-Conditions-1.pdf" target="_blank" style="color: #777;">Terms &amp; Conditions</a>
                      <span style="color: #bbb;">
                        &nbsp;
                        |
                        &nbsp;
                      </span>
                      <a href="<?php echo RS_THEME_URL ?>/images/scu-scholarship-2020/Privacy-Notice.pdf" target="_blank" style="color: #777;">Privacy Notice</a>
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
                        <div style="margin-right: 1rem;">
                          <a class="btn-share btn-social-icon facebook-button" id="share_facebook" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode(get_the_permalink() . '?utm_source=share_facebook'); ?>"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                        </div>
                        <div>
                          <a class="btn-share btn-social-icon twitter-button" id="share_twitter" href="https://twitter.com/intent/tweet?text=&url=<?php echo urlencode(get_the_permalink() . '?utm_source=share_twitter'); ?>"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              <?php else : ?>
                <!-- <div style="width: 550px; max-width: 100%; text-align: center; margin: 1rem auto; font-size: 125%; background: #f3f3f3; border-radius: 20px; padding: 1rem .5rem; box-shadow: 0 0 10px #aaa;">

                </div> -->

              <?php endif; // If $submissions_active 
              ?>


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
              <div class="slide"><img src="<?php echo RS_THEME_URL ?>/images/scu-scholarship-2020/scroll-2.jpg" /></div>
              <div class="slide"><img src="<?php echo RS_THEME_URL ?>/images/scu-scholarship-2020/scroll-2.jpg" /></div>
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
