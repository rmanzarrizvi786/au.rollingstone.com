<?php

/**
 * The template for displaying the header.
 *
 * @package rs-au-2019
 * @since 2019-11-20
 */

use PMC\Lists\List_Post;

$list_page_active = 'l-header l-header--list';
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

  <script src="https://kit.fontawesome.com/2d9b4b58e5.js" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.1/build/base-min.css">
  <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
  <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>

  <?php $fonts_url = RS_THEME_URL . '/assets/build/fonts'; ?>

  <?php $img_url = RS_THEME_URL . '/images/vote-25-movies-2021/'; ?>

  <style>
    /* body {
			background-color: #000 !important;
		}
		.l-page__content {
			background-color: transparent !important;
		} */

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
      background-image: url(<?php echo $img_url; ?>header-bg3.jpg);
      background-repeat: repeat-x;
      background-position: center;
      background-color: #ddd;
    }

    .logo {
      padding: 2rem 2rem;
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


    #gmoat-vote-form,
    #gmoat-comp-form {
      border-top: 1px solid #ededed;
      border-bottom: 1px solid #ededed;
      padding-bottom: 1rem;
      margin-top: 1rem;
    }

    #gmoat-comp-form {
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

    /* .gmoat-wrap {
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

    .gmoat-wrap .select-movies {
      width: 100%;
      padding: .5rem;
    }

    .gmoat-wrap .mt-1 {
      margin-top: 1rem;
    }

    input[type=password],
    .gmoat-wrap input[type=text],
    .gmoat-wrap input[type=email],
    .gmoat-wrap textarea {
      border: 1px solid #aaa;
      border-radius: 4px;
      padding: .5rem .25rem;
      width: 100%;
    }

    .gmoat-wrap input[type=text],
    .gmoat-wrap input[type=email] {
      width: 100%;
    }

    input[type=submit],
    .gmoat-wrap .btn-submit {
      background: #d32531;
      color: #fff;
      padding: .5rem 1rem;
      cursor: pointer;
      border-radius: 4px;
    }

    .gmoat-wrap .btn-next-prev {
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

    .select2-container--open .select2-search__field {
      padding: .5rem !important;
    }

    #voted_movies_wrap {
      display: flex;
      flex-direction: column;
      margin: 2rem auto;
      justify-content: center;
      align-items: center;
      /* background: #000; */
    }

    #voted_movies_wrap.hidden {
      display: none;
    }

    .my-votes-img-container {
      /* position: absolute; */
    }

    .my-votes-container {
      background-image: url(<?php echo $img_url; ?>MyVotes_1200x1200-V2.jpg);
      background-repeat: no-repeat;
      background-size: contain;
      background-position: center;
      padding: 1rem;
      width: 80%;
      height: 0;
      padding-top: 80%;
      position: relative;
    }

    .my-votes-img-container img {
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

    @font-face {
      font-family: 'Roboto-Bold';
      src: url('<?php echo esc_url($fonts_url); ?>/Roboto-Bold.ttf') format('ttf');
      font-weight: normal;
      font-style: normal;
      font-display: swap;
    }

    .voted_movies_container {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translateX(-50%);
      width: 70%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 40%;
      max-height: 40%;
      overflow: scroll;
    }

    #voted_movies {
      list-style: none;
      position: absolute;
      top: 45%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 75%;
      border: none;
    }

    #voted_movies tr {
      border: none;
    }

    #voted_movies tr td {
      padding: 1rem 1rem 1rem 3.5rem;
      margin: .5rem auto;
      vertical-align: top;
      background-position: left center;
      background-repeat: no-repeat;
      background-size: 50px;
      height: 5.5rem;
      display: flex;
      align-items: center;
    }

    <?php for ($i = 1; $i <= 3; $i++) : ?>#voted_movies tr:nth-of-type(<?php echo $i; ?>) td {
      background-image: url(<?php echo $img_url; ?>Number<?php echo $i; ?>.png);
    }

    <?php endfor; ?>#voted_movies tr td,
    #voted_movies li {
      /* color: #28a745; */
      font-family: Roboto-Bold, sans-serif;
      font-size: 150%;
      line-height: 125%;
      /* margin: 1rem auto; */
      font-weight: bold;
    }

    .flickity-button-icon,
    .flickity-button {
      display: none;
    }

    .slideshow {
      height: 300px;
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
        height: 150px;
        width: auto !important;
        max-width: none;
      }

      .my-votes-container {
        width: 95%;
        padding-top: 95%;
      }

      #voted_movies {
        top: 45%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 75%;
      }
    }

    @media (max-width: 700px) {

      #voted_movies tr td,
      #voted_movies li {
        font-size: 120%;
      }
    }

    @media (max-width: 500px) {

      #voted_movies tr td,
      #voted_movies li {
        font-size: 80%;
        margin: 0 auto;
        height: 3.5rem;
        padding: .5rem 1rem .5rem 2.5rem;
        background-size: 2rem;
      }
    }

    .slide img {
      width: auto !important;
      height: 100% !important;
      max-width: none !important;
    }

    .movie-reel {
      height: 70px;
      background-image: url(<?php echo $img_url; ?>movie-reel.jpg);
      background-repeat: repeat-x;
      background-size: contain;
    }

    .movie-reel-v {
      width: 70px;
      background-image: url(<?php echo $img_url; ?>movie-reel.jpg);
      background-repeat: repeat-y;
      background-size: contain;
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

    .c-list__title {
      margin-bottom: 1rem;
    }

    .btn-purchase {
      background-color: #d32531;
      color: #fff;
      font-family: Graphik, sans-serif;
      border-radius: .25rem;
      padding: .25rem .5rem;
      display: inline-block;
      transition: .25s all linear;
      border: 1px solid #d32531;
    }

    .btn-purchase:hover {
      background-color: #fff;
      color: #d32531;
      border: 1px solid #d32531;
    }

    .l-header__menu-link.highlight {
      color: #d32531;
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

      <header class="<?php echo esc_attr($list_page_active); ?>" style="height: auto;">

        <!--. l-header__wrap -->


      </header><!-- .l-header -->

    </div>