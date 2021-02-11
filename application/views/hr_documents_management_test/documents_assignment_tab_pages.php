<?php
    $ncd = $pp = $cd = $nad = 0;
?>
<div class="row">
    <div class="col-xs-12">
        <ul class="nav nav-tabs nav-justified doc_assign_nav_tab">
            <li class="active doc_assign_nav_li"><a data-toggle="tab" href="#in_complete_doc_details">Not Completed (<span class="js-ncd">0</span>)</a></li>
            <?php if($pp_flag){?>
                <!-- <li class="doc_assign_nav_li"><a data-toggle="tab" href="#offer_letter_doc_details">Offer Letter / Pay Plan (<span class="js-pp">0</span>)</a></li> -->
            <?php }?>
            <li class="doc_assign_nav_li"><a data-toggle="tab" href="#signed_doc_details">Completed Documents (<span class="js-cd">0</span>)</a></li>
            <!-- <li class="doc_assign_nav_li"><a data-toggle="tab" href="#completed_doc_details">Completed Documents</a></li> -->
            <li class="doc_assign_nav_li"><a data-toggle="tab" href="#no_action_required_doc_details">No Action Required (<span class="js-nad">0</span>)</a></li>
        </ul>
        <div class="tab-content hr-documents-tab-content">
            <!-- Not Completed Document Start -->
            <div id="in_complete_doc_details" class="tab-pane fade in active hr-innerpadding">
                <div class="panel-body">
                    <h2 class="tab-title">Not Completed Document Detail</h2>
                    <?php if(sizeof($uncompleted_payrolls) || sizeof($assigned_documents)){?>
                    <div class="table-responsive full-width">
                        <table class="table table-plane js-uncompleted-docs">
                            <thead>
                                <tr>
                                    <th class="col-lg-8">Document Name</th>
                                    <th class="col-lg-4 text-center" colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($assigned_documents)) { ?>
                                    <?php $assigned_documents = array_reverse($assigned_documents);  ?>
                                    <?php foreach ($assigned_documents as $document) { ?>
                                        <?php if ($document['archive'] != 1) { ?>
                                            <?php if ($document['status'] != 0) { ?>
                                                <?php $ncd++; ?>
                                                <tr>
                                                    <td class="col-lg-8">
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
                                                        <?php if ($document['document_type'] == 'uploaded') { ?>
                                                            <?php if ($document['document_sid'] != 0) { ?>
                                                                <td class="col-lg-2">
                                                                    <button class="btn btn-success btn-sm btn-block"
                                                                        onclick="preview_document_model(this);"
                                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>"
                                                                        data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>"
                                                                        data-print-url="<?php echo $document['document_s3_name']; ?>"
                                                                        data-print-type="assigned"
                                                                        data-download-sid="<?php echo $document['sid']; ?>"
                                                                        data-file-name="<?php echo $document['document_original_name']; ?>"
                                                                        data-document-title="<?php echo $document['document_original_name']; ?>">Preview Assigned</button>
                                                                </td>
                                                            <?php } ?>
                                                            <td class="col-lg-2">
                                                                <?php if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_manage_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_manage_doc'))) { ?>
                                                                    <?php if ($document['document_sid'] != 0) { ?>
                                                                        <?php if ($document['status'] == 1) { ?>
                                                                            <?php if ($user_type == 'applicant') { ?>
                                                                                <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management_test/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                            <?php } else { ?>
                                                                                <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management_test/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <button class="btn btn-warning btn-sm btn-block" onclick="func_document_revoked();">Manage Document</button>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </td>
                                                        <?php } else { ?>
                                                            <td class="col-lg-2">
                                                                <button onclick="generated_document_original_preview(<?php echo $document['sid']; ?>);" class="btn btn-success btn-sm btn-block">
                                                                    Preview Assigned
                                                                </button>
                                                            </td>
                                                            <td class="col-lg-2">
                                                                <?php if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_manage_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_manage_doc'))) { ?>
                                                                    <?php if ($document['status'] == 1) { ?>
                                                                        <?php if ($user_type == 'applicant') { ?>
                                                                            <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management_test/manage_document/applicant/' . $document['sid'] . '/' . $user_sid . '/' . $job_list_sid); ?>">Manage Document</a>
                                                                        <?php } else { ?>
                                                                             <a class="btn btn-success btn-sm btn-block" href="<?php echo base_url('hr_documents_management_test/manage_document/employee/' . $document['sid'] . '/' . $user_sid); ?>">Manage Document</a>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <button class="btn btn-warning btn-sm btn-block" onclick="func_document_revoked();">Manage Document</button>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </td>
                                                        <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php }?>
                                <?php if(!sizeof($assigned_documents) && !sizeof($uncompleted_payrolls)) { ?>
                                    <tr>
                                        <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
                    <!--  -->
                    <?php if(sizeof($uncompleted_payrolls)){
                        foreach ($uncompleted_payrolls as $k => $category_document) {
                            ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <br />
                                    <div class="panel panel-default hr-documents-tab-content">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_notcompleted<?php echo $category_document['category_sid']; ?>">
                                                    <span class="glyphicon glyphicon-plus"></span>
                                                    <?php echo $category_document['name']; ?>
                                                    <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($category_document['documents']); ?></b></div>
                                                </a>

                                            </h4>
                                        </div>

                                        <div id="collapse_notcompleted<?php echo $category_document['category_sid']; ?>" class="panel-collapse collapse">
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
                                                            <?php $ncd++; ?>
                                                                <tr>
                                                                    <td class="col-lg-8">
                                                                        <?php
                                                                            echo $document['document_title'] . '&nbsp;';
                                                                            echo $document['status'] ? '' : '<b>(revoked)&nbsp;</b>';
                                                                            if ($document['manual_document_type'] == 'offer_letter') {
                                                                                echo '<b>(Manual Upload)</b>';
                                                                            }
                                                                            if ($document['sid'] == $current_payroll) echo '&nbsp;<b>(Current)</b>';


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
                                                                        <?php if($document['document_s3_name'] != ''){ ?>
                                                                            <button class="btn btn-success btn-sm btn-block"
                                                                                onclick="preview_document_model(this);"
                                                                                data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>"
                                                                                data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>"
                                                                                data-print-type="submitted"
                                                                                data-download-sid="<?php echo $document['sid']; ?>"
                                                                                data-file-name="<?php echo $document['document_s3_name']; ?>"
                                                                                data-document-title="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>Preview Assigned</button>
                                                                        <?php } else { ?>
                                                                            <button onclick="func_get_generated_document_preview(<?php echo $document['document_sid']; ?>, 'generated', 'modified');" class="btn btn-success btn-sm btn-block">Preview Assigned</button>
                                                                        <?php } ?>
                                                                        </td>
                                                                        <td class="col-lg-2">
                                                                            <?php if (isset($document['uploaded_file']) && !empty($document['uploaded_file'])) { ?>
                                                                                <button class="btn btn-success btn-sm btn-block"
                                                                                    onclick="preview_document_model(this);"
                                                                                    data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>"
                                                                                    data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>"
                                                                                    data-print-type="submitted"
                                                                                    data-download-sid="<?php echo $document['sid']; ?>"
                                                                                    data-file-name="<?php echo $document['uploaded_file']; ?>"
                                                                                    data-document-title="<?php echo $document['uploaded_file']; ?>" <?= !$document['uploaded'] ? 'disabled' : ''; ?>>Preview Submitted</button>
                                                                            <?php } else { ?>
                                                                                <button class="btn btn-success btn-sm btn-block"
                                                                                    onclick="preview_submitted_generated_document(this);"
                                                                                    data-preview-url="<?php echo $document['submitted_description']; ?>"
                                                                                    data-download-url="<?php echo $document['submitted_description']; ?>"
                                                                                    data-print-id="<?php echo $document['sid']; ?>"
                                                                                    data-file-name="<?php echo 'mysubmitted.pdf' ?>"
                                                                                    data-document-title="<?php echo 'User Submitted pdf' ?>" <?= !$document['submitted_description'] ? 'disabled' : ''; ?>>Preview Submitted</button>
                                                                            <?php } ?>
                                                                        </td>
                                                                    <?php } else{ ?>
                                                                        <td class="col-lg-2">
                                                                            <button class="btn btn-success btn-sm btn-block"
                                                                            onclick="preview_document_model(this);"
                                                                            data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>"
                                                                            data-print-type="assigned"
                                                                            data-download-sid="<?php echo $document['sid']; ?>"
                                                                            data-file-name="<?php echo $document['document_original_name']; ?>"
                                                                            data-document-title="<?php echo $document['document_title']; ?>"
                                                                            >Preview Document</button>
                                                                        </td>
                                                                        <td class="col-lg-2">
                                                                            <?php
                                                                                $categories = isset($no_action_document_categories[$document['sid']]) ? json_encode($no_action_document_categories[$document['sid']]) : "[]";
                                                                                $manual_document_type = $document['manual_document_type'] == "offer_letter" ? true : false;
                                                                                $document_type = $document['document_type'] == "confidential" ? true : false;
                                                                                $assign_date = isset($document['assigned_date']) ? date('m-d-Y',strtotime($document['assigned_date'])) : '';
                                                                                $sign_date = isset($document['signature_timestamp']) ?  date('m-d-Y',strtotime($document['signature_timestamp'])) : '';


                                                                            ?>

                                                                            <button class="btn btn-success btn-sm btn-block"
                                                                            onclick="no_action_req_edit_document_model(this);"
                                                                            data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>"
                                                                            data-print-type="assigned"
                                                                            data-download-sid="<?php echo $document['sid']; ?>"
                                                                            data-file-name="<?php echo $document['document_original_name']; ?>"
                                                                            data-document-title="<?php echo $document['document_title']; ?>"
                                                                            is-offer-letter="<?php echo $manual_document_type; ?>"
                                                                            data-categories='<?php echo $categories; ?>'
                                                                            data-update-accessible="<?php echo $document_type; ?>"
                                                                            assign-date="<?php echo $assign_date; ?>"
                                                                            sign-date="<?php echo $sign_date; ?>"
                                                                            >Edit Document</button>
                                                                        </td>
                                                                    <?php } ?>
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
                            <?php
                        }
                    } ?>
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
                                                <?php if($document['document_s3_name'] != ''){ ?>
                                                    <button class="btn btn-success btn-sm btn-block"
                                                        onclick="preview_document_model(this);"
                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>"
                                                        data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>"
                                                        data-print-type="submitted"
                                                        data-download-sid="<?php echo $document['sid']; ?>"
                                                        data-file-name="<?php echo $document['document_s3_name']; ?>"
                                                        data-document-title="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>Preview Assigned</button>
                                                <?php } else { ?>
                                                    <button onclick="func_get_generated_document_preview(<?php echo $document['document_sid']; ?>, 'generated', 'modified');" class="btn btn-success btn-sm btn-block">Preview Assigned</button>
                                                <?php } ?>
                                                </td>
                                                <td class="col-lg-2">
                                                    <?php if (isset($document['uploaded_file']) && !empty($document['uploaded_file'])) { ?>
                                                        <button class="btn btn-success btn-sm btn-block"
                                                            onclick="preview_document_model(this);"
                                                            data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>"
                                                            data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>"
                                                            data-print-type="submitted"
                                                            data-download-sid="<?php echo $document['sid']; ?>"
                                                            data-file-name="<?php echo $document['uploaded_file']; ?>"
                                                            data-document-title="<?php echo $document['uploaded_file']; ?>" <?= !$document['uploaded'] ? 'disabled' : ''; ?>>Preview Submitted</button>
                                                    <?php } else { ?>
                                                        <button class="btn btn-success btn-sm btn-block"
                                                            onclick="preview_submitted_generated_document(this);"
                                                            data-preview-url="<?php echo $document['submitted_description']; ?>"
                                                            data-download-url="<?php echo $document['submitted_description']; ?>"
                                                            data-print-id="<?php echo $document['sid']; ?>"
                                                            data-file-name="<?php echo 'mysubmitted.pdf' ?>"
                                                            data-document-title="<?php echo 'User Submitted pdf' ?>" <?= !$document['submitted_description'] ? 'disabled' : ''; ?>>Preview Submitted</button>
                                                    <?php } ?>
                                                </td>
                                            <?php } else{ ?>
                                                <td class="col-lg-2">
                                                    <button class="btn btn-success btn-sm btn-block"
                                                    onclick="preview_document_model(this);"
                                                    data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>"
                                                    data-print-type="assigned"
                                                    data-download-sid="<?php echo $document['sid']; ?>"
                                                    data-file-name="<?php echo $document['document_original_name']; ?>"
                                                    data-document-title="<?php echo $document['document_title']; ?>"
                                                    >Preview Document</button>
                                                </td>
                                                <td class="col-lg-2">
                                                    <?php
                                                        $categories = isset($no_action_document_categories[$document['sid']]) ? json_encode($no_action_document_categories[$document['sid']]) : "[]";
                                                        $manual_document_type = $document['manual_document_type'] == "offer_letter" ? true : false;
                                                        $document_type = $document['document_type'] == "confidential" ? true : false;
                                                        $assign_date = isset($document['assigned_date']) ? date('m-d-Y',strtotime($document['assigned_date'])) : '';
                                                        $sign_date = isset($document['signature_timestamp']) ?  date('m-d-Y',strtotime($document['signature_timestamp'])) : '';


                                                    ?>

                                                    <button class="btn btn-success btn-sm btn-block"
                                                    onclick="no_action_req_edit_document_model(this);"
                                                    data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>"
                                                    data-print-type="assigned"
                                                    data-download-sid="<?php echo $document['sid']; ?>"
                                                    data-file-name="<?php echo $document['document_original_name']; ?>"
                                                    data-document-title="<?php echo $document['document_title']; ?>"
                                                    is-offer-letter="<?php echo $manual_document_type; ?>"
                                                    data-categories='<?php echo $categories; ?>'
                                                    data-update-accessible="<?php echo $document_type; ?>"
                                                    assign-date="<?php echo $assign_date; ?>"
                                                    sign-date="<?php echo $sign_date; ?>"
                                                    >Edit Document</button>
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

            <!-- Signed Document Start -->
            <div id="signed_doc_details" class="tab-pane fade in hr-innerpadding">
                <div class="panel-body">
                    <!-- Category Completed Document Start -->
                    <h2 class="tab-title">Completed Document Detail</h2>
                    <?php if (!empty($categories_documents_completed)) { ?>
                        <?php foreach ($categories_documents_completed as $category_document) { ?>
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
                                                                <?php $cd++; ?>
                                                                <?php if($document['document_type'] == 'offer_letter' || $document['manual_document_type'] == 'offer_letter'){ ?>
                                                                        <tr>
                                                                            <td class="col-lg-8">
                                                                                <?php
                                                                                    echo $document['document_title'] . '&nbsp;';
                                                                                    echo $document['status'] ? '' : '<b>(revoked)&nbsp;</b>';
                                                                                    if ($document['manual_document_type'] == 'offer_letter') {
                                                                                        echo '<b>(Manual Upload)</b>';
                                                                                    }
                                                                                    if ($document['sid'] == $current_payroll) echo '&nbsp;<b>(Current)</b>';


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
                                                                                <?php if($document['document_s3_name'] != ''){ ?>
                                                                                    <button class="btn btn-success btn-sm btn-block"
                                                                                        onclick="preview_document_model(this);"
                                                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>"
                                                                                        data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>"
                                                                                        data-print-type="submitted"
                                                                                        data-download-sid="<?php echo $document['sid']; ?>"
                                                                                        data-file-name="<?php echo $document['document_s3_name']; ?>"
                                                                                        data-document-title="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>Preview Assigned</button>
                                                                                <?php } else { ?>
                                                                                    <button onclick="func_get_generated_document_preview(<?php echo $document['document_sid']; ?>, 'generated', 'modified');" class="btn btn-success btn-sm btn-block">Preview Assigned</button>
                                                                                <?php } ?>
                                                                                </td>
                                                                                <td class="col-lg-2">
                                                                                    <?php if (isset($document['uploaded_file']) && !empty($document['uploaded_file'])) { ?>
                                                                                        <button class="btn btn-success btn-sm btn-block"
                                                                                            onclick="preview_document_model(this);"
                                                                                            data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>"
                                                                                            data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>"
                                                                                            data-print-type="submitted"
                                                                                            data-download-sid="<?php echo $document['sid']; ?>"
                                                                                            data-file-name="<?php echo $document['uploaded_file']; ?>"
                                                                                            data-document-title="<?php echo $document['uploaded_file']; ?>" <?= !$document['uploaded'] ? 'disabled' : ''; ?>>Preview Submitted</button>
                                                                                    <?php } else { ?>
                                                                                        <button class="btn btn-success btn-sm btn-block"
                                                                                            onclick="preview_submitted_generated_document(this);"
                                                                                            data-preview-url="<?php echo $document['submitted_description']; ?>"
                                                                                            data-download-url="<?php echo $document['submitted_description']; ?>"
                                                                                            data-print-id="<?php echo $document['sid']; ?>"
                                                                                            data-file-name="<?php echo 'mysubmitted.pdf' ?>"
                                                                                            data-document-title="<?php echo 'User Submitted pdf' ?>" <?= !$document['submitted_description'] ? 'disabled' : ''; ?>>Preview Submitted</button>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            <?php } else{ ?>
                                                                                <td class="col-lg-2">
                                                                                    <button class="btn btn-success btn-sm btn-block"
                                                                                    onclick="preview_document_model(this);"
                                                                                    data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>"
                                                                                    data-print-type="assigned"
                                                                                    data-download-sid="<?php echo $document['sid']; ?>"
                                                                                    data-file-name="<?php echo $document['document_original_name']; ?>"
                                                                                    data-document-title="<?php echo $document['document_title']; ?>"
                                                                                    >Preview Document</button>
                                                                                </td>
                                                                                <td class="col-lg-2">
                                                                                    <?php
                                                                                        $categories = isset($no_action_document_categories[$document['sid']]) ? json_encode($no_action_document_categories[$document['sid']]) : "[]";
                                                                                        $manual_document_type = $document['manual_document_type'] == "offer_letter" ? true : false;
                                                                                        $document_type = $document['document_type'] == "confidential" ? true : false;
                                                                                        $assign_date = isset($document['assigned_date']) ? date('m-d-Y',strtotime($document['assigned_date'])) : '';
                                                                                        $sign_date = isset($document['signature_timestamp']) ?  date('m-d-Y',strtotime($document['signature_timestamp'])) : '';


                                                                                    ?>

                                                                                    <button class="btn btn-success btn-sm btn-block"
                                                                                    onclick="no_action_req_edit_document_model(this);"
                                                                                    data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>"
                                                                                    data-print-type="assigned"
                                                                                    data-download-sid="<?php echo $document['sid']; ?>"
                                                                                    data-file-name="<?php echo $document['document_original_name']; ?>"
                                                                                    data-document-title="<?php echo $document['document_title']; ?>"
                                                                                    is-offer-letter="<?php echo $manual_document_type; ?>"
                                                                                    data-categories='<?php echo $categories; ?>'
                                                                                    data-update-accessible="<?php echo $document_type; ?>"
                                                                                    assign-date="<?php echo $assign_date; ?>"
                                                                                    sign-date="<?php echo $sign_date; ?>"
                                                                                    >Edit Document</button>
                                                                                </td>
                                                                            <?php } ?>
                                                                        </tr>
                                                                <?php  } else { ?>
                                                                        <tr>
                                                                            <td class="col-lg-8">
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

                                                                            <?php if ($document['document_type'] == 'uploaded') { ?>
                                                                                <?php if ($document['document_sid'] != 0) { ?>
                                                                                    <td class="col-lg-2">
                                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_original_name']; ?>">Preview Assigned</button>
                                                                                    </td>
                                                                                <?php } ?>
                                                                                <td class="col-lg-2">
                                                                                    <?php if (in_array($document['document_sid'], $signed_document_sids)) { ?>
                                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>" data-print-url="<?php echo $document['uploaded_file']; ?>" data-print-type="submitted" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_file']; ?>" data-document-title="<?php echo $document['uploaded_file']; ?>" <?= !$document['uploaded'] ? 'disabled' : ''; ?>>Preview Submitted</button>
                                                                                    <?php } else { ?>
                                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_original_name']; ?>">Preview Submitted</button>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            <?php } else { ?>
                                                                                <td class="col-lg-2"><button onclick="generated_document_original_preview(<?php echo $document['sid']; ?>);" class="btn btn-success btn-sm btn-block">Preview Assigned</button></td>
                                                                                <td class="col-lg-2">
                                                                                    <?php if (in_array($document['document_sid'], $signed_document_sids)) { ?>
                                                                                        <?php if (isset($document['uploaded_file']) && !empty($document['uploaded_file'])) { ?>
                                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_file']; ?>" data-print-type="submitted" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_file']; ?>" data-document-title="<?php echo $document['uploaded_file']; ?>" <?= !$document['uploaded'] ? 'disabled' : ''; ?>>Preview Submitted</button>
                                                                                        <?php } else { ?>
                                                                                            <button class="btn btn-success btn-sm btn-block" onclick="preview_submitted_generated_document(this);" data-preview-url="<?php echo $document['submitted_description']; ?>" data-download-url="<?php echo $document['submitted_description']; ?>" data-print-id="<?php echo $document['sid']; ?>" data-file-name="<?php echo 'mysubmitted.pdf' ?>" data-document-title="<?php echo 'User Submitted pdf' ?>" <?= !$document['submitted_description'] ? 'disabled' : ''; ?>>Preview Submitted</button>
                                                                                        <?php } ?>
                                                                                    <?php } else { ?>
                                                                                        <button onclick="generated_document_original_preview(<?php echo $document['sid']; ?>);" class="btn btn-success btn-sm btn-block">Preview Submitted</button>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                        </tr>
                                                                <?php  }
                                                                ?>
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
                        <?php
                                }
                            } ?>
                    <?php }else{
                        ?>
                        <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                        <?php
                    } ?>
                    <!-- Category Completed Document End -->
                </div>
            </div>
            <!-- Signed Document End -->

            <!-- Completed Document Start -->
           <!--  <div id="completed_doc_details" class="tab-pane fade in hr-innerpadding">
                <div class="panel-body">
                    <h2 class="tab-title">Completed Document Detail</h2>
                    <div class="table-responsive full-width">
                        <table class="table table-plane">
                            <thead>
                                <tr>
                                    <th class="col-lg-10">Document Name</th>
                                    <th class="col-lg-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php //if (!empty($completed_documents)) { ?>
                                    <?php //foreach ($completed_documents as $document) { ?>
                                        <?php //if ($document['archive'] != 1) { ?>
                                            <tr>
                                                <td class="col-lg-6">
                                                    <?php
                                                        // echo $document['document_title'] . '&nbsp;';
                                                        // echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                        // echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                        // echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                        // if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                        //     echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                        // }
                                                    ?>
                                                </td>

                                                <?php //if ($document['document_type'] == 'uploaded') { ?>
                                                    <?php //if ($document['document_sid'] != 0) { ?>
                                                        <td class="col-lg-2">
                                                            <button class="btn btn-success btn-sm btn-block"
                                                                    onclick="preview_document_model(this);"
                                                                    data-preview-url="<?php //echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>"
                                                                    data-download-url="<?php //echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>"
                                                                    data-print-url="<?php //echo $document['document_s3_name']; ?>"
                                                                    data-print-type="assigned"
                                                                    data-download-sid="<?php //echo $document['sid']; ?>"
                                                                    data-file-name="<?php //echo $document['document_original_name']; ?>"
                                                                    data-document-title="<?php //echo $document['document_original_name']; ?>">Preview Document</button>
                                                        </td>
                                                    <?php //} ?>
                                                <?php //} else { ?>
                                                    <td class="col-lg-2"><button onclick="generated_document_original_preview(<?php //echo $document['sid']; ?>);" class="btn btn-success btn-sm btn-block">Preview Document</button></td>
                                                <?php //} ?>
                                            </tr>
                                        <?php //} ?>
                                    <?php //} ?>
                                <?php //} else { ?>
                                    <tr>
                                        <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Completed!</b></td>
                                    </tr>
                                <?php //} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->
            <!-- Completed Document End -->

            <!-- No Action Required Document Start -->
            <div id="no_action_required_doc_details" class="tab-pane fade in hr-innerpadding">
                <div class="panel-body">
                    <!-- Category No Action Required Document Start -->
                    <?php if (!empty($categories_no_action_documents)) { ?>
                        <h2 class="tab-title">No Action Required Document Detail</h2>
                        <?php foreach ($categories_no_action_documents as $category_document) { ?>
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
                                                                <th class="col-lg-10">Document Name</th>
                                                                <th class="col-lg-2 text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (count($category_document['documents']) > 0) { ?>
                                                                <?php foreach ($category_document['documents'] as $document) { ?>
                                                                    <?php if ($document['archive'] != 1 && $document['manual_document_type'] != 'offer_letter') { ?>
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
                                                                                <td class="col-lg-2">

                                                                                    <?php if ($document['document_sid'] != 0) { ?>
                                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_original_name']; ?>">Preview Document</button>
                                                                                    <?php } else if ($document['document_sid'] == 0) { ?>
                                                                                        <button class="btn btn-success btn-sm btn-block" onclick="preview_document_model(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>" data-print-type="assigned" data-download-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>">Preview Document</button>
                                                                                        <?php
                                                                                            $categories = isset($no_action_document_categories[$document['sid']]) ? json_encode($no_action_document_categories[$document['sid']]) : "[]";
                                                                                            $manual_document_type = $document['manual_document_type'] == "offer_letter" ? true : false;
                                                                                            $document_type = $document['document_type'] == "confidential" ? true : false;
                                                                                            $assign_date = isset($document['assigned_date']) ? date('m-d-Y',strtotime($document['assigned_date'])) : '';
                                                                                            $sign_date = isset($document['signature_timestamp']) ?  date('m-d-Y',strtotime($document['signature_timestamp'])) : '';
                                                                                        ?>
                                                                                        <button class="btn btn-success btn-sm btn-block"
                                                                                        onclick="no_action_req_edit_document_model(this);"
                                                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-print-url="<?php echo $document['document_s3_name']; ?>"
                                                                                        data-print-type="assigned"
                                                                                        data-download-sid="<?php echo $document['sid']; ?>"
                                                                                        data-file-name="<?php echo $document['document_original_name']; ?>" data-document-title="<?php echo $document['document_title']; ?>"
                                                                                        is-offer-letter="<?php echo $manual_document_type; ?>"
                                                                                        data-categories='<?php echo $categories; ?>'
                                                                                        data-update-accessible="<?php echo $document_type; ?>"
                                                                                        assign-date="<?php echo $assign_date; ?>"
                                                                                        sign-date="<?php echo $sign_date; ?>"
                                                                                        >Edit Document</button>
                                                                                        <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                                            <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                                <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                                <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type']?>" />
                                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                            </form>
                                                                                            <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            <?php } else { ?>
                                                                                <td class="col-lg-2"><button onclick="generated_document_original_preview(<?php echo $document['sid']; ?>);" class="btn btn-success btn-sm btn-block">Preview Document</button></td>
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
                        <?php
                                }
                            } ?>
                    <?php }else{
                        ?>
                        <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                        <?php
                    } ?>
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
</script>
