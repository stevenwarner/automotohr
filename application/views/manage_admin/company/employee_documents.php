<?php
$ncd = $pp = $cd = $nad = 0;

$action_btn_flag = true;
if ($pp_flag == 1) {
    $action_btn_flag = false;
}

//
$completedDocumentsList = [];
$notCompletedDocumentsList = [];
$noActionRequiredDocumentsList = [];
?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-files-o"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/employers/edit_employer') . "/" . $employee_detail["sid"]; ?>" class="btn black-btn float-right">Back</a>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title employee_info_section">
                                        <h1 class="page-title">Company Name : <?php echo $companyName; ?></h1>
                                        <br>
                                        <h1 class="page-title">Employee Name : <?php echo $employeeName; ?></h1>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 all_download_btn">

                                    <?php if ($downloadDocumentData && count($downloadDocumentData) && $downloadDocumentData['user_type'] == $user_type && $downloadDocumentData['download_type'] == 'single_download' && file_exists(APPPATH . '../temp_files/employee_export/' . $downloadDocumentData['folder_name'])) { ?>
                                        <div class="alert alert-success">
                                            Last export was generated at <?= DateTime::createFromFormat('Y-m-d H:i:s', $downloadDocumentData['created_at'])->format('m/d/Y H:i'); ?>.
                                            <a class="btn btn-success float-right" href="<?= base_url('hr_documents_management/generate_zip/' . ($downloadDocumentData['folder_name']) . ''); ?>">Download
                                            </a>
                                        </div>
                                    <?php } ?>

                                    <a href="<?= base_url('download/' . ($user_type) . '/' . ($user_sid) . '/AllCompletedDocument/0/' . ($company_sid) . ''); ?>" target="_blank" class="btn btn-success float-right">Download All Document(s)</a>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="panel panel-default" style="position: relative;">
                                        <div class="panel-heading">Completed Documents
                                            <span class="pull-right">
                                                <a href="<?= base_url('download/' . ($user_type) . '/' . ($user_sid) . '/completed/0/' . ($company_sid) . ''); ?>" target="_blank" class="btn btn-success">Download Document(s)</a>
                                            </span>
                                        </div>
                                        <div class="panel-body" style="min-height: 200px;">
                                            <!-- Data -->
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
                                                                                                <tr>
                                                                                                    <td class="col-lg-8">
                                                                                                        <?php
                                                                                                        echo $document['document_title'] . '&nbsp;';
                                                                                                        echo $document['is_document_authorized'] == 1 && $document['authorized_sign_status'] == 0  ? '( <b style="color:red;"> Awaiting Authorized Signature </b> )' : '';
                                                                                                        echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                                                        echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                                                        echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                                                                        if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                                            echo "<br><b>Assigned On: </b>" . formatDateToDB($document['assigned_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                                        }
                                                                                                        ?>
                                                                                                    </td>

                                                                                                    <?php if ($document['document_type'] == 'uploaded' || $document['document_type'] == 'confidential') { ?>
                                                                                                        <td class="col-lg-1">
                                                                                                            <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['document_s3_name']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                        </td>
                                                                                                    <?php } else { ?>
                                                                                                        <td class="col-lg-1">
                                                                                                            <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                        </td>
                                                                                                    <?php } ?>
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
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                                            <?php } ?>

                                            <?php if (sizeof($completed_offer_letter)) { ?>
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
                                                                                            ?>
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
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php if (!empty($completed_payroll_documents)) { ?>
                                                                                <?php foreach ($completed_payroll_documents as $document) { ?>
                                                                                    <?php
                                                                                    $GLOBALS['ad'][] = $document;
                                                                                    $completedDocumentsList[] = $document;
                                                                                    $cd++; ?>
                                                                                    <?php if ($document['document_sid'] == 0) { ?>
                                                                                        <tr>
                                                                                            <td class="col-lg-6">
                                                                                                <?php
                                                                                                echo $document['document_title'] . '&nbsp;';
                                                                                                echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                                                echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                                                echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                                                                if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                                    echo "<br><b>Assigned On: </b>" . formatDateToDB($document['assigned_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME);                                                                                                }

                                                                                                if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                                                    echo "<br><b>Signed On: </b>" . formatDateToDB($document['signature_timestamp'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                                } else {
                                                                                                    echo "<br><b>Signed On: </b> N/A";
                                                                                                }
                                                                                                ?>
                                                                                            </td>

                                                                                            <?php if ($document['document_type'] == 'uploaded' || $document['document_type'] == 'confidential') { ?>

                                                                                                <td class="col-lg-1">
                                                                                                    <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['document_s3_name']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                </td>
                                                                                            <?php } else { ?>

                                                                                                <td class="col-lg-1">
                                                                                                    <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                </td>
                                                                                            <?php } ?>
                                                                                        </tr>
                                                                                    <?php } else { ?>
                                                                                        <tr>
                                                                                            <td class="col-lg-6">
                                                                                                <?php
                                                                                                echo $document['document_title'] . '&nbsp;';
                                                                                                echo $document['is_document_authorized'] == 1 && $document['authorized_sign_status'] == 0  ? '( <b style="color:red;"> Awaiting Authorized Signature </b> )' : '';
                                                                                                echo $document['status'] ? '' : '<b>(revoked)</b>';

                                                                                                if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                                    echo "<br><b>Assigned On: </b>" . formatDateToDB($document['assigned_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                                }
                                                                                                ?>
                                                                                            </td>
                                                                                        </tr>
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

                                            <?php if (sizeof($EEVDocument)) { ?>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <br />
                                                        <div class="panel panel-default hr-documents-tab-content">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_EEVDocument_documents">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                        <?php echo 'Employment Eligibility Verification Document'; ?>
                                                                        <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($EEVDocument); ?></b></div>
                                                                    </a>

                                                                </h4>
                                                            </div>

                                                            <div id="collapse_EEVDocument_documents" class="panel-collapse collapse">
                                                                <div class="table-responsive full-width">
                                                                    <table class="table table-plane">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="col-lg-8">Document Name</th>
                                                                                <th class="col-lg-2 text-right">Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php if (!empty($EEVDocument)) { ?>
                                                                                <?php foreach ($EEVDocument as $document) { ?>
                                                                                    <tr>
                                                                                        <td class="col-lg-6">
                                                                                            <?php
                                                                                            echo $document['title'];

                                                                                            if (isset($document['assign_date']) && $document['assign_date'] != '0000-00-00 00:00:00') {
                                                                                                echo "<br><b>Assigned On: </b>" .  formatDateToDB($document['assign_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                            }

                                                                                            if (isset($document['sign_date']) && $document['sign_date'] != '0000-00-00 00:00:00') {
                                                                                                echo "<br><b>Signed On: </b>" .formatDateToDB($document['sign_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                            } else {
                                                                                                echo "<br><b>Signed On: </b> N/A";
                                                                                            }
                                                                                            ?>
                                                                                        </td>

                                                                                        <td class="col-lg-1">
                                                                                            <a href="<?= $document['url']; ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
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

                                            <?php if (sizeof($CompletedGeneralDocuments)) { ?>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <br />
                                                        <div class="panel panel-default hr-documents-tab-content">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_general_documents">
                                                                        <span class="glyphicon glyphicon-plus"></span>
                                                                        <?php echo 'General Document(s)'; ?>
                                                                        <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($CompletedGeneralDocuments); ?></b></div>
                                                                    </a>

                                                                </h4>
                                                            </div>

                                                            <div id="collapse_general_documents" class="panel-collapse collapse">
                                                                <div class="table-responsive full-width">
                                                                    <table class="table table-plane">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="col-lg-8">Document Name</th>
                                                                                <th class="col-lg-2 text-right">Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php if (!empty($CompletedGeneralDocuments)) { ?>
                                                                                <?php foreach ($CompletedGeneralDocuments as $document) { ?>
                                                                                    <tr>
                                                                                        <td class="col-lg-6">
                                                                                            <?php
                                                                                            echo $document['document_type'];

                                                                                            if (isset($document['assigned_at']) && $document['assigned_at'] != '0000-00-00 00:00:00') {
                                                                                                echo "<br><b>Assigned On: </b>" . formatDateToDB($document['assigned_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                            }

                                                                                            if (isset($document['sign_date']) && $document['sign_date'] != '0000-00-00 00:00:00') {
                                                                                                echo "<br><b>Signed On: </b>" . formatDateToDB($document['sign_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                            } else {
                                                                                                echo "<br><b>Signed On: </b> N/A";
                                                                                            }
                                                                                            ?>
                                                                                        </td>

                                                                                        <td class="col-lg-1">
                                                                                            <a href="<?= base_url("hr_documents_management/gpd/download") . "/" . $document['document_type'] . "/employee" . "/" . $user_sid; ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
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
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="panel panel-default" style="position: relative;">
                                        <div class="panel-heading">No Action Required Documents
                                            <span class="pull-right">
                                                <a href="<?= base_url('download/' . ($user_type) . '/' . ($user_sid) . '/noActionRequired/0/' . ($company_sid) . ''); ?>" target="_blank" class="btn btn-success">Download Document(s)</a>
                                            </span>
                                        </div>
                                        <div class="panel-body" style="min-height: 200px;">
                                            <!-- Data -->
                                            <?php if (!empty($categories_no_action_documents)) { ?>
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
                                                                                                            echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                                                                            if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                                                echo "<br><b>Assigned On: </b>" . formatDateToDB($document['assigned_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                                            }

                                                                                                            if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                                                                echo "<br><b>Signed On: </b>" . formatDateToDB($document['signature_timestamp'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                                            } else {
                                                                                                                echo "<br><b>Signed On: </b> N/A";
                                                                                                            }
                                                                                                            ?>
                                                                                                        </td>

                                                                                                        <?php if ($document['document_type'] == 'uploaded' || $document['document_type'] == 'confidential') { ?>
                                                                                                            <?php
                                                                                                            $no_action_document_url = $document['document_s3_name'];
                                                                                                            $no_action_document_info = get_required_url($no_action_document_url);
                                                                                                            $no_action_download_url = $no_action_document_info['download_url'];
                                                                                                            ?>
                                                                                                            <!-- Print Download by Adil-->
                                                                                                            <td class="col-lg-1">
                                                                                                                <a href="<?php echo $no_action_download_url; ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                            </td>
                                                                                                        <?php } else { ?>
                                                                                                            <td class="col-lg-1">

                                                                                                                <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                                            </td>
                                                                                                        <?php } ?>

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
                                            <?php } else { ?>
                                                <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                                            <?php } ?>

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
                                                                                            echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                                            echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                                                            if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                                echo "<br><b>Assigned On: </b>" . formatDateToDB($document['assigned_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                            }

                                                                                            if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                                                echo "<br><b>Signed On: </b>" . formatDateToDB($document['signature_timestamp'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                            } else {
                                                                                                echo "<br><b>Signed On: </b> N/A";
                                                                                            }
                                                                                            ?>
                                                                                        </td>

                                                                                        <?php if ($document['document_type'] == 'uploaded' || $document['document_type'] == 'confidential') { ?>

                                                                                            <td class="col-lg-1">
                                                                                                <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['document_s3_name']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                            </td>
                                                                                        <?php } else { ?>

                                                                                            <td class="col-lg-1">
                                                                                                <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                            </td>
                                                                                        <?php } ?>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .all_download_btn {
        margin: 10px 0px;
    }

    .employee_info_section {
        margin: 8px 0px;
    }
</style>