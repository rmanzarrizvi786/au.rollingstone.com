<?php
//fopen("wp-content/themes/rs-au/page-templates/page-50-greatest-artists-2023AA.php", "w");
//exit;
/**
 * Template Name: 50 Greatest Artists 2023
 */

get_template_part('page-templates/greatest-artists-2021/header-results');

$paged = get_query_var('paged', 1);
if ($paged == 0)
    $paged = 1;
$per_page = 10;

$total = 50;

$release_date = new DateTime('2021-04-01');
$today = new DateTime();
$diff_days = $today->diff($release_date)->days;

$release_upto = $total - $diff_days * $per_page - $per_page + 1;

if ($release_upto <= 1) {
    $release_upto = 1;
}

$release_upto = 1;

$no_pages = ceil(($total - $release_upto) / $per_page);
//$myfile = fopen("aziz_99.php", "w");
?>
<link rel='stylesheet' id='list-css' href='https://cdn-r2-2.thebrag.com/assets/css/list.css?ver=1.63' type='text/css' media='all' />
<?php
add_action('wp_footer', function () {
    ?>
    <script>
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        jQuery(document).ready(function ($) {

        });
    </script>
    <?php
}); // wp_footer

if (have_posts()):
    while (have_posts()):
        the_post();

        if (!post_password_required($post)):
            ?>
            <div class="l-page__content">
                <div class="logo-wrap">
                    <div class="l-section l-section--no-separator">
                        <figure class="c-picture2" style="margin: auto;">
                            <div class="c-picture__frame2">

                                <div class="c-crop2 c-crop--ratio-3x2-2 logo">
                                    <img src="https://cdn-r2-2.thebrag.com/assets/images/RSAUNZ_PrimaryRGB_HEXe00019_1@2x.png"
                                        style="width: 60%; max-width: 100%;">
                                    <div
                                        style="font-family: Graphik,sans-serif; margin-top: 2rem; font-size: 175%; line-height: 130%; display: none;">
                                    </div>
                                </div><!-- .c-crop -->
                            </div>

                        </figure><!-- .c-picture -->
                    </div>
                </div>

                <div class="l-section l-section--no-separator" style="margin: 2rem auto; max-width: 65rem;">
                    <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">

                        <article class="top-list-wrap" data-id="<?php echo get_the_ID(); ?>" data-premium="true">

                            <h1 data-href="<?php the_permalink(); ?>" style="line-height: 2.5rem;">
                                <?php the_title(); ?>
                            </h1>

                            <?php the_content(); ?>
                            <!-- <h3>WILL COME SHORTLY</h3> -->

                            <nav class="l-header__block l-header__block--list-nav"
                                style="position: sticky; background: white; z-index: 30001; padding-top: .5rem; padding-bottom: .5rem;">
                                <ul class="l-header__menu l-header__menu--list t-semibold t-semibold--upper" data-list-nav>

                                    <?php
                                    // for ($page = 1; $page <= $no_pages; $page++) :
                                    $page = 0;
                                    for ($i = $total; $i >= 1; $i -= $per_page):
                                        $page++;
                                        $range_start = $i;
                                        $range_end = $i - $per_page + 1;
                                        if ($range_end <= 1)
                                            $range_end = 1;
                                        ?>
                                        <li class="l-header__menu-item">
                                            <?php if ($range_start > $release_upto):
                                                ?>
                                                <a href="<?php echo get_permalink(); ?>page/<?php echo $page; ?>/"
                                                    class="l-header__menu-link" data-list-nav-item
                                                    data-list-range-start="<?php echo $range_start; ?>"
                                                    data-list-range-end="<?php echo $range_end; ?>">
                                                    <?php echo $range_start; ?>-
                                                    <?php echo $range_end; ?>
                                                </a>
                                            <?php else:
                                                ?>
                                                <span class="l-header__menu-link" data-list-nav-item
                                                    data-list-range-start="<?php echo $range_start; ?>"
                                                    data-list-range-end="<?php echo $range_end; ?>" style="color: #ccc;">
                                                    <?php echo $range_start; ?>-
                                                    <?php echo $range_end; ?>
                                                </span>
                                            <?php endif; ?>
                                        </li><!-- .l-header__menu-item -->
                                        <?php //endfor; 
                                                        ?>
                                        <?php
                                    endfor;
                                    // $per_page = 15;
                                    ?>

                                </ul>
                                <div class="l-header__progress-bar" data-list-progress-bar></div>
                            </nav>

                            <div class="c-list c-list--albums">
                                <?php
                                $start = $total - ($paged - 1) * $per_page;
                                $to = $start - $per_page;
                                if ($to <= 0)
                                    $to = 0;
                                if ($start > 0):

                                    if ($start < $release_upto):
                                        $will_be_released = $release_date->modify(($paged) . ' days');
                                        ?>
                                        <article class="c-list__item" id="list-item-<?php echo $start; ?>"
                                            data-list-item="<?php echo $start; ?>" data-list-title="Artist at <?php echo $start; ?>"
                                            data-list-item-id="<?php echo $start; ?>" style="margin: 0; border: none;">
                                            <div
                                                style="margin: 1rem auto; text-align: center; font-size: 120%; background: #f3f3f3; padding: 1rem;">
                                                Numbers
                                                <?php echo $start . '-' . ($to + 1); ?> will be announced
                                                <?php echo $will_be_released->format('l'); ?> the
                                                <?php echo $will_be_released->format('jS'); ?>
                                            </div>
                                        </article>
                                        <?php
                                    else:
                                        $query = "SELECT * FROM {$wpdb->prefix}greatest_artists_2023 WHERE position <= {$start} AND position > {$to} ORDER BY position DESC ";
                                        // echo $query;
                                        $artists = $wpdb->get_results($query);

                                        if ($artists):
                                            foreach ($artists as $artist): ?>
                                                <article class="c-list__item" id="list-item-<?php echo $artist->position; ?>"
                                                    data-list-item="<?php echo $artist->position; ?>"
                                                    data-list-title="Artist at <?php echo $artist->position; ?>"
                                                    data-list-permalink="<?php echo get_permalink(); ?>page/<?php echo $paged; ?>/<?php echo $artist->slug; ?>/"
                                                    data-list-item-id="<?php echo $artist->position; ?>"
                                                    data-href="<?php echo get_permalink(); ?>page/<?php echo $paged; ?>/<?php echo $artist->slug; ?>/">
                                                    <div class="list-content" style="margin: auto;">
                                                        <?php if (!is_null($artist->image_url) && '' != trim($artist->image_url)): ?>
                                                            <figure class="c-list__picture2" style="float: none; margin: auto; position: relative;">
                                                                <div>
                                                                    <img width="900" src="<?php echo $artist->image_url; ?>"
                                                                        data-src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1366,height=910/https://images-r2.thebrag.com/rs/uploads/2023/12/<?php echo $artist->image_url; ?>"
                                                                        alt="<?php echo $artist->title; ?>">
                                                                </div><!-- .c-crop -->
                                                                <?php if (!is_null($artist->image_credit) && '' != trim($artist->image_credit)): ?>
                                                                    <div
                                                                        style="position: absolute; bottom: 0.45rem; right: 0; background: rgba(0,0,0,.75); color: #fff; padding: .5rem; font-size: 80%;">
                                                                        <em>Image credit:
                                                                            <?php echo trim($artist->image_credit); ?>
                                                                        </em></div>
                                                                <?php endif; ?>
                                                            </figure><!-- /.c-list__picture -->
                                                        <?php else: ?>
                                                            <div style="height: 50px;"></div>
                                                        <?php endif; ?>

                                                        <header class="c-list__header">
                                                            <!-- <span class="c-list__number t-bold">
                                                                <?php echo $artist->position; ?>
                                                            </span> -->

                                                            <h3 class="c-list__title t-bold" style="margin-bottom: 1rem;">
                                                                <?php echo stripslashes($artist->title); ?>
                                                            </h3><!-- /.c-list__title -->
                                                        </header><!-- /.c-list__header -->

                                                        <main class="c-list__main">
                                                            <div class="c-list__lead c-content">
                                                                <?php echo wpautop(stripslashes($artist->description)); ?>
                                                            </div><!-- /.c-list__lead -->

                                                        </main><!-- /.c-list__main -->
                                                    </div>
                                                </article>


                                            <?php endforeach; // For Each $artist in $artists
                                        endif; // If $artists 
                                    endif; // If $to < $release_upto
                                endif;
                                ?>
                            </div>

                        </article><!-- .top-list-wrap -->
                    </div><!-- /.c-content t-copy -->
                </div><!-- /.l-section -->


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
        endif; // Pasword protected
    endwhile;
endif;
?>

    <?php get_template_part('template-parts/footer/footer'); ?>
</div><!-- .l-page__content -->
<?php
get_footer();
