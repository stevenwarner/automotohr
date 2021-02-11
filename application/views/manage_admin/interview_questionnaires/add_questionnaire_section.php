<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="hr-box-body hr-innerpadding">
    <form method="post" enctype="multipart/form-data" id="form_add_new_interview_questionnaire_section" action="<?php echo current_url(); ?>">
        <input type="hidden" id="questionnaire_sid" name="questionnaire_sid" value="<?php echo (isset($questionnaire['sid']) ? $questionnaire['sid'] : ''); ?>" />

        <?php $temp = (isset($questionnaire_section['sid']) ? $questionnaire_section['sid'] : 0); ?>
        <input type="hidden" id="questionnaire_section_sid" name="questionnaire_section_sid" value="<?php echo $temp; ?>" />

        <?php $temp = (isset($questionnaire_section['sid']) && $questionnaire_section['sid'] > 0 ? 'update_questionnaire_section' : 'insert_questionnaire_section'); ?>
        <input type="hidden" id="perform_action" name="perform_action" value="<?php echo $temp; ?>" />
        <div class="row">
            <div class="col-xs-12">
                <div class="field-row">
                    <?php $temp = (isset($questionnaire_section['title']) ? $questionnaire_section['title'] : ''); ?>
                    <?php echo form_label('Section Title <span class="hr-required">*</span>', 'title'); ?>
                    <?php echo form_input('title', set_value('title', $temp), 'class="hr-form-fileds"'); ?>
                    <?php echo form_error('title'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="field-row field-row-autoheight">
                    <?php $temp = (isset($questionnaire_section['short_description']) ? $questionnaire_section['short_description'] : ''); ?>
                    <?php echo form_label('Section Short Description', 'title'); ?>
                    <?php echo form_textarea('short_description', set_value('short_description', $temp), 'class="hr-form-fileds field-row-autoheight"'); ?>
                    <?php echo form_error('short_description'); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="field-row field-row-autoheight">
                    <?php $temp = (isset($questionnaire_section['sort_order']) ? $questionnaire_section['sort_order'] : '5'); ?>
                    <?php echo form_label('Section Position', 'title'); ?>
                    <input id="sort_order" name="sort_order" value="<?php echo set_value('sort_order', $temp); ?>" type="number" class="hr-form-fileds" type="number" min="5" max="1000" step="5" />
                    <?php echo form_error('sort_order'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-8 col-sm-4">
                <div class="field-row">
                    <?php $temp = (isset($questionnaire_section['status']) ? $questionnaire_section['status'] : ''); ?>
                    <?php $default_checked = ($temp == 'active' ? true : true); ?>
                    <label>Section Status</label>
                    <label class="control control--radio">
                        <input name="status" id="status_active" value="active" class="" <?php echo set_radio('status', 'active', $default_checked); ?> type="radio">
                        Active
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-4 col-sm-4">
                <div class="field-row">
                    <?php $default_checked = ($temp == 'inactive' ? true : false); ?>
                    <label>&nbsp;</label>
                    <label class="control control--radio">
                        <input name="status" id="status_active" value="inactive" class="" <?php echo set_radio('status', 'inactive', $default_checked); ?> type="radio">
                        Inactive
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="hr-box-footer hr-innerpadding">
    <button type="button" class="btn btn-success" onclick="func_save_questionnaire_section();"><?php echo $submit_btn_text; ?></button>
    <button class="btn black-btn" data-dismiss="modal" >Cancel</button>
</div>

