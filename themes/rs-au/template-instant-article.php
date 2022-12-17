<!doctype html>
<html lang="en" prefix="op: http://media.facebook.com/op#">
    <head>
        <meta charset="utf-8">
        <link rel="canonical" href="<?php the_permalink() ;?>">
        <link rel="stylesheet" title="default" href="#">
        <title><?php the_title(); ?></title>
        <meta property="fb:article_style" content="default">
        <meta property="fb:likes_and_comments" content="enable">
        <meta property="fb:use_automatic_ad_placement" content="enable=true ad_density=default">
  </head>
<body>
    <article>
    <header>
        <!-- title -->
        <h1><?php the_title(); ?></h1>

        <!-- publication date/time -->
        <time class="op-published" datetime="<?php echo get_the_date("c"); ?>"><?php echo get_the_date(get_option('date_format') . ", " . get_option('time_format')); ?></time>

        <!-- modification date/time -->
        <time class="op-modified" datetime="<?php echo get_the_modified_date("c"); ?>"><?php echo get_the_modified_date(get_option('date_format') . ", " . get_option('time_format')); ?></time>

        <!-- The author of your article -->
        <address><?php
            if ( get_field('author') ) {
                $author = get_field('author');
            } else if ( (get_field('Author')) ) {
                $author = get_field('Author', '');
            } else {
                $author = get_the_author();
            }
            echo $author;
            ?></address>
        <?php
        if ( get_post_meta( $post_id, 'thumbnail_ext_url', TRUE ) && '' != get_post_meta( $post_id, 'thumbnail_ext_url', TRUE ) ) {
        ?>
        <img src="<?php echo get_post_meta( $post_id, 'thumbnail_ext_url', TRUE ); ?>">
        <?php
        } else if ( has_post_thumbnail() ): ?>
        <figure>
            <img src="<?php
            $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
            echo $src[0];
            ?>">
            <figcaption><?php the_title(); ?></figcaption>
        </figure>
        <?php endif; ?>

    <?php
    /*
     * Ads array
     */
    $ad_tags = array(
        array(
            'slot_no' => 1,
            'slot' => '/9876188/rollingstoneau/homepage_rail1',
            'width' => 300,
            'height' => 250,
        ),
        array(
            'slot_no' => 2,
            'slot' => '/9876188/rollingstoneau/homepage_rail2',
            'width' => 300,
            'height' => 600,
        ),
    );
    if ( $ad_tags && count( $ad_tags ) > 0 ) : ?>
    <section class="op-ad-template">
    <?php
    foreach ( $ad_tags as $ad_tag ) :
        ob_start();
    ?>
        <figure class="op-ad<?php echo 1 == $ad_tag['slot_no'] ? ' op-ad-default' : ''; ?>">
            <iframe height="<?php echo $ad_tag['height']; ?>" width="<?php echo $ad_tag['width']; ?>">
                <script type='text/javascript'>
                    var googletag = googletag || {};
                    googletag.cmd = googletag.cmd || [];
                    (function() {
                        var gads = document.createElement('script');
                        gads.async = true;
                        gads.type = 'text/javascript';
                        var useSSL = 'https:' == document.location.protocol;
                        gads.src = (useSSL ? 'https:' : 'http:') + '//www.googletagservices.com/tag/js/gpt.js';
                        var node = document.getElementsByTagName('script')[0];
                        node.parentNode.insertBefore(gads, node);
                    })();
                </script>
                <script type='text/javascript'>
                    googletag.cmd.push(function() {
                        googletag.defineSlot( '<?php echo $ad_tag['slot']; ?>', [<?php echo $ad_tag['width']; ?>, <?php echo $ad_tag['height']; ?>], 'ad_fbia<?php echo $ad_tag['slot_no']; ?>').addService(googletag.pubads());
                        googletag.pubads().enableSingleRequest();
                        googletag.pubads().collapseEmptyDivs();
                        googletag.pubads().setTargeting('Section', ['Facebook']);
                        googletag.enableServices();
                    });
                </script>
                <div id='ad_fbia<?php echo $ad_tag['slot_no']; ?>' style='height: <?php echo $ad_tag['height']; ?>px; width: <?php echo $ad_tag['width']; ?>px;'>
                    <script type='text/javascript'>googletag.cmd.push(function() { googletag.display('ad_fbia<?php echo $ad_tag['slot_no']; ?>'); });</script>
                </div>
            </iframe>
        </figure>
    <?php
        $insertion = ob_get_clean();

        echo $insertion;

    endforeach;
    ?>
        </section>
    <?php endif; ?>
    </header>


    <?php
//    the_content();
    // remove_filter( 'the_content', 'ssm_youtube_lazy_load' );
    // remove_filter( 'the_content', 'ssm_inject_teads' );
    remove_filter( 'the_content', 'inject_gotchosen' );
    remove_filter( 'the_content', 'inject_ads' );

//    remove_filter( 'the_content', 'td_remove_p_tags_around_iframes' );
    $content = apply_filters('the_content', get_the_content());

    $content = str_replace(
      array(
        '<h3',
        '<h4',
        '<h5',
        '<h6',
        '</h3>',
        '</h4>',
        '</h5>',
        '</h6>',
      ),
      array(
        '<h2',
        '<h2',
        '<h2',
        '<h2',
        '</h2>',
        '</h2>',
        '</h2>',
        '</h2>',
      ),
      $content
    );

    $content = preg_replace( '#<p>\s*</p>#', '', $content );

    echo $content;
    ?>

    <figure class="op-tracker">
        <iframe>
            <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

                <?php
                $categories = get_the_category(get_the_ID());
                $CategoryCD = '';
                if ( $categories ) :
                    foreach( $categories as $category ) :
                        $CategoryCD .= $category->slug . ' ';
                    endforeach; // For Each Category
                endif; // If there are categories for the post
                ?>

                ga('create', 'UA-156845367-1', 'auto');
                // ga('set', 'contentGroup1', '<?php echo $author; ?>');
                ga('set', 'dimension1', '<?php echo get_the_date('M d, Y'); ?>');
                ga('set', 'dimension3', '<?php echo $author; ?>');
                ga('set', 'dimension3', '<?php echo $author; ?>');
                ga('set', 'campaignSource', 'Facebook');
                ga('set', 'campaignMedium', 'Social');
                ga('set', 'campaignName', 'IA_RSA');
                ga('send', 'pageview', {title: '<?php the_title(); ?>'});


                ga('create', 'UA-101631840-1', 'auto', 'ssmallsite');
                ga('ssmallsite.set', 'contentGroup1', '<?php echo $author; ?>');
                ga('ssmallsite.set', 'dimension1', '<?php echo get_the_date('M d, Y'); ?>');
                ga('ssmallsite.set', 'dimension3', '<?php echo $author; ?>');
                ga('ssmallsite.set', 'dimension4', '<?php echo $CategoryCD; ?>');
                ga('ssmallsite.set', 'campaignSource', 'Facebook');
                ga('ssmallsite.set', 'campaignMedium', 'Social');
                ga('ssmallsite.set', 'campaignName', 'IA_RSA');
                ga('ssmallsite.send', 'pageview', {title: '<?php the_title(); ?>'});
            </script>
        </iframe>
    </figure>

    <footer>
        <ul class="op-related-articles">
        <?php
        $post_id = get_the_ID();
        $tags = wp_get_post_tags( $post_id );
        $arg_tags = array();
        foreach ( $tags as $tag ) {
            array_push( $arg_tags, $tag->term_id );
        }
        $args=array(
            'post_status' => 'publish',
            'tag__in' => $arg_tags,
            'post__not_in' => array($post_id),
            'posts_per_page' => 4,
            'orderby' => 'rand',
            'date_query' => array(
                'column' => 'post_date',
                'after' => '-60 days'
            )
        );
        $related_posts_query = new WP_Query($args);
        $require_more_posts = 4;
        if ( count( $arg_tags ) > 0 && $related_posts_query->have_posts() ) :
            while ($related_posts_query->have_posts()) :
                $related_posts_query->the_post();
        ?>
                <li><a href="<?php the_permalink(); ?>"></a></li>
        <?php
                $require_more_posts--;
            endwhile;
            wp_reset_query();
        endif;

        if( $require_more_posts > 0 ):
            $cats = wp_get_post_categories( $post_id );
            $args=array(
                'post_status' => 'publish',
                'post__not_in' => array($post_id),
                'posts_per_page' => $require_more_posts,
                'category__in' => $cats,
                'orderby' => 'rand',
                'date_query' => array(
                    'column' => 'post_date',
                    'after' => '-30 days'
                )
            );
            $random_posts_query = new WP_Query($args);
            if ( $random_posts_query->have_posts() ) :
                while ($random_posts_query->have_posts()) :
                    $random_posts_query->the_post();
        ?>
                <li><a href="<?php the_permalink(); ?>"></a></li>
        <?php
                endwhile;
                wp_reset_query();
            endif;
        endif;
        ?>
        </ul>
        <!-- Credits for your article -->
        <aside>Music, Film, TV and Political News Coverage</aside>

        <!-- Copyright details for your article -->
        <small>&copy; The Brag Media</small>
    </footer>
    </article>
</body>
</html>
