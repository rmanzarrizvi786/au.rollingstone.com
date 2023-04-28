<?php

/**
 * Template Name: Unifier Beer Microsite (2021) - Staging
 */

get_template_part('page-templates/unifierbeer-2021-staging/header');

wp_enqueue_script('unifierbeer-2021', RS_THEME_URL . '/page-templates/unifierbeer-2021-staging/js/scripts.js', ['jquery'], time(), true);
// wp_enqueue_script('skrollr', RS_THEME_URL . '/page-templates/unifierbeer-2021-staging/js/skrollr.min.js', [], null, true);
// wp_enqueue_script('skrollr-menu', RS_THEME_URL . '/page-templates/unifierbeer-2021-staging/js/skrollr.menu.min.js', [], null, true);

$live = true; // isset($_GET['live']) && '60582b2170b4e' == $_GET['live'] ? true : false;

add_action('wp_footer', function () {
?>
  <style>

  </style>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
  <script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    jQuery(document).ready(function($) {});
  </script>
  <?php
}); // wp_footer

if (have_posts()) :
  while (have_posts()) :
    the_post();

    if (!post_password_required($post)) :
  ?>
      <div class="l-page__content c-content">

        <div class="intro l-section2 l-section--no-separator" style="background: #f7f0e6; min-height: 100vh;">
          <!-- data-0="z-index: 1; position: fixed; top: 0; left: 0; bottom: 0; left: 0; margin: auto; height: 100%; width: 100%; " data-800="z-index: 0;"  -->

          <div class="container container-intro" <?php echo !$live ? 'style="min-height: 100vh;"' : ''; ?>>
            <div class="container-intro-inner">
              <figure class="c-picture2" style="margin: auto;">
                <div class="c-picture__frame2">

                  <div class="c-crop2 c-crop--ratio-3x2-2 logo">
                    <img src="<?php echo RS_THEME_URL; ?>/page-templates/unifierbeer-2021-staging/images/Asset1.svg" style="width: 35%; max-width: 100%;">
                  </div><!-- .c-crop -->
                </div>

              </figure><!-- .c-picture -->

              <div class="font-body" style="width: 100%;">
                <div class="intro-text" style=" margin: 0 auto; text-align: center; letter-spacing: .025rem; font-size: 110%;">
                  <p>Rolling Stone and Young Henrys have come together to celebrate music that's made a difference.
                    <br>
                    This bold Hazy Pale was made with Golden Promise, malted and rolled oats and stacks of hops for stone fruit aroma and flavour.
                    <br>
                    This beer salutes musicians across the ages who use their art to protest injustice, heal divides and bring people together.
                  </p>
                </div>

                <?php if ($live) : ?>
                  <div class="scroll-arrow bounce2 font-heading" style="text-align: center; font-size: 200%; margin-top: 2rem;">
                    <div class="text-explore" style="font-size: 75%;">EXPLORE</div>
                    <i class="fas fa-chevron-down"></i>
                  </div>
                <?php else : ?>
                  <div class="scroll-arrow bounce2 font-heading" style="text-align: center; font-size: 200%; margin-top: 2rem;">
                    <div class="text-explore" style="font-size: 75%;">COMING 29<sup>TH</sup> APRIL 2021</div>
                  </div>
                <?php endif; ?>

                <div class="text-red text-center" style="margin-top: 2rem;">
                  <h2 class="font-heading subheading">
                    <span>BEER AND MUSIC</span>
                    <span>FOR THE PEOPLE</span>
                  </h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php if ($live) : ?>
        <div class="decades">
          <!-- data-300="position: fixed; top: 100%; width: 100%; height: 100%;" data-500="top: 0%; "> -->
          <section class="page decade decade-video bg-yellow" id="section-video" style="z-index: 5;">
            <!-- data-500="top: 100%;" data-550="top: 0%;"> -->
            <div class="intro-video">
              <script src="https://api.dmcdn.net/all.js"></script>
              <div id="intro-dm-player"></div>
              <script>
                var introDMPlayer = DM.player(document.getElementById("intro-dm-player"), {
                  video: "k6JQsqjlzfL0GAwLsJD",
                  width: "100%",
                  height: "100%",
                  params: {
                    autoplay: false,
                    // mute: true,
                    "queue-enable": false,
                    "queue-autoplay-next": false,
                    loop: true
                  }
                });
                // player.pause();
              </script>
            </div>
          </section>
          <?php
          $scroll_number = 1000;
          for ($i = 1, $decade = 1960; $decade <= 2020; $i++, $decade += 10) :
            include CHILD_THEME_PATH . '/page-templates/unifierbeer-2021-staging/decades/' . $decade . '.php';
          endfor;

          include CHILD_THEME_PATH . '/page-templates/unifierbeer-2021-staging/decades/charities.php';
          ?>
        </div>
        <nav class="app-nav nav-intro font-body">
          <div style="display: flex; flex-direction: row; width: 100%; justify-content: space-between;">
            <ul>
              <?php for ($i = 1, $decade = 1960; $decade <= 2020; $i++, $decade += 10) : ?>
                <li data-target="decade<?php echo $decade; ?>"><a data-position="<?php echo $i * 1000; ?>" href="#decade<?php echo $decade; ?>"><span><?php echo $decade; ?></span></a></li>
              <?php endfor; ?>
            </ul>
            <div class="font-heading text-timeline hide-m">
              <div>T</div>
              <div>I</div>
              <div>M</div>
              <div>E</div>
              <div>L</div>
              <div>I</div>
              <div>N</div>
              <div>E</div>
            </div>
          </div>
          <div class="font-heading join-the-conversation hide-m">
            <a data-position="<?php echo $i * 1000; ?>" href="#charities">
              <div style="font-size: 3rem;">JOIN</div>
              <div style="font-size: 1.7rem; margin: .6rem auto 0; display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between; align-items: center;">
                <div style="font-size: 1rem;">★</div>
                <div>THE</div>
                <div style="font-size: 1rem;">★</div>
              </div>
              <div style="font-size: .9rem; transform: scaleY(1.3);">CONVERSATION</div>
            </a>
          </div>
        </nav>
      <?php endif; // if $live 
      ?>
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



<?php
get_footer();
