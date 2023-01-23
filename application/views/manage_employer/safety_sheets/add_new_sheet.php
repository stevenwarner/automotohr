<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('main/manage_ems_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <?php echo isset($sheet_data) ? 'Update Safety Sheet Sheet' : 'Add New Sheet'; ?>
                        </span>
                    </div>
                    <div class="add-new-company">
                        <div class="form-wrp">
                            <form action="" method="POST" id="add_sheet">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group auto-height">
                                            <label for="title">Title<span class="hr-required">*</span></label>
                                            <?php $sheet_name = isset($sheet_data) ? $sheet_data['title'] : ''; ?>
                                            <?php echo form_input('title', set_value('title',$sheet_name), 'class="form-control"'); ?>
                                            <?php echo form_error('title'); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group auto-height">
                                            <label>File</label>

                                            <div class="upload-file form-control">
                                                <span class="selected-file" id="name_docs">No file selected</span>
                                                <input name="docs" id="docs" onchange="check_file('docs')" type="file">
                                                <a href="javascript:;">Choose File</a>
                                            </div>
                                            <div id="file-upload-div" class="file-upload-box"></div>
                                            <div class="attached-files" id="uploaded-files" style="display: none;"></div>

                                            <div class="video-link" style="font-style: italic;"><b>Note.</b> Upload Multiple Files One After Other </div>
                                            <div class="custom_loader">
                                                <div id="loader" class="loader" style="display: none">
                                                    <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                    <span>Uploading...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if(!isset($clone_flag) && isset($sheet_data)){?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group auto-height">
                                                <label>Documents :</label>
                                                <?php if(sizeof($sheet_files)>0) {
                                                    foreach ($sheet_files as $file) { ?>
                                                        <div class="form-control full-width"
                                                             style="height: auto; float: left;margin: 5px 0 0 0;">
                                                            <div class="pull-left">
                                                                <span class="uploaded-file-name"><a href="<?= AWS_S3_BUCKET_URL.$file['file_code']?>" download="<?php echo $file['file_name']?>"><?php echo $file['file_name']?></a></span>
                                                            </div>
                                                            <div class="pull-right">
                                                                <a href="javascript:;" title="Delete File" class="btn btn-danger delete-record" data-attr="<?= $file['sid']?>"> <i class="fa fa-times"></i></a>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } else { ?>
                                                    <div class="form-control full-width"
                                                         style="height: auto; float: left">
                                                        <div class="pull-left">
                                                            <span class="uploaded-file-name">No documents founds</span>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                            </div>
                                        </div>
                                    <?php }?>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group auto-height">
                                            <label for="notes">Notes</label>
                                            <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                            <textarea class="ckeditor textarea" name="notes" rows="8" cols="60"><?php echo isset($sheet_data) ? $sheet_data['notes'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
    <!--                                    <div class="form-group" id="category">-->
                                            <label class="text-left">Category <span class="hr-required">*</span></label>
    <!--                                        <div class="form-control">-->
                                                <select class="chosen-select" multiple="multiple" name="cat_sid[]" id="cat_sid">
                                                    <option value="">Please Select Category</option>
                                                    <?php if(!empty($safety_category)) { ?>
                                                        <?php foreach ($safety_category as $active_company) { ?>
                                                            <option value="<?php echo $active_company['sid']; ?>" <?php echo (isset($sheet_data) && in_array($active_company['sid'],$sheet_data['categories'])) || (isset($cat_sid) && $cat_sid == $active_company['sid']) ? 'selected="selected"' : '' ;?>>
                                                                <?php echo $active_company['name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error('cat_sid'); ?>
    <!--                                        </div>-->
    <!--                                    </div>-->
                                    </div>
                                    <!--                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">-->
                                    <!--                                                    <div class="field-row">-->
                                    <!--                                                        <label></label>-->
                                    <!--                                                    </div>-->
                                    <!--                                                </div>-->
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <label class="text-left">Visible To</label>
                                        <select class="chosen-select" multiple="multiple" name="employees[]" id="employees">
<!--                                            <option value="">Please Select</option>-->
                                            <option value="">All</option>
                                            <?php foreach ($current_employees as $current_employee) { ?>
                                                <option <?php if (in_array($current_employee['sid'], $employeesArray)) { ?> selected <?php } ?> value="<?php echo $current_employee['sid']; ?>" ><?php echo ucwords($current_employee['first_name'] . ' ' . $current_employee['last_name']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="pre_id" id="pre_id" value="<?= !isset($clone_flag) && isset($sheet_data) ? $sheet_data['sid'] : 0;?>">

                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center">
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success" value="<?php echo !isset($clone_flag) && isset($sheet_data) ? 'Update Sheet' : 'Add Sheet'; ?>" name="form-submit">
                                            <a href="<?php echo (!isset($clone_flag) && isset($sheet_data)) || isset($cat_sid) ? base_url('safety_sheets/view_company_sheets/'.$cat_sid) : base_url('safety_sheets/manage_safety_sheets')?>" class="btn btn-default"> Cancel</a>
                                        </div>
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

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript">

    var jobs_selectize = $('#cat_sid').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        persist: true,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });
    var employee_selectize = $('#employees').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        persist: true,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });
    $(function () {
        $("#add_sheet").validate({
            ignore: ":hidden:not(select)",
            debug: false,
            rules: {
                title: {
                    required: true
                },
                cat_sid: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Title is required'
                },
                cat_sid: {
                    required: 'Category required'
                }
            },
            submitHandler: function (form) {
                var instances = $.trim(CKEDITOR.instances.notes.getData());
                if (instances.length === 0) {
                    alertify.alert('Error! Notes Missing', "Please provide some notes");
                    return false;
                }
                var items_length = $('#cat_sid :selected').length;

                if (items_length == 0) {
                    alertify.alert('Error! Category Missing', "Please Select Category");
                    return false;
                }
                form.submit();
            }
        });
    });

    $(document).on('click','.delete-record',function() {
        var id = $(this).attr('data-attr');
        alertify.confirm('Confirmation', "Are you sure you want to delete this file?",
            function () {
                $.ajax({
                    url: '<?= base_url('safety_sheets/delete_record_ajax') ?>',
                    type: 'post',
                    data: {
                        id: id,
                        action: 'delete'
                    },
                    success: function(data){
                        alertify.success('File Deleted Successfully');
                        window.location.href = window.location.href;
                    },
                    error: function(){
                    }
                });
            },
            function () {
                alertify.error('Canceled');
            });
    });

    function check_file(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            if (ext != "png" && ext != "jpg" && ext != "jpeg" && ext != "jpe" && ext != "pdf" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG" && ext != "PDF") {
                $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                return false;
            }
            $('.upload-file').hide();
            $('#uploaded-files').hide();
            $('#file-upload-div').append('<div class="form-control btn-upload"><div class="pull-left"> <span class="selected-file" id="name_docs">'+fileName+'</span> </div> <div class="pull-right"> <input class="btn btn-success" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="btn btn-success" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div>');
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function CancelUpload() {
        $('.upload-file').show();

        if($('#uploaded-files').html() != '') {
            $('#uploaded-files').show();
        }

        $('#file-upload-div').html("");
        $('#name_docs').html("No file selected");
    }

    function DoUpload() {

        var file_data = $('#docs').prop('files')[0];
        var form_data = new FormData();
        form_data.append('docs', file_data);

        if($('#pre_id').val()!=0) {
            form_data.append('pre_id', $('#pre_id').val());
        }

        $('#loader').show();
        $('#upload').addClass('disabled-btn');
        $('#upload').prop('disabled', true);
        $.ajax({
            url: '<?= base_url('safety_sheets/ajax_handler') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(data) {
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);
                alertify.success('New document has been uploaded');
                $('.upload-file').show();
                $('#uploaded-files').show();
                $('#uploaded-files').append('<div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"> <div id="uploaded-files-name"><b>Name:</b> '+file_data['name']+'</div> </div> <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right"> <span><b>Status:</b> Uploaded</span> </div> </div>');
//                $('#uploaded-files').append(file_data['name'] + '<br>');
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");
                if(data!="error"){
                    $('#pre_id').val(data);
                } else {
                    alert('Doc error');
                }
            },
            error: function() {
            }
        });
    }

</script>
