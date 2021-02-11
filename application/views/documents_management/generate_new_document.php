<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                            <span class="page-heading down-arrow">
                                <a class="dashboard-link-btn" href="<?php echo base_url('documents_management'); ?>"><i class="fa fa-chevron-left"></i>Document Management</a>
                                <?php echo $title; ?>
                            </span>
                            </div>


                            <div class="row">
                                <div class="col-xs-9">
                                    <form id="form_new_document" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="generate_new_document" />
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                        <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />

                                        <?php if(isset($document_info['sid'])) { ?>
                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document_info['sid']; ?>" />
                                        <?php } ?>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <?php $save_value = isset($document_info['target_user_type']) ? $document_info['target_user_type'] : 'applicant'; ?>
                                                <label>Target User</label>
                                                <div class="radios-container">
                                                    <label class="control control--radio">
                                                        Applicant
                                                        <input <?php echo $save_value == 'applicant' ? 'checked' : ''; ?> class="user_type" name="target_user_type" id="target_user_type_applicant" value="applicant" checked="" type="radio">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <label class="control control--radio">
                                                        Employee
                                                        <input <?php echo $save_value == 'employee' ? 'checked' : ''; ?> class="user_type" name="target_user_type" id="target_user_type_employee" value="employee" type="radio">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <label class="control control--radio">
                                                        General
                                                        <input <?php echo $save_value == 'general' ? 'checked' : ''; ?> class="user_type" name="target_user_type" id="target_user_type_employee" value="general" type="radio">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <?php $field_id = 'document_title'; ?>
                                                <?php $save_value = isset($document_info[$field_id]) ? $document_info[$field_id] : ''; ?>
                                                <?php echo form_label('Title', $field_id); ?>
                                                <?php echo form_input($field_id, set_value($field_id, $save_value), ' class="invoice-fields" id="' . $field_id . '"'); ?>
                                                <?php echo form_error($field_id); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <?php $field_id = 'document_content'; ?>
                                                <?php $save_value = isset($document_info[$field_id]) ? html_entity_decode($document_info[$field_id]) : ''; ?>
                                                <?php echo form_label('Content', $field_id); ?>
                                                <?php echo form_textarea($field_id, set_value($field_id, $save_value, false), ' class="invoice-fields ckeditor" id="' . $field_id . '"'); ?>
                                                <?php echo form_error($field_id); ?>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <button type="submit" class="btn btn-success">Save</button>
                                                <a href="<?php echo base_url('documents_management'); ?>" class="btn black-btn">Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-xs-3">
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            Available Tags
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div id="applicant_tags">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{first_name}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{last_name}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{email}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{company_name}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{company_address}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{company_phone}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{career_site_url}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{desired_job_title}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{job_title}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{application_date}}" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div id="employee_tags">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{first_name}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{last_name}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{email}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{company_name}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{company_address}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{company_phone}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{career_site_url}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{job_title}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{registration_date}}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="general_tags">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{company_name}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{company_address}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{company_phone}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{career_site_url}}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control tag" readonly value="{{about_company}}" />
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
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('.tag').on('click', function () {
            $(this).select();
        });

        $('.user_type').on('click', function () {
            var selected = $(this).val();
            show_tags(selected);
        });

        $('.user_type:checked').trigger('click');
    });

    function show_tags(type) {
        switch (type) {
            case 'applicant':
                $('#applicant_tags').show();
                $('#employee_tags').hide();
                $('#general_tags').hide();
                break;
            case 'employee':
                $('#applicant_tags').hide();
                $('#employee_tags').show();
                $('#general_tags').hide();
                break;
            case 'general':
                $('#applicant_tags').hide();
                $('#employee_tags').hide();
                $('#general_tags').show();
                break;
        }
    }
</script>