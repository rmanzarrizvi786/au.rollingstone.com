<?php

/**
 * Template Name: RS Awards Countdown (2020)
 */
wp_redirect( home_url('/rolling-stone-readers-award-2021/' ) ); exit;
get_header();

add_action('wp_footer', function () {
?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap');

        #timer {
            width: 450px;
            max-width: 100%;
            max-width: 100%;
            margin: auto;
            text-align: center;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            font-size: 300%;
            /* font-family: Graphik,sans-serif; */
        }

        #timer div {
            display: flex;
            flex-direction: column;
            margin: auto 2rem;
        }

        #timer div span {
            display: block;
        }

        #timer div .number {
            font-size: 200%;
            line-height: 150%;
            font-family: 'Roboto Mono', monospace;
        }

        @media (max-width: 576px) {
            #timer {
                font-size: 9vw;
            }

            #timer div {
                margin: auto 1rem;
            }

            #timer div .number {
                font-size: 150%;
            }
        }
    </style>

    <script>
        var currDate = new Date();
        var timerInterval;

        function makeTimer() {

            var endTime = new Date('2020-11-30');
            endTime = (Date.parse(endTime) / 1000);

            var now = new Date();
            now = (Date.parse(now) / 1000);

            var timeLeft = endTime - now;

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                // alert('We couldn\'t hold your headphones, press OK to try again!');
                // window.location.reload();
            }

            var days = Math.floor(timeLeft / 86400);
            var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
            var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600)) / 60);
            var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));

            if (hours < "10") {
                hours = "0" + hours;
            }
            if (minutes < "10") {
                minutes = "0" + minutes;
            }
            if (seconds < "10") {
                seconds = "0" + seconds;
            }

            jQuery("#days").html('<span class="number">' + days + "</span><span>Days</span>");
            // jQuery("#hours").html(hours + "<span>Hours</span>");
            jQuery("#minutes").html('<span class="number">' + minutes + '</span><span>Mins</span>');
            jQuery("#seconds").html('<span class="number">' + seconds + '</span><span>Secs</span></div>');
        }

        timerInterval = setInterval(function() {
            makeTimer();
        }, 1000);
        const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        jQuery(document).ready(function($) {});
    </script>
<?php
}); // wp_footer action
?>
<?php
if (have_posts()) :
    while (have_posts()) :
        the_post();

        if (!post_password_required($post)) :
?>
            <div class="l-page__content">

                <div class="l-section l-section--no-separator" style="margin: 2rem auto;">
                    <div class="c-content c-content--no-sidebar t-copy" style="width: 100%;">
                        <h1><?php the_title(); ?></h1>

                        <div id="timer">
                            <div id="days"></div>
                            <div id="minutes"></div>
                            <div id="seconds"></div>
                        </div>

                        <div style="margin-top: 2rem; font-size: 20px; text-align: center;">
                            <p>This is for Rolling Stone Australia magazine subscribers only. If you want to judge, <a href="https://au.rollingstone.com/subscribe-magazine/" target="_blank" style="color: #d32531;">subscribe here</a> so that you can join an elite club of supporters and readers of long form music journalism.</p>
                        </div>

                    </div><!-- /.c-content t-copy -->
                </div><!-- /.l-section -->

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
