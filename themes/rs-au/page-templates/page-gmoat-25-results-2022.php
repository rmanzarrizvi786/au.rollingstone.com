<?php

/**
 * Template Name: GMOAT 25 of 2021 - Results
 */

get_template_part('page-templates/gmoat-top-25-2021/header');

$paged = get_query_var('paged', 1);
if ($paged == 0)
  $paged = 1;
$per_page = 5;

$total = 25;

date_default_timezone_set('Australia/Sydney');

$release_date = new DateTime('2022-01-27');
$today = new DateTime();
$diff_days = $today->diff($release_date)->days;

/* $release_upto = $total - $diff_days * $per_page - $per_page + 1;

if ($release_upto <= 1) {
  $release_upto = 1;
} */

#25-#21 on 27 Jan
#20-#16 live on 31 Jan
#15-#11 live on 2 Feb
#10-#6 live on 4 Feb
#Top 5 live pm 7 Feb

// $release_upto = 1;
if (time() >= strtotime('2022-02-07')) {
  $release_upto = 1;
} else if (time() >= strtotime('2022-02-04')) {
  $release_upto = 6;
} else if (time() >= strtotime('2022-02-02')) {
  $release_upto = 11;
} else if (time() >= strtotime('2022-01-31')) {
  $release_upto = 16;
} else {
  $release_upto = 21;
}

// $release_upto = 1;


$no_pages = ceil(($total - $release_upto) / $per_page);

$img_url = RS_THEME_URL . '/images/vote-25-movies-2021/';
?>

<?php
wp_enqueue_style('list', RS_THEME_URL . '/assets/build/css/list.css', [], RS_THEME_VERSION, 'all');
wp_enqueue_script('vote-gmoat', get_template_directory_uri() . '/page-templates/gmoat-top-25-2021/js/scripts-results.js', ['jquery'], time(), true);
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
                  <img src="<?php echo $img_url . 'AHEDATitle.png'; ?>" style="width: 460px; max-width: 100%;">
                </div><!-- .c-crop -->
              </div>

            </figure><!-- .c-picture -->
          </div>
        </div>

        <div class="l-section l-section--no-separator" style="margin: 2rem auto; max-width: 50rem;">
          <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">

            <div class="gmoat-wrap">

              <h1 style="line-height: 2.5rem;"><?php the_title(); ?></h1>

              <p>2021 ended up being an incredibly strong year for movies. So much so, that picking the best films of the year feels like an almost impossible task. This is exactly why Rolling Stone Australia called upon you, the readers, to share your valuable opinions on your favourite movies of 2021. Having tabulated over 10.000 votes in the process, we've now been left with a list of films that act as perfect time capsules of the year that was. If any of the titles slipped you by or if they demand a re-watch, they are all available now to either rent, buy or pre-order on disc or digital, so you can enjoy them all from the comfort of your home.</p>

              <nav class="l-header__block l-header__block--list-nav" style="position: sticky; background: white; z-index: 30001; padding-top: .5rem; padding-bottom: .5rem; height: 100%;">
                <ul class="l-header__menu l-header__menu--list t-semibold t-semibold--upper" data-list-nav>

                  <?php

                  $start = 25 - ($paged - 1) * $per_page;
                  $to = $start - $per_page;

                  // for ($page = 1; $page <= $no_pages; $page++) :
                  $page = 0;
                  for ($i = $total; $i >= 1; $i -= $per_page) :
                    $page++;
                    $range_start = $i;
                    $range_end = $i - $per_page + 1;
                    if ($range_end <= 1)
                      $range_end = 1;
                  ?>
                    <li class="l-header__menu-item">
                      <a href="<?php echo get_permalink(); ?>page/<?php echo $page; ?>/" class="l-header__menu-link <?php echo $range_start == $start ? 'highlight' : ''; ?>" data-list-nav-item data-list-range-start="<?php echo $range_start; ?>" data-list-range-end="<?php echo $range_end; ?>">
                        <?php echo $range_start; ?>-<?php echo $range_end; ?>
                      </a>
                    </li><!-- .l-header__menu-item -->
                    <?php //endfor; 
                    ?>
                  <?php
                  endfor;
                  $per_page = 5;
                  ?>

                </ul>
                <div class=" l-header__progress-bar" data-list-progress-bar>
                </div>
              </nav>

              <div class="c-list c-list--albums">
                <?php

                if ($to <= 0)
                  $to = 0;
                // if ( $paged == 6 )
                //   $to = 0;
                // if ($to < $release_upto)
                //   $to = $release_upto - 1;
                $query = "SELECT * FROM {$wpdb->prefix}gmoat_final_2021 WHERE position <= {$start} AND position > {$to} ORDER BY position DESC ";
                if ($start < $release_upto) :
                  // $will_be_released = $release_date->modify(($paged) . ' days');
                  switch ($paged):
                    case 2:
                      $will_be_released = $release_date->modify('4 days');
                      break;
                    case 3:
                      $will_be_released = $release_date->modify('6 days');
                      break;
                    case 4:
                      $will_be_released = $release_date->modify('8 days');
                      break;
                    case 5:
                      $will_be_released = $release_date->modify('11 days');
                      break;
                    default:
                      $will_be_released = $release_date;
                      break;
                  endswitch;
                ?>
                  <article class="c-list__item" id="list-item-<?php echo $start; ?>" data-list-item="<?php echo $start; ?>" data-list-title="Movie <?php echo $start; ?>" data-list-item-id="<?php echo $start; ?>" style="margin: 0 auto !important; border: none; padding: 0;">
                    <div style="margin: 1rem auto; text-align: center; font-size: 120%; background: #f3f3f3; padding: 1rem;">
                      <?php if ($to > 4) : ?>
                        Numbers <?php echo $start . '-' . ($to + 1); ?> will be announced <?php echo $will_be_released->format('l'); ?> the <?php echo $will_be_released->format('j\<\s\u\p\>S\<\/\s\u\p\> M'); ?>
                      <?php else : ?>
                        Top 5 will be announced <?php echo $will_be_released->format('l'); ?> the <?php echo $will_be_released->format('j\<\s\u\p\>S\<\/\s\u\p\> M'); ?>
                      <?php endif; ?>
                    </div>
                  </article>
                  <?php
                else :
                  // echo $query;
                  $movies = $wpdb->get_results($query);

                  if ($movies) :
                    foreach ($movies as $movie) :
                      $movie = stripslashes_deep($movie);
                  ?>
                      <article class="c-list__item" id="list-item-<?php echo $movie->position; ?>" data-list-item="<?php echo $movie->position; ?>" data-list-title="Movie <?php echo $movie->position; ?>" data-list-permalink="<?php echo get_permalink(); ?>page/<?php echo $paged; ?>/<?php echo $movie->slug; ?>/" data-list-item-id="<?php echo $movie->position; ?>" style="<?php echo $movie->position == $start ? 'margin-top: 0;' : ''; ?>">

                        <?php if ($movie->position <= 5) : ?>
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

                                <h3 class="c-list__title t-bold"><?php echo $movie->title; ?></h3><!-- /.c-list__title -->
                              </header><!-- /.c-list__header -->

                              <main class="c-list__main">
                                <div class="c-list__lead c-content">
                                  <?php echo wpautop($movie->description); ?>
                                </div><!-- /.c-list__lead -->

                                <?php if (
                                  (!is_null($movie->link_purchase) && '' != trim($movie->link_purchase))
                                ) :
                                ?>
                                  <div class="buy-movie-links" style="justify-content: start; margin-top: 0; margin-bottom: .5rem;">
                                    <a href="<?php echo $movie->link_purchase; ?>" target="_blank" class="btn btn-purchase" style="margin: auto .15rem;">Purchase Here</a>
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
            <?php for ($i = 0; $i < 4; $i++) : ?>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/Shang-Chi.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Raya_And_The_Last_Dragon.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Eternals.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Jungle_Cruise.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Cruella.jpg" /></div>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/AQuietPlacePart2.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/PawPatrol_TheMovie.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Infinite.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/SnakeEyes_GIJoeOrigins.jpg" /></div>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/TheDry.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Promising_Young_Woman.jpg" /></div>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/DUNE.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/slick.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/The_father.jpg" /></div>

              <div class="slide"><img src="<?php echo $img_url; ?>slides/Black_Widow.jpg" /></div>
              <div class="slide"><img src="<?php echo $img_url; ?>slides/Free_Guy.jpg" /></div>
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
