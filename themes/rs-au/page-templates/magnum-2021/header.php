<?php

/**
 * The template for displaying the header.
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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

  <?php wp_head(); ?>

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

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">




  <style>
    @import url(https://fonts.googleapis.com/css?family=Titillium+Web:400,200,200italic,300,300italic,900,700italic,700,600italic,600,400italic);

    body {
      font-family: 'Josefin Sans', sans-serif;
      font-size: 1.2rem;
    }

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

    .brands__sub-menu {
      max-width: 100% !important;
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

    .logo {
      /* padding: 2rem 2rem; */
      margin-bottom: 1rem;
    }

    @media (max-width: 59.9375rem) {
      .l-header__wrap2 {
        margin-top: 25px !important;
      }

      .nav-network-wrap {
        position: relative;
        border-bottom: none !important;
      }

      .logo {
        padding: 2rem 1rem;
      }

      .subheading {
        display: flex;
        flex-direction: column;
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

    .nav-network-wrap {
      position: fixed !important;
      z-index: 200;
    }

    .c-picture__title,
    .c-picture__source {
      text-align: left;
    }


    .d-flex {
      display: flex;
      /* justify-content: center;
      align-items: center; */
    }

    .d-flex .flex-fill {
      flex: 1 1 auto;
    }

    .flex-row {
      flex-direction: row;
    }

    .col-6 {
      width: 50%;
      max-width: 100%;
    }

    .col-4 {
      width: calc(100% / 3);
    }

    .px-1,
    .pl-1 {
      padding-left: 1rem;
    }

    .px-1,
    .pr-1 {
      padding-right: 1rem;
    }

    .px-2,
    .pl-2 {
      padding-left: 2rem;
    }

    .px-2,
    .pr-2 {
      padding-right: 2rem;
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



    #timer {
      /* background: rgba(241, 241, 241, 1) url(<?php echo RS_THEME_URL; ?>/page-templates/magnum-2021/magnum-classic.jpg) no-repeat top center; */
      /* padding: 230px 0 350px; */

      background-color: rgb(32, 32, 32);
      /* background-image: url(<?php echo RS_THEME_URL; ?>/page-templates/magnum-2021/Symbol_Miley_v2.jpg); */
      background-repeat: no-repeat;
      background-position: center;

      background-size: contain;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      position: relative;
      /* height: 100vh; */

      max-width: 100%;
      overflow: hidden;
    }

    #timer .img-wrap {
      display: block;
      width: 100%;
      text-align: center;
      height: 100%;
      position: absolute;
      top: 0;
      left: 0;
    }

    #timer .img-wrap img {
      height: 100% !important;
      width: auto;
      max-width: none;
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);
      z-index: 1;
    }


    /* #timer:after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, .55);
      z-index: 1;
    } */

    #timer .countdown-wrap {
      z-index: 2;
      margin-top: -5rem;
    }

    #timer .countdown-wrap div {
      /* display: inline-block; */
      font-family: 'Titillium Web', cursive;
      line-height: 1;
      padding: .5rem;
      font-weight: 100;
      text-align: center;
      z-index: 2;
      font-size: 10vh !important;
    }

    #timer .countdown-wrap div span {
      display: block;
      font-size: 3vh;
    }

    #days {
      font-size: 100px;
      color: #db4844;
    }

    #hours {
      font-size: 100px;
      color: #f07c22;
    }

    #minutes {
      font-size: 100px;
      color: #f6da74;
    }

    #timer div#seconds {
      font-size: 4vh !important;
      color: #abcd58;
      margin-top: .75rem;
    }

    #timer div#seconds span {
      font-size: 1.5vh !important;
    }

    @media (max-width: 60rem) {
      #timer .countdown-wrap div {
        font-size: 10vh !important;
        padding: .25rem !important;
      }

      #timer .countdown-wrap div span {
        font-size: 3vh !important;
      }

      #timer .countdown-wrap {
        margin-top: -3.5rem;
      }

      #timer div#seconds {
        margin-top: .25rem;
      }
    }

    @media (max-height: 30rem) {
      #timer .countdown-wrap {
        margin-top: -2rem;
      }

      #timer .countdown-wrap div {
        font-size: 7vh !important;
        padding: .25rem !important;
      }

      #timer div#seconds {
        margin-top: .25rem;
        font-size: 3vh !important;
      }

      #timer .countdown-wrap div span {
        font-size: 3vh !important;
      }
    }

    @media (max-height: 20rem) {
      #timer .countdown-wrap {
        margin-top: -2rem;
      }
    }

    @media (max-width: 576px) {
      .flex-sm-column {
        flex-direction: column !important;
      }

      .flex-mob-column {
        flex-direction: column !important;
      }

      .px-1,
      .pl-1 {
        padding-left: 0;
      }

      .px-1,
      .pr-1 {
        padding-right: 0;
      }

      .col-6,
      .col-4 {
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

    .subheading {
      font-size: 1.4rem;
    }

    .l-page__mega {
      display: none;
    }

    input[type=password] {
      border: 1px solid #ccc;
      border-radius: .25rem;
      padding: .25rem .5rem;
    }

    input[type="submit"] {
      cursor: pointer;
      background: #d32531;
      color: #fff;
      padding: .25rem 0.5rem;
      border-radius: .25rem;
    }

    #onesignal-slidedown-container,
    #onesignal-bell-container {
      display: none;
    }

    body,
    .bg-dark {
      background-color: rgb(32, 32, 32) !important;
    }

    .videos {
      width: 62.5rem;
      max-width: 100%;
      margin: auto;
      align-items: center;

      background-image: url(<?php echo RS_THEME_URL; ?>/page-templates/magnum-2021/Symbol_Miley_v3.jpg);
      background-repeat: no-repeat;
      background-position: center center;
      background-size: cover;
      background-attachment: fixed;
      position: relative;


    }

    .videos .video-wrap {

      overflow: hidden;
      /* margin: .25rem auto; */
      border-radius: 2rem;

      /* padding-top: 40.1875%;
      width: 75%; */

      border: 3px solid rgb(150, 130, 70);

      z-index: 0;
    }

    .videos .video-wrap.active {
      z-index: 2;
    }

    .videos .video-wrap iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }



    @media (min-width: 48rem) {
      .videos {
        /* flex-direction: row; */
        background-size: contain;
      }

      .videos .video-wrap {
        /* padding-top: 27.125% !important;
        width: 50% !important; */

        position: absolute;
        width: 45%;
        max-width: 550px;
      }

      <?php $top_px = 200; ?>.videos .video-wrap:nth-of-type(1) {
        top: 0;
      }

      .videos .video-wrap:nth-of-type(2) {
        /* top: 30%; */
        top: <?php echo $top_px; ?>px;
        right: 0;
      }

      .videos .video-wrap:nth-of-type(3) {
        /* top: 60%; */
        top: <?php echo $top_px * 2; ?>px;
      }

      .videos .video-wrap:nth-of-type(4) {
        right: 0;
        /* top: 90%; */
        top: <?php echo $top_px * 3; ?>px;
      }

      .videos .video-wrap:nth-of-type(5) {
        /* top: 120%; */
        top: <?php echo $top_px * 4; ?>px;
      }

      .videos .video-wrap:nth-of-type(6) {
        /* top: 150%; */
        top: <?php echo $top_px * 5; ?>px;
        right: 0;
      }
    }


    @media (max-width: 47.9375rem) {
      .videos {
        margin-bottom: 3rem;
      }

      .videos .video-wrap {
        width: 90%;
        margin: .25rem auto;
      }

      #story-title {
        font-size: 1.5rem !important;
        margin: 1rem auto !important
      }
    }
  </style>

</head>

<body <?php body_class(); ?>>

  <?php do_action('pmc-tags-top'); ?>

  <!-- Facebook Pixel Code -->

  <script>
    ! function(f, b, e, v, n, t, s)

    {
      if (f.fbq) return;
      n = f.fbq = function() {
        n.callMethod ?

          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };

      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';

      n.queue = [];
      t = b.createElement(e);
      t.async = !0;

      t.src = v;
      s = b.getElementsByTagName(e)[0];

      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',

      'https://connect.facebook.net/en_US/fbevents.js');


    fbq('init', '243859349395737');

    fbq('track', 'PageView');
  </script>

  <noscript>

    <img height="1" width="1" src="https://www.facebook.com/tr?id=243859349395737&ev=PageView

&noscript=1" />

  </noscript>

  <!-- End Facebook Pixel Code -->

  <div class="l-page" id="site_wrap">
    <div class="l-page__header">
      <?php get_template_part('template-parts/header/nav-network'); ?>

      <div class="l-header__branding" style="padding-top: 29px; background: #000;">
        <a href="<?php echo esc_url(home_url('/')); ?>" style="margin-right: .25rem;">
          <img class="l-header__logo" src="<?php echo RS_THEME_URL . '/assets/src/images/_dev/RS-AU_LOGO-RED.png'; ?>">
          <span class="screen-reader-text"><?php esc_html_e('Rolling Stone Australia', 'pmc-rollingstone'); ?></span>
        </a>
        <img src="<?php echo RS_THEME_URL; ?>/page-templates/magnum-2021/magnum.png" alt="Magnum" style="width: 141px; margin-left: .25rem; margin-top: .5rem;">
        <span class="screen-reader-text">Magnum</span>
      </div>

    </div>