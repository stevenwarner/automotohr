<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <a id="back_btn" href="<?php echo base_url('manage_admin/safety_data_sheet')?>" class="btn btn-success"><i class="fa fa-arrow-left"> </i> Go Back</a>
                                        </div>
                                    </div>
                                    <div class="add-new-company">
                                        <form action="" method="POST" id="add_sheet">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <?php if($this->uri->segment(3) == 'add_safety_sheet') { ?>
                                                            <h1 class="page-title">Add New Sheet</h1>
                                                        <?php } elseif($this->uri->segment(3) == 'edit_safety_sheet') { ?>
                                                            <h1 class="page-title">Edit Safety Sheet</h1>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label for="title">Title<span class="hr-required">*</span></label>
                                                        <?php $cat_name = isset($sheet_data) ? $sheet_data['title'] : ''; ?>
                                                        <?php echo form_input('title', set_value('title',$cat_name), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('title'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="docs">File</label>
                                                        <div class="upload-file form-control">
                                                            <span class="selected-file" id="name_docs">No file selected</span>
                                                            <input name="docs" id="docs" onchange="check_file('docs')" type="file">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                        <div id="file-upload-div" class="file-upload-box"></div>

                                                        <div class="video-link" style="font-style: italic;"><b>Note.</b> Upload Multiple Files One After Another </div>
                                                        <div class="custom_loader">
                                                            <div id="loader" class="loader" style="display: none">
                                                                <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                                <span>Uploading...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <label>Documents :</label>
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>File Name</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>    
                                                        </thead>
                                                        <tbody id="append_file_upload">
                                                            <?php if(isset($sheet_data)){?>
                                                                <?php if(sizeof($sheet_files) > 0) {
                                                                    foreach ($sheet_files as $file) { ?>
                                                                        <tr>
                                                                            <td>
                                                                                <span class="uploaded-file-name"><a href="<?= AWS_S3_BUCKET_URL.$file['file_code']?>" download="<?php echo $file['file_name']?>"><?php echo $file['file_name']?></a></span>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a href="javascript:;" title="Delete File" class="btn btn-danger delete-record" data-attr="<?= $file['sid']?>"> <i class="fa fa-times"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php }
                                                                } else { ?>
                                                                    <tr>
                                                                        <td colspan="2" class="text-center">No Document Found</td>
                                                                    </tr>
                                                                <?php } ?>     
                                                            <?php } ?>
                                                            <?php if($this->uri->segment(3) == 'add_safety_sheet') { ?>
                                                                <tr id="default_record">
                                                                    <td colspan="2" class="text-center">No Document Found</td>
                                                                </tr>
                                                            <?php } ?>    
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row field-row-autoheight">
                                                        <label for="notes">Notes</label>
                                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                        <textarea class="ckeditor textarea" name="notes" rows="8" cols="60"><?php echo isset($sheet_data) ? $sheet_data['notes'] : ''; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row" id="category">
                                                        <label class="text-left">Category : <span class="hr-required">*</span></label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="chosen-select" multiple="multiple" name="cat_sid[]" id="cat_sid">
                                                                <option value="">Please Select Category</option>
                                                                <?php if(!empty($safety_category)) { ?>
                                                                    <?php foreach ($safety_category as $active_company) { ?>
                                                                        <option value="<?php echo $active_company['sid']; ?>" <?php echo isset($sheet_data) && in_array($active_company['sid'],$sheet_data['categories']) ? 'selected="selected"' : '' ;?>>
                                                                            <?php echo $active_company['name']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                            <?php echo form_error('cat_sid'); ?>
                                                        </div>
                                                    </div>
                                                </div>
<!--                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">-->
<!--                                                    <div class="field-row">-->
<!--                                                        <label></label>-->
<!--                                                    </div>-->
<!--                                                </div>-->
                                                <input type="hidden" name="pre_id" id="pre_id" value="<?= isset($sheet_data) ? $sheet_data['sid'] : 0;?>">

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center">
                                                    <!-- <div class="field-row"> -->
                                                        <input type="submit" class="search-btn" value="<?php echo isset($sheet_data) ? 'Update Sheet' : 'Add Sheet'; ?>" name="form-submit">
                                                        <a href="<?php echo base_url('manage_admin/safety_data_sheet') ?>" class="btn black-btn" type="submit">Cancel</a>

                                                    <!-- </div> -->
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
        alertify.confirm('Confirmation', "Are you sure you want to disable this file?",
            function () {
                $.ajax({
                    url: '<?= base_url('manage_admin/safety_data_sheet/delete_record_ajax') ?>',
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
                $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png .pdf) allowed!</p>');
                return false;
            }
            $('.upload-file').hide();
            $('#file-upload-div').append('<div class="form-control btn-upload"><div class="pull-left"> <span class="selected-file" id="name_docs">'+fileName+'</span> </div> <div class="pull-right"> <input class="btn btn-success" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="btn btn-success" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div>');
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function CancelUpload() {
        $('.upload-file').show();

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
            url: '<?= base_url('manage_admin/safety_data_sheet/ajax_handler') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(data) {
                var obj = jQuery.parseJSON(data);
                var sheet_sid = obj.sheet_sid;
                var file_sid = obj.file_sid;
                var file_code = obj.file_code;
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);
                alertify.success('New document has been uploaded');
                $('.upload-file').show();
                $('#default_record').hide();
                $('#append_file_upload').append('<tr><td><span class="uploaded-file-name"><a href="https://automotohrattachments.s3.amazonaws.com/'+file_code+'" download="'+file_data['name']+'">'+file_data['name']+'</a></span></td><td class="text-center"><a href="javascript:;" title="Delete File" class="btn btn-danger delete-record" data-attr="'+file_sid+'"><i class="fa fa-times"></i></a></td></tr>');
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");
                if(sheet_sid!="error"){
                    $('#pre_id').val(sheet_sid);
                } else {
                    alert('Doc error');
                }
            },
            error: function() {
            }
        });
    }

</script>

