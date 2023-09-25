<?php

/**
 * Template Name: Subscribe Thank you
 */
/* require_once(  ABSPATH . 'sso-sp/simplesaml/lib/_autoload.php');
$auth = new SimpleSAML_Auth_Simple('default-sp');

if( $auth->isAuthenticated() && ! is_user_logged_in() ) {
  $sso_user = $auth->getAttributes();
  $user = get_user_by( 'email', $sso_user['mail'][0] );
  if ( ! $user ) :
    $user_id = wp_insert_user(array(
      'user_login' => $sso_user['mail'][0],
      'user_pass' => NULL,
      'user_email' => $sso_user['mail'][0],
      'first_name' => $sso_user['first_name'][0],
      'last_name' => $sso_user['last_name'][0],
      'user_registered' => date('Y-m-d H:i:s'),
      'role' => 'subscriber'
    )
  );
  else :
    $user_id = $user->ID;
  endif; // User not found using SSO email

  $current_user = wp_set_current_user( $user_id );
  wp_set_auth_cookie( $user_id );

  wp_redirect( home_url( '/thank-you-for-subscription/' ) );

} elseif ( isset( $_GET['ss'] ) || isset( $_GET['oc'] ) ) {
  $auth->requireAuth([
    'ReturnTo' => home_url( '/thank-you-for-subscription/' ),
    'KeepPost' => FALSE,
  ]);
  \SimpleSAML\Session::getSessionFromRequest()->cleanup();
} */

get_header();
?>
<div class="l-page__content">
  <div class="l-section l-section--standard-template l-section--no-separator">
    <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">
      <?php

      if (have_posts()) :
        while (have_posts()) :
          the_post();
      ?>

          <h1><?php the_title(); ?></h1>

          <p>Congratulations, thanks for joining the Rolling Stone Australia family and supporting long form music journalism.</p>

          <?php if (is_user_logged_in()) : ?>

            <p>While we work hard on delivering you the best Rolling Stone magazine possible, check out our newsletter options below. Rolling Stone digital articles feature across The Brag Observer platform (below) which curates newsletters from Rolling Stone and our other titles, based on specific interests.</p>
            <p>Simply select your interests below and you'll start receiving breaking news and announcements about topics that you care about.</p>
            <div style="text-align: center;" class="mt-4">
              <h2>The Brag Observer Newsletters</h2>
              <p>Subscribe to newsletters using the check boxes</p>
            </div>

          <?php else : // User is not logged in 
          ?>

            <p>While we work hard on delivering you the best Rolling Stone magazine possible, check out our newsletter options below. Rolling Stone digital articles feature across The Brag Observer platform (below) which curates newsletters from Rolling Stone and our other titles, based on specific interests.</p>
            <div style="text-align: center;" class="mt-4">
              <h2>The Brag Observer Newsletters</h2>
            </div>

          <?php endif; // If user is logged in 
          ?>

      <?php
        endwhile;
      endif;
      ?>

      <?php
      $brag_api_url_base = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) ? 'https://the-brag.com/' : 'https://thebrag.com/';

      if (is_user_logged_in()) :
        $current_user = wp_get_current_user();

        $brag_api_url = $brag_api_url_base . 'wp-json/brag_observer/v1/get_topics/?key=1fc08f46-3537-43f6-b5c1-c68704acf3fa&email=' . $current_user->user_email;

        $response = wp_remote_get($brag_api_url, ['sslverify' => !in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])]);

        $responseBody = wp_remote_retrieve_body($response);
        $resonseJson = json_decode($responseBody);
        $lists = $resonseJson->data;
        if ($lists && is_array($lists)) :
      ?>
          <div class="row align-items-stretch">
            <?php
            foreach ($lists as $list) :

              $list_image_url = $list->image_url;
              if (!$list->image_url || '' == $list->image_url) :
                $list_image_url = 'https://dummyimage.com/800x800/333/fff.jpg&text=' . $list->title;
              endif; // If image url is not set
            ?>
              <div class="col-lg-3 col-md-3 col-6 my-4 px-2 topic">
                <label class="text-center d-flex flex-column justify-content-between h-100" style="border: 1px solid #ccc; padding: .5rem; cursor: pointer;">
                  <div class="list-info">
                    <figure class="img-wrap rounded-circle2">
                      <img alt="" src="<?php echo $list_image_url; ?>" class="rounded-circle2" width="">
                      <div class="tags text-center text-white bg-danger text-uppercase"><?php echo $list->frequency ?: 'Breaking News'; ?></div>
                    </figure>
                    <h3><?php
                        echo !in_array($list->id, [4,]) ? trim(str_ireplace('Observer', '', $list->title)) : trim($list->title);
                        ?></h3>
                    <div><?php echo wpautop($list->description); ?></div>
                  </div>
                  <div class="list-subscription-action">
                    <input type="checkbox" name="lists[]" class="checkbox-list" id="lists_<?php echo $list->id; ?>" value="<?php echo $list->id; ?>" <?php echo $list->subscribed ? 'checked' : ''; ?>>
                    <label for="lists_<?php echo $list->id; ?>"></label>
                    <div class="loading" style="display: none;">
                      <div class="spinner">
                        <div class="double-bounce1"></div>
                        <div class="double-bounce2"></div>
                      </div>
                    </div>
                  </div>
                </label>
              </div>
            <?php
            endforeach; // For Each $lists
            ?>
          </div>
        <?php
        endif; // If $lists and is array

      else :
        $brag_api_url = $brag_api_url_base . 'wp-json/brag_observer/v1/get_topics/?key=1fc08f46-3537-43f6-b5c1-c68704acf3fa';

        $response = wp_remote_get($brag_api_url, ['sslverify' => !in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])]);

        $responseBody = wp_remote_retrieve_body($response);
        $resonseJson = json_decode($responseBody);
        $lists = $resonseJson->data;
        if ($lists && is_array($lists)) :
        ?>
          <div class="row align-items-stretch">
            <?php
            foreach ($lists as $list) :

              $list_image_url = $list->image_url;
              if (!$list->image_url || '' == $list->image_url) :
                $list_image_url = 'https://dummyimage.com/800x800/333/fff.jpg&text=' . $list->title;
              endif; // If image url is not set
            ?>
              <div class="col-lg-3 col-md-3 col-6 my-4 px-2 topic">
                <a href="<?php echo $list->link; ?>" target="_blank" class="text-center d-flex flex-column justify-content-between h-100 text-dark" style="border: 1px solid #ccc; padding: .5rem; cursor: pointer; text-decoration: none;">
                  <div class="list-info">
                    <figure class="img-wrap rounded-circle2">
                      <img alt="" src="<?php echo $list_image_url; ?>" class="rounded-circle2" width="">
                      <div class="tags text-center text-white bg-danger text-uppercase"><?php echo $list->frequency ?: 'Breaking News'; ?></div>
                    </figure>
                    <h3><?php
                        echo !in_array($list->id, [4,]) ? trim(str_ireplace('Observer', '', $list->title)) : trim($list->title);
                        ?></h3>
                    <div><?php echo wpautop($list->description); ?></div>
                  </div>
                </a>
              </div>
            <?php
            endforeach; // For Each $lists
            ?>
          </div>
        <?php
        endif; // If $lists and is array
        ?>
      <?php endif; // If user is logged in 
      ?>
    </div><!-- /.c-content t-copy -->
  </div><!-- /.l-section -->

  <?php get_template_part('template-parts/footer/footer'); ?>
</div><!-- .l-page__content -->

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
<style>
  a:hover {
    text-decoration: none;
  }

  .l-header__wrap--subscribe {
    z-index: 1;
  }

  .c-hamburger {
    margin-top: -1rem;
  }

  .is-header-sticky .c-hamburger {
    margin-top: -0.5rem;
  }

  .l-header__block--read-next {
    margin-top: -0.25rem;
  }

  .c-content p {
    margin-bottom: 0;
  }

  .topic .tags {
    font-size: .75rem;
    /* max-width: 5rem; */
    position: absolute;
    right: 0;
    top: 0;
    padding: .25rem .5rem;
  }

  .topic-inner {
    border: 1px solid #ccc;
    padding: .5rem;
  }

  .topic h3 {
    font-size: 1.3rem;
  }

  .topic figure.img-wrap {
    /* width: 100px;
    height: 100px; */
    margin-left: auto;
    margin-right: auto;
    position: relative;
    overflow: hidden;
  }

  .topic figure.img-wrap img {
    /* height: 100%;
    width: auto;
    max-width: none;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); */
  }

  .checkbox-list:not(:checked),
  .checkbox-list:checked {
    position: absolute;
    left: -9999px;
  }

  .checkbox-list:not(:checked)+label,
  .checkbox-list:checked+label {
    position: relative;
    /* padding-left: 1.95rem; */
    cursor: pointer;
    width: 2rem;
    height: 2rem;
    margin: 2rem auto -1rem;
  }

  /* checkbox aspect */
  .checkbox-list:not(:checked)+label:before,
  .checkbox-list:checked+label:before {
    content: '';
    position: absolute;
    left: 0;
    top: -50%;
    width: 2rem;
    height: 2rem;
    border: 1px solid #ccc;
    background: #fff;
    border-radius: 4px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, .1);
    padding: .5rem;
  }

  /* checked mark aspect */
  .checkbox-list:not(:checked)+label:after,
  .checkbox-list:checked+label:after {
    content: '\2713\0020';
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%) scale(1.5);
    max-width: 100%;
    font-size: 1.25rem;
    line-height: 0;
    color: #000;
    transition: all .2s;
    font-family: 'Lucida Sans Unicode', 'Arial Unicode MS', Arial;
  }

  /* checked mark aspect changes */
  .checkbox-list:not(:checked)+label:after {
    opacity: 0;
    transform: scale(0);
  }

  .checkbox-list:checked+label:after {
    opacity: 1;
    /* transform: scale(1.6); */
  }

  /* disabled checkbox */
  .checkbox-list:disabled:not(:checked)+label:before,
  .checkbox-list:disabled:checked+label:before {
    box-shadow: none;
    border-color: #bbb;
    background-color: #ddd;
  }

  .checkbox-list:disabled:checked+label:after {
    color: #999;
  }

  .checkbox-list:disabled+label {
    color: #aaa;
  }
</style>

<script>
  jQuery(document).ready(function($) {
    <?php if (is_user_logged_in()) : ?>
      if ($('.checkbox-list').length) {
        $('.checkbox-list').on('change', function() {

          if ($(this).prop('checked')) {
            var status = 'subscribed';
          } else {
            var status = 'unsubscribed';
          }
          var checkbox = $(this);
          checkbox.next('label').addClass('d-none');
          checkbox.parent().find('.loading').show();
          checkbox.prop('disabled', true);
          var data = {
            action: 'subscribe_observer',
            formData: 'list=' + $(this).val() + '&status=' + status
          };
          $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(res) {
            checkbox.next('label').removeClass('d-none');
            checkbox.parent().find('.loading').hide();
            checkbox.prop('disabled', false);
          });
        });
      }
    <?php endif; // If logged in 
    ?>
  });
</script>

<?php
get_footer();
