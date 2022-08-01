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
                        <?php if (!empty($assigned_documents)) { ?>
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

                                                    echo $document['document_title'] . '&nbsp;';
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
                                                        <?= getdocumenttabpagesbutton($document, 'preview_assigned', ['hybrid_preview' => 1]) ?>
                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                            <?= getdocumenttabpagesbutton($document, 'modify', ['data_type' => 'notCompletedDocuments']) ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                            <?= getdocumenttabpagesbutton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid]) ?>
                                                            <?= getSendDocumentEmailButton($document, $session['employer_detail'], $user_type); ?>
                                                            <?php if ($document['approval_process'] == 1) { ?>
                                                                <?= getdocumenttabpagesbutton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid]) ?>
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

                                                            <?= getdocumenttabpagesbutton($document, 'print', ['document_tab' => 'uncompleted']) ?>
                                                            <?= getdocumenttabpagesbutton($document, 'download', ['document_tab' => 'uncompleted']) ?>

                                                        </td>
                                                        <td class="col-lgfa-stack-1x">
                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>
                                                                Preview Assigned
                                                            </button>
                                                            <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                <?= getdocumenttabpagesbutton($document, 'modify', ['data_type' => 'notCompletedDocuments']) ?>
                                                            <?php } ?>
                                                        </td>
                                                    <?php } ?>
                                                    <td class="col-lg-1">
                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                            <?php if ($document['document_sid'] != 0) { ?>
                                                                <?= getdocumenttabpagesbutton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid]) ?>
                                                            <?php } ?>
                                                            <?= getSendDocumentEmailButton($document, $session['employer_detail'], $user_type); ?>
                                                            <?php if ($document['approval_process'] == 1) { ?>
                                                                <?= getdocumenttabpagesbutton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid]) ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                <?php } else { ?>
                                                    <td class="col-lg-1">
                                                        <?= getdocumenttabpagesbutton($document, 'print', ['document_tab' => 'uncompleted']) ?>
                                                        <?= getdocumenttabpagesbutton($document, 'download', ['document_tab' => 'uncompleted']) ?>
                                                    </td>

                                                    <td class="col-lg-2">
                                                        <?= getdocumenttabpagesbutton($document, 'preview_assigned') ?>
                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                            <?= getdocumenttabpagesbutton($document, 'modify', ['data_type' => 'notCompletedDocuments']) ?>
                                                        <?php } ?>
                                                    </td>

                                                    <td class="col-lg-2">
                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                            <?= getdocumenttabpagesbutton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid]) ?>
                                                            <?= getSendDocumentEmailButton($document, $session['employer_detail'], $user_type); ?>
                                                            <?php if ($document['approval_process'] == 1) { ?>
                                                                <?= getdocumenttabpagesbutton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid]) ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                                <td>
                                                    <?php if ($document_all_permission) { ?>
                                                        <?= getdocumenttabpagesbutton($document, 'manage_category') ?>
                                                    <?php } ?>
                                                    <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                        <?php if ($document['is_document_authorized'] == 1) { ?>
                                                            <?= getdocumenttabpagesbutton($document, 'employer_section', ['current_user_signature' => $current_user_signature]) ?>
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
                                                        echo $document['document_title'] . '&nbsp;';
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
                                                            <?= getdocumenttabpagesbutton($document, 'preview_assigned', ['data_type' => 'offer_letter']) ?>
                                                            <?php if ($document_all_permission) { ?>
                                                                <?= getdocumenttabpagesbutton($document, 'modify', ['data_type' => 'notCompletedOfferLetters']) ?>
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
                                                                    <?= getdocumenttabpagesbutton($document, 'modify', ['data_type' => 'notCompletedOfferLetters']) ?>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document">
                                                                    Preview Assigned
                                                                </button>
                                                                <?php if ($document_all_permission) { ?>
                                                                    <?= getdocumenttabpagesbutton($document, 'modify', ['data_type' => 'notCompletedOfferLetters']) ?>
                                                                    <?= getAuthorizedDocument($document); ?>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td class="col-lg-2">
                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>">Preview Document</button>
                                                            <?php if ($document_all_permission) { ?>
                                                                <?= getdocumenttabpagesbutton($document, 'modify', ['data_type' => 'notCompletedOfferLetters']) ?>
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
                                                                <?= getdocumenttabpagesbutton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid]) ?>
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
                                                        echo $document['document_title'] . '&nbsp;';
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
                                                                <?= getdocumenttabpagesbutton($document, 'modify', ['data_type' => 'notCompletedOfferLetters']) ?>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document">
                                                                Preview Assigned
                                                            </button>
                                                            <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                                <?= getdocumenttabpagesbutton($document, 'modify', ['data_type' => 'notCompletedDocuments']) ?>
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
                                                        <?php } ?>

                                                        <?php if ($document_all_permission && $document['isdoctolibrary'] == 0) { ?>
                                                            <?php
                                                            if (true) {
                                                            ?>
                                                                <?php if ($document['document_sid'] != 0) { ?>
                                                                    <?= getdocumenttabpagesbutton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid]) ?>
                                                                <?php } ?>
                                                            <?php } ?>
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
    </div>
</div>