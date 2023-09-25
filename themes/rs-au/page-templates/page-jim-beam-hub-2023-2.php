<?php

/**
* Template Name: Jim Beam 2023 Hub 2
*/

wp_enqueue_script( 'iframeResizer', 'https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/4.3.2/iframeResizer.min.js', array('jquery'));

get_header('rsbonds-custom-menu');

if (have_posts()) : while (have_posts()) : the_post(); ?>
<div style="min-height: 1200px;">
<?php if (!post_password_required($post)) : ?>
<iframe id="jb2023hub" src="https://welcomesessionslive-23.jimbeampromotions.com.au/" width="100%" style="display: block;"></iframe>


<!--                     <section class="py-2 upcoming-events text-center">
                        <h3 class="mt-1">Select Your City Below To Enter</h3>
                    </section>

                    <iframe id="jb2023hub" src="https://welcomesessionslive-23.jimbeampromotions.com.au/" width="100%" style="display: block;"></iframe> -->

<?php else : ?>
<div class="l-page__content">
    <div class="l-section l-section--no-separator">
        <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">
            <div style="margin: 1rem auto;">
                <?php echo get_the_password_form(); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; // Password protected ?>
<div>
<?php endwhile;
    wp_reset_query();
endif; ?>
</div><!-- .l-page__content -->
</div><!-- .l-page -->
<?php get_template_part('template-parts/footer/footer'); ?>
<?php wp_footer(); ?>

<script>
    jQuery(document).ready(function($) {
        iFrameResize({
            log: true,
            autoResize: false,
            minHeight: 1200
        }, '#jb2023hub')
    });
</script>
<script>
    jQuery(document).ready(function($) {
        $('.l_toggle_menu_network').on('click', function(e) {
            e.preventDefault();
            $('#menu-network').toggle();
            $('#site_wrap').toggleClass('freeze');
            $('body').toggleClass('network-open');

            $('.is-header-sticky .l-header__search').toggle();
            $(this).toggleClass('expanded');
        });
    });
</script>

</body>
</html>