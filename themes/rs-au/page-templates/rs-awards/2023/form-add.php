<?php
extract($args);
?>
<div id="nomination-entries">
    <h2 class="text-center my-4">Add Nominations</h2>
    <?php echo $intro_block; ?>

    <form method="post" action="" class="mt-3">
        <input type="hidden" name="action" value="add">
        <div class="accordion" id="accordionAwardsNoms">
            <?php foreach ($award_categories as $award_category) : ?>
                <div class="card mb-4 counter-wrap" style="border-radius: 0 !important;">
                    <div class="card-header p-0" id="awardnoms-heading<?php echo $award_category->id; ?>" style="border-radius: 0 !important;">
                        <button class="btn bg-dark text-primary rounded-top" type="button" data-toggle="collapse" data-target="#awardnoms<?php echo $award_category->id; ?>" aria-expanded="true" aria-controls="awardnoms<?php echo $award_category->id; ?>">
                            <span class="arrow-down"><img src="<?php echo ICONS_URL; ?>icon_arrow-down-rs.svg" class="rotate180"></span>
                            <span class="ml-1"><?php echo $award_category->title; ?></span>
                        </button>
                    </div>

                    <div id="awardnoms<?php echo $award_category->id; ?>" class="awardnoms <?php echo (isset($formdata) && is_array($formdata) && !empty($formdata)) ? 'show' : ''; ?>" aria-labelledby="awardnoms-heading<?php echo $award_category->id; ?>" data-id="<?php echo $award_category->id; ?>">
                        <div class="card-body bg-dark text-white py-2">
                            <div class="mx-2"><?php echo wpautop($award_category->description); ?></div>
                            <div id="form-wrap2-<?php echo $award_category->id; ?>">
                                <?php
                                if (isset($formdata) && is_array($formdata) && !empty($formdata) && isset($formdata['fields']) && !empty($formdata['fields'])) :

                                    $count = count($formdata['fields'][$award_category->id]['field_1']);

                                    for ($counter = 0; $counter < $count; $counter++) :

                                        $v_cat = $formdata['fields'][$award_category->id];
                                ?>
                                        <div class="<?php echo $counter == 0 ? 'clonable' : 'clone'; ?> fields-wrap p-2 pb-4">
                                            <?php if ($counter > 0) : ?>
                                                <hr>
                                                <div class="text-right">
                                                    <div class="btn btn-sm btn-danger btn-remove py-0">-</div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="row">
                                                <?php if (!is_null($award_category->field_1)) : ?>
                                                    <div class="col-12 col-md-4 pr-0 pr-md-1 mt-1 mt-md-2">
                                                        <input type="text" name="fields[<?php echo $award_category->id; ?>][field_1][]" class="form-control" placeholder="<?php echo $award_category->field_1; ?>" value="<?php echo isset($v_cat['field_1'][$counter]) && '' != trim($v_cat['field_1'][$counter]) ? stripslashes(esc_attr($v_cat['field_1'][$counter])) : ''; ?>">
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!is_null($award_category->field_2)) : ?>
                                                    <div class="col-12 col-md-4 px-0 px-md-1 mt-1 mt-md-2">
                                                        <?php if ($award_category->id == 4) : ?>
                                                            <input type="text" name="fields[<?php echo $award_category->id; ?>][field_2][]" class="form-control datepicker2" placeholder="<?php echo $award_category->field_2; ?>" value="<?php echo isset($v_cat['field_2'][$counter]) && '' != trim($v_cat['field_2'][$counter]) ? stripslashes(esc_attr($v_cat['field_2'][$counter])) : ''; ?>" autocomplete="off">
                                                        <?php else : ?>
                                                            <input type="text" name="fields[<?php echo $award_category->id; ?>][field_2][]" class="form-control datepicker" placeholder="<?php echo $award_category->field_2; ?>" value="<?php echo isset($v_cat['field_2'][$counter]) && '' != trim($v_cat['field_2'][$counter]) ? stripslashes(esc_attr($v_cat['field_2'][$counter])) : ''; ?>" autocomplete="off">
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!is_null($award_category->field_3)) : ?>
                                                    <div class="col-12 col-md-4 pl-0 pl-md-1 mt-1 mt-md-2">
                                                        <input type="text" name="fields[<?php echo $award_category->id; ?>][field_3][]" class="form-control" placeholder="<?php echo $award_category->field_3; ?>" value="<?php echo isset($v_cat['field_3'][$counter]) && '' != trim($v_cat['field_3'][$counter]) ? stripslashes(esc_attr($v_cat['field_3'][$counter])) : ''; ?>">
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="row">
                                                <?php if (!is_null($award_category->field_4)) : ?>
                                                    <div class="col-12 mt-1 mt-md-2" style="line-height: 1;">
                                                        <textarea name="fields[<?php echo $award_category->id; ?>][field_4][]" class="form-control" placeholder="<?php echo $award_category->field_4; ?>"><?php echo isset($v_cat['field_4'][$counter]) && '' != trim($v_cat['field_4'][$counter]) ? stripslashes(esc_textarea($v_cat['field_4'][$counter])) : ''; ?></textarea>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="row">
                                                <?php if (!is_null($award_category->field_5)) : ?>
                                                    <div class="col-12 mt-1 mt-md-2">
                                                        <input type="text" name="fields[<?php echo $award_category->id; ?>][field_5][]" class="form-control" placeholder="<?php echo $award_category->field_5; ?>" value="<?php echo isset($v_cat['field_5'][$counter]) && '' != trim($v_cat['field_5'][$counter]) ? stripslashes(esc_attr($v_cat['field_5'][$counter])) : ''; ?>">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endfor; // For $count 
                                    ?>

                                <?php else : // Form is not submitted, show basic form 
                                ?>

                                    <div class="clonable fields-wrap p-2 pb-4">
                                        <div class="row">
                                            <?php if (!is_null($award_category->field_1)) : ?>
                                                <div class="col-12 col-md-4 pr-0 pr-md-1 mt-1 mt-md-2">
                                                    <input type="text" name="fields[<?php echo $award_category->id; ?>][field_1][]" class="form-control" placeholder="<?php echo $award_category->field_1; ?>">
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!is_null($award_category->field_2)) : ?>
                                                <div class="col-12 col-md-4 px-0 px-md-1 mt-1 mt-md-2">
                                                    <?php if ($award_category->id == 4) : ?>
                                                        <input type="text" name="fields[<?php echo $award_category->id; ?>][field_2][]" class="form-control datepicker2" placeholder="<?php echo $award_category->field_2; ?>" autocomplete="off">
                                                    <?php else : ?>
                                                        <input type="text" name="fields[<?php echo $award_category->id; ?>][field_2][]" class="form-control datepicker" placeholder="<?php echo $award_category->field_2; ?>" autocomplete="off">
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!is_null($award_category->field_3)) : ?>
                                                <div class="col-12 col-md-4 pl-0 pl-md-1 mt-1 mt-md-2">
                                                    <input type="text" name="fields[<?php echo $award_category->id; ?>][field_3][]" class="form-control" placeholder="<?php echo $award_category->field_3; ?>">
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="row">
                                            <?php if (!is_null($award_category->field_4)) : ?>
                                                <div class="col-12 mt-1 mt-md-2" style="line-height: 1;">
                                                    <textarea name="fields[<?php echo $award_category->id; ?>][field_4][]" class="form-control" placeholder="<?php echo $award_category->field_4; ?>"></textarea>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="row">
                                            <?php if (!is_null($award_category->field_5)) : ?>
                                                <div class="col-12 mt-1 mt-md-2">
                                                    <input type="text" name="fields[<?php echo $award_category->id; ?>][field_5][]" class="form-control" placeholder="<?php echo $award_category->field_5; ?>">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                <?php endif; // If $formdata is set 
                                ?>
                            </div>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-add" data-target="form-wrap2-<?php echo $award_category->id; ?>">+ Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div style="text-align: center;">
                <button type="submit" class="btn btn-success rounded mt-3" id="btn-submit" name="submit">Submit</button>
            </div>

        </div>
    </form>
</div>