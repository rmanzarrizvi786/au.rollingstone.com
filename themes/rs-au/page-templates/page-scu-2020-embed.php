<?php

/**
 * Template Name: SCU Scholarship (2020) Embed
 */

$submissions_active = time() < strtotime('2021-02-01');

// wp_enqueue_script('scu-scholarship-2020', get_template_directory_uri() . '/page-templates/scu-scholarship-2020/js/scripts.js', ['jquery'], time(), true);
?>

<?php

/**
 * The template for displaying the header.
 *
 * @package rs-au-2019
 * @since 2019-11-20
 */

use PMC\Lists\List_Post;

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<head>
  <meta charset="<?php echo esc_attr(get_bloginfo('charset')); ?>" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <link rel="manifest" href="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/manifest.json'); // WPCS: XSS okay. 
                              ?>">

  <!-- Responsiveness -->
  <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1">

  <!-- Browser shell -->
  <meta name="theme-color" content="#df3535">

  <!-- Add to home screen for iOS -->
  <meta name="apple-mobile-web-app-title" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <link rel="apple-touch-icon" href="https://www.rollingstone.com/wp-content/uploads/2018/07/cropped-rs-favicon.png?w=180">

  <!-- Tile icons for Windows -->
  <meta name="msapplication-config" content="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/browserconfig.xml'); // WPCS: XSS okay. 
                                              ?>">
  <meta name="msapplication-TileImage" content="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/icons/mstile-144x144.png'); // WPCS: XSS okay. 
                                                ?>">
  <meta name="msapplication-TileColor" content="#eff4ff">

  <!-- Favicons -->
  <link rel="icon" type="image/png" sizes="32x32" href="https://www.rollingstone.com/wp-content/uploads/2018/07/cropped-rs-favicon.png?w=32">
  <link rel="shortcut icon" href="https://www.rollingstone.com/wp-content/uploads/2018/07/cropped-rs-favicon.png?w=196">

  <!-- Safari pin icon -->
  <link rel="mask-icon" href="<?php echo PMC::esc_url_ssl_friendly(RS_THEME_URL . '/assets/app/icons/safari-pinned-tab.svg'); // WPCS: XSS okay. 
                              ?>" color="#000000">

  <!-- Titles -->
  <meta name="apple-mobile-web-app-title" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
  <meta name="application-name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
  <meta name="description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
  <!-- Titles:end -->

  <meta property="fb:pages" content="203538151294" />

  <?php if (is_single()) {
    $src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
  ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@rollingstoneaus">
    <meta name="twitter:title" content="<?php the_title(); ?>">
    <meta name="twitter:image" content="<?php if (has_post_thumbnail()) {
                                          echo $src[0];
                                        } ?>">
  <?php } // If single 
  ?>

  <?php do_action('pmc_tags_head'); ?>

  <?php wp_head(); ?>
  <script type='text/javascript' defer='defer' src='https://au.rollingstone.com/wp-content/themes/rs-au/page-templates/scu-scholarship-2020/js/scripts.js?ver=1611699886' id='scu-scholarship-2020-js'></script>

  <!-- TikTok -->
  <script>
    (function() {
      var ta = document.createElement('script');
      ta.type = 'text/javascript';
      ta.async = true;
      ta.src = 'https://analytics.tiktok.com/i18n/pixel/sdk.js?sdkid=BRGQC53J857475I0KC5G';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(ta, s);
    })();
  </script>

  <link rel="stylesheet" id="fontawesome" href="<?php echo get_template_directory_uri(); ?>/fontawesome/css/all.min.css?v=20201126" type="text/css" media="all" />

  <!-- <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.1/build/base-min.css"> -->

  <style>
    h2 {
      margin-top: 1rem;
      margin-bottom: 1rem;
    }

    h3 {
      margin-top: .5rem;
      margin-bottom: .5rem;
    }

    ul {
      padding-left: 2.5rem;
    }

    .l-page,
    .is-header-sticky .l-header__wrap {
      width: 100% !important;
    }

    @media (min-width: 60rem) {

      .l-header,
      .l-header__content,
      .l-header__wrap {
        height: auto !important;
      }
    }

    a {
      color: #d32531;
      text-decoration: none;
    }

    .logo-wrap {
      background-image: url(<?php echo RS_THEME_URL; ?>/images/vote-100-movies/header-bg3.jpg);
      background-repeat: no-repeat;
      background-position: center;
      background-color: #ddd;
      background-size: cover;
    }

    .logo {
      padding: 2rem 2rem;
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


    @media (max-width: 59.9375rem) {
      .l-header__wrap2 {
        margin-top: 25px !important;
      }

      .nav-network-wrap {
        position: relative;
      }

      .logo {
        padding: 2rem 1rem;
      }
    }

    @media (max-width: 480px) {

      .logo img {
        width: 90% !important;
      }
    }


    div.admz,
    div.admz-sp {
      margin-left: auto;
      margin-right: auto;
      text-align: center;
    }

    figure {
      max-width: 100%;
      text-align: center;
    }

    iframe {
      margin: auto;
      max-width: 100%;
    }

    .c-picture__title,
    .c-picture__source {
      text-align: left;
    }


    #scu-scholarship-form {
      border-top: 1px solid #ededed;
      border-bottom: 1px solid #ededed;
      padding-bottom: 1rem;
      margin-top: 1rem;
    }

    #scu-scholarship-comp-form {
      padding-bottom: 0;
    }

    .tab {
      /* display: none; */
    }

    .select2-selection__rendered {
      line-height: 31px !important;
    }

    .select2-container .select2-selection--single {
      height: 35px !important;
    }

    .select2-selection__arrow {
      height: 34px !important;
    }

    .error {
      color: red;
    }

    /* .scu-scholarship-wrap {
      border-radius: 15px;
      background-color: #fff;
      padding: 15px 25px;
    } */

    .select-movie-wrap {
      position: relative;
    }

    .select-movie-wrap .edit-select-movie {
      position: absolute;
      right: .5rem;
      top: 50%;
      transform: translateY(-50%);
      color: #ccc;
      cursor: pointer;
    }

    .select-movie-wrap .edit-select-movie.hidden {
      display: none;
    }

    .scu-scholarship-wrap .select-movies {
      width: 100%;
    }

    .scu-scholarship-wrap .mt-1 {
      margin-top: 1rem;
    }

    input[type=password],
    .scu-scholarship-wrap input[type=text],
    .scu-scholarship-wrap input[type=email],
    .scu-scholarship-wrap select,
    .scu-scholarship-wrap textarea {
      border: 1px solid #aaa;
      border-radius: 4px;
      padding: .5rem .25rem;
      width: 100%;
    }

    .scu-scholarship-wrap input[type=text],
    .scu-scholarship-wrap input[type=email] {
      width: 100%;
    }

    input[type=submit],
    .scu-scholarship-wrap .btn-submit {
      background: #d32531;
      color: #fff;
      padding: .5rem 1rem;
      cursor: pointer;
      border-radius: 4px;
    }

    .scu-scholarship-wrap .btn-next-prev {
      background: #333;
      color: #fff;
      padding: .5rem 1rem;
      cursor: pointer;
      border-radius: 4px;
    }

    .d-flex {
      display: flex;
      justify-content: center;
    }

    .d-flex .flex-fill {
      flex: 1 1 auto;
    }

    .col-6 {
      width: 50%;
      max-width: 100%;
    }

    .px-1,
    .pl-1 {
      padding-left: 1rem;
    }

    .px-1,
    .pr-1 {
      padding-right: 1rem;
    }

    .text-danger {
      color: #dc3545 !important;
    }

    .text-success {
      color: #28a745 !important;
    }

    .js-errors,
    #js-success {
      margin: 1rem auto;
    }

    .hidden {
      /* display: none; */
    }

    .loading {
      display: inline-block;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      animation: spin 1s ease-in-out infinite;
      -webkit-animation: spin 1s ease-in-out infinite;
    }

    .spinner.loading {
      border: 3px solid rgba(255, 255, 255, 0.3);
      border-top-color: #fff;
      /* margin: .25rem auto 0; */
      margin: .05rem auto -.25rem
    }

    @keyframes spin {
      to {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }

    @-webkit-keyframes spin {
      to {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }

    @media (max-width: 576px) {
      .flex-sm-column {
        flex-direction: column;
      }

      .px-1,
      .pl-1 {
        padding-left: 0;
      }

      .px-1,
      .pr-1 {
        padding-right: 0;
      }

      .col-6 {
        width: 100%;
      }
    }

    .social-icons {
      display: flex;
      justify-content: center;
    }

    .btn-social-icon {
      color: #fff;
      height: 50px;
      min-width: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: none;
      transition: all .1s ease-out;
      box-shadow: 0 0.063em 0.313em 0 rgba(0, 0, 0, .07), 0 0.438em 1.063em 0 rgba(0, 0, 0, .1);
      font-size: 150%;
    }

    .btn-social-icon:visited {
      color: #fff;
    }

    .btn-social-icon:hover {
      color: #fff;
      box-shadow: 0 15px 20px -10px rgba(0, 0, 0, .5);
      transform: translateY(-3px);
    }

    .btn-social-icon.sms-button {
      background: linear-gradient(90deg, #0f2027, #203a43, #2c5364);
      color: #fff;
    }

    .btn-social-icon.whatsapp-button {
      background: #25d366;
    }

    .btn-social-icon.messenger-button {
      background: #0084ff;
    }

    .btn-social-icon.facebook-button {
      background: #3b5998;
    }

    .btn-social-icon.twitter-button {
      background: #1da1f2;
    }

    .btn-social-icon.linkedin-button {
      background: #0077b5;
    }

    .btn-social-icon.email-button {
      background: #000;
    }


    #submissions_wrap.hidden {
      display: none;
    }

    .my-submissions-container {
      background-image: url(<?php echo RS_THEME_URL; ?>/images/vote-100-movies/my-votes-bg.jpg);
      background-repeat: repeat;
      padding: 1rem;
      border-radius: 50px;
    }

    .my-submissions-img-container img {
      width: 100%;
      height: auto;
    }

    @font-face {
      font-family: 'RobotoCondensed-Light';
      src: url('<?php echo esc_url($fonts_url); ?>/RobotoCondensed-Light.ttf') format('ttf');
      font-weight: normal;
      font-style: normal;
      font-display: swap;
    }

    .flickity-button-icon,
    .flickity-button {
      display: none;
    }

    .slideshow {
      height: 200px;
      max-width: 100%;
      overflow: hidden;
    }

    .slide {
      margin-right: 0;
      text-align: center;
      height: 100%;
    }

    @media (max-width: 59.9375rem) {

      .slideshow,
      .slide {
        height: 100px;
      }
    }

    .slide img {
      width: auto;
      height: 100%;
      max-width: none;
    }

    .t-c-wrap {
      font-size: 12px
    }

    .t-c-wrap table {
      border: none;
    }

    .t-c-wrap table tr,
    .t-c-wrap table tr th,
    .t-c-wrap table tr td {
      /* border: none; */
      vertical-align: top;
    }

    .t-c-wrap table tr th,
    .t-c-wrap table tr td {
      padding-left: 5px;
      padding-right: 5px;
    }
  </style>

</head>

<body style="background: #fff;">
  <?php

if (have_posts()) :
  while (have_posts()) :
    the_post();

    if (!post_password_required($post)) :
  ?>
      <div class="l-page__content">
        <div class="l-section l-section--no-separator">
          <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">

            <div class="scu-scholarship-wrap">
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
</div>

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
</body>
</html>