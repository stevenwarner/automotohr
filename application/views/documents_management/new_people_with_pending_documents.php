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
<!--                                    <div class="row">-->
<!--                                        <div class="col-xs-12">-->
<!--                                            <h3 class="">Employees</h3>-->
<!--                                        </div>-->
<!--                                    </div>-->


                                    <?php
                                    if ( isset($employees) && count($employees) > 0) {
                                        ?>
                                        <div class="table-responsive">
                                            <h3>Employees with Pending Document Actions</h3>
                                            <div class="hr-document-list">
                                                <table class="hr-doc-list-table">
                                                    <thead>
                                                    <tr>
                                                        <th>Employee Name</th>
                                                        <th>Email</th>
                                                        <th style="text-align: right" >View Document(s)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    foreach ($employees as $employee) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo ucfirst($employee['first_name']); ?> <?php echo $employee['last_name']; ?></td>
                                                            <td><?php echo $employee['email']; ?></td>
                                                            <td>
                                                                <a  href="<?php echo base_url('documents_management/employee_document'); ?>/<?php echo $employee['sid']; ?>" class="action-btn">
                                                                    <i class="fa fa-eye"></i>
                                                                    <span class="btn-tooltip">View</span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="table-responsive">
                                            <h3>Employees with Pending Document Actions</h3>
                                            <div class="hr-document-list">
                                                <table class="hr-doc-list-table">
                                                    <thead>
                                                    <tr>
                                                        <th>Employee Name</th>
                                                        <th>Email</th>
                                                        <th style="text-align: right" >View Document(s)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="3">No employee with pending document(s)</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <?php
                                    }?>


                                </div>
                            </div>

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