<?php

/**
 * Template Name: Premium
 */

get_header();

$args = [
    'post_status' => 'publish',
    'has_password'   => FALSE,
    'post_type' => 'any',
    'paged' => $paged,
    'meta_query' => [
        'relation' => 'AND', [
            'key' => 'premium',
            'value' => '1'
        ]
    ]
];
$query = new WP_Query($args);

?>

<div class="l-page__content" data-flyout="is-sidebar-open" data-flyout-trigger="close">

    <div class="l-archive-top">
        <h1 class="l-archive-top__heading">
            <span class="t-super t-super--upper">
                PREMIUM
            </span>
        </h1><!-- .l-archive-top__heading -->
    </div><!-- .l-archive-top -->

    <div class="l-blog">
        <main class="l-blog__primary">

            <div class="l-blog__item l-blog__item--spacer-xl">
                <div class="c-section-header">
                    &nbsp;
                </div><!-- .c-section-header -->
            </div><!-- .l-blog__item -->

            <div class="l-blog__item l-blog__item--spacer-s">
                <ul class="l-river">

                    <?php
                    if ($query->have_posts()) :
                        while ($query->have_posts()) :
                            $query->the_post();
                            get_template_part('template-parts/archive/post');
                        endwhile;
                    endif;
                    ?>

                </ul><!-- .l-river -->
            </div><!-- .l-blog__item -->

            <div class="l-blog__item l-blog__item--spacer-l">
                <div class="c-pagination">
                    <?php previous_posts_link(__('Previous', 'pmc-rollingstone'), $query->max_num_pages); ?>
                    <?php next_posts_link(__('Next', 'pmc-rollingstone'), $query->max_num_pages); ?>
                </div><!-- .c-pagination -->

            </div><!-- .l-blog__item -->

        </main><!-- .l-blog__primary -->

        <?php if (is_active_sidebar('archive_right_1')) : ?>
            <aside class="l-blog__secondary">

                <?php dynamic_sidebar('archive_right_1'); ?>

            </aside><!-- .l-blog__secondary -->
        <?php endif; ?>

    </div><!-- .l-blog -->

    <?php get_template_part('template-parts/footer/footer'); ?>

</div><!-- .l-page__content -->

<?php
get_footer();
