<link rel="stylesheet" href="<?= base_url(); ?>/assets/mFileUploader/index.css" />
<style>
    /*.select2-container--default .select2-selection--single .select2-selection__arrow{ top: 1px !important; }*/
    .select2-container--default .select2-selection--single {
        border: 1px solid #ccc !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 8px !important;
        padding-right: 20px !important;
    }
</style>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <?php if ($user_type == 'applicant') { ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('applicant_profile/' . $user_sid . '/' . $job_list_sid); ?>"><i class="fa fa-chevron-left"></i>Applicant Profile</a>
                                    <?php } else { ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('employee_profile/' . $user_sid); ?>"><i class="fa fa-chevron-left"></i>Employee Profile</a>
                                        <?php }
                                        ?>Upload / Generate / Hybrid Document
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-right">

                                                        <!-- <button type="submit" name="submit" value="save" class="btn btn-success" onclick="validate_form();">Save</button> -->
                                                        <button type="button" name="submitBTN" value="saveandassign" class="btn btn-success js-click">Save & Assign</button>
                                                        <a href="<?php echo base_url('hr_documents_management'); ?>" class="btn black-btn">Cancel</a>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
                                            <form id="form_new_document_1" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                <!-- Hidden inputs -->
                                                <input type="hidden" id="js_perform_action" name="perform_action" value="" />
                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                                <input type="hidden" id="auth_sign_sid" name="auth_sign_sid" value="0" />
                                                <input type="hidden" name="uploaded_file" class="js-uploaded-file" />
                                                <input type="hidden" name="uploaded_file_orig" class="js-uploaded-file-orig" />
                                                <input type="hidden" name="uploaded_file_ext" class="js-uploaded-file-ext" />
                                                <input type="hidden" name="uploaded_video" class="js-uploaded-video" />
                                                <input type="hidden" name="is_specific" value="<?= $user_id; ?>" />

                                                <input type="hidden" name="document_url" id="add_specific_doc_url" />
                                                <input type="hidden" name="document_name" id="add_specific_doc_name" />
                                                <input type="hidden" name="document_extension" id="add_specific_doc_extension" />
                                                <input type="hidden" name="saveAndAssign" id="saveAndAssign" />

                                                <!-- Template Type -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <label>Template Type <span class="cs-required">*</span></label>
                                                            <br />
                                                            <label class="control control--radio">
                                                                <input type="radio" class="js-template-type" name="js-template-type" value="uploaded" /> Upload &nbsp;
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <label class="control control--radio">
                                                                <input type="radio" class="js-template-type" name="js-template-type" value="generated" /> Generate &nbsp;
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <?php if (checkIfAppIsEnabled('hybrid_document')) { ?>
                                                                <label class="control control--radio">
                                                                    <input type="radio" class="js-template-type" name="js-template-type" value="hybrid_document" /> Hybrid &nbsp;
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            <?php } ?>
                                                            <label class="control control--radio">
                                                                <input type="radio" class="js-template-type" name="js-template-type" value="template" /> Select Template &nbsp;
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Templates -->
                                                <div class="row js-for-selected">
                                                    <div class="col-xs-12">
                                                        <label>Template(s)</label>
                                                        <select id="js-templates">
                                                            <option value="">[Select a template]</option>
                                                            <?php
                                                            if (sizeof($all_documents)) {
                                                                foreach ($all_documents as $key => $value) {
                                                                    $all_documents[$key]['document_description'] = html_entity_decode($value['document_description']);
                                                                    echo '<option value="' . ($value['sid']) . '">' . ($value['document_title']) . ' (' . ($value['document_type']) . ')</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Title -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Document Name<span class="staric">*</span></label>
                                                        <input type="text" name="document_title" id="js-template-title" class="invoice-fields" />
                                                        <?php echo form_error('document_title'); ?>
                                                    </div>
                                                </div>

                                                <!-- Description -->
                                                <div class="row js-for-generated">
                                                    <div class="col-xs-12">
                                                        <?php $field_id = 'document_description'; ?>
                                                        <?php $save_value = isset($document_info[$field_id]) ? html_entity_decode($document_info[$field_id]) : ''; ?>
                                                        <?php echo form_label('Document Content<span class="staric">*</span>', $field_id); ?>
                                                        <div style="margin-bottom:5px;"><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                                        <?php echo form_textarea($field_id, set_value($field_id, $save_value, false), ' class="invoice-fields ckeditor" id="' . $field_id . '"'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>

                                                <!-- Guidence -->
                                                <div class="row js-for-uploaded js-for-guidence">
                                                    <div class="col-xs-12">
                                                        <?php $field_id = 'document_guidence'; ?>
                                                        <?php $save_value = isset($document_info[$field_id]) ? html_entity_decode($document_info[$field_id]) : ''; ?>
                                                        <?php echo form_label('Guidence / Instructions', $field_id); ?>
                                                        <div style="margin-bottom:5px;"><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                                        <?php echo form_textarea($field_id, set_value($field_id, $save_value, false), ' class="invoice-fields ckeditor" id="' . $field_id . '"'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>

                                                <!-- Choose File -->
                                                <div class="row js-for-uploaded">
                                                    <div class="col-xs-12">
                                                        <label>Browse Document<span class="staric">*</span></label>
                                                        <input style="display: none;" type="file" name="document" id="specific_document">
                                                        <!-- <div class="upload-file invoice-fields">
                                                            <div id="remove_image" class="profile-picture js-image-preview-btn">
                                                                <a href="javascript:;" class="action-btn js-show-current-document">
                                                                    <i class="fa fa-lightbulb-o fa-2x"></i>
                                                                    <span class="btn-tooltip">View Current Document</span>
                                                                </a>
                                                            </div>

                                                            <input type="file" name="document" id="document" onchange="check_file('document')" />
                                                            <p id="name_document"></p>
                                                            <a href="javascript:;">Choose File</a>
                                                        </div> -->
                                                    </div>
                                                </div>

                                                <!-- Onboarding -->
                                                <div class="row">
                                                    <div class="col-xs-12 margin-top">
                                                        <label>Include in Onboarding<span class="staric">*</span></label>
                                                        <div class="hr-select-dropdown">
                                                            <select id="js-onboarding" class="invoice-fields" name="onboarding">
                                                                <option value="0"> No </option>
                                                                <option value="1"> Yes </option>
                                                            </select>
                                                        </div>
                                                        <div class="help-text">
                                                            This document will be available to select or send to new hires as part of the Onboarding wizard.
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Acknowledged -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Acknowledgment Required</label>
                                                        <div class="hr-select-dropdown">
                                                            <select id="js-acknowledgment" class="invoice-fields" name="acknowledgment_required">
                                                                <option value="0"> No </option>
                                                                <option value="1"> Yes </option>
                                                            </select>
                                                        </div>
                                                        <div class="help-text">
                                                            Enable the Acknowledgment Requirement, if you need a confirmation that a Document has been received by the Employee or Onboarding Candidate.
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Download -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Download Required</label>
                                                        <div class="hr-select-dropdown">
                                                            <select id="js-download" class="invoice-fields" name="download_required">
                                                                <option value="0"> No </option>
                                                                <option value="1"> Yes </option>
                                                            </select>
                                                        </div>
                                                        <div class="help-text">
                                                            Enable the Download Required, if you need the Employee or Onboarding Candidate to download this form.
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Signature -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label class="js-for-generated">Signature Required</label>
                                                        <label class="js-for-uploaded">Re-upload Required</label>
                                                        <div class="hr-select-dropdown">
                                                            <select id="js-signature" class="invoice-fields" name="signature_required">
                                                                <option value="0"> No </option>
                                                                <option value="1"> Yes </option>
                                                            </select>
                                                        </div>
                                                        <div class="help-text js-for-generated">
                                                            Enable the Signature Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.
                                                        </div>
                                                        <div class="help-text js-for-uploaded">
                                                            Enable the Re-Upload Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Sort Order -->
                                                <div class="row hidden">
                                                    <div class="col-xs-12">
                                                        <label>Sort Order</label>
                                                        <input type="number" id="js-sort-order" name="sort_order" class="invoice-fields" />
                                                    </div>
                                                </div>
                                                <br />

                                                <!-- Video Section -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="hr-box">
                                                            <div class="hr-box-header">
                                                                <strong>Assign Video:</strong>
                                                            </div>
                                                            <div class="hr-innerpadding">
                                                                <div class="universal-form-style-v2">
                                                                    <ul>
                                                                        <li class="form-col-100 autoheight edit_filter">
                                                                            <label for="video_source">Video Source</label>
                                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                                <?php echo NO_VIDEO; ?>
                                                                                <input class="video_source" type="radio" id="video_source_youtube" name="video_source" value="not_required">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                                <?php echo YOUTUBE_VIDEO; ?>
                                                                                <input class="video_source" type="radio" id="video_source_youtube" name="video_source" value="youtube" />
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                                <?php echo VIMEO_VIDEO; ?>
                                                                                <input class="video_source" type="radio" id="video_source_vimeo" name="video_source" value="vimeo" />
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                                <?php echo UPLOAD_VIDEO; ?>
                                                                                <input class="video_source" type="radio" id="video_source_upload" name="video_source" value="upload" />
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </li>
                                                                        <li class="form-col-100" id="yt_vm_video_container">
                                                                            <input type="text" name="yt_vm_video_url" value="" class="invoice-fields" id="yt_vm_video_url">
                                                                            <?php echo form_error('yt_vm_video_url'); ?>
                                                                        </li>
                                                                        <li class="form-col-100 autoheight edit_filter" id="up_video_container" style="display: none">
                                                                            <div class="upload-file invoice-fields">
                                                                                <span class="selected-file" id="name_video_upload"></span>
                                                                                <input type="file" name="video_upload" id="video_upload" onchange="video_check('video_upload')">
                                                                                <a href="javascript:;">Choose Video</a>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Groups Section -->
                                                <div class="row hidden">
                                                    <div class="col-xs-12">
                                                        <div class="hr-box">
                                                            <div class="hr-box-header">
                                                                <strong>Document Group Management:</strong>
                                                            </div>
                                                            <div class="hr-innerpadding">
                                                                <div class="universal-form-style-v2">
                                                                    <?php if (!empty($document_groups)) { ?>
                                                                        <?php foreach ($document_groups as $key => $document) { ?>
                                                                            <div class="col-xs-6">
                                                                                <label class="control control--checkbox font-normal">
                                                                                    <?php echo $document['name']; ?>
                                                                                    <input class="disable_doc_checkbox document_group_assignment" name="document_group_assignment[]" type="checkbox" value="<?php echo $document['sid']; ?>" <?php echo in_array($document['sid'], $pre_assigned_groups) ? 'checked="checked"' : ''; ?>>
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            </div>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Employees Section -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="hr-box">
                                                            <div class="hr-box-header">
                                                                <strong>Authorized Management Signers:</strong>
                                                            </div>
                                                            <div class="hr-innerpadding">
                                                                <div class="universal-form-style-v2">
                                                                    <?php foreach ($employeesList as $key => $emp) { ?>
                                                                        <div class="col-xs-6">
                                                                            <label class="control control--checkbox font-normal">
                                                                                <?php echo remakeEmployeeName($emp); ?>
                                                                                <input class="disable_doc_checkbox js-signer-list" name="managersList[]" type="checkbox" value="<?php echo $emp['sid']; ?>" />
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Category Section -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <label>Categories</label><br>
                                                        <div class="Category_chosen">
                                                            <select data-placeholder="Please Select" multiple="multiple" onchange="" name="categories[]" id="createcategories" class="categories">
                                                                <?php if (sizeof($active_categories) > 0) {
                                                                    foreach ($active_categories as $category) { ?>
                                                                        <option <?= isset($assigned_categories) && in_array($category['sid'], $assigned_categories) ? "selected" : "" ?> value="<?php echo $category['sid']; ?>"><?= $category['name'] ?></option>
                                                                <?php }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br />

                                                <!-- Visibility Section -->
                                                <br />
                                                <?php $this->load->view('hr_documents_management/partials/visibility'); ?>
                                                <br />
                                                <?php $this->load->view('hr_documents_management/partials/test_approvers_section', ["appCheckboxIdx" => "jsHasApprovalFlowAD", "containerIdx" => "jsApproverFlowContainerAD", "addEmployeeIdx" => "jsAddDocumentApproversAD", "intEmployeeBoxIdx" => "jsEmployeesadditionalBoxAD", "extEmployeeBoxIdx" => "jsEmployeesadditionalExternalBoxAD", "approverNoteIdx" => "jsApproversNoteAD", 'mainId' => 'testApproversAD']); ?>
                                                <!-- Sign In -->
                                                <div class="row hidden">
                                                    <div class="col-xs-12">
                                                        <div class="hr-box">
                                                            <div class="hr-box-header">
                                                                <strong>Automatically assign after Days:</strong>
                                                            </div>
                                                            <div class="hr-innerpadding">
                                                                <div class="row">
                                                                    <div class="col-xs-12">
                                                                        <div class="">
                                                                            <div class="">
                                                                                <label class="control control--radio">
                                                                                    Days
                                                                                    <input type="radio" class="js-assign-type" name="assign_type" value="days" />
                                                                                    <div class="control__indicator"></div>
                                                                                </label> &nbsp;
                                                                                <label class="control control--radio font-normal">
                                                                                    Months
                                                                                    <input type="radio" class="js-assign-type" name="assign_type" value="months" />
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br />

                                                                <div class="row">
                                                                    <div class="col-xs-6 js-type-days js-type">
                                                                        <div class="universal-form-style-v2">
                                                                            <div class="input-group pto-time-off-margin-custom">
                                                                                <input type="number" class="form-control js-assign-type-days" value="0" name="assign-in-days">
                                                                                <span class="input-group-addon">Days</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-6 js-type-months js-type">
                                                                        <div class="universal-form-style-v2">
                                                                            <div class="input-group pto-time-off-margin-custom">
                                                                                <input type="number" class="form-control js-assign-type-months" value="0" name="assign-in-months">
                                                                                <span class="input-group-addon">Months</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <br />
                                                <?php $this->load->view('hr_documents_management/partials/send_dwmc', [
                                                    'userSid' => $user_sid,
                                                    'userType' => $user_type,
                                                    'dwmc' => true
                                                ]); ?>

                                                <!-- Send email -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="hr-box">
                                                            <div class="hr-box-header">

                                                                <strong>Send an email notification?</strong>
                                                            </div>
                                                            <div class="hr-innerpadding">
                                                                <div class="row">
                                                                    <div class="col-xs-12">
                                                                        <label class="control control--radio font-normal">
                                                                            <input class="disable_doc_checkbox" name="sendEmail" type="radio" value="no" checked="true" />
                                                                            No &nbsp;
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                        <label class="control control--radio font-normal">
                                                                            <input class="disable_doc_checkbox" name="sendEmail" type="radio" value="yes" checked="true" />
                                                                            Yes &nbsp;
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Send email -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="hr-box">
                                                            <div class="hr-box-header">
                                                                <strong>Is this document required?</strong>
                                                                <p class="help-text">If marked yes, then the applicant needs to add e-sign this document to complete the onboarding process.</p>
                                                            </div>
                                                            <div class="hr-innerpadding">
                                                                <div class="row">
                                                                    <div class="col-xs-12">
                                                                        <label class="control control--radio font-normal">
                                                                            <input class="disable_doc_checkbox" name="isRequired" type="radio" value="0" checked="true" />
                                                                            No &nbsp;
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                        <label class="control control--radio font-normal">
                                                                            <input class="disable_doc_checkbox" name="isRequired" type="radio" value="1" />
                                                                            Yes &nbsp;
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <br>
                                                <?php $this->load->view('hr_documents_management/partials/settings'); ?>

                                                <?php if (checkIfAppIsEnabled('documentlibrary')) { ?>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="hr-box">
                                                                <div class="hr-box-header">
                                                                    <strong>Document Library?</strong>
                                                                </div>
                                                                <div class="hr-innerpadding">
                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <p class="text-danger"><strong><em>If marked "Yes", this document will appear in the Employee Document library and allow Employees to initiate this document themselves.</em></strong></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <p>Add this document to the Employee library?</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <label class="control control--radio font-normal">
                                                                                <input class="disable_doc_checkbox" name="isdoctolibrary" type="radio" value="0" checked="true" />
                                                                                No &nbsp;
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                            <label class="control control--radio font-normal">
                                                                                <input class="disable_doc_checkbox" name="isdoctolibrary" type="radio" value="1" />
                                                                                Yes &nbsp;
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <?php if (false) { ?>
                                                                        <hr>
                                                                        <div class="row">
                                                                            <div class="col-xs-12">
                                                                                <p class="text-danger"><strong><em>If "No", the document will not visible to employee on document center.</em></strong></p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-xs-12">
                                                                                <p>Is the document visible to employee on document center?</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-xs-12">
                                                                                <label class="control control--radio font-normal">
                                                                                    <input class="disable_doc_checkbox" name="visibletodocumentcenter" type="radio" value="0" checked="true" />
                                                                                    No &nbsp;
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                                <label class="control control--radio font-normal">
                                                                                    <input class="disable_doc_checkbox" name="visibletodocumentcenter" type="radio" value="1" />
                                                                                    Yes &nbsp;
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>


                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="hr-box">
                                                                <div class="hr-box-header">
                                                                    <strong>Employee Handbook and Policies?</strong>
                                                                </div>
                                                                <div class="hr-innerpadding">
                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <p class="text-danger"><strong><em>If marked "Yes", this document will appear in the Employee Handbook and Policies.</em></strong></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <p>Add this document to the Employee Handbook and Policies?</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <label class="control control--radio font-normal">
                                                                                <input class="disable_doc_checkbox" name="isdoctohandbook" type="radio" value="0" checked="true" />
                                                                                No &nbsp;
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                            <label class="control control--radio font-normal">
                                                                                <input class="disable_doc_checkbox" name="isdoctohandbook" type="radio" value="1" />
                                                                                Yes &nbsp;
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                <!-- Send email -->
                                                <div class="row hidden">
                                                    <div class="col-xs-12">
                                                        <div class="hr-box">
                                                            <div class="hr-box-header">
                                                                <strong>Is signature required?</strong>
                                                                <p class="help-text">If marked yes, then the applicant needs to add e-sign this document to complete the onboarding process.</p>
                                                            </div>
                                                            <div class="hr-innerpadding">
                                                                <div class="row">
                                                                    <div class="col-xs-12">
                                                                        <label class="control control--radio font-normal">
                                                                            <input class="disable_doc_checkbox" name="isSignatureRequired" type="radio" value="0" checked="true" />
                                                                            No &nbsp;
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                        <label class="control control--radio font-normal">
                                                                            <input class="disable_doc_checkbox" name="isSignatureRequired" type="radio" value="1" />
                                                                            Yes &nbsp;
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <span class="pull-right">
                                                            <input type="submit" id="jsSubmitFormBTN" style="display: none;" />
                                                            <!-- <button type="submit" name="submit" value="save" class="btn btn-success" onclick="validate_form();">Save</button> -->
                                                            <button type="button" name="submitBTN" value="saveandassign" class="btn btn-success" id="jsSubmitForm">Save & Assign</button>
                                                            <a href="<?php echo base_url('hr_documents_management'); ?>" class="btn black-btn">Cancel</a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                            <div class="offer-letter-help-widget" style="top: 0;">
                                                <div class="how-it-works-insturction">
                                                    <strong>How it's Works :</strong>
                                                    <p class="how-works-attr">1. Generate new Electronic Document</p>
                                                    <p class="how-works-attr">2. Enable Document Acknowledgment</p>
                                                    <p class="how-works-attr">3. Enable Electronic Signature</p>
                                                    <p class="how-works-attr">4. Insert multiple tags where applicable</p>
                                                    <p class="how-works-attr">5. Save the Document</p>
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
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" id="abcde" value="{{inital}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{sign_date}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{authorized_signature}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{authorized_signature_date}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{authorized_editable_date}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{short_text}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text_area}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{checkbox}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{short_text_required}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text_required}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text_area_required}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{checkbox_required}}"></li>
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
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<input type="hidden" value="0" id="validation_flag">

<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;"> Please wait, while we are processing your request.
        </div>
    </div>
</div>


<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/mFileUploader/index.js"></script>
<script src="<?= base_url('assets/approverDocument/index.js'); ?>"></script>

<script>
    var btnTypeO = 'saveandassign';
    $(document).ready(function() {
        //
        var approverSection = approverSection = {
            appCheckboxIdx: '.jsHasApprovalFlowAD',
            containerIdx: '.jsApproverFlowContainerAD',
            addEmployeeIdx: '.jsAddDocumentApproversAD',
            intEmployeeBoxIdx: '.jsEmployeesadditionalBoxAD',
            extEmployeeBoxIdx: '.jsEmployeesadditionalExternalBoxAD',
            approverNoteIdx: '.jsApproversNoteAD',
            employeesList: <?= json_encode($employeesList); ?>,
            documentId: 0
        };
        //
        $("#jsGenerateOfferLetter").documentApprovalFlow(approverSection);
        //
        $('#specific_document').mFileUploader({
            fileLimit: -1, // Default is '2MB', Use -1 for no limit (Optional)
            allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv'], //(Optional)
        });

        //
        $('.js-click').click(function() {
            $('#jsSubmitForm').click();
        });
        $('#jsSubmitForm').click(function() {
            $('#jsSubmitFormBTN').click();
        });
        // New JS
        //
        var
            allDocumnets = <?= json_encode($all_documents); ?>,
            selectedTemplate = {};
        //
        $('#js-templates').select2();
        // 
        $('.js-template-type').click(function(e) {
            //
            $('#js_perform_action').val($(this).val());
            //
            $('.js-for-selected').hide(0);
            //
            makeView($(this).val());
        });
        //
        $('#js-templates').change(function(e) {
            //
            var d = getDocument($(this).val());
            //
            makeView(d.document_type);
            //
            selectedTemplate = d;
            //
            $('#js-template-title').val(d.document_title);
            //
            CKEDITOR.instances.document_description.setData(d.document_description);
            CKEDITOR.instances.document_guidence.setData(d.document_description);
            //
            $('#js-onboarding option[value="' + (d.onboarding) + '"]').prop('selected', true);
            $('#js-download option[value="' + (d.download_required) + '"]').prop('selected', true);
            $('#js-acknowledgment option[value="' + (d.acknowledgment_required) + '"]').prop('selected', true);
            $('#js-signature option[value="' + (d.signature_required) + '"]').prop('selected', true);
            //
            $('#js-visible-pp[value="' + (d.visible_to_payroll) + '"]').prop('selected', true);
            //
            if (d.is_available_for_na != null) {
                $('#js-roles').select2('val', d.is_available_for_na.split(','))
            }
            if (d.allowed_employees != null) {
                $('#js-specific-employee-visibility').select2('val', d.allowed_employees.split(','))
            }
            //
            $('.js-assign-type-days').val(d.automatic_assign_in);
            $('.js-assign-type-months').val(d.automatic_assign_in);
            $('.js-assign-type[value="' + (d.automatic_assign_type) + '"]').prop('checked', true);
            //
            if (d.document_type == 'uploaded' || d.document_type == 'hybrid_document') {
                $('.js-uploaded-file').val(d.uploaded_document_s3_name);
                $('.js-uploaded-file-orig').val(d.uploaded_document_original_name);
                $('.js-uploaded-file-ext').val(d.uploaded_document_extension);
                $('.js-image-preview-btn').show();
            }
            //
            if (d.video_source != null) {
                $('.video_source[value="' + (d.video_source) + '"]').prop('checked', true);
                if (d.video_source == 'youtube' || d.video_source == 'vimeo') {
                    $('#yt_vm_video_container').show();
                    $('#yt_vm_video_url').val((d.video_source == 'youtube' ? 'https://www.youtube.com/watch?v=' : 'https://vimeo.com/') + d.video_url);
                } else {
                    $('#up_video_container').show();
                    $('#name_video_upload').text(d.video_url);
                    $('#yt_vm_video_url').val(d.video_url);
                }
            }
            //
            if (d.managers_list != null) {
                d.managers_list.split(',').map(function(v) {
                    $('.js-signer-list[value="' + (v) + '"]').prop('checked', true);
                });
            }
            //
            if (d.groups != null) {
                d.groups.map(function(v) {
                    $('.document_group_assignment[value="' + (v) + '"]').prop('checked', true);
                });
            }
            //
            if (d.categories != null) {
                $('#createcategories').select2('val', d.categories);
            }
            //
            $("#setting_is_confidential").prop('checked', false);
            $("#confidentialSelectedEmployees").select2("val", null);
            //
            if(d.is_confidential == 1){
                $("#setting_is_confidential").prop('checked', true);
                $("#confidentialSelectedEmployees").select2("val", null);
                //
                if(d.confidential_employees){
                    $("#confidentialSelectedEmployees").select2("val", d.confidential_employees.split(','));
                }
            }
            //
            $('#js_perform_action').val(d.document_type);
        });

        //
        $('.js-image-preview-btn').click(ShowUploadedFilePreview);

        //
        resetView();

        // Helpers
        //
        function ShowUploadedFilePreview(e) {
            e.preventDefault();
            //
            var f = getUploadedFileAPIUrl(
                selectedTemplate.uploaded_document_s3_name
            );
            //
            Modal(
                selectedTemplate.uploaded_document_original_name,
                f.getHTML(),
                f.getButtonHTML(),
                'js-document-file-popup'
            );
            //
            loadIframe(
                f.URL,
                f.Target,
                true
            );
            //
            $('.js-document-file-popup-loader').hide(0);
        }

        // Make view
        function makeView(c) {
            switch (c) {
                case 'generated':
                    $('.js-for-uploaded').hide(0);
                    $('.js-for-generated').show(0);
                    break;
                case 'uploaded':
                    $('.js-for-generated').hide(0);
                    $('.js-for-uploaded').show(0);
                    break;
                case 'template':
                    $('.js-for-selected').show(0);
                    break;
                case 'hybrid_document':

                    $('.js-for-generated').show(0);
                    $('.js-for-uploaded').show(0);
                    $('.js-for-guidence').hide(0);
                    break;
            }
            resetView(true);
        }

        // Reset view
        function resetView(i) {
            if (i == undefined) $('.js-template-type[value="uploaded"]').trigger('click');
            $('.js-image-preview-btn').hide(0);
            $('#js-roles').select2('val', []);
            $('#js-specific-employee-visibility').select2('val', []);
            //
            $('.video_source[value="not_required"]').prop('checked', true);
        }

        // Get document
        function getDocument(
            sid
        ) {
            var
                i = 0,
                il = allDocumnets.length;
            //
            for (i; i < il; i++) {
                if (allDocumnets[i]['sid'] == sid) return allDocumnets[i];
            }
            //
            return {};
        }

        // Modal generator
        function Modal(
            title,
            contents,
            footerButtons,
            sid,
            cks,
            sels,
            cb
        ) {
            //
            sid = sid == undefined ? uuidv4() : sid;
            title = title == undefined ? '' : title;
            contents = contents == undefined ? '' : contents;
            footerButtons = footerButtons == undefined ? '' : footerButtons;
            cks = cks == undefined ? [] : cks;
            sels = sels == undefined ? [] : sels;
            //
            var rows = '';
            //
            rows += '<div class="modal fade" id="' + (sid) + '">';
            rows += '   <div class="modal-dialog modal-lg">';
            rows += '       <!-- loader --><div class="loader ' + (sid) + '-loader"><i class="fa fa-spinner fa-spin"></i></div>';
            rows += '       <div class="modal-content">';
            rows += '           <div class="modal-header modal-header-bg">';
            rows += '               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            rows += '               <h4 class="modal-title">' + (title) + '</h4>';
            rows += '           </div>';
            rows += '           <div class="modal-body">';
            rows += contents;
            rows += '           </div>';
            rows += '           <div class="modal-footer">';
            rows += '               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
            rows += footerButtons;
            rows += '           </div>';
            rows += '       </div>';
            rows += '   </div>';
            rows += '</div>';
            //
            $('#' + sid).remove();
            //
            $('body').append(rows);
            //
            $('#' + sid).modal();
            //
            if (cks.length !== 0) $.each(cks, function(index, val) {
                CKEDITOR.replace(val);
            });
            if (sels.length !== 0) $.each(sels, function(index, val) {
                $(val).select2({
                    closeOnSelect: $(val).prop('multiple') ? false : true
                });
            });

            if (cb !== undefined) cb();
        }

        //
        function getUploadedFileAPIUrl(
            f,
            o
        ) {
            if (f == null || f == '') return {};
            // Get file extension
            var
                r = {},
                full = "<?= AWS_S3_BUCKET_URL; ?>" + f,
                t = f.split('.');
            t = t[t.length - 1].toLowerCase().trim();
            //
            if ($.inArray(t, ['csv', 'docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx']) !== -1) {
                r = {
                    URL: 'https://view.officeapps.live.com/op/embed.aspx?src=' + (full) + '',
                    PrintURL: 'https://view.officeapps.live.com/op/embed.aspx?src=' + (full) + '',
                    DownloadURL: "<?= base_url('hr_documents_management/download_upload_document'); ?>/" + f,
                    Extension: t,
                    Target: '.js-preview-iframe',
                    Type: 'iframe',
                    getHTML: () => '<iframe src="' + (r.URL) + '" frameborder="0" style="width: 100%; height: 500px;" class="js-preview-iframe"></iframe>',
                    getPrintHTML: () => '<a href="' + (r.PrintURL) + '" target="_blank" class="btn btn-success btn-sm">Print</a>',
                    getDownloadHTML: () => '<a href="' + (r.DownloadURL) + '" class="btn btn-success btn-sm">Download</a>',
                    getButtonHTML: () => r.getPrintHTML() + ' &nbsp; ' + r.getDownloadHTML()
                };
            } else if ($.inArray(t, ['jpe', 'jpeg', 'png', 'gif', 'jpg', 'jpe', 'jpeg', 'png', 'gif']) !== -1) {
                r = {
                    URL: full,
                    PrintURL: full,
                    DownloadURL: "<?= base_url('hr_documents_management/download_upload_document'); ?>/" + f,
                    Extension: t,
                    Target: '.js-preview-iframe',
                    Type: 'image',
                    getHTML: () => '<img src="' + (r.URL) + '" style="max-width: 100%; display: block; margin: auto;" class="js-preview-iframe" />',
                    getPrintHTML: () => '<a href="' + (r.PrintURL) + '" target="_blank" class="btn btn-success btn-sm">Print</a>',
                    getDownloadHTML: () => '<a href="' + (r.DownloadURL) + '" class="btn btn-success btn-sm">Download</a>',
                    getButtonHTML: () => r.getPrintHTML() + ' &nbsp; ' + r.getDownloadHTML()
                };
            } else {
                r = {
                    URL: 'https://docs.google.com/gview?url=' + (full) + '&embedded=true',
                    PrintURL: 'https://docs.google.com/gview?url=' + (full) + '&embedded=true',
                    DownloadURL: "<?= base_url('hr_documents_management/download_upload_document'); ?>/" + f,
                    Extension: t,
                    Type: 'iframe',
                    Target: '.js-preview-iframe',
                    getHTML: () => '<iframe src="' + (r.URL) + '" frameborder="0" style="width: 100%; height: 500px;" class="js-preview-iframe"></iframe>',
                    getPrintHTML: () => '<a href="' + (r.PrintURL) + '" target="_blank" class="btn btn-success btn-sm">Print</a>',
                    getDownloadHTML: () => '<a href="' + (r.DownloadURL) + '" class="btn btn-success btn-sm">Download</a>',
                    getButtonHTML: () => r.getPrintHTML() + ' &nbsp; ' + r.getDownloadHTML()
                }
            }
            //
            return r;
        }

        // 
        $('input[name="assign-in-days"]').val(0);
        $('input[name="assign-in-months"]').val(0);
        $('.js-type').hide();
        $('input[value="days"]').prop('checked', false);
        $('input[value="months"]').prop('checked', false);

        $('input[value="days"]').prop('checked', true);
        $('.js-type-days').show();
        $('#yt_vm_video_container').hide();
        $('#up_video_container').hide();
        //
        $('input[name="assign_type"]').click(function() {
            $('.js-type').hide(0).val(0);
            $('.js-type-' + ($(this).val()) + '').show(0);
        });


        //
        $('.categories').select2({
            closeOnSelect: false,
            allowHtml: true,
            allowClear: true,
            // tags: true
        });
    });

    function check_file(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 38));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'document') {
                if (ext != "pdf" && ext != "docx" && ext != "pptx" && ext != "ppt" && ext != "doc" && ext != "xls" && ext != "xlsx" && ext != "PDF" && ext != "DOCX" && ext != "DOC" && ext != "XLS" && ext != "XLSX" && ext != "CSV" && ext != "csv") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc .pptx .ppt) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    var inst = null;

    /**
     * Uploads file to server and
     * appends to the form
     */
    function upload_document_with_ajax_request(
        docURL,
        docName,
        docExt,
        document_title,
        upload_file,
        loaderREF
    ) {
        //
        $(loaderREF).show();
        return new Promise((resolve, reject) => {
            //
            var form_data = new FormData();
            form_data.append('document', upload_file);
            form_data.append('company_sid', '<?php echo $company_sid; ?>');
            form_data.append('user_sid', '<?php echo $user_sid; ?>');
            form_data.append('user_type', '<?php echo $user_type; ?>');
            form_data.append('document_title', document_title);

            $.ajax({
                url: '<?= base_url('hr_documents_management/upload_file_ajax_handler') ?>',
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(data) {
                    $(loaderREF).hide();
                    var obj = jQuery.parseJSON(data);
                    if (obj.upload_status == 'success') {
                        $(docURL).val(obj.document_url);
                        $(docName).val(obj.original_name);
                        $(docExt).val(obj.extension);
                        resolve('success');

                    } else resolve('failed');
                },
                error: function() {
                    resolve('failed');
                }
            });
        });
    }

    $('#form_new_document_1').on('submit', function check_form(e) {
        validate_my_form(e, this);
    });

    async function validate_my_form(event, form) {
        event.preventDefault();
        //
        var document_title = $('#js-template-title').val();
        //
        if (document_title == '' || document_title == undefined) {
            alertify.alert('WARNING!', 'Please enter the document title.');
            return false;
        }
        //
        if (($('.js-template-type:checked').val() == 'generated' || $('.js-template-type:checked').val() == 'hybrid_document') && CKEDITOR.instances.document_description.getData() == '') {
            alertify.alert('WARNING!', 'Document content is required.');
            return false;
        }
        //

        if ($("#setting_is_confidential").is(":checked")) {
              var call=$("#confidentialSelectedEmployees").select2("val");
              if($("#confidentialSelectedEmployees").select2("val")==null){
                alertify.error('Please Select employee for confidential document');
                return false;
              }
            } 

        var video_source = $('input[name="video_source"]:checked').val();

        if (video_source != 'not_required') {
            if (video_source == 'youtube') {
                if ($('#yt_vm_video_url').val() != '') {
                    var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;

                    if (!$('#yt_vm_video_url').val().match(p)) {
                        alertify.alert('WARNING!', 'Not a Valid Youtube URL');
                        return false;
                    }
                } else {
                    var url_check = '<?php echo $this->uri->segment(2); ?>';

                    if (url_check == 'edit_hr_document') {
                        var old_doc_video_source = $('#old_doc_video_source').val();
                        var old_doc_video_url = $('#old_doc_video_url').val();

                        if (old_doc_video_source == 'youtube' && old_doc_video_url != '') {
                            return true;
                        } else {
                            alertify.alert('WARNING!', 'Please provide a Valid Youtube URL');
                            return false;
                        }
                    } else {
                        alertify.alert('WARNING!', 'Please provide a Valid Youtube URL');
                        return false;
                    }
                }

            }

            if (video_source == 'vimeo') {
                if ($('#yt_vm_video_url').val() != '') {
                    var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {
                            url: $('#yt_vm_video_url').val()
                        },
                        async: false,
                        success: function(data) {
                            if (data == false) {
                                alertify.alert('WARNING!', 'Not a Valid Vimeo URL');
                                return false;
                            }
                        },
                        error: function(data) {}
                    });
                } else {
                    var url_check = '<?php echo $this->uri->segment(2); ?>';

                    if (url_check == 'edit_hr_document') {
                        var old_doc_video_source = $('#old_doc_video_source').val();
                        var old_doc_video_url = $('#old_doc_video_url').val();

                        if (old_doc_video_source == 'vimeo' && old_doc_video_url != '') {
                            return true;
                        } else {
                            alertify.alert('WARNING!', 'Please provide a Valid Vimeo URL');
                            return false;
                        }
                    } else {
                        alertify.alert('WARNING!', 'Please provide a Valid Vimeo URL');
                        return false;
                    }
                }
            }

            if (video_source == 'upload') {
                var old_uploaded_video = $('#pre_upload_video_url').val();
                if (old_uploaded_video != '') {
                    return true;
                } else {
                    var file = video_check('video_upload');
                    if (file == false) {
                        alertify.alert('WARNING!', 'Please select a video to upload.');
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        }

        if ($('.js-template-type:checked').val() == 'uploaded' || $('.js-template-type:checked').val() == 'hybrid_document') {
            var upload_file = $('#specific_document').mFileUploader('get');

            if ($.isEmptyObject(upload_file)) {
                alertify.alert('WARNING!', 'Please select a file to upload.');
                return false;
            } else if (upload_file.hasError == true) {
                alertify.alert('WARNING!', 'Please select a valid file format.');
                return false;
            } else {
                const resp = await upload_document_with_ajax_request(
                    '#add_specific_doc_url',
                    '#add_specific_doc_name',
                    '#add_specific_doc_extension',
                    document_title,
                    upload_file,
                    '#my_loader'
                );
                //
                if (resp == 'failed') {
                    alertify.alert('WARNING!', 'Something went wrong while uploading the file. Please, try again in a few seconds.', () => {});
                    return;
                }
            }
        } else if ($('.js-template-type:checked').val() == 'template') {
            //
            var upload_file = $('#specific_document').mFileUploader('get');
            //
            if (!$.isEmptyObject(upload_file) && upload_file.hasError == false) {
                //
                const resp = await upload_document_with_ajax_request(
                    '#add_specific_doc_url',
                    '#add_specific_doc_name',
                    '#add_specific_doc_extension',
                    document_title,
                    upload_file,
                    '#my_loader'
                );
                //
                if (resp == 'failed') {
                    alertify.alert('WARNING!', 'Something went wrong while uploading the file. Please, try again in a few seconds.', () => {});
                    return;
                }
            }
        }
        //
        $('#saveAndAssign').val(btnTypeO);
        //
        form.submit();
    }

    function check_length() {
        var text_allowed = 500;
        var user_text = $('#document_description').val();
        var newLines = user_text.match(/(\r\n|\n|\r)/g);
        var addition = 0;

        if (newLines != null) {
            addition = newLines.length;
        }

        var text_length = user_text.length + addition;
        var text_left = text_allowed - text_length;
        $('#remaining_text').html(text_left + ' characters left!');
    }

    $('.video_source').on('click', function() {
        var selected = $(this).val();

        if (selected == 'youtube' || selected == 'vimeo') {
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if (selected == 'upload') {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').show();
        } else {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').hide();
        }
    });

    function video_check(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'video_upload') {
                if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid video format.");
                    $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                    return false;
                } else {
                    var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                    var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');

                    if (video_size_limit < file_size) {
                        $("#" + val).val(null);
                        alertify.error('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                        $('#name_' + val).html('');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        return true;
                    }
                }
            }
        } else {
            var url_check = '<?php echo $this->uri->segment(2); ?>';

            if (url_check == 'edit_hr_document') {
                var old_doc_video_source = $('#old_doc_video_source').val();
                var old_doc_video_url = $('#old_doc_video_url').val();

                if (old_doc_video_source == 'upload' && old_doc_video_url == '') {
                    $('#name_' + val).html('No video selected');
                    alertify.error("No video selected");
                    $('#name_' + val).html('<p class="red">Please select video</p>');
                    return false;
                } else {
                    $('#name_' + val).html('No video selected');
                    alertify.error("No video selected");
                    $('#name_' + val).html('<p class="red">Please select video</p>');
                    return false;
                }
            } else {
                $('#name_' + val).html('No video selected');
                alertify.error("No video selected");
                $('#name_' + val).html('<p class="red">Please select video</p>');
                return false;
            }
        }
    }

    $("#yt_vm_video_url").change(function() {
        var video_source = $('input[name="video_source"]:checked').val();

        if (video_source == 'youtube') {
            if ($('#yt_vm_video_url').val() != '') {
                var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;

                if (!$('#yt_vm_video_url').val().match(p)) {
                    alertify.error('Not a Valid Youtube URL');
                    return false;
                }
            }

        }

        if (video_source == 'vimeo') {
            if ($('#yt_vm_video_url').val() != '') {
                var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {
                        url: $('#yt_vm_video_url').val()
                    },
                    async: false,
                    success: function(data) {
                        if (data == false) {
                            alertify.error('Not a Valid Vimeo URL');
                            return false;
                        }
                    },
                    error: function(data) {}
                });
            }
        }

    });
</script>
<style>
    .select2-container {
        min-width: 400px;
    }

    .select2-results__option {
        padding-right: 20px;
        vertical-align: middle;
    }

    .select2-results__option:before {
        content: "";
        display: inline-block;
        position: relative;
        height: 20px;
        width: 20px;
        border: 2px solid #e9e9e9;
        border-radius: 4px;
        background-color: #fff;
        margin-right: 20px;
        vertical-align: middle;
    }

    .select2-results__option[aria-selected=true]:before {
        font-family: fontAwesome;
        content: "\f00c";
        color: #fff;
        background-color: #81b431;
        border: 0;
        display: inline-block;
        padding-left: 3px;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #fff;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #eaeaeb;
        color: #272727;
    }

    .select2-container--default .select2-selection--multiple {
        margin-bottom: 10px;
    }

    .select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
        border-radius: 4px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #81b431;
        border-width: 2px;
    }

    .select2-container--default .select2-selection--multiple {
        border-width: 2px;
    }

    .select2-container--open .select2-dropdown--below {

        border-radius: 6px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

    }

    .select2-selection .select2-selection--multiple:after {
        content: 'hhghgh';
    }

    /* select with icons badges single*/
    .select-icon .select2-selection__placeholder .badge {
        display: none;
    }

    .select-icon .placeholder {
        display: none;
    }

    .select-icon .select2-results__option:before,
    .select-icon .select2-results__option[aria-selected=true]:before {
        display: none !important;
        /* content: "" !important; */
    }

    .select-icon .select2-search--dropdown {
        display: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        height: 25px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: 30px;
    }
</style>

<script>
    $('[data-toggle="propover"]').popover({
        trigger: 'hover',
        placement: 'right'
    });
</script>