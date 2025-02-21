<!-- Manage Documents -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-heading-text text-medium">
            <strong>Questions</strong>
        </h1>
    </div>
    <div class="panel-body">
        <?php if (sizeof($questions) > 0) { ?>

            <?php foreach ($questions as $question) { ?>
                <?php echo '<div class="row"><div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><div class="form-group autoheight">'; ?>

                <?php if ($question['question_type'] == 'textarea') { ?>
                    <label class="auto-height"><?php echo strip_tags($question['label']); ?>: <span class="required required_<?php echo $question['related_to_question']; ?>"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                    <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                    <textarea id="text_<?php echo $question['id']; ?>" class="form-control textarea related_<?php echo $question['related_to_question']; ?>" data-require="<?php echo $question['is_required']; ?>" data-attr="<?php echo $question['related_to_question']; ?>" name="text_<?php echo $question['id']; ?>" rows="8" cols="60" <?php echo $question['is_required'] ? "required" : "" ?>><?php echo set_value('text_' . $question['id']); ?></textarea>
                <?php } elseif ($question['question_type'] == 'text') { ?>
                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required required_<?php echo $question['related_to_question']; ?>"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                    <?php
                    $required = $question['is_required'] ? "required" : "";
                    echo form_input('text_' . $question['id'], set_value('text_' . $question['id']), 'class="form-control related_' . $question['related_to_question'] . '" id="' . $question['id'] . '" data-require="' . $question['is_required'] . '" ' . $required . ' data-attr="' . $question['related_to_question'] . '"');
                    ?>
                <?php } elseif ($question['question_type'] == 'time') { ?>
                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required required_<?php echo $question['related_to_question']; ?>"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                    <?php $required = $question['is_required'] ? "required" : ""; ?>
                    <input id="<?php echo $question['id']; ?>" type="text" name="time_<?php echo $question['id']; ?>" value="12:00AM" class="form-control start_time related_<?php echo $question['related_to_question']; ?>" readonly data-require="<?php echo $question['is_required']; ?>" data-attr="<?php echo $question['related_to_question']; ?>" aria-invalid="false" <?php echo $required; ?>>
                <?php } elseif ($question['question_type'] == 'date') { ?>
                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required required_<?php echo $question['related_to_question']; ?>"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                    <?php $required = $question['is_required'] ? "required" : ""; ?>
                    <input id="<?php echo $question['id']; ?>" type="text" name="date_<?php echo $question['id']; ?>" value="" data-require="<?php echo $question['is_required']; ?>" data-attr="<?php echo $question['related_to_question']; ?>" class="form-control start_date related_<?php echo $question['related_to_question']; ?>" aria-invalid="false" <?php echo $required; ?> autocomplete="off" readonly>
                <?php } elseif ($question['question_type'] == 'signature') { ?>
                    <div class="form-group">
                        <label class="auto-height">Signature : <span class="required">*</span></label>
                    </div>

                    <!-- the below loaded view add e-signature -->
                    <?php $this->load->view('static-pages/e_signature_button'); ?>
                    <input type="hidden" name="signature" value="" id="signature_bas64_image">

                <?php } elseif ($question['question_type'] == 'radio') { ?>
                    <label><?php echo strip_tags($question['label']) ?>: <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                            <label class="control control--radio">
                                Yes<input type="radio" id="<?php echo $question['id']; ?>" name="radio_<?php echo $question['id']; ?>" data-attr="<?php echo $question['is_required']; ?>" value="yes" style="position: relative;">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                            <label class="control control--radio">
                                No<input type="radio" id="<?php echo $question['id']; ?>" name="radio_<?php echo $question['id']; ?>" data-attr="<?php echo $question['is_required']; ?>" value="no" style="position: relative;" checked>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                <?php } elseif ($question['question_type'] == 'single select') { ?>
                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                    <div class="hr-select-dropdown">
                        <select id="<?php echo $question['id']; ?>" name="list_<?php echo $question['id']; ?>" class="form-control" <?php if ($question['is_required'] == 1) { ?> required <?php } ?>>
                            <option value="">-- Please Select --</option>
                            <?php
                            $options = explode(',', $question['options']);
                            foreach ($options as $option) {
                            ?>
                                <option value="<?php echo $option; ?>"> <?php echo ucfirst($option); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                <?php } elseif ($question['question_type'] == 'multi select') { ?>
                    <label class="multi-checkbox auto-height" data-attr="<?php echo $question['is_required'] ?>" data-key="<?php echo $question['id']; ?>" data-value="<?php echo $question['label'] ?>"><?php echo strip_tags($question['label']); ?> <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                    <div class="row">
                        <?php $options = explode(',', $question['options']); ?>
                        <?php foreach ($options as $option) { ?>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <label class="control control--checkbox">
                                    <?php echo $option; ?>
                                    <input id="<?php echo $question['id']; ?>" type="checkbox" name="multi-list_<?php echo $question['id']; ?>[]" value="<?php echo $option; ?>" style="position: relative;">
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php echo '</div> </div> </div>'; ?>
            <?php } ?>

        <?php } else { ?>
            <?php echo "<span class='no-data'>No Questions Scheduled For This Type</span>"; ?>
        <?php } ?>
    </div>
</div>