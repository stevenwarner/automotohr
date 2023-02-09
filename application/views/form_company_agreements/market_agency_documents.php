<?php $this->load->view('main/static_header'); ?>
<body>
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="credit-card-authorization">
                    <div class="top-logo text-center">
                        <img src="<?php echo base_url('assets/images/form-logo.jpg') ?>">
                    </div>
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Company Agreements</span>
                    <div class="end-user-agreement-wrp recurring-payment-authorization">
                        <div class="row">
                            <div class="col-xs-12">
                                <hr />
                                <h4 class="text-center">
                                    Below is a document from <?php echo STORE_NAME; ?> please complete and return it as soon as possible.<br><br>

                                    "Download" and complete the following documents.<br>
                                    Once the documents have been filled out and completed save to your computer.<br>
                                    Click on the "Choose file" button below, select the document file and click the "Open" button. Then click the "Upload" button.
                                </h4>
                                <hr />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <p><strong>Document Detail</strong></p>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-stripped table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <th>Document Name</th>
                                                                <td><?php echo $document_record['document_name']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Date</th>
                                                                <td><?php echo convert_date_to_frontend_format($document_record['insert_date']); ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="javascript:void(0);" onclick="fLaunchModal(this);" class=" btn btn-success" data-preview-url="<?php echo $document_record['aws_document_name']; ?>" data-download-url="<?php echo $document_record['aws_document_name']; ?>" data-document-title="<?php echo $document_record['document_name']; ?>" >Download</a>
<!--                                        <a href="--><?php //echo AWS_S3_BUCKET_URL . $document_record['aws_document_name']; ?><!--" class="submit-btn" >Download</a>-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <form id="form_upload_document" method="post" enctype="multipart/form-data" action="<?php echo base_url('form_company_agreements/market_agency_documents/' . $document_record['verification_key']);?>">
                                    <input type="hidden" id="company_sid" name="marketing_agency_sid" value="<?php echo $document_record['marketing_agency_sid']?>" />
                                    <input type="hidden" id="document_name" name="document_name" value="<?php echo $document_record['document_name']?>" />
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <p><strong>Upload Filled Document</strong></p>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label for="client_document">Select File</label>

                                                    <div class="upload-file file-button-container">
                                                        <span id="file_client_document" class="selected-file">No file selected</span>
                                                        <input type="file" id="client_document" name="client_document" />
                                                        <a href="javascript:void(0);">Choose File</a>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-footer">
                                            <button type="button" class="submit-btn"  onclick="fValidateDocumentUploadForm();">Upload</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="file_preview_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
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
    $(document).ready(function () {
        $('#client_document').on('change', function () {
            //console.log($(this).val());
            $('#file_client_document').html($(this).val());
        });
    });

    function fValidateDocumentUploadForm(){
        $('#form_upload_document').validate({
            rules: {
                'client_document': {
                    required : true,
                    extension: "docx|rtf|doc|pdf"
                }
            },
            messages: {
                'client_document': {
                    required : 'Please Select a Document',
                    extension: 'Only .doc, .docx, and .pdf files are allowed.'
                }
            }
        });

        if($('#form_upload_document').valid()){
            $('#form_upload_document').submit();
        }
    }

    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var type = document_preview_url.split(".");
        var file_type = type[type.length - 1];
        var modal_content = '';
        var footer_content = '';
        var iframe_url = 'https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL; ?>' + document_preview_url + '&embedded=true';
        var is_document = false;

        if (document_preview_url != '') {
            if (file_type == 'jpg' || file_type == 'jpe' || file_type == 'jpeg' || file_type == 'png' || file_type == 'gif'){
                modal_content = '<img src="<?php echo AWS_S3_BUCKET_URL; ?>' + document_preview_url + '" style="width:100%; height:500px;" />';
            } else {
                is_document = true;
                modal_content = '<iframe id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
            }

            footer_content = '<a class="btn btn-success" href="<?php echo AWS_S3_BUCKET_URL; ?>' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#file_preview_modal').modal("toggle");

        if (is_document) {
            document.getElementById('preview_iframe').contentWindow.location = iframe_url;
        }
    }
</script>
</body>
</html>