<?php

/**
 * Template Name: Magnum Countdown (2021)
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
      <div class="l-page__content c-content">

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

              <div id="timer">
                <span class="img-wrap">
                  <img src="<?php echo RS_THEME_URL; ?>/page-templates/magnum-2021/Symbol_Miley_v3.jpg">
                </span>
                <div class="countdown-wrap">
                  <div id="days"></div>
                  <div id="hours"></div>
                  <div id="minutes"></div>
                  <div id="seconds"></div>
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
    setTimerHeight();

    $(window).on('resize', function() {
      setTimerHeight();
    })

    function setTimerHeight() {
      $("#timer").css('height', $(window).height() - $('.l-page__header').height());
    }
  });
</script>

<?php
get_footer();
