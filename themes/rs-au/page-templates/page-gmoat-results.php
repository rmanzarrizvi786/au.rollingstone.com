<?php

/**
 * Template Name: GMOAT - Results
 */

get_template_part('page-templates/gmoat-top-100/header-results');

$paged = get_query_var('paged', 1);
if ($paged == 0)
  $paged = 1;
$per_page = 15;

$total = 100;

$release_date = new DateTime('2020-11-01');
$today = new DateTime();
$diff_days = $today->diff($release_date)->days;

$release_upto = 100 - $diff_days * $per_page - $per_page + 1;

if ($release_upto <= 1) {
  $release_upto = 1;
}

$release_upto = 1;


$no_pages = ceil(($total - $release_upto) / $per_page);

?>

<?php
wp_enqueue_style('list', RS_THEME_URL . '/assets/build/css/list.css', [], RS_THEME_VERSION, 'all');
wp_enqueue_script('vote-gmoat', get_template_directory_uri() . '/page-templates/gmoat-top-100/js/scripts-results.js', ['jquery'], time(), true);
?>

<link rel="stylesheet" id="jq-ui-css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" type="text/css" media="all" />
<?php
add_action('wp_footer', function () {
?>
  <script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    jQuery(document).ready(function($) {

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

    if (!post_password_required($post)) :
  ?>
      <div class="l-page__content">
        <div class="logo-wrap">
          <div class="l-section l-section--no-separator">
            <figure class="c-picture2" style="margin: auto;">
              <div class="c-picture__frame2">

                <div class="c-crop2 c-crop--ratio-3x2-2 logo">
                  <img src="<?php echo RS_THEME_URL . '/images/vote-100-movies/logo-v1.png'; ?>" style="width: 60%; max-width: 100%;">
                  <div style="font-family: Graphik,sans-serif; margin-top: 2rem; font-size: 175%; line-height: 130%;">
                    Never Lose Access to Your Favourite Movies with 4K UHD, Blu-ray & DVD
                  </div>
                </div><!-- .c-crop -->
              </div>

            </figure><!-- .c-picture -->
          </div>
        </div>

        <div class="l-section l-section--no-separator" style="margin: 2rem auto; max-width: 50rem;">
          <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">

            <div class="gmoat-wrap">

              <h1 style="line-height: 2.5rem;"><?php the_title(); ?></h1>

              <p>It feels almost impossible to be able to pick the greatest film, which is exactly why Rolling Stone called upon you, the readers, to share your valuable opinions on these masterpieces of the cinematic world. Having tabulated over 35,000 votes in the process, we’ve now been left with a list of films that are so great that they could be used as inspiration for generations of filmmakers to come.</p>
              <p>There’s bound to be some old favourites, long-forgotten gems, and even some new movies you’re bound to discover for the first time. So, dim the lights, put your feet up, grab some popcorn, and enjoy Rolling Stone’s list of the 100 greatest movies of all time.</p>

              <nav class="l-header__block l-header__block--list-nav" style="position: sticky; background: white; z-index: 30001; padding-top: .5rem; padding-bottom: .5rem;">
                <ul class="l-header__menu l-header__menu--list t-semibold t-semibold--upper" data-list-nav>

                  <?php
                  // for ($page = 1; $page <= $no_pages; $page++) :
                  $page = 0;
                  for ($i = $total; $i >= 1; $i -= $per_page) :
                    $page++;
                    // if ($page == 6) {
                    //   $per_page = 25;
                    // }
                    $range_start = $i;
                    $range_end = $i - $per_page + 1;
                    if ($range_end <= 1)
                      $range_end = 1;
                  ?>
                    <li class="l-header__menu-item">
                      <?php // if ($range_start > $release_upto) : 
                      ?>
                      <a href="<?php echo get_permalink(); ?>page/<?php echo $page; ?>/" class="l-header__menu-link" data-list-nav-item data-list-range-start="<?php echo $range_start; ?>" data-list-range-end="<?php echo $range_end; ?>">
                        <?php echo $range_start; ?>-<?php echo $range_end; ?>
                      </a>
                      <?php if (0) : //  else : 
                      ?>
                        <span class="l-header__menu-link" data-list-nav-item data-list-range-start="<?php echo $range_start; ?>" data-list-range-end="<?php echo $range_end; ?>" style="color: #ccc;">
                          <?php echo $range_start; ?>-<?php echo $range_end; ?>
                        </span>
                      <?php endif; ?>
                    </li><!-- .l-header__menu-item -->
                    <?php //endfor; 
                    ?>
                  <?php
                  endfor;
                  $per_page = 15;
                  ?>

                </ul>
                <div class="l-header__progress-bar" data-list-progress-bar></div>
              </nav>

              <div class="c-list c-list--albums">
                <?php
                $start = 100 - ($paged - 1) * $per_page;
                $to = $start - $per_page;
                if ($to <= 0)
                  $to = 0;
                // if ( $paged == 6 )
                //   $to = 0;
                // if ($to < $release_upto)
                //   $to = $release_upto - 1;
                $query = "SELECT * FROM {$wpdb->prefix}gmoat_final WHERE position <= {$start} AND position > {$to} ORDER BY position DESC ";
                if ($start < $release_upto) :
                  $will_be_released = $release_date->modify(($paged) . ' days');
                ?>
                  <article class="c-list__item" id="list-item-<?php echo $start; ?>" data-list-item="<?php echo $start; ?>" data-list-title="Movie <?php echo $start; ?>" data-list-item-id="<?php echo $start; ?>" data-list-item-authors="adm_rs" style="margin: 0; border: none;">
                    <div style="margin: 1rem auto; text-align: center; font-size: 120%; background: #f3f3f3; padding: 1rem;">
                      <?php if ($to > 11) : ?>
                        Numbers <?php echo $start . '-' . ($to + 1); ?> will be announced <?php echo $will_be_released->format('l'); ?> the <?php echo $will_be_released->format('jS'); ?>
                      <?php else : ?>
                        Top 25 will be announced Monday the 9th
                      <?php endif; ?>
                    </div>
                  </article>
                  <?php
                else :
                  // echo $query;
                  $movies = $wpdb->get_results($query);

                  if ($movies) :
                    foreach ($movies as $movie) : ?>
                      <article class="c-list__item" id="list-item-<?php echo $movie->position; ?>" data-list-item="<?php echo $movie->position; ?>" data-list-title="Movie <?php echo $movie->position; ?>" data-list-permalink="<?php echo get_permalink(); ?>page/<?php echo $paged; ?>/<?php echo $movie->slug; ?>/" data-list-item-id="<?php echo $movie->position; ?>" data-list-item-authors="adm_rs">

                      <?php if ( $movie->position <= 10 ) : ?>
                        <figure class="c-list__picture" style="float: none; margin: auto; width: 100%;">
                          <div class="c-crop c-crop--ratio-x">
                      <?php else : ?>
                        <figure class="c-list__picture">
                          <div class="c-crop c-crop--ratio-1x1">
                      <?php endif; ?>
                            <img width="900" src="<?php echo $movie->image_url; ?>" data-src="<?php echo $movie->image_url; ?>" class="c-crop__img wp-post-image visible" alt="<?php echo $movie->title; ?>">
                          </div><!-- .c-crop -->
                        </figure><!-- /.c-list__picture -->

                        <header class="c-list__header">
                          <span class="c-list__number t-bold"><?php echo $movie->position; ?></span>

                          <h3 class="c-list__title t-bold"><?php echo stripslashes($movie->title); ?></h3><!-- /.c-list__title -->
                        </header><!-- /.c-list__header -->

                        <main class="c-list__main">
                          <div class="c-list__lead c-content">
                            <?php echo wpautop(stripslashes($movie->description)); ?>
                          </div><!-- /.c-list__lead -->

                          <?php if (
                            (!is_null($movie->link_jbhifi_bluray) && '' != trim($movie->link_jbhifi_bluray))
                            ||
                            (!is_null($movie->link_jbhifi_dvd) && '' != trim($movie->link_jbhifi_dvd))
                            ||
                            (!is_null($movie->link_jbhifi_4k) && '' != trim($movie->link_jbhifi_4k))
                          ) :
                          ?>
                            <div class="buy-movie-links" style="justify-content: start; margin-top: 0; margin-bottom: .5rem;">
                              <div style="display: flex; align-items: center;"><img src="<?php echo RS_THEME_URL . '/images/vote-100-movies/jb.jpg'; ?>" style="width: 100px;"></div>

                              <?php if (!is_null($movie->link_jbhifi_bluray) && '' != trim($movie->link_jbhifi_bluray)) : ?>
                                <a href="<?php echo $movie->link_jbhifi_bluray; ?>" target="_blank" class="button" style="padding: .15rem .25rem; margin: auto .15rem;">Blu ray</a>
                              <?php endif; ?>

                              <?php if (!is_null($movie->link_jbhifi_dvd) && '' != trim($movie->link_jbhifi_dvd)) : ?>
                                <a href="<?php echo $movie->link_jbhifi_dvd; ?>" target="_blank" class="button" style="padding: .15rem .25rem; margin: auto .15rem;">DVD</a>
                              <?php endif; ?>

                              <?php if (!is_null($movie->link_jbhifi_4k) && '' != trim($movie->link_jbhifi_4k)) : ?>
                                <a href="<?php echo $movie->link_jbhifi_4k; ?>" target="_blank" class="button" style="padding: .15rem .25rem; margin: auto .15rem;">4K</a>
                              <?php endif; ?>
                            </div>
                          <?php endif; ?>

                          <?php if (
                            (!is_null($movie->link_amazon_bluray) && '' != trim($movie->link_amazon_bluray))
                            ||
                            (!is_null($movie->link_amazon_dvd) && '' != trim($movie->link_amazon_dvd))
                            ||
                            (!is_null($movie->link_amazon_4k) && '' != trim($movie->link_amazon_4k))
                          ) :
                          ?>
                            <div class="buy-movie-links" style="justify-content: start; margin-top: 0; margin-bottom: .5rem;">
                              <div style="display: flex; align-items: center;"><img src="<?php echo RS_THEME_URL . '/images/vote-100-movies/amazon.jpg'; ?>" style="width: 100px;"></div>

                              <?php if (!is_null($movie->link_amazon_bluray) && '' != trim($movie->link_amazon_bluray)) : ?>
                                <a href="<?php echo $movie->link_amazon_bluray; ?>" target="_blank" class="button" style="padding: .15rem .25rem; margin: auto .15rem;">Blu ray</a>
                              <?php endif; ?>

                              <?php if (!is_null($movie->link_amazon_dvd) && '' != trim($movie->link_amazon_dvd)) : ?>
                                <a href="<?php echo $movie->link_amazon_dvd; ?>" target="_blank" class="button" style="padding: .15rem .25rem; margin: auto .15rem;">DVD</a>
                              <?php endif; ?>

                              <?php if (!is_null($movie->link_amazon_4k) && '' != trim($movie->link_amazon_4k)) : ?>
                                <a href="<?php echo $movie->link_amazon_4k; ?>" target="_blank" class="button" style="padding: .15rem .25rem; margin: auto .15rem;">4K</a>
                              <?php endif; ?>
                            </div>
                          <?php endif; ?>

                          <?php if (
                            (!is_null($movie->link_sanity_bluray) && '' != trim($movie->link_sanity_bluray))
                            ||
                            (!is_null($movie->link_sanity_dvd) && '' != trim($movie->link_sanity_dvd))
                            ||
                            (!is_null($movie->link_sanity_4k) && '' != trim($movie->link_sanity_4k))
                          ) :
                          ?>
                            <div class="buy-movie-links" style="justify-content: start; margin-top: 0; margin-bottom: .5rem;">
                              <div style="display: flex; align-items: center;"><img src="<?php echo RS_THEME_URL . '/images/vote-100-movies/sanity.jpg'; ?>" style="width: 100px;"></div>

                              <?php if (!is_null($movie->link_sanity_bluray) && '' != trim($movie->link_sanity_bluray)) : ?>
                                <a href="<?php echo $movie->link_sanity_bluray; ?>" target="_blank" class="button" style="padding: .15rem .25rem; margin: auto .15rem;">Blu ray</a>
                              <?php endif; ?>

                              <?php if (!is_null($movie->link_sanity_dvd) && '' != trim($movie->link_sanity_dvd)) : ?>
                                <a href="<?php echo $movie->link_sanity_dvd; ?>" target="_blank" class="button" style="padding: .15rem .25rem; margin: auto .15rem;">DVD</a>
                              <?php endif; ?>

                              <?php if (!is_null($movie->link_sanity_4k) && '' != trim($movie->link_sanity_4k)) : ?>
                                <a href="<?php echo $movie->link_sanity_4k; ?>" target="_blank" class="button" style="padding: .15rem .25rem; margin: auto .15rem;">4K</a>
                              <?php endif; ?>
                            </div>
                          <?php endif; ?>

                        </main><!-- /.c-list__main -->
                      </article>


                <?php endforeach; // For Each $movie in $movies
                  endif; // If $movies 
                endif; // If $to < $release_upto
                ?>
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
