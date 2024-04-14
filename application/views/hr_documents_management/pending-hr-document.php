<?php
$all_assigned_offer_letter = [];
$assigned_documents = [];
$assigned_offer_letters = [];
?>
<!-- Main Start -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/manage_ems_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('loader', ['props' => 'id="jsEmployeeEmailLoader"']); ?>
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management/people_with_pending_documents'); ?>"><i class="fa fa-chevron-left"></i>Employees With Pending Documents</a><?php echo $title; ?>
                        </span>
                    </div>
                    <div class="create-job-wrap">
                        <?php if (!sizeof($documents) && !sizeof($w4_form) && !sizeof($w9_form) && !sizeof($i9_form) && !sizeof($eeoc_form) && !sizeof($NotCompletedGeneralDocuments) && !$userNotCompletedStateForms) { ?>
                            <div class="archived-document-area">
                                <div class="cloud-icon"><i class="fa fa-cloud-upload"></i></div>
                                <div class="archived-heading-area">
                                    <h2>No Pending Documents!</h2>
                                </div>
                            </div>
                        <?php } else { ?>
                            <?php if (count($documents) > 0 || !empty($w4_form) || !empty($w9_form) || !empty($i9_form) || !empty($eeoc_form) || count($NotCompletedGeneralDocuments) || $userNotCompletedStateForms) { ?>
                                <div class="table-responsive">
                                    <h3>Document Details For Employee: <b><a href="<?php echo base_url('hr_documents_management/documents_assignment/employee/' . $userDetail['sid']); ?>" class="text" style="font-weight: bold; color:#272727;"><?php echo $userDetail['first_name']; ?> <?php echo $userDetail['last_name']; ?></a></b>
                                        <span class="pull-right">
                                            <button class="btn btn-success jsSendEmailReminder">
                                                Send Email Reminder
                                            </button>
                                        </span>
                                        <div class="clearfix"></div>
                                    </h3>
                                    <div class="hr-document-list">
                                        <table class="hr-doc-list-table">
                                            <thead>
                                                <tr>
                                                    <th>Document Name</th>
                                                    <th class="text-center">Type</th>
                                                    <th class="text-center">Sent On</th>
                                                    <th class="text-center">Acknowledged</th>
                                                    <th class="text-center">Downloaded</th>
                                                    <th class="text-center">Uploaded</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($w4_form)) { ?>
                                                    <tr>
                                                        <td>
                                                            W4 Fillable <b>(Employment Eligibility Verification Documents)</b>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-2x fa-file-text"></i>
                                                        </td>
                                                        <td>
                                                            <?php echo reset_datetime(array('datetime' => $w4_form['sent_date'], '_this' => $this)); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<b>N/A</b>'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<b>N/A</b>'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<b>N/A</b>'; ?>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#w4_modal" href="javascript:void(0);">
                                                                Preview Assigned
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>

                                                <?php if (!empty($w9_form)) { ?>
                                                    <tr>
                                                        <td>
                                                            W9 Fillable <b>(Employment Eligibility Verification Documents)</b>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-2x fa-file-text"></i>
                                                        </td>
                                                        <td>
                                                            <?php echo reset_datetime(array('datetime' => $w9_form['sent_date'], '_this' => $this)); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<b>N/A</b>'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<b>N/A</b>'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<b>N/A</b>'; ?>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#w9_modal" href="javascript:void(0);">
                                                                Preview Assigned
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>


                                                <?php if (!empty($i9_form)) { ?>
                                                    <tr>
                                                        <td>
                                                            I9 Fillable <b>(Employment Eligibility Verification Documents)</b>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-2x fa-file-text"></i>
                                                        </td>
                                                        <td>
                                                            <?php echo reset_datetime(array('datetime' => $i9_form['sent_date'], '_this' => $this)); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<b>N/A</b>'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<b>N/A</b>'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<b>N/A</b>'; ?>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#i9_modal" href="javascript:void(0);">
                                                                Preview Assigned
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>

                                                <?php if (!empty($eeoc_form)) { ?>
                                                    <tr>
                                                        <td>
                                                            EEOC Fillable <b>(Employment Eligibility Verification Documents)</b>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-2x fa-file-text"></i>
                                                        </td>
                                                        <td>
                                                            <?php echo reset_datetime(array('datetime' => $eeoc_form['last_sent_at'], '_this' => $this)); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<b>N/A</b>'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<b>N/A</b>'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo '<b>N/A</b>'; ?>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#eeoc_modal" href="javascript:void(0);">
                                                                Preview Assigned
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>

                                                <!-- State forms -->
                                                <?php foreach ($userNotCompletedStateForms as $v) { ?>
                                                    <tr>
                                                        <td><?= $v["title"]; ?> <b>(State Form)</b></td>
                                                        <td><i class="fa fa-2x fa-file-text"></i></td>
                                                        <td><?= reset_datetime(array('datetime' => $v['assigned_at'], '_this' => $this)); ?></td>
                                                        <td><b>N/A</b></td>
                                                        <td><b>N/A</b></td>
                                                        <td><b>N/A</b></td>
                                                        <td><b>N/A</b></td>
                                                    </tr>
                                                <?php } ?>

                                                <!-- General Documents -->
                                                <?php foreach ($NotCompletedGeneralDocuments as $v) { ?>
                                                    <tr>
                                                        <td><?= ucwords(str_replace('_', ' ', $v['document_type'])); ?> <b>(General Document)</b></td>
                                                        <td><i class="fa fa-2x fa-file-text"></i></td>
                                                        <td><?= reset_datetime(array('datetime' => $v['updated_at'], '_this' => $this)); ?></td>
                                                        <td><b>N/A</b></td>
                                                        <td><b>N/A</b></td>
                                                        <td><b>N/A</b></td>
                                                        <td><b>N/A</b></td>
                                                    </tr>
                                                <?php } ?>

                                                <?php foreach ($documents as $document) { ?>
                                                    <?php
                                                    if ($document['letter_type'] == 'hybrid_document')  $assigned_offer_letters[] = $document;
                                                    else  $assigned_documents[] = $document;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo ucfirst($document['document_title']) . '<br>';
                                                            echo $document['status'] ? '' : '<b>(revoked)</b>'; ?></td>
                                                        <td class="text-center">
                                                            <?php $doc_type = '';

                                                            if (!empty($document['document_extension'])) {
                                                                $doc_type = strtolower($document['document_extension']);
                                                            } ?>
                                                            <?php if ($doc_type == 'pdf') { ?>
                                                                <i class="fa fa-2x fa-file-pdf-o"></i>
                                                            <?php } else if (in_array($doc_type, ['ppt', 'pptx'])) { ?>
                                                                <i class="fa fa-2x fa-file-powerpoint-o"></i>
                                                            <?php } else if (in_array($doc_type, ['doc', 'docx'])) { ?>
                                                                <i class="fa fa-2x fa-file-o"></i>
                                                            <?php } else if (in_array($doc_type, ['xlsx'])) { ?>
                                                                <i class="fa fa-2x fa-file-excel-o"></i>
                                                            <?php } else if ($doc_type == '') { ?>
                                                                <i class="fa fa-2x fa-file-text"></i>
                                                            <?php } ?>
                                                        </td>
                                                        <td><?= reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this)); ?></td>
                                                        <td class="text-center">
                                                            <?php
                                                            if (!$document['acknowledgment_required']) {
                                                                echo '<b>N/A</b>';
                                                            } elseif ($document['acknowledged']) {
                                                                echo '<i class="fa fa-check fa-2x text-success"></i> ' . reset_datetime(array('datetime' => $document['acknowledged_date'], '_this' => $this));
                                                            } else {
                                                                echo '<i class="fa fa-times fa-2x text-danger"></i>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            if (!$document['download_required']) {
                                                                echo '<b>N/A</b>';
                                                            } elseif ($document['downloaded']) {
                                                                echo '<i class="fa fa-check fa-2x text-success"></i> ' . reset_datetime(array('datetime' => $document['downloaded_date'], '_this' => $this));
                                                            } else {
                                                                echo '<i class="fa fa-times fa-2x text-danger"></i>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            if (!$document['signature_required']) {
                                                                echo '<b>N/A</b>';
                                                            } elseif ($document['uploaded']) {
                                                                echo '<i class="fa fa-check fa-2x text-success"></i> ' . reset_datetime(array('datetime' => $document['uploaded_date'], '_this' => $this));
                                                            } else {
                                                                echo '<i class="fa fa-times fa-2x text-danger"></i>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="col-lg-1 text-center">

                                                            <?php if ($document['letter_type'] == 'hybrid_document') { ?>
                                                                <button class="btn btn-success btn-sm btn-block js-hybrid-preview" data-document="assigned" data-type="offer_letter" data-id="<?= $document['sid']; ?>">
                                                                    Preview Assigned
                                                                </button>
                                                            <?php } else if ($document['document_type'] == 'hybrid_document') { ?>
                                                                <button class="btn btn-success btn-sm btn-block js-hybrid-preview" data-document="assigned" data-type="document" data-id="<?= $document['sid']; ?>">
                                                                    Preview Assigned
                                                                </button>
                                                            <?php } else if ($document['document_type'] == 'uploaded' || $document['document_type'] == 'offer_letter') { ?>
                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['document_s3_name']; ?>" data-s3-name="<?php echo $document['document_s3_name']; ?>" <?= !$document['document_s3_name'] ? 'disabled' : ''; ?>>
                                                                    Preview Assigned
                                                                </button>
                                                            <?php } else { ?>
                                                                <button class="btn btn-success btn-sm btn-block" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="assigned" data-from="assigned_document">
                                                                    Preview Assigned
                                                                </button>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <table class="hr-doc-list-table">
                                    <thead>
                                        <tr>
                                            <th>Document Name</th>
                                            <th>Sent On</th>
                                            <th>Acknowledged</th>
                                            <th>Downloaded</th>
                                            <th>Uploaded</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            No Document Available
                                        </tr>
                                    </tbody>
                                </table>
                            <?php
                            }
                        }

                        if (check_access_permissions_for_view($security_details, 'send_reminder') && (sizeof($documents) || sizeof($w4_form) || sizeof($w9_form) || sizeof($i9_form))) { ?>
                            <div class="btn-panel">
                                <a class="delete-all-btn active-btn" onclick="send_reminder(this.id)" id="<?php echo $userDetail['sid'] ?>" href="javascript:;">Send Reminder</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
</div> -->

<?php if (!empty($w4_form) > 0) { ?>
    <div id="w4_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="review_modal_title">Assigned W4 Form</h4>
                </div>
                <div id="review_modal_body" class="modal-body">
                    <?php $view = get_form_view('pw4', $w4_form);
                    echo $view; ?>
                </div>
                <div id="review_modal_footer" class="modal-footer">

                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (!empty($w9_form) > 0) { ?>
    <div id="w9_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="review_modal_title">Assigned W9 Form</h4>
                </div>
                <div id="review_modal_body" class="modal-body">
                    <?php $view = get_form_view('pw9', $w9_form);
                    echo $view; ?>
                </div>
                <div id="review_modal_footer" class="modal-footer">

                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (!empty($i9_form) > 0) { ?>
    <div id="i9_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="review_modal_title">Assigned I9 Form</h4>
                </div>
                <div id="review_modal_body" class="modal-body">
                    <?php $view = get_form_view('i9', $i9_form);
                    echo $view; ?>
                </div>
                <div id="review_modal_footer" class="modal-footer">

                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (!empty($eeoc_form)) { ?>
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
<?php } ?>

<div id="my_loader" class="text-center my_loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we are processing your request.
        </div>
    </div>
</div>

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

<script type="text/javascript">
    // function fLaunchModal(source) {
    //     var document_preview_url = $(source).attr('data-preview-url');
    //     var document_download_url = $(source).attr('data-download-url');
    //     var document_title = $(source).attr('data-document-title');
    //     var document_file_name = $(source).attr('data-file-name');
    //     var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
    //     var modal_content = '';
    //     var footer_content = '';
    //     var iframe_url = '';

    //     if (document_preview_url != '') {
    //         switch (file_extension.toLowerCase()) {
    //             case 'doc':
    //             case 'docx':
    //             case 'DOC':
    //             case 'DOCX':
    //                 iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
    //                 modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
    //                 break;
    //             case 'jpg':
    //             case 'jpe':
    //             case 'jpeg':
    //             case 'png':
    //             case 'gif':
    //             case 'JPG':
    //             case 'JPE':
    //             case 'JPEG':
    //             case 'PNG':
    //             case 'GIF':
    //                 modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
    //             default : //using google docs
    //                 iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
    //                 modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
    //                 break;
    //         }

    //         footer_content = '<a target="_blank" download="download" class="btn btn-success" href="' + document_download_url + '">Download</a>';
    //     } else {
    //         modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
    //         footer_content = '';
    //     }

    //     $('#document_modal_body').html(modal_content);
    //     $('#document_modal_footer').html(footer_content);
    //     $('#document_modal_title').html(document_title);
    //     $('#document_modal').modal("toggle");
    //     $('#document_modal').on("shown.bs.modal", function () {

    //         if (iframe_url != '') {
    //             $('#preview_iframe').attr('src', iframe_url);
    //         }
    //     });
    // }

    // function func_get_generated_document_preview(document_sid, source = 'original', fetch_data = 'original') {
    //     var my_request;
    //     my_request = $.ajax({
    //         'url': '<?php echo base_url('hr_documents_management/ajax_responder'); ?>',
    //         'type': 'POST',
    //         'data': {
    //             'perform_action': 'get_generated_document_preview',
    //             'document_sid': document_sid,
    //             'user_type': '<?php echo $user_type; ?>',
    //             'user_sid': <?php echo $user_sid; ?>,
    //             'source': source,
    //             'fetch_data': fetch_data
    //         }
    //     });

    //     my_request.done(function (response) {
    //         $('#popupmodalbody').html(response);
    //         $('#popupmodallabel').html('Preview Hr Document');
    //         $('#popupmodal .modal-dialog').css('width', '60%');
    //         $('#popupmodal').modal('toggle');
    //     });
    // }

    // function func_show_submitted_generated_document(document_base_64) {
    //     modal_content = '<iframe src="' + document_base_64 + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
    //     footer_content = '';
    //     $('#document_modal_body').html(modal_content);
    //     $('#document_modal_footer').html(footer_content);
    //     $('#document_modal_title').html('Preview Hr Document');
    //     $('#document_modal').modal("toggle");
    // }

    function send_reminder(id) {
        var url = "<?= base_url() ?>hr_documents_management/send_document_reminder";
        alertify.confirm('Confirmation', "Are you sure you want to send a Document Reminder?",
            function() {
                $('#my_loader').show();
                $.post(url, {
                        user_document_sid: id
                    })
                    .done(function(data) {
                        if (data == 'success')
                            alertify.alert('SUCCESS!', 'Reminder email has been sent.');
                        else
                            alertify.alert('ERROR!', 'Something went wrong while sending reminder email. Please, try again ain a few seconds.');
                        $('#my_loader').fadeOut(300);
                    });
            },
            function() {});
    }

    $('#my_loader').fadeOut(300);

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

            let isPDF = false;
            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    isPDF = true;
                    preview_iframe_url = 'https://docs.google.com/viewer?url=' + file_s3_path + '&embedded=true';
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
                    ocument_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exls';
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


            //
            if (isPDF) {
                modal_content = '<iframe src="" id="latest_document_iframe" class="uploaded-file-preview jsCustomPreview"  style="width:100%; height:500px;" frameborder="0"></iframe>';

                iframe_url = $(source).attr('data-s3-name');
                $.ajax({
                        url: "<?= base_url("v1/Aws_pdf/getFileBase64"); ?>",
                        method: "POST",
                        data: {
                            fileName: iframe_url
                        }
                    })
                    .done(function() {

                    })

                if (isPDF) {
                    preview_iframe_url = "https://automotohrattachments.s3.amazonaws.com/" + iframe_url;
                }
            }



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

            if (preview_document == 1) {
                loadIframe(preview_iframe_url, '#latest_document_iframe', true);
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

                            loadIframe(requested_content, '#latest_document_iframe', true);
                        } else {
                            $('#latest-iframe-container').hide();
                            $('#latest_assigned_document_preview').show();
                            $("#latest_assigned_document_preview").html(document_view);

                            form_input_data = Object.entries(form_input_data);

                            $.each(form_input_data, function(key, input_value) {
                                if (input_value[0] == 'signature_person_name') {
                                    var input_field_id = input_value[0];
                                    var input_field_val = input_value[1];
                                    $('#' + input_field_id).val(input_field_val);
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

    $('#show_latest_preview_document_modal').on('hidden.bs.modal', function() {
        $("#latest-iframe-holder").html('');
        $("#latest_document_iframe").remove();
        $("#latest_image_tag").remove();
        $('#latest-iframe-container').hide();
        $('#latest_assigned_document_preview').html('');
        $('#latest_assigned_document_preview').hide();
    });

    //
    $(function() {
        var employee = <?= json_encode($userDetail); ?>;
        //
        $('.jsSendEmailReminder').click(function(event) {
            //
            event.preventDefault();
            //
            alertify.confirm('Do you really want to send an email reminder notification?', function() {
                //
                startSendEmailProcess();
            });
        });

        //
        function startSendEmailProcess() {
            //
            var text = '<p>Please wait, while we are sending email to <b>' + (employee.first_name + ' ' + employee.last_name) + '</b></p>';
            //
            loader(true, text);
            //
            $.post("<?= base_url('send_manual_reminder_email_to_employee'); ?>", {
                first_name: employee.first_name,
                last_name: employee.last_name,
                email: employee.email,
                company_sid: "<?= $session['company_detail']['sid']; ?>",
                company_name: "<?= $session['company_detail']['CompanyName']; ?>"
            }).done(function() {
                //
                loader(false);
                //
                alertify.alert('Success!', 'You have successfully sent an email reminder to ' + (employee.first_name + ' ' + employee.last_name) + '', function() {
                    return;
                });
            });
        }

        //
        function loader(doShow, text) {
            //
            if (doShow) {
                $('#jsEmployeeEmailLoader').show(0);
                $('#jsEmployeeEmailLoader .jsLoaderText').html(text);
            } else {
                $('#jsEmployeeEmailLoader').hide(0);
                $('#jsEmployeeEmailLoader .jsLoaderText').html('Please wait, while we are processing your request.');
            }
        }
    })
</script>


<?php $this->load->view('iframeLoader'); ?>
<?php $this->load->view('hr_documents_management/hybrid/scripts', ['assigned_documents' => $assigned_documents, 'assigned_offer_letters' => $assigned_offer_letters]); ?>