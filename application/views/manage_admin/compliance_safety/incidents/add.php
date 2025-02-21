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
                                            <a href="<?php echo base_url('manage_admin/compliance_safety/report_types/add') ?>" class="btn btn-success"><i class="fa fa-plus-circle"> </i> Add An Report Type</a>
                                        </div>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <div class="hr-registered">
                                                Add A Compliance Report Incident Type
                                            </div>
                                        </div>

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
                                                            <label for="report_name">Report Incident Name <span class="hr-required">*</span></label>
                                                            <?php echo form_input('compliance_incident_type_name', set_value('compliance_incident_type_name', ""), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('compliance_incident_type_name'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label for="status">Status <span class="hr-required">*</span></label>
                                                            <select name="status" class="hr-form-fileds">
                                                                <option value="0">In Active</option>
                                                                <option value="1">Active</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="description-editor">
                                                            <label>Add description <span class="hr-required">*</span></label>
                                                            <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                            <textarea class="ckeditor" name="description" rows="8" cols="60" required>
                                                                <?php echo set_value('description', $ins); ?>
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row field-row-autoheight">
                                                            <label for="report_name">Code</label>
                                                            <?php echo form_input('code', set_value('code', ""), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('code'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row field-row-autoheight">
                                                            <label for="report_name">Priority</label>
                                                            <?php echo form_input('priority', set_value('priority', ""), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('priority'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                        <input type="submit" class="search-btn" value="Add" name="form-submit" />
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
                compliance_incident_type_name: {
                    required: true
                },
                description: {
                    required: function() {
                        CKEDITOR.instances.description.updateElement();
                    }
                },
            },
            messages: {
                report_name: {
                    required: 'Incident Type is required'
                },
                instructions: {
                    required: 'Please provide some description for this report'
                },
            },
            submitHandler: function(form) {

                var instances = $.trim(CKEDITOR.instances.description.getData());
                if (instances.length === 0) {
                    alertify.alert('Error! Description Missing', "Description cannot be Empty");
                    return false;
                }

                form.submit();
            }
        });
    });

    $(document).ready(function() {
        CKEDITOR.replace('description');
    });
</script>