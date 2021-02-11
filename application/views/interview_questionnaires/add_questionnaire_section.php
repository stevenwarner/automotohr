<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="universal-form-style-v2">
    <form method="post" enctype="multipart/form-data" id="form_add_new_interview_questionnaire_section" action="<?php echo current_url(); ?>">
        <input type="hidden" id="questionnaire_sid" name="questionnaire_sid" value="<?php echo (isset($questionnaire['sid']) ? $questionnaire['sid'] : ''); ?>" />

        <?php $temp = (isset($questionnaire_section['sid']) ? $questionnaire_section['sid'] : 0); ?>
        <input type="hidden" id="questionnaire_section_sid" name="questionnaire_section_sid" value="<?php echo $temp; ?>" />

        <?php $temp = (isset($questionnaire_section['sid']) && $questionnaire_section['sid'] > 0 ? 'update_questionnaire_section' : 'insert_questionnaire_section'); ?>
        <input type="hidden" id="perform_action" name="perform_action" value="<?php echo $temp; ?>" />

        <input type="hidden" id="status" name="status" value="active" />

        <ul>
            <li class="form-col-100">
                <?php $temp = (isset($questionnaire_section['title']) ? $questionnaire_section['title'] : ''); ?>
                <?php echo form_label('Section Title <span class="hr-required">*</span>', 'title'); ?>
                <?php echo form_input('title', set_value('title', $temp), 'class="invoice-fields"'); ?>
                <?php echo form_error('title'); ?>
            </li>
            <li class="form-col-100 autoheight">
                <?php $temp = (isset($questionnaire_section['short_description']) ? $questionnaire_section['short_description'] : ''); ?>
                <?php echo form_label('Section Short Description', 'title'); ?>
                <?php echo form_textarea('short_description', set_value('short_description', $temp), 'class="invoice-fields-textarea field-row-autoheight"'); ?>
                <?php echo form_error('short_description'); ?>
            </li>
            <li class="form-col-100">
                <?php $temp = (isset($questionnaire_section['sort_order']) ? $questionnaire_section['sort_order'] : '5'); ?>
                <?php echo form_label('Section Position', 'title'); ?>
                <input id="sort_order" name="sort_order" value="<?php echo set_value('sort_order', $temp); ?>" type="number" class="invoice-fields" type="number" min="1" max="1000" step="1" />
                <?php echo form_error('sort_order'); ?>
            </li>
        </ul>

    </form>
</div>
<div class="hr-box-footer hr-innerpadding">
    <button type="button" class="btn btn-success" onclick="func_save_questionnaire_section();"><?php echo $submit_btn_text; ?></button>
    <button class="btn black-btn" data-dismiss="modal" >Cancel</button>
</div>

