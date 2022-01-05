<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <?php if ($user_type == 'applicant') { ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management/documents_assignment/employee/' . $user_sid . '/' . $job_list_sid); ?>"><i class="fa fa-chevron-left"></i>Applicant Documents</a>
                                    <?php } else { ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management/documents_assignment/employee/' . $user_sid); ?>"><i class="fa fa-chevron-left"></i>Employee Documents</a>
                                        <?php
                                    }
                                    echo $title;
                                    ?>
                                </span>
                            </div>
                            <div>
                                <h2>
                                    <?php
                                        if($form_type == "uploaded"){
                                    ?>
                                    <?= strtoupper($form_uploaded['document_type']) ." Fillable" ?>
                                    <?php }else{ ?>
                                        <?= strtoupper(str_replace("_assigned","",$form_type)) ." Fillable" ?>
                                    <?php } ?>
                                </h2>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="well">
                                            <div class="help-block">
                                                <h3><strong>Upload Supporting Documents: </strong></h3>
                                                <h4>To upload supporting documents for <?php echo $FormName; ?>, you can select or drag-n-drop the document. Please, follow the below instructions. </h4>
                                                <h4>1- Select the document you want to upload.</h4>
                                                <h4>2- Click the upload button to add the document.</h4>
                                                <h4 class="allow_formate">The allowed formats are; <strong class="allow_formate_color">PDF, DOC, DOCX, PNG, JPG, JPEG</strong></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <form id="form_upload_eev_document" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <label>Browse Document <span class="staric">*</span></label>
                                            <div class="upload-file invoice-fields">
                                                <input style="height: 38px;" type="file" name="document" id="document_file" required onchange="check_file('document_file')">
                                                <p id="name_document_file"></p>
                                                <a href="javascript:;" style="line-height: 38px; height: 38px;">Choose File</a>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                            <input type="hidden" name="eev_documents_sid" id="eev_documents_sid" value="" />
                                            <input type="hidden" name="eev_required_documents_sid" id="eev_required_documents_sid" value="" />
                                            <input type="hidden" name="perform_action" value="upload_required_doc" />
                                            <button id="btn_document_file" style="display: none" type="submit" class="btn btn-success pull-right">Upload</button>
                                            <!-- Drag and Drop container-->
                                            <div class="upload-area"  id="uploadfile">
                                                <h1>To Upload a Document Click Here</br>&<br/>Drag and Drop a Document Here</h1>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <br>
                            <div class="table-responisve">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-4 text-center">Document Name</th>
                                            <th class="col-lg-4 text-center">Uploaded Date</th>
                                            <th class="col-lg-4 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($form_uploaded)){ ?>
                                        <tr>
                                            <td class="col-lg-4 text-center"><?= strtoupper($form_uploaded['document_type']) ." Fillable" ?></td>
                                            <td class="col-lg-4 text-center">
                                            <?=reset_datetime(array( 'datetime' => $form_uploaded['date_uploaded'], '_this' => $this));?>
                                            </td>
                                            <td class="col-lg-4 text-center">
                                                <button class="btn btn-success"
                                                        onclick="preview_document_model(this);"
                                                        data-file-type="form"
                                                        data-document-type="<?= $form_uploaded['document_type'] ?>"
                                                        data-file-name="<?= $form_uploaded['s3_filename'] ?>"
                                                        data-document-title="<?= strtoupper($form_uploaded['document_type']) ." Fillable" ?>"
                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $form_uploaded['s3_filename']; ?>"
                                                        data-download-url="<?= base_url()."hr_documents_management/download_upload_document/". $form_uploaded['s3_filename']; ?>"
                                                        data-document-sid="<?= $form_uploaded['sid'] ?>">View/Update</button>
                                            </td>
                                        </tr>
                                        <?php }else if($form_type == "w4_assigned" && sizeof($w4_form) > 0){ ?>
                                        <tr>
                                            <td class="col-lg-4 text-center">
                                                W4 Fillable
                                            </td>
                                            <td class="col-lg-4 text-center">
                                                <?=reset_datetime(array( 'datetime' => $w4_form['sent_date'], '_this' => $this));?>
                                            </td>
                                            <td class="col-lg-4 text-center">
                                                <?php if(!empty($w4_form['uploaded_file']) && $w4_form['uploaded_file'] != NULL){?>
                                                    <button
                                                        class="btn btn-success"
                                                        onclick="fLaunchModal(this);"
                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $w4_form['uploaded_file']; ?>"
                                                        data-download-url="<?php echo AWS_S3_BUCKET_URL . $w4_form['uploaded_file']; ?>"
                                                        data-file-name="<?php echo $w4_form['uploaded_file']; ?>"
                                                        data-document-title="<?php echo $w4_form['uploaded_file']; ?>">
                                                        View Uploaded Doc
                                                    </button>
                                                <?php }else { ?>
                                                    <a class="btn btn-success" data-toggle="modal" data-target="#w4_modal" href="javascript:void(0);">View Doc</a>
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <?php }else if($form_type == "w9_assigned" && sizeof($w9_form) > 0){ ?>
                                        <tr>
                                            <td class="col-lg-4 text-center">
                                                W9 Fillable
                                            </td>
                                            <td class="col-lg-4 text-center">
                                                <?=reset_datetime(array( 'datetime' => $w9_form['sent_date'], '_this' => $this));?>
                                            </td>
                                            <td class="col-lg-4 text-center">
                                                <?php if(!empty($w9_form['uploaded_file']) && $w9_form['uploaded_file'] != NULL){?>
                                                    <button
                                                        class="btn btn-success"
                                                        onclick="fLaunchModal(this);"
                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $w9_form['uploaded_file']; ?>"
                                                        data-download-url="<?php echo AWS_S3_BUCKET_URL . $w9_form['uploaded_file']; ?>"
                                                        data-file-name="<?php echo $w9_form['uploaded_file']; ?>"
                                                        data-document-title="<?php echo $w9_form['uploaded_file']; ?>">
                                                        View Uploaded Doc
                                                    </button>
                                                <?php }else { ?>
                                                    <a class="btn btn-success" data-toggle="modal" data-target="#w9_modal" href="javascript:void(0);">View Doc</a>
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <?php }else if($form_type == "i9_assigned" && sizeof($i9_form) > 0){ ?>
                                        <tr>
                                            <td class="col-lg-4 text-center">
                                                I9 Fillable
                                            </td>
                                            <td class="col-lg-4 text-center">
                                                <?=reset_datetime(array( 'datetime' => $i9_form['sent_date'], '_this' => $this));?>
                                            </td>
                                            <td class="col-lg-4 text-center">
                                                <?php if(!empty($i9_form['s3_filename']) && $i9_form['s3_filename'] != NULL){?>
                                                    <button
                                                        class="btn btn-success"
                                                        onclick="fLaunchModal(this);"
                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $i9_form['s3_filename']; ?>"
                                                        data-download-url="<?php echo AWS_S3_BUCKET_URL . $i9_form['s3_filename']; ?>"
                                                        data-file-name="<?php echo $i9_form['s3_filename']; ?>"
                                                        data-document-title="<?php echo $i9_form['s3_filename']; ?>">
                                                        View Uploaded Doc
                                                    </button>
                                                <?php }else { ?>
                                                    <?php if ($i9_form['employer_flag']) { ?>
                                                        <a class="btn btn-success" data-toggle="modal"
                                                           data-target="#i9_modal" href="javascript:void(0);">View
                                                            Doc</a>
                                                    <?php } else { ?>
                                                        <a class="btn btn-success"
                                                           href="<?php echo $user_type == 'applicant' ? base_url('form_i9/applicant') . '/' . $applicant_info['sid'] . "/" . $job_list_sid : base_url('form_i9/employee') . '/' . $employer['sid']; ?>">View
                                                            Doc</a>
                                                    <?php }
                                                }?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <?php foreach ($required_documents as $required_document) { ?>
                                            <tr>
                                                <td class="col-lg-4 text-center"><?= $required_document['document_name'] ?></td>
                                                <td class="col-lg-4 text-center">
                                                <?=reset_datetime(array( 'datetime' => $required_document['date_uploaded'], '_this' => $this));?>
                                                </td>
                                                <td class="col-lg-4 text-center">
                                                    <button class="btn btn-success"
                                                            onclick="preview_document_model(this);"
                                                            data-file-type="docs"
                                                            data-file-name="<?= $required_document['s3_filename'] ?>"
                                                            data-document-title="<?= $required_document['document_name'] ?>"
                                                            data-preview-url="<?php echo AWS_S3_BUCKET_URL . $required_document['s3_filename']; ?>"
                                                            data-download-url="<?= base_url()."hr_documents_management/download_upload_document/". $required_document['s3_filename']; ?>"
                                                            data-document-sid="<?= $required_document['sid'] ?>"
                                                            >View/Update</button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>


<!-- Document Modal -->
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

<!-- Modal -->
<div id="upload_eev_document" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="uploaded_document_modal_title">Required Documents</h4>
            </div>
            <div id="uploaded_document_modal_body" class="modal-body">
                <div class="preview">

                </div>
            </div>
            <div id="uploaded_document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="upload_eev_form_document" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="uploaded_document_modal_title">Required Documents</h4>
            </div>
            <div id="uploaded_document_modal_body" class="modal-body">
                <form id="form_upload_eev_document_popup" enctype="multipart/form-data" method="post" action="<?php echo base_url()."hr_documents_management/documents_assignment/employee/".$user_sid; ?>">
                    <div class="row">
                        <div class="col-xs-12">
                            <label>Browse Document <span class="staric">*</span></label>
                            <div class="upload-file invoice-fields">
                                <input style="height: 38px;" type="file" name="document" id="document_pop" required onchange="check_file('document_pop')">
                                <p id="name_document_pop"></p>
                                <a href="javascript:;" style="line-height: 38px; height: 38px;">Choose File</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                            <input type="hidden" name="sid" id="eev_sid" value="" />
                            <input type="hidden" name="document_type" id="data-document-type" value="" />
                            <input type="hidden" name="perform_action" value="upload_eev_document" />
                            <input type="hidden" name="redirect_link" value="<?php echo current_url(); ?>" />
                            <button id="btn_document_pop" style="display: none" type="submit" class="btn btn-success pull-right">Upload</button>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="preview">

                </div>
            </div>
            <div id="uploaded_document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .upload-area{
        width: 100%;
        height: 200px;
        border: 2px solid lightgray;
        border-radius: 3px;
        margin: 0 auto;
        margin-top: 50px;
        text-align: center;
        overflow: auto;
    }

    .upload-area:hover{
        cursor: pointer;
    }

    .upload-area h1{
        text-align: center;
        font-weight: normal;
        font-family: sans-serif;
        line-height: 50px;
        color: darkslategray;
    }

    #drop_box_file{
        display: none;
    }

    .allow_formate {
        font-style: italic;
    }

    .allow_formate_color {
        color: #ea0000;
    }
</style>
<script>
    $(function() {

        // Open file selector on div click
        $("#uploadfile").click(function(){
            $("#document_file").click();
        });

        uploadfile.ondragover = uploadfile.ondragenter = function(evt) {
            evt.preventDefault();
        };

        uploadfile.ondrop = function(evt) {
            document_file.files = evt.dataTransfer.files;
            evt.preventDefault();
            uploadDragBoxData();
        };

        // preventing page from redirecting
        $("html").on("dragover", function(e) {
            e.preventDefault();
            e.stopPropagation();
            $("h1").text("Drag Document Here");
        });


        //Drag over
        $('.upload-area').on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop Document");
        });
    });
    // Sending AJAX request and upload file
    function uploadDragBoxData(){
        var fileName = $("#document_file").val();

        if (fileName.length > 0) {
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();
            if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "png" && ext != "jpg" && ext != "jpeg") {
                $("#drop_box_file").val(null);
                $("h1").text('Only (.pdf .docx .doc .png .jpg .jpeg) allowed!');
            } else {
                submit_upload_file();
            }
        } else {
            $("h1").text('Please Select Document');
        }
    }

    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var footer_content_print = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    iframe_url = document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_content_print = '<a target="_blank" class="btn btn-success" href="https://docs.google.com/gview?url=' + document_download_url + '&embedded=true">Print</a>';
                    break;
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_content_print = '<a target="_blank" class="btn btn-success" href="https://view.officeapps.live.com/op/embed.aspx?src=' + document_download_url + '&embedded=true">Print</a>';
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
            footer_content += footer_content_print;
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        // $('#document_modal_footer').html(footer_content);
        $('#document_modal .modal-footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function () {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
    }

    function submit_upload_file () {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you want to Upload this Document?',
            function () {
                $('#form_upload_eev_document').submit();
            },
            function () {
                alertify.error('Cancelled!');
            }).set('labels', {ok: 'Yes!', cancel: 'Cancel'});
    }
    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 38));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();
            if (val == 'document_file' || val == 'document_pop') {

                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "png" && ext != "jpg" && ext != "jpeg") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc .png .jpg .jpeg) allowed!</p>');
                    $('#btn_' + val).css("display","none");
                }else{
                    $('#btn_' + val).css("display","block");
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    function preview_document_model(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var document_file_type = $(source).attr('data-file-type');
        var document_sid = $(source).attr('data-document-sid');
        $("#data-document-type").val($(source).attr('data-document-type'));
        $("#eev_sid").val(document_sid);
        //console.log(document_file_type);
        var form_url = "<?= base_url()."hr_documents_management/documents_assignment/employee/".$user_sid; ?>";
        var docs_utl = "<?= current_url() ?>";

        if(document_file_type == "form"){
            $("#form_upload_eev_document_popup").attr('action',form_url);
        }else{
            $("#form_upload_eev_document_popup").attr('action',docs_utl)
        }
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var footer_print_btn = '';
        var print_url = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    print_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':        //             iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;

                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    break;
                default : //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    print_url = 'https://docs.google.com/gview?url=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }
        footer_content = '<a target="_blank" class="btn btn-success" href="'+document_download_url+'">Download</a>';
        footer_print_btn = '<a target="_blank" class="btn btn-success" href="'+print_url+'" >Print</a>';

        $('#upload_eev_form_document .modal-footer').html(footer_content);
        $('#upload_eev_form_document .modal-footer').append(footer_print_btn);
        $('#upload_eev_form_document .modal-title').html(document_title);
        $('#upload_eev_form_document .preview').html(modal_content);
        $('#upload_eev_form_document').modal('show');
    }
    $("#btn_document_file,#btn_document_pop").click(function(){
        if($(this).attr('id') == "btn_document_file")
            $("#form_upload_eev_document").submit();
        if($(this).attr('id') == "btn_document_pop")
            $("#form_upload_eev_document_popup").submit();
        $(this).html('<i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>');
        $(this).attr("disabled", true);


    });
</script>
