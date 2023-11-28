<?php

/**
 * Template Name: Rolling Stone Awards 2023 (Info)
 */

define('ICONS_URL', get_template_directory_uri() . '/images/');

$award_categories = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rsawards_categories_2023 WHERE status = '1'");
$action = isset($_GET['a']) && in_array(trim($_GET['a']), ['add', 'edit', 'ty']) ? trim($_GET['a']) : NULL;

get_header('rsawards-custom-menu-2023');
$noms_open_at = isset($noms_open_at) ? $noms_open_at : '';
?>

<?php
if (have_posts()):
  while (have_posts()):
    the_post();

    if (!post_password_required($post)):
      ?>
      <div id="content-wrap">

        <!-- <div class="container rsa-header" style="background-color: #fff;">
          <img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/header-red.jpg">
        </div> -->

        <section class="bg-white p-3 d-flex" style="background-color: #fff; padding: 1.5rem; margin-bottom: 72px;">
          <div class="col-12">
            <div class="d-flex flex-column flex-md-row align-items-stretch">
              <div class="col-12">
                <?php
                $show = isset($_GET['show']) ? trim(strtolower($_GET['show'])) : 'news';
                switch ($show) {
                  case 'news':
                    get_template_part('page-templates/rs-awards/2023/news');
                    break;
                  case 'categories':
                    get_template_part('page-templates/rs-awards/2023/categories', NULL, ['award_categories' => $award_categories, 'noms_open' => $noms_open, 'noms_open_at' => $noms_open_at, 'context' => 'info']);
                    break;
                  case 'faq':
                    get_template_part('page-templates/rs-awards/2023/faq');
                    break;
                  case 'info':
                    get_template_part('page-templates/rs-awards/2023/info');
                    break;
                  case 'judges':
                    get_template_part('page-templates/rs-awards/2023/judges');
                    break;
                  case 'venue-details':
                    get_template_part('page-templates/rs-awards/2023/venue-details');
                    break;
                  case 'sponsors':
                    get_template_part('page-templates/rs-awards/2023/sponsors');
                    break;
                  default:
                    get_template_part('page-templates/rs-awards/2023/news');
                    break;
                }
                ?>
              </div>
            </div>
          </div>
        </section>
      </div><!-- #content-wrap -->
      <?php
    else:
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

<?php get_template_part('template-parts/footer/footer'); ?>
</div><!-- .l-page__content -->
<?php get_footer();