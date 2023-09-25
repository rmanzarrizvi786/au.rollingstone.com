<?php

/**
 * Template Name: Stream
 */



add_action('wp_head', function () {
?>
    <style>
        .ad-bb-header {
            display: none;
        }

        .videoWrapper {
            position: relative;
            padding-bottom: 56.25%;
            /* 16:9 */
            height: 0;
            background-color: #000;
        }

        .videoWrapper #player {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* margin: 0 auto .75rem auto; */
        }

        @media (min-width: 60rem) {

            .l-header,
            .l-header__content,
            .l-header__wrap {
                height: 7.5rem;
            }

            .l-header__nav {
                height: 2.4rem;
            }
        }

        @media (max-width: 59.9375rem) {
            .videoWrapper {
                padding-bottom: calc(100vh - 5rem);
            }

            .videoWrapper #player {
                top: 50%;
                transform: translateY(-50%);
            }
        }
    </style>

<?php
});

add_action('wp_footer', function () {
?>
    <script src="https://cdn.jwplayer.com/libraries/GSyPNv61.js"></script>
    <script type="text/JavaScript">
        jwplayer("player").setup({
        "playlist": [
            {
                "sources": [
                    {
                        "type": "hls",
                        "file": "https://cdn3.wowza.com/1/d2laNFBUSTdXOEo5/VGtNdlRG/hls/live/playlist.m3u8",
                    }
                ],
                "image": 'https://cdn.thebrag.com/videos/RSAU200SONOSholder_v1.jpg'
            }
        ],
        "aboutlink": "https://au.rollingstone.com",
        "primary": "html5",
        "hlshtml": true,
        "autostart": false,
        "loop": true,
        "cast": {},
        "autoPause": {
            "viewability": false,
            "pauseAds": false
        }
    })
    </script>
<?php
});

get_header();
?>


<div class="l-page__content">
    <div class="videoWrapper">
        <?php // the_content(); 
        ?>
        <div id="player"></div>
    </div>
    <?php get_template_part('template-parts/footer/footer'); ?>
</div><!-- .l-page__content -->
<?php
get_footer();
