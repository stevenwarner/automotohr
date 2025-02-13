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
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                            <a href="<?php echo base_url('manage_admin/compliance_safety/dashboard') ?>" class="btn black-btn"><i class="fa fa-arrow-left"> </i> Back To Compliance Safety Overview</a>
                                            <a href="<?php echo base_url('manage_admin/compliance_safety/incident_types/add') ?>" class="btn btn-success"><i class="fa fa-plus-circle"> </i> Add An Incident Type</a>
                                        </div>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <div class="hr-registered">
                                                Edit - <?= $report_type["compliance_report_name"]; ?>
                                            </div>
                                        </div>
                                        <?php echo validation_errors(); ?>

                                        <div class="hr-innerpadding">
                                            <form action="<?= current_url(); ?>" method="POST" id="add_type">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="heading-title page-title">
                                                            <h1 class="page-title"><?= $form == 'add' ? 'New Incident Type' : $name; ?></h1>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row field-row-autoheight">
                                                            <label for="report_name">Report Name <span class="hr-required">*</span></label>
                                                            <?php echo form_input('report_name', set_value('report_name', $report_type["compliance_report_name"]), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('report_name'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label for="status">Status <span class="hr-required">*</span></label>
                                                            <select name="status" class="hr-form-fileds">
                                                                <option <?= $report_type["status"] == "0" ? "selected" : ""; ?> value="0">In Active</option>
                                                                <option <?= $report_type["status"] == "1" ? "selected" : ""; ?> value="1">Active</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="description-editor">
                                                            <label>Add Instructions <span class="hr-required">*</span></label>
                                                            <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                            <textarea class="ckeditor" name="instructions" rows="8" cols="60" required>
                                                                <?php echo set_value('instructions', $report_type["instructions"]); ?>
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="description-editor">
                                                            <label>Add Reasons</label>
                                                            <textarea class="ckeditor" name="reasons" rows="8" cols="60" required>
                                                                <?php echo set_value('reasons', $report_type["reasons"]); ?>
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <?php if ($incident_types) { ?>
                                            <br>

                                            <div class="hr-box">
                                                <div class="hr-box-header bg-header-green">
                                                    <div class="hr-registered">
                                                        Compliance Report Incident Types
                                                    </div>
                                                </div>
                                                <div class="hr-innerpadding">
                                                    <?php foreach ($incident_types as $item) { ?>
                                                        <div class="form-group">
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="incident_types[]" value="<?= $item["id"]; ?>" <?= $report_type["incident_type_ids"] && in_array($item["id"], $report_type["incident_type_ids"]) ? "checked" : ""; ?>>
                                                                <?= $item["compliance_incident_type_name"]; ?> <?= $item["code"] ? "(" . ($item["code"]) . ")" : ""; ?>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>


                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                            <input type="submit" class="search-btn" value="Update" name="form-submit" />
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
                report_name: {
                    required: true
                },
                instructions: {
                    required: function() {
                        CKEDITOR.instances.instructions.updateElement();
                    }
                },
            },
            messages: {
                report_name: {
                    required: 'Incident Type is required'
                },
                instructions: {
                    required: 'Please provide some instructions for this report'
                },
            },
            submitHandler: function(form) {

                var instances = $.trim(CKEDITOR.instances.instructions.getData());
                var reasons = $.trim(CKEDITOR.instances.reasons.getData());
                if (instances.length === 0) {
                    alertify.alert('Error! Instructions Missing', "Instructions cannot be Empty");
                    return false;
                }

                form.submit();
            }
        });
    });

    $(document).ready(function() {
        CKEDITOR.replace('instructions');
        CKEDITOR.replace('reasons');
    });
</script>