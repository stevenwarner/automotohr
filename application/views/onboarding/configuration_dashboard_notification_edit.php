<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Onboarding Configuration</span>
                        </div>
                        <div class="row" id="add_new_useful_link_form">
                            <div class="col-xs-12">
                                <div class="hr-box">
                                    <div class="hr-box-header">
                                        Edit EMS Notification
                                    </div>
                                    <div class="hr-innerpadding">
                                        <form id="func_insert_new_ems_noti" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" id="perform_action" name="perform_action" value="insert_ems_dashboard" />
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <div class="universal-form-style-v2">
                                                <ul>
                                                    <li class="form-col-100">
                                                        <?php $field_id = 'title'; ?>
                                                        <?php echo form_label('Title:', $field_id); ?>
                                                        <?php echo form_input($field_id, $ems_notification[0]['title'], 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </li>
                                                    <li class="form-col-100 autoheight">
                                                        <?php $field_id = 'description'; ?>
                                                        <?php echo form_label('Description:', $field_id); ?>
                                                        <?php echo form_textarea($field_id, $ems_notification[0]['description'], 'class="invoice-fields autoheight ckeditor" id="' . $field_id . '"'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </li>
                                                    <li class="form-col-50-left autoheigh edit_filter">
                                                        <label for="video_source">Video Source</label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            <?php echo YOUTUBE_VIDEO; ?>
                                                            <input <?= $ems_notification[0]['video_source'] == 'youtube' ? 'checked="checked"' : ''?> class="video_source" type="radio" id="video_source_youtube" name="video_source" value="youtube">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            <?php echo VIMEO_VIDEO; ?>
                                                            <input <?= $ems_notification[0]['video_source'] == 'vimeo' ? 'checked="checked"' : ''?> class="video_source" type="radio" id="video_source_vimeo" name="video_source" value="vimeo">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            <?php echo UPLOAD_VIDEO; ?>
                                                            <input <?= $ems_notification[0]['video_source'] == 'upload' ? 'checked="checked"' : ''?> class="video_source" type="radio" id="video_source_upload" name="video_source" value="upload">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>
                                                    <li class="form-col-50-right autoheight">
                                                        <label for="video_source">Video Status</label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            Enable
                                                            <input <?= $ems_notification[0]['video_status'] ? 'checked="checked"' : ''?> class="video_status" type="radio" id="video_status_enable" name="video_status" value="1">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            Disable
                                                            <input <?= !$ems_notification[0]['video_status'] ? 'checked="checked"' : ''?> class="video_status" type="radio" id="video_status_disable" name="video_status" value="0">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>

                                                    <?php if(!empty($ems_notification[0]['video_url']) != '') { ?>
                                                        <li class="form-col-100 autoheight">
                                                            <div class="well well-sm">
                                                                <?php if($ems_notification[0]['video_source'] == 'youtube') { ?>
                                                                    <div class="embed-responsive embed-responsive-16by9">
                                                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $ems_notification[0]['video_url']; ?>"></iframe>
                                                                    </div>
                                                                <?php } else if($ems_notification[0]['video_source'] == 'vimeo') { ?>
                                                                    <div class="embed-responsive embed-responsive-16by9">
                                                                        <iframe src="https://player.vimeo.com/video/<?php echo $ems_notification[0]['video_url']; ?>" frameborder="0"></iframe>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <video controls>
                                                                        <source src="<?php echo base_url('assets/uploaded_videos/'.$ems_notification[0]['video_url']); ?>" type='video/mp4'>   
                                                                    </video>
                                                                <?php } ?>
                                                            </div>
                                                        </li>
                                                    <?php } ?>


                                                    <?php if($ems_notification[0]['video_source'] == 'youtube') { ?>
                                                        <?php $temp = empty($ems_notification[0]['video_url']) ? '' : 'https://www.youtube.com/watch?v=' . $ems_notification[0]['video_url']; ?>
                                                    <?php } else if($ems_notification[0]['video_source'] == 'vimeo') { ?>
                                                        <?php $temp = empty($ems_notification[0]['video_url']) ? '' : 'https://vimeo.com/' . $ems_notification[0]['video_url']; ?>
                                                    <?php } else{
                                                        $temp = '';
                                                    } ?>
                                                    <li class="form-col-100" id="yt_vm_video_container">
                                                        <?php $field_id = 'url'; ?>
                                                        <?php echo form_label('Video Url:', $field_id); ?>
                                                        <?php echo form_input($field_id, $temp, 'class="invoice-fields" id="' . $field_id . '"'); ?>
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
                                                    <?php if ($ems_notification[0]['video_source'] == 'upload') {?>
                                                        <input type="hidden" id="old_upload_video" name="old_upload_video" value="<?php echo $ems_notification[0]['video_url']; ?>">  
                                                    <?php } ?>
                                                    <input type="hidden" name="source-flag" value="<?= $ems_notification[0]['video_source']; ?>" id="source-flag"/>

                                                    <li class="form-col-50-left autoheight">
                                                        <label for="employees_assigned_to">Assigned To Employees</label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            All
                                                            <input class="employees_assigned_to" type="radio" id="employees_assigned_to_all" name="employees_assigned_to" value="all" <?= $ems_notification[0]['assigned_to'] == 'all' ? 'checked="checked"' : ''?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            Specific
                                                            <input class="employees_assigned_to" type="radio" id="employees_assigned_to_specific" name="employees_assigned_to" value="specific" <?= $ems_notification[0]['assigned_to'] == 'specific' ? 'checked="checked"' : ''?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>
                                                    <li class="form-col-50-right">
                                                        <label>Sort Order:</label>
                                                        <input type="number" value="<?= $ems_notification[0]['sort_order']?>" name="sort_order" id="sort_order" class="invoice-fields">
                                                    </li>
                                                    <li class="form-col-100 autoheight">
                                                        <?php $field_name = 'employees_assigned_sid' ?>
                                                        <?php echo form_label('Assigned To Employees', $field_name); ?>
                                                        <div class="hr-select-dropdown">
                                                            <select data-rule-required="true" class="" name="employees_assigned_sid[]" id="employees_assigned_sid" multiple="multiple" disabled="disabled">
                                                                <option value="">Please Select</option>
                                                                <?php if (!empty($employees)) { ?>
                                                                    <?php foreach ($employees as $key => $employee) { ?>
                                                                        <option  value="<?php echo $key; ?>" <?= in_array($key,$ems_notification[0]['assigned_emp']) ? 'selected="selected"' : ''?>><?php echo $employee; ?></option>
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
                                                            <input <?= $ems_notification[0]['image_status'] ? 'checked="checked"' : ''?> class="image_status" type="radio" id="image_status_enable" name="image_status" value="1">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                            Disable
                                                            <input <?= !$ems_notification[0]['image_status'] ? 'checked="checked"' : ''?> class="image_status" type="radio" id="image_status_disable" name="image_status" value="0">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>
                                                    <?php $temp = (isset($ems_notification[0]['image_code']) ? $ems_notification[0]['image_code'] : ''); ?>
                                                    <?php if(!empty($temp)) { ?>
                                                        <li class="form-col-100 autoheight">
                                                            <div class="well well-sm">
                                                                <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $temp; ?>" />
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                    <li class="form-col-100">
                                                        <button type="submit" class="submit-btn btn-success">Update Notification</button>
                                                        <input type="button" value="Cancel" class="submit-btn btn-cancel" onclick="document.location.href = '<?php echo base_url('onboarding/configuration'); ?>'">
                                                    </li>
                                                </ul>

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

<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            <?php echo VIDEO_LOADER_MESSAGE; ?>
        </div>
    </div>
</div>


<script>

    function check_banner_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
        } else {
            $('#name_' + val).html('No file selected');
        }
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
                var flag = 0;

                var video_status = $('input[name="video_status"]:checked').val();
                var video_source = $('input[name="video_source"]:checked').val();
                if(video_status==1) {
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
                        }
                        else{
                            alertify.error('Vimeo URL is required');
                            flag = 1;
                            return false;
                        }
                        if (flag) {
                            return false;
                        }
                    }
                    if($('input[name="video_source"]:checked').val() == 'upload' && $('#source-flag').val()!='upload'){
                        if($('#video_upload').val()==''){
                            alertify.error('Please Upload Video');
                            flag = 1;
                            return false;
                        }
                    }
                }
                else if(video_source!=$('#source-flag').val()){
                    if((video_source=='youtube' || video_source=='vimeo') && $('#url').val()==''){
                        alertify.error('Please provide changed source');
                        return false;
                    } else if(video_source=='upload' && $('#video_upload').val()==''){
                        alertify.error('Please provide changed source');
                        return false;
                    }
                }
                if(flag){
                    return false;
                }

                $('#my_loader').show();
                form.submit();
            }
        });

        $('.status-toggle').click(function(){
            var id = $(this).attr('id');
            var status = $(this).html();
            if(status == 'Disable'){
                $.ajax({
                    type: 'GET',
                    data:{
                        status:0
                    },
                    url: '<?= base_url('onboarding/enable_disable_notification')?>/' + id,
                    success: function(data){
                        data = JSON.parse(data);
                        if(data.message == 'updated'){
                            $('#'+id).removeClass('btn-warning');
                            $('#'+id).addClass('btn-primary');
                            $('#'+id).html('Enable');
                        }
                    },
                    error: function(){

                    }
                });
            }
            else if(status == 'Enable'){
                $.ajax({
                    type: 'GET',
                    data:{
                        status:1
                    },
                    url: '<?= base_url('onboarding/enable_disable_notification')?>/' + id,
                    success: function(data){
                        data = JSON.parse(data);
                        if(data.message == 'updated'){
                            $('#'+id).removeClass('btn-primary');
                            $('#'+id).addClass('btn-warning');
                            $('#'+id).html('Disable');
                        }
                    },
                    error: function(){

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
                } else{
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

    $('.video_source').on('click', function(){
        var selected = $(this).val();
        var video_status = $('input[name="video_status"]:checked').val();
            if(selected == 'youtube' || selected == 'vimeo'){
                $('#yt_vm_video_container').show();
                $('#up_video_container').hide();
            } else if(selected == 'upload'){
                $('#yt_vm_video_container').hide();
                $('#up_video_container').show();
            }
    });

    $('input[type=radio]:checked').trigger('click');
</script>