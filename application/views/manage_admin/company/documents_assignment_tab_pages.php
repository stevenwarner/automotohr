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
<style>
    .download_document_note{
        display: block;
        margin-top: 20px;
    }
    .jsCategoryManagerBTN{ display: none;}
</style>
<div class="row">
    <div class="col-xs-12">
        <ul class="nav nav-tabs nav-justified doc_assign_nav_tab">
            <li class="active doc_assign_nav_li"><a data-toggle="tab" href="#signed_doc_details">Completed Documents (<span class="js-cd">0</span>)</a></li>
            <!-- <li class="doc_assign_nav_li"><a data-toggle="tab" href="#completed_doc_details">Completed Documents</a></li> -->
            <li class="doc_assign_nav_li"><a data-toggle="tab" href="#no_action_required_doc_details">No Action Required (<span class="js-nad">0</span>)</a></li>
        </ul>
        <div class="tab-content hr-documents-tab-content">

            <!-- Signed Document Start -->
            <div id="signed_doc_details" class="tab-pane fade in hr-innerpadding">
                <div class="panel-body">
                        <?php if ($downloadDocumentData && count($downloadDocumentData) && $downloadDocumentData['user_type'] == $user_type && $downloadDocumentData['download_type'] == 'single_download' && file_exists(APPPATH.'../temp_files/employee_export/'.$downloadDocumentData['folder_name'])) { ?>
                            <div class="alert alert-success">Last export was generated at <?=DateTime::createFromFormat('Y-m-d H:i:s', $downloadDocumentData['created_at'])->format('m/d/Y H:i');?>. <a class="btn btn-success" href="<?=base_url('hr_documents_management/generate_zip/'.($downloadDocumentData['folder_name']).'');?>">Download</a></div>
                        <?php } ?>    
                    <!-- Category Completed Document Start -->
                    <h2 class="tab-title">Completed Document Detail
                        <span class="pull-right">
                            <a href="<?=base_url('download/'.($user_type).'/'.($user_sid).'/completed');?>" target="_blank" class="btn btn-success">Download Document(s)</a>
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
                                                                        <tr>
                                                                            <td class="col-lg-8">
                                                                                <?php
                                                                                    echo $document['document_title'] . '&nbsp;';
                                                                                    echo $document['is_document_authorized'] == 1 && $document['authorized_sign_status'] == 0  ? '( <b style="color:red;"> Awaiting Authorized Signature </b> )' : '';
                                                                                    echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                                    echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                                    echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                                                    if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
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
                                                                                    $name = explode(".",$document_filename);
                                                                                    $url_segment_original = $name[0];
                                                                                    ?>
                                                                                    <?php if ($document_extension == 'pdf') { ?>
                                                                                        <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$url_segment_original.'.pdf' ?>" class="btn btn-success btn-sm btn-block">Print</a>

                                                                                    <?php } else if ($document_extension == 'docx') { ?>
                                                                                        <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edocx&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                    <?php } else if ($document_extension == 'doc') { ?>
                                                                                        <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edoc&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                    <?php } else if ($document_extension == 'xls') { ?>
                                                                                        <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exls' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                    <?php } else if ($document_extension == 'xlsx') { ?>
                                                                                        <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exlsx' ?>" class="btn btn-success btn-sm btn-block">Print</a>
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
                                                                                    <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['document_s3_name']);?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                </td>
                                                                            <?php } else { ?>
                                                                                <td class="col-lg-1">
                                                                                    <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type);?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                </td>

                                                                                <td class="col-lg-1">
                                                                                    <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type . '/download');?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
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
                                                <?php //echo $category_document['name']; ?>
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
                                                        <?php $GLOBALS['uofl'][] = $document; $cd++; ?>
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
                                                            <?php //} ?>
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
                                                        $cd++; ?>
                                                            <?php if($document['document_sid'] == 0) { ?>
                                                                <tr>
                                                                    <td class="col-lg-6">
                                                                        <?php
                                                                            echo $document['document_title'] . '&nbsp;';
                                                                            echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                            echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                            echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                                            if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'],'format' => 'M d Y, D', '_this' => $this));
                                                                            }

                                                                            if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                                echo "<br><b>Signed On: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], 'format' => 'M d Y, D',  '_this' => $this));
                                                                            } else {
                                                                                echo "<br><b>Signed On: </b> N/A";
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
                                                                            $name = explode(".",$document_filename);
                                                                            $url_segment_original = $name[0];
                                                                            ?>
                                                                            <?php if ($document_extension == 'pdf') { ?>
                                                                                <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$url_segment_original.'.pdf' ?>" class="btn btn-success btn-sm btn-block">Print</a>

                                                                            <?php } else if ($document_extension == 'docx') { ?>
                                                                                <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edocx&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                            <?php } else if ($document_extension == 'doc') { ?>
                                                                                <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edoc&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                            <?php } else if ($document_extension == 'xls') { ?>
                                                                                <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exls' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                            <?php } else if ($document_extension == 'xlsx') { ?>
                                                                                <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exlsx' ?>" class="btn btn-success btn-sm btn-block">Print</a>
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
                                                                            <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['document_s3_name']);?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                        </td>
                                                                    <?php } else { ?>
                                                                        <td class="col-lg-1">
                                                                            <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type);?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                        </td>

                                                                        <td class="col-lg-1">
                                                                            <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type . '/download');?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
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
                                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
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
                    <!-- Category Completed Document End -->
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
                            <a href="<?=base_url('download/'.($user_type).'/'.($user_sid).'/noActionRequired');?>" target="_blank" class="btn btn-success">Download Document(s)</a>
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
                                                                                        echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                                                        if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                            echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'],'format' => 'M d Y, D', '_this' => $this));
                                                                                        }

                                                                                        if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                                            echo "<br><b>Signed On: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], 'format' => 'M d Y, D',  '_this' => $this));
                                                                                        } else {
                                                                                            echo "<br><b>Signed On: </b> N/A";
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
                                                                                        <a target="_blank" href="<?php echo $no_action_print_url; ?>" class="btn btn-success btn-sm btn-block">
                                                                                            Print
                                                                                        </a>
                                                                                    
                                                                                        <a href="<?php echo $no_action_download_url; ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                    </td>
                                                                                <?php } else { ?>
                                                                                    <td class="col-lg-1">
                                                                                        <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type);?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                    
                                                                                        <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type . '/download');?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
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
                    <?php }else{
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
                                                        <?php $nad++; $noActionRequiredDocumentsList[] = $document; ?>
                                                            <tr>
                                                                <td class="col-lg-6">
                                                                    <?php
                                                                        echo $document['document_title'] . '&nbsp;';
                                                                        echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                        echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                        echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                                        if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                            echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'],'format' => 'M d Y, D', '_this' => $this));
                                                                        }

                                                                        if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                            echo "<br><b>Signed On: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], 'format' => 'M d Y, D',  '_this' => $this));
                                                                        } else {
                                                                            echo "<br><b>Signed On: </b> N/A";
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
                                                                        $name = explode(".",$document_filename);
                                                                        $url_segment_original = $name[0];
                                                                        ?>
                                                                        <?php if ($document_extension == 'pdf') { ?>
                                                                            <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$url_segment_original.'.pdf' ?>" class="btn btn-success btn-sm btn-block">Print</a>

                                                                        <?php } else if ($document_extension == 'docx') { ?>
                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edocx&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                        <?php } else if ($document_extension == 'doc') { ?>
                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edoc&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                        <?php } else if ($document_extension == 'xls') { ?>
                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exls' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                                        <?php } else if ($document_extension == 'xlsx') { ?>
                                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exlsx' ?>" class="btn btn-success btn-sm btn-block">Print</a>
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
                                                                        <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['document_s3_name']);?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class="col-lg-1">
                                                                        <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type);?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                    </td>

                                                                    <td class="col-lg-1">
                                                                        <a href="<?= base_url('hr_documents_management/print_generated_doc/original/' . $document['sid'] . '/' . $user_sid . '/' . $user_type . '/download');?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
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
                    <!-- Category No Action Required Document End -->
                </div>
            </div>
            <!-- No Action Required Document End -->
        </div>
    </div>
</div>   

<script>
    $('.js-ncd').text(<?=$ncd;?>);
    $('.js-pp').text(<?=$pp;?>);
    $('.js-cd').text(<?=$cd;?>);
    $('.js-nad').text(<?=$nad;?>);
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
                // setTimeout(() => {
                //     $('#my_loader .loader-text').html('Please wait while we generate your E-Signature...');
                //     $('body').css('overflow-y', 'auto');
                // }, 5000);
            },
            () => {}
        ).set('labels', {
            ok: 'YES',
            cancel: 'NO'
        });
    });


    //
    function sendDocumentByEmail(
        assignedDocumentSid
    ){
        $.post("<?=base_url('hr_documents_management/send_document_to_sign');?>", {
            assignedDocumentSid: assignedDocumentSid
        }, (resp) => {
            //
            $('#my_loader').hide(0);
            $('#my_loader .loader-text').html('Please wait while we generate your E-Signature...');
            $('body').css('overflow-y', 'auto');
            //
            if(resp.Status === false){
                alertify.alert('WARNING!', resp.Response, () => {});
                return;
            }
            //
            alertify.alert('SUCCESS!', resp.Response, () => {});
            return;
        });
    }

    //
    function offer_letter_archive (document_sid) {
        
        var baseURI = "<?=base_url('hr_documents_management/handler');?>";

        var formData = new FormData();
        formData.append('document_sid', document_sid);
        formData.append('action', 'change_offer_letter_archive_status');

        $.ajax({
            url: baseURI,
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false
        }).done(function(resp){
            var successMSG = 'Offer letter archived successfully.';
            alertify.alert('SUCCESS!', successMSG, function(){
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