<?php
extract($args);
?>
<div id="nomination-entries-edit">
    <h2 class="text-center my-4">Edit Nominations</h2>
    <?php echo $intro_block; ?>

    <form method="post" action="" class="mt-3">
        <input type="hidden" name="action" value="edit">
        <div class="accordion" id="accordionAwardsNoms">
            <?php foreach ($award_categories as $award_category) : ?>
                <div class="card mb-4 counter-wrap">
                    <div class="card-header p-0" id="awardnoms-heading<?php echo $award_category->id; ?>" style="border-radius: 0 !important;">
                        <button class="btn bg-dark text-primary rounded-top" type="button" data-toggle="collapse" data-target="#awardnoms<?php echo $award_category->id; ?>" aria-expanded="true" aria-controls="awardnoms<?php echo $award_category->id; ?>">
                            <span class="arrow-down"><img src="<?php echo ICONS_URL; ?>icon_arrow-down-rs.svg" class="rotate180"></span>
                            <span class="ml-1"><?php echo $award_category->title; ?></span>
                        </button>
                    </div>

                    <div id="awardnoms<?php echo $award_category->id; ?>" class="awardnoms show" aria-labelledby="awardnoms-heading<?php echo $award_category->id; ?>" data-id="<?php echo $award_category->id; ?>">
                        <div class="card-body bg-dark text-white">
                            <div class="mx-2 my-2"><?php echo wpautop($award_category->description); ?></div>
                            <div id="form-wrap2-<?php echo $award_category->id; ?>">
                                <?php
                                if (isset($my_noms[$award_category->id]) && is_array($my_noms[$award_category->id]) && !empty($my_noms[$award_category->id])) {
                                    foreach ($my_noms[$award_category->id] as $counter => $my_nom) {
                                ?>
                                        <div class="clone p-2 pb-4 fields-wrap">
                                            <div class="d-flex">
                                                <div class="counter">
                                                    <?php echo $counter + 1; ?>
                                                </div>
                                                <div class="text-right flex-fill">
                                                    <button type="button" class="btn btn-sm btn-danger btn-remove py-0">-</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <?php if (!is_null($my_nom->field_1)) : ?>
                                                    <div class="col-12 col-md-4 pr-0 pr-md-1 mt-1 mt-md-2">
                                                        <input type="text" name="fields[<?php echo $award_category->id; ?>][field_1][]" class="form-control" value="<?php echo isset($formdata) && isset($formdata['fields'][$award_category->id]['field_1'][$counter]) ? $formdata['fields'][$award_category->id]['field_1'][$counter] : $my_nom->field_1; ?>">
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!is_null($my_nom->field_2)) : ?>
                                                    <div class="col-12 col-md-4 px-0 px-md-1 mt-1 mt-md-2">
                                                        <?php if ($award_category->id == 4) : ?>
                                                            <input type="text" name="fields[<?php echo $award_category->id; ?>][field_2][]" class="form-control datepicker2" value="<?php echo isset($formdata) && isset($formdata['fields'][$award_category->id]['field_2'][$counter]) ? $formdata['fields'][$award_category->id]['field_2'][$counter] : date('d M Y', strtotime($my_nom->field_2)); ?>">
                                                        <?php else :  ?>
                                                            <input type="text" name="fields[<?php echo $award_category->id; ?>][field_2][]" class="form-control datepicker" value="<?php echo isset($formdata) && isset($formdata['fields'][$award_category->id]['field_2'][$counter]) ? $formdata['fields'][$award_category->id]['field_2'][$counter] : date('d M Y', strtotime($my_nom->field_2)); ?>">
                                                        <?php endif;  ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!is_null($my_nom->field_3)) : ?>
                                                    <div class="col-12 col-md-4 pl-0 pl-md-1 mt-1 mt-md-2">
                                                        <input type="text" name="fields[<?php echo $award_category->id; ?>][field_3][]" class="form-control" value="<?php echo isset($formdata) && isset($formdata['fields'][$award_category->id]['field_3'][$counter]) ? $formdata['fields'][$award_category->id]['field_3'][$counter] : $my_nom->field_3; ?>">
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="row">
                                                <?php if (!is_null($my_nom->field_4)) : ?>
                                                    <div class="col-12 mt-1 mt-md-2">
                                                        <textarea name="fields[<?php echo $award_category->id; ?>][field_4][]" class="form-control"><?php echo isset($formdata) && isset($formdata['fields'][$award_category->id]['field_4'][$counter]) ? $formdata['fields'][$award_category->id]['field_4'][$counter] : $my_nom->field_4; ?></textarea>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="row">
                                                <?php if (!is_null($my_nom->field_5)) : ?>
                                                    <div class="col-12 mt-1 mt-md-2">
                                                        <input type="text" name="fields[<?php echo $award_category->id; ?>][field_5][]" class="form-control" value="<?php echo isset($formdata) && isset($formdata['fields'][$award_category->id]['field_5'][$counter]) ? $formdata['fields'][$award_category->id]['field_5'][$counter] : $my_nom->field_5; ?>">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php
                                    } // For each $my_nom in edit
                                } // if $my_noms[$award_category->id] is set and not empty
                                else {
                                    ?>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach;
            ?>
        </div>

        <div style="text-align: center;">
            <button type="submit" class="btn btn-success rounded mt-3" id="btn-submit" name="submit">Submit</button>
        </div>
    </form>

    <div class="text-center py-1 border-top mt-3" style="border-top: 1px solid rgba(0,0,0,.15)">
        <a href="<?php echo add_query_arg('a', 'add'); ?>" target="_blank" class="btn btn-outline-primary mt-3" style="display: inline-block;">Add new nominations</a>
    </div>
</div>