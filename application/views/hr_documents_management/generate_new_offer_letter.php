<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management'); ?>"><i class="fa fa-chevron-left"></i>Document Management</a>
                                    <?php echo 'Add HR Document'; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
                                    <div class="upload-new-doc-heading">
                                            <i class="fa fa-file-text-o"></i>
                                            <?php echo $title; ?>
                                        </div>
                                    <p class="upload-file-type">You can easily create electronically formatted fillable documents for your Employees and Applicants</p>
                                    <form id="form_new_document" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="<?php echo isset($document_info) ? 'update_offer_letter' : 'generate_new_offer_letter';?>" />
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                        <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Template Name <span class="staric">*</span></label>
                                                <input type="text" name="letter_name"  value="<?php
                                                    if (isset($document_info['letter_name'])) {
                                                        echo set_value('letter_name', $document_info['letter_name']);
                                                    } else {
                                                        echo set_value('letter_name');
                                                    } ?>" class="invoice-fields">
                                                <?php echo form_error('letter_name'); ?>
                                            </div>
                                        </div>
                                        <div class="row margin-top">
                                            <div class="col-xs-12">
                                                <?php $field_id = 'letter_body'; ?>
                                                <?php $save_value = isset($document_info[$field_id]) ? html_entity_decode($document_info[$field_id]) : ''; ?>
                                                <?php echo form_label('Template Letter Body <span class="staric">*</span>', $field_id); ?>
                                                <div style="margin-bottom:5px;"><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                                <?php echo form_textarea($field_id, set_value($field_id, $save_value, false), ' class="invoice-fields ckeditor" id="' . $field_id . '"'); ?>
                                                <?php echo form_error($field_id); ?>
                                            </div>                                            
                                        </div>
                                        <div class="row margin-top">
                                            <div class="col-xs-12">
                                                <label>Acknowledgment Required</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="acknowledgment_required">
                                                        <option <?php if (isset($document_info['acknowledgment_required']) && $document_info['acknowledgment_required'] == '0') echo 'selected';?> value="0"> No </option>
                                                        <option <?php if (isset($document_info['acknowledgment_required']) && $document_info['acknowledgment_required'] == '1') echo 'selected';?> value="1"> Yes </option>
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    Enable the Acknowledgment Requirement, if you need a confirmation that a Document has been received by the Employee or Onboarding Candidate.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Download Required</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="download_required">
                                                        <option <?php if (isset($document_info['download_required']) && $document_info['download_required'] == '0') echo 'selected';?> value="0"> No </option>
                                                        <option <?php if (isset($document_info['download_required']) && $document_info['download_required'] == '1') echo 'selected';?> value="1"> Yes </option>
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    Enable the Download Required, if you need the Employee or Onboarding Candidate to download this form.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Signature Required</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="signature_required">
                                                        <option <?php if (isset($document_info['signature_required']) && $document_info['signature_required'] == '0') echo 'selected';?> value="0"> No </option>
                                                        <option <?php if (isset($document_info['signature_required']) && $document_info['signature_required'] == '1')echo 'selected'; ?> value="1"> Yes </option>
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    Enable the Signature Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Sort Order</label>
                                                <input type="number" name="sort_order" class="invoice-fields" value="<?php if (isset($document_info['sort_order'])) echo $document_info['sort_order']; ?>">
                                            </div>
                                        </div>
                                        <!-- <div class="row">-->
                                        <!-- <div class="col-xs-6">-->
                                        <!-- <label>&nbsp;</label>-->
                                        <!-- <div class="input-group pto-time-off-margin-custom">-->
                                        <!-- <span class="input-group-addon">Automatically assign after Days</span>-->
                                        <!--   <input type="number" class="form-control" value="--><?php //echo isset($document_info['automatic_assign_in']) && !empty($document_info['automatic_assign_in']) ? $document_info['automatic_assign_in'] : 0; ?><!--" name="assign-in">-->
                                        <!--  </div>-->
                                        <!--  </div>-->
                                        <!--  </div>-->
                                        <br />
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h5>
                                                            <strong>Authorized Managers</strong>&nbsp;<i class="fa fa-question-circle-o csClickable jsHintBtn" aria-hidden="true" data-target="managers"></i>
                                                            <p class="jsHintBody" data-hint="managers"><br /><?=getUserHint('authorized_managers_hint');?></p>
                                                        </h5>
                                                    </div>
                                                    <div class="panel-body">
                                                        <!-- Employees -->
                                                        <label>Employees</label>
                                                        <select name="managers[]" id="jsManagers" multiple>
                                                        <?php 
                                                            //
                                                            $allowedOnes = empty($document_info['signers']) ? [] : explode(',', $document_info['signers']);
                                                            //
                                                            if(!empty($employeesList)){
                                                                foreach($employeesList as $v){
                                                                    ?>
                                                                    <option value="<?=$v['sid'];?>" <?=in_array($v['sid'], $allowedOnes) ? 'selected' : '';?>><?=remakeEmployeeName($v);?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Visibility  -->
                                        <br />
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h5>
                                                            <strong>Visibility</strong>&nbsp;<i class="fa fa-question-circle-o csClickable jsHintBtn" aria-hidden="true"  data-target="visibilty"></i>
                                                            <p class="jsHintBody" data-hint="visibilty"><br /><?=getUserHint('visibility_hint');?></p>
                                                        </h5>
                                                    </div>
                                                    <div class="panel-body">
                                                        <!-- Payroll -->
                                                        <label class="control control--checkbox">
                                                            Visible To Payroll
                                                            <input type="checkbox" name="visible_to_payroll" <?=isset($document_info['visible_to_payroll']) && $document_info['visible_to_payroll'] ? 'checked' : '';?> value="yes"/>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <hr />
                                                        <!-- Roles -->
                                                        <label>Roles</label>
                                                        <select name="roles[]" id="jsRoles" multiple>
                                                        <?php
                                                            //
                                                            $allowedOnes = empty($document_info['is_available_for_na']) ? [] : explode(',', $document_info['is_available_for_na']);
                                                            //
                                                            foreach(getRoles() as $k => $v){
                                                                ?>
                                                                <option value="<?=$k;?>" <?=in_array($k, $allowedOnes) ? 'selected' : '';?>><?=$v;?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                        </select>
                                                        <br />
                                                        <br />
                                                        <!-- Departments -->
                                                        <label>Departments</label>
                                                        <select name="departments[]" id="jsDepartments" multiple>
                                                        <?php 
                                                            //
                                                            $allowedOnes = empty($document_info['allowed_departments']) ? [] : explode(',', $document_info['allowed_departments']);
                                                            //
                                                            if(!empty($departments)){
                                                                foreach($departments as $v){
                                                                    ?>
                                                                    <option value="<?=$v['sid'];?>" <?=in_array($v['sid'], $allowedOnes) ? 'selected' : '';?>><?=$v['name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        ?>
                                                        </select>
                                                        <br />
                                                        <br />
                                                        <!-- Teams -->
                                                        <label>Teams</label>
                                                        <select name="teams[]" id="jsTeams" multiple>
                                                        <?php 
                                                            //
                                                            $allowedOnes = empty($document_info['allowed_teams']) ? [] : explode(',', $document_info['allowed_teams']);
                                                            //
                                                            if(!empty($teams)){
                                                                foreach($teams as $v){
                                                                    ?>
                                                                    <option value="<?=$v['sid'];?>" <?=in_array($v['sid'], $allowedOnes) ? 'selected' : '';?>><?=$v['name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        ?>
                                                        </select>
                                                        <br />
                                                        <br />
                                                        <!-- Employees -->
                                                        <label>Employees</label>
                                                        <select name="employees[]" id="jsEmployees" multiple>
                                                        <?php 
                                                            //
                                                            $allowedOnes = empty($document_info['allowed_employees']) ? [] : explode(',', $document_info['allowed_employees']);
                                                            //
                                                            if(!empty($employeesList)){
                                                                foreach($employeesList as $v){
                                                                    ?>
                                                                    <option value="<?=$v['sid'];?>" <?=in_array($v['sid'], $allowedOnes) ? 'selected' : '';?>><?=remakeEmployeeName($v);?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        ?>
                                                        </select>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <?php $this->load->view('hr_documents_management/partials/test_approvers_section', ["appCheckboxIdx" => "jsHasApprovalFlowGOL", "containerIdx" => "jsApproverFlowContainerGOL", "addEmployeeIdx" => "jsAddDocumentApproversGOL", "intEmployeeBoxIdx" => "jsEmployeesadditionalBoxGOL", "extEmployeeBoxIdx" => "jsEmployeesadditionalExternalBoxGOL", "approverNoteIdx" => "jsApproversNoteGOL", 'mainId' => 'testApproversGOL']); ?>
                                       <br>
                                        <?php $this->load->view('hr_documents_management/partials/settings', [
                                            'is_confidential' =>  $document_info['is_confidential']
                                        ]); ?>
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <button type="submit" class="btn btn-success" onclick="validate_form();">Save</button>
                                                <a href="<?php echo base_url('hr_documents_management'); ?>" class="btn black-btn">Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                    <div class="offer-letter-help-widget" style="top: 0;">
                                        <div class="how-it-works-insturction">
                                            <strong>Have an Offer Letter / Pay Plan in Word or PDF?</strong>
                                            <p class="how-works-attr">1. Copy and paste your text into this editor</p>
                                            <p class="how-works-attr">2. Insert tags where applicable</p>
                                            <p class="how-works-attr">3. Save the template</p>
                                        </div>
                                        <div class="tags-arae">
                                            <strong>Company Information Tags :</strong>
                                            <ul class="tags">
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{company_name}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{company_address}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{company_phone}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{career_site_url}}"></li>
                                            </ul>
                                        </div>
                                        <div class="tags-arae">
                                            <strong>Employee / Applicant Tags :</strong>
                                            <ul class="tags">
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{first_name}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{last_name}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{email}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{job_title}}"></li>
                                            </ul>
                                        </div>
                                        <div class="tags-arae">
                                            <strong>Signature tags:</strong>

                                            <ul class="tags">
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{signature}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{signature_print_name}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{inital}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{sign_date}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{authorized_signature}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{authorized_signature_date}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{authorized_editable_date}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{short_text}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text_area}}"></li>
                                                <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{checkbox}}"></li>
                                            </ul>
                                        </div>
                                        <div class="tags-arae">
                                            <strong>Pay Plan / Offer Letter tags:</strong>
                                            <ul class="tags">
                                                <li style="background-color: transparent; border: 0px; display: block;">
                                                    <input type="text" class="form-control tag" readonly="" value="{{hourly_rate}}">
                                                </li>
                                                <li style="background-color: transparent; border: 0px; display: block;">
                                                    <input type="text" class="form-control tag" readonly="" value="{{hourly_technician}}">
                                                </li>
                                                <li style="background-color: transparent; border: 0px; display: block;">
                                                    <input type="text" class="form-control tag" readonly="" id="abcde" value="{{flat_rate_technician}}">
                                                </li>
                                                <li style="background-color: transparent; border: 0px; display: block;">
                                                    <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_salary}}">
                                                </li>
                                                <li style="background-color: transparent; border: 0px; display: block;">
                                                    <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_draw}}">
                                                </li>
                                            </ul>
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
<script src="<?= base_url('assets/approverDocument/index.js'); ?>"></script>

<script>
    $(document).ready(function() {
        var approverPrefill = {};
        var approverSection = approverSection = {
            appCheckboxIdx: '.jsHasApprovalFlowGOL',
            containerIdx: '.jsApproverFlowContainerGOL',
            addEmployeeIdx: '.jsAddDocumentApproversGOL',
            intEmployeeBoxIdx: '.jsEmployeesadditionalBoxGOL',
            extEmployeeBoxIdx: '.jsEmployeesadditionalExternalBoxGOL',
            approverNoteIdx: '.jsApproversNoteGOL',
            employeesList: <?= json_encode($employeesList); ?>,
            documentId: 0
        };
        //        
        <?php if (isset($document_info) && !empty($document_info)) { ?>
            var l = <?= json_encode($document_info); ?>;
            //
            if (l.has_approval_flow == 1) {
                approverPrefill.isChecked = true;
                approverPrefill.approverNote = l.document_approval_note;
                approverPrefill.approversList = l.document_approval_employees.split(','); 
                //
                approverSection.prefill = approverPrefill;
            }
        <?php } ?>

        $("#jsGenerateOfferLetter").documentApprovalFlow(approverSection);

        $("#confidentialSelectedEmployees").select2();

        $("#confidentialSelectedEmployeesdiv").show();

        //--- Automatically assign after Days:
        $('input[name="assign-in-days"]').val(0);
        $('input[name="assign-in-months"]').val(0);
        $('.js-type').hide();
        $('input[value="days"]').prop('checked', false);
        $('input[value="months"]').prop('checked', false);
        //
        <?php if (isset($document_info['automatic_assign_in']) && !empty($document_info['automatic_assign_in'])) { ?>
            $('.js-type-<?= $document_info['automatic_assign_type']; ?>').show();
            $('input[value="<?= $document_info['automatic_assign_type']; ?>"]').prop('checked', true);
            $('.js-type-<?= $document_info['automatic_assign_type']; ?>').find('input').val(<?= $document_info['automatic_assign_in']; ?>);
        <?php } else { ?>
            $('input[value="days"]').prop('checked', true);
            $('.js-type-days').show();
        <?php } ?>
        //
        $('input[name="assign_type"]').click(function() {
            $('.js-type').hide(0).val(0);
            $('.js-type-' + ($(this).val()) + '').show(0);
        });
        //---------------------
    });

    function validate_form() {
        $("#form_new_document").validate({
            ignore: [],
            rules: {
                letter_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\-._ ]+$/
                },
                letter_body: {
                    required: true
                }
            },
            messages: {
                letter_name: {
                    required: 'Offer Letter / Pay Plan Template Name is required',
                    pattern: 'Letters, numbers,underscore and dashes only please'
                },
                letter_body: {
                    required: 'Template Letter Body is required',
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }

    //
    $(function(){
        //
        var config = { closeOnSelect: false };
        //
        $('#jsRoles').select2(config);
        $('#jsDepartments').select2(config);
        $('#jsTeams').select2(config);
        $('#jsEmployees').select2(config);
        $('#jsManagers').select2(config);
    });
      
</script>