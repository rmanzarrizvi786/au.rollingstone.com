<?php

/**
 * Template Name: Rolling Stone Awards NZ 2022 (Info)
 */

define('ICONS_URL', get_template_directory_uri() . '/images/');

$award_categories = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rsawards_categories_2022 WHERE status = '1'");

$action = isset($_GET['a']) && in_array(trim($_GET['a']), ['add', 'edit', 'ty']) ? trim($_GET['a']) : NULL;
$success = isset($_GET['success']) ? trim($_GET['success']) : NULL;

date_default_timezone_set('Australia/NSW');
$noms_open_at = '2021-10-25 12:00:00';
$noms_open = true;
$noms_closed = false;

$errors = [];

$formdata = [];

if (is_user_logged_in()) :
  require_once get_template_directory() . '/page-templates/rs-awards/2022/nz/submit.php';
  $current_user = wp_get_current_user();

  wp_enqueue_script('jquery-ui-datepicker');
  wp_enqueue_style('jquery-ui-datepicker-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/dark-hive/jquery-ui.min.css');

  wp_enqueue_script('rs-awards', get_template_directory_uri() . '/page-templates/rs-awards/js/scripts-2022.js', array('jquery'), time(), true);
endif; // If logged in

get_header('rsawards-nz-custom-menu');
?>

<?php
if (have_posts()) :
  while (have_posts()) :
    the_post();

    if (!post_password_required($post)) :
?>
      <div id="content-wrap">

        <!-- <div class="container rsa-header" style="background-color: #fff;">
          <img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/header-red.jpg">
        </div> -->

        <section class="bg-white p-3 d-flex" style="background-color: #fff; padding: 1.5rem;">
          <div class="col-12">
            <div class="d-flex flex-column flex-md-row align-items-stretch">
              <div class="col-12">
                <?php
                $show = isset($_GET['show']) ? trim(strtolower($_GET['show'])) : 'news';
                switch ($show) {
                  case 'news':
                    get_template_part('page-templates/rs-awards/2022/nz/news');
                    break;
                    // case 'categories':
                    //   get_template_part('page-templates/rs-awards/2022/nz/categories', NULL, ['award_categories' => $award_categories, 'noms_open' => $noms_open, 'noms_open_at' => $noms_open_at, 'context' => 'info']);
                    //   break;
                  case 'info':
                    get_template_part('page-templates/rs-awards/2022/nz/info');
                    break;
                  case 'faq':
                    get_template_part('page-templates/rs-awards/2022/nz/faq');
                    break;
                  case 'judges':
                    get_template_part('page-templates/rs-awards/2022/nz/judges');
                    break;
                  case 'venue-details':
                    get_template_part('page-templates/rs-awards/2022/nz/venue-details');
                    break;
                  case 'sponsors':
                    get_template_part('page-templates/rs-awards/2022/nz/sponsors');
                    break;
                  default:
                    get_template_part('page-templates/rs-awards/2022/nz/news');
                    break;
                }

                ?>
              </div>
            </div>
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
    jQuery(document).ready(function($) {
      $('[data-toggle="collapse"]').on('click', function() {
        var target = $($(this).data('target'));
        target.collapse(
          target.is(":hidden") ? 'show' : 'hide'
        );
        $(this).toggleClass('rounded-top');
        $(this).find('.arrow-down img').toggleClass('rotate180');
      });

      /* setTimeout(function() {
        $('.awardnoms').collapse('show');
      }, 1500); */

      /**
       * Extend jQuery for collapse
       */
      $.fn.extend({
        collapse: function(action) {
          if ('show' == action) {
            $(this).slideDown();
          } else if ('hide' == action) {
            $(this).slideUp();
          }
        }
      });

      <?php if (!$noms_open) : ?>
        /**
         * Countdown timer
         */
        var endDate = new Date('<?php echo $noms_open_at; ?>');
        var timerInterval;

        function makeTimer() {
          $('#timer-awards-noms-open').removeClass('d-none').addClass("d-flex");

          var endTime = new Date(endDate.getTime());
          endTime = Date.parse(endTime) / 1000;

          var now = new Date();
          now = Date.parse(now) / 1000;

          var timeLeft = endTime - now;

          if (timeLeft <= 0) {
            clearInterval(timerInterval);
            window.location.reload();
          }

          var days = Math.floor(timeLeft / 86400);
          var hours = Math.floor((timeLeft - days * 86400) / 3600);
          var minutes = Math.floor((timeLeft - days * 86400 - hours * 3600) / 60);
          var seconds = Math.floor(
            timeLeft - days * 86400 - hours * 3600 - minutes * 60
          );

          if (days < "10") {
            days = "0" + days;
          }
          if (hours < "10") {
            hours = "0" + hours;
          }
          if (minutes < "10") {
            minutes = "0" + minutes;
          }
          if (seconds < "10") {
            seconds = "0" + seconds;
          }

          jQuery("#timer-awards-noms-open .days").html('<div class="number">' + days + "</div><div>Days</div>");
          jQuery("#timer-awards-noms-open .hours").html('<div class="number">' + hours + "</div><div>Hours</div>");
          jQuery("#timer-awards-noms-open .minutes").html('<div class="number">' + minutes + "</div><div>Mins</div>");
          jQuery("#timer-awards-noms-open .seconds").html('<div class="number">' + seconds + "</div><div>Secs</div>");


        }

        timerInterval = setInterval(function() {
          makeTimer();
        }, 1000);
      <?php endif; ?>

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
