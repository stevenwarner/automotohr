<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';

if (isset($applicant)) {

    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $first_name = $applicant['first_name'];
    $last_name = $applicant['last_name'];
    $email = $applicant['email'];
    $back_url = base_url('onboarding/getting_started/' . $unique_sid);

} else if (isset($employee)) {

    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $first_name = $employee['first_name'];
    $last_name = $employee['last_name'];
    $email = $employee['email'];
    $back_url = base_url('my_profile');

}

$view_data = array();
$view_data['company_sid'] = $company_sid;
$view_data['users_type'] = $users_type;
$view_data['users_sid'] = $users_sid;
$view_data['first_name'] = $first_name;
$view_data['last_name'] = $last_name;
$view_data['email'] = $email;
$view_data['save_post_url'] = current_url();
$view_data['documents_assignment_sid'] = $document['sid'];
$view_data['is_old_document'] = true;

?>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <a href="<?php echo $back_url; ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> back</a>
                </div>
            </div>
            <div class="<?php echo $users_type != 'applicant' ? 'col-lg-9 col-md-9 col-xs-12 col-sm-8' : 'col-lg-12 col-md-12 col-xs-12 col-sm-12'; ?>">
                <div class="page-header">
                    <h1 class="section-ttile">Documents</h1>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <?php if(!empty($document)) { ?>
                            <?php if($document['document_type'] == 'document') { ?>
                                <div class="img-thumbnail text-center" style="width: 100%;">
                                    <?php $document_filename = $document['document_name'];?>
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

                            <?php } else if ($document['document_type'] == 'offerletter') {?>
                                <div class="document_container" style="padding: 40px; background-color: #FFFFFF;">
                                    <div class="text-center">
                                        <h3 class=""><?php echo $document['offer_letter_name']; ?></h3>
                                    </div>
                                    <?php echo html_entity_decode($document['offer_letter_body']); ?>
                                </div>
                            <?php } ?>
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
                                                                <th class="col-xs-3">Downloaded</th>
                                                                <td class="col-xs-1 text-center">
                                                                    <?php echo $document['downloaded'] == 1 ? '<strong class="text-success">Yes</strong>' : '<strong class="text-danger">No</strong>'?>
                                                                </td>
                                                                <td></td>
                                                                <td class="col-xs-2">
                                                                    <a target="_blank" href="<?php echo base_url('documents_management/download_old_system_document/' . $document['sid']);?>" class="btn btn-success btn-block">Download</a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3">Acknowledged</th>
                                                                <td class="col-xs-1 text-center">
                                                                    <?php echo $document['acknowledged'] == 1 ? '<strong class="text-success">Yes</strong>' : '<strong class="text-danger">No</strong>'?>
                                                                </td>
                                                                <td></td>
                                                                <td class="col-xs-2">
                                                                    <form id="form_acknowledge_document" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="acknowledge_document" />
                                                                        <input type="hidden" id="user_type" name="user_type" value="employee" />
                                                                        <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $document['receiver_sid']; ?>" />
                                                                        <input type="hidden" id="record_sid" name="record_sid" value="<?php echo $document['sid']; ?>" />
                                                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $document['company_sid']; ?>" />
                                                                        <input type="hidden" id="document_type" name="document_type" value="uploaded" />
                                                                        <button type="submit" class="btn btn-success btn-block">Acknowledge</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3">Uploaded</th>
                                                                <td class="col-xs-1 text-center">
                                                                    <?php echo $document['uploaded'] == 1 ? '<strong class="text-success">Yes</strong>' : '<strong class="text-danger">No</strong>'?>
                                                                </td>
                                                                <td colspan="2">
                                                                    <form id="form_upload_file" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="upload_document" />
                                                                        <input type="hidden" id="user_type" name="user_type" value="employee" />
                                                                        <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $document['receiver_sid']; ?>" />
                                                                        <input type="hidden" id="record_sid" name="record_sid" value="<?php echo $document['sid']; ?>" />
                                                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $document['company_sid']; ?>" />
                                                                        <input type="hidden" id="document_type" name="document_type" value="uploaded" />

                                                                        <div class="row">
                                                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                                                <div class="form-wrp">
                                                                                    <div class="form-group">
                                                                                        <input class="filestyle" name="upload_file" id="upload_file" type="file" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                                <button type="submit" class="btn btn-primary btn-equalizer btn-block"><?php echo empty($document['uploaded_file']) ? 'Upload' : 'Re-Upload'; ?></button>
                                                                                <?php if(!empty($document['uploaded_file'])) { ?>
                                                                                    <?php $document_filename = $document['uploaded_file'];?>
                                                                                    <?php $document_file = pathinfo($document_filename); ?>
                                                                                    <?php $document_extension = $document_file['extension']; ?>
                                                                                    <button class="btn btn-success btn-equalizer btn-block" type="button"
                                                                                            onclick="fLaunchModal(this);"
                                                                                            data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                                                            data-download-url="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                                                            data-file-name="<?php echo $document_filename; ?>"
                                                                                            data-document-title="<?php echo $document_filename; ?>"
                                                                                            data-preview-ext="<?php echo $document_extension ?>">Preview Uploaded</button>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </form>
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
                        <?php } else { ?>

                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if($users_type != 'applicant') { ?>
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/employee_hub_right_menu'); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

    });

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

<div id="document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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