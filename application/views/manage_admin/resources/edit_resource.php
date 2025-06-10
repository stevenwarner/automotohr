<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <form enctype="multipart/form-data" method="post" id="resource_form">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="heading-title page-title">
                                            <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit Resource</h1>
                                            <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/resources/') ?>"><i class="fa fa-long-arrow-left"></i> Back to resources</a>
                                        </div>

                                        <div class="hr-search-main " style="display: block;">
                                            <div class="row">
                                                <div class="col-xs-12">

                                                    <div class="hr-box" style="margin: 15px 0 0;">

                                                        <div class="hr-box-header bg-header-green">
                                                            <h1 class="hr-registered pull-left">Meta Details</h1>
                                                        </div>

                                                        <div class="col-xs-12 form-group">
                                                            <br> <label>Meta Title:</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="meta_title" id="meta_title" value="<?php echo $page_data['meta_title']; ?>" />
                                                        </div>

                                                        <div class="col-xs-12 form-group">
                                                            <label>Meta Description:</label><b class="text-danger"> *</b>
                                                            <textarea class="invoice-fields" name="meta_description" id="meta_description" rows="4" cols="60"><?php echo $page_data['meta_description']; ?></textarea>
                                                        </div>

                                                        <div class="col-xs-12 form-group">
                                                            <label>Meta Keywords:</label><b class="text-danger"> *</b>
                                                            <textarea class="invoice-fields" name="meta_key_word" id="meta_key_word" rows="4" cols="60"><?php echo $page_data['meta_key_word']; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="hr-box" style="margin: 15px 0 0;">
                                                        <div class="hr-box-header bg-header-green">
                                                            <h1 class="hr-registered pull-left">Resource Details</h1>
                                                        </div>
                                                        <div class="col-xs-12 form-group">
                                                            <br> <label>Title:</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="title" id="title" value="<?php echo $page_data['title']; ?>" />
                                                        </div>

                                                        <div class="col-xs-12 form-group">
                                                            <label>Slug:</label><b class="text-danger"> *</b>

                                                            <?php
                                                            $newslugarray = explode('-', $page_data['slug']);
                                                            unset($newslugarray[(sizeof($newslugarray) - 1)]);
                                                            $newSlug = implode('-', $newslugarray);
                                                            ?>

                                                            <input type="text" class="invoice-fields" name="slug" id="slug" value="<?php echo $newSlug; ?>" />
                                                        </div>

                                                        <div class="col-xs-12 form-group">
                                                            <label>Description:</label><b class="text-danger"> *</b>
                                                            <textarea class="ckeditor" name="description" id="description" rows="4" cols="60"><?php echo $page_data['description']; ?></textarea>
                                                        </div>

                                                        <div class="col-xs-12 form-group">
                                                            <label>Status:</label><b class="text-danger"> *</b>
                                                            <div class="hr-select-dropdown">
                                                                <select name="status" id="status" class="invoice-fields">
                                                                    <option value="0" <?php echo $page_data['status'] == 0 ? "Selected" : '' ?>>Unpublish</option>
                                                                    <option value="1" <?php echo $page_data['status'] == 1 ? "Selected" : '' ?>>Publish</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12 form-group">
                                                            <label for="feature_image">Feature Image:</label>
                                                            <input type="file" style="display: none;" id="jsFeatureImage" name="feature_image" />
                                                            <input type="hidden" id="jsFeatureImageInput" name="feature_imagefile" />

                                                        </div>

                                                        <div class="col-xs-12 form-group">
                                                            <label>Resource Type:</label>
                                                            <?php $resourceTypeArray = explode(',', $page_data['resource_type']) ?>
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="resourcetype[]" value="Videos" <?php echo in_array("Videos", $resourceTypeArray) ? 'checked' : '' ?>> Videos
                                                                <div class="control__indicator"></div>
                                                            </label>

                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="resourcetype[]" value="Audio" <?php echo in_array("Audio", $resourceTypeArray) ? 'checked' : '' ?>> Audio
                                                                <div class="control__indicator"></div>
                                                            </label>

                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="resourcetype[]" value="Webinars" <?php echo in_array("Webinars", $resourceTypeArray) ? 'checked' : '' ?>> Webinars
                                                                <div class="control__indicator"></div>
                                                            </label>

                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="resourcetype[]" value="Articles" <?php echo in_array("Articles", $resourceTypeArray) ? 'checked' : '' ?>> Articles
                                                                <div class="control__indicator"></div>
                                                            </label>

                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="resourcetype[]" value="eBooks" <?php echo in_array("eBooks", $resourceTypeArray) ? 'checked' : '' ?>> eBooks
                                                                <div class="control__indicator"></div>
                                                            </label>

                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="resourcetype[]" value="Other" <?php echo in_array("Other", $resourceTypeArray) ? 'checked' : '' ?>> Other
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>

                                                        <div class="col-xs-12 form-group">
                                                            <label>Resource: </label>
                                                            <input type="file" style="display: none;" id="jsFileUpload" name="resources" />
                                                            <input type="hidden" id="jsFileUploadInput" name="resourcesfile" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>

                            <hr />
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <a class="btn btn-success" href='javascript:' onclick="saveFormInto()">Save</a>
                                    <a class="btn btn-default" href='<?php echo base_url('manage_admin/resources') ?>'>Cancel</a>
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


<div id="my_loader" class="text-center my_loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait ...
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/mFileUploader/index.css'); ?>" />
<script src="<?= base_url('assets/mFileUploader/index.js'); ?>"></script>

<script type="text/javascript">
    var totalFiles = 2;
    var currentFile = 0;
    var fileIds = {
        "0": "jsFeatureImage",
        "1": "jsFileUpload"
    }

    loaderTarget = $('#my_loader');
    loader(false);
    //
    function saveFormInto() {
        //
        let meta_title = $("#meta_title").val().trim();
        let meta_description = $("#meta_description").val().trim();
        let meta_key_word = $("#meta_key_word").val() || [];
        let title = $("#title").val();
        let slug = $("#slug").val();
        let status = $("#status").val();
        let description = CKEDITOR.instances['description'].getData().trim();
        let upload_file_1 = $('#jsFeatureImage').mFileUploader('get');
        let upload_file_2 = $('#jsFileUpload').mFileUploader('get');
        let old_upload_file_1 = '<?= $page_data['feature_image']; ?>';
        let old_upload_file_2 = '<?= $page_data['resources']; ?>';
        //
        const errorArray = [];
        // validate
        if (!meta_title) {
            errorArray.push("Meta title is required.");
        }
        if (!meta_description) {
            errorArray.push("Meta description is required.");
        }
        if (!meta_key_word) {
            errorArray.push("Meta key word is required.");
        }
        if (!title) {
            errorArray.push("Title is required.");
        }
        if (!slug) {
            errorArray.push("Slug is required.");
        }
        if (!status) {
            errorArray.push("Status is required.");
        }
        if (!description) {
            errorArray.push("Description is required.");
        }
        //
        if ($.isEmptyObject(upload_file_1)) {
            if (!old_upload_file_1) {
                errorArray.push("Feature Image is required.");
            } else {
                $("#jsFeatureImageInput").val(old_upload_file_1);
            }

        }

        $("#jsFileUploadInput").val(old_upload_file_2);

        //
        if (errorArray.length) {
            // make the user notify of errors
            return alertify.alert(
                "ERROR!",
                getErrorsStringFromArray(errorArray)
            );
        }
        //
        loader(true);

        ajaxUpload();

    }
    //
    function getErrorsStringFromArray(errorArray, errorMessage) {
        return (
            "<strong><p>" +
            (errorMessage ?
                errorMessage :
                "Please, resolve the following errors") +
            "</p></strong><br >" +
            errorArray.join("<br />")
        );
    }
    //
    $('#jsFileUpload').mFileUploader({
        fileLimit: -1,
        allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'pdf','doc','docx', 'mp4', 'm4a', 'm4v', 'f4v', 'f4a', 'm4b', 'm4r', 'f4b', 'mov', 'mp3', 'wav', 'aac', 'flac'],
        text: 'Click / Drag to upload',
        <?php if (isset($page_data['resources']) && $page_data['resources'] != "") { ?>
            placeholderImage: "<?= $page_data['resources']; ?>"
        <?php } ?>
    });

    //
    $('#jsFeatureImage').mFileUploader({
        fileLimit: -1,
        allowedTypes: ['jpg', 'jpeg', 'png', 'gif'],
        text: 'Click / Drag to upload',
        <?php if (isset($page_data['feature_image']) && $page_data['feature_image'] != "") { ?>
            placeholderImage: "<?= $page_data['feature_image']; ?>"
        <?php } ?>
    });

    //
    function ajaxUpload() {
        //
        let id = fileIds[currentFile];
        //
        var formData = new FormData();
        var upload_file = $('#' + id).mFileUploader('get');
        //
        if (!$.isEmptyObject(upload_file) && upload_file.hasError == false) {
            formData.append('document', upload_file);
        }

        formData.append('document_title', 'resources');

        $.ajax({
            url: '<?= base_url('manage_admin/uploadresource'); ?>',
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false
        }).success(function(resp) {

            let respData = JSON.parse(resp);

            $("#" + id + "Input").val(respData.document_url);
            //
            currentFile++;
            //
            if (totalFiles > currentFile) {
                setTimeout(function() {
                    ajaxUpload();
                }, 1000)

            } else {
                console.log("now save form")
                $("#resource_form").submit();
                loader(false);

            }

        });
    }

    //
    $("#title").keyup(function() {
        //
        $("#slug").val(
            $("#title").val()
            .trim()
            .replace(/\s+/g, ' ')
            .replace(/[^a-z0-9\s]/ig, '')
            .replace(/\s/g, '-')
            .toLowerCase()
        );
    });


    function loader(is_show) {
        if (is_show == true) loaderTarget.fadeIn(500);
        else loaderTarget.fadeOut(500);
    }
</script>