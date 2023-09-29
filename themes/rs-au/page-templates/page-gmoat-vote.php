<?php

/**
 * Template Name: GMOAT - Vote 100
 */

get_template_part('page-templates/gmoat-top-100/header');

$voting_active = time() < strtotime('2020-10-31');
?>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
// wp_enqueue_style( 'select2' , 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css', [], NULL, 'all' );
// wp_enqueue_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js' );

// wp_enqueue_script( 'jquery-autocomplete', get_template_directory_uri() . '/page-templates/gmoat-top-100/js/jquery.auto-complete.min.js', [ 'jquery' ], '1.0', true );
// wp_enqueue_script( 'jquery-ui-autocomplete', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', [ 'jquery' ], '1.0', true );
wp_enqueue_script('vote-gmoat', get_template_directory_uri() . '/page-templates/gmoat-top-100/js/scripts.js', ['jquery'], time(), true);
?>

<link rel="stylesheet" id="jq-ui-css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" type="text/css" media="all" />
<?php
add_action('wp_footer', function () {
?>
  <script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    jQuery(document).ready(function($) {
      <?php if (0) : ?>
        /*
  $('.select-movies').select2(
    {
      maximumSelectionLength: 1,
      ajax: {
        delay: 500, // wait x milliseconds before triggering the request
        url: ajaxurl,
        data: function (params) {
          var query = {
            search: params.term,
            excludeMovies: excludeMovies,
            action: 'search_movie'
          }
          return query;
        },
        processResults: function (res) {
          return {
            results: res.data
          };
        }
      },
      placeholder: 'Search for a movie',
      allowClear: true,
      minimumInputLength: 1,
      templateResult: movieTemplateResult,
      templateSelection: function(movie){
        return movie.title;
      },
      closeOnSelect: true
    }
  ); // Select2 : .select-movies

  $('.select-movies').on('change', function() {
    excludeMovies = [];
    for( var i = 1; i <= 3; i++ ) {
      var m = $('#select-movies' + i).val();
      if ( $.trim( m ) != '' )
        excludeMovies.push( m );
    }
    if( $.trim( $('#user_entry_movie').val() ) != '0' ) {
      excludeMovies.push( $('#user_entry_movie').val() );
    }
  });

  // on first focus (bubbles up to document), open the menu
$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
  $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});

$('select.select-movies').on('select2:open', function (e) {
  $(document.activeElement).blur();
  $('.select2-search input').focus();
});

// steal focus during close - only capture once and stop propogation
$('select.select-movies').on('select2:closing', function (e) {
  $(e.target).data("select2").$selection.one('focus focusin', function (e) {
    e.stopPropagation();
  });
});


  function movieTemplateResult(movie) {
    if (movie.loading) {
      return movie.title;
    }
    var $container = $(
      "<div class='select2-result-movie clearfix'>" +
        "<div class='select2-result-movie__meta'>" +
          "<div class='select2-result-movie__title' style='font-weight: bold;'></div>" +
        "</div>" +
      "</div>"
    );

    $container.find(".select2-result-movie__title").text(movie.title);

    return $container;
  }
  $('.user_entry_toggler').on('click', function(e) {
    e.preventDefault();
    $('#user_entry_manual_wrap,#user_entry_auto_wrap').toggle();
    if ( $('#user_entry_manual_wrap').is(':visible') ) {
      $('#user_entry_manual_wrap').find('input').focus();
      $('#user_entry_auto_wrap').find('select').val(null).trigger('change');
    } else if ( $('#user_entry_auto_wrap').is(':visible') ) {
      $('#user_entry_manual_wrap').find('input').val('');
    }
  });
*/
      <?php endif; ?>
    });
  </script>

  <noscript>
    <style>
      .js-slideshow {
        display: flex !important;
      }

      #gmoat-form .tab {
        display: block !important;
      }
    </style>
  </noscript>

  <?php
}); // wp_footer

if (have_posts()) :
  while (have_posts()) :
    the_post();

    if (time() >= strtotime('2020-10-19') || !post_password_required($post)) :
  ?>
      <div class="l-page__content">
        <div class="logo-wrap">
          <div class="l-section l-section--no-separator">
            <figure class="c-picture2" style="margin: auto;">
              <div class="c-picture__frame2">

                <div class="c-crop2 c-crop--ratio-3x2-2 logo">
                  <img src="<?php echo RS_THEME_URL . '/images/vote-100-movies/logo-v1.png'; ?>" style="width: 60%; max-width: 100%;">
                  <div style="font-family: Graphik,sans-serif; margin-top: 2rem; font-size: 175%;">
                    Vote for you favourite movies now!
                  </div>
                </div><!-- .c-crop -->
              </div>

            </figure><!-- .c-picture -->
          </div>
        </div>

        <div class="slideshow-wrap" id="slideshow-wrap1">
          <div class="slideshow js-slideshow" id="js-slideshow1" style="display: none;">
            <?php for ($i = 0; $i < 5; $i++) : ?>
              <div class="slide"><img src="<?php echo RS_THEME_URL ?>/images/vote-100-movies/movies-top-v2.jpg" /></div>
              <div class="slide"><img src="<?php echo RS_THEME_URL ?>/images/vote-100-movies/movies-bottom-v2.jpg" /></div>
            <?php endfor; ?>
          </div>
        </div>

        <div class="l-section l-section--no-separator" style="margin: 2rem auto; max-width: 50rem;">
          <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">

            <div class="gmoat-wrap">

              <h1 style="line-height: 2.5rem;"><?php the_title(); ?></h1>

              <p><em>Rolling Stone Australia</em> is searching for the 100 Greatest Movies of All Time and we need your help! Whether it’s a critically-acclaimed gem or an overlooked classic, anything is eligible for nomination.</p>
              <p>Powered by <a href="https://www.aheda.com.au/" target="_blank">AHEDA</a>, voting runs until Oct 30, with readers able to vote for their top three movies, plus one bonus &quot;Wildcard&quot; movie that allows you to share your ultimate guilty-pleasure movie. By entering the Wildcard round, you&#39;re in the running with a chance to win a Sony 65-inch 4K TV and 4K UHD Blu-ray player!</p>
              <p>The full countdown begins on Nov 2, so take a look back into your 4K, Blu-ray, DVD or VHS, cast your mind back to those seminal movie moments, pick out some of the greatest films of all time and vote below.</p>
              <?php if( $voting_active ) : ?>
              <p><strong>To enter for the chance to win a Sony 65-inch 4K TV and 4K UHD player valued at $2000 fill out the wildcard round which is unlocked by submitting your votes below.</strong></p>
              
              <div class="tab">
                <form action="#" method="post" id="gmoat-vote-form">
                  <div style="text-align: center;">
                    <h2 style="margin-top: 1rem;">Vote Here</h2>
                  </div>
                  <?php for ($i = 1; $i <= 3; $i++) : ?>
                    <div class="mt-1"><label>Movie <?php echo $i; ?>
                        <div class="select-movie-wrap">
                          <input type="text" class="select-movies" id="select-movies<?php echo $i; ?>" name="select-movies[]">
                          <div class="edit-select-movie hidden" style="position:absolute; left:0; right:0; height: 100%; bottom:0;"></div>
                          <div class="edit-select-movie hidden"><i class="fas fa-pencil-alt"></i></div>
                          <input type="hidden" class="select-movies-id" id="select-movies-id<?php echo $i; ?>" name="select-movies-id[]">
                        </div>
                      </label></div>
                  <?php endfor; ?>

                  <div class="d-flex flex-sm-column">
                    <div class="mt-1 pr-1 flex-fill">
                      <label>
                        Your name
                        <input type="text" name="user_fullname" required2>
                      </label>
                    </div>

                    <div class="mt-1 pl-1 flex-fill">
                      <label>
                        Your email
                        <input type="email" name="user_email" required2>
                      </label>
                    </div>
                  </div>

                  <div class="mt-1">
                    <div class="text-danger js-errors" style="display: none;"></div>
                  </div>

                  <div style="margin-top: 1rem; text-align :right;">
                    <button type="submit" class="btn-submit" id="btn-submit-vote" style="background: #000;">
                      <div class="spinner hidden"></div>
                      <span class="button-text d-flex" style="align-items: center;"><span style="margin-right: 0.5rem;">Vote</span> <i class="fa fa-chevron-right"></i></span>
                    </button>
                  </div>
                </form>
              </div>

              <div class="tab" id="gmoat-comp-form-wrap" style="display: none;">
                <!-- <div class="text-success js-success" style="display: none; text-align: center; font-size: 125%;"></div> -->
                <form action="#" method="post" id="gmoat-comp-form">
                  <div style="text-align: center;" id="enter-comp-heading">
                    <h2 style="margin-top: 1rem;">Enter Competition Here</h2>
                  </div>
                  <div class="text-success" id="js-success" style="display: none; text-align: center; font-size: 125%;"></div>
                  <div class="fields-wrap">
                    <input type="hidden" name="entry_id" id="entry_id" value="">
                    <div class="mt-1">
                      <label>
                        Your wildcard entry
                        <div class="select-movie-wrap">
                          <input type="text" class="select-movies" id="user_entry" name="user_entry">
                          <div class="edit-select-movie hidden" style="position:absolute; left:0; right:0; height: 100%; bottom:0;"></div>
                          <div class="edit-select-movie hidden"><i class="fas fa-pencil-alt"></i></div>
                          <input type="hidden" class="select-movies-id" id="user_entry_id" name="user_entry_id">
                        </div>
                      </label>
                    </div>


                    <div class="mt-1">
                      <label>
                        Tell us why you chose your wildcard vote in 25 words or less to enter to win a Sony 65-inch 4K TV and 4K UHD Blu-ray player
                        <textarea name="reason" id="reason" rows="2"></textarea>
                        <div style="display: none;"># of words <span id="reason_wordcount">0</span></div>
                      </label>
                    </div>

                    <div>
                      <div class="mt-1">
                        <label>
                          <input type="checkbox" name="consent_observer" value="yes" checked>
                          I agree to be signed up to The <a href="https://thebrag.com/observer/film-tv/" target="_blank">Film &amp; TV Observer</a> as part of the requirements for entry
                        </label>
                      </div>
                      <div class="mt-1">
                        <label>
                          <input type="checkbox" name="consent_aheda" value="yes" checked>
                          I agree to be signed up to AHEDA's mailing list as part of the requirements for entry
                        </label>
                      </div>
                    </div>

                    <div class="mt-1">
                      <div class="text-danger js-errors" style="display: none;"></div>
                    </div>

                    <div style="margin-top: 1rem; text-align :right;">
                      <button type="submit" class="btn-submit" id="btn-submit-comp">
                        <div class="spinner hidden"></div>
                        <span class="button-text">Submit</span>
                      </button>
                    </div>
                  </div>
                </form>
              </div>

              <div id="voted_movies_wrap" class="hidden">
                <div class="movie-reel-v"></div>
                <div style="border-top: 15px solid #000; border-bottom: 15px solid #000; padding: 0 15px;">
                  <div class="my-votes-container">
                    <div style="text-align: center; margin-bottom: 1rem;"><img src="<?php echo RS_THEME_URL . '/images/vote-100-movies/my-votes-top.jpg'; ?>"></div>
                    <ul id="voted_movies"></ul>
                    <div class="social-icons" style="margin-top: .5rem;">
                      <div style="margin-right: 1rem;">
                        <a class="btn-share btn-social-icon facebook-button" id="share_facebook" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode(get_the_permalink() . '&utm_source=share_facebook'); ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                      </div>
                      <div>
                        <a class="btn-share btn-social-icon twitter-button" id="share_twitter" href="https://twitter.com/intent/tweet?text=&url=<?php echo urlencode(get_the_permalink() . '&utm_source=share_twitter'); ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="movie-reel-v"></div>
              </div>

              <?php else : ?>
              <div style="width: 550px; max-width: 100%; text-align: center; margin: 1rem auto; font-size: 125%; background: #f3f3f3; border-radius: 20px; padding: 1rem .5rem; box-shadow: 0 0 10px #aaa;">
              <p>Voting has now closed for Rolling Stone Australia's 100 Greatest Movies of all Time readers poll.</p>
              <p>We'll start counting down the results on this page starting 9:00am  Monday November 2, stay tuned!</p>
              </div>

              <?php endif; // If $voting_active ?>


              <div>
                <a href="https://www.aheda.com.au/" target="_blank" style="text-align: center; margin: 2rem auto; font-weight: 600; box-shadow: 0 0 18px #aaa; border-radius: 1rem; padding: .5rem 1rem; background: #000; color: #fff; display: block;">
                  <p>The Greatest Movies of All Time is powered by the Australian Home Entertainment Distributors Association. Learn more <span style="color: #fff; text-decoration: underline;">here</span></p>
                  <p>Never Lose Access to your Favourite Movies<br>Buy them on 4K Ultra HD, Blu-ray and DVD today!</p>
                </a>
              </div>

            </div><!-- .gmoat-wrap -->
          </div><!-- /.c-content t-copy -->
        </div><!-- /.l-section -->

        <div class="slideshow-wrap" id="slideshow-wrap2" style="margin-top: 2rem;">
          <div class="slideshow js-slideshow" id="js-slideshow2" style="display: none;">
            <?php for ($i = 0; $i < 5; $i++) : ?>
              <div class="slide"><img src="<?php echo RS_THEME_URL ?>/images/vote-100-movies/movies-top-v2.jpg" /></div>
              <div class="slide"><img src="<?php echo RS_THEME_URL ?>/images/vote-100-movies/movies-bottom-v2.jpg" /></div>
            <?php endfor; ?>
          </div>
        </div>

        <?php if( $voting_active ) : ?>
        <div class="l-page__content" style="margin-top: 1rem;">
          <div class="l-section l-section--no-separator">
            <div class="c-content c-content--no-sidebar t-copy">
              <div class="t-c-wrap">
                <h4>Terms &amp; Conditions</h4>

                <table>
                  <tbody>
                    <tr>
                      <td>Competition</td>
                      <td>AHEDA x The Brag competition</td>
                    </tr>
                    <tr>
                      <td>Entry Period</td>
                      <td>Starts at 00:01 hours AEST / AEDT on 19th October 2020 and ends at [23:59 hours AEST / AEDT] on 30th October 2020</span></td>
                    </tr>
                    <tr>
                      <td>Entry</td>
                      <td>(a) Go to <a href="https://thebrag.com/observer/competitions/">https://thebrag.com/observer/competitions/</a> (&quot;Website&quot;);
                        <br>
                        (b) Fill in all the required data fields on the entry form for the Competition; and
                        <br>
                        (c) Vote for your top 3 movies of all time and answer the following question in 25 words or less: &quot;Why did you pick your number one choice?&quot;
                        <br>
                        (d) You need to agree to be signed up to The <a href="https://thebrag.com/observer/film-tv/" target="_blank">Film &amp; TV Observer</a> as part of the requirements for entry
                        <br>
                        (e) You need to agree to be signed up to AHEDA's mailing list as part of the requirements for entry
                      </td>
                    </tr>
                    <tr>
                      <td>Limit</td>
                      <td>Limited to one entry per person</td>
                    </tr>
                    <tr>
                      <td>Judging Criteria</td>
                      <td>Originality and creativity</td>
                    </tr>
                    <tr>
                      <td>Prize Determination</td>
                      <td>10:00 hours AEST / AEDT on 2nd November 2020 at The Brag Offices.</td>
                    </tr>
                    <tr>
                      <td>Notification</td>
                      <td>By email by 5th November 2020</td>
                    </tr>
                    <tr>
                      <td>Claim Period</td>
                      <td>Within 4 weeks&nbsp;from the date of Notification</td>
                    </tr>
                    <tr>
                      <td>Unclaimed Prize Determination</td>
                      <td>10:00 hours AEST]&nbsp;on 4th December at The Brag Offices
                        <br>
                        The winner will be notified by telephone and in writing via email or SMS (depending on the method of entry) within 5 working days of the Prize Determination.
                      </td>
                    </tr>
                    <tr>
                      <td>Prize</td>
                      <td>
                        (a) SONY 65-inch 4k television valued at $1600;<br>
                        (b) Blu-ray player valued at $400;
                      </td>
                    </tr>
                    <tr>
                      <td>Total Prize Value</td>
                      <td>$2000</td>
                    </tr>
                    <tr>
                      <td>Prize Conditions</td>
                      <td>N/A</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <?php endif; // If $voting_active ?>
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
