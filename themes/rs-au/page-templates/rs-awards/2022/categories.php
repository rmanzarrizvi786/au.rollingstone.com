<?php
extract($args);
$current_url = home_url(add_query_arg([], $GLOBALS['wp']->request));
?>

<?php if ($noms_open) : ?>
    <?php if (isset($context) && 'nominate' == $context) : ?>
        <div class="text-center mt-5 mb-3">
            <img src="<?php echo get_template_directory_uri(); ?>/images/Nominate.jpg" alt="Nominate">
            <p>
                <a href="https://theindustryobserver.thebrag.com/nominate-for-rolling-stone-awards-2022/?a=add" target="_blank" class="p-2 btn-nominate">
                    Click Here to Nominate
                </a>
            </p>
        </div>
    <?php endif; ?>
<?php else : ?>
    <div id="timer-awards-noms-open" class="d-none">
        <div class="days d-flex flex-column mx-1 mx-md-2"></div>
        <div class="sep">:</div>
        <div class="hours d-flex flex-column mx-1 mx-md-2"></div>
        <div class="sep">:</div>
        <div class="minutes d-flex flex-column mx-1 mx-md-2"></div>
        <div class="sep">:</div>
        <div class="seconds d-flex flex-column mx-1 mx-md-2"></div>
    </div>

    <h2 class="text-center text-dark my-3">Nominations will be open at <?php echo date('h:ia', strtotime($noms_open_at)); ?> on <?php echo date('d M, Y', strtotime($noms_open_at)); ?>.</h2>

    <div class="how-to-nominate d-flex flex-column mt-3 py-2">
        <div>
            <img src="<?php echo get_template_directory_uri(); ?>/images/rsa2022/how-to-nominate.png" alt="How to Nominate">
        </div>
        <div class="d-flex align-items-stretch">
            <div class="ml-2">

                <ol class="py-0 my-0">
                    <li><a href="<?php echo esc_url(wp_login_url($current_url)); ?>">Login</a></li>
                    <li>You can nominate artists in multiple categories at once. You will need to provide these details;
                        <ul class="mb-0" style="list-style: disc; padding-left: 2rem;">
                            <li>Artist name</li>
                            <li>Release date</li>
                            <li>Single/Record/Album name</li>
                            <li>List commercial success of release</li>
                            <li>Link to Spotify</li>
                        </ul>
                    </li>
                    <li>You can nominate multiple artists in same category at once. Simply click "+ Add" button and add details.</li>
                    <li>Finally, remember to click "Submit" to submit your entries. You should receive cofirmation email shortly after.</li>
                    <li>To add more nominations or edit your nominations, you can come back to this page and do so before nominations close.</li>
                </ol>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="rsa-content my-2">
    <h2 class="text-center my-3">THE CATEGORIES</h2>
    <p class="text-center" style="font-size: 125%;">You will decide the nominations of the Sailor Jerry Rolling Stone Australia Awards.</p>

    <div class="d-flex flex-wrap align-items-stretch mb-3">
        <?php foreach ($award_categories as $award_category) : ?>
            <div class="col-md-6 mt-2">
                <div class="mt-2 mx-0 mx-md-1 px-2 py-2 card h-100" style="border: 2px solid #000;">
                    <div class="card-body d-flex">
                        <?php if ($noms_open) : ?>
                            <a href="https://theindustryobserver.thebrag.com/nominate-for-rolling-stone-awards-2022/?a=add" target="_blank" style="color: #333;">
                            <?php endif; ?>
                            <div class="d-flex flex-column text-center">
                                <div class="mb-2"><img src="<?php echo ICONS_URL; ?>star.svg" width="32" height="32" style="filter: invert(1);"></div>
                                <h3 class="text-uppercase mb-3" style="font-weight: 800;"><?php echo $award_category->title; ?></h3>
                                <div class=""><?php echo wpautop($award_category->description); ?></div>
                                <?php if ($noms_open) : ?>
                                    <div class="my-3" style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);">
                                        <h4><i class="fas fa-angle-down"></i></h4>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if ($noms_open) : ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if (0 && isset($context) && 'info' == $context) : ?>
    <div class="text-center mt-4 pt-4 mb-3">
        <img src="<?php echo get_template_directory_uri(); ?>/images/Nominate.jpg" alt="Nominate">
        <p>
            <a href="https://theindustryobserver.thebrag.com/nominate-for-rolling-stone-awards-2022/?a=add" target="_blank" class="p-2 btn-nominate">
                Click Here to Nominate
            </a>
        </p>
    </div>
<?php endif; ?>