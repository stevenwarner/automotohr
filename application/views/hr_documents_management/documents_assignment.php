<?php
$canAccessDocument = hasDocumentsAssigned($session['employer_detail']);
$user_role = $session['employer_detail']['access_level'];
$bulk_btn_access = false;
if ($session['employer_detail']['access_level_plus'] == 1 || $user_role == "Hiring Manager" || $user_role == "Admin") {
    $bulk_btn_access = true;
}
// We need to give the hiring manager and Admin non plus the ability to have a Manually upload documents button so that they can still upload documents to Candidates and employees when needed
?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/mFileUploader/index.css" />
<style>
    #model_generated_offer_letter .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: auto !important;
    }

    #collapse_adh_1 .nav-justified .active .jsTabBTN {
        color: #fff !important;
    }
</style>

<?php
$action_btn_flag = false;
$document_all_permission = false;
if ($session['employer_detail']['access_level_plus'] == 1) {
    $action_btn_flag = true;
    $document_all_permission = true;
}
//
$GLOBALS['ad'] = $assigned_documents;
//
$modifyBTN = '<button
        class="btn btn-success btn-sm btn-block js-modify-assigned-document-btn"
        data-id="{{sid}}"
        data-type="{{type}}"
        title="Modify assigned document"
    >Modify</button>';
//
$assignIdObj = $confidential_sids;
//
?>

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
                                        <a class="dashboard-link-btn" href="<?php echo base_url('applicant_profile/' . $user_sid . '/' . $job_list_sid); ?>"><i aria-hidden="true" class="fa fa-chevron-left"></i>Applicant Profile</a>
                                    <?php } else { ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('employee_profile/' . $user_sid); ?>"><i aria-hidden="true" class="fa fa-chevron-left"></i>Employee Profile</a>
                                    <?php }
                                    echo $title; ?>
                                </span>
                            </div>

                            <?php $this->load->view('hr_documents_management/documents_assignment_tab_pages'); ?>

                            <?php if (isPayrollOrPlus()) { ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <strong>Employment Eligibility Verification Document</strong>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover table-stripped js-verification-table">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="column" class="col-lg-2">Document Name</th>
                                                                <th scope="column" class="col-lg-1 text-center">Type</th>
                                                                <th scope="column" class="col-lg-2 text-center">Assigned On</th>
                                                                <th scope="column" class="col-lg-6 text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="col-lg-2">
                                                                    W4 Fillable <?php echo !empty($w4_form) && !$w4_form['status'] && !isset($w4_form_uploaded) ? '<b>(revoked)</b>' : ''; ?>
                                                                    <?php if (!empty($w4_form) && $w4_form['status']) { ?>
                                                                        <?php if ($w4_form['user_consent']) { ?>
                                                                            <img class="img-responsive pull-left" style=" width: 22px; height: 22px; margin-right:5px;" title="Signed" data-toggle="tooltip" data-placement="top" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>" alt="" /></br>
                                                                            Completed on: <?= reset_datetime(array('datetime' => $w4_form['signature_timestamp'], '_this' => $this)); ?>
                                                                        <?php } else { ?>
                                                                            <img class="img-responsive pull-left" style=" width: 22px; height: 22px; margin-right:5px;" title="Unsigned" data-toggle="tooltip" data-placement="top" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>" alt="" />
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-1 text-center">
                                                                    <i aria-hidden="true" class="fa fa-2x fa-file-text"></i>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php if (!empty($w4_form) && $w4_form['status']) { ?>
                                                                        <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                                        <div class="text-center">
                                                                            <?= reset_datetime(array('datetime' => $w4_form['sent_date'], '_this' => $this)); ?>
                                                                            <?php if ($w4_form['last_assign_by'] != 0) {
                                                                                echo "<br>Assigned By: " . getUserNameBySID($w4_form['last_assign_by']);
                                                                            } ?>

                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <?php if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_assign_revoke_fillable')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_assign_revoke_fillable')) || $pp_flag == 1) { ?>
                                                                    <td class="col-lg-6 text-center">
                                                                        <?php if (!empty($w4_form)) { ?>
                                                                            <?php if ($w4_form['status']) { ?>
                                                                                <form id="form_remove_w4" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="remove_w4" />
                                                                                </form>
                                                                                <button onclick="func_remove_w4();" class="btn btn-danger">Revoke</button>
                                                                                <?php echo '<button class="btn btn-success jsManageW4" title="Manage W4">Manage W4</button>'; ?>
                                                                                <!-- <a class="btn <?php //echo $w4_SD > 0 ? 'btn-success' : 'blue-button'; 
                                                                                                    ?>" href="<? //= base_url() . "hr_documents_management/required_documents/employee/" . $user_sid . "/" . $w4_form['sid'] . "/w4_assigned" 
                                                                                                                ?>">Upload Supporting Docs</a> -->
                                                                                <?php if (!empty($w4_form['uploaded_file']) && $w4_form['uploaded_file'] != NULL) { ?>
                                                                                    <a class="btn btn-success" data-toggle="modal" href="javascript:;" data-document-type="w4" onclick="preview_eev_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $w4_form['uploaded_file']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $w4_form['uploaded_file']; ?>" data-file-name="<?php echo $w4_form['uploaded_file']; ?>" data-document-title="<?php echo $w4_form['uploaded_file']; ?>">
                                                                                        View hand signed W4
                                                                                    </a>
                                                                                <?php } else { ?>
                                                                                    <?php if ($w4_employer_section == 1) { ?>
                                                                                        <a class="btn <?php echo isW4EmployerSectionCompleted($w4_form) ? 'btn-success' : 'blue-button' ?> edit_employer_section" href="javascript:;" data-form-type="w4_edit_btn"><?php echo isW4EmployerSectionCompleted($w4_form) ? 'Employer Section - Completed' : 'Employer Section - Not Completed' ?></a>
                                                                                    <?php } ?>
                                                                                    <a class="btn btn-success" data-toggle="modal" data-target="#w4_modal" href="javascript:void(0);">View W4</a>
                                                                                <?php } ?>

                                                                            <?php } else { ?>
                                                                                <form id="form_assign_w4" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="assign_w4" />
                                                                                </form>
                                                                                <button onclick="func_assign_w4();" class="btn btn-warning">Re-Assign</button>
                                                                                <?php if ($user_type == 'employee') { ?>
                                                                                    <button class="btn btn-success" onclick="preview_eev_document_model(this);" data-document-type="w4" data-purpose="upload">Upload a hand signed W4</button>
                                                                                <?php } ?>
                                                                            <?php } ?>


                                                                        <?php } else { ?>
                                                                            <form id="form_assign_w4" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="assign_w4" />
                                                                            </form>
                                                                            <button onclick="func_assign_w4();" class="btn btn-success">Assign</button>
                                                                            <?php if ($user_type == 'employee') { ?>
                                                                                <button class="btn btn-success" onclick="preview_eev_document_model(this);" data-document-type="w4" data-purpose="upload">Upload a hand signed W4</button>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                        <?php if (isset($w4_form['sid'])) { ?>
                                                                            <!--  -->
                                                                            <a href="javascript:;" onclick="show_document_track('w4', <?= $w4_form['sid']; ?>);" class="btn btn-success" title="View action trail for W4 form" placement="top">W4 Trail</a>
                                                                            <!--  -->
                                                                            <a href="javascript:;" onclick="VerificationDocumentHistory('w4', <?= $w4_form['sid']; ?>);" class="btn btn-success" title="View history for W4 form" placement="top">W4
                                                                                History</a>
                                                                        <?php } ?>
                                                                        <!--  -->
                                                                    </td>
                                                                <?php } ?>
                                                            </tr>
                                                            <tr>
                                                                <td class="col-lg-2">
                                                                    W9 Fillable <?php echo !empty($w9_form) && !$w9_form['status'] && !isset($w9_form_uploaded) ? '<b>(revoked)</b>' : ''; ?>
                                                                    <?php
                                                                    if (!empty($w9_form) && $w9_form['status']) { ?>
                                                                        <?php if ($w9_form['user_consent']) { ?>
                                                                            <img class="img-responsive pull-left" style=" width: 22px; height: 22px; margin-right:5px;" title="Signed" data-toggle="tooltip" data-placement="top" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>" alt=""></br>
                                                                            Completed on: <?= reset_datetime(array('datetime' => $w9_form['signature_timestamp'], '_this' => $this)); ?>

                                                                        <?php } else { ?>
                                                                            <img class="img-responsive pull-left" style=" width: 22px; height: 22px; margin-right:5px;" title="Unsigned" data-toggle="tooltip" data-placement="top" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>" alt="">
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-1 text-center">
                                                                    <i aria-hidden="true" class="fa fa-2x fa-file-text"></i>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php if (!empty($w9_form) && $w9_form['status']) { ?>
                                                                        <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                                        <div class="text-center">
                                                                            <?= reset_datetime(array('datetime' => $w9_form['sent_date'], '_this' => $this)); ?>
                                                                            <?php
                                                                            if ($w9_form['last_assign_by'] != '' && $w9_form['last_assign_by'] != '0') {
                                                                                echo "<br>Assigned By: " . getUserNameBySID($w9_form['last_assign_by']);
                                                                            }
                                                                            ?>

                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <?php if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_assign_revoke_fillable')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_assign_revoke_fillable')) || $pp_flag == 1) { ?>
                                                                    <td class="col-lg-6 text-center">
                                                                        <?php if (!empty($w9_form)) { ?>
                                                                            <?php if ($w9_form['status']) { ?>
                                                                                <form id="form_remove_w9" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="remove_w9" />
                                                                                </form>
                                                                                <button onclick="func_remove_w9();" class="btn btn-danger">Revoke</button>
                                                                                <?php echo '<button class="btn btn-success jsManageW9" title="Manage W9">Manage W9</button>'; ?>
                                                                                <?php if ($user_type == 'employee') { ?>
                                                                                    <a class="btn <?php echo $w9_SD > 0 ? 'btn-success' : 'blue-button'; ?>" href="<?= base_url() . "hr_documents_management/required_documents/employee/" . $user_sid . "/" . $w9_form['sid'] . "/w9_assigned" ?>">Upload Supporting Docs</a>
                                                                                <?php } ?>
                                                                                <?php if (!empty($w9_form['uploaded_file']) && $w9_form['uploaded_file'] != NULL) { ?>
                                                                                    <a class="btn btn-success" onclick="preview_eev_document_model(this);" data-toggle="modal" href="javascript:;" data-document-type="w9" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $w9_form['uploaded_file']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $w9_form['uploaded_file']; ?>" data-file-name="<?php echo $w9_form['uploaded_file']; ?>" data-document-title="<?php echo $w9_form['uploaded_file']; ?>">
                                                                                        View hand signed W9
                                                                                    </a>
                                                                                <?php } else { ?>
                                                                                    <a class="btn btn-success" data-toggle="modal" data-target="#w9_modal" href="javascript:void(0);">View W9</a>
                                                                                <?php } ?>


                                                                            <?php } else { ?>
                                                                                <form id="form_assign_w9" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="assign_w9" />
                                                                                </form>
                                                                                <button onclick="func_assign_w9();" class="btn btn-warning">Re-Assign</button>
                                                                                <?php if ($user_type == 'employee') { ?>
                                                                                    <button class="btn btn-success" onclick="preview_eev_document_model(this);" data-document-type="w9" data-purpose="upload">Upload a hand signed W9</button>
                                                                                <?php } ?>
                                                                            <?php } ?>

                                                                        <?php } else { ?>
                                                                            <?php if (!empty($w9_form['uploaded_file']) && $user_type == 'employee') { ?>
                                                                                <button class="btn btn-success" onclick="preview_eev_document_model(this);" data-document-sid="<?= $w9_form['sid'] ?>" data-document-type="w9" data-file-name="<?= $w9_form['uploaded_file'] ?>" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $w9_form['uploaded_file']; ?>" data-download-url="<?= base_url() . "hr_documents_management/download_upload_document/" . $w9_form['uploaded_file']; ?>">View/Update</button>
                                                                                <a class="btn <?php echo $w9_SD > 0 ? 'btn-success' : 'blue-button'; ?>" href="<?= base_url() . "hr_documents_management/required_documents/employee/" . $user_sid . "/" . $w9_form['sid'] ?>">Upload Supporting Docs</a>

                                                                            <?php } else { ?>
                                                                                <form id="form_assign_w9" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="assign_w9" />
                                                                                </form>
                                                                                <button onclick="func_assign_w9();" class="btn btn-success">Assign</button>
                                                                                <?php if ($user_type == 'employee') { ?>
                                                                                    <button class="btn btn-success" onclick="preview_eev_document_model(this);" data-document-type="w9" data-purpose="upload">Upload a hand signed W9</button>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                        <?php if (isset($w9_form['sid'])) { ?>
                                                                            <!--  -->
                                                                            <a href="javascript:;" onclick="show_document_track('w9', <?= $w9_form['sid']; ?>);" class="btn btn-success" title="View action trail for W9 form" placement="top">W9 Trail</a>
                                                                            <!--  -->
                                                                            <a href="javascript:;" onclick="VerificationDocumentHistory('w9', <?= $w9_form['sid']; ?>);" class="btn btn-success" title="View history for W9 form" placement="top">W9
                                                                                History</a>
                                                                            <!--  -->
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } ?>
                                                            </tr>
                                                            <tr>
                                                                <td class="col-lg-2">
                                                                    I9 Fillable <?php echo sizeof($i9_form) > 0 && !$i9_form['status'] ? '<b>(revoked)</b>' : ''; ?>
                                                                    <?php if (sizeof($i9_form) > 0  && $i9_form['status']) { ?>
                                                                        <?php if ($i9_form['user_consent']) { ?>
                                                                            <img class="img-responsive pull-left" style=" width: 22px; height: 22px; margin-right:5px;" title="Signed" data-toggle="tooltip" data-placement="top" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"> <br>
                                                                            Completed on: <?= reset_datetime(array('datetime' => $i9_form['section1_today_date'], '_this' => $this)); ?>

                                                                        <?php } else { ?>
                                                                            <img class="img-responsive pull-left" style=" width: 22px; height: 22px; margin-right:5px;" title="Unsigned" data-toggle="tooltip" data-placement="top" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                                    <?php }
                                                                    } ?>
                                                                </td>
                                                                <td class="col-lg-1 text-center">
                                                                    <i aria-hidden="true" class="fa fa-2x fa-file-text"></i>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php if (sizeof($i9_form) > 0 && $i9_form['status']) { ?>
                                                                        <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                                        <div class="text-center">
                                                                            <?= reset_datetime(array('datetime' => $i9_form['sent_date'], '_this' => $this)); ?>
                                                                            <?php
                                                                            if ($i9_form['last_assign_by'] != '' && $i9_form['last_assign_by'] != 0) {
                                                                                echo "<br>Assigned By: " . getUserNameBySID($i9_form['last_assign_by']);
                                                                            }
                                                                            ?>

                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <?php if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_assign_revoke_fillable')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_assign_revoke_fillable')) || $pp_flag == 1) { ?>
                                                                    <td class="col-lg-6 text-center">
                                                                        <?php
                                                                        if (sizeof($i9_form) > 0) { ?>
                                                                            <?php if ($i9_form['status']) { ?>
                                                                                <form id="form_remove_i9" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="remove_i9" />
                                                                                </form>
                                                                                <button onclick="func_remove_i9();" class="btn btn-danger">Revoke</button>
                                                                                <button class="btn btn-success jsManageI9" title="Manage I9">Manage I9</button>
                                                                                <a href="<?= base_url("forms/i9/modify/$user_type/$user_sid"); ?>" class="btn btn-orange" title="Modify I9">Modify I9</a>
                                                                                <?php if ($user_type == 'employee') { ?>
                                                                                    <a class="btn <?php echo $i9_SD > 0 ? 'btn-success' : 'blue-button'; ?>" href="<?= base_url() . "hr_documents_management/required_documents/employee/" . $user_sid . "/" . $i9_form['sid'] . "/i9_assigned" ?>">Upload Supporting Docs</a>
                                                                                <?php } ?>
                                                                                <?php if ($i9_form['employer_flag']) { ?>
                                                                                    <?php if (!empty($i9_form['s3_filename']) && $i9_form['s3_filename'] != NULL) { ?>
                                                                                        <a class="btn btn-success" onclick="preview_eev_document_model(this);" data-toggle="modal" href="javascript:;" data-document-type="i9" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $i9_form['s3_filename']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $i9_form['s3_filename']; ?>" data-file-name="<?php echo $i9_form['s3_filename']; ?>" data-document-title="<?php echo $i9_form['s3_filename']; ?>">
                                                                                            View hand signed I9
                                                                                        </a>
                                                                                    <?php } else { ?>
                                                                                        <?php if ($i9_form['user_consent'] == 1 && (empty($i9_form['s3_filename']) || $i9_form['s3_filename'] == NULL)) {
                                                                                            if ($i9_form['employer_flag']) { ?>
                                                                                                <a class="btn btn-success <?= $i9_form['version'] == '2023' ? 'i9_edit_employer_section' : 'i9_edit_employer_section'; ?>" href="javascript:;" data-form-type="i9_edit_btn">Employer Section - Completed</a>
                                                                                            <?php } else { ?>
                                                                                                <a class="btn blue-button <?= $i9_form['version'] == '2023' ? 'i9_edit_employer_section' : 'i9_edit_employer_section'; ?>" href="javascript:;" data-form-type="i9_edit_btn">Employer Section - Not Completed</a>
                                                                                        <?php }
                                                                                        } ?>
                                                                                        <a class="btn btn-success" data-toggle="modal" data-target="#i9_modal" href="javascript:void(0);">View I9</a>
                                                                                    <?php }
                                                                                } else { ?>
                                                                                    <?php if ($i9_form['user_consent'] == 1 && (empty($i9_form['s3_filename']) || $i9_form['s3_filename'] == NULL)) {
                                                                                        if ($i9_form['employer_flag']) { ?>
                                                                                            <a class="btn btn-success <?= $i9_form['version'] == '2023' ? 'i9_edit_employer_section' : 'i9_edit_employer_section'; ?>" href="javascript:;" data-form-type="i9_edit_btn">Employer Section - Completed</a>
                                                                                        <?php } else { ?>
                                                                                            <a class="btn blue-button i9_edit_employer_section" href="javascript:;" data-form-type="i9_edit_btn">Employer Section - Not Completed</a>
                                                                                    <?php }
                                                                                    } ?>

                                                                                    <a class="btn btn-success" data-form-type="i9" href="<?php echo $user_type == 'applicant' ? base_url('form_i9/applicant') . '/' . $applicant_info['sid'] . "/" . $job_list_sid : base_url('form_i9/employee') . '/' . $employer['sid']; ?>">View I9 </a>


                                                                                <?php } ?>




                                                                            <?php } else { ?>

                                                                                <form id="form_assign_i9" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="assign_i9" />
                                                                                </form>
                                                                                <button onclick="func_assign_i9();" class="btn btn-warning">Re-Assign</button>
                                                                                <?php if ($user_type == 'employee') { ?>
                                                                                    <button class="btn btn-success" onclick="preview_eev_document_model(this);" data-document-type="i9" data-purpose="upload">Upload a hand signed I9</button>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <?php if (!empty($i9_form['s3_filename']) && $user_type == 'employee') { ?>
                                                                                <button class="btn btn-success" onclick="preview_eev_document_model(this);" data-document-sid="<?= $i9_form['sid'] ?>" data-document-type="i9" data-file-name="<?= $i9_form['s3_filename'] ?>" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $i9_form['s3_filename']; ?>" data-download-url="<?= base_url() . "hr_documents_management/download_upload_document/" . $i9_form['s3_filename']; ?>">View/Update</button>
                                                                                <a class="btn <?php echo $i9_SD > 0 ? 'btn-success' : 'blue-button'; ?>" href="<?= base_url() . "hr_documents_management/required_documents/employee/" . $user_sid . "/" . $i9_form['sid'] ?>">Upload Supporting Docs</a>

                                                                            <?php } else { ?>
                                                                                <button onclick="func_assign_i9();" class="btn btn-success">Assign</button>
                                                                                <?php if ($user_type == 'employee') { ?>
                                                                                    <button class="btn btn-success" onclick="preview_eev_document_model(this);" data-document-type="i9" data-purpose="upload">Upload a hand signed I9</button>
                                                                                <?php } ?>

                                                                                <form id="form_assign_i9" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="assign_i9" />
                                                                                </form>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                        <?php if (isset($i9_form['sid'])) { ?>
                                                                            <!--  -->
                                                                            <a href="javascript:;" onclick="show_document_track('i9', <?= $i9_form['sid']; ?>);" class="btn btn-success" title="View action trail for I9 form" placement="top">I9 Trail</a>
                                                                            <!--  -->
                                                                            <a href="javascript:;" onclick="VerificationDocumentHistory('i9', <?= $i9_form['sid']; ?>);" class="btn btn-success" title="View history for I9 form" placement="top">I9
                                                                                History</a>
                                                                            <!--  -->
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } ?>
                                                            </tr>
                                                            <?php if ($this->session->userdata('logged_in')['portal_detail'][$user_type == 'applicant' ? 'eeo_on_applicant_document_center' : 'eeo_on_employee_document_center']) { ?>
                                                                <tr>
                                                                    <td class="col-lg-2">
                                                                        EEOC FORM
                                                                        <img class="img-responsive pull-left" style=" width: 22px; height: 22px; margin-right:5px;" alt="" title="Signed" data-toggle="tooltip" data-placement="top" src="<?php echo site_url('assets/manage_admin/images/' . ($eeo_form_info['status'] == 1 && $eeo_form_info['is_expired'] == 1 ? 'on' : 'off') . '.gif'); ?>"></br>
                                                                        <?php if ($eeo_form_info && $eeo_form_info["status" == 1]) { ?>
                                                                            Completed on: <?= reset_datetime(array('datetime' => $eeo_form_info['last_completed_on'], '_this' => $this)); ?>
                                                                        <?php } ?>
                                                                        <?php if ($eeo_form_info["is_opt_out"] == 1) { ?>
                                                                            The user has Opt-out on <?= reset_datetime(array('datetime' => $eeo_form_info['last_completed_on'], '_this' => $this)); ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="col-lg-1 text-center">
                                                                        <i aria-hidden="true" class="fa fa-2x fa-file-text"></i>
                                                                    </td>
                                                                    <td class="col-lg-2 text-center">
                                                                        <?php if (empty($eeo_form_info)) { ?>
                                                                            <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                                                        <?php } else { ?>
                                                                            <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                                            <div class="text-center">
                                                                                <?php
                                                                                if (!empty($eeo_form_info['last_sent_at'])) {
                                                                                    //  echo DateTime::createfromformat('Y-m-d H:i:s', $eeo_form_info['last_sent_at'])->format('M d Y, D H:i:s');

                                                                                    echo convertTimeZone(
                                                                                        $eeo_form_info['last_sent_at'],
                                                                                        DB_DATE_WITH_TIME,
                                                                                        STORE_DEFAULT_TIMEZONE_ABBR,
                                                                                        getLoggedInPersonTimeZone(),
                                                                                        true,
                                                                                        DATE_WITH_TIME
                                                                                    );
                                                                                } else {
                                                                                    echo "N/A";
                                                                                }

                                                                                if (!empty($eeo_form_info['last_assigned_by']) && $eeo_form_info['last_assigned_by'] != '0') {
                                                                                    echo "<br>Assigned By: " . getUserNameBySID($eeo_form_info['last_assigned_by']);
                                                                                }

                                                                                ?>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="col-lg-6 text-center">

                                                                        <?php if (!empty($eeo_form_info)) { ?>
                                                                            <?php if ($eeo_form_info['status']) { ?>
                                                                                <form id="form_remove_EEOC" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="remove_EEOC" />
                                                                                </form>
                                                                                <button onclick="func_remove_EEOC();" class="btn btn-danger">Revoke</button>
                                                                                <?php if (!$eeo_form_info["is_opt_out"]) { ?>
                                                                                    <a class="btn btn-success" href="<?php echo base_url('EEOC/'.$user_type.'/' . $user_sid); ?>">View EEOC Form</a>
                                                                                    <?php if ($eeo_form_info['is_expired'] == 1) { ?>
                                                                                        <a class="btn btn-success" href="<?php echo base_url('hr_documents_management/print_eeoc_form/print/' . $user_sid . '/' . $user_type); ?>">Print</a>
                                                                                        <a class="btn btn-success" href="<?php echo base_url('hr_documents_management/print_eeoc_form/download/' . $user_sid . '/' . $user_type); ?>">Download</a>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                                <?php if ($eeo_form_info['is_expired'] != 1) { ?>
                                                                                    <a class="btn btn-success jsResendEEOC" ref="javascript:void(0);" title="Send reminder email to <?= ucwords($user_info['first_name'] . ' ' . $user_info['last_name']); ?>" placement="top">
                                                                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                                                        Send Email Notification
                                                                                    </a>
                                                                                    <button class="btn btn-orange jsEEOCOptOut" data-id="<?= $eeo_form_info["sid"]; ?>" title="You will be opt-out of the EEOC form.">
                                                                                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                                                                                        Opt-out
                                                                                    </button>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <form id="form_assign_EEOC" enctype="multipart/form-data" method="post">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="assign_EEOC" />
                                                                                </form>
                                                                                <button onclick="func_assign_EEOC();" class="btn btn-warning">Re-Assign</button>
                                                                            <?php } ?>
                                                                            <?php if (isset($eeo_form_info['sid'])) { ?>
                                                                                <!--  -->
                                                                                <button onclick="show_document_track('eeoc', <?= $eeo_form_info['sid']; ?>);" class="btn btn-success" title="View action trail for EEOC form" placement="top">EEOC Trail</button>
                                                                                <!--  -->
                                                                                <button onclick="VerificationDocumentHistory('eeoc', <?= $eeo_form_info['sid']; ?>);" class="btn btn-success" title="View history for EEOC form" placement="top">EEOC History</button>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <a class="btn btn-success jsResendEEOC" ref="javascript:void(0);" title="Assign EEOC form to <?= ucwords($user_info['first_name'] . ' ' . $user_info['last_name']); ?>" placement="top">Assign</a>
                                                                        <?php } ?>

                                                                        <?php if (!empty($eeo_form_info['last_completed_on'])) { ?>
                                                                            <p>Last completed on <strong><?php
                                                                                                            echo convertTimeZone(
                                                                                                                $eeo_form_info['last_completed_on'],
                                                                                                                DB_DATE_WITH_TIME,
                                                                                                                STORE_DEFAULT_TIMEZONE_ABBR,
                                                                                                                getLoggedInPersonTimeZone(),
                                                                                                                true,
                                                                                                                DATE_WITH_TIME
                                                                                                            );
                                                                                                            ?></strong></p>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($companyStateForms) { ?>
                                <!-- State forms -->
                                <?php $this->load->view('hr_documents_management/state_forms'); ?>
                            <?php } ?>
                            <!--  -->
                            <?php $this->load->view('hr_documents_management/general_document_assignment'); ?>

                            <?php if (checkIfAppIsEnabled('performancemanagement') && $user_type != 'applicant' && isPayrollOrPlus()) { ?>
                                <?php $this->load->view('employee_performance_evaluation/document_center'); ?>
                            <?php } ?>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            All Document
                                            <?php if ($action_btn_flag == true || $bulk_btn_access) { ?>
                                                <?php if ($action_btn_flag == true) { ?>
                                                    <button type="button" onclick="open_uploaded_model();" class="btn btn-success pull-right">Manual Document Upload</button>
                                                    <button type="button" class="btn btn-success pull-right js-offer-letter-btn" style="margin-right: 10px;">Add Offer Letter / Pay Plan</button>
                                                    <a href="<?= base_url('hr_documents_management/add_document/' . ($user_type) . '/' . ($EmployeeSid) . ''); ?>" class="btn btn-success pull-right" style="margin-right: 10px;">Add Document</a>
                                                <?php } ?>
                                                <a href="<?= base_url('hr_documents_management/add_history_documents/' . ($user_type) . '/' . ($EmployeeSid) . ''); ?>" class="btn btn-success pull-right" style="margin-right: 10px;">Assign Bulk Documents</a>
                                            <?php } ?>
                                        </div>
                                        <div class="hr-innerpadding">

                                            <input type="text" class="form-control" id="js-search-bar" placeholder="Search document; e.g. Handbook" />
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="hr-document-list">
                                                <!-- Active Group Document Start -->
                                                <?php if (!empty($active_groups)) { ?>
                                                    <?php foreach ($active_groups as $active_group) {
                                                        if ($active_group['documents_count'] == 0) {
                                                            continue;
                                                        } ?>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="panel panel-default hr-documents-tab-content js-search-header">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_ag<?php echo $active_group['sid']; ?>">
                                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                                <?php echo $active_group['name']; ?>
                                                                                <div class="btn btn-xs btn-success">Active Group</div>
                                                                                <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . $active_group['documents_count']; ?></b></div>
                                                                            </a>

                                                                            <?php if ($action_btn_flag == true) { ?>
                                                                                <?php if (in_array($active_group['sid'], $assigned_groups)) { ?>
                                                                                    <!-- <button
                                                                                        class="btn btn-success btn-xs pull-right">
                                                                                        Document Group Assigned
                                                                                    </button> -->
                                                                                    <?php $group_status = get_user_assign_group_status($active_group['sid'], $user_type, $user_sid); ?>

                                                                                    <?php if ($group_status == 1) { ?>
                                                                                        <button style="margin-left: 5px;" class="btn btn-danger btn-xs pull-right" id="btn_group_<?php echo $active_group['sid']; ?>" onclick="func_revoke_document_group('<?php echo $active_group['sid']; ?>','<?php echo $user_type; ?>','<?php echo $user_sid; ?>', '<?php echo $active_group['name'] ?>')">
                                                                                            Revoke Document Group
                                                                                        </button>
                                                                                    <?php }  ?>
                                                                                    <button class="btn btn-warning btn-xs pull-right" id="btn_group_<?php echo $active_group['sid']; ?>" onclick="func_reassign_document_group('<?php echo $active_group['sid']; ?>','<?php echo $user_type; ?>','<?php echo $user_sid; ?>', '<?php echo $active_group['name'] ?>')">
                                                                                        Reassign Document Group
                                                                                    </button>
                                                                                    <?php //} 
                                                                                    ?>
                                                                                <?php } else { ?>
                                                                                    <button class="btn btn-primary btn-xs pull-right" id="btn_group_<?php echo $active_group['sid']; ?>" onclick="func_assign_document_group('<?php echo $active_group['sid']; ?>','<?php echo $user_type; ?>','<?php echo $user_sid; ?>', '<?php echo $active_group['name'] ?>')">
                                                                                        Assign Document Group
                                                                                    </button>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </h4>
                                                                    </div>

                                                                    <div id="collapse_ag<?php echo $active_group['sid']; ?>" class="panel-collapse collapse">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-plane">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th scope="column" class="col-xs-8">Document Name</th>
                                                                                        <th scope="column" class="col-xs-2">Document Type</th>
                                                                                        <th scope="column" class="col-xs-2 text-center" colspan="2">Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <!-- List verification and general documents with no actions -->
                                                                                    <?php if ($active_group['other_documents']) : ?>
                                                                                        <?php foreach ($active_group['other_documents'] as $otherDocument) : ?>
                                                                                            <tr class="js-search-row">
                                                                                                <td class="col-xs-8"><?= $otherDocument; ?></td>
                                                                                                <td>-</td>
                                                                                                <td class="text-center">-</td>
                                                                                            </tr>
                                                                                        <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                    <?php if ($active_group['documents_count'] > 0) { ?>
                                                                                        <?php foreach ($active_group['documents'] as $document) { ?>
                                                                                            <tr class="js-search-row">
                                                                                                <td class="col-xs-8"><?php echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : ''); ?></td>
                                                                                                <td class="col-xs-2">
                                                                                                    <?php echo ucwords($document['document_type']); ?>
                                                                                                </td>
                                                                                                <?php if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_assign_revoke_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_assign_revoke_doc')) || $canAccessDocument) { ?>
                                                                                                    <?php if ($action_btn_flag == true || $session['employer_detail']['pay_plan_flag'] == 0) { ?>
                                                                                                        <td>
                                                                                                            <?php if ($document_all_permission) { ?>
                                                                                                                <?php if (in_array($document['sid'], $assigned_sids) || in_array($document['sid'], $revoked_sids) || in_array($document['sid'], $completed_sids) || in_array($document['sid'], $signed_document_sids)) { ?>

                                                                                                                    <?php if (in_array($document['sid'], $assigned_sids)) { ?>
                                                                                                                        <!-- revoke here  -->
                                                                                                                        <form id="form_remove_document_<?php echo $document['document_type']; ?>_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                                                            <input type="hidden" id="perform_action" name="perform_action" value="remove_document" />
                                                                                                                            <input type="hidden" id="document_type" name="document_type" value="<?php echo $document['document_type']; ?>" />
                                                                                                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                                                        </form>
                                                                                                                        <?php
                                                                                                                        if (array_key_exists($document['sid'], $assignIdObj)) {
                                                                                                                            echo str_replace(['{{sid}}', '{{type}}'], [$document['sid'], 'notCompletedDocuments'], $modifyBTN);
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                        <button onclick="func_remove_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>);" class="btn btn-danger btn-block btn-sm">Revoke</button>
                                                                                                                    <?php } else if (in_array($document['sid'], $signed_document_sids)) { ?>
                                                                                                                        <button class="btn blue-button btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Completed and Reassign</button>
                                                                                                                    <?php } else { // re-assign here 
                                                                                                                    ?>
                                                                                                                        <button class="btn btn-warning btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Modify and Reassign</button>
                                                                                                                    <?php } ?>
                                                                                                                <?php } else { ?>
                                                                                                                    <!-- assign here -->
                                                                                                                    <button class="btn btn-success btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Modify and Assign</button>
                                                                                                                <?php } ?>
                                                                                                            <?php } ?>
                                                                                                        </td>
                                                                                                    <?php } ?>
                                                                                                <?php } ?>
                                                                                                <td>
                                                                                                    <?php if ($document['document_type'] == 'uploaded') {
                                                                                                        $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                                        $document_file = pathinfo($document_filename);
                                                                                                        $name = explode(".", $document_filename);
                                                                                                        $url_segment_original = $name[0]; ?>
                                                                                                        <button class="btn btn-success btn-sm btn-block" onclick="view_original_uploaded_doc(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-document-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">View Doc</button>
                                                                                                    <?php } else { ?>
                                                                                                        <button onclick="view_original_generated_document(<?php echo $document['sid']; ?>, 'generated', 'original');" class="btn btn-success btn-sm btn-block">View Doc</button>
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                                <?php if ($document['document_type'] == 'uploaded') { ?>
                                                                                                    <td class="col-lg-1">
                                                                                                        <?php
                                                                                                        $document_filename = $document['uploaded_document_s3_name'];
                                                                                                        $document_file = pathinfo($document_filename);
                                                                                                        $document_extension = $document_file['extension'];
                                                                                                        $name = explode(".", $document_filename);
                                                                                                        $url_segment_original = $name[0];
                                                                                                        ?>
                                                                                                        <?php if ($document_extension == 'pdf') { ?>
                                                                                                            <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_original . '.pdf' ?>" class="btn btn-success btn-sm btn-block">Print</a>

                                                                                                        <?php } else if ($document_extension == 'docx') { ?>
                                                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edocx&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                        <?php } else if ($document_extension == 'doc') { ?>
                                                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edoc&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                        <?php } else if ($document_extension == 'xls') { ?>
                                                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exls' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                        <?php } else if ($document_extension == 'xlsx') { ?>
                                                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exlsx' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                        <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                                                                            <a target="_blank" href="<?php echo base_url('hr_documents_management/print_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/original'); ?>" class="btn btn-success btn-sm btn-block">
                                                                                                                Print
                                                                                                            </a>
                                                                                                        <?php } else { ?>
                                                                                                            <a class="btn btn-success btn-sm btn-block" href="javascript:void(0);" onclick="fLaunchModal(this);" data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-file-name="<?php echo $document_filename; ?>" data-document-title="<?php echo $document_filename; ?>" data-preview-ext="<?php echo $document_extension ?>">Print</a>
                                                                                                        <?php } ?>
                                                                                                    </td>
                                                                                                    <td class="col-lg-1">
                                                                                                        <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['uploaded_document_s3_name']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                    </td>
                                                                                                <?php } else { ?>
                                                                                                    <td class="col-lg-1">
                                                                                                        <a href="<?= base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document['sid']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                    </td>

                                                                                                    <td class="col-lg-1">
                                                                                                        <a href="<?= base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document['sid'] . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                    </td>

                                                                                                <?php } ?>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                    <?php } else { ?>
                                                                                        <tr>
                                                                                            <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                                <!-- Active Group Document End -->

                                                <!-- In-Active Group Document Start -->
                                                <?php if (!empty($in_active_groups)) { ?>
                                                    <?php foreach ($in_active_groups as $active_group) {
                                                        if ($active_group['documents_count'] == 0) {
                                                            continue;
                                                        } ?>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="panel panel-default hr-documents-tab-content js-search-header">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_ig<?php echo $active_group['sid']; ?>">
                                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                                <?php echo $active_group['name']; ?>
                                                                                <div class="btn btn-xs btn-danger">Inactive Group</div>
                                                                                <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . $active_group['documents_count']; ?></b></div>
                                                                            </a>


                                                                            <?php if ($action_btn_flag == true) { ?>
                                                                                <?php if (in_array($active_group['sid'], $assigned_groups)) { ?>
                                                                                    <button class="btn btn-success btn-xs pull-right">
                                                                                        Document Group Assigned
                                                                                    </button>
                                                                                <?php } else { ?>
                                                                                    <button class="btn btn-primary btn-xs pull-right" id="btn_group_<?php echo $active_group['sid']; ?>" onclick="func_assign_document_group('<?php echo $active_group['sid']; ?>','<?php echo $user_type; ?>','<?php echo $user_sid; ?>', '<?php echo $active_group['name'] ?>')">
                                                                                        Assign Document Group
                                                                                    </button>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </h4>
                                                                    </div>
                                                                    <div id="collapse_ig<?php echo $active_group['sid']; ?>" class="panel-collapse collapse">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-plane">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th scope="column" class="col-xs-8">Document Name</th>
                                                                                        <th scope="column" class="col-xs-2">Document Type</th>
                                                                                        <th scope="column" class="col-xs-2 text-center" colspan="2">Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php if ($active_group['documents_count'] > 0) { ?>
                                                                                        <?php foreach ($active_group['documents'] as $document) { ?>
                                                                                            <tr class="js-search-row">
                                                                                                <td class="col-xs-8"><?php echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : ''); ?></td>
                                                                                                <td class="col-xs-2">
                                                                                                    <?php echo ucwords($document['document_type']); ?>
                                                                                                </td>
                                                                                                <?php if ($action_btn_flag == true) { ?>
                                                                                                    <?php if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_assign_revoke_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_assign_revoke_doc')) || $canAccessDocument) { ?>
                                                                                                        <td>
                                                                                                            <?php if (in_array($document['sid'], $assigned_sids) || in_array($document['sid'], $revoked_sids)) { ?>
                                                                                                                <?php if (in_array($document['sid'], $assigned_sids)) {  // revoke here 
                                                                                                                ?>
                                                                                                                    <form id="form_remove_document_<?php echo $document['document_type']; ?>_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="remove_document" />
                                                                                                                        <input type="hidden" id="document_type" name="document_type" value="<?php echo $document['document_type']; ?>" />
                                                                                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                                                    </form>
                                                                                                                    <button onclick="func_remove_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>);" class="btn btn-danger btn-block btn-sm">Revoke</button>
                                                                                                                <?php } else { // re-assign here 
                                                                                                                ?>
                                                                                                                    <button class="btn btn-warning btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Modify and Reassign</button>
                                                                                                                <?php } ?>
                                                                                                            <?php } else { // assign here 
                                                                                                            ?>
                                                                                                                <button class="btn btn-success btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Modify and Assign</button>
                                                                                                            <?php } ?>
                                                                                                        </td>
                                                                                                    <?php } ?>
                                                                                                <?php } ?>
                                                                                                <td>
                                                                                                    <?php if ($document['document_type'] == 'uploaded') {
                                                                                                        $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                                        $document_file = pathinfo($document_filename);
                                                                                                        $name = explode(".", $document_filename);
                                                                                                        $url_segment_original = $name[0]; ?>
                                                                                                        <button class="btn btn-success btn-sm btn-block" onclick="view_original_uploaded_doc(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-document-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">View Doc</button>
                                                                                                    <?php } else { ?>
                                                                                                        <button onclick="view_original_generated_document(<?php echo $document['sid']; ?>, 'generated', 'original');" class="btn btn-success btn-sm btn-block">View Doc</button>
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                                <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                                    <td colspan="2"></td>
                                                                                                <?php } else  if ($document['document_type'] == 'uploaded') { ?>
                                                                                                    <td class="col-lg-1">
                                                                                                        <?php
                                                                                                        $document_filename = $document['uploaded_document_s3_name'];
                                                                                                        $document_file = pathinfo($document_filename);
                                                                                                        $document_extension = $document_file['extension'];
                                                                                                        $name = explode(".", $document_filename);
                                                                                                        $url_segment_original = $name[0];
                                                                                                        ?>
                                                                                                        <?php if ($document_extension == 'pdf') { ?>
                                                                                                            <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_original . '.pdf' ?>" class="btn btn-success btn-sm btn-block">Print</a>

                                                                                                        <?php } else if ($document_extension == 'docx') { ?>
                                                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edocx&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                        <?php } else if ($document_extension == 'doc') { ?>
                                                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edoc&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                        <?php } else if ($document_extension == 'xls') { ?>
                                                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exls' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                        <?php } else if ($document_extension == 'xlsx') { ?>
                                                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exlsx' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                        <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                                                                            <a target="_blank" href="<?php echo base_url('hr_documents_management/print_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/original'); ?>" class="btn btn-success btn-sm btn-block">
                                                                                                                Print
                                                                                                            </a>
                                                                                                        <?php } else { ?>
                                                                                                            <a class="btn btn-success btn-sm btn-block" href="javascript:void(0);" onclick="fLaunchModal(this);" data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-file-name="<?php echo $document_filename; ?>" data-document-title="<?php echo $document_filename; ?>" data-preview-ext="<?php echo $document_extension ?>">Print</a>
                                                                                                        <?php } ?>
                                                                                                    </td>
                                                                                                    <td class="col-lg-1">
                                                                                                        <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['uploaded_document_s3_name']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                    </td>
                                                                                                <?php } else { ?>
                                                                                                    <td class="col-lg-1">
                                                                                                        <a href="<?= base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document['sid']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                    </td>

                                                                                                    <td class="col-lg-1">
                                                                                                        <a href="<?= base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document['sid'] . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                    </td>

                                                                                                <?php } ?>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                    <?php } else { ?>
                                                                                        <tr>
                                                                                            <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                                <!-- In-Active Group Document End -->

                                                <!-- Uncategorized Document Start -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="panel panel-default hr-documents-tab-content">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_uncategorized_documents">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                        Uncategorized Documents
                                                                        <div class="btn btn-xs btn-info">Uncategorized</div>
                                                                        <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . count($uncategorized_documents); ?></b></div>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapse_uncategorized_documents" class="panel-collapse collapse">
                                                                <div class="table-responsive">
                                                                    <table class="table table-plane">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="column" class="col-xs-8">Document Name</th>
                                                                                <th scope="column" class="col-xs-2">Document Type</th>
                                                                                <th scope="column" class="col-xs-2 text-center" colspan="2">Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php if (count($uncategorized_documents) > 0) { ?>
                                                                                <?php foreach ($uncategorized_documents as $document) { ?>
                                                                                    <tr>
                                                                                        <td class="col-xs-6"><?php echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : ''); ?></td>
                                                                                        <td class="col-xs-2">
                                                                                            <?php echo ucwords($document['document_type']); ?>
                                                                                        </td>
                                                                                        <?php if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_assign_revoke_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_assign_revoke_doc')) || $canAccessDocument) { ?>
                                                                                            <?php if ($action_btn_flag == true) { ?>
                                                                                                <td>
                                                                                                    <?php if (in_array($document['sid'], $assigned_sids) || in_array($document['sid'], $revoked_sids) || in_array($document['sid'], $completed_sids) || in_array($document['sid'], $signed_document_sids)) { ?>
                                                                                                        <?php if (in_array($document['sid'], $assigned_sids)) { ?>
                                                                                                            <!-- assign doc revoke here -->
                                                                                                            <form id="form_remove_document_<?php echo $document['document_type']; ?>_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                                                <input type="hidden" id="perform_action" name="perform_action" value="remove_document" />
                                                                                                                <input type="hidden" id="document_type" name="document_type" value="<?php echo $document['document_type']; ?>" />
                                                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                                            </form>

                                                                                                            <?php
                                                                                                            if (array_key_exists($document['sid'], $assignIdObj)) {
                                                                                                                echo str_replace(['{{sid}}', '{{type}}'], [$document['sid'], 'notCompletedDocuments'], $modifyBTN);
                                                                                                            }
                                                                                                            ?>

                                                                                                            <button onclick="func_remove_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>);" class="btn btn-danger btn-block btn-sm">Revoke</button>
                                                                                                        <?php } else if (in_array($document['sid'], $signed_document_sids)) { ?>
                                                                                                            <button class="btn blue-button btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Completed and Reassign</button>
                                                                                                        <?php } else { ?>
                                                                                                            <!-- revoke doc re-assign here -->
                                                                                                            <button class="btn btn-warning btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Modify and Reassign</button>
                                                                                                        <?php } ?>
                                                                                                    <?php } else { // assign here 
                                                                                                    ?>
                                                                                                        <button class="btn btn-success btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Modify and Assign</button>
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                            <?php } ?>
                                                                                        <?php } ?>
                                                                                        <td>
                                                                                            <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                                <button data-id="<?= $document['sid']; ?>" data-from="company" class="btn btn-success btn-sm btn-block js-hybrid-preview">View Doc</button>
                                                                                            <?php } else if ($document['document_type'] == 'uploaded') { ?>
                                                                                                <?php
                                                                                                $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                                $document_file = pathinfo($document_filename);
                                                                                                $name = explode(".", $document_filename);
                                                                                                $url_segment_original = $name[0];
                                                                                                ?>
                                                                                                <button class="btn btn-success btn-sm btn-block" onclick="view_original_uploaded_doc(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-document-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">View Doc</button>
                                                                                            <?php } else { ?>
                                                                                                <button onclick="view_original_generated_document(<?php echo $document['sid']; ?>, 'generated', 'original');" class="btn btn-success btn-sm btn-block">View Doc</button>
                                                                                            <?php } ?>
                                                                                        </td>

                                                                                        <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                            <td colspan="2"></td>
                                                                                        <?php } else if ($document['document_type'] == 'uploaded') { ?>
                                                                                            <td class="col-lg-1">
                                                                                                <?php
                                                                                                $document_filename = $document['uploaded_document_s3_name'];
                                                                                                $document_file = pathinfo($document_filename);
                                                                                                $document_extension = $document_file['extension'];
                                                                                                $name = explode(".", $document_filename);
                                                                                                $url_segment_original = $name[0];
                                                                                                ?>
                                                                                                <?php if ($document_extension == 'pdf') { ?>
                                                                                                    <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_original . '.pdf' ?>" class="btn btn-success btn-sm btn-block">Print</a>

                                                                                                <?php } else if ($document_extension == 'docx') { ?>
                                                                                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edocx&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                <?php } else if ($document_extension == 'doc') { ?>
                                                                                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edoc&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                <?php } else if ($document_extension == 'xls') { ?>
                                                                                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exls' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                <?php } else if ($document_extension == 'xlsx') { ?>
                                                                                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exlsx' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                                                                    <a target="_blank" href="<?php echo base_url('hr_documents_management/print_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/original'); ?>" class="btn btn-success btn-sm btn-block">
                                                                                                        Print
                                                                                                    </a>
                                                                                                <?php } else { ?>
                                                                                                    <a class="btn btn-success btn-sm btn-block" href="javascript:void(0);" onclick="fLaunchModal(this);" data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-file-name="<?php echo $document_filename; ?>" data-document-title="<?php echo $document_filename; ?>" data-preview-ext="<?php echo $document_extension ?>">Print</a>
                                                                                                <?php } ?>
                                                                                            </td>
                                                                                            <td class="col-lg-1">
                                                                                                <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['uploaded_document_s3_name']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                            </td>
                                                                                        <?php } else { ?>
                                                                                            <td class="col-lg-1">
                                                                                                <a href="<?= base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document['sid']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                            </td>

                                                                                            <td class="col-lg-1">
                                                                                                <a href="<?= base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document['sid'] . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                            </td>

                                                                                        <?php } ?>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <tr>
                                                                                    <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Uncategorized Document End -->

                                                <!-- Admin or Access Level Plus Document Start -->
                                                <?php if ($session['employer_detail']['access_level_plus']) { ?>
                                                    <!-- Access Level Plus Document Start -->
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="panel panel-default hr-documents-tab-content js-search-header">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_admin_documents">
                                                                            <span class="glyphicon glyphicon-plus"></span>
                                                                            Admin Documents
                                                                            <div class="btn btn-xs btn-info">Access Level Plus</div>
                                                                            <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . count($access_level_manual_doc); ?></b></div>
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse_admin_documents" class="panel-collapse collapse">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-plane">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th scope="column" class="col-xs-8">Document Name</th>
                                                                                    <th scope="column" class="col-xs-2">Document Type</th>
                                                                                    <th scope="column" class="col-xs-2 text-center" colspan="2">Actions</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php if (count($access_level_manual_doc) > 0) { ?>
                                                                                    <?php foreach ($access_level_manual_doc as $document) { ?>
                                                                                        <?php if ($document['document_type'] == 'confidential') { ?>
                                                                                            <?php
                                                                                            $confidential_document_url = $document['document_s3_name'];
                                                                                            $confidential_document_info = get_required_url($confidential_document_url);
                                                                                            $confidential_print_url = $confidential_document_info['print_url'];
                                                                                            $confidential_download_url = $confidential_document_info['download_url'];
                                                                                            ?>
                                                                                            <tr class="js-search-row">
                                                                                                <td class="col-xs-6"><?php echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : ''); ?></td>
                                                                                                <td class="col-xs-2">
                                                                                                    <?php echo ucwords($document['document_type']); ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>">View Doc</button>

                                                                                                </td>
                                                                                                <td class="col-lg-1">
                                                                                                    <a target="_blank" href="<?php echo $confidential_print_url; ?>" class="btn btn-success btn-sm btn-block">
                                                                                                        Print
                                                                                                    </a>
                                                                                                </td>
                                                                                                <td class="col-lg-1">
                                                                                                    <a href="<?php echo $confidential_download_url; ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                <?php } else { ?>
                                                                                    <tr>
                                                                                        <td colspan="7" class="col-lg-12 text-center"><b>No Admin Documents Found!</b></td>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Access Level Plus Document End -->
                                                <?php } ?>
                                                <!-- Admin or Access Level Plus Document End -->

                                                <!--Archived All Documents Start -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="panel panel-default hr-documents-tab-content js-search-header">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#archived_assign_document">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                        Archived Documents
                                                                        <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . count($archived_assign_document); ?></b></div>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="archived_assign_document" class="panel-collapse collapse">
                                                                <div class="table-responsive full-width">
                                                                    <table class="table table-plane">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="column" class="col-lg-5">Document Name</th>
                                                                                <th scope="column" class="col-lg-2">Document Type</th>
                                                                                <th scope="column" class="col-lg-5 text-center" colspan="5">Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php if (count($archived_assign_document) > 0) { ?>
                                                                                <?php foreach ($archived_assign_document as $document) {
                                                                                    $GLOBALS['ad'][] = $document; ?>
                                                                                    <?php
                                                                                    $archive_print_url = '';
                                                                                    $archive_download_url = '';
                                                                                    if ($document['document_type'] == 'uploaded' || $document['document_type'] == 'confidential') {
                                                                                        $archive_document_url = $document['document_s3_name'];

                                                                                        $archive_document_info = get_required_url($archive_document_url);
                                                                                        $archive_print_url = $archive_document_info['print_url'];
                                                                                        $archive_download_url = $archive_document_info['download_url'];
                                                                                    } else {
                                                                                        $archive_print_url = base_url('hr_documents_management/perform_action_on_document_content' . '/' . $document['sid'] . '/assigned/assigned_document/print');
                                                                                        $archive_download_url = base_url('hr_documents_management/perform_action_on_document_content' . '/' . $document['sid'] . '/assigned/assigned_document/download');
                                                                                    }
                                                                                    ?>
                                                                                    <tr class="js-search-row">
                                                                                        <td class="col-lg-5">
                                                                                            <?php
                                                                                            echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                                            echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';

                                                                                            if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class="col-lg-2">
                                                                                            <?php echo ucfirst($document['document_type']) . '&nbsp;'; ?>
                                                                                        </td>
                                                                                        <td class="col-lg-1">
                                                                                            <?php if ($action_btn_flag == true && $document['document_sid'] == 0) { ?>
                                                                                                <button class="btn btn-success btn-sm btn-block" onclick="no_action_req_edit_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>" is-payroll-visible="<?php echo $document['visible_to_payroll'] == 1 ? true : false; ?>" data-categories='<?php echo isset($archived_no_action_document_categories[$document['sid']]) ? json_encode($archived_no_action_document_categories[$document['sid']]) : "[]" ?>' data-update-accessible="<?php echo $document['document_type'] == "confidential" ? true : false; ?>">Edit Document</button>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td class="col-lg-1">
                                                                                            <?php if ($action_btn_flag == true && ($document['user_archived'] == 1 || $document['document_sid'] == 0)) { ?>
                                                                                                <button class="btn btn-default btn-sm btn-block" onclick="func_unarchive_uploaded_document(<?php echo $document['sid']; ?>)">Activate</button>
                                                                                                <form id="form_activate_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="activate_uploaded_document" />
                                                                                                    <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                                    <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                                </form>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td class="col-lg-1">
                                                                                            <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                                <button data-id="<?= $document['sid']; ?>" data-from="company" data-document="assigned" data-type="document" class="btn btn-success btn-sm btn-block js-hybrid-preview">View Doc</button>
                                                                                            <?php } else if ($document['document_type'] == 'uploaded' || $document['document_type'] == 'confidential') { ?>
                                                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>">View Doc</button>
                                                                                            <?php } else { ?>
                                                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document">View Doc</button>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                            <td colspan="2"></td>
                                                                                        <?php } else { ?>
                                                                                            <td class="col-lg-1">
                                                                                                <a href="<?php echo $archive_print_url; ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                            </td>
                                                                                            <td class="col-lg-1">
                                                                                                <a href="<?php echo $archive_download_url; ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                            </td>
                                                                                        <?php } ?>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <tr>
                                                                                    <td colspan="7" class="col-lg-12 text-center"><b>No Document(s) Found!</b></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Archived All Documents End -->

                                                <!--User Assigned Manual Documents Start -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="panel panel-default hr-documents-tab-content js-search-header">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#user_assigned_manual_documents">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                        Manual Documents
                                                                        <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . count($user_assigned_manual_documents); ?></b></div>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="user_assigned_manual_documents" class="panel-collapse collapse">
                                                                <div class="table-responsive full-width">
                                                                    <table class="table table-plane">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="column" class="col-lg-7">Document Name</th>
                                                                                <th scope="column" class="col-lg-5 text-center" colspan="5">Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php if (count($user_assigned_manual_documents) > 0) { ?>
                                                                                <?php foreach ($user_assigned_manual_documents as $document) { ?>
                                                                                    <?php
                                                                                    $manual_document_url = $document['document_s3_name'];
                                                                                    $manual_document_info = get_required_url($manual_document_url);
                                                                                    $manual_print_url = $manual_document_info['print_url'];
                                                                                    $manual_download_url = $manual_document_info['download_url'];
                                                                                    ?>
                                                                                    <tr class="js-search-row">
                                                                                        <td class="col-lg-7">
                                                                                            <?php
                                                                                            echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                                            echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';

                                                                                            if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class="col-lg-1">
                                                                                            <?php if ($action_btn_flag == true) { ?>
                                                                                                <button class="btn btn-success btn-sm btn-block" onclick="no_action_req_edit_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>" is-payroll-visible="<?php echo $document['visible_to_payroll'] == 1 ? true : false; ?>" data-categories='<?php echo isset($archived_no_action_document_categories[$document['sid']]) ? json_encode($archived_no_action_document_categories[$document['sid']]) : "[]" ?>' data-update-accessible="<?php echo $document['document_type'] == "confidential" ? true : false; ?>">Edit Document</button>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td class="col-lg-1">
                                                                                            <?php if ($action_btn_flag == true) { ?>
                                                                                                <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                                    <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                                    <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                                </form>
                                                                                                <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td class="col-lg-1">
                                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>">View Doc</button>
                                                                                        </td>
                                                                                        <td class="col-lg-1">
                                                                                            <a href="<?php echo $manual_print_url; ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                        </td>
                                                                                        <td class="col-lg-1">
                                                                                            <a href="<?php echo $manual_download_url; ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <tr>
                                                                                    <td colspan="7" class="col-lg-12 text-center"><b>No Document(s) Found!</b></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- User Assigned Manual Documents End -->

                                                <!--Archived Manual Documents Start -->
                                                <!-- <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="panel panel-default hr-documents-tab-content">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_archived_manual_documents">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                         Manual Documents
                                                                        <div class="btn btn-xs btn-info">Archived</div>
                                                                        <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . count($archived_manual_documents); ?></b></div>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapse_archived_manual_documents" class="panel-collapse collapse">
                                                                <div class="table-responsive full-width">
                                                                    <table class="table table-plane">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="column" class="col-lg-10">Document Name</th>
                                                                                <th scope="column" class="col-lg-2 text-center">Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php if (count($archived_manual_documents) > 0) { ?>
                                                                                <?php foreach ($archived_manual_documents as $document) { ?>
                                                                                    <?php if ($document['archive'] != 1) { ?>
                                                                                        <tr>
                                                                                            <td class="col-lg-6">
                                                                                                <?php
                                                                                                echo $document['document_title'] . '&nbsp;';
                                                                                                echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                                                echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                                                echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                                                                if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                                    echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                                                                }
                                                                                                ?>
                                                                                            </td>

                                                                                            <?php if ($document['document_type'] == 'uploaded' || $document['document_type'] == 'confidential') { ?>
                                                                                                <?php if ($action_btn_flag == true) { ?>
                                                                                                    <td class="col-lg-2">
                                                                                                        <form id="form_activate_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                                            <input type="hidden" id="perform_action" name="perform_action" value="activate_uploaded_document" />
                                                                                                            <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                                        </form>
                                                                                                        <button class="btn btn-default btn-sm btn-block" onclick="func_unarchive_uploaded_document(<?php echo $document['sid']; ?>)">Activate</button>
                                                                                                        <button class="btn btn-success btn-sm btn-block"
                                                                                                            onclick="no_action_req_edit_document_model(this);"
                                                                                                            data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>"
                                                                                                            data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>"
                                                                                                            data-print-url="<?php echo $document['document_s3_name']; ?>"
                                                                                                            data-print-type="assigned"
                                                                                                            data-download-sid="<?php echo $document['sid']; ?>"
                                                                                                            data-file-name="<?php echo $document['document_original_name']; ?>"
                                                                                                            data-document-title="<?php echo $document['document_title']; ?>"
                                                                                                            is-offer-letter="<?php echo $document['manual_document_type'] == "offer_letter" ? true : false; ?>"
                                                                                                            is-payroll-visible="<?php echo $document['visible_to_payroll'] == 1 ? true : false; ?>"
                                                                                                            data-categories="<?php echo isset($archived_no_action_document_categories[$document['sid']]) ? json_encode($archived_no_action_document_categories[$document['sid']]) : '[]' ?>"
                                                                                                            data-update-accessible="<?php echo $document['document_type'] == "confidential" ? true : false; ?>">Edit Document</button>

                                                                                                        <?php if ($document['document_sid'] != 0) { ?>
                                                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_original_name']; ?>">Preview Document</button>
                                                                                                        <?php } else if ($document['document_sid'] == 0) { ?>
                                                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_original_name']; ?>">Preview Document</button>

                                                                                                        <?php } ?>
                                                                                                    </td>
                                                                                                <?php } ?>
                                                                                            <?php } else { ?>
                                                                                                <td class="col-lg-2"><button onclick="generated_document_original_preview(<?php echo $document['sid']; ?>);" class="btn btn-success btn-sm btn-block">Preview Document</button></td>
                                                                                            <?php } ?>
                                                                                            <?php if ($document['document_type'] == 'uploaded' || $document['document_type'] == 'confidential') { ?>
                                                                                                <td class="col-lg-1">
                                                                                                    <?php
                                                                                                    $document_filename = $document['document_s3_name'];
                                                                                                    $document_file = pathinfo($document_filename);
                                                                                                    $document_extension = $document_file['extension'];
                                                                                                    $name = explode(".", $document_filename);
                                                                                                    $url_segment_original = $name[0];
                                                                                                    ?>
                                                                                                    <?php if ($document_extension == 'pdf') { ?>
                                                                                                        <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_original . '.pdf' ?>" class="btn btn-success btn-sm btn-block">Print</a>

                                                                                                    <?php } else if ($document_extension == 'docx') { ?>
                                                                                                        <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edocx&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                    <?php } else if ($document_extension == 'doc') { ?>
                                                                                                        <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edoc&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                    <?php } else if ($document_extension == 'xls') { ?>
                                                                                                        <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exls' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                    <?php } else if ($document_extension == 'xlsx') { ?>
                                                                                                        <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exlsx' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                    <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                                                                        <a target="_blank" href="<?php echo base_url('hr_documents_management/print_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/original'); ?>" class="btn btn-success btn-sm btn-block">
                                                                                                            Print
                                                                                                        </a>
                                                                                                    <?php } else { ?>
                                                                                                        <a class="btn btn-success btn-sm btn-block"
                                                                                                           href="javascript:void(0);"
                                                                                                           onclick="fLaunchModal(this);"
                                                                                                           data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                                                                           data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                                                                           data-file-name="<?php echo $document_filename; ?>"
                                                                                                           data-document-title="<?php echo $document_filename; ?>"
                                                                                                           data-preview-ext="<?php echo $document_extension ?>">Print</a>
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                                <td class="col-lg-1">
                                                                                                    <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['document_s3_name']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                </td>
                                                                                            <?php } else { ?>
                                                                                                <td class="col-lg-1">
                                                                                                    <a href="<?= base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document['sid']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                </td>

                                                                                                <td class="col-lg-1">
                                                                                                    <a href="<?= base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document['sid'] . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                </td>

                                                                                            <?php } ?>

                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <tr>
                                                                                    <td colspan="7" class="col-lg-12 text-center"><b>No Document(s) Found!</b></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <!-- Archived Manual Documents End -->

                                                <?php if ($session['employer_detail']['access_level_plus'] == 1) { ?>
                                                    <!-- Offer Letter Start -->
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="panel panel-default ems-documents js-search-header">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_total_offer_letters">
                                                                            <span class="glyphicon glyphicon-plus"></span>
                                                                            Offer Letter / Pay Plan
                                                                            <div class="btn btn-xs btn-primary">All</div>
                                                                            <div class="pull-right total-records"><b><?php echo 'Total: ' . count($company_offer_letters); ?></b></div>
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse_total_offer_letters" class="panel-collapse collapse">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-plane">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th scope="column" class="col-xs-6">Offer Letter / Pay Plan Name</th>
                                                                                    <th scope="column" class="col-xs-2">Offer Letter / Pay Plan Type</th>

                                                                                    <th scope="column" class="col-xs-4 text-center" colspan="5">Actions</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php if (count($company_offer_letters) > 0) { ?>
                                                                                    <?php foreach ($company_offer_letters as $offer_letter) { ?>
                                                                                        <?php if ($offer_letter['letter_type'] == 'uploaded') { ?>
                                                                                            <?php
                                                                                            $offer_letter_iframe_url    = '';
                                                                                            $offer_letter_url           = $offer_letter['uploaded_document_s3_name'];
                                                                                            $offer_letter_file_info     = explode(".", $offer_letter_url);
                                                                                            $offer_letter_name          = $offer_letter_file_info[0];
                                                                                            $offer_letter_extension     = isset($offer_letter_file_info[1]) ? $offer_letter_file_info[1] : 'pdf';
                                                                                            $offer_letter_print_url     = '';
                                                                                            $offer_letter_download_url  = '';

                                                                                            if (in_array($offer_letter_extension, ['pdf', 'ppt', 'pptx', 'csv'])) {
                                                                                                $offer_letter_iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $offer_letter_url . '&embedded=true';

                                                                                                if ($offer_letter_extension == 'pdf') {
                                                                                                    $offer_letter_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $offer_letter_name . '.pdf';
                                                                                                } else if ($offer_letter_extension == 'ppt') {
                                                                                                    $offer_letter_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $offer_letter_name . '.ppt';
                                                                                                } else if ($offer_letter_extension == 'pptx') {
                                                                                                    $offer_letter_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $offer_letter_name . '.pptx';
                                                                                                } else if ($offer_letter_extension == 'csv') {
                                                                                                    $offer_letter_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $offer_letter_name . '.csv';
                                                                                                }
                                                                                            } else if (in_array($offer_letter_extension, ['doc', 'docx', 'xls', 'xlsx'])) {
                                                                                                $offer_letter_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $offer_letter_url);

                                                                                                if ($offer_letter_extension == 'doc') {
                                                                                                    $offer_letter_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $offer_letter_name . '%2Edoc&wdAccPdf=0';
                                                                                                } else if ($offer_letter_extension == 'docx') {
                                                                                                    $offer_letter_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $offer_letter_name . '%2Edocx&wdAccPdf=0';
                                                                                                } else if ($offer_letter_extension == 'xls') {
                                                                                                    $offer_letter_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $offer_letter_name . '%2Exls';
                                                                                                } else if ($offer_letter_extension == 'xlsx') {
                                                                                                    $offer_letter_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $offer_letter_name . '%2Exlsx';
                                                                                                }
                                                                                            } else if (in_array($offer_letter_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
                                                                                                $offer_letter_iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $offer_letter_url . '&embedded=true';
                                                                                                $offer_letter_print_url = base_url('hr_documents_management/print_generated_and_offer_later/submitted/generated/' . $offer_letter['sid']);
                                                                                            }

                                                                                            $offer_letter_download_url = base_url('hr_documents_management/download_upload_document/' . $offer_letter_url);
                                                                                            ?>
                                                                                        <?php } ?>
                                                                                        <tr class="js-search-row">
                                                                                            <td class="col-xs-6">
                                                                                                <?php echo $offer_letter['letter_name']; ?>
                                                                                            </td>
                                                                                            <td class="col-xs-2:"><?php echo ucwords($offer_letter['letter_type']); ?></td>
                                                                                            <td class="col-xs-1">
                                                                                                <?php if ($document_all_permission) { ?>
                                                                                                    <?php if (in_array($offer_letter['sid'], $approval_offer_letters)) { ?>
                                                                                                        <button data-document_sid="<?= $offer_letter['sid']; ?>" class="btn btn-danger btn-block btn-sm jsRevokeApprovalDocument">Revoke Approval</button>
                                                                                                        <button data-document_sid="<?= $offer_letter['sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                                            View Approver(s)
                                                                                                        </button>
                                                                                                    <?php } else { ?>
                                                                                                        <?php if ($assigned_offer_letter_sid == $offer_letter['sid']) { ?>
                                                                                                            <?php if ($assigned_offer_letter_status == 1 && $assigned_offer_letter_archive == 0) { ?>
                                                                                                                <form id="form_assign_document_offer_letter_<?php echo $offer_letter['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo base_url('hr_documents_management/revoke_offer_letter'); ?>">
                                                                                                                    <input type="hidden" name="perform_action" value="revoke_offer_letter" />
                                                                                                                    <input type="hidden" name="offer_letter_sid" value="<?php echo $offer_letter['sid']; ?>">
                                                                                                                    <input type="hidden" name="user_sid" value="<?php echo $user_sid; ?>">
                                                                                                                    <input type="hidden" name="user_type" value="<?php echo $user_type; ?>">
                                                                                                                    <?php if ($user_type == 'applicant') { ?>
                                                                                                                        <input type="hidden" name="job_list_sid" value="<?php echo $job_list_sid; ?>">
                                                                                                                    <?php } ?>
                                                                                                                </form>

                                                                                                                <?php
                                                                                                                if (array_key_exists($document['sid'], $assignIdObj)) {
                                                                                                                    echo str_replace(['{{sid}}', '{{type}}'], [$offer_letter['sid'], 'notCompletedOfferLetters'], $modifyBTN);
                                                                                                                }
                                                                                                                ?>

                                                                                                                <button onclick="func_assign_uploaded_offer_letter('offer_letter', <?php echo $offer_letter['sid']; ?>);" class="btn btn-danger btn-block btn-sm">Revoke</button>

                                                                                                            <?php } else if ($assigned_offer_letter_status == 1 && $assigned_offer_letter_archive == 1) { ?>


                                                                                                                <button class="btn btn-warning btn-sm btn-block js-modify-assign-offer-letter-btn" data-id="<?= $offer_letter['sid']; ?>">Modify and Reassign</button>

                                                                                                            <?php } ?>

                                                                                                        <?php } else { ?>
                                                                                                            <button class="btn btn-success btn-sm btn-block js-modify-assign-offer-letter-btn" data-id="<?= $offer_letter['sid']; ?>">Modify and Reassign</button>
                                                                                                        <?php } ?>
                                                                                                    <?php } ?>
                                                                                                <?php } ?>
                                                                                            </td>
                                                                                            <td class="col-xs-1">
                                                                                                <?php if ($offer_letter['letter_type'] == 'hybrid_document') { ?>
                                                                                                    <button data-id="<?= $offer_letter['sid']; ?>" data-document="original" data-type="offer_letter" class="btn btn-success btn-sm btn-block js-hybrid-preview" data-from="company_offer_letter">View Doc</button>
                                                                                                <?php } else if ($offer_letter['letter_type'] == 'uploaded') { ?>
                                                                                                    <button class="btn btn-success btn-sm btn-block" onclick="show_uploaded_offer_letter(this);" data-preview-url="<?php echo $offer_letter_iframe_url; ?>" data-file-name="<?php echo $offer_letter['letter_name']; ?>" data-print-url="<?php echo $offer_letter_print_url; ?>" data-download-url="<?php echo $offer_letter_download_url; ?>">View Doc</button>
                                                                                                <?php } else { ?>
                                                                                                    <button onclick="func_get_generated_document_preview(<?php echo $offer_letter['sid']; ?>,'offer', 'original');" class="btn btn-success btn-sm btn-block">View Doc</button>
                                                                                                <?php  } ?>
                                                                                            </td>
                                                                                            <td class="col-lg-1">
                                                                                                <?php if ($offer_letter['letter_type'] == 'hybrid_document') { ?>
                                                                                                <?php } else if ($offer_letter['letter_type'] == 'uploaded') { ?>
                                                                                                    <a target="_blank" href="<?php echo $offer_letter_print_url; ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                <?php } else { ?>
                                                                                                    <a href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original/offer/' . $offer_letter['sid']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                <?php }  ?>
                                                                                            </td>
                                                                                            <td class="col-lg-1">
                                                                                                <?php if ($offer_letter['letter_type'] == 'hybrid_document') { ?>
                                                                                                <?php } else if ($offer_letter['letter_type'] == 'uploaded') { ?>
                                                                                                    <a href="<?php echo $offer_letter_download_url; ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                <?php } else { ?>
                                                                                                    <a href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original/offer/' . $offer_letter['sid'] . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                <?php } ?>

                                                                                            </td>
                                                                                            <td class="col-lg-1">
                                                                                                <?php if ($offer_letter['is_specific'] != 0) { ?>
                                                                                                    <!-- <button class="btn btn-success btn-sm js-offer-letter-edit-btn" data-id="<?= $offer_letter['sid']; ?>">Edit</button> -->
                                                                                                <?php } ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php  }
                                                                                } else { ?>
                                                                                    <tr>
                                                                                        <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>

                                                                                    </tr>
                                                                                <?php  } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Offer Letter End -->
                                                <?php } ?>

                                                <!-- All Document Tab Start -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="panel panel-default ems-documents js-search-header">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_total_documents">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                        All Documents
                                                                        <div class="btn btn-xs btn-primary">All</div>
                                                                        <div class="pull-right total-records"><b><?php echo 'Total: ' . count($all_documents); ?></b></div>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapse_total_documents" class="panel-collapse collapse">
                                                                <div class="table-responsive">
                                                                    <table class="table table-plane">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="column" class="col-xs-6">Document Name</th>
                                                                                <th scope="column" class="col-xs-2">Document Type</th>
                                                                                <th scope="column" class="col-xs-4 text-center" colspan="4">Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                            <?php if (count($all_documents) > 0) { ?>
                                                                                <?php foreach ($all_documents as $document) { ?>
                                                                                    <tr class="js-search-row">
                                                                                        <td class="col-xs-6"><?php echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : ''); ?></td>
                                                                                        <td class="col-xs-2">
                                                                                            <?php echo ucwords($document['document_type']); ?>
                                                                                        </td>
                                                                                        <?php if (
                                                                                            ($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_assign_revoke_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_assign_revoke_doc')) ||
                                                                                            $canAccessDocument
                                                                                        ) { ?>
                                                                                            <?php if ($action_btn_flag == true || $session['employer_detail']['pay_plan_flag'] == 0) { ?>
                                                                                                <?php if ($document_all_permission || $canAccessDocument) { ?>
                                                                                                    <td>
                                                                                                        <?php if (in_array($document['sid'], $approval_documents)) { ?>
                                                                                                            <button data-document_sid="<?= $document['sid']; ?>" class="btn btn-danger btn-block btn-sm jsRevokeApprovalDocument">Revoke Approval</button>
                                                                                                            <button data-document_sid="<?= $document['sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                                                View Approver(s)
                                                                                                            </button>
                                                                                                        <?php } else { ?>
                                                                                                            <?php if (in_array($document['sid'], $assigned_sids) || in_array($document['sid'], $revoked_sids) || in_array($document['sid'], $completed_sids) || in_array($document['sid'], $signed_document_sids) || in_array($document['sid'], $approval_documents)) { ?>
                                                                                                                <?php if (in_array($document['sid'], $assigned_sids) || in_array($document['sid'], $approval_documents)) { ?>
                                                                                                                    <!-- assign doc revoke here -->
                                                                                                                    <form id="form_remove_document_<?php echo $document['document_type']; ?>_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="remove_document" />
                                                                                                                        <input type="hidden" id="document_type" name="document_type" value="<?php echo $document['document_type']; ?>" />
                                                                                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                                                    </form>

                                                                                                                    <?php
                                                                                                                    if (array_key_exists($document['sid'], $assignIdObj)) {
                                                                                                                        echo str_replace(['{{sid}}', '{{type}}'], [$document['sid'], 'notCompletedDocuments'], $modifyBTN);
                                                                                                                    }
                                                                                                                    ?>

                                                                                                                    <button onclick="func_remove_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>);" class="btn btn-danger btn-block btn-sm">Revoke</button>
                                                                                                                <?php } else if (in_array($document['sid'], $signed_document_sids)) { ?>
                                                                                                                    <button class="btn blue-button btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Completed and Reassign</button>
                                                                                                                <?php } else { ?>
                                                                                                                    <!-- revoke doc re-assign here -->
                                                                                                                    <button class="btn btn-warning btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Modify and Reassign</button>
                                                                                                                <?php } ?>
                                                                                                            <?php } else { // assign here 
                                                                                                            ?>
                                                                                                                <button class="btn btn-success btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Modify and Assign</button>
                                                                                                            <?php } ?>
                                                                                                        <?php } ?>
                                                                                                    </td>
                                                                                                <?php } ?>
                                                                                            <?php } ?>
                                                                                        <?php } ?>
                                                                                        <td>
                                                                                            <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                                <button data-id="<?= $document['sid']; ?>" data-from="company" class="btn btn-success btn-sm btn-block js-hybrid-preview">View Doc</button>
                                                                                            <?php } else if ($document['document_type'] == 'uploaded') { ?>
                                                                                                <?php
                                                                                                $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                                $document_file = pathinfo($document_filename);
                                                                                                $name = explode(".", $document_filename);
                                                                                                $url_segment_original = $name[0];
                                                                                                ?>
                                                                                                <button class="btn btn-success btn-sm btn-block" onclick="view_original_uploaded_doc(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-document-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">View Doc</button>
                                                                                            <?php } else { ?>
                                                                                                <button onclick="view_original_generated_document(<?php echo $document['sid']; ?>, 'generated', 'original');" class="btn btn-success btn-sm btn-block">View Doc</button>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                        <?php } else if ($document['document_type'] == 'uploaded') { ?>
                                                                                            <td class="col-lg-1">
                                                                                                <?php
                                                                                                $document_sid = $document['sid'];
                                                                                                $document_filename = $document['uploaded_document_s3_name'];
                                                                                                $document_file = pathinfo($document_filename);
                                                                                                $document_extension = $document_file['extension'];
                                                                                                $name = explode(".", $document_filename);
                                                                                                $url_segment_original = $name[0];
                                                                                                ?>
                                                                                                <?php if ($document_extension == 'pdf') { ?>
                                                                                                    <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_original . '.pdf' ?>" class="btn btn-success btn-sm btn-block">Print</a>

                                                                                                <?php } else if ($document_extension == 'docx') { ?>
                                                                                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edocx&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                <?php } else if ($document_extension == 'doc') { ?>
                                                                                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edoc&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                <?php } else if ($document_extension == 'xls') { ?>
                                                                                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exls' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                <?php } else if ($document_extension == 'xlsx') { ?>
                                                                                                    <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exlsx' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                                <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                                                                    <a target="_blank" href="<?php echo base_url('hr_documents_management/print_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/original'); ?>" class="btn btn-success btn-sm btn-block">
                                                                                                        Print
                                                                                                    </a>
                                                                                                <?php } else { ?>
                                                                                                    <a class="btn btn-success btn-sm btn-block" href="javascript:void(0);" onclick="fLaunchModal(this);" data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-file-name="<?php echo $document_filename; ?>" data-document-title="<?php echo $document_filename; ?>" data-preview-ext="<?php echo $document_extension ?>">Print</a>
                                                                                                <?php } ?>
                                                                                            </td>
                                                                                            <td class="col-lg-1">
                                                                                                <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['uploaded_document_s3_name']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                            </td>
                                                                                        <?php } else { ?>
                                                                                            <td class="col-lg-1">
                                                                                                <a href="<?= base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document['sid']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                            </td>

                                                                                            <td class="col-lg-1">
                                                                                                <a href="<?= base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document['sid'] . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                            </td>

                                                                                        <?php } ?>
                                                                                    </tr>
                                                                                <?php  } ?>
                                                                            <?php } else { ?>
                                                                                <tr>
                                                                                    <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- All Document Tab End -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel panel-default hr-documents-tab-content">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_adh_1">
                                                    <span class="glyphicon glyphicon-plus"></span>
                                                    <?php echo 'Re-Assign Document History'; ?>
                                                    <div class="pull-right total-records"><b><?php echo '&nbsp;Total: <span class="jsDDTotal"></span>'; ?></b></div>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse_adh_1" class="panel-collapse collapse">
                                            <br />
                                            <ul class="nav nav-tabs nav-justified" style="background-color: #e0e0e0;" id="tab-nav">
                                                <li class="active"><a data-toggle="tab" class="jsTabBTN" onclick="loadDD(this)" data-type="normal">Document(s) (<span class="jsDDNC">0</span>)</a></li>
                                                <li><a data-toggle="tab" class="jsTabBTN" onclick="loadDD(this)" data-type="timed">Scheduled Document(s) (<span class="jsDDNT">0</span>)</a></li>
                                            </ul>

                                            <div class="jsTabPager">
                                                <div class="csTab jsTab csTabActive" id="jsNormalDocuments">
                                                    <div class="table-responsive">
                                                        <table class="table table-plane">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="column" class="col-lg-3">Document Name</th>
                                                                    <th scope="column" class="col-lg-1 text-center">Type</th>
                                                                    <th scope="column" class="col-lg-2 text-center">Sent On</th>
                                                                    <th scope="column" class="col-lg-2 text-center">Acknowledged</th>
                                                                    <th scope="column" class="col-lg-2 text-center">Downloaded</th>
                                                                    <th scope="column" class="col-lg-1 text-center">Uploaded</th>
                                                                    <th scope="column" class="col-lg-1 text-center">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="7" class="col-lg-12 text-center"><b>No History Available</b></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="csTab jsTab" id="jsTimedDocuments">
                                                    <div class="table-responsive">
                                                        <table class="table table-plane">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="column" class="col-lg-3">Document Name</th>
                                                                    <th scope="column" class="col-lg-1 text-center">Type</th>
                                                                    <th scope="column" class="col-lg-2 text-center">Sent On</th>
                                                                    <th scope="column" class="col-lg-2 text-center">Acknowledged</th>
                                                                    <th scope="column" class="col-lg-2 text-center">Downloaded</th>
                                                                    <th scope="column" class="col-lg-1 text-center">Uploaded</th>
                                                                    <th scope="column" class="col-lg-1 text-center">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="7" class="col-lg-12 text-center"><b>No History Available</b></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel panel-default hr-documents-tab-content">
                                        <div class="panel-heading <?php echo !empty($assigned_offer_letter_history) ? 'btn-success' : ''; ?>">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_aolh_2">
                                                    <span class="glyphicon glyphicon-plus"></span>
                                                    <?php echo 'Re-Assign Offer Letter History'; ?>
                                                    <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . sizeof($assigned_offer_letter_history); ?></b></div>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse_aolh_2" class="panel-collapse collapse">
                                            <div class="table-responsive">
                                                <table class="table table-plane">
                                                    <thead>
                                                        <tr>
                                                            <th scope="column" class="col-lg-3">Document Name</th>
                                                            <th scope="column" class="col-lg-1 text-center">Type</th>
                                                            <th scope="column" class="col-lg-2 text-center">Sent On</th>
                                                            <th scope="column" class="col-lg-2 text-center">Acknowledged</th>
                                                            <th scope="column" class="col-lg-2 text-center">Downloaded</th>
                                                            <th scope="column" class="col-lg-1 text-center">Uploaded</th>
                                                            <th scope="column" class="col-lg-1 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($assigned_offer_letter_history)) {
                                                            foreach ($assigned_offer_letter_history as $document) { ?>
                                                                <tr>
                                                                    <td class="col-lg-3">
                                                                        <?php echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : ''); ?>
                                                                    </td>
                                                                    <td class="col-lg-1 text-center">
                                                                        <?php $doc_type = '';

                                                                        if (!empty($document['document_extension'])) {
                                                                            $doc_type = strtolower($document['document_extension']);
                                                                        } ?>
                                                                        <?php if ($doc_type == 'pdf') { ?>
                                                                            <i aria-hidden="true" class="fa fa-2x fa-file-pdf-o"></i>
                                                                        <?php } else if (in_array($doc_type, ['ppt', 'pptx'])) { ?>
                                                                            <i aria-hidden="true" class="fa fa-2x fa-file-powerpoint-o"></i>
                                                                        <?php } else if (in_array($doc_type, ['doc', 'docx'])) { ?>
                                                                            <i aria-hidden="true" class="fa fa-2x fa-file-o"></i>
                                                                        <?php } else if (in_array($doc_type, ['xlsx'])) { ?>
                                                                            <i aria-hidden="true" class="fa fa-2x fa-file-excel-o"></i>
                                                                        <?php } else if ($doc_type == '') { ?>
                                                                            <i aria-hidden="true" class="fa fa-2x fa-file-text"></i>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="col-lg-2 text-center">
                                                                        <?php if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') { ?>
                                                                            <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                                            <div class="text-center">
                                                                                <?php // echo date_format(new DateTime($document['assigned_date']), 'M d Y h:m a'); 
                                                                                ?>
                                                                                <?= reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this)); ?>

                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="col-lg-2 text-center">
                                                                        <?php if (!$document['acknowledgment_required']) {
                                                                            echo '<b>N/A</b>';
                                                                        } elseif (isset($document['acknowledged_date']) && $document['acknowledged_date'] != '0000-00-00 00:00:00') { ?>
                                                                            <?php if ($document['acknowledged'] == 0) { ?>
                                                                                <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                                                            <?php } else { ?>
                                                                                <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                                            <?php } ?>
                                                                            <div class="text-center">
                                                                                <?php // echo date_format(new DateTime($document['acknowledged_date']), 'M d Y h:m a'); 
                                                                                ?>
                                                                                <?= reset_datetime(array('datetime' => $document['acknowledged_date'], '_this' => $this)); ?>

                                                                            </div>
                                                                        <?php } elseif ($document['user_consent'] == 1) { ?>
                                                                            <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                                            <div class="text-center">
                                                                                <?php // echo date_format(new DateTime($document['signature_timestamp']), 'M d Y h:m a'); 
                                                                                ?>
                                                                                <?= reset_datetime(array('datetime' => $document['signature_timestamp'], '_this' => $this)); ?>

                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="col-lg-2 text-center">
                                                                        <?php if (!$document['download_required']) {
                                                                            echo '<b>N/A</b>';
                                                                        } elseif (isset($document['downloaded_date']) && $document['downloaded_date'] != '0000-00-00 00:00:00') { ?>
                                                                            <?php if ($document['downloaded'] == 0) { ?>
                                                                                <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                                                            <?php } else { ?>
                                                                                <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                                            <?php } ?>
                                                                            <div class="text-center">
                                                                                <?php // echo date_format(new DateTime($document['downloaded_date']), 'M d Y h:m a'); 
                                                                                ?>
                                                                                <?= reset_datetime(array('datetime' => $document['downloaded_date'], '_this' => $this)); ?>

                                                                            </div>
                                                                        <?php } elseif ($document['user_consent'] == 1) { ?>
                                                                            <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                                            <div class="text-center">
                                                                                <?php // echo date_format(new DateTime($document['signature_timestamp']), 'M d Y h:m a'); 
                                                                                ?>
                                                                                <?= reset_datetime(array('datetime' => $document['signature_timestamp'], '_this' => $this)); ?>

                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="col-lg-2 text-center">
                                                                        <?php if (!$document['signature_required']) {
                                                                            echo '<b>N/A</b>';
                                                                        } elseif (isset($document['uploaded_date']) && $document['uploaded_date'] != '0000-00-00 00:00:00') { ?>
                                                                            <?php if ($document['uploaded'] == 0) { ?>
                                                                                <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                                                            <?php } else { ?>
                                                                                <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                                            <?php } ?>
                                                                            <div class="text-center">
                                                                                <?php // echo date_format(new DateTime($document['uploaded_date']), 'M d Y h:m a'); 
                                                                                ?>
                                                                                <?= reset_datetime(array('datetime' => $document['uploaded_date'], '_this' => $this)); ?>

                                                                            </div>
                                                                        <?php } elseif ($document['user_consent'] == 1) { ?>
                                                                            <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                                            <div class="text-center">
                                                                                <?php // echo date_format(new DateTime($document['signature_timestamp']), 'M d Y h:m a'); 
                                                                                ?>
                                                                                <?= reset_datetime(array('datetime' => $document['signature_timestamp'], '_this' => $this)); ?>

                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="col-lg-1 text-center">
                                                                        <?php if ($document['offer_letter_type'] == 'hybrid_document') { ?>
                                                                            <button class="btn btn-success btn-sm btn-block js-hybrid-preview" data-id="<?= $document['sid']; ?>" data-document="assigned_history" data-type="offer_letter">
                                                                                Preview Assigned
                                                                            </button>
                                                                            <?php if ($document_all_permission) { ?>
                                                                                <button class="btn btn-success btn-sm btn-block <?= $document['submitted_description'] != null ? 'js-hybrid-preview' : 'disabled'; ?>" data-id="<?= $document['sid']; ?>" data-document="assigned_history" data-type="offer_letter">
                                                                                    Preview submitted
                                                                                </button>
                                                                            <?php } ?>
                                                                        <?php } else if ($document['offer_letter_type'] == 'uploaded') { ?>
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>
                                                                                Preview Assigned
                                                                            </button>
                                                                            <?php if ($document_all_permission) { ?>
                                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="submitted" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>" data-s3-name="<?php echo $document['uploaded_file']; ?>" <?php echo $document['user_consent'] != 1 ? 'disabled' : ''; ?>>
                                                                                    Preview Submitted
                                                                                </button>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document_history">
                                                                                Preview Assigned
                                                                            </button>
                                                                            <?php if ($document_all_permission) { ?>
                                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="submitted" data-from="assigned_document_history" <?php echo $document['user_consent'] != 1 ? 'disabled' : ''; ?>>
                                                                                    Preview Submitted
                                                                                </button>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan="7" class="col-lg-12 text-center"><b>No History Available</b></td>
                                                            </tr>
                                                        <?php }  ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <?php
                // die($left_navigation);
                $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<!-- Document Modal -->
<div id="document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                ...
            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>

<!-- Modal Manual Document -->
<div id="uploaded_document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="uploaded_document_modal_title">Paper Form Upload</h4>
            </div>
            <div id="uploaded_document_modal_body" class="modal-body">
                <div class="loader" id="add_form_upload_document_loader" style="display: none;">
                    <i aria-hidden="true" class="fa fa-spinner fa-spin"></i>
                </div>
                <form id="add_form_upload_document" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                    <div class="row">
                        <div class="col-xs-12">
                            <label>Browse Document <span class="staric">*</span></label>
                            <input style="display: none;" type="file" name="document" id="manual_document">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 margin-top">
                            <label>Document Name<span class="staric">*</span></label>
                            <input type="text" name="document_title" id="add_upload_manual_doc_title" value="" class="invoice-fields">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 margin-top">
                            <label>Instructions / Guidance </label>
                            <textarea class="invoice-fields autoheight ckeditor" maxlength="250" name="document_description" id="document_description" cols="54" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 margin-top">
                            <label>Assigned Date</label>
                            <input type="text" name="doc_assign_date" value="" class="invoice-fields doc_date" readonly>
                        </div>
                        <div class="col-xs-6 margin-top">
                            <label>Signed Completed Date</label>
                            <input type="text" name="doc_sign_date" value="" class="invoice-fields doc_date" readonly>
                        </div>
                    </div>
                    <div class="row" id="categories_section">
                        <div class="col-xs-12 margin-top">
                            <label>Categories</label><br>
                            <div class="Category_chosen">
                                <select data-placeholder="Please Select" multiple="multiple" onchange="" name="categories[]" id="createcategories" class="categories">
                                    <?php if (sizeof($active_categories) > 0) { ?>
                                        <?php foreach ($active_categories as $category) { ?>
                                            <option value="<?php echo $category['sid']; ?>"><?= $category['name'] ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php if ($session['employer_detail']['access_level_plus']) { ?>
                        <div class="row">
                            <div class="col-xs-12 margin-top">
                                <label class="control control--checkbox">
                                    <input name="accessable" id="accessable" type="checkbox" value="" class="ej_checkbox">
                                    <div class="control__indicator"></div>
                                    Accessible for Admins
                                </label>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($session['employer_detail']['access_level_plus']) { ?>
                        <div class="row">
                            <div class="col-xs-12 margin-top">
                                <label class="control control--checkbox">
                                    <input name="is_offer_letter" id="is_offer_letter" type="checkbox" value="" class="ej_checkbox">
                                    <div class="control__indicator"></div>
                                    Offer Letter / Pay Plan
                                </label>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row" id="manual_doc_payroll_section">
                        <div class="col-xs-12 margin-top">
                            <label class="control control--checkbox font-normal">
                                Visible To Payroll Plus
                                <input class="disable_doc_checkbox" id="visible_manual_doc_to_payroll" name="visible_manual_doc_to_payroll" type="checkbox" value="1" />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>

                    <br />
                    <?php $this->load->view('hr_documents_management/partials/settings'); ?>
                    <br />

                    <div class="row">
                        <div class="col-xs-12">
                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                            <input type="hidden" name="perform_action" value="assign_specific" />
                            <input type="hidden" name="document_url" id="add_manual_doc_url" />
                            <input type="hidden" name="document_name" id="add_manual_doc_name" />
                            <input type="hidden" name="document_extension" id="add_manual_doc_extension" />
                            <button type="submit" class="btn btn-success pull-right">Upload to Document Center</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="uploaded_document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>

<!-- Modal Update Manual Document -->
<div id="no_action_document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="no_action_document_modal_title">Modal title</h4>
            </div>
            <div class="modal-body">
                <div class="loader" id="update_form_upload_document_loader" style="display: none;">
                    <i aria-hidden="true" class="fa fa-spinner fa-spin"></i>
                </div>
                <div id="document_modal_upload">
                    <form id="update_form_upload_document" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                        <div class="row">
                            <div class="col-xs-12">
                                <label class="error_message"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 margin-top">
                                <label>Document Name<span class="staric">*</span></label>
                                <input type="text" name="document_title" value="" class="invoice-fields" id="update_manual_doc_title">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Browse Document</label>
                                <input style="display: none;" type="file" name="document" id="reupload_manual_document">
                                <!-- <div class="upload-file invoice-fields">
                                    <input style="height: 38px;" type="file" name="document" id="reupload_document" onchange="check_file('reupload_document')">
                                    <p id="name_reupload_document"></p>
                                    <a href="javascript:;" style="line-height: 38px; height: 38px;">Choose File</a>
                                </div> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 margin-top">
                                <label>Assigned Date</label>
                                <input type="text" name="doc_assign_date" value="" id="doc_assign_date" class="invoice-fields doc_date" readonly>
                            </div>
                            <div class="col-xs-6 margin-top">
                                <label>Signed Completed Date</label>
                                <input type="text" name="doc_sign_date" value="" id="doc_sign_date" class="invoice-fields doc_date" readonly>
                            </div>
                        </div>
                        <div class="row" id="update_categories_section">
                            <div class="col-xs-12">
                                <label>Categories</label>
                                <div class="Category_chosen">
                                    <select data-placeholder="Please Select" multiple="multiple" onchange="" name="categories[]" id="updatecategories" class="categories">
                                        <?php if (sizeof($active_categories) > 0) { ?>
                                            <?php foreach ($active_categories as $category) { ?>
                                                <option value="<?php echo $category['sid']; ?>"><?= $category['name'] ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php if ($session['employer_detail']['access_level_plus']) { ?>
                            <div class="row">
                                <div class="col-xs-12 margin-top">
                                    <label class="control control--checkbox">
                                        <input name="accessable" id="update_accessible" type="checkbox" value="" class="ej_checkbox">
                                        <div class="control__indicator"></div>
                                        Accessable for Admins
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($session['employer_detail']['access_level_plus']) { ?>
                            <div class="row">
                                <div class="col-xs-12 margin-top">
                                    <label class="control control--checkbox">
                                        <input name="is_offer_letter" id="update_is_offer_letter" type="checkbox" value="" class="ej_checkbox">
                                        <div class="control__indicator"></div>
                                        Offer Letter / Pay Plan
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row" id="update_manual_doc_payroll_section">
                            <div class="col-xs-12 margin-top">
                                <label class="control control--checkbox font-normal">
                                    Visible To Payroll Plus
                                    <input class="disable_doc_checkbox" id="update_manual_doc_to_payroll" name="update_manual_doc_to_payroll" type="checkbox" value="1" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <br />

                        <div class="row">
                            <div class="col-xs-12">
                                <input type="hidden" name="document_url" id="update_manual_doc_url" />
                                <input type="hidden" name="document_name" id="update_manual_doc_name" />
                                <input type="hidden" name="document_extension" id="update_manual_doc_extension" />
                                <input type="hidden" id="previous_categories" value="" />
                                <input type="hidden" id="previous_update_accessible" value="" />
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                <input type="hidden" id="not_action_req_document_sid" name="documents_assigned_sid" value="" />
                                <input type="hidden" name="perform_action" value="reupload_assign_specific" />
                                <button type="submit" class="btn btn-success pull-right">Update Manual Document</button>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
            <div id="no_action_document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>

<?php
if ($user_type == 'applicant') {
    if ($EeocFormStatus && !empty($eeo_form_info)) {
?>
        <div id="eeoc_modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-bg">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="review_modal_title">EEOC FORM</h4>
                    </div>
                    <div id="review_modal_body" class="modal-body">
                        <div class="table-responsive">
                            <div class="container-fluid">
                                <?php $this->load->view('eeo/eeoc_view'); ?>
                            </div>
                        </div>
                    </div>
                    <div id="review_modal_footer" class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>

<?php
if ($user_type == 'employee') {
    if (!empty($eeo_form_info)) {
?>
        <div id="eeoc_modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-bg">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="review_modal_title">EEOC FORM</h4>
                    </div>
                    <div id="review_modal_body" class="modal-body">
                        <div class="table-responsive">
                            <div class="container-fluid">
                                <?php $this->load->view('eeo/eeoc_view'); ?>
                            </div>
                        </div>
                    </div>
                    <div id="review_modal_footer" class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>

<div id="model_generated_doc" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="generated_document_title">Assign This Document</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method='post' id='register-form' name='register-form' action="<?= current_url(); ?>">
                        <div class="col-lg-12">
                            <div class="form-group full-width">
                                <h4 id="gen_document_label"></h4>
                                <b>Please review this document and make any necessary modifications. Modifications will not affect the Original Document.</b>
                                <!--<br>The Modified document will only be sent to the <?= ucwords($user_type); ?> it was assigned to.-->
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group full-width">
                                <label>Document Description<span class="hr-required red"> * </span></label>
                                <textarea style="padding:5px; height:200px; width:100%;" class="ckeditor" id="gen_doc_description" name="document_description"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="offer-letter-help-widget full-width form-group">
                                <div class="how-it-works-insturction">
                                    <strong>How it's Works :</strong>
                                    <p class="how-works-attr">1. Generate new Electronic Document</p>
                                    <p class="how-works-attr">2. Enable Document Acknowledgment</p>
                                    <p class="how-works-attr">3. Enable Electronic Signature</p>
                                    <p class="how-works-attr">4. Insert multiple tags where applicable</p>
                                    <p class="how-works-attr">5. Save the Document</p>
                                </div>

                                <div class="tags-arae">
                                    <div class="col-md-12">
                                        <h5><strong>Company Information Tags:</strong></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{company_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{company_address}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{company_phone}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{career_site_url}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="tags-arae">
                                    <div class="col-md-12">
                                        <h5><strong>Employee / Applicant Tags :</strong></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{first_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{last_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{email}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{job_title}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="tags-arae">
                                    <div class="col-md-12">
                                        <h5><strong>Signature tags:</strong></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{signature}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{signature_print_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{inital}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{sign_date}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{authorized_signature}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{authorized_signature_date}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{short_text}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{text}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{text_area}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{checkbox}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="tags-arae">
                                    <div class="col-md-12">
                                        <h5><strong>Pay Plan / Offer Letter tags:</strong></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{hourly_rate}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{hourly_technician}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{flat_rate_technician}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_salary}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_draw}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" id="perform_action" name="perform_action" value="assign_document" />
                            <input type="hidden" name="document_type" id="gen-doc-type">
                            <input type="hidden" name="document_sid" id="gen-doc-sid">
                            <input type="hidden" id="document_sid_for_validation">
                            <input type="hidden" id="auth_sign_sid" name="auth_sign_sid" value="0" />
                            <div class="message-action-btn">
                                <input type="button" value="Assign This Document" id="send-gen-doc" onclick="func_assign_generated_document();" class="submit-btn">
                                <!-- <button id="send-gen-doc" class="btn btn-success">Assign This Document</button> -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="upload_eev_document" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="uploaded_document_modal_title">Employment Eligibility Verification Document Upload</h4>
            </div>
            <div id="uploaded_document_modal_body" class="modal-body">
                <form id="form_upload_eev_document" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                    <div class="row" id="UploadVarificationSignDoc" style="display:none;">
                        <div class="col-xs-12">
                            <label>Browse Document <span class="staric">*</span></label>
                            <div class="upload-file invoice-fields">
                                <input style="height: 38px;" type="file" name="document" id="eev_document" required onchange="check_file('eev_document')">
                                <p id="name_eev_document"></p>
                                <a href="javascript:;" style="line-height: 38px; height: 38px;">Choose File</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                            <input type="hidden" name="sid" id="eev_sid" value="" />
                            <input type="hidden" name="document_type" id="data-document-type" value="" />
                            <input type="hidden" name="perform_action" value="upload_eev_document" />
                            <button type="submit" id="btn_eev_document" class="btn btn-success pull-right" style="display:none">Upload</button>
                        </div>
                    </div>
                    <br>
                    <div class="preview">

                    </div>
                </form>
            </div>
            <div id="uploaded_document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>

<!-- W4 Employer Section Modal -->
<div id="update_eemployer_section_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <?php
    $companySid = $this->session->userdata('logged_in')['company_detail']['sid'];
    $employerPrefill = getDataForEmployerPrefill($companySid, $EmployeeSid);
    ?>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="uploaded_document_modal_title">Form W4 Employer Section</h4>
            </div>
            <div id="uploaded_document_modal_body" class="modal-body">
                <form id="form_w4_employer_section" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Employer’s name <span class="cs-required">*</span></label>
                                    <input type="text" id="emp-name" name="emp_name" value="<?php echo $popup_emp_name != '' ? $popup_emp_name : $employerPrefill['CompanyName']; ?>" class="form-control" />
                                    <label id="emp-name-error" class="error"></label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Employer’s address <span class="cs-required">*</span></label>
                                    <input type="text" id="emp-address" name="emp_address" value="<?php echo $popup_emp_address != '' ? $popup_emp_address : $employerPrefill['Location_Address']; ?>" class="form-control" />
                                    <label id="emp-address-error" class="error"></label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>First date of employment <span class="cs-required">*</span></label>
                                    <input type="text" name="first_date_of_employment" id="emp-fdoe" value="<?php echo $popup_first_date_of_employment != '' ? $popup_first_date_of_employment : $employerPrefill['first_day_of_employment']; ?>" class="form-control first_date_of_employment" readonly />
                                    <label id="emp-fdoe-error" class="error"></label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Employer identification number (EIN) <span class="cs-required">*</span></label>
                                    <input type="text" id="emp-ein" name="EIN" value="<?php echo $popup_emp_identification_number != '' ? $popup_emp_identification_number : $employerPrefill['ssn']; ?>" class="form-control" />
                                    <label id="emp-ein-error" class="error"></label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <input type="hidden" name="company_sid" value="<?php echo $company_sid; ?>" />
                                <input type="hidden" name="user_sid" value="<?php echo $user_sid; ?>" />
                                <input type="hidden" name="user_type" value="<?php echo $user_type; ?>" />
                                <input type="hidden" name="perform_action" value="update_w4_employer_section" />
                                <!-- <button type="submit" class="btn btn-success pull-right">Update W4</button> -->
                                <button type="button" class="btn btn-success pull-right" id="update_employer_info_btn">
                                    Save Employer Section
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div id="uploaded_document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>
<?php //if ($i9_form['version'] && $i9_form['version'] != '2023') : 
?>
<!-- I9 Employer Section Modal -->
<div id="update_i9_employer_section_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <?php
    $pre_form = $i9_form;
    $first_name = $employer_first_name;
    $last_name = $employer_last_name;
    $email = $employer_email;
    $states = db_get_active_states(227);
    $signed_flag = isset($pre_form['user_consent']) && $pre_form['user_consent'] == 1 ? true : false;
    ?>

    <?php if (sizeof($pre_form)) { ?>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="uploaded_document_modal_title">Form I9 Employer Section</h4>
                </div>
                <div id="uploaded_document_modal_body" class="modal-body form-wrp">
                    <?php $this->load->view('form_i9/form_i9_employer_section'); ?>
                </div>
                <div id="uploaded_document_modal_footer" class="modal-footer">

                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?php //endif; 
?>

<!-- Preview Offer Letter Modal Start -->
<div id="show_uploaded_offer_letter_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="offer_letter_modal_title"></h4>
            </div>
            <div class="modal-body">
                <div id="offer-letter-iframe-container" style="display:none;">
                    <div class="embed-responsive embed-responsive-4by3">
                        <div id="offer-letter-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                </div>
                <div id="assigned_document_preview" style="display:none;">

                </div>
            </div>
            <div class="modal-footer" id="offer_letter_modal_footer">

            </div>
        </div>
    </div>
</div>
<!-- Preview Offer Letter Modal End -->

<!-- Generated Offer Letter Modal Start -->
<div id="model_generated_offer_letter" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="generated_offer_letter_title">Assign This Document</h4>
            </div>
            <div class="modal-body">
                <form method='post' id='assign-offer-letter-form' name='assign-offer-letter-form' action="<?php echo base_url('hr_documents_management/assign_offer_letter'); ?>">
                    <div class="col-lg-12">
                        <div class="form-group full-width">
                            <h4 id="gen_offer_letter_label"></h4>
                            <b>Please review this offer letter / pay plan and make any necessary modifications. Modifications will not affect the Original Offer letter / Pay Plan.</b>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group full-width">
                            <label>Letter Body<span class="hr-required red"> * </span></label>
                            <textarea style="padding:5px; height:200px; width:100%;" class="ckeditor" id="gen_offer_letter_description" name="letter_body"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group full-width">
                            <label>Authorized Management Signers</label>
                            <select id="gen_offer_letter_signers" name="gen_offer_letter_signers[]" multiple="">
                                <?php
                                if (sizeof($managers_list)) {
                                    foreach ($managers_list as $key => $value) {
                                        echo '<option value="' . ($value['sid']) . '">' . (remakeEmployeeName($value)) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="offer-letter-help-widget full-width form-group">
                            <div class="how-it-works-insturction">
                                <strong>How it's Works :</strong>
                                <p class="how-works-attr">1. Generate new Electronic Document</p>
                                <p class="how-works-attr">2. Enable Document Acknowledgment</p>
                                <p class="how-works-attr">3. Enable Electronic Signature</p>
                                <p class="how-works-attr">4. Insert multiple tags where applicable</p>
                                <p class="how-works-attr">5. Save the Document</p>
                            </div>

                            <div class="tags-arae">
                                <div class="col-md-12">
                                    <h5><strong>Company Information Tags:</strong></h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{company_name}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{company_address}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{company_phone}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{career_site_url}}">
                                    </div>
                                </div>
                            </div>

                            <div class="tags-arae">
                                <div class="col-md-12">
                                    <h5><strong>Employee / Applicant Tags :</strong></h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{first_name}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{last_name}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{email}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{job_title}}">
                                    </div>
                                </div>
                            </div>

                            <div class="tags-arae">
                                <div class="col-md-12">
                                    <h5><strong>Signature tags:</strong></h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{authorized_signature}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{authorized_signature_date}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{signature}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{signature_print_name}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{inital}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{sign_date}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{short_text}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{text}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{text_area}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{checkbox}}">
                                    </div>
                                </div>
                            </div>

                            <div class="tags-arae">
                                <div class="col-md-12">
                                    <h5><strong>Pay Plan / Offer Letter tags:</strong></h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{hourly_rate}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{hourly_technician}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{flat_rate_technician}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_salary}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group autoheight">
                                        <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_draw}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" id="perform_action" name="perform_action" value="assign_generated_offer_letter" />
                        <input type="hidden" name="offer_letter_type" id="gen-offer-letter-type">
                        <input type="hidden" name="offer_letter_sid" id="gen-offer-letter-sid">
                        <input type="hidden" name="user_sid" value="<?php echo $user_sid; ?>">
                        <input type="hidden" name="user_type" value="<?php echo $user_type; ?>">
                        <?php if ($user_type == 'applicant') { ?>
                            <input type="hidden" name="job_list_sid" value="<?php echo $job_list_sid; ?>">
                        <?php } ?>
                        <div class="message-action-btn">
                            <input type="button" value="Assign This Document" id="send-gen-offer-letter" onclick="func_assign_generated_offer_letter();" class="submit-btn">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- Generated Offer Letter Modal End -->

<!-- Preview Latest Document Modal Start -->
<div id="show_latest_preview_document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="latest_document_modal_title"></h4>
            </div>
            <div class="modal-body">
                <div id="latest-iframe-container" style="display:none;">
                    <div class="embed-responsive embed-responsive-4by3">
                        <div id="latest-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                </div>
                <div id="latest_assigned_document_preview" style="display:none;">

                </div>
            </div>
            <div class="modal-footer" id="latest_document_modal_footer">

            </div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->

<!-- Loader Start -->
<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;">
        </div>
    </div>
</div>
<!-- Loader End -->

<?php $this->load->view('form_i9/pop_up_info'); ?>
<?php $this->load->view('static-pages/e_signature_popup'); ?>
<?php $this->load->view('hr_documents_management/authorized_signature_popup'); ?>
<?php $this->load->view('hr_documents_management/show_document_history'); ?>
<?php $this->load->view('v1/forms/employer_state_form_section'); ?>

<style>
    #document_preview_div ol,
    #document_preview_div ul {
        padding-left: 15px !important;
    }
</style>

<link rel="stylesheet" type="text/css" href="https://printjs-4de6.kxcdn.com/print.min.css">
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/i9-form.js?v=1"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/mFileUploader/index.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script>
    //

    $('.modalShow').click(function(event) {

        event.preventDefault();
        var info_id = $(this).attr("src");
        var title_string = $(this).parent().text();
        var model_title = title_string.replace("*", "");
        if (info_id == "section_2_alien_number") {
            if ($('#alien_authorized_to_work').is(':checked')) {
                info_id = 'section_21_alien_number';
            }
        }
        var model_content = $('#' + info_id).html();
        var mymodal = $('#myPopupModal');
        mymodal.find('#popup-modal-title').text(model_title);
        mymodal.find('#feed_me_a_text').html(model_content);
        mymodal.modal('show');
    });

    var pdf_base64_data = '';
    $(document).ready(function() {
        $(document).on('click', '#close-popup-modal', function() {
            $('#myPopupModal').modal('toggle');
        });
        <?php if (sizeof($pre_form) > 0) { ?>
            var radio_val = '<?php echo $pre_form['section1_penalty_of_perjury'] ?>';
            radio_val != '' ? i9_manager.fill_part1_title(radio_val) : '';
            radio_val != '' ? i9_manager.fill_list_c(radio_val) : '';

            var access_level_plus = '<?php if ($this->session->userdata('logged_in')['employer_detail']['access_level_plus']) {
                                            echo 1;
                                        } else {
                                            echo 0;
                                        } ?>';
            var section1_validate = '<?php if (empty($pre_form['section1_last_name']) && empty($pre_form['user_consent'])) {
                                            echo 1;
                                        } else {
                                            echo 0;
                                        } ?>';
            var exist_e_signature_data = '<?php if (!empty($e_signature_data)) {
                                                echo true;
                                            } else {
                                                echo false;
                                            } ?>';
            var filler_e_signature_exist = '<?php if (!empty($filler_e_signature_data)) {
                                                echo true;
                                            } else {
                                                echo false;
                                            } ?>';

            $("#i9-form").validate({
                ignore: ":hidden:not(select)",
                rules: {
                    section2_firstday_of_emp_date: {
                        required: true
                    },
                    section2_today_date: {
                        required: true
                    },
                    section2_title_of_emp: {
                        required: true
                    },
                    section2_last_name_of_emp: {
                        required: true
                    },
                    section2_first_name_of_emp: {
                        required: true
                    },
                    section2_emp_business_name: {
                        required: true
                    },
                    section2_emp_business_address: {
                        required: true
                    },
                    section2_city_town: {
                        required: true
                    },
                    section2_state: {
                        required: true
                    },
                    section2_zip_code: {
                        required: true
                    },
                    section3_today_date: {
                        required: true
                    },
                    section3_name_of_emp: {
                        required: true
                    }
                },
                messages: {
                    section2_firstday_of_emp_date: {
                        required: 'Date of first day of employment is required.'
                    },
                    section2_today_date: {
                        required: 'Date of signature of employer or authorized representative is required.'
                    },
                    section2_title_of_emp: {
                        required: 'Title of employer or authorized representative is required.'
                    },
                    section2_last_name_of_emp: {
                        required: 'Last name of employer or authorized representative is required.'
                    },
                    section2_first_name_of_emp: {
                        required: 'First name of employer or authorized representative is required.'
                    },
                    section2_emp_business_name: {
                        required: 'Business name of employer or authorized representative is required.'
                    },
                    section2_emp_business_address: {
                        required: 'Business address of employer or authorized representative is required.'
                    },
                    section2_city_town: {
                        required: 'City/Town of employer or authorized representative is required.'
                    },
                    section2_state: {
                        required: 'State of employer or authorized representative is required.'
                    },
                    section2_zip_code: {
                        required: 'Zip Code of employer or authorized representative is required.'
                    },
                    section3_today_date: {
                        required: 'Date of signature of authorized representative is required.'
                    },
                    section3_name_of_emp: {
                        required: 'Full name of authorized representative is required.'
                    }
                },
                submitHandler: function(form) {
                    var list_a_document = $('input[name=section2_lista_part1_document_title]').val();
                    var list_b_document = $('#section2_listb_document_title').val();
                    var list_c_document = $('#section2_listc_document_title').val();

                    if (list_a_document == 'n_a') {
                        if (list_b_document == 'n_a' || list_c_document == 'n_a') {
                            alertify.alert("Warning", 'You must physically examine one document from List A OR a combination of one document from List B and one document from List C as listed on the "Lists of Acceptable Documents."');
                            return false;
                        }
                    }
                    form.submit();
                }
            });

            preFillLists('section2_lista_part1_document_title', section2_lista_part1_document_title);
            preFillLists('section2_lista_part1_issuing_authority', section2_lista_part1_issuing_authority);
            preFillLists('section2_lista_part2_document_title', section2_lista_part2_document_title);
            preFillLists('section2_lista_part2_issuing_authority', section2_lista_part2_issuing_authority);
            preFillLists('section2_lista_part3_document_title', section2_lista_part3_document_title);
            preFillLists('section2_lista_part3_issuing_authority', section2_lista_part3_issuing_authority);


            preFillLists('section2_listb_document_title', section2_listb_document_title);
            preFillLists('section2_listb_issuing_authority', section2_listb_issuing_authority);


            preFillLists('section2_listc_document_title', section2_listc_document_title);
            preFillLists('section2_listc_issuing_authority', section2_listc_issuing_authority);

        <?php } ?>
        $('.collapse').on('shown.bs.collapse', function() {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function() {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });

        $('.categories').select2({
            closeOnSelect: false,
            allowHtml: true,
            allowClear: true,
            // tags: true
        });
    });

    function preview_latest_generic_function(source) {
        var letter_type = $(source).attr('date-letter-type');
        var request_type = $(source).attr('data-on-action');
        var document_title = '';

        if (request_type == 'assigned') {
            document_title = 'Assigned Document';
        } else if (request_type == 'submitted') {
            document_title = 'Submitted Document';
        } else if (request_type == 'company') {
            document_title = 'Company Document';
        }

        if (letter_type == 'uploaded') {
            var preview_document = 1;
            var model_contant = '';
            var preview_iframe_url = '';
            var preview_image_url = '';
            var document_print_url = '';
            var document_download_url = '';

            var document_sid = $(source).attr('data-doc-sid');
            var file_s3_path = $(source).attr('data-preview-url');
            var file_s3_name = $(source).attr('data-s3-name');

            var file_extension = file_s3_name.substr(file_s3_name.lastIndexOf('.') + 1, file_s3_name.length);
            var document_file_name = file_s3_name.substr(0, file_s3_name.lastIndexOf('.'));
            var document_extension = file_extension.toLowerCase();


            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pdf';
                    break;
                case 'csv':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.csv';
                    break;
                case 'doc':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edoc&wdAccPdf=0';
                    break;
                case 'docx':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edocx&wdAccPdf=0';
                    break;
                case 'ppt':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.ppt';
                    break;
                case 'pptx':
                    dpreview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pptx';
                    break;
                case 'xls':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exls';
                    break;
                case 'xlsx':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exlsx';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    preview_document = 0;
                    preview_image_url = file_s3_path;
                    document_print_url = '<?php echo base_url("hr_documents_management/print_s3_image"); ?>' + '/' + file_s3_name;
                    break;
                default: //using google docs
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    break;
            }

            document_download_url = '<?php echo base_url("hr_documents_management/download_upload_document"); ?>' + '/' + file_s3_name;

            $('#show_latest_preview_document_modal').modal('show');
            $("#latest_document_modal_title").html(document_title);
            $('#latest-iframe-container').show();

            if (preview_document == 1) {
                model_contant = $("<iframe />")
                    .attr("id", "latest_document_iframe")
                    .attr("class", "uploaded-file-preview")
                    .attr("src", preview_iframe_url);
            } else {
                model_contant = $("<img />")
                    .attr("id", "latest_image_tag")
                    .attr("class", "img-responsive")
                    .css("margin-left", "auto")
                    .css("margin-right", "auto")
                    .attr("src", preview_image_url);
            }


            $("#latest-iframe-holder").append(model_contant);
            //
            if (preview_document == 1) {
                loadIframe(
                    preview_iframe_url,
                    '#latest_document_iframe',
                    true
                );
            }

            footer_content = '<a target="_blank" class="btn btn-success" href="' + document_print_url + '">Print</a>';
            footer_content += '<a target="_blank" class="btn btn-success" href="' + document_download_url + '">Download</a>';
            $("#latest_document_modal_footer").html(footer_content);
        } else {
            var request_sid = $(source).attr('data-doc-sid');
            var request_from = $(source).attr('data-from');

            $.ajax({
                'url': '<?php echo base_url('hr_documents_management/get_document_content'); ?>' + '/' + request_sid + '/' + request_type + '/' + request_from,
                'type': 'GET',
                success: function(contant) {
                    var obj = jQuery.parseJSON(contant);
                    var requested_content = obj.requested_content;
                    var document_view = obj.document_view;
                    var form_input_data = obj.form_input_data;
                    var is_iframe_preview = obj.is_iframe_preview;

                    var print_url = '<?php echo base_url('hr_documents_management/perform_action_on_document_content'); ?>' + '/' + request_sid + '/' + request_type + '/' + request_from + '/print';
                    var download_url = '<?php echo base_url('hr_documents_management/perform_action_on_document_content'); ?>' + '/' + request_sid + '/' + request_type + '/' + request_from + '/download';

                    $('#show_latest_preview_document_modal').modal('show');
                    $("#latest_document_modal_title").html(document_title);

                    if (request_type == 'submitted') {
                        if (is_iframe_preview == 1) {
                            var model_contant = '';

                            $('#latest-iframe-container').show();
                            $('#latest_assigned_document_preview').hide();

                            var model_contant = $("<iframe />")
                                .attr("id", "latest_document_iframe")
                                .attr("class", "uploaded-file-preview")
                                .attr("src", requested_content);

                            $("#latest-iframe-holder").append(model_contant);

                            loadIframe(
                                requested_content,
                                '#latest_document_iframe',
                                true
                            );
                        } else {
                            $('#latest-iframe-container').hide();
                            $('#latest_assigned_document_preview').show();
                            $("#latest_assigned_document_preview").html(document_view);

                            form_input_data = Object.entries(form_input_data);

                            if ($('#latest_assigned_document_preview').find('select').length >= 0) {
                                $('#latest_assigned_document_preview').find('select').map(function(i) {
                                    //
                                    $(this).addClass('js_select_document');
                                    $(this).prop('name', 'selectDD' + i);
                                });
                            }

                            $.each(form_input_data, function(key, input_value) {
                                // for fillables
                                if (input_value[0] === "supervisor") {
                                    $("input.js_supervisor").val(input_value[1]);
                                } else if (input_value[0] === "department") {
                                    $("input.js_department").val(input_value[1]);
                                } else if (input_value[0] === "last_work_date") {
                                    $("input.js_last_work_date").val(input_value[1]);
                                } else if (input_value[0] === "reason_to_leave_company") {
                                    $("textarea.js_reason_to_leave_company").val(input_value[1]);
                                } else if (input_value[0] === "forwarding_information") {
                                    $("textarea.js_forwarding_information").val(input_value[1]);
                                } else if (input_value[0] === "employee_name") {
                                    $("input.js_employee_name").val(input_value[1]);
                                } else if (input_value[0] === "employee_job_title") {
                                    $("input.js_employee_job_title").val(input_value[1]);
                                } else if (input_value[0] === "is_termination_voluntary") {
                                    $('input.js_is_termination_voluntary[value="' + (input_value[1]) + '"]').prop("checked", true);
                                } else if (input_value[0] === "property_returned") {
                                    $('input.js_property_returned[value="' + (input_value[1]) + '"]').prop("checked", true);
                                } else if (input_value[0] === "reemploying") {
                                    $('input.js_reemploying[value="' + (input_value[1]) + '"]').prop("checked", true);
                                } else if (input_value[0] === "date_of_occurrence") {
                                    $("input.js_date_of_occurrence").val(input_value[1]);
                                } else if (input_value[0] === "summary_of_violation") {
                                    $("textarea.js_summary_of_violation").val(input_value[1]);
                                } else if (input_value[0] === "summary_of_corrective_plan") {
                                    $("textarea.js_summary_of_corrective_plan").val(input_value[1]);
                                }  else if (input_value[0] === "follow_up_dates") {
                                    $("textarea.js_follow_up_dates").val(input_value[1]);
                                }  else if (input_value[0] === "counselling_form_fields_textarea") {
                                    $("textarea.js_counselling_form_fields_textarea").removeClass("hidden");
                                    $("textarea.js_counselling_form_fields_textarea").val(input_value[1]);
                                }  else if (input_value[0] === "counselling_form_fields") {
                                    input_value[1].map(function(v){
                                        $('input.js_counselling_form_fields[value="'+(v)+'"]').prop("checked", true);
                                    });
                                } else if (input_value[0] === "q1") {
                                    $("textarea.js_q1").val(input_value[1]);
                                } else if (input_value[0] === "employee_number") {
                                    $("input.js_employee_number").val(input_value[1]);
                                } else if (input_value[0] === "q2") {
                                    $("textarea.js_q2").val(input_value[1]);
                                } else if (input_value[0] === "q3") {
                                    $("textarea.js_q3").val(input_value[1]);
                                } else if (input_value[0] === "q4") {
                                    $("textarea.js_q4").val(input_value[1]);
                                } else if (input_value[0] === "q5") {
                                    $("textarea.js_q5").val(input_value[1]);
                                } else if (input_value[0] == 'signature_person_name') {
                                    var input_field_id = input_value[0];
                                    var input_field_val = input_value[1];
                                    $('#' + input_field_id).val(input_field_val);
                                    $('.js_' + input_field_id).val(input_field_val);
                                } else {
                                    var input_field_id = input_value[0] + '_id';
                                    var input_field_val = input_value[1];
                                    var input_type = $('#' + input_field_id).attr('data-type');

                                    if (input_type == 'text') {
                                        $('#' + input_field_id).val(input_field_val);
                                        $('#' + input_field_id).prop('disabled', true);
                                    } else if (input_type == 'checkbox') {
                                        if (input_field_val == 'yes') {
                                            $('#' + input_field_id).prop('checked', true);;
                                        }
                                        $('#' + input_field_id).prop('disabled', true);

                                    } else if (input_type == 'textarea') {
                                        $('#' + input_field_id).hide();
                                        $('#' + input_field_id + '_sec').show();
                                        $('#' + input_field_id + '_sec').html(input_field_val);
                                    } else if (input_value[0].match(/select/) !== -1) {
                                        if (input_value[1] != null) {
                                            let cc = get_select_box_value(input_value[0], input_value[1]);
                                            $(`select.js_select_document[name="${input_value[0]}"]`).hide(0);
                                            $(`select.js_select_document[name="${input_value[0]}"]`).after(`<strong style="font-size: 20px;">${cc}</strong>`)
                                        }
                                    }
                                }
                            });
                        }
                    } else {

                        model_contant = requested_content;
                        $('#latest-iframe-container').hide();
                        $('#latest_assigned_document_preview').show();
                        $("#latest_assigned_document_preview").html(document_view);
                    }

                    footer_content = '<a target="_blank" class="btn btn-success" href="' + print_url + '">Print</a>';
                    footer_content += '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                    $("#latest_document_modal_footer").html(footer_content);
                }
            });
        }
    }

    function get_select_box_value(select_box_name, select_box_val) {
        var data = select_box_val;
        let cc = '';

        if (select_box_val.indexOf(',') > -1) {
            data = select_box_val.split(',');
        }


        if ($.isArray(data)) {
            let modify_string = '';
            $.each(data, function(key, value) {
                if (modify_string == '') {
                    modify_string = ' ' + $(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                } else {
                    modify_string = modify_string + ', ' + $(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                }
            });
            cc = modify_string;
        } else {
            cc = $(`select.js_select_document[name="${select_box_name}"] option[value="${select_box_val}"]`).text();
        }

        return cc;
    }

    function check_iframe_content(url) {
        try {
            if ($("#offer_letter-pop-up-iframe").contents().find("body").text() == '') {
                $("#offer_letter-pop-up-iframe").prop('src', url);
                setTimeout(function() {
                    check_iframe_content(url);
                }, 3000);
            }
        } catch (err) {
            console.log('iframe preview load successfully')
        }
    }

    $('#show_latest_preview_document_modal').on('hidden.bs.modal', function() {
        $("#latest-iframe-holder").html('');
        $("#latest_document_iframe").remove();
        $("#latest_image_tag").remove();
        $('#latest-iframe-container').hide();
        $('#latest_assigned_document_preview').html('');
        $('#latest_assigned_document_preview').hide();
    });

    $('.manage_authorized_signature').on('click', function() {
        var document_auth_sid = $(this).attr('data-auth-sid');
        var auth_signature = $(this).attr('data-auth-signature');

        $('#authorized_document_sid').val(document_auth_sid);

        var default_signature = $('#default_e_signature').attr('src');
        $('#drawn_authorized_signature').val(default_signature);

        $('#assign_manager_section').hide();
        $('#edit_authorized_signature_section').hide();

        $('#authorized_signature_section').show();
        $('#default_authorized_signature_section').show();

        $('#add_authorized_e_signature_button').hide();
        $('#save_authorized_e_signature_button').show();

        $('#authorized_e_Signature_Modal').modal('show');

        if (auth_signature != '' || auth_signature != undefined) {
            $("#defaultAuthorizedDocument").show();
            $("#selected_auth_e_signature").attr('src', auth_signature);
        } else {
            $("#defaultAuthorizedDocument").hide();
        }
    });

    $("#is_offer_letter").on('click', function() {
        if ($("#is_offer_letter").is(":checked")) {
            $("#categories_section").hide();
            $("#createcategories").select2("val", "");
            $("#visible_manual_doc_to_payroll").attr('checked', false);
            $("#manual_doc_payroll_section").hide();
        } else {
            $("#categories_section").show();
            $("#manual_doc_payroll_section").show();
        }
    });

    $("#update_is_offer_letter").on('click', function() {
        if ($("#update_is_offer_letter").is(":checked")) {
            $("#update_categories_section").hide();
            $("#updatecategories").select2("val", "");
            $("#update_manual_doc_to_payroll").attr('checked', false);
            $("#update_manual_doc_payroll_section").hide();
        } else {
            $("#update_categories_section").show();
            $("#update_manual_doc_payroll_section").show();
        }
    });

    $('.date_picker').datepicker({
        dateFormat: 'mm-dd-yy',
        setDate: new Date(),
        maxDate: new Date,
        minDate: new Date()
    });

    $('.date_picker2').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+100"
        //yearRange: "<?php //echo STARTING_DATE_LIMIT; 
                        ?>"
    });

    <?php if (sizeof($pre_form) > 0) { ?>
        var option_val = '<?php echo sizeof($pre_form) > 0 ? $pre_form['section1_penalty_of_perjury'] : '' ?>';

        // A Lists
        var section2_lista_part1_document_title = "<?php echo sizeof($pre_form) > 0 ? $pre_form['section2_lista_part1_document_title'] : '' ?>";
        var section2_lista_part1_issuing_authority = "<?php echo sizeof($pre_form) > 0 ? $pre_form['section2_lista_part1_issuing_authority'] : '' ?>";
        var section2_lista_part2_document_title = "<?php echo sizeof($pre_form) > 0 ? $pre_form['section2_lista_part2_document_title'] : '' ?>";
        var section2_lista_part2_issuing_authority = "<?php echo sizeof($pre_form) > 0 ? $pre_form['section2_lista_part2_issuing_authority'] : '' ?>";
        var section2_lista_part3_document_title = "<?php echo sizeof($pre_form) > 0 ? $pre_form['section2_lista_part3_document_title'] : '' ?>";
        var section2_lista_part3_issuing_authority = "<?php echo sizeof($pre_form) > 0 ? $pre_form['section2_lista_part3_issuing_authority'] : '' ?>";

        // B Lists
        var section2_listb_document_title = "<?php echo sizeof($pre_form) > 0 ? $pre_form['section2_listb_document_title'] : '' ?>";
        var section2_listb_auth_select_input = "<?php echo sizeof($pre_form) > 0 ? $pre_form['listb_auth_select_input'] : '' ?>";
        var section2_listb_issuing_authority = "<?php echo sizeof($pre_form) > 0 ? $pre_form['section2_listb_issuing_authority'] : '' ?>";

        // C Lists
        var section2_listc_document_title = "<?php echo sizeof($pre_form) > 0 ? $pre_form['section2_listc_document_title'] : '' ?>";
        var section2_listc_auth_select_input = "<?php echo sizeof($pre_form) > 0 ? $pre_form['listc_auth_select_input'] : '' ?>";
        var section2_listc_issuing_authority = "<?php echo sizeof($pre_form) > 0 ? $pre_form['section2_listc_issuing_authority'] : '' ?>";
        var lista_part1_doc_select_input = "<?php echo sizeof($pre_form) > 0 ? $pre_form['lista_part1_doc_select_input'] : '' ?>";
        var lista_part1_issuing_select_input = "<?php echo sizeof($pre_form) > 0 ? $pre_form['lista_part1_issuing_select_input'] : '' ?>";
        var lista_part2_doc_select_input = "<?php echo sizeof($pre_form) > 0 ? $pre_form['lista_part2_doc_select_input'] : '' ?>";
        var lista_part2_issuing_select_input = "<?php echo sizeof($pre_form) > 0 ? $pre_form['lista_part2_issuing_select_input'] : '' ?>";
        var lista_part3_doc_select_input = "<?php echo sizeof($pre_form) > 0 ? $pre_form['lista_part3_doc_select_input'] : '' ?>";
        var lista_part3_issuing_select_input = "<?php echo sizeof($pre_form) > 0 ? $pre_form['lista_part3_issuing_select_input'] : '' ?>";


        $('#section2_lista_part1_document_title').on('change', function() {
            var title = $(this).val();
            i9_manager.fill_part1_authority(title);
        });

        $('#section2_lista_part3_document_title').on('change', function() {
            var title = $(this).val();
            i9_manager.fill_part3_auth(title);
        });

        $('#section2_listc_document_title').on('change', function() {
            var title = $(this).val();
            i9_manager.fill_list_c_auth(title, section2_listc_issuing_authority);
        });

        $('#section2_listb_document_title').on('change', function() {
            var title = $(this).val();
            i9_manager.fill_list_b_auth(title, section2_listb_issuing_authority);
        });
        option_val != '' && option_val != null ? i9_manager.fill_part1_title(option_val) : '';
        option_val != '' && option_val != null ? i9_manager.fill_list_c(option_val) : '';
        i9_manager.fill_listb();

        setTimeout(function() {
            if (section2_listb_auth_select_input != '' && section2_listb_auth_select_input == 'input') {
                $('input[name="listb-auth-select-input"][value="input"]').prop('checked', true);
                $('#list_b_auth_text_val').val(section2_listb_issuing_authority);
                $('#list_b_auth_select').hide(0);
                $('#list_b_auth_text').show(0);
            } else {
                $('input[name="listb-auth-select-input"][value="select"]').prop('checked', true);
                $('#section2_listb_issuing_authority option[value="' + (section2_listb_issuing_authority) + '"]').prop('selected', true);
                $('#list_b_auth_text').hide(0);
            }
        }, 3000);

        setTimeout(function() {
            if (section2_listc_auth_select_input != '' && section2_listc_auth_select_input == 'input') {
                $('input[name="listc-auth-select-input"][value="input"]').prop('checked', true);
                $('#list_c_auth_text_val').val(section2_listc_issuing_authority);
                $('#list_c_auth_select').hide(0);
                $('#list_c_auth_text').show(0);
            } else {
                $('input[name="listc-auth-select-input"][value="select"]').prop('checked', true);
                $('#section2_listc_issuing_authority option[value="' + (section2_listc_issuing_authority) + '"]').prop('selected', true);
                $('#list_c_auth_text').hide(0);
            }
        }, 3000);

        setTimeout(function() {
            if (lista_part1_doc_select_input != '' && lista_part1_doc_select_input == 'input') {
                $('input[name="lista_part1_doc_select_input"][value="input"]').prop('checked', true);
                $('#lista_part1_doc_text_val').val(section2_lista_part1_document_title);
                $('#lista_part1_doc_select').hide(0);
                $('#lista_part1_doc_text').show(0);
            } else {
                $('input[name="lista_part1_doc_select_input"][value="select"]').prop('checked', true);
                $('#section2_lista_part1_document_title option[value="' + (section2_lista_part1_document_title) + '"]').prop('selected', true);
                $('#lista_part1_doc_text').hide(0);
            }
        }, 3000);

        setTimeout(function() {
            if (lista_part1_issuing_select_input != '' && lista_part1_issuing_select_input == 'input') {
                $('input[name="lista_part1_issuing_select_input"][value="input"]').prop('checked', true);
                $('#lista_part1_issuing_text_val').val(section2_lista_part1_issuing_authority);
                $('#lista_part1_issuing_select').hide(0);
                $('#lista_part1_issuing_text').show(0);
            } else {
                $('input[name="lista_part1_issuing_select_input"][value="select"]').prop('checked', true);
                $('#section2_lista_part1_issuing_authority option[value="' + (section2_lista_part1_issuing_authority) + '"]').prop('selected', true);
                $('#lista_part1_issuing_text').hide(0);
            }
        }, 3000);

        setTimeout(function() {
            if (lista_part2_doc_select_input != '' && lista_part2_doc_select_input == 'input') {
                $('input[name="lista_part2_doc_select_input"][value="input"]').prop('checked', true);
                $('#lista_part2_doc_text_val').val(section2_lista_part2_document_title);
                $('#lista_part2_doc_select').hide(0);
                $('#lista_part2_doc_text').show(0);
            } else {
                $('input[name="lista_part2_doc_select_input"][value="select"]').prop('checked', true);
                $('#section2_lista_part2_document_title option[value="' + (section2_lista_part2_document_title) + '"]').prop('selected', true);
                $('#lista_part2_doc_text').hide(0);
            }
        }, 3000);

        setTimeout(function() {
            if (lista_part2_issuing_select_input != '' && lista_part2_issuing_select_input == 'input') {
                $('input[name="lista_part2_issuing_select_input"][value="input"]').prop('checked', true);
                $('#lista_part2_issuing_text_val').val(section2_lista_part2_issuing_authority);
                $('#lista_part2_issuing_select').hide(0);
                $('#lista_part2_issuing_text').show(0);
            } else {
                $('input[name="lista_part2_issuing_select_input"][value="select"]').prop('checked', true);
                $('#section2_lista_part2_issuing_authority option[value="' + (section2_lista_part2_issuing_authority) + '"]').prop('selected', true);
                $('#lista_part2_issuing_text').hide(0);
            }
        }, 3000);

        setTimeout(function() {
            if (lista_part3_doc_select_input != '' && lista_part3_doc_select_input == 'input') {
                $('input[name="lista_part3_doc_select_input"][value="input"]').prop('checked', true);
                $('#lista_part3_doc_text_val').val(section2_lista_part3_document_title);
                $('#lista_part3_doc_select').hide(0);
                $('#lista_part3_doc_text').show(0);
            } else {
                $('input[name="lista_part3_doc_select_input"][value="select"]').prop('checked', true);
                $('#section2_lista_part3_document_title option[value="' + (section2_lista_part3_document_title) + '"]').prop('selected', true);
                $('#lista_part3_doc_text').hide(0);
            }
        }, 3000);

        setTimeout(function() {
            if (lista_part3_issuing_select_input != '' && lista_part3_issuing_select_input == 'input') {
                $('input[name="lista_part3_issuing_select_input"][value="input"]').prop('checked', true);
                $('#lista_part3_issuing_text_val').val(section2_lista_part3_issuing_authority);
                $('#lista_part3_issuing_select').hide(0);
                $('#lista_part3_issuing_text').show(0);
            } else {
                $('input[name="lista_part3_issuing_select_input"][value="select"]').prop('checked', true);
                $('#section2_lista_part3_issuing_authority option[value="' + (section2_lista_part3_issuing_authority) + '"]').prop('selected', true);
                $('#lista_part3_issuing_text').hide(0);
            }
        }, 3000);

        function func_save_e_signature() {
            var section2_signature_exist = $('#section2_emp_sign').val();
            var section3_signature_exist = $('#section3_emp_sign').val();

            if (section2_signature_exist == "") {
                alertify.alert("Warning", 'Please Add Employer Or Authorized Representative!');
                return false;
            }

            if (section3_signature_exist == "") {
                alertify.alert("Warning", 'Please Add Authorized Representative!');
                return false;
            }

            if ($('#i9-form').valid()) {
                alertify.confirm(
                    'Are you Sure?',
                    'Are you sure you want to Consent And Accept Electronic Signature Agreement?',
                    function() {
                        $('#i9-form').submit();
                    },
                    function() {
                        alertify.alert("Warning", 'Cancelled!');
                    }).set('labels', {
                    ok: 'I Consent and Accept!',
                    cancel: 'Cancel'
                });
            }
        }

    <?php } ?>

    function preFillLists(id, value) {
        if (value != 'n_a' && value != '' && value != null) {
            $('#' + id).val(value);
            $('#' + id).change();
        }
    }

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    function show_uploaded_offer_letter(source) {
        var offer_letter_preview_url = $(source).attr('data-preview-url');
        var offer_letter_name = $(source).attr('data-file-name');
        var offer_letter_print_url = $(source).attr('data-print-url');
        var offer_letter_download_url = $(source).attr('data-download-url');

        $('#show_uploaded_offer_letter_modal').modal('show');
        $("#offer_letter_modal_title").html(offer_letter_name);

        $('#offer-letter-iframe-container').show();
        var offer_letter_content = $("<iframe />")
            .attr("id", "offer_letter_iframe")
            .attr("class", "uploaded-file-preview")
            .attr("src", offer_letter_preview_url);

        $("#offer-letter-iframe-holder").append(offer_letter_content);

        footer_content = '<a target="_blank" class="btn btn-success" href="' + offer_letter_print_url + '">Print</a>';
        footer_content += '<a target="_blank" class="btn btn-success" href="' + offer_letter_download_url + '">Download</a>';
        $("#offer_letter_modal_footer").html(footer_content);
    }

    $('#show_uploaded_offer_letter_modal').on('hidden.bs.modal', function() {
        $("#offer-letter-iframe-holder").html('');
        $("#offer_letter_iframe").remove();
        $('#offer-letter-iframe-container').hide();
        $('#assigned_document_preview').html('');
        $('#assigned_document_preview').hide();
    });

    function func_assign_uploaded_offer_letter(type, document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Reassign the Offer Letter? <br> <strong>Note:</strong>This action will revoke any assigned Offer letter / Pay plan.',
            function() {
                $('#form_assign_document_' + type + '_' + document_sid).submit();
            },
            function() {
                alertify.alert('Process Cancelled!');
            });
    }

    var companyOfferLetters = <?= json_encode($company_offer_letters); ?>;

    function assign_generated_offer_letter(source, assign_type = 'assign') {
        var offer_letter_title = $(source).attr('data-title');
        var offer_letter_description = $(source).attr('data-description');
        var offer_letter_type = $(source).attr('data-document-type');
        var offer_letter_sid = $(source).attr('data-document-sid');

        $('#document_sid_for_validation').val(offer_letter_sid);

        title = 'Modify and Re-Assign This Offer Letter / Pay Plan';
        button_title = 'Re-Assign This Letter / Pay Plan';
        offer_letter_label = "Are you sure you want to Re-Assign this offer letter / pay plan [<b>" + offer_letter_title + "</b>] <br> <?php echo ucwords($user_type); ?> will be required to re-submit this document";

        if (assign_type == 'assign') {
            title = 'Modify and Assign This Offer Letter / Pay Plan';
            button_title = 'Assign This Letter / Pay Plan';
            offer_letter_label = "Are you sure you want to assign this offer letter / pay plan : [<b>" + offer_letter_title + "</b>]";
        }

        $('#model_generated_offer_letter').modal('toggle');

        CKEDITOR.instances['gen_offer_letter_description'].setData(offer_letter_description);

        $('#gen-offer-letter-type').val(offer_letter_type);
        $('#gen-offer-letter-sid').val(offer_letter_sid);
        $('#send-gen-offer-letter').val(button_title);
        $('#generated_offer_letter_title').html(title);
        $('#gen_offer_letter_label').html(offer_letter_label);
        $('#gen_offer_letter_signers').select2({
            closeOnSelect: false
        });
        //
        var y = getOfferLetter(offer_letter_sid)['signers'];
        y = y != null ? y.split(',') : y;
        //
        $('#gen_offer_letter_signers').select2('val', y);

    }

    function getOfferLetter(idd) {
        var
            i = 0,
            il = companyOfferLetters.length;
        //
        for (i; i < il; i++) {
            if (companyOfferLetters[i]['sid'] == idd) return companyOfferLetters[i];
        }
        return {};
    }

    function func_assign_generated_offer_letter() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Reassign the Offer Letter? <br> <strong>Note:</strong>This action will revoke any assigned Offer letter / Pay plan.',
            function() {
                $('#assign-offer-letter-form').submit();
            },
            function() {
                alertify.alert('Process Canceled!');
            });
    }

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 38));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();
            if (val == 'document' || val == 'eev_document') {
                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "PDF" && ext != "DOCX" && ext != "DOC") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc) allowed!</p>');
                    $('#btn_' + val).css("display", "none");
                } else {
                    $('#btn_' + val).css("display", "block");
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    function open_uploaded_model() {
        $('#uploaded_document_modal').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });

        $('#manual_document').mFileUploader({
            fileLimit: '2MB', // Default is '2MB', Use -1 for no limit (Optional)
            allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv'], //(Optional)
            text: 'Click / Drag to upload', // (Optional)
            onSuccess: (file, event) => {}, // file will the uploaded file object and event will be the actual event  (Optional)
            onError: (errorCode, event) => {}, // errorCode will either 'size' or 'type' and event will be the actual event  (Optional)
            placeholderImage: '' // Default is empty ('') but can be set any image  (Optional)
        });

        $('#confidentialSelectedEmployees').select2({
            closeOnSelect: false
        });
    }

    // $('#uploaded_document_modal').on('hidden.bs.modal', function () {
    //     $(".csUploadAreaImageWrapper").remove();
    //     $(".csUploadArea").remove();
    // });

    $('#add_form_upload_document').on('submit', function(e) {
        e.preventDefault();
        var upload_file = $('#manual_document').mFileUploader('get');

        var upload_file_title = $('#add_upload_manual_doc_title').val();

        if ($.isEmptyObject(upload_file)) {
            alertify.alert('ERROR!', 'Please select a file to upload.');
            return false;
        } else if (upload_file.hasError == true) {
            alertify.alert('ERROR!', 'Please select a valid file format.');
            return false;
        } else if (upload_file_title == '') {
            alertify.alert('ERROR!', 'Please enter the document title.');
            return false;
        } else {
            $('#add_form_upload_document_loader').show();

            var form_data = new FormData();
            form_data.append('document', upload_file);
            form_data.append('company_sid', '<?php echo $company_sid; ?>');
            form_data.append('user_sid', '<?php echo $user_sid; ?>');
            form_data.append('user_type', '<?php echo $user_type; ?>');
            form_data.append('document_title', upload_file_title);

            $.ajax({
                url: '<?= base_url('hr_documents_management/upload_file_ajax_handler') ?>',
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(data) {
                    $('#loader').hide();
                    var obj = jQuery.parseJSON(data);
                    var upload_status = obj.upload_status;
                    var document_url = obj.document_url;
                    var document_name = obj.original_name;
                    var document_extension = obj.extension;
                    if (upload_status == 'success') {
                        $('#add_manual_doc_url').val(document_url);
                        $('#add_manual_doc_name').val(document_name);
                        $('#add_manual_doc_extension').val(document_extension);
                        $('#add_form_upload_document').unbind('submit').submit();
                    } else {
                        var reason = obj.reason;
                        alertify.alert('ERROR!', reason);
                        $('#add_form_upload_document_loader').hide();
                    }

                },
                error: function() {}
            });
        }


    });

    function no_action_req_edit_document_model(source) {

        var document_sid = $(source).attr('data-download-sid');
        var document_title = $(source).attr('data-document-title');
        var update_accessible = $(source).attr('data-update-accessible');
        var is_offer_letter = $(source).attr('is-offer-letter');
        var is_payroll_visible = $(source).attr('is-payroll-visible');
        var print_type = $(source).attr('data-print-type');
        var categories = $(source).attr('data-categories');
        var assign_date = $(source).attr('assign-date');
        var sign_date = $(source).attr('sign-date');
        var user_type = '<?php echo $user_type; ?>';
        var user_sid = '<?php echo $user_sid; ?>';

        let s3Name = $(source).data('print-url');

        if (document_sid.length > 0) {
            $('#no_action_document_modal_title').html(document_title);
            $("#not_action_req_document_sid").val(document_sid);
            $("#update_manual_doc_title").val(document_title);
            $("#update_accessible").prop("checked", update_accessible);
            $("#update_is_offer_letter").prop("checked", is_offer_letter);
            $("#update_manual_doc_to_payroll").prop("checked", is_payroll_visible);
            $("#previous_update_accessible").val(update_accessible);
            $("#doc_assign_date").val(assign_date);
            $("#doc_sign_date").val(sign_date);

            if (is_offer_letter) {
                $("#update_categories_section").hide();
                $("#update_manual_doc_payroll_section").hide();
                $("#updatecategories").select2("val", "");
            } else {
                categories = JSON.parse(categories);
                $('#previous_categories').val(categories);
                $('#updatecategories').val(categories).trigger('change');
            }

            $('#no_action_document_modal').modal("toggle");

            $('#reupload_manual_document').mFileUploader({
                fileLimit: -1, // Default is '2MB', Use -1 for no limit (Optional)
                allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv'], //(Optional)
                placeholderImage: s3Name // Default is empty ('') but can be set any image  (Optional)
            });
        }
    }

    // $('#no_action_document_modal').on('hidden.bs.modal', function () {
    //     $(".csUploadAreaImageWrapper").remove();
    //     $(".csUploadArea").remove();
    // });

    $('#update_form_upload_document').on('submit', function(e) {
        e.preventDefault();
        //
        var upload_file = $('#reupload_manual_document').mFileUploader('get');
        var update_title = $("#update_manual_doc_title").val();

        if (update_title == "" || update_title == null) {
            alertify.alert('ERROR!', 'Document Title is Required, Please Add Title.');
            return false;
        } else if (!$.isEmptyObject(upload_file) && upload_file.hasError == true) {
            alertify.alert('ERROR!', 'Please select a valid file format.');
            return false;
        } else if (!$.isEmptyObject(upload_file) && upload_file.hasError == false) {
            $('#update_form_upload_document_loader').show();

            var form_data = new FormData();
            form_data.append('document', upload_file);
            form_data.append('company_sid', '<?php echo $company_sid; ?>');
            form_data.append('user_sid', '<?php echo $user_sid; ?>');
            form_data.append('user_type', '<?php echo $user_type; ?>');
            form_data.append('document_title', update_title);

            $.ajax({
                url: '<?= base_url('hr_documents_management/upload_file_ajax_handler') ?>',
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(data) {


                    var obj = jQuery.parseJSON(data);
                    var upload_status = obj.upload_status;
                    var document_url = obj.document_url;
                    var document_name = obj.original_name;
                    var document_extension = obj.extension;

                    if (upload_status == 'success') {
                        $('#update_manual_doc_url').val(document_url);
                        $('#update_manual_doc_name').val(document_name);
                        $('#update_manual_doc_extension').val(document_extension);
                        $('#update_form_upload_document').unbind('submit').submit();
                    } else {
                        $('#document_loader').hide();
                        var reason = obj.reason;
                        alertify.alert('ERROR!', reason);
                        $('#update_form_upload_document_loader').hide();
                    }
                },
                error: function() {
                    var reason = obj.reason;
                    alertify.alert('ERROR!', reason);
                    $('#update_form_upload_document_loader').hide();
                }
            });
        } else {
            $('#update_form_upload_document').unbind('submit').submit();
        }
    });

    function func_document_revoked() {
        alertify.alert('Document Manage Error!', 'You can not Manage Revoked Document');
    }

    function func_remove_document(type, document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function() {
                $('#form_remove_document_' + type + '_' + document_sid).submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function func_assign_document(type, document_sid, title = 'Assign This Document', message = 'Are you sure you want to assign this document?') {
        alertify.confirm(
            title,
            message,
            function() {
                $('#form_assign_document_' + type + '_' + document_sid).submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function func_assign_generated_document() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function() {
                $('#register-form').submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function func_remove_w4() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function() {
                $('#form_remove_w4').submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function func_assign_w4() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function() {
                $('#form_assign_w4').submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function func_remove_i9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function() {
                $('#form_remove_i9').submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function func_assign_i9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function() {
                $('#form_assign_i9').submit();
            },
            function() {
                alertify.alert("Warning", 'Canceled!');
            }).set('labels', {
            ok: "Yes",
            cancel: "No"
        });
    }

    function func_remove_w9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function() {
                $('#form_remove_w9').submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function func_assign_w9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function() {
                $('#form_assign_w9').submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function func_remove_w9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function() {
                $('#form_remove_w9').submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function func_remove_EEOC() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function() {
                $('#form_remove_EEOC').submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function func_assign_EEOC() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function() {
                $('#form_assign_EEOC').submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var footer_content_print = '';
        var iframe_url = '';
        var base_url = '<?= base_url() ?>';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    iframe_url = document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_content_print = '<a target="_blank" class="btn btn-success" href="https://docs.google.com/gview?url=' + document_download_url + '&embedded=true">Print</a>';
                    break;
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_content_print = '<a target="_blank" class="btn btn-success" href="https://view.officeapps.live.com/op/embed.aspx?src=' + document_download_url + '&embedded=true">Print</a>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    break;
                default:
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

            footer_content = '<a target="_blank" class="btn btn-success" href="' + base_url + "hr_documents_management/download_upload_document/" + document_file_name + '">Download</a>';
            footer_content += footer_content_print;
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        // $('#document_modal_footer').html(footer_content);
        $('#document_modal .modal-footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function() {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
    }

    function fLaunchModalGen(source, assign_type = 'assign') {
        var document_title = $(source).attr('data-title');
        var document_description = $(source).attr('data-description');
        var document_type = $(source).attr('data-document-type');
        var document_sid = $(source).attr('data-document-sid');
        $('#document_sid_for_validation').val(document_sid);

        title = 'Modify and Re-Assign This Document';
        document_label = "Are you sure you want to Re-Assign this document [<b>" + document_title + "</b>] <br> <?php echo ucwords($user_type); ?> will be required to re-submit this document";
        button_title = 'Re-Assign this Document';

        if (assign_type == 'assign') {
            title = 'Modify and Assign This Document';
            document_label = "Are you sure you want to assign this document: [<b>" + document_title + "</b>]";
            button_title = 'Assign This Document';
        }

        $('#model_generated_doc').modal('toggle');
        // $('#gen-doc-title').val(document_title);
        CKEDITOR.instances.gen_doc_description.setData(document_description);
        //$('#gen-doc-description').val(document_description);
        $('#gen-doc-type').val(document_type);
        $('#gen-doc-sid').val(document_sid);
        $('#send-gen-doc').val(button_title);
        $('#generated_document_title').html(title);
        $('#gen_document_label').html(document_label);
    }

    function func_get_generated_document_preview(document_sid, source, fetch_data, assigned_id) {
        var my_request;
        my_request = $.ajax({
            'url': '<?php echo base_url('hr_documents_management/ajax_responder'); ?>',
            'type': 'POST',
            'data': {
                'perform_action': 'get_generated_document_preview',
                'document_sid': document_sid,
                'user_type': '<?php echo $user_type; ?>',
                'user_sid': <?php echo $user_sid; ?>,
                'source': source,
                'fetch_data': fetch_data
            }
        });

        my_request.done(function(response) {

            if (assigned_id != undefined) {
                var base_url = '<?php echo base_url() ?>';
                footer_content = '<a target="_blank" class="btn btn-success" href="' + base_url + 'hr_documents_management/print_generated_and_offer_later/assigned/offer_letter/' + assigned_id + '/download">Download</a>';
                footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + base_url + 'hr_documents_management/print_generated_and_offer_later/assigned/offer_letter/' + assigned_id + '" >Print</a>';


                $('#popupmodalbody').html(response);
                $('#popupmodalbodyfooter').html(footer_content);
                $('#popupmodalbodyfooter').append(footer_print_btn);
                $('#popupmodallabel').html('Preview Hr Document');
                $('#popupmodal .modal-dialog').css('width', '60%');
                $('#popupmodal').modal('toggle');
            } else {
                $.ajax({
                    'url': '<?php echo base_url('hr_documents_management/get_print_url'); ?>',
                    'type': 'POST',
                    'data': {
                        'request_type': fetch_data,
                        'document_type': source,
                        'document_sid': document_sid
                    },
                    success: function(urls) {
                        var obj = jQuery.parseJSON(urls);
                        var print_url = obj.print_url;
                        var download_url = obj.download_url;
                        footer_content = '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                        footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + print_url + '" >Print</a>';


                        $('#popupmodalbody').html(response);
                        $('#popupmodalbodyfooter').html(footer_content);
                        $('#popupmodalbodyfooter').append(footer_print_btn);
                        $('#popupmodallabel').html('Preview Hr Document');
                        $('#popupmodal .modal-dialog').css('width', '60%');
                        $('#popupmodal').modal('toggle');


                    }
                });
            }
        });
    }

    function func_get_generated_document_history_preview(document_sid, source = 'original', fetch_data = 'original', history_sid = '') {
        var my_request;
        my_request = $.ajax({
            'url': '<?php echo base_url('hr_documents_management/ajax_responder'); ?>',
            'type': 'POST',
            'data': {
                'perform_action': 'get_generated_document_preview',
                'document_sid': document_sid,
                'user_type': '<?php echo $user_type; ?>',
                'user_sid': <?php echo $user_sid; ?>,
                'source': source,
                'fetch_data': fetch_data,
                'history_sid': history_sid
            }
        });

        my_request.done(function(response) {
            $('#popupmodalbody').html(response);
            $('#popupmodallabel').html('Preview Hr Document');
            $('#popupmodal .modal-dialog').css('width', '60%');
            $('#popupmodal').modal('toggle');
        });
    }

    function preview_document_model(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var pcheck = $(source).attr('data-pcheck');

        var document_file_name = $(source).attr('data-file-name');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';
        var document_sid = $(source).attr('data-download-sid');
        var print_type = $(source).attr('data-print-type');
        var user_type = '<?php echo $user_type; ?>';
        var user_sid = '<?php echo $user_sid; ?>';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png': //             iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;

                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    break;
                default: //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        if (pcheck == undefined) {
            $.ajax({
                'url': '<?php echo base_url('hr_documents_management/get_print_url'); ?>',
                'type': 'POST',
                'data': {
                    'request_type': print_type,
                    'document_type': 'MS',
                    'document_sid': document_sid
                },
                success: function(urls) {
                    var obj = jQuery.parseJSON(urls);
                    var print_url = obj.print_url;
                    var download_url = obj.download_url;
                    footer_content = '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                    footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + print_url + '" >Print</a>';

                    $('#document_modal_body').html(modal_content);
                    $('#document_modal_footer').html(footer_content);
                    $('#document_modal_footer').append(footer_print_btn);
                    $('#document_modal_title').html(document_title);
                    $('#document_modal').modal("toggle");
                    $('#document_modal').on("shown.bs.modal", function() {

                        if (iframe_url != '') {
                            $('#preview_iframe').attr('src', iframe_url);
                            //
                            loadIframe(iframe_url, '#preview_iframe', true);
                        }
                    });
                }
            });
        } else {
            $('#document_modal_body').html(modal_content);
            $('#document_modal_title').html(document_title);
            $('#document_modal').modal("toggle");
            $('#document_modal').on("shown.bs.modal", function() {

                if (iframe_url != '') {
                    $('#preview_iframe').attr('src', iframe_url);
                }
            });
        }
    }

    function print_my_content(pdfData) {
        printJS({
            printable: pdf_base64_data,
            type: 'pdf',
            base64: true
        })
    }

    function preview_submitted_generated_document(source) {
        var document_sid = $(source).attr('data-print-id');
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var pcheck = $(source).attr('data-pcheck');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    iframe_url = document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        if (pcheck == undefined) {
            $.ajax({
                'url': '<?php echo base_url('hr_documents_management/get_print_url'); ?>',
                'type': 'POST',
                'data': {
                    'request_type': 'submitted',
                    'document_type': 'generated',
                    'document_sid': document_sid
                },
                success: function(urls) {
                    var obj = jQuery.parseJSON(urls);
                    var print_url = obj.print_url;
                    var download_url = obj.download_url;
                    footer_content = '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                    footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + print_url + '" >Print</a>';

                    $('#document_modal_body').html(modal_content);
                    $('#document_modal_footer').html(footer_content);
                    $('#document_modal_footer').append(footer_print_btn);
                    $('#document_modal_title').html(document_title);
                    $('#document_modal').modal("toggle");
                    $('#document_modal').on("shown.bs.modal", function() {

                        if (iframe_url != '') {
                            $('#preview_iframe').attr('src', iframe_url);
                        }
                    });
                }
            });
        } else {
            $('#document_modal_body').html(modal_content);
            $('#document_modal_title').html(document_title);
            $('#document_modal').modal("toggle");
            $('#document_modal').on("shown.bs.modal", function() {

                if (iframe_url != '') {
                    $('#preview_iframe').attr('src', iframe_url);
                }
            });
        }
    }

    function generated_document_original_preview(document_sid) {
        var my_request;
        my_request = $.ajax({
            'url': '<?php echo base_url('hr_documents_management/preview_generated_doc'); ?>',
            'type': 'POST',
            'data': {
                'document_sid': document_sid,
                'user_type': '<?php echo $user_type; ?>',
                'user_sid': '<?php echo $user_sid; ?>'
            }
        });

        my_request.done(function(response) {

            $.ajax({
                'url': '<?php echo base_url('hr_documents_management/get_print_url'); ?>',
                'type': 'POST',
                'data': {
                    'request_type': 'assigned',
                    'document_type': 'generated',
                    'document_sid': document_sid
                },
                success: function(urls) {
                    var obj = jQuery.parseJSON(urls);
                    var print_url = obj.print_url;
                    var download_url = obj.download_url;
                    footer_content = '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                    footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + print_url + '" >Print</a>';
                    $('#document_modal_body').html(response);
                    $('#document_modal_footer').html(footer_content);
                    $('#document_modal_footer').append(footer_print_btn);
                    $('#document_modal_title').html('Preview Hr Document');
                    $('#document_modal').modal("toggle");
                }
            });
        });
    }

    function view_original_uploaded_doc(source) {
        var url_segment_original = $(source).attr('data-print-url');
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var document_sid = $(source).attr('data-document-sid');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'ppt':
                case 'pptx':
                case 'xls':
                case 'xlsx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    footer_print_btn = '<a target="_blank" href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original/generated'); ?>' + '/' + document_sid + '" class="btn btn-success">Print</a>';
                    break;
                default: //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $.ajax({
            'url': '<?php echo base_url('hr_documents_management/get_print_url'); ?>',
            'type': 'POST',
            'data': {
                'request_type': 'original',
                'document_type': 'MS',
                'document_sid': document_sid
            },
            success: function(urls) {
                var obj = jQuery.parseJSON(urls);
                var print_url = obj.print_url;
                var download_url = obj.download_url;
                footer_content = '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + print_url + '" >Print</a>';

                $('#document_modal_body').html(modal_content);
                $('#document_modal_footer').html(footer_content);
                $('#document_modal_footer').append(footer_print_btn);
                $('#document_modal_title').html(document_title);
                $('#document_modal').modal("toggle");
                $('#document_modal').on("shown.bs.modal", function() {

                    if (iframe_url != '') {
                        $('#preview_iframe').attr('src', iframe_url);
                        //
                        loadIframe(iframe_url, '#preview_iframe', true);
                    }
                });
            }
        });
    }

    function view_original_uploaded_manual_doc(source) {
        var url_segment_original = $(source).attr('data-print-url');
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var document_sid = $(source).attr('data-document-sid');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    footer_print_btn = '<a target="_blank" href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original/generated'); ?>' + '/' + document_sid + '" class="btn btn-success">Print</a>';
                    break;
                default: //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $.ajax({
            'url': '<?php echo base_url('hr_documents_management/get_print_url'); ?>',
            'type': 'POST',
            'data': {
                'request_type': 'original',
                'document_type': 'DS',
                'document_sid': document_sid
            },
            success: function(urls) {
                var obj = jQuery.parseJSON(urls);
                var print_url = obj.print_url;
                var download_url = obj.download_url;
                footer_content = '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + print_url + '" >Print</a>';

                $('#document_modal_body').html(modal_content);
                $('#document_modal_footer').html(footer_content);
                $('#document_modal_footer').append(footer_print_btn);
                $('#document_modal_title').html(document_title);
                $('#document_modal').modal("toggle");
                $('#document_modal').on("shown.bs.modal", function() {

                    if (iframe_url != '') {
                        $('#preview_iframe').attr('src', iframe_url);
                    }
                });
            }
        });
    }

    function view_original_generated_document(document_sid, doc_flag = 'generated', doc_title = 'Preview Generated Document') {
        var my_request;
        my_request = $.ajax({
            'url': '<?php echo base_url('hr_documents_management/ajax_responder'); ?>',
            'type': 'POST',
            'data': {
                'perform_action': 'get_generated_document_preview',
                'document_sid': document_sid,
                'source': doc_flag,
                'fetch_data': 'original'
            }
        });

        my_request.done(function(response) {
            footer_content = '<a target="_blank" class="btn btn-success" href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original'); ?>' + '/' + doc_flag + '/' + document_sid + '/download">Download</a>';
            footer_print_btn = '<a target="_blank" href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original'); ?>' + '/' + doc_flag + '/' + document_sid + '" class="btn btn-success">Print</a>';

            $('#document_modal_body').html(response);
            $('#document_modal_footer').html(footer_content);
            $('#document_modal_footer').append(footer_print_btn);
            $('#document_modal_title').html(doc_title);
            $('#document_modal').modal("toggle");
        });
    }

    function func_assign_document_group(group_sid, user_type, user_sid, group_name) {
        var user_name = "<?php echo $user_info['first_name']; ?> <?php echo $user_info['last_name']; ?>";
        alertify.confirm(
            'Confirm Document Group Assignment?',
            'Are you sure you want to assign <strong><i>' + group_name + '</i></strong> group to <strong><i>' + user_name + '</i></strong> ?',
            function() {
                var myurl = "<?php echo base_url('hr_documents_management/ajax_assign_group_2_applicant'); ?>" + '/' + group_sid + "/" + user_type + "/" + user_sid;

                $.ajax({
                    type: "GET",
                    url: myurl,
                    async: false,
                    success: function(data) {
                        $("#btn_group_" + group_sid).removeClass("btn btn-primary btn-block btn-sm");
                        $("#btn_group_" + group_sid).addClass("btn btn-success btn-block btn-sm");
                        $("#btn_group_" + group_sid).text("Group Assigned");

                        alertify.success('Group Assigned Successfully');
                        location.reload();
                    },
                    error: function(data) {

                    }
                });
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    }

    function func_revoke_document_group(group_sid, user_type, user_sid, group_name) {
        var user_name = "<?php echo $user_info['first_name']; ?> <?php echo $user_info['last_name']; ?>";
        alertify.confirm(
            'Confirm Document Group Revoke?',
            'Are you sure you want to revoke <strong><i>' + group_name + '</i></strong> group ?',
            function() {
                var myurl = "<?php echo base_url('hr_documents_management/ajax_revoke_document_group'); ?>" + '/' + group_sid + "/" + user_type + "/" + user_sid;

                $.ajax({
                    type: "GET",
                    url: myurl,
                    async: false,
                    success: function(data) {
                        alertify.alert('SUCCESS!', "Group Revoked Successfully", function() {
                            window.location.reload();
                        });
                    },
                    error: function(data) {

                    }
                });
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    }

    function func_reassign_document_group(group_sid, user_type, user_sid, group_name) {
        var user_name = "<?php echo $user_info['first_name']; ?> <?php echo $user_info['last_name']; ?>";
        alertify.confirm(
            'Confirm Document Group Reassign?',
            'Are you sure you want to reassign <strong><i>' + group_name + '</i></strong> group to <strong><i>' + user_name + '</i></strong> ?',
            function() {
                var myurl = "<?php echo base_url('hr_documents_management/ajax_reassign_document_group'); ?>" + '/' + group_sid + "/" + user_type + "/" + user_sid;

                $.ajax({
                    type: "GET",
                    url: myurl,
                    async: false,
                    success: function(data) {
                        alertify.alert('SUCCESS!', "Group Reassigned Successfully", function() {
                            window.location.reload();
                        });
                    },
                    error: function(data) {

                    }
                });
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    }

    function preview_eev_document_model(source) {
        var yoy_here = '';
        var purpose = $(source).attr('data-purpose');
        //
        console.log(purpose)
        if (purpose != undefined && purpose == "upload") {
            $("#UploadVarificationSignDoc").show();
        } else {
            $("#UploadVarificationSignDoc").hide();
        }
        //
        var document_file_name = $(source).attr('data-file-name');
        $("#data-document-type").val($(source).attr('data-document-type'));
        $("#eev_sid").val($(source).attr('data-document-sid'));
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var modal_content = '';
        var footer_content = '';
        var footer_print_btn = '';
        var footer_print_url = '';
        var baseurl = '<?= base_url() ?>';
        $('#upload_eev_document .modal-footer').html('');
        $('#upload_eev_document .modal-footer').append('');
        if (document_preview_url != undefined && document_preview_url != '') {
            var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'xls':
                case 'ppt':
                case 'pptx':
                case 'xlsx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    footer_print_url = iframe_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    break;
                default: //using google docs
                    // iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    iframe_url = document_preview_url;
                    footer_print_url = 'https://docs.google.com/gview?url=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }
            footer_content = '<a target="_blank" class="btn btn-success" href="' + baseurl + "hr_documents_management/download_upload_document/" + document_file_name + '">Download</a>';
            footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + footer_print_url + '" >Print</a>';

            $('#upload_eev_document .modal-footer').html(footer_content);
            $('#upload_eev_document .modal-footer').append(footer_print_btn);

        }
        $('#upload_eev_document .preview').html(modal_content);
        $('#upload_eev_document').modal('show');
    }

    $("#btn_eev_document").click(function() {
        $("#form_upload_eev_document").submit();
        $(this).html('<i aria-hidden="true" class="fa fa-refresh fa-spin fa-3x fa-fw"></i>');
        $(this).attr("disabled", true);
    });

    function func_archive_uploaded_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to archive this document?',
            function() {
                $('#form_archive_hr_document_' + document_sid).submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function func_unarchive_uploaded_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to activate this document?',
            function() {
                $('#form_activate_hr_document_' + document_sid).submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    $('.doc_date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+50",
    }).val();

    $('.first_date_of_employment').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+50",
    }).val();

    $(document).on('click', '.edit_employer_section', function() {
        $('#update_eemployer_section_modal').modal('show');
    });

    $(document).on('click', '.i9_edit_employer_section', function() {
        $('#update_i9_employer_section_modal').modal('show');
    });

    $("#update_employer_info_btn").on('click', function() {
        var employe_name = $("#emp-name").val();
        var employe_address = $("#emp-address").val();
        var employe_FDOE = $("#emp-fdoe").val();
        var employe_EIN = $("#emp-ein").val();
        var flag = 0;

        if (employe_name == '') {
            $("#emp-name-error").text('Employer’s name is required.');
            flag = 1;
        } else {
            $("#emp-name-error").text('');
        }


        if (employe_address == '') {
            $("#emp-address-error").text('Employer’s address is required.');
            flag = 1;
        } else {
            $("#emp-address-error").text('');
        }

        if (employe_FDOE == '') {
            $("#emp-fdoe-error").text('First date of employment is required.');
            flag = 1;
        } else {
            $("#emp-fdoe-error").text('');
        }

        if (employe_EIN == '') {
            $("#emp-ein-error").text('Employer identification number is required.');
            flag = 1;
        } else {
            $("#emp-ein-error").text('');
        }

        if (flag == 1) {
            return false;
        } else {
            $("#update_employer_info_btn").attr('type', 'submit');
            $('#update_employer_info_btn').click();
        }

    });

    $('#update_eemployer_section_modal').on('hidden.bs.modal', function() {
        $("#emp-name-error").text('');
        $("#emp-address-error").text('');
        $("#emp-fdoe-error").text('');
        $("#emp-ein-error").text('');
    });
</script>

<style>
    #cke_3_top {
        /*display: none !important;*/
    }

    #cke_3_contents {
        height: 651px !important;
    }

    #assigned_document_preview ul li {
        list-style-type: disc;
    }

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

    #document_modal_body {
        background-color: #ffffff;
    }
</style>

<script>
    $(function() {


        $('table.js-verification-table tbody tr').map(function(i) {

            if ($(this).find('img').length != 0) {
                let
                    dn = $(this).find('td:nth-child(1)').text(),
                    aon = $(this).find('td:nth-child(3)').find('.text-center').html(),

                    btn = $(this).find('td:nth-child(4)').find('a[data-toggle="modal"]').clone().addClass('btn-sm btn-block'),
                    w4_btn = '',
                    i9_btn = '',
                    db = '';
                //
                if (dn.trim() == 'EEOC FORM') {
                    if (<?= $eeo_form_info['status'] ?? 0; ?> != 1) {
                        return;
                    }
                }
                //
                if ($(this).find('td:nth-child(4)').find('button[data-document-type="i9"]').length !== 0) {
                    btn = $(this).find('td:nth-child(4)').find('button[data-document-type="i9"]').clone().addClass('btn-sm btn-block');
                }

                if (btn.length == 0) {
                    if ($(this).find('td:nth-child(4)').find('a[data-form-type="i9"]').length !== 0) {
                        btn = $(this).find('td:nth-child(4)').find('a[data-form-type="i9"]').clone().addClass('btn-sm btn-block');
                    }
                }


                if ($(this).find('td:nth-child(4)').find('a[data-form-type="w4_edit_btn"]').length !== 0) {
                    w4_btn = $(this).find('td:nth-child(4)').find('a[data-form-type="w4_edit_btn"]').clone();

                    //w4_btn = w4_btn + '<a class="btn btn-success"  href="#">Send Document I9</a>';
                }

                if ($(this).find('td:nth-child(4)').find('a[data-form-type="i9_edit_btn"]').length !== 0) {
                    i9_btn = $(this).find('td:nth-child(4)').find('a[data-form-type="i9_edit_btn"]').clone();
                }



                if ($(this).find('img').attr('src').match(/on/)) {
                    // Completed document
                    // if($('#signed_doc_details #collapse_completed-1').length == 0) {
                    //     $('.panel-body').find('b.js-error').remove();
                    //     $('#signed_doc_details .panel-body').append(`<div class="row"><div class="col-xs-12"><div class="panel panel-default hr-documents-tab-content"><div class="panel-heading"><h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed-1"><span class="glyphicon glyphicon-plus"></span>Employment Eligibility Verification Document<div class="pull-right total-records"><b>&nbsp;Total: <span class="js-cdi">0</span> </b></div></a></h4></div><div id="collapse_completed-1" class="panel-collapse collapse"><div class="table-responsive full-width"><table class="table table-plane"><thead><tr><th scope="column" class="col-lg-8">Document Name</th><th scope="column" class="col-lg-2 text-right">Actions</th><th scope="column" class="col-lg-2 text-center">&nbsp;</th></tr></thead><tbody></tbody></table>`);
                    // }
                    // $('#signed_doc_details #collapse_completed-1 tbody').prepend(`<tr><td class="col-lg-8">${dn}<br /><strong>Assigned on: </strong>${aon}</td><td class="col-lg-2 clv-${i}"></td><td class="col-lg-2 blv-${i}"></td></tr>`);
                    // $('.js-cd').text(
                    //     parseInt($('.js-cd').text()) + 1
                    // );
                    // $('.js-cdi').text(
                    //     parseInt($('.js-cdi').text()) + 1
                    // );
                    // $('#signed_doc_details').find('td[colspan="7"]').parent().remove();
                } else {
                    // Uncompleted Document
                    if ($('#in_complete_doc_details #collapse_ncompleted-1').length == 0) {
                        $('.panel-body').find('b.js-error').remove();
                        $('#in_complete_doc_details .panel-body').append(`<br /><div class="row"><div class="col-xs-12"><div class="panel panel-default hr-documents-tab-content"><div class="panel-heading"><h4 class="panel-title"><a class="accordion-toggle open_not_completed_varification_doc" data-toggle="collapse" data-parent="#accordion" href="#collapse_ncompleted-1"><span class="glyphicon glyphicon-plus"></span>Employment Eligibility Verification Document<div class="pull-right total-records"><b>&nbsp;Total: <span class="js-ncdi">0</span> </b></div></a></h4></div><div id="collapse_ncompleted-1" class="panel-collapse collapse in"><div class="table-responsive full-width"><table class="table table-plane"><thead><tr><th scope="column" class="col-lg-8">Document Name</th><th scope="column" class="col-lg-2 text-right">Actions</th><th scope="column" class="col-lg-2 text-center">&nbsp;</th></tr></thead><tbody></tbody></table>`);
                    }
                    $('#in_complete_doc_details  #collapse_ncompleted-1 tbody').prepend(`<tr><td class="col-lg-8">${dn}<br /><strong>Assigned on: </strong>${aon}</td><td class="col-lg-2 clv-${i}"></td><td class="col-lg-2 blv-${i}"></td></tr>`);
                    $('.js-ncd').text(
                        parseInt($('.js-ncd').text()) + 1
                    );
                    $('.js-ncdi').text(
                        parseInt($('.js-ncdi').text()) + 1
                    );
                    $('#in_complete_doc_details').find('td[colspan="7"]').parent().remove();
                    if ($('.js-uncompleted-docs tbody tr').length == 0) $('.js-uncompleted-docs').remove();

                }
                //
                $('.clv-' + (i) + '').html(btn);
                $('.clv-' + (i) + '').append(w4_btn);
                //
                var user_type = '<?php echo $user_type; ?>';
                //
                if (dn.trim() == "W4 Fillable" && user_type == "applicant") {
                    $('.clv-' + (i) + '').append('<a class="btn btn-success btn-sm btn-block js-send-document-notification" data-toggle="popover" data-placement="left" data-content="Send document by email to complete without going through OnBoarding process." data-original-title="Send Document By Email" data-type="w4">Send Document</a>');
                }

                $('.clv-' + (i) + '').append(i9_btn);
                // console.log(dn.trim())
                if (dn.trim() == "I9 Fillable" && user_type == "applicant") {
                    $('.clv-' + (i) + '').append('<a class="btn btn-success btn-sm btn-block js-send-document-notification" data-toggle="popover" data-placement="left" data-content="Send document by email to complete without going through OnBoarding process." data-original-title="Send Document By Email" data-type="I9">Send Document</a>');
                }


                //
            }
        });
        //
        $('.js-send-document-notification').popover({
            trigger: 'hover',
            html: true
        });
    })
</script>


<script>
    // Convert a document to pay plan
    function convertToPayPlan(
        documentId,
        documentType
    ) {
        // Confirm 
        alertify.confirm('Do you really want to convert this document to Pay Plan?',
            function() {
                // Show loader
                loader('show');
                // Send ajax request to convert
                $.post("<?= base_url('hr_documents_management/convert_document_to_payplan'); ?>", {
                    documentId: documentId,
                    documentType: documentType
                }, function(resp) {
                    if (resp.Status === false) {
                        loader('hide');
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    //
                    alertify.alert('SUCCESS!', resp.Response, function() {
                        window.location.reload();
                    });
                });
                // Show message
                // Reload page
            }
        ).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    }

    //
    function loader(doShow) {
        if (doShow == true || doShow == 'show' || doShow == undefined) $('#js-loader').fadeIn();
        else $('#js-loader').fadeOut();
    }


    function func_assign_offer_letter(sid) {
        loader(show);
    }

    $(function() {
        $('#myPopupModal').on('hidden.bs.modal', function() {
            $('body').addClass('modal-open');
        });
    })
</script>

<style>
    .modal {
        overflow-y: auto !important;
    }
</style>

<script>
    $(function() {

        var completed = true;
        $('#js-search-bar').keyup(SearchDocumentProcessStart)

        //
        function SearchDocumentProcessStart(e) {
            //
            if ($(this).val().trim().toLowerCase().replace(/\s+/g, '') == '') {
                //
                $('.js-search-header, .js-search-row').show();
                //
                return;
            }
            //
            var s = new RegExp($(this).val().trim().toLowerCase().replace(/\s+/g, ''), 'gi');
            //
            //
            if (completed === false) {
                setTimeout(function() {
                    SearchDocument(e.key, s);
                }, 700);
                return;
            }

            setTimeout(function() {
                SearchDocument(e.key, s);
            }, 200);
        }

        //
        function SearchDocument(
            key,
            s
        ) {
            //
            if ($.inArray(key, ['ArrowUp', 'ArrowDown', 'ArrowRight', 'ArrowLeft', 'PageDown', 'PageUp']) !== -1) return;
            //
            completed = false;
            //
            var f = false;
            //
            $('.js-search-header').map(function() {
                f = false;
                $(this).find('tr.js-search-row').map(function() {
                    if ($(this).find('td:nth-child(1)').text().trim().toLowerCase().replace(/\s+/g, '').match(s) === null) {
                        $(this).hide(0);
                    } else {
                        $(this).show(0);
                        f = $(this).parent().parent().parent().parent().prop('id');
                    }
                });
                if (f) $(this).find('a[href="#' + (f) + '"][aria-expanded!="true"]').click();
            });
            setTimeout(function() {
                completed = true;
            }, 500);
        }

    })
</script>
<?php
//
$adn = '';
$adt = '';
//
if (!empty($assigned_documents_history)) {
    foreach ($assigned_documents_history as $document) {
        //
        $row = '
            <tr>
                <td class="col-lg-3">
                    ' . $document['document_title'] . '<br>
                </td>
                <td class="col-lg-1 text-center">';
        $doc_type = '';

        if (!empty($document['document_extension'])) {
            $doc_type = strtolower($document['document_extension']);
        }
        if ($doc_type == 'pdf') {
            $row .= '<i aria-hidden="true" class="fa fa-2x fa-file-pdf-o"></i>';
        } else if (in_array($doc_type, ['ppt', 'pptx'])) {
            $row .= '<i aria-hidden="true" class="fa fa-2x fa-file-powerpoint-o"></i>';
        } else if (in_array($doc_type, ['doc', 'docx'])) {
            $row .= ' <i aria-hidden="true" class="fa fa-2x fa-file-o"></i>';
        } else if (in_array($doc_type, ['xlsx'])) {
            $row .= ' <i aria-hidden="true" class="fa fa-2x fa-file-excel-o"></i>';
        } else if ($doc_type == '') {
            $row .= ' <i aria-hidden="true" class="fa fa-2x fa-file-text"></i>';
        }
        $row .= '
                </td>
                <td class="col-lg-2 text-center">';
        if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
            $row .= '<i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                        <div class="text-center">';
            $row .= reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
            $row .= '</div>';
        } else {
            $row .= '    <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>';
        }
        $row .= '</td>
                <td class="col-lg-2 text-center">';
        if (!$document['acknowledgment_required']) {
            $row .= '<b>N/A</b>';
        } elseif (isset($document['acknowledged_date']) && $document['acknowledged_date'] != '0000-00-00 00:00:00') {
            if ($document['acknowledged'] == 0) {
                $row .= '<i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>';
            } else {
                $row .= '<i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>';
            }
            $row .= '<div class="text-center">';
            // echo date_format(new DateTime($document['acknowledged_date']), 'M d Y h:m a').'
            $row .= reset_datetime(array('datetime' => $document['acknowledged_date'], '_this' => $this));

            $row .= '</div>';
        } elseif ($document['user_consent'] == 1) {
            $row .= '<i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                        <div class="text-center">';
            // echo date_format(new DateTime($document['signature_timestamp']), 'M d Y h:m a').'
            $row .= reset_datetime(array('datetime' => $document['signature_timestamp'], '_this' => $this));

            $row .= '</div>';
        } else {
            $row .= '<i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>';
        }
        $row .= '</td>
                <td class="col-lg-2 text-center">';
        if (!$document['download_required']) {
            $row .= '<b>N/A</b>';
        } elseif (isset($document['downloaded_date']) && $document['downloaded_date'] != '0000-00-00 00:00:00') {
            if ($document['downloaded'] == 0) {
                $row .= '<i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>';
            } else {
                $row .= '<i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>';
            }
            $row .= '<div class="text-center">';
            // echo date_format(new DateTime($document['downloaded_date']), 'M d Y h:m a').'
            $row .= reset_datetime(array('datetime' => $document['downloaded_date'], '_this' => $this));

            $row .= '</div>';
        } elseif ($document['user_consent'] == 1) {
            $row .= '<i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                        <div class="text-center">';
            // echo date_format(new DateTime($document['signature_timestamp']), 'M d Y h:m a').'
            $row .= reset_datetime(array('datetime' => $document['signature_timestamp'], '_this' => $this));

            $row .= '</div>';
        } else {
            $row .= '<i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>';
        }
        $row .= '</td>
                <td class="col-lg-2 text-center">';
        if (!$document['signature_required']) {
            $row .=  '<b>N/A</b>';
        } elseif (isset($document['uploaded_date']) && $document['uploaded_date'] != '0000-00-00 00:00:00') {
            if ($document['uploaded'] == 0) {
                $row .= '<i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>';
            } else {
                $row .= '<i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>';
            }
            $row .= '<div class="text-center">';
            // echo date_format(new DateTime($document['uploaded_date']), 'M d Y h:m a').'
            $row .= reset_datetime(array('datetime' => $document['uploaded_date'], '_this' => $this));

            $row .= '</div>';
        } elseif ($document['user_consent'] == 1) {
            $row .= '<i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                        <div class="text-center">';
            // echo date_format(new DateTime($document['signature_timestamp']), 'M d Y h:m a').'
            $row .= reset_datetime(array('datetime' => $document['signature_timestamp'], '_this' => $this));

            $row .= '</div>';
        } else {
            $row .= '<i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>';
        }
        $row .= '</td>
                <td class="col-lg-1 text-center">';
        if ($document['document_type'] == 'hybrid_document') {
            $row .= '<button
                            class="btn btn-success btn-sm btn-block js-hybrid-preview"
                            data-id="' . ($document['sid']) . '"
                            data-document="assigned_history"
                            data-type="document"
                        >
                        Preview Assigned
                        </button>';
            if ($document_all_permission) {
                $row .= '<button
                                class="btn btn-success btn-sm btn-block ' . ($document['submitted_description'] != null ? 'js-hybrid-preview' : 'disabled') . '>"
                                data-id="' . ($document['sid']) . '"
                                data-document="assigned_history"
                                data-type="document"
                            >
                            Preview submitted
                            </button>';
            }
        } else if ($document['document_type'] == 'uploaded') {
            $row .= '<button
                            class="btn btn-success btn-sm btn-block"
                            onclick="fLaunchModal(this);"
                            data-preview-url="' . (AWS_S3_BUCKET_URL . $document['document_s3_name']) . '"
                            data-download-url="' . (AWS_S3_BUCKET_URL . $document['document_s3_name']) . '"
                            data-file-name="' . ($document['document_original_name']) . '"
                            data-document-title="' . ($document['document_original_name']) . '">
                            Preview Assigned
                        </button>';
            if ($document_all_permission) {
                $row .= '<button
                                class="btn btn-success btn-sm btn-block"
                                onclick="fLaunchModal(this);"
                                data-preview-url="' . (AWS_S3_BUCKET_URL . $document['uploaded_file']) . '"
                                data-download-url="' . (AWS_S3_BUCKET_URL . $document['uploaded_file']) . '"
                                data-file-name="' . $document['uploaded_file'] . '"
                                data-document-title="' . $document['uploaded_file'] . '" ' . (!$document['uploaded'] ? 'disabled' : '') . '>
                                Preview Submitted
                            </button>';
            }
        } else {
            $row .= '<button
                            onclick="func_get_generated_document_history_preview(' . ($document['document_sid']) . ', \'generated\', \'history\', ' . ($document['sid']) . ');"
                            class="btn btn-success btn-sm btn-block">
                            Preview Assigned
                        </button>';
            if ($document_all_permission) {
                $row .= '<button class="btn btn-success btn-sm btn-block"
                                        onclick="preview_latest_generic_function(this);"
                                        date-letter-type="generated"
                                        data-doc-sid="' . ($document['sid']) . '"
                                        data-on-action="submitted"
                                        data-from="assigned_document_history" ' . ($document['user_consent'] == 0 ? 'disabled' : '') . '>
                                        Preview Submitted
                                    </button>';
            }
        }
        $row .= '</td>
            </tr>';

        //
        if ($document['assign_type'] != 'none') $adt .= $row;
        else $adn .= $row;
    }
}
?>


<script>
    $('.jsTab').hide();
    //
    let
        assignedHistory = <?= json_encode($assigned_documents_history); ?>,
        adt = <?= json_encode($adt) ?>,
        adn = <?= json_encode($adn) ?>;

    if (adn == '') adn = ` <tr><td colspan="7" class="col-lg-12 text-center"><b>No History Available</b></td></tr>`;
    if (adt == '') adt = ` <tr><td colspan="7" class="col-lg-12 text-center"><b>No History Available</b></td></tr>`;

    //
    if (assignedHistory.length !== 0) {
        $('.jsDDTotal').text(assignedHistory.length);
        $('.jsDDTotal').closest('.panel-heading').addClass('btn-success');
    }

    //
    $('#jsTimedDocuments tbody').html(adt);
    $('.jsDDNT').text(
        <?= json_encode($adt) ?> == '' ? "0" : $('#jsTimedDocuments tbody tr').length
    );

    $('#jsNormalDocuments tbody').html(adn);
    $('.jsDDNC').text(
        <?= json_encode($adn) ?> == '' ? '0' : $('#jsNormalDocuments tbody tr').length
    );
    //
    $('#jsNormalDocuments').show();
    //
    function loadDD(e) {
        $('.jsTab').hide();
        //
        if ($(e).data('type') == 'normal') $('#jsNormalDocuments').show();
        else $('#jsTimedDocuments').show();
    }
</script>


<?php $this->load->view('iframeLoader'); ?>
<?php $this->load->view('hr_documents_management/hybrid/scripts'); ?>
<?php
$this->load->view('hr_documents_management/scripts/index', [
    'offerLetters' => $company_offer_letters
]);
?>

<?php $this->load->view('hr_documents_management/category_manager'); ?>

<script>
    $('[data-toggle="tooltip"]').tooltip({
        trigger: "hover"
    });

    //
    $(function(e) {
        //
        $('.jsResendEEOC').click(function(event) {
            //
            event.preventDefault();
            var msg = $(this).text().trim().toLowerCase() == 'assign' ? "Are you sure you want to assign EEOC form?" : "Are you sure you want to sent an email notification?";
            var msg2 = $(this).text().trim().toLowerCase() == 'assign' ? 'EEOC form has been assigned.' : 'EEOC form notification has been sent.';
            //
            alertify.confirm(
                msg,
                function() {
                    //
                    $.post(
                        "<?= base_url('send_eeoc_form'); ?>", {
                            userId: <?= $user_sid; ?>,
                            userType: "<?= $user_type; ?>",
                            userJobId: "<?= $job_list_sid; ?>",
                            userLocation: "Document Center"
                        }
                    ).done(function(resp) {
                        //
                        if (resp == 'success') {
                            alertify.alert('Success!', msg2, function() {
                                window.location.reload();
                            });
                        } else {
                            //
                            alertify.alert('Error!', 'Something went wrong. Please, try again in a few moments.')
                        }
                    });
                }
            ).setHeader('Confirm!');
        });
    });
</script>

<script type="text/javascript">
    function preview_verification_doc_history(source) {
        var history_id = $(source).data('history_id');
        var history_type = $(source).data('history_type');
        //
        $('#document_loader').show();
        $('#loader_text_div').text("Please wait while we are getting history ");
        //
        $.ajax({
            'url': '<?php echo base_url('hr_documents_management/get_verification_history_document'); ?>' + '/' + history_id + '/' + history_type,
            'type': 'GET',
            success: function(resp) {

                var document_title = resp.name;
                var document_view = resp.html;
                //
                $('#fillable_history_document_modal').modal('show');
                $("#history_document_modal_title").html(document_title);
                $("#history_document_preview").html(document_view);
                $("#history_document_preview").show();
                //
                $('#document_loader').hide();
                //

                // footer_content = '<a target="_blank" class="btn btn-success" href="' + print_url + '">Print</a>';
                // footer_content += '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                // $("#latest_document_modal_footer").html(footer_content);
            }
        });
    }

    $('#show_latest_preview_document_modal').on('hidden.bs.modal', function() {
        $("#history_document_modal_title").html("Fillable Verification History");
        $('#history_document_preview').html('');
        $('#history_document_preview').hide();
    });

    //
    $(document).on('click', '.jsRevokeApprovalDocument', function() {
        //
        var approval_document_sid = $(this).data("approval_document_sid") === undefined ? $(this).data('document_sid') : $(this).data("approval_document_sid");
        //
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke approval?',
            function() {
                $('#loader').show();
                //
                var form_data = new FormData();
                form_data.append('document_sid', approval_document_sid);
                form_data.append('user_sid', '<?php echo $user_sid; ?>');
                form_data.append('user_type', '<?php echo $user_type; ?>');

                $.ajax({
                    url: '<?= base_url('hr_documents_management/revoke_approval_document') ?>',
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    data: form_data,
                    success: function(resp) {
                        $('#loader').hide();
                        alertify.alert('SUCCESS!', resp.message, function() {
                            window.location.reload();
                        });

                    },
                    error: function() {}
                });
            },
            function() {
                alertify.error('Canceled!');
            }
        );
    });
</script>
<!--  -->
<?php $this->load->view('hr_documents_management/document_track'); ?>
<?php $this->load->view('hr_documents_management/verification_document_history', ['user_sid' => $user_sid, 'user_type' => $user_type]); ?>

<!-- Preview Latest Document Modal Start -->
<div id="fillable_history_document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="history_document_modal_title">
                    Fillable Verification History
                </h4>
            </div>
            <div class="modal-body">
                <div id="history_document_preview" style="display:none;">

                </div>
            </div>
            <div class="modal-footer" id="history_document_modal_footer">

            </div>
        </div>
    </div>
</div>

<script>
    function sendFederalFillableDocumentReminder(
        fillable_type
    ) {
        $.post('<?= base_url('hr_documents_management/send_email_notification_pending_document') ?>', {
            document_type: fillable_type,
            user_sid: '<?php echo $user_sid; ?>',
            user_type: '<?php echo $user_type; ?>'
        }, (resp) => {
            //
            $('#my_loader').hide(0);
            $('#my_loader .loader-text').html('Please wait while we are sending email notification ...');
            $('body').css('overflow-y', 'auto');
            //
            if (resp.Status === false) {
                alertify.alert('WARNING!', resp.Response, () => {});
                return;
            }
            //
            alertify.alert('SUCCESS!', resp.Response, () => {});
            return;
        });
    }


    $(document).on('click', '.js-send-document-notification', function() {
        //
        let type = $(this).data('type');
        //
        alertify.confirm(
            'Confirm!',
            'Do you really want to send this document by email?',
            () => {
                $('body').css('overflow-y', 'hidden');
                $('#my_loader .loader-text').html('Please wait while we are sending this document....');
                $('#my_loader').show();
                //
                sendFederalFillableDocumentReminder(type);
            },
            () => {}
        ).set('labels', {
            ok: 'YES',
            cancel: 'NO'
        });
    });
</script>


<!-- Preview Latest Document Modal Modal End -->


<!-- <script>
    $(function I9FormEmployer() {
        //
        let formId = "<?= $i9_form['sid'] ?? 0; ?>";
        //
        $(document).on('click', function(event) {
            // $(document).on('click', '.jsI9EmployerSection', function(event) {
            //
            Model({
                Title: 'I9 form',
                Id: 'jsI9FormModal',
                Loader: 'jsI9FormModalLoader',
                Body: '<div id="jsI9FormModalBody"></div>',
            }, function() {
                setInterval(getI9EmployerView, 3000)
            });
        });

        function getI9EmployerView() {
            $.ajax({
                    url: window.location.origin + '/forms/i9/view/employer',
                    
                })
                .success()
                .fail()
                .always()
        }
    })
</script> -->


<script>
    $(function() {
        let eeoId;
        $(".jsEEOCOptOut").click(function(event) {
            event.preventDefault();
            eeoId = $(this).data("id");
            _confirm(
                "Do you really want to 'Opt-out' of the EEOC form?",
                startOptOutProcess
            );
        });

        function startOptOutProcess() {
            const _hook = callButtonHook(
                $(".jsEEOCOptOut"),
                true
            );
            $.ajax({
                    url: baseUrl("eeoc/" + (eeoId) + "/opt_out"),
                    method: "PUT",
                })
                .always(function() {
                    callButtonHook(_hook, false)
                })
                .fail(handleErrorResponse)
                .success(function(resp) {
                    _success(
                        resp.message,
                        window.location.refresh
                    )
                });
        }
    })
</script>
