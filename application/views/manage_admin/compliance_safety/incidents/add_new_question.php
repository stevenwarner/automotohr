<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <a id="back_btn" href="<?php echo base_url('manage_admin/compliance_safety/incident_types/view_incident_questions/' . $inc_id) ?>" class="btn btn-success"><i class="fa fa-arrow-left"> </i> Go Back</a>
                                        </div>
                                    </div>

                                    <div class="add-new-company">
                                        <form action="" method="POST" id="add_ques" autocomplete="off">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title">
                                                        <h1 class="page-title"><?= $form == 'add' ? $sub_title : 'Update Incident Question' ?></h1>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="label">Incident Question<span class="hr-required">*</span></label>
                                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                        <textarea class="ckeditor textarea" name="label" rows="8" cols="60" required>
                                                            <?php echo set_value('label'); ?>
                                                        </textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row autoheight">
                                                        <label class="control control--checkbox">
                                                            Is Required
                                                            <input type="checkbox" value="1" name="is_required" id="is_required" checked="checked" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="radio_question">Related To</label>
                                                        <select name="related_to_question" class="hr-form-fileds" id="radio_question">
                                                            <option value='0'>-- Please Select --</option>
                                                            <?php
                                                            foreach ($radio_questions as $field) {
                                                                echo "<option value='" . $field['id'] . "' >" . ucfirst($field['label']) . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="placeholder">Placeholder</label>
                                                        <?php echo form_input('placeholder', set_value('placeholder'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('placeholder'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="status">Status <span class="hr-required">*</span></label>
                                                        <select name="status" class="hr-form-fileds">
                                                            <option value="1" <?= $status ? 'selected="selected"' : '' ?>>Active</option>
                                                            <option value="0" <?= !$status ? 'selected="selected"' : '' ?>>In Active</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="question_type">Question Type <span class="hr-required">*</span></label>
                                                        <select name="question_type" class="hr-form-fileds" id="question_type">
                                                            <option value='text'>Single line answer</option>
                                                            <option value='textarea'>Multiple line answer</option>
                                                            <option value='radio'>Yes/No</option>
                                                            <option value='single select'>List of answer with single choice</option>
                                                            <option value='multi select'>List of answer with multiple choice</option>
                                                            <option value='signature'>Signature</option>
                                                            <option value='date'>Date</option>
                                                            <option value='time'>Time</option>
                                                            <?php
                                                            //                                                                foreach($fields as $field){
                                                            //                                                                    echo "<option value='".$field."' >".ucfirst($field)."</option>";
                                                            //                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 last">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="options">Options<span class="hr-required">*</span></label>
                                                        <?php echo form_input('options[]', set_value('options[]'), 'class="hr-form-fileds" required="required"'); ?>
                                                        <?php echo form_error('options'); ?>
                                                        <div id="answerAddsingle"></div>
                                                    </div>
                                                    <div class="form-col-100" id="add_answersingle"><a href="javascript:;" onclick="addAnswerBlocksingle(); return false;" class="add"> + Add Option</a></div>
                                                </div>

                                                <input type="hidden" name="compliance_incident_types_id" value="<?= $inc_id ?>">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                    <input type="submit" class="search-btn" id="form-submit" value="Done" name="form-submit">
                                                    <input type="submit" class="search-btn btn-warning" id="more" value="Add More" name="more">
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript">
    var j = 1;

    function addAnswerBlocksingle() {
        var idj = "answerAddsingle" + j;
        $("<div id='" + idj + "'><\/div>").appendTo("#answerAddsingle");
        $('#' + idj).html($('#' + idj).html() + '<div class="field-row field-row-autoheight"> <input type="text" name="options[]" value="" class="hr-form-fileds" required="required" aria-required="true"> </div>');
        j++;
    }

    $(document).ready(function() {
        CKEDITOR.replace('label');
        $('.last').hide();
        $('#question_type').on('change', function() {
            var type = $(this).val();
            if (type == 'single select' || type == 'multi select') {
                $('.last').show();
            } else {
                $('.last').hide();
            }
        });
    });

    $(function() {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });

        $("#add_ques").validate({
            ignore: ":hidden:not(select)",
            rules: {
                label: {
                    required: function() {
                        CKEDITOR.instances.label.updateElement();
                    }
                },
                question_type: {
                    required: true
                },
                status: {
                    required: true
                },
                options: {
                    required: true
                }
            },
            messages: {
                label: {
                    required: 'Incident Question is required'
                },
                question_type: {
                    required: 'Incident Question Type is required'
                },
                status: {
                    required: 'Incident Question Status is required'
                },
                option: {
                    required: 'Option is required'
                }
            },
            submitHandler: function(form) {

                var instances = $.trim(CKEDITOR.instances.label.getData());
                if (instances.length === 0) {
                    alertify.alert('Error! Question Missing', "Incident Question cannot be Empty");
                    return false;
                }
                form.submit();
            }
        });
    });
</script>