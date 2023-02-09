<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-xs-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="page-header-area">
                                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                            <a class="dashboard-link-btn" href="<?php echo base_url('documents_management'); ?>"><i class="fa fa-chevron-left"></i>Document Management</a>
                                            <?php echo $title; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h3 class="">Employees</h3>
                                        </div>
                                    </div>
                                    <div class="row margin-top-10">
                                        <div class="col-xs-12">
                                            <ul class="nav nav-tabs nav-justified">
                                                <li class="active"><a data-toggle="tab" href="#employee_uploaded_documents">Uploaded Documents</a></li>
                                                <li><a data-toggle="tab" href="#employee_generated_documents">Generated Documents</a></li>
                                                <li><a data-toggle="tab" href="#old_pending_documents">Old Pending Documents</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div id="employee_uploaded_documents" class="tab-pane fade in active hr-innerpadding">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <div class="table-responsive margin-top-20">
                                                                <table class="table table-bordered table-striped table-hover table-condensed">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="col-xs-3">Name</th>
                                                                            <th class="col-xs-4">Document</th>
                                                                            <th class="col-xs-1">Sent On</th>
                                                                            <th class="col-xs-1 text-center">Acknowledged</th>
                                                                            <th class="col-xs-1 text-center">Downloaded</th>
                                                                            <th class="col-xs-1 text-center">Uploaded</th>
                                                                            <th class="col-xs-1 text-center">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if(!empty($uploaded_employee_documents)) { ?>
                                                                            <?php foreach($uploaded_employee_documents as $uploaded_employee_document) { ?>
                                                                                <tr>
                                                                                    <td><?php echo $uploaded_employee_document['first_name'] . ' ' . $uploaded_employee_document['last_name']; ?></td>
                                                                                    <td><?php echo $uploaded_employee_document['document_name']; ?></td>
                                                                                    <td><?php echo date_with_time($uploaded_employee_document['assigned_date']); ?></td>
                                                                                    <td class="text-center">
                                                                                        <?php if($uploaded_employee_document['acknowledged'] == 0) { ?>
                                                                                            <span class="text-danger">No</span>
                                                                                        <?php } else { ?>
                                                                                            <span class="text-success">Yes</span>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php if($uploaded_employee_document['downloaded'] == 0) { ?>
                                                                                            <span class="text-danger">No</span>
                                                                                        <?php } else { ?>
                                                                                            <span class="text-success">Yes</span>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php if($uploaded_employee_document['uploaded'] == 0) { ?>
                                                                                            <span class="text-danger">No</span>
                                                                                        <?php } else { ?>
                                                                                            <span class="text-success">Yes</span>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button" class="btn btn-info btn-sm btn-block"
                                                                                                onclick="fLaunchModal(this);"
                                                                                                data-preview-url="<?php echo AWS_S3_BUCKET_URL . $uploaded_employee_document["document_s3_file_name"]; ?>"
                                                                                                data-download-url="<?php echo AWS_S3_BUCKET_URL . $uploaded_employee_document["document_s3_file_name"]; ?>"
                                                                                                data-file-name="<?php echo $uploaded_employee_document['document_original_name']; ?>"
                                                                                                data-document-title="<?php echo $uploaded_employee_document['document_original_name']; ?>">View</button>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <tr>
                                                                                <td colspan="9">
                                                                                    <span class="no-data">No Documents</span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="employee_generated_documents" class="tab-pane fade hr-innerpadding">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <div class="table-responsive margin-top-20">
                                                                <table class="table table-bordered table-striped table-hover table-condensed">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="col-xs-3">Name</th>
                                                                            <th class="col-xs-6">Document</th>
                                                                            <th class="col-xs-1">Sent On</th>
                                                                            <th class="col-xs-1 text-center">eSigned</th>
                                                                            <th class="col-xs-1 text-center">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if(!empty($generated_employee_documents)) { ?>
                                                                            <?php foreach($generated_employee_documents as $generated_employee_document) { ?>
                                                                                <tr>
                                                                                    <td><?php echo $generated_employee_document['first_name'] . ' ' . $generated_employee_document['last_name']; ?></td>
                                                                                    <td><?php echo $generated_employee_document['document_title']; ?></td>
                                                                                    <td><?php echo date_with_time($uploaded_employee_document['assigned_date']); ?></td>
                                                                                    <td class="text-center">
                                                                                        <?php if($generated_employee_document['signature'] == '') { ?>
                                                                                            <span class="text-danger">No</span>
                                                                                        <?php } else { ?>
                                                                                            <span class="text-success">Yes</span>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button onclick="func_get_generated_document_preview(<?php echo $generated_employee_document['document_sid']; ?>, '<?php echo $generated_employee_document['user_type']; ?>', <?php echo $generated_employee_document['user_sid']; ?>);" class="btn btn-info btn-sm btn-block">View</button>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <tr>
                                                                                <td colspan="6">
                                                                                    <span class="no-data">No Records</span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="old_pending_documents" class="tab-pane fade hr-innerpadding">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <div class="table-responsive margin-top-20">
                                                                <table class="table table-bordered table-striped table-hover table-condensed">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="col-xs-3">Name</th>
                                                                        <th class="col-xs-4">Document</th>
                                                                        <th class="col-xs-1">Sent On</th>
                                                                        <th class="col-xs-1 text-center">Acknowledged</th>
                                                                        <th class="col-xs-1 text-center">Downloaded</th>
                                                                        <th class="col-xs-1 text-center">Uploaded</th>
                                                                        <th class="col-xs-1 text-center">Actions</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php if(!empty($old_pending_documents)) { ?>
                                                                        <?php foreach($old_pending_documents as $old_pending_document) { ?>
                                                                            <tr>
                                                                                <td><?php echo !empty($old_pending_document['user']) ? $old_pending_document['user']['first_name'] . ' ' . $old_pending_document['user']['last_name'] : 'Not Available'; ?></td>
                                                                                <td><?php echo $old_pending_document['document']['document_original_name'];?></td>
                                                                                <td><?php echo date_with_time($old_pending_document['sent_on']);?></td>
                                                                                <td class="text-center"><?php echo $old_pending_document['acknowledged'] == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' ;?></td>
                                                                                <td class="text-center"><?php echo $old_pending_document['downloaded'] == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' ;?></td>
                                                                                <td class="text-center"><?php echo $old_pending_document['uploaded'] == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' ;?></td>
                                                                                <td class="text-center"><a target="_blank" href="<?php echo base_url('edit_hr_document/' . $old_pending_document['document_sid']); ?>" class="btn btn-info btn-sm btn-block">View</a></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <tr>
                                                                            <td colspan="9">
                                                                                <span class="no-data"></span>
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
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--
                            <hr />
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h3 class="">Applicants</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <ul class="nav nav-tabs nav-justified">
                                                <li class="active"><a data-toggle="tab" href="#applicant_uploaded_documents">Uploaded Documents</a></li>
                                                <li><a data-toggle="tab" href="#applicant_generated_documents">Generated Documents</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div id="applicant_uploaded_documents" class="tab-pane fade in active hr-innerpadding">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="applicant_generated_documents" class="tab-pane fade">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function fLaunchModal(source) {
        console.log(source);

        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');

        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);

        var modal_content = '';
        var footer_content = '';

        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                    console.log('in office docs check');
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    console.log('in images check');
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                default :
                    console.log('in google docs check');
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

            footer_content = '<a download="download" class="btn btn-success" href="' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function () {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
                //document.getElementById('preview_iframe').contentWindow.location.reload();
            }
        });


    }

    function func_get_generated_document_preview(document_sid, user_type, user_sid) {
        var my_request;
        my_request = $.ajax({
            'url': '<?php echo base_url('documents_management/ajax_responder'); ?>',
            'type': 'POST',
            'data': {
                'perform_action': 'get_generated_document_preview',
                'document_sid': document_sid,
                'user_type': user_type,
                'user_sid': user_sid,
                'source': 'assigned'
            }
        });

        my_request.done(function (response) {
            $('#popupmodalbody').html(response);
            $('#popupmodallabel').html('Preview Generated Document');

            $('#popupmodal .modal-dialog').css('width', '60%');
            $('#popupmodal').modal('toggle');
        });
    }
</script>

<!-- Modal -->
<div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">

            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>