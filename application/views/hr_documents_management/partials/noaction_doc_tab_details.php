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
                                                                        echo $document['document_title'] . '&nbsp;';
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
                                                                            <?= getdocumenttabpagesbutton($document, 'print', ['document_tab' => 'no_action']) ?>
                                                                            <?= getdocumenttabpagesbutton($document, 'download', ['document_tab' => 'no_action']) ?>
                                                                        </td>
                                                                        <td class="col-lg-2">

                                                                            <?php if ($document['document_sid'] != 0) { ?>
                                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>">Preview Document
                                                                                </button>

                                                                                <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                    <?= getdocumenttabpagesbutton($document, 'modify', ['data_type' => 'noActionDocuments']) ?>
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
                                                                            <?= getdocumenttabpagesbutton($document, 'preview_assigned') ?>
                                                                            <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                                <?= getdocumenttabpagesbutton($document, 'modify', ['data_type' => 'noActionDocuments']) ?>
                                                                            <?php } ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <td>
                                                                        <?php if ($document_all_permission) { ?>
                                                                            <?= getdocumenttabpagesbutton($document, 'manage_category') ?>
                                                                        <?php } ?>
                                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                            <?= getdocumenttabpagesbutton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid]) ?>

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
                                                                                <?= getdocumenttabpagesbutton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid]) ?>
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
                                            <?php foreach ($no_action_required_payroll_documents as $document) { ?>
                                                <?php $nad++;
                                                $noActionRequiredDocumentsList[] = $document; ?>
                                                <tr>
                                                    <td class="col-lg-6">
                                                        <?php
                                                        echo $document['document_title'] . '&nbsp;';
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
                                                                <?= getdocumenttabpagesbutton($document, 'modify', ['data_type' => 'noActionDocuments']) ?>
                                                        </td>
                                                    <?php } ?>
                                                <?php } ?>
                                                <td>
                                                    <?php if ($document_all_permission) { ?>
                                                        <a href="javascript:void(0);" class="btn btn-success btn-sm btn-block jsCategoryManagerBTN" title="Modify Category" data-asid="<?= $document['sid']; ?>" data-sid="<?= $document['document_sid']; ?>">Manage Category</a>
                                                    <?php } ?>
                                                    <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                        <?= getdocumenttabpagesbutton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid]) ?>
                                                        <?php if ($document['approval_process'] == 1) { ?>
                                                            <?= getdocumenttabpagesbutton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid]) ?>
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
<script>
    $('.js-nad').text(<?= $nad; ?>);
</script>