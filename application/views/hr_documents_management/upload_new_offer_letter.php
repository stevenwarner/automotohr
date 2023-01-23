<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management'); ?>"><i class="fa fa-chevron-left"></i>Document Management</a>
                                    <?php echo !isset($document_info) ? 'Add HR Document' : 'Edit HR Document'; ?>
                                </span>
                            </div>
                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
                                <div class="upload-new-doc-heading">
                                    <i class="fa fa-file-text-o"></i>
                                    <?php echo $title; ?>
                                </div>
                                <p class="upload-file-type">Upload a .pdf, .pptx, .ppt, .doc or .docx to distribute to your Employees or Onboarding Candidates.</p>
                                <form id="form_upload_document" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                    <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                    
                                    <?php if (isset($document_info['sid'])) { ?>
                                            <input type="hidden" id="offer_letter_sid" name="offer_letter_sid" value="<?php echo $document_info['sid']; ?>" />
                                            <input type="hidden" id="perform_action" name="perform_action" value="update_offer_letter" />
                                    <?php } else { ?>
                                            <input type="hidden" id="perform_action" name="perform_action" value="upload_offer_letter" />
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <label>Offer Letter / Pay Plan Name<span class="staric">*</span></label>
                                            <input type="text" name="letter_name"  value="<?php
                                                if (isset($document_info['letter_name'])) {
                                                    echo set_value('letter_name', $document_info['letter_name']);
                                                } else {
                                                    echo set_value('letter_name');
                                                } ?>" class="invoice-fields">
                                            <?php echo form_error('letter_name'); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <label>Instructions / Guidance </label>
                                            <div style="margin-bottom:5px;"><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                            <textarea class="invoice-fields autoheight ckeditor" name="letter_body" onkeyup="check_length()" id="letter_body" cols="54" rows="6"><?php
                                            if (isset($document_info['letter_body'])) {
                                                $desc = strip_tags(html_entity_decode($document_info['letter_body']));
                                                    echo set_value('letter_body', $desc);
                                                } else {
                                                    echo set_value('letter_body');
                                                } ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <label>Browse Document<?php echo !isset($document_info) ? '<span class="staric">*</span>' : '';?></label>
                                            <div class="upload-file invoice-fields">
                                                <?php if (isset($document_info['uploaded_document_original_name']) && $document_info['uploaded_document_original_name'] != "") { ?>
                                                    <input type="hidden" id="old_file_name" name="old_file_name" value="<?php echo $document_info['uploaded_document_original_name']; ?>" >
                                                    <input type="hidden" name="old_file_type" value="<?php echo getFileExtension($document_info['uploaded_document_original_name']); ?>" >

                                                    <div id="remove_image" class="profile-picture">
                                                        <a  href="javascript:;" data-toggle="modal" data-target="#document_modal" class = "action-btn">
                                                            <i class = "fa fa-lightbulb-o fa-2x"></i>
                                                            <span class = "btn-tooltip">View Current Document</span>
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                                <input type="file" name="document" id="document" <?php echo !isset($document_info) ? 'required' : '';?> onchange="check_file('document')">
                                                <p id="name_document"></p>
                                                <a href="javascript:;" style="height:38px; line-height: 38px">Choose File</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row margin-top">
                                            <div class="col-xs-12">
                                                <label>Acknowledgment Required</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="acknowledgment_required">
                                                        <option <?php if (isset($document_info['acknowledgment_required']) && $document_info['acknowledgment_required'] == '0') echo 'selected';?> value="0"> No </option>
                                                        <option <?php if (isset($document_info['acknowledgment_required']) && $document_info['acknowledgment_required'] == '1') echo 'selected';?> value="1"> Yes </option>
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    Enable the Acknowledgment Requirement, if you need a confirmation that a Document has been received by the Employee or Onboarding Candidate.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Download Required</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="download_required">
                                                        <option <?php if (isset($document_info['download_required']) && $document_info['download_required'] == '0') echo 'selected';?> value="0"> No </option>
                                                        <option <?php if (isset($document_info['download_required']) && $document_info['download_required'] == '1') echo 'selected';?> value="1"> Yes </option>
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    Enable the Download Required, if you need the Employee or Onboarding Candidate to download this form.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Signature Required</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="signature_required">
                                                        <option <?php if (isset($document_info['signature_required']) && $document_info['signature_required'] == '0') echo 'selected';?> value="0"> No </option>
                                                        <option <?php if (isset($document_info['signature_required']) && $document_info['signature_required'] == '1')echo 'selected'; ?> value="1"> Yes </option>
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    Enable the Signature Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.
                                                </div>
                                            </div>
                                        </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <label>Sort Order</label>
                                            <input type="number" name="sort_order" class="invoice-fields" value="<?php if (isset($document_info['sort_order'])) echo $document_info['sort_order']; ?>">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <button type="submit" onclick="validate_form();" class="btn btn-success"><?php echo isset($document_info['sid']) ? 'Update' : 'Upload'; ?></button>
                                            <a href="<?php echo base_url('hr_documents_management'); ?>" class="btn black-btn">Cancel</a>
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
<?php if (isset($document_info)) { ?>
    <div id="document_modal" class="modal fade file-uploaded-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $document_info['uploaded_document_original_name']; ?> </h4>
                </div>
                <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL . urlencode($document_info['uploaded_document_s3_name']); ?>&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
<?php } ?>
<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            <?php echo VIDEO_LOADER_MESSAGE; ?>
        </div>
    </div>
</div>

<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    $(document).ready(function () {
        var pre_selected = '<?php echo !empty($document_info['video_url']) ? $document_info['video_source'] : ''; ?>';

        if(pre_selected == 'youtube' || pre_selected == 'vimeo'){
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if(pre_selected == 'upload'){
            $('#yt_vm_video_container').hide();
            $('#up_video_container').show();
        } else {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').hide();
        }
        $('.categories').select2({
            closeOnSelect : false,
            allowHtml: true,
            allowClear: true,
            tags: true
        });
    });

    function check_file(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 38));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'document') {
                if (ext != "pdf" && ext != "docx" && ext != "pptx" && ext != "ppt" && ext != "doc" && ext != "xls" && ext != "xlsx" && ext != "PDF" && ext != "DOCX" && ext != "DOC" && ext != "XLS" && ext != "XLSX" && ext != "CSV" && ext != "csv") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc .pptx .ppt) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    function validate_form() {
        $("#form_upload_document").validate({
            ignore: [],
            rules: {
                letter_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\-._ ]+$/
                }
            },
            messages: {
                letter_name: {
                    required: 'Document name is required',
                    pattern: 'Letters, numbers,underscore and dashes only please'
                },
                document: {
                    required: 'Document file is required',
                }
            },
            submitHandler: function (form) {
                var flag = 1;
                var video_source = $('input[name="video_source"]:checked').val();

                if (video_source != 'not_required') {
                    if(video_source == 'youtube') {
                        if($('#yt_vm_video_url').val() != '') {
                            var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;

                            if (!$('#yt_vm_video_url').val().match(p)) {
                                alertify.error('Not a Valid Youtube URL');
                                flag = 0;
                                return false;
                            }
                        } else {
                            var url_check = '<?php echo $this->uri->segment(2); ?>';

                            if (url_check == 'edit_hr_document') {
                                var old_doc_video_source = $('#old_doc_video_source').val();
                                var old_doc_video_url = $('#old_doc_video_url').val();

                                if(old_doc_video_source == 'youtube' && old_doc_video_url != ''){
                                    flag = 1;
                                } else {
                                    flag = 0;
                                    alertify.error('Please provide a Valid Youtube URL');
                                }
                            } else {
                                flag = 0;
                                alertify.error('Please provide a Valid Youtube URL');
                            }
                        }

                    }

                    if(video_source == 'vimeo'){
                        if($('#yt_vm_video_url').val() != '') {
                            var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                            $.ajax({
                                type: "POST",
                                url: myurl,
                                data: {url: $('#yt_vm_video_url').val()},
                                async : false,
                                success: function (data) {
                                    if (data == false) {
                                        alertify.error('Not a Valid Vimeo URL');
                                        flag = 0;
                                        return false;
                                    }
                                },
                                error: function (data) {
                                }
                            });
                        } else {
                            var url_check = '<?php echo $this->uri->segment(2); ?>';

                            if (url_check == 'edit_hr_document') {
                                var old_doc_video_source = $('#old_doc_video_source').val();
                                var old_doc_video_url = $('#old_doc_video_url').val();

                                if(old_doc_video_source == 'vimeo' && old_doc_video_url != ''){
                                    flag = 1;
                                } else {
                                    flag = 0;
                                    alertify.error('Please provide a Valid Vimeo URL');
                                }
                            } else {
                                flag = 0;
                                alertify.error('Please provide a Valid Vimeo URL');
                            }
                        }
                    }

                    if(video_source == 'upload') {
                        var old_uploaded_video = $('#pre_upload_video_url').val();
                        if(old_uploaded_video != ''){
                            flag = 1;
                        } else {
                            var file = video_check('video_upload');
                            if (file == false){
                                flag = 0;
                                return false;
                            } else {
                                flag = 1;
                            }
                        }
                    }
                }

                if (flag == 1) {
                    $('#my_loader').show();
                    form.submit();
                }
            }
        });
    }

    function check_length() {
        var text_allowed = 500;
        var user_text = $('#letter_body').val();
        var newLines = user_text.match(/(\r\n|\n|\r)/g);
        var addition = 0;

        if (newLines != null) {
            addition = newLines.length;
        }

        var text_length = user_text.length + addition;
        var text_left = text_allowed - text_length;
        $('#remaining_text').html(text_left + ' characters left!');
    }

    $('.video_source').on('click', function(){
        var selected = $(this).val();

        if(selected == 'youtube' || selected == 'vimeo'){
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if(selected == 'upload'){
            $('#yt_vm_video_container').hide();
            $('#up_video_container').show();
        } else {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').hide();
        }
    });

    function video_check(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'video_upload') {
                if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid video format.");
                    $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                    return false;
                } else {
                    var file_size = Number(($("#" + val)[0].files[0].size/1024/1024).toFixed(2));
                    var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');

                    if (video_size_limit < file_size) {
                        $("#" + val).val(null);
                        alertify.error('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                        $('#name_' + val).html('');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        return true;
                    }
                }
            }
        } else {
            var url_check = '<?php echo $this->uri->segment(2); ?>';

            if (url_check == 'edit_hr_document') {
                var old_doc_video_source = $('#old_doc_video_source').val();
                var old_doc_video_url = $('#old_doc_video_url').val();

                if(old_doc_video_source == 'upload' && old_doc_video_url == ''){
                    $('#name_' + val).html('No video selected');
                    alertify.error("No video selected");
                    $('#name_' + val).html('<p class="red">Please select video</p>');
                    return false;
                } else {
                    $('#name_' + val).html('No video selected');
                    alertify.error("No video selected");
                    $('#name_' + val).html('<p class="red">Please select video</p>');
                    return false;
                }
            } else {
                $('#name_' + val).html('No video selected');
                alertify.error("No video selected");
                $('#name_' + val).html('<p class="red">Please select video</p>');
                return false;
            }
        }
    }

    $("#yt_vm_video_url").change(function(){
        var video_source = $('input[name="video_source"]:checked').val();

        if(video_source == 'youtube') {
            if($('#yt_vm_video_url').val() != '') {
                var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;

                if (!$('#yt_vm_video_url').val().match(p)) {
                    alertify.error('Not a Valid Youtube URL');
                    return false;
                }
            }

        }

        if(video_source == 'vimeo'){
            if($('#yt_vm_video_url').val() != '') {
                var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {url: $('#yt_vm_video_url').val()},
                    async : false,
                    success: function (data) {
                        if (data == false) {
                            alertify.error('Not a Valid Vimeo URL');
                            return false;
                        }
                    },
                    error: function (data) {
                    }
                });
            }
        }
    });
</script>
<style>
.select2-container {
  min-width: 400px;
}

.select2-results__option {
  padding-right: 20px;
  vertical-align: middle;
}
.select2-results__option:before {
  content: "";
  display: inline-block;
  position: relative;
  height: 20px;
  width: 20px;
  border: 2px solid #e9e9e9;
  border-radius: 4px;
  background-color: #fff;
  margin-right: 20px;
  vertical-align: middle;
}
.select2-results__option[aria-selected=true]:before {
  font-family:fontAwesome;
  content: "\f00c";
  color: #fff;
  background-color: #81b431;
  border: 0;
  display: inline-block;
  padding-left: 3px;
}
.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #fff;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #eaeaeb;
    color: #272727;
}
.select2-container--default .select2-selection--multiple {
    margin-bottom: 10px;
}
.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
    border-radius: 4px;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #81b431;
    border-width: 2px;
}
.select2-container--default .select2-selection--multiple {
    border-width: 2px;
}
.select2-container--open .select2-dropdown--below {

    border-radius: 6px;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);

}
.select2-selection .select2-selection--multiple:after {
    content: 'hhghgh';
}
/* select with icons badges single*/
.select-icon .select2-selection__placeholder .badge {
    display: none;
}
.select-icon .placeholder {
    display: none;
}
.select-icon .select2-results__option:before,
.select-icon .select2-results__option[aria-selected=true]:before {
    display: none !important;
    /* content: "" !important; */
}
.select-icon  .select2-search--dropdown {
    display: none;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice{
    height: 25px !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__rendered{
    height: 30px;
}
</style>
