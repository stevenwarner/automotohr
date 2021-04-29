<div class="row">
    <div class="col-xs-12">
        <p style="font-size: 18px;"><b>Note:</b> Employee EMS Notifications are displayed on each Employee's Employee Management System EMS Home page.</p>
        <div class="dashboard-conetnt-wrp">
            <div class="announcements-listing">
        <?php   if (!empty($ems_notification)) {
                    foreach ($ems_notification as $notification) { ?>
                        <article class="listing-article">
                            <figure>
                                <?php if (isset($notification['video_url']) && !empty($notification['video_url'])) { ?>
                                    <?php if ($notification['video_source'] == 'youtube') { ?>
                                        <img class="thumbnail_video_url" src="https://img.youtube.com/vi/<?php echo $notification['video_url']; ?>/hqdefault.jpg"/>
                                    <?php } else if ($notification['video_source'] == 'vimeo') { 
                                        $thumbnail_image = vimeo_video_data($notification['video_url']);
                                        echo '<img class="thumbnail_video_url"  src="'.$thumbnail_image.'"/>';
                                    } else if ($notification['image_code'] != '' && $notification['video_status'] == 0) {  ?>
                                        <img class="thumbnail_video_url"  src="<?php echo AWS_S3_BUCKET_URL . $notification['image_code']; ?>"/>
                                    <?php }  else { ?>
                                        <video width="214" height="auto">
                                            <source src="<?php echo base_url('assets/uploaded_videos/'.$notification['video_url']); ?>" type='video/mp4'>
                                        </video>
                                    <?php } ?>
                                <?php } else if (isset($notification['image_code']) && !empty($notification['image_code'])) { ?>
                                    <img class="thumbnail_video_url"  src="<?php echo AWS_S3_BUCKET_URL . $notification['image_code']; ?>"/>
                                <?php }  else { ?>
                                    <img class="thumbnail_video_url"  src="<?php echo base_url('assets/images/notification_default_img.png'); ?>"/>
                                <?php } ?>  
                            </figure>
                            <div class="text">
                                <h3><?php echo $notification['title']; ?></h3>
                                <div class="post-options">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-3 col-xs-12 col-sm-3">
                                            <ul>
                                                <li><?=reset_datetime(array( 'datetime' => $notification['created_date'], '_this' => $this)); ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                            <?php if (check_access_permissions_for_view($security_details, 'add_edit_employee_ems_notification')) { ?>
                                                <a class="btn btn-success btn-sm btn-block status-toggle" href="<?= base_url('edit_ems_notification/' . $notification['sid']); ?>">Edit</a>
                                            <?php } ?>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                            <?php if (check_access_permissions_for_view($security_details, 'disable_employee_ems_notification')) { ?>
                                                <a class="btn <?php echo $notification['status'] ? 'btn-warning' : 'btn-primary' ?> btn-sm btn-block status-toggle" id="<?php echo $notification['sid']; ?>" href="javascript:;"><?php echo $notification['status'] ? 'Disable' : 'Enable' ?></a>
                                            <?php } ?>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                        <?php if (check_access_permissions_for_view($security_details, 'delete_employee_ems_notification')) { ?>
                                            <form id="form_delete_ems_notification_<?php echo $notification['sid']; ?>" method="post" action="<?php echo current_url(); ?>">
                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_ems_notification" />
                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                <input type="hidden" id="notification_sid" name="notification_sid" value="<?php echo $notification['sid']; ?>" />
                                                <button type="button" class="btn btn-danger btn-sm btn-block" onclick="func_delete_ems_notification(<?php echo $notification['sid']; ?>);">Delete</button>
                                            </form>
                                        <?php } ?>
                                        </div>
                                    </div>                                                                
                                </div>
                                <div class="full-width announcement-des" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                    <?php echo strlen($notification['description']) > 100 ? substr($notification['description'], 0, 100) . " ..." : $notification['description']; ?>
                                </div>
                            </div>
                        </article>
                        <?php
                    } 
                } ?>
            </div>
        </div>
    </div>
</div>
<hr />
<?php if (check_access_permissions_for_view($security_details, 'add_edit_employee_ems_notification')) { ?>
    <div class="row" id="add_new_useful_link_form">
        <div class="col-xs-12">
            <div class="hr-box">
                <div class="hr-box-header">
                    Add New Notification
                </div>
                <div class="hr-innerpadding">
                    <form id="func_insert_new_ems_noti" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                        <input type="hidden" id="perform_action" name="perform_action" value="insert_ems_dashboard" />
                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                        <div class="universal-form-style-v2">
                            <ul>
                                <li class="form-col-100">
                                    <?php $field_id = 'title'; ?>
                                    <?php echo form_label('Title: <span class="staric">*</span>', $field_id); ?>
                                    <?php echo form_input($field_id, '', 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </li>

                                <li class="form-col-100 autoheight">
                                    <?php $field_id = 'description'; ?>
                                    <?php echo form_label('Description: <span class="staric">*</span>', $field_id); ?>
                                    <?php echo form_textarea($field_id, '', 'class="invoice-fields autoheight ckeditor" id="' . $field_id . '"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </li>

                                <li class="form-col-50-left autoheight edit_filter">
                                    <label for="video_source">Video Source</label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        <?php echo YOUTUBE_VIDEO; ?>
                                        <input checked="checked" class="video_source" type="radio" id="video_source_youtube" name="video_source" value="youtube">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        <?php echo VIMEO_VIDEO; ?>
                                        <input class="video_source" type="radio" id="video_source_vimeo" name="video_source" value="vimeo">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        <?php echo UPLOAD_VIDEO; ?>
                                        <input class="video_source" type="radio" id="video_source_upload" name="video_source" value="upload">
                                        <div class="control__indicator"></div>
                                    </label>
                                </li>

                                <li class="form-col-50-right autoheight">
                                    <label for="video_status">Video Status</label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        Enable
                                        <input class="video_status" type="radio" id="video_status_enable" name="video_status" value="1">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        Disable
                                        <input class="video_status" type="radio" id="video_status_disable" name="video_status" value="0" checked>
                                        <div class="control__indicator"></div>
                                    </label>
                                </li>

                                <li class="form-col-100" id="yt_vm_video_container">
                                    <?php $field_id = 'url'; ?>
                                    <?php echo form_label('Video Url:', $field_id); ?>
                                    <?php echo form_input($field_id, '', 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                    <?php echo form_error($field_id); ?>
                                </li>

                                <li class="form-col-100 autoheight edit_filter" id="up_video_container" style="display: none">
                                    <label>Upload Video <span class="hr-required">*</span></label>
                                    <div class="upload-file invoice-fields">
                                        <span class="selected-file" id="name_video_upload"></span>
                                        <input type="file" name="video_upload" id="video_upload" onchange="check_file('video_upload')" >
                                        <a href="javascript:;">Choose Video</a>
                                    </div>
                                </li>

                                <li class="form-col-50-left autoheight">
                                    <label for="employees_assigned_to">Assigned To Employees</label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        All
                                        <input class="employees_assigned_to" type="radio" id="employees_assigned_to_all" name="employees_assigned_to" value="all" checked="checked">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        Specific
                                        <input class="employees_assigned_to" type="radio" id="employees_assigned_to_specific" name="employees_assigned_to" value="specific">
                                        <div class="control__indicator"></div>
                                    </label>
                                </li>

                                <li class="form-col-50-right">
                                    <label>Sort Order:</label>
                                    <input type="number" name="sort_order" id="sort_order" class="invoice-fields">
                                </li>

                                <li class="form-col-100 autoheight">
                                    <?php $field_name = 'employees_assigned_sid' ?>
                                    <?php echo form_label('Assigned To Employees', $field_name); ?>
                                    <div class="">
                                        <select data-rule-required="true" class="" name="employees_assigned_sid[]" id="employees_assigned_sid" multiple="multiple" disabled="disabled">
                                            <option value="">Please Select</option>
                                            <?php if (!empty($employees)) { ?>
                                                <?php foreach ($employees as $key => $employee) { ?>
                                                    <option  value="<?php echo $key; ?>" ><?php echo $employee; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </li>

                                <li class="form-col-50-left autoheight">
                                    <label>Banner Image </label>
                                    <div class="upload-file invoice-fields">
                                        <span class="selected-file" id="name_docs">No file selected</span>
                                        <input name="docs" id="docs" onchange="check_banner_file('docs')" type="file">
                                        <a href="javascript:;">Choose File</a>
                                    </div>
                                </li>

                                <li class="form-col-50-right autoheight">
                                    <label for="video_source">Banner Status</label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        Enable
                                        <input class="image_status" type="radio" id="image_status_enable" name="image_status" value="1">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        Disable
                                        <input class="image_status" type="radio" id="image_status_disable" name="image_status" value="0" checked>
                                        <div class="control__indicator"></div>
                                    </label>
                                </li>

                                <li class="form-col-100">
                                    <button type="submit" class="btn btn-success">Add New Notification</button>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
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

<script language="JavaScript" type="text/javascript" src="<?=base_url('assets/js/chosen.jquery.js');?>"></script>
<script>
    function check_banner_file(val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function func_delete_ems_notification(ems_notification_sid) {
        alertify.confirm(
                'Are you sure?',
                'Are you sure you want to delete this notification',
                function () {
                    $('#form_delete_ems_notification_' + ems_notification_sid).submit();
                },
                function () {
                    alertify.error('Cancelled!');
                });
    }

    $(document).ready(function () {
        $('select[multiple]').chosen();
        //$('#add_new_location_form').hide();
        $("#func_insert_new_ems_noti").validate({
            ignore: [],
            rules: {
                title: {
                    required: true
                },
                description: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Title is required.'
                },
                description: {
                    required: 'Description is required.'
                }
            },
            submitHandler: function (form) {
                var video_status = $('input[name="video_status"]:checked').val();
                var video_source = $('input[name="video_source"]:checked').val();
                if (video_status == 1) {
                    var flag = 0;
                    
                    if ($('input[name="video_source"]:checked').val() == 'youtube') {
                        if ($('#url').val() != '') {
                            var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                            
                            if (!$('#url').val().match(p)) {
                                alertify.error('Not a Valid Youtube URL');
                                flag = 1;
                                return false;
                            }
                        } else {
                            alertify.error('Video URL is required');
                            flag = 1;
                            return false;
                        }
                    }
                    
                    if ($('input[name="video_source"]:checked').val() == 'vimeo') {

                        if ($('#url').val() != '') {
                            var flag = 0;
                            var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                            $.ajax({
                                type: "POST",
                                url: myurl,
                                data: {url: $('#url').val()},
                                async: false,
                                success: function (data) {
                                    if (data == false) {
                                        alertify.error('Not a Valid Vimeo URL');
                                        flag = 1;
                                        return false;
                                    }
                                },
                                error: function (data) {
                                }
                            });
                        } else {
                            alertify.error('Video URL is required');
                            flag = 1;
                            return false;
                        }
                        
                        if (flag) {
                            return false;
                        }
                    }

                    if ($('input[name="video_source"]:checked').val() == 'upload') {
                        if ($('#video_upload').val() == '') {
                            alertify.error('Please Choose Video');
                            flag = 1;
                            return false;
                        }
                    }
                }
                
                $('#my_loader').show();
                form.submit();
            }
        });

        $('.status-toggle').click(function () {
            var id = $(this).attr('id');
            var status = $(this).html();

            if (status == 'Disable') {
                $.ajax({
                    type: 'GET',
                    data: {
                        status: 0
                    },
                    url: '<?= base_url('manage_ems/enable_disable_notification') ?>/' + id,
                    success: function (data) {
                        data = JSON.parse(data);

                        if (data.message == 'updated') {
                            $('#' + id).removeClass('btn-warning');
                            $('#' + id).addClass('btn-primary');
                            $('#' + id).html('Enable');
                        }
                    },
                    error: function () {
                    }
                });
            } else if (status == 'Enable') {
                $.ajax({
                    type: 'GET',
                    data: {
                        status: 1
                    },
                    url: '<?= base_url('manage_ems/enable_disable_notification') ?>/' + id,
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.message == 'updated') {
                            $('#' + id).removeClass('btn-primary');
                            $('#' + id).addClass('btn-warning');
                            $('#' + id).html('Disable');
                        }
                    },
                    error: function () {
                    }
                });
            }
        });
    });

    $('.employees_assigned_to').on('click', function () {
        if ($(this).prop('checked') == true) {
            var value = $(this).val();
            
            if (value == 'all') {
                $('#employees_assigned_sid').prop('disabled', true).trigger("chosen:updated");
            } else {
                $('#employees_assigned_sid').prop('disabled', false).trigger("chosen:updated");
            }
        }
    });

    function check_file(val) {
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
            $('#name_' + val).html('No video selected');
            alertify.error("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');

        }
    }

    $('.video_source').on('click', function () {
        var selected = $(this).val();
        
        if (selected == 'youtube' || selected == 'vimeo') {
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if (selected == 'upload') {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').show();
        }
    });

    $('input[type=radio]:checked').trigger('click');
</script>
