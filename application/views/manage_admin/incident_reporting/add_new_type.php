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
                                            <a id="back_btn" href="<?php echo base_url('manage_admin/reports/incident_reporting') ?>" class="btn btn-success"><i class="fa fa-arrow-left"> </i> Go Back</a>
                                        </div>
                                    </div>
                                    <div class="add-new-company">
                                        <form action="" method="POST" id="add_type">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title"><?= $form == 'add' ? 'New Incident Type' : $name; ?></h1>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="incident_name">Incident Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('incident_name', set_value('incident_name', $name), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('incident_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="status">Status <span class="hr-required">*</span></label>
                                                        <select name="status" class="hr-form-fileds">
                                                            <option value="0" <?= !$status ? 'selected="selected"' : '' ?>>In Active</option>
                                                            <option value="1" <?= $status ? 'selected="selected"' : '' ?>>Active</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="description-editor">
                                                        <label>Add Instructions: <span class="hr-required">*</span></label>
                                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                        <textarea class="ckeditor" name="instructions" rows="8" cols="60" required>
                                                                <?php echo set_value('instructions', $ins); ?>
                                                            </textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="description-editor">
                                                        <label>Add Reasons: <span class="hr-required">*</span></label>
                                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                        <textarea class="ckeditor" name="reasons" rows="8" cols="60" required>
                                                                <?php echo set_value('reasons', $rsn); ?>
                                                            </textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label for="status">Safety Check List</label>
                                                        <input type="radio" name="safety_checklist" value="1" <?php if (!isset($safety_checklist) || $safety_checklist == '1') { ?>checked="" <?php } ?>>&nbsp;<b>Yes</b>
                                                        &nbsp;&nbsp;
                                                        <input type="radio" name="safety_checklist" value="0" <?php if (isset($safety_checklist) && $safety_checklist == '0') { ?>checked="" <?php } ?>>&nbsp;<b>No</b><br><br>

                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label for="status">Compliance and Safety?</label>
                                                        <input type="radio" name="is_safety_incident" value="1" <?php if (!isset($is_safety_incident) || $is_safety_incident == '1') { ?>checked="" <?php } ?>>&nbsp;<b>Yes</b>
                                                        &nbsp;&nbsp;
                                                        <input type="radio" name="is_safety_incident" value="0" <?php if (isset($is_safety_incident) && $is_safety_incident == '0') { ?>checked="" <?php } ?>>&nbsp;<b>No</b><br><br>

                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="div_fillable_by">
                                                    <div class="field-row">
                                                        <label for="status">Fillable By</label>
                                                        <input type="radio" name="fillable_by" value="team" <?php if (!isset($fillable_by) || $fillable_by == 'team') { ?>checked="" <?php } ?>>&nbsp;<b>Team</b>
                                                        &nbsp;&nbsp;
                                                        <input type="radio" name="fillable_by" value="manager" <?php if (isset($fillable_by) && $fillable_by == 'manager') { ?>checked="" <?php } ?>>&nbsp;<b>Manager</b><br><br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6" id="link_to_manager">
                                                <div class="field-row">
                                                    <label for="status">Link it to Manager</label>
                                                    <select name="parent_sid" class="hr-form-fileds">
                                                        <?php foreach ($managerlists as $value) { ?>
                                                            <option value="<?php echo $value['id']; ?>" <?php if (isset($parent_sid) && $parent_sid == $value['id']) { ?>selected="selected" <?php } ?>>
                                                                <?php echo $value['incident_name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <input type="hidden" value="<?= $form ?>">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                <input type="submit" class="search-btn" value="<?= $form == 'add' ? 'Add' : 'Update' ?>" name="form-submit">
                                                <?php if (isset($safety_checklist) && $safety_checklist == '1') { ?>
                                                    <input type="button" value="Cancel" class="search-btn bg-dark-cancel_btn" onclick="document.location.href = '<?php echo base_url("manage_admin/reports/incident_reporting/checklists") ?>'">
                                                <?php } else { ?>
                                                    <input type="button" value="Cancel" class="search-btn bg-dark-cancel_btn" onclick="document.location.href = '<?php echo base_url("manage_admin/reports/incident_reporting") ?>'">
                                                <?php } ?>
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
    $(function() {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });

        $("#add_type").validate({
            ignore: ":hidden:not(select)",
            debug: false,
            rules: {
                incident_name: {
                    required: true
                },
                instructions: {
                    required: function() {
                        CKEDITOR.instances.instructions.updateElement();
                    }
                },
                reasons: {
                    required: function() {
                        CKEDITOR.instances.reasons.updateElement();
                    }
                }
            },
            messages: {
                incident_name: {
                    required: 'Incident Type is required'
                },
                instructions: {
                    required: 'Please provide some instructions for this report'
                },
                reasons: {
                    required: 'Please provide some reasons for this report'
                }
            },
            submitHandler: function(form) {

                var instances = $.trim(CKEDITOR.instances.instructions.getData());
                var reasons = $.trim(CKEDITOR.instances.reasons.getData());
                if (instances.length === 0) {
                    alertify.alert('Error! Instructions Missing', "Instructions cannot be Empty");
                    return false;
                }
                if (reasons.length === 0) {
                    alertify.alert('Error! Reasons Missing', "Reasons cannot be Empty");
                    return false;
                }

                form.submit();
            }
        });
    });

    $(document).ready(function() {
        CKEDITOR.replace('instructions');
        CKEDITOR.replace('reasons');

        var linkCheck = '<?= $form == 'add' ? 'team' : $fillable_by ?>';

        var documenttype = document.getElementsByName('safety_checklist');
        for (var i = 0, length = documenttype.length; i < length; i++) {
            if (documenttype[i].checked) {
                var value = documenttype[i].value;
                if (value == 1) {
                    $('#link_to_manager').hide();
                    $('#div_fillable_by').hide();
                } else {
                    $('#link_to_manager').show();
                    $('#div_fillable_by').show();
                }
                break;
            }
        }

        $('input[type=radio][name=safety_checklist]').change(function() {
            if (this.value == '1') {
                $('#link_to_manager').hide();
                $('#div_fillable_by').hide();
            } else if (this.value == '0') {
                $('#link_to_manager').show();
                $('#div_fillable_by').show();
            }
        });

        if (linkCheck != 'team') {
            $('#link_to_manager').hide();
        }


        $('input[type=radio][name=fillable_by]').change(function() {
            if (this.value == 'manager') {
                $('#link_to_manager').hide();
            } else if (this.value == 'team') {
                $('#link_to_manager').show();
            }
        });
    });
</script>