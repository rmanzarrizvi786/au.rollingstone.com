<?php
extract($args);
$current_url = home_url(add_query_arg([], $GLOBALS['wp']->request));

$noms_open = false;
?>

<div class="rsa-content my-2">
    <h2 class="text-center my-3 d-flex" style="font-family: GraphikXCondensed-BoldItalic; font-size: 250%;">
        <span class="mx-2">THE CATEGORIES</span>
    </h2>
    <p class="text-center" style="font-size: 125%;">You will decide the nominations of the Panhead 2023 Rolling Stone Aotearoa Awards.</p>

    <div class="d-flex flex-wrap align-items-stretch mb-3">
        <?php foreach ($award_categories as $award_category) : ?>
            <div class="col-md-6 mt-2">
                <div class="mt-2 mx-0 mx-md-1 px-2 py-2 card h-100" style="border: 2px solid #000;">
                    <div class="card-body d-flex">
                        <div class="d-flex flex-column text-center">
                            <div class="mb-2"><img src="<?php echo ICONS_URL; ?>star.svg" width="32" height="32" style="filter: invert(1);"></div>
                            <h3 class="text-uppercase mb-3" style="font-weight: 800;"><?php echo $award_category->title; ?></h3>
                            <div class=""><?php echo wpautop($award_category->description); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>