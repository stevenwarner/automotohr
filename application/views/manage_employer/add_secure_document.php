<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="dashboard-conetnt-wrp">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('company/documents/secure/listing') ?>">
                                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                        &nbsp;Company Secure Documents
                                    </a>
                                    <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <?= $title; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="well">
                                <div class="help-block">
                                    <h4>Allowed formats are; <strong>jpg, jpeg, png, gif, pdf, doc, docx, rtf, ppt, pptx, xls, xlsx, csv</strong></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <strong>Folder Name</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="document-action-required" style="padding: 14px;">
                                        <b>Please add the folder name before linking the document into it.</b>
                                    </div>
                                    <div class="document-action-required">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label>Name:</label>
                                            <input class="invoice-fields" name="folder" type="text" id="jsFolderName">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <div role="tabpanel" id="js-main-page">

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button class="btn btn-success pull-right js-upload-btn">Upload</button>
                                            </div>
                                        </div>

                                        <div class="cs-upload-box">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h4>Upload documents <small>(You can use the upload button or drop your documents.)</small>
                                                        <p class="pull-right">Selected documents: <strong class="js-selected-file-count">0</strong></p>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="js-dropzone-box">
                                            </div>
                                            <div class="js-dropzone-additional">
                                            </div>
                                        </div>
                                        <br />
                                    </div>
                                    <!--  -->
                                    <div id="js-status-page" style="display: none;">
                                        <div class="table-responsive">
                                            <button class="btn btn-default pull-right js-back-btn"><i class="fa fa-arrow-left"></i>&nbsp; back</button>
                                            <br />
                                            <br />
                                            <br />
                                            <table class="table table-hover table-striped table-bordered">
                                                <thead>
                                                    <tr class="bg-success">
                                                        <th>Document</th>
                                                        <th>Upload Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div id="loader" class="hr-box" style="display: none;">
                                <div class="hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="loader">
                                                <h3 class="text-center">
                                                    <strong>
                                                        <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i> Please wait while the resumes are processed.....
                                                    </strong>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div id="response_box" style="display:none;" class="hr-box">
                                <div class="hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="help-block">
                                                <h3 class="text-center">
                                                    <strong id="response_message"></strong>
                                                </h3>
                                                <div class="js-applicant-wrap"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <?php echo form_open(base_url('bulk_resume_download/download'), array('id' => 'js-download-zip-form', 'method' => 'post')); ?>
                                            <?php echo form_close(); ?>
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

<div id="my_loader" class="text-center my_loader js-body-loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text js-body-loader-text" style="display:block; margin-top: 35px;">Please wait while we are generating a preview...
        </div>
    </div>
</div>

<style>
    /* */
    .cs-tab li>a {
        color: #000000;
    }

    .cs-tab li.active>a {
        background-color: #81b431 !important;
        color: #ffffff !important;
    }

    .cs-custom-input {
        margin-bottom: 10px;
    }

    .cs-custom-input input {
        height: 40px;
    }

    .cs-custom-input .input-group-addon {
        background: 0;
        padding: 0;
        border: none;
    }

    .cs-custom-input .input-group-addon>input {
        margin: 0;
        border-radius: 0;
    }

    .cs-error,
    .cs-required {
        font-weight: bolder;
        color: #cc0000;
    }

    .cs-dropzone {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .cs-drag-overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        background-color: rgba(255, 255, 255, .7);
        z-index: 10;
        display: none;
    }

    .cs-drag-overlay p {
        line-height: 40px;
        font-size: 18px;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #aaaaaa !important;
        padding: 3px 5px !important;
    }
</style>

<script>
    $(function UploadAndAssignBulkDocuments() {
        var megaOBJ = {
                type: 'employee',
                applicantId: 0,
                id: 0
            },
            fileArray = [],
            allowedFormats = '.jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.rtf,.ppt,.pptx,.xls,.xlsx,.csv',
            errorText = 'Invalid format! allowed formats are "' + allowedFormats + '".',
            baseURI = "<?= base_url("assign-bulk-documents"); ?>/",
            targets = {
                uploadButton: $('#js-upload-btn'),
                dropzone: $('.js-dropzone-0'),
                dropzoneBox: $('.js-dropzone-box'),
                dropzoneAdditional: $('.js-dropzone-additional'),
                loaderText: $('.js-body-loader-text'),
                mainPage: $('#js-main-page'),
                statusPage: $('#js-status-page'),
                backBTN: $('.js-back-btn'),
                count: $('.js-selected-file-count')
            },
            xhr = {
                applicantXHR: null
            },
            loaders = {
                applicant: $('.js-applicant-loader'),
                body: $('.js-body-loader')
            },
            fileIndex = 0,
            fileLength = 0,
            currentFile = 1;

        window.fileArray = fileArray;

        // File select start
        // Trigger upload file input
        $(document).on('click', '.js-select-btn', function() {
            $(this).parent().find('.js-file').trigger('click');
        });
        // Trigger when user select file
        $(document).on('change', '.js-file', function(e) {
            validateFile(
                e.target.files[0],
                $(this)
            );
            //
            if (e.target.files.length > 1) {
                //
                $.each(e.target.files, function(i, v) {
                    if (i !== 0) {
                        // fileArray.push(v);
                        var tmp = generateDropzoneRow(true, v.name, true);
                        targets.dropzoneBox.append(tmp.rows);
                        validateFile(
                            v,
                            $('.js-dropzone-' + tmp.id + ''),
                            true
                        );
                    }
                });
            }
        });
        // Trigger when user blur on input file
        $(document).on('dragover', '.js-dropzone', function(e) {
            e.stopPropagation();
            e.preventDefault();
            $(this).find('.js-drag-overlay').show(0);
        });
        // Trigger when user is dragging the file file
        $(document).on('dragleave', '.js-drag-overlay', function(e) {
            e.stopPropagation();
            e.preventDefault();
            $(this).hide(0);
        });
        // Trigger when user drop file
        $(document).on('drop', '.js-dropzone', function(e) {
            e.stopPropagation();
            e.preventDefault();
            $(this).parent().find('.js-drag-overlay').hide(0);
            validateFile(
                e.originalEvent.dataTransfer.files[0],
                $(this).parent(),
                true
            );
            if (e.originalEvent.dataTransfer.files.length > 1) {
                //
                $.each(e.originalEvent.dataTransfer.files, function(i, v) {
                    if (i !== 0) {
                        // fileArray.push(v);
                        var tmp = generateDropzoneRow(true, v.name, true);
                        targets.dropzoneAdditional.prepend(tmp.rows);
                        validateFile(
                            v,
                            $('.js-dropzone-' + tmp.id + ''),
                            true
                        );
                    }
                });
            }

        });
        // Trigger when user adds a new file row
        $(document).on('click', '.js-dropzone-add-btn', function(e) {
            e.preventDefault();
            var tmp = generateDropzoneRow(true, '', true)
            targets.dropzoneAdditional.prepend(tmp.rows);
        });
        // Trigger when user delete file row
        $(document).on('click', '.js-dropzone-delete-btn', function(e) {
            e.preventDefault();
            var _this = $(this);

            if ($(this).closest('.cs-dropzone').find('.js-text').val() == '') {
                $(this).closest('.cs-dropzone').remove();
                return;
            }
            //
            alertify.confirm('Do you want to delete this document?', function() {
                var removeItem = _this.closest('.cs-dropzone').parent().data('id');
                findAndDeleteFileByCode(removeItem);
                _this.closest('.cs-dropzone').remove();
                updateCount();
            })
        });
        // Validated the selected file
        function validateFile(file, e, clicked) {

            var tmp = file.type.split('/'),
                parent = clicked === undefined ? e.parentsUntil('div.row') : e;
            //
            var file_code = clicked === undefined ? parent.parent().data('id') : parent.data('id');
            file.file_code = file_code;
            //
            parent.find('span.js-error').text('');
            findAndDeleteFileByCode(file_code);
            //
            file.format = tmp[tmp.length - 1].trim().toLowerCase();
            //
            file.format = get_document_extension(file.type);
            //
            parent.find('.js-text').val(file.name);
            //
            if (file.format === '') {
                parent.find('span.js-error').text(errorText);
                return;
            } else if (allowedFormats.indexOf(file.format) === -1) {
                parent.find('span.js-error').text(errorText);
                return;
            }
            //
            fileArray.push(file);
            updateCount();
        }
        // File select end

        // Upload and assign
        $('.js-upload-btn').click(startUploadProcess);

        // Back Button
        targets.backBTN.click(resetView);

        // Generate file row
        targets.dropzoneBox.html(generateDropzoneRow());

        loader('hide');

        //
        String.prototype.ucwords = function() {
            str = this.toLowerCase();
            return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function(s) {
                return s.toUpperCase();
            });
        };

        // loader
        function loader(show_it) {
            show_it = show_it === undefined || show_it == true || show_it === 'show' ? 'show' : show_it;
            if (show_it === 'show') loaders.body.fadeIn(150);
            else loaders.body.fadeOut(300);
        }

        // generates dropzone rows
        function generateDropzoneRow(generateId, fileName, returnId) {

            fileName = fileName === undefined ? '' : fileName;
            returnId = returnId === undefined ? false : true;
            var rows = '',
                rowId = generateId === undefined ? 0 : Math.round((Math.random() * 10000) + 1);
            rows += '<!-- DropZone ' + (rowId) + ' -->';
            rows += '<div class="row js-dropzone-' + (rowId) + ' row_id" data-id="' + (rowId) + '">';
            rows += '    <div class="cs-dropzone js-dropzone">';
            rows += '        <div class="col-sm-11 col-xs-12 cs-custom-input">';
            rows += '            <div class="input-group">';
            rows += '                <input type="text" class="form-control js-text" value="' + (fileName) + '" readonly/>';
            rows += '                <div class="input-group-addon">';
            rows += '                    <input type="button" class="btn btn-success js-select-btn" value="Upload Document" />';
            rows += '                    <input type="file" class="js-file" name="txt_file_0" style="display: none;" multiple="true"/>';
            rows += '                </div>';
            rows += '            </div>';
            rows += '            <span class="cs-error js-error"></span>';
            rows += '        </div>';
            rows += '        <div class="col-sm-1 col-xs-12">';
            if (rowId === 0)
                rows += '            <button class="btn btn-success js-dropzone-add-btn"><i class="fa fa-plus"></i></button>';
            else
                rows += '            <button class="btn btn-danger js-dropzone-delete-btn"><i class="fa fa-trash"></i></button>';
            rows += '        </div>';
            rows += '        <!-- drag overlay -->';
            rows += '        <div class="cs-drag-overlay js-drag-overlay"><p class="text-center">Drop file here</p></div>';
            rows += '    </div>';
            rows += '</div>';
            //
            if (returnId) return {
                rows: rows,
                id: rowId
            };
            return rows;
        }


        // start uploading files
        function startUploadProcess(e) {
            e.preventDefault();

            $('.js-error').text('');
            //
            var is_error = false;
            // Validate all selected documents
            $.each($('.js-text'), function(i, v) {
                if ($(this).val() == '') return true;
                var ext = $(this).val().split('.');
                var file_ext = ext[ext.length - 1].trim().toLowerCase();
                // Throw error
                if (allowedFormats.indexOf(file_ext) === -1) {
                    $(this).parentsUntil('div.row').find('.js-error').text(errorText);
                    is_error = true;
                }
            });

            if (is_error) {
                alertify.alert('ERROR!', 'Selected document format is invalid.');
                return;
            }

            if (fileArray.length === 0) {
                alertify.alert('ERROR!', 'Please upload at least one document.');
                return;
            }

            if (is_error) return;

            // Start the upload process
            loader('show');
            targets.loaderText.html('Please, be patient as we Upload your documents.<br /> It may take a few minutes. <br />File <span>1</span> of ' + (fileArray.length) + '');

            uploadAndAssignFiles();
        }

        // Send ajax request
        function uploadAndAssignFiles() {
            var folderName = $("#jsFolderName").val();
            //
            fileLength = fileArray.length;
            var formpost = new FormData();
            formpost.append('file', fileArray[fileIndex]);
            formpost.append('folder_name', folderName);
            $.each(megaOBJ, function(i, v) {
                formpost.append(i, v);
            });

            $.ajax({
                    url: baseURI + 'upload_secure_document',
                    type: 'POST',
                    contentType: false,
                    enctype: 'multipart/form-data',
                    data: formpost,
                    processData: false
                })
                .done(function(resp) {
                    if (resp.Status === false) {
                        fileArray[fileIndex]['status'] = 0;
                        return;
                    } else fileArray[fileIndex]['status'] = 1;
                    //
                    if (currentFile < fileLength) {
                        currentFile++;
                        fileIndex++;
                        targets.loaderText.find('span').text(currentFile);
                        setTimeout(function() {
                            uploadAndAssignFiles();
                        }, 700);
                    } else {

                        showUploadStatus();
                        fileIndex = 0;
                        currentFile = 0;
                    }
                })
                .always(function(xhr) {
                    if (xhr.status == 403) {
                        setTimeout(function() {
                            uploadAndAssignFiles();
                        }, 700);
                    }
                })
        }

        // change view
        function showUploadStatus() {
            targets.mainPage.fadeOut(0);
            targets.statusPage.fadeIn(100);
            var rows = '';
            $.each(fileArray, function(i, v) {
                rows += '<tr>';
                rows += '  <td>' + (v.name) + '</td>';
                rows += '  <td>' + (v.status === 0 ? '<p class="text-danger">Failed</p>' : '<p class="text-success">Uploaded</p>') + '</td>';
                rows += '</tr>';
            });
            targets.statusPage.find('tbody').html(rows);
            loader('hide');

            setTimeout(function() {
                window.location = "<?php echo base_url('company/documents/secure/listing'); ?>"
            }, 3000);

        }

        // reset the view
        function resetView() {
            fileArray = [];
            fileLength = 0;
            currentFile = 1;
            $('.js-text').val('');
            targets.dropzoneBox.html(generateDropzoneRow());
            targets.statusPage.fadeOut(0);
            targets.mainPage.fadeIn(100);
            $('.js-dropzone-additional').html('');
            targets.count.text(0);
        }

        // Update count
        function updateCount() {
                targets.count.text(fileArray.length);
        }

        //
        function findAndDeleteFileByCode(file_code) {
            if (fileArray.length === 0) return;
            fileArray.forEach(function(v, i) {
                if (typeof(v.file_code) !== 'undefined' && v.file_code == file_code) fileArray.splice(i, 1);
            });
        }

    })
</script>