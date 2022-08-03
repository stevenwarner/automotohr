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
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management'); ?>"><i class="fa fa-chevron-left"></i>Document Management</a>
                                    <?php echo !isset($document_info) ? 'Add HR Document' : 'Edit HR Document'; ?>
                                </span>
                            </div>

                            <?php $this->load->view('hr_documents_management/partials/document_name'); ?>
                            <?php $this->load->view('hr_documents_management/partials/browse_document'); ?>
                            <?php $this->load->view('hr_documents_management/partials/include_in_onboarding'); ?>
                            <?php $this->load->view('hr_documents_management/partials/acknowledgment_required'); ?>
                            <?php $this->load->view('hr_documents_management/partials/download_required'); ?>
                            <?php $this->load->view('hr_documents_management/partials/reupload_required'); ?>

                            <div class="row">
                                <div class="col-xs-12">
                                    <label>Sort Order</label>
                                    <input type="number" name="sort_order" class="invoice-fields" value="<?php if (isset($document_info['sort_order'])) echo $document_info['sort_order']; ?>">
                                </div>
                            </div>
                            <br>
                            <?php $this->load->view('hr_documents_management/partials/assign_video'); ?>

                            <?php if (!empty($document_groups)) { ?>
                                <?php $this->load->view('hr_documents_management/partials/group_management'); ?>
                            <?php } ?>
                            <?php if (!empty($active_categories)) { ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>Categories</label><br>
                                        <div class="Category_chosen">
                                            <select data-placeholder="Please Select" multiple="multiple" onchange="" name="categories[]" id="createcategories" class="categories">
                                                <?php if (sizeof($active_categories) > 0) { ?>
                                                    <?php foreach ($active_categories as $category) { ?>
                                                        <option <?= isset($assigned_categories) && in_array($category['sid'], $assigned_categories) ? "selected" : "" ?> value="<?php echo $category['sid']; ?>"><?= $category['name'] ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            <?php } ?>
                            <?php if (isset($document_info['sid'])) { ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label class="control control--checkbox font-normal">
                                            Convert To Pay Plan
                                            <input class="disable_doc_checkbox" name="to_pay_plan" type="checkbox" value="yes" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <br />
                            <?php } ?>
                            <?php $this->load->view('hr_documents_management/partials/visibility'); ?>
                            <?php $this->load->view('hr_documents_management/partials/approvers_section'); ?>
                            <?php $this->load->view('hr_documents_management/partials/automatically_assign'); ?>
                            <br />
                            <?php $this->load->view('hr_documents_management/partials/send_dwmc'); ?>

                            <div class="row">
                                <div class="col-xs-12">
                                    <br />
                                    <?php $this->load->view('hr_documents_management/partials/settings', [
                                        'is_confidential' =>  $document_info['is_confidential']
                                    ]); ?>
                                </div>
                            </div>

                            <?php if (checkIfAppIsEnabled('documentlibrary')) { ?>
                                <?php $this->load->view('hr_documents_management/partials/document_library'); ?>
                            <?php } ?>

                            <div class="row">
                                <div class="col-xs-12">
                                    <button type="submit" onclick="validate_form();" class="btn btn-success"><?php echo isset($document_info['sid']) ? 'Update' : 'Upload'; ?></button>
                                    <a href="<?php echo base_url('hr_documents_management'); ?>" class="btn black-btn">Cancel</a>
                                </div>
                            </div>

                            </form>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                            <div class="tick-list-box" style="margin: 0; width: 100%">
                                <h2><?php echo STORE_NAME; ?> is Secure</h2>
                                <ul>
                                    <li>Automated HR Document distribution</li>
                                    <li>Upload documents for new hires and employees</li>
                                    <li>Unlimited document storage</li>
                                    <li>Tracking of receipt/acknowledgment</li>
                                    <li>Revokes access for terminated employees</li>
                                    <li>Transmissions encrypted by SSL</li>
                                    <li>Information treated confidential by AutomotHR</li>
                                    <li>Receive emails with your signed paperwork</li>
                                    <li>Supports Adobe PDF&reg; and <br />Microsoft Word&reg;</li>
                                </ul>
                            </div>
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

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>

<link rel="stylesheet" href="<?= base_url('assets/mFileUploader/index.css'); ?>" />
<script src="<?= base_url('assets/mFileUploader/index.js'); ?>"></script>

<script>
    $(document).ready(function() {

        $('#jsFileUpload').mFileUploader({
            fileLimit: -1,
            allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv'],
            text: 'Click / Drag to upload',
            <?php if (isset($document_info['uploaded_document_s3_name']) && $document_info['uploaded_document_s3_name'] != "") { ?>
                placeholderImage: "<?= $document_info['uploaded_document_s3_name']; ?>"
            <?php } ?>
        });

        var pre_selected = '<?php echo !empty($document_info['video_url']) ? $document_info['video_source'] : ''; ?>';

        $('input[name="assign-in-days"]').val(0);
        $('input[name="assign-in-months"]').val(0);
        $('.js-type').hide();
        $('input[value="days"]').prop('checked', false);
        $('input[value="months"]').prop('checked', false);
        <?php if (isset($document_info['automatic_assign_in']) && !empty($document_info['automatic_assign_in'])) { ?>
            $('.js-type-<?= $document_info['automatic_assign_type']; ?>').show();
            $('input[value="<?= $document_info['automatic_assign_type']; ?>"]').prop('checked', true);
            $('.js-type-<?= $document_info['automatic_assign_type']; ?>').find('input').val(<?= $document_info['automatic_assign_in']; ?>);
        <?php } else { ?>
            $('input[value="days"]').prop('checked', true);
            $('.js-type-days').show();
        <?php } ?>
        //
        $('input[name="assign_type"]').click(function() {
            $('.js-type').hide(0).val(0);
            $('.js-type-' + ($(this).val()) + '').show(0);
        });

        if (pre_selected == 'youtube' || pre_selected == 'vimeo') {
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if (pre_selected == 'upload') {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').show();
        } else {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').hide();
        }
        $('.categories').select2({
            closeOnSelect: false,
            allowHtml: true,
            allowClear: true,
            // tags: true
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
                document_title: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\-._ ]+$/
                }
            },
            messages: {
                document_title: {
                    required: 'Document name is required',
                    pattern: 'Letters, numbers, underscore and dashes only please'
                },
            document: {
                    required: 'Document file is required',
                }
            },
            submitHandler: function(form) {
                var flag = 1;
                var video_source = $('input[name="video_source"]:checked').val();

                if (video_source != 'not_required') {
                    if (video_source == 'youtube') {
                        if ($('#yt_vm_video_url').val() != '') {
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

                                if (old_doc_video_source == 'youtube' && old_doc_video_url != '') {
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

                    if (video_source == 'vimeo') {
                        if ($('#yt_vm_video_url').val() != '') {
                            var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                            $.ajax({
                                type: "POST",
                                url: myurl,
                                data: {
                                    url: $('#yt_vm_video_url').val()
                                },
                                async: false,
                                success: function(data) {
                                    if (data == false) {
                                        alertify.error('Not a Valid Vimeo URL');
                                        flag = 0;
                                        return false;
                                    }
                                },
                                error: function(data) {}
                            });
                        } else {
                            var url_check = '<?php echo $this->uri->segment(2); ?>';

                            if (url_check == 'edit_hr_document') {
                                var old_doc_video_source = $('#old_doc_video_source').val();
                                var old_doc_video_url = $('#old_doc_video_url').val();

                                if (old_doc_video_source == 'vimeo' && old_doc_video_url != '') {
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

                    if (video_source == 'upload') {
                        var old_uploaded_video = $('#pre_upload_video_url').val();
                        if (old_uploaded_video != '') {
                            flag = 1;
                        } else {
                            var file = video_check('video_upload');
                            if (file == false) {
                                flag = 0;
                                return false;
                            } else {
                                flag = 1;
                            }
                        }
                    }
                }

                if (flag == 1) {
                    //
                    handleForm(form);
                }
            }
        });
    }


    async function handleForm(form) {
        //
        const fileOBJ = $('#jsFileUpload').mFileUploader('get');
        <?php if (isset($document_info['uploaded_document_s3_name']) && $document_info['uploaded_document_s3_name'] != "") { ?>
            const oldFile = "<?= $document_info['uploaded_document_s3_name']; ?>";
        <?php } else { ?>
            const oldFile = "";
        <?php }  ?>
        //
        if (Object.keys(fileOBJ).length == 0 && oldFile == '') {
            alertify.alert('WARNING!', 'File is required.', () => {});
            return false;
        }

        if (fileOBJ.hasOwnProperty('hasError') && fileOBJ.hasError !== false) {
            alertify.alert('WARNING!', 'Uploaded file is invalid.', () => {});
            return false;
        }

        if (Object.keys(fileOBJ).length > 0) {
            // handling uploaded image
            await upload_document_with_ajax_request(
                '#add_specific_doc_url',
                '#add_specific_doc_name',
                '#add_specific_doc_extension',
                $('input[name="document_title"]').val().trim(),
                fileOBJ,
                '#my_loader'
            );
        }

        $('#my_loader').show();
        form.submit();
    }

    /**
     * Uploads file to server and
     * appends to the form
     */
    function upload_document_with_ajax_request(
        docURL,
        docName,
        docExt,
        document_title,
        upload_file,
        loaderREF
    ) {
        //
        $(loaderREF).show();
        return new Promise((resolve, reject) => {
            //
            var form_data = new FormData();
            form_data.append('document', upload_file);
            form_data.append('company_sid', '<?php echo $company_sid; ?>');
            form_data.append('user_sid', '');
            form_data.append('user_type', '');
            form_data.append('document_title', document_title);

            $.ajax({
                url: '<?= base_url('hr_documents_management/upload_file_ajax_handler') ?>',
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(data) {
                    $(loaderREF).hide();
                    var obj = jQuery.parseJSON(data);
                    if (obj.upload_status == 'success') {
                        $(docURL).val(obj.document_url);
                        $(docName).val(obj.original_name);
                        $(docExt).val(obj.extension);
                        resolve('success');

                    } else resolve('failed');
                },
                error: function() {
                    resolve('failed');
                }
            });
        });
    }

    function check_length() {
        var text_allowed = 500;
        var user_text = $('#document_description').val();
        var newLines = user_text.match(/(\r\n|\n|\r)/g);
        var addition = 0;

        if (newLines != null) {
            addition = newLines.length;
        }

        var text_length = user_text.length + addition;
        var text_left = text_allowed - text_length;
        $('#remaining_text').html(text_left + ' characters left!');
    }

    $('.video_source').on('click', function() {
        var selected = $(this).val();

        if (selected == 'youtube' || selected == 'vimeo') {
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if (selected == 'upload') {
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
                    var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
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

                if (old_doc_video_source == 'upload' && old_doc_video_url == '') {
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

    $("#yt_vm_video_url").change(function() {
        var video_source = $('input[name="video_source"]:checked').val();

        if (video_source == 'youtube') {
            if ($('#yt_vm_video_url').val() != '') {
                var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;

                if (!$('#yt_vm_video_url').val().match(p)) {
                    alertify.error('Not a Valid Youtube URL');
                    return false;
                }
            }

        }

        if (video_source == 'vimeo') {
            if ($('#yt_vm_video_url').val() != '') {
                var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {
                        url: $('#yt_vm_video_url').val()
                    },
                    async: false,
                    success: function(data) {
                        if (data == false) {
                            alertify.error('Not a Valid Vimeo URL');
                            return false;
                        }
                    },
                    error: function(data) {}
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
        font-family: fontAwesome;
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
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

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

    .select-icon .select2-search--dropdown {
        display: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        height: 25px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: 30px;
    }
</style>

<?php $this->load->view('hr_documents_management/scripts/approvers'); ?>