<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>

                    <div class="message-action">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <form enctype="multipart/form-data" id="document_search" method="post" novalidate="novalidate">
                                            <div class="row" style="margin-top: 12px;">
                                                <div class="col-lg-8 col-md-4 col-xs-12 col-sm-4">
                                                    <label>Document title </label>
                                                    <input type="text" name="document_title" id="document_title" value="<?php echo $documentTitle; ?>" class="invoice-fields">
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-block btn-success">Apply Filter</button>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-block btn-success" id="clearefilter">Cleare Filter</button>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="message-action-btn">
                                    <a class="submit-btn" href="<?php echo base_url('add_secure_document'); ?>">Uploade New Document</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($secure_documents)) { ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                                <div class="table-responsive table-outer">
                                    <div class="data-table">
                                        <table id="categories_table" class="table">
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-6">Document Title</th>
                                                    <th class="col-xs-2">Created By</th>
                                                    <th class="col-xs-2">Created At</th>
                                                    <?php $function_names = array(''); ?>
                                                    <th class="col-xs-1">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($secure_documents as $rowDocument) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $rowDocument['document_title']; ?></td>
                                                        <td><?php echo getUserNameBySID($rowDocument['created_by']) ?></td>
                                                        <td><?php echo formatDateToDB($rowDocument['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></td>
                                                        <td>
                                                            <a class="btn btn-default" href="<?php echo base_url('edit_secure_document/') . $rowDocument['sid']; ?>"><i class="fa fa-pencil"></i></a>
                                                            <button class="btn btn-info btn-sm btn-block" onclick="fLaunchModal(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $rowDocument['document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $rowDocument['document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-document-sid="<?php echo  $rowDocument['sid']; ?>" data-file-name="<?php echo $rowDocument['document_title']; ?>" data-document-title="<?php echo $rowDocument['document_title']; ?>">View</button>
                                                            <a class="btn btn-success" target="_blank" href="<?php echo base_url('hr_documents_management/download_upload_document/' . $rowDocument['document_s3_name']);?>">Download</a>
                                                        </td>
                                                    </tr>
                                                <?php }
                                                ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div id="show_no_jobs">
                                                    <span class="applicant-not-found">No Document Found!</span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php $this->load->view('iframeLoader'); ?>

<div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
</div>


<script>
    $("#clearefilter").click(function() {
        $('#document_title').val('');
        $("form").submit();
    });


    //
    function fLaunchModal(source) {
        var url_segment_original = $(source).attr('data-print-url');
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var document_sid = $(source).attr('data-document-sid');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                case 'ppt':
                case 'pptx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
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
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    footer_print_btn = '<a target="_blank" href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original/generated'); ?>' + '/' + document_sid + '" class="btn btn-success">Print</a>';
                    break;
                default: //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $.ajax({
            'url': '<?php echo base_url('hr_documents_management/get_print_url_secure'); ?>',
            'type': 'POST',
            'data': {
                'request_type': 'original',
                'document_type': 'MS',
                'document_sid': document_sid
            },
            success: function(urls) {
                var obj = jQuery.parseJSON(urls);
                var print_url = obj.print_url;
                var download_url = obj.download_url;
                footer_content = '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + print_url + '" >Print</a>';

                $('#document_modal_body').html(modal_content);
                $('#document_modal_footer').html(footer_content);
                $('#document_modal_footer').append(footer_print_btn);
                $('#document_modal_title').html(document_title);
                $('#document_modal').modal("toggle");
                $('#document_modal').on("shown.bs.modal", function() {

                    if (iframe_url != '') {
                        $('#preview_iframe').attr('src', iframe_url);
                        loadIframe(iframe_url, '#preview_iframe', true);
                    }
                });
            }
        });
    }
</script>