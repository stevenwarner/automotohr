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