<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="universal-form-style-v2">
    <form id="form_add_question" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
        <input type="hidden" id="questionnaire_sid" name="questionnaire_sid" value="<?php echo $questionnaire['sid']; ?>" />
        <input type="hidden" id="questionnaire_section_sid" name="questionnaire_section_sid" value="<?php echo $questionnaire_section['sid']; ?>" />


        <?php $temp = (isset($question['sid']) ? $question['sid'] : 0); ?>

        <?php if($temp == 0) { ?>
            <input type="hidden" id="perform_action" name="perform_action" value="insert_questionnaire_section_question" />
        <?php } else { ?>
            <input type="hidden" id="perform_action" name="perform_action" value="update_questionnaire_section_question" />
        <?php } ?>

        <input type="hidden" id="questionnaire_section_question_sid" name="questionnaire_section_question_sid" value="<?php echo $temp; ?>" />

        <div class="row">
            <div class="col-xs-12">
                <div class="heading-title">
                    <div class="page-title">
                        <span class="">Section:</span>&nbsp;<?php echo $questionnaire_section['title']; ?>
                    </div>
                </div>
            </div>
        </div>
        <ul>
            <li class="form-col-100">
                <?php $temp = (isset($question['question_text']) ? $question['question_text'] : ''); ?>
                <?php echo form_label('Question Text <span class="hr-required">*</span>', 'question_text');?>
                <?php echo form_input('question_text', set_value('question_text', $temp), 'class="invoice-fields"'); ?>
                <?php echo form_error('question_text'); ?>
            </li>
            <li class="form-col-100">
                <?php $temp = (isset($question['sort_order']) ? $question['sort_order'] : '5'); ?>
                <?php echo form_label('Question Position', 'title'); ?>
                <input id="sort_order" name="sort_order" value="<?php echo set_value('sort_order', $temp); ?>" type="number" class="invoice-fields" type="number" min="1" max="1000" step="1" />
                <?php echo form_error('sort_order'); ?>
            </li>
            <li class="form-col-100 autoheight">
                <div class="field-row field-row-autoheight">
                    <?php $temp = (isset($question['answer_required']) ? $question['answer_required'] : 0); ?>
                    <?php $is_default_yes = ($temp == 1 ? true : false); ?>
                    <label>Answer Required</label>
                    <label class="control control--radio">
                        <input <?php echo set_radio('answer_required', 1, $is_default_yes); ?> name="answer_required" id="answer_required_yes" value="1" class="answer_required" type="radio">
                        <span>Yes</span>&nbsp;<span class="text-success"><small>( You can select Answer Types e.g. Text Box, Radio Button, Checkbox as response for this Question. )</small></span>
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="field-row field-row-autoheight">
                        <?php $is_default_no = ($temp == 0 ? true : false); ?>
                        <label class="control control--radio">
                            <input <?php echo set_radio('answer_required', 0, $is_default_no); ?> name="answer_required" id="answer_required_no" value="0" class="answer_required" type="radio">
                            <span>No</span>&nbsp;<span class="text-success"><small> ( No Answer Required, it will display as simple text on screen with no place to type or select answers. )</small></span>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                <?php echo form_error('answer_required'); ?>
            </li>
            <li class="form-col-100 autoheight" id="answer_container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="field-row">
                            <?php $temp = (isset($question['answer_type']) ? $question['answer_type'] : ''); ?>
                            <label for="answer_type">Answer Type<span class="hr-required">*</span></label>
                            <div class="hr-select-dropdown">
                                <select class="invoice-fields" id="answer_type" name="answer_type" >
                                    <?php $is_default_textual = ($temp == 'textual' ? true : false); ?>
                                    <option <?php echo set_select('answer_type', 'textual', $is_default_textual); ?> value="textual">Textual</option>

                                    <?php $is_default_mca_m = ($temp == 'mca_m' ? true : false); ?>
                                    <option <?php echo set_select('answer_type', 'textual', $is_default_mca_m); ?> value="mca_m">Multiple Choice Answers ( Multiple Answers )</option>

                                    <?php $is_default_mca_s = ($temp == 'mca_s' ? true : false); ?>
                                    <option <?php echo set_select('answer_type', 'textual', $is_default_mca_s); ?> value="mca_s">Multiple Choice Answers ( Single Answer )</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </li>
            <?php $options = (isset($question['answer_options']) ? explode(',', $question['answer_options']) : ''); ?>
            <li class="form-col-100 autoheight" id="answer_options_container">
                <div class="row" >
                    <div class="col-xs-12">
                        <div id="options_container">
                            <?php if(!empty($options)) { ?>
                                <?php foreach($options as $key => $option) { ?>
                                    <?php if($key == 0) { ?>

                                        <div class="row option_row_template field-row field-row-autoheight" id="option_row_template">
                                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-2 text-right">
                                                <label class="valign-middle">Option</label>
                                            </div>
                                            <div class="col-lg-7 col-md-9 col-xs-12 col-sm-8">
                                                <?php echo form_input('answer_options[]', set_value('answer_options[]', $option), 'class="invoice-fields" data-rule-required="true"'); ?>
                                                <?php echo form_error('answer_options'); ?>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 add-option-btn">
                                                <button disabled="disabled" onclick="func_remove_question_option(this);" class="btn btn-equalizer btn-danger disabled" type="button"><i class="fa fa-trash"></i></button>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 add-option-btn">
                                                <button id="add_option_btn" onclick="func_add_question_option();" class="btn btn-equalizer btn-success" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>

                                    <?php } else { ?>

                                        <div class="row option_row_template field-row field-row-autoheight">
                                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-2 text-right">
                                                <label class="valign-middle">Option</label>
                                            </div>
                                            <div class="col-lg-7 col-md-9 col-xs-12 col-sm-8">
                                                <?php echo form_input('answer_options[]', set_value('answer_options[]', $option), 'class="invoice-fields" data-rule-required="true"'); ?>
                                                <?php echo form_error('answer_options'); ?>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 add-option-btn">
                                                <button disabled="disabled" onclick="func_remove_question_option(this);" class="btn btn-equalizer btn-danger disabled" type="button"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>

                                    <?php } ?>
                                <?php } ?>
                            <?php } else { ?>

                                <div class="row option_row_template field-row field-row-autoheight" id="option_row_template">
                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-2 text-right">
                                        <label class="valign-middle">Option</label>
                                    </div>
                                    <div class="col-lg-7 col-md-9 col-xs-12 col-sm-8">
                                        <?php echo form_input('answer_options[]', set_value('answer_options[]', $temp), 'class="invoice-fields" data-rule-required="true"'); ?>
                                        <?php echo form_error('answer_options'); ?>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 add-option-btn">
                                        <button disabled="disabled" onclick="func_remove_question_option(this);" class="btn btn-equalizer btn-danger disabled" type="button"><i class="fa fa-trash"></i></button>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 add-option-btn">
                                        <button id="add_option_btn" onclick="func_add_question_option();" class="btn btn-equalizer btn-success" type="button"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>

                            <?php } ?>

                        </div>

                    </div>
                </div>
            </li>

        </ul>



        <div class="row">
            <div class="col-xs-12">
                <div class="field-row">

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="field-row">

                </div>
            </div>
        </div>






    </form>
    <div class="">
        <button type="button" class="btn btn-success" onclick="function_save_question();"><?php echo $submit_btn_text; ?></button>
        <button type="button" data-dismiss="modal" class="btn black-btn"  >Cancel</button>
    </div>
</div>


<script>
    $(document).ready(function () {

        $('#file_preview_modal').on('shown.bs.modal', function () {
            var answer_required = "<?php echo(isset($question['answer_required']) ? $question['answer_required'] : 0)?>";

            console.log(answer_required);

            <?php if(!isset($question['answer_required']) || (isset($question['answer_required']) && intval($question['answer_required']) == 0)) {?>
            $('#answer_container').hide();
            $('#answer_options_container').hide();
            $('#answer_required_no').trigger('click');

            <?php } else {?>
            $('#answer_required_yes').trigger('click');
            $('#answer_type').trigger('change');
            <?php } ?>
        });
    });
</script>