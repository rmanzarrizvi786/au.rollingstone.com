<?php
extract($args);
?>
<div id="my-noms">
    <h2 class="text-center my-4">My Nominations</h2>
    <p class="text-center mb-3">Add or edit your nominations at any time here.</p>
    <?php echo $intro_block; ?>
    <div class="d-flex justify-content-center mt-3">
        <a href="<?php echo add_query_arg('a', 'add', remove_query_arg('success')); ?>" class="btn btn-outline-primary mr-2">Add new nominations</a>
        <a href="<?php echo add_query_arg('a', 'edit', remove_query_arg('success')); ?>" class="btn btn-outline-primary ml-2">Edit my nominations</a>
    </div>
    <div class="row">
        <div class="col-12 my-3">
            <div class="accordion" id="accordionAwardsNoms">
                <?php foreach ($award_categories as $award_category) : ?>
                    <div class="card mb-3">
                        <div class="card-header p-0" id="awardnoms-heading<?php echo $award_category->id; ?>">
                            <button class="btn bg-dark rounded-top text-primary" type=" button" data-toggle="collapse" data-target="#awardnoms<?php echo $award_category->id; ?>" aria-expanded="true" aria-controls="awardnoms<?php echo $award_category->id; ?>" style="<?php echo isset($my_noms[$award_category->id]) && is_array($my_noms[$award_category->id]) && !empty($my_noms[$award_category->id]) ? 'background-color: rgba(0, 0, 0, 0.025) !important;' : ''; ?>">
                                <span class="arrow-down"><img src="<?php echo ICONS_URL; ?>icon_arrow-down-rs.svg" class="rotate180"></span>
                                <span class="ml-1"><?php echo $award_category->title; ?></span>
                            </button>
                        </div>

                        <div id="awardnoms<?php echo $award_category->id; ?>" class="awardnoms show" aria-labelledby="awardnoms-heading<?php echo $award_category->id; ?>" data-id="<?php echo $award_category->id; ?>">
                            <?php if (isset($my_noms[$award_category->id]) && is_array($my_noms[$award_category->id]) && !empty($my_noms[$award_category->id])) : ?>
                                <div>
                                    <div class="card-body bg-dark text-white">

                                        <?php foreach ($my_noms[$award_category->id] as $my_nom) : ?>

                                            <div class="fields-wrap p-2 pb-4">

                                                <?php for ($i = 1; $i <= 4; $i++) :
                                                    $field_i = 'field_' . $i;
                                                ?>
                                                    <div class="my-2">
                                                        <strong><?php echo $award_category->$field_i; ?></strong>:
                                                        <?php
                                                        if ($i == 4) {
                                                        ?>
                                                            <div class="textarea-partial">
                                                                <?php echo wpautop($my_nom->$field_i); ?>
                                                            </div>
                                                        <?php
                                                        } else if ($i == 2) {
                                                            echo date('d M, Y', strtotime($my_nom->$field_i));
                                                        } else {
                                                            echo $my_nom->$field_i;
                                                        }
                                                        ?>
                                                    </div>

                                                <?php endfor; ?>

                                                <?php if ($award_category->id <= 3 && isset($my_nom->field_5) && !is_null($my_nom->field_5) && '' != $my_nom->field_5) : ?>
                                                    <div>
                                                        <strong><?php echo $award_category->field_5; ?></strong>:
                                                        <a href="<?php echo $my_nom->field_5; ?>" target="_blank"><?php echo $my_nom->field_5; ?></a>
                                                    </div>
                                                <?php endif; ?>

                                            </div>

                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="card">
                                    <div class="card-body">
                                        <!-- <div class="text-center">-</div> -->
                                        <div class="text-center border-bottom pt-1 pb-3 mb-3">
                                            <a href="<?php echo add_query_arg('a', 'add', remove_query_arg('success')); ?>" target="_blank" class="btn btn-outline-primary text-white mt-3" style="display: inline-block;">Add new nominations</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>
        </div>
    </div>
    <!-- <div class="d-flex justify-content-center">
        <a href="<?php echo add_query_arg('a', 'add', remove_query_arg('success')); ?>" class="btn bg-primary text-white mr-2">Add new nominations</a>
        <a href="<?php echo add_query_arg('a', 'edit', remove_query_arg('success')); ?>" class="btn bg-primary text-white ml-2">Edit my nominations</a>
    </div> -->
</div>