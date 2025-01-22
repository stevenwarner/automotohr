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
                                            <a id="back_btn" href="<?php echo base_url('manage_admin/reports/compliance_reporting/incident_list') ?>" class="btn btn-success"><i class="fa fa-arrow-left"> </i> Go Back</a>
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

                                            </div>


                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="compliance_name">Incident Name <span class="hr-required">*</span></label>
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

                                            </div>


                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="compliance_name">Code</label>
                                                        <?php echo form_input('code', set_value('code', $code), 'class="hr-form-fileds"'); ?>
                                                    
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="compliance_name">Priority </label>
                                                        <?php echo form_input('priority', set_value('priority', $priority), 'class="hr-form-fileds"'); ?>
                                                
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="description-editor">
                                                        <label>Description <span class="hr-required">*</span></label>
                                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                        <textarea class="ckeditor" name="description" rows="8" cols="60" required>
                                                                <?php echo set_value('description', $ins); ?>
                                                            </textarea>
                                                    </div>
                                                </div>

                                            </div>


                                        <div class="row">
                                            <input type="hidden" value="<?= $form ?>">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                <input type="submit" class="search-btn" value="<?= $form == 'add' ? 'Add' : 'Update' ?>" name="form-submit">
                                               
                                            </div>
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
                compliance_name: {
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
                compliance_name: {
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