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
                                    <?php echo !isset($document_info) ? 'Add HR Document' : 'Edit HR Document'; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
                                    ` <div class="upload-new-doc-heading">
                                        <i class="fa fa-file-text-o"></i>
                                        <?php echo $title; ?>
                                    </div>
                                    <p class="upload-file-type">You can easily create electronically formatted fillable documents for your Employees and Applicants</p>
                                    <form id="form_new_document" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="generate_new_document" />
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                        <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                        <input type="hidden" id="auth_sign_sid" name="auth_sign_sid" value="0" />

                                        <?php if (isset($document_info['sid'])) { ?>
                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document_info['sid']; ?>" />
                                            <input type="hidden" id="perform_action" name="perform_action" value="update_document" />
                                            <input type="hidden" id="type" name="type" value="generated" />
                                        <?php } ?>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Document Name<span class="staric">*</span></label>
                                                <input type="text" name="document_title" value="<?php
                                                                                                if (isset($document_info['document_title'])) {
                                                                                                    echo set_value('document_title', $document_info['document_title']);
                                                                                                } else {
                                                                                                    echo set_value('document_title');
                                                                                                } ?>" class="invoice-fields">
                                                <?php echo form_error('document_title'); ?>
                                            </div>
                                        </div>
                                        <br/>
                                        
                                        <?php if($document_info["fillable_document_slug"]) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <?php $field_id = 'document_description'; ?>
                                                    <?php $save_value = isset($document_info[$field_id]) ? html_entity_decode($document_info[$field_id]) : ''; ?>
                                                    <?php echo form_label('Document Content<span class="staric">*</span>', $field_id); ?>
                                                    <?php $this->load->view("v1/documents/fillable/{$document_info["fillable_document_slug"]}_preview");?>
                                                    <?php echo form_textarea($field_id, set_value($field_id, $save_value, false), ' class="hidden" id="' . $field_id . '"'); ?>
                                                </div>
                                            </div>
                                        <?php } else {?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <?php $field_id = 'document_description'; ?>
                                                    <?php $save_value = isset($document_info[$field_id]) ? html_entity_decode($document_info[$field_id]) : ''; ?>
                                                    <?php echo form_label('Document Content<span class="staric">*</span>', $field_id); ?>
                                                    <div style="margin-bottom:5px;"><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                                    <?php echo form_textarea($field_id, set_value($field_id, $save_value, false), ' class="invoice-fields ckeditor" id="' . $field_id . '"'); ?>
                                                    <?php echo form_error($field_id); ?>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if (!empty($authorized_signature)) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <a href="javascript:;" class="btn btn-success" id="view_auth_signature" style="margin-top: 10px">View Authorized Signature</a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        

                                        <div class="row <?=$document_info["fillable_document_slug"] ? "hidden" :"";?>">
                                            <div class="col-xs-12 margin-top">
                                                <label>Include in Onboarding<span class="staric">*</span></label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="onboarding">
                                                        <option <?php if (isset($document_info['onboarding']) && $document_info['onboarding'] == '0') echo 'selected'; ?> value="0"> No </option>
                                                        <option <?php if (isset($document_info['onboarding']) && $document_info['onboarding'] == '1') echo 'selected'; ?> value="1"> Yes </option>
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    This document will be available to select or send to new hires as part of the Onboarding wizard.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row <?=$document_info["fillable_document_slug"] ? "hidden" :"";?>">
                                            <div class="col-xs-12">
                                                <label>Acknowledgment Required</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="acknowledgment_required">
                                                        <option <?php
                                                                if (isset($document_info['acknowledgment_required']) && $document_info['acknowledgment_required'] == '0') echo 'selected'; ?> value="0"> No </option>
                                                        <option <?php
                                                                if (isset($document_info['acknowledgment_required']) && $document_info['acknowledgment_required'] == '1') echo 'selected'; ?> value="1"> Yes </option>
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    Enable the Acknowledgment Requirement, if you need a confirmation that a Document has been received by the Employee or Onboarding Candidate.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row <?=$document_info["fillable_document_slug"] ? "hidden" :"";?>">
                                            <div class="col-xs-12">
                                                <label>Download Required</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="download_required">
                                                        <option <?php
                                                                if (isset($document_info['download_required']) && $document_info['download_required'] == '0') echo 'selected'; ?> value="0"> No </option>
                                                        <option <?php
                                                                if (isset($document_info['download_required']) && $document_info['download_required'] == '1') echo 'selected'; ?> value="1"> Yes </option>
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    Enable the Download Required, if you need the Employee or Onboarding Candidate to download this form.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row <?=$document_info["fillable_document_slug"] ? "hidden" :"";?>">
                                            <div class="col-xs-12">
                                                <label>Signature Required</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="signature_required">
                                                        <option <?php if (isset($document_info['signature_required']) && $document_info['signature_required'] == '0') echo 'selected'; ?> value="0"> No </option>
                                                        <option <?php if (isset($document_info['signature_required']) && $document_info['signature_required'] == '1') echo 'selected'; ?> value="1"> Yes </option>
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

                                        <br>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header">
                                                        <strong>Assign Video:</strong>
                                                    </div>
                                                    <div class="hr-innerpadding">
                                                        <div class="universal-form-style-v2">
                                                            <ul>
                                                                <?php if (isset($document_info['video_source']) && !empty($document_info['video_source']) && $document_info['video_required'] == 1) { ?>
                                                                    <input type="hidden" id="old_doc_video_url" value="<?php echo $document_info['video_url']; ?>">
                                                                    <input type="hidden" id="old_doc_video_source" value="<?php echo $document_info['video_source']; ?>">
                                                                    <li class="form-col-100 autoheight" style="width:100%; height:500px !important;">
                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                            <?php $source = $document_info['video_source']; ?>
                                                                            <?php if ($source == 'youtube') { ?>
                                                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $document_info['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                                                            <?php } elseif ($source == 'vimeo') { ?>
                                                                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $document_info['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                                                            <?php } else { ?>
                                                                                <video controls style="width:100%; height:500px !important;">
                                                                                    <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $document_info['video_url']; ?>" type='video/mp4'>
                                                                                    <p class="vjs-no-js">
                                                                                        To view this video please enable JavaScript, and consider upgrading to a web browser that
                                                                                        <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                                                                    </p>
                                                                                </video>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </li>
                                                                <?php } ?>
                                                                <li class="form-col-100 autoheight edit_filter">
                                                                    <label for="video_source">Video Source</label>
                                                                    <?php
                                                                    $document_video_source = 'not_required';

                                                                    if (isset($document_info['video_required']) && $document_info['video_required'] == 1) {
                                                                        $document_video_source = $document_info['video_source'];
                                                                    }
                                                                    ?>
                                                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                        <?php echo NO_VIDEO; ?>
                                                                        <input class="video_source" type="radio" id="video_source_youtube" name="video_source" <?php echo $document_video_source == 'not_required' ? 'checked="checked"' : ''; ?> value="not_required">
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                        <?php echo YOUTUBE_VIDEO; ?>
                                                                        <input class="video_source" type="radio" id="video_source_youtube" name="video_source" value="youtube" <?php echo $document_video_source == 'youtube' ? 'checked="checked"' : ''; ?>>
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                        <?php echo VIMEO_VIDEO; ?>
                                                                        <input class="video_source" type="radio" id="video_source_vimeo" name="video_source" value="vimeo" <?php echo $document_video_source == 'vimeo' ? 'checked="checked"' : ''; ?>>
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                        <?php echo UPLOAD_VIDEO; ?>
                                                                        <input class="video_source" type="radio" id="video_source_upload" name="video_source" value="upload" <?php echo $document_video_source == 'upload' ? 'checked="checked"' : ''; ?>>
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </li>
                                                                <li class="form-col-100" id="yt_vm_video_container">
                                                                    <input type="text" name="yt_vm_video_url" value="" class="invoice-fields" id="yt_vm_video_url">
                                                                    <?php echo form_error('yt_vm_video_url'); ?>
                                                                </li>
                                                                <li class="form-col-100 autoheight edit_filter" id="up_video_container" style="display: none">
                                                                    <?php if (!empty($document_info['video_url']) && $document_info['video_source'] == 'upload') { ?>
                                                                        <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="<?php echo $document_info['video_url']; ?>">
                                                                    <?php } else { ?>
                                                                        <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="">
                                                                    <?php } ?>
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

                                        <?php if (!empty($document_groups)) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="hr-box">
                                                        <div class="hr-box-header">
                                                            <strong>Document Group Management:</strong>
                                                        </div>
                                                        <div class="hr-innerpadding">
                                                            <div class="universal-form-style-v2">
                                                                <?php foreach ($document_groups as $key => $document) { ?>
                                                                    <div class="col-xs-6">
                                                                        <label class="control control--checkbox font-normal">
                                                                            <?php echo $document['name']; ?>
                                                                            <input class="disable_doc_checkbox" name="document_group_assignment[]" type="checkbox" value="<?php echo $document['sid']; ?>" <?php echo in_array($document['sid'], $pre_assigned_groups) ? 'checked="checked"' : ''; ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if (!empty($employeesList)) { ?>
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
                                                                            <input class="disable_doc_checkbox" name="managersList[]" type="checkbox" value="<?php echo $emp['sid']; ?>" <?php echo in_array($emp['sid'], $pre_assigned_employees) ? 'checked="checked"' : ''; ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if (!empty($active_categories)) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label>Categories</label><br>
                                                    <div class="Category_chosen">
                                                        <select data-placeholder="Please Select" multiple="multiple" onchange="" name="categories[]" id="createcategories" class="categories">
                                                            <?php if (sizeof($active_categories) > 0) {
                                                                foreach ($active_categories as $category) { ?>
                                                                    <option <?= isset($assigned_categories) && in_array($category['sid'], $assigned_categories) ? "selected" : "" ?> value="<?php echo $category['sid']; ?>"><?= $category['name'] ?></option>
                                                            <?php
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                        <?php } ?>

                                        <?php if (isset($document_info['sid'])) { ?>
                                            <div class="row <?=$document_info["fillable_document_slug"] ? "hidden" :"";?>">
                                                <div class="col-xs-12">
                                                    <label class="control control--checkbox font-normal">
                                                        Convert To Pay Plan
                                                        <input class="disable_doc_checkbox" name="to_pay_plan" type="checkbox" value="yes" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <br />
                                        <?php } ?>

                                        <?php $this->load->view('hr_documents_management/partials/visibility'); ?>

                                        <?php $this->load->view('hr_documents_management/partials/test_approvers_section', ["appCheckboxIdx" => "jsHasApprovalFlowGOL", "containerIdx" => "jsApproverFlowContainerGOL", "addEmployeeIdx" => "jsAddDocumentApproversGOL", "intEmployeeBoxIdx" => "jsEmployeesadditionalBoxGOL", "extEmployeeBoxIdx" => "jsEmployeesadditionalExternalBoxGOL", "approverNoteIdx" => "jsApproversNoteGOL", 'mainId' => 'testApproversGOL']); ?>

                                        <div class="row">
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
                                                                            <input type="radio" name="assign_type" value="days" />
                                                                            <div class="control__indicator"></div>
                                                                        </label> &nbsp;
                                                                        <label class="control control--radio font-normal">
                                                                            Months
                                                                            <input type="radio" name="assign_type" value="months" />
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
                                                                        <input type="number" class="form-control" value="<?php echo isset($document_info['automatic_assign_in']) && !empty($document_info['automatic_assign_in']) ? $document_info['automatic_assign_in'] : 0; ?>" name="assign-in-days">
                                                                        <span class="input-group-addon">Days</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-6 js-type-months js-type">
                                                                <div class="universal-form-style-v2">
                                                                    <div class="input-group pto-time-off-margin-custom">
                                                                        <input type="number" class="form-control" value="<?php echo isset($document_info['automatic_assign_in']) && !empty($document_info['automatic_assign_in']) ? $document_info['automatic_assign_in'] : 0; ?>" name="assign-in-months">
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
                                        <?php $this->load->view('hr_documents_management/partials/send_dwmc'); ?>
                                        <br />
                                        <?php $this->load->view('hr_documents_management/partials/settings', [
                                            'is_confidential' =>  $document_info['is_confidential']
                                        ]); ?>

                                        <?php if (checkIfAppIsEnabled('documentlibrary')) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="hr-box">
                                                        <div class="hr-box-header">
                                                            <strong>Document Library?</strong>
                                                        </div>
                                                        <div class="hr-innerpadding">
                                                            <?php
                                                            if ($document_info['isdoctolibrary'] == 1) {
                                                                $isdoctolibrary1 = 'checked="true"';
                                                            } else {
                                                                $isdoctolibrary0 = 'checked="true"';
                                                            }
                                                            ?>
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
                                                                        <input class="disable_doc_checkbox" name="isdoctolibrary" type="radio" value="0" <?php echo $isdoctolibrary0; ?> />
                                                                        No &nbsp;
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                    <label class="control control--radio font-normal">
                                                                        <input class="disable_doc_checkbox" name="isdoctolibrary" type="radio" value="1" <?php echo $isdoctolibrary1; ?> />
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
                                                                    <?php
                                                                    if ($document_info['visible_to_document_center'] == 1) {
                                                                        $visibletodocumentcenter1 = 'checked="true"';
                                                                    } else {
                                                                        $visibletodocumentcenter0 = 'checked="true"';
                                                                    }
                                                                    ?>
                                                                    <div class="col-xs-12">
                                                                        <label class="control control--radio font-normal">
                                                                            <input class="disable_doc_checkbox" name="visibletodocumentcenter" type="radio" value="0" <?php echo $visibletodocumentcenter0; ?> />
                                                                            No &nbsp;
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                        <label class="control control--radio font-normal">
                                                                            <input class="disable_doc_checkbox" name="visibletodocumentcenter" type="radio" value="1" <?php echo $visibletodocumentcenter1; ?> />
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
                                                            <?php
                                                            if ($document_info['isdoctohandbook'] == 1) {
                                                                $isdoctohandbook1 = 'checked="true"';
                                                            } else {
                                                                $isdoctohandbook0 = 'checked="true"';
                                                            }
                                                            ?>
                                                             <div class="row">
                                                        <div class="col-xs-12">
                                                            <p class="text-danger"><strong><em><?= $this->lang->line('dm_handbook_text'); ?></em></strong></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <p><?= $this->lang->line('dm_handbook_label'); ?></p>
                                                        </div>
                                                    </div>
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <label class="control control--radio font-normal">
                                                                        <input class="disable_doc_checkbox" name="isdoctohandbook" type="radio" value="0" <?php echo $isdoctohandbook0; ?> />
                                                                        No &nbsp;
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                    <label class="control control--radio font-normal">
                                                                        <input class="disable_doc_checkbox" name="isdoctohandbook" type="radio" value="1" <?php echo $isdoctohandbook1; ?> />
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
                                            <div class="hr-box">
                                                <div class="hr-box-header">
                                                    <strong>Is this document required?</strong>
                                                </div>
                                                <div class="hr-innerpadding">
                                                     <div class="row">
                                                        <div class="col-sm-12">
                                                            <p class="text-danger">
                                                                <strong>
                                                                    <em>
                                                                        If marked yes, the applicant/employee is required to fill out this document once they have been assigned.
                                                                    </em>
                                                                </strong>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <?php
                                                        if ($document_info['is_required'] == 1) {
                                                            $isRequired1 = 'checked="true"';
                                                        } else {
                                                            $isRequired0 = 'checked="true"';
                                                        } ?>
                                                        <div class="col-xs-12">
                                                            <label class="control control--radio font-normal">
                                                                <input class="disable_doc_checkbox" name="isRequired" type="radio" value="0" <?php echo $isRequired0 ?> />
                                                                No &nbsp;
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <label class="control control--radio font-normal">
                                                                <input class="disable_doc_checkbox" name="isRequired" type="radio" value="1" <?php echo $isRequired1 ?> />
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
                                                <?php
                                                $url_segment = $this->uri->segment(2);
                                                $btn_text = '';

                                                if ($url_segment == 'edit_hr_document') {
                                                    $btn_text = 'Update';
                                                } else {
                                                    $btn_text = 'Save';
                                                }
                                                ?>
                                                <button type="submit" id="gen_boc_btn" class="btn btn-success" onclick="validate_form();"><?php echo $btn_text; ?></button>
                                                <!-- <button type="button" id="auth_sign_btn" class="btn btn-success" onclick="check_authorized_signature();"><?php //echo $btn_text; ?></button> -->
                                                <a href="<?php echo base_url('hr_documents_management'); ?>" class="btn black-btn">Cancel</a>
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
    </div>
</div>

<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            <?php echo VIDEO_LOADER_MESSAGE; ?>
        </div>
    </div>
</div>

<?php if (!empty($authorized_signature)) { ?>
    <div id="view_auth_signature_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Authorized Signature Detail</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Authorized Name</th>
                                <th scope="col">Authorized Date</th>
                                <th scope="col">Authorized Signature</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $authorized_signature['first_name'] . ' ' . $authorized_signature['last_name']; ?></td>
                                <td><?php echo my_date_format($authorized_signature['signature_timestamp']); ?></td>
                                <td><img src="<?php echo $authorized_signature['signature_base64']; ?>"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="replace_auth_sign">Replace Authorized Signature</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php $this->load->view('hr_documents_management/authorized_signature_popup'); ?>
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
        //
        $("#jsGenerateOfferLetter").documentApprovalFlow(approverSection);
        //
        var pre_selected = '<?php echo !empty($document_info['video_url']) ? $document_info['video_source'] : ''; ?>';

        $('input[name="assign-in-days"]').val(0);
        $('input[name="assign-in-months"]').val(0);
        $('.js-type').hide();
        $('input[value="days"]').prop('checked', false);
        $('input[value="months"]').prop('checked', false);
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

        if (pre_selected == 'youtube' || pre_selected == 'vimeo') {
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if (pre_selected == 'upload') {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').show();
        } else {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').hide();
        }
    });

    function check_authorized_signature() {
        var word = '{{authorized_signature}}';
        var textValue = $('#document_description').val();

        if (textValue.indexOf(word) != -1) {
            if ($('#auth_sign_sid').val() > 0) {
                return 1;
            } else if ($('#auth_sign_sid').val() == 0) {
                var company_sid = '<?php echo $company_sid; ?>';
                var document_sid = '<?php echo isset($document_info) ? $document_info['sid'] : ''; ?>';
                var myurl = "<?= base_url() ?>Hr_documents_management/check_active_auth_signature/" + document_sid + "/" + company_sid;
                var active_signature = '';

                $.ajax({
                    type: "GET",
                    url: myurl,
                    async: false,
                    success: function(status) {
                        active_signature = status;
                    }
                });

                if (active_signature == 1) {
                    return 1;
                } else {
                    $('#authorized_e_Signature_Modal').modal('show');
                }
            }
        } else {
            return 1;
        }
    }

    function validate_form() {
        $("#form_new_document").validate({
            ignore: [],
            rules: {
                document_title: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\-._ ]+$/
                },
                document_description: {
                    required: true
                }
            },
            messages: {
                document_title: {
                    required: 'Document name is required',
                    pattern: 'Letters, numbers,underscore and dashes only please'
                },
                document_description: {
                    required: 'Document Content is required',
                }
            },
            submitHandler: function(form) {
                var flag = 1;
                var video_source = $('input[name="video_source"]:checked').val();
             
                if (video_source != 'not_required') {

                    if (video_source == 'youtube') {
                        if ($('#yt_vm_video_url').val() != '') {
                            var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;

                            if (!$('#yt_vm_video_url').val().match(p)) {
                                alertify.error('Not a Valid Youtube URL');
                                flag = 0;
                                return false;
                            }
                        } else {
                            var url_check = '<?php echo $this->uri->segment(2); ?>';

                            if (url_check == 'edit_hr_document') {
                                var old_doc_video_source = $('#old_doc_video_source').val();
                                var old_doc_video_url = $('#old_doc_video_url').val();

                                if (old_doc_video_source == 'youtube' && old_doc_video_url != '') {
                                    flag = 1;
                                } else {
                                    flag = 0;
                                    alertify.error('Please provide a Valid Youtube URL');
                                }
                            } else {
                                flag = 0;
                                alertify.error('Please provide a Valid Youtube URL');
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
                                        flag = 0;
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
                                    flag = 1;
                                } else {
                                    flag = 0;
                                    alertify.error('Please provide a Valid Vimeo URL');
                                }
                            } else {
                                flag = 0;
                                alertify.error('Please provide a Valid Vimeo URL');
                            }
                        }
                    }

                    if (video_source == 'upload') {
                        var old_uploaded_video = $('#pre_upload_video_url').val();
                        if (old_uploaded_video != '') {
                            flag = 1;
                        } else {
                            var file = video_check('video_upload');
                            if (file == false) {
                                flag = 0;
                                return false;
                            } else {
                                flag = 1;
                            }
                        }
                    }
                }

                // flag = check_authorized_signature(); 

                if (flag == 1) {
                    $('#my_loader').show();
                    form.submit();
                }
            }
        });
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

    $('#view_auth_signature').on('click', function() {
        $('#view_auth_signature_modal').modal('show');
    });

    $('#replace_auth_sign').on('click', function() {
        $('#view_auth_signature_modal').modal('hide');
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you want to Replace Authorized Electronic Signature?',
            function() {
                var document_sid = '<?php echo isset($document_info) ? $document_info['sid'] : ''; ?>';
                var myurl = "<?= base_url() ?>Hr_documents_management/deactivate_auth_signature/" + document_sid;
                var active_signature = '';

                $.ajax({
                    type: "GET",
                    url: myurl,
                    async: false,
                    success: function(status) {
                        $('#authorized_e_Signature_Modal').modal('show');
                    }
                });
            },
            function() {
                alertify.error('Cancelled!');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'Cancel'
        });
    });
</script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $('body').ready(function() {
        var mylimit = parseInt($('#choiceLimit').html());
        multiselectbox();
        $("#Category").chosen({
            max_selected_options: mylimit
        }); // .chosen-select
        $("#Category").bind("liszt:maxselected", function() { // .chosen-select
        });
        $(".chosen-select").chosen().change(function() {});
        $('.categories').select2({
            closeOnSelect: false,
            allowHtml: true,
            allowClear: true,
            // tags: true 
        });
    });


    function multiselectbox() {
        var items_length = $('#Category :selected').length;
        var total_allowed = parseInt($('#choiceLimit').html());
        var total_left = total_allowed - items_length;

        if (total_left < 0) {
            total_left = 0;
        }

        $('#choicelimitavailable').html(total_left);
        var no_error = 0;
        var i = 1;

        if (items_length > total_allowed) {
            $('#Category option:selected').each(function() {
                if (i > total_allowed) {
                    $(this).removeAttr("selected");
                    no_error = 1;
                }
                i++;
            });
        }

        if (no_error) {
            alertify.alert("You can only select " + total_allowed + " values");
        }
    }
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