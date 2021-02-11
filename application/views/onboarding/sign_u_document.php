<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$delete_post_url = '';
$save_post_url = '';

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/documents/' . $unique_sid);

    $download_url = base_url('onboarding/download_u_document/' . $unique_sid . '/' . $document['document_sid']);

} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = base_url('documents_management/my_documents');

    $download_url = base_url('documents_management/download_u_document/' . $document['document_sid']);

    $unique_sid = ''; //No Need for Unique Sid for Employee
}

?>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <a href="<?php echo $back_url; ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Documents</a>
                </div>
                <div class="page-header">
                    <h1 class="section-ttile">Sign Document</h1>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="hr-box">
                            <div class="hr-innerpadding">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="alert alert-info">
                                            <strong>Information:</strong> If you are unable to view the document, kindly reload the page.
                                        </div>
                                        <div class="img-thumbnail text-center" style="width: 100%; max-height: 80em;">
                                            <?php $document_filename = $document['document_s3_file_name']; $document['uploaded_file']?>
                                            <?php $document_file = pathinfo($document_filename); ?>
                                            <?php $dcoument_extension = $document_file['extension']; ?>
                                            <?php if(in_array($dcoument_extension, ['pdf'])){ ?>
                                                <?php $allowed_mime_types = ''?>
                                                <iframe src="<?php echo 'https://docs.google.com/gview?url=' . urlencode(AWS_S3_BUCKET_URL . $document_filename) . '&embedded=true'; ?>" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php } else if(in_array($dcoument_extension, [ 'jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])){ ?>
                                                <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>" />
                                            <?php } else if(in_array($dcoument_extension, ['doc', 'docx'])){ ?>
                                                <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="table-responsive table-outer">
                                                            <table class="table table-bordered table-hover table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Action</th>
                                                                        <th class="text-center">Status</th>
                                                                        <th colspan="2"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="col-xs-3">
                                                                            Downloaded
                                                                        </th>
                                                                        <td class="col-xs-1 text-center">
                                                                            <?php echo $document['downloaded'] == 1 ? '<strong class="text-success">Yes</strong>' : '<strong class="text-danger">No</strong>'?>
                                                                        </td>
                                                                        <td><small class="text-success">(Download a copy of document to print)</small></td>
                                                                        <td class="col-xs-2">
                                                                            <a target="_blank" href="<?php echo $download_url;?>" class="btn btn-success btn-block">Download</a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="col-xs-3">
                                                                            Acknowledged
                                                                        </th>
                                                                        <td class="col-xs-1 text-center">
                                                                            <?php echo $document['acknowledged'] == 1 ? '<strong class="text-success">Yes</strong>' : '<strong class="text-danger">No</strong>'?>
                                                                        </td>
                                                                        <td><small class="text-success">(Acknowledge that you have read and understood the document)</small></td>
                                                                        <td class="col-xs-2">
                                                                            <form id="form_acknowledge_document" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="acknowledge_document" />
                                                                                <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo $unique_sid; ?>" />
                                                                                <input type="hidden" id="user_type" name="user_type" value="<?php echo $document['user_type']; ?>" />
                                                                                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $document['user_sid']; ?>" />
                                                                                <input type="hidden" id="document_type" name="document_type" value="uploaded" />
                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['document_sid']; ?>" />
                                                                            </form>
                                                                            <button onclick="func_acknowledge_document();" type="button" class="btn btn-success btn-block">Acknowledge</button>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="col-xs-3">
                                                                            Uploaded
                                                                        </th>
                                                                        <td class="col-xs-1 text-center">
                                                                            <?php echo $document['uploaded'] == 1 ? '<strong class="text-success">Yes</strong>' : '<strong class="text-danger">No</strong>'?>
                                                                        </td>
                                                                        <td colspan="2">
                                                                            
                                                                            <form id="form_upload_file" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="upload_document" />
                                                                                <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo $unique_sid; ?>" />
                                                                                <input type="hidden" id="user_type" name="user_type" value="<?php echo $document['user_type']; ?>" />
                                                                                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $document['user_sid']; ?>" />
                                                                                <input type="hidden" id="document_type" name="document_type" value="uploaded" />
                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['document_sid']; ?>" />
                                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $document['company_sid']; ?>" />

                                                                                <div class="row">
                                                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                                                        <div class="form-wrp width-280">
                                                                                            <div class="form-group">
                                                                                                <div class="upload-file form-control">
                                                                                                    <span class="selected-file" id="name_upload_file">No file selected</span>
                                                                                                    <input name="upload_file" id="upload_file" type="file" />
                                                                                                    <a href="javascript:;">Choose File</a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                                        <button type="submit" class="btn btn-primary btn-equalizer btn-block"><?php echo empty($document['uploaded_file']) ? 'Upload' : 'Re-Upload'; ?></button>
                                                                                        <?php if(!empty($document['uploaded_file'])) { ?>
                                                                                            <?php $document_filename = $document['uploaded_file'];?>
                                                                                            <?php $document_file = pathinfo($document_filename); ?>
                                                                                            <?php $document_extension = $document_file['extension']; ?>
                                                                                            <a class="btn btn-success btn-equalizer btn-block"
                                                                                               href="javascript:void(0);"
                                                                                               onclick="fLaunchModal(this);"
                                                                                               data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                                                               data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                                                               data-file-name="<?php echo $document_filename; ?>"
                                                                                               data-document-title="<?php echo $document_filename; ?>"
                                                                                               data-preview-ext="<?php echo $document_extension ?>">Preview Uploaded</a>
                                                                                        <?php } ?>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                            <small class="text-success">(<?php echo empty($document['uploaded_file']) ? 'Upload the Signed Document' : 'In case you uploaded wrong document you can replace with the correct version by re uploading it.'; ?>)</small>
                                                                        </td>
                                                                    </tr>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

        $('#form_upload_file').validate({
            rules: {
                upload_file: {
                    required: true,
                    accept: 'image/png,image/bmp,image/gif,image/jpeg,image/tiff,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                }
            },
            messages: {
                upload_file: {
                    required: 'You must select a file to upload.',
                    accept: 'Only Images, MS Word Documents and PDF files are allowed.'
                }
            }
        });

        $('body').on('change', 'input[type=file]', function () {
            var selected_file = $(this).val();
            var selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);

            var id = $(this).attr('id');
            $('#name_' + id).html(selected_file);
        });
    });

    function func_acknowledge_document() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Acknowledge the document?',
            function () {
                $('#form_acknowledge_document').submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = $(source).attr('data-preview-ext');
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    break;
                default :
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

            footer_content = '<a target="_blank" download="download" class="btn btn-success" href="' + document_download_url + '">Download</a>';
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
            }
        });
    }
</script>

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