<?php ?>
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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/employers/edit_employer')."/".$employee_detail["sid"]; ?>" class="btn black-btn float-right">Back</a>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"> 
                                    <div class="heading-title page-title">
                                        <h1 class="page-title">Company Name : <?php echo $companyName; ?></h1>
                                        <br>
                                        <h1 class="page-title">Employee Name : <?php echo $employeeName; ?></h1>
                                    </div> 
                                </div>      
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="well">
                                        <div class="help-block">
                                            <h3><strong>Assign Bulk Documents: </strong></h3>
                                            <h4>To assign bulk documents, follow the steps below;</h4>
                                            <h4>1- Upload the document(s) by clicking on the file button or by dropping your file.</h4>
                                            <h4>2- After selecting all the files, click on the 'Upload & Assign' button.</h4>
                                            <h4>Allowed formats are; <strong>jpg, jpeg, png, gif, pdf, doc, docx, rtf, ppt, pptx, xls, xlsx, csv</strong></h4>
                                            <h4><strong>Depending on the number of documents you are assigning this could take a bit of time to download, <span class="text-success">Please be patient.</span></strong></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div role="tabpanel" id="js-main-page">

                                                <!-- Upload BTN  -->
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <button class="btn btn-success pull-right js-upload-btn">Upload & Assign</button>
                                                    </div>
                                                </div>
                                                <!-- Upload BOX -->
                                                <div class="cs-upload-box">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <h4>Upload documents <small>(You can use the upload button or drop your documents.)</small> <p class="pull-right">Selected documents: <strong class="js-selected-file-count">0</strong></p></h4>
                                                        </div>
                                                    </div>

                                                    <!-- Drop zone area -->
                                                    <div class="js-dropzone-box">
                                                    </div>
                                                    <div class="js-dropzone-additional">
                                                    </div>
                                                </div>

                                                <br />
                                                <!-- Upload BTN  -->
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <button class="btn btn-success pull-right js-upload-btn">Upload & Assign</button>
                                                    </div>
                                                </div>
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
                                                                <th>Assign Status</th>
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
                                                    <div class="loader" >
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
                                                        <!-- Added on  -->
                                                        <!-- wrapper for applocant table and download button  -->
                                                        <!-- 19-03-2019  -->
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

<!-- include select2.css -->
<link href="<?=base_url('assets/css/select2.css');?>" rel="stylesheet" />

<style>
    /* */
    .cs-tab li > a{ color: #000000; }
    .cs-tab li.active > a{ background-color: #81b431 !important; color: #ffffff !important; }
    .cs-applicant-box i{ position: absolute; top: 50%; right: 30px; font-size: 20px; margin-top: -16px; color: #81b431; }
    .cs-custom-input{ margin-bottom: 10px; }
    .cs-custom-input input{ height: 40px;}
    .cs-custom-input .input-group-addon{ background: 0; padding: 0; border: none; }
    .cs-custom-input .input-group-addon > input{ margin: 0; border-radius: 0; }
    .cs-error, .cs-required{ font-weight: bolder; color: #cc0000; }
    .cs-dropzone{ position: relative; display: inline-block; width: 100%; }
    .cs-drag-overlay{ position: absolute; top: 0; bottom: 0; left: 0; right: 0; width: 100%; background-color:  rgba(255,255,255,.7); z-index: 10; display: none; }
    .cs-drag-overlay p{ line-height: 40px; font-size: 18px; }
    .select2-container--default .select2-selection--single{ border: 1px solid #aaaaaa !important;  padding: 3px 5px !important; }
</style>

<!-- Include common.js  -->
<script src="<?=base_url('assets/js/common.js?v1.0');?>"></script>
<!-- include select2.js -->
<script src="<?=base_url('assets/js/select2.js');?>"></script>

<script>
    $(function UploadAndAssignBulkDocuments(){
        var megaOBJ = {
            type: 'employee',
            employeeId: '<?php echo $employee_detail["sid"]; ?>',
            companyId: '<?php echo $companySid; ?>'
        },
        fileArray = [],
        allowedFormats = '.jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.rtf,.ppt,.pptx,.xls,.xlsx,.csv',
        errorText = 'Invalid format! allowed formats are "'+allowedFormats+'".',
        baseURI = "<?=base_url("manage_admin/employers");?>/",
        targets = {
            employeeSelect: $('#js-employee-select'),
            applicantInput: $('#js-applicant-input'),
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
            applicantXHR : null
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
        $(document).on('click', '.js-select-btn', function(){ $(this).parent().find('.js-file').trigger('click'); });
        // Trigger when user select file
        $(document).on('change', '.js-file', function(e){
            validateFile(
                e.target.files[0],
                $(this)
            );
            //
            if(e.target.files.length > 1){
                //
                $.each(e.target.files, function(i, v) {
                    if(i !== 0){
                        // fileArray.push(v);
                        var tmp = generateDropzoneRow(true, v.name, true);
                        targets.dropzoneBox.append(tmp.rows);
                        validateFile(
                            v,
                            $('.js-dropzone-'+tmp.id+''),
                            true
                        );
                        createSelect2(tmp.id);
                    }
                });
            }
        });
        // Trigger when user blur on input file
        $(document).on('dragover', '.js-dropzone', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $(this).find('.js-drag-overlay').show(0);
        });
        // Trigger when user is dragging the file file
        $(document).on('dragleave', '.js-drag-overlay', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $(this).hide(0);
        });
        // Trigger when user drop file
        $(document).on('drop', '.js-dropzone', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $(this).parent().find('.js-drag-overlay').hide(0);
            validateFile(
                e.originalEvent.dataTransfer.files[0],
                $(this).parent(),
                true
            );
            if(e.originalEvent.dataTransfer.files.length > 1){
                //
                $.each(e.originalEvent.dataTransfer.files, function(i, v) {
                    if(i !== 0){
                        // fileArray.push(v);
                        var tmp = generateDropzoneRow(true, v.name, true);
                        targets.dropzoneAdditional.prepend(tmp.rows);
                        validateFile(
                            v,
                            $('.js-dropzone-'+tmp.id+''),
                            true
                        );
                        createSelect2(tmp.id);
                    }
                });
            }

        });
        // Trigger when user adds a new file row
        $(document).on('click', '.js-dropzone-add-btn', function(e){
            e.preventDefault();
            var tmp = generateDropzoneRow(true,'',true)
            targets.dropzoneAdditional.prepend(tmp.rows);
            createSelect2(tmp.id);
        });
        // Trigger when user delete file row
        $(document).on('click', '.js-dropzone-delete-btn', function(e){
            e.preventDefault();
            var _this = $(this);

            if($(this).closest('.cs-dropzone').find('.js-text').val() == ''){
                $(this).closest('.cs-dropzone').remove();
                return;
            }
            //
            alertify.confirm('Do you want to delete this document?', function(){
                var removeItem = _this.closest('.cs-dropzone').parent().data('id');
                findAndDeleteFileByCode(removeItem);
                _this.closest('.cs-dropzone').remove();
                updateCount();
            })
        });
        // Validated the selected file
        function validateFile(file, e, clicked){

            var tmp = file.type.split('/'),
            parent  = clicked === undefined ? e.parentsUntil('div.row') : e;
            //
            var file_code = clicked === undefined ? parent.parent().data('id') : parent.data('id');
            file.file_code = file_code;
            //
            parent.find('span.js-error').text('');
            findAndDeleteFileByCode(file_code);
            //
            file.format = tmp[tmp.length -1].trim().toLowerCase();
            //
            file.format = get_document_extension(file.type);
            //
            parent.find('.js-text').val( file.name );
            //
            if(file.format === ''){
                parent.find('span.js-error').text(errorText);
                return;
            } else if(allowedFormats.indexOf(file.format) === -1){
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

        // loader
        function loader(show_it){
            show_it = show_it === undefined || show_it == true || show_it === 'show' ? 'show' : show_it;
            if(show_it === 'show') loaders.body.fadeIn(150);
            else loaders.body.fadeOut(300);
        }

        // generates dropzone rows
        function generateDropzoneRow(generateId, fileName, returnId){


            fileName = fileName === undefined ? '' : fileName;
            returnId = returnId === undefined ? false : true;
            var rows = '',
            rowId = generateId === undefined ? 0 : Math.round((Math.random() * 10000) + 1);
            rows += '<!-- DropZone '+( rowId )+' -->';
            rows += '<div class="row js-dropzone-'+( rowId )+' row_id" data-id="'+( rowId )+'">';
            rows += '    <div class="cs-dropzone js-dropzone">';
            rows += '        <div class="col-sm-3 col-xs-12 cs-custom-input" id="category_hide_'+( rowId )+'">';
            rows += '                 <div class="Category_chosen">';
            rows += '                 <select data-placeholder="Please Select Category" id="reset_category_'+( rowId )+'" multiple="multiple" onchange="" class="categories chosen-select">';
                                        <?php   foreach ($active_categories as $category) { ?>
            rows += '                       <option value="<?php echo $category['sid']; ?>" ><?= $category['name'] ?></option>';
                                        <?php       } ?>
            rows += '                    </select>'
            rows += '                </div>';
            rows += '        </div>';
            rows += '        <div class="col-sm-2 col-xs-12 cs-custom-input">';
            rows += '               <input name="signed_date" id="signed_date_'+( rowId )+'" type="text" readonly="true" class="form-control signed_date" placeholder="Signed Completed Date">';
            rows += '        </div>';
            rows += '        <div class="col-sm-4 col-xs-12 cs-custom-input">';
            rows += '            <div class="input-group">';
            rows += '                <input type="text" class="form-control js-text" value="'+( fileName )+'" readonly/>';
            rows += '                <div class="input-group-addon">';
            rows += '                    <input type="button" class="btn btn-success js-select-btn" value="Upload Document" />';
            rows += '                    <input type="file" class="js-file" name="txt_file_0" style="display: none;" multiple="true"/>';
            rows += '                </div>';
            rows += '            </div>';
            rows += '            <span class="cs-error js-error"></span>';
            rows += '        </div>';
            rows += '        <div class="col-sm-2 col-xs-12 cs-custom-input" style="padding: 6px;">';
            rows += '           <label class="control control--checkbox">';
            rows += '               <input name="is_offer_letter" this-id="'+( rowId )+'" id="is_offer_letter_'+( rowId )+'" type="checkbox" value="" class="ej_checkbox">';
            rows += '               <div class="control__indicator"></div>';
            rows += '                   Offer Letter / Pay Plan';
            rows += '            </label>';
            rows += '           <label class="control control--checkbox">';
            rows += '               <input name="visible_to_payroll" this-id="'+( rowId )+'" id="visible_to_payroll'+( rowId )+'" type="checkbox" value="" class="ek_checkbox">';
            rows += '               <div class="control__indicator"></div>';
            rows += '                   Visible To Payroll Plus';
            rows += '            </label>';
            rows += '        </div>';
            rows += '        <div class="col-sm-1 col-xs-12">';
            if( rowId === 0)
            rows += '            <button class="btn btn-success js-dropzone-add-btn"><i class="fa fa-plus"></i></button>';
            else
            rows += '            <button class="btn btn-danger js-dropzone-delete-btn"><i class="fa fa-trash"></i></button>';
            rows += '        </div>';
            rows += '        <!-- drag overlay -->';
            rows += '        <div class="cs-drag-overlay js-drag-overlay"><p class="text-center">Drop file here</p></div>';
            rows += '    </div>';
            rows += '</div>';
            //
            if(returnId) return {rows : rows, id: rowId};
            return rows;
        }

        $(document).on('click', '.ej_checkbox', function() {
            var sid = $(this).attr('this-id');

            if ($(this).is(":checked")) {
                $("#reset_category_"+sid).select2("val", "");
                $("#reset_category_"+sid).prop('disabled', true);
            } else {
                $("#reset_category_"+sid).prop('disabled', false);
            }
        });

        // start uploading files
        function startUploadProcess(e){
            e.preventDefault();
            //
            $('.js-error').text('');
            //
            var is_error = false;
            // Validate all selected documents
            $.each($('.js-text'), function(i, v) {
                if($(this).val() == '') return true;
                var ext = $(this).val().split('.');
                var file_ext = ext[ext.length -1].trim().toLowerCase();
                // Throw error
                if(allowedFormats.indexOf(file_ext) === -1){
                    $(this).parentsUntil('div.row').find('.js-error').text( errorText );
                    is_error = true;
                }
            });

            if(is_error) {
                alertify.alert('ERROR!', 'Selected document format is invalid.');
                return;
            }

            if(fileArray.length === 0){
                alertify.alert('ERROR!', 'Please upload atleast one document.'); return;
            }

            if(is_error) return;

            // Start the upload process
            loader('show');
            targets.loaderText.html('Please, be patient as we Upload and assign your documents.<br /> It may take a few minutes. <br />File <span>1</span> of '+( fileArray.length )+'');

            uploadAndAssignFiles();
        }

        // Send ajax request
        function uploadAndAssignFiles(){
            fileLength = fileArray.length;
            var formpost = new FormData();
            // console.log(fileArray);
            // console.log(rowArray);
            // alert(fileIndex);
            formpost.append('file', fileArray[fileIndex]);
            $.each(megaOBJ, function(i, v){
                formpost.append(i, v);
            });
            // console.log(formpost);

            var categories = $(".categories:eq( "+fileIndex+" )").val();
            var signed_date = $(".signed_date:eq( "+fileIndex+")").val();
            formpost.append("signed_date", signed_date);

            if(categories != null)
            for(i=0;i<categories.length;i++)
                formpost.append("categories[]", categories[i]);


            if ($(".ej_checkbox:eq( "+fileIndex+" )").is(":checked"))
            {
                formpost.append("is_offer_letter", "yes");
            }

            if ($(".ek_checkbox:eq( "+fileIndex+" )").is(":checked"))
            {
                formpost.append("visible_to_payroll", "yes");
            }

            $.ajax({
                url: baseURI+'upload-assign-document',
                type: 'POST',
                contentType: false,
                enctype: 'multipart/form-data',
                data: formpost,
                processData: false
            })
            .done(function(resp) {
                if(resp.Status === false) { fileArray[fileIndex]['status'] = 0; return; }
                else fileArray[fileIndex]['status'] = 1;
                //
                if( currentFile < fileLength ){
                    currentFile++;
                    fileIndex++;
                    targets.loaderText.find('span').text(currentFile);
                    // Temporary solution
                    // Need to fix it
                    setTimeout(function(){
                        uploadAndAssignFiles();
                    }, 700);
                }
                else{
                    showUploadStatus();
                    fileIndex = 0;
                    currentFile=0;
                }
            })
            .always(function(xhr){
                if(xhr.status == 403) {
                    setTimeout(function(){
                        uploadAndAssignFiles();
                    }, 700);
                }
            })
        }

        // change view
        function showUploadStatus(){
            targets.mainPage.fadeOut(0);
            targets.statusPage.fadeIn(100);
            var rows = '';
            $.each(fileArray, function(i, v) {
                 rows += '<tr>';
                 rows += '  <td>'+( v.name )+'</td>';
                 rows += '  <td>'+( v.status === 0 ? '<p class="text-danger">Failed</p>' : '<p class="text-success">Uploaded</p>' )+'</td>';
                 rows += '  <td>'+( v.status === 0 ? '<p class="text-danger">Failed</p>' : '<p class="text-success">Uploaded</p>' )+'</td>';
                 rows += '</tr>';
            });
            targets.statusPage.find('tbody').html(rows);
            loader('hide');
            // console.log(fileArray);
        }

        // reset the view
        function resetView(){
            fileArray = [];
            fileLength = 0;
            currentFile = 1;
            $('.js-text').val('');
            targets.employeeSelect.select2('val', 0);
            targets.applicantInput.val('');
            targets.dropzoneBox.html(generateDropzoneRow());
            targets.statusPage.fadeOut(0);
            targets.mainPage.fadeIn(100);
            $('.js-dropzone-additional').html('');
            createSelect2(0);
            targets.count.text(0);
        }

        // Update count
        function updateCount(){
            targets.count.text(fileArray.length);
        }

        //
        function findAndDeleteFileByCode(file_code){
            if(fileArray.length === 0) return;
            fileArray.forEach(function(v, i){
                if(typeof(v.file_code) !== 'undefined' && v.file_code == file_code) fileArray.splice(i, 1);
            });
        }

        //
        targets.employeeSelect.select2();
        createSelect2(0);
        function createSelect2(id){
            $('#signed_date_'+id+'').datepicker({ 
                format: 'mm-dd-yyyy',
                changeYear: true,
                changeMonth: true,
                yearRange: "-40:+50"
            });
            $('.js-dropzone-'+id+' .categories').select2({
                closeOnSelect : false,
                allowHtml: true,
                allowClear: true,
                // tags: true
            });
            $('.js-dropzone-'+id+' .categories').on('select2:close', function (evt) {
                var uldiv = $(this).siblings('span.select2').find('ul')
                var count = $(this).select2('data').length
                if(count==0){
                    uldiv.html("")
                }
                else{
                    uldiv.html("<li>"+count+" categories selected</li>")
                }
            });
        }
    })
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
    padding-top:5px;

}

.select2-container {
    min-width: 100%;
}
.cs-custom-input input.select2-search__field{
    height: 25px;
}
.select2-container .select2-search--inline .select2-search__field{
    margin-top: 0px;
}
</style>
