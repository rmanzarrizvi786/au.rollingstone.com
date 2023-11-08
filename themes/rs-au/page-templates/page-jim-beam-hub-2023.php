<?php

/**
* Template Name: Jim Beam 2023 Hub
*/

if (isset($_POST['age_year'])) {
    define('AGE_ALLOWED', 18);
    $currentYear = date('Y');
    $birthYear =
        isset($_POST['dob_year_ns']) ? absint($_POST['dob_year_ns']) : (isset($_POST['age_year']) ? absint($_POST['age_year']) : 0);

    $isAmbiguousValue = $currentYear - $birthYear === AGE_ALLOWED;

    if ($isAmbiguousValue) {
        $birthMonth =
            isset($_POST['dob_month_ns']) ? absint($_POST['dob_month_ns']) : (isset($_POST['dob_month']) ? absint($_POST['dob_month']) : 0);

        $birthDay =
            isset($_POST['dob_day_ns']) ? absint($_POST['dob_day_ns']) : (isset($_POST['dob_day']) ? absint($_POST['dob_day']) : 0);
        if ($birthYear > 0 && $birthMonth > 0 && $birthDay > 0) {
            $birthDate = new DateTime($birthYear . '-' . $birthMonth . '-' . $birthDay);
            $now = new DateTime();
            $interval = $now->diff($birthDate);
            if ($interval->y >= 18) {
                proceed(isset($_POST['rememberme']));
            }
        }
    }

    $hasLegalAgeValue = $currentYear - $birthYear >= AGE_ALLOWED;
    if ($hasLegalAgeValue) {
        proceed(isset($_POST['rememberme']));
    }
}

function proceed($rememberme = false, $url = '')
{
    if ('' == $url) {
        $url = get_the_permalink();
    }
    if ($rememberme) {
        setcookie(
            'tbm-jimbeam-over-18',
            true,
            time() + 31556926 // For a year
        );
    } else {
        if (!session_id()) {
            session_start();
        }
        $_SESSION['tbm-jimbeam-over-18'] = true;
    }
    wp_redirect($url);
    exit;
}

$is_over_18 = isset($_COOKIE['tbm-jimbeam-over-18']) || isset($_SESSION['tbm-jimbeam-over-18']);

get_header('jim-beam-2022');
?>

<?php
if (have_posts()) :
    while (have_posts()) :
        the_post();

        if (!post_password_required($post)) :
?>

            <?php if ($is_over_18) : ?>
                <div class="video-wrap d-flex text-center" id="video-1" style="display: none;">
                    <div style="width: 560px; max-width: 100%">
                        <button class="btn-close-video" data-target="video-1">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2022/btn-close.svg">
                        </button>
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/UlJpFuwpmh8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <p class="mt-2">A live performance of Muse’s new hit single, “Compliance,” beginning with an ode to the community that raised them.</p>
                    </div>
                </div>
                <div id="page-overlay" style="display: none"></div>
            <?php endif; ?>

            <div class="l-page" id="site_wrap" style="width: 100%; max-width: 100%; overflow-x: hidden">
                <?php if (!$is_over_18) : ?>
                    <div class="l-page__header">
                        <header>
                            <?php get_template_part('template-parts/header/nav-network'); ?>
                            <div class="l-header__wrap tbm">
                                <div class="content d-flex flex-column">
                                    <div class="l-header__content content-wrap">
                                        <div style="font-size: 1.25rem; font-family: Americana Std Extra Bold, serif;"><strong>JIM BEAM &amp; ROLLING STONE AUSTRALIA</strong></div>
                                        <div class="text-gold" style="margin-top: .25rem; font-family: Graphik-Medium, sans-serif; letter-spacing: 1px;">PRESENT</div>
                                        <h1 class="l-header__branding">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2022/header-1.png" class="feature-image" style="width: 200px; max-width: 100%;">
                                        </h1>
                                        <div class="content-wrap">
                                            <h1 class="text-kuunari-bold mb-3" style="line-height: 100%;">Welcome to the home of Jim Beam Welcome Sessions</h1>
                                            <div style="width: 650px; margin: auto; max-width: 100%; font-family: Graphik-Medium, sans-serif;">
                                                <p>Jim Beam Welcome Sessions creates one-of-a-kind, shared music experiences that bring us closer together.</p>
                                                <br>
                                                <p>You need to be of legal drinking age to enter this site. By entering the site you confirm that you are over the age of 18.</p>
                                            </div>
                                        </div>
                                        <div class="age-gate content-wrap" style="font-family: Graphik-Medium, sans-serif;">
                                            <div id="agegate">
                                                <form name="agegate" method="post">
                                                    <div class="mt-3 d-flex">
                                                        <noscript>
                                                            <div class="d-flex align-items-stretch">
                                                                <input type="number" name="dob_day_ns" placeholder="DD" min="1" max="31" class="age-gate__input" required />
                                                                <input type="number" name="dob_month_ns" placeholder="MM" min="1" max="12" class="age-gate__input" required />
                                                                <input type="number" name="dob_year_ns" placeholder="YYYY" min="1900" class="age-gate__input" required />
                                                                <button type="submit" class="js-step2-submit">OK</button>
                                                            </div>
                                                        </noscript>

                                                        <fieldset class="agegate_digit-wrapper js-agegate_digit-wrapper js-agegate_step1 clear-after">
                                                            <input id="age-gate-year" type="tel" name="age_year" placeholder="YYYY" maxlength="4" onclick="this.select();" class="age-gate__input">
                                                        </fieldset>

                                                        <fieldset class="agegate_step2 js-agegate_step2 clear-after">
                                                            <div class="d-flex align-items-stretch">
                                                                <input type="tel" data-value="01" name="dob_day" placeholder="DD" min="1" max="31" maxlength="2" class="age-gate__input">
                                                                <input type="tel" data-value="01" name="dob_month" placeholder="MM" min="1" max="12" maxlength="2" class="age-gate__input">
                                                                <input type="tel" data-value="" name="dob_year" placeholder="YYYY" min="1900" maxlength="4" class="age-gate__input">
                                                                <button type="submit" class="js-step2-submit" name="confirm-over-18"><span>OK</span></button>
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                    <p class=" agegate_validation" data-underage="You must be 18+ to enter." data-invalid="You must be 18+ to enter. Please enter your year of birth" data-invalid-birth-date="You must be 18+ to enter. Please enter your date of birth" style="display: none;"></p>

                                                    <div class="mt-3">
                                                        <label for="age-gate-rememberme" class="age-gate-rememberme-wrapper"><input type="checkbox" name="rememberme" id="age-gate-rememberme" checked="checked"><label for="age-gate-rememberme"></label>
                                                            <p>Remember me</p>
                                                        </label>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-4"><img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2022/drink-smart.png" style="width: 120px;"></div>
                                        <!-- .l-header__branding -->
                                    </div><!-- .l-header__content -->
                                </div>
                            </div>
                            <!--. l-header__wrap -->

                        </header><!-- .l-header -->
                    </div>
                <?php else : ?>
                    <div class="l-page__header">
                        <header>
                            <?php get_template_part('template-parts/header/nav-network'); ?>
                        </header>
                    </div>
                    <div id="content-wrap2">
                        <section class="d-flex flex-column align-items-start">
                            <div class="intro">
                                <div class="content-wrap" style="z-index: 2;">
                                    <div class="content d-flex flex-column mb-3 text-white2" style="width: 100%;">
                                        <div style="text-align: center; font-size: 1.25rem; font-family: Americana Std Extra Bold, serif;"><strong>JIM BEAM &amp; ROLLING STONE AUSTRALIA</strong></div>
                                        <div style="margin-top: .25rem; font-family: Graphik-Medium, sans-serif; letter-spacing: 1px;">PRESENT</div>
                                    </div>

                                    <div class="content d-flex flex-column flex-md-row mb-4 pb-4" style="width: 100%;">
                                        <div class="col-12 col-md-1"></div>
                                        <div class="col-12 col-md-4 img-header-wrap">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2022/header-1.png" class="img-header">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="text-kuunari-bold text-header">
                                                <div class="d-flex flex-column">
                                                    <div class="text-all-together">ALL TOGETHER</div>
                                                    <div class="d-flex flex-row">
                                                        <div class="text-for-the">FOR THE</div>
                                                        <div class="text-music text-kuunari-condensed">MUSIC</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-1"></div>
                                    </div>
                                </div>
                                <div class="header-drink-smart-wrap">
                                    <div class="px-2"><img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2022/drink-smart.png" class="header-drink-smart" style=" width: 120px;"></div>
                                </div>
                            </div>
                        </section>

                        <section class="about d-flex flex-column align-items-start py-3 py-md-4 px-3">
                            <div class="content-wrap d-flex flex-column">
                                <p>
                                    Music has the innate ability to facilitate human connections and Jim Beam Welcome Sessions wants to harness this unrivalled power to ignite strong communities of music fans through unique experiences.
                                </p>
                            </div>
                        </section>

                        <section class="upcoming-events px-2 px-md-4 py-2 py-md-3">
                            <div class="content-wrap">
                                <h2 class="d-flex justify-content-start" style="margin-bottom: 1rem">
                                    <span class="text-kuunari-condensed" style="font-size: 300%; line-height: 100%;">EVENTS</span>
                                </h2>

                                <div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch my-3">

                                    <div class="col-12 col-md-4 d-flex align-items-stretch">
                                        <div class="mt-3 mt-md-0 d-flex flex-column align-items-stretch mr-0 mr-md-2">
                                            <div class="event-wrap event-01">
                                                <div class="event">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2023/LC.jpg">
                                                    <div class="city" style="color: #fff; text-shadow: 1px 1px 10px #000">
                                                        SYDNEY
                                                    </div>
                                                </div>
                                                <div class="info">
                                                    <div class="text-kuunari-bold" style="font-size: 200%; line-height: 200%;">LIME CORDIALE (DJ SET)</div>
                                                    <p>Sunday, February 12th</p>
                                                    <p>The Island, Sydney</p>
                                                    <a href="https://welcomesessionslive-23.jimbeampromotions.com.au/sydney.php" target="_blank" class="mt-3 btn btn-tickets">ENTER HERE</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 d-flex align-items-stretch">
                                        <div class="mt-4 mt-md-0 d-flex flex-column align-items-stretch ml-0 ml-md-2">
                                            <div class="event-wrap event-02">
                                                <div class="event">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2023/TBA.jpg">
                                                    <div class="city">
                                                        PERTH
                                                    </div>
                                                </div>
                                                <div class="info col-12 col-md-6">
                                                    <div class="text-kuunari-bold" style="font-size: 200%; line-height: 200%;">TBA</div>
                                                    <p>Saturday, March 4th</p>
                                                    <p>The Aviary, Perth</p>
                                                    <a href="https://welcomesessionslive-23.jimbeampromotions.com.au/notify-perth.php" target="_blank" class="mt-3 btn btn-tickets">GET NOTIFIED</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 d-flex align-items-stretch">
                                        <div class="mt-4 mt-md-0 d-flex flex-column align-items-stretch ml-0 ml-md-2">
                                            <div class="event-wrap event-02">
                                                <div class="event">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2023/TBA.jpg">
                                                    <div class="city">
                                                        ADELAIDE
                                                    </div>
                                                </div>
                                                <div class="info col-12 col-md-6">
                                                    <div class="text-kuunari-bold" style="font-size: 200%; line-height: 200%;">TBA</div>
                                                    <p>Saturday, March 25th</p>
                                                    <p>Mosely Bar & Kitchen, Adelaide</p>
                                                    <a href="https://welcomesessionslive-23.jimbeampromotions.com.au/notify-ade.php" target="_blank" class="mt-3 btn btn-tickets">GET NOTIFIED</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch my-3">

                                    <div class="col-12 col-md-4 d-flex align-items-stretch">
                                        <div class="mt-3 mt-md-0 d-flex flex-column align-items-stretch mr-0 mr-md-2">
                                            <div class="event-wrap event-01">
                                                <div class="event">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2023/TBA.jpg">
                                                    <div class="city">
                                                        GOLD COAST
                                                    </div>
                                                </div>
                                                <div class="info">
                                                    <div class="text-kuunari-bold" style="font-size: 200%; line-height: 200%;">TBA</div>
                                                    <p>Saturday, April 8th</p>
                                                    <p>The Garden Kitchen & Bar, Gold Coast</p>
                                                    <a href="https://welcomesessionslive-23.jimbeampromotions.com.au/notify-gc.php" target="_blank" class="mt-3 btn btn-tickets">GET NOTIFIED</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 d-flex align-items-stretch">
                                        <div class="mt-4 mt-md-0 d-flex flex-column align-items-stretch ml-0 ml-md-2">
                                            <div class="event-wrap event-02">
                                                <div class="event">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2023/TBA.jpg">
                                                    <div class="city">
                                                        MELBOURNE
                                                    </div>
                                                </div>
                                                <div class="info col-12 col-md-6">
                                                    <div class="text-kuunari-bold" style="font-size: 200%; line-height: 200%;">TBA</div>
                                                    <p>Saturday, April 29th</p>
                                                    <p>The Osbourne Rooftop, Melbourne</p>
                                                    <a href="https://welcomesessionslive-23.jimbeampromotions.com.au/notify-melb.php" target="_blank" class="mt-3 btn btn-tickets">GET NOTIFIED</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>

                    <?php if (0) : ?>
                        <section class="upcoming-events px-2 px-md-4 py-2 py-md-3">
                            <div class="content-wrap">
                                <div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch my-3">
                                    <div class="col-12 col-md-7">
                                        <div class="mr-0 mr-md-2">
                                            <h2 class="d-flex justify-content-start" style="margin-bottom: 1rem">
                                                <span class="text-kuunari-bold" style="font-size: 175%; line-height: 100%; text-decoration: underline;">UP-COMING</span>
                                                &nbsp;
                                                <span class="text-kuunari-condensed" style="font-size: 300%; line-height: 100%;">EVENTS</span>
                                            </h2>

                                            <div class="event-wrap event-01">
                                                <div class="event event-sydney">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2022/FF.jpg">
                                                    <div class="city">
                                                        SYDNEY
                                                    </div>
                                                </div>
                                                <div class="info">
                                                    <div class="text-kuunari-bold" style="font-size: 200%; line-height: 200%;">FLIGHT FACILITIES (DJ SET)</div>
                                                    <p>Saturday, June 11th</p>
                                                    <p>The Rooftop, Sydney</p>
                                                    <a href="https://welcomesessionslive.jimbeampromotions.com.au/" target="_blank" class="mt-3 btn btn-tickets">Enter Here</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-5 d-flex align-items-stretch">
                                        <div class="ml-0 ml-md-2 mt-3 mt-md-0 d-flex flex-column align-items-stretch">
                                            <div class="event-wrap event-02 flex-fill">
                                                <div class="d-flex flex-column flex-md-row2 flex-nowrap">
                                                    <div class="col-12 col-md-6">
                                                        <div class="event event-brisbane">
                                                            <img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2022/ClientLiaison.jpg">
                                                            <div class="city">
                                                                BRISBANE
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="info col-12 col-md-6">
                                                        <div class="text-kuunari-bold" style="font-size: 200%; line-height: 200%;">CLIENT LIAISON</div>
                                                        <p>Saturday, July 16th</p>
                                                        <p>Buffalo Bar, Brisbane</p>
                                                        <a href="https://welcomesessionslive.jimbeampromotions.com.au/notify.php" target="_blank" class="mt-3 btn btn-tickets">Enter Here</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="event-wrap event-02 flex-fill mt-2 mt-md-4">
                                                <div class="d-flex flex-column flex-md-row2 flex-nowrap">
                                                    <div class="col-12 col-md-6">
                                                        <div class="event event-melbourne">
                                                            <img src="<?php echo get_template_directory_uri(); ?>/images/jim-beam-2022/The-Veronicas.jpg">
                                                            <div class="city">
                                                                MELBOURNE
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="info col-12 col-md-6">
                                                        <div class="text-kuunari-bold" style="font-size: 200%; line-height: 200%;">The Veronicas</div>
                                                        <p>Saturday, August 20th</p>
                                                        <p>Beer DeLuxe Fed Square, Melbourne</p>
                                                        <!-- <a href="https://welcomesessionslive.jimbeampromotions.com.au/notify.php" target="_blank" class="mt-3 btn btn-tickets">Enter Here</a> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>

                    <section class="latest-news px-2 px-md-4 py-2 py-md-3">
                        <div class="content-wrap">
                            <div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch my-3">
                                <div class="col-12">
                                    <div class="mb-4 mb-md-2 mr-0 mr-md-1">
                                        <h2 class="d-flex justify-content-start" style="margin-bottom: 1rem">
                                            <span class="text-kuunari-bold" style="font-size: 175%; line-height: 100%; text-decoration: underline;">WELCOME SESSIONS</span>
                                            &nbsp;
                                            <span class="text-kuunari-condensed" style="font-size: 300%; line-height: 100%;">2022</span>
                                        </h2>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column my-4">
                                <div style="width: 1px; height: 70px; background-color: #ccc;"></div>
                                <h3 class="mt-1">The Veronicas in Melbourne Recap</h3>
                                <div class="content-wrap my-2 d-flex">
                                    <div class="text-center" style="width: 890px; max-width: 100%;font-family: Graphik-Medium, sans-serif;">
                                        <div>
                                            <iframe width="890" height="501" src="https://www.youtube.com/embed/1Mdx45aKe4o" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column my-4">
                                <div style="width: 1px; height: 70px; background-color: #ccc;"></div>
                                <h3 class="mt-1">Client Liaison in Brisbane Recap</h3>
                                <div class="content-wrap my-2 d-flex">
                                    <div class="text-center" style="width: 890px; max-width: 100%;font-family: Graphik-Medium, sans-serif;">
                                        <div>
                                            <iframe width="890" height="501" src="https://www.youtube.com/embed/W4ZudfUnT9E" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column my-4">
                                <div style="width: 1px; height: 70px; background-color: #ccc;"></div>
                                <h3 class="mt-1">Flight Facilities in Sydney Recap</h3>
                                <div class="content-wrap my-2 d-flex">
                                    <div class="text-center" style="width: 890px; max-width: 100%;font-family: Graphik-Medium, sans-serif;">
                                        <div>
                                            <iframe width="890" height="501" src="https://www.youtube.com/embed/mmqpl3jyPiE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column">
                                <div class="d-flex flex-column flex-md-row align-items-stretch mb-3">
                                    <div class="col-12 col-md-4 mb-4 pr-0 pr-md-2 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/music/music-features/jim-beam-welcome-sessions-creates-one-of-a-kind-live-music-experiences-42953/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2022/09/JBWS-local-2022.jpg?resize=900,600&w=1200&nologo=1">
                                            <h4>Jim Beam Welcome Sessions Creates One of a Kind Live Music Experiences</h4>
                                            <p>TA look back at the Australia leg of Jim Beam Welcome Sessions 2023, featuring intimate performances from Flight Facilities, Client Liaison, and The Veronicas.</p>
                                        </a>
                                    </div>

                                    <div class="col-12 col-md-4 mb-4 pr-0 pr-md-2 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/music/music-live-reviews/the-veronicas-jim-beam-welcome-sessions-review-42345/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2022/08/Jim-Beam-Welcome-Sessions-Melbourne-3.jpg?resize=900,600&w=1200&nologo=1">
                                            <h4>The Veronicas Light Up the Room During the Final Jim Beam Welcome Sessions</h4>
                                            <p>The Jim Beam Welcome Sessions have concluded with an intimate, love-filled gig with pop duo The Veronicas at the helm.</p>
                                        </a>
                                    </div>

                                    <div class="col-12 col-md-4 mb-4 px-0 px-md-2 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/music/music-features/the-veronicas-jim-beam-welcome-sessions-interview-41499/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2022/07/veronicas.jpg?resize=900,600&w=1200?resize=900,600&w=1200&nologo=1">
                                            <h4>‘Music Can Be So Healing’</h4>
                                            <p>The Veronicas Look Forward to Intimate Jim Beam Welcome Sessions Gig.</p>
                                        </a>
                                    </div>

                                    <div class="col-12 col-md-4 mb-4 pr-0 pr-md-2 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/music/music-news/win-tickets-to-see-the-veronicas-41491/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2022/07/veronicas.jpg?resize=900,600&w=1200?resize=900,600&w=1200&nologo=1">
                                            <h4>Win Tickets to See The Veronicas at Jim Beam’s Welcome Sessions Event in Melbourne</h4>
                                            <p>Seventy-five double passes are up for grabs to see The Veronicas perform as part of the Jim Beam Welcome Sessions in Melbourne.</p>
                                        </a>
                                    </div>

                                    <div class="col-12 col-md-4 mb-4 pr-0 pr-md-2 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/music/music-live-reviews/client-liaison-jim-beam-welcome-sessions-review-41544/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2022/07/Welcome-Sessions-x-Client-Liason-Mitch-Lowe-Photo-1-47.jpg?resize=900,600&w=1200&nologo=1">
                                            <h4>Client Liaison Continue the Jim Beam Welcome Sessions with An Iconic Brisbane Show</h4>
                                            <p>Client Liaison took to the stage in Brisbane for a massive DJ set as part of the ongoing Jim Beam Welcome Sessions.</p>
                                        </a>
                                    </div>

                                    <div class="col-12 col-md-4 mb-4 px-0 px-md-2 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/music/music-features/client-liaison-interview-jim-beam-welcome-sessions-41480/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2021/08/client-liaison.jpg?resize=900,600&w=1200&nologo=1">
                                            <h4>‘We’re Going to Pump It Up’: Client Liaison Promise to Bring the Heat for Jim Beam Welcome Sessions</h4>
                                            <p>Client Liaison will be performing a DJ set on Saturday July 16th at Buffalo Bar, Brisbane.</p>
                                        </a>
                                    </div>

                                    <div class="col-12 col-md-4 mb-4 pr-0 pr-md-2 d-flex align-items-stretch">
                                        <a href="https://tonedeaf.thebrag.com/client-liaison-at-jim-beam-welcome-sessions-brisbane/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/td/uploads/2022/06/client-liaison.jpg?resize=900,600&w=1200&nologo=1">
                                            <h4>Win tickets to see Client Liaison at Jim Beam's Welcome Sessions event in Brisbane</h4>
                                            <p>Win tickets to see Flight Facilities and Client Liaison in Jim Beam's Welcome Sessions 2022.</p>
                                        </a>
                                    </div>

                                    <div class="col-12 col-md-4 mb-4 pr-0 pr-md-2 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/music/music-features/flight-facilities-jim-beam-welcome-sessions-40687/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2021/05/flight-facilities.jpg?resize=900,600&w=1200&nologo=1">
                                            <h4>Flight Facilities Get Back To Their Roots For An Early Afternoon DJ Set For Jim Beam Welcome Sessions</h4>
                                            <p>Courtesy of Jim Beam Welcome Sessions, Flight Facilities are heading back to the city that birthed them for an early afternoon DJ set on Saturday, June 11th at The Rooftop Sydney.</p>
                                        </a>
                                    </div>

                                    <div class="col-12 col-md-4 mb-4 px-0 px-md-2 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/music/music-news/win-tickets-to-see-flight-facilities-at-jim-beams-welcome-sessions-event-in-sydney-40529/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2021/05/flight-facilities.jpg?resize=900,600&w=1200&nologo=1">
                                            <h4>Win tickets to see Flight Facilities at Jim Beam’s Welcome Sessions event in Sydney</h4>
                                            <p>Jim Beam gives you the chance to win tickets to attend an intimate Welcome Sessions DJ Set performance by Flight Facilities in Sydney.</p>
                                        </a>
                                    </div>
                                    
                                    <div class="col-12 col-md-4 mb-4 pl-0 pl-md-2 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/culture/culture-news/jim-beam-welcome-sessions-returns-in-2022-40385/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2021/05/Rolling-stone-jim-beam-welcome-sessions-2022-1.jpg&nologo=1">
                                            <h4>Jim Beam Welcome Sessions returns with a bang in 2022</h4>
                                            <p>Jim Beam has announced the lineup of Australian acts that will join their 2022 Welcome Sessions</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="latest-news bg-gray px-2 px-md-4 py-2 py-md-3">
                        <div class="content-wrap">
                            <div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch my-3">
                                <div class="col-12">
                                    <div class="mb-4 mb-md-2 mr-0 mr-md-1">
                                        <h2 class="d-flex justify-content-start" style="margin-bottom: 1rem">
                                            <span class="text-kuunari-bold" style="font-size: 175%; line-height: 100%; text-decoration: underline;">GLOBAL WELCOME</span>
                                            &nbsp;
                                            <span class="text-kuunari-condensed" style="font-size: 300%; line-height: 100%;">SESSIONS</span>
                                        </h2>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column my-4">
                                <div style="width: 1px; height: 70px; background-color: #ccc;"></div>
                                <h3 class="mt-1">MUSE's Return home</h3>
                                <div class="content-wrap my-2 d-flex">
                                    <div class="text-center" style="width: 890px; max-width: 100%;font-family: Graphik-Medium, sans-serif;">
                                        <div>
                                            <iframe width="890" height="501" src="https://www.youtube.com/embed/1cUPh2XroWw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                        <p class="mt-1 mb-2">A celebration of the community that made Muse the band they are today, through a once-in-a-lifetime show bringing Muse back to their first stage - thanks to Jim Beam</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column my-4">
                                <div style="width: 1px; height: 70px; background-color: #ccc;"></div>
                                <h2 class="mt-1 text-kuunari-condensed" style="color: rgb(149, 149, 149)">Episode 1</h2>
                                <h3 class="mt-1">MUSE LEAVING HOME</h3>
                                <div class="content-wrap my-2 d-flex">
                                    <div class="text-center" style="width: 890px; max-width: 100%;font-family: Graphik-Medium, sans-serif;">
                                        <div>
                                            <iframe width="890" height="501" src="https://www.youtube.com/embed/81_f87i3Gos" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                        <p class="mt-1 mb-2">Witnessing a music community reborn in real time, Jim Beam celebrates the people behind every artist.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column my-4">
                                <div style="width: 1px; height: 70px; background-color: #ccc;"></div>
                                <h2 class="mt-1 text-kuunari-condensed" style="color: rgb(149, 149, 149)">Episode 2</h2>
                                <h3 class="mt-1">LYRICAL COMMUNION</h3>
                                <div class="content-wrap my-2 d-flex">
                                    <div class="text-center" style="width: 890px; max-width: 100%;font-family: Graphik-Medium, sans-serif;">
                                        <div>
                                            <iframe width="890" height="501" src="https://www.youtube.com/embed/fVaqQV2xCKM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                        <p class="mt-1 mb-2">In this episode of #WelcomeSessions, we see how lyrics have the power to connect us, to unite us in a moment. Regardless of where you come from or what language you speak, music empowers us to come together over something we love.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column my-4">
                                <div style="width: 1px; height: 70px; background-color: #ccc;"></div>
                                <h2 class="mt-1 text-kuunari-condensed" style="color: rgb(149, 149, 149)">Episode 3</h2>
                                <h3 class="mt-1">THE FAN COMMUNITY</h3>
                                <div class="content-wrap my-2 d-flex">
                                    <div class="text-center" style="width: 890px; max-width: 100%;font-family: Graphik-Medium, sans-serif;">
                                        <div>
                                            <iframe width="890" height="501" src="https://www.youtube.com/embed/ZhsSD1ZjEGU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                        <p class="mt-1 mb-2">Witnessing a music community reborn in real time, Jim Beam celebrates the people behind every artist.</p>
                                    </div>
                                </div>
                            </div>


                            <div class="d-flex flex-column">
                                <div class="col-12 col-md-8 d-flex flex-column flex-md-row align-items-stretch my-3">
                                    <div class="col-12 col-md-8 mb-2 px-0 px-md-1 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/music/music-features/more-than-just-the-music-how-muse-inspire-unity-among-their-most-dedicated-fans-42377/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2021/08/muse-JBWS-Sam-Grant.jpg&nologo=1">
                                            <h4 class="text-center mt-1 mt-md-2 mt-lg-3">More Than Just the Music:</h4>
                                            <p class="text-center">How Muse Inspire Unity Among Their Most Dedicated Fans.</p>
                                        </a>
                                    </div>
                                    <div class="col-12 col-md-6 mb-2 px-0 px-md-1 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/music/music-features/muse-return-to-the-venue-where-it-all-started-42355/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2022/05/muse-jim-beam-welcome-sessions.jpg&nologo=1">
                                            <h4 class="text-center mt-1 mt-md-2 mt-lg-3">‘It Was Like Time Travel’:</h4>
                                            <p class="text-center">Muse Return to the Venue Where It All Started.</p>
                                        </a>
                                    </div>
                                    <div class="col-12 col-md-6 mb-2 px-0 px-md-1 d-flex align-items-stretch">
                                        <div class="news">
                                            <div class="video-placehoder-wrap mb-4 d-flex flex-column">
                                                <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://cdn-r2-2.thebrag.com/pages/jim-beam-2022/images/video-1.1.jpg&nologo=1">
                                                <div class="my-2 text-center">
                                                    <div class="" style="border-bottom: 2px solid rgb(213, 30, 41)">
                                                        <p class="ls-1">WATCH THE FULL PERFORMANCE</span></p>
                                                    </div>
                                                    <h4 class="text-kuunari-bold">Muse at Cavern Exeter</h4>
                                                </div>
                                                <button class="btn-watch-now" id="btn-watch-now-1" data-target="video-1">Watch Now</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mb-2 px-0 px-md-1 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/music/music-features/jim-beam-reconnects-muse-to-the-community-that-raised-them-40650/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2022/05/muse-jim-beam-welcome-sessions.jpg&nologo=1">
                                            <h4 class="text-center mt-1 mt-md-2 mt-lg-3">Jim Beam Reconnects Muse to the Community that Raised Them</h4>
                                            <p class="text-center">Jim Beam brings Muse home to a tiny stage in the South West of England, reuniting them to the early community that created the global band we know today.</p>
                                        </a>
                                    </div>
                                    <div class="col-12 col-md-6 mb-2 px-0 px-md-1 d-flex align-items-stretch">
                                        <a href="https://au.rollingstone.com/music/music-features/muse-fosters-community-between-the-lines-of-your-favourite-song-41487/" target="_blank" class="news">
                                            <img src="https://images.thebrag.com/cdn-cgi/image/fit=crop,width=1200,height=628/https://images-r2.thebrag.com/rs/uploads/2022/07/SamGrant_220608_JBWS_Muse_ViennaBerlin_Day3_4643-3-1.jpg&nologo=1">
                                            <h4 class="text-center mt-1 mt-md-2 mt-lg-3">Muse Fosters Community Between the Lines of Your Favourite Song</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="latest-news px-2 px-md-4 py-2 py-md-3">
                        <div class="content-wrap">
                            <div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch my-3">
                                <div class="col-12 col-md-8">
                                    <div class="mb-4 mb-md-2 mr-0 mr-md-1">
                                        <h2 class="d-flex justify-content-start" style="margin-bottom: 1rem">
                                            <span class="text-kuunari-bold" style="font-size: 175%; line-height: 100%; text-decoration: underline;">WELCOME SESSIONS</span>
                                            &nbsp;
                                            <span class="text-kuunari-condensed" style="font-size: 300%; line-height: 100%;">2021</span>
                                        </h2>
                                        <a href="https://au.rollingstone.com/music/music-features/revisiting-jim-beam-welcome-sessions-31850/" target="_blank" class="news">
                                            <img src="https://images-r2.thebrag.com/rs/uploads/2021/10/jim-beam-welcome-sessions-RS.jpg?resize=900,600&w=1200">
                                            <h4>Revisiting the Jim Beam Welcome Sessions</h4>
                                            <p>Connecting world-renowned artists with the venues that first fostered them, the Jim Beam Welcome Sessions was a much-needed respite in the drought of live music.</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 d-flex align-items-stretch">
                                    <div class="d-flex flex-column justify-content-between">
                                        <a href="https://au.rollingstone.com/music/music-features/wolf-alice-come-full-circle-with-a-breathtaking-performance-31418/" target="_blank" class="news mb-2 mb-md-2 ml-0 ml-md-2 d-flex align-items-stretch">
                                            <img src="https://images-r2.thebrag.com/rs/uploads/2021/09/wolf-alice-RS-2.jpg?resize=900,600&w=1200">
                                            <h4 class="side">In a Gothic Chapel, Wolf Alice Come Full Circle with a Breathtaking Performance</h4>
                                        </a>

                                        <a href="https://au.rollingstone.com/music/music-features/wolf-alice-jim-beam-welcome-sessions-29868/" target="_blank" class="news mb-2 mb-md-2 ml-0 ml-md-2 d-flex align-items-stretch">
                                            <img src="https://images-r2.thebrag.com/rs/uploads/2021/09/wolf-alice-RS-1-1.jpg?resize=900,600&w=1200">
                                            <h4 class="side">Wolf Alice descend upon Union Chapel for an exclusive performance of ‘Lipstick on the Glass’</h4>
                                        </a>
                                    </div>
                                </div>

                                <a href="https://au.rollingstone.com/music/music-features/jose-gonzalezs-home-away-from-home-29253/" target="_blank" class="col-12 col-md-4 mt-2 mb-md-2 mr-0 d-flex align-items-stretch">
                                    <div class="mr-0 mr-md-2 news">
                                        <img src="https://images-r2.thebrag.com/rs/uploads/2021/08/Jose-RS.jpg?resize=1400,700&w=1400">
                                        <h4 class="side">José González’s Home Away From Home</h4>
                                    </div>
                                </a>

                                <a href="https://au.rollingstone.com/music/music-news/jose-gonzalez-jim-beam-welcome-sessions-28542/" target="_blank" class="col-12 col-md-4 mt-2 mb-md-2 mx-0 d-flex align-items-stretch">
                                    <div class="mx-0 mx-md-1 news">
                                        <img src="https://images-r2.thebrag.com/rs/uploads/2021/08/JBWS_JOSE_GONZALES_HALO_by_Nick_Helderman_07.jpg?resize=900,600&w=1200">
                                        <h4 class="side">Watch José González return to Berlin’s The Michelberger for exclusive performance</h4>
                                    </div>
                                </a>

                                <a href="https://au.rollingstone.com/music/music-news/fontaines-dc-interview-27552/" target="_blank" class="col-12 col-md-4 mt-2 mb-md-2 ml-0 d-flex align-items-stretch">
                                    <div class="ml-0 ml-md-2 news">
                                        <img src="https://images-r2.thebrag.com/rs/uploads/2021/07/fontaines-rs2.jpg?resize=1400,700&w=1400">
                                        <h4 class="side">How Fontaines D.C. Ascended from an Infamous North London Dive Bar to the World Stage</h4>
                                    </div>
                                </a>

                                <a href="https://au.rollingstone.com/music/music-news/how-jack-garratt-welcomed-the-world-to-his-long-awaited-return-to-stage-2-26783/" target="_blank" class="col-12 col-md-4 mt-2 mb-md-2 mr-0 d-flex align-items-stretch">
                                    <div class="mr-0 mr-md-2 news">
                                        <img src="https://images-r2.thebrag.com/rs/uploads/2021/05/042121_JACK_GARRATT_HERO_035-scaled.jpg?resize=1400,700&w=1400">
                                        <h4 class="side">How Jack Garratt Welcomed the World to his Long-Awaited Return to Stage</h4>
                                    </div>
                                </a>

                                <a href="https://au.rollingstone.com/music/music-news/jack-garratt-jim-beam-welcome-sessions-26231/" target="_blank" class="col-12 col-md-4 mt-2 mb-md-2 mx-0 d-flex align-items-stretch">
                                    <div class="mx-0 mx-md-1 news">
                                        <img src="https://images-r2.thebrag.com/rs/uploads/2021/06/jack-garratt-rs.jpg?resize=900,600&w=1200">
                                        <h4 class="side">Watch Jack Garratt Return Home for Special One-Off Performance</h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </section>

                    <section class="latest-news bg-gray px-2 px-md-4 py-2 py-md-3">
                        <div class="content-wrap">
                            <div class="d-flex flex-wrap flex-column flex-md-row align-items-stretch my-3">
                                <div class="col-12 col-md-8">
                                    <div class="mb-2 mb-md-2 mr-0 mr-md-1">
                                        <h2 class="d-flex justify-content-start" style="margin-bottom: 1rem">
                                            <span class="text-kuunari-bold" style="font-size: 175%; line-height: 100%; text-decoration: underline;">TRENDING</span>
                                            &nbsp;
                                            <span class="text-kuunari-condensed" style="font-size: 300%; line-height: 100%;">NEWS</span>
                                        </h2>
                                        <a href="hhttps://au.rollingstone.com/music/music-features/lime-cordiale-facts-of-life-interview-40488/" target="_blank" class="news">
                                            <img src="https://images-r2.thebrag.com/rs/uploads/2022/05/Lime-Cordiale-Facts-of-Life.jpg?resize=900,600&w=1200">
                                            <h4>‘It’s Just Been a Progressive Thing’: Lime Cordiale’s Oli and Louis Leimbach on the Band’s Continued Ascent</h4>
                                            <p>Rolling Stone Australia caught up with Lime Cordiale members Oli and Louis Leimbach.</p>
                                        </a>
                                    </div>
                                </div>
<!--                                 <div class="col-12 col-md-4 d-flex align-items-stretch">
                                    <div class="d-flex flex-column justify-content-between">
                                        <a href="https://au.rollingstone.com/music/music-album-reviews/client-liaison-divine-intevention-review-31568/" target="_blank" class="news mb-2 mb-md-2 ml-0 ml-md-2">
                                            <img src="https://images-r2.thebrag.com/rs/uploads/2021/08/client-liaison.jpg">
                                            <h4 class="side">Client Liaison Unleash Pure Joy With ‘Divine Intervention’</h4>
                                            <p>Their long-awaited second album, ‘Divine Intervention’ is everything you want from a Client Liaison record, and so much more.</p>
                                        </a>

                                        <a href="https://au.rollingstone.com/music/music-news/client-liaison-groove-is-in-the-heart-like-a-version-29119/" target="_blank" class="news mb-2 mb-md-2 ml-0 ml-md-2">
                                            <img src="https://images-r2.thebrag.com/rs/uploads/2021/08/clientliaison.jpg">
                                            <h4 class="side">Client Liaison Take On Deee-Lite’s ‘Groove Is In The Heart’ for Like a Version</h4>
                                        </a>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </section>

                    <section class="px-2 px-md-4 py-2 py-md-4">
                        <div class="content-wrap py-4">
                            <div class="d-flex flex-column flex-md-row align-items-stretch">
                                <div class="col-12 col-md-4">
                                    <h2 class="d-flex justify-content-start" style="margin-bottom: 1rem">
                                        <span class="text-kuunari-bold" style="font-size: 175%; line-height: 100%;">Listen to the official Jim Beam Welcome Sessions playlist</span>
                                    </h2>
                                </div>
                                <div class="col-12 col-md-8">
                                    <div class="mt-3 mt-md-0">
                                        <iframe style="border-radius:12px" src="https://open.spotify.com/embed/playlist/4k3h2KUlFfnH2JWB6UuJLS?utm_source=generator" width="100%" height="380" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

            </div><!-- #content-wrap -->
        <?php
                endif; // Over 18 check
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
            endif; // Password protected
        endwhile;
        wp_reset_query();
    endif;

?>
<?php get_template_part('template-parts/footer/footer'); ?>
</div><!-- .l-page__content -->

<script>
    jQuery(document).ready(function($) {

        if ($('#age-gate-year').length)
            $('#age-gate-year').focus();

        $('.btn-watch-now').on('click', function() {
            var target = $(this).data('target');
            if (!target) return;
            var targetElem = $('#' + target);
            if (!targetElem.length) return;
            $('#page-overlay').show();
            targetElem.show();
        })

        // $('#btn-watch-now-1').trigger('click');

        $('.btn-close-video').on('click', function() {
            var target = $(this).data('target');
            if (!target) return;
            var targetElem = $('#' + target);
            if (!targetElem.length) return;
            targetElem.find('iframe').each(function(i, e2) {
                this.src = this.src;
            });
            $('#page-overlay').hide();
            targetElem.hide();
        })
        $('#page-overlay').on('click', function() {
            $(this).hide();
            $('.video-wrap').hide();
            $('.video-wrap').each(function(i, e) {
                $(e).find('iframe').each(function(i, e2) {
                    this.src = this.src;
                });
            });
        })
    })
</script>
<script>
    jQuery(document).ready(function($) {
        iFrameResize({
            log: true,
            // autoResize: false,
            minHeight: 1100
        }, '#jb2023hub')
    });
</script>
<?php
get_footer();
