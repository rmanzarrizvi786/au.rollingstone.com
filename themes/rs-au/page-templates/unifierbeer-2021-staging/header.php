<?php

/**
 * The template for displaying the header.
 */

use PMC\Lists\List_Post;

$is_a_list_page   = rollingstone_is_list();
$list_page_active = ($is_a_list_page) ? 'l-header l-header--list' : 'l-header';
$total_items      = List_Post::get_instance()->get_list_items_count();

$fonts_url = RS_THEME_URL . '/assets/build/fonts';
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

    @font-face {
      font-family: FatFrank;
      src: url("<?php echo RS_THEME_URL; ?>/page-templates/unifierbeer-2021-staging/fonts/FatFrank-Regular.otf") format("opentype");
      font-weight: normal;
      font-style: normal;
      font-display: swap;
    }

    @font-face {
      font-family: PintassilgoPrints;
      src: url("<?php echo RS_THEME_URL; ?>/page-templates/unifierbeer-2021-staging/fonts/PintassilgoPrints - Ars Nova.otf") format("opentype");
      font-weight: normal;
      font-style: normal;
      font-display: swap;
    }

    /* @font-face {
      font-family: 'RobotoCondensed-Light';
      src: url('<?php echo esc_url($fonts_url); ?>/RobotoCondensed-Light.ttf') format('ttf');
      font-weight: normal;
      font-style: normal;
      font-display: swap;
    } */

    .font-body {
      font-family: FatFrank, sans-serif;
    }

    .font-heading {
      font-family: PintassilgoPrints, sans-serif;
    }

    .text-black {
      color: #000 !important;
    }

    .bg-black {
      background: #000 !important;
    }

    .text-white {
      color: #fff !important;
    }

    .text-yellow {
      color: #f7f0e6 !important;
    }

    .bg-yellow {
      background: #f7f0e6 !important;
    }

    .text-green {
      color: #989c6c !important;
    }

    .bg-green {
      background: #989c6c !important;
    }

    .text-red {
      color: #d20208 !important;
    }

    .bg-red {
      background: #d20208 !important;
    }

    .w-100 {
      width: 100% !important;
    }

    .intro-text-video-wrap {
      justify-content: center;
      align-items: center;
      width: 90%;
      margin: auto;
    }

    #intro-dm-player {
      width: 80%;
      height: 80vh;
      margin: auto;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -60%)
    }

    @media (min-width: 1201px) and (min-height: 860px) {
      .container-intro {
        position: relative;
        margin-top: 1rem;
      }

      .container-intro-inner {
        width: 100%;
        /* position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%); */
      }

      .inner-support {
        position: absolute;
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%);
      }
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

    .logo-wrap {}

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
      }

      .logo {
        padding: 2rem 1rem;
      }

      .subheading {
        display: flex;
        flex-direction: column;
      }

      .decade .container .content-text .intro {
        width: 90% !important;
      }
    }

    @media only screen and (max-height: 760px) {
      .intro-text {
        max-width: 42% !important;
        font-size: 100%;
      }

      .subheading {
        font-size: 1.1rem;
      }

      .logo img {
        max-height: 40vh !important;
      }

      .scroll-arrow {
        font-size: 150% !important;
      }
    }

    @media only screen and (max-width: 480px) {

      .logo img {
        width: 90% !important;
        max-height: none !important;
      }

      .text-1980 {
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

    .scroll-arrow {
      /*
      cursor: pointer;
      position: absolute;
      bottom: 0;
      left: 50%; */
    }

    .skrollr .container {
      height: 100%;
    }

    .container {
      position: relative;
      padding: 50px 3%;
      width: 94%;
      max-width: 1200px;
      margin: 0 auto;
      font-size: 1rem;
    }

    .intro {
      z-index: 105;
    }

    .intro-text {
      max-width: 32%;
      /* font-size: 120%; */
    }

    .subheading {
      font-size: 1.4rem;
    }

    .decades {
      position: relative;
      display: block;
      z-index: 103;
      height: 120% !important;
    }

    .decade {
      /* overflow: hidden; */
      /* height: 120%; */
      min-height: 100vh;
      position: relative;
      margin-top: -1rem;
    }

    .decade .container {
      width: 100%;
      max-width: 100%;
      padding: 0;
      /* display: flex; */
      justify-content: center;
      align-items: center;
      position: relative;
    }

    .decade .container .inner {
      padding: 0 2rem 2rem 2rem;
      width: 94%;
      max-width: 1200px;
      margin: 1rem auto;
      /* position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -65%); */
    }

    .decade .container h2.title {
      margin: 4rem auto 0 auto;
      text-align: center;
      color: #fff;
      font-size: 5vh;
      line-height: .9;
      position: relative;
    }

    .decade .container h2.title .text-decade {
      font-size: 15vh;
      display: block;
    }

    .decade .container .divider {
      width: 150px;
      height: 5px;
      background: #000;
      margin: 3rem auto;
    }

    .decade .container .content-wrap {
      display: flex;
      /* height: 100vh; */
    }

    .decade .container .content-text {
      flex: 7;
      padding: 0 2rem 2rem 2rem;
      color: #fff;
      font-family: FatFrank, sans-serif;
    }

    .decade .container .content-text .intro {
      width: 75%;
      margin: auto;
      font-size: 110%;
      margin: 0 auto 3rem auto;
      letter-spacing: .025rem;
    }

    .decade .container .flex-reverse {
      flex-direction: row-reverse;
    }

    .decade .container .flex-reverse .content-text {
      /* padding: 0 2rem 2rem 2rem; */
    }

    .decade .container .content-text p {
      padding: .9375rem 0;
    }

    .decade .container .content-text p:first-child {
      padding-top: 0;
    }

    .no-js .intro {
      position: relative;
    }

    .decade .container .content-img {
      flex: 5;
      position: relative;
      margin-top: 70px;
    }

    .decade .container .content-img .img-wrap {
      position: sticky !important;
      top: 70px;
    }

    .decade .container .content-img .img-caption {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      /* background: #d32531; */
      /* background: rgba(211, 37, 49, 0.7); */
      background: rgba(0, 0, 0, .7);
      color: #fff;
      padding: .5rem 1rem;
    }

    .decade .container .content-text .link-bottom {
      display: flex;
      margin-top: 2rem;
      font-family: PintassilgoPrints, sans-serif;
      justify-content: center;
      align-items: stretch;
    }

    .decade .container .content-text .link-bottom .text-further-reading {
      background: #ffe4ad !important;
      color: #000;
      padding: 1rem .5rem;
    }

    .decade .container .content-text .link-bottom .link-further-reading {
      background: #000 !important;
      color: #f7f0e6;
    }

    .decade .container .content-text .link-bottom .link-further-reading a {
      display: block;
      padding: 1rem .5rem;
      color: #ffe4ad !important;
    }

    .decade .container .content-text .link-bottom .text-further-reading,
    .decade .container .content-text .link-bottom .link-further-reading {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .skrollr .decade {
      /* position: absolute;
      height: 100%;
      width: 100%;
      min-height: 700px;
      top: 100%; */
    }

    .decade1960 {
      /* background-color: #1abc9c; */
      background-color: #4e7d9d;
      z-index: 10;
    }

    .decade1970 {
      /* background-color: #f39c12; */
      background-color: #989c6c;
      z-index: 20;
    }

    .decade1980 {
      /* background-color: #2ecc71; */
      background-color: #d20208;
      z-index: 30;
    }

    .text-1980 {
      /* display: flex; */
      padding: 1.3rem 0;
      border-top: 5px solid #000;
      border-bottom: 5px solid #000;
      text-align: center;
    }

    .text-1980 div {
      padding: .5rem 1rem;
      /* font-size: 90%; */
    }

    .decade1990 {
      /* background-color: #8e44ad; */
      background-color: #000;
      z-index: 40;
    }

    .decade2000 {
      /* background-color: #3498db; */
      background-color: #ffe4ad;
      z-index: 50;
    }

    .decade2010 {
      background-color: #2c3e50;
      z-index: 60;
    }

    .decade2020 {
      background-color: #000;
      z-index: 70;
    }

    .app-nav {
      /* display: none; */
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      position: fixed;
      /* width: 70px; */
      margin-right: 1rem;
      /* top: 0; */
      right: 0;
      bottom: 2rem;
      /* height: 100%; */
      z-index: 104;
    }

    .app-nav.active {}

    .app-nav ul {
      position: relative;
    }

    .app-nav li {
      display: block;
      margin-bottom: 1rem;
      margin-top: 1rem;
    }

    .app-nav li:first-child {
      margin-top: 0;
    }

    .app-nav li:last-child {
      margin-bottom: 0;
    }

    .app-nav .text-timeline {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      text-align: center;
      font-size: 1.5rem;
      color: rgba(255, 255, 255, .5);
    }

    .join-the-conversation {
      margin: 1rem auto;
      padding: 1.4rem 0 .5rem;
      border-top: 5px solid rgba(255, 255, 255, .5);
      border-bottom: 5px solid rgba(255, 255, 255, .5);
      color: rgba(255, 255, 255, .5);
    }

    .app-nav li a {
      display: block;
      height: 13px;
      width: 1px;
      border: none;
      /* background: rgba(255, 255, 255, .5); */
      color: rgba(255, 255, 255, .5);
      border-radius: 8px;
      margin: 0;
      position: relative;
      font-size: 1.1rem;
      line-height: 1.2;
      letter-spacing: .1rem;
    }

    .join-the-conversation a {
      color: rgba(255, 255, 255, .5);
    }

    .app-nav li a span {
      /* display: none; */
      position: absolute;
      font-weight: 900;
      /* left: -110px; */
      left: -90px;
      text-align: right;
      top: -2px;
      width: 100px;
    }

    .app-nav.nav-intro li a,
    .app-nav.nav-intro li.active a,
    .app-nav.nav-intro li a:hover,
    .app-nav.nav-intro li.active a:hover,
    .app-nav.nav-intro .text-timeline,
    .app-nav.nav-intro .join-the-conversation a {
      /* color: #d32531; */
      color: #d20208;

      /* border-color: #d32531 !important; */
      /* background-color: #d32531 !important; */
    }

    .app-nav.nav-intro .join-the-conversation {
      border-color: #000;
      color: #000;
    }

    .app-nav li.active a {
      color: #fff;
      border-color: #fff !important;
    }

    .app-nav li:hover a,
    .app-nav li.active a {
      /* border: 3px solid #FFF; */
      /* padding: 0; */
      /* background: transparent; */
      color: #fff;
      /* background: #fff; */
    }

    .app-nav li.active a span,
    .app-nav li:hover a span {
      /* top: -5px;
      left: -113px; */
    }

    .app-nav li:hover a span {
      display: block;
    }

    .charities .charity {
      border-top: 5px solid #000;
      border-bottom: 5px solid #000;
      height: 100%;
    }

    .l-page__mega {
      display: none;
    }

    .bs-popover-top .popover-arrow {
      border-top-color: #FFF;
    }

    h3.popover-header {
      font-family: Graphik, sans-serif;
      text-transform: uppercase;
      font-weight: 700;
      font-size: 14px;
      background: transparent;
      border-bottom: none;
      text-align: center;
      color: #4c4d4f;
    }

    .popover-body {
      font-size: 14px;
      text-align: center;
      color: #959393;
    }

    .decade .container .content-text .popover {
      margin-top: -7px !important;
      min-width: 400px;
      max-width: 400px;
      box-shadow: 0 0 10px #000;
    }

    .decade .container .content-text .highlight-box {
      background: #fff;
      /* border-radius: .5rem; */
      padding: 1rem;
      position: relative;
      margin-top: 1rem;
      color: #333;
      width: 90%;
      margin: auto;
    }

    .popover-plus {
      /* cursor: pointer; */
      font-size: 1rem;
      padding: 4px 0;
      display: inline-block;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      text-align: center;
      color: #fff;
      background: #d32531;
      /* margin: 1rem auto; */
      position: absolute;
      left: -1rem;
      top: -1rem;
    }

    .decade .container .flex-reverse .popover-plus {
      right: -1rem;
      left: auto;
    }

    .pulse {
      /* position: relative; */
    }

    .pulse:after {
      content: '';
      border: 10px solid rgba(255, 255, 255, 0.7);
      background: transparent;
      border-radius: 50%;
      height: 200%;
      width: 200%;
      animation: pulse 3s ease-out infinite;
      position: absolute;
      top: -50%;
      left: -50%;
      z-index: -1;
      opacity: 0;
    }

    @keyframes pulse {
      0% {
        transform: scale(0);
        opacity: 0.0;
      }

      25% {
        transform: scale(0.25);
        opacity: 0.25;
      }

      50% {
        transform: scale(0.5);
        opacity: 0.5;
      }

      75% {
        transform: scale(0.75);
        opacity: 0.75;
      }

      100% {
        transform: scale(1);
        opacity: 0.0;
      }
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

    .logo img {
      max-height: 45vh;
    }

    @media only screen and (max-width: 768px) {
      .decade .container .content-wrap {
        flex-direction: column !important;
      }

      .decade .container .inner {
        padding: 1rem;
        width: 100%;
        max-width: 100%;
        margin: auto;
        /* transform: none;
        top: 0;
        left: 0;
        position: relative;
        padding: 0 2rem 2rem 2rem; */
      }

      .decade .container h2.title {
        margin: 1rem auto 2rem;
      }

      .intro-text {
        max-width: 90% !important;
      }

      #intro-dm-player {
        height: 250px;
        width: 100%;
        position: relative;
        transform: none;
        top: 0;
        left: 0;
      }

      .decade-video {
        height: auto !important;
        min-height: 300px !important;
      }
    }

    .bounce {
      animation-iteration-count: infinite;
      animation-duration: 1.5s;
      animation: bounce 3.6s ease infinite;
      transform-origin: 50% 50%;
    }

    @keyframes bounce {
      0% {
        transform: translateY(0);
      }

      5.55556% {
        transform: translateY(0);
      }

      11.11111% {
        transform: translateY(0);
      }

      22.22222% {
        transform: translateY(-15px);
      }

      27.77778% {
        transform: translateY(0);
      }

      33.33333% {
        transform: translateY(-15px);
      }

      44.44444% {
        transform: translateY(0);
      }

      100% {
        transform: translateY(0);
      }
    }

    .no-js .app-nav {
      /* display: none; */
    }

    @media only screen and (max-width: 1200px) {
      .decade .container .inner {
        max-width: 1000px;
      }
    }

    /* @media only screen and (max-width: 768px) { */
    @media only screen and (max-width: 1024px) {

      .hide-m {
        display: none !important;
        ;
      }

      .scroll-arrow {
        /* display: none; */
      }

      .decade .container .content-text {
        padding-left: 2rem !important;
        padding-right: 0 !important;
      }

      .decade .container .flex-reverse .content-text {
        padding-left: 0 !important;
        padding-right: 2rem !important;
      }

      .app-nav {
        position: fixed;
        top: 24px;
        align-items: start;
        width: 100%;
        margin: 0 auto;
        padding: 1rem .5rem;
        background: rgba(0, 0, 0, .7);
        height: 40px;
      }

      .app-nav ul {
        display: flex;
        justify-content: space-between;
        width: 100%;
        padding: 0;
        margin: 0;
        align-items: center;
      }

      .app-nav li {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
      }
      .app-nav li a {
        font-size: .85rem;
      }

      .app-nav li:first-child {
        margin-top: 0;
      }

      .app-nav li:last-child {
        margin-bottom: 0;
      }

      .app-nav li a,
      .app-nav.nav-intro li a {
        color: #fff !important;
        width: auto;
        background-color: transparent !important;
        border-color: transparent !important;
      }

      .app-nav li a span {
        width: auto;
        position: relative;
        left: auto;
      }
    }

    @media only screen and (max-width: 576px) {

      .decade .container .content-text,
      .decade .container .flex-reverse .content-text {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        padding-top: 2rem;
      }

      .decade .container .content-text .popover {
        min-width: 300px;
        max-width: 300px;
      }

      .decade .container h2.title {
        margin: 2rem 1rem 1rem;
      }

      .charities .charity {
        border: none;
        height: auto;
      }



      /* .decade .container .content-img {
        margin-top: 2.5rem;
      }

      .decade .container h2.title {
        width: 100%;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
      } */
    }

    #onesignal-slidedown-container,
    #onesignal-bell-container {
      display: none;
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

      <header class="<?php echo esc_attr($list_page_active); ?>">

        <!--. l-header__wrap -->


      </header><!-- .l-header -->

    </div>