<?php
$requiredMessage = 'You must complete this document to finish the onboarding process.';
//
$ncd = $pp = $cd = $nad = 0;
$canAccessDocument = hasDocumentsAssigned($session['employer_detail']);

$action_btn_flag = true;
if ($pp_flag == 1 || $canAccessDocument) {
    $action_btn_flag = false;
}

$document_all_permission = false;
if ($session['employer_detail']['access_level_plus'] == 1 || $canAccessDocument) {
    $document_all_permission = true;
}

// Modify Assigned document
// only available for Access_level_plus
// employees.
// Added in Not Completed, Completed
// and Not Required tabs
$modifyBTN = '<button
        class="btn btn-success btn-sm btn-block js-modify-assigned-document-btn"
        data-id="{{sid}}"
        data-type="{{type}}"
        title="Modify assigned document"
    >Modify</button>';

//
$completedDocumentsList = [];
$notCompletedDocumentsList = [];
$noActionRequiredDocumentsList = [];
?>
<style>
    .download_document_note {
        display: block;
        margin-top: 20px;
    }

    .jsCategoryManagerBTN {
        display: none;
    }
</style>

<div class="row">
    <div class="col-xs-12">
        <ul class="nav nav-tabs nav-justified doc_assign_nav_tab">
            <li class="active doc_assign_nav_li"><a data-toggle="tab" href="#in_complete_doc_details">Not Completed (<span class="js-ncd">0</span>)</a></li>
            <?php if ($pp_flag) { ?>
                <!-- <li class="doc_assign_nav_li"><a data-toggle="tab" href="#offer_letter_doc_details">Offer Letter / Pay Plan (<span class="js-pp">0</span>)</a></li> -->
            <?php } ?>
            <li class="doc_assign_nav_li"><a data-toggle="tab" href="#signed_doc_details">Completed Documents (<span class="js-cd">0</span>)</a></li>
            <!-- <li class="doc_assign_nav_li"><a data-toggle="tab" href="#completed_doc_details">Completed Documents</a></li> -->
            <li class="doc_assign_nav_li"><a data-toggle="tab" href="#no_action_required_doc_details">No Action Required (<span class="js-nad">0</span>)</a></li>
        </ul>
        <div class="tab-content hr-documents-tab-content">
            <!-- Not Completed Document Start -->
            <div id="in_complete_doc_details" class="tab-pane fade in active hr-innerpadding">
                <div class="panel-body">
                    <h2 class="tab-title">Not Completed Document Detail</h2>
                    <?php if (sizeof($assigned_documents) || sizeof($uncompleted_offer_letter) || sizeof($uncompleted_payroll_documents)) { ?>
                        <div class="table-responsive full-width">
                            <table class="table table-plane js-uncompleted-docs">
                                <thead>
                                    <tr>
                                        <th class="col-lg-8">Document Name</th>
                                        <th class="col-lg-4 text-center" colspan="4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($assigned_documents)) { ?>
                                        <?php $assigned_documents = array_reverse($assigned_documents);  ?>
                                        <?php foreach ($assigned_documents as $document) { ?>
                                            <?php if (!in_array($document['sid'], $payroll_documents_sids)) { ?>
                                                <?php if ($document['archive'] != 1) { ?>
                                                    <?php if ($document['status'] != 0) { ?>
                                                        <?php $ncd++;
                                                        $notCompletedDocumentsList[] = $document; ?>
                                                        <tr>
                                                            <td class="col-lg-6">
                                                                <?php
                                                                echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                echo $document['isdoctolibrary'] == 1 ? '( <b style="color:red;"> Document Library </b> )' : '';
                                                                echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';
                                                                echo $document['is_confidential'] ? '<br/><b> (Confidential)</b>' : '';

                                                                if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                    echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                                }

                                                                if ($document['approval_process'] == 1) {
                                                                    echo '<br><b class="text-danger">(Document Approval Pending)</b>';
                                                                }
                                                                ?>
                                                            </td>

                                                            <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                <td></td>
                                                                <td class="col-lg-2">
                                                                    <button data-id="<?= $document['sid']; ?>" data-document="assigned" class="btn btn-success btn-sm btn-block js-hybrid-preview">Preview Assigned</button>
                                                                    <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                        <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'notCompletedDocuments'], $modifyBTN); ?>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                        <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/' . ($user_type) . '/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                        <?= getSendDocumentEmailButton($document, $session['employer_detail'], $user_type); ?>
                                                                        <?php if ($document['approval_process'] == 1) { ?>
                                                                            <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                View Approver(s)
                                                                            </button>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                            <?php } else if ($document['document_type'] == 'uploaded') { ?>
                                                                <?php if ($document['document_sid'] != 0) { ?>
                                                                    <td class="col-lg-1">
                                                                        <?php
                                                                        $document_sid = $document['document_sid'];
                                                                        $document_filename = $document['document_s3_name'];
                                                                        $document_file = pathinfo($document_filename);
                                                                        $document_extension = $document_file['extension'];
                                                                        $name = explode(".", $document_filename);
                                                                        $url_segment_original = $name[0];
                                                                        ?>
                                                                        <?php if ($document['fillable_documents_slug'] != null && $document['fillable_documents_slug'] != '') { ?>
                                                                            <a target="_blank" href="<?php echo base_url('v1/fillable_documents/PrintPrevieFillable/' . $document['fillable_documents_slug'] . '/' . $document_sid . '/original/print'); ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                        <?php } elseif ($document_extension == 'pdf') { ?>
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

                                                                        <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['document_s3_name']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>

                                                                    </td>

                                                                    <td class="col-lgfa-stack-1x">

                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>
                                                                            Preview Assigned
                                                                        </button>


                                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                            <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'notCompletedDocuments'], $modifyBTN); ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } ?>

                                                                <td class="col-lg-1">
                                                                    <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                        <?php //if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_manage_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_manage_doc'))) { 
                                                                        if (true) {  ?>
                                                                            <?php if ($document['document_sid'] != 0) { ?>
                                                                                <?php if ($document['status'] == 1) { ?>
                                                                                    <?php if ($user_type == 'applicant') { ?>
                                                                                        <a class="btn btn-success  btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                                    <?php } else { ?>
                                                                                        <a class="btn btn-success  btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                                    <?php } ?>
                                                                                <?php } else { ?>
                                                                                    <button class="btn btn-warning  btn-sm btn-block" onclick="func_document_revoked();">Manage Document</button>
                                                                                <?php } ?>
                                                                            <?php } ?>

                                                                        <?php } ?>
                                                                        <?= getSendDocumentEmailButton($document, $session['employer_detail'], $user_type); ?>
                                                                        <?php if ($document['approval_process'] == 1) { ?>
                                                                            <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                View Approver(s)
                                                                            </button>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td class="col-lg-1">

                                                                    <?php if ($document['fillable_documents_slug'] != null && $document['fillable_documents_slug'] != '') { ?>
                                                                        <a target="_blank" href="<?php echo base_url('v1/fillable_documents/PrintPrevieFillable/' . $document['fillable_documents_slug'] . '/' . $document['sid'] . '/original/print'); ?>" class="btn btn-success btn-sm btn-block">Print</a>

                                                                        <a target="_blank" href="<?php echo base_url('v1/fillable_documents/PrintPrevieFillable/' . $document['fillable_documents_slug'] . '/' . $document['sid'] . '/original/download'); ?>" class="btn btn-success btn-sm btn-block">Download</a>

                                                                    <?php } else { ?>

                                                                        <a href="<?= base_url('hr_documents_management/perform_action_on_document_content' . '/' . $document['sid'] . '/assigned/assigned_document/print'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>

                                                                        <a href="<?= base_url('hr_documents_management/perform_action_on_document_content' . '/' . $document['sid'] . '/assigned/assigned_document/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                    <?php } ?>
                                                                </td>

                                                                <td class="col-lg-2">
                                                                    <?php
                                                                    if ($document['fillable_documents_slug'] != null && $document['fillable_documents_slug'] != '') { ?>
                                                                        <button class="btn btn-success btn-sm btn-block" onclick="fLaunchModalFillable(this);" date-letter-type="generated" data-on-action="assigned" data-preview-url="<?php echo $document['fillable_documents_slug']; ?>" data-s3-name="<?php echo $document['fillable_documents_slug']; ?>" data-document-sid="<?php echo $document['document_sid']; ?>" data-document-title="Assigned Document">
                                                                            Preview Assigned
                                                                        </button>
                                                                    <?php } else { ?>
                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document">
                                                                            Preview Assigned
                                                                        </button>
                                                                    <?php } ?>


                                                                    <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                        <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'notCompletedDocuments'], $modifyBTN); ?>
                                                                    <?php } ?>
                                                                </td>

                                                                <td class="col-lg-2">
                                                                    <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                        <?php
                                                                        //if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_manage_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_manage_doc'))) {
                                                                        if (true) {
                                                                        ?>
                                                                            <?php if ($document['status'] == 1) { ?>
                                                                                <?php if ($user_type == 'applicant') { ?>
                                                                                    <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                                <?php } else { ?>
                                                                                    <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <button class="btn btn-warning btn-sm btn-block" onclick="func_document_revoked();">Manage Document</button>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                        <?= getSendDocumentEmailButton($document, $session['employer_detail'], $user_type); ?>
                                                                        <?php if ($document['approval_process'] == 1) { ?>
                                                                            <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                View Approver(s)
                                                                            </button>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                            <?php } ?>
                                                            <td>
                                                                <?php if ($document_all_permission) { ?>
                                                                    <a href="javascript:void(0);" class="btn btn-success btn-sm btn-block jsCategoryManagerBTN" title="Modify Category" data-asid="<?= $document['sid']; ?>" data-sid="<?= $document['document_sid']; ?>">Manage Category</a>
                                                                    <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-block jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                    <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                        <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                                        <a class="<?php echo $btn_show; ?> manage_authorized_signature" href="javascript:;" data-auth-sid="<?php echo $document['sid']; ?>" data-auth-signature="<?php echo $document['authorized_sign_status'] == 1 ? $document['authorized_signature'] : $current_user_signature; ?>">
                                                                            <?php if ($document['authorized_sign_status'] == 0) { ?>
                                                                                Employer Section - Not Completed
                                                                            <?php } else if ($document['authorized_sign_status'] == 1) { ?>
                                                                                Employer Section - Completed
                                                                            <?php } ?>
                                                                        </a>
                                                                    <?php } ?>
                                                                <?php } ?>


                                                                <?php if ($document['performance_document_json'] != '') {
                                                                    $performanceDocumentData = json_decode($document['performance_document_json'], true);
                                                                ?>
                                                                    <?php if ($performanceDocumentData['section1']['status'] != 'completed') { ?>

                                                                        <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                                        <a class="<?php echo $btn_show; ?> performance_doc_section1" href="javascript:;" data-sid="<?php echo $document['sid']; ?>" data-employeesid="<?php echo $document['user_sid']; ?>" data-employeetype="<?php echo $document['user_type']; ?>">
                                                                            Section 1 - Not Completed

                                                                        </a>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($document['performance_document_json'] != '') {
                                                                    $performanceDocumentData = json_decode($document['performance_document_json'], true);
                                                                ?>
                                                                    <?php if ($performanceDocumentData['section3']['status'] != 'completed' && $performanceDocumentData['section1']['status'] == 'completed') { ?>

                                                                        <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                                        <a class="<?php echo $btn_show; ?> performance_doc_section3" href="javascript:;" data-sid="<?php echo $document['sid']; ?>" data-employeesid="<?php echo $document['user_sid']; ?>" data-employeetype="<?php echo $document['user_type']; ?>" data-managercomment="<?php echo $performanceDocumentData['section3']['data']['section3ManagerComment'] ? $performanceDocumentData['section3']['data']['section3ManagerComment'] : '' ?>" data-employeecomment="<?php echo $performanceDocumentData['section3']['data']['section3EmployeeComment'] ? $performanceDocumentData['section3']['data']['section3EmployeeComment'] : ''; ?>">

                                                                            <?php if ($performanceDocumentData['section3']['data']['section3ManagerComment'] == '') {
                                                                                echo "Section 3 - Manager Not Completed";
                                                                            } else if ($performanceDocumentData['section3']['data']['section3EmployeeComment'] == '') {

                                                                                echo "Section 3 - Employee Not Completed";
                                                                            } else {
                                                                                echo "Section 3 - Not Completed";
                                                                            }

                                                                            ?>


                                                                        </a>
                                                                    <?php } ?>
                                                                <?php } ?>



                                                                <?php if ($document['performance_document_json'] != '') {
                                                                    $performanceDocumentData = json_decode($document['performance_document_json'], true);
                                                                ?>
                                                                    <?php if ($performanceDocumentData['section4']['status'] != 'completed' && $performanceDocumentData['section3']['status'] == 'completed') { ?>
                                                                        <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                                        <a class="<?php echo $btn_show; ?> performance_doc_section4" href="javascript:;" data-sid="<?php echo $document['sid']; ?>" data-employeesid="<?php echo $document['user_sid']; ?>" data-employeetype="<?php echo $document['user_type']; ?>" data-section4employeeSignature="<?php echo $performanceDocumentData['section4']['data']['section4employeeSignature'] ? $performanceDocumentData['section4']['data']['section4employeeSignature'] : ''; ?>" data-section4employeeSignatureDate="<?php echo $performanceDocumentData['section4']['data']['section4employeeSignatureDate'] ? formatDateToDB($performanceDocumentData['section4']['data']['section4employeeSignatureDate'], DB_DATE_WITH_TIME, SITE_DATE) : ''; ?>" data-section4managerSignature="<?php echo $performanceDocumentData['section4']['data']['section4managerSignature'] ? $performanceDocumentData['section4']['data']['section4managerSignature'] : ''; ?>" data-section4managerSignatureDate="<?php echo $performanceDocumentData['section4']['data']['section4managerSignatureDate'] ? formatDateToDB($performanceDocumentData['section4']['data']['section4managerSignatureDate'], DB_DATE_WITH_TIME, SITE_DATE) : ''; ?>" data-section4nextLevelSignature="<?php echo $performanceDocumentData['section4']['data']['section4nextLevelSignature'] ? $performanceDocumentData['section4']['data']['section4nextLevelSignature'] : ''; ?>" data-section4nextLevelSignatureDate="<?php echo $performanceDocumentData['section4']['data']['section4nextLevelSignatureDate'] ? formatDateToDB($performanceDocumentData['section4']['data']['section4nextLevelSignatureDate'], DB_DATE_WITH_TIME, SITE_DATE) : ''; ?>" data-section4hrSignature="<?php echo $performanceDocumentData['section4']['data']['section4hrSignature'] ? $performanceDocumentData['section4']['data']['section4hrSignature'] : ''; ?>" data-section4hrSignatureDate="<?php echo $performanceDocumentData['section4']['data']['section4hrSignatureDate'] ? formatDateToDB($performanceDocumentData['section4']['data']['section4hrSignatureDate'], DB_DATE_WITH_TIME, SITE_DATE) : ''; ?>">

                                                                            <?php if ($performanceDocumentData['section4']['data']['section4EmployeeSignature'] == '') {
                                                                                echo "Section 4 - Employee Not Completed";
                                                                            } else if ($performanceDocumentData['section4']['data']['section4ManagerSignature'] == '') {

                                                                                echo "Section 4 - Manager Not Completed";
                                                                            } else if ($performanceDocumentData['section4']['data']['section4NextLevelSignature'] == '') {

                                                                                echo "Section 4 - Next Level Not Completed";
                                                                            } else if ($performanceDocumentData['section4']['data']['section4HumanResourceSignature'] == '') {

                                                                                echo "Section 4 - Human Resource Not Completed";
                                                                            } else {
                                                                                echo "Section 4 - Not Completed";
                                                                            }

                                                                            ?>

                                                                        </a>
                                                                    <?php } ?>


                                                                    <?php if ($performanceDocumentData['section5']['status'] != 'completed' && $performanceDocumentData['section4']['status'] == 'completed') {
                                                                    ?>
                                                                        <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                                        <a class="<?php echo $btn_show; ?> performance_doc_section5" href="javascript:;" data-sid="<?php echo $document['sid']; ?>" data-employeesid="<?php echo $document['user_sid']; ?>" data-employeetype="<?php echo $document['user_type']; ?>" data-section5approvedBySignature="<?php echo $performanceDocumentData['section5']['data']['section5approvedBySignature'] ? $performanceDocumentData['section5']['data']['section5approvedBySignature'] : ''; ?>" data-section5approvedBySignatureDate="<?php echo $performanceDocumentData['section5']['data']['section5approvedBySignatureDate'] ? formatDateToDB($performanceDocumentData['section5']['data']['section5approvedBySignatureDate'], DB_DATE_WITH_TIME, SITE_DATE) : ''; ?>" data-section5approvedAmount="<?php echo $performanceDocumentData['section5']['data']['section5approvedAmount'] ? $performanceDocumentData['section5']['data']['section5approvedAmount'] : ''; ?>" data-section5recommendedIncrease="<?php echo $performanceDocumentData['section5']['data']['section5recommendedIncrease'] ? $performanceDocumentData['section5']['data']['section5recommendedIncrease'] : ''; ?>" data-section5currentRate="<?php echo $performanceDocumentData['section5']['data']['section5currentRate'] ? $performanceDocumentData['section5']['data']['section5currentRate'] : ''; ?>" data-section5IncreaseEffectiveDate="<?php echo $performanceDocumentData['section5']['data']['section5IncreaseEffectiveDate'] ? formatDateToDB($performanceDocumentData['section5']['data']['section5IncreaseEffectiveDate'], DB_DATE, SITE_DATE) : ''; ?>">
                                                                            Section 5 - Not Completed
                                                                        </a>
                                                                    <?php }
                                                                    ?>

                                                                <?php } ?>


                                                                <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) {
                                                                ?>
                                                                    <?php if ($document['fillable_documents_slug'] == 'written-employee-counseling-report-form' || $document['fillable_documents_slug'] == 'notice-of-separation') { ?>
                                                                        <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                                        <a class="<?php echo $btn_show; ?> manage_authorized_signature" href="javascript:;" data-auth-sid="<?php echo $document['sid']; ?>" data-auth-signature="<?php echo $document['authorized_sign_status'] == 1 ? $document['authorized_signature'] : $current_user_signature; ?>">
                                                                            <?php if ($document['authorized_sign_status'] == 0) { ?>
                                                                                Employer Section - Not Completed
                                                                            <?php } else if ($document['authorized_sign_status'] == 1) { ?>
                                                                                Employer Section - Completed
                                                                            <?php } ?>
                                                                        </a>
                                                                    <?php } ?>
                                                                <?php } ?>


                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if (!sizeof($assigned_documents) && !sizeof($uncompleted_offer_letter)) { ?>
                                        <tr>
                                            <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                    <!--  -->
                    <?php if (
                        sizeof($uncompleted_offer_letter) &&
                        has_approval(
                            $uncompleted_offer_letter[0]['allowed_roles'],
                            $uncompleted_offer_letter[0]['allowed_departments'],
                            $uncompleted_offer_letter[0]['allowed_teams'],
                            $uncompleted_offer_letter[0]['allowed_employees'],
                            [
                                'user_id' => $session['employer_detail']['sid'],
                                'access_level_plus' => $session['employer_detail']['access_level_plus'],
                                'pay_plan_flag' => $session['employer_detail']['pay_plan_flag'],
                                'access_level' => $session['employer_detail']['access_level']
                            ],
                            $uncompleted_offer_letter[0]['visible_to_payroll']
                        )
                    ) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle open_not_completed_doc" data-toggle="collapse" data-parent="#accordion" href="#collapse_uncompleted_offer_letter">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php //echo $category_document['name']; 
                                                ?>
                                                <?php echo 'Offer Letter / Pay Plan'; ?>
                                                <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($uncompleted_offer_letter); ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="collapse_uncompleted_offer_letter" class="panel-collapse collapse in">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-2 text-right">Actions</th>
                                                        <th class="col-lg-2 text-center">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($uncompleted_offer_letter)) { ?>
                                                        <?php foreach ($uncompleted_offer_letter as $document) { ?>
                                                            <?php
                                                            $GLOBALS['uofl'][] = $document;
                                                            $ncd++; ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    <?php
                                                                    echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                    echo $document['status'] ? '' : '<b>(revoked)&nbsp;</b>';
                                                                    echo $document['isdoctolibrary'] == 1 ? '( <b style="color:red;"> Document Library </b> )' : '';
                                                                    if ($document['manual_document_type'] == 'offer_letter') {
                                                                        echo '<b>(Manual Upload)</b>';
                                                                    }

                                                                    if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                                    }

                                                                    if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                        echo "<br><b>Signed On: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], 'format' => 'M d Y, D',  '_this' => $this));
                                                                    } else {
                                                                        echo "<br><b>Signed On: </b> N/A";
                                                                    }

                                                                    if ($document['approval_process'] == 1) {
                                                                        echo '<br><b class="text-danger">(Document Approval Pending)</b>';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <?php if ($document['letter_type'] == 'hybrid_document') { ?>
                                                                    <td></td>
                                                                    <td>
                                                                        <button data-id="<?= $document['sid'] ?>" data-type="offer_letter" data-document="assigned" class="btn btn-success btn-sm btn-block js-hybrid-preview">
                                                                            Preview Assigned
                                                                        </button>
                                                                        <?php if ($document_all_permission) { ?>
                                                                            <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'notCompletedOfferLetters'], $modifyBTN); ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } else if ($document['document_type'] == 'offer_letter') { ?>
                                                                    <td></td>
                                                                    <td class="col-lg-2">
                                                                        <?php if ($document['document_s3_name'] != '') { ?>
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>
                                                                                Preview Assigned
                                                                            </button>
                                                                            <?php if ($document_all_permission) { ?>
                                                                                <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'notCompletedOfferLetters'], $modifyBTN); ?>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document">
                                                                                Preview Assigned
                                                                            </button>
                                                                            <?php if ($document_all_permission) { ?>
                                                                                <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'notCompletedOfferLetters'], $modifyBTN); ?>
                                                                                <?= getAuthorizedDocument($document); ?>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class="col-lg-2">
                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>">Preview Document</button>
                                                                        <?php if ($document_all_permission) { ?>
                                                                            <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'notCompletedOfferLetters'], $modifyBTN); ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="col-lg-2">
                                                                        <?php if ($document_all_permission) { ?>
                                                                            <?php
                                                                            $categories = isset($no_action_document_categories[$document['sid']]) ? json_encode($no_action_document_categories[$document['sid']]) : "[]";
                                                                            $manual_document_type = $document['manual_document_type'] == "offer_letter" ? true : false;
                                                                            $document_type = $document['document_type'] == "confidential" ? true : false;
                                                                            $assign_date = isset($document['assigned_date']) ? date('m-d-Y', strtotime($document['assigned_date'])) : '';
                                                                            $sign_date = isset($document['signature_timestamp']) ?  date('m-d-Y', strtotime($document['signature_timestamp'])) : '';


                                                                            ?>

                                                                            <button class="btn btn-success btn-sm btn-block" onclick="no_action_req_edit_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>" is-offer-letter="<?php echo $manual_document_type; ?>" data-categories='<?php echo $categories; ?>' data-update-accessible="<?php echo $document_type; ?>" assign-date="<?php echo $assign_date; ?>" sign-date="<?php echo $sign_date; ?>">Edit Document</button>
                                                                        <?php } ?>

                                                                    </td>
                                                                <?php } ?>
                                                                <td>
                                                                    <?php if ($document_all_permission) { ?>
                                                                        <?php if ($user_type == 'applicant') { ?>
                                                                            <a class="btn btn-success  btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                        <?php } else { ?>
                                                                            <a class="btn btn-success  btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                        <?php } ?>
                                                                        <button class="btn btn-warning btn-sm btn-block" onclick="offer_letter_archive(<?php echo $document['sid']; ?>)">Archive</button>
                                                                        <?php if ($document['approval_process'] == 1) { ?>
                                                                            <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                View Approver(s)
                                                                            </button>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                            <?php //} 
                                                            ?>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Completed!</b></td>
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

                    <?php if (sizeof($uncompleted_payroll_documents)) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle open_not_completed_doc" data-toggle="collapse" data-parent="#accordion" href="#collapse_uncompleted_payroll_documents">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php echo 'Payroll Documents'; ?>
                                                <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($uncompleted_payroll_documents); ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="collapse_uncompleted_payroll_documents" class="panel-collapse collapse in">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-2 text-right">Actions</th>
                                                        <th class="col-lg-2 text-center">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($uncompleted_payroll_documents)) { ?>
                                                        <?php foreach ($uncompleted_payroll_documents as $document) { ?>
                                                            <?php
                                                            $GLOBALS['ad'][] = $documents;
                                                            $ncd++;
                                                            $notCompletedDocumentsList[] = $document;
                                                            if (!empty($documents)) {
                                                                $GLOBALS['ad'][] = $documents;
                                                            }
                                                            $ncd++;
                                                            ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    <?php
                                                                    echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                    echo $document['status'] ? '' : '<b>(revoked)&nbsp;</b>';
                                                                    echo $document['isdoctolibrary'] == 1 ? '( <b style="color:red;"> Document Library </b> )' : '';
                                                                    if ($document['manual_document_type'] == 'offer_letter') {
                                                                        echo '<b>(Manual Upload)</b>';
                                                                    }
                                                                    echo $document['is_confidential'] ? '<br/><b> (Confidential)</b>' : '';

                                                                    if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                                    }

                                                                    if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                        echo "<br><b>Signed On: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], 'format' => 'M d Y, D',  '_this' => $this));
                                                                    } else {
                                                                        echo "<br><b>Signed On: </b> N/A";
                                                                    }

                                                                    if ($document['approval_process'] == 1) {
                                                                        echo '<br><b class="text-danger">(Document Approval Pending)</b>';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <?php if ($document['document_s3_name'] != '') { ?>
                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>
                                                                            Preview Assigned
                                                                        </button>
                                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                            <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'notCompletedDocuments'], $modifyBTN); ?>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document">
                                                                            Preview Assigned
                                                                        </button>
                                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                            <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'notCompletedDocuments'], $modifyBTN); ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <?php if ($document_all_permission) { ?>
                                                                        <?php if (isset($document['uploaded_file']) && !empty($document['uploaded_file'])) { ?>
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="submitted" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>" data-s3-name="<?php echo $document['uploaded_file']; ?>" <?php echo $document['user_consent'] != 1 ? 'disabled' : ''; ?>>
                                                                                Preview Submitted
                                                                            </button>
                                                                        <?php } else { ?>
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="submitted" data-from="assigned_document" <?php echo $document['user_consent'] != 1 ? 'disabled' : ''; ?>>
                                                                                Preview Submitted
                                                                            </button>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <?php if ($document_all_permission) { ?>
                                                                        <a href="javascript:void(0);" class="btn btn-success btn-sm btn-block jsCategoryManagerBTN" title="Modify Category" data-asid="<?= $document['sid']; ?>" data-sid="<?= $document['document_sid']; ?>">Manage Category</a>

                                                                        <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                            <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-block jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                        <?php } ?>
                                                                    <?php } ?>

                                                                    <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                        <?php
                                                                        //if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_manage_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_manage_doc'))) { 
                                                                        if (true) {
                                                                        ?>
                                                                            <?php if ($document['document_sid'] != 0) { ?>
                                                                                <?php if ($document['status'] == 1) { ?>
                                                                                    <?php if ($user_type == 'applicant') { ?>
                                                                                        <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                                    <?php } else { ?>
                                                                                        <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                                    <?php } ?>
                                                                                <?php } else { ?>
                                                                                    <button class="btn btn-warning btn-sm btn-block" onclick="func_document_revoked();">Manage Document</button>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                        <?php if ($document['approval_process'] == 1) { ?>
                                                                            <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                View Approver(s)
                                                                            </button>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Completed!</b></td>
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

                </div>
            </div>
            <!-- Not Completed Document End -->

            <!-- Offer Letter Document Start -->
            <div id="offer_letter_doc_details" class="tab-pane fade in hr-innerpadding">
                <div class="panel-body">
                    <h2 class="tab-title">Assigned Offer Letter / Pay Plan Detail</h2>
                    <div class="table-responsive full-width">
                        <table class="table table-plane">
                            <thead>
                                <tr>
                                    <th class="col-lg-8">Document Name</th>
                                    <th class="col-lg-4 text-center" colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($assigned_offer_letters)) { ?>
                                    <?php $assigned_offer_letters = array_reverse($assigned_offer_letters);  ?>
                                    <?php foreach ($assigned_offer_letters as $document) { ?>
                                        <?php $pp++; ?>
                                        <tr>
                                            <td class="col-lg-8">
                                                <?php
                                                echo $document['document_title'] . '&nbsp;';
                                                echo $document['status'] ? '' : '<b>(revoked)&nbsp;</b>';
                                                if ($document['manual_document_type'] == 'offer_letter') {
                                                    echo '<b>(Manual Upload)</b>';
                                                }


                                                if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                    echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                }

                                                if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                    echo "<br><b>Signed On: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], 'format' => 'M d Y, D',  '_this' => $this));
                                                } else {
                                                    echo "<br><b>Signed On: </b> N/A";
                                                }
                                                ?>
                                            </td>
                                            <?php if ($document['document_type'] == 'offer_letter') { ?>
                                                <td class="col-lg-2">
                                                    <?php if ($document['document_s3_name'] != '') { ?>
                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-type="assigned" data-pcheck="0" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_s3_name']; ?>" data-document-title="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>Preview Assigned</button>

                                                    <?php } else { ?>
                                                        <button onclick="func_get_generated_document_preview(<?php echo $document['document_sid']; ?>, 'generated', 'modified', 0);" class="btn btn-success btn-sm btn-block">Preview Assigned</button>
                                                        <!-- <button onclick="func_get_generated_document_preview(<?php //echo $document['document_sid']; 
                                                                                                                    ?>//, 'generated', 'modified', <?php //echo $document['sid']; 
                                                                                                                                                    ?>//);" class="btn btn-success btn-sm btn-block">Preview Assigned</button>-->
                                                    <?php } ?>
                                                </td>
                                                <td class="col-lg-2">
                                                    <?php if ($document_all_permission) { ?>
                                                        <?php if (isset($document['uploaded_file']) && !empty($document['uploaded_file'])) { ?>
                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>" data-print-type="submitted" data-pcheck="0" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_file']; ?>" data-document-title="<?php echo $document['uploaded_file']; ?>" <?= !$document['uploaded'] ? 'disabled' : ''; ?>>Preview Submitted</button>
                                                        <?php } else { ?>
                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_submitted_generated_document(this);" data-preview-url="<?php echo $document['submitted_description']; ?>" data-download-url="<?php echo $document['submitted_description']; ?>" data-pcheck="0" data-print-id="<?php echo $document['sid']; ?>" data-file-name="<?php echo 'mysubmitted.pdf' ?>" data-document-title="<?php echo 'User Submitted pdf' ?>" <?= !$document['submitted_description'] ? 'disabled' : ''; ?>>Preview Submitted</button>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                            <?php } else { ?>
                                                <td class="col-lg-2">
                                                    <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-pcheck="0" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>">Preview Document</button>
                                                </td>
                                                <td class="col-lg-2">
                                                    <?php if ($document_all_permission) { ?>
                                                        <?php
                                                        $categories = isset($no_action_document_categories[$document['sid']]) ? json_encode($no_action_document_categories[$document['sid']]) : "[]";
                                                        $manual_document_type = $document['manual_document_type'] == "offer_letter" ? true : false;
                                                        $document_type = $document['document_type'] == "confidential" ? true : false;
                                                        $assign_date = isset($document['assigned_date']) ? date('m-d-Y', strtotime($document['assigned_date'])) : '';
                                                        $sign_date = isset($document['signature_timestamp']) ?  date('m-d-Y', strtotime($document['signature_timestamp'])) : '';


                                                        ?>

                                                        <button class="btn btn-success btn-sm btn-block" onclick="no_action_req_edit_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>" is-offer-letter="<?php echo $manual_document_type; ?>" data-categories='<?php echo $categories; ?>' data-update-accessible="<?php echo $document_type; ?>" assign-date="<?php echo $assign_date; ?>" sign-date="<?php echo $sign_date; ?>">Edit Document</button>
                                                    <?php } ?>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="7" class="col-lg-12 text-center"><b>No Offer Letter Assigned!</b></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Offer Letter Document End -->

            <?php //$modifyBTN = ''; 
            ?>

            <!-- Signed Document Start -->
            <div id="signed_doc_details" class="tab-pane fade in hr-innerpadding">
                <div class="panel-body">
                    <?php if ($document_all_permission) { ?>
                        <?php if ($downloadDocumentData && count($downloadDocumentData) && $downloadDocumentData['user_type'] == $user_type && $downloadDocumentData['download_type'] == 'single_download' && file_exists(APPPATH . '../temp_files/employee_export/' . $downloadDocumentData['folder_name'])) { ?>
                            <div class="alert alert-success">Last export was generated at <?= DateTime::createFromFormat('Y-m-d H:i:s', $downloadDocumentData['created_at'])->format('m/d/Y H:i'); ?>. <a class="btn btn-success" href="<?= base_url('hr_documents_management/generate_zip/' . ($downloadDocumentData['folder_name']) . ''); ?>">Download</a></div>
                        <?php } ?>
                    <?php } ?>
                    <!-- Category Completed Document Start -->
                    <h2 class="tab-title">Completed Document Detail
                        <span class="pull-right">
                            <?php if ($document_all_permission) { ?>
                                <a href="<?= base_url('download/' . ($user_type) . '/' . ($user_sid) . '/completed'); ?>" target="_blank" class="btn btn-success">Download Document(s)</a>
                            <?php } ?>
                        </span>
                        <hr />
                    </h2>
                    <?php if (!empty($categories_documents_completed)) { ?>
                        <?php foreach ($categories_documents_completed as $category_document) { ?>
                            <?php if ($category_document['category_sid'] != 27) { ?>
                                <?php if (isset($category_document['documents'])) { ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="panel panel-default hr-documents-tab-content">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed<?php echo $category_document['category_sid']; ?>">
                                                            <span class="glyphicon glyphicon-plus"></span>
                                                            <?php echo $category_document['name']; ?>
                                                            <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($category_document['documents']); ?></b></div>
                                                        </a>

                                                    </h4>
                                                </div>

                                                <div id="collapse_completed<?php echo $category_document['category_sid']; ?>" class="panel-collapse collapse">
                                                    <div class="table-responsive full-width">
                                                        <table class="table table-plane">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-lg-8">Document Name</th>
                                                                    <th class="col-lg-2 text-right">Actions</th>
                                                                    <th class="col-lg-2 text-center">&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($category_document['documents'])) { ?>
                                                                    <?php foreach ($category_document['documents'] as $document) { ?>
                                                                        <?php
                                                                        $completedDocumentsList[] = $document;
                                                                        $GLOBALS['ad'][] = $document;
                                                                        $cd++;
                                                                        ?>
                                                                        <?php if ($document["is_history"] == 0) { ?>
                                                                            <tr>
                                                                                <td class="col-lg-8">
                                                                                    <?php
                                                                                    echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                                    echo $document['is_document_authorized'] == 1 && $document['authorized_sign_status'] == 0  ? '( <b style="color:red;"> Awaiting Authorized Signature </b> )' : '';
                                                                                    echo $document['isdoctolibrary'] == 1 ? '( <b style="color:red;"> Document Library </b> )' : '';
                                                                                    echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                                    echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                                    echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';
                                                                                    echo $document['is_confidential'] ? '<br/><b> (Confidential)</b>' : '';

                                                                                    if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                                                    }

                                                                                    if ($document['approval_process'] == 1) {
                                                                                        echo '<br><b class="text-danger">(Document Approval Pending)</b>';
                                                                                    }
                                                                                    ?>
                                                                                </td>

                                                                                <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                    <td>
                                                                                        <button data-id="<?= $document['sid']; ?>" data-type="document" data-document="assigned" class="btn btn-success btn-sm btn-block js-hybrid-preview">
                                                                                            Preview Assigned
                                                                                        </button>
                                                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                            <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'completedDocuments'], $modifyBTN); ?>
                                                                                        <?php } ?>
                                                                                        <?php if ($document_all_permission) { ?>

                                                                                            <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                                                <?php
                                                                                                $authorized_signature_url = '';

                                                                                                if ($user_type == 'applicant') {
                                                                                                    $authorized_signature_url = base_url('hr_documents_management/sign_authorized_signature_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid);
                                                                                                } else {
                                                                                                    $authorized_signature_url = base_url('hr_documents_management/sign_authorized_signature_document/employee/' . $document['sid'] . '/' . $user_sid);
                                                                                                }
                                                                                                ?>

                                                                                                <a class="btn btn-success btn-sm btn-block" href="<?php echo $authorized_signature_url; ?>">
                                                                                                    View Doc
                                                                                                </a>
                                                                                            <?php } ?>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button data-id="<?= $document['sid']; ?>" data-type="document" data-document="submitted" class="btn btn-success btn-sm btn-block js-hybrid-preview">
                                                                                            Preview Submitted
                                                                                        </button>
                                                                                        <?php if ($document_all_permission) { ?>
                                                                                            <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                                                <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                                                                <a class="<?php echo $btn_show; ?> manage_authorized_signature" href="javascript:;" data-auth-sid="<?php echo $document['sid']; ?>" data-auth-signature="<?php echo $document['authorized_sign_status'] == 1 ? $document['authorized_signature'] : $current_user_signature; ?>">
                                                                                                    <?php if ($document['authorized_sign_status'] == 0) { ?>
                                                                                                        Employer Section - Not Completed
                                                                                                    <?php } else if ($document['authorized_sign_status'] == 1) { ?>
                                                                                                        Employer Section - Completed
                                                                                                    <?php } ?>
                                                                                                </a>
                                                                                            <?php } ?>
                                                                                        <?php } ?>

                                                                                        <?php if ($document_all_permission  && $document['isdoctolibrary'] == 0) { ?>
                                                                                            <?php
                                                                                            //if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_manage_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_manage_doc'))) { 
                                                                                            if (true) {
                                                                                            ?>
                                                                                                <?php if ($document['document_sid'] != 0) { ?>
                                                                                                    <?php if ($document['status'] == 1) { ?>
                                                                                                        <?php if ($user_type == 'applicant') { ?>
                                                                                                            <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                                                        <?php } else { ?>
                                                                                                            <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                                                        <?php } ?>
                                                                                                    <?php } else { ?>
                                                                                                        <button class="btn btn-warning btn-sm btn-block" onclick="func_document_revoked();">Manage Document</button>
                                                                                                    <?php } ?>
                                                                                                <?php } ?>
                                                                                            <?php } ?>
                                                                                            <?php if ($document['approval_process'] == 1) { ?>
                                                                                                <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                                    View Approver(s)
                                                                                                </button>
                                                                                            <?php } ?>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                <?php } else if ($document['document_type'] == 'uploaded') { ?>
                                                                                    <?php if ($document['document_sid'] != 0) { ?>
                                                                                        <td class="col-lg-2">


                                                                                            <?php if ($document['fillable_documents_slug'] != null && $document['fillable_documents_slug'] != '') { ?>

                                                                                                <button class="btn btn-success btn-sm btn-block" onclick="fLaunchModalFillable(this);" date-letter-type="generated" data-on-action="assigned" data-preview-url="<?php echo $document['fillable_documents_slug']; ?>" data-s3-name="<?php echo $document['fillable_documents_slug']; ?>" data-document-sid="<?php echo $document['document_sid']; ?>">
                                                                                                    Preview Assigned
                                                                                                </button>
                                                                                            <?php } else { ?>
                                                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>
                                                                                                    Preview Assigned
                                                                                                </button>
                                                                                            <?php } ?>


                                                                                            <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                                <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'completedDocuments'], $modifyBTN); ?>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                    <?php } ?>
                                                                                    <td class="col-lg-2">
                                                                                        <?php if ($document_all_permission) { ?>
                                                                                            <?php if (in_array($document['document_sid'], $signed_document_sids)) { ?>

                                                                                                <?php if ($document['fillable_documents_slug'] != null && $document['fillable_documents_slug'] != '') { ?>
                                                                                                    <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_fillable_function(this);" date-letter-type="generated" data-on-action="assigned" data-preview-url="<?php echo $document['fillable_documents_slug']; ?>" data-s3-name="<?php echo $document['fillable_documents_slug']; ?>" data-document-sid="<?php echo $document['sid']; ?>">
                                                                                                        Preview Submitted
                                                                                                    </button>

                                                                                                <?php } else { ?>

                                                                                                    <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="submitted" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>" data-s3-name="<?php echo $document['uploaded_file']; ?>" <?php echo $document['user_consent'] != 1 ? 'disabled' : ''; ?>>
                                                                                                        Preview Submitted
                                                                                                    </button>
                                                                                                <?php } ?>

                                                                                            <?php } ?>
                                                                                        <?php } ?>

                                                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                            <?php
                                                                                            //if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_manage_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_manage_doc'))) { 
                                                                                            if (true) {
                                                                                            ?>
                                                                                                <?php if ($document['document_sid'] != 0) { ?>
                                                                                                    <?php if ($document['status'] == 1) { ?>
                                                                                                        <?php if ($user_type == 'applicant') { ?>
                                                                                                            <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                                                        <?php } else { ?>
                                                                                                            <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                                                        <?php } ?>
                                                                                                    <?php } else { ?>
                                                                                                        <button class="btn btn-warning btn-sm btn-block" onclick="func_document_revoked();">Manage Document</button>
                                                                                                    <?php } ?>
                                                                                                <?php } ?>
                                                                                            <?php } ?>
                                                                                            <?php if ($document['approval_process'] == 1) { ?>
                                                                                                <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                                    View Approver(s)
                                                                                                </button>
                                                                                            <?php } ?>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                <?php } else { ?>



                                                                                    <td class="col-lg-2">

                                                                                        <?php if ($document['fillable_documents_slug'] != null && $document['fillable_documents_slug'] != '') { ?>

                                                                                            <button class="btn btn-success btn-sm btn-block" onclick="fLaunchModalFillable(this);" date-letter-type="generated" data-on-action="assigned" data-preview-url="<?php echo $document['fillable_documents_slug']; ?>" data-s3-name="<?php echo $document['fillable_documents_slug']; ?>" data-document-sid="<?php echo $document['document_sid']; ?>" data-document-title="Assigned Document">
                                                                                                Preview Assigned
                                                                                            </button>
                                                                                        <?php } else { ?>
                                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document">
                                                                                                Preview Assigned
                                                                                            </button>
                                                                                        <?php } ?>


                                                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                            <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'completedDocuments'], $modifyBTN); ?>
                                                                                        <?php } ?>

                                                                                        <?php if ($document_all_permission) { ?>
                                                                                            <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                                                <?php
                                                                                                $authorized_signature_url = '';

                                                                                                if ($user_type == 'applicant') {
                                                                                                    $authorized_signature_url = base_url('hr_documents_management/sign_authorized_signature_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid);
                                                                                                } else {
                                                                                                    $authorized_signature_url = base_url('hr_documents_management/sign_authorized_signature_document/employee/' . $document['sid'] . '/' . $user_sid);
                                                                                                }
                                                                                                ?>

                                                                                                <a class="btn btn-success btn-sm btn-block" href="<?php echo $authorized_signature_url; ?>">
                                                                                                    View Doc
                                                                                                </a>
                                                                                            <?php } ?>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="col-lg-2">
                                                                                        <?php if ($document_all_permission) { ?>
                                                                                            <?php if (in_array($document['document_sid'], $signed_document_sids)) { ?>
                                                                                                <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                                                    <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="submitted" data-from="assigned_document">
                                                                                                        Preview Submitted
                                                                                                    </button>
                                                                                                <?php } else { ?>

                                                                                                    <?php if ($document['fillable_documents_slug'] != null && $document['fillable_documents_slug'] != '') { ?>
                                                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_fillable_function(this);" date-letter-type="generated" data-on-action="assigned" data-preview-url="<?php echo $document['fillable_documents_slug']; ?>" data-s3-name="<?php echo $document['fillable_documents_slug']; ?>" data-document-sid="<?php echo $document['sid']; ?>">
                                                                                                            Preview Submitted
                                                                                                        </button>
                                                                                                    <?php } else { ?>
                                                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="submitted" data-from="assigned_document">
                                                                                                            Preview Submitted
                                                                                                        </button>
                                                                                                    <?php } ?>
                                                                                                <?php } ?>
                                                                                            <?php } else { ?>
                                                                                                <button onclick="generated_document_original_preview(<?php echo $document['sid']; ?>);" class="btn btn-success btn-sm btn-block">Preview Submitted</button>
                                                                                            <?php } ?>

                                                                                            <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                                                <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                                                                <a class="<?php echo $btn_show; ?> manage_authorized_signature" href="javascript:;" data-auth-sid="<?php echo $document['sid']; ?>" data-auth-signature="<?php echo $document['authorized_sign_status'] == 1 ? $document['authorized_signature'] : $current_user_signature; ?>">
                                                                                                    <?php if ($document['authorized_sign_status'] == 0) { ?>
                                                                                                        Employer Section - Not Completed
                                                                                                    <?php } else if ($document['authorized_sign_status'] == 1) { ?>
                                                                                                        Employer Section - Completed
                                                                                                    <?php } ?>
                                                                                                </a>
                                                                                            <?php } ?>

                                                                                            <?php
                                                                                            //if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_manage_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_manage_doc'))) { 
                                                                                            if (true) {
                                                                                            ?>
                                                                                                <?php if ($document['document_sid'] != 0 && $document['isdoctolibrary'] == 0) { ?>
                                                                                                    <?php if ($document['status'] == 1) { ?>
                                                                                                        <?php if ($user_type == 'applicant') { ?>
                                                                                                            <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                                                        <?php } else { ?>
                                                                                                            <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                                                        <?php } ?>
                                                                                                    <?php } else { ?>
                                                                                                        <button class="btn btn-warning btn-sm btn-block" onclick="func_document_revoked();">Manage Document</button>
                                                                                                    <?php } ?>
                                                                                                <?php } ?>
                                                                                            <?php } ?>
                                                                                            <?php if ($document['approval_process'] == 1) { ?>
                                                                                                <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                                    View Approver(s)
                                                                                                </button>
                                                                                            <?php } ?>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                <?php } ?>
                                                                                <td>
                                                                                    <?php if ($document_all_permission) { ?>
                                                                                        <a href="javascript:void(0);" class="btn btn-success btn-sm btn-block jsCategoryManagerBTN" title="Modify Category" data-asid="<?= $document['sid']; ?>" data-sid="<?= $document['document_sid']; ?>">Manage Category</a>

                                                                                        <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                                            <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-block jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } else { ?>
                                                                            <?php $this->load->view('hr_documents_management/document_assign_history_row', ['user_sid' => $user_sid, 'user_type' => $user_type, 'history_document' => $document, "document_all_permission" => $document_all_permission]); ?>
                                                                        <?php } ?>
                                                                        <?php if (!empty($document["history"])) { ?>
                                                                            <?php foreach ($document["history"] as $history_document) { ?>
                                                                                <?php $cd++; ?>
                                                                                <?php $this->load->view('hr_documents_management/document_assign_history_row', ['user_sid' => $user_sid, 'user_type' => $user_type, 'history_document' => $history_document, "document_all_permission" => $document_all_permission]); ?>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Completed!</b></td>
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
                        <?php } ?>
                    <?php } else { ?>
                        <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                    <?php } ?>

                    <?php if (
                        sizeof($completed_offer_letter)  &&
                        has_approval(
                            $completed_offer_letter[0]['allowed_roles'],
                            $completed_offer_letter[0]['allowed_departments'],
                            $completed_offer_letter[0]['allowed_teams'],
                            $completed_offer_letter[0]['allowed_employees'],
                            [
                                'user_id' => $session['employer_detail']['sid'],
                                'access_level_plus' => $session['employer_detail']['access_level_plus'],
                                'pay_plan_flag' => $session['employer_detail']['pay_plan_flag'],
                                'access_level' => $session['employer_detail']['access_level']
                            ],
                            $completed_offer_letter[0]['visible_to_payroll']
                        )
                    ) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed_offer_letter">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php //echo $category_document['name']; 
                                                ?>
                                                <?php echo 'Offer Letter / Pay Plan'; ?>
                                                <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($completed_offer_letter); ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="collapse_completed_offer_letter" class="panel-collapse collapse">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-2 text-right">Actions</th>
                                                        <th class="col-lg-2 text-center">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($completed_offer_letter)) { ?>
                                                        <?php foreach ($completed_offer_letter as $document) { ?>
                                                            <?php $GLOBALS['uofl'][] = $document;
                                                            $cd++; ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    <?php
                                                                    echo $document['document_title'] . '&nbsp;';
                                                                    echo $document['is_document_authorized'] == 1 && $document['authorized_sign_status'] == 0  ? '( <b style="color:red;"> Awaiting Authorized Signature </b> )' : '';
                                                                    echo $document['isdoctolibrary'] == 1 ? '( <b style="color:red;"> Document Library </b> )' : '';
                                                                    echo $document['status'] ? '' : '<b>(revoked)&nbsp;</b>';
                                                                    if ($document['manual_document_type'] == 'offer_letter') {
                                                                        echo '<b>(Manual Upload)</b>';
                                                                    }

                                                                    if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                        echo "<br><b>Assigned On: </b>" . date('M d Y, D', strtotime($document['assigned_date']));
                                                                    }

                                                                    if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                        echo "<br><b>Signed On: </b>" . date('M d Y, D', strtotime($document['signature_timestamp']));
                                                                    } else {
                                                                        echo "<br><b>Signed On: </b> N/A";
                                                                    }

                                                                    if ($document['approval_process'] == 1) {
                                                                        echo '<br><b class="text-danger">(Document Approval Pending)</b>';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <?php if ($document['letter_type'] == 'hybrid_document') { ?>
                                                                    <td>
                                                                        <button data-id="<?= $document['sid']; ?>" data-type="offer_letter" data-document="assigned" class="btn btn-success btn-sm btn-block js-hybrid-preview">
                                                                            Preview Assigned
                                                                        </button>
                                                                        <?php if ($document_all_permission) { ?>
                                                                            <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'completedOfferLetters'], $modifyBTN); ?>
                                                                            <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                                <?php
                                                                                $authorized_signature_url = '';

                                                                                if ($user_type == 'applicant') {
                                                                                    $authorized_signature_url = base_url('hr_documents_management/sign_authorized_signature_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid);
                                                                                } else {
                                                                                    $authorized_signature_url = base_url('hr_documents_management/sign_authorized_signature_document/employee/' . $document['sid'] . '/' . $user_sid);
                                                                                }
                                                                                ?>
                                                                                <a class="btn btn-success btn-sm btn-block" href="<?php echo $authorized_signature_url; ?>">
                                                                                    View Doc
                                                                                </a>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($document_all_permission) { ?>
                                                                            <button data-id="<?= $document['sid']; ?>" data-type="offer_letter" data-document="submitted" class="btn btn-success btn-sm btn-block <?= $document['submitted_description'] != '' ? 'js-hybrid-preview' : 'disabled'; ?>">
                                                                                Preview Submitted
                                                                            </button>
                                                                            <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                                <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                                                <a class="<?php echo $btn_show; ?> manage_authorized_signature" href="javascript:;" data-auth-sid="<?php echo $document['sid']; ?>" data-auth-signature="<?php echo $document['authorized_sign_status'] == 1 ? $document['authorized_signature'] : $current_user_signature; ?>">
                                                                                    <?php if ($document['authorized_sign_status'] == 0) { ?>
                                                                                        Employer Section - Not Completed
                                                                                    <?php } else if ($document['authorized_sign_status'] == 1) { ?>
                                                                                        Employer Section - Completed
                                                                                    <?php } ?>
                                                                                </a>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } else if ($document['document_type'] == 'offer_letter') { ?>
                                                                    <td class="col-lg-2">
                                                                        <?php if ($document['document_s3_name'] != '') { ?>
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>
                                                                                Preview Assigned
                                                                            </button>
                                                                            <?php if ($document_all_permission) { ?>
                                                                                <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'completedOfferLetters'], $modifyBTN); ?>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document">
                                                                                Preview Assigned
                                                                            </button>
                                                                            <?php if ($document_all_permission) { ?>
                                                                                <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'completedOfferLetters'], $modifyBTN); ?>
                                                                                <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                                    <?php
                                                                                    $authorized_signature_url = '';

                                                                                    if ($user_type == 'applicant') {
                                                                                        $authorized_signature_url = base_url('hr_documents_management/sign_authorized_signature_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid);
                                                                                    } else {
                                                                                        $authorized_signature_url = base_url('hr_documents_management/sign_authorized_signature_document/employee/' . $document['sid'] . '/' . $user_sid);
                                                                                    }
                                                                                    ?>

                                                                                    <a class="btn btn-success btn-sm btn-block" href="<?php echo $authorized_signature_url; ?>">
                                                                                        View Doc
                                                                                    </a>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="col-lg-2">
                                                                        <?php if ($document_all_permission) { ?>
                                                                            <?php if (isset($document['uploaded_file']) && !empty($document['uploaded_file'])) { ?>
                                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="submitted" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>" data-s3-name="<?php echo $document['uploaded_file']; ?>" <?php echo $document['user_consent'] != 1 ? 'disabled' : ''; ?>>
                                                                                    Preview Submitted
                                                                                </button>
                                                                            <?php } else { ?>
                                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="submitted" data-from="assigned_document" <?php echo $document['user_consent'] != 1 ? 'disabled' : ''; ?>>
                                                                                    Preview Submitted
                                                                                </button>
                                                                                <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                                    <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                                                    <a class="<?php echo $btn_show; ?> manage_authorized_signature" href="javascript:;" data-auth-sid="<?php echo $document['sid']; ?>" data-auth-signature="<?php echo $document['authorized_sign_status'] == 1 ? $document['authorized_signature'] : $current_user_signature; ?>">
                                                                                        <?php if ($document['authorized_sign_status'] == 0) { ?>
                                                                                            Employer Section - Not Completed
                                                                                        <?php } else if ($document['authorized_sign_status'] == 1) { ?>
                                                                                            Employer Section - Completed
                                                                                        <?php } ?>
                                                                                    </a>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class="col-lg-2">
                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>">Preview Document</button>
                                                                        <?php if ($document_all_permission) { ?>
                                                                            <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'completedOfferLetters'], $modifyBTN); ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="col-lg-2">
                                                                        <?php if ($document_all_permission) { ?>
                                                                            <?php
                                                                            $categories = isset($no_action_document_categories[$document['sid']]) ? json_encode($no_action_document_categories[$document['sid']]) : "[]";
                                                                            $manual_document_type = $document['manual_document_type'] == "offer_letter" ? true : false;
                                                                            $document_type = $document['document_type'] == "confidential" ? true : false;
                                                                            $assign_date = isset($document['assigned_date']) ? date('m-d-Y', strtotime($document['assigned_date'])) : '';
                                                                            $sign_date = isset($document['signature_timestamp']) ?  date('m-d-Y', strtotime($document['signature_timestamp'])) : '';


                                                                            ?>

                                                                            <button class="btn btn-success btn-sm btn-block" onclick="no_action_req_edit_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>" is-offer-letter="<?php echo $manual_document_type; ?>" data-categories='<?php echo $categories; ?>' data-update-accessible="<?php echo $document_type; ?>" assign-date="<?php echo $assign_date; ?>" sign-date="<?php echo $sign_date; ?>">Edit Documents</button>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } ?>
                                                                <td>
                                                                    <?php if ($document_all_permission) { ?>
                                                                        <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                            <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-block jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                        <?php } ?>
                                                                        <?php if ($user_type == 'applicant') { ?>
                                                                            <a class="btn btn-success  btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                        <?php } else { ?>
                                                                            <a class="btn btn-success  btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                        <?php } ?>
                                                                        <button class="btn btn-warning btn-sm btn-block" onclick="offer_letter_archive(<?php echo $document['sid']; ?>)">Archive</button>
                                                                        <?php if ($document['approval_process'] == 1) { ?>
                                                                            <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                View Approver(s)
                                                                            </button>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                            <?php //} 
                                                            ?>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Completed!</b></td>
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

                    <?php if (sizeof($completed_payroll_documents)) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed_payroll_documents">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php echo 'Payroll Documents'; ?>
                                                <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($completed_payroll_documents); ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="collapse_completed_payroll_documents" class="panel-collapse collapse">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-2 text-right">Actions</th>
                                                        <th class="col-lg-2 text-center">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($completed_payroll_documents)) { ?>
                                                        <?php foreach ($completed_payroll_documents as $document) { ?>
                                                            <?php
                                                            $GLOBALS['ad'][] = $document;
                                                            $completedDocumentsList[] = $document;
                                                            $cd++;
                                                            ?>
                                                            <?php if ($document["is_history"] == 0) { ?>
                                                                <?php if ($document['document_sid'] == 0) { ?>
                                                                    <tr>
                                                                        <td class="col-lg-6">
                                                                            <?php
                                                                            echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                            echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                            echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                            echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';
                                                                            echo $document['is_confidential'] ? '<br/><b> (Confidential)</b>' : '';

                                                                            if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                                            }

                                                                            if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                                echo "<br><b>Signed On: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], 'format' => 'M d Y, D',  '_this' => $this));
                                                                            } else {
                                                                                echo "<br><b>Signed On: </b> N/A";
                                                                            }

                                                                            if ($document['approval_process'] == 1) {
                                                                                echo '<br><b class="text-danger">(Document Approval Pending)</b>';
                                                                            }
                                                                            ?>
                                                                        </td>

                                                                        <?php if ($document['document_type'] == 'uploaded' || $document['document_type'] == 'confidential') { ?>
                                                                            <!-- Print Download by Adil-->
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
                                                                                    <a class="btn btn-success btn-sm btn-block" href="javascript:void(0);" onclick="fLaunchModal(this);" data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-file-name="<?php echo $document_filename; ?>" data-document-title="<?php echo $document_filename; ?>" data-preview-ext="<?php echo $document_extension ?>">Print</a>
                                                                                <?php } ?>
                                                                            </td>

                                                                            <td class="col-lg-1">
                                                                                <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['document_s3_name']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                            </td>
                                                                            <td class="col-lg-2">
                                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>
                                                                                    Preview Assigned
                                                                                </button>
                                                                                <?php if ($action_btn_flag == true && $document['isdoctolibrary'] == 0) { ?>
                                                                                    <?php
                                                                                    $categories = isset($no_action_document_categories[$document['sid']]) ? json_encode($no_action_document_categories[$document['sid']]) : "[]";
                                                                                    $manual_document_type = $document['manual_document_type'] == "offer_letter" ? true : false;
                                                                                    $document_type = $document['document_type'] == "confidential" ? true : false;
                                                                                    $assign_date = isset($document['assigned_date']) ? date('m-d-Y', strtotime($document['assigned_date'])) : '';
                                                                                    $sign_date = isset($document['signature_timestamp']) ?  date('m-d-Y', strtotime($document['signature_timestamp'])) : '';
                                                                                    ?>
                                                                                    <button class="btn btn-success btn-sm btn-block" onclick="no_action_req_edit_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>" is-offer-letter="<?php echo $manual_document_type; ?>" is-payroll-visible="<?php echo $document['visible_to_payroll'] == 1 ? true : false; ?>" data-categories='<?php echo $categories; ?>' data-update-accessible="<?php echo $document_type; ?>" assign-date="<?php echo $assign_date; ?>" sign-date="<?php echo $sign_date; ?>">Edit Document</button>
                                                                                    <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                                        <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                            <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                            <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                        </form>
                                                                                        <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                            </td>
                                                                        <?php } else { ?>
                                                                            <td class="col-lg-1">
                                                                                <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                            </td>

                                                                            <td class="col-lg-1">
                                                                                <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                            </td>
                                                                            <td class="col-lg-2"><button onclick="generated_document_original_preview(<?php echo $document['sid']; ?>);" class="btn btn-success btn-sm btn-block">Preview Document</button></td>
                                                                        <?php } ?>
                                                                        <td>
                                                                            <?php if ($document_all_permission) { ?>
                                                                                <a href="javascript:void(0);" class="btn btn-success btn-sm btn-block jsCategoryManagerBTN" title="Modify Category" data-asid="<?= $document['sid']; ?>" data-sid="<?= $document['document_sid']; ?>">Manage Category</a>
                                                                            <?php } ?>

                                                                            <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                <?php
                                                                                // if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_manage_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_manage_doc'))) { 
                                                                                if (true) {
                                                                                ?>
                                                                                    <?php if ($document['document_sid'] != 0) { ?>
                                                                                        <?php if ($document['status'] == 1) { ?>
                                                                                            <?php if ($user_type == 'applicant') { ?>
                                                                                                <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                                            <?php } else { ?>
                                                                                                <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                                            <?php } ?>
                                                                                        <?php } else { ?>
                                                                                            <button class="btn btn-warning btn-sm btn-block" onclick="func_document_revoked();">Manage Document</button>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                                <?php if ($document['approval_process'] == 1) { ?>
                                                                                    <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                        View Approver(s)
                                                                                    </button>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td class="col-lg-6">
                                                                            <?php
                                                                            echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                            echo $document['is_document_authorized'] == 1 && $document['authorized_sign_status'] == 0  ? '( <b style="color:red;"> Awaiting Authorized Signature </b> )' : '';
                                                                            echo $document['isdoctolibrary'] == 1 ? '( <b style="color:red;"> Document Library </b> )' : '';
                                                                            echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                            echo $document['is_confidential'] ? '<br/><b> (Confidential)</b>' : '';

                                                                            if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <?php if ($document['document_type'] == 'uploaded') { ?>
                                                                            <td class="col-lg-2">
                                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>
                                                                                    Preview Assigned
                                                                                </button>
                                                                            </td>
                                                                            <td class="col-lg-2">
                                                                                <?php if ($document_all_permission) { ?>
                                                                                    <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="submitted" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>" data-s3-name="<?php echo $document['uploaded_file']; ?>" <?php echo $document['user_consent'] != 1 ? 'disabled' : ''; ?>>
                                                                                        Preview Submitted
                                                                                    </button>
                                                                                <?php } ?>
                                                                            </td>
                                                                        <?php } else { ?>
                                                                            <td class="col-lg-2">
                                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document">
                                                                                    Preview Assigned
                                                                                </button>
                                                                                <?php if ($document_all_permission) { ?>
                                                                                    <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                                        <?php
                                                                                        $authorized_signature_url = '';

                                                                                        if ($user_type == 'applicant') {
                                                                                            $authorized_signature_url = base_url('hr_documents_management/sign_authorized_signature_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid);
                                                                                        } else {
                                                                                            $authorized_signature_url = base_url('hr_documents_management/sign_authorized_signature_document/employee/' . $document['sid'] . '/' . $user_sid);
                                                                                        }
                                                                                        ?>

                                                                                        <a class="btn btn-success btn-sm btn-block" href="<?php echo $authorized_signature_url; ?>">
                                                                                            View Doc
                                                                                        </a>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td class="col-lg-2">
                                                                                <?php if ($document_all_permission) { ?>
                                                                                    <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="submitted" data-from="assigned_document" <?php echo $document['user_consent'] != 1 ? 'disabled' : ''; ?>>
                                                                                        Preview Submitted
                                                                                    </button>
                                                                                    <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                                        <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                                                        <a class="<?php echo $btn_show; ?> manage_authorized_signature" href="javascript:;" data-auth-sid="<?php echo $document['sid']; ?>" data-auth-signature="<?php echo $document['authorized_sign_status'] == 1 ? $document['authorized_signature'] : $current_user_signature; ?>">
                                                                                            <?php if ($document['authorized_sign_status'] == 0) { ?>
                                                                                                Employer Section - Not Completed
                                                                                            <?php } else if ($document['authorized_sign_status'] == 1) { ?>
                                                                                                Employer Section - Completed
                                                                                            <?php } ?>
                                                                                        </a>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <td>
                                                                            <?php if ($document_all_permission) { ?>
                                                                                <a href="javascript:void(0);" class="btn btn-success btn-sm btn-block jsCategoryManagerBTN" title="Modify Category" data-asid="<?= $document['sid']; ?>" data-sid="<?= $document['document_sid']; ?>">Manage Category</a>

                                                                            <?php } ?>
                                                                            <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                <?php
                                                                                //if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_manage_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_manage_doc'))) { 
                                                                                if (true) {
                                                                                ?>
                                                                                    <?php if ($document['document_sid'] != 0) { ?>
                                                                                        <?php if ($document['status'] == 1) { ?>
                                                                                            <?php if ($user_type == 'applicant') { ?>
                                                                                                <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                                            <?php } else { ?>
                                                                                                <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                                            <?php } ?>
                                                                                        <?php } else { ?>
                                                                                            <button class="btn btn-warning btn-sm btn-block" onclick="func_document_revoked();">Manage Document</button>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                                <?php if ($document['approval_process'] == 1) { ?>
                                                                                    <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                        View Approver(s)
                                                                                    </button>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <?php $this->load->view('hr_documents_management/document_assign_history_row', ['user_sid' => $user_sid, 'user_type' => $user_type, 'history_document' => $document, "document_all_permission" => $document_all_permission]); ?>
                                                            <?php } ?>
                                                            <?php if (!empty($document["history"])) { ?>
                                                                <?php foreach ($document["history"] as $history_document) { ?>
                                                                    <?php $cd++; ?>
                                                                    <?php $this->load->view('hr_documents_management/document_assign_history_row', ['user_sid' => $user_sid, 'user_type' => $user_type, 'history_document' => $history_document, "document_all_permission" => $document_all_permission]); ?>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Completed!</b></td>
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
                    <!-- Category Completed Document End -->

                    <?php if (!empty($completed_w4) || !empty($completed_w9) || !empty($completed_i9)) { ?>
                        <?php $cvd = count($completed_w4) + count($completed_w9) + count($completed_i9); ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed-1">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                Employment Eligibility Verification Document
                                                <div class="pull-right total-records">
                                                    <b>&nbsp;Total: <span class="js-cdi"><?php echo $cvd; ?></span> </b>
                                                </div>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_completed-1" class="panel-collapse collapse">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th scope="column" class="col-lg-8">
                                                            Document Name
                                                        </th>
                                                        <th scope="column" class="col-lg-2">
                                                            Status
                                                        </th>
                                                        <th scope="column" class="col-lg-2">
                                                            Actions
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($completed_w4)) { ?>
                                                        <?php foreach ($completed_w4 as $w4_form) { ?>
                                                            <?php $cd++; ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    W4 Fillable
                                                                    <br />
                                                                    <strong>Assigned on: </strong>
                                                                    <?php echo date('M d Y, D', strtotime($w4_form['sent_date'])); ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <?php echo $w4_form['form_status']; ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <a href="javascript:;" data-type="W4_Form" data-section="complete_pdf" data-status="<?php echo $w4_form['form_status']; ?>" data-doc_sid="<?php echo $w4_form['sid']; ?>" class="btn btn-success btn-block jsShowVarificationDocument" title="" placement="top" data-original-title="View W4 form">View W4</a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if (!empty($completed_w9)) { ?>
                                                        <?php foreach ($completed_w9 as $w9_form) { ?>
                                                            <?php $cd++; ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    W9 Fillable
                                                                    <br />
                                                                    <strong>Assigned on: </strong>
                                                                    <?php echo date('M d Y, D', strtotime($w9_form['sent_date'])); ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <?php echo $w9_form['form_status']; ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <a href="javascript:;" data-type="W9_Form" data-section="complete_pdf" data-status="<?php echo $w9_form['form_status']; ?>" data-doc_sid="<?php echo $w9_form['sid']; ?>" class="btn btn-success btn-block jsShowVarificationDocument" title="" placement="top" data-original-title="View W9 form">View W9</a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if (!empty($completed_i9)) { ?>
                                                        <?php foreach ($completed_i9 as $i9_form) { ?>
                                                            <?php $cd++; ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    I9 Fillable
                                                                    <br />
                                                                    <strong>Assigned on: </strong>
                                                                    <?php echo date('M d Y, D', strtotime($i9_form['sent_date'])); ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <?php echo $i9_form['form_status']; ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <a href="javascript:;" data-type="I9_Form" data-section="complete_pdf" data-status="<?php echo $i9_form['form_status']; ?>" data-doc_sid="<?php echo $i9_form['sid']; ?>" class="btn btn-success btn-block jsShowVarificationDocument" title="" placement="top" data-original-title="View I9 form">View I9</a>
                                                                </td>

                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- Signed Document End -->

            <!-- Completed Document End -->

            <!-- No Action Required Document Start -->
            <div id="no_action_required_doc_details" class="tab-pane fade in hr-innerpadding">
                <div class="panel-body">
                    <!-- Category No Action Required Document Start -->
                    <?php if (!empty($categories_no_action_documents)) { ?>
                        <h2 class="tab-title">No Action Required Document Detail
                            <span class="pull-right">
                                <a href="<?= base_url('download/' . ($user_type) . '/' . ($user_sid) . '/noActionRequired'); ?>" target="_blank" class="btn btn-success">Download Document(s)</a>
                            </span>
                            <hr />
                        </h2>
                        <?php foreach ($categories_no_action_documents as $category_document) { ?>
                            <?php if ($category_document['category_sid'] != 27) { ?>
                                <?php if (isset($category_document['documents'])) { ?>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="panel panel-default hr-documents-tab-content">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_no_action<?php echo $category_document['category_sid']; ?>">
                                                            <span class="glyphicon glyphicon-plus"></span>
                                                            <?php echo $category_document['name']; ?>
                                                            <?php
                                                            $total_record = 0;
                                                            if (count($category_document['documents']) > 0) {
                                                                foreach ($category_document['documents'] as $cou => $document) {
                                                                    if ($document['archive'] != 1 && $document['manual_document_type'] != 'offer_letter') {
                                                                        $total_record = $total_record + 1;
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . $total_record; ?></b></div>
                                                        </a>

                                                    </h4>
                                                </div>

                                                <div id="collapse_no_action<?php echo $category_document['category_sid']; ?>" class="panel-collapse collapse">
                                                    <div class="table-responsive full-width">
                                                        <table class="table table-plane">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-lg-6">Document Name</th>
                                                                    <th class="col-lg-6 text-center" colspan="4">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (count($category_document['documents']) > 0) { ?>
                                                                    <?php foreach ($category_document['documents'] as $document) { ?>
                                                                        <?php if ($document['archive'] != 1 && $document['manual_document_type'] != 'offer_letter') { ?>
                                                                            <?php $noActionRequiredDocumentsList[] = $document; ?>
                                                                            <?php $nad++; ?>
                                                                            <tr>
                                                                                <td class="col-lg-6">
                                                                                    <?php
                                                                                    echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                                    echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                                    echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                                    echo $document['isdoctolibrary'] == 1 ? '( <b style="color:red;"> Document Library </b> )' : '';
                                                                                    echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';
                                                                                    echo $document['is_confidential'] ? '<br/><b> (Confidential)</b>' : '';

                                                                                    if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                                                    }

                                                                                    if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                                        echo "<br><b>Signed On: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], 'format' => 'M d Y, D',  '_this' => $this));
                                                                                    } else {
                                                                                        echo "<br><b>Signed On: </b> N/A";
                                                                                    }

                                                                                    if ($document['approval_process'] == 1) {
                                                                                        echo '<br><b class="text-danger">(Document Approval Pending)</b>';
                                                                                    }
                                                                                    ?>
                                                                                </td>

                                                                                <?php if ($document['document_type'] == 'uploaded' || $document['document_type'] == 'confidential') { ?>
                                                                                    <?php
                                                                                    $no_action_document_url = $document['document_s3_name'];
                                                                                    $no_action_document_info = get_required_url($no_action_document_url);
                                                                                    $no_action_print_url = $no_action_document_info['print_url'];
                                                                                    $no_action_download_url = $no_action_document_info['download_url'];
                                                                                    ?>
                                                                                    <!-- Print Download by Adil-->
                                                                                    <td class="col-lg-1">

                                                                                        <?php

                                                                                        if ($document['fillable_documents_slug'] != null && $document['fillable_documents_slug'] != '') {
                                                                                            $no_action_print_url = base_url('v1/fillable_documents/PrintPrevieFillable/' . $document['fillable_documents_slug'] . '/' . $document['document_sid'] . '/original/print');
                                                                                            $no_action_download_url = base_url('v1/fillable_documents/PrintPrevieFillable/' . $document['fillable_documents_slug'] . '/' . $document['document_sid'] . '/original/download');
                                                                                        }

                                                                                        ?>
                                                                                        <a target="_blank" href="<?php echo $no_action_print_url; ?>" class="btn btn-success btn-sm btn-block">
                                                                                            Print
                                                                                        </a>

                                                                                        <a href="<?php echo $no_action_download_url; ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                    </td>
                                                                                    <td class="col-lg-2">

                                                                                        <?php if ($document['document_sid'] != 0 && $document['fillable_documents_slug'] != null && $document['fillable_documents_slug'] != '') { ?>

                                                                                            <button class="btn btn-success btn-sm btn-block" onclick="fLaunchModalFillable(this);" date-letter-type="generated" data-on-action="assigned" data-preview-url="<?php echo $document['fillable_documents_slug']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>" data-document-sid="<?php echo $document['document_sid']; ?>">Preview Document
                                                                                            </button>

                                                                                            <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                                <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'noActionDocuments'], $modifyBTN); ?>
                                                                                            <?php } ?>

                                                                                        <?php } elseif ($document['document_sid'] != 0) { ?>
                                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>">Preview Document
                                                                                            </button>
                                                                                            <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                                <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'noActionDocuments'], $modifyBTN); ?>
                                                                                            <?php } ?>

                                                                                        <?php } else if ($document['document_sid'] == 0) { ?>
                                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>">Preview Document
                                                                                            </button>
                                                                                            <?php if ($action_btn_flag == true) { ?>
                                                                                                <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                                    <?php
                                                                                                    $categories = isset($no_action_document_categories[$document['sid']]) ? json_encode($no_action_document_categories[$document['sid']]) : "[]";
                                                                                                    $manual_document_type = $document['manual_document_type'] == "offer_letter" ? true : false;
                                                                                                    $document_type = $document['document_type'] == "confidential" ? true : false;
                                                                                                    $assign_date = isset($document['assigned_date']) ? date('m-d-Y', strtotime($document['assigned_date'])) : '';
                                                                                                    $sign_date = isset($document['signature_timestamp']) ?  date('m-d-Y', strtotime($document['signature_timestamp'])) : '';
                                                                                                    ?>
                                                                                                    <button class="btn btn-success btn-sm btn-block" onclick="no_action_req_edit_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>" is-offer-letter="<?php echo $manual_document_type; ?>" is-payroll-visible="<?php echo $document['visible_to_payroll'] == 1 ? true : false; ?>" data-categories='<?php echo $categories; ?>' data-update-accessible="<?php echo $document_type; ?>" assign-date="<?php echo $assign_date; ?>" sign-date="<?php echo $sign_date; ?>">Edit Document</button>
                                                                                                    <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                                                        <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                                            <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                                            <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                                        </form>
                                                                                                        <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                                                    <?php } ?>
                                                                                                <?php } ?>
                                                                                            <?php } ?>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                <?php } else { ?>
                                                                                    <td class="col-lg-1">
                                                                                        <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>

                                                                                        <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                    </td>
                                                                                    <td class="col-lg-2">
                                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document">
                                                                                            Preview Assigned
                                                                                        </button>
                                                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                            <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'noActionDocuments'], $modifyBTN); ?>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                <?php } ?>
                                                                                <td>
                                                                                    <?php if ($document_all_permission) { ?>
                                                                                        <a href="javascript:void(0);" class="btn btn-sm btn-success jsCategoryManagerBTN" title="Modify Category" data-asid="<?= $document['sid']; ?>" data-sid="<?= $document['document_sid']; ?>">Manage Category</a>
                                                                                        <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                                            <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-block jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                    <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                        <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/' . ($user_type) . '/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                                        <?php if ($document['is_document_authorized'] == 1) { ?>
                                                                                            <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                                                            <a class="<?php echo $btn_show; ?> manage_authorized_signature" href="javascript:;" data-auth-sid="<?php echo $document['sid']; ?>" data-auth-signature="<?php echo $document['authorized_sign_status'] == 1 ? $document['authorized_signature'] : $current_user_signature; ?>">
                                                                                                <?php if ($document['authorized_sign_status'] == 0) { ?>
                                                                                                    Employer Section - Not Completed
                                                                                                <?php } else if ($document['authorized_sign_status'] == 1) { ?>
                                                                                                    Employer Section - Completed
                                                                                                <?php } ?>
                                                                                            </a>
                                                                                        <?php } ?>
                                                                                        <?php if ($document['approval_process'] == 1) { ?>
                                                                                            <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                                View Approver(s)
                                                                                            </button>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
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
                        <?php } ?>
                    <?php } else {
                    ?>
                        <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                    <?php
                    } ?>

                    <?php if (sizeof($no_action_required_payroll_documents)) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_no_action_required_payroll_documents">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php echo 'Payroll Documents'; ?>
                                                <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($no_action_required_payroll_documents); ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="collapse_no_action_required_payroll_documents" class="panel-collapse collapse">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-2 text-right">Actions</th>
                                                        <th class="col-lg-2 text-center">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($no_action_required_payroll_documents)) { ?>
                                                        <?php foreach ($no_action_required_payroll_documents as $document) {
                                                            if (!$document['status']) {
                                                                continue;
                                                            } ?>
                                                            <?php $nad++;
                                                            $noActionRequiredDocumentsList[] = $document; ?>
                                                            <tr>
                                                                <td class="col-lg-6">
                                                                    <?php
                                                                    echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                    echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                    echo $document['isdoctolibrary'] == 1 ? '( <b style="color:red;"> Document Library </b> )' : '';
                                                                    echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                    echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';
                                                                    echo $document['is_confidential'] ? '<br/><b> (Confidential)</b>' : '';

                                                                    if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                                    }

                                                                    if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                        echo "<br><b>Signed On: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], 'format' => 'M d Y, D',  '_this' => $this));
                                                                    } else {
                                                                        echo "<br><b>Signed On: </b> N/A";
                                                                    }

                                                                    if ($document['approval_process'] == 1) {
                                                                        echo '<br><b class="text-danger">(Document Approval Pending)</b>';
                                                                    }
                                                                    ?>
                                                                </td>

                                                                <?php if ($document['document_type'] == 'uploaded' || $document['document_type'] == 'confidential') { ?>
                                                                    <!-- Print Download by Adil-->
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
                                                                            <a class="btn btn-success btn-sm btn-block" href="javascript:void(0);" onclick="fLaunchModal(this);" data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-file-name="<?php echo $document_filename; ?>" data-document-title="<?php echo $document_filename; ?>" data-preview-ext="<?php echo $document_extension ?>">Print</a>
                                                                        <?php } ?>
                                                                    </td>

                                                                    <td class="col-lg-1">
                                                                        <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['document_s3_name']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                    </td>
                                                                    <td class="col-lg-2">

                                                                        <?php if ($document['document_sid'] != 0) { ?>
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>">Preview Document
                                                                            </button>
                                                                        <?php } else if ($document['document_sid'] == 0) { ?>
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>">Preview Document
                                                                            </button>
                                                                            <?php if ($action_btn_flag == true) { ?>
                                                                                <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                    <?php
                                                                                    $categories = isset($no_action_document_categories[$document['sid']]) ? json_encode($no_action_document_categories[$document['sid']]) : "[]";
                                                                                    $manual_document_type = $document['manual_document_type'] == "offer_letter" ? true : false;
                                                                                    $document_type = $document['document_type'] == "confidential" ? true : false;
                                                                                    $assign_date = isset($document['assigned_date']) ? date('m-d-Y', strtotime($document['assigned_date'])) : '';
                                                                                    $sign_date = isset($document['signature_timestamp']) ?  date('m-d-Y', strtotime($document['signature_timestamp'])) : '';
                                                                                    ?>
                                                                                    <button class="btn btn-success btn-sm btn-block" onclick="no_action_req_edit_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>" is-offer-letter="<?php echo $manual_document_type; ?>" is-payroll-visible="<?php echo $document['visible_to_payroll'] == 1 ? true : false; ?>" data-categories='<?php echo $categories; ?>' data-update-accessible="<?php echo $document_type; ?>" assign-date="<?php echo $assign_date; ?>" sign-date="<?php echo $sign_date; ?>">Edit Document</button>
                                                                                    <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                                        <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                            <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                            <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                        </form>
                                                                                        <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class="col-lg-1">
                                                                        <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                    </td>

                                                                    <td class="col-lg-1">
                                                                        <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                    </td>
                                                                    <td class="col-lg-2">
                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document">
                                                                            Preview Document
                                                                        </button>
                                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                            <?= str_replace(['{{sid}}', '{{type}}'], [$document['document_sid'], 'noActionDocuments'], $modifyBTN); ?>
                                                                    </td>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            <td>
                                                                <?php if ($document_all_permission) { ?>
                                                                    <a href="javascript:void(0);" class="btn btn-success btn-sm btn-block jsCategoryManagerBTN" title="Modify Category" data-asid="<?= $document['sid']; ?>" data-sid="<?= $document['document_sid']; ?>">Manage Category</a>

                                                                    <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-block jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                    <?php } ?>

                                                                <?php } ?>
                                                                <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                    <?php
                                                                    // if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_manage_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_manage_doc'))) {
                                                                    if (true) {
                                                                    ?>
                                                                        <?php if ($document['status'] == 1) { ?>
                                                                            <?php if ($user_type == 'applicant') { ?>
                                                                                <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                            <?php } else { ?>
                                                                                <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <button class="btn btn-warning btn-sm btn-block" onclick="func_document_revoked();">Manage Document</button>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                    <?php if ($document['approval_process'] == 1) { ?>
                                                                        <button data-document_sid="<?= $document['document_sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                            View Approver(s)
                                                                        </button>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Completed!</b></td>
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
                    <!-- Category No Action Required Document End -->
                </div>
            </div>
            <!-- No Action Required Document End -->
        </div>
    </div>
</div>




<div id="performance_doc_section2_Modal" class="modal fade" role="dialog">
    <?php
    $userPrefillInfo  = [];
    $userPrefillInfo = get_employee_profile_info_detail($EmployeeSid, $Type);
    ?>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Performance Section1</h4>
            </div>
            <div class="modal-body">
                <div id="performance_doc_section1">
                    <form id="employee_performance_doc_section1" enctype="multipart/form-data" method="POST">
                        <input type="hidden" name="perform_action" value="employee_performance_doc_section1" />
                        <input type="hidden" name="user_sid" value="<?php echo $employer_sid; ?>" />
                        <input type="hidden" name="document_sid" id='performance_document_sid' />
                        <input type="hidden" name="employee_sid" id='employee_sid' />
                        <input type="hidden" name="employee_type" id='employee_type' />


                        <!-- Section to populate current user info -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">

                                    <section class="pdf-cover-page">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td width="50%" style="border-top:0px;"><br><br>
                                                        <strong style="font-size: 14px;">Manager Section 1: Employee Year in Review Evaluation
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <strong> Employee Name <span class="staric">*</span></strong>
                                                                    </td>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <strong> Job Title <span class="staric">*</span></strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empName']; ?>" name="empName" id="empName" data-type='text' autocomplete="off" />
                                                                        <label id="empNameError" class="error"></label>

                                                                    </td>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empJobTitle']; ?>" name="empJobTitle" id="empJobTitle" data-type='text' autocomplete="off" />
                                                                        <label id="empJobTitleError" class="error"></label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <strong> Department <span class="staric">*</span></strong>
                                                                    </td>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <strong> Manager <span class="staric">*</span></strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empDepartment']; ?>" name="empDepartment" id="empDepartment" data-type='text' autocomplete="off" />
                                                                        <label id="empDepartmentError" class="error"></label>

                                                                    </td>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empSupervisor']; ?>" name="empManager" id="empManager" data-type='text' autocomplete="off" />
                                                                        <label id="empManagerError" class="error"></label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <strong> Hire Date with DeFOUW Automotive <span class="staric">*</span></strong>
                                                                    </td>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <strong> Start Date in Current Position <span class="staric">*</span></strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <input class="invoice-fields short_textbox date_picker2" type="text" value="<?php echo $userPrefillInfo['empJoinedAt']; ?>" name="empHireDate" id="empHireDate" data-type='text' autocomplete="off" readonly/>
                                                                        <label id="empHireDateError" class="error"></label>

                                                                    </td>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <input class="invoice-fields short_textbox date_picker2" type="text" value="<?php echo $userPrefillInfo['empJoinedAt']; ?>" name="empStartDate" id="empStartDate" data-type='text' autocomplete="off" readonly />
                                                                        <label id="empStartDateError" class="error"></label>

                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <strong> Review Period Start <span class="staric">*</span></strong>
                                                                    </td>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <strong> Review Period End <span class="staric">*</span></strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <input class="invoice-fields short_textbox date_picker2" type="text" value="<?= $formInputData['short_textbox_6'] ? $formInputData['short_textbox_6'] : '' ?>" name="reviewPeriodStart" id="reviewPeriodStart" data-type='text' autocomplete="off" readonly/>
                                                                        <label id="reviewPeriodStartError" class="error"></label>

                                                                    </td>
                                                                    <td width="50%" style="font-size: 14px;">
                                                                        <input class="invoice-fields short_textbox date_picker2" type="text" value="<?= $formInputData['short_textbox_7'] ? $formInputData['short_textbox_7'] : '' ?>" name="reviewPeriodEnd" id="reviewPeriodEnd" data-type='text' autocomplete="off" readonly />
                                                                        <label id="reviewPeriodEndError" class="error"></label>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </section>

                                    <section class="pdf-cover-page">
                                        <table class="table table-border-collapse" style="margin-top: -10px;">
                                            <tbody>

                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <strong style="font-size: 14px;">Rate the employee in each area below. Comments are required for each section. </strong><br>

                                                        <strong style="font-size: 14px;"> POSITION KNOWLEDGE: </strong> To what level is this employee knowledgeable of the job duties of the position to include methods, procedures, standard practices, and techniques? This may have been acquired through formal training, education and/or experience.

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <table class="table" style="border-collapse: collapse;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Knowledge is below the minimum requirements of the position. Improvement is mandatory. </strong>
                                                                    </td>
                                                                    <td style="solid; font-size: 14px;">
                                                                        <strong> Knowledge is sufficient to perform the requirements of the position.</strong>
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee is exceptionally well informed and competent in all aspects of the position..</strong>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio"  name="knowledgeBelow" id="knowledgeBelow" value="knowledgeBelow" checked>
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="knowledgeBelow" id="knowledgeSufficient" value="knowledgeSufficient">
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="knowledgeBelow" id="knowledgeExceptionally" value="knowledgeSufficient">
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="3" width="50%" style="font-size: 14px;">
                                                                        <strong>Comment: <span class="staric">*</span></strong>
                                                                        <textarea id="knowledgeComment" name="knowledgeComment" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                                                        <div id='long_textbox_0_id_sec'> </div>
                                                                        <label id="knowledgeCommentError" class="error"></label>


                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </section>

                                    <section class="pdf-cover-page">
                                        <table class="table table-border-collapse" style="margin-top: -10px;">
                                            <tbody>

                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <strong style="font-size: 14px;">How may the employee’s position knowledge be improved?. <?= $formInputData['short_textbox_9'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>

                                                        <strong style="font-size: 14px;"> QUANTITY OF WORK: </strong> Evaluate the quantity of work produced.

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <table class="table" style="border-collapse: collapse;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Output is below that required of the position. Improvement is mandatory. </strong>
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Output meets that required of the position.</strong>
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <strong> Output consistently exceeds that required of the position.</strong>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="outputBelow" id="outputBelow" value="outputBelow" checked>

                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="outputBelow" id="outputMeets" value="outputMeets">
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="outputBelow" id="outputConsistently" value="outputConsistently">
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="3" width="50%" style="font-size: 14px;">
                                                                        <strong>Comment: <span class="staric">*</span></strong>
                                                                        <textarea id="outputComment" name="outputComment" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                                                        <div id='long_textbox_1_id_sec'> </div>
                                                                        <label id="outputCommentError" class="error"></label>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </section>

                                    <section class="pdf-cover-page">
                                        <table class="table table-border-collapse" style="margin-top: -10px;">
                                            <tbody>
                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <strong style="font-size: 14px;">How may the employee’s quantity of work be improved?. <?= $formInputData['short_textbox_11'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>
                                                        <strong style="font-size: 14px;"> QUANTITY OF WORK: </strong> Evaluate the quality of work produced in accordance with requirements for accuracy, completeness, and attention to detail.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <table class="table" style=" border-collapse: collapse;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Quality of work is frequently below position requirements. Improvement is mandatory. </strong>

                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Quality of work meets position requirements.</strong>
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <strong> Quality of work consistently exceeds position requirements.</strong>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="qualityBelow" id="qualityBelow" value="qualityBelow" checked>
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="qualityBelow" id="qualityMeets" value="qualityMeets" >
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="qualityBelow" id="qualityConsistently" value="qualityConsistently">
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="3" width="50%" style="font-size: 14px;">
                                                                        <strong>Comment: <span class="staric">*</span></strong>
                                                                        <textarea id="qualityComment" name="qualityComment" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                                                        <div id='long_textbox_2_id_sec'> </div>
                                                                        <label id="qualityCommentError" class="error"></label>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </section>
                                    <section class="pdf-cover-page">
                                        <table class="table table-border-collapse" style="margin-top: -10px;">
                                            <tbody>

                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <strong style="font-size: 14px;">How may the employee’s quantity of work be improved?. <?= $formInputData['short_textbox_13'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>
                                                        <strong style="font-size: 14px;"> INTERPERSONAL RELATIONS: </strong> o what level does this individual demonstrate cooperative behavior and contribute to a supportive work environment?.
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <table class="table" style="border-collapse: collapse;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee is frequently non-supportive. Improvement is mandatory. </strong>
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee adequately contributes to supportive environment.</strong>
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee consistently contributes to supportive work environment.</strong>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeeFrequently" id="employeeFrequently" value="employeeFrequently" checked>
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeeFrequently" id="employeeAdequately" value="employeeAdequately">
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeeFrequently" id="employeeConsistently" value="employeeConsistently">
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="3" width="50%" style="font-size: 14px;">
                                                                        <strong>Comment: <span class="staric">*</span></strong>
                                                                        <textarea id="employeeComment" name="employeeComment" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                                                        <div id='long_textbox_3_id_sec'> </div>
                                                                        <label id="employeeCommentError" class="error"></label>

                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </section>

                                    <section class="pdf-cover-page">
                                        <table class="table table-border-collapse" style="margin-top: -10px;">
                                            <tbody>
                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <strong style="font-size: 14px;">How may the employee’s interpersonal relations be improved?. <?= $formInputData['short_textbox_15'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>

                                                        <strong style="font-size: 14px;"> Mission: </strong> To what level does the employees work support the Mission of the organization; To what level does the employee make themselves available to respond to needs of others both internally and externally?
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <table class="table" style="border-collapse: collapse;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Level of mission focus is often below the required/acceptable standard. Improvement is mandatory. </strong>
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee adequately contributes to high quality mission.</strong>
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee consistently demonstrates exceptional commitment to the mission.</strong>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="missionBelow" id="missionBelow" value="missionBelow" checked>
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="missionBelow" id="missionHigh" value="missionHigh" >
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="missionBelow" id="missionExceptional" value="missionExceptional" >
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="3" width="50%" style="font-size: 14px;">
                                                                        <strong>Comment: <span class="staric">*</span></strong>
                                                                        <textarea id="missionComment" name="missionComment" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                                                        <div id='long_textbox_4_id_sec'> </div>
                                                                        <label id="missionCommentError" class="error"></label>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </section>

                                    <section class="pdf-cover-page">
                                        <table class="table table-border-collapse" style="margin-top: -10px;">
                                            <tbody>
                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <strong style="font-size: 14px;">How may the employee’s customer service skills/delivery be improved?. <?= $formInputData['short_textbox_17'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>
                                                        <strong style="font-size: 14px;"> DEPENDABILITY: </strong> To what level is the employee dependable; How often does the employee show up to work on time and complete their scheduled shifts? Can the employee be counted on to complete tasks and meet deadlines consistently?
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <table class="table" style="border-collapse: collapse;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee is late, absent, misses deadlines. Improvement is mandatory. </strong>
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee adequately attends work, rarely misses or late, meets deadlines.</strong>
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee consistently on time, at work and completes deadlines ahead of schedule.</strong>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeeLate" id="employeeLate" value="employeeLate" checked>
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeeLate" id="employeeAdequatelyAttends" value="employeeAdequatelyAttends" >
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeeLate" id="employeeOnTime" value="employeeOnTime">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3" width="50%" style="font-size: 14px;">
                                                                        <strong>Comment: <span class="staric">*</span></strong>
                                                                        <textarea id="employeeTimeComment" name="employeeTimeComment" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                                                        <div id='long_textbox_5_id_sec'> </div>
                                                                        <label id="employeeTimeCommentError" class="error"></label>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </section>

                                    <section class="pdf-cover-page">
                                        <table class="table table-border-collapse" style="margin-top: -10px;">
                                            <tbody>
                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <strong style="font-size: 14px;">How may the employee’s dependability be improved?. <?= $formInputData['short_textbox_19'] ? $formInputData['short_textbox_19'] : '' ?> </strong><br>
                                                        <strong style="font-size: 14px;"> ADHERENCE TO POLICY & PROCEDURE: </strong> To what level does the employee adhere to standard operating policies and procedures?
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <table class="table" style="border-collapse: collapse;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee is frequently coached on standard operating policies and procedures. Improvement is mandatory. </strong>
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee adequately adheres to standard operating policies and procedures with few reminders.</strong>
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee is consistently exceptional in following standard operating policies and procedures..</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeeFrequentlyCoached" id="employeeFrequentlyCoached" value="employeeFrequentlyCoached" checked>
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeeFrequentlyCoached" id="employeeAdequatelyAdheres" value="employeeAdequatelyAdheres">
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeeFrequentlyCoached" id="employeeConsistentlyExceptional" value="employeeConsistentlyExceptional">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3" width="50%" style="font-size: 14px;">
                                                                        <strong>Comment: <span class="staric">*</span></strong>
                                                                        <textarea id="employeeFrequentlyCoachedComment" name="employeeFrequentlyCoachedComment" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                                                        <div id='long_textbox_6_id_sec'> </div>
                                                                        <label id="employeeFrequentlyCoachedCommentError" class="error"></label>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </section>

                                    <section class="pdf-cover-page">
                                        <table class="table table-border-collapse" style="margin-top: -10px;">
                                            <tbody>

                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <strong style="font-size: 14px;">How may the employee’s adherence to policy and procedure be improved?. <?= $formInputData['short_textbox_21'] ? $formInputData['short_textbox_21'] : '' ?> </strong><br>
                                                        <strong style="font-size: 14px;"> OTHER: </strong> <?= $formInputData['short_textbox_22'] ? $formInputData['short_textbox_22'] : '' ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <table class="table" style="border-collapse: collapse;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee frequently falls below acceptable standard as outlined above. </strong>

                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee adequately meets standard as outlined above.</strong>
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee is consistently exceptional in meeting performance standard.</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeeOutlinedAbove" id="employeeOutlinedAbove" value="employeeOutlinedAbove" checked>
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeeOutlinedAbove" id="employeeOutlinedStandardAbove" value="employeeOutlinedStandardAbove" >
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeeOutlinedAbove" id="employeeOutlinedStandard" value="employeeOutlinedStandard">
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="3" width="50%" style="font-size: 14px;">
                                                                        <strong>Comment: <span class="staric">*</span></strong>
                                                                        <textarea id="employeeOutlinedComment" name="employeeOutlinedComment" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                                                        <div id='long_textbox_7_id_sec'> </div>
                                                                        <label id="employeeOutlinedCommentError" class="error"></label>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </section>

                                    <section class="pdf-cover-page">
                                        <table class="table table-border-collapse" style="margin-top: -10px;">
                                            <tbody>
                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <strong style="font-size: 14px;"> How may employee’s performance in meeting this standard be improved? <?= $formInputData['short_textbox_24'] ? $formInputData['short_textbox_24'] : '' ?> </strong><br>
                                                        <strong style="font-size: 14px;"> OTHER: </strong> <?= $formInputData['short_textbox_25'] ? $formInputData['short_textbox_25'] : '' ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="50%" style="border-top:0px;">
                                                        <table class="table" style="border-collapse: collapse;">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee frequently falls below acceptable standard as outlined above. </strong>

                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee adequately meets standard as outlined above.</strong>
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <strong> Employee is consistently exceptional in meeting performance standard.</strong>
                                                                    </td>
                                                                </tr>


                                                                <tr>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeePerformanceOutlinedAbove" id="employeePerformanceOutlinedAbove" value="employeePerformanceOutlinedAbove" checked >
                                                                    </td>
                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeePerformanceOutlinedAbove" id="employeePerformanceOutlinedStandard" value="employeePerformanceOutlinedStandard">
                                                                    </td>

                                                                    <td style="font-size: 14px;">
                                                                        <input type="radio" name="employeePerformanceOutlinedAbove" id="employeePerformanceOutlinedExceptional" value="employeePerformanceOutlinedExceptional">
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="3" width="50%" style="font-size: 14px;">
                                                                        <strong>Comment: <span class="staric">*</span></strong>
                                                                        <textarea id="employeePerformanceComment" name="employeePerformanceComment" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                                                        <div id='long_textbox_8_id_sec'> </div>
                                                                        <label id="employeePerformanceCommentError" class="error"></label>

                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="3" width="50%" style="font-size: 14px;">
                                                                        <strong>How may employee’s performance in meeting this standard be improved?</strong> <?= $formInputData['short_textbox_27'] ? $formInputData['short_textbox_27'] : '' ?>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="3" width="50%" style="font-size: 14px;">
                                                                        <strong>Managers Additional Comments for the Review Period: </strong>
                                                                        <textarea id="managersAdditionalComments" name="managersAdditionalComments" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                                                        <div id='long_textbox_9_id_sec'> </div>
                                                                        <label id="managersAdditionalCommentsError" class="error"></label>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </section>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button id="performanceSection1Save" type="button" class="btn btn-success break-word-text">Save</button>
                                <button type="button" class="btn cancel_btn_black" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div id="performance_doc_section3_Modal" class="modal fade" role="dialog">
    <?php
    $userPrefillInfo  = [];
    $userPrefillInfo = get_employee_profile_info_detail($EmployeeSid, $Type);
    ?>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Performance Section3</h4>
            </div>
            <div class="modal-body">
                <div id="performance_doc_section1">
                    <form id="employee_performance_doc_section3" enctype="multipart/form-data" method="POST">
                        <input type="hidden" name="perform_action" value="employee_performance_doc_section3" />
                        <input type="hidden" name="document_sid" id='section3_performance_document_sid' />
                        <input type="hidden" name="employee_type" id='section3_employee_type' />
                        <input type="hidden" name="section3_employee_sid" id='section3_employee_sid' />

                        <section class="pdf-cover-page">
                            <table class="table table-border-collapse">
                                <tbody>
                                    <tr>
                                        <td width="50%" style="border-top:0px;">
                                            <strong style="font-size: 14px;">
                                                Section 3: The Year in Review </strong><br>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%" style="border-top:0px;">
                                            <strong style="font-size: 14px;">
                                                Additional Comments, Feedback - Managers Comments: <span class="staric">*</span></strong> <br>
                                            <textarea id="section3ManagerComment" name="section3ManagerComment" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                            <div id='long_textbox_12_id_sec'></div>
                                            <label id="section3ManagerCommentError" class="error"></label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%" style="border-top:0px;">
                                        <input type="hidden" name="section3EmployeeComment" id="section3EmployeeComment" >
                                        
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </section>
                        <hr />
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button id="performanceSection3Save" type="button" class="btn btn-success break-word-text">Save</button>
                                <button type="button" class="btn cancel_btn_black" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div id="performance_doc_section4_Modal" class="modal fade" role="dialog">
    <?php
    $userPrefillInfo  = [];
    $userPrefillInfo = get_employee_profile_info_detail($EmployeeSid, $Type);
    ?>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Performance Section4</h4>
            </div>
            <div class="modal-body">
                <div id="performance_doc_section1">
                    <form id="employee_performance_doc_section4" enctype="multipart/form-data" method="POST">
                        <input type="hidden" name="perform_action" value="employee_performance_doc_section4" />
                        <input type="hidden" name="document_sid" id='section4_performance_document_sid' />
                        <input type="hidden" name="employee_type" id='section4_employee_type' />
                        <input type="hidden" name="section4_employee_sid" id='section4_employee_sid' />
                        <input type="hidden" name="section4managerSignature" id='section3_authorized_signature_1' />
                        <input type="hidden" name="section4nextLevelSignature" id='section3_authorized_signature_2' />
                        <input type="hidden" name="section4hrSignature" id='section3_authorized_signature_3' />

                        <section class="pdf-cover-page">
                            <table class="table table-border-collapse">
                                <tbody>
                                    <tr>
                                        <td style="border-top:0px;">
                                            <strong>Employee Signature :</strong>
                                            <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="" id="section4employeeSignature" />
                                            <br><br>
                                            <strong> Signature Date : <span id="section4employeeSignatureDate"></span> </strong><br>
                                        </td>

                                        <td style="border-top:0px;">
                                            <label> Manaer Signature :</label>
                                            <a class="btn btn-success btn-sm jsSetAuthorizedSignature jsSetAuthorizedSignature_1" data-key="1">
                                                Create E-Signature
                                            </a><img style="max-height: 75px" alt="" class="authorized_signature_img_1" id="section4managerSignature"><br><br>

                                            <strong> Signature Date : </strong> <span id="section4managerSignatureDate"></span><br>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label> Next Level Approval Signature:</label>
                                            <a class="btn btn-success btn-sm jsSetAuthorizedSignature jsSetAuthorizedSignature_2" data-key="2">
                                                Create E-Signature
                                            </a><img style="max-height: 75px" alt="" class="authorized_signature_img_2" id="section4nextLevelSignature"> <br><br>
                                            <strong> Signature Date : </strong><span id="section4nextLevelSignatureDate"></span><br>
                                        </td>

                                        <td>
                                            <label>Human Resources Signature:</label>
                                            <a class="btn btn-success btn-sm jsSetAuthorizedSignature jsSetAuthorizedSignature_3" data-key="3">
                                                Create E-Signature
                                            </a><img style="max-height: 75px" alt="" class="authorized_signature_img_3" id="section4hrSignature"> <br><br>
                                            <strong> Signature Date : </strong><span id="section4hrSignatureDate"></span><br>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </section>
                        <hr />
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button id="performanceSection4Save" type="button" class="btn btn-success break-word-text">Save</button>
                                <button type="button" class="btn cancel_btn_black" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div id="performance_doc_section5_Modal" class="modal fade" role="dialog">
    <?php
    $userPrefillInfo  = [];
    $userPrefillInfo = get_employee_profile_info_detail($EmployeeSid, $Type);
    ?>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Performance Section 5 Salary Recommendation</h4>
            </div>
            <div class="modal-body">
                <div id="performance_doc_section1">
                    <form id="employee_performance_doc_section5" enctype="multipart/form-data" method="POST">
                        <input type="hidden" name="perform_action" value="employee_performance_doc_section5" />
                        <input type="hidden" name="document_sid" id='section5_performance_document_sid' />
                        <input type="hidden" name="employee_type" id='section5_employee_type' />
                        <input type="hidden" name="section5_employee_sid" id='section5_employee_sid' />
                        <input type="hidden" name="section5approvedBySignature" id='section5_authorized_signature_1' />
                        <section class="pdf-cover-page">
                            <table class="table table-border-collapse">
                                <tbody>
                                    <tr>
                                        <td style="border-top:0px;">
                                            <label> Employees Current Pay Rate: </label>
                                            <input type="number" class="form-control" name="section5currentRate" id='section5currentRate' autocomplete="off" min="0" value="0" step="any"/>
                                        </td>

                                        <td style="border-top:0px;">
                                            <label> Recommended Pay Increase: </label>
                                            <input type="number" class="form-control" name="section5recommendedIncrease" id='section5recommendedIncrease' autocomplete="off"  min="0" value="0" step="any"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label> Approved Amount: </label>
                                            <input type="number" class="form-control" name="section5approvedAmount" id='section5approvedAmount' autocomplete="off" min="0" value="0" step="any"/>
                                        </td>

                                        <td>
                                            <label>Approved By:</label>
                                            <a class="btn btn-success btn-sm jsSetAuthorizedSignature jsSetAuthorizedSignature_1" data-key="1">
                                                Create E-Signature
                                            </a><img style="max-height: 75px" alt="" class="authorized_signature_img_1" id="section5approvedBySignature"> <br><br>
                                            <strong> Approved Date : </strong><span id="section5approvedBySignatureDate"></span><br>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label> Effective Date of Increase: </label>
                                            <input type="text" class="form-control date_picker2" name="section5IncreaseEffectiveDate" id='section5IncreaseEffectiveDate' autocomplete="off" readonly />
                                        </td>

                                        <td></td>
                                    </tr>

                                </tbody>
                            </table>
                        </section>

                        <hr />
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button id="performanceSection5Save" type="button" class="btn btn-success break-word-text">Save</button>
                                <button type="button" class="btn cancel_btn_black" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<script>
    $('.js-ncd').text(<?= $ncd; ?>);
    $('.js-pp').text(<?= $pp; ?>);
    $('.js-cd').text(<?= $cd; ?>);
    $('.js-nad').text(<?= $nad; ?>);
    //
    $('.js-send-document').popover({
        trigger: 'hover',
        html: true
    });

    //
    $('.js-send-document').click((e) => {
        //
        e.preventDefault();
        //
        let sid = $(e.target).data('id');
        //
        alertify.confirm(
            'Confirm!',
            'Do you really want to send this document by email?',
            () => {
                $('body').css('overflow-y', 'hidden');
                $('#my_loader .loader-text').html('Please wait while we are sending this document....');
                $('#my_loader').show();
                //
                sendDocumentByEmail(sid);
            },
            () => {}
        ).set('labels', {
            ok: 'YES',
            cancel: 'NO'
        });
    });

    //
    $('.jsRevokeDocumentLibrary').click((e) => {
        //
        e.preventDefault();
        //
        let sid = $(e.target).data('asid');
        //
        alertify.confirm(
            'Are you Sure?',
            'Do you really want to revoke this library document?',
            function() {
                $('#loader').show();
                var myRequest;
                var myData = {
                    'action': 'revoke_library_document',
                    'document_sid': sid
                };
                var myUrl = '<?php echo base_url("hr_documents_management/handler"); ?>';


                myRequest = $.ajax({
                    url: myUrl,
                    type: 'POST',
                    data: myData,
                    dataType: 'json'
                });

                myRequest.done(function(response) {
                    alertify.alert("Success", 'Document Library Revoke Successfully', function() {
                        window.location.reload();
                    });
                });
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            }).set('labels', {
            ok: 'I Consent and Accept!',
            cancel: 'Cancel'
        });
    });


    //
    function sendDocumentByEmail(
        assignedDocumentSid
    ) {
        $.post("<?= base_url('hr_documents_management/send_document_to_sign'); ?>", {
            assignedDocumentSid: assignedDocumentSid
        }, (resp) => {
            //
            $('#my_loader').hide(0);
            $('#my_loader .loader-text').html('Please wait while we generate your E-Signature...');
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

    //
    function offer_letter_archive(document_sid) {

        var baseURI = "<?= base_url('hr_documents_management/handler'); ?>";

        var formData = new FormData();
        formData.append('document_sid', document_sid);
        formData.append('action', 'change_offer_letter_archive_status');

        $.ajax({
            url: baseURI,
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false
        }).done(function(resp) {
            var successMSG = 'Offer letter archived successfully.';
            alertify.alert('SUCCESS!', successMSG, function() {
                window.location.reload();
            });
        });
    }
</script>


<?php
$GLOBALS['notCompletedDocumentsList'] = $notCompletedDocumentsList;
$GLOBALS['completedDocumentsList'] = $completedDocumentsList;
$GLOBALS['noActionRequiredDocumentsList'] = $noActionRequiredDocumentsList;
?>